<?php

namespace App\Http\Controllers;

use App\RequisicaoFarmaco;
use App\RequisicaoFarmacoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class RequisicaoFarmacoController extends Controller
{
    // ── LABORATÓRIO: lista das suas requisições ───────────────────────────────
    public function index()
    {
        $user = Auth::user();

        $requisicoes = DB::table('requisicao_farmaco')
            ->join('departamento', 'departamento.id', '=', 'requisicao_farmaco.departamento_id')
            ->join('users as sol', 'sol.id', '=', 'requisicao_farmaco.solicitante_id')
            ->leftJoin('users as ate', 'ate.id', '=', 'requisicao_farmaco.atendido_por')
            ->select(
                'requisicao_farmaco.id',
                'requisicao_farmaco.estado',
                'requisicao_farmaco.observacao',
                'requisicao_farmaco.created_at',
                'requisicao_farmaco.atendido_em',
                'departamento.departamento as dep_nome',
                'sol.name as solicitante',
                'ate.name as atendente'
            )
            ->where('requisicao_farmaco.departamento_id', $user->departamento_id)
            ->orderByDesc('requisicao_farmaco.id')
            ->get();

        // Todos os fármacos com flag de bloqueio — para o JS filtrar e avisar
        $farmacos = DB::table('produto')
            ->orderBy('produto')
            ->select('id', 'produto', 'apresentacao', 'bloqueado')
            ->get();

        return view('sistema.laboratorio.requisicao_farmaco', compact('requisicoes', 'farmacos'));
    }

    // ── LABORATÓRIO: criar requisição ─────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'itens'              => 'required|array|min:1',
            'itens.*.produto_id' => 'required|integer|exists:produto,id',
            'itens.*.quantidade' => 'required|integer|min:1',
        ], [
            'itens.required' => 'Adicione pelo menos um fármaco.',
        ]);

        // Verificar bloqueados
        $ids        = array_column($request->itens, 'produto_id');
        $bloqueados = \App\Helpers\FarmacoHelper::verificarBloqueados($ids);

        if (!empty($bloqueados)) {
            return response()->json([
                'success'    => false,
                'bloqueados' => $bloqueados,
                'erro'       => \App\Helpers\FarmacoHelper::msgBloqueados($bloqueados),
            ], 422);
        }

        $user = Auth::user();

        $req = RequisicaoFarmaco::create([
            'departamento_id' => $user->departamento_id,
            'solicitante_id'  => $user->id,
            'estado'          => 'pendente',
            'observacao'      => $request->observacao,
        ]);

        foreach ($request->itens as $item) {
            RequisicaoFarmacoItem::create([
                'requisicao_farmaco_id' => $req->id,
                'produto_id'            => $item['produto_id'],
                'quantidade'            => $item['quantidade'],
                'observacao_item'       => $item['observacao_item'] ?? null,
            ]);
        }

        return response()->json(['success' => true, 'id' => $req->id]);
    }

    // ── LABORATÓRIO: editar (só pendentes) ────────────────────────────────────
    public function edit($id)
    {
        $req = RequisicaoFarmaco::with('itens.produto')->findOrFail($id);

        if ($req->departamento_id !== Auth::user()->departamento_id || $req->estado !== 'pendente') {
            abort(403);
        }

        $farmacos = DB::table('produto')
            ->orderBy('produto')
            ->select('id', 'produto', 'apresentacao', 'bloqueado')
            ->get();

        return response()->json([
            'requisicao' => $req,
            'itens'      => $req->itens,
            'farmacos'   => $farmacos,
        ]);
    }

    // ── LABORATÓRIO: actualizar (só pendentes) ───────────────────────────────
    public function update(Request $request, $id)
    {
        $request->validate([
            'itens'              => 'required|array|min:1',
            'itens.*.produto_id' => 'required|integer|exists:produto,id',
            'itens.*.quantidade' => 'required|integer|min:1',
        ]);

        $req = RequisicaoFarmaco::findOrFail($id);

        if ($req->departamento_id !== Auth::user()->departamento_id || $req->estado !== 'pendente') {
            abort(403);
        }

        // Verificar bloqueados
        $ids        = array_column($request->itens, 'produto_id');
        $bloqueados = \App\Helpers\FarmacoHelper::verificarBloqueados($ids);

        if (!empty($bloqueados)) {
            return response()->json([
                'success'    => false,
                'bloqueados' => $bloqueados,
                'erro'       => \App\Helpers\FarmacoHelper::msgBloqueados($bloqueados),
            ], 422);
        }

        $req->update(['observacao' => $request->observacao]);

        RequisicaoFarmacoItem::where('requisicao_farmaco_id', $id)->delete();

        foreach ($request->itens as $item) {
            RequisicaoFarmacoItem::create([
                'requisicao_farmaco_id' => $id,
                'produto_id'            => $item['produto_id'],
                'quantidade'            => $item['quantidade'],
                'observacao_item'       => $item['observacao_item'] ?? null,
            ]);
        }

        return response()->json(['success' => true]);
    }

    // ── FARMÁCIA: lista de requisições ────────────────────────────────────────
    public function farmaciaIndex()
    {
        $requisicoes = DB::table('requisicao_farmaco')
            ->join('departamento', 'departamento.id', '=', 'requisicao_farmaco.departamento_id')
            ->join('users as sol', 'sol.id', '=', 'requisicao_farmaco.solicitante_id')
            ->leftJoin('users as ate', 'ate.id', '=', 'requisicao_farmaco.atendido_por')
            ->select(
                'requisicao_farmaco.id',
                'requisicao_farmaco.estado',
                'requisicao_farmaco.observacao',
                'requisicao_farmaco.created_at',
                'requisicao_farmaco.atendido_em',
                'departamento.departamento as dep_nome',
                'sol.name as solicitante',
                'ate.name as atendente'
            )
            ->orderByRaw("FIELD(requisicao_farmaco.estado,'pendente','atendida','rejeitada')")
            ->orderByDesc('requisicao_farmaco.id')
            ->get();

        return view('sistema.laboratorio.requisicao_farmaco_farmacia', compact('requisicoes'));
    }

    // ── FARMÁCIA: atender/rejeitar ────────────────────────────────────────────
    public function atender(Request $request, $id)
    {
        $request->validate(['acao' => 'required|in:atendida,rejeitada']);

        RequisicaoFarmaco::findOrFail($id)->update([
            'estado'       => $request->acao,
            'atendido_por' => Auth::id(),
            'atendido_em'  => now(),
        ]);

        return redirect()->route('requisicao-farmaco.farmacia')
            ->with('success', 'Requisição ' . ($request->acao === 'atendida' ? 'atendida' : 'rejeitada') . ' com sucesso.');
    }

    // ── PDF: gerar e guardar ficheiro ─────────────────────────────────────────
    public function pdf($id)
    {
        $req = RequisicaoFarmaco::with(['itens.produto', 'solicitante', 'atendente', 'departamento'])
            ->findOrFail($id);

        $itens = DB::table('requisicao_farmaco_item')
            ->join('produto', 'produto.id', '=', 'requisicao_farmaco_item.produto_id')
            ->where('requisicao_farmaco_id', $id)
            ->select(
                'produto.produto',
                'produto.apresentacao',
                'requisicao_farmaco_item.quantidade',
                'requisicao_farmaco_item.observacao_item'
            )
            ->get();

        $pdf = PDF::loadView('sistema.pdf.requisicao_farmaco_pdf', compact('req', 'itens'))
            ->setPaper('a4', 'portrait');

        $nomeArquivo = 'requisicao_farmaco_' . str_pad($id, 5, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($nomeArquivo);
    }
}
