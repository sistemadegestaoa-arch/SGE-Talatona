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
    <table style="width: 100%;text-align: center">
        <tbody>
            <tr>
                <td colspan="8" style="text-align:center;padding:10px 0;">
                    <img style="width:10%;margin-bottom:6px;" src="{{ asset('public/assets/img/insignia.png') }}" alt="Insígnia"><br>
                    <strong>REPÚBLICA DE ANGOLA</strong><br>
                    GOVERNO PROVÍNCIAL DE LUANDA<br>
                    ADMINISTRAÇÃO MUNICIPAL DE TALATONA<br>
                    DIRECÇÃO MUNICIPAL DA SAÚDE<br>
                    <strong>CENTRO DE SAÚDE DE REFERÊNCIA DO KIFICA</strong><br>
                    <small style="color:#888;">SANKWEVA, SU LDA</small>
                </td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3">FICHA DE STOCK</td>
                <td colspan="3"></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4">Estrutura Sanitaria: CENTRO DE SAÚDE DE REFERÊNCIA DO KIFICA</td>
                <td colspan="2">Município: Talatona</td>
                <td colspan="2">Província: Luanda</td>
            </tr>
            <tr>
                <td colspan="4">DESIGNAÇÃO-DOSAGEM-FORMA</td>
                <td colspan="4">UNIDADE DE EMBALAGEM</td>
            </tr>
            <tr>
                <td colspan="4">{{ $produto->produto }}</td>
                <td colspan="4">{{ $produto->apresentacao }}</td>
            </tr>
            <tr>
                <td>Data do Movimento</td>
                <td>Nº de Lote</td>
                <td>Origem/destno</td>
                <td>Stock Inicial</td>
                <td>Entradas</td>
                <td>Saídas</td>
                <td>Stock Existente</td>
                <td>Usuário</td>
            </tr>
            @php
                $data = DB::table('estoque')
                    ->join('produto', 'estoque.produto_id', '=', 'produto.id')
                    ->join('lote', 'estoque.lote_id', '=', 'lote.id')
                    ->join('departamento', 'estoque.departamento_id', '=', 'departamento.id')

                    ->select('produto.*', 'departamento.departamento', 'estoque.*', 'lote.lote')
                    ->where('estoque.produto_id', '=', $id)
                    ->orderBy('estoque.data', 'asc')
                    ->get();
            @endphp

            @foreach ($data as $p)
                <tr>
                    <td>{{ $p->data }}</td>
                    <td>{{ $p->lote }}</td>
                    <td>{{ $p->fornecedor_id }}</td>
                    <td>{{ $p->qinicial }}</td>
                    <td>{{ $p->entrada }}</td>
                    <td>{{ $p->saida }}</td>
                    <td>{{ $p->qfinal }}</td>
                    <TD></TD>
                </tr>
            @endforeach



        </tbody>
    </table>
</body>

</html>
