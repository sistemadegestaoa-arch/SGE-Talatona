<?php

namespace App\Http\Controllers;

use App\PedidoExame;
use App\ResultadoExame;
use App\Episodio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LaboratorioController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // HOME / DASHBOARD DO LABORATÓRIO
    // ─────────────────────────────────────────────────────────────────────────
    public function index()
    {
        $hoje = today();

        // Pedidos pendentes (urgentes primeiro, depois por ordem de chegada)
        $pendentes = DB::table('pedido_exame')
            ->join('consulta',  'consulta.id',  '=', 'pedido_exame.consulta_id')
            ->join('episodio',  'episodio.id',  '=', 'consulta.episodio_id')
            ->join('paciente',  'paciente.id',  '=', 'episodio.paciente_id')
            ->join('users',     'users.id',     '=', 'pedido_exame.medico_id')
            ->select(
                'pedido_exame.id as pedido_id',
                'pedido_exame.descricao_exame',
                'pedido_exame.urgente',
                'pedido_exame.estado',
                'pedido_exame.observacao',
                'pedido_exame.data_pedido',
                'pedido_exame.created_at as hora_pedido',
                'episodio.id as episodio_id',
                'paciente.nome',
                'paciente.sexo',
                'paciente.data_nascimento',
                'paciente.numero_processo',
                'users.name as medico'
            )
            ->where('pedido_exame.estado', 'pendente')
            ->orderByDesc('pedido_exame.urgente')
            ->orderBy('pedido_exame.id', 'asc')
            ->get();

        // Resultados registados hoje
        $concluidos = DB::table('pedido_exame')
            ->join('resultado_exame', 'resultado_exame.pedido_exame_id', '=', 'pedido_exame.id')
            ->join('consulta',  'consulta.id',  '=', 'pedido_exame.consulta_id')
            ->join('episodio',  'episodio.id',  '=', 'consulta.episodio_id')
            ->join('paciente',  'paciente.id',  '=', 'episodio.paciente_id')
            ->join('users',     'users.id',     '=', 'resultado_exame.tecnico_id')
            ->select(
                'pedido_exame.id as pedido_id',
                'pedido_exame.descricao_exame',
                'paciente.nome',
                'paciente.sexo',
                'resultado_exame.data_resultado',
                'resultado_exame.created_at as hora_resultado',
                'users.name as tecnico',
                'resultado_exame.ficheiro_path'
            )
            ->whereDate('resultado_exame.created_at', $hoje)
            ->orderByDesc('resultado_exame.id')
            ->get();

        // Totais
        $totalPendentes  = $pendentes->count();
        $totalUrgentes   = $pendentes->where('urgente', 1)->count();
        $totalConcluidosHoje = $concluidos->count();
        $totalGeral      = DB::table('resultado_exame')->count();

        return view('sistema.laboratorio.index', compact(
            'pendentes', 'concluidos',
            'totalPendentes', 'totalUrgentes', 'totalConcluidosHoje', 'totalGeral'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // VER PEDIDO — formulário para registar resultado
    // ─────────────────────────────────────────────────────────────────────────
    public function show($pedido_id)
    {
        $pedido = DB::table('pedido_exame')
            ->join('consulta',  'consulta.id',  '=', 'pedido_exame.consulta_id')
            ->join('episodio',  'episodio.id',  '=', 'consulta.episodio_id')
            ->join('paciente',  'paciente.id',  '=', 'episodio.paciente_id')
            ->join('users',     'users.id',     '=', 'pedido_exame.medico_id')
            ->leftJoin('triagem', 'triagem.episodio_id', '=', 'episodio.id')
            ->select(
                'pedido_exame.id as pedido_id',
                'pedido_exame.consulta_id',
                'pedido_exame.medico_id',
                'pedido_exame.descricao_exame',
                'pedido_exame.urgente',
                'pedido_exame.estado',
                'pedido_exame.observacao',
                'pedido_exame.data_pedido',
                'pedido_exame.created_at as hora_pedido',
                'episodio.id as episodio_id',
                'paciente.nome',
                'paciente.sexo',
                'paciente.data_nascimento',
                'paciente.numero_processo',
                'users.name as medico',
                'triagem.pressao_arterial',
                'triagem.temperatura',
                'triagem.peso',
                'triagem.altura',
                'triagem.observacao as obs_triagem'
            )
            ->where('pedido_exame.id', $pedido_id)
            ->first();

        if (!$pedido) return redirect()->route('laboratorio.index');

        $resultado = ResultadoExame::where('pedido_exame_id', $pedido_id)->first();

        // Outros exames do mesmo paciente (histórico)
        $historico = DB::table('pedido_exame')
            ->join('consulta',  'consulta.id',  '=', 'pedido_exame.consulta_id')
            ->join('episodio',  'episodio.id',  '=', 'consulta.episodio_id')
            ->leftJoin('resultado_exame', 'resultado_exame.pedido_exame_id', '=', 'pedido_exame.id')
            ->where('episodio.paciente_id', function ($q) use ($pedido_id) {
                $q->select('p2.id')
                  ->from('pedido_exame as pe2')
                  ->join('consulta as c2',  'c2.id',  '=', 'pe2.consulta_id')
                  ->join('episodio as e2',  'e2.id',  '=', 'c2.episodio_id')
                  ->join('paciente as p2',  'p2.id',  '=', 'e2.paciente_id')
                  ->where('pe2.id', $pedido_id)
                  ->limit(1);
            })
            ->where('pedido_exame.id', '!=', $pedido_id)
            ->orderByDesc('pedido_exame.id')
            ->limit(5)
            ->select(
                'pedido_exame.descricao_exame',
                'pedido_exame.data_pedido',
                'pedido_exame.estado',
                'resultado_exame.resultado',
                'resultado_exame.data_resultado'
            )
            ->get();

        return view('sistema.laboratorio.show', compact('pedido', 'resultado', 'historico'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GUARDAR RESULTADO DE EXAME
    // ─────────────────────────────────────────────────────────────────────────
    public function store(Request $request, $pedido_id)
    {
        $request->validate([
            'resultado'  => 'required|string',
            'ficheiro'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'resultado.required' => 'O resultado do exame é obrigatório.',
            'ficheiro.max'       => 'O ficheiro não pode exceder 5MB.',
            'ficheiro.mimes'     => 'Apenas PDF, JPG ou PNG são aceites.',
        ]);

        $pedido = PedidoExame::findOrFail($pedido_id);

        // Upload do ficheiro
        $path = null;
        if ($request->hasFile('ficheiro') && $request->file('ficheiro')->isValid()) {
            $path = $request->file('ficheiro')->store('resultados', 'public');
        }

        // Cria ou actualiza resultado
        ResultadoExame::updateOrCreate(
            ['pedido_exame_id' => $pedido_id],
            [
                'tecnico_id'     => auth()->id(),
                'resultado'      => $request->resultado,
                'ficheiro_path'  => $path ?? ResultadoExame::where('pedido_exame_id', $pedido_id)->value('ficheiro_path'),
                'data_resultado' => today(),
            ]
        );

        // Marca pedido como concluído
        $pedido->update(['estado' => 'concluido']);

        // Actualiza estado do episódio — verifica se ainda há pedidos pendentes
        $consulta_id = $pedido->consulta_id;
        $aindaPendentes = PedidoExame::where('consulta_id', $consulta_id)
            ->where('estado', 'pendente')
            ->where('id', '!=', $pedido_id)
            ->exists();

        if (!$aindaPendentes) {
            // Volta para em_consulta para o médico ver os resultados
            $episodio_id = DB::table('consulta')->where('id', $consulta_id)->value('episodio_id');
            if ($episodio_id) {
                Episodio::where('id', $episodio_id)
                    ->where('estado', 'aguarda_exame')
                    ->update(['estado' => 'em_consulta']);
            }
        }

        return redirect()->route('laboratorio.index')
            ->with('success', 'Resultado registado com sucesso. Médico notificado.');
    }
}
