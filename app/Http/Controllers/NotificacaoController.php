<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NotificacaoController extends Controller
{
    /**
     * Retorna os contadores de notificações para o utilizador autenticado.
     * Chamado via AJAX a cada 30 segundos pelo frontend.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) return response()->json(['total' => 0, 'items' => []]);

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

        $items    = [];
        $total    = 0;
        $urgente  = false;

        // ── ENFERMEIRO / S.O. ─────────────────────────────────────────────────
        if (
            str_contains($dn,'s.o') || str_contains($dn,'observa') ||
            str_contains($dn,'enferm') || str_contains($dn,'p.a.v') ||
            str_contains($dn,'pav') || str_contains($dn,'s.a.t')
        ) {
            $prescUrgentes = DB::table('prescricao')
                ->join('consulta','consulta.id','=','prescricao.consulta_id')
                ->join('episodio','episodio.id','=','consulta.episodio_id')
                ->whereDate('episodio.data', today())
                ->where('episodio.urgente', 1)
                ->count();

            $prescHoje = DB::table('prescricao')
                ->join('consulta','consulta.id','=','prescricao.consulta_id')
                ->join('episodio','episodio.id','=','consulta.episodio_id')
                ->whereDate('episodio.data', today())
                ->count();

            if ($prescUrgentes > 0) {
                $items[] = [
                    'tipo'    => 'prescricao_urgente',
                    'total'   => $prescUrgentes,
                    'texto'   => "⚡ $prescUrgentes prescrição(ões) URGENTE(S) hoje",
                    'url'     => url('enfermeiro'),
                    'urgente' => true,
                    'icone'   => '⚡',
                ];
                $urgente = true;
                $total  += $prescUrgentes;
            }

            if ($prescHoje > 0) {
                $items[] = [
                    'tipo'    => 'prescricao_hoje',
                    'total'   => $prescHoje,
                    'texto'   => "$prescHoje prescrição(ões) emitida(s) hoje",
                    'url'     => url('enfermeiro'),
                    'urgente' => false,
                    'icone'   => '📋',
                ];
                $total += $prescHoje;
            }
        }

        // ── MÉDICO — todos os bancos e especialidades clínicas ───────────────
        elseif (
            str_contains($dn,'banco') || str_contains($dn,'medic') ||
            str_contains($dn,'pediatr') || str_contains($dn,'intern') ||
            str_contains($dn,'cirurg') || str_contains($dn,'puerp') ||
            str_contains($dn,'neonat') || str_contains($dn,'oftalm') ||
            str_contains($dn,'fisiot') || str_contains($dn,'nutric') ||
            str_contains($dn,'odont') || str_contains($dn,'tisiolog') ||
            str_contains($dn,'gesso') || str_contains($dn,'estreliz')
        ) {
            $resultados = DB::table('pedido_exame')
                ->join('consulta', 'consulta.id', '=', 'pedido_exame.consulta_id')
                ->join('episodio', 'episodio.id', '=', 'consulta.episodio_id')
                ->where('pedido_exame.estado', 'concluido')
                ->where('consulta.medico_id', $user->id)
                ->whereIn('episodio.estado', ['em_consulta', 'aguarda_exame'])
                ->count();

            if ($resultados > 0) {
                $items[] = [
                    'tipo'    => 'resultado_exame',
                    'total'   => $resultados,
                    'texto'   => $resultados === 1
                        ? '1 resultado de exame disponível'
                        : "$resultados resultados de exame disponíveis",
                    'url'     => url('consultas'),
                    'urgente' => false,
                    'icone'   => '🔬',
                ];
                $total += $resultados;
            }

            $urgentesEspera = DB::table('episodio')
                ->whereDate('data', today())
                ->where('estado', 'em_espera')
                ->where('urgente', 1)
                ->count();

            if ($urgentesEspera > 0) {
                $items[] = [
                    'tipo'    => 'paciente_urgente',
                    'total'   => $urgentesEspera,
                    'texto'   => $urgentesEspera === 1
                        ? '⚡ 1 consulta URGENTE em espera'
                        : "⚡ $urgentesEspera consultas URGENTES em espera",
                    'url'     => url('consultas'),
                    'urgente' => true,
                    'icone'   => '⚡',
                ];
                $total += $urgentesEspera;
                $urgente = true;
            }

            $espera = DB::table('episodio')
                ->whereDate('data', today())
                ->where('estado', 'em_espera')
                ->count();

            if ($espera > 0) {
                $items[] = [
                    'tipo'    => 'paciente_espera',
                    'total'   => $espera,
                    'texto'   => $espera === 1
                        ? '1 paciente aguarda consulta'
                        : "$espera pacientes aguardam consulta",
                    'url'     => url('consultas'),
                    'urgente' => false,
                    'icone'   => '🩺',
                ];
                $total += $espera;
            }
        }

        // ── LABORATÓRIO / RAIO X / HEMOTERAPIA ───────────────────────────────
        elseif (str_contains($dn, 'lab') || str_contains($dn, 'raio') || str_contains($dn, 'hemot') || str_contains($dn, 'cada')) {
            $pendentes = DB::table('pedido_exame')
                ->where('estado', 'pendente')
                ->count();

            $urgentes = DB::table('pedido_exame')
                ->where('estado', 'pendente')
                ->where('urgente', 1)
                ->count();

            if ($urgentes > 0) {
                $items[] = [
                    'tipo'    => 'exame_urgente',
                    'total'   => $urgentes,
                    'texto'   => $urgentes === 1
                        ? '⚡ 1 pedido URGENTE pendente'
                        : "⚡ $urgentes pedidos URGENTES pendentes",
                    'url'     => url('laboratorio'),
                    'urgente' => true,
                    'icone'   => '⚡',
                ];
                $urgente = true;
                $total  += $urgentes;
            }

            if ($pendentes > $urgentes) {
                $normais = $pendentes - $urgentes;
                $items[] = [
                    'tipo'    => 'exame_pendente',
                    'total'   => $normais,
                    'texto'   => "$normais pedido(s) de exame pendente(s)",
                    'url'     => url('laboratorio'),
                    'urgente' => false,
                    'icone'   => '🔬',
                ];
                $total += $normais;
            }
        }

        // ── FARMÁCIA ──────────────────────────────────────────────────────────
        elseif (str_contains($dn, 'farm')) {
            $receitas = DB::table('receita')
                ->where('estado', 'pendente')
                ->count();

            if ($receitas > 0) {
                $items[] = [
                    'tipo'    => 'receita_pendente',
                    'total'   => $receitas,
                    'texto'   => $receitas === 1
                        ? '1 receita médica pendente'
                        : "$receitas receitas médicas pendentes",
                    'url'     => url('receitas-pendentes'),
                    'urgente' => false,
                    'icone'   => '💊',
                ];
                $total += $receitas;
            }

            $reqFarmaco = DB::table('requisicao_farmaco')
                ->where('estado', 'pendente')
                ->count();

            if ($reqFarmaco > 0) {
                $items[] = [
                    'tipo'    => 'requisicao_farmaco',
                    'total'   => $reqFarmaco,
                    'texto'   => $reqFarmaco === 1
                        ? '1 requisição de fármaco pendente'
                        : "$reqFarmaco requisições de fármacos pendentes",
                    'url'     => url('requisicao-farmaco-farmacia'),
                    'urgente' => false,
                    'icone'   => '🧪',
                ];
                $total += $reqFarmaco;
            }
        }

        // ── ADMIN ─────────────────────────────────────────────────────────────
        elseif ($user->tipo === 'admin') {
            $requisicoes = DB::table('requisicao')
                ->where('statos', 'Pendente')
                ->count();

            if ($requisicoes > 0) {
                $items[] = [
                    'tipo'    => 'requisicao',
                    'total'   => $requisicoes,
                    'texto'   => "$requisicoes requisição(ões) pendente(s)",
                    'url'     => url('verrequisicao'),
                    'urgente' => false,
                    'icone'   => '📋',
                ];
                $total += $requisicoes;
            }
        }

        return response()->json([
            'total'   => $total,
            'urgente' => $urgente,
            'items'   => $items,
        ]);
    }
}
