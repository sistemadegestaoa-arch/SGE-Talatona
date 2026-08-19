<?php

namespace App\Http\Controllers;

use App\Departamento;
use Illuminate\Http\Request;
use App\Produto;
use App\Estoque;
use App\Fornecedor;
use App\lote;
use App\tipo;
use Illuminate\Support\Facades\DB;

class EstoqueController extends Controller
{
    /**
     * Página de gestão de stock de um lote.
     */
    public function estoque($id)
    {
        $tipo    = tipo::all();
        $depa    = Departamento::all();
        $fornecedor = Fornecedor::all();

        $produto = DB::table('produto')
            ->join('lote', 'produto.id', '=', 'lote.produto_id')
            ->select('produto.*', 'lote.lote')
            ->where('lote.id', '=', $id)
            ->get();

        return view('sistema.esoque', [
            'Pr'      => $produto,
            'Dp'      => $depa,
            'Fr'      => $fornecedor,
            'Tipo'    => $tipo,
            'lote_id' => $id,
        ]);
    }

    /**
     * Regista entrada ou saída de stock.
     *
     * Numa saída do armazém, cria automaticamente a entrada no(s)
     * departamento(s) de farmácia — usando o MESMO produto_id e lote_id,
     * eliminando qualquer problema de nomes diferentes entre departamentos.
     */
    public function create(Request $request)
    {
        $today      = date('Y-m-d');
        $lote_id    = $request['lote_id'];
        $produto_id = $request['produto_id'];

        // Calcula stock actual do lote no departamento de origem
        $soma = DB::table('estoque')
            ->where('lote_id', $lote_id)
            ->where('departamento_id', $request['departamento_id'])
            ->sum(DB::raw('entrada - saida'));

        $produto = Produto::find($produto_id);
        if (!$produto) {
            return redirect()->back()->with('error', 'Produto não encontrado.');
        }

        // ── Validação: fármaco bloqueado ──────────────────────────────────
        if ($produto->bloqueado) {
            $motivo = $produto->motivo_bloqueio
                ? " Motivo: {$produto->motivo_bloqueio}."
                : '';
            return redirect()->back()->with('error',
                "⛔ O fármaco \"{$produto->produto}\" está bloqueado e não pode ser movimentado.{$motivo} Contacte a Farmácia ou o Armazém para desbloquear."
            );
        }

        if ($request['situacao'] == 'Saida') {

            if ($soma >= $request['estock']) {

                // qinicial calculado a partir da tabela estoque (fonte da verdade),
                // evitando divergências causadas pelo campo denormalizado produto.quantidade
                $qinicial = (int) DB::table('estoque')
                    ->where('produto_id', $produto_id)
                    ->where('departamento_id', $request['departamento_id'])
                    ->sum(DB::raw('entrada - saida'));

                $q1       = (int) $request['estock'];
                $q        = $qinicial - $q1;
                $dataMovimento = $request['data'] ?? $today;

                // ── Aviso stock mínimo ────────────────────────────────────
                $stockAposMovimento = $q;
                $avisoStock = '';
                if ($stockAposMovimento <= $produto->stokminimo) {
                    $avisoStock = " ⚠️ ATENÇÃO: stock ficará em {$stockAposMovimento} unidades (mínimo: {$produto->stokminimo}).";
                }

                // Actualiza o campo denormalizado a partir do valor recalculado
                $produto->quantidade = (int) DB::table('estoque')
                    ->where('produto_id', $produto_id)
                    ->sum(DB::raw('entrada - saida')) - $q1;
                $produto->save();

                Estoque::create([
                    'produto_id'     => $produto_id,
                    'tipo_id'        => $request['tipo_id'],
                    'situacao'       => 'Saida',
                    'departamento_id'=> $request['departamento_id'],
                    'users_id'       => $request['users_id'],
                    'entrada'        => 0,
                    'saida'          => $q1,
                    'qinicial'       => $qinicial,
                    'qfinal'         => $q,
                    'data'           => $dataMovimento,
                    'departamento'   => $request['departamento'],
                    'obs'            => $request['obs'],
                    'lote_id'        => $lote_id,
                    'fornecedor_id'  => 'Transferência do Armazém',
                ]);

                // Entrada automática na Farmácia — mesmo produto_id e lote_id
                $this->registarEntradaFarmacia(
                    $produto_id,
                    $lote_id,
                    $q1,
                    $dataMovimento,
                    $request['users_id'],
                    $request['departamento_id']
                );

                return redirect()->back()->with('success', 'Saída registada com sucesso. Entrada automática criada na Farmácia.' . $avisoStock);

            } else {
                return redirect()->back()->with('error', 'Stock insuficiente. Stock disponível: ' . $soma . '.');
            }

        } elseif ($request['situacao'] == 'Entrada') {

            // qinicial calculado a partir da tabela estoque (fonte da verdade)
            $qinicial = (int) DB::table('estoque')
                ->where('produto_id', $produto_id)
                ->where('departamento_id', $request['departamento_id'])
                ->sum(DB::raw('entrada - saida'));

            $q2 = (int) $request['estock'];
            $q  = $qinicial + $q2;

            // Actualiza o campo denormalizado (total global de todos os departamentos)
            $produto->quantidade = (int) DB::table('estoque')
                ->where('produto_id', $produto_id)
                ->sum(DB::raw('entrada - saida')) + $q2;
            $produto->save();

            Estoque::create([
                'produto_id'     => $produto_id,
                'situacao'       => 'Entrada',
                'lote_id'        => $lote_id,
                'tipo_id'        => $request['tipo_id'],
                'departamento_id'=> $request['departamento_id'],
                'users_id'       => $request['users_id'],
                'entrada'        => $q2,
                'saida'          => 0,
                'qinicial'       => $qinicial,
                'qfinal'         => $q,
                'data'           => $request['data'],
                'fornecedor_id'  => $request['fornecedor_id'],
            ]);

            return redirect()->back()->with('success', 'Entrada registada com sucesso.');
        }
    }

