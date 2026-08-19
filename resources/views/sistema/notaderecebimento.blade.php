<!DOCTYPE html>
<html>
<head>
   <title>SANKWEVA, SU LDA</title>
   <style>
       td {
           border: 1px solid #000;
       }
       tr {
           border: 1px solid #000;
       }
   </style>
</head>
<body>
<div class="container">
<center>
    <img style="width:12%;margin-bottom:6px;" src="{{ asset('public/assets/img/insignia.png') }}" alt="Insígnia">
    <p><strong>REPÚBLICA DE ANGOLA</strong></p>
    <p><strong>GOVERNO PROVÍNCIAL DE LUANDA</strong></p>
    <p><strong>ADMINISTRAÇÃO MUNICIPAL DE TALATONA</strong></p>
    <p><strong>DIRECÇÃO MUNICIPAL DA SAÚDE</strong></p>
    <p><strong>CENTRO DE SAÚDE DE REFERÊNCIA DO KIFICA</strong></p>
    <p style="font-size:10px;color:#888;">SANKWEVA, SU LDA</p>
    <P> <strong>SECÇÃO DE FARMÁCIA</strong></P>
    <h3> <strong style="text-align: center;"> NOTA DE RECEBIMENTO</strong> N º:
 </h3>
    <span> <strong>DATA:{{$Data1}}</strong></span> <br>
    <span> <strong>SOLICITANTE:{{$Departamento}}</strong></span> <br>
<br>
<br>
</center>

<div style="width: 150px">

<br>
<br>


<br>


<table style="font-family: 'Times New Roman', Times, serif;font-size: 15px;padding: 10px; width: 696px;text-align: center;">
    <thead >
        <tr style="background: #ccc;color:#000;">
            <td>Nº</td>
            <td>DESIGNAÇÃO</td>
            <td>ENTRADA</td>
            <td>SAÍDA</td>





        </tr>

    </thead>
    <tbody>
        @php
            $cont=0;
        @endphp

                @foreach ($Dt as $data)

                @if ($data->departamento=$Departamento)
                @php
                    $cont=$cont +1;
                @endphp
                <tr>
                    <td>{{$cont}}</td>
                    <td>{{$data->produto}}</td>
                    <td>{{$data->entrada}}</td>
                    <td>{{$data->saida}}</td>







                </tr>

                @endif
                @endforeach


<tr style="height: 200px;border: 1px solid #000">
    <td>Observarção:</td>
    <td colspan="5" style="height: 100px;text-align: left;">

        </td>
</tr>

    </tbody>
</table>

<br>

    <center> <span>ENTREGUEI</span></center>
       <br>
        <hr style="width: 200px;">
        <p> Tel:</p>
        <p> Data:____/____/_____/</p>

        <br>
        <div style="position: relative; left:500px; margin-top:-150px; background: #fff; height: 200px; width: 200px;">
            <center>
                RECEBEU</span></center> <br>


                 <hr style="width: 200px;">

                     <p >Tel:</p>
                    <p >Data:____/____/_____/</p>
        </div>

</body>
</html>
