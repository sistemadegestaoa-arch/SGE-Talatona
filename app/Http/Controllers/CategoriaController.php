<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;
use App\categoria_geral;
use App\Http\Controllers\Traits\VerificaPermissaoEliminar;

class CategoriaController extends Controller
{
    use VerificaPermissaoEliminar;
    // ── SUBCATEGORIAS ──────────────────────────────────────────

    public function index()
    {
        $categorias = \DB::table('categoria')
            ->leftJoin('categoria_geral','categoria.categoria_geral_id','=','categoria_geral.id')
            ->select('categoria.*','categoria_geral.categoria_geral')
            ->orderBy('categoria_geral.categoria_geral')
            ->orderBy('categoria.categoria')
            ->get();

        $gerais = categoria_geral::orderBy('categoria_geral')->get();

        return view('sistema.categoria', compact('categorias', 'gerais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoria'          => 'required|string|max:255',
            'categoria_geral_id' => 'required|exists:categoria_geral,id',
        ]);
        Categoria::create([
            'categoria'          => $request->categoria,
            'categoria_geral_id' => $request->categoria_geral_id,
        ]);
        return redirect()->route('categoria.index')->with('success', 'Subcategoria criada com sucesso.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'categoria'          => 'required|string|max:255',
            'categoria_geral_id' => 'required|exists:categoria_geral,id',
        ]);
        $cat = Categoria::findOrFail($id);
        $cat->categoria          = $request->categoria;
        $cat->categoria_geral_id = $request->categoria_geral_id;
        $cat->save();
        return redirect()->route('categoria.index')->with('success', 'Subcategoria actualizada com sucesso.');
    }

    public function destroy($id)
    {
        if ($redirect = $this->abortSeNaoPodeEliminar()) return $redirect;
        Categoria::findOrFail($id)->delete();
        return redirect()->route('categoria.index')->with('success', 'Subcategoria eliminada com sucesso.');
    }

    // ── CATEGORIAS GERAIS ──────────────────────────────────────

    public function storeGeral(Request $request)
    {
        $request->validate(['categoria_geral' => 'required|string|max:255|unique:categoria_geral,categoria_geral']);
        categoria_geral::create(['categoria_geral' => $request->categoria_geral]);
        return redirect()->route('categoria.index')->with('success', 'Categoria geral criada com sucesso.');
    }

    public function updateGeral(Request $request, $id)
    {
        $request->validate(['categoria_geral' => 'required|string|max:255']);
        $g = categoria_geral::findOrFail($id);
        $g->categoria_geral = $request->categoria_geral;
        $g->save();
        return redirect()->route('categoria.index')->with('success', 'Categoria geral actualizada com sucesso.');
    }

    public function destroyGeral($id)
    {
        if ($redirect = $this->abortSeNaoPodeEliminar()) return $redirect;
        $count = Categoria::where('categoria_geral_id', $id)->count();
        if ($count > 0) {
            return redirect()->route('categoria.index')
                ->with('error', "Não é possível eliminar — existem $count subcategorias associadas.");
        }
        categoria_geral::findOrFail($id)->delete();
        return redirect()->route('categoria.index')->with('success', 'Categoria geral eliminada com sucesso.');
    }
}
