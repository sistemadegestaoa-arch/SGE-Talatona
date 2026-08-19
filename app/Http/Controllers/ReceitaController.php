<?php

namespace App\Http\Controllers;

use App\Receita;
use App\ReceitaItem;
use App\Produto;
use App\Estoque;
use App\Atendimento;
use App\AtendimentoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class ReceitaController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // LISTA DE RECEITAS PENDENTES
    // ─────────────────────────────────────────────────────────────────────────
    public function index()
    {
        $pendentes = DB::table('receita')
            ->join('consulta',  'consulta.id',  '=', 'receita.consulta_id')
            ->join('episodio',  'episodio.id',  '=', 'consulta.episodio_id')
            ->join('paciente',  'paciente.id',  '=', 'episodio.paciente_id')
            ->join('users',     'users.id',     '=', 'receita.medico_id')
            ->select(
                'receita.id as receita_id',
                'receita.estado',
                'receita.observacao',
                'receita.data',
                'receita.created_at as hora',
                'paciente.id as paciente_id',
                'paciente.nome',
                'paciente.sexo',
                'paciente.data_nascimento',
                'paciente.numero_processo',
                'users.name as medico',
                'episodio.id as episodio_id'
            )
            ->where('receita.estado', 'pendente')
            ->orderBy('receita.id', 'asc')
            ->get();

        // Carrega os itens de cada receita
        foreach ($pendentes as $r) {
            $r->itens = DB::table('receita_item')
                ->join('produto', 'produto.id', '=', 'receita_item.produto_id')
                ->select('receita_item.*', 'produto.produto', 'produto.apresentacao')
                ->where('receita_item.receita_id', $r->receita_id)
                ->get();

            // Verifica stock disponível para cada item no departamento da farmácia
            foreach ($r->itens as $item) {
                $stock = DB::table('estoque')
                    ->where('produto_id', $item->produto_id)
                    ->where('departamento_id', auth()->user()->departamento_id)
                    ->sum(DB::raw('entrada - saida'));
                $item->stock_disponivel = max(0, $stock);
                $item->stock_suficiente = $item->stock_disponivel >= $item->quantidade;
            }
        }

        // Receitas dispensadas hoje
        $dispensadasHoje = DB::table('receita')
            ->join('consulta',  'consulta.id',  '=', 'receita.consulta_id')
            ->join('episodio',  'episodio.id',  '=', 'consulta.episodio_id')
            ->join('paciente',  'paciente.id',  '=', 'episodio.paciente_id')
            ->join('users',     'users.id',     '=', 'receita.medico_id')
            ->select(
                'receita.id as receita_id',
                'paciente.nome',
                'users.name as medico',
                'receita.updated_at as hora_dispensa'
            )
            ->where('receita.estado', 'dispensada')
            ->whereDate('receita.updated_at', today())
            ->orderByDesc('receita.updated_at')
            ->get();

        $totalPendentes    = $pendentes->count();
        $totalDispensados  = $dispensadasHoje->count();
        $totalGeral        = DB::table('receita')->where('estado', 'dispensada')->count();

        return view('sistema.receitas.index', compact(
            'pendentes', 'dispensadasHoje',
            'totalPendentes', 'totalDispensados', 'totalGeral'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // VER RECEITA — formulário de dispensa
    // ─────────────────────────────────────────────────────────────────────────
    public function show($receita_id)
    {
        $receita = DB::table('receita')
            ->join('consulta',  'consulta.id',  '=', 'receita.consulta_id')
            ->join('episodio',  'episodio.id',  '=', 'consulta.episodio_id')
            ->join('paciente',  'paciente.id',  '=', 'episodio.paciente_id')
            ->join('users',     'users.id',     '=', 'receita.medico_id')
            ->select(
                'receita.id as receita_id',
                'receita.estado',
                'receita.observacao',
                'receita.data',
                'receita.created_at as hora',
                'paciente.id as paciente_id',
                'paciente.nome',
                'paciente.sexo',
                'paciente.data_nascimento',
                'paciente.numero_processo',
                'paciente.telefone',
                'users.name as medico',
                'episodio.id as episodio_id',
                'consulta.diagnostico'
            )
            ->where('receita.id', $receita_id)
            ->first();

        if (!$receita) return redirect()->route('receitas.index');

        $itens = DB::table('receita_item')
            ->join('produto', 'produto.id', '=', 'receita_item.produto_id')
            ->join('lote', function($j) {
                $j->on('lote.produto_id', '=', 'receita_item.produto_id')
                  ->on('lote.departamento_id', '=', DB::raw(auth()->user()->departamento_id));
            })
            ->select(
                'receita_item.*',
                'produto.produto',
                'produto.apresentacao',
                'lote.id as lote_id',
                'lote.lote as lote_num',
                'lote.validade'
            )
            ->where('receita_item.receita_id', $receita_id)
            ->get();

        // Calcula stock por lote
        foreach ($itens as $item) {
            $item->stock = DB::table('estoque')
                ->where('lote_id', $item->lote_id)
                ->where('departamento_id', auth()->user()->departamento_id)
                ->sum(DB::raw('entrada - saida'));
            $item->stock = max(0, $item->stock);
            $item->stock_suficiente = $item->stock >= $item->quantidade;
        }

        // Atendimento já criado para esta receita?
        $atendimento = Atendimento::where('receita_id', $receita_id)->first();

        return view('sistema.receitas.show', compact('receita', 'itens', 'atendimento'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // DISPENSAR RECEITA — cria atendimento + baixa stock + marca dispensada
    // ─────────────────────────────────────────────────────────────────────────
    public function dispensar(Request $request, $receita_id)
    {
        $receita = Receita::findOrFail($receita_id);

        if ($receita->estado === 'dispensada') {
            return redirect()->route('receitas.index')
                ->with('error', 'Esta receita já foi dispensada.');
        }

        // ── Validação: fármacos bloqueados ────────────────────────────────
        $itens = ReceitaItem::where('receita_id', $receita_id)->get();
        $idsBloqueados = $itens->pluck('produto_id')->toArray();
        $bloqueados = \App\Helpers\FarmacoHelper::verificarBloqueados($idsBloqueados);
        if (!empty($bloqueados)) {
            return redirect()->back()
                ->with('error', \App\Helpers\FarmacoHelper::msgBloqueados($bloqueados));
        }
        foreach ($itens as $item) {
            $lote_id = $request->input('lote_id_' . $item->produto_id);
            if (!$lote_id) {
                return redirect()->back()->with('error', "Seleccione o lote para: " . DB::table('produto')->where('id',$item->produto_id)->value('produto'));
            }
            $stock = DB::table('estoque')
                ->where('lote_id', $lote_id)
                ->where('departamento_id', auth()->user()->departamento_id)
                ->sum(DB::raw('entrada - saida'));
            if ($stock < $item->quantidade) {
                $nome = DB::table('produto')->where('id', $item->produto_id)->value('produto');
                return redirect()->back()->with('error', "Stock insuficiente para \"$nome\". Disponível: $stock, necessário: {$item->quantidade}.");
            }
        }

        // Busca dados do paciente via receita
        $dadosPac = DB::table('receita')
            ->join('consulta', 'consulta.id', '=', 'receita.consulta_id')
            ->join('episodio', 'episodio.id', '=', 'consulta.episodio_id')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->select('paciente.nome', 'paciente.numero_processo')
            ->where('receita.id', $receita_id)
            ->first();

        DB::transaction(function () use ($request, $receita, $itens, $dadosPac, $receita_id) {

            // Cria atendimento ligado à receita
            $atend = Atendimento::create([
                'receita_id'      => $receita_id,
                'utente'          => $dadosPac->nome,
                'processo'        => $dadosPac->numero_processo,
                'departamento_id' => auth()->user()->departamento_id,
                'users_id'        => auth()->id(),
                'observacao'      => $request->observacao,
                'data'            => date('Y-m-d'),
            ]);

            foreach ($itens as $item) {
                $lote_id = $request->input('lote_id_' . $item->produto_id);
                $qty     = $item->quantidade;

                // Regista item do atendimento
                AtendimentoItem::create([
                    'atendimento_id' => $atend->id,
                    'produto_id'     => $item->produto_id,
                    'lote_id'        => $lote_id,
                    'quantidade'     => $qty,
                ]);

                // Baixa stock do produto
                $produto   = Produto::find($item->produto_id);
                $qinicial  = $produto->quantidade;
                $qfinal    = max(0, $qinicial - $qty);
                $produto->quantidade = $qfinal;
                $produto->save();

                Estoque::create([
                    'produto_id'     => $item->produto_id,
                    'situacao'       => 'Saida',
                    'lote_id'        => $lote_id,
                    'tipo_id'        => 1,
                    'departamento_id'=> auth()->user()->departamento_id,
                    'users_id'       => auth()->id(),
                    'entrada'        => 0,
                    'saida'          => $qty,
                    'qinicial'       => $qinicial,
                    'qfinal'         => $qfinal,
                    'data'           => date('Y-m-d'),
                    'fornecedor_id'  => 'Atendimento',
                    'departamento'   => auth()->user()->departamento_id,
                    'obs'            => 'Dispensa via receita médica #' . $receita_id,
                ]);
            }

            // Marca receita como dispensada
            $receita->estado = 'dispensada';
            $receita->save();
        });

        return redirect()->route('receitas.index')
            ->with('success', 'Receita dispensada com sucesso.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PDF DA RECEITA
    // ─────────────────────────────────────────────────────────────────────────
    public function pdf($receita_id)
    {
        $receita = DB::table('receita')
            ->join('consulta', 'consulta.id', '=', 'receita.consulta_id')
            ->join('episodio', 'episodio.id', '=', 'consulta.episodio_id')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->join('users',    'users.id',    '=', 'receita.medico_id')
            ->select(
                'receita.*',
                'paciente.nome', 'paciente.sexo', 'paciente.data_nascimento',
                'paciente.numero_processo',
                'users.name as medico',
                'consulta.diagnostico'
            )
            ->where('receita.id', $receita_id)
            ->first();

        $itens = DB::table('receita_item')
            ->join('produto', 'produto.id', '=', 'receita_item.produto_id')
            ->select('receita_item.*', 'produto.produto', 'produto.apresentacao')
            ->where('receita_item.receita_id', $receita_id)
            ->get();

        $pdf = PDF::loadView('sistema.receitas.pdf', compact('receita', 'itens'));
        return $pdf->stream('Receita-' . $receita->nome . '.pdf');
    }
}
