<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Departamento;
use App\Http\Controllers\Traits\VerificaPermissaoEliminar;

class DepartamentoConroller extends Controller
{
    use VerificaPermissaoEliminar;
    // 

    public function index(){
        $depa=Departamento::all();
        //return redirect()->route('departamento.index',['Depa'=>'$depa']);
        return view('sistema.departamento',['Dp'=>$depa]);
        
    }

    public function create(){
        return view('sistema.createdepa');
    }
    public function store(Request $request){
        $request->validate(['departamento' => 'required|string|max:255']);
        $depa = new Departamento;
        $depa->departamento = $request->departamento;
        $depa->save();
        return redirect()->route('departamento.index')->with('success', 'Departamento criado com sucesso.');
    }
  

        public function edit($id)
        {
            $depa = Departamento::findOrFail($id);
            return view('sistema.editedepa', compact('depa'));
        }

    public function update(Request $request, $id){
        $request->validate(['departamento' => 'required|string|max:255']);
        $depa = Departamento::findOrFail($id);
        $depa->departamento = $request->departamento;
        $depa->save();
        return redirect()->route('departamento.index')->with('success', 'Departamento actualizado com sucesso.');
    }
    public function destroy($id){
        if ($redirect = $this->abortSeNaoPodeEliminar()) return $redirect;
        if (!$depa = Departamento::find($id))
            return redirect()->back()->with('error', 'Departamento não encontrado.');
        $depa->delete();
        return redirect()->route('departamento.index')->with('success', 'Departamento eliminado com sucesso.');
    }
}
