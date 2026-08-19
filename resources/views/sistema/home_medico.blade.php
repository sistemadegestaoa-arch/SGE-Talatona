@extends('louyout.app')
@section('conteodo')

    @php
        $hoje = \Carbon\Carbon::today();
        $emEspera = \DB::table('episodio')->whereDate('data', $hoje)->where('estado', 'em_espera')->count();
        $emConsulta = \DB::table('episodio')
            ->whereDate('data', $hoje)
            ->whereIn('estado', ['em_consulta', 'aguarda_exame'])
            ->count();
        $concluidos = \DB::table('episodio')->whereDate('data', $hoje)->where('estado', 'concluido')->count();
        $totalHoje = $emEspera + $emConsulta + $concluidos;

        $resultadosProntos = \DB::table('pedido_exame')
            ->join('consulta', 'consulta.id', '=', 'pedido_exame.consulta_id')
            ->join('episodio', 'episodio.id', '=', 'consulta.episodio_id')
            ->where('pedido_exame.estado', 'concluido')
            ->whereDate('episodio.data', $hoje)
            ->where('consulta.medico_id', Auth::id())
            ->whereIn('episodio.estado', ['em_consulta', 'aguarda_exame'])
            ->count();

        // Próximo paciente em espera
        $proximo = \DB::table('episodio')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->leftJoin('triagem', 'triagem.episodio_id', '=', 'episodio.id')
            ->select(
                'episodio.id as episodio_id',
                'paciente.nome',
                'paciente.sexo',
                'paciente.data_nascimento',
                'triagem.temperatura',
                'triagem.pressao_arterial',
                'triagem.observacao as obs_triagem',
                'episodio.created_at'
            )
            ->whereDate('episodio.data', $hoje)
            ->where('episodio.estado', 'em_espera')
            ->orderBy('episodio.id', 'asc')
            ->first();

        // Histórico recente do médico
        $recentes = \DB::table('consulta')
            ->join('episodio', 'episodio.id', '=', 'consulta.episodio_id')
            ->join('paciente', 'paciente.id', '=', 'episodio.paciente_id')
            ->where('consulta.medico_id', Auth::id())
            ->orderBy('consulta.id', 'desc')
            ->limit(5)
            ->select(
                'episodio.id as episodio_id',
                'paciente.nome',
                'paciente.sexo',
                'episodio.data',
                'episodio.estado',
                'consulta.diagnostico'
            )
            ->get();

        // Porcentagem do dia
        $pct = $totalHoje > 0 ? round(($concluidos / $totalHoje) * 100) : 0;
    @endphp

    <style>
        .md-wrap {
            max-width: 100%;
        }

        .md-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .md-title {
            font-size: 22px;
            font-weight: 800;
            color: #1a2e1a;
            margin: 0;
        }

        .md-sub {
            font-size: 13px;
            color: #6b7280;
            margin: 4px 0 0;
        }

        /* Stats */
        .md-stats {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .md-stat {
            border-radius: 16px;
            padding: 18px 16px 14px;
            color: #fff;
            position: relative;
            overflow: hidden;
            transition: transform .2s;
        }

        .md-stat:hover {
            transform: translateY(-3px);
        }

        .md-stat.s1 {
            background: linear-gradient(135deg, #1a6b2f, #2d9e4a);
        }

        .md-stat.s2 {
            background: linear-gradient(135deg, #c0620a, #f08030);
        }

        .md-stat.s3 {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        }

        .md-stat.s4 {
            background: linear-gradient(135deg, #0d7a6b, #14b89e);
        }

        .md-stat.s5 {
            background: linear-gradient(135deg, #5b21b6, #8b5cf6);
        }

        .md-stat-num {
            font-size: 32px;
            font-weight: 900;
            line-height: 1;
        }

        .md-stat-lbl {
            font-size: 11px;
            font-weight: 600;
            opacity: .85;
            margin-top: 3px;
        }

        .md-stat-icon {
            position: absolute;
            right: 12px;
            top: 12px;
            font-size: 28px;
            opacity: .15;
        }

        /* Progresso */
        .prog-bar-wrap {
            height: 8px;
            background: rgba(255, 255, 255, .25);
            border-radius: 99px;
            margin-top: 8px;
            overflow: hidden;
        }

        .prog-bar {
            height: 100%;
            background: #fff;
            border-radius: 99px;
            transition: width .8s ease;
        }

        /* Próximo paciente */
        .proximo-card {
            background: linear-gradient(135deg, #0f3d1e, #1a6b2f);
            border-radius: 18px;
            padding: 24px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(26, 107, 47, .3);
        }

        .proximo-card::before {
            content: '';
            position: absolute;
            right: -40px;
            top: -40px;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
        }

        .proximo-card::after {
            content: '';
            position: absolute;
            right: 40px;
            bottom: -30px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .04);
        }

        .prox-av {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 800;
            flex-shrink: 0;
            background: rgba(255, 255, 255, .2);
            color: #fff;
        }

        .prox-info {
            flex: 1;
            min-width: 0;
        }

        .prox-nome {
            font-size: 20px;
            font-weight: 800;
        }

        .prox-meta {
            font-size: 13px;
            opacity: .8;
            margin-top: 4px;
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .prox-vitais {
            display: flex;
            gap: 8px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .prox-chip {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            background: rgba(255, 255, 255, .15);
            color: #fff;
        }

        .prox-chip.warn {
            background: rgba(239, 68, 68, .3);
        }

        .btn-atender {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: #fff;
            color: #1a6b2f;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 800;
            text-decoration: none;
            white-space: nowrap;
            flex-shrink: 0;
            transition: transform .2s, box-shadow .2s;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .15);
        }

        .btn-atender:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, .2);
            color: #1a6b2f;
            text-decoration: none;
        }

        /* Grid inferior */
        .md-grid2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .md-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
            overflow: hidden;
        }

        .md-card-head {
            padding: 14px 20px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .md-card-head i {
            color: #1a6b2f;
            font-size: 16px;
        }

        .md-card-head span {
            font-size: 14px;
            font-weight: 700;
            color: #1a2e1a;
        }

        /* Fila resumo */
        .fila-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 20px;
            border-bottom: 1px solid #f3f4f6;
            transition: background .15s;
            cursor: default;
        }

        .fila-item:last-child {
            border-bottom: none;
        }

        .fila-item:hover {
            background: #f0faf2;
        }

        .fi-pos {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #f0faf2;
            color: #1a6b2f;
            font-size: 12px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
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

        .fi-m {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        }

        .fi-f {
            background: linear-gradient(135deg, #9d174d, #ec4899);
        }

        .fi-info {
            flex: 1;
            min-width: 0;
        }

        .fi-nome {
            font-size: 13px;
            font-weight: 600;
            color: #1a2e1a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .fi-meta {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 2px;
        }

        /* Histórico */
        .hist-item2 {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 20px;
            border-bottom: 1px solid #f3f4f6;
        }

        .hist-item2:last-child {
            border-bottom: none;
        }

        .hi-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        @media(max-width:900px) {
            .md-stats {
                grid-template-columns: repeat(3, 1fr);
            }

            .md-grid2 {
                grid-template-columns: 1fr;
            }

            .proximo-card {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    <div class="md-wrap">

        {{-- Header --}}
        <div class="md-header">
            <div>
                <h1 class="md-title">Olá, Dr. {{ Auth::user()->name }} 👋</h1>
                <p class="md-sub">{{ $hoje->isoFormat('dddd, D [de] MMMM [de] YYYY') }} ·
                    {{ \Carbon\Carbon::now()->format('H:i') }}</p>
            </div>
            <a href="{{ route('consultas.index') }}"
                style="display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border:2px solid #1a6b2f;border-radius:10px;color:#1a6b2f;font-size:13px;font-weight:600;text-decoration:none;transition:all .2s;"
                onmouseover="this.style.background='#1a6b2f';this.style.color='#fff'"
                onmouseout="this.style.background='transparent';this.style.color='#1a6b2f'">
                <i class="feather icon-users"></i> Ver Lista de Espera
            </a>
        </div>

        {{-- Stats --}}
        <div class="md-stats">
            <div class="md-stat s1">
                <div class="md-stat-icon"><i class="feather icon-users"></i></div>
                <div class="md-stat-num">{{ $totalHoje }}</div>
                <div class="md-stat-lbl">Total Hoje</div>
                <div class="prog-bar-wrap">
                    <div class="prog-bar" style="width:{{ $pct }}%;"></div>
                </div>
            </div>
            <div class="md-stat s2 {{ $emEspera > 0 ? 'pulse' : '' }}">
                <div class="md-stat-icon"><i class="feather icon-clock"></i></div>
                <div class="md-stat-num">{{ $emEspera }}</div>
                <div class="md-stat-lbl">Em Espera</div>
            </div>
            <div class="md-stat s3">
                <div class="md-stat-icon"><i class="feather icon-heart"></i></div>
                <div class="md-stat-num">{{ $emConsulta }}</div>
                <div class="md-stat-lbl">Em Consulta</div>
            </div>
            <div class="md-stat s4 {{ $resultadosProntos > 0 ? 'pulse' : '' }}">
                <div class="md-stat-icon"><i class="feather icon-activity"></i></div>
                <div class="md-stat-num">{{ $resultadosProntos }}</div>
                <div class="md-stat-lbl">Resultados Prontos</div>
            </div>
            <div class="md-stat s5">
                <div class="md-stat-icon"><i class="feather icon-check-circle"></i></div>
                <div class="md-stat-num">{{ $concluidos }}</div>
                <div class="md-stat-lbl">Concluídos</div>
            </div>
        </div>

        {{-- Próximo paciente --}}
        @if ($proximo)
            <div class="proximo-card">
                <div class="prox-av">{{ mb_strtoupper(mb_substr($proximo->nome, 0, 1)) }}</div>
                <div class="prox-info">
                    <div
                        style="font-size:11px;font-weight:700;opacity:.7;text-transform:uppercase;letter-spacing:.8px;margin-bottom:4px;">
                        Próximo Paciente</div>
                    <div class="prox-nome">{{ $proximo->nome }}</div>
                    <div class="prox-meta">
                        @if ($proximo->data_nascimento)
                            <span>{{ \Carbon\Carbon::parse($proximo->data_nascimento)->age }} anos</span>
                        @endif
                        <span>⏰ Entrada {{ \Carbon\Carbon::parse($proximo->created_at)->format('H:i') }}</span>
                        @if ($proximo->pressao_arterial)
                            <span>🩺 {{ $proximo->pressao_arterial }}</span>
                        @endif
                    </div>
                    <div class="prox-vitais">
                        @if ($proximo->temperatura)
                            <span class="prox-chip {{ $proximo->temperatura > 37.5 ? 'warn' : '' }}">
                                🌡️ {{ $proximo->temperatura }}°C {{ $proximo->temperatura > 37.5 ? '⚠️ FEBRE' : '' }}
                            </span>
                        @endif
                        @if ($proximo->obs_triagem)
                            <span class="prox-chip">💬 {{ \Str::limit($proximo->obs_triagem, 50) }}</span>
                        @endif
                    </div>
                </div>
                <a href="{{ route('consultas.show', $proximo->episodio_id) }}" class="btn-atender">
                    <i class="feather icon-edit-3"></i> Iniciar Consulta
                </a>
            </div>
        @else
            <div
                style="background:#f0faf2;border-radius:16px;border:2px dashed #a7f3c0;padding:28px;text-align:center;margin-bottom:24px;">
                <i class="feather icon-check-circle"
                    style="font-size:36px;color:#1a6b2f;display:block;margin-bottom:10px;"></i>
                <div style="font-size:16px;font-weight:700;color:#1a2e1a;">Fila de espera vazia</div>
                <div style="font-size:13px;color:#6b7280;margin-top:4px;">Todos os pacientes foram atendidos hoje.</div>
            </div>
        @endif

        {{-- Grid: Fila + Histórico --}}
        <div class="md-grid2">

            {{-- Fila de espera completa --}}
            <div class="md-card">
                <div class="md-card-head">
                    <i class="feather icon-users"></i>
                    <span>Fila de Espera</span>
                    @if ($emEspera > 0)
                        <span
                            style="background:#ef4444;color:#fff;font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;margin-left:auto;">{{ $emEspera }}</span>
                    @endif
                </div>
                <div style="max-height:340px;overflow-y:auto;">
                    @php
                        $filaCompleta = \DB::table('episodio')
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
                                'triagem.pressao_arterial'
                            )
                            ->whereDate('episodio.data', today())
                            ->whereIn('episodio.estado', ['em_espera', 'em_consulta', 'aguarda_exame'])
                            ->orderBy('episodio.id', 'asc')
                            ->get();
                        $pos = 0;
                    @endphp
                    @forelse($filaCompleta as $fp)
                        @php
                            $pos++;
                            $avCls = $fp->sexo === 'M' ? 'fi-m' : 'fi-f';
                            $temFebre = $fp->temperatura && $fp->temperatura > 37.5;
                            $stMap = ['em_espera' => 'background:#fef3c7;color:#92400e', 'em_consulta' => 'background:#dbeafe;color:#1d4ed8', 'aguarda_exame' => 'background:#ede9fe;color:#5b21b6'];
                            $stCls = $stMap[$fp->estado] ?? '';
                            $stLblMap = ['em_espera' => '⏳', 'em_consulta' => '🩺', 'aguarda_exame' => '🔬'];
                            $stLbl = $stLblMap[$fp->estado] ?? '';
                        @endphp
                        <a href="{{ route('consultas.show', $fp->episodio_id) }}" class="fila-item"
                            style="text-decoration:none;">
                            <div class="fi-pos">{{ $pos }}</div>
                            <div class="fi-av {{ $avCls }}">{{ mb_strtoupper(mb_substr($fp->nome, 0, 1)) }}</div>
                            <div class="fi-info">
                                <div class="fi-nome">{{ $fp->nome }}</div>
                                <div class="fi-meta">
                                    @if ($fp->data_nascimento)
                                        {{ \Carbon\Carbon::parse($fp->data_nascimento)->age }}a
                                    @endif
                                    · {{ \Carbon\Carbon::parse($fp->created_at)->format('H:i') }}
                                    @if ($temFebre)
                                        · <span style="color:#dc2626;font-weight:700;">🌡️ FEBRE</span>
                                    @endif
                                    @if ($fp->pressao_arterial)
                                        · {{ $fp->pressao_arterial }}
                                    @endif
                                </div>
                            </div>
                            <span
                                style="padding:2px 8px;border-radius:20px;font-size:12px;{{ $stCls }}">{{ $stLbl }}</span>
                        </a>
                    @empty
                        <div style="padding:28px;text-align:center;color:#9ca3af;font-size:13px;">
                            Fila vazia.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Histórico recente --}}
            <div class="md-card">
                <div class="md-card-head">
                    <i class="feather icon-clock"></i>
                    <span>Minhas Consultas Recentes</span>
                </div>
                <div>
                    @forelse($recentes as $r)
                        @php
                            $dotMap = ['concluido' => '#1a6b2f', 'em_consulta' => '#3b82f6', 'aguarda_exame' => '#8b5cf6'];
                            $dot = $dotMap[$r->estado] ?? '#9ca3af';
                            $avCls2 = $r->sexo === 'M' ? 'fi-m' : 'fi-f';
                        @endphp
                        <a href="{{ route('consultas.show', $r->episodio_id) }}" class="hist-item2"
                            style="text-decoration:none;">
                            <div class="hi-dot" style="background:{{ $dot }};"></div>
                            <div class="fi-av {{ $avCls2 }}" style="width:34px;height:34px;font-size:12px;">
                                {{ mb_strtoupper(mb_substr($r->nome, 0, 1)) }}</div>
                            <div class="fi-info">
                                <div class="fi-nome">{{ $r->nome }}</div>
                                <div class="fi-meta">
                                    {{ \Carbon\Carbon::parse($r->data)->format('d/m/Y') }}
                                    @if ($r->diagnostico)
                                        · {{ \Str::limit($r->diagnostico, 40) }}
                                    @endif
                                </div>
                            </div>
                        </a>
                    @empty
                        <div style="padding:28px;text-align:center;color:#9ca3af;font-size:13px;">
                            Nenhuma consulta registada ainda.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

@endsection
