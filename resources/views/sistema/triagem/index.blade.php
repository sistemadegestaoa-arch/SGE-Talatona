@extends('louyout.app')
@section('conteodo')

    <style>
        /* ── Layout ─────────────────────────────────────────── */
        .tg-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .tg-title {
            font-size: 22px;
            font-weight: 800;
            color: #1a2e1a;
            margin: 0;
        }

        .tg-sub {
            font-size: 13px;
            color: #6b7280;
            margin: 4px 0 0;
        }

        /* ── Contadores ─────────────────────────────────────── */
        .tg-counters {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 24px;
        }

        .tg-cnt {
            border-radius: 16px;
            padding: 18px 16px 14px;
            color: #fff;
            display: flex;
            flex-direction: column;
            gap: 4px;
            position: relative;
            overflow: hidden;
            transition: transform .2s;
            cursor: default;
        }

        .tg-cnt:hover {
            transform: translateY(-3px);
        }

        .tg-cnt.cn-total {
            background: linear-gradient(135deg, #1a6b2f, #2d9e4a);
        }

        .tg-cnt.cn-espera {
            background: linear-gradient(135deg, #c0620a, #f08030);
        }

        .tg-cnt.cn-consult {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        }

        .tg-cnt.cn-conc {
            background: linear-gradient(135deg, #334155, #64748b);
        }

        .tg-cnt-num {
            font-size: 36px;
            font-weight: 900;
            line-height: 1;
        }

        .tg-cnt-lbl {
            font-size: 12px;
            font-weight: 600;
            opacity: .85;
        }

        .tg-cnt-icon {
            position: absolute;
            right: 12px;
            top: 12px;
            font-size: 30px;
            opacity: .15;
        }

        /* ── Filtro / Pesquisa ───────────────────────────────── */
        .tg-toolbar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .tg-search {
            flex: 1;
            min-width: 200px;
            position: relative;
        }

        .tg-search input {
            width: 100%;
            padding: 9px 14px 9px 36px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 13px;
            outline: none;
            font-family: 'Inter', sans-serif;
            background: #fff;
        }

        .tg-search input:focus {
            border-color: #1a6b2f;
            box-shadow: 0 0 0 3px rgba(26, 107, 47, .1);
        }

        .tg-search i {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 15px;
        }

        .tg-filter-btn {
            padding: 9px 16px;
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

        .tg-filter-btn.active {
            border-color: #1a6b2f;
            background: #f0faf2;
            color: #1a6b2f;
        }

        .tg-filter-btn:hover:not(.active) {
            border-color: #9ca3af;
        }

        /* ── Cards de paciente ───────────────────────────────── */
        .tg-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .tg-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
            transition: box-shadow .2s, transform .2s;
            cursor: default;
        }

        .tg-card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, .1);
            transform: translateY(-2px);
        }

        .tg-card.urgente {
            border-left: 4px solid #dc2626;
        }

        /* Avatar */
        .tg-av {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 800;
            color: #fff;
            flex-shrink: 0;
        }

        .av-m {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        }

        .av-f {
            background: linear-gradient(135deg, #9d174d, #ec4899);
        }

        /* Info */
        .tg-info {
            flex: 1;
            min-width: 0;
        }

        .tg-nome {
            font-size: 14px;
            font-weight: 700;
            color: #1a2e1a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .tg-meta {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 4px;
        }

        .tg-meta span {
            font-size: 11px;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .tg-meta .alerta {
            color: #dc2626;
            font-weight: 700;
        }

        /* Vitais inline */
        .tg-vitais {
            display: flex;
            gap: 8px;
            margin-top: 8px;
            flex-wrap: wrap;
        }

        .vi-chip {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            background: #f0faf2;
            color: #1a6b2f;
            border: 1px solid #d1fae5;
        }

        .vi-chip.warn {
            background: #fef3c7;
            color: #92400e;
            border-color: #fde68a;
        }

        .vi-chip.danger {
            background: #fee2e2;
            color: #991b1b;
            border-color: #fca5a5;
        }

        .vi-chip.obs {
            background: #ede9fe;
            color: #5b21b6;
            border-color: #ddd6fe;
        }

        /* Estado badge */
        .ep-estado {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .es-espera {
            background: #fef3c7;
            color: #92400e;
        }

        .es-consult {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .es-exame {
            background: #ede9fe;
            color: #5b21b6;
        }

        .es-conc {
            background: #d1fae5;
            color: #065f46;
        }

        /* Hora */
        .tg-hora {
            font-size: 18px;
            font-weight: 800;
            color: #1a2e1a;
            text-align: center;
            min-width: 44px;
        }

        .tg-hora small {
            display: block;
            font-size: 10px;
            color: #9ca3af;
            font-weight: 600;
        }

        /* Acção */
        .btn-ver {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            background: #1a6b2f;
            color: #fff;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            transition: background .2s;
        }

        .btn-ver:hover {
            background: #2d9e4a;
            color: #fff;
            text-decoration: none;
        }

        .btn-ver-outline {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border: 2px solid #e5e7eb;
            color: #374151;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            transition: all .2s;
            background: #fff;
        }

        .btn-ver-outline:hover {
            border-color: #1a6b2f;
            color: #1a6b2f;
            text-decoration: none;
        }

        /* Vazio */
        .tg-empty {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }

        .tg-empty i {
            font-size: 52px;
            display: block;
            margin-bottom: 14px;
            opacity: .4;
        }

        .tg-empty p {
            font-size: 15px;
            font-weight: 600;
            margin: 0 0 6px;
        }

        .tg-empty span {
            font-size: 13px;
        }

        /* Linha do tempo lateral */
        .tg-timeline {
            position: relative;
            padding-left: 20px;
        }

        .tg-timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
            border-radius: 2px;
        }

        .tml-dot {
            position: absolute;
            left: -5px;
            top: 50%;
            transform: translateY(-50%);
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        @media(max-width:700px) {
            .tg-counters {
                grid-template-columns: 1fr 1fr;
            }

            .tg-card {
                flex-wrap: wrap;
            }

            .tg-hora {
                display: none;
            }
        }
    </style>

    {{-- HEADER --}}
    <div class="tg-header">
        <div>
            <h1 class="tg-title">
                🏥 Triagem — {{ \Carbon\Carbon::today()->isoFormat('D [de] MMMM [de] YYYY') }}
            </h1>
            <p class="tg-sub">{{ \Carbon\Carbon::now()->format('H:i') }} · {{ $totalHoje }}
                paciente{{ $totalHoje !== 1 ? 's' : '' }} hoje</p>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <a href="{{ route('triagem.estatisticas') }}" class="btn-ver-outline">
                <i class="feather icon-bar-chart-2"></i> Estatísticas
            </a>
            <a href="{{ route('triagem.create') }}" class="btn-ver">
                <i class="feather icon-user-plus"></i> Nova Triagem
            </a>
        </div>
    </div>

    @include('louyout.flash')

    {{-- CONTADORES --}}
    <div class="tg-counters">
        <div class="tg-cnt cn-total">
            <div class="tg-cnt-icon"><i class="feather icon-users"></i></div>
            <div class="tg-cnt-num">{{ $totalHoje }}</div>
            <div class="tg-cnt-lbl">Total Hoje</div>
        </div>
        <div class="tg-cnt cn-espera {{ $emEspera > 0 ? 'pulse' : '' }}">
            <div class="tg-cnt-icon"><i class="feather icon-clock"></i></div>
            <div class="tg-cnt-num">{{ $emEspera }}</div>
            <div class="tg-cnt-lbl">Em Espera</div>
        </div>
        <div class="tg-cnt cn-consult">
            <div class="tg-cnt-icon"><i class="feather icon-heart"></i></div>
            <div class="tg-cnt-num">{{ $emConsulta }}</div>
            <div class="tg-cnt-lbl">Em Consulta / Exame</div>
        </div>
        <div class="tg-cnt cn-conc">
            <div class="tg-cnt-icon"><i class="feather icon-check-circle"></i></div>
            <div class="tg-cnt-num">{{ $concluidos }}</div>
            <div class="tg-cnt-lbl">Concluídos</div>
        </div>
    </div>

    {{-- TOOLBAR: pesquisa + filtros --}}
    <div class="tg-toolbar">
        <div class="tg-search">
            <i class="feather icon-search"></i>
            <input type="text" id="inp-filtro" placeholder="Pesquisar por nome ou processo..." oninput="filtrar()">
        </div>
        <button class="tg-filter-btn active" data-estado="todos" onclick="setFiltro(this,'todos')">Todos</button>
        <button class="tg-filter-btn" data-estado="urgente" onclick="setFiltro(this,'urgente')" style="border-color:#fca5a5;color:#991b1b;">⚡ Urgentes</button>
        <button class="tg-filter-btn" data-estado="em_espera" onclick="setFiltro(this,'em_espera')">Em Espera</button>
        <button class="tg-filter-btn" data-estado="em_consulta" onclick="setFiltro(this,'em_consulta')">Consulta</button>
        <button class="tg-filter-btn" data-estado="concluido" onclick="setFiltro(this,'concluido')">Concluídos</button>
    </div>

    {{-- LISTA --}}
    <div class="tg-list" id="tg-list">
        @forelse($episodios as $ep)
            @php
                // Dados vitais
                $tri = \DB::table('triagem')->where('episodio_id', $ep->episodio_id)->first();
                $temFebre = $tri && $tri->temperatura > 37.5;
                $temSatBaixa = $tri && $tri->saturacao_oxigenio && $tri->saturacao_oxigenio < 95;
                $isUrgente = (bool)($ep->urgente ?? false);

                $avCls = $ep->sexo === 'M' ? 'av-m' : 'av-f';
                $esCls = ['em_espera'=>'es-espera','em_consulta'=>'es-consult','aguarda_exame'=>'es-exame','concluido'=>'es-conc'][$ep->estado] ?? 'es-espera';
                $esLbl = ['em_espera'=>'⏳ Em Espera','em_consulta'=>'🩺 Em Consulta','aguarda_exame'=>'🔬 Aguarda Exame','concluido'=>'✅ Concluído'][$ep->estado] ?? $ep->estado;
            @endphp
            <div class="tg-card {{ ($temFebre || $temSatBaixa || $isUrgente) ? 'urgente' : '' }}" data-estado="{{ $ep->estado }}"
                data-nome="{{ strtolower($ep->nome) }}" data-proc="{{ strtolower($ep->numero_processo ?? '') }}"
                data-urgente="{{ $isUrgente ? '1' : '0' }}">

                {{-- Hora --}}
                <div class="tg-hora">
                    {{ \Carbon\Carbon::parse($ep->created_at)->format('H:i') }}
                    <small>Entrada</small>
                </div>

                {{-- Avatar --}}
                <div class="tg-av {{ $avCls }}">
                    {{ mb_strtoupper(mb_substr($ep->nome, 0, 1)) }}
                </div>

                {{-- Info --}}
                <div class="tg-info">
                    <div class="tg-nome">{{ $ep->nome }}</div>
                    <div class="tg-meta">
                        <span>
                            <i class="feather icon-user" style="font-size:10px;"></i>
                            {{ $ep->sexo === 'M' ? 'Masculino' : 'Feminino' }}
                            @if ($ep->data_nascimento)
                                · {{ \Carbon\Carbon::parse($ep->data_nascimento)->age }} anos
                            @endif
                        </span>
                        @if ($ep->numero_processo)
                            <span>
                                <i class="feather icon-hash" style="font-size:10px;"></i>
                                {{ $ep->numero_processo }}
                            </span>
                        @endif
                        <span>
                            <i class="feather icon-user-check" style="font-size:10px;"></i>
                            {{ $ep->triagem_by }}
                        </span>
                    </div>
                    {{-- Vitais em chips --}}
                    @if ($tri)
                        <div class="tg-vitais">
                            @if ($tri->pressao_arterial)
                                <span class="vi-chip">🩺 {{ $tri->pressao_arterial }}</span>
                            @endif
                            @if ($tri->temperatura)
                                <span class="vi-chip {{ $temFebre ? 'danger' : '' }}">
                                    🌡️ {{ $tri->temperatura }}°C{{ $temFebre ? ' FEBRE' : '' }}
                                </span>
                            @endif
                            @if ($tri->peso && $tri->altura)
                                @php $imc = round($tri->peso / pow($tri->altura/100,2),1); @endphp
                                <span class="vi-chip {{ $imc < 18.5 || $imc >= 25 ? 'warn' : '' }}">
                                    ⚖️ IMC {{ $imc }}
                                </span>
                            @endif
                            @if ($temSatBaixa)
                                <span class="vi-chip danger">💨 Sat.O₂ {{ $tri->saturacao_oxigenio }}%</span>
                            @endif
                            @if ($tri->observacao)
                                <span class="vi-chip obs" title="{{ $tri->observacao }}">
                                    💬 {{ \Str::limit($tri->observacao, 40) }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Estado --}}
                <div style="text-align:right;flex-shrink:0;display:flex;flex-direction:column;align-items:flex-end;gap:6px;">
                    @if($isUrgente)
                        <span style="background:#fee2e2;color:#991b1b;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:800;">⚡ URGENTE</span>
                    @endif
                    <span class="ep-estado {{ $esCls }}">{{ $esLbl }}</span>
                </div>

                {{-- Acção --}}
                <a href="{{ route('triagem.show', $ep->episodio_id) }}"
                    class="{{ $ep->estado === 'concluido' ? 'btn-ver-outline' : 'btn-ver' }}">
                    <i class="feather icon-eye"></i>
                    {{ $ep->estado === 'concluido' ? 'Ver' : 'Detalhe' }}
                </a>
            </div>
        @empty
            <div class="tg-empty">
                <i class="feather icon-clipboard"></i>
                <p>Sem pacientes triados hoje</p>
                <span>Clique em "Nova Triagem" para começar</span>
                <br><br>
                <a href="{{ route('triagem.create') }}" class="btn-ver">
                    <i class="feather icon-user-plus"></i> Registar primeiro paciente
                </a>
            </div>
        @endforelse
    </div>

    <script>
        let filtroEstado = 'todos';

        function setFiltro(btn, estado) {
            filtroEstado = estado;
            document.querySelectorAll('.tg-filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            filtrar();
        }

        function filtrar() {
            const q = document.getElementById('inp-filtro').value.toLowerCase().trim();
            const cards = document.querySelectorAll('#tg-list .tg-card');
            cards.forEach(c => {
                const nome = c.dataset.nome || '';
                const proc = c.dataset.proc || '';
                const estado = c.dataset.estado || '';
                const urgente = c.dataset.urgente === '1';
                const matchQ = !q || nome.includes(q) || proc.includes(q);
                const matchE = filtroEstado === 'todos' ||
                    (filtroEstado === 'urgente' && urgente) ||
                    estado === filtroEstado ||
                    (filtroEstado === 'em_consulta' && (estado === 'em_consulta' || estado === 'aguarda_exame'));
                c.style.display = matchQ && matchE ? '' : 'none';
            });
        }
    </script>

@endsection
