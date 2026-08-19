@extends('louyout.app')
@section('conteodo')
    @php
        $hoje = \Carbon\Carbon::today();
        $semana = \Carbon\Carbon::today()->startOfWeek();
        $mes = \Carbon\Carbon::today()->startOfMonth();

        // Totais gerais
        $totalPacientes = \DB::table('paciente')->count();
        $totalEpisodios = \DB::table('episodio')->count();
        $totalHoje = \DB::table('episodio')->whereDate('data', $hoje)->count();
        $totalSemana = \DB::table('episodio')
            ->whereBetween('data', [$semana, $hoje])
            ->count();
        $totalMes = \DB::table('episodio')
            ->whereBetween('data', [$mes, $hoje])
            ->count();

        // Por estado hoje
        $emEspera = \DB::table('episodio')->whereDate('data', $hoje)->where('estado', 'em_espera')->count();
        $emConsulta = \DB::table('episodio')
            ->whereDate('data', $hoje)
            ->whereIn('estado', ['em_consulta', 'aguarda_exame'])
            ->count();
        $concluidos = \DB::table('episodio')->whereDate('data', $hoje)->where('estado', 'concluido')->count();

        // Por sexo (total)
        $masc = \DB::table('paciente')->where('sexo', 'M')->count();
        $fem = \DB::table('paciente')->where('sexo', 'F')->count();

        // Últimos 7 dias — episódios por dia
        $porDia = \DB::table('episodio')
            ->selectRaw('DATE(data) as dia, COUNT(*) as total')
            ->whereBetween('data', [\Carbon\Carbon::today()->subDays(6), $hoje])
            ->groupBy('dia')
            ->orderBy('dia')
            ->get()
            ->keyBy('dia');

        // Últimos 10 pacientes
        $ultimosPacientes = \DB::table('paciente')->orderBy('id', 'desc')->limit(8)->get();

        // Episódios do dia
        $episodiosHoje = \DB::table('episodio')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->select(
                'episodio.id as episodio_id',
                'episodio.estado',
                'episodio.created_at',
                'paciente.nome',
                'paciente.sexo',
                'paciente.data_nascimento',
            )
            ->whereDate('episodio.data', $hoje)
            ->orderBy('episodio.id', 'desc')
            ->get();
    @endphp

    <style>
        .eq-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .eq-title {
            font-size: 22px;
            font-weight: 800;
            color: #1a2e1a;
            margin: 0;
        }

        .eq-sub {
            font-size: 13px;
            color: #6b7280;
            margin: 4px 0 0;
        }

        /* Stats */
        .eq-stats {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 14px;
            margin-bottom: 28px;
        }

        .eq-stat {
            border-radius: 16px;
            padding: 20px 18px 16px;
            color: #fff;
            position: relative;
            overflow: hidden;
            transition: transform .2s;
            cursor: default;
        }

        .eq-stat:hover {
            transform: translateY(-3px);
        }

        .eq-stat.c1 {
            background: linear-gradient(135deg, #1a6b2f, #2d9e4a);
        }

        .eq-stat.c2 {
            background: linear-gradient(135deg, #0d7a6b, #14b89e);
        }

        .eq-stat.c3 {
            background: linear-gradient(135deg, #c0620a, #f08030);
        }

        .eq-stat.c4 {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        }

        .eq-stat.c5 {
            background: linear-gradient(135deg, #5b21b6, #8b5cf6);
        }

        .eq-stat.c6 {
            background: linear-gradient(135deg, #334155, #64748b);
        }

        .eq-stat-num {
            font-size: 36px;
            font-weight: 900;
            line-height: 1;
        }

        .eq-stat-lbl {
            font-size: 12px;
            opacity: .85;
            font-weight: 600;
            margin-top: 4px;
        }

        .eq-stat-sub {
            font-size: 11px;
            opacity: .7;
            margin-top: 6px;
        }

        .eq-stat-icon {
            position: absolute;
            right: 14px;
            top: 14px;
            font-size: 28px;
            opacity: .2;
        }

        /* Gráfico de barras simples */
        .bar-chart {
            display: flex;
            align-items: flex-end;
            gap: 8px;
            height: 100px;
            margin-top: 8px;
        }

        .bar-day {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .bar {
            background: linear-gradient(180deg, #1a6b2f, #3aad5e);
            border-radius: 6px 6px 0 0;
            width: 100%;
            min-height: 4px;
            transition: height .5s ease;
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

        /* Grids de cards */
        .eq-grid2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            margin-bottom: 18px;
        }

        .eq-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
            overflow: hidden;
        }

        .eq-card-head {
            padding: 14px 20px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .eq-card-head i {
            color: #1a6b2f;
            font-size: 16px;
        }

        .eq-card-head span {
            font-size: 14px;
            font-weight: 700;
            color: #1a2e1a;
        }

        .eq-card-body {
            padding: 18px 20px;
        }

        /* Donut simples (CSS) */
        .donut-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 24px;
        }

        .donut {
            position: relative;
            width: 100px;
            height: 100px;
        }

        .donut svg {
            transform: rotate(-90deg);
        }

        .donut-label {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 20px;
            color: #1a2e1a;
        }

        .donut-label span {
            font-size: 11px;
            color: #6b7280;
            font-weight: 500;
        }

        .donut-legend {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .dl-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }

        .dl-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* Tabela episódios hoje */
        .ep-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .ep-row:last-child {
            border-bottom: none;
        }

        .ep-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
            color: #fff;
        }

        .ep-m {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        }

        .ep-f {
            background: linear-gradient(135deg, #9d174d, #ec4899);
        }

        .estado-pill {
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        .pi-espera {
            background: #fef3c7;
            color: #92400e;
        }

        .pi-consulta {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .pi-exame {
            background: #ede9fe;
            color: #5b21b6;
        }

        .pi-conc {
            background: #d1fae5;
            color: #065f46;
        }

        @media(max-width:800px) {
            .eq-grid2 {
                grid-template-columns: 1fr;
            }

            .eq-stats {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>

    {{-- HEADER --}}
    <div class="eq-header">
        <div>
            <h1 class="eq-title">📊 Estatísticas da Triagem</h1>
            <p class="eq-sub">{{ $hoje->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
        </div>
        <a href="{{ route('triagem.create') }}" class="btn-new">
            <i class="feather icon-user-plus"></i> Nova Triagem
        </a>
    </div>

    {{-- STATS PRINCIPAIS --}}
    <div class="eq-stats">
        <div class="eq-stat c1">
            <div class="eq-stat-icon"><i class="feather icon-users"></i></div>
            <div class="eq-stat-num">{{ $totalPacientes }}</div>
            <div class="eq-stat-lbl">Total de Pacientes</div>
            <div class="eq-stat-sub">Cadastrados no sistema</div>
        </div>
        <div class="eq-stat c2">
            <div class="eq-stat-icon"><i class="feather icon-clipboard"></i></div>
            <div class="eq-stat-num">{{ $totalEpisodios }}</div>
            <div class="eq-stat-lbl">Total de Episódios</div>
            <div class="eq-stat-sub">Todas as visitas</div>
        </div>
        <div class="eq-stat c3 {{ $emEspera > 0 ? 'pulse' : '' }}">
            <div class="eq-stat-icon"><i class="feather icon-clock"></i></div>
            <div class="eq-stat-num">{{ $totalHoje }}</div>
            <div class="eq-stat-lbl">Hoje</div>
            <div class="eq-stat-sub">{{ $emEspera }} em espera agora</div>
        </div>
        <div class="eq-stat c4">
            <div class="eq-stat-icon"><i class="feather icon-calendar"></i></div>
            <div class="eq-stat-num">{{ $totalSemana }}</div>
            <div class="eq-stat-lbl">Esta Semana</div>
            <div class="eq-stat-sub">Últimos 7 dias</div>
        </div>
        <div class="eq-stat c5">
            <div class="eq-stat-icon"><i class="feather icon-bar-chart-2"></i></div>
            <div class="eq-stat-num">{{ $totalMes }}</div>
            <div class="eq-stat-lbl">Este Mês</div>
            <div class="eq-stat-sub">{{ $hoje->format('F Y') }}</div>
        </div>
        <div class="eq-stat c6">
            <div class="eq-stat-icon"><i class="feather icon-check-circle"></i></div>
            <div class="eq-stat-num">{{ $concluidos }}</div>
            <div class="eq-stat-lbl">Concluídos Hoje</div>
            <div class="eq-stat-sub">{{ $emConsulta }} ainda em curso</div>
        </div>
    </div>

    {{-- GRÁFICO + DONUT --}}
    <div class="eq-grid2">

        {{-- Episódios dos últimos 7 dias --}}
        <div class="eq-card">
            <div class="eq-card-head">
                <i class="feather icon-trending-up"></i>
                <span>Episódios — Últimos 7 Dias</span>
            </div>
            <div class="eq-card-body">
                @php
                    $dias = [];
                    $maxV = 1;
                    for ($i = 6; $i >= 0; $i--) {
                        $d = \Carbon\Carbon::today()->subDays($i);
                        $key = $d->format('Y-m-d');
                        $tot = $porDia->get($key)->total ?? 0;
                        $dias[] = ['label' => $d->isoFormat('ddd'), 'val' => $tot];
                        if ($tot > $maxV) {
                            $maxV = $tot;
                        }
                    }
                @endphp
                <div class="bar-chart">
                    @foreach ($dias as $d)
                        @php $h = $maxV > 0 ? max(4, round(($d['val'] / $maxV) * 90)) : 4; @endphp
                        <div class="bar-day">
                            <div class="bar-val">{{ $d['val'] ?: '' }}</div>
                            <div class="bar" style="height:{{ $h }}px;"></div>
                            <div class="bar-lbl">{{ $d['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Donut sexo --}}
        <div class="eq-card">
            <div class="eq-card-head">
                <i class="feather icon-pie-chart"></i>
                <span>Pacientes por Sexo</span>
            </div>
            <div class="eq-card-body" style="display:flex;align-items:center;justify-content:center;min-height:130px;">
                @php
                    $tot = $masc + $fem ?: 1;
                    $pM = round(($masc / $tot) * 100);
                    $pF = 100 - $pM;
                    $r = 40;
                    $c = 2 * M_PI * $r;
                    $dashM = ($c * $pM) / 100;
                    $dashF = ($c * $pF) / 100;
                @endphp
                <div class="donut-wrap">
                    <div class="donut">
                        <svg width="100" height="100" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="{{ $r }}" fill="none" stroke="#e5e7eb"
                                stroke-width="16" />
                            <circle cx="50" cy="50" r="{{ $r }}" fill="none" stroke="#3b82f6"
                                stroke-width="16" stroke-dasharray="{{ $dashM }} {{ $c - $dashM }}"
                                stroke-dashoffset="0" />
                            <circle cx="50" cy="50" r="{{ $r }}" fill="none" stroke="#ec4899"
                                stroke-width="16" stroke-dasharray="{{ $dashF }} {{ $c - $dashF }}"
                                stroke-dashoffset="{{ -$dashM }}" />
                        </svg>
                        <div class="donut-label">{{ $tot - 1 }}<span>total</span></div>
                    </div>
                    <div class="donut-legend">
                        <div class="dl-item">
                            <div class="dl-dot" style="background:#3b82f6;"></div>
                            <div><strong>{{ $masc }}</strong> Masculino <span
                                    style="color:#9ca3af;">({{ $pM }}%)</span></div>
                        </div>
                        <div class="dl-item">
                            <div class="dl-dot" style="background:#ec4899;"></div>
                            <div><strong>{{ $fem }}</strong> Feminino <span
                                    style="color:#9ca3af;">({{ $pF }}%)</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Episódios hoje + Últimos pacientes --}}
    <div class="eq-grid2">

        {{-- Episódios de hoje --}}
        <div class="eq-card">
            <div class="eq-card-head">
                <i class="feather icon-activity"></i>
                <span>Episódios de Hoje ({{ $episodiosHoje->count() }})</span>
            </div>
            <div class="eq-card-body" style="padding:0;">
                <div style="max-height:320px;overflow-y:auto;padding:0 20px;">
                    @forelse($episodiosHoje as $ep)
                        @php
                            $avCls = $ep->sexo === 'M' ? 'ep-m' : 'ep-f';
                            $stCls = ['em_espera'=>'pi-espera','em_consulta'=>'pi-consulta','aguarda_exame'=>'pi-exame','concluido'=>'pi-conc'][$ep->estado] ?? 'pi-espera';
                            $stLbl = ['em_espera'=>'Espera','em_consulta'=>'Consulta','aguarda_exame'=>'Exame','concluido'=>'Concluído'][$ep->estado] ?? $ep->estado;
                        @endphp
                        <div class="ep-row">
                            <div class="ep-avatar {{ $avCls }}">{{ mb_strtoupper(mb_substr($ep->nome, 0, 1)) }}
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div
                                    style="font-size:13px;font-weight:600;color:#1a2e1a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $ep->nome }}</div>
                                <div style="font-size:11px;color:#9ca3af;">
                                    {{ \Carbon\Carbon::parse($ep->created_at)->format('H:i') }}
                                    @if ($ep->data_nascimento)
                                        · {{ \Carbon\Carbon::parse($ep->data_nascimento)->age }} anos
                                    @endif
                                </div>
                            </div>
                            <span class="estado-pill {{ $stCls }}">{{ $stLbl }}</span>
                            <a href="{{ route('triagem.show', $ep->episodio_id) }}"
                                style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:7px;background:#f3f4f6;color:#374151;text-decoration:none;flex-shrink:0;">
                                <i class="feather icon-eye" style="font-size:12px;"></i>
                            </a>
                        </div>
                    @empty
                        <div style="text-align:center;padding:30px;color:#9ca3af;font-size:13px;">
                            Nenhum episódio hoje.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Últimos pacientes cadastrados --}}
        <div class="eq-card">
            <div class="eq-card-head">
                <i class="feather icon-user-plus"></i>
                <span>Últimos Pacientes Cadastrados</span>
            </div>
            <div class="eq-card-body" style="padding:0;">
                <div style="padding:0 20px;">
                    @foreach ($ultimosPacientes as $p)
                        @php $av = $p->sexo === 'M' ? 'ep-m' : 'ep-f'; @endphp
                        <div class="ep-row">
                            <div class="ep-avatar {{ $av }}">{{ mb_strtoupper(mb_substr($p->nome, 0, 1)) }}</div>
                            <div style="flex:1;min-width:0;">
                                <div
                                    style="font-size:13px;font-weight:600;color:#1a2e1a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $p->nome }}</div>
                                <div style="font-size:11px;color:#9ca3af;">
                                    {{ $p->sexo === 'M' ? 'Masculino' : 'Feminino' }}
                                    @if ($p->data_nascimento)
                                        · {{ \Carbon\Carbon::parse($p->data_nascimento)->age }} anos
                                    @endif
                                    @if ($p->numero_processo)
                                        · {{ $p->numero_processo }}
                                    @endif
                                </div>
                            </div>
                            <div style="font-size:11px;color:#9ca3af;">
                                {{ \Carbon\Carbon::parse($p->created_at)->diffForHumans() }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
@endsection
