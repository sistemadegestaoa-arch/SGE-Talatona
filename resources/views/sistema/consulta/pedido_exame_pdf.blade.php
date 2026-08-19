<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Pedido de Exame — Ep. #{{ $episodio->episodio_id }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'DejaVu Sans',sans-serif; font-size:12px; color:#1a1a1a; padding:24px 28px; }

        .header { display:table; width:100%; border-bottom:3px solid #1a6b2f; padding-bottom:14px; margin-bottom:18px; }
        .header-left { display:table-cell; vertical-align:top; }
        .header-left h2 { font-size:16px; font-weight:bold; color:#1a6b2f; margin-bottom:2px; }
        .header-left p  { font-size:10px; color:#6b7280; }
        .header-right { display:table-cell; text-align:right; vertical-align:top; }
        .header-right h1 { font-size:20px; font-weight:bold; color:#1a6b2f; letter-spacing:1px; }
        .header-right .num { font-size:11px; color:#6b7280; margin-top:3px; }

        .info-grid { display:table; width:100%; margin-bottom:16px; border-spacing:6px; }
        .info-cell { display:table-cell; width:50%; background:#f9fafb; border:1px solid #e5e7eb; padding:10px 12px; vertical-align:top; }
        .info-cell h4 { font-size:9px; text-transform:uppercase; letter-spacing:.8px; color:#9ca3af; font-weight:bold; margin-bottom:6px; }
        .info-cell .nome { font-size:14px; font-weight:bold; margin-bottom:4px; }
        .info-cell .meta { font-size:11px; color:#6b7280; }

        .diag-box { background:#f0faf2; border:1px solid #d1fae5; padding:10px 12px; margin-bottom:16px; }
        .diag-box h4 { font-size:9px; text-transform:uppercase; color:#1a6b2f; font-weight:bold; margin-bottom:5px; }
        .diag-box p  { font-size:12px; color:#374151; line-height:1.5; }

        table.pedidos { width:100%; border-collapse:collapse; margin-bottom:20px; }
        table.pedidos thead tr { background:#1a6b2f; }
        table.pedidos th { padding:8px 10px; text-align:left; font-size:10px; font-weight:bold; text-transform:uppercase; letter-spacing:.5px; color:#fff; }
        table.pedidos tbody tr:nth-child(even) { background:#f9fafb; }
        table.pedidos td { padding:9px 10px; border-bottom:1px solid #e5e7eb; font-size:12px; vertical-align:middle; }
        .badge-urg { background:#fee2e2; color:#991b1b; padding:2px 8px; border-radius:12px; font-size:10px; font-weight:bold; }
        .badge-norm { background:#f0faf2; color:#065f46; padding:2px 8px; border-radius:12px; font-size:10px; font-weight:bold; }

        .assin-table { width:100%; border-collapse:collapse; margin-top:40px; border-top:1px solid #e5e7eb; padding-top:16px; }
        .assin-td { width:50%; text-align:center; padding:0 20px; vertical-align:bottom; }
        .assin-line { border-top:1px solid #1a1a1a; width:80%; margin:50px auto 6px; }
        .assin-nome { font-size:11px; font-weight:bold; }
        .assin-cargo { font-size:10px; color:#6b7280; margin-top:2px; }

        .footer { text-align:center; font-size:9px; color:#9ca3af; border-top:1px solid #e5e7eb; padding-top:8px; margin-top:16px; }
    </style>
</head>
<body>

{{-- CABEÇALHO --}}
<div class="header">
    <div class="header-left">
        <img src="{{ asset('public/assets/img/insignia.png') }}" alt="Insígnia" style="width:60px;margin-bottom:6px;display:block;">
        <h2>REPÚBLICA DE ANGOLA</h2>
        <p>GOVERNO PROVÍNCIAL DE LUANDA</p>
        <p>ADMINISTRAÇÃO MUNICIPAL DE TALATONA</p>
        <p>DIRECÇÃO MUNICIPAL DA SAÚDE</p>
        <p style="font-weight:bold;color:#1a6b2f;">CENTRO DE SAÚDE DE REFERÊNCIA DO KIFICA</p>
    </div>
    <div class="header-right">
        <h1>PEDIDO DE EXAME</h1>
        <div class="num">Episódio Nº {{ str_pad($episodio->episodio_id, 6, '0', STR_PAD_LEFT) }}</div>
        <div class="num">Data: {{ \Carbon\Carbon::parse($episodio->ep_data ?? $episodio->data)->format('d/m/Y') }}</div>
    </div>
</div>

{{-- PACIENTE + MÉDICO --}}
<table style="width:100%;margin-bottom:14px;border-collapse:separate;border-spacing:6px;">
    <tr>
        <td class="info-cell">
            <h4>Paciente</h4>
            <div class="nome">{{ $episodio->nome }}</div>
            @if($episodio->data_nascimento)
                <div class="meta">{{ $episodio->sexo === 'M' ? 'Masculino' : 'Feminino' }} · {{ \Carbon\Carbon::parse($episodio->data_nascimento)->age }} anos</div>
            @endif
            @if($episodio->numero_processo)
                <div class="meta">Proc: {{ $episodio->numero_processo }}</div>
            @endif
        </td>
        <td class="info-cell">
            <h4>Médico Solicitante</h4>
            @if($pedidos->isNotEmpty())
                <div class="nome">Dr. {{ $pedidos->first()->medico }}</div>
            @endif
            <div class="meta">Data: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</div>
        </td>
    </tr>
</table>

{{-- DIAGNÓSTICO --}}
@if($consulta && $consulta->diagnostico)
<div class="diag-box">
    <h4>Diagnóstico / Hipótese Clínica</h4>
    <p>{{ $consulta->diagnostico }}</p>
</div>
@endif

{{-- PEDIDOS --}}
@if($pedidos->isEmpty())
    <p style="text-align:center;padding:20px;color:#9ca3af;">Nenhum pedido de exame registado.</p>
@else
<table class="pedidos">
    <thead>
        <tr>
            <th style="width:36px;">#</th>
            <th>Exame Solicitado</th>
            <th style="width:80px;">Prioridade</th>
            <th>Observações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pedidos as $i => $p)
        <tr>
            <td style="text-align:center;color:#9ca3af;font-weight:bold;">{{ $i+1 }}</td>
            <td style="font-weight:bold;">🔬 {{ $p->descricao_exame }}</td>
            <td style="text-align:center;">
                @if($p->urgente)
                    <span class="badge-urg">⚡ URGENTE</span>
                @else
                    <span class="badge-norm">Normal</span>
                @endif
            </td>
            <td style="color:#6b7280;font-style:italic;">{{ $p->observacao ?? '—' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- ASSINATURAS --}}
<table class="assin-table">
    <tr>
        <td class="assin-td">
            <div class="assin-line"></div>
            <div class="assin-nome">{{ $pedidos->isNotEmpty() ? 'Dr. '.$pedidos->first()->medico : '________________________________' }}</div>
            <div class="assin-cargo">Médico Solicitante</div>
        </td>
        <td class="assin-td">
            <div class="assin-line"></div>
            <div class="assin-nome">Técnico de Laboratório</div>
            <div class="assin-cargo">Laboratório — Centro de Referência do Kifica</div>
        </td>
    </tr>
</table>

{{-- RODAPÉ --}}
<div class="footer">
    Pedido de Exame · Episódio Nº {{ str_pad($episodio->episodio_id, 6, '0', STR_PAD_LEFT) }} ·
    {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }} · SANKWEVA, SU LDA &mdash; Centro de Saúde de Referência do Kifica · Luanda, Angola
</div>

</body>
</html>
