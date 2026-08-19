<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ChamadaController extends Controller
{
    /**
     * Painel público de chamadas — ecrã da sala de espera.
     * Não requer autenticação.
     */
    public function painel()
    {
        return view('painel.chamada');
    }

    /**
     * SSE — envia eventos de chamada em tempo real para o painel.
     * Monitoriza a coluna chamado_em: quando muda num episódio,
     * emite o nome do paciente e a senha.
     */
    public function stream()
    {
        $response = response()->stream(function () {
            while (ob_get_level() > 0) ob_end_flush();
            ob_implicit_flush(true);
            set_time_limit(0);
            ini_set('output_buffering', 'off');
            ini_set('zlib.output_compression', false);

            $ultimoChamadoEm = null;   // timestamp da última chamada vista
            $iteracoes        = 0;
            $maxIteracoes     = 720;   // ~1 hora (a 5s)

            while ($iteracoes < $maxIteracoes) {
                if (connection_aborted()) break;

                // Busca o episódio chamado mais recentemente hoje
                // APENAS em estado em_espera ou em_consulta — nunca aguarda_exame ou concluido
                $ultimo = DB::table('episodio')
                    ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
                    ->whereDate('episodio.data', today())
                    ->whereNotNull('episodio.chamado_em')
                    ->whereIn('episodio.estado', ['em_espera', 'em_consulta'])
                    ->orderByDesc('episodio.chamado_em')
                    ->select(
                        'episodio.id',
                        'episodio.senha',
                        'episodio.urgente',
                        'episodio.estado',
                        'episodio.chamado_em',
                        'paciente.nome'
                    )
                    ->first();

                if ($ultimo && $ultimo->chamado_em !== $ultimoChamadoEm) {
                    $ultimoChamadoEm = $ultimo->chamado_em;

                    $json = json_encode([
                        'senha'   => $ultimo->senha,
                        'nome'    => $ultimo->nome,
                        'urgente' => (bool) $ultimo->urgente,
                        'hora'    => \Carbon\Carbon::parse($ultimo->chamado_em)->format('H:i'),
                    ]);

                    echo "event: chamada\n";
                    echo "data: {$json}\n\n";
                } else {
                    echo ": heartbeat\n\n";
                }

                flush();
                $iteracoes++;
                sleep(3);
            }

            echo "data: {\"reconectar\":true}\n\n";
            flush();

        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Connection'        => 'keep-alive',
        ]);

        return $response;
    }

    /**
     * Endpoint AJAX — retorna a fila de espera actual para o painel.
     */
    public function filaEspera()
    {
        $fila = DB::table('episodio')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->whereDate('episodio.data', today())
            ->where('episodio.estado', 'em_espera')   // só em espera — não aguarda_exame
            ->orderByDesc('episodio.urgente')
            ->orderBy('episodio.id')
            ->select('episodio.id', 'episodio.senha', 'episodio.urgente', 'paciente.nome')
            ->get();

        return response()->json($fila);
    }

    /**
     * Endpoint AJAX — médico pode re-chamar um paciente manualmente.
     * Só permitido se o episódio estiver em em_espera ou em_consulta.
     */
    public function rechamar($episodio_id)
    {
        $ep = DB::table('episodio')
            ->where('id', $episodio_id)
            ->select('estado')
            ->first();

        if (!$ep || !in_array($ep->estado, ['em_espera', 'em_consulta'])) {
            return response()->json([
                'success' => false,
                'motivo'  => 'Paciente não pode ser chamado neste estado (' . ($ep->estado ?? 'desconhecido') . ').',
            ], 422);
        }

        DB::table('episodio')
            ->where('id', $episodio_id)
            ->update(['chamado_em' => now()]);

        return response()->json(['success' => true]);
    }
}
