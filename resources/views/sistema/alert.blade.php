@extends('louyout.app')
@section('conteodo')
    <div class="page-header-bar">
        <div>
            <h4 class="page-title"><i class="feather icon-alert-triangle" style="color:#d97706;"></i> Fármacos em Risco</h4>
            <p class="page-sub">Produtos com estoque baixo ou próximos da validade</p>
        </div>
        <a href="{{ route('home.index') }}" class="btn-back">
            <i class="feather icon-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="table-card">
        <div class="table-responsive">
            <table class="sys-table" id="dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Descrição</th>
                        <th>Apresentação</th>
                        <th>Categoria</th>
                        <th>Quantidade</th>
                        <th>Mínimo</th>
                        <th>Data Aquisição</th>
                        <th>Validade</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Products as $produto)
                        @php
                            $estoqueMin = $produto->quantidade <= $produto->stokminimo;
                            $expirado =
                                $produto->data_expiracao && \Carbon\Carbon::parse($produto->data_expiracao)->isPast();
                            $aExpirar =
                                $produto->data_expiracao &&
                                !$expirado &&
                                \Carbon\Carbon::parse($produto->data_expiracao)->diffInDays(now(), false) >= -90;
                        @endphp
                        <tr>
                            <td>{{ $produto->id }}</td>
                            <td><strong>{{ $produto->produto }}</strong></td>
                            <td>{{ $produto->apresentacao }}</td>
                            <td>{{ $produto->categoria }}</td>
                            <td>
                                @if ($estoqueMin)
                                    <span class="qty-badge qty-low">{{ $produto->quantidade }}</span>
                                @else
                                    <span class="qty-badge qty-ok">{{ $produto->quantidade }}</span>
                                @endif
                            </td>
                            <td>{{ $produto->stokminimo }}</td>
                            <td>{{ $produto->data_aquisicao }}</td>
                            <td>
                                @if ($produto->data_expiracao)
                                    @if ($expirado)
                                        <span class="qty-badge qty-low">{{ $produto->data_expiracao }}</span>
                                    @elseif($aExpirar)
                                        <span class="qty-badge qty-warn">{{ $produto->data_expiracao }}</span>
                                    @else
                                        {{ $produto->data_expiracao }}
                                    @endif
                                @else
                                    <span style="color:#9ca3af;">—</span>
                                @endif
                            </td>
                            <td>
                                @if ($expirado)
                                    <span class="status-badge status-red">Expirado</span>
                                @elseif($aExpirar)
                                    <span class="status-badge status-orange">A expirar</span>
                                @elseif($estoqueMin)
                                    <span class="status-badge status-orange">Stock baixo</span>
                                @else
                                    <span class="status-badge status-green">OK</span>
                                @endif
                            </td>
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

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border: 2px solid #1a6b2f;
            border-radius: 9px;
            color: #1a6b2f;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: background .2s, color .2s;
        }

        .btn-back:hover {
            background: #1a6b2f;
            color: #fff;
            text-decoration: none;
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
            color: #92400e;
            background: #fffbeb;
            border-bottom: 2px solid #fde68a;
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
            background: #fffbeb;
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

        .qty-warn {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .status-red {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-orange {
            background: #fef3c7;
            color: #92400e;
        }

        .status-green {
            background: #d1fae5;
            color: #065f46;
        }
    </style>
@endsection
