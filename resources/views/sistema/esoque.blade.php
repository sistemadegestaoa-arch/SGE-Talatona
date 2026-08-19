@extends('louyout.app')
@section('conteodo')
    @php
        $produtoInfo = $Pr->first();

        // Stock actual do lote
        $movs = DB::table('estoque')->where('lote_id', $lote_id)->get();
        $totalE = $movs->sum('entrada');
        $totalS = $movs->sum('saida');
        $stock = $totalE - $totalS;

        // Departamento do utilizador — busca o departamento_id correto
        $departamento_id = Auth::user()->departamento_id;

        $stockClass =
            $stock <= 0 ? 'stock-critical' : ($stock <= ($produtoInfo->stokminimo ?? 0) ? 'stock-low' : 'stock-ok');
    @endphp

    <style>
        .pg-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 24px;
        }

        .pg-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a2e1a;
            margin: 0;
        }

        .pg-sub {
            font-size: 13px;
            color: #6b7280;
            margin: 3px 0 0;
        }

        /* Flash */
        .flash {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 18px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .flash-success {
            background: #d1fae5;
            border: 1px solid #6ee7b7;
            color: #065f46;
        }

        .flash-error {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        .flash i {
            font-size: 16px;
        }

        /* Stock summary */
        .stock-summary {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            padding: 18px 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
            text-align: center;
        }

        .stat-card .val {
            font-size: 28px;
            font-weight: 700;
        }

        .stat-card .lbl {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #9ca3af;
            margin-top: 4px;
        }

        .stock-ok {
            color: #065f46;
        }

        .stock-low {
            color: #92400e;
        }

        .stock-critical {
            color: #991b1b;
        }

        /* Info card */
        .info-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
            padding: 20px 24px;
            margin-bottom: 24px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 16px;
        }

        .info-item label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #9ca3af;
            margin-bottom: 3px;
        }

        .info-item span {
            font-size: 13px;
            font-weight: 600;
            color: #1a2e1a;
        }

        /* Form tabs */
        .tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
        }

        .tab-btn {
            flex: 1;
            padding: 11px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            background: #fff;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            color: #6b7280;
            transition: all .2s;
            text-align: center;
        }

        .tab-btn.active-entrada {
            border-color: #1a6b2f;
            background: #f0faf2;
            color: #1a6b2f;
        }

        .tab-btn.active-saida {
            border-color: #ef4444;
            background: #fef2f2;
            color: #991b1b;
        }

        .tab-btn:hover {
            border-color: #9ca3af;
        }

        /* Form card */
        .form-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
            padding: 24px;
            max-width: 640px;
        }

        .fg {
            margin-bottom: 16px;
        }

        .fg label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: #374151;
            margin-bottom: 6px;
        }

        .fc {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 9px;
            font-size: 13px;
            color: #1a2e1a;
            background: #f9fafb;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .fc:focus {
            border-color: #1a6b2f;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(26, 107, 47, .1);
        }

        .row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: opacity .2s, transform .1s;
            margin-top: 4px;
        }

        .btn-submit:hover {
            opacity: .9;
            transform: translateY(-1px);
        }

        .btn-entrada {
            background: linear-gradient(135deg, #1a6b2f, #2d9e4a);
            color: #fff;
        }

        .btn-saida {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: #fff;
        }

        /* Histórico */
        .section-title {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: #6b7280;
            margin: 28px 0 14px;
        }

        .hist-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
        }

        .hist-table thead th {
            padding: 10px 14px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #1a6b2f;
            background: #f0faf2;
            border-bottom: 2px solid #d1fae5;
        }

        .hist-table tbody td {
            padding: 10px 14px;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
            vertical-align: middle;
        }

        .hist-table tbody tr:last-child td {
            border-bottom: none;
        }

        .hist-table tbody tr:hover td {
            background: #f9fafb;
        }

        .badge-e {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            background: #d1fae5;
            color: #065f46;
        }

        .badge-s {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            background: #fee2e2;
            color: #991b1b;
        }

        @media(max-width:600px) {
            .row-2 {
                grid-template-columns: 1fr;
            }

            .stock-summary {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>

    {{-- HEADER --}}
    <div class="pg-header">
        <div>
            <h4 class="pg-title">
                <i class="feather icon-activity" style="color:#1a6b2f;margin-right:8px;"></i>
                Gestão de Stock
            </h4>
            @if ($produtoInfo)
                <p class="pg-sub">
                    {{ $produtoInfo->produto }} &mdash; Lote: <strong>{{ $produtoInfo->lote }}</strong>
                </p>
            @endif
        </div>
        <a href="{{ route('produto.verp') }}" class="btn btn-secondary btn-sm">
            <i class="feather icon-arrow-left"></i> Ver Fármacos
        </a>
    </div>

    {{-- FLASH MESSAGES --}}
    @if (session('success'))
        <div class="flash flash-success">
            <i class="feather icon-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="flash flash-error">
            <i class="feather icon-alert-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- STOCK SUMMARY --}}
    <div class="stock-summary">
        <div class="stat-card">
            <div class="val" style="color:#065f46;">{{ $totalE }}</div>
            <div class="lbl">Total Entradas</div>
        </div>
        <div class="stat-card">
            <div class="val" style="color:#991b1b;">{{ $totalS }}</div>
            <div class="lbl">Total Saídas</div>
        </div>
        <div class="stat-card">
            <div class="val {{ $stockClass }}">{{ $stock }}</div>
            <div class="lbl">Stock Actual</div>
        </div>
        @if ($produtoInfo)
            <div class="stat-card">
                <div class="val" style="color:#6b7280;">{{ $produtoInfo->stokminimo ?? 0 }}</div>
                <div class="lbl">Stock Mínimo</div>
            </div>
        @endif
    </div>

    {{-- INFO DO PRODUTO --}}
    @if ($produtoInfo)
        <div class="info-card">
            <div class="info-item">
                <label>Produto</label>
                <span>{{ $produtoInfo->produto }}</span>
            </div>
            <div class="info-item">
                <label>Lote</label>
                <span>{{ $produtoInfo->lote }}</span>
            </div>
            <div class="info-item">
                <label>Apresentação</label>
                <span>{{ $produtoInfo->apresentacao ?? '—' }}</span>
            </div>
            <div class="info-item">
                <label>Código</label>
                <span>{{ $produtoInfo->codigo ?? '—' }}</span>
            </div>
        </div>
    @endif

    {{-- FORMULÁRIO COM TABS --}}
    <div class="form-card">
        <div class="tabs">
            <div class="tab-btn active-entrada" style="cursor:default;">
                <i class="feather icon-arrow-down-circle"></i> Registar Entrada
            </div>
        </div>

        <form action="{{ route('estoque.create') }}" method="post" id="estoque-form">
            @csrf
            <input type="hidden" name="departamento_id" value="{{ $departamento_id }}">
            <input type="hidden" name="users_id" value="{{ auth::user()->id }}">
            <input type="hidden" name="lote_id" value="{{ $lote_id }}">
            <input type="hidden" name="situacao" value="Entrada">

            @foreach ($Pr as $p)
                <input type="hidden" name="produto_id" value="{{ $p->id }}">
            @break
        @endforeach

        <div class="row-2">
            <div class="fg">
                <label>Quantidade</label>
                <input type="number" name="estock" class="fc" min="1" required placeholder="Ex: 10">
            </div>
            <div class="fg">
                <label>Data</label>
                <input type="date" name="data" class="fc" value="{{ date('Y-m-d') }}" required>
            </div>
        </div>

        <div class="row-2">
            <div class="fg">
                <label>Fornecedor</label>
                <select name="fornecedor_id" class="fc" required>
                    @foreach ($Fr as $fornecedor)
                        <option value="{{ $fornecedor->fornecedor }}">{{ $fornecedor->fornecedor }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fg">
                <label>Tipo de Aquisição</label>
                <select name="tipo_id" class="fc" required>
                    @foreach ($Tipo as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->tipo }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if (auth::user()->tipo == 'admin')
            <div class="fg">
                <label>Observação</label>
                <textarea name="obs" class="fc" rows="2" placeholder="Opcional..."></textarea>
            </div>
        @endif

        <button type="submit" class="btn-submit btn-entrada">
            <i class="feather icon-arrow-down-circle"></i>
            <span>Confirmar Entrada</span>
        </button>
    </form>
</div>

{{-- HISTÓRICO --}}
@php
    $historico = DB::table('estoque')->where('lote_id', $lote_id)->orderBy('id', 'desc')->limit(20)->get();
@endphp

@if ($historico->isNotEmpty())
    <div class="section-title">
        <i class="feather icon-clock" style="margin-right:6px;"></i>
        Últimos movimentos
    </div>
    <div style="overflow-x:auto;">
        <table class="hist-table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Tipo</th>
                    <th>Entrada</th>
                    <th>Saída</th>
                    <th>Stk Anterior</th>
                    <th>Stk Final</th>
                    <th>Fornecedor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($historico as $mov)
                    <tr>
                        <td>{{ $mov->data }}</td>
                        <td>
                            @if ($mov->situacao == 'Entrada')
                                <span class="badge-e"><i class="feather icon-arrow-down" style="font-size:10px;"></i>
                                    Entrada</span>
                            @else
                                <span class="badge-s"><i class="feather icon-arrow-up" style="font-size:10px;"></i>
                                    Saída</span>
                            @endif
                        </td>
                        <td>{{ $mov->entrada > 0 ? $mov->entrada : '—' }}</td>
                        <td>{{ $mov->saida > 0 ? $mov->saida : '—' }}</td>
                        <td>{{ $mov->qinicial }}</td>
                        <td><strong>{{ $mov->qfinal }}</strong></td>
                        <td>{{ $mov->fornecedor_id ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
