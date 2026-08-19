@extends('louyout.app')
@section('conteodo')
    @include('louyout.flash')

    @php
        $primeiro = $Products->first();
    @endphp

    <style>
        .det-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 24px;
        }

        .det-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a2e1a;
            margin: 0;
        }

        .det-sub {
            font-size: 13px;
            color: #6b7280;
            margin: 3px 0 0;
        }

        /* Info card */
        .info-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
            padding: 24px;
            margin-bottom: 24px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
        }

        .info-item label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #9ca3af;
            margin-bottom: 4px;
        }

        .info-item span {
            font-size: 14px;
            font-weight: 600;
            color: #1a2e1a;
        }

        /* Stock badge */
        .stock-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
        }

        .stock-ok {
            background: #d1fae5;
            color: #065f46;
        }

        .stock-low {
            background: #fef3c7;
            color: #92400e;
        }

        .stock-critical {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Section title */
        .section-title {
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 14px;
        }

        /* Lote cards */
        .lote-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 16px;
        }

        .lote-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
            overflow: hidden;
        }

        .lote-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 18px;
            background: #f0faf2;
            border-bottom: 1px solid #d1fae5;
        }

        .lote-card-header .lote-name {
            font-size: 14px;
            font-weight: 700;
            color: #1a6b2f;
        }

        .lote-card-header .lote-validade {
            font-size: 12px;
            color: #6b7280;
        }

        .lote-stats {
            display: flex;
            gap: 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .lote-stat {
            flex: 1;
            text-align: center;
            padding: 12px 8px;
            border-right: 1px solid #f3f4f6;
        }

        .lote-stat:last-child {
            border-right: none;
        }

        .lote-stat .stat-val {
            font-size: 20px;
            font-weight: 700;
            color: #1a2e1a;
        }

        .lote-stat .stat-lbl {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #9ca3af;
            margin-top: 2px;
        }

        .lote-card-body {
            padding: 16px 18px;
        }

        .lote-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-bottom: 16px;
        }

        .lote-meta-item label {
            font-size: 11px;
            color: #9ca3af;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .4px;
            display: block;
            margin-bottom: 2px;
        }

        .lote-meta-item span {
            font-size: 13px;
            color: #374151;
            font-weight: 500;
        }

        /* Saída form */
        .saida-form {
            background: #fafafa;
            border-radius: 10px;
            border: 1px solid #f3f4f6;
            padding: 14px;
        }

        .saida-form .form-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: #6b7280;
            margin-bottom: 4px;
            display: block;
        }

        .saida-form .fc {
            width: 100%;
            padding: 8px 12px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 13px;
            color: #1a2e1a;
            background: #fff;
            outline: none;
            transition: border-color .2s;
            margin-bottom: 10px;
        }

        .saida-form .fc:focus {
            border-color: #1a6b2f;
            box-shadow: 0 0 0 3px rgba(26, 107, 47, .1);
        }

        .saida-form .row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .btn-saida {
            width: 100%;
            padding: 9px;
            background: #1a6b2f;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-saida:hover {
            background: #2d9e4a;
        }

        .btn-gerir {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            background: #f0faf2;
            color: #1a6b2f;
            border: 1.5px solid #d1fae5;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: background .2s;
            margin-top: 10px;
        }

        .btn-gerir:hover {
            background: #d1fae5;
            color: #1a6b2f;
            text-decoration: none;
        }

        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: #9ca3af;
        }

        .empty-state i {
            font-size: 40px;
            margin-bottom: 12px;
            display: block;
        }

        @media(max-width:600px) {
            .info-card {
                grid-template-columns: 1fr 1fr;
            }

            .lote-grid {
                grid-template-columns: 1fr;
            }

            .lote-meta {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="det-header">
        <div>
            <h4 class="det-title">
                <i class="feather icon-package" style="color:#1a6b2f;margin-right:8px;"></i>
                {{ $primeiro->produto ?? 'Detalhes do Produto' }}
            </h4>
            <p class="det-sub">{{ $primeiro->categoria ?? '' }} &mdash; {{ $primeiro->departamento ?? '' }}</p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('createproduto.registar') }}" class="btn btn-primary btn-sm">
                <i class="feather icon-plus"></i> Novo Produto
            </a>
            <a href="{{ route('produto.verp') }}" class="btn btn-secondary btn-sm">
                <i class="feather icon-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    {{-- INFO CARD --}}
    @if ($primeiro)
        @php
            // Stock total calculado por produto_id — igual à listagem, evita inconsistências de lote_id
            $prodId = $Products->first()->produto_id ?? null;
            $movTotal = $prodId ? DB::table('estoque')->where('produto_id', $prodId)->get() : collect();
            $stockAtual = $movTotal->sum('entrada') - $movTotal->sum('saida');
            $stockMin = $primeiro->stokminimo ?? 0;
            $stockClass = $stockAtual <= 0 ? 'stock-critical' : ($stockAtual <= $stockMin ? 'stock-low' : 'stock-ok');
            $stockIcon =
                $stockAtual <= 0
                    ? 'icon-alert-circle'
                    : ($stockAtual <= $stockMin
                        ? 'icon-alert-triangle'
                        : 'icon-check-circle');
        @endphp
        <div class="info-card">
            <div class="info-item">
                <label>Apresentação</label>
                <span>{{ $primeiro->apresentacao ?? '—' }}</span>
            </div>
            <div class="info-item">
                <label>Código de Barras</label>
                <span>{{ $primeiro->codigo ?? '—' }}</span>
            </div>
            <div class="info-item">
                <label>Categoria</label>
                <span>{{ $primeiro->categoria ?? '—' }}</span>
            </div>
            <div class="info-item">
                <label>Departamento</label>
                <span>{{ $primeiro->departamento ?? '—' }}</span>
            </div>
            <div class="info-item">
                <label>Stock Mínimo</label>
                <span>{{ $stockMin }}</span>
            </div>
            <div class="info-item">
                <label>Stock Actual</label>
                <span class="stock-badge {{ $stockClass }}">
                    <i class="feather {{ $stockIcon }}" style="font-size:13px;"></i>
                    {{ $stockAtual }}
                </span>
            </div>
            <div class="info-item">
                <label>Data de Aquisição</label>
                <span>{{ $primeiro->data_aquisicao ?? '—' }}</span>
            </div>
        </div>
    @endif

    {{-- LOTES --}}
    <div class="section-title">
        <i class="feather icon-layers" style="margin-right:6px;"></i>
        Lotes registados ({{ $Products->count() }})
    </div>

    @if ($Products->isEmpty())
        <div class="empty-state">
            <i class="feather icon-inbox"></i>
            Nenhum lote registado para este produto.
        </div>
    @else
        <div class="lote-grid">
            @foreach ($Products as $lote)
                @php
                    // Stock por lote: calculado exclusivamente pelos movimentos deste lote_id
                    $movs      = DB::table('estoque')->where('lote_id', $lote->id)->get();
                    $loteE     = $movs->sum('entrada');
                    $loteS     = $movs->sum('saida');
                    $loteStock = $loteE - $loteS;
                    $loteClass =
                        $loteStock <= 0
                            ? 'stock-critical'
                            : ($loteStock <= ($primeiro->stokminimo ?? 0)
                                ? 'stock-low'
                                : 'stock-ok');

                    // Validade
                    $diasValidade = null;
                    $validadeClass = '';
                    if (!empty($lote->validade)) {
                        $hoje = new DateTime(date('Y-m-d'));
                        $val = new DateTime($lote->validade);
                        $diff = $hoje->diff($val);
                        $diasValidade = (int) $diff->format('%R%a');
                        $validadeClass =
                            $diasValidade < 0
                                ? 'color:#991b1b;'
                                : ($diasValidade <= 90
                                    ? 'color:#92400e;'
                                    : 'color:#065f46;');
                    }
                @endphp
                <div class="lote-card">
                    <div class="lote-card-header">
                        <span class="lote-name">
                            <i class="feather icon-tag" style="font-size:13px;margin-right:4px;"></i>
                            Lote: {{ $lote->lote ?? '—' }}
                        </span>
                        @if (!empty($lote->validade))
                            <span class="lote-validade" style="{{ $validadeClass }}">
                                <i class="feather icon-calendar" style="font-size:11px;"></i>
                                Val: {{ $lote->validade }}
                                @if ($diasValidade !== null)
                                    ({{ $diasValidade >= 0 ? $diasValidade . ' dias' : 'Expirado' }})
                                @endif
                            </span>
                        @endif
                    </div>

                    <div class="lote-stats">
                        <div class="lote-stat">
                            <div class="stat-val" style="color:#065f46;">{{ $loteE }}</div>
                            <div class="stat-lbl">Entradas</div>
                        </div>
                        <div class="lote-stat">
                            <div class="stat-val" style="color:#991b1b;">{{ $loteS }}</div>
                            <div class="stat-lbl">Saídas</div>
                        </div>
                        <div class="lote-stat">
                            <div class="stat-val">
                                <span class="stock-badge {{ $loteClass }}"
                                    style="font-size:14px;padding:2px 10px;">{{ $loteStock }}</span>
                            </div>
                            <div class="stat-lbl">Stock</div>
                        </div>
                    </div>

                    <div class="lote-card-body">
                        {{-- Saída rápida --}}
                        <div class="saida-form">
                            <p style="font-size:12px;font-weight:600;color:#374151;margin:0 0 10px;">
                                <i class="feather icon-send" style="color:#1a6b2f;margin-right:4px;"></i>
                                Registar Saída
                            </p>
                            @if (session('error') && session('lote_error_id') == $lote->id)
                                <div
                                    style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;padding:8px 12px;border-radius:8px;font-size:12px;margin-bottom:10px;display:flex;align-items:center;gap:6px;">
                                    <i class="feather icon-alert-circle"></i> {{ session('error') }}
                                </div>
                            @endif
                            <form action="{{ route('estoque.create') }}" method="post" data-no-loader
                                onsubmit="return checkStock(this, {{ $loteStock }})">
                                @csrf
                                <input type="hidden" name="departamento_id" value="{{ $lote->departamento_id }}">
                                <input type="hidden" name="users_id" value="{{ auth::user()->id }}">
                                <input type="hidden" name="situacao" value="Saida">
                                <input type="hidden" name="produto_id" value="{{ $lote->produto_id }}">
                                <input type="hidden" name="lote_id" value="{{ $lote->id }}">

                                <div class="row-2">
                                    <div>
                                        <label class="form-label">Quantidade</label>
                                        <input type="number" name="estock" class="fc" min="1" required
                                            placeholder="0">
                                    </div>
                                    <div>
                                        <label class="form-label">Destino</label>
                                        <select name="departamento" class="fc">
                                            @foreach ($Dp as $depa)
                                                @if ($depa->id != auth::user()->departamento_id)
                                                    <option>{{ $depa->departamento }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div style="margin-top:10px;">
                                    <label class="form-label">Data da Saída</label>
                                    <input type="date" name="data" class="fc" value="{{ date('Y-m-d') }}"
                                        required>
                                </div>
                                <button type="submit" class="btn-saida">
                                    <i class="feather icon-check"></i> Confirmar Saída
                                </button>
                            </form>
                        </div>

                        <a href="{{ route('estoque.estoque', ['id' => $lote->id]) }}" class="btn-gerir">
                            <i class="feather icon-edit-2"></i> Gerir Stock deste Lote
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <script>
        function checkStock(form, stockDisponivel) {
            const qtyInput = form.querySelector('input[name="estock"]');
            const qty = parseInt(qtyInput.value);
            if (!qty || qty <= 0) {
                qtyInput.style.borderColor = '#ef4444';
                qtyInput.focus();
                return false;
            }
            if (qty > stockDisponivel) {
                const wrap = document.getElementById('flash-wrap');
                if (wrap) {
                    const el = document.createElement('div');
                    el.className = 'flash-toast flash-err';
                    el.style.position = 'relative';
                    el.style.overflow = 'hidden';
                    el.innerHTML =
                        '<div class="flash-icon"><i class="feather icon-alert-circle"></i></div>' +
                        '<div class="flash-msg">Stock insuficiente. Disponível: <strong>' + stockDisponivel +
                        '</strong> unidades.</div>' +
                        '<button class="flash-close" onclick="closeToast(this)">&#x2715;</button>' +
                        '<div class="flash-progress"></div>';
                    wrap.appendChild(el);
                    setTimeout(function() {
                        closeToast(el.querySelector('.flash-close'));
                    }, 4000);
                }
                qtyInput.style.borderColor = '#ef4444';
                qtyInput.focus();
                return false;
            }
            // Stock OK — activa o loader antes de submeter
            qtyInput.style.borderColor = '';
            const loader = document.getElementById('kifica-loader');
            loader.classList.remove('hidden');
            const bar = loader.querySelector('.loader-bar');
            bar.style.animation = 'none';
            bar.offsetHeight;
            bar.style.animation = 'loaderProgress 1.6s cubic-bezier(.4,0,.2,1) forwards';
            return true;
        }
    </script>
@endsection
