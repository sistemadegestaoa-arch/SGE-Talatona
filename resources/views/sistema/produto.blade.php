@extends('louyout.app')
@section('conteodo')
    <div class="page-header-bar">
        <div>
            <h4 class="page-title"><i class="feather icon-package"></i> Fármacos</h4>
            <p class="page-sub">Lista de produtos do seu departamento</p>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <a href="{{ route('createproduto.registar') }}" class="qa-btn green-dark">
                <i class="feather icon-plus-circle"></i> Novo
            </a>
            <a href="{{ route('relatorioproduto.relatorioproduto') }}" class="qa-btn outline">
                <i class="feather icon-printer"></i> Imprimir
            </a>
        </div>
    </div>

    <div class="table-card">
        <div class="table-responsive">
            <table class="sys-table" id="dataTable">
                <thead>
                    <tr>
                        <th>Acções</th>
                        <th>Movimentos</th>
                        <th>#</th>
                        <th>Descrição</th>
                        <th>Apresentação</th>
                        <th>Código</th>
                        <th>Categoria</th>
                        <th>Quantidade</th>
                        <th>Mínimo</th>
                        <th>Data Aquisição</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Products as $produto)
                        <tr>
                            <td>
                                <div style="display:flex;gap:4px;flex-wrap:wrap;">
                                    <a href="{{ route('produtedite.produtedite', $produto->id) }}" class="tbl-btn tbl-edit">
                                        <i class="feather icon-edit-2"></i>
                                    </a>
                                    <a href="{{ route('ficha.ficha', $produto->id) }}" class="tbl-btn tbl-info">
                                        <i class="feather icon-file-text"></i>
                                    </a>
                                    @if(\App\Helpers\PermissaoHelper::podeEliminar())
                                        <form action="{{ route('apagarproduto.destroy', ['id' => $produto->id]) }}"
                                            method="post" style="display:inline;"
                                            onsubmit="return confirm('Confirma a exclusão?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="tbl-btn tbl-del">
                                                <i class="feather icon-trash-2"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('detalhes_produto.detalhes_produto', ['id' => $produto->id]) }}"
                                    class="tbl-btn tbl-view">
                                    <i class="feather icon-eye"></i>
                                </a>
                            </td>
                            <td>{{ $produto->id }}</td>
                            <td>
                                <a href="{{ route('detalhes_produto.detalhes_produto', ['id' => $produto->id]) }}"
                                    class="prod-link">
                                    {{ $produto->produto }}
                                </a>
                            </td>
                            <td>{{ $produto->apresentacao }}</td>
                            <td><span class="code-badge">{{ $produto->codigo }}</span></td>
                            <td>{{ $produto->categoria }}</td>
                            <td>
                                @if ($produto->stock_real <= $produto->stokminimo)
                                    <span class="qty-badge qty-low">{{ $produto->stock_real }}</span>
                                @else
                                    <span class="qty-badge qty-ok">{{ $produto->stock_real }}</span>
                                @endif
                            </td>
                            <td>{{ $produto->stokminimo }}</td>
                            <td>{{ $produto->data_aquisicao }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .page-header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .page-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a2e1a;
            margin: 0;
        }

        .page-sub {
            font-size: 13px;
            color: #6b7280;
            margin: 3px 0 0;
        }

        .qa-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: opacity .2s, transform .1s;
        }

        .qa-btn:hover {
            opacity: .88;
            transform: translateY(-1px);
            text-decoration: none;
        }

        .qa-btn.green-dark {
            background: #1a6b2f;
            color: #fff;
        }

        .qa-btn.outline {
            background: transparent;
            color: #1a6b2f;
            border: 2px solid #1a6b2f;
        }

        .qa-btn.outline:hover {
            background: #1a6b2f;
            color: #fff;
        }

        .table-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
        }

        .sys-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .sys-table thead th {
            padding: 11px 14px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #1a6b2f;
            background: #f0faf2;
            border-bottom: 2px solid #d1fae5;
            white-space: nowrap;
        }

        .sys-table tbody td {
            padding: 10px 14px;
            color: #374151;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }

        .sys-table tbody tr:last-child td {
            border-bottom: none;
        }

        .sys-table tbody tr:hover td {
            background: #f0faf2;
        }

        .tbl-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 7px;
            border: none;
            cursor: pointer;
            font-size: 13px;
            text-decoration: none;
            transition: opacity .2s;
        }

        .tbl-btn:hover {
            opacity: .8;
            text-decoration: none;
        }

        .tbl-edit {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .tbl-info {
            background: #d1fae5;
            color: #065f46;
        }

        .tbl-del {
            background: #fee2e2;
            color: #991b1b;
        }

        .tbl-view {
            background: #f3f4f6;
            color: #374151;
        }

        .prod-link {
            color: #1a6b2f;
            font-weight: 500;
            text-decoration: none;
        }

        .prod-link:hover {
            text-decoration: underline;
        }

        .code-badge {
            display: inline-block;
            padding: 2px 8px;
            background: #f3f4f6;
            border-radius: 6px;
            font-size: 11px;
            font-family: monospace;
            color: #374151;
        }

        .qty-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .qty-ok {
            background: #d1fae5;
            color: #065f46;
        }

        .qty-low {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
@endsection
