@extends('louyout.app')
@section('conteodo')

    @php
        $hoje = \Carbon\Carbon::today();
        $totalHoje = \DB::table('episodio')->whereDate('data', $hoje)->count();
        $emEspera = \DB::table('episodio')->whereDate('data', $hoje)->where('estado', 'em_espera')->count();
        $emConsulta = \DB::table('episodio')
            ->whereDate('data', $hoje)
            ->whereIn('estado', ['em_consulta', 'aguarda_exame'])
            ->count();
        $concluidos = \DB::table('episodio')->whereDate('data', $hoje)->where('estado', 'concluido')->count();

        // Último paciente triado — para o banner
        $ultimo = \DB::table('episodio')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->leftJoin('triagem', 'triagem.episodio_id', '=', 'episodio.id')
            ->select(
                'episodio.id as episodio_id',
                'episodio.estado',
                'episodio.created_at',
                'paciente.nome',
                'paciente.sexo',
                'paciente.data_nascimento',
                'triagem.temperatura',
                'triagem.pressao_arterial',
                'triagem.observacao as obs',
            )
            ->whereDate('episodio.data', $hoje)
            ->where('episodio.estado', 'em_espera')
            ->orderBy('episodio.id', 'asc')
            ->first();

        // Lista dos últimos 8 episódios
        $episodios = \DB::table('episodio')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->leftJoin('triagem', 'triagem.episodio_id', '=', 'episodio.id')
            ->select(
                'episodio.id as episodio_id',
                'episodio.estado',
                'episodio.created_at',
                'paciente.nome',
                'paciente.sexo',
                'paciente.data_nascimento',
                'triagem.temperatura',
                'triagem.pressao_arterial',
            )
            ->whereDate('episodio.data', $hoje)
            ->orderBy('episodio.id', 'desc')
            ->limit(8)
            ->get();

        // Últimos 7 dias — episódios por dia
        $porDia = \DB::table('episodio')
            ->selectRaw('DATE(data) as dia, COUNT(*) as total')
            ->whereBetween('data', [\Carbon\Carbon::today()->subDays(6), $hoje])
            ->groupBy('dia')
            ->orderBy('dia')
            ->get()
            ->keyBy('dia');

        $pct = $totalHoje > 0 ? round(($concluidos / $totalHoje) * 100) : 0;
    @endphp

    <style>
        .tr-home {
            max-width: 100%;
        }

        .tr-hdr {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .tr-title {
            font-size: 22px;
            font-weight: 800;
            color: #1a2e1a;
            margin: 0;
        }

        .tr-sub {
            font-size: 13px;
            color: #6b7280;
            margin: 4px 0 0;
        }

        /* Stats */
        .tr-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .tr-stat {
            border-radius: 16px;
            padding: 20px 16px 16px;
            color: #fff;
            position: relative;
            overflow: hidden;
            transition: transform .2s;
            cursor: default;
        }

        .tr-stat:hover {
            transform: translateY(-3px);
        }

        .tr-stat.ts1 {
            background: linear-gradient(135deg, #0d7a6b, #14b89e);
        }

        .tr-stat.ts2 {
            background: linear-gradient(135deg, #c0620a, #f08030);
        }

        .tr-stat.ts3 {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        }

        .tr-stat.ts4 {
            background: linear-gradient(135deg, #334155, #64748b);
        }

        .tr-stat-num {
            font-size: 36px;
            font-weight: 900;
            line-height: 1;
        }

        .tr-stat-lbl {
            font-size: 11px;
            font-weight: 600;
            opacity: .85;
            margin-top: 3px;
        }

        .tr-stat-icon {
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

        /* Banner próximo em espera */
        .tr-next {
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

        .tr-next::before {
            content: '';
            position: absolute;
            right: -30px;
            top: -30px;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
        }

        .tr-next::after {
            content: '';
            position: absolute;
            right: 60px;
            bottom: -30px;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .03);
        }

        .tr-nxt-av {
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

        .tr-nxt-tag {
            font-size: 11px;
            font-weight: 700;
            opacity: .7;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 4px;
        }

        .tr-nxt-nome {
            font-size: 20px;
            font-weight: 800;
        }

        .tr-nxt-meta {
            font-size: 13px;
            opacity: .8;
            margin-top: 5px;
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .btn-ver-tri {
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

        .btn-ver-tri:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, .2);
            color: #1a6b2f;
            text-decoration: none;
        }

        /* Grid 2 colunas */
        .tr-grid2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .tr-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
            overflow: hidden;
        }

        .tr-card-head {
            padding: 14px 20px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tr-card-head i {
            font-size: 16px;
            color: #1a6b2f;
        }

        .tr-card-head span {
            font-size: 14px;
            font-weight: 700;
            color: #1a2e1a;
        }

        /* Gráfico barras */
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
            background: linear-gradient(180deg, #0d7a6b, #14b89e);
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
            color: #0d7a6b;
            font-weight: 800;
        }

        /* Lista pacientes */
        .tr-pac-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 20px;
            border-bottom: 1px solid #f3f4f6;
            text-decoration: none;
            color: inherit;
            transition: background .15s;
        }

        .tr-pac-item:last-child {
            border-bottom: none;
        }

        .tr-pac-item:hover {
            background: #f0faf2;
        }

        .tr-av {
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

        .tav-m {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        }

        .tav-f {
            background: linear-gradient(135deg, #9d174d, #ec4899);
        }

        /* Estado pill */
        .ep-pill {
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .ep-espera {
            background: #fef3c7;
            color: #92400e;
        }

        .ep-consult {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .ep-exame {
            background: #ede9fe;
            color: #5b21b6;
        }

        .ep-conc {
            background: #d1fae5;
            color: #065f46;
        }

        @media(max-width:900px) {
            .tr-stats {
                grid-template-columns: 1fr 1fr;
            }

            .tr-grid2 {
                grid-template-columns: 1fr;
            }

            .tr-next {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    <div class="tr-home">

        {{-- Header --}}
        <div class="tr-hdr">
            <div>
                <h1 class="tr-title">Olá, {{ Auth::user()->name }} 🏥</h1>
                <p class="tr-sub">{{ $hoje->isoFormat('dddd, D [de] MMMM [de] YYYY') }} ·
                    {{ \Carbon\Carbon::now()->format('H:i') }}</p>
            </div>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <a href="{{ route('triagem.estatisticas') }}"
                    style="display:inline-flex;align-items:center;gap:6px;padding:10px 18px;border:2px solid #e5e7eb;background:#fff;color:#374151;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;transition:all .2s;"
                    onmouseover="this.style.borderColor='#1a6b2f';this.style.color='#1a6b2f'"
                    onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#374151'">
                    <i class="feather icon-bar-chart-2"></i> Estatísticas
                </a>
                <a href="{{ route('triagem.create') }}"
                    style="display:inline-flex;align-items:center;gap:6px;padding:10px 18px;background:#1a6b2f;color:#fff;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;transition:background .2s;"
                    onmouseover="this.style.background='#2d9e4a'" onmouseout="this.style.background='#1a6b2f'">
                    <i class="feather icon-user-plus"></i> Nova Triagem
                </a>
            </div>
        </div>

        {{-- Stats --}}
        <div class="tr-stats">
            <div class="tr-stat ts1">
                <div class="tr-stat-icon"><i class="feather icon-users"></i></div>
                <div class="tr-stat-num">{{ $totalHoje }}</div>
                <div class="tr-stat-lbl">Total Hoje</div>
                <div class="prog-bar-wrap">
                    <div class="prog-bar" style="width:{{ $pct }}%;"></div>
                </div>
            </div>
            <div class="tr-stat ts2 {{ $emEspera > 0 ? 'pulse' : '' }}">
                <div class="tr-stat-icon"><i class="feather icon-clock"></i></div>
                <div class="tr-stat-num">{{ $emEspera }}</div>
                <div class="tr-stat-lbl">Em Espera</div>
            </div>
            <div class="tr-stat ts3">
                <div class="tr-stat-icon"><i class="feather icon-heart"></i></div>
                <div class="tr-stat-num">{{ $emConsulta }}</div>
                <div class="tr-stat-lbl">Em Consulta</div>
            </div>
            <div class="tr-stat ts4">
                <div class="tr-stat-icon"><i class="feather icon-check-circle"></i></div>
                <div class="tr-stat-num">{{ $concluidos }}</div>
                <div class="tr-stat-lbl">Concluídos</div>
            </div>
        </div>

        {{-- Banner: primeiro em espera --}}
        @if ($ultimo)
            <div class="tr-next">
                <div class="tr-nxt-av">{{ mb_strtoupper(mb_substr($ultimo->nome, 0, 1)) }}</div>
                <div style="flex:1;min-width:0;">
                    <div class="tr-nxt-tag">🏥 Próximo a ser chamado</div>
                    <div class="tr-nxt-nome">{{ $ultimo->nome }}</div>
                    <div class="tr-nxt-meta">
                        @if ($ultimo->data_nascimento)
                            <span>{{ $ultimo->sexo === 'M' ? '♂' : '♀' }}
                                {{ \Carbon\Carbon::parse($ultimo->data_nascimento)->age }} anos</span>
                        @endif
                        <span>🕐 Entrada {{ \Carbon\Carbon::parse($ultimo->created_at)->format('H:i') }}
                            ({{ \Carbon\Carbon::parse($ultimo->created_at)->diffForHumans() }})</span>
                        @if ($ultimo->pressao_arterial)
                            <span>🩺 {{ $ultimo->pressao_arterial }}</span>
                        @endif
                        @if ($ultimo->temperatura && $ultimo->temperatura > 37.5)
                            <span style="background:rgba(239,68,68,.3);padding:2px 8px;border-radius:10px;">🌡️
                                {{ $ultimo->temperatura }}°C FEBRE</span>
                        @endif
                    </div>
                    @if ($ultimo->obs)
                        <div style="margin-top:6px;font-size:12px;opacity:.75;">💬 {{ \Str::limit($ultimo->obs, 60) }}
                        </div>
                    @endif
                </div>
                <a href="{{ route('triagem.show', $ultimo->episodio_id) }}" class="btn-ver-tri">
                    <i class="feather icon-eye"></i> Ver Detalhe
                </a>
            </div>
        @else
            <div
                style="background:#f0faf2;border-radius:16px;border:2px dashed #a7f3c0;padding:28px;text-align:center;margin-bottom:24px;">
                <div style="font-size:44px;margin-bottom:10px;">✅</div>
                <div style="font-size:16px;font-weight:700;color:#1a2e1a;">Nenhum paciente em espera</div>
                <div style="font-size:13px;color:#6b7280;margin-top:4px;">Registe uma nova triagem para começar.</div>
                <a href="{{ route('triagem.create') }}"
                    style="display:inline-flex;align-items:center;gap:6px;padding:10px 22px;background:#1a6b2f;color:#fff;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;margin-top:14px;">
                    <i class="feather icon-user-plus"></i> Registar Paciente
                </a>
            </div>
        @endif

        {{-- Grid: Gráfico + Lista --}}
        <div class="tr-grid2">

            {{-- Actividade 7 dias --}}
            <div class="tr-card">
                <div class="tr-card-head">
                    <i class="feather icon-trending-up"></i>
                    <span>Episódios — Últimos 7 Dias</span>
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

            {{-- Lista de hoje --}}
            <div class="tr-card">
                <div class="tr-card-head">
                    <i class="feather icon-list"></i>
                    <span>Pacientes de Hoje</span>
                    @if ($totalHoje > 0)
                        <span
                            style="margin-left:auto;background:#f0faf2;color:#1a6b2f;font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px;">{{ $totalHoje }}</span>
                    @endif
                </div>
                @forelse($episodios as $ep)
                    @php
                        $avCls = $ep->sexo === 'M' ? 'tav-m' : 'tav-f';
                        $epCls = ['em_espera'=>'ep-espera','em_consulta'=>'ep-consult','aguarda_exame'=>'ep-exame','concluido'=>'ep-conc'][$ep->estado] ?? 'ep-espera';
                        $epLbl = ['em_espera'=>'⏳','em_consulta'=>'🩺','aguarda_exame'=>'🔬','concluido'=>'✅'][$ep->estado] ?? '';
                        $temFebre = $ep->temperatura && $ep->temperatura > 37.5;
                    @endphp
                    <a href="{{ route('triagem.show', $ep->episodio_id) }}" class="tr-pac-item">
                        <div class="tr-av {{ $avCls }}">{{ mb_strtoupper(mb_substr($ep->nome, 0, 1)) }}</div>
                        <div style="flex:1;min-width:0;">
                            <div
                                style="font-size:13px;font-weight:600;color:#1a2e1a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $ep->nome }}
                                @if ($temFebre)
                                    <span
                                        style="color:#dc2626;font-size:10px;font-weight:700;margin-left:4px;">🌡️FEBRE</span>
                                @endif
                            </div>
                            <div style="font-size:11px;color:#9ca3af;margin-top:2px;">
                                {{ \Carbon\Carbon::parse($ep->created_at)->format('H:i') }}
                                @if ($ep->data_nascimento)
                                    · {{ \Carbon\Carbon::parse($ep->data_nascimento)->age }}a
                                @endif
                                @if ($ep->pressao_arterial)
                                    · {{ $ep->pressao_arterial }}
                                @endif
                            </div>
                        </div>
                        <span class="ep-pill {{ $epCls }}">{{ $epLbl }}</span>
                        <i class="feather icon-chevron-right" style="color:#d1d5db;font-size:14px;"></i>
                    </a>
                @empty
                    <div style="padding:28px;text-align:center;color:#9ca3af;font-size:13px;">
                        <i class="feather icon-users"
                            style="font-size:28px;display:block;margin-bottom:8px;opacity:.4;"></i>
                        Nenhum paciente hoje.
                    </div>
                @endforelse
            </div>

        </div>

    </div>

@endsection
