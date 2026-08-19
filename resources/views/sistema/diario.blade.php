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
            <br>
            <img style="width:12%;margin-bottom:6px;" src="{{ asset('public/assets/img/insignia.png') }}" alt="Insígnia">
            <p><strong>REPÚBLICA DE ANGOLA</strong></p>
            <p><strong>GOVERNO PROVÍNCIAL DE LUANDA</strong></p>
            <p><strong>ADMINISTRAÇÃO MUNICIPAL DE TALATONA</strong></p>
            <p><strong>DIRECÇÃO MUNICIPAL DA SAÚDE</strong></p>
            <p><strong>CENTRO DE SAÚDE DE REFERÊNCIA DO KIFICA</strong></p>
            <p style="font-size:10px;color:#888;">SANKWEVA, SU LDA</p>
            <P> <strong>SECÇÃO - {{ $departamento }}</strong></P>
            <h3> <strong style="text-align: center;"> RELATÓRIO DIÁRIO</strong> N º:
            </h3>
            <span> <strong>DATA:{{ $Data1 }}</strong></span> <br>
            <br>
            <br>
        </center>

        <div style="width: 150px">

            <br>
            <br>


            <br>


            <table style="font-family: 'Times New Roman', Times, serif;font-size: 15px;padding: 10px; width: 696px;">
                <thead>
                    <tr style="background: #ccc;color:#000;">
                        <td>Nº</td>
                        <td>Material</td>

                        <td>Entrada</td>
                        <td>Stock Inicial</td>
                        <td>Saída </td>
                        <td>Stock Final</td>



                    </tr>

                </thead>
                <tbody>
                    @php
                        $cont = 0;
                    @endphp


                    @foreach ($Dt as $data)
                        @php
                            $cont = $cont + 1;
                        @endphp



                        @php

                            $estoque = DB::table('estoque')
                                ->select('entrada')
                                ->where('produto_id', '=', $data->id)
                                ->where('estoque.data', $Data1)
                                ->get('order', 'ASC');

                            $total_entrada = $estoque->sum('entrada');

                        @endphp

                        @php

                            $estoque = DB::table('estoque')
                                ->select('saida')
                                ->where('produto_id', '=', $data->id)
                                ->where('estoque.data', $Data1)
                                ->get();

                            $total_saida = $estoque->sum('saida');

                        @endphp
                        @if ($total_entrada > 0 && $total_saida >= 0) || ($total_entrada >= 0 &&
                            $total_saida >0 )
                            <tr>
                                <td>{{ $cont }}</td>
                                <td>{{ $data->produto }}</td>

                                <td>{{ $total_entrada }}</td>
                                <td>{{ $data->quantidade + $total_saida }}</td>
                                <td>{{ $total_saida }}</td>
                                <td>{{ $data->quantidade }}</td>

                            </tr>
                        @endif





                    @endif
                    @endforeach



                </tbody>
            </table>

            <br>
            <center> <span>O TÉCNICO</span></center>
            <br>
            <hr>
            <span style="position: relative;float: right; margin: -500px; top:-30px;">O CHEFE DA FARMÁCIA</span>
            <br>
            <hr style="position: relative;margin-right:-540px;width: 235px; top:-10px;">
</body>

</html>
