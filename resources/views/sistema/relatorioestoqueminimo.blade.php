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
            <P> <strong>SECÇÃO DO ARMAZÉM</strong></P>
        </center>
        <p style="text-align:center;font-size:10px;color:#888;margin-top:4px;">SANKWEVA, SU LDA</p>

        <div style="width: 200px">
            <CENter>
                <span> <strong style="text-align: center;font-size:12px;"> VISTO DIRECTOR CLÍNICO</strong></span>
            </CENter>

            <br>
            <CENter>
                <span>
                    <hr style="width: 80%;font-size:12px;">
                </span>
            </CENter>


            <span> <strong style="font-size:12px;">DOUTOR WALTER DOS SANTOS</strong></span> <br>
            <span> <strong></strong></span> <br>
            <br>
            <br>
        </div>

        <h4> <strong>ASSUNTO:</strong> Relatório do Armazém</h4>


        <br>
        <center>
            <h3>Produtos em risco , stoque insuficiente
            </h3>
        </center>

        <div class="table-responsive">

            <table class="table table-bordered" id="dataTable" cellspacing="0"
                style="font-family: 'Times New Roman', Times, serif;font-size: 15px;padding: 10px; width: 100%;">
                <thead>
                    <tr>
                        <td>Descrição</td>

                        <td>Quantidade</td>

                    </tr>
                </thead>
                <tbody>

                    @foreach ($Products as $produto)
                        @if ($produto->quantidade <= $produto->stokminimo)
                            <tr>
                                <td>{{ $produto->produto }}</td>
                                <td style="color:#fff;background-color:orange;text-align:center;font-size:30px;">
                                    {{ $produto->quantidade }}</td>
                            </tr>
                        @endif
                    @endforeach

                </tbody>
            </table>
        </div>
        <center>
            <span style="text-align: center"> <strong> LUANDA
                    {{ date('d-m-y') }}
                </strong></span>
            <br><br> <br><span style="text-align: center;"> <strong>O TECNICO EM SERVIÇO</strong></span>
            <br>

            <hr style="width: 200px;">
            <span style="text-align: center;"> <strong> </strong></span><br>

            <span style="text-align: center;"> <strong></strong></span>
        </center>
</body>

</html>
