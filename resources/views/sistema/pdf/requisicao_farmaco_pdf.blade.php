<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Nota de Requisição #{{ $req->id }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
            background: #fff;
            padding: 24px 30px;
        }

        /* ── CABEÇALHO ── */
        .cabecalho {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .cabecalho h1 { font-size: 15px; text-transform: uppercase; letter-spacing: 1px; }
        .cabecalho h2 { font-size: 13px; text-transform: uppercase; margin: 4px 0; }
        .cabecalho h3 { font-size: 14px; font-weight: bold; margin-top: 8px; text-decoration: underline; }
        .cabecalho .num { font-size: 11px; margin-top: 4px; }

        /* ── BLOCO DE INFORMAÇÕES ── */
        .info-bloco {
            margin: 12px 0;
            border: 1px solid #aaa;
            padding: 8px 12px;
        }
        .info-bloco table { width: 100%; border-collapse: collapse; }
        .info-bloco td { padding: 3px 6px; vertical-align: top; font-size: 12px; }
        .info-bloco .label { font-weight: bold; width: 160px; }

        /* ── TABELA DE ITENS ── */
        .tabela-itens {
            width: 100%;
            border-collapse: collapse;
            margin: 14px 0;
        }
        .tabela-itens thead tr { background-color: #d0d0d0; }
        .tabela-itens th {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 10px;
            text-transform: uppercase;
            text-align: center;
            font-weight: bold;
        }
        .tabela-itens td {
            border: 1px solid #000;
            padding: 5px 8px;
            font-size: 11px;
        }
        .tabela-itens td.centro { text-align: center; }
        .linha-vazia td { height: 18px; }

        /* ── OBSERVAÇÃO ── */
        .obs-bloco {
            border: 1px solid #aaa;
            min-height: 40px;
            padding: 8px;
            margin-top: 10px;
            font-size: 11px;
            font-style: italic;
            color: #444;
        }

        /* ── ASSINATURAS ── */
        .assinaturas {
            margin-top: 50px;
            width: 100%;
        }
        .assinaturas table { width: 100%; border-collapse: collapse; }
        .assinaturas td { width: 50%; text-align: center; vertical-align: bottom; padding: 0 20px; }
        .linha-ass {
            border-top: 1px solid #000;
            margin: 0 auto 6px;
            width: 180px;
        }
        .ass-nome { font-size: 12px; font-weight: bold; }
        .ass-cargo { font-size: 11px; margin-top: 2px; }
        .ass-data  { font-size: 11px; margin-top: 8px; }
    </style>
</head>
<body>

{{-- ── CABEÇALHO ── --}}
<div class="cabecalho">
    <img src="{{ asset('public/assets/img/insignia.png') }}" alt="Insígnia" style="width:60px;margin-bottom:6px;">
    <h1>REPÚBLICA DE ANGOLA</h1>
    <h2>GOVERNO PROVÍNCIAL DE LUANDA</h2>
    <h2>ADMINISTRAÇÃO MUNICIPAL DE TALATONA</h2>
    <h2>DIRECÇÃO MUNICIPAL DA SAÚDE</h2>
    <h2>CENTRO DE SAÚDE DE REFERÊNCIA DO KIFICA</h2>
    <h3>NOTA DE REQUISIÇÃO DE FÁRMACOS</h3>
    <p class="num">Nº {{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}</p>
</div>

{{-- ── INFORMAÇÕES ── --}}
<div class="info-bloco">
    <table>
        <tr>
            <td class="label">Departamento Solicitante:</td>
            <td><strong>{{ $req->departamento->departamento ?? '—' }}</strong></td>
            <td class="label">Data da Requisição:</td>
            <td><strong>{{ \Carbon\Carbon::parse($req->created_at)->format('d/m/Y H:i') }}</strong></td>
        </tr>
        <tr>
            <td class="label">Técnico Solicitante:</td>
            <td><strong>{{ $req->solicitante->name ?? '—' }}</strong></td>
            <td class="label">Estado:</td>
            <td>
                <strong>
                    @if($req->estado === 'pendente') PENDENTE
                    @elseif($req->estado === 'atendida') ATENDIDA
                    @else REJEITADA
                    @endif
                </strong>
            </td>
        </tr>
        @if($req->estado === 'atendida' || $req->estado === 'rejeitada')
        <tr>
            <td class="label">Técnico da Farmácia:</td>
            <td><strong>{{ $req->atendente->name ?? '—' }}</strong></td>
            <td class="label">Data Atendimento:</td>
            <td><strong>{{ $req->atendido_em ? \Carbon\Carbon::parse($req->atendido_em)->format('d/m/Y H:i') : '—' }}</strong></td>
        </tr>
        @endif
    </table>
</div>

{{-- ── TABELA DE ITENS ── --}}
<table class="tabela-itens">
    <thead>
        <tr>
            <th style="width:36px;">Nº</th>
            <th>Designação do Fármaco</th>
            <th style="width:120px;">Apresentação</th>
            <th style="width:70px;">Qtd.</th>
            <th style="width:130px;">Observação</th>
        </tr>
    </thead>
    <tbody>
        @foreach($itens as $i => $item)
        <tr>
            <td class="centro">{{ $i + 1 }}</td>
            <td>{{ $item->produto }}</td>
            <td>{{ $item->apresentacao }}</td>
            <td class="centro">{{ $item->quantidade }}</td>
            <td>{{ $item->observacao_item ?? '' }}</td>
        </tr>
        @endforeach
        {{-- Linhas em branco para completar até 12 --}}
        @for($x = count($itens); $x < 12; $x++)
        <tr class="linha-vazia">
            <td></td><td></td><td></td><td></td><td></td>
        </tr>
        @endfor
    </tbody>
</table>

{{-- ── OBSERVAÇÃO GERAL ── --}}
<div>
    <strong style="font-size:11px;">Observação Geral:</strong>
    <div class="obs-bloco">{{ $req->observacao ?: 'Sem observações.' }}</div>
</div>

{{-- ── ASSINATURAS ── --}}
<div class="assinaturas">
    <table>
        <tr>
            <td>
                <div class="linha-ass"></div>
                <div class="ass-nome">{{ $req->solicitante->name ?? '________________________________' }}</div>
                @if($req->departamento->departamento === 'S.O')
                <div class="ass-cargo">Técnico da S.O (Solicitante)</div>
                @elseif ($req->departamento->departamento === 'LABORÁTORIO')
                <div class="ass-cargo">Técnico do Laboratorio (Solicitante)</div>
                @endif
                <div class="ass-data">Data: _______ / _______ / ___________</div>
            </td>
            <td>
                <div class="linha-ass"></div>
                <div class="ass-nome">{{ $req->atendente->name ?? '________________________________' }}</div>
                <div class="ass-cargo">Técnico da Farmácia (Receptor)</div>
                <div class="ass-data">Data: _______ / _______ / ___________</div>
            </td>
        </tr>
    </table>
</div>

<p style="text-align:center;font-size:9px;color:#888;margin-top:16px;border-top:1px solid #ccc;padding-top:6px;">
    SANKWEVA, SU LDA &mdash; Centro de Saúde de Referência do Kifica &mdash; Gerado em {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
</p>

</body>
</html>
