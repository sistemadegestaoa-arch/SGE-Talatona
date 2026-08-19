@include('sistema.pdf._cabecalho')

<div class="cab" style="border:none;padding:0;margin-bottom:14px;">
    <span class="tipo-rel">ATENDIMENTOS POR FUNCIONARIO</span>
</div>

<div class="meta-box">
    <table>
        <tr>
            <td><strong>Periodo:</strong> {{ \Carbon\Carbon::parse($dataInicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($dataFim)->format('d/m/Y') }}</td>
            <td><strong>Gerado em:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y \a\s H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Funcionario:</strong> {{ $funcionario }}</td>
            <td><strong>Total de registos:</strong> {{ $dados->count() }}</td>
        </tr>
    </table>
</div>

<div class="totais-wrap">
    <table>
        <tr>
            <td><div class="tot-box"><div class="tot-num">{{ $dados->count() }}</div><div class="tot-lbl">Total</div></div></td>
            <td><div class="tot-box tot-conc"><div class="tot-num">{{ $dados->where('estado','concluido')->count() }}</div><div class="tot-lbl">Concluidos</div></div></td>
            <td><div class="tot-box tot-pend"><div class="tot-num">{{ $dados->where('estado','em_espera')->count() }}</div><div class="tot-lbl">Em Espera</div></div></td>
            <td><div class="tot-box tot-cur"><div class="tot-num">{{ $dados->whereIn('estado',['em_consulta','aguarda_exame'])->count() }}</div><div class="tot-lbl">Em Curso</div></div></td>
            <td><div class="tot-box"><div class="tot-num">{{ $dados->where('sexo','M')->count() }}/{{ $dados->where('sexo','F')->count() }}</div><div class="tot-lbl">M / F</div></div></td>
        </tr>
    </table>
</div>

<div class="sec-titulo">Listagem Detalhada por Funcionario</div>

<table class="dados">
    <thead>
        <tr>
            <th style="width:24px;">#</th>
            <th>Data</th>
            <th>Nome do Paciente</th>
            <th>Sexo</th>
            <th>N Processo</th>
            <th>Triagem por</th>
            <th>Medico</th>
            <th>Diagnostico</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @forelse($dados as $i => $d)
        @php
            $esCls = ['concluido'=>'b-conc','em_espera'=>'b-esp','em_consulta'=>'b-cur','aguarda_exame'=>'b-cur'][$d->estado] ?? 'b-esp';
            $esLbl = ['concluido'=>'Concluido','em_espera'=>'Em Espera','em_consulta'=>'Em Consulta','aguarda_exame'=>'Ag. Exame'][$d->estado] ?? $d->estado;
        @endphp
        <tr>
            <td>{{ $i+1 }}</td>
            <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($d->data)->format('d/m/Y') }}</td>
            <td><strong>{{ $d->nome }}</strong></td>
            <td>{{ $d->sexo === 'M' ? 'Masc.' : 'Fem.' }}</td>
            <td style="font-family:monospace;font-size:9px;">{{ $d->numero_processo ?: '--' }}</td>
            <td style="font-size:9px;">{{ $d->triador }}</td>
            <td style="font-size:9px;">{{ $d->medico ?: '--' }}</td>
            <td style="font-size:9px;">{{ $d->diagnostico ? \Illuminate\Support\Str::limit($d->diagnostico, 40) : '--' }}</td>
            <td><span class="badge {{ $esCls }}">{{ $esLbl }}</span></td>
        </tr>
        @empty
        <tr><td colspan="9" class="vazio">Nenhum registo encontrado no periodo seleccionado.</td></tr>
        @endforelse
    </tbody>
    @if($dados->isNotEmpty())
    <tfoot>
        <tr>
            <td colspan="2">TOTAL</td>
            <td colspan="5">{{ $dados->count() }} atendimentos registados</td>
            <td colspan="2">Funcionario: {{ $funcionario }}</td>
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
                <div class="assin-nome">{{ $funcionario }}</div>
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
