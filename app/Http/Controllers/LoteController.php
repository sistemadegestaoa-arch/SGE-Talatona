<?php

namespace App\Http\Controllers;

use App\lote;
use App\Http\Controllers\Traits\VerificaPermissaoEliminar;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LoteController extends Controller
{
    use VerificaPermissaoEliminar;
    public function index()
    {
        $lote = DB::table('produto')
            ->join('lote', 'produto.id', '=', 'lote.produto_id')
            ->select('produto.produto', 'lote.*')
            ->where('lote.departamento_id', auth()->user()->departamento_id)
            ->orderBy('produto', 'ASC')
            ->get();

        return view('sistema.ver-lote', compact('lote'));
    }

    public function create()
    {
        $pro = DB::table('produto')
            ->select('produto.*')
            ->orderBy('produto', 'ASC')
            ->get();

        return view('sistema.lote', compact('pro'));
    }

    public function store(Request $request)
    {
        if (DB::table('lote')->where([
            ['lote',       $request->lote],
            ['produto_id', $request->produto_id],
        ])->count() == 0) {

            $lote = new lote;
            $lote->departamento_id = $request->departamento_id;
            $lote->lote            = $request->lote;
            $lote->codigo_barra    = $request->codigo_barra;
            $lote->validade        = $request->validade;
            $lote->produto_id      = $request->produto_id;
            $lote->save();

            return redirect()->route('lote.create')->with('success', 'Lote associado com sucesso.');
        }

        return redirect()->route('lote.create')->with('error', 'Este lote já está associado a este produto.');
    }

    public function edit($id)
    {
        $lote = DB::table('produto')
            ->join('lote', 'produto.id', '=', 'lote.produto_id')
            ->select('produto.produto', 'lote.*')
            ->where('lote.id', $id)
            ->first();

        if (!$lote) return redirect()->route('ver-lotes.index')->with('error', 'Lote não encontrado.');

        $pro = DB::table('produto')->select('produto.*')->orderBy('produto')->get();

        return view('sistema.editarlote', compact('lote', 'pro'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'lote'     => 'required|string|max:255',
            'validade' => 'required|date',
        ]);

        $l = lote::findOrFail($id);
        $l->lote         = $request->lote;
        $l->validade     = $request->validade;
        $l->codigo_barra = $request->codigo_barra;
        $l->save();

        return redirect()->route('ver-lotes.index')->with('success', 'Lote actualizado com sucesso.');
    }

    public function destroy($id)
    {
        if ($redirect = $this->abortSeNaoPodeEliminar()) return $redirect;
        $l = lote::findOrFail($id);
        $l->delete();
        return redirect()->route('ver-lotes.index')->with('success', 'Lote eliminado com sucesso.');
    }
}
