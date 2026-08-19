<!DOCTYPE html>
<html>

<head>
    <title>RELAÓRIO</title>

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
            <img style="width:12%;margin-bottom:6px;" src="{{ asset('public/assets/img/insignia.png') }}" alt="Insígnia">
            <p><strong>REPÚBLICA DE ANGOLA</strong></p>
            <p><strong>GOVERNO PROVÍNCIAL DE LUANDA</strong></p>
            <p><strong>ADMINISTRAÇÃO MUNICIPAL DE TALATONA</strong></p>
            <p><strong>DIRECÇÃO MUNICIPAL DA SAÚDE</strong></p>
            <p><strong>CENTRO DE SAÚDE DE REFERÊNCIA DO KIFICA</strong></p>
            <p style="font-size:11px;color:#888;">SANKWEVA, SU LDA</p>
        </center>

        <br>
        <center>
            <h1>Produtos em risco de expirar ou expirados</h1>
        </center>

        <div class="table-responsive">

            <table class="table table-bordered" id="dataTable" cellspacing="0"
                style="font-family: 'Times New Roman', Times, serif;font-size: 15px;padding: 10px; width: 100%;">
                <thead>
                    <tr>
                        <td>Descrição</td>
                        <td>Lote </td>
                        <td>Data de Expiração </td>
                        <td>Dias de Validade </td>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($Products as $produto)
                        @php
                            $data_atual = new DateTime(date('Y-m-d'));
                            $data_expiracao = new DateTime($produto->validade);
                            $intervalo_em_dias = $data_atual->diff($data_expiracao);
                        @endphp
                        @if ($intervalo_em_dias->format('%R%a dias') <= 90)
                            <tr>

                                <td>{{ $produto->produto }}</td>



                                <td>{{ $produto->lote }}</td>


                                @if ($intervalo_em_dias->format('%R%a dias') <= 90)
                                    <td style="background: red;color:#fff;">{{ $produto->validade }}</td>
                                @else
                                    <td>{{ $produto->validade }}</td>
                                @endif

                                <td>{{ $intervalo_em_dias->format('%R%a dias') }}</td>

                            </tr>
                        @endif
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
