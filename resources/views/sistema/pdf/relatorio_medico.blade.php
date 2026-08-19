@include('sistema.pdf._cabecalho')

<div class="cab" style="border:none;padding:0;margin-bottom:14px;">
    <span class="tipo-rel">RELATORIO DO MEDICO</span>
</div>

<div class="meta-box">
    <table>
        <tr>
            <td><strong>Medico:</strong> {{ $medico->name }}</td>
            <td><strong>Gerado em:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y \a\s H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Periodo:</strong> {{ \Carbon\Carbon::parse($dataInicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($dataFim)->format('d/m/Y') }}</td>
            <td><strong>Total de consultas:</strong> {{ $consultas->count() }}</td>
        </tr>
    </table>
</div>

<div class="totais-wrap">
    <table>
        <tr>
            <td><div class="tot-box"><div class="tot-num">{{ $consultas->count() }}</div><div class="tot-lbl">Consultas</div></div></td>
            <td><div class="tot-box tot-conc"><div class="tot-num">{{ $consultas->where('estado','concluido')->count() }}</div><div class="tot-lbl">Concluidas</div></div></td>
            <td><div class="tot-box tot-cur"><div class="tot-num">{{ $exames->total ?? 0 }}</div><div class="tot-lbl">Exames Solicitados</div></div></td>
            <td><div class="tot-box tot-urg"><div class="tot-num">{{ $exames->urgentes ?? 0 }}</div><div class="tot-lbl">Exames Urgentes</div></div></td>
            <td><div class="tot-box" style="background:#fdf4ff;border-color:#e9d5ff;"><div class="tot-num" style="color:#7e22ce;">{{ $consultas->where('tem_receita','>',0)->count() }}</div><div class="tot-lbl">Receitas Emitidas</div></div></td>
        </tr>
    </table>
</div>

<div class="sec-titulo">Consultas Realizadas no Periodo</div>

<table class="dados">
    <thead>
        <tr>
            <th style="width:24px;">#</th>
            <th>Data</th>
            <th>Nome do Paciente</th>
            <th>Sexo</th>
            <th>Idade</th>
            <th>N Processo</th>
            <th>Diagnostico Clinico</th>
            <th style="text-align:center;">Receita</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @forelse($consultas as $i => $c)
        @php
            $esCls = ['concluido'=>'b-conc','em_espera'=>'b-esp','em_consulta'=>'b-cur','aguarda_exame'=>'b-cur'][$c->estado] ?? 'b-esp';
            $esLbl = ['concluido'=>'Concluido','em_espera'=>'Em Espera','em_consulta'=>'Em Consulta','aguarda_exame'=>'Ag. Exame'][$c->estado] ?? $c->estado;
            $idade = $c->data_nascimento ? \Carbon\Carbon::parse($c->data_nascimento)->age . ' anos' : '--';
        @endphp
        <tr>
            <td>{{ $i+1 }}</td>
            <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($c->data)->format('d/m/Y') }}</td>
            <td><strong>{{ $c->nome }}</strong></td>
            <td>{{ $c->sexo === 'M' ? 'Masc.' : 'Fem.' }}</td>
            <td style="white-space:nowrap;">{{ $idade }}</td>
            <td style="font-family:monospace;font-size:9px;">{{ $c->numero_processo ?: '--' }}</td>
            <td style="font-size:9px;">{{ $c->diagnostico ? \Illuminate\Support\Str::limit($c->diagnostico, 50) : '--' }}</td>
            <td style="text-align:center;">
                @if($c->tem_receita > 0)
                    <span class="badge b-conc">Sim</span>
                @else
                    <span style="color:#9ca3af;font-size:9px;">--</span>
                @endif
            </td>
            <td><span class="badge {{ $esCls }}">{{ $esLbl }}</span></td>
        </tr>
        @empty
        <tr><td colspan="9" class="vazio">Nenhuma consulta registada no periodo seleccionado.</td></tr>
        @endforelse
    </tbody>
    @if($consultas->isNotEmpty())
    <tfoot>
        <tr>
            <td colspan="2">TOTAL</td>
            <td colspan="4">{{ $consultas->count() }} consultas &bull; {{ $consultas->where('tem_receita','>',0)->count() }} com receita</td>
            <td colspan="3">Exames: {{ $exames->total ?? 0 }} ({{ $exames->urgentes ?? 0 }} urgentes)</td>
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
                <div class="assin-nome">{{ $medico->name }}</div>
                <div class="assin-cargo">Medico Responsavel</div>
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
