<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Requisições de Fármacos</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #000; background: #fff; padding: 20px 24px; }

        /* CABEÇALHO */
        .cab { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 14px; }
        .cab h1 { font-size: 14px; text-transform: uppercase; letter-spacing: 1px; }
        .cab h2 { font-size: 12px; text-transform: uppercase; margin: 3px 0; }
        .cab h3 { font-size: 13px; font-weight: bold; margin-top: 8px; text-decoration: underline; }
        .cab .sub { font-size: 10px; margin-top: 4px; color: #555; }

        /* TOTALIZADORES */
        .totais { display: table; width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .totais-cel { display: table-cell; width: 25%; text-align: center; padding: 8px 6px;
                      border: 1px solid #ccc; }
        .totais-num { font-size: 20px; font-weight: bold; }
        .totais-lbl { font-size: 9px; text-transform: uppercase; color: #555; margin-top: 2px; }
        .t-total { background: #f0f9ff; }
        .t-atend { background: #f0faf2; }
        .t-pend  { background: #fefce8; }
        .t-rej   { background: #fff1f2; }

        /* TABELA PRINCIPAL */
        table.principal { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        table.principal thead tr { background: #d0d0d0; }
        table.principal th {
            border: 1px solid #000; padding: 5px 6px;
            font-size: 9px; text-transform: uppercase; text-align: center; font-weight: bold;
        }
        table.principal td { border: 1px solid #999; padding: 4px 6px; font-size: 10px; vertical-align: top; }
        table.principal tr.req-row { background: #f9fafb; }
        table.principal tr.req-row:hover { background: #f0faf2; }
        table.principal tr.item-row td { background: #fff; padding: 2px 6px 2px 16px; font-size: 9px; color: #444; }
        table.principal td.centro { text-align: center; }

        /* BADGES DE ESTADO */
        .badge { padding: 2px 7px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .badge-pend { background: #fef3c7; color: #92400e; }
        .badge-aten { background: #d1fae5; color: #065f46; }
        .badge-rej  { background: #fee2e2; color: #991b1b; }

        /* RODAPÉ */
        .rodape { margin-top: 24px; border-top: 1px solid #ccc; padding-top: 10px;
                  display: table; width: 100%; }
        .rod-cel { display: table-cell; width: 33%; text-align: center; font-size: 10px; padding: 0 10px; }
        .rod-linha { border-top: 1px solid #000; width: 160px; margin: 0 auto 4px; }
        .rod-titulo { font-weight: bold; font-size: 10px; }
    </style>
</head>
<body>

{{-- CABEÇALHO --}}
<div class="cab">
    <img src="{{ asset('public/assets/img/insignia.png') }}" alt="Insígnia" style="width:55px;margin-bottom:6px;">
    <h1>REPÚBLICA DE ANGOLA</h1>
    <h2>GOVERNO PROVÍNCIAL DE LUANDA</h2>
    <h2>ADMINISTRAÇÃO MUNICIPAL DE TALATONA</h2>
    <h2>DIRECÇÃO MUNICIPAL DA SAÚDE</h2>
    <h2>CENTRO DE SAÚDE DE REFERÊNCIA DO KIFICA</h2>
    <h3>RELATÓRIO DE REQUISIÇÕES DE FÁRMACOS</h3>
    <p class="sub">
        Período: {{ \Carbon\Carbon::parse($dataInicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($dataFim)->format('d/m/Y') }}
        &nbsp;|&nbsp;
        Estado: {{ ucfirst($estadoFiltro) }}
        &nbsp;|&nbsp;
        Emitido em: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </p>
</div>

{{-- TOTALIZADORES --}}
<div class="totais">
    <div class="totais-cel t-total">
        <div class="totais-num">{{ $total }}</div>
        <div class="totais-lbl">Total de Requisições</div>
    </div>
    <div class="totais-cel t-aten">
        <div class="totais-num">{{ $totalAtendidas }}</div>
        <div class="totais-lbl">Atendidas</div>
    </div>
    <div class="totais-cel t-pend">
        <div class="totais-num">{{ $totalPendentes }}</div>
        <div class="totais-lbl">Pendentes</div>
    </div>
    <div class="totais-cel t-rej">
        <div class="totais-num">{{ $totalRejeitadas }}</div>
        <div class="totais-lbl">Rejeitadas</div>
    </div>
</div>

{{-- TABELA --}}
@if($requisicoes->isEmpty())
    <p style="text-align:center;padding:20px;color:#666;">Nenhuma requisição encontrada para o período seleccionado.</p>
@else
<table class="principal">
    <thead>
        <tr>
            <th style="width:40px;">Nº</th>
            <th>Departamento</th>
            <th>Solicitante</th>
            <th style="width:90px;">Data Req.</th>
            <th style="width:60px;">Estado</th>
            <th>Atendido por</th>
            <th style="width:90px;">Data Atend.</th>
            <th>Fármacos Solicitados</th>
        </tr>
    </thead>
    <tbody>
        @foreach($requisicoes as $r)
        <tr class="req-row">
            <td class="centro">{{ str_pad($r->id, 5, '0', STR_PAD_LEFT) }}</td>
            <td>{{ $r->dep_nome }}</td>
            <td>{{ $r->solicitante }}</td>
            <td class="centro">{{ \Carbon\Carbon::parse($r->created_at)->format('d/m/Y H:i') }}</td>
            <td class="centro">
                @if($r->estado === 'pendente')
                    <span class="badge badge-pend">PENDENTE</span>
                @elseif($r->estado === 'atendida')
                    <span class="badge badge-aten">ATENDIDA</span>
                @else
                    <span class="badge badge-rej">REJEITADA</span>
                @endif
            </td>
            <td>{{ $r->atendente ?? '—' }}</td>
            <td class="centro">
                {{ $r->atendido_em ? \Carbon\Carbon::parse($r->atendido_em)->format('d/m/Y H:i') : '—' }}
            </td>
            <td>
                @if(isset($itensPorReq[$r->id]))
                    @foreach($itensPorReq[$r->id] as $item)
                        • {{ $item->produto }} ({{ $item->apresentacao }}) × {{ $item->quantidade }}<br>
                    @endforeach
                @else
                    —
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- RODAPÉ --}}
<div class="rodape">
    <div class="rod-cel">
        <div class="rod-linha"></div>
        <div class="rod-titulo">Responsável da Farmácia</div>
        <div>Data: _______ / _______ / ___________</div>
    </div>
    <div class="rod-cel">
        <div class="rod-linha"></div>
        <div class="rod-titulo">Director/(a) Administrativo</div>
        <div>Data: _______ / _______ / ___________</div>
    </div>
    <div class="rod-cel">
        <div class="rod-linha"></div>
        <div class="rod-titulo">Director/(a) Clínico</div>
        <div>Data: _______ / _______ / ___________</div>
    </div>
</div>

</body>
</html>
