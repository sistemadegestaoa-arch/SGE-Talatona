<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class RelatorioHospitalarController extends Controller
{
    private function getPerfil()
    {
        $user = auth()->user();
        $dep = DB::table('departamento')->where('id', $user->departamento_id)->value('departamento') ?? '';
        $dn = mb_strtolower($dep);
        $dn = strtr($dn, [
            'á'=>'a','à'=>'a','â'=>'a','ã'=>'a',
            'é'=>'e','è'=>'e','ê'=>'e',
            'í'=>'i','ó'=>'o','ô'=>'o','õ'=>'o',
            'ú'=>'u','ç'=>'c',
        ]);
        if ($user->tipo === 'admin') return 'admin';
        if (str_contains($dn, 'catalogac') || str_contains($dn, 'consultas') || str_contains($dn, 'triag') || str_contains($dn, 'c.p.n') || str_contains($dn, 's.a.t')) return 'triagem';
        if (str_contains($dn, 'lab') || str_contains($dn, 'raio') || str_contains($dn, 'hemot') || str_contains($dn, 'cada')) return 'laboratorio';
        if (str_contains($dn, 'farm')) return 'farmacia';
        if (
            str_contains($dn, 'banco') || str_contains($dn, 'medic') ||
            str_contains($dn, 'pediatr') || str_contains($dn, 'intern') ||
            str_contains($dn, 'cirurg') || str_contains($dn, 'puerp') ||
            str_contains($dn, 'neonat') || str_contains($dn, 'oftalm') ||
            str_contains($dn, 'fisiot') || str_contains($dn, 'nutric') ||
            str_contains($dn, 'odont') || str_contains($dn, 'tisiolog') ||
            str_contains($dn, 'gesso') || str_contains($dn, 'p.a.v') || str_contains($dn, 'estreliz')
        ) return 'medico';
        return 'admin'; // armazém/direcção — acesso total
    }

    public function index()
    {
        $perfil  = $this->getPerfil();
        $medicos = DB::table('users')
            ->join('departamento', 'departamento.id', '=', 'users.departamento_id')
            ->select('users.id', 'users.name', 'departamento.departamento')
            ->orderBy('users.name')
            ->get();
        return view('sistema.relatorio_hospitalar', compact('medicos', 'perfil'));
    }

    public function atendimentosPacientes(Request $request)
    {
        $request->validate([
            'data_inicio' => 'required|date',
            'data_fim'    => 'required|date|after_or_equal:data_inicio',
        ]);
        $dados = DB::table('episodio')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->leftJoin('consulta', 'consulta.episodio_id', '=', 'episodio.id')
            ->leftJoin('users', 'users.id', '=', 'consulta.medico_id')
            ->select(
                'episodio.id as episodio_id', 'episodio.data', 'episodio.estado',
                'paciente.nome', 'paciente.sexo', 'paciente.data_nascimento', 'paciente.numero_processo',
                'consulta.diagnostico', 'users.name as medico'
            )
            ->whereBetween('episodio.data', [$request->data_inicio, $request->data_fim])
            ->orderBy('episodio.data')
            ->get();

        $pdf = PDF::loadView('sistema.pdf.atendimentos_pacientes', [
            'dados'      => $dados,
            'total'      => $dados->count(),
            'concluidos' => $dados->where('estado', 'concluido')->count(),
            'emEspera'   => $dados->where('estado', 'em_espera')->count(),
            'dataInicio' => $request->data_inicio,
            'dataFim'    => $request->data_fim,
        ]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('atendimentos-pacientes.pdf');
    }

    public function atendimentosPorData(Request $request)
    {
        $request->validate([
            'data_inicio' => 'required|date',
            'data_fim'    => 'required|date|after_or_equal:data_inicio',
        ]);
        $dados = DB::table('episodio')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->selectRaw('episodio.data as dia,
                COUNT(*) as total,
                SUM(CASE WHEN episodio.estado="concluido" THEN 1 ELSE 0 END) as concluidos,
                SUM(CASE WHEN episodio.estado="em_espera" THEN 1 ELSE 0 END) as em_espera,
                SUM(CASE WHEN episodio.estado IN ("em_consulta","aguarda_exame") THEN 1 ELSE 0 END) as em_curso,
                SUM(CASE WHEN paciente.sexo="M" THEN 1 ELSE 0 END) as masculino,
                SUM(CASE WHEN paciente.sexo="F" THEN 1 ELSE 0 END) as feminino')
            ->whereBetween('episodio.data', [$request->data_inicio, $request->data_fim])
            ->groupBy('episodio.data')
            ->orderBy('episodio.data')
            ->get();

        $pdf = PDF::loadView('sistema.pdf.atendimentos_por_data', [
            'dados'      => $dados,
            'dataInicio' => $request->data_inicio,
            'dataFim'    => $request->data_fim,
        ]);
        return $pdf->stream('atendimentos-por-data.pdf');
    }

    public function atendimentosPorFuncionario(Request $request)
    {
        $request->validate([
            'data_inicio'    => 'required|date',
            'data_fim'       => 'required|date|after_or_equal:data_inicio',
            'funcionario_id' => 'nullable|integer|exists:users,id',
        ]);
        $query = DB::table('episodio')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->join('users as triador', 'triador.id', '=', 'episodio.triagem_user_id')
            ->leftJoin('consulta', 'consulta.episodio_id', '=', 'episodio.id')
            ->leftJoin('users as medico', 'medico.id', '=', 'consulta.medico_id')
            ->select(
                'episodio.id as episodio_id', 'episodio.data', 'episodio.estado',
                'paciente.nome', 'paciente.sexo', 'paciente.numero_processo',
                'triador.name as triador', 'medico.name as medico', 'consulta.diagnostico'
            )
            ->whereBetween('episodio.data', [$request->data_inicio, $request->data_fim]);

        $funcionario = 'Todos';
        if ($request->filled('funcionario_id')) {
            $fid = $request->funcionario_id;
            $query->where(function ($q) use ($fid) {
                $q->where('episodio.triagem_user_id', $fid)->orWhere('consulta.medico_id', $fid);
            });
            $funcionario = DB::table('users')->where('id', $fid)->value('name');
        }

        $dados = $query->orderBy('episodio.data')->get();
        $pdf = PDF::loadView('sistema.pdf.atendimentos_por_funcionario', [
            'dados'       => $dados,
            'funcionario' => $funcionario,
            'dataInicio'  => $request->data_inicio,
            'dataFim'     => $request->data_fim,
        ]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('atendimentos-funcionario.pdf');
    }

    public function relatorioMedico(Request $request)
    {
        $request->validate([
            'data_inicio' => 'required|date',
            'data_fim'    => 'required|date|after_or_equal:data_inicio',
            'medico_id'   => 'required|integer|exists:users,id',
        ]);
        $medico = DB::table('users')->where('id', $request->medico_id)->first();
        $consultas = DB::table('consulta')
            ->join('episodio', 'episodio.id', '=', 'consulta.episodio_id')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->leftJoin('receita', 'receita.consulta_id', '=', 'consulta.id')
            ->select(
                'consulta.id as consulta_id', 'episodio.data', 'episodio.estado',
                'paciente.nome', 'paciente.sexo', 'paciente.data_nascimento', 'paciente.numero_processo',
                'consulta.diagnostico', DB::raw('COUNT(DISTINCT receita.id) as tem_receita')
            )
            ->where('consulta.medico_id', $request->medico_id)
            ->whereBetween('episodio.data', [$request->data_inicio, $request->data_fim])
            ->groupBy('consulta.id', 'episodio.data', 'episodio.estado', 'paciente.nome', 'paciente.sexo', 'paciente.data_nascimento', 'paciente.numero_processo', 'consulta.diagnostico')
            ->orderBy('episodio.data')
            ->get();

        $exames = DB::table('pedido_exame')
            ->join('consulta', 'consulta.id', '=', 'pedido_exame.consulta_id')
            ->join('episodio', 'episodio.id', '=', 'consulta.episodio_id')
            ->selectRaw('COUNT(*) as total, SUM(CASE WHEN pedido_exame.urgente=1 THEN 1 ELSE 0 END) as urgentes')
            ->where('consulta.medico_id', $request->medico_id)
            ->whereBetween('episodio.data', [$request->data_inicio, $request->data_fim])
            ->first();

        $pdf = PDF::loadView('sistema.pdf.relatorio_medico', [
            'medico'     => $medico,
            'consultas'  => $consultas,
            'exames'     => $exames,
            'dataInicio' => $request->data_inicio,
            'dataFim'    => $request->data_fim,
        ]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('relatorio-medico.pdf');
    }

    public function relatorioRequisicoesFarmaco(Request $request)
    {
        $request->validate([
            'data_inicio' => 'required|date',
            'data_fim'    => 'required|date|after_or_equal:data_inicio',
            'estado'      => 'nullable|in:pendente,atendida,rejeitada',
        ]);

        $query = DB::table('requisicao_farmaco')
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
            ->whereBetween(DB::raw('DATE(requisicao_farmaco.created_at)'), [$request->data_inicio, $request->data_fim])
            ->orderByRaw("FIELD(requisicao_farmaco.estado,'pendente','atendida','rejeitada')")
            ->orderByDesc('requisicao_farmaco.id');

        if ($request->filled('estado')) {
            $query->where('requisicao_farmaco.estado', $request->estado);
        }

        // Filtrar por departamento se for laboratório (não admin)
        $user   = auth()->user();
        $perfil = $this->getPerfil();
        if ($perfil === 'laboratorio') {
            $query->where('requisicao_farmaco.departamento_id', $user->departamento_id);
        }

        $requisicoes = $query->get();

        // Buscar itens de cada requisição
        $ids = $requisicoes->pluck('id');
        $itensPorReq = DB::table('requisicao_farmaco_item')
            ->join('produto', 'produto.id', '=', 'requisicao_farmaco_item.produto_id')
            ->whereIn('requisicao_farmaco_id', $ids)
            ->select(
                'requisicao_farmaco_id',
                'produto.produto',
                'produto.apresentacao',
                'requisicao_farmaco_item.quantidade'
            )
            ->get()
            ->groupBy('requisicao_farmaco_id');

        $pdf = PDF::loadView('sistema.pdf.relatorio_requisicoes_farmaco', [
            'requisicoes'   => $requisicoes,
            'itensPorReq'   => $itensPorReq,
            'total'         => $requisicoes->count(),
            'totalAtendidas'  => $requisicoes->where('estado', 'atendida')->count(),
            'totalPendentes'  => $requisicoes->where('estado', 'pendente')->count(),
            'totalRejeitadas' => $requisicoes->where('estado', 'rejeitada')->count(),
            'dataInicio'    => $request->data_inicio,
            'dataFim'       => $request->data_fim,
            'estadoFiltro'  => $request->estado ?? 'todos',
            'perfil'        => $perfil,
        ]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('relatorio-requisicoes-farmaco-' . $request->data_inicio . '-' . $request->data_fim . '.pdf');
    }

    public function relatorioLaboratorio(Request $request)
    {
        $request->validate([
            'data_inicio' => 'required|date',
            'data_fim'    => 'required|date|after_or_equal:data_inicio',
        ]);
        $pedidos = DB::table('pedido_exame')
            ->join('consulta', 'consulta.id', '=', 'pedido_exame.consulta_id')
            ->join('episodio', 'episodio.id', '=', 'consulta.episodio_id')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->join('users as medico', 'medico.id', '=', 'pedido_exame.medico_id')
            ->leftJoin('resultado_exame', 'resultado_exame.pedido_exame_id', '=', 'pedido_exame.id')
            ->leftJoin('users as tecnico', 'tecnico.id', '=', 'resultado_exame.tecnico_id')
            ->select(
                'pedido_exame.id as pedido_id', 'pedido_exame.descricao_exame',
                'pedido_exame.urgente', 'pedido_exame.estado', 'pedido_exame.created_at as data_pedido',
                'paciente.nome', 'paciente.sexo', 'paciente.numero_processo',
                'medico.name as medico', 'resultado_exame.resultado',
                'resultado_exame.created_at as data_resultado', 'tecnico.name as tecnico'
            )
            ->whereBetween(DB::raw('DATE(pedido_exame.created_at)'), [$request->data_inicio, $request->data_fim])
            ->orderByDesc('pedido_exame.urgente')
            ->orderBy('pedido_exame.created_at')
            ->get();

        $pdf = PDF::loadView('sistema.pdf.relatorio_laboratorio', [
            'pedidos'    => $pedidos,
            'total'      => $pedidos->count(),
            'concluidos' => $pedidos->where('estado', 'concluido')->count(),
            'urgentes'   => $pedidos->where('urgente', 1)->count(),
            'pendentes'  => $pedidos->where('estado', 'pendente')->count(),
            'dataInicio' => $request->data_inicio,
            'dataFim'    => $request->data_fim,
        ]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('relatorio-laboratorio.pdf');
    }
}
