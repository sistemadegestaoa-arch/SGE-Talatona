<!DOCTYPE html>
<html>
<head>
   <title>Relatório por Tipo - {{ $tipo }}</title>
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

<div style="width:200px;">
    <center><strong style="font-size:12px;">VISTO DIRECTOR CLÍNICO</strong></center>
    <br>
    <center><hr style="width:80%;"></center>
    <br><br>
</div>

<h4><strong>ASSUNTO:</strong> Relatório {{ $de }}</h4>
<h4><strong>TIPO DE AQUISIÇÃO:</strong> {{ $tipo }}</h4>
<h4><strong>PERÍODO:</strong> {{ $Data1 }} à {{ $Data2 }}</h4>

<br>

@if(empty($agrupado))
    <p><em>Nenhum movimento do tipo "{{ $tipo }}" encontrado no período selecionado.</em></p>
@else

@php $cont = 0; @endphp

<table>
    <thead>
        <tr>
            <th style="width:40px;">Nº</th>
            <th>Designação</th>
            <th style="width:80px;">Entrada</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($agrupado as $cat_geral => $categorias)
        <tr class="tr-cat-geral">
            <td colspan="3">{{ strtoupper($cat_geral) }}</td>
        </tr>

        @foreach ($categorias as $cat => $produtos)
        <tr class="tr-cat">
            <td colspan="3">{{ $cat }}</td>
        </tr>

        @foreach ($produtos as $produto)
        @php $cont++ @endphp
        <tr>
            <td>{{ $cont }}</td>
            <td>{{ $produto->produto }}</td>
            <td>{{ $produto->total_entrada }}</td>
        </tr>
        @endforeach

        @endforeach
        @endforeach
    </tbody>
</table>

@endif

<br><br><br>
<center>
    <strong>LUANDA, {{ date('d-m-Y') }}</strong>
    <br><br><br>
    <strong>O TÉCNICO EM SERVIÇO</strong>
    <br><br>
    <hr style="width:200px;">
</center>

</body>
</html>
