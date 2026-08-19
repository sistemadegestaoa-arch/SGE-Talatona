<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Atendimento;
use App\AtendimentoItem;
use App\Produto;
use App\Estoque;
use App\lote;
use PDF;

class AtendimentoController extends Controller
{
    /** Formulário de novo atendimento */
    public function create(Request $request)
    {
        $requisicao_id = $request->get('requisicao_id');
        $requisicao    = null;

        if ($requisicao_id) {
            $requisicao = DB::table('requisicao')
                ->join('departamento','departamento.id','=','requisicao.departamento_id')
                ->join('users','users.id','=','requisicao.users_id')
                ->select('requisicao.*','departamento.departamento','users.name')
                ->where('requisicao.id', $requisicao_id)
                ->first();
        }

        // Produtos com lotes disponíveis (catálogo global — filtra stock por departamento)
        $produtos = DB::table('produto')
            ->join('lote','produto.id','=','lote.produto_id')
            ->select('produto.id as produto_id','produto.produto','produto.apresentacao','lote.id as lote_id','lote.lote','lote.validade')
            ->where('lote.departamento_id', auth()->user()->departamento_id)
            ->orderBy('produto.produto')
            ->get();

        // Calcula stock por lote no departamento da farmácia
        $stocks = DB::table('estoque')
            ->select('lote_id', DB::raw('SUM(entrada)-SUM(saida) as stock'))
            ->where('departamento_id', auth()->user()->departamento_id)
            ->groupBy('lote_id')
            ->get()->keyBy('lote_id');

        foreach ($produtos as $p) {
            $p->stock = $stocks->get($p->lote_id)->stock ?? 0;
        }

        // Agrupa por produto
        $produtosAgrupados = $produtos->groupBy('produto_id');

        // Prepara array simples para o JS
        $produtosJs = [];
        foreach ($produtos as $p) {
            if (($stocks->get($p->lote_id)->stock ?? 0) > 0) {
                $produtosJs[] = [
                    'produto_id'   => $p->produto_id,
                    'nome'         => $p->produto,
                    'apresentacao' => $p->apresentacao ?? '',
                    'lote_id'      => $p->lote_id,
                    'lote'         => $p->lote,
                    'stock'        => $stocks->get($p->lote_id)->stock ?? 0,
                    'validade'     => $p->validade,
                ];
            }
        }

        return view('sistema.atendimento', compact('requisicao', 'requisicao_id', 'produtosAgrupados', 'produtosJs'));
    }

    /** Guarda o atendimento e baixa o stock */
    public function store(Request $request)
    {
        $request->validate([
            'utente'       => 'required|string|max:255',
            'produto_id'   => 'required|array|min:1',
            'produto_id.*' => 'required|integer',
            'lote_id'      => 'required|array',
            'lote_id.*'    => 'required|integer',
            'quantidade'   => 'required|array',
            'quantidade.*' => 'required|integer|min:1',
        ], [
            'utente.required'    => 'O nome do utente é obrigatório.',
            'produto_id.min'     => 'Adicione pelo menos um medicamento.',
            'quantidade.*.min'   => 'A quantidade deve ser maior que zero.',
        ]);

        // Verifica stock antes de guardar
        foreach ($request->produto_id as $i => $pid) {
            $lote_id = $request->lote_id[$i];
            $qty     = $request->quantidade[$i];
            $movs    = DB::table('estoque')->where('lote_id', $lote_id)->get();
            $stock   = $movs->sum('entrada') - $movs->sum('saida');
            if ($qty > $stock) {
                $prod = DB::table('produto')->where('id',$pid)->value('produto');
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Stock insuficiente para \"$prod\". Disponível: $stock.");
            }
        }

        DB::transaction(function() use ($request) {
            // Cria o atendimento
            $atend = Atendimento::create([
                'requisicao_id'  => $request->requisicao_id ?: null,
                'utente'         => $request->utente,
                'processo'       => $request->processo,
                'departamento_id'=> auth()->user()->departamento_id,
                'users_id'       => auth()->id(),
                'observacao'     => $request->observacao,
                'data'           => date('Y-m-d'),
            ]);

            foreach ($request->produto_id as $i => $pid) {
                $lote_id = $request->lote_id[$i];
                $qty     = $request->quantidade[$i];

                // Regista item
                AtendimentoItem::create([
                    'atendimento_id' => $atend->id,
                    'produto_id'     => $pid,
                    'lote_id'        => $lote_id,
                    'quantidade'     => $qty,
                ]);

                // Baixa stock
                $produto = Produto::find($pid);
                $qinicial = $produto->quantidade;
                $qfinal   = $qinicial - $qty;
                $produto->quantidade = $qfinal;
                $produto->save();

                Estoque::create([
                    'produto_id'     => $pid,
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
                ]);
            }

            // Marca requisição como atendida
            if ($request->requisicao_id) {
                DB::table('requisicao')->where('id', $request->requisicao_id)
                    ->update(['statos' => 'atendido']);
            }
        });

