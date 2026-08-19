<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\categoria_geral;
use App\Departamento;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Estoque;
use App\Fornecedor;
use App\Produto;
use App\lote;
use PDF;

class RealtoeioController extends Controller
{
    public function fichaestok(Request $request)
    {
        $Data1   = $request['datainicial'];
        $Data2   = $request['datafinal'];
        $id      = $request['produto_id'];
        $produto = Produto::find($id);

        $pdf = PDF::loadView('sistema.fichapordata', compact('Data1', 'Data2', 'produto'));
        return $pdf->stream('ficha de stock.pdf');
    }

    public function fichalote(Request $request)
    {
        $Data1 = $request['datainicial'];
        $Data2 = $request['datafinal'];
        $id    = $request['lote_id'];

        $lote          = lote::find($id);
        $lote_produto  = $lote->produto_id;
        $produto       = Produto::find($lote_produto);

        $pdf = PDF::loadView('sistema.fichalote', compact('Data1', 'Data2', 'produto', 'id'));
        return $pdf->stream('ficha de stock.pdf');
    }

    /**
     * Relatório por tipo de aquisição (Compras / Doação).
     * Filtra por departamento via estoque.departamento_id (produto já não tem departamento_id).
     */
    public function relatoriotipo(Request $request)
    {
        $data1        = $request['datainicial'];
        $Tipo         = $request['tipo'];
        $data2        = $request['datafinal'];
        $departamento = $request['departamento'];

        $depId   = DB::table('departamento')->where('departamento', $departamento)->value('id');
        $tipo_id = $Tipo === 'DOAÇÃO' ? 2 : 1;

        $movimentos = DB::table('estoque')
            ->select('produto_id', DB::raw('SUM(entrada) as total_entrada'))
            ->where('tipo_id', $tipo_id)
            ->where('departamento_id', $depId)
            ->whereBetween('data', [$data1, $data2])
            ->groupBy('produto_id')
            ->get()->keyBy('produto_id');

        $produto_ids = $movimentos->keys();

        $produtos = DB::table('produto')
            ->join('categoria', 'produto.categoria_id', '=', 'categoria.id')
            ->join('categoria_geral', 'categoria.categoria_geral_id', '=', 'categoria_geral.id')
            ->select('produto.id', 'produto.produto', 'categoria.categoria', 'categoria_geral.categoria_geral')
            ->whereIn('produto.id', $produto_ids)
            ->orderBy('categoria_geral.categoria_geral')
            ->orderBy('categoria.categoria')
            ->orderBy('produto.produto')
            ->get();

        foreach ($produtos as $p) {
            $mov = $movimentos->get($p->id);
            $p->total_entrada = $mov ? $mov->total_entrada : 0;
        }

        $agrupado = [];
        foreach ($produtos as $p) {
            $agrupado[$p->categoria_geral][$p->categoria][] = $p;
        }

        $pdf = PDF::loadView('sistema.relatoriotipo', [
            'agrupado' => $agrupado,
            'tipo'     => $Tipo,
            'de'       => $departamento,
            'Data1'    => $data1,
            'Data2'    => $data2,
        ]);

        return $pdf->stream('realtoriogeral.pdf');
    }

    /**
     * Relatório geral de entradas/saídas por departamento.
     * Filtra por departamento via estoque.departamento_id.
     */
    public function relatorio(Request $request)
    {
        $data1        = $request['datainicial'];
        $Tipo         = $request['tipo'];
        $data2        = $request['datafinal'];
        $departamento = $request['departamento'];

        $depId = DB::table('departamento')->where('departamento', $departamento)->value('id');

        $query = DB::table('estoque')
            ->select('produto_id',
                DB::raw('SUM(entrada) as total_entrada'),
                DB::raw('SUM(saida) as total_saida'))
            ->where('departamento_id', $depId)
            ->whereBetween('data', [$data1, $data2]);

        if ($Tipo === 'Entradas') {
            $query->where('entrada', '>', 0);
        } elseif ($Tipo === 'Saidas') {
            $query->where('saida', '>', 0);
        }

        $movimentos  = $query->groupBy('produto_id')->get()->keyBy('produto_id');
        $produto_ids = $movimentos->keys();

        $produtos = DB::table('produto')
            ->join('categoria', 'produto.categoria_id', '=', 'categoria.id')
            ->join('categoria_geral', 'categoria.categoria_geral_id', '=', 'categoria_geral.id')
            ->select('produto.id', 'produto.produto', 'produto.quantidade', 'categoria.categoria', 'categoria_geral.categoria_geral')
            ->whereIn('produto.id', $produto_ids)
            ->orderBy('categoria_geral.categoria_geral')
            ->orderBy('categoria.categoria')
            ->orderBy('produto.produto')
            ->get();

        foreach ($produtos as $p) {
            $mov = $movimentos->get($p->id);
            $p->total_entrada = $mov ? $mov->total_entrada : 0;
            $p->total_saida   = $mov ? $mov->total_saida   : 0;

            // Stock acumulado ANTES do período (movimentos anteriores a data1 no mesmo departamento)
            $p->stock_antes = (int) DB::table('estoque')
                ->where('produto_id', $p->id)
                ->where('departamento_id', $depId)
                ->where('data', '<', $data1)
                ->sum(DB::raw('entrada - saida'));
        }

        $agrupado = [];
        foreach ($produtos as $p) {
            $agrupado[$p->categoria_geral][$p->categoria][] = $p;
        }

        $pdf = PDF::loadView('sistema.relatoriodepa', [
            'agrupado' => $agrupado,
            'tipo'     => $Tipo,
            'de'       => $departamento,
            'Data1'    => $data1,
            'Data2'    => $data2,
        ]);

        return $pdf->stream('realtoriogeral.pdf');
    }

