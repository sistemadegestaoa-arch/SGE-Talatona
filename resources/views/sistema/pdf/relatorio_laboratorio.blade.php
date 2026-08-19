@include('sistema.pdf._cabecalho')

<div class="cab" style="border:none;padding:0;margin-bottom:14px;">
    <span class="tipo-rel">RELATORIO DO LABORATORIO</span>
</div>

<div class="meta-box">
    <table>
        <tr>
            <td><strong>Periodo:</strong> {{ \Carbon\Carbon::parse($dataInicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($dataFim)->format('d/m/Y') }}</td>
            <td><strong>Gerado em:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y \a\s H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Total de pedidos:</strong> {{ $total }}</td>
            <td><strong>Taxa de conclusao:</strong> {{ $total > 0 ? round(($concluidos/$total)*100) : 0 }}%</td>
        </tr>
    </table>
</div>

<div class="totais-wrap">
    <table>
        <tr>
            <td><div class="tot-box"><div class="tot-num">{{ $total }}</div><div class="tot-lbl">Total Pedidos</div></div></td>
            <td><div class="tot-box tot-conc"><div class="tot-num">{{ $concluidos }}</div><div class="tot-lbl">Concluidos</div></div></td>
            <td><div class="tot-box tot-pend"><div class="tot-num">{{ $pendentes }}</div><div class="tot-lbl">Pendentes</div></div></td>
            <td><div class="tot-box tot-urg"><div class="tot-num">{{ $urgentes }}</div><div class="tot-lbl">Urgentes</div></div></td>
            <td><div class="tot-box"><div class="tot-num">{{ $total > 0 ? round(($concluidos/$total)*100) : 0 }}%</div><div class="tot-lbl">Taxa Conclusao</div></div></td>
        </tr>
    </table>
</div>

<div class="sec-titulo">Pedidos de Exame no Periodo</div>

<table class="dados">
    <thead>
        <tr>
            <th style="width:24px;">#</th>
            <th>Data Pedido</th>
            <th>Paciente</th>
            <th>Sexo</th>
            <th>N Processo</th>
            <th>Exame Solicitado</th>
            <th>Medico Solicitante</th>
            <th>Tecnico</th>
            <th>Data Resultado</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pedidos as $i => $p)
        <tr class="{{ $p->urgente ? 'row-urg' : '' }}">
            <td>{{ $i+1 }}</td>
            <td style="white-space:nowrap;font-size:9px;">{{ \Carbon\Carbon::parse($p->data_pedido)->format('d/m/Y H:i') }}</td>
            <td><strong>{{ $p->nome }}</strong></td>
            <td>{{ $p->sexo === 'M' ? 'M' : 'F' }}</td>
            <td style="font-family:monospace;font-size:9px;">{{ $p->numero_processo ?: '--' }}</td>
            <td style="font-size:9px;">
                {{ \Illuminate\Support\Str::limit($p->descricao_exame, 35) }}
                @if($p->urgente)
                    <span class="badge b-urg">URGENTE</span>
                @endif
            </td>
            <td style="font-size:9px;">{{ $p->medico }}</td>
            <td style="font-size:9px;">{{ $p->tecnico ?: '--' }}</td>
            <td style="white-space:nowrap;font-size:9px;">
                {{ $p->data_resultado ? \Carbon\Carbon::parse($p->data_resultado)->format('d/m/Y H:i') : '--' }}
            </td>
            <td>
                <span class="badge {{ $p->estado === 'concluido' ? 'b-conc' : 'b-pend' }}">
                    {{ $p->estado === 'concluido' ? 'Concluido' : 'Pendente' }}
                </span>
            </td>
        </tr>
        @empty
        <tr><td colspan="10" class="vazio">Nenhum pedido encontrado no periodo seleccionado.</td></tr>
        @endforelse
    </tbody>
    @if($pedidos->isNotEmpty())
    <tfoot>
        <tr>
            <td colspan="2">TOTAL</td>
            <td colspan="5">{{ $total }} pedidos &bull; {{ $urgentes }} urgentes</td>
            <td colspan="3">{{ $concluidos }} concluidos &bull; {{ $pendentes }} pendentes</td>
        </tr>
    </tfoot>
    @endif
</table>

<div class="assinatura-wrap">
    <table>
        <tr>
            <td>
                <div class="assin-linha"></div>
                <div class="assin-nome">Director Clinico</div>
                <div class="assin-cargo">Visto e aprovado</div>
            </td>
            <td>
                <div class="assin-linha"></div>
                <div class="assin-nome">Responsavel de Laboratorio</div>
                <div class="assin-cargo">Elaborado por</div>
            </td>
            <td>
                <div class="assin-linha"></div>
                <div class="assin-nome">Administracao</div>
                <div class="assin-cargo">Tomou conhecimento</div>
            </td>
        </tr>
    </table>
</div>

<div class="rodape">
    SANKWEVA, SU LDA &bull; Relatorio gerado automaticamente &bull; Sistema de Gestao KIFICA &bull; {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
</div>
</body>
</html>
