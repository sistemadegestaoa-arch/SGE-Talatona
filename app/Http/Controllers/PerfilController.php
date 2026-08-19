<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\User;

class PerfilController extends Controller
{
    // ── Detecta o perfil funcional do utilizador ──────────────────────────────
    private function detectarPerfil($user): string
    {
        if ($user->tipo === 'admin') return 'admin';

        $dep = DB::table('departamento')
            ->where('id', $user->departamento_id)
            ->value('departamento') ?? '';

        $dn = mb_strtolower($dep);
        $dn = strtr($dn, [
            'á'=>'a','à'=>'a','â'=>'a','ã'=>'a',
            'é'=>'e','è'=>'e','ê'=>'e','ë'=>'e',
            'í'=>'i','ì'=>'i','î'=>'i',
            'ó'=>'o','ò'=>'o','ô'=>'o','õ'=>'o',
            'ú'=>'u','ù'=>'u','û'=>'u',
            'ç'=>'c','ñ'=>'n',
        ]);

        // Armazém central
        if (str_contains($dn, 'armazem')) return 'armazem';

        // Farmácia
        if (str_contains($dn, 'farm')) return 'farmacia';

        // Laboratório / Raio X / Hemoterapia / Análises
        if (
            str_contains($dn, 'lab') ||
            str_contains($dn, 'raio') ||
            str_contains($dn, 'hemot') ||
            str_contains($dn, 'analise') ||
            str_contains($dn, 'cada')
        ) return 'laboratorio';

        // Triagem / Catalogação / CPN / SAT / Consultas externas
        if (
            str_contains($dn, 'catalogac') ||
            str_contains($dn, 'consultas') ||
            str_contains($dn, 'triag') ||
            str_contains($dn, 'c.p.n') ||
            str_contains($dn, 'cpn') ||
            str_contains($dn, 's.a.t') ||
            str_contains($dn, 'sat')
        ) return 'triagem';

        // Médico — todos os bancos clínicos e especialidades
        if (
            str_contains($dn, 'banco') ||
            str_contains($dn, 'medic') ||   // medicina, mediciana, medico
            str_contains($dn, 'pediatr') ||
            str_contains($dn, 'intern') ||
            str_contains($dn, 'cirurg') ||
            str_contains($dn, 'puerp') ||
            str_contains($dn, 'odont') ||
            str_contains($dn, 'tisiolog') ||
            str_contains($dn, 'neonat') ||
            str_contains($dn, 'oftalm') ||
            str_contains($dn, 'fisiot') ||
            str_contains($dn, 'nutric') ||
            str_contains($dn, 'pos-parto') ||
            str_contains($dn, 'gesso') ||
            str_contains($dn, 'estreliz') ||
            str_contains($dn, 'p.a.v') ||
            str_contains($dn, 'pav') ||
            str_contains($dn, 'direc')
        ) return 'medico';

        // Fallback — mostra perfil clínico genérico (sem movimentos de stock)
        return 'medico';
    }

