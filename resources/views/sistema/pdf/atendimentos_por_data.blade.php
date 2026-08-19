@include('sistema.pdf._cabecalho')

<div class="cab" style="border:none;padding:0;margin-bottom:14px;">
    <span class="tipo-rel">ATENDIMENTOS POR DATA</span>
</div>

@php
    $totalGeral = $dados->sum('total');
    $totalConc  = $dados->sum('concluidos');
    $totalM     = $dados->sum('masculino');
    $totalF     = $dados->sum('feminino');
    $mediaDia   = $dados->count() > 0 ? round($totalGeral / $dados->count(), 1) : 0;
@endphp

<div class="meta-box">
    <table>
        <tr>
            <td><strong>Periodo:</strong> {{ \Carbon\Carbon::parse($dataInicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($dataFim)->format('d/m/Y') }}</td>
            <td><strong>Gerado em:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y \a\s H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Dias com atendimento:</strong> {{ $dados->count() }}</td>
            <td><strong>Media por dia:</strong> {{ $mediaDia }} pacientes</td>
        </tr>
    </table>
</div>

<div class="totais-wrap">
    <table>
        <tr>
            <td><div class="tot-box"><div class="tot-num">{{ $totalGeral }}</div><div class="tot-lbl">Total Atendimentos</div></div></td>
            <td><div class="tot-box tot-conc"><div class="tot-num">{{ $totalConc }}</div><div class="tot-lbl">Concluidos</div></div></td>
            <td><div class="tot-box tot-pend"><div class="tot-num">{{ $dados->sum('em_espera') }}</div><div class="tot-lbl">Em Espera</div></div></td>
            <td><div class="tot-box tot-cur"><div class="tot-num">{{ $dados->sum('em_curso') }}</div><div class="tot-lbl">Em Curso</div></div></td>
            <td><div class="tot-box"><div class="tot-num">{{ $totalGeral > 0 ? round(($totalConc/$totalGeral)*100) : 0 }}%</div><div class="tot-lbl">Taxa Conclusao</div></div></td>
        </tr>
    </table>
</div>

<div class="sec-titulo">Resumo Diario de Atendimentos</div>

<table class="dados">
    <thead>
        <tr>
            <th style="width:24px;">#</th>
            <th>Data</th>
            <th>Dia da Semana</th>
            <th style="text-align:center;">Total</th>
            <th style="text-align:center;">Concluidos</th>
            <th style="text-align:center;">Em Espera</th>
            <th style="text-align:center;">Em Curso</th>
            <th style="text-align:center;">Masculino</th>
            <th style="text-align:center;">Feminino</th>
            <th style="text-align:center;">% Conc.</th>
        </tr>
    </thead>
    <tbody>
        @forelse($dados as $i => $d)
        @php $pct = $d->total > 0 ? round(($d->concluidos/$d->total)*100) : 0; @endphp
        <tr>
            <td>{{ $i+1 }}</td>
            <td style="white-space:nowrap;"><strong>{{ \Carbon\Carbon::parse($d->dia)->format('d/m/Y') }}</strong></td>
            <td>{{ \Carbon\Carbon::parse($d->dia)->isoFormat('dddd') }}</td>
            <td style="text-align:center;font-weight:700;">{{ $d->total }}</td>
            <td style="text-align:center;color:#065f46;">{{ $d->concluidos }}</td>
            <td style="text-align:center;color:#92400e;">{{ $d->em_espera }}</td>
            <td style="text-align:center;color:#1d4ed8;">{{ $d->em_curso }}</td>
            <td style="text-align:center;">{{ $d->masculino }}</td>
            <td style="text-align:center;">{{ $d->feminino }}</td>
            <td style="text-align:center;">
                <span class="badge {{ $pct>=80 ? 'b-conc' : ($pct>=50 ? 'b-cur' : 'b-urg') }}">{{ $pct }}%</span>
            </td>
        </tr>
        @empty
        <tr><td colspan="10" class="vazio">Nenhum registo encontrado no periodo seleccionado.</td></tr>
        @endforelse
    </tbody>
    @if($dados->isNotEmpty())
    <tfoot>
        <tr>
            <td colspan="3"><strong>TOTAIS GERAIS</strong></td>
            <td style="text-align:center;"><strong>{{ $totalGeral }}</strong></td>
            <td style="text-align:center;color:#065f46;"><strong>{{ $totalConc }}</strong></td>
            <td style="text-align:center;color:#92400e;"><strong>{{ $dados->sum('em_espera') }}</strong></td>
            <td style="text-align:center;color:#1d4ed8;"><strong>{{ $dados->sum('em_curso') }}</strong></td>
            <td style="text-align:center;"><strong>{{ $totalM }}</strong></td>
            <td style="text-align:center;"><strong>{{ $totalF }}</strong></td>
            <td style="text-align:center;"><strong>{{ $totalGeral > 0 ? round(($totalConc/$totalGeral)*100) : 0 }}%</strong></td>
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
                <div class="assin-nome">Responsavel de Triagem</div>
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
