<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SseController extends Controller
{
    /**
     * Stream de Server-Sent Events.
     * O browser mantém esta ligação aberta e recebe actualizações em tempo real.
     */
    public function stream(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response('Unauthorized', 401);
        }

        // Determina o perfil do utilizador
        $dep = DB::table('departamento')
            ->where('id', $user->departamento_id)
            ->value('departamento') ?? '';

        $dn = mb_strtolower($dep);
        $dn = strtr($dn, [
            'á'=>'a','à'=>'a','â'=>'a','ã'=>'a',
            'é'=>'e','è'=>'e','ê'=>'e',
            'í'=>'i','ó'=>'o','ô'=>'o','õ'=>'o',
            'ú'=>'u','ç'=>'c',
        ]);

        $perfil = 'outro';
        if (str_contains($dn,'catalogac') || str_contains($dn,'consultas') || str_contains($dn,'triag') || str_contains($dn,'c.p.n') || str_contains($dn,'cpn')) $perfil = 'triagem';
        elseif (str_contains($dn,'s.o') || str_contains($dn,'observa') || str_contains($dn,'enferm') || str_contains($dn,'p.a.v') || str_contains($dn,'pav') || str_contains($dn,'s.a.t')) $perfil = 'enfermeiro';
        elseif (str_contains($dn,'lab') || str_contains($dn,'raio') || str_contains($dn,'hemot') || str_contains($dn,'cada'))  $perfil = 'laboratorio';
        elseif (str_contains($dn,'farm')) $perfil = 'farmacia';
        elseif (
            str_contains($dn,'banco') || str_contains($dn,'medic') || str_contains($dn,'pediatr') ||
            str_contains($dn,'intern') || str_contains($dn,'cirurg') || str_contains($dn,'puerp') ||
            str_contains($dn,'neonat') || str_contains($dn,'oftalm') || str_contains($dn,'fisiot') ||
            str_contains($dn,'nutric') || str_contains($dn,'odont') || str_contains($dn,'tisiolog') ||
            str_contains($dn,'gesso') || str_contains($dn,'estreliz')
        ) $perfil = 'medico';
        elseif ($user->tipo === 'admin')  $perfil = 'admin';

        $response = response()->stream(function () use ($user, $perfil) {
            // Desliga TODOS os buffers de output (crítico no XAMPP)
            while (ob_get_level() > 0) {
                ob_end_flush();
            }
            ob_implicit_flush(true);

            // Aumenta o tempo máximo de execução para a ligação SSE
            set_time_limit(0);
            ini_set('output_buffering', 'off');
            ini_set('zlib.output_compression', false);

            $estadoAnterior = [];
            $iteracoes = 0;
            $maxIteracoes = 120; // ~10 minutos (a 5s por iteração)

            while ($iteracoes < $maxIteracoes) {
                // Verifica se o cliente desligou
                if (connection_aborted()) break;

                $dados = $this->obterContadores($user->id, $perfil);

                // Detecta mudanças em relação ao estado anterior
                $notificacoes = [];
                foreach ($dados as $tipo => $info) {
                    $anterior = $estadoAnterior[$tipo] ?? null;

                    // Na primeira iteração, apenas inicializa sem notificar
                    if ($anterior === null) {
                        $estadoAnterior[$tipo] = $info['total'];
                        continue;
                    }

                    // Notifica apenas quando o número AUMENTA e o total é > 0
                    if ($info['total'] > $anterior && $info['total'] > 0) {
                        $notificacoes[] = $info;
                    }
                    $estadoAnterior[$tipo] = $info['total'];
                }

                // Envia evento SSE
                if (!empty($notificacoes)) {
                    $json = json_encode([
                        'notificacoes' => $notificacoes,
                        'total'        => array_sum(array_column($notificacoes, 'total')),
                    ]);
                    echo "data: {$json}\n\n";
                } else {
                    // Heartbeat — mantém a ligação viva
                    echo ": heartbeat\n\n";
                }

                flush();

                $iteracoes++;
                sleep(5); // Verifica a cada 5 segundos
            }

            // Sinal para o browser reconectar após expirar
            echo "data: {\"reconectar\":true}\n\n";
            flush();

        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache',
            'X-Accel-Buffering' => 'no',  // desliga buffering no Nginx
            'Connection'        => 'keep-alive',
        ]);

        return $response;
    }

    /**
     * Retorna os contadores actuais por perfil.
     * IMPORTANTE: sempre inclui todos os tipos com total >= 0
     * para que o loop de detecção de mudanças funcione correctamente,
     * mesmo quando o valor está a 0 (caso de primeira receita/paciente).
     */
    private function obterContadores($userId, $perfil): array
    {
        $dados = [];

        switch ($perfil) {

            case 'medico':
                $resultados = DB::table('pedido_exame')
                    ->join('consulta','consulta.id','=','pedido_exame.consulta_id')
                    ->join('episodio','episodio.id','=','consulta.episodio_id')
                    ->where('pedido_exame.estado','concluido')
                    ->where('consulta.medico_id', $userId)
                    ->whereIn('episodio.estado',['em_consulta','aguarda_exame'])
                    ->count();

                $espera = DB::table('episodio')
                    ->whereDate('data', today())
                    ->where('estado','em_espera')
                    ->count();

                $urgentes = DB::table('episodio')
                    ->whereDate('data', today())
                    ->where('estado','em_espera')
                    ->where('urgente', 1)
                    ->count();

                $dados['resultado_exame'] = [
                    'tipo'    => 'resultado_exame',
                    'total'   => $resultados,
                    'texto'   => $resultados === 1 ? '1 resultado de exame disponível' : "$resultados resultados de exame disponíveis",
                    'url'     => url('consultas'),
                    'urgente' => false,
                    'icone'   => '🔬',
                ];
                $dados['paciente_espera'] = [
                    'tipo'    => 'paciente_espera',
                    'total'   => $espera,
                    'texto'   => $espera === 1 ? '1 paciente aguarda consulta' : "$espera pacientes aguardam consulta",
                    'url'     => url('consultas'),
                    'urgente' => false,
                    'icone'   => '🩺',
                ];
                $dados['paciente_urgente'] = [
                    'tipo'    => 'paciente_urgente',
                    'total'   => $urgentes,
                    'texto'   => $urgentes === 1 ? '⚡ 1 consulta URGENTE em espera' : "⚡ $urgentes consultas URGENTES em espera",
                    'url'     => url('consultas'),
                    'urgente' => true,
                    'icone'   => '⚡',
                ];
                break;

            case 'laboratorio':
                $urgentes  = DB::table('pedido_exame')->where('estado','pendente')->where('urgente',1)->count();
                $pendentes = DB::table('pedido_exame')->where('estado','pendente')->count();
                $normais   = $pendentes - $urgentes;

                $dados['exame_urgente'] = [
                    'tipo'    => 'exame_urgente',
                    'total'   => $urgentes,
                    'texto'   => "⚡ $urgentes pedido(s) URGENTE(S)",
                    'url'     => url('laboratorio'),
                    'urgente' => true,
                    'icone'   => '⚡',
                ];
                $dados['exame_pendente'] = [
                    'tipo'    => 'exame_pendente',
                    'total'   => $normais,
                    'texto'   => "$normais pedido(s) de exame pendente(s)",
                    'url'     => url('laboratorio'),
                    'urgente' => false,
                    'icone'   => '🔬',
                ];
                break;

            case 'farmacia':
                $receitas = DB::table('receita')->where('estado','pendente')->count();
                $reqFarmaco = DB::table('requisicao_farmaco')->where('estado','pendente')->count();

                $dados['receita_pendente'] = [
                    'tipo'    => 'receita_pendente',
                    'total'   => $receitas,
                    'texto'   => $receitas === 1 ? '1 receita médica pendente' : "$receitas receitas médicas pendentes",
                    'url'     => url('receitas-pendentes'),
                    'urgente' => false,
                    'icone'   => '💊',
                ];
                $dados['requisicao_farmaco'] = [
                    'tipo'    => 'requisicao_farmaco',
                    'total'   => $reqFarmaco,
                    'texto'   => $reqFarmaco === 1 ? '1 requisição de fármaco pendente' : "$reqFarmaco requisições de fármacos pendentes",
                    'url'     => url('requisicao-farmaco-farmacia'),
                    'urgente' => false,
                    'icone'   => '🧪',
                ];
                break;

            case 'enfermeiro':
                $prescHoje = DB::table('prescricao')
                    ->join('consulta','consulta.id','=','prescricao.consulta_id')
                    ->join('episodio','episodio.id','=','consulta.episodio_id')
                    ->whereDate('episodio.data', today())
                    ->count();

                $prescUrgentes = DB::table('prescricao')
                    ->join('consulta','consulta.id','=','prescricao.consulta_id')
                    ->join('episodio','episodio.id','=','consulta.episodio_id')
                    ->whereDate('episodio.data', today())
                    ->where('episodio.urgente', 1)
                    ->count();

                // Requisições do departamento do utilizador
                $reqEnfPend = DB::table('requisicao_farmaco')
                    ->where('departamento_id', DB::table('users')->where('id',$userId)->value('departamento_id'))
                    ->where('estado','pendente')
                    ->count();

                $dados['prescricao_hoje'] = [
                    'tipo'    => 'prescricao_hoje',
                    'total'   => $prescHoje,
                    'texto'   => "$prescHoje prescrição(ões) emitida(s) hoje",
                    'url'     => url('enfermeiro'),
                    'urgente' => false,
                    'icone'   => '📋',
                ];
                $dados['prescricao_urgente'] = [
                    'tipo'    => 'prescricao_urgente',
                    'total'   => $prescUrgentes,
                    'texto'   => "⚡ $prescUrgentes prescrição(ões) URGENTE(S)",
                    'url'     => url('enfermeiro'),
                    'urgente' => true,
                    'icone'   => '⚡',
                ];
                $dados['req_enf_pendente'] = [
                    'tipo'    => 'req_enf_pendente',
                    'total'   => $reqEnfPend,
                    'texto'   => "$reqEnfPend requisição(ões) de fármaco pendente(s)",
                    'url'     => url('enfermeiro'),
                    'urgente' => false,
                    'icone'   => '🧪',
                ];
                break;

            case 'admin':
                $req = DB::table('requisicao')->where('statos','Pendente')->count();
                $dados['requisicao'] = [
                    'tipo'    => 'requisicao',
                    'total'   => $req,
                    'texto'   => "$req requisição(ões) pendente(s)",
                    'url'     => url('verrequisicao'),
                    'urgente' => false,
                    'icone'   => '📋',
                ];
                break;
        }

        return $dados;
    }
}
