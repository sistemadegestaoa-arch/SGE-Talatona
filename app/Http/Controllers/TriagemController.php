<?php

namespace App\Http\Controllers;

use App\Paciente;
use App\Episodio;
use App\Triagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TriagemController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // DASHBOARD
    // ─────────────────────────────────────────────────────────────────────────
    public function index()
    {
        $episodios = DB::table('episodio')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->join('users',    'users.id',    '=', 'episodio.triagem_user_id')
            ->select(
                'episodio.id as episodio_id',
                'episodio.estado',
                'episodio.created_at',
                'paciente.nome',
                'paciente.sexo',
                'paciente.data_nascimento',
                'paciente.numero_processo',
                'users.name as triagem_by'
            )
            ->whereDate('episodio.data', today())
            ->orderBy('episodio.id', 'desc')
            ->get();

        $totalHoje  = $episodios->count();
        $emEspera   = $episodios->where('estado', 'em_espera')->count();
        $emConsulta = $episodios->whereIn('estado', ['em_consulta', 'aguarda_exame'])->count();
        $concluidos = $episodios->where('estado', 'concluido')->count();

        return view('sistema.triagem.index', compact(
            'episodios', 'totalHoje', 'emEspera', 'emConsulta', 'concluidos'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PESQUISAR PACIENTE (AJAX) — rota deve vir antes de triagem/{id}
    // ─────────────────────────────────────────────────────────────────────────
    public function pesquisarPaciente(Request $request)
    {
        $q = trim($request->get('q', ''));

        $pacientes = DB::table('paciente')
            ->where(function ($query) use ($q) {
                $query->where('nome', 'like', "%$q%")
                      ->orWhere('numero_processo', 'like', "%$q%");
            })
            ->orderBy('nome')
            ->limit(10)
            ->get(['id', 'nome', 'numero_processo', 'data_nascimento', 'sexo', 'telefone', 'morada']);

        return response()->json($pacientes);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // FORMULÁRIO NOVA TRIAGEM
    // ─────────────────────────────────────────────────────────────────────────
    public function create()
    {
        return view('sistema.triagem.create');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GUARDAR PACIENTE + EPISÓDIO + TRIAGEM
    // ─────────────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'nome'                   => 'required|string|max:255',
            'sexo'                   => 'required|in:M,F',
            'data_nascimento'        => 'nullable|date|before:today',
            'telefone'               => 'nullable|string|max:30',
            'morada'                 => 'nullable|string|max:255',
            'numero_processo'        => 'nullable|string|max:50',
            'urgente'                => 'nullable|boolean',
            'pressao_arterial'       => 'nullable|string|max:20',
            'temperatura'            => 'nullable|numeric|between:30,45',
            'peso'                   => 'nullable|numeric|min:1|max:300',
            'altura'                 => 'nullable|numeric|min:30|max:250',
            'frequencia_cardiaca'    => 'nullable|integer|min:20|max:300',
            'frequencia_respiratoria'=> 'nullable|integer|min:5|max:60',
            'saturacao_oxigenio'     => 'nullable|integer|min:1|max:100',
            'observacao'             => 'nullable|string',
        ], [
            'nome.required'          => 'O nome do paciente é obrigatório.',
            'sexo.required'          => 'Seleccione o sexo.',
            'data_nascimento.before' => 'A data de nascimento deve ser anterior a hoje.',
            'temperatura.between'    => 'Temperatura inválida (deve estar entre 30 e 45°C).',
        ]);

        // 1. Cria ou actualiza o paciente FORA da transaction para poder verificar erros antes
        if ($request->filled('paciente_id')) {
            $paciente = Paciente::findOrFail($request->paciente_id);
            $paciente->update(array_filter([
                'telefone' => $request->telefone,
                'morada'   => $request->morada,
            ]));
        } else {
            $paciente = Paciente::create([
                'nome'            => $request->nome,
                'sexo'            => $request->sexo,
                'data_nascimento' => $request->data_nascimento ?: null,
                'numero_processo' => $request->numero_processo ?: null,
                'telefone'        => $request->telefone ?: null,
                'morada'          => $request->morada ?: null,
            ]);
        }

        // 2. Verifica se já tem episódio hoje
        $jaTemHoje = Episodio::where('paciente_id', $paciente->id)
            ->whereDate('data', today())
            ->exists();

        if ($jaTemHoje) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Este paciente já foi triado hoje.');
        }

        // 3. Cria episódio + triagem numa transaction
        DB::transaction(function () use ($request, $paciente) {

            $episodio = Episodio::create([
                'paciente_id'     => $paciente->id,
                'triagem_user_id' => auth()->id(),
                'data'            => today(),
                'estado'          => 'em_espera',
                'urgente'         => $request->boolean('urgente'),
            ]);

            Triagem::create([
                'episodio_id'             => $episodio->id,
                'pressao_arterial'        => $request->pressao_arterial        ?: null,
                'temperatura'             => $request->temperatura             ?: null,
                'peso'                    => $request->peso                    ?: null,
                'altura'                  => $request->altura                  ?: null,
                'frequencia_cardiaca'     => $request->frequencia_cardiaca     ?: null,
                'frequencia_respiratoria' => $request->frequencia_respiratoria ?: null,
                'saturacao_oxigenio'      => $request->saturacao_oxigenio      ?: null,
                'observacao'              => $request->observacao              ?: null,
            ]);
        });

        return redirect()->route('triagem.index')
            ->with('success', 'Triagem registada. Paciente em espera.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ESTATÍSTICAS
    // ─────────────────────────────────────────────────────────────────────────
    public function estatisticas()
    {
        return view('sistema.triagem.estatisticas');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // VER DETALHE DO EPISÓDIO
    // ─────────────────────────────────────────────────────────────────────────
    public function show($id)
    {
        // Seleciona explicitamente as colunas para evitar conflito de ids
        $episodio = DB::table('episodio')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->join('users',    'users.id',    '=', 'episodio.triagem_user_id')
            ->select(
                'episodio.id as episodio_id',
                'episodio.estado',
                'episodio.data',
                'episodio.created_at',
                'paciente.id as paciente_id',
                'paciente.nome',
                'paciente.sexo',
                'paciente.data_nascimento',
                'paciente.numero_processo',
                'paciente.telefone',
                'paciente.morada',
                'users.name as triagem_by'
            )
            ->where('episodio.id', $id)
            ->first();

        if (!$episodio) return redirect()->route('triagem.index');

        $triagem = Triagem::where('episodio_id', $id)->first();

        // Histórico de visitas anteriores do mesmo paciente
        $historico = DB::table('episodio')
            ->leftJoin('triagem', 'triagem.episodio_id', '=', 'episodio.id')
            ->where('episodio.paciente_id', $episodio->paciente_id)
            ->where('episodio.id', '!=', $id)
            ->orderBy('episodio.data', 'desc')
            ->limit(5)
            ->select(
                'episodio.data',
                'episodio.estado',
                'triagem.pressao_arterial',
                'triagem.temperatura',
                'triagem.peso'
            )
            ->get();

        return view('sistema.triagem.show', compact('episodio', 'triagem', 'historico'));
    }
}
