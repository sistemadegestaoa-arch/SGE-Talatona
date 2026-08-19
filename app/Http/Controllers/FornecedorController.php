<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Fornecedor;
use App\Http\Controllers\Traits\VerificaPermissaoEliminar;

class FornecedorController extends Controller
{
    use VerificaPermissaoEliminar;
    //
    public function index(){
        $fornecedor=Fornecedor::all();
        //return redirect()->route('departamento.index',['Depa'=>'$depa']);
        return view('sistema.fornecedor',['Fornecedor'=>$fornecedor]);
        
    }

    public function create(){
        return view('sistema.createfornecedor');
    }
    public function store(Request $request){
        $request->validate([
            'fornecedor' => 'required|string|max:255',
            'telefone'   => 'required|string|max:50',
            'nif'        => 'required|string|max:50',
            'email'      => 'required|email',
            'endereco'   => 'required|string',
        ]);
        $fornecedor = new Fornecedor;
        $fornecedor->fornecedor = $request->fornecedor;
        $fornecedor->telefone   = $request->telefone;
        $fornecedor->nif        = $request->nif;
        $fornecedor->email      = $request->email;
        $fornecedor->endereco   = $request->endereco;
        $fornecedor->save();
        return redirect()->route('fornecedor.index')->with('success', 'Fornecedor criado com sucesso.');
    }
  

        public function edit($id)
        {
            if (!$depa= Departamento::find($id)) 
            return redirect()->back();
    
            return view('sistema.departamento',['Dp'=>$depa]);
      
         } 

    public function update(Request $request, $id){

    }
    public function destroy($id){
        // Verifica permissão
        if ($redirect = $this->abortSeNaoPodeEliminar()) return $redirect;

        if (!$fornecedor = Fornecedor::find($id))
            return redirect()->back()->with('error', 'Fornecedor não encontrado.');

        $fornecedor->delete();
        return redirect()->route('fornecedor.index')->with('success', 'Fornecedor eliminado com sucesso.');
    }
}
