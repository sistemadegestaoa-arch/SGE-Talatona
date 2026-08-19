<!DOCTYPE html>
<html>

<head>
    <title>SANKWEVA, SU LDA</title>

    <style>
        td {
            border: 1px solid #ccc;

        }

        tr {
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <div class="container">
        <center>
            <span>
                <img style="margin-top:10px;width:14%;margin-bottom:6px;"
                    src="{{ asset('public/assets/img/insignia.png') }}" alt="Insígnia">
            </span>
            <br>
            <p><strong>REPÚBLICA DE ANGOLA</strong></p>
            <p><strong>GOVERNO PROVÍNCIAL DE LUANDA</strong></p>
            <p><strong>ADMINISTRAÇÃO MUNICIPAL DE TALATONA</strong></p>
            <p><strong>DIRECÇÃO MUNICIPAL DA SAÚDE</strong></p>
            <p><strong>CENTRO DE SAÚDE DE REFERÊNCIA DO KIFICA</strong></p>
            <P> <strong>SECÇÃO DE FARMÁCIA</strong></P>
        </center>
        <p style="text-align:center;font-size:10px;color:#888;margin-top:4px;">SANKWEVA, SU LDA</p>

        <div style="width: 150px">
            <span> <strong style="text-align: center;"> VISTO</strong></span>
            <br>
            <br>
            <span>
                <hr style="width: 100%;">
            </span>


            <br>
            <br>
        </div>

        <h4> <strong>ASSUNTO:</strong> Relatório da Farmácia</h4>




        <br>


        <table style="font-family: 'Times New Roman', Times, serif;font-size: 15px;padding: 10px; width: 100%;">
            <thead>

                <tr>

                    <td> <strong> Produto </strong></td>
                    <td> <strong> Quantidade </strong></td>




                </tr>



            </thead>
            <tbody>

                @foreach ($Dt as $produto)
                    <tr>
                        <td>{{ $produto->produto }}</td>
                        <td>{{ $produto->quantidade }}</td>
                    </tr>
                @endforeach



            </tbody>
        </table>
        <br><br><br><br>
        <center>
            <span style="text-align: center"> <strong> LUANDA
                    {{ date('d-m-y') }}
                </strong></span>
            <br><br> <br><br><span style="text-align: center;"> <strong>O TECNICO EM SERVIÇO</strong></span>
            <br>
            <br>
            <br>
            <hr style="width: 200px;">
            <span style="text-align: center;"> <strong> </strong></span><br>

            <span style="text-align: center;"> <strong></strong></span>
        </center>
</body>

</html>