        return redirect()->route('atendimento.index')->with('success', 'Atendimento registado com sucesso.');
    }

    /** Lista de atendimentos */
    public function index()
    {
        $atendimentos = DB::table('atendimento')
            ->join('users','users.id','=','atendimento.users_id')
            ->join('departamento','departamento.id','=','atendimento.departamento_id')
            ->select('atendimento.*','users.name as tecnico','departamento.departamento')
            ->where('atendimento.departamento_id', auth()->user()->departamento_id)
            ->orderBy('atendimento.id','desc')
            ->paginate(15);

        // Conta atendimentos de hoje directamente na base de dados (evita contagem errada na paginação)
        $hoje = DB::table('atendimento')
            ->where('departamento_id', auth()->user()->departamento_id)
            ->whereDate('data', today())
            ->count();

        return view('sistema.atendimentos', compact('atendimentos', 'hoje'));
    }

    /** Detalhe de um atendimento */
    public function show($id)
    {
        $atend = DB::table('atendimento')
            ->join('users','users.id','=','atendimento.users_id')
            ->join('departamento','departamento.id','=','atendimento.departamento_id')
            ->select('atendimento.*','users.name as tecnico','departamento.departamento')
            ->where('atendimento.id', $id)
            ->first();

        if (!$atend) return redirect()->route('atendimento.index');

        $itens = DB::table('atendimento_item')
            ->join('produto','produto.id','=','atendimento_item.produto_id')
            ->join('lote','lote.id','=','atendimento_item.lote_id')
            ->select('atendimento_item.*','produto.produto','produto.apresentacao','lote.lote as lote_num')
            ->where('atendimento_item.atendimento_id', $id)
            ->get();

        return view('sistema.atendimento_detalhe', compact('atend','itens'));
    }

    /** PDF do atendimento individual */
    public function pdf($id)
    {
        $atend = DB::table('atendimento')
            ->join('users','users.id','=','atendimento.users_id')
            ->join('departamento','departamento.id','=','atendimento.departamento_id')
            ->select('atendimento.*','users.name as tecnico','departamento.departamento')
            ->where('atendimento.id', $id)
            ->first();

        $itens = DB::table('atendimento_item')
            ->join('produto','produto.id','=','atendimento_item.produto_id')
            ->join('lote','lote.id','=','atendimento_item.lote_id')
            ->select('atendimento_item.*','produto.produto','produto.apresentacao','lote.lote as lote_num')
            ->where('atendimento_item.atendimento_id', $id)
            ->get();

        $pdf = PDF::loadView('sistema.atendimento_pdf', compact('atend','itens'));
        return $pdf->stream('Atendimento-'.$atend->utente.'.pdf');
    }

    /** Relatório de atendimentos por período */
    public function relatorio(Request $request)
    {
        $data1 = $request->get('data1', date('Y-m-01'));
        $data2 = $request->get('data2', date('Y-m-d'));

        $atendimentos = DB::table('atendimento')
            ->join('users','users.id','=','atendimento.users_id')
            ->join('departamento','departamento.id','=','atendimento.departamento_id')
            ->select('atendimento.*','users.name as tecnico','departamento.departamento')
            ->where('atendimento.departamento_id', auth()->user()->departamento_id)
            ->whereBetween('atendimento.data', [$data1, $data2])
            ->orderBy('atendimento.data')
            ->get();

        // Busca itens de todos os atendimentos
        $ids = $atendimentos->pluck('id');
        $itensAll = DB::table('atendimento_item')
            ->join('produto','produto.id','=','atendimento_item.produto_id')
            ->join('lote','lote.id','=','atendimento_item.lote_id')
            ->select('atendimento_item.*','produto.produto','produto.apresentacao','lote.lote as lote_num')
            ->whereIn('atendimento_item.atendimento_id', $ids)
            ->get()->groupBy('atendimento_id');

        $depNome = DB::table('departamento')
            ->where('id', auth()->user()->departamento_id)
            ->value('departamento');

        $pdf = PDF::loadView('sistema.atendimento_relatorio_pdf', compact(
            'atendimentos','itensAll','data1','data2','depNome'
        ));
        return $pdf->stream('Relatorio-Atendimentos.pdf');
    }
}