    /**
     * Regista automaticamente uma entrada na Farmácia quando o armazém faz uma saída.
     *
     * Como os produtos são agora globais (sem departamento_id na tabela produto),
     * usa-se o MESMO produto_id e lote_id — não há risco de nomes diferentes.
     * Apenas o departamento_id no registo de estoque muda.
     *
     * Se o lote ainda não existir no departamento da farmácia, é criado
     * automaticamente com os mesmos dados do lote do armazém.
     *
     * @param int    $produto_id      ID do produto (global)
     * @param int    $loteArmazem_id  ID do lote do armazém
     * @param int    $quantidade      Quantidade transferida
     * @param string $data            Data do movimento (Y-m-d)
     * @param int    $users_id        ID do utilizador que fez a saída
     * @param int    $depArmazem_id   ID do departamento de origem (armazém)
     */
    private function registarEntradaFarmacia($produto_id, $loteArmazem_id, $quantidade, $data, $users_id, $depArmazem_id)
    {
        // Encontra departamentos de farmácia (excluindo o departamento de origem)
        $farmacias = DB::table('departamento')
            ->where('departamento', 'like', '%farm%')
            ->where('id', '!=', $depArmazem_id)
            ->get();

        if ($farmacias->isEmpty()) {
            return;
        }

        $loteArmazem = DB::table('lote')->where('id', $loteArmazem_id)->first();
        $produto     = Produto::find($produto_id);

        foreach ($farmacias as $farmacia) {

            // Verifica se o lote já existe para este departamento de farmácia.
            // Como o produto é global, o lote pode ser o mesmo ID ou um lote
            // específico criado para a farmácia com o mesmo número.
            $loteFarmaciaId = null;

            if ($loteArmazem) {
                // Procura lote com o mesmo número associado ao mesmo produto na farmácia
                $loteFarmacia = DB::table('lote')
                    ->where('produto_id', $produto_id)
                    ->where('lote', $loteArmazem->lote)
                    ->where('departamento_id', $farmacia->id)
                    ->first();

                if ($loteFarmacia) {
                    $loteFarmaciaId = $loteFarmacia->id;
                } else {
                    // Cria o lote na farmácia com os mesmos dados
                    $loteFarmaciaId = DB::table('lote')->insertGetId([
                        'produto_id'     => $produto_id,
                        'departamento_id'=> $farmacia->id,
                        'lote'           => $loteArmazem->lote,
                        'codigo_barra'   => $loteArmazem->codigo_barra ?? null,
                        'validade'       => $loteArmazem->validade ?? null,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);
                }
            } else {
                // Sem lote de referência — usa o primeiro lote do produto na farmácia
                $primeiroLote   = DB::table('lote')
                    ->where('produto_id', $produto_id)
                    ->where('departamento_id', $farmacia->id)
                    ->first();
                $loteFarmaciaId = $primeiroLote ? $primeiroLote->id : null;
            }

            if (!$loteFarmaciaId) {
                continue;
            }

            // Calcula stock actual do produto na farmácia
            $qinicialFarmacia = DB::table('estoque')
                ->where('produto_id', $produto_id)
                ->where('departamento_id', $farmacia->id)
                ->sum(DB::raw('entrada - saida'));

            $qfinalFarmacia = $qinicialFarmacia + $quantidade;

            // Actualiza produto.quantidade (campo global — soma de todos os departamentos)
            // Nota: este campo é redundante com o estoque; aqui mantemos por compatibilidade
            DB::table('produto')
                ->where('id', $produto_id)
                ->update([
                    'quantidade' => DB::raw('quantidade + ' . (int)$quantidade),
                    'updated_at' => now(),
                ]);

            Estoque::create([
                'produto_id'     => $produto_id,
                'situacao'       => 'Entrada',
                'lote_id'        => $loteFarmaciaId,
                'tipo_id'        => 1,
                'departamento_id'=> $farmacia->id,
                'users_id'       => $users_id,
                'entrada'        => $quantidade,
                'saida'          => 0,
                'qinicial'       => $qinicialFarmacia,
                'qfinal'         => $qfinalFarmacia,
                'data'           => $data,
                'fornecedor_id'  => 'Transferência do Armazém',
                'departamento'   => $farmacia->id,
                'obs'            => 'Entrada automática — saída do armazém em ' . $data,
            ]);
        }
    }
}
