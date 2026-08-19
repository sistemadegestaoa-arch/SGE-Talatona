<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #1a1a1a;
            padding: 20px;
        }

        /* Cabeçalho */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #1a6b2f;
            padding-bottom: 14px;
            margin-bottom: 16px;
        }

        .logo-area {
            flex: 1;
        }

        .logo-area h2 {
            font-size: 16px;
            font-weight: bold;
            color: #1a6b2f;
            margin-bottom: 2px;
        }

        .logo-area p {
            font-size: 10px;
            color: #6b7280;
        }

        .rec-title {
            text-align: right;
        }

        .rec-title h1 {
            font-size: 20px;
            font-weight: bold;
            color: #1a6b2f;
            letter-spacing: 1px;
        }

        .rec-title .rec-num {
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
        }

        /* Info boxes */
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 14px;
        }

        .info-box {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 10px 12px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
        }

        .info-box:first-child {
            margin-right: 4px;
        }

        .info-box h4 {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: #9ca3af;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .info-box p {
            font-size: 12px;
            color: #1a1a1a;
            margin-bottom: 3px;
        }

        .info-box .label {
            font-size: 10px;
            color: #6b7280;
        }

        /* Diagnóstico */
        .diag-box {
            background: #f0faf2;
            border: 1px solid #d1fae5;
            border-radius: 6px;
            padding: 10px 12px;
            margin-bottom: 14px;
        }

        .diag-box h4 {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: #1a6b2f;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .diag-box p {
            font-size: 12px;
            color: #374151;
            line-height: 1.5;
        }

        /* Tabela medicamentos */
        .med-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .med-table thead tr {
            background: #1a6b2f;
            color: #fff;
        }

        .med-table thead th {
            padding: 8px 10px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .med-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .med-table tbody tr:nth-child(odd) {
            background: #fff;
        }

        .med-table tbody td {
            padding: 9px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 12px;
            vertical-align: middle;
        }

        .med-nome {
            font-weight: bold;
            color: #1a1a1a;
        }

        .med-apres {
            font-size: 10px;
            color: #6b7280;
            display: block;
            margin-top: 2px;
        }

        .qty-badge {
            background: #1a6b2f;
            color: #fff;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
        }

        /* Observações */
        .obs-box {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 6px;
            padding: 10px 12px;
            margin-bottom: 16px;
        }

        .obs-box h4 {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: #92400e;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .obs-box p {
            font-size: 12px;
            color: #374151;
        }

        /* Estado */
        .estado-dispensada {
            background: #d1fae5;
            color: #065f46;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
        }

        .estado-pendente {
            background: #fef3c7;
            color: #92400e;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
        }

        /* Assinaturas */
        .assinaturas {
            display: table;
            width: 100%;
            margin-top: 30px;
            border-top: 1px solid #e5e7eb;
            padding-top: 16px;
        }

        .ass-cell {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 0 20px;
        }

        .ass-line {
            border-top: 1px solid #1a1a1a;
            margin-bottom: 6px;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
            margin-top: 40px;
        }

        .ass-nome {
            font-size: 11px;
            font-weight: bold;
            color: #1a1a1a;
        }

        .ass-cargo {
            font-size: 10px;
            color: #6b7280;
            margin-top: 2px;
        }

        /* Rodapé */
        .footer {
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
            margin-top: 16px;
        }
    </style>
</head>

<body>

    {{-- CABEÇALHO --}}
    <div class="header">
        <div class="logo-area">
            <img src="{{ asset('public/assets/img/insignia.png') }}" alt="Insígnia" style="width:60px;margin-bottom:6px;display:block;">
            <h2>REPÚBLICA DE ANGOLA</h2>
            <p>GOVERNO PROVÍNCIAL DE LUANDA</p>
            <p>ADMINISTRAÇÃO MUNICIPAL DE TALATONA</p>
            <p>DIRECÇÃO MUNICIPAL DA SAÚDE</p>
            <p style="font-weight:bold;">CENTRO DE SAÚDE DE REFERÊNCIA DO KIFICA</p>
        </div>
        <div class="rec-title">
            <h1>RECEITA MÉDICA</h1>
            <div class="rec-num">Nº {{ str_pad($receita->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div style="margin-top:6px;">
                <span class="{{ $receita->estado === 'dispensada' ? 'estado-dispensada' : 'estado-pendente' }}">
                    {{ $receita->estado === 'dispensada' ? '✓ DISPENSADA' : 'PENDENTE' }}
                </span>
            </div>
        </div>
    </div>

    {{-- INFO: PACIENTE + MÉDICO --}}
    <table style="width:100%;margin-bottom:14px;border-collapse:separate;border-spacing:6px;">
        <tr>
            <td
                style="width:50%;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;padding:10px 12px;vertical-align:top;">
                <div
                    style="font-size:9px;text-transform:uppercase;letter-spacing:.8px;color:#9ca3af;font-weight:bold;margin-bottom:6px;">
                    PACIENTE</div>
                <div style="font-size:14px;font-weight:bold;color:#1a1a1a;margin-bottom:4px;">{{ $receita->nome }}</div>
                @if ($receita->data_nascimento)
                    <div style="font-size:11px;color:#6b7280;">{{ $receita->sexo === 'M' ? 'Masculino' : 'Feminino' }} ·
                        {{ \Carbon\Carbon::parse($receita->data_nascimento)->age }} anos</div>
                @endif
                @if ($receita->numero_processo)
                    <div style="font-size:11px;color:#6b7280;margin-top:3px;">Proc: {{ $receita->numero_processo }}
                    </div>
                @endif
            </td>
            <td
                style="width:50%;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;padding:10px 12px;vertical-align:top;">
                <div
                    style="font-size:9px;text-transform:uppercase;letter-spacing:.8px;color:#9ca3af;font-weight:bold;margin-bottom:6px;">
                    MÉDICO PRESCRITOR</div>
                <div style="font-size:14px;font-weight:bold;color:#1a1a1a;margin-bottom:4px;">Dr. {{ $receita->medico }}
                </div>
                <div style="font-size:11px;color:#6b7280;">Data:
                    {{ \Carbon\Carbon::parse($receita->data)->format('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    {{-- DIAGNÓSTICO --}}
    @if ($receita->diagnostico)
        <div class="diag-box">
            <h4>Diagnóstico</h4>
            <p>{{ $receita->diagnostico }}</p>
        </div>
    @endif

    {{-- MEDICAMENTOS --}}
    <table class="med-table">
        <thead>
            <tr>
                <th style="width:40px;">#</th>
                <th>Medicamento</th>
                <th>Dose</th>
                <th>Frequência</th>
                <th>Duração</th>
                <th style="text-align:center;">Qtd</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($itens as $i => $item)
                <tr>
                    <td style="color:#9ca3af;font-weight:bold;">{{ $i + 1 }}</td>
                    <td>
                        <span class="med-nome">{{ $item->produto }}</span>
                        @if ($item->apresentacao)
                            <span class="med-apres">{{ $item->apresentacao }}</span>
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
    @if ($receita->observacao)
        <div class="obs-box">
            <h4>Observações / Instruções</h4>
            <p>{{ $receita->observacao }}</p>
        </div>
    @endif

    {{-- ASSINATURAS — só médico prescritor --}}
    <table style="width:100%;margin-top:30px;border-top:1px solid #e5e7eb;">
        <tr>
            <td style="width:50%;text-align:center;padding:0 20px;vertical-align:bottom;">
                <div style="margin-top:50px;border-top:1px solid #1a1a1a;padding-top:6px;width:80%;margin-left:auto;margin-right:auto;">
                    <div style="font-size:11px;font-weight:bold;">Dr. {{ $receita->medico }}</div>
                    <div style="font-size:10px;color:#6b7280;margin-top:2px;">Médico Prescritor</div>
                    <div style="font-size:10px;color:#9ca3af;margin-top:2px;">Data: _______ / _______ / ___________</div>
                </div>
            </td>
            <td style="width:50%;"></td>
        </tr>
    </table>

    {{-- RODAPÉ --}}
    <div class="footer">
        SANKWEVA, SU LDA &mdash;
        Receita Nº {{ str_pad($receita->id, 6, '0', STR_PAD_LEFT) }} ·
        {{ \Carbon\Carbon::parse($receita->data)->format('d/m/Y') }} · Centro de Saúde de Referência do Kifica · Luanda, Angola
    </div>

</body>

</html>
