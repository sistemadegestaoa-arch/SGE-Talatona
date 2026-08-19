@extends('louyout.app')
@section('conteodo')

    @php
        $hoje = \Carbon\Carbon::today();
        $totalPend = \DB::table('pedido_exame')->where('estado', 'pendente')->count();
        $totalUrg = \DB::table('pedido_exame')->where('estado', 'pendente')->where('urgente', 1)->count();
        $concluidosHoje = \DB::table('resultado_exame')->whereDate('created_at', $hoje)->count();
        $totalGeral = \DB::table('resultado_exame')->count();

        // Próximo a processar (urgentes primeiro)
        $proximo = \DB::table('pedido_exame')
            ->join('consulta', 'consulta.id', '=', 'pedido_exame.consulta_id')
            ->join('episodio', 'episodio.id', '=', 'consulta.episodio_id')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->join('users', 'users.id', '=', 'pedido_exame.medico_id')
            ->where('pedido_exame.estado', 'pendente')
            ->orderByDesc('pedido_exame.urgente')
            ->orderBy('pedido_exame.id', 'asc')
            ->select(
                'pedido_exame.id as pedido_id',
                'pedido_exame.descricao_exame',
                'pedido_exame.urgente',
                'pedido_exame.created_at as hora',
                'pedido_exame.observacao',
                'paciente.nome',
                'paciente.sexo',
                'paciente.data_nascimento',
                'users.name as medico',
            )
            ->first();

        // Últimos 7 dias — resultados por dia
        $porDia = \DB::table('resultado_exame')
            ->selectRaw('DATE(created_at) as dia, COUNT(*) as total')
            ->whereBetween('created_at', [\Carbon\Carbon::today()->subDays(6), \Carbon\Carbon::now()])
            ->groupBy('dia')
            ->orderBy('dia')
            ->get()
            ->keyBy('dia');

        // Últimos concluídos
        $recentes = \DB::table('resultado_exame')
            ->join('pedido_exame', 'pedido_exame.id', '=', 'resultado_exame.pedido_exame_id')
            ->join('consulta', 'consulta.id', '=', 'pedido_exame.consulta_id')
            ->join('episodio', 'episodio.id', '=', 'consulta.episodio_id')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->join('users', 'users.id', '=', 'resultado_exame.tecnico_id')
            ->orderByDesc('resultado_exame.id')
            ->limit(6)
            ->select(
                'pedido_exame.id as pedido_id',
                'pedido_exame.descricao_exame',
                'pedido_exame.urgente',
                'paciente.nome',
                'paciente.sexo',
                'resultado_exame.created_at',
                'users.name as tecnico',
                'resultado_exame.ficheiro_path',
            )
            ->get();

        // Pedidos urgentes pendentes
        $urgentes = \DB::table('pedido_exame')
            ->join('consulta', 'consulta.id', '=', 'pedido_exame.consulta_id')
            ->join('episodio', 'episodio.id', '=', 'consulta.episodio_id')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->join('users', 'users.id', '=', 'pedido_exame.medico_id')
            ->where('pedido_exame.estado', 'pendente')
            ->where('pedido_exame.urgente', 1)
            ->orderBy('pedido_exame.id', 'asc')
            ->select(
                'pedido_exame.id as pedido_id',
                'pedido_exame.descricao_exame',
                'pedido_exame.created_at as hora',
                'paciente.nome',
                'paciente.sexo',
                'users.name as medico',
            )
            ->get();
    @endphp

    <style>
        .lb-wrap {
            max-width: 100%;
        }

        .lb-hdr {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
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
            padding: 20px 16px 16px;
            color: #fff;
            position: relative;
            overflow: hidden;
            transition: transform .2s;
            cursor: default;
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
            font-size: 36px;
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
            font-size: 30px;
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
        }

        /* Banner próximo */
        .lb-next-banner {
            border-radius: 18px;
            padding: 24px 28px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .2);
        }

        .lb-next-banner.normal {
            background: linear-gradient(135deg, #1e3a5f, #1d4ed8);
        }

        .lb-next-banner.urgente {
            background: linear-gradient(135deg, #7f1d1d, #dc2626);
        }

        .lb-next-banner::before {
            content: '';
            position: absolute;
            right: -30px;
            top: -30px;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
        }

        .lb-next-banner::after {
            content: '';
            position: absolute;
            right: 60px;
            bottom: -40px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .03);
        }

        .lb-nxt-av {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 900;
            flex-shrink: 0;
            background: rgba(255, 255, 255, .2);
        }

        .lb-nxt-tag {
            font-size: 11px;
            font-weight: 700;
            opacity: .7;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 4px;
        }

        .lb-nxt-exm {
            font-size: 20px;
            font-weight: 800;
        }

        .lb-nxt-meta {
            font-size: 13px;
            opacity: .8;
            margin-top: 5px;
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .btn-proc {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 26px;
            background: #fff;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 800;
            text-decoration: none;
            flex-shrink: 0;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .15);
            transition: transform .2s, box-shadow .2s;
        }

        .btn-proc:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, .2);
            text-decoration: none;
        }

        .btn-proc.blue {
            color: #1d4ed8;
        }

        .btn-proc.red {
            color: #dc2626;
        }

        /* Grid inferior */
        .lb-grid2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .lb-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
            overflow: hidden;
        }

        .lb-card-head {
            padding: 14px 20px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .lb-card-head i {
            font-size: 16px;
            color: #1a6b2f;
        }

        .lb-card-head span {
            font-size: 14px;
            font-weight: 700;
            color: #1a2e1a;
        }

        .lb-card-head .cnt {
            margin-left: auto;
            background: #fee2e2;
            color: #991b1b;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
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
            background: linear-gradient(180deg, #1d4ed8, #3b82f6);
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
            color: #1d4ed8;
            font-weight: 800;
        }

        /* Lista urgentes */
        .urg-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            border-bottom: 1px solid #f3f4f6;
            text-decoration: none;
            color: inherit;
            transition: background .15s;
        }

        .urg-item:last-child {
            border-bottom: none;
        }

        .urg-item:hover {
            background: #fff8f8;
        }

        .urg-av {
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

        .uav-m {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        }

        .uav-f {
            background: linear-gradient(135deg, #9d174d, #ec4899);
        }

        /* Lista recentes */
        .res-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
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

        /* Vazio */
        .lb-empty {
            text-align: center;
            padding: 28px;
            color: #9ca3af;
            font-size: 13px;
        }

        @media(max-width:900px) {
            .lb-stats {
                grid-template-columns: 1fr 1fr;
            }

            .lb-grid2 {
                grid-template-columns: 1fr;
            }

            .lb-next-banner {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    <div class="lb-wrap">

        {{-- Header --}}
        <div class="lb-hdr">
            <div>
                <h1 class="lb-title">🔬 Laboratório</h1>
                <p class="lb-sub">{{ $hoje->isoFormat('dddd, D [de] MMMM [de] YYYY') }} ·
                    {{ \Carbon\Carbon::now()->format('H:i') }}</p>
            </div>
            <a href="{{ route('laboratorio.index') }}"
                style="display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border:2px solid #1a6b2f;border-radius:10px;color:#1a6b2f;font-size:13px;font-weight:600;text-decoration:none;transition:all .2s;"
                onmouseover="this.style.background='#1a6b2f';this.style.color='#fff'"
                onmouseout="this.style.background='transparent';this.style.color='#1a6b2f'">
                <i class="feather icon-list"></i> Ver Todos os Pedidos
            </a>
        </div>

        {{-- Stats --}}
        @php $pct = $totalGeral > 0 ? min(100, round($concluidosHoje / max($totalPend + $concluidosHoje, 1) * 100)) : 0; @endphp
        <div class="lb-stats">
            <div class="lb-stat ls1 {{ $totalPend > 0 ? 'pulse' : '' }}">
                <div class="lb-stat-icon"><i class="feather icon-clock"></i></div>
                <div class="lb-stat-num">{{ $totalPend }}</div>
                <div class="lb-stat-lbl">Pendentes</div>
                @if ($totalPend > 0)
                    <div class="prog-bar-wrap">
                        <div class="prog-bar" style="width:100%;"></div>
                    </div>
                @endif
            </div>
            <div class="lb-stat ls2 {{ $totalUrg > 0 ? 'pulse' : '' }}">
                <div class="lb-stat-icon"><span style="font-size:26px;">⚡</span></div>
                <div class="lb-stat-num">{{ $totalUrg }}</div>
                <div class="lb-stat-lbl">Urgentes Agora</div>
            </div>
            <div class="lb-stat ls3">
                <div class="lb-stat-icon"><i class="feather icon-check-circle"></i></div>
                <div class="lb-stat-num">{{ $concluidosHoje }}</div>
                <div class="lb-stat-lbl">Concluídos Hoje</div>
                <div class="prog-bar-wrap">
                    <div class="prog-bar" style="width:{{ $pct }}%;"></div>
                </div>
            </div>
            <div class="lb-stat ls4">
                <div class="lb-stat-icon"><i class="feather icon-archive"></i></div>
                <div class="lb-stat-num">{{ $totalGeral }}</div>
                <div class="lb-stat-lbl">Total de Resultados</div>
            </div>
        </div>

        {{-- Próximo pedido --}}
        @if ($proximo)
            <div class="lb-next-banner {{ $proximo->urgente ? 'urgente' : 'normal' }}">
                <div class="lb-nxt-av">{{ mb_strtoupper(mb_substr($proximo->nome, 0, 1)) }}</div>
                <div style="flex:1;min-width:0;">
                    <div class="lb-nxt-tag">
                        {{ $proximo->urgente ? '⚡ PEDIDO URGENTE — Processar Imediatamente' : '🔬 Próximo Pedido' }}
                    </div>
                    <div class="lb-nxt-exm">{{ $proximo->descricao_exame }}</div>
                    <div class="lb-nxt-meta">
                        <span>👤 {{ $proximo->nome }}
                            @if ($proximo->data_nascimento)
                                · {{ \Carbon\Carbon::parse($proximo->data_nascimento)->age }}a
                            @endif
                        </span>
                        <span>🩺 Dr. {{ $proximo->medico }}</span>
                        <span>🕐 {{ \Carbon\Carbon::parse($proximo->hora)->diffForHumans() }}</span>
                    </div>
                    @if ($proximo->observacao)
                        <div style="margin-top:6px;font-size:12px;opacity:.8;">💬 {{ $proximo->observacao }}</div>
                    @endif
                </div>
                <a href="{{ route('laboratorio.show', $proximo->pedido_id) }}"
                    class="btn-proc {{ $proximo->urgente ? 'red' : 'blue' }}">
                    <i class="feather icon-edit-3"></i> Registar Resultado
                </a>
            </div>
        @else
            <div
                style="background:#f0faf2;border-radius:16px;border:2px dashed #a7f3c0;padding:28px;text-align:center;margin-bottom:24px;">
                <div style="font-size:44px;margin-bottom:10px;">🎉</div>
                <div style="font-size:16px;font-weight:700;color:#1a2e1a;">Sem pedidos pendentes!</div>
                <div style="font-size:13px;color:#6b7280;margin-top:4px;">Todos os exames foram processados.</div>
            </div>
        @endif

        {{-- Grid: Gráfico + Urgentes --}}
        <div class="lb-grid2">

            {{-- Actividade dos últimos 7 dias --}}
            <div class="lb-card">
                <div class="lb-card-head">
                    <i class="feather icon-trending-up"></i>
                    <span>Resultados — Últimos 7 Dias</span>
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
                                    style="height:{{ $h }}px;{{ $d['hoje'] ? 'background:linear-gradient(180deg,#1a6b2f,#2d9e4a);' : '' }}">
                                </div>
                                <div class="bar-lbl" style="{{ $d['hoje'] ? 'color:#1a6b2f;font-weight:800;' : '' }}">
                                    {{ $d['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Urgentes pendentes --}}
            <div class="lb-card">
                <div class="lb-card-head">
                    <i class="feather icon-alert-triangle" style="color:#dc2626;"></i>
                    <span style="color:#dc2626;">Urgentes Pendentes</span>
                    @if ($urgentes->count() > 0)
                        <span class="cnt">{{ $urgentes->count() }}</span>
                    @endif
                </div>
                @if ($urgentes->isEmpty())
                    <div class="lb-empty">
                        <i class="feather icon-check-circle"
                            style="font-size:28px;color:#6ee7b7;display:block;margin-bottom:6px;"></i>
                        Nenhum pedido urgente.
                    </div>
                @else
                    @foreach ($urgentes as $u)
                        <a href="{{ route('laboratorio.show', $u->pedido_id) }}" class="urg-item">
                            <span style="font-size:18px;">⚡</span>
                            <div class="urg-av {{ $u->sexo === 'M' ? 'uav-m' : 'uav-f' }}">
                                {{ mb_strtoupper(mb_substr($u->nome, 0, 1)) }}
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div
                                    style="font-size:13px;font-weight:600;color:#1a2e1a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $u->nome }}
                                </div>
                                <div
                                    style="font-size:11px;color:#9ca3af;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $u->descricao_exame }} · Dr. {{ $u->medico }}
                                </div>
                            </div>
                            <div style="font-size:11px;color:#9ca3af;white-space:nowrap;">
                                {{ \Carbon\Carbon::parse($u->hora)->format('H:i') }}
                            </div>
                            <i class="feather icon-chevron-right" style="color:#d1d5db;font-size:14px;"></i>
                        </a>
                    @endforeach
                @endif
            </div>

        </div>

        {{-- Últimos resultados --}}
        @if ($recentes->isNotEmpty())
            <div
                style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.8px;margin:8px 0 12px;display:flex;align-items:center;gap:8px;">
                <span style="width:8px;height:8px;border-radius:50%;background:#1a6b2f;display:inline-block;"></span>
                Últimos Resultados Registados
            </div>
            <div class="lb-card">
                @foreach ($recentes as $r)
                    <a href="{{ route('laboratorio.show', $r->pedido_id) }}" class="res-item">
                        <div
                            style="width:34px;height:34px;border-radius:50%;background:{{ $r->sexo === 'M' ? 'linear-gradient(135deg,#1e3a8a,#3b82f6)' : 'linear-gradient(135deg,#9d174d,#ec4899)' }};display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0;">
                            {{ mb_strtoupper(mb_substr($r->nome, 0, 1)) }}
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div
                                style="font-size:13px;font-weight:600;color:#1a2e1a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $r->nome }} — {{ $r->descricao_exame }}
                                @if ($r->urgente)
                                    <span
                                        style="background:#fee2e2;color:#991b1b;padding:1px 6px;border-radius:20px;font-size:10px;font-weight:700;margin-left:4px;">⚡</span>
                                @endif
                            </div>
                            <div style="font-size:11px;color:#9ca3af;margin-top:2px;">
                                Por {{ $r->tecnico }} · {{ \Carbon\Carbon::parse($r->created_at)->diffForHumans() }}
                                @if ($r->ficheiro_path)
                                    · 📎
                                @endif
                            </div>
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