    public function index()
    {
        $user = Auth::user();

        $departamento = DB::table('departamento')
            ->where('id', $user->departamento_id)
            ->first();

        $perfil = $this->detectarPerfil($user);
        $stats  = [];
        $historico = collect();
        $historicoTitulo = '';

        switch ($perfil) {

            // ── ADMIN / ARMAZÉM ───────────────────────────────────────────────
            case 'admin':
            case 'armazem':
                $stats = [
                    [
                        'valor' => DB::table('estoque')->where('users_id', $user->id)->where('situacao', 'Entrada')->count(),
                        'label' => 'Entradas Registadas',
                        'cor'   => '#065f46',
                        'icone' => 'icon-arrow-down-circle',
                        'bg'    => '#d1fae5',
                    ],
                    [
                        'valor' => DB::table('estoque')->where('users_id', $user->id)->where('situacao', 'Saida')->count(),
                        'label' => 'Saídas Registadas',
                        'cor'   => '#991b1b',
                        'icone' => 'icon-arrow-up-circle',
                        'bg'    => '#fee2e2',
                    ],
                    [
                        'valor' => DB::table('estoque')->where('users_id', $user->id)->count(),
                        'label' => 'Total Movimentos',
                        'cor'   => '#1a6b2f',
                        'icone' => 'icon-activity',
                        'bg'    => '#f0faf2',
                    ],
                    [
                        'valor' => DB::table('produto')->count(),
                        'label' => 'Fármacos no Sistema',
                        'cor'   => '#1d4ed8',
                        'icone' => 'icon-package',
                        'bg'    => '#dbeafe',
                    ],
                    [
                        'valor' => DB::table('users')->count(),
                        'label' => 'Utilizadores',
                        'cor'   => '#5b21b6',
                        'icone' => 'icon-users',
                        'bg'    => '#ede9fe',
                    ],
                    [
                        'valor' => DB::table('requisicao')->where('statos', 'Pendente')->count(),
                        'label' => 'Requisições Pendentes',
                        'cor'   => '#92400e',
                        'icone' => 'icon-bell',
                        'bg'    => '#fef3c7',
                    ],
                ];
                $historico = DB::table('estoque')
                    ->join('produto', 'produto.id', '=', 'estoque.produto_id')
                    ->select('estoque.*', 'produto.produto')
                    ->where('estoque.users_id', $user->id)
                    ->orderBy('estoque.id', 'desc')
                    ->limit(8)
                    ->get();
                $historicoTitulo = 'Últimos Movimentos de Estoque';
                break;

            // ── FARMÁCIA ──────────────────────────────────────────────────────
            case 'farmacia':
                $stats = [
                    [
                        'valor' => DB::table('estoque')
                            ->where('users_id', $user->id)
                            ->where('situacao', 'Entrada')
                            ->count(),
                        'label' => 'Entradas Registadas',
                        'cor'   => '#065f46',
                        'icone' => 'icon-arrow-down-circle',
                        'bg'    => '#d1fae5',
                    ],
                    [
                        'valor' => DB::table('estoque')
                            ->where('users_id', $user->id)
                            ->where('situacao', 'Saida')
                            ->count(),
                        'label' => 'Saídas / Dispensas',
                        'cor'   => '#991b1b',
                        'icone' => 'icon-arrow-up-circle',
                        'bg'    => '#fee2e2',
                    ],
                    [
                        'valor' => DB::table('atendimento')
                            ->where('users_id', $user->id)
                            ->count(),
                        'label' => 'Atendimentos',
                        'cor'   => '#1a6b2f',
                        'icone' => 'icon-user-check',
                        'bg'    => '#f0faf2',
                    ],
                    [
                        'valor' => DB::table('receita')
                            ->where('estado', 'dispensada')
                            ->whereExists(function ($q) use ($user) {
                                $q->select(DB::raw(1))
                                  ->from('atendimento')
                                  ->whereColumn('atendimento.receita_id', 'receita.id')
                                  ->where('atendimento.users_id', $user->id);
                            })
                            ->count(),
                        'label' => 'Receitas Dispensadas',
                        'cor'   => '#065f46',
                        'icone' => 'icon-file-text',
                        'bg'    => '#d1fae5',
                    ],
                    [
                        'valor' => DB::table('requisicao_farmaco')
                            ->where('atendido_por', $user->id)
                            ->count(),
                        'label' => 'Req. Fármacos Atendidas',
                        'cor'   => '#5b21b6',
                        'icone' => 'icon-shopping-cart',
                        'bg'    => '#ede9fe',
                    ],
                    [
                        'valor' => DB::table('receita')->where('estado', 'pendente')->count(),
                        'label' => 'Receitas Pendentes',
                        'cor'   => '#92400e',
                        'icone' => 'icon-clock',
                        'bg'    => '#fef3c7',
                    ],
                ];
                $historico = DB::table('estoque')
                    ->join('produto', 'produto.id', '=', 'estoque.produto_id')
                    ->select('estoque.*', 'produto.produto')
                    ->where('estoque.users_id', $user->id)
                    ->orderBy('estoque.id', 'desc')
                    ->limit(8)
                    ->get();
                $historicoTitulo = 'Últimos Movimentos de Estoque';
                break;

            // ── LABORATÓRIO ───────────────────────────────────────────────────
            case 'laboratorio':
                $stats = [
                    [
                        'valor' => DB::table('resultado_exame')
                            ->where('tecnico_id', $user->id)
                            ->count(),
                        'label' => 'Exames Processados',
                        'cor'   => '#1a6b2f',
                        'icone' => 'icon-activity',
                        'bg'    => '#f0faf2',
                    ],
                    [
                        'valor' => DB::table('resultado_exame')
                            ->where('tecnico_id', $user->id)
                            ->whereDate('created_at', today())
                            ->count(),
                        'label' => 'Exames Hoje',
                        'cor'   => '#1d4ed8',
                        'icone' => 'icon-calendar',
                        'bg'    => '#dbeafe',
                    ],
                    [
                        'valor' => DB::table('pedido_exame')
                            ->where('estado', 'pendente')
                            ->count(),
                        'label' => 'Exames Pendentes',
                        'cor'   => '#92400e',
                        'icone' => 'icon-clock',
                        'bg'    => '#fef3c7',
                    ],
                    [
                        'valor' => DB::table('pedido_exame')
                            ->where('estado', 'pendente')
                            ->where('urgente', 1)
                            ->count(),
                        'label' => 'Urgentes Pendentes',
                        'cor'   => '#991b1b',
                        'icone' => 'icon-alert-triangle',
                        'bg'    => '#fee2e2',
                    ],
                    [
                        'valor' => DB::table('requisicao_farmaco')
                            ->where('departamento_id', $user->departamento_id)
                            ->count(),
                        'label' => 'Requisições de Fármacos',
                        'cor'   => '#5b21b6',
                        'icone' => 'icon-shopping-cart',
                        'bg'    => '#ede9fe',
                    ],
                    [
                        'valor' => DB::table('requisicao_farmaco')
                            ->where('departamento_id', $user->departamento_id)
                            ->where('estado', 'pendente')
                            ->count(),
                        'label' => 'Req. Fármacos Pendentes',
                        'cor'   => '#92400e',
                        'icone' => 'icon-bell',
                        'bg'    => '#fef3c7',
                    ],
                ];
                $historico = DB::table('resultado_exame')
                    ->join('pedido_exame', 'pedido_exame.id', '=', 'resultado_exame.pedido_exame_id')
                    ->join('consulta', 'consulta.id', '=', 'pedido_exame.consulta_id')
                    ->join('episodio', 'episodio.id', '=', 'consulta.episodio_id')
                    ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
                    ->select(
                        'resultado_exame.created_at as data',
                        'pedido_exame.descricao_exame as produto',
                        'paciente.nome as paciente',
                        'pedido_exame.urgente'
                    )
                    ->where('resultado_exame.tecnico_id', $user->id)
                    ->orderByDesc('resultado_exame.id')
                    ->limit(8)
                    ->get();
                $historicoTitulo = 'Últimos Resultados de Exame Registados';
                break;

            // ── MÉDICO ────────────────────────────────────────────────────────
            case 'medico':
                $totalConsultas = DB::table('consulta')
                    ->where('medico_id', $user->id)
                    ->count();

                $consultasHoje = DB::table('consulta')
                    ->join('episodio', 'episodio.id', '=', 'consulta.episodio_id')
                    ->where('consulta.medico_id', $user->id)
                    ->whereDate('episodio.data', today())
                    ->count();

                $examesSolicitados = DB::table('pedido_exame')
                    ->where('medico_id', $user->id)
                    ->count();

                $receitasEmitidas = DB::table('receita')
                    ->where('medico_id', $user->id)
                    ->count();

                $prescricoesEmitidas = DB::table('prescricao')
                    ->where('medico_id', $user->id)
                    ->count();

                // Pacientes em espera HOJE (global — médico vê todos)
                $pacientesEspera = DB::table('episodio')
                    ->whereDate('data', today())
                    ->where('estado', 'em_espera')
                    ->count();

                $stats = [
                    [
                        'valor' => $totalConsultas,
                        'label' => 'Consultas Realizadas',
                        'cor'   => '#1a6b2f',
                        'icone' => 'icon-heart',
                        'bg'    => '#f0faf2',
                    ],
                    [
                        'valor' => $consultasHoje,
                        'label' => 'Consultas Hoje',
                        'cor'   => '#1d4ed8',
                        'icone' => 'icon-calendar',
                        'bg'    => '#dbeafe',
                    ],
                    [
                        'valor' => $examesSolicitados,
                        'label' => 'Exames Solicitados',
                        'cor'   => '#5b21b6',
                        'icone' => 'icon-activity',
                        'bg'    => '#ede9fe',
                    ],
                    [
                        'valor' => $receitasEmitidas,
                        'label' => 'Receitas Emitidas',
                        'cor'   => '#065f46',
                        'icone' => 'icon-file-text',
                        'bg'    => '#d1fae5',
                    ],
                    [
                        'valor' => $prescricoesEmitidas,
                        'label' => 'Prescrições Emitidas',
                        'cor'   => '#92400e',
                        'icone' => 'icon-edit',
                        'bg'    => '#fef3c7',
                    ],
                    [
                        'valor' => $pacientesEspera,
                        'label' => 'Em Espera Agora',
                        'cor'   => '#991b1b',
                        'icone' => 'icon-users',
                        'bg'    => '#fee2e2',
                    ],
                ];
                $historico = DB::table('consulta')
                    ->join('episodio', 'episodio.id', '=', 'consulta.episodio_id')
                    ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
                    ->select(
                        'episodio.data',
                        'paciente.nome as paciente',
                        'consulta.diagnostico as produto',
                        'episodio.estado'
                    )
                    ->where('consulta.medico_id', $user->id)
                    ->orderByDesc('consulta.id')
                    ->limit(8)
                    ->get();
                $historicoTitulo = 'Últimas Consultas Realizadas';
                break;

            // ── TRIAGEM ───────────────────────────────────────────────────────
            case 'triagem':
                $stats = [
                    [
                        'valor' => DB::table('episodio')
                            ->where('triagem_user_id', $user->id)
                            ->count(),
                        'label' => 'Triagens Realizadas por Mim',
                        'cor'   => '#1a6b2f',
                        'icone' => 'icon-clipboard',
                        'bg'    => '#f0faf2',
                    ],
                    [
                        'valor' => DB::table('episodio')
                            ->where('triagem_user_id', $user->id)
                            ->whereDate('data', today())
                            ->count(),
                        'label' => 'Triagens Minhas Hoje',
                        'cor'   => '#1d4ed8',
                        'icone' => 'icon-calendar',
                        'bg'    => '#dbeafe',
                    ],
                    [
                        'valor' => DB::table('episodio')
                            ->whereDate('data', today())
                            ->where('estado', 'em_espera')
                            ->count(),
                        'label' => 'Em Espera Agora',
                        'cor'   => '#92400e',
                        'icone' => 'icon-clock',
                        'bg'    => '#fef3c7',
                    ],
                    [
                        'valor' => DB::table('episodio')
                            ->whereDate('data', today())
                            ->where('urgente', 1)
                            ->count(),
                        'label' => 'Urgentes Hoje',
                        'cor'   => '#991b1b',
                        'icone' => 'icon-alert-triangle',
                        'bg'    => '#fee2e2',
                    ],
                    [
                        'valor' => DB::table('episodio')
                            ->whereDate('data', today())
                            ->where('estado', 'concluido')
                            ->count(),
                        'label' => 'Concluídos Hoje',
                        'cor'   => '#065f46',
                        'icone' => 'icon-check-circle',
                        'bg'    => '#d1fae5',
                    ],
                    [
                        'valor' => DB::table('paciente')->count(),
                        'label' => 'Pacientes Registados',
                        'cor'   => '#5b21b6',
                        'icone' => 'icon-users',
                        'bg'    => '#ede9fe',
                    ],
                ];
                $historico = DB::table('episodio')
                    ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
                    ->select(
                        'episodio.data',
                        'paciente.nome as paciente',
                        DB::raw("CONCAT(CASE paciente.sexo WHEN 'M' THEN 'Masc.' ELSE 'Fem.' END,
                            IF(paciente.data_nascimento IS NOT NULL,
                               CONCAT(' · ', TIMESTAMPDIFF(YEAR, paciente.data_nascimento, CURDATE()), ' anos'), '')) as produto"),
                        'episodio.estado',
                        'episodio.urgente'
                    )
                    ->where('episodio.triagem_user_id', $user->id)
                    ->orderByDesc('episodio.id')
                    ->limit(8)
                    ->get();
                $historicoTitulo = 'Minhas Últimas Triagens';
                break;
        }

        return view('sistema.perfil', compact(
            'user', 'departamento', 'perfil',
            'stats', 'historico', 'historicoTitulo'
        ));
    }

    public function updateNome(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ], ['name.required' => 'O nome é obrigatório.']);

        Auth::user()->update(['name' => $request->name]);

        return redirect()->route('perfil.index')->with('success', 'Nome actualizado com sucesso.');
    }

    public function updateSenha(Request $request)
    {
        $request->validate([
            'senha_actual' => 'required',
            'nova_senha'   => 'required|string|min:6|confirmed',
        ], [
            'senha_actual.required' => 'Insira a senha actual.',
            'nova_senha.required'   => 'Insira a nova senha.',
            'nova_senha.min'        => 'A nova senha deve ter pelo menos 6 caracteres.',
            'nova_senha.confirmed'  => 'As senhas não coincidem.',
        ]);

        if (!Hash::check($request->senha_actual, Auth::user()->password)) {
            return redirect()->route('perfil.index')
                ->with('error_senha', 'A senha actual está incorrecta.')
                ->withFragment('seguranca');
        }

        Auth::user()->update(['password' => Hash::make($request->nova_senha)]);

        return redirect()->route('perfil.index')
            ->with('success_senha', 'Senha alterada com sucesso.')
            ->withFragment('seguranca');
    }
}
