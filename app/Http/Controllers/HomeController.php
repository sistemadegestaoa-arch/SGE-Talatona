<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $depNome = \DB::table('departamento')
            ->where('id', auth()->user()->departamento_id)
            ->value('departamento') ?? '';

        $dn = mb_strtolower($depNome);
        $dn = strtr($dn, ['á'=>'a','à'=>'a','â'=>'a','ã'=>'a','é'=>'e','è'=>'e','ê'=>'e','í'=>'i','ó'=>'o','ô'=>'o','õ'=>'o','ú'=>'u','ç'=>'c','ñ'=>'n']);

        // Enfermeiro / S.O. — ANTES do laboratório e médico
        if (
            str_contains($dn, 'S.O')

        ) {
            return view('sistema.home_enfermeiro');
        }

        // Triagem
        if (str_contains($dn, 'catalogac') || str_contains($dn, 'consultas') || str_contains($dn, 'triag') || str_contains($dn, 'c.p.n') || str_contains($dn, 'cpn')) {
            return view('sistema.home_triagem');
        }

        // Laboratório / Raio X / Hemoterapia
        if (str_contains($dn, 'lab') || str_contains($dn, 'raio') || str_contains($dn, 'hemot') || str_contains($dn, 'cada')) {
            return view('sistema.home_laboratorio');
        }

        // Médico — todos os bancos e especialidades clínicas (sem p.a.v/s.a.t que são enfermagem)
        if (
            str_contains($dn, 'banco') || str_contains($dn, 'medic') ||
            str_contains($dn, 'pediatr') || str_contains($dn, 'intern') ||
            str_contains($dn, 'cirurg') || str_contains($dn, 'puerp') ||
            str_contains($dn, 'odont') || str_contains($dn, 'tisiolog') ||
            str_contains($dn, 'neonat') || str_contains($dn, 'oftalm') ||
            str_contains($dn, 'fisiot') || str_contains($dn, 'nutric') ||
            str_contains($dn, 'pos-parto') || str_contains($dn, 'gesso') ||
            str_contains($dn, 'estreliz')
        ) {
            return view('sistema.home_medico');
        }

        // Farmácia (user — não admin)
        if (str_contains($dn, 'farm') && Auth::user()->tipo !== 'admin') {
            return view('sistema.home_farmacia');
        }

        return view('sistema.home');
    }
    public function sair(){
        Auth::logout();
        return redirect()->back();
    }
}
