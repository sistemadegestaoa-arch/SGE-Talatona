<?php

namespace App\Http\Controllers;

use App\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProdutoBloqueioController extends Controller
{
    public function index()
    {
        $produtos = DB::table('produto')
            ->join('categoria', 'produto.categoria_id', '=', 'categoria.id')
            ->leftJoin('users', 'users.id', '=', 'produto.bloqueado_por')
            ->select(
                'produto.id',
                'produto.produto',
                'produto.apresentacao',
                'produto.bloqueado',
                'produto.motivo_bloqueio',
                'produto.bloqueado_em',
                'produto.quantidade',
                'produto.stokminimo',
                'categoria.categoria',
                'users.name as bloqueado_por_nome'
            )
            ->orderBy('produto.bloqueado', 'desc')
            ->orderBy('produto.produto')
            ->get();

        $totalBloqueados    = $produtos->where('bloqueado', 1)->count();
        $totalDesbloqueados = $produtos->where('bloqueado', 0)->count();
        $totalStockBaixo    = $produtos->where('bloqueado', 0)->filter(fn($p) => $p->quantidade <= $p->stokminimo)->count();

        return view('sistema.produto_bloqueio', compact('produtos', 'totalBloqueados', 'totalDesbloqueados', 'totalStockBaixo'));
    }

    public function bloquear(Request $request, $id)
    {
        $request->validate([
            'motivo' => 'required|string|max:255',
        ], [
            'motivo.required' => 'O motivo do bloqueio é obrigatório.',
        ]);

        $produto = Produto::findOrFail($id);

        $produto->update([
            'bloqueado'       => true,
            'motivo_bloqueio' => $request->motivo,
            'bloqueado_por'   => Auth::id(),
            'bloqueado_em'    => now(),
        ]);

        return redirect()->route('produto-bloqueio.index')
            ->with('success', "Fármaco \"{$produto->produto}\" bloqueado com sucesso.");
    }

    public function desbloquear($id)
    {
        $produto = Produto::findOrFail($id);

        $produto->update([
            'bloqueado'       => false,
            'motivo_bloqueio' => null,
            'bloqueado_por'   => null,
            'bloqueado_em'    => null,
        ]);

        return redirect()->route('produto-bloqueio.index')
            ->with('success', "Fármaco \"{$produto->produto}\" desbloqueado com sucesso.");
    }
}
