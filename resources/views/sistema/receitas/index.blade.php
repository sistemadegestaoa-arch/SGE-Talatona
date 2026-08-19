@extends('louyout.app')
@section('conteodo')
    <style>
        .rc-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .rc-title {
            font-size: 22px;
            font-weight: 800;
            color: #1a2e1a;
            margin: 0;
        }

        .rc-sub {
            font-size: 13px;
            color: #6b7280;
            margin: 4px 0 0;
        }

        /* Stats */
        .rc-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .rc-stat {
            border-radius: 16px;
            padding: 20px 16px 16px;
            color: #fff;
            position: relative;
            overflow: hidden;
            transition: transform .2s;
        }

        .rc-stat:hover {
            transform: translateY(-3px);
        }

        .rc-stat.rs1 {
            background: linear-gradient(135deg, #c0620a, #f08030);
        }

        .rc-stat.rs2 {
            background: linear-gradient(135deg, #1a6b2f, #2d9e4a);
        }

        .rc-stat.rs3 {
            background: linear-gradient(135deg, #334155, #64748b);
        }

        .rc-stat-num {
            font-size: 36px;
            font-weight: 900;
            line-height: 1;
        }

        .rc-stat-lbl {
            font-size: 11px;
            font-weight: 600;
            opacity: .85;
            margin-top: 3px;
        }

        .rc-stat-icon {
            position: absolute;
            right: 12px;
            top: 12px;
            font-size: 28px;
            opacity: .15;
        }

        /* Toolbar */
        .rc-toolbar {
            display: flex;
            gap: 10px;
            margin-bottom: 16px;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
        }

        .rc-search {
            position: relative;
            flex: 1;
            min-width: 200px;
        }

        .rc-search input {
            width: 100%;
            padding: 9px 14px 9px 36px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 13px;
            outline: none;
            font-family: 'Inter', sans-serif;
        }

        .rc-search input:focus {
            border-color: #1a6b2f;
            box-shadow: 0 0 0 3px rgba(26, 107, 47, .1);
        }

        .rc-search i {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        /* Cards de receita */
        .rc-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 28px;
        }

        .rc-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
            text-decoration: none;
            color: inherit;
            display: block;
            transition: box-shadow .2s, transform .2s;
        }

        .rc-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, .1);
            transform: translateY(-2px);
            text-decoration: none;
            color: inherit;
        }

        .rc-card-inner {
            display: flex;
            align-items: stretch;
        }

        /* Número */
        .rc-pos {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 54px;
            background: #f9fafb;
            border-right: 1px solid #f3f4f6;
            flex-shrink: 0;
            font-size: 20px;
            font-weight: 900;
            color: #9ca3af;
        }

        /* Conteúdo */
        .rc-content {
            flex: 1;
            padding: 16px 18px;
            min-width: 0;
        }

        .rc-pac {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .rc-av {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: 800;
            color: #fff;
            flex-shrink: 0;
        }

        .rav-m {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        }

        .rav-f {
            background: linear-gradient(135deg, #9d174d, #ec4899);
        }

        .rc-nome {
            font-size: 15px;
            font-weight: 700;
            color: #1a2e1a;
        }

        .rc-chips {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .rc-chip {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .rcc-med {
            background: #dbeafe;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .rcc-hora {
            background: #f3f4f6;
            color: #6b7280;
        }

        .rcc-obs {
            background: #ede9fe;
            color: #5b21b6;
            border: 1px solid #ddd6fe;
        }

        /* Medicamentos mini-lista */
        .rc-meds {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .rc-med-pill {
            background: #f0faf2;
            border: 1px solid #d1fae5;
            border-radius: 8px;
            padding: 4px 10px;
            font-size: 12px;
            color: #1a2e1a;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .rc-med-pill.sem-stock {
            background: #fef2f2;
            border-color: #fca5a5;
            color: #991b1b;
        }

        .rc-med-pill .qty {
            font-weight: 800;
            color: #1a6b2f;
            margin-left: 2px;
        }

        .rc-med-pill.sem-stock .qty {
            color: #991b1b;
        }

        /* Acção */
        .rc-action {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0 18px;
            gap: 8px;
            flex-shrink: 0;
        }

        .btn-dispensar {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            background: #1a6b2f;
            color: #fff;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            transition: background .2s;
            white-space: nowrap;
        }

        .btn-dispensar:hover {
            background: #2d9e4a;
        }

        .btn-ver-rec {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 14px;
            border: 1.5px solid #e5e7eb;
            background: #fff;
            color: #374151;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            transition: all .2s;
            white-space: nowrap;
        }

        .btn-ver-rec:hover {
            border-color: #1a6b2f;
            color: #1a6b2f;
        }

        /* Sem stock alerta */
        .sem-stock-alerta {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 700;
            color: #991b1b;
            white-space: nowrap;
        }

        /* Concluídos */
        .sec-title {
            font-size: 12px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .disp-list {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .disp-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            border-bottom: 1px solid #f3f4f6;
            text-decoration: none;
            color: inherit;
            transition: background .15s;
        }

        .disp-item:last-child {
            border-bottom: none;
        }

        .disp-item:hover {
            background: #f0faf2;
        }

        /* Botão atendimento manual */
        .btn-manual {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 20px;
            border: 2px solid #e5e7eb;
            background: #fff;
            color: #374151;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s;
        }

        .btn-manual:hover {
            border-color: #1a6b2f;
            color: #1a6b2f;
            text-decoration: none;
        }

        /* Vazio */
        .rc-empty {
            text-align: center;
            padding: 64px 20px;
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
        }

        @media(max-width:700px) {
            .rc-stats {
                grid-template-columns: 1fr 1fr;
            }

            .rc-card-inner {
                flex-wrap: wrap;
            }

            .rc-pos {
                width: 100%;
                height: 38px;
            }

            .rc-action {
                padding: 0 16px 16px;
                flex-direction: row;
                justify-content: flex-start;
            }
        }
    </style>

    {{-- HEADER --}}
    <div class="rc-header">
        <div>
            <h1 class="rc-title">💊 Receitas Pendentes</h1>
            <p class="rc-sub">{{ \Carbon\Carbon::today()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <a href="{{ route('atendimento.create') }}" class="btn-manual">
                <i class="feather icon-plus-circle"></i> Atendimento Manual
            </a>
            <a href="{{ route('atendimento.index') }}" class="btn-manual">
                <i class="feather icon-list"></i> Histórico
            </a>
        </div>
    </div>

    @include('louyout.flash')

    {{-- STATS --}}
    <div class="rc-stats">
        <div class="rc-stat rs1 {{ $totalPendentes > 0 ? 'pulse' : '' }}">
            <div class="rc-stat-icon"><i class="feather icon-clock"></i></div>
            <div class="rc-stat-num">{{ $totalPendentes }}</div>
            <div class="rc-stat-lbl">Receitas Pendentes</div>
        </div>
        <div class="rc-stat rs2">
            <div class="rc-stat-icon"><i class="feather icon-check-circle"></i></div>
            <div class="rc-stat-num">{{ $totalDispensados }}</div>
            <div class="rc-stat-lbl">Dispensadas Hoje</div>
        </div>
        <div class="rc-stat rs3">
            <div class="rc-stat-icon"><i class="feather icon-archive"></i></div>
            <div class="rc-stat-num">{{ $totalGeral }}</div>
            <div class="rc-stat-lbl">Total Dispensadas</div>
        </div>
    </div>

    {{-- TOOLBAR --}}
    <div class="rc-toolbar">
        <div class="rc-search">
            <i class="feather icon-search" style="font-size:14px;"></i>
            <input type="text" id="inp-rc" placeholder="Pesquisar por paciente ou médico..." oninput="filtrarRc()">
        </div>
    </div>

    {{-- LISTA --}}
    @if ($pendentes->isEmpty())
        <div class="rc-empty">
            <div style="font-size:56px;margin-bottom:14px;">🎉</div>
            <div style="font-size:18px;font-weight:700;color:#1a2e1a;">Sem receitas pendentes!</div>
            <div style="font-size:14px;color:#6b7280;margin-top:6px;">Todas as receitas foram dispensadas.</div>
        </div>
    @else
        <div class="rc-list" id="rc-list">
            @php $pos = 0; @endphp
            @foreach ($pendentes as $r)
                @php
                    $pos++;
                    $avCls = $r->sexo === 'M' ? 'rav-m' : 'rav-f';
                    $temSemStock = $r->itens->where('stock_suficiente', false)->count() > 0;
                    $tempoEspera = \Carbon\Carbon::parse($r->hora)->diffForHumans();
                @endphp
                <div class="rc-card {{ $temSemStock ? 'border-left:4px solid #dc2626;' : '' }}"
                    style="{{ $temSemStock ? 'border-left:4px solid #fca5a5;' : '' }}"
                    data-nome="{{ strtolower($r->nome) }}" data-medico="{{ strtolower($r->medico) }}">
                    <div class="rc-card-inner">
                        <div class="rc-pos">{{ $pos }}</div>
                        <div class="rc-content">
                            {{-- Paciente --}}
                            <div class="rc-pac">
                                <div class="rc-av {{ $avCls }}">{{ mb_strtoupper(mb_substr($r->nome, 0, 1)) }}</div>
                                <div>
                                    <div class="rc-nome">{{ $r->nome }}</div>
                                    <div
                                        style="font-size:12px;color:#6b7280;margin-top:2px;display:flex;gap:10px;flex-wrap:wrap;">
                                        @if ($r->data_nascimento)
                                            <span>{{ $r->sexo === 'M' ? '♂' : '♀' }}
                                                {{ \Carbon\Carbon::parse($r->data_nascimento)->age }} anos</span>
                                        @endif
                                        @if ($r->numero_processo)
                                            <span># {{ $r->numero_processo }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- Chips info --}}
                            <div class="rc-chips">
                                <span class="rc-chip rcc-med">🩺 Dr. {{ $r->medico }}</span>
                                <span class="rc-chip rcc-hora">🕐 {{ $tempoEspera }}</span>
                                @if ($r->observacao)
                                    <span class="rc-chip rcc-obs">💬 {{ \Str::limit($r->observacao, 40) }}</span>
                                @endif
                            </div>
                            {{-- Medicamentos --}}
                            <div class="rc-meds">
                                @foreach ($r->itens as $item)
                                    <div class="rc-med-pill {{ !$item->stock_suficiente ? 'sem-stock' : '' }}">
                                        💊 {{ $item->produto }}
                                        @if ($item->apresentacao)
                                            <span style="opacity:.7;font-size:10px;"> — {{ $item->apresentacao }}</span>
                                        @endif
                                        <span class="qty">× {{ $item->quantidade }}</span>
                                        @if (!$item->stock_suficiente)
                                            <span style="font-size:10px;"> (stock: {{ $item->stock_disponivel }})</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @if ($temSemStock)
                                <div
                                    style="margin-top:8px;padding:6px 10px;background:#fef2f2;border-radius:8px;font-size:11px;font-weight:700;color:#991b1b;display:inline-flex;align-items:center;gap:5px;">
                                    <i class="feather icon-alert-triangle" style="font-size:12px;"></i>
                                    Atenção: stock insuficiente para alguns medicamentos
                                </div>
                            @endif
                        </div>
                        <div class="rc-action">
                            <a href="{{ route('receitas.show', $r->receita_id) }}" class="btn-dispensar">
                                <i class="feather icon-package"></i> Dispensar
                            </a>
                            <a href="{{ route('receitas.pdf', $r->receita_id) }}" target="_blank" class="btn-ver-rec">
                                <i class="feather icon-printer"></i> Imprimir
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- DISPENSADAS HOJE --}}
    @if ($dispensadasHoje->isNotEmpty())
        <div class="sec-title" style="margin-top:8px;">
            <span style="width:8px;height:8px;border-radius:50%;background:#1a6b2f;display:inline-block;"></span>
            Dispensadas hoje ({{ $dispensadasHoje->count() }})
        </div>
        <div class="disp-list">
            @foreach ($dispensadasHoje as $d)
                <a href="{{ route('receitas.show', $d->receita_id) }}" class="disp-item">
                    <div
                        style="width:32px;height:32px;border-radius:50%;background:#d1fae5;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#065f46;flex-shrink:0;">
                        {{ mb_strtoupper(mb_substr($d->nome, 0, 1)) }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div
                            style="font-size:13px;font-weight:600;color:#1a2e1a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $d->nome }}</div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:2px;">Dr. {{ $d->medico }} ·
                            {{ \Carbon\Carbon::parse($d->hora_dispensa)->format('H:i') }}</div>
                    </div>
                    <span
                        style="padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;background:#d1fae5;color:#065f46;">✅
                        Dispensada</span>
                    <a href="{{ route('receitas.pdf', $d->receita_id) }}" target="_blank"
                        style="display:inline-flex;align-items:center;gap:4px;padding:5px 10px;border:1px solid #d1fae5;border-radius:8px;color:#1a6b2f;font-size:11px;font-weight:600;text-decoration:none;"
                        onclick="event.stopPropagation()">
                        <i class="feather icon-printer" style="font-size:11px;"></i> PDF
                    </a>
                </a>
            @endforeach
        </div>
    @endif

    <script>
        function filtrarRc() {
            const q = document.getElementById('inp-rc').value.toLowerCase().trim();
            document.querySelectorAll('#rc-list .rc-card').forEach(c => {
                const nome = c.dataset.nome || '';
                const medico = c.dataset.medico || '';
                c.style.display = !q || nome.includes(q) || medico.includes(q) ? '' : 'none';
            });
        }
    </script>
@endsection
