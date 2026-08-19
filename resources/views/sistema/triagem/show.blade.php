@extends('louyout.app')
@section('conteodo')

    <style>
        .sh-grid {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 20px;
            align-items: start;
        }

        .f-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
            overflow: hidden;
            margin-bottom: 16px;
        }

        .f-card-head {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 22px;
            background: #f0faf2;
            border-bottom: 2px solid #d1fae5;
        }

        .f-card-head i {
            font-size: 15px;
            color: #1a6b2f;
        }

        .f-card-head span {
            font-size: 14px;
            font-weight: 700;
            color: #1a6b2f;
        }

        .f-card-body {
            padding: 22px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 16px;
        }

        .ii label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #9ca3af;
            margin-bottom: 3px;
        }

        .ii span {
            font-size: 14px;
            font-weight: 700;
            color: #1a2e1a;
        }

        /* Vitais */
        .vt-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 12px;
        }

        .vt-box {
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            padding: 16px;
            text-align: center;
            transition: box-shadow .2s;
        }

        .vt-box:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
        }

        .vt-emoji {
            font-size: 24px;
            display: block;
            margin-bottom: 6px;
        }

        .vt-val {
            font-size: 22px;
            font-weight: 900;
            line-height: 1;
        }

        .vt-lbl {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            color: #9ca3af;
            margin-top: 4px;
        }

        .vt-sub {
            font-size: 11px;
            margin-top: 3px;
            font-weight: 500;
        }

        .vt-ok {
            color: #1a6b2f;
        }

        .vt-warn {
            color: #92400e;
            background: #fffbeb;
            border-color: #fde68a;
        }

        .vt-danger {
            color: #991b1b;
            background: #fef2f2;
            border-color: #fca5a5;
        }

        /* Estado badge */
        .ep-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .eb-espera {
            background: #fef3c7;
            color: #92400e;
        }

        .eb-consult {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .eb-exame {
            background: #ede9fe;
            color: #5b21b6;
        }

        .eb-conc {
            background: #d1fae5;
            color: #065f46;
        }

        /* Histórico */
        .hist-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            border-bottom: 1px solid #f3f4f6;
        }

        .hist-item:last-child {
            border-bottom: none;
        }

        .hist-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        @media(max-width:800px) {
            .sh-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    {{-- HEADER --}}
    <div class="page-header-bar">
        <div>
            <h4 class="page-title">
                <i class="feather icon-clipboard" style="color:#1a6b2f;margin-right:8px;"></i>
                {{ $episodio->nome }}
            </h4>
            <p class="page-sub">
                Episódio #{{ $episodio->episodio_id }} &nbsp;·&nbsp;
                {{ $episodio->sexo === 'M' ? 'Masculino' : 'Feminino' }}
                @if ($episodio->data_nascimento)
                    &nbsp;·&nbsp; {{ \Carbon\Carbon::parse($episodio->data_nascimento)->age }} anos
                @endif
            </p>
        </div>
        <div style="display:flex;gap:8px;align-items:center;">
            @php
                $esCls = ['em_espera'=>'eb-espera','em_consulta'=>'eb-consult','aguarda_exame'=>'eb-exame','concluido'=>'eb-conc'][$episodio->estado] ?? 'eb-espera';
                $esLbl = ['em_espera'=>'⏳ Em Espera','em_consulta'=>'🩺 Em Consulta','aguarda_exame'=>'🔬 Aguarda Exame','concluido'=>'✅ Concluído'][$episodio->estado] ?? $episodio->estado;
                $isUrgente = (bool)(\DB::table('episodio')->where('id',$episodio->episodio_id)->value('urgente'));
            @endphp
            @if($isUrgente)
                <span style="background:#fee2e2;color:#991b1b;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:800;">⚡ URGENTE</span>
            @endif
            <span class="ep-badge {{ $esCls }}">{{ $esLbl }}</span>
            <a href="{{ route('triagem.index') }}"
                style="display:inline-flex;align-items:center;gap:5px;padding:8px 16px;border:2px solid #e5e7eb;border-radius:10px;color:#374151;font-size:13px;font-weight:600;text-decoration:none;transition:all .2s;"
                onmouseover="this.style.borderColor='#1a6b2f';this.style.color='#1a6b2f'"
                onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#374151'">
                <i class="feather icon-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    @include('louyout.flash')

    <div class="sh-grid">

        {{-- COLUNA PRINCIPAL --}}
        <div>

            {{-- Dados do paciente --}}
            <div class="f-card">
                <div class="f-card-head">
                    <i class="feather icon-user"></i>
                    <span>Dados do Paciente</span>
                </div>
                <div class="f-card-body">
                    <div
                        style="display:flex;align-items:center;gap:16px;margin-bottom:20px;padding:16px;background:#f0faf2;border-radius:12px;">
                        <div
                            style="width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:800;color:#fff;flex-shrink:0;background:{{ $episodio->sexo === 'M' ? 'linear-gradient(135deg,#1e3a8a,#3b82f6)' : 'linear-gradient(135deg,#9d174d,#ec4899)' }}">
                            {{ mb_strtoupper(mb_substr($episodio->nome, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-size:18px;font-weight:800;color:#1a2e1a;">{{ $episodio->nome }}</div>
                            <div style="font-size:13px;color:#6b7280;margin-top:3px;">
                                {{ $episodio->sexo === 'M' ? 'Masculino' : 'Feminino' }}
                                @if ($episodio->data_nascimento)
                                    &nbsp;·&nbsp; {{ \Carbon\Carbon::parse($episodio->data_nascimento)->format('d/m/Y') }}
                                    ({{ \Carbon\Carbon::parse($episodio->data_nascimento)->age }} anos)
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="info-grid">
                        <div class="ii">
                            <label>Nº Processo</label>
                            <span style="font-family:monospace;">{{ $episodio->numero_processo ?: '—' }}</span>
                        </div>
                        <div class="ii">
                            <label>Telefone</label>
                            <span>{{ $episodio->telefone ?: '—' }}</span>
                        </div>
                        <div class="ii">
                            <label>Morada</label>
                            <span>{{ $episodio->morada ?: '—' }}</span>
                        </div>
                        <div class="ii">
                            <label>Triagem por</label>
                            <span>{{ $episodio->triagem_by }}</span>
                        </div>
                        <div class="ii">
                            <label>Data / Hora</label>
                            <span>{{ \Carbon\Carbon::parse($episodio->created_at)->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dados Vitais --}}
            @if ($triagem)
                <div class="f-card">
                    <div class="f-card-head">
                        <i class="feather icon-heart"></i>
                        <span>Dados Vitais</span>
                    </div>
                    <div class="f-card-body">
                        <div class="vt-grid">
                            @if ($triagem->pressao_arterial)
                                <div class="vt-box">
                                    <span class="vt-emoji">🩺</span>
                                    <div class="vt-val vt-ok">{{ $triagem->pressao_arterial }}</div>
                                    <div class="vt-lbl">Pressão Arterial</div>
                                    <div class="vt-sub" style="color:#6b7280;">mmHg</div>
                                </div>
                            @endif

                            @if ($triagem->temperatura)
                                @php $temFebre = $triagem->temperatura > 37.5; @endphp
                                <div class="vt-box {{ $temFebre ? 'vt-danger' : '' }}">
                                    <span class="vt-emoji">🌡️</span>
                                    <div class="vt-val {{ $temFebre ? 'vt-danger' : 'vt-ok' }}">
                                        {{ $triagem->temperatura }}°</div>
                                    <div class="vt-lbl">Temperatura</div>
                                    <div class="vt-sub" style="color:{{ $temFebre ? '#991b1b' : '#6b7280' }};">
                                        {{ $temFebre ? '⚠️ FEBRE' : 'Normal' }}</div>
                                </div>
                            @endif

                            @if ($triagem->peso)
                                <div class="vt-box">
                                    <span class="vt-emoji">⚖️</span>
                                    <div class="vt-val vt-ok">{{ $triagem->peso }}</div>
                                    <div class="vt-lbl">Peso</div>
                                    <div class="vt-sub" style="color:#6b7280;">kg</div>
                                </div>
                            @endif

                            @if ($triagem->altura)
                                <div class="vt-box">
                                    <span class="vt-emoji">📏</span>
                                    <div class="vt-val vt-ok">{{ $triagem->altura }}</div>
                                    <div class="vt-lbl">Altura</div>
                                    <div class="vt-sub" style="color:#6b7280;">cm</div>
                                </div>
                            @endif

                            @if ($triagem->peso && $triagem->altura)
                                @php
                                    $imc = round($triagem->peso / pow($triagem->altura / 100, 2), 1);
                                    $imcCls =
                                        $imc < 18.5 || $imc >= 30 ? 'vt-danger' : ($imc >= 25 ? 'vt-warn' : 'vt-ok');
                                    $imcCat =
                                        $imc < 18.5
                                            ? 'Abaixo'
                                            : ($imc < 25
                                                ? 'Normal'
                                                : ($imc < 30
                                                    ? 'Sobrepeso'
                                                    : 'Obesidade'));
                                @endphp
                                <div
                                    class="vt-box {{ $imc < 18.5 || $imc >= 30 ? 'vt-danger' : ($imc >= 25 ? 'vt-warn' : '') }}">
                                    <span class="vt-emoji">📊</span>
                                    <div class="vt-val {{ $imcCls }}">{{ $imc }}</div>
                                    <div class="vt-lbl">IMC</div>
                                    <div class="vt-sub" style="color:{{ $imc >= 25 ? '#92400e' : '#065f46' }};">
                                        {{ $imcCat }}</div>
                                </div>
                            @endif

                            @if ($triagem->frequencia_cardiaca)
                                @php
                                    $fc = $triagem->frequencia_cardiaca;
                                    $fcWarn = $fc < 60 || $fc > 100;
                                @endphp
                                <div class="vt-box {{ $fcWarn ? 'vt-warn' : '' }}">
                                    <span class="vt-emoji">❤️</span>
                                    <div class="vt-val {{ $fcWarn ? 'vt-warn' : 'vt-ok' }}">{{ $fc }}</div>
                                    <div class="vt-lbl">Freq. Cardíaca</div>
                                    <div class="vt-sub" style="color:#6b7280;">bpm</div>
                                </div>
                            @endif

                            @if ($triagem->frequencia_respiratoria)
                                <div class="vt-box">
                                    <span class="vt-emoji">🫁</span>
                                    <div class="vt-val vt-ok">{{ $triagem->frequencia_respiratoria }}</div>
                                    <div class="vt-lbl">Freq. Resp.</div>
                                    <div class="vt-sub" style="color:#6b7280;">rpm</div>
                                </div>
                            @endif

                            @if ($triagem->saturacao_oxigenio)
                                @php
                                    $sat = $triagem->saturacao_oxigenio;
                                    $satBad = $sat < 95;
                                @endphp
                                <div class="vt-box {{ $satBad ? 'vt-danger' : '' }}">
                                    <span class="vt-emoji">💨</span>
                                    <div class="vt-val {{ $satBad ? 'vt-danger' : 'vt-ok' }}">{{ $sat }}%</div>
                                    <div class="vt-lbl">Saturação O₂</div>
                                    <div class="vt-sub" style="color:{{ $satBad ? '#991b1b' : '#065f46' }};">
                                        {{ $satBad ? '⚠️ BAIXA' : 'Normal' }}</div>
                                </div>
                            @endif
                        </div>

                        @if ($triagem->observacao)
                            <div
                                style="margin-top:16px;padding:14px 16px;background:#fffbeb;border-radius:12px;border:1px solid #fde68a;">
                                <div
                                    style="font-size:11px;font-weight:700;color:#92400e;text-transform:uppercase;margin-bottom:6px;">
                                    <i class="feather icon-message-square"
                                        style="margin-right:4px;font-size:12px;"></i>Queixas / Observações
                                </div>
                                <div style="font-size:13px;color:#374151;line-height:1.6;">{{ $triagem->observacao }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div
                    style="background:#f9fafb;border-radius:14px;border:1px dashed #d1d5db;padding:24px;text-align:center;color:#9ca3af;font-size:13px;margin-bottom:16px;">
                    <i class="feather icon-heart" style="font-size:24px;display:block;margin-bottom:8px;opacity:.4;"></i>
                    Dados vitais não registados.
                </div>
            @endif

        </div>

        {{-- COLUNA LATERAL --}}
        <div>
            {{-- Histórico de visitas --}}
            <div class="f-card">
                <div class="f-card-head">
                    <i class="feather icon-clock"></i>
                    <span>Visitas Anteriores</span>
                </div>
                <div class="f-card-body" style="padding:0;">
                    @forelse($historico as $h)
                        @php
                            $hCor = ['concluido'=>'#1a6b2f','em_consulta'=>'#1d4ed8','aguarda_exame'=>'#5b21b6'][$h->estado ?? ''] ?? '#9ca3af';
                        @endphp
                        <div class="hist-item">
                            <div class="hist-dot" style="background:{{ $hCor }};"></div>
                            <div style="flex:1;">
                                <div style="font-size:13px;font-weight:600;color:#1a2e1a;">
                                    {{ \Carbon\Carbon::parse($h->data)->format('d/m/Y') }}
                                </div>
                                <div style="font-size:11px;color:#6b7280;margin-top:2px;">
                                    @if ($h->pressao_arterial)
                                        PA: {{ $h->pressao_arterial }}
                                    @endif
                                    @if ($h->temperatura)
                                        · {{ $h->temperatura }}°C
                                    @endif
                                    @if ($h->peso)
                                        · {{ $h->peso }}kg
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="padding:20px;text-align:center;color:#9ca3af;font-size:13px;">
                            <i class="feather icon-star"
                                style="display:block;font-size:24px;margin-bottom:6px;opacity:.4;"></i>
                            Primeira visita do paciente.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Acções --}}
            <div class="f-card">
                <div class="f-card-head">
                    <i class="feather icon-zap"></i>
                    <span>Acções</span>
                </div>
                <div class="f-card-body" style="display:flex;flex-direction:column;gap:8px;">
                    <a href="{{ route('triagem.index') }}"
                        style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:#f0faf2;border-radius:10px;color:#1a6b2f;font-size:13px;font-weight:600;text-decoration:none;transition:background .2s;"
                        onmouseover="this.style.background='#d1fae5'" onmouseout="this.style.background='#f0faf2'">
                        <i class="feather icon-list"></i> Ver todos os pacientes
                    </a>
                    <a href="{{ route('triagem.create') }}"
                        style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:#1a6b2f;border-radius:10px;color:#fff;font-size:13px;font-weight:600;text-decoration:none;transition:background .2s;"
                        onmouseover="this.style.background='#2d9e4a'" onmouseout="this.style.background='#1a6b2f'">
                        <i class="feather icon-user-plus"></i> Nova triagem
                    </a>
                </div>
            </div>
        </div>

    </div>{{-- /sh-grid --}}

@endsection