    public function pdf(Request $request)
    {
        $data1        = $request['data'];
        $categoria    = Categoria::all();
        $departamento = $request['departamento'];

        $produto = DB::table('estoque')
            ->join('produto', 'produto.id', '=', 'estoque.produto_id')
            ->select('produto.categoria_id', 'produto.produto', 'produto.apresentacao', 'estoque.*')
            ->where('estoque.departamento', '=', $departamento)
            ->where('estoque.data', '=', $data1)
            ->orderBy('produto', 'ASC')
            ->get();

        $pdf = PDF::loadView('sistema.notadeentrega', [
            'Dt'          => $produto,
            'Data1'       => $data1,
            'Departamento'=> $departamento,
            'Categoria'   => $categoria,
        ]);

        return $pdf->stream('notadeentrega.pdf');
    }

    /**
     * Relatório de produtos por departamento.
     * Agora usa estoque para determinar quais produtos existem em cada departamento.
     */
    public function relatorioproduto()
    {
        $categoria = Categoria::all();

        // Produtos com pelo menos um movimento de estoque (visíveis globalmente)
        $data = DB::table('produto')
            ->join('categoria', 'produto.categoria_id', '=', 'categoria.id')
            ->select('produto.*', 'categoria.categoria')
            ->orderBy('produto', 'ASC')
            ->get();

        $pdf = PDF::loadView('sistema.relatoriodepaproduto', [
            'Dt'       => $data,
            'Categoria'=> $categoria,
        ]);

        return $pdf->stream('realtorioproduto.pdf');
    }

    public function dia(Request $request)
    {
        $Categoria    = Categoria::all();
        $Data1        = $request['data'];
        $departamento = $request['departamento'];
        $Dt           = Produto::orderBy('produto', 'ASC')->get();

        $pdf = PDF::loadView('sistema.diario', compact('Dt', 'Data1', 'Categoria', 'departamento'));
        return $pdf->stream('relatorio_diario.pdf');
    }

    public function ficha($id)
    {
        $produto = Produto::find($id);
        $depa    = Departamento::all();

        $pdf = PDF::loadView('sistema.fichadeestoque', compact('produto', 'id'));
        return $pdf->stream('Ficha de estock.pdf');
    }

    public function expirados()
    {
        $Products = DB::table('produto')
            ->join('lote', 'produto.id', '=', 'lote.produto_id')
            ->select('produto.produto', 'lote.*')
            ->orderBy('produto', 'ASC')
            ->get();

        $pdf = PDF::loadView('sistema.relatoriopordataexpiracao1', compact('Products'));
        return $pdf->stream('Relatório por data de expiração.pdf');
    }

    public function estoqueminimo()
    {
        // Stock mínimo agora é global — mostra todos os produtos abaixo do mínimo
        $Products = DB::table('produto')
            ->join('categoria', 'produto.categoria_id', '=', 'categoria.id')
            ->select('produto.*', 'categoria.categoria')
            ->orderBy('produto', 'ASC')
            ->get();

        $pdf = PDF::loadView('sistema.relatorioestoqueminimo', compact('Products'));
        return $pdf->stream('Relatório por estoque.pdf');
    }

    public function relatoriofornecedor(Request $request)
    {
        $data1           = $request['datainicial'];
        $Tipo            = $request['tipo'];
        $fornecedor_nome = $request['fornecedor_id'];
        $data2           = $request['datafinal'];
        $departamento    = $request['departamento'];

        $query = DB::table('estoque')
            ->select('produto_id',
                DB::raw('SUM(entrada) as total_entrada'),
                DB::raw('SUM(saida) as total_saida'))
            ->where('fornecedor_id', '=', $fornecedor_nome)
            ->whereBetween('data', [$data1, $data2]);

        if ($Tipo === 'entrada') {
            $query->where('entrada', '>', 0);
        } elseif ($Tipo === 'saida') {
            $query->where('saida', '>', 0);
        }

        $movimentos  = $query->groupBy('produto_id')->get()->keyBy('produto_id');
        $produto_ids = $movimentos->keys();

        $produtos = DB::table('produto')
            ->join('categoria', 'produto.categoria_id', '=', 'categoria.id')
            ->join('categoria_geral', 'categoria.categoria_geral_id', '=', 'categoria_geral.id')
            ->select('produto.id', 'produto.produto', 'categoria.id as categoria_id', 'categoria.categoria', 'categoria_geral.id as categoria_geral_id', 'categoria_geral.categoria_geral')
            ->whereIn('produto.id', $produto_ids)
            ->orderBy('categoria_geral.categoria_geral')
            ->orderBy('categoria.categoria')
            ->orderBy('produto.produto')
            ->get();

        foreach ($produtos as $produto) {
            $mov = $movimentos->get($produto->id);
            $produto->total_entrada = $mov ? $mov->total_entrada : 0;
            $produto->total_saida   = $mov ? $mov->total_saida   : 0;
        }

        $agrupado = [];
        foreach ($produtos as $produto) {
            $agrupado[$produto->categoria_geral][$produto->categoria][] = $produto;
        }

        $pdf = PDF::loadView('sistema.relatoriofornecedor', [
            'agrupado'   => $agrupado,
            'tipo'       => $Tipo,
            'de'         => $departamento,
            'Fornecedor' => $fornecedor_nome,
            'Data1'      => $data1,
            'Data2'      => $data2,
        ]);

        return $pdf->stream('Relatório Fornecedor.pdf');
    }
}
