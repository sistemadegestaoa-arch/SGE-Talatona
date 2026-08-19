<?php

namespace App\Http\Controllers;

use App\Consulta;
use App\Episodio;
use App\Prescricao;
use App\PrescricaoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class PrescricaoController extends Controller
{
    // ── Guardar / actualizar prescrição ───────────────────────────────────────
    public function store(Request $request, $episodio_id)
    {
        $request->validate([
            'medicamentos'                    => 'required|array|min:1',
            'medicamentos.*.medicamento'      => 'required|string|max:255',
            'medicamentos.*.quantidade'       => 'required|integer|min:1',
        ], [
            'medicamentos.required'                => 'Adicione pelo menos um medicamento.',
            'medicamentos.*.medicamento.required'  => 'O nome do medicamento é obrigatório.',
            'medicamentos.*.quantidade.min'        => 'Quantidade mínima é 1.',
        ]);

        // Garante consulta
        $consulta = Consulta::firstOrCreate(
            ['episodio_id' => $episodio_id],
            ['medico_id'   => auth()->id(), 'data' => today()]
        );

        // Cria ou actualiza a prescrição (uma por consulta)
        $prescricao = Prescricao::updateOrCreate(
            ['consulta_id' => $consulta->id],
            [
                'medico_id'   => auth()->id(),
                'diagnostico' => $request->diagnostico,
                'observacao'  => $request->observacao,
                'data'        => today(),
            ]
        );

        // Reconstrói os itens
        PrescricaoItem::where('prescricao_id', $prescricao->id)->delete();

        foreach ($request->medicamentos as $med) {
            PrescricaoItem::create([
                'prescricao_id'      => $prescricao->id,
                'medicamento'        => $med['medicamento'],
                'forma_farmaceutica' => $med['forma_farmaceutica']  ?? null,
                'dosagem'            => $med['dosagem']             ?? null,
                'dose'               => $med['dose']                ?? null,
                'frequencia'         => $med['frequencia']          ?? null,
                'duracao'            => $med['duracao']             ?? null,
                'quantidade'         => $med['quantidade'],
                'instrucoes'         => $med['instrucoes']          ?? null,
            ]);
        }

        return redirect()->route('consultas.show', $episodio_id)
            ->with('success', 'Prescrição guardada com sucesso.');
    }

    // ── PDF da prescrição ─────────────────────────────────────────────────────
    public function pdf($episodio_id)
    {
        $consulta = Consulta::where('episodio_id', $episodio_id)->firstOrFail();

        $prescricao = Prescricao::with(['itens', 'medico'])
            ->where('consulta_id', $consulta->id)
            ->firstOrFail();

        $paciente = DB::table('episodio')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->where('episodio.id', $episodio_id)
            ->select(
                'paciente.nome', 'paciente.sexo',
                'paciente.data_nascimento', 'paciente.numero_processo',
                'paciente.telefone', 'paciente.morada',
                'episodio.data as ep_data'
            )
            ->first();

        // Dados do médico (departamento)
        $dep = DB::table('departamento')
            ->join('users', 'departamento.id', '=', 'users.departamento_id')
            ->where('users.id', $prescricao->medico_id)
            ->value('departamento.departamento');

        $pdf = PDF::loadView('sistema.pdf.prescricao', compact('prescricao', 'paciente', 'dep'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Prescricao-' . str_pad($prescricao->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }
}
