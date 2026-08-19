<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1a1a1a; padding: 20px; }

    /* CABEÇALHO */
    .cab { text-align: center; margin-bottom: 18px; padding-bottom: 12px; border-bottom: 3px solid #1a6b2f; }
    .cab img { width: 55px; margin-bottom: 6px; }
    .cab h2 { font-size: 13px; color: #1a6b2f; font-weight: 700; margin: 4px 0 2px; }
    .cab .sub { font-size: 10px; color: #555; }
    .cab .tipo-rel { display: inline-block; margin-top: 6px; background: #1a6b2f; color: #fff; font-size: 11px; font-weight: 700; padding: 3px 16px; border-radius: 20px; letter-spacing: .5px; }

    /* META INFO */
    .meta-box { background: #f0faf2; border: 1px solid #d1fae5; border-radius: 8px; padding: 10px 14px; margin-bottom: 14px; }
    .meta-box table { width: 100%; border-collapse: collapse; }
    .meta-box td { padding: 2px 8px; font-size: 10px; color: #374151; width: 50%; }
    .meta-box td strong { color: #1a6b2f; }

    /* TOTAIS */
    .totais-wrap { margin-bottom: 16px; }
    .totais-wrap table { width: 100%; border-collapse: collapse; }
    .totais-wrap td { padding: 0 6px 0 0; vertical-align: top; width: 20%; }
    .tot-box { background: #f0faf2; border: 1px solid #d1fae5; border-radius: 8px; padding: 8px 10px; text-align: center; }
    .tot-num { font-size: 20px; font-weight: 900; color: #1a6b2f; line-height: 1.1; }
    .tot-lbl { font-size: 9px; color: #6b7280; margin-top: 2px; }
    .tot-conc { background: #d1fae5; border-color: #6ee7b7; }
    .tot-conc .tot-num { color: #065f46; }
    .tot-pend { background: #fef3c7; border-color: #fcd34d; }
    .tot-pend .tot-num { color: #92400e; }
    .tot-urg  { background: #fee2e2; border-color: #fca5a5; }
    .tot-urg  .tot-num { color: #991b1b; }
    .tot-cur  { background: #dbeafe; border-color: #93c5fd; }
    .tot-cur  .tot-num { color: #1d4ed8; }

    /* TABELA PRINCIPAL */
    table.dados { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    table.dados thead tr th { background: #1a6b2f; color: #fff; padding: 7px 8px; text-align: left; font-size: 10px; font-weight: 700; }
    table.dados tbody tr td { padding: 6px 8px; font-size: 10px; border-bottom: 1px solid #e5e7eb; vertical-align: middle; }
    table.dados tbody tr:nth-child(even) td { background: #f9fafb; }
    table.dados tbody tr:last-child td { border-bottom: 2px solid #1a6b2f; }
    table.dados tfoot tr td { padding: 7px 8px; font-size: 10px; font-weight: 700; background: #f0faf2; border-top: 2px solid #1a6b2f; }

    /* BADGES */
    .badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 9px; font-weight: 700; white-space: nowrap; }
    .b-conc { background: #d1fae5; color: #065f46; }
    .b-esp  { background: #fef3c7; color: #92400e; }
    .b-cur  { background: #dbeafe; color: #1d4ed8; }
    .b-urg  { background: #fee2e2; color: #991b1b; }

    /* SECÇÃO TÍTULO */
    .sec-titulo { font-size: 11px; font-weight: 700; color: #1a6b2f; border-left: 4px solid #1a6b2f; padding-left: 8px; margin: 16px 0 8px; text-transform: uppercase; letter-spacing: .5px; }

    /* ASSINATURA — sempre no fim sem sobreposição */
    .assinatura-wrap { margin-top: 30px; padding-top: 16px; border-top: 1px solid #e5e7eb; }
    .assinatura-wrap table { width: 100%; border-collapse: collapse; }
    .assinatura-wrap td { width: 33%; text-align: center; padding: 0 10px; vertical-align: bottom; }
    .assin-linha { border-top: 1px solid #1a2e1a; margin: 0 auto 6px; width: 140px; }
    .assin-nome { font-size: 10px; font-weight: 700; color: #1a2e1a; }
    .assin-cargo { font-size: 9px; color: #6b7280; margin-top: 2px; }

    /* RODAPÉ */
    .rodape { margin-top: 14px; font-size: 9px; color: #9ca3af; text-align: center; border-top: 1px solid #f3f4f6; padding-top: 6px; }

    /* VAZIO */
    .vazio { text-align: center; padding: 24px; color: #9ca3af; font-size: 11px; font-style: italic; }

    /* URGENTE */
    .row-urg td { background: #fff5f5 !important; }
</style>
</head>
<body>
<div class="cab">
    <img src="{{ asset('public/assets/img/insignia.png') }}" alt="Insígnia">
    <h2>REPÚBLICA DE ANGOLA</h2>
    <h2>GOVERNO PROVÍNCIAL DE LUANDA</h2>
    <h2>ADMINISTRAÇÃO MUNICIPAL DE TALATONA</h2>
    <h2>DIRECÇÃO MUNICIPAL DA SAÚDE</h2>
    <div class="sub">CENTRO DE SAÚDE DE REFERÊNCIA DO KIFICA</div>
</div>
