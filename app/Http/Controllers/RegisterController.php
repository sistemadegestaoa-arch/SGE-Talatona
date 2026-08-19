<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Departamento;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\VerificaPermissaoEliminar;

class RegisterController extends Controller
{
    use VerificaPermissaoEliminar;
    /**
     * Exibe a página pública de criação do primeiro administrador.
     */
    public function setupView()
    {
        return view('auth.setup');
    }

    /**
     * Processa o cadastro do administrador inicial (sem autenticação).
     */
    public function setupStore(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:8|confirmed',
        ]);

        // Cria ou encontra o departamento "Direcção"
        $departamento = Departamento::firstOrCreate(
            ['departamento' => 'Direcção']
        );

        User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'tipo'            => 'admin',
            'departamento_id' => $departamento->id,
            'password'        => Hash::make($request->password),
        ]);

        return view('auth.setup', ['sms' => 'sucesso']);
    }

 
    public function show(){
        $users = DB::table('departamento')
            ->join('users', 'departamento.id', '=', 'users.departamento_id')
            ->select('users.*','departamento.departamento')
            ->get();
        return view('sistema.verusuario', ['User' => $users]);
    }

    public function destroy($id){
        if ($redirect = $this->abortSeNaoPodeEliminar()) return $redirect;
        if (!$user = User::find($id))
            return redirect()->back()->with('error', 'Utilizador não encontrado.');
        $user->delete();
        return redirect()->back()->with('success', 'Utilizador eliminado com sucesso.');
    }

    public function registar(){
        $depa = Departamento::all();
        return view('sistema.register', ['Dp' => $depa]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|string|min:6|confirmed',
            'tipo'            => 'required|in:user,admin',
            'departamento_id' => 'required|exists:departamento,id',
        ], [
            'name.required'            => 'O nome é obrigatório.',
            'email.required'           => 'O email é obrigatório.',
            'email.unique'             => 'Este email já está em uso.',
            'password.required'        => 'A senha é obrigatória.',
            'password.min'             => 'A senha deve ter pelo menos 6 caracteres.',
            'password.confirmed'       => 'As senhas não coincidem.',
            'departamento_id.required' => 'Selecione um departamento.',
        ]);

        User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'tipo'            => $request->tipo,
            'departamento_id' => $request->departamento_id,
            'password'        => Hash::make($request->password),
        ]);

        return redirect()->route('verusuario.show')
            ->with('success', 'Utilizador criado com sucesso.');
    }
 
}
