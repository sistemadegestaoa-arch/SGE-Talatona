<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nota de Dispensa</title>
    <style>
        body { font-family:'Times New Roman',Times,serif; font-size:13px; margin:20px; color:#1a2e1a; }
        table { width:100%; border-collapse:collapse; margin-top:10px; }
        th, td { border:1px solid #999; padding:6px 10px; }
        th { background:#f0f0f0; text-align:left; font-size:12px; }
        center p { margin:2px 0; }
        h4 { margin:4px 0; }
        .badge { display:inline-block; padding:2px 8px; border-radius:4px; font-size:11px; font-weight:700; }
        .total-row td { background:#f0faf2; font-weight:700; }
    </style>
</head>
<body>

<center>
    <img style="width:16%;margin-bottom:6px;" src="{{ asset('public/assets/img/insignia.png') }}" alt="Insígnia">
    <p><strong>REPÚBLICA DE ANGOLA</strong></p>
    <p><strong>GOVERNO PROVÍNCIAL DE LUANDA</strong></p>
    <p><strong>ADMINISTRAÇÃO MUNICIPAL DE TALATONA</strong></p>
    <p><strong>DIRECÇÃO MUNICIPAL DA SAÚDE</strong></p>
    <p><strong>CENTRO DE SAÚDE DE REFERÊNCIA DO KIFICA</strong></p>
    <p><strong>{{ $atend->departamento }}</strong></p>
</center>

<br>

<h3 style="text-align:center;border-bottom:2px solid #1a6b2f;padding-bottom:6px;">NOTA DE DISPENSA DE MEDICAMENTOS</h3>

<table style="border:none;margin-bottom:16px;">
    <tr>
        <td style="border:none;width:50%;"><strong>Utente:</strong> {{ $atend->utente }}</td>
        <td style="border:none;width:50%;"><strong>Processo Nº:</strong> {{ $atend->processo ?? '—' }}</td>
    </tr>
    <tr>
        <td style="border:none;"><strong>Data:</strong> {{ \Carbon\Carbon::parse($atend->data)->format('d/m/Y') }}</td>
        <td style="border:none;"><strong>Técnico:</strong> {{ $atend->tecnico }}</td>
    </tr>
    @if($atend->observacao)
    <tr>
        <td style="border:none;" colspan="2"><strong>Observações:</strong> {{ $atend->observacao }}</td>
    </tr>
    @endif
</table>

<table>
    <thead>
        <tr>
            <th style="width:30px;">Nº</th>
            <th>Designação</th>
            <th>Apresentação</th>
            <th>Lote</th>
            <th style="width:80px;text-align:center;">Qtd</th>
        </tr>
    </thead>
    <tbody>
        @foreach($itens as $i => $item)
        <tr>
            <td>{{ $i+1 }}</td>
            <td><strong>{{ $item->produto }}</strong></td>
            <td>{{ $item->apresentacao ?? '—' }}</td>
            <td style="font-family:monospace;font-size:11px;">{{ $item->lote_num }}</td>
            <td style="text-align:center;font-weight:700;">{{ $item->quantidade }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="4" style="text-align:right;">Total de itens:</td>
            <td style="text-align:center;">{{ $itens->sum('quantidade') }}</td>
        </tr>
    </tbody>
</table>

<br><br><br>
<table style="border:none;width:100%;">
    <tr>
        <td style="border:none;text-align:center;width:50%;">
            <strong>O TÉCNICO EM SERVIÇO</strong><br><br><br>
            <hr style="width:180px;margin:0 auto;">
            <small>{{ $atend->tecnico }}</small>
        </td>
        <td style="border:none;text-align:center;width:50%;">
            <strong>O UTENTE / RESPONSÁVEL</strong><br><br><br>
            <hr style="width:180px;margin:0 auto;">
            <small>{{ $atend->utente }}</small>
        </td>
    </tr>
</table>

<br>
<center><small>SANKWEVA, SU LDA &mdash; LUANDA, {{ \Carbon\Carbon::parse($atend->data)->format('d \d\e F \d\e Y') }}</small></center>

</body>
</html>
