<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório de Atendimentos</title>
    <style>
        body { font-family:'Times New Roman',Times,serif; font-size:12px; margin:20px; color:#1a2e1a; }
        table { width:100%; border-collapse:collapse; margin-top:8px; margin-bottom:16px; }
        th, td { border:1px solid #aaa; padding:5px 8px; }
        th { background:#e8e8e8; font-size:11px; text-align:left; }
        .atend-header { background:#d1fae5; font-weight:bold; }
        .total-row td { background:#f0faf2; font-weight:700; }
        .summary-box { border:1px solid #aaa; border-radius:4px; padding:10px 14px; margin-bottom:16px; display:inline-block; min-width:160px; text-align:center; }
        .summary-box .sv { font-size:24px; font-weight:800; color:#1a6b2f; }
        .summary-box .sl { font-size:10px; text-transform:uppercase; letter-spacing:.5px; color:#6b7280; }
        .summary-row { display:flex; gap:12px; margin-bottom:16px; }
        h3 { border-bottom:2px solid #1a6b2f; padding-bottom:5px; color:#1a2e1a; }
    </style>
</head>
<body>

<center>
    <img style="width:14%;margin-bottom:6px;" src="{{ asset('public/assets/img/insignia.png') }}" alt="Insígnia">
    <p><strong>REPÚBLICA DE ANGOLA</strong></p>
    <p><strong>GOVERNO PROVÍNCIAL DE LUANDA</strong></p>
    <p><strong>ADMINISTRAÇÃO MUNICIPAL DE TALATONA</strong></p>
    <p><strong>DIRECÇÃO MUNICIPAL DA SAÚDE</strong></p>
    <p><strong>CENTRO DE SAÚDE DE REFERÊNCIA DO KIFICA</strong></p>
    <p><strong>{{ $depNome }}</strong></p>
</center>

<h3 style="text-align:center;">RELATÓRIO DE ATENDIMENTOS</h3>
<p style="text-align:center;color:#6b7280;">Período: <strong>{{ \Carbon\Carbon::parse($data1)->format('d/m/Y') }}</strong> a <strong>{{ \Carbon\Carbon::parse($data2)->format('d/m/Y') }}</strong></p>

<br>

{{-- Resumo --}}
@php
    $totalAtend  = $atendimentos->count();
    $totalItens  = $itensAll->flatten()->sum('quantidade');
    $totalUtentes = $atendimentos->pluck('utente')->unique()->count();
@endphp

<table style="border:none;width:auto;margin-bottom:20px;">
    <tr>
        <td style="border:none;padding-right:20px;">
            <div class="summary-box">
                <div class="sv">{{ $totalAtend }}</div>
                <div class="sl">Atendimentos</div>
            </div>
        </td>
        <td style="border:none;padding-right:20px;">
            <div class="summary-box">
                <div class="sv">{{ $totalUtentes }}</div>
                <div class="sl">Utentes</div>
            </div>
        </td>
        <td style="border:none;">
            <div class="summary-box">
                <div class="sv">{{ $totalItens }}</div>
                <div class="sl">Unidades dispensadas</div>
            </div>
        </td>
    </tr>
</table>

{{-- Detalhe por atendimento --}}
@foreach($atendimentos as $a)
@php $itens = $itensAll->get($a->id, collect()); @endphp
<table>
    <thead>
        <tr class="atend-header">
            <td colspan="5">
                <strong>{{ \Carbon\Carbon::parse($a->data)->format('d/m/Y') }}</strong>
                &nbsp;|&nbsp; Utente: <strong>{{ $a->utente }}</strong>
                @if($a->processo) &nbsp;|&nbsp; Proc: {{ $a->processo }} @endif
                &nbsp;|&nbsp; Técnico: {{ $a->tecnico }}
            </td>
        </tr>
        <tr>
            <th style="width:25px;">Nº</th>
            <th>Medicamento</th>
            <th>Apresentação</th>
            <th>Lote</th>
            <th style="width:60px;text-align:center;">Qtd</th>
        </tr>
    </thead>
    <tbody>
        @foreach($itens as $i => $item)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $item->produto }}</td>
            <td>{{ $item->apresentacao ?? '—' }}</td>
            <td style="font-family:monospace;font-size:10px;">{{ $item->lote_num }}</td>
            <td style="text-align:center;font-weight:700;">{{ $item->quantidade }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="4" style="text-align:right;">Total dispensado:</td>
            <td style="text-align:center;">{{ $itens->sum('quantidade') }}</td>
        </tr>
    </tbody>
</table>
@endforeach

@if($atendimentos->isEmpty())
<p style="text-align:center;color:#9ca3af;padding:30px;">Nenhum atendimento no período seleccionado.</p>
@endif

<br>
<center>
    <small>SANKWEVA, SU LDA &mdash; Gerado em {{ date('d/m/Y H:i') }} &mdash; LUANDA</small>
</center>

</body>
</html>
