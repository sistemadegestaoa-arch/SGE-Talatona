<!DOCTYPE html>
<html>
<head>
   <title>Relatório por Fornecedor</title>
   <style>
       body { font-family: 'Times New Roman', Times, serif; font-size: 13px; margin: 20px; }
       table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
       th, td { border: 1px solid #999; padding: 5px 8px; }
       th { background: #e8e8e8; text-align: left; }
       .tr-cat-geral td { background: #c8c8c8; font-weight: bold; font-size: 13px; }
       .tr-cat td { background: #efefef; font-weight: bold; font-size: 12px; padding-left: 16px; }
       center p { margin: 2px 0; }
       h4 { margin: 4px 0; }
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
    <p><strong>{{ $de }}</strong></p>
</center>
<p style="text-align:center;font-size:10px;color:#888;margin-top:4px;">SANKWEVA, SU LDA</p>

<br>

<h4><strong>ASSUNTO:</strong> Relatório por Fornecedor</h4>
<h4><strong>FORNECEDOR:</strong> {{ $Fornecedor }}</h4>
<h4><strong>PERÍODO:</strong> {{ $Data1 }} à {{ $Data2 }}</h4>
<h4><strong>TIPO:</strong>
    @if($tipo === 'entrada') Entradas
    @elseif($tipo === 'saida') Saídas
    @else Entradas e Saídas
    @endif
</h4>

<br>

@if(empty($agrupado))
    <p><em>Nenhum movimento encontrado para este fornecedor no período selecionado.</em></p>
@else

@php $cont = 0; @endphp

<table>
    <thead>
        <tr>
            <th style="width:40px;">Nº</th>
            <th>Designação</th>
            @if($tipo === 'entrada' || $tipo === 'ambos')
            <th style="width:80px;">Entrada</th>
            @endif
            @if($tipo === 'saida' || $tipo === 'ambos')
            <th style="width:80px;">Saída</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($agrupado as $cat_geral => $categorias)
        {{-- Linha de categoria geral --}}
        <tr class="tr-cat-geral">
            <td colspan="{{ $tipo === 'ambos' ? 4 : 3 }}">{{ strtoupper($cat_geral) }}</td>
        </tr>

        @foreach ($categorias as $cat => $produtos)
        {{-- Linha de categoria --}}
        <tr class="tr-cat">
            <td colspan="{{ $tipo === 'ambos' ? 4 : 3 }}">{{ $cat }}</td>
        </tr>

        @foreach ($produtos as $produto)
        @php $cont++ @endphp
        <tr>
            <td>{{ $cont }}</td>
            <td>{{ $produto->produto }}</td>
            @if($tipo === 'entrada' || $tipo === 'ambos')
            <td>{{ $produto->total_entrada ?? 0 }}</td>
            @endif
            @if($tipo === 'saida' || $tipo === 'ambos')
            <td>{{ $produto->total_saida ?? 0 }}</td>
            @endif
        </tr>
        @endforeach

        @endforeach
        @endforeach
    </tbody>
</table>

@endif

<br><br><br>
<center>
    <p><strong>LUANDA, {{ date('d-m-Y') }}</strong></p>
    <br><br>
    <p><strong>O TÉCNICO EM SERVIÇO</strong></p>
    <br><br>
    <hr style="width:200px;">
</center>

</body>
</html>
