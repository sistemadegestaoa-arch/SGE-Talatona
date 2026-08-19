<?php

namespace App\Http\Controllers;

use App\Consulta;
use App\Episodio;
use App\PedidoExame;
use App\Receita;
use App\ReceitaItem;
use App\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultaController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // LISTA DE ESPERA — episódios do dia em estado "em_espera"
    // ─────────────────────────────────────────────────────────────────────────
    public function index()
    {
        $espera = DB::table('episodio')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->join('triagem',  'triagem.episodio_id', '=', 'episodio.id')
            ->select(
                'episodio.id as episodio_id',
                'episodio.estado',
                'episodio.urgente',
                'episodio.created_at',
                'paciente.id as paciente_id',
                'paciente.nome',
                'paciente.sexo',
                'paciente.data_nascimento',
                'paciente.numero_processo',
                'triagem.pressao_arterial',
                'triagem.temperatura',
                'triagem.peso',
                'triagem.observacao as obs_triagem'
            )
            ->whereDate('episodio.data', today())
            ->whereIn('episodio.estado', ['em_espera', 'em_consulta', 'aguarda_exame'])
            ->orderByDesc('episodio.urgente')
            ->orderBy('episodio.id', 'asc')
            ->get();

        $concluidos = DB::table('episodio')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->select('episodio.id as episodio_id', 'paciente.nome', 'episodio.updated_at')
            ->whereDate('episodio.data', today())
            ->where('episodio.estado', 'concluido')
            ->orderBy('episodio.updated_at', 'desc')
            ->limit(5)
            ->get();

        return view('sistema.consulta.index', compact('espera', 'concluidos'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ABRIR EPISÓDIO — médico inicia a consulta
    // ─────────────────────────────────────────────────────────────────────────
    public function show($episodio_id)
    {
        $episodio = DB::table('episodio')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->select('episodio.*', 'paciente.*',
                     'episodio.id as episodio_id', 'episodio.estado as ep_estado')
            ->where('episodio.id', $episodio_id)
            ->first();

        if (!$episodio) return redirect()->route('consultas.index');

        $triagem = DB::table('triagem')
            ->where('episodio_id', $episodio_id)->first();

        $consulta = Consulta::where('episodio_id', $episodio_id)->first();

        $pedidos = DB::table('pedido_exame')
            ->leftJoin('resultado_exame', 'resultado_exame.pedido_exame_id', '=', 'pedido_exame.id')
            ->where('pedido_exame.consulta_id', optional($consulta)->id)
            ->select('pedido_exame.*', 'resultado_exame.resultado', 'resultado_exame.ficheiro_path',
                     'resultado_exame.data_resultado', 'resultado_exame.id as resultado_id')
            ->get();

        $receita = $consulta
            ? Receita::with('itens.produto')->where('consulta_id', $consulta->id)->first()
            : null;

        $prescricao = $consulta
            ? \App\Prescricao::with('itens')->where('consulta_id', $consulta->id)->first()
            : null;

        // Produtos para receita — com info de bloqueio e stock
        $produtos = DB::table('produto')
            ->join('categoria', 'produto.categoria_id', '=', 'categoria.id')
            ->select(
                'produto.id',
                'produto.produto',
                'produto.apresentacao',
                'produto.bloqueado',
                'produto.motivo_bloqueio',
                'produto.quantidade',
                'produto.stokminimo',
                'categoria.categoria'
            )
            ->orderBy('produto.bloqueado', 'asc')  // disponíveis primeiro
            ->orderBy('produto.produto')
            ->get();

        // Marca episódio como em_consulta se ainda estiver em_espera
        // E gera a senha + regista o momento da chamada
        if ($episodio->ep_estado === 'em_espera') {
            // Gera senha sequencial do dia (A001, A002, ...)
            $ultimaSenha = DB::table('episodio')
                ->whereDate('data', today())
                ->whereNotNull('senha')
                ->orderByDesc('senha')
                ->value('senha');

            $proximo = 1;
            if ($ultimaSenha) {
                preg_match('/(\d+)$/', $ultimaSenha, $m);
                $proximo = isset($m[1]) ? ((int)$m[1] + 1) : 1;
            }
            $senha = 'A' . str_pad($proximo, 3, '0', STR_PAD_LEFT);

            Episodio::where('id', $episodio_id)->update([
                'estado'     => 'em_consulta',
                'senha'      => $senha,
                'chamado_em' => now(),
            ]);
            $episodio->ep_estado = 'em_consulta';

        } elseif ($episodio->ep_estado === 'em_consulta') {
            // Médico re-abre consulta em curso — actualiza chamada mas não muda estado
            Episodio::where('id', $episodio_id)->update(['chamado_em' => now()]);

        }
        // aguarda_exame e concluido — não alterar estado nem chamar o paciente

        return view('sistema.consulta.show', compact(
            'episodio', 'triagem', 'consulta', 'pedidos', 'receita', 'prescricao', 'produtos'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GUARDAR / ACTUALIZAR DIAGNÓSTICO
    // ─────────────────────────────────────────────────────────────────────────
    public function storeDiagnostico(Request $request, $episodio_id)
    {
        $request->validate([
            'diagnostico' => 'required|string',
            'observacao'  => 'nullable|string',
        ], [
            'diagnostico.required' => 'O diagnóstico é obrigatório.',
        ]);

        $consulta = Consulta::updateOrCreate(
            ['episodio_id' => $episodio_id],
            [
                'medico_id'   => auth()->id(),
                'diagnostico' => $request->diagnostico,
                'observacao'  => $request->observacao,
                'data'        => today(),
            ]
        );

        return redirect()->route('consultas.show', $episodio_id)
            ->with('success', 'Diagnóstico guardado.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PEDIR EXAME AO LABORATÓRIO
    // ─────────────────────────────────────────────────────────────────────────
    public function storePedidoExame(Request $request, $episodio_id)
    {
        $request->validate([
            'descricao_exame' => 'required|string|max:255',
            'urgente'         => 'nullable|boolean',
            'observacao'      => 'nullable|string',
        ], [
            'descricao_exame.required' => 'Descreva o exame solicitado.',
        ]);

        // Garante que existe uma consulta
        $consulta = Consulta::firstOrCreate(
            ['episodio_id' => $episodio_id],
            [
                'medico_id' => auth()->id(),
                'data'      => today(),
            ]
        );

        PedidoExame::create([
            'consulta_id'    => $consulta->id,
            'medico_id'      => auth()->id(),
            'descricao_exame'=> $request->descricao_exame,
            'urgente'        => $request->boolean('urgente'),
            'estado'         => 'pendente',
            'observacao'     => $request->observacao,
            'data_pedido'    => today(),
        ]);

        // Muda estado do episódio para aguarda_exame
        Episodio::where('id', $episodio_id)
            ->update(['estado' => 'aguarda_exame']);

        return redirect()->route('consultas.show', $episodio_id)
            ->with('success', 'Pedido de exame enviado ao laboratório.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // CRIAR RECEITA MÉDICA
    // ─────────────────────────────────────────────────────────────────────────
    public function storeReceita(Request $request, $episodio_id)
    {
        $request->validate([
            'produto_id'   => 'required|array|min:1',
            'produto_id.*' => 'required|integer|exists:produto,id',
            'quantidade'   => 'required|array',
            'quantidade.*' => 'required|integer|min:1',
            'dose'         => 'nullable|array',
            'frequencia'   => 'nullable|array',
            'duracao'      => 'nullable|array',
            'observacao'   => 'nullable|string',
        ], [
            'produto_id.min'   => 'Adicione pelo menos um medicamento.',
            'quantidade.*.min' => 'A quantidade deve ser maior que zero.',
        ]);

        // ── Validação: fármacos bloqueados ────────────────────────────────
        $bloqueados = \App\Helpers\FarmacoHelper::verificarBloqueados($request->produto_id);
        if (!empty($bloqueados)) {
            return redirect()->back()
                ->withInput()
                ->with('error', \App\Helpers\FarmacoHelper::msgBloqueados($bloqueados));
        }

        // ── Validação: stock baixo / mínimo ───────────────────────────────
        $itensParaCheck = array_map(fn($pid, $qty) => ['produto_id' => $pid, 'quantidade' => $qty],
            $request->produto_id, $request->quantidade);

        $stockBaixo = \App\Helpers\FarmacoHelper::verificarStockBaixo(
            $itensParaCheck,
            auth()->user()->departamento_id
        );
        if (!empty($stockBaixo)) {
            return redirect()->back()
                ->withInput()
                ->with('error', \App\Helpers\FarmacoHelper::msgStockBaixo($stockBaixo));
        }

        // Garante que existe uma consulta
        $consulta = Consulta::firstOrCreate(
            ['episodio_id' => $episodio_id],
            ['medico_id' => auth()->id(), 'data' => today()]
        );

        DB::transaction(function () use ($request, $consulta, $episodio_id) {
            $receita = Receita::updateOrCreate(
                ['consulta_id' => $consulta->id],
                [
                    'medico_id'  => auth()->id(),
                    'estado'     => 'pendente',
                    'observacao' => $request->observacao,
                    'data'       => today(),
                ]
            );

            ReceitaItem::where('receita_id', $receita->id)->delete();

            foreach ($request->produto_id as $i => $pid) {
                ReceitaItem::create([
                    'receita_id'  => $receita->id,
                    'produto_id'  => $pid,
                    'quantidade'  => $request->quantidade[$i],
                    'dose'        => $request->dose[$i]       ?? null,
                    'frequencia'  => $request->frequencia[$i] ?? null,
                    'duracao'     => $request->duracao[$i]    ?? null,
                ]);
            }

            Episodio::where('id', $episodio_id)->update(['estado' => 'concluido']);
        });

        return redirect()->route('consultas.show', $episodio_id)
            ->with('success', 'Receita enviada à farmácia. Episódio concluído.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // CONCLUIR EPISÓDIO SEM RECEITA
    // ─────────────────────────────────────────────────────────────────────────
    public function concluir($episodio_id)
    {
        Episodio::where('id', $episodio_id)->update(['estado' => 'concluido']);

        return redirect()->route('consultas.index')
            ->with('success', 'Episódio concluído.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PDF DA RECEITA — visualizar antes de imprimir
    // ─────────────────────────────────────────────────────────────────────────
    public function receitaPdf($episodio_id)
    {
        $consulta = Consulta::where('episodio_id', $episodio_id)->first();
        if (!$consulta) abort(404);

        $receita = DB::table('receita')
            ->join('consulta', 'consulta.id', '=', 'receita.consulta_id')
            ->join('episodio', 'episodio.id', '=', 'consulta.episodio_id')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->join('users',    'users.id',    '=', 'receita.medico_id')
            ->select(
                'receita.*',
                'paciente.nome', 'paciente.sexo', 'paciente.data_nascimento',
                'paciente.numero_processo',
                'users.name as medico',
                'consulta.diagnostico'
            )
            ->where('receita.consulta_id', $consulta->id)
            ->first();

        if (!$receita) abort(404);

        $itens = DB::table('receita_item')
            ->join('produto', 'produto.id', '=', 'receita_item.produto_id')
            ->select('receita_item.*', 'produto.produto', 'produto.apresentacao')
            ->where('receita_item.receita_id', $receita->id)
            ->get();

        $pdf = \PDF::loadView('sistema.receitas.pdf', compact('receita', 'itens'));
        return $pdf->stream('Receita-' . str_pad($receita->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PDF DO PEDIDO DE EXAME — visualizar antes de imprimir
    // ─────────────────────────────────────────────────────────────────────────
    public function pedidoExamePdf($episodio_id)
    {
        $episodio = DB::table('episodio')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->select('episodio.*', 'paciente.*',
                     'episodio.id as episodio_id', 'episodio.data as ep_data')
            ->where('episodio.id', $episodio_id)
            ->first();

        if (!$episodio) abort(404);

        $consulta = Consulta::where('episodio_id', $episodio_id)->first();

        $pedidos = $consulta
            ? DB::table('pedido_exame')
                ->join('users', 'users.id', '=', 'pedido_exame.medico_id')
                ->select('pedido_exame.*', 'users.name as medico')
                ->where('pedido_exame.consulta_id', $consulta->id)
                ->get()
            : collect();

        $pdf = \PDF::loadView('sistema.consulta.pedido_exame_pdf', compact('episodio', 'pedidos', 'consulta'));
        return $pdf->stream('PedidoExame-Ep' . $episodio_id . '.pdf');
    }
}
