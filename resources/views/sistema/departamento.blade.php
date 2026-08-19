@extends('louyout.app')
@section('conteodo')
    @include('louyout.styles')
    @include('louyout.flash')

    <div class="page-header-bar">
        <div>
            <h4 class="page-title"><i class="feather icon-grid"></i> Departamentos</h4>
            <p class="page-sub">Gerir departamentos do sistema</p>
        </div>
        <a href="{{ route('createdepartamento.create') }}" class="btn-new">
            <i class="feather icon-plus-circle"></i> Novo
        </a>
    </div>

    <div class="table-card">
        <div class="table-responsive">
            <table class="sys-table" id="dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Acções</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Dp as $depa)
                        <tr>
                            <td>{{ $depa->id }}</td>
                            <td><strong>{{ $depa->departamento }}</strong></td>
                            <td>
                                <div style="display:flex;gap:4px;">
                                    <a href="{{ route('alterardepa.edit', ['id' => $depa->id]) }}" class="tbl-btn tbl-edit">
                                        <i class="feather icon-edit-2"></i>
                                    </a>
                                    @if(\App\Helpers\PermissaoHelper::podeEliminar())
                                        <form action="{{ route('apagardepa.destroy', ['id' => $depa->id]) }}" method="post"
                                            style="display:inline;" onsubmit="return confirm('Confirma a exclusão?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="tbl-btn tbl-del"><i
                                                    class="feather icon-trash-2"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
