<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Prescrição Médica Nº {{ str_pad($prescricao->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #1a1a1a;
            padding: 22px 28px;
        }

        /* ── CABEÇALHO ── */
        .header {
            display: table;
            width: 100%;
            border-bottom: 3px solid #1a6b2f;
            padding-bottom: 14px;
            margin-bottom: 16px;
        }
        .header-left  { display: table-cell; vertical-align: top; }
        .header-right { display: table-cell; text-align: right; vertical-align: top; }
        .header-left h2  { font-size:15px; font-weight:bold; color:#1a6b2f; margin-bottom:2px; }
        .header-left p   { font-size:10px; color:#6b7280; }
        .header-right h1 { font-size:20px; font-weight:bold; color:#1a6b2f; letter-spacing:1px; }
        .header-right .num { font-size:11px; color:#6b7280; margin-top:3px; }

        /* ── INFO PACIENTE / MÉDICO ── */
        .info-table { width:100%; border-collapse:separate; border-spacing:6px; margin-bottom:14px; }
        .info-cell {
            width:50%; background:#f9fafb; border:1px solid #e5e7eb;
            padding:10px 12px; vertical-align:top;
        }
        .info-cell h4 {
            font-size:9px; text-transform:uppercase; letter-spacing:.8px;
            color:#9ca3af; font-weight:bold; margin-bottom:6px;
        }
        .info-cell .nome  { font-size:14px; font-weight:bold; margin-bottom:4px; }
        .info-cell .meta  { font-size:11px; color:#6b7280; line-height:1.7; }

        /* ── DIAGNÓSTICO ── */
        .diag-box {
            background:#f0faf2; border:1px solid #d1fae5;
            padding:10px 12px; margin-bottom:14px;
        }
        .diag-box h4 {
            font-size:9px; text-transform:uppercase; color:#1a6b2f;
            font-weight:bold; margin-bottom:5px; letter-spacing:.8px;
        }
        .diag-box p { font-size:12px; color:#374151; line-height:1.5; }

        /* ── TABELA DE MEDICAMENTOS ── */
        table.meds { width:100%; border-collapse:collapse; margin-bottom:16px; }
        table.meds thead tr { background:#1a6b2f; }
        table.meds th {
            padding:8px 10px; text-align:left; font-size:10px;
            font-weight:bold; text-transform:uppercase; letter-spacing:.5px; color:#fff;
        }
        table.meds tbody tr:nth-child(even) { background:#f9fafb; }
        table.meds td {
            padding:9px 10px; border-bottom:1px solid #e5e7eb;
            font-size:11px; vertical-align:top;
        }
        .med-num { color:#9ca3af; font-weight:bold; text-align:center; width:28px; }
        .med-nome { font-weight:bold; color:#1a1a1a; }
        .med-forma { font-size:10px; color:#6b7280; display:block; margin-top:2px; }
        .med-dos  { font-size:11px; color:#1a6b2f; font-weight:bold; }
        .qty-badge {
            background:#1a6b2f; color:#fff; padding:2px 8px;
            border-radius:12px; font-size:11px; font-weight:bold;
            display:inline-block;
        }
        .instr-box {
            font-size:10px; color:#92400e; background:#fffbeb;
            border:1px solid #fde68a; padding:3px 7px;
            border-radius:4px; margin-top:4px; display:inline-block;
        }

        /* ── OBSERVAÇÕES ── */
        .obs-box {
            background:#fffbeb; border:1px solid #fde68a;
            padding:10px 12px; margin-bottom:14px;
        }
        .obs-box h4 {
            font-size:9px; text-transform:uppercase; color:#92400e;
            font-weight:bold; margin-bottom:4px; letter-spacing:.8px;
        }
        .obs-box p { font-size:11px; color:#374151; }

        /* ── NOTA LEGAL ── */
        .nota-legal {
            border:1px dashed #d1d5db; padding:8px 12px; margin-bottom:16px;
            font-size:10px; color:#9ca3af; text-align:center; line-height:1.6;
        }

        /* ── ASSINATURA ── */
        .assin-table { width:100%; border-collapse:collapse; margin-top:30px; }
        .assin-td { width:50%; text-align:center; padding:0 20px; vertical-align:bottom; }
        .assin-line { border-top:1px solid #1a1a1a; width:80%; margin:50px auto 6px; }
        .assin-nome { font-size:11px; font-weight:bold; }
        .assin-cargo { font-size:10px; color:#6b7280; margin-top:2px; }

        /* ── RODAPÉ ── */
        .footer {
            text-align:center; font-size:9px; color:#9ca3af;
            border-top:1px solid #e5e7eb; padding-top:8px; margin-top:14px;
        }
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
        @if($dep)
            <p style="margin-top:3px;color:#1a6b2f;font-weight:bold;">Departamento: {{ $dep }}</p>
        @endif
    </div>
    <div class="header-right">
        <h1>PRESCRIÇÃO MÉDICA</h1>
        <div class="num">Nº {{ str_pad($prescricao->id, 6, '0', STR_PAD_LEFT) }}</div>
        <div class="num">Data: {{ \Carbon\Carbon::parse($prescricao->data)->format('d/m/Y') }}</div>
    </div>
</div>

{{-- PACIENTE + MÉDICO --}}
<table class="info-table">
    <tr>
        <td class="info-cell">
            <h4>Paciente</h4>
            <div class="nome">{{ $paciente->nome }}</div>
            @if($paciente->data_nascimento)
                <div class="meta">{{ $paciente->sexo === 'M' ? 'Masculino' : 'Feminino' }} · {{ \Carbon\Carbon::parse($paciente->data_nascimento)->age }} anos</div>
            @endif
            @if($paciente->numero_processo)
                <div class="meta">Nº Processo: {{ $paciente->numero_processo }}</div>
            @endif
            @if($paciente->telefone)
                <div class="meta">Tel: {{ $paciente->telefone }}</div>
            @endif
        </td>
        <td class="info-cell">
            <h4>Médico Prescritor</h4>
            <div class="nome">Dr(a). {{ $prescricao->medico->name }}</div>
            @if($dep)
                <div class="meta">{{ $dep }}</div>
            @endif
            <div class="meta">Centro de Referência do Kifica</div>
            <div class="meta">Data: {{ \Carbon\Carbon::parse($prescricao->data)->format('d/m/Y') }}</div>
        </td>
    </tr>
</table>

{{-- DIAGNÓSTICO --}}
@if($prescricao->diagnostico)
<div class="diag-box">
    <h4>Diagnóstico / Hipótese Clínica</h4>
    <p>{{ $prescricao->diagnostico }}</p>
</div>
@endif

{{-- MEDICAMENTOS --}}
<table class="meds">
    <thead>
        <tr>
            <th style="width:28px;">#</th>
            <th>Medicamento</th>
            <th style="width:90px;">Dose</th>
            <th style="width:110px;">Frequência</th>
            <th style="width:80px;">Duração</th>
            <th style="width:50px;text-align:center;">Qtd</th>
        </tr>
    </thead>
    <tbody>
        @foreach($prescricao->itens as $i => $item)
        <tr>
            <td class="med-num">{{ $i+1 }}</td>
            <td>
                <span class="med-nome">{{ $item->medicamento }}</span>
                @if($item->forma_farmaceutica || $item->dosagem)
                    <span class="med-forma">
                        {{ $item->forma_farmaceutica }}{{ $item->forma_farmaceutica && $item->dosagem ? ' · ' : '' }}<span class="med-dos">{{ $item->dosagem }}</span>
                    </span>
                @endif
                @if($item->instrucoes)
                    <span class="instr-box">📋 {{ $item->instrucoes }}</span>
                @endif
            </td>
            <td>{{ $item->dose ?? '—' }}</td>
            <td>{{ $item->frequencia ?? '—' }}</td>
            <td>{{ $item->duracao ?? '—' }}</td>
            <td style="text-align:center;"><span class="qty-badge">{{ $item->quantidade }}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- OBSERVAÇÕES --}}
@if($prescricao->observacao)
<div class="obs-box">
    <h4>Observações / Instruções</h4>
    <p>{{ $prescricao->observacao }}</p>
</div>
@endif

{{-- NOTA LEGAL --}}
<div class="nota-legal">
    Esta prescrição é válida por 30 dias a partir da data de emissão.<br>
    Apresente este documento na farmácia. Documento emitido pelo Centro de Referência do Kifica.
</div>

{{-- ASSINATURA --}}
<table class="assin-table">
    <tr>
        <td class="assin-td">
            <div class="assin-line"></div>
            <div class="assin-nome">Dr(a). {{ $prescricao->medico->name }}</div>
            <div class="assin-cargo">Médico Prescritor{{ $dep ? ' · '.$dep : '' }}</div>
        </td>
        <td class="assin-td"></td>
    </tr>
</table>

{{-- RODAPÉ --}}
<div class="footer">
    SANKWEVA, SU LDA &mdash;
    Prescrição Nº {{ str_pad($prescricao->id, 6, '0', STR_PAD_LEFT) }} ·
    {{ \Carbon\Carbon::parse($prescricao->data)->format('d/m/Y') }} ·
    Centro de Saúde de Referência do Kifica · Luanda, Angola
</div>

</body>
</html>
