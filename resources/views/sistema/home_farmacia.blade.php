@extends('louyout.app')
@section('conteodo')

    @php
        $hoje = \Carbon\Carbon::today();
        $recPend = \DB::table('receita')->where('estado', 'pendente')->count();
        $dispHoje = \DB::table('receita')->where('estado', 'dispensada')->whereDate('updated_at', $hoje)->count();
        $atendHoje = \DB::table('atendimento')->whereDate('data', $hoje)->count();
        $totalGeral = \DB::table('receita')->where('estado', 'dispensada')->count();

        // Próxima receita pendente
        $proxima = \DB::table('receita')
            ->join('consulta', 'consulta.id', '=', 'receita.consulta_id')
            ->join('episodio', 'episodio.id', '=', 'consulta.episodio_id')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->join('users', 'users.id', '=', 'receita.medico_id')
            ->where('receita.estado', 'pendente')
            ->orderBy('receita.id', 'asc')
            ->select(
                'receita.id as receita_id',
                'receita.created_at as hora',
                'paciente.nome',
                'paciente.sexo',
                'paciente.data_nascimento',
                'users.name as medico',
                'receita.observacao',
            )
            ->first();

        // Nº de medicamentos da próxima receita
        if ($proxima) {
            $proxima->num_itens = \DB::table('receita_item')->where('receita_id', $proxima->receita_id)->count();
        }

        // Últimas dispensas
        $recentes = \DB::table('receita')
            ->join('consulta', 'consulta.id', '=', 'receita.consulta_id')
            ->join('episodio', 'episodio.id', '=', 'consulta.episodio_id')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->join('users', 'users.id', '=', 'receita.medico_id')
            ->where('receita.estado', 'dispensada')
            ->orderByDesc('receita.updated_at')
            ->limit(5)
            ->select(
                'receita.id as receita_id',
                'paciente.nome',
                'paciente.sexo',
                'users.name as medico',
                'receita.updated_at as hora_disp',
            )
            ->get();

        // Últimos 7 dias — dispensas por dia
        $porDia = \DB::table('receita')
            ->selectRaw('DATE(updated_at) as dia, COUNT(*) as total')
            ->where('estado', 'dispensada')
            ->whereBetween('updated_at', [\Carbon\Carbon::today()->subDays(6), \Carbon\Carbon::now()])
            ->groupBy('dia')
            ->orderBy('dia')
            ->get()
            ->keyBy('dia');

        // Produtos com stock baixo na farmácia
        $depId = Auth::user()->departamento_id;
        $stockBaixo = \DB::table('produto')
            ->join('estoque', 'estoque.produto_id', '=', 'produto.id')
            ->select(
                'produto.id',
                'produto.produto',
                'produto.stokminimo',
                \DB::raw('SUM(estoque.entrada)-SUM(estoque.saida) as stock'),
            )
            ->where('estoque.departamento_id', $depId)
            ->groupBy('produto.id', 'produto.produto', 'produto.stokminimo')
            ->havingRaw('SUM(estoque.entrada)-SUM(estoque.saida) <= produto.stokminimo')
            ->orderBy('produto.produto')
            ->limit(5)
            ->get();

        $pct = $recPend + $dispHoje > 0 ? round(($dispHoje / ($recPend + $dispHoje)) * 100) : 0;
    @endphp

    <style>
        .fm-wrap {
            max-width: 100%;
        }

        .fm-hdr {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .fm-title {
            font-size: 22px;
            font-weight: 800;
            color: #1a2e1a;
            margin: 0;
        }

        .fm-sub {
            font-size: 13px;
            color: #6b7280;
            margin: 4px 0 0;
        }

        /* Stats */
        .fm-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .fm-stat {
            border-radius: 16px;
            padding: 20px 16px 16px;
            color: #fff;
            position: relative;
            overflow: hidden;
            transition: transform .2s;
            cursor: default;
        }

        .fm-stat:hover {
            transform: translateY(-3px);
        }

        .fm-stat.fs1 {
            background: linear-gradient(135deg, #c0620a, #f08030);
        }

        .fm-stat.fs2 {
            background: linear-gradient(135deg, #1a6b2f, #2d9e4a);
        }

        .fm-stat.fs3 {
            background: linear-gradient(135deg, #0d7a6b, #14b89e);
        }

        .fm-stat.fs4 {
            background: linear-gradient(135deg, #334155, #64748b);
        }

        .fm-stat-num {
            font-size: 36px;
            font-weight: 900;
            line-height: 1;
        }

        .fm-stat-lbl {
            font-size: 11px;
            font-weight: 600;
            opacity: .85;
            margin-top: 3px;
        }

        .fm-stat-icon {
            position: absolute;
            right: 12px;
            top: 12px;
            font-size: 28px;
            opacity: .15;
        }

        .prog-bar-wrap {
            height: 6px;
            background: rgba(255, 255, 255, .2);
            border-radius: 99px;
            margin-top: 10px;
            overflow: hidden;
        }

        .prog-bar {
            height: 100%;
            background: rgba(255, 255, 255, .7);
            border-radius: 99px;
            transition: width .8s ease;
        }

        /* Banner próxima receita */
        .fm-next {
            background: linear-gradient(135deg, #0f3d1e, #1a6b2f);
            border-radius: 18px;
            padding: 24px 28px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(26, 107, 47, .3);
        }

        .fm-next::before {
            content: '';
            position: absolute;
            right: -30px;
            top: -30px;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
        }

        .fm-next::after {
            content: '';
            position: absolute;
            right: 60px;
            bottom: -30px;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .03);
        }

        .fm-nxt-av {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 900;
            flex-shrink: 0;
            background: rgba(255, 255, 255, .2);
        }

        .fm-nxt-tag {
            font-size: 11px;
            font-weight: 700;
            opacity: .7;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 4px;
        }

        .fm-nxt-nome {
            font-size: 20px;
            font-weight: 800;
        }

        .fm-nxt-meta {
            font-size: 13px;
            opacity: .8;
            margin-top: 5px;
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .btn-dispensar-now {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 26px;
            background: #fff;
            color: #1a6b2f;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 800;
            text-decoration: none;
            flex-shrink: 0;
            white-space: nowrap;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .15);
            transition: transform .2s, box-shadow .2s;
        }

        .btn-dispensar-now:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, .2);
            color: #1a6b2f;
            text-decoration: none;
        }

        /* Grid 2 colunas */
        .fm-grid2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .fm-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
            overflow: hidden;
        }

        .fm-card-head {
            padding: 14px 20px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .fm-card-head i {
            font-size: 16px;
            color: #1a6b2f;
        }

        .fm-card-head span {
            font-size: 14px;
            font-weight: 700;
            color: #1a2e1a;
        }

        /* Barra gráfico */
        .bar-chart {
            display: flex;
            align-items: flex-end;
            gap: 6px;
            height: 90px;
            padding: 0 4px;
        }

        .bar-day {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
        }

        .bar-fill {
            background: linear-gradient(180deg, #1a6b2f, #2d9e4a);
            border-radius: 6px 6px 0 0;
            width: 100%;
            min-height: 4px;
            transition: height .6s ease;
        }

        .bar-lbl {
            font-size: 10px;
            color: #9ca3af;
            font-weight: 600;
        }

        .bar-val {
            font-size: 11px;
            color: #1a6b2f;
            font-weight: 800;
        }

        /* Lista items */
        .fm-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 20px;
            border-bottom: 1px solid #f3f4f6;
            text-decoration: none;
            color: inherit;
            transition: background .15s;
        }

        .fm-item:last-child {
            border-bottom: none;
        }

        .fm-item:hover {
            background: #f0faf2;
        }

        .fi-av {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .fav-m {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        }

        .fav-f {
            background: linear-gradient(135deg, #9d174d, #ec4899);
        }

        /* Stock baixo */
        .stock-low-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            border-bottom: 1px solid #f3f4f6;
        }

        .stock-low-item:last-child {
            border-bottom: none;
        }

        .sli-nome {
            font-size: 13px;
            font-weight: 600;
            color: #1a2e1a;
        }

        .sli-stock {
            font-size: 12px;
        }

        @media(max-width:900px) {
            .fm-stats {
                grid-template-columns: 1fr 1fr;
            }

            .fm-grid2 {
                grid-template-columns: 1fr;
            }

            .fm-next {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    <div class="fm-wrap">

        {{-- Header --}}
        <div class="fm-hdr">
            <div>
                <h1 class="fm-title">Olá, {{ Auth::user()->name }} 💊</h1>
                <p class="fm-sub">{{ $hoje->isoFormat('dddd, D [de] MMMM [de] YYYY') }} ·
                    {{ \Carbon\Carbon::now()->format('H:i') }}</p>
            </div>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <a href="{{ route('receitas.index') }}"
                    style="display:inline-flex;align-items:center;gap:6px;padding:10px 20px;background:#1a6b2f;color:#fff;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;transition:background .2s;"
                    onmouseover="this.style.background='#2d9e4a'" onmouseout="this.style.background='#1a6b2f'">
                    <i class="feather icon-file-text"></i> Receitas Pendentes
                </a>
                <a href="{{ route('atendimento.create') }}"
                    style="display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border:2px solid #e5e7eb;background:#fff;color:#374151;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;transition:all .2s;"
                    onmouseover="this.style.borderColor='#1a6b2f';this.style.color='#1a6b2f'"
                    onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#374151'">
                    <i class="feather icon-plus-circle"></i> Atendimento Manual
                </a>
            </div>
        </div>

        {{-- Stats --}}
        <div class="fm-stats">
            <div class="fm-stat fs1 {{ $recPend > 0 ? 'pulse' : '' }}">
                <div class="fm-stat-icon"><i class="feather icon-clock"></i></div>
                <div class="fm-stat-num">{{ $recPend }}</div>
                <div class="fm-stat-lbl">Receitas Pendentes</div>
            </div>
            <div class="fm-stat fs2">
                <div class="fm-stat-icon"><i class="feather icon-check-circle"></i></div>
                <div class="fm-stat-num">{{ $dispHoje }}</div>
                <div class="fm-stat-lbl">Dispensadas Hoje</div>
                <div class="prog-bar-wrap">
                    <div class="prog-bar" style="width:{{ $pct }}%;"></div>
                </div>
            </div>
            <div class="fm-stat fs3">
                <div class="fm-stat-icon"><i class="feather icon-user-check"></i></div>
                <div class="fm-stat-num">{{ $atendHoje }}</div>
                <div class="fm-stat-lbl">Atendimentos Hoje</div>
            </div>
            <div class="fm-stat fs4">
                <div class="fm-stat-icon"><i class="feather icon-archive"></i></div>
                <div class="fm-stat-num">{{ $totalGeral }}</div>
                <div class="fm-stat-lbl">Total Dispensadas</div>
            </div>
        </div>

        {{-- Próxima receita --}}
        @if ($proxima)
            <div class="fm-next">
                <div class="fm-nxt-av">{{ mb_strtoupper(mb_substr($proxima->nome, 0, 1)) }}</div>
                <div style="flex:1;min-width:0;">
                    <div class="fm-nxt-tag">💊 Próxima Receita Pendente</div>
                    <div class="fm-nxt-nome">{{ $proxima->nome }}</div>
                    <div class="fm-nxt-meta">
                        @if ($proxima->data_nascimento)
                            <span>{{ \Carbon\Carbon::parse($proxima->data_nascimento)->age }} anos</span>
                        @endif
                        <span>🩺 Dr. {{ $proxima->medico }}</span>
                        <span>💊 {{ $proxima->num_itens }} medicamento(s)</span>
                        <span>🕐 {{ \Carbon\Carbon::parse($proxima->hora)->diffForHumans() }}</span>
                    </div>
                    @if ($proxima->observacao)
                        <div style="margin-top:6px;font-size:12px;opacity:.75;">💬 {{ $proxima->observacao }}</div>
                    @endif
                </div>
                <a href="{{ route('receitas.show', $proxima->receita_id) }}" class="btn-dispensar-now">
                    <i class="feather icon-package"></i> Dispensar Agora
                </a>
            </div>
        @else
            <div
                style="background:#f0faf2;border-radius:16px;border:2px dashed #a7f3c0;padding:28px;text-align:center;margin-bottom:24px;">
                <div style="font-size:44px;margin-bottom:10px;">🎉</div>
                <div style="font-size:16px;font-weight:700;color:#1a2e1a;">Sem receitas pendentes!</div>
                <div style="font-size:13px;color:#6b7280;margin-top:4px;">Todas as receitas foram dispensadas.</div>
            </div>
        @endif

        {{-- Grid: Gráfico + Stock baixo --}}
        <div class="fm-grid2">

            {{-- Actividade 7 dias --}}
            <div class="fm-card">
                <div class="fm-card-head">
                    <i class="feather icon-trending-up"></i>
                    <span>Dispensas — Últimos 7 Dias</span>
                </div>
                <div style="padding:16px 20px;">
                    @php
                        $dias = [];
                        $maxV = 1;
                        for ($i = 6; $i >= 0; $i--) {
                            $d = \Carbon\Carbon::today()->subDays($i);
                            $key = $d->format('Y-m-d');
                            $tot = $porDia->get($key)->total ?? 0;
                            $dias[] = ['label' => $d->isoFormat('ddd'), 'val' => $tot, 'hoje' => $i === 0];
                            if ($tot > $maxV) {
                                $maxV = $tot;
                            }
                        }
                    @endphp
                    <div class="bar-chart">
                        @foreach ($dias as $d)
                            @php $h = $maxV > 0 ? max(4, round(($d['val'] / $maxV) * 80)) : 4; @endphp
                            <div class="bar-day">
                                <div class="bar-val">{{ $d['val'] ?: '' }}</div>
                                <div class="bar-fill"
                                    style="height:{{ $h }}px;{{ $d['hoje'] ? 'background:linear-gradient(180deg,#c0620a,#f08030);' : '' }}">
                                </div>
                                <div class="bar-lbl" style="{{ $d['hoje'] ? 'color:#c0620a;font-weight:800;' : '' }}">
                                    {{ $d['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Stock em baixo --}}
            <div class="fm-card">
                <div class="fm-card-head">
                    <i class="feather icon-alert-triangle" style="color:#dc2626;"></i>
                    <span style="color:#dc2626;">Stock Abaixo do Mínimo</span>
                    @if ($stockBaixo->count() > 0)
                        <span
                            style="margin-left:auto;background:#fee2e2;color:#991b1b;font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px;">{{ $stockBaixo->count() }}</span>
                    @endif
                </div>
                @if ($stockBaixo->isEmpty())
                    <div style="padding:28px;text-align:center;color:#9ca3af;font-size:13px;">
                        <i class="feather icon-check-circle"
                            style="font-size:28px;color:#6ee7b7;display:block;margin-bottom:6px;"></i>
                        Stock dentro dos limites.
                    </div>
                @else
                    @foreach ($stockBaixo as $s)
                        <div class="stock-low-item">
                            <div>
                                <div class="sli-nome">{{ $s->produto }}</div>
                                <div style="font-size:11px;color:#9ca3af;">Mínimo: {{ $s->stokminimo }}</div>
                            </div>
                            <span
                                style="padding:3px 10px;border-radius:20px;font-size:12px;font-weight:800;background:{{ $s->stock <= 0 ? '#fee2e2' : '#fef3c7' }};color:{{ $s->stock <= 0 ? '#991b1b' : '#92400e' }};">
                                {{ $s->stock <= 0 ? 'Esgotado' : $s->stock }}
                            </span>
                        </div>
                    @endforeach
                @endif
            </div>

        </div>

        {{-- Últimas dispensas --}}
        @if ($recentes->isNotEmpty())
            <div
                style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.8px;margin:8px 0 12px;display:flex;align-items:center;gap:8px;">
                <span style="width:8px;height:8px;border-radius:50%;background:#1a6b2f;display:inline-block;"></span>
                Últimas Dispensas
            </div>
            <div class="fm-card">
                @foreach ($recentes as $r)
                    <a href="{{ route('receitas.show', $r->receita_id) }}" class="fm-item">
                        <div class="fi-av {{ $r->sexo === 'M' ? 'fav-m' : 'fav-f' }}">
                            {{ mb_strtoupper(mb_substr($r->nome, 0, 1)) }}</div>
                        <div style="flex:1;min-width:0;">
                            <div
                                style="font-size:13px;font-weight:600;color:#1a2e1a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $r->nome }}</div>
                            <div style="font-size:11px;color:#9ca3af;margin-top:2px;">Dr. {{ $r->medico }} ·
                                {{ \Carbon\Carbon::parse($r->hora_disp)->diffForHumans() }}</div>
                        </div>
                        <span
                            style="padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;background:#d1fae5;color:#065f46;flex-shrink:0;">✅</span>
                        <i class="feather icon-chevron-right" style="color:#d1d5db;font-size:14px;"></i>
                    </a>
                @endforeach
            </div>
        @endif

    </div>

@endsection
