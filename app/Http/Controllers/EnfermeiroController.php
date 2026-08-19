<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnfermeiroController extends Controller
{
    /**
     * Painel principal do enfermeiro / S.O.
     * Mostra prescrições médicas enviadas (pacientes que foram para S.O.)
     * e permite fazer requisições de fármacos.
     */
    public function index()
    {
        $user = Auth::user();
        $hoje = today();

        // ── Prescrições do dia — pacientes em consulta ou concluídos com prescrição ──
        $prescricoes = DB::table('prescricao')
            ->join('consulta',  'consulta.id',  '=', 'prescricao.consulta_id')
            ->join('episodio',  'episodio.id',  '=', 'consulta.episodio_id')
            ->join('paciente',  'paciente.id',  '=', 'episodio.paciente_id')
            ->join('users',     'users.id',     '=', 'prescricao.medico_id')
            ->select(
                'prescricao.id as prescricao_id',
                'prescricao.diagnostico',
                'prescricao.observacao',
                'prescricao.data',
                'prescricao.created_at as hora',
                'episodio.id as episodio_id',
                'episodio.urgente',
                'episodio.estado',
                'paciente.id as paciente_id',
                'paciente.nome',
                'paciente.sexo',
                'paciente.data_nascimento',
                'paciente.numero_processo',
                'users.name as medico'
            )
            ->whereDate('episodio.data', $hoje)
            ->orderByDesc('episodio.urgente')
            ->orderByDesc('prescricao.id')
            ->get();

        // Carregar itens de cada prescrição
        foreach ($prescricoes as $p) {
            $p->itens = DB::table('prescricao_item')
                ->where('prescricao_id', $p->prescricao_id)
                ->select('medicamento', 'forma_farmaceutica', 'dosagem', 'dose', 'frequencia', 'duracao', 'quantidade', 'instrucoes')
                ->get();
        }

        // ── Requisições de fármacos deste departamento ─────────────────────
        $requisicoes = DB::table('requisicao_farmaco')
            ->join('departamento', 'departamento.id', '=', 'requisicao_farmaco.departamento_id')
            ->join('users as sol', 'sol.id', '=', 'requisicao_farmaco.solicitante_id')
            ->leftJoin('users as ate', 'ate.id', '=', 'requisicao_farmaco.atendido_por')
            ->select(
                'requisicao_farmaco.id',
                'requisicao_farmaco.estado',
                'requisicao_farmaco.observacao',
                'requisicao_farmaco.created_at',
                'requisicao_farmaco.atendido_em',
                'departamento.departamento as dep_nome',
                'sol.name as solicitante',
                'ate.name as atendente'
            )
            ->where('requisicao_farmaco.departamento_id', $user->departamento_id)
            ->orderByDesc('requisicao_farmaco.id')
            ->get();

        // ── Fármacos disponíveis para requisição ───────────────────────────
        $farmacos = DB::table('produto')
            ->where('bloqueado', 0)
            ->orderBy('produto')
            ->select('id', 'produto', 'apresentacao', 'bloqueado', 'quantidade', 'stokminimo')
            ->get();

        // ── Totais ─────────────────────────────────────────────────────────
        $totalPrescricoes = $prescricoes->count();
        $totalUrgentes    = $prescricoes->where('urgente', 1)->count();
        $reqPendentes     = $requisicoes->where('estado', 'pendente')->count();

        return view('sistema.enfermeiro.index', compact(
            'prescricoes', 'requisicoes', 'farmacos',
            'totalPrescricoes', 'totalUrgentes', 'reqPendentes'
        ));
    }
    public function listAll()
    {
         $user = Auth::user();

        $requisicoes = DB::table('requisicao_farmaco')
            ->join('departamento', 'departamento.id', '=', 'requisicao_farmaco.departamento_id')
            ->join('users as sol', 'sol.id', '=', 'requisicao_farmaco.solicitante_id')
            ->leftJoin('users as ate', 'ate.id', '=', 'requisicao_farmaco.atendido_por')
            ->select(
                'requisicao_farmaco.id',
                'requisicao_farmaco.estado',
                'requisicao_farmaco.observacao',
                'requisicao_farmaco.created_at',
                'requisicao_farmaco.atendido_em',
                'departamento.departamento as dep_nome',
                'sol.name as solicitante',
                'ate.name as atendente'
            )
            ->where('requisicao_farmaco.departamento_id', $user->departamento_id)
            ->orderByDesc('requisicao_farmaco.id')
            ->get();

        // Todos os fármacos com flag de bloqueio — para o JS filtrar e avisar
        $farmacos = DB::table('produto')
            ->orderBy('produto')
            ->select('id', 'produto', 'apresentacao', 'bloqueado')
            ->get();

        return view('sistema.enfermeiro.requisicao_farmaco', compact('requisicoes', 'farmacos'));
    }
}
