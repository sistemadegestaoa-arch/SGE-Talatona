<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class FarmacoHelper
{
    /**
     * Verifica se algum produto está bloqueado.
     * Retorna array com nomes dos produtos bloqueados (vazio se nenhum).
     *
     * @param array $ids  Array de produto_id
     */
    public static function verificarBloqueados(array $ids): array
    {
        if (empty($ids)) return [];

        return DB::table('produto')
            ->whereIn('id', $ids)
            ->where('bloqueado', 1)
            ->pluck('produto')
            ->toArray();
    }

    /**
     * Verifica se algum produto tem stock abaixo ou igual ao mínimo.
     * Retorna array de ['nome' => ..., 'stock' => ..., 'minimo' => ...].
     *
     * @param array $itens  Array de ['produto_id' => X, 'quantidade' => Y]
     * @param int   $departamento_id  Departamento para calcular stock
     */
    public static function verificarStockBaixo(array $itens, int $departamento_id): array
    {
        $alertas = [];

        foreach ($itens as $item) {
            $pid = (int)($item['produto_id'] ?? 0);
            $qty = (int)($item['quantidade'] ?? 1);
            if (!$pid) continue;

            $produto = DB::table('produto')->where('id', $pid)->first();
            if (!$produto) continue;

            // Stock real = quantidade na tabela produto (campo quantidade = stock total)
            $stockReal = (int)$produto->quantidade;
            $minimo    = (int)$produto->stokminimo;

            if ($stockReal <= $minimo) {
                $alertas[] = [
                    'nome'    => $produto->produto,
                    'stock'   => $stockReal,
                    'minimo'  => $minimo,
                    'pedido'  => $qty,
                ];
            }
        }

        return $alertas;
    }

    /**
     * Monta a mensagem de erro de fármacos bloqueados.
     */
    public static function msgBloqueados(array $nomes): string
    {
        $lista = implode(', ', array_map(fn($n) => "\"{$n}\"", $nomes));
        return count($nomes) === 1
            ? "O fármaco {$lista} está bloqueado e não pode ser utilizado. Contacte a Farmácia ou o Armazém para desbloquear."
            : "Os seguintes fármacos estão bloqueados e não podem ser utilizados: {$lista}. Contacte a Farmácia ou o Armazém para desbloquear.";
    }

    /**
     * Monta a mensagem de erro de stock baixo.
     */
    public static function msgStockBaixo(array $alertas): string
    {
        $linhas = array_map(function ($a) {
            return "\"{$a['nome']}\" (stock: {$a['stock']}, mínimo: {$a['minimo']})";
        }, $alertas);

        return 'Stock baixo ou insuficiente: ' . implode('; ', $linhas)
            . '. Não é possível prosseguir. Solicite reposição ao Armazém.';
    }
}
