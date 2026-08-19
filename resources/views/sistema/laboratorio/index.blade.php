@extends('louyout.app')
@section('conteodo')
    <style>
        .lb-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .lb-title {
            font-size: 22px;
            font-weight: 800;
            color: #1a2e1a;
            margin: 0;
        }

        .lb-sub {
            font-size: 13px;
            color: #6b7280;
            margin: 4px 0 0;
        }

        /* Stats */
        .lb-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .lb-stat {
            border-radius: 16px;
            padding: 18px 16px 14px;
            color: #fff;
            position: relative;
            overflow: hidden;
            transition: transform .2s;
        }

        .lb-stat:hover {
            transform: translateY(-3px);
        }

        .lb-stat.ls1 {
            background: linear-gradient(135deg, #c0620a, #f08030);
        }

        .lb-stat.ls2 {
            background: linear-gradient(135deg, #991b1b, #dc2626);
        }

        .lb-stat.ls3 {
            background: linear-gradient(135deg, #1a6b2f, #2d9e4a);
        }

        .lb-stat.ls4 {
            background: linear-gradient(135deg, #334155, #64748b);
        }

        .lb-stat-num {
            font-size: 34px;
            font-weight: 900;
            line-height: 1;
        }

        .lb-stat-lbl {
            font-size: 11px;
            font-weight: 600;
            opacity: .85;
            margin-top: 3px;
        }

        .lb-stat-icon {
            position: absolute;
            right: 12px;
            top: 12px;
            font-size: 28px;
            opacity: .15;
        }

        /* Toolbar */
        .lb-toolbar {
            display: flex;
            gap: 10px;
            margin-bottom: 16px;
            flex-wrap: wrap;
            align-items: center;
        }

        .lb-search {
            flex: 1;
            min-width: 200px;
            position: relative;
        }

        .lb-search input {
            width: 100%;
            padding: 9px 14px 9px 36px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 13px;
            outline: none;
            font-family: 'Inter', sans-serif;
        }

        .lb-search input:focus {
            border-color: #1a6b2f;
            box-shadow: 0 0 0 3px rgba(26, 107, 47, .1);
        }

        .lb-search i {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 15px;
        }

        .lb-fbtn {
            padding: 9px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            background: #fff;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            color: #374151;
            font-family: 'Inter', sans-serif;
            transition: all .2s;
        }

        .lb-fbtn.active {
            border-color: #1a6b2f;
            background: #f0faf2;
            color: #1a6b2f;
        }

        /* Cards de pedido */
        .lb-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 28px;
        }

        .lb-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
            display: block;
            text-decoration: none;
            color: inherit;
            transition: box-shadow .2s, transform .2s;
        }

        .lb-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, .1);
            transform: translateY(-2px);
            text-decoration: none;
            color: inherit;
        }

        .lb-card.urgente {
            border-left: 5px solid #dc2626;
        }

        .lb-card-inner {
            display: flex;
            align-items: stretch;
        }

        /* Número / urgência */
        .lb-pos {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 58px;
            background: #f9fafb;
            border-right: 1px solid #f3f4f6;
            flex-shrink: 0;
            gap: 4px;
        }

        .lb-pos-num {
            font-size: 18px;
            font-weight: 900;
            color: #9ca3af;
        }

        .lb-pos.urg {
            background: #fef2f2;
        }

        .lb-pos.urg .lb-pos-num {
            color: #dc2626;
        }

        .urg-icon {
            font-size: 18px;
        }

        /* Conteúdo */
        .lb-content {
            flex: 1;
            padding: 16px 18px;
            min-width: 0;
        }

        .lb-exame {
            font-size: 15px;
            font-weight: 800;
            color: #1a2e1a;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .lb-badges {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-bottom: 8px;
        }

        .lb-chip {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .lc-pac {
            background: #f0faf2;
            color: #1a6b2f;
            border: 1px solid #d1fae5;
        }

        .lc-med {
            background: #dbeafe;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .lc-hora {
            background: #f3f4f6;
            color: #6b7280;
        }

        .lc-urg {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .lb-obs {
            font-size: 12px;
            color: #6b7280;
            font-style: italic;
            margin-top: 4px;
        }

        /* Acção */
        .lb-action {
            display: flex;
            align-items: center;
            padding: 0 18px;
            flex-shrink: 0;
        }

        .btn-registar {
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

        .btn-registar:hover {
            background: #2d9e4a;
        }

        .btn-ver-res {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            border: 2px solid #1a6b2f;
            color: #1a6b2f;
            background: #fff;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            transition: all .2s;
            white-space: nowrap;
        }

        .btn-ver-res:hover {
            background: #f0faf2;
        }

        /* Secção concluídos */
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

        .res-list {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .res-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            border-bottom: 1px solid #f3f4f6;
            text-decoration: none;
            color: inherit;
            transition: background .15s;
        }

        .res-item:last-child {
            border-bottom: none;
        }

        .res-item:hover {
            background: #f0faf2;
        }

        .res-av {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .rav-m {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        }

        .rav-f {
            background: linear-gradient(135deg, #9d174d, #ec4899);
        }

        /* Vazio */
        .lb-empty {
            text-align: center;
            padding: 64px 20px;
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
        }

        .lb-empty-icon {
            font-size: 56px;
            margin-bottom: 14px;
        }

        @media(max-width:700px) {
            .lb-stats {
                grid-template-columns: 1fr 1fr;
            }

            .lb-card-inner {
                flex-wrap: wrap;
            }

            .lb-pos {
                width: 100%;
                height: 40px;
                flex-direction: row;
                padding: 0 16px;
            }

            .lb-action {
                padding: 0 16px 16px;
            }
        }
    </style>

    {{-- HEADER --}}
    <div class="lb-header">
        <div>
            <h1 class="lb-title">🔬 Laboratório</h1>
            <p class="lb-sub">{{ \Carbon\Carbon::today()->isoFormat('dddd, D [de] MMMM [de] YYYY') }} ·
                {{ \Carbon\Carbon::now()->format('H:i') }}</p>
        </div>
    </div>

    @include('louyout.flash')

    {{-- STATS --}}
    <div class="lb-stats">
        <div class="lb-stat ls1 {{ $totalPendentes > 0 ? 'pulse' : '' }}">
            <div class="lb-stat-icon"><i class="feather icon-clock"></i></div>
            <div class="lb-stat-num">{{ $totalPendentes }}</div>
            <div class="lb-stat-lbl">Pendentes</div>
        </div>
        <div class="lb-stat ls2 {{ $totalUrgentes > 0 ? 'pulse' : '' }}">
            <div class="lb-stat-icon">⚡</div>
            <div class="lb-stat-num">{{ $totalUrgentes }}</div>
            <div class="lb-stat-lbl">Urgentes</div>
        </div>
        <div class="lb-stat ls3">
            <div class="lb-stat-icon"><i class="feather icon-check-circle"></i></div>
            <div class="lb-stat-num">{{ $totalConcluidosHoje }}</div>
            <div class="lb-stat-lbl">Concluídos Hoje</div>
        </div>
        <div class="lb-stat ls4">
            <div class="lb-stat-icon"><i class="feather icon-archive"></i></div>
            <div class="lb-stat-num">{{ $totalGeral }}</div>
            <div class="lb-stat-lbl">Total de Resultados</div>
        </div>
    </div>

    {{-- TOOLBAR --}}
    <div class="lb-toolbar">
        <div class="lb-search">
            <i class="feather icon-search"></i>
            <input type="text" id="inp-lb" placeholder="Pesquisar por paciente ou exame..." oninput="filtrarLb()">
        </div>
        <button class="lb-fbtn active" data-f="todos" onclick="setFlb(this,'todos')">Todos</button>
        <button class="lb-fbtn" data-f="urgentes" onclick="setFlb(this,'urgentes')">⚡ Urgentes</button>
    </div>

    {{-- LISTA DE PEDIDOS PENDENTES --}}
    @if ($pendentes->isEmpty())
        <div class="lb-empty">
            <div class="lb-empty-icon">🎉</div>
            <div style="font-size:18px;font-weight:700;color:#1a2e1a;">Sem pedidos pendentes!</div>
            <div style="font-size:14px;color:#6b7280;margin-top:6px;">Todos os exames foram processados.</div>
        </div>
    @else
        <div class="lb-list" id="lb-list">
            @php $pos = 0; @endphp
            @foreach ($pendentes as $p)
                @php
                    $pos++;
                    $urgente = (bool) $p->urgente;
                    $tempoEspera = \Carbon\Carbon::parse($p->hora_pedido)->diffForHumans();
                @endphp
                <a href="{{ route('laboratorio.show', $p->pedido_id) }}" class="lb-card {{ $urgente ? 'urgente' : '' }}"
                    data-urgente="{{ $urgente ? '1' : '0' }}" data-nome="{{ strtolower($p->nome) }}"
                    data-exame="{{ strtolower($p->descricao_exame) }}">
                    <div class="lb-card-inner">
                        <div class="lb-pos {{ $urgente ? 'urg' : '' }}">
                            @if ($urgente)
                                <span class="urg-icon">⚡</span>
                            @endif
                            <span class="lb-pos-num">{{ $pos }}</span>
                        </div>
                        <div class="lb-content">
                            <div class="lb-exame">
                                🔬 {{ $p->descricao_exame }}
                                @if ($urgente)
                                    <span
                                        style="background:#fee2e2;color:#991b1b;padding:2px 8px;border-radius:20px;font-size:10px;font-weight:800;">⚡
                                        URGENTE</span>
                                @endif
                            </div>
                            <div class="lb-badges">
                                <span class="lb-chip lc-pac">
                                    {{ $p->sexo === 'M' ? '♂' : '♀' }} {{ $p->nome }}
                                    @if ($p->data_nascimento)
                                        · {{ \Carbon\Carbon::parse($p->data_nascimento)->age }}a
                                    @endif
                                </span>
                                <span class="lb-chip lc-med">
                                    <i class="feather icon-user" style="font-size:10px;"></i> Dr. {{ $p->medico }}
                                </span>
                                <span class="lb-chip lc-hora">
                                    🕐 {{ $tempoEspera }}
                                </span>
                                @if ($p->numero_processo)
                                    <span class="lb-chip" style="background:#f3f4f6;color:#6b7280;">#
                                        {{ $p->numero_processo }}</span>
                                @endif
                            </div>
                            @if ($p->observacao)
                                <div class="lb-obs">💬 {{ $p->observacao }}</div>
                            @endif
                        </div>
                        <div class="lb-action">
                            <span class="btn-registar">
                                <i class="feather icon-edit-3"></i> Registar Resultado
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    {{-- CONCLUÍDOS HOJE --}}
    @if ($concluidos->isNotEmpty())
        <div class="sec-title" style="margin-top:8px;">
            <span style="width:8px;height:8px;border-radius:50%;background:#1a6b2f;display:inline-block;"></span>
            Resultados registados hoje ({{ $concluidos->count() }})
        </div>
        <div class="res-list">
            @foreach ($concluidos as $r)
                <a href="{{ route('laboratorio.show', $r->pedido_id) }}" class="res-item">
                    <div class="res-av {{ $r->sexo === 'M' ? 'rav-m' : 'rav-f' }}">
                        {{ mb_strtoupper(mb_substr($r->nome, 0, 1)) }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div
                            style="font-size:13px;font-weight:600;color:#1a2e1a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $r->nome }} — {{ $r->descricao_exame }}
                        </div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:2px;">
                            Registado por {{ $r->tecnico }} ·
                            {{ \Carbon\Carbon::parse($r->hora_resultado)->format('H:i') }}
                            @if ($r->ficheiro_path)
                                · 📎 Com ficheiro
                            @endif
                        </div>
                    </div>
                    <span
                        style="padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;background:#d1fae5;color:#065f46;">✅
                        Concluído</span>
                    <i class="feather icon-chevron-right" style="color:#d1d5db;font-size:14px;"></i>
                </a>
            @endforeach
        </div>
    @endif

    <script>
        let filtroLbEstado = 'todos';

        function setFlb(btn, f) {
            filtroLbEstado = f;
            document.querySelectorAll('.lb-fbtn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            filtrarLb();
        }

        function filtrarLb() {
            const q = document.getElementById('inp-lb').value.toLowerCase().trim();
            document.querySelectorAll('#lb-list .lb-card').forEach(c => {
                const nome = c.dataset.nome || '';
                const exame = c.dataset.exame || '';
                const urg = c.dataset.urgente || '0';
                const matchQ = !q || nome.includes(q) || exame.includes(q);
                const matchF = filtroLbEstado === 'todos' || (filtroLbEstado === 'urgentes' && urg === '1');
                c.style.display = matchQ && matchF ? '' : 'none';
            });
        }
    </script>
@endsection
