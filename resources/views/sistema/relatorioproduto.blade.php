@extends('louyout.app')

@section('conteodo')
    <br>
    <h3> RELATÓRIO</h3>
    <div class="container">
        <br>
        <form action="{{ route('pdf.relatorio') }}" method="post" class="form-group">
            @csrf
            <div class="row">
                <div class="col-4">
                    <label for=""> Data Inicial</label>
                    <input type="date" name="datainicial" id="" class="form-control">
                </div>
                <div class="col-4">
                    <label for=""> Data Inicial</label>
                    <input type="date" name="datafinal" id="" class="form-control">
                </div>
                <div class="col-4">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>


            </div>
        </form>
        @if (auth::user()->tipo == 'admin')
            <h3> GERAR NOTA DE NTREGA</h3>
            <form action="{{ route('guia.pdf') }}" method="post" class="form-group">
                @csrf
                <div class="row">
                    <div class="col-4">
                        <label for=""> Data</label>
                        <input type="date" name="data" id="" class="form-control">
                    </div>
                    <div class="col-4">
                        <label for=""> Destino</label>
                        <select name="departamento" class="form-control">
                            @foreach ($Dp as $depa)
                                <option>{{ $depa->departamento }}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>


                </div>
            </form>
        @endif
        <table class="table table-bordered" id="dataTable" cellspacing="0"
            style="font-family: 'Times New Roman', Times, serif;font-size: 12px;padding: 2px;">
            <thead>
                <tr>
                    <td>data</td>
                    <td>Material</td>
                    <td>Fornecedor</td>
                    <td>Entrada</td>
                    <td>Stock Inicial</td>
                    <td>Saídas</td>
                    <td>Stock Final </td>


                </tr>

            </thead>
            <tbody>

                @foreach ($Dt as $produto)
                    @if ($produto->departamento_id == auth::user()->departamento_id)
                        <tr>
                            <td>{{ $produto->data }}</td>
                            <td>{{ $produto->produto }}</td>
                            <td>{{ $produto->fornecedor }}</td>
                            <td>{{ $produto->entrada }}</td>
                            <td>{{ $produto->qinicial }}</td>
                            <td>{{ $produto->saida }}</td>
                            <td>{{ $produto->qfinal }}</td>
                        </tr>
                    @endif
                @endforeach



            </tbody>
        </table>

    </div>

@endsection
