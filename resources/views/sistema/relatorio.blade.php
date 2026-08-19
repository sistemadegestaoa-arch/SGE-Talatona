@extends('louyout.app')
@section('conteodo')
    @php
        $depUser = \DB::table('departamento')
            ->join('users', 'departamento.id', '=', 'users.departamento_id')
            ->where('users.id', Auth::id())
            ->value('departamento.departamento');
    @endphp

    <div class="page-header-bar">
        <div>
            <h4 class="page-title"><i class="feather icon-bar-chart-2"></i> Relatórios</h4>
            <p class="page-sub">Gere relatórios e fichas de stock</p>
        </div>
    </div>

    <input type="hidden" id="dep-value" value="{{ $depUser }}">

    <div class="report-grid">

        {{-- ENTRADAS E SAÍDAS --}}
        <div class="report-card">
            <div class="report-card-header">
                <i class="feather icon-trending-up"></i>
                <span>Entradas e Saídas</span>
            </div>
            <form action="{{ route('pdf.relatorio') }}" method="post">
                @csrf
                <input type="hidden" name="departamento" value="{{ $depUser }}">
                <div class="form-row-2">
                    <div class="fg">
                        <label>Data Inicial</label>
                        <input type="date" name="datainicial" class="fc" required>
                    </div>
                    <div class="fg">
                        <label>Data Final</label>
                        <input type="date" name="datafinal" class="fc" required>
                    </div>
                </div>
                <div class="fg">
                    <label>Tipo</label>
                    <select name="tipo" class="fc">
                        <option>Entradas</option>
                        <option>Saidas</option>
                        <option>Todos</option>
                    </select>
                </div>
                <button type="submit" class="btn-report"><i class="feather icon-file-text"></i> Gerar PDF</button>
            </form>
        </div>

        {{-- POR FORNECEDOR --}}
        <div class="report-card">
            <div class="report-card-header">
                <i class="feather icon-truck"></i>
                <span>Por Fornecedor</span>
            </div>
            <form action="{{ route('relatoriofornecedor.relatoriofornecedor') }}" method="post">
                @csrf
                <input type="hidden" name="departamento" value="{{ $depUser }}">
                <div class="form-row-2">
                    <div class="fg">
                        <label>Data Inicial</label>
                        <input type="date" name="datainicial" class="fc" required>
                    </div>
                    <div class="fg">
                        <label>Data Final</label>
                        <input type="date" name="datafinal" class="fc" required>
                    </div>
                </div>
                <div class="fg">
                    <label>Tipo</label>
                    <select name="tipo" class="fc">
                        <option value="entrada">Entradas</option>
                        <option value="saida">Saídas</option>
                        <option value="ambos">Todos</option>
                    </select>
                </div>
                <div class="fg">
                    <label>Fornecedor</label>
                    <select name="fornecedor_id" class="fc">
                        @foreach ($Fornecedor as $for)
                            <option value="{{ $for->fornecedor }}">{{ $for->fornecedor }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-report"><i class="feather icon-file-text"></i> Gerar PDF</button>
            </form>
        </div>

        {{-- TIPO DE AQUISIÇÃO --}}
        <div class="report-card">
            <div class="report-card-header">
                <i class="feather icon-tag"></i>
                <span>Tipo de Aquisição</span>
            </div>
            <form action="{{ route('relatoriotipo.relatoriotipo') }}" method="post">
                @csrf
                <input type="hidden" name="departamento" value="{{ $depUser }}">
                <div class="form-row-2">
                    <div class="fg">
                        <label>Data Inicial</label>
                        <input type="date" name="datainicial" class="fc" required>
                    </div>
                    <div class="fg">
                        <label>Data Final</label>
                        <input type="date" name="datafinal" class="fc" required>
                    </div>
                </div>
                <div class="fg">
                    <label>Tipo</label>
                    <select name="tipo" class="fc">
                        <option>COMPRAS</option>
                        <option>DOAÇÃO</option>
                    </select>
                </div>
                <button type="submit" class="btn-report"><i class="feather icon-file-text"></i> Gerar PDF</button>
            </form>
        </div>

        {{-- FICHA DE STOCK --}}
        <div class="report-card">
            <div class="report-card-header">
                <i class="feather icon-clipboard"></i>
                <span>Ficha de Stock</span>
            </div>
            <form action="{{ route('fichastok.fichaestok') }}" method="post">
                @csrf
                <div class="form-row-2">
                    <div class="fg">
                        <label>Data Inicial</label>
                        <input type="date" name="datainicial" class="fc" required>
                    </div>
                    <div class="fg">
                        <label>Data Final</label>
                        <input type="date" name="datafinal" class="fc" required>
                    </div>
                </div>
                <div class="fg">
                    <label>Produto</label>
                    <select name="produto_id" class="fc">
                        @foreach ($produto as $p)
                            <option value="{{ $p->id }}">{{ $p->produto }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-report"><i class="feather icon-file-text"></i> Gerar PDF</button>
            </form>
        </div>

        {{-- FICHA POR LOTE --}}
        <div class="report-card">
            <div class="report-card-header">
                <i class="feather icon-layers"></i>
                <span>Ficha por Lote</span>
            </div>
            <form action="{{ route('fichalote.fichalote') }}" method="post">
                @csrf
                <div class="form-row-2">
                    <div class="fg">
                        <label>Data Inicial</label>
                        <input type="date" name="datainicial" class="fc" required>
                    </div>
                    <div class="fg">
                        <label>Data Final</label>
                        <input type="date" name="datafinal" class="fc" required>
                    </div>
                </div>
                <div class="fg">
                    <label>Lote</label>
                    <select name="lote_id" class="fc">
                        @foreach ($lote as $p)
                            <option value="{{ $p->id }}">{{ $p->produto }} — {{ $p->lote }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-report"><i class="feather icon-file-text"></i> Gerar PDF</button>
            </form>
        </div>

        {{-- NOTA DE ENTREGA --}}
        <div class="report-card">
            <div class="report-card-header">
                <i class="feather icon-send"></i>
                <span>Nota de Entrega</span>
            </div>
            <form action="{{ route('guia.pdf') }}" method="post">
                @csrf
                <div class="fg">
                    <label>Data</label>
                    <input type="date" name="data" class="fc" required>
                </div>
                <div class="fg">
                    <label>Destino</label>
                    <select name="departamento" class="fc">
                        @foreach ($Dp as $depa)
                            <option>{{ $depa->departamento }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-report"><i class="feather icon-file-text"></i> Gerar PDF</button>
            </form>
        </div>

    </div>

    {{-- TABELA DE MOVIMENTAÇÕES --}}
    <div class="section-title" style="margin-top:32px;">Movimentações registadas</div>
    <div class="table-card">
        <div class="table-responsive">
            <table class="sys-table" id="dataTable">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Produto</th>
                        <th>Data Expiração</th>
                        <th>Lote</th>
                        <th>Fornecedor</th>
                        <th>Entrada</th>
                        <th>Stock Inicial</th>
                        <th>Saída</th>
                        <th>Stock Final</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Dt as $produto)
                        <tr>
                            <td>{{ $produto->data }}</td>
                            <td><strong>{{ $produto->produto }}</strong></td>
                            <td>{{ $produto->data_expiracao ?? '—' }}</td>
                            <td>{{ $produto->lote ?? '—' }}</td>
                            <td>{{ $produto->fornecedor ?? '—' }}</td>
                            <td><span class="badge-mov badge-entrada">{{ $produto->entrada }}</span></td>
                            <td>{{ $produto->qinicial }}</td>
                            <td><span class="badge-mov badge-saida">{{ $produto->saida }}</span></td>
                            <td><strong>{{ $produto->qfinal }}</strong></td>
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
            margin-bottom: 24px;
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

        .report-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 18px;
            margin-bottom: 32px;
        }

        .report-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
        }

        .report-card-header {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 700;
            color: #1a6b2f;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid #d1fae5;
        }

        .report-card-header i {
            font-size: 16px;
        }

        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .fg {
            margin-bottom: 12px;
        }

        .fg label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .fc {
            width: 100%;
            padding: 9px 12px;
            border: 1.5px solid #e5e7eb;
            border-radius: 9px;
            font-size: 13px;
            color: #1a2332;
            background: #f9fafb;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }

        .fc:focus {
            border-color: #1a6b2f;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(26, 107, 47, .1);
        }

        .btn-report {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            width: 100%;
            justify-content: center;
            padding: 10px;
            background: #1a6b2f;
            color: #fff;
            border: none;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 4px;
            transition: background .2s;
        }

        .btn-report:hover {
            background: #2d9e4a;
        }

        .section-title {
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 12px;
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

        .badge-mov {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-entrada {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-saida {
            background: #fee2e2;
            color: #991b1b;
        }

        @media(max-width:600px) {
            .form-row-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection
