<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Departamento;
use App\Fornecedor;

class RelatorioController extends Controller
{
    //

    public function index()
    {
        $fornecedor = Fornecedor::all();

        $Produto = DB::table('produto')
            ->select('produto.*')
            ->orderBy('produto', 'ASC')
            ->get();

        $depa = Departamento::all();

        $depId = auth()->user()->departamento_id;

        // leftJoin com fornecedor porque saídas têm fornecedor_id = 'Transferência do Armazém' (string)
        // e um INNER JOIN excluiria esses registos da tabela.
        // leftJoin com lote para mostrar validade (data_expiracao) na tabela.
        // Filtra pelo departamento do utilizador para não misturar movimentos de outros departamentos.
        $data = DB::table('estoque')
            ->join('produto', 'produto.id', '=', 'estoque.produto_id')
            ->leftJoin('lote', 'lote.id', '=', 'estoque.lote_id')
            ->leftJoin('fornecedor', function($join) {
                $join->on('fornecedor.id', '=', DB::raw('CAST(estoque.fornecedor_id AS UNSIGNED)'))
                     ->whereRaw("estoque.fornecedor_id REGEXP '^[0-9]+$'");
            })
            ->select(
                DB::raw("COALESCE(fornecedor.fornecedor, estoque.fornecedor_id) as fornecedor"),
                'produto.produto', 'produto.apresentacao',
                'lote.lote',
                'lote.validade as data_expiracao',
                'estoque.id', 'estoque.data', 'estoque.entrada', 'estoque.saida',
                'estoque.qinicial', 'estoque.qfinal', 'estoque.situacao',
                'estoque.obs', 'estoque.fornecedor_id', 'estoque.departamento_id',
                'estoque.produto_id', 'estoque.lote_id'
            )
            ->where('estoque.departamento_id', $depId)
            ->orderBy('estoque.data', 'DESC')
            ->orderBy('estoque.id', 'DESC')
            ->get();

        $Lote = DB::table('produto')
            ->join('lote', 'produto.id', '=', 'lote.produto_id')
            ->select('lote.*', 'produto.produto')
            ->get();

        return view('sistema.relatorio', [
            'Dt'        => $data,
            'Dp'        => $depa,
            'produto'   => $Produto,
            'lote'      => $Lote,
            'Fornecedor'=> $fornecedor,
        ]);
    }

}

