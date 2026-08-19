@extends('louyout.app')
@section('conteodo')
    <style>
        .cl-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .cl-title {
            font-size: 22px;
            font-weight: 800;
            color: #1a2e1a;
            margin: 0;
        }

        .cl-sub {
            font-size: 13px;
            color: #6b7280;
            margin: 4px 0 0;
        }

        /* Contadores */
        .cl-counts {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 22px;
        }

        .cl-cnt {
            border-radius: 14px;
            padding: 16px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: transform .2s;
        }

        .cl-cnt:hover {
            transform: translateY(-2px);
        }

        .cl-cnt.cc1 {
            background: linear-gradient(135deg, #c0620a, #f08030);
        }

        .cl-cnt.cc2 {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        }

        .cl-cnt.cc3 {
            background: linear-gradient(135deg, #5b21b6, #8b5cf6);
        }

        .cl-cnt.cc4 {
            background: linear-gradient(135deg, #334155, #64748b);
        }

        .cl-cnt-ico {
            font-size: 24px;
            opacity: .8;
        }

        .cl-cnt-num {
            font-size: 26px;
            font-weight: 900;
            line-height: 1;
        }

        .cl-cnt-lbl {
            font-size: 11px;
            opacity: .85;
            font-weight: 600;
        }

        /* Toolbar */
        .cl-toolbar {
            display: flex;
            gap: 10px;
            margin-bottom: 16px;
            flex-wrap: wrap;
            align-items: center;
        }

        .cl-search {
            flex: 1;
            min-width: 180px;
            position: relative;
        }

        .cl-search input {
            width: 100%;
            padding: 9px 14px 9px 36px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 13px;
            outline: none;
            font-family: 'Inter', sans-serif;
        }

        .cl-search input:focus {
            border-color: #1a6b2f;
            box-shadow: 0 0 0 3px rgba(26, 107, 47, .1);
        }

        .cl-search i {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .cl-fbtn {
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

        .cl-fbtn.active {
            border-color: #1a6b2f;
            background: #f0faf2;
            color: #1a6b2f;
        }

        /* Cards de paciente */
        .cl-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 28px;
        }

        .cl-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
            transition: box-shadow .2s, transform .2s;
            display: block;
            text-decoration: none;
            color: inherit;
        }

        .cl-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, .1);
            transform: translateY(-2px);
            text-decoration: none;
            color: inherit;
        }

        .cl-card.urgente {
            border-left: 4px solid #dc2626;
        }

        .cl-card-inner {
            display: flex;
            align-items: stretch;
            gap: 0;
        }

        /* Número de posição */
        .cl-pos {
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

        .cl-pos.urgente-pos {
            background: #fef2f2;
            color: #dc2626;
        }

        /* Conteúdo */
        .cl-content {
            flex: 1;
            padding: 16px 20px;
            min-width: 0;
        }

        .cl-row1 {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 6px;
        }

        .cl-av {
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

        .av-m2 {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        }

        .av-f2 {
            background: linear-gradient(135deg, #9d174d, #ec4899);
        }

        .cl-nome {
            font-size: 15px;
            font-weight: 700;
            color: #1a2e1a;
        }

        .cl-chips {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-top: 8px;
        }

        .cl-chip {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .ch-info {
            background: #f0faf2;
            color: #1a6b2f;
            border: 1px solid #d1fae5;
        }

        .ch-warn {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .ch-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .ch-obs {
            background: #ede9fe;
            color: #5b21b6;
            border: 1px solid #ddd6fe;
        }

        .ch-hora {
            background: #f3f4f6;
            color: #6b7280;
        }

        /* Estado badge */
        .cl-estado {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .ce-espera {
            background: #fef3c7;
            color: #92400e;
        }

        .ce-consult {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .ce-exame {
            background: #ede9fe;
            color: #5b21b6;
        }

        .ce-conc {
            background: #d1fae5;
            color: #065f46;
        }

        /* Acção */
        .cl-action {
            display: flex;
            align-items: center;
            padding: 0 20px;
            flex-shrink: 0;
        }

        .btn-iniciar {
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

        .btn-iniciar:hover {
            background: #2d9e4a;
        }

        .btn-continuar {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            background: #3b82f6;
            color: #fff;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            transition: background .2s;
            white-space: nowrap;
        }

        .btn-continuar:hover {
            background: #2563eb;
        }

        .btn-ver2 {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            border: 2px solid #e5e7eb;
            background: #fff;
            color: #374151;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            transition: all .2s;
            white-space: nowrap;
        }

        .btn-ver2:hover {
            border-color: #1a6b2f;
            color: #1a6b2f;
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

        .conc-list {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .conc-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 18px;
            border-bottom: 1px solid #f3f4f6;
        }

        .conc-item:last-child {
            border-bottom: none;
        }

        @media(max-width:700px) {
            .cl-counts {
                grid-template-columns: 1fr 1fr;
            }

            .cl-card-inner {
                flex-wrap: wrap;
            }

            .cl-pos {
                width: 100%;
                height: 40px;
            }

            .cl-action {
                padding: 0 16px 16px;
            }
        }
    </style>

    {{-- HEADER --}}
    <div class="cl-header">
        <div>
            <h1 class="cl-title">🩺 Lista de Espera</h1>
            <p class="cl-sub">{{ \Carbon\Carbon::today()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
        </div>
        <a href="{{ route('home.index') }}"
            style="display:inline-flex;align-items:center;gap:5px;padding:9px 18px;border:2px solid #e5e7eb;border-radius:10px;color:#374151;font-size:13px;font-weight:600;text-decoration:none;transition:all .2s;"
            onmouseover="this.style.borderColor='#1a6b2f';this.style.color='#1a6b2f'"
            onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#374151'">
            <i class="feather icon-home"></i> Início
        </a>
    </div>

    @include('louyout.flash')

    {{-- CONTADORES --}}
    @php
        $totalFila = $espera->count();
        $nEspera = $espera->where('estado', 'em_espera')->count();
        $nConsult = $espera->whereIn('estado', ['em_consulta', 'aguarda_exame'])->count();
        $nConc = $concluidos->count();
    @endphp
    <div class="cl-counts">
        <div class="cl-cnt cc1 {{ $nEspera > 0 ? 'pulse' : '' }}">
            <div class="cl-cnt-ico">⏳</div>
            <div>
                <div class="cl-cnt-num">{{ $nEspera }}</div>
                <div class="cl-cnt-lbl">Em Espera</div>
            </div>
        </div>
        <div class="cl-cnt cc2">
            <div class="cl-cnt-ico">🩺</div>
            <div>
                <div class="cl-cnt-num">{{ $nConsult }}</div>
                <div class="cl-cnt-lbl">Em Consulta</div>
            </div>
        </div>
        <div class="cl-cnt cc3">
            <div class="cl-cnt-ico">🔬</div>
            @php $nExame = $espera->where('estado','aguarda_exame')->count(); @endphp
            <div>
                <div class="cl-cnt-num">{{ $nExame }}</div>
                <div class="cl-cnt-lbl">Aguarda Exame</div>
            </div>
        </div>
        <div class="cl-cnt cc4">
            <div class="cl-cnt-ico">✅</div>
            <div>
                <div class="cl-cnt-num">{{ $nConc }}</div>
                <div class="cl-cnt-lbl">Concluídos</div>
            </div>
        </div>
    </div>

    {{-- TOOLBAR --}}
    <div class="cl-toolbar">
        <div class="cl-search">
            <i class="feather icon-search" style="font-size:14px;"></i>
            <input type="text" id="inp-cl" placeholder="Pesquisar por nome..." oninput="filtrarCl()">
        </div>
        <button class="cl-fbtn active" data-f="todos" onclick="setFCl(this,'todos')">Todos</button>
        <button class="cl-fbtn" data-f="urgente" onclick="setFCl(this,'urgente')" style="border-color:#fca5a5;color:#991b1b;">⚡ Urgentes</button>
        <button class="cl-fbtn" data-f="em_espera" onclick="setFCl(this,'em_espera')">Em Espera</button>
        <button class="cl-fbtn" data-f="em_consulta" onclick="setFCl(this,'em_consulta')">Consulta</button>
    </div>

    {{-- LISTA DE ESPERA --}}
    @if ($espera->isEmpty())
        <div style="text-align:center;padding:64px 20px;background:#fff;border-radius:16px;border:1px solid #e5e7eb;">
            <div style="font-size:56px;margin-bottom:14px;">🎉</div>
            <div style="font-size:18px;font-weight:700;color:#1a2e1a;">Fila de espera vazia!</div>
            <div style="font-size:14px;color:#6b7280;margin-top:6px;">Todos os pacientes foram atendidos.</div>
        </div>
    @else
        <div class="cl-list" id="cl-list">
            @php $pos = 0; @endphp
            @foreach ($espera as $ep)
                @php
                    $pos++;
                    $avCls = $ep->sexo === 'M' ? 'av-m2' : 'av-f2';
                    $temFebre = $ep->temperatura && $ep->temperatura > 37.5;
                    $urgente  = (bool)($ep->urgente ?? false) || $temFebre;

                    $esCls = ['em_espera'=>'ce-espera','em_consulta'=>'ce-consult','aguarda_exame'=>'ce-exame'][$ep->estado] ?? 'ce-espera';
                    $esLbl = ['em_espera'=>'⏳ Em Espera','em_consulta'=>'🩺 Em Consulta','aguarda_exame'=>'🔬 Aguarda Exame'][$ep->estado] ?? $ep->estado;
                    $btnCls = $ep->estado === 'em_espera' ? 'btn-iniciar' : 'btn-continuar';
                    $btnTxt = $ep->estado === 'em_espera' ? 'Iniciar Consulta' : 'Continuar';
                    $btnIcon = $ep->estado === 'em_espera' ? 'icon-play' : 'icon-edit-3';
                @endphp
                <a href="{{ route('consultas.show', $ep->episodio_id) }}" class="cl-card {{ $urgente ? 'urgente' : '' }}"
                    data-estado="{{ $ep->estado }}" data-nome="{{ strtolower($ep->nome) }}"
                    data-urgente="{{ $urgente ? '1' : '0' }}">
                    <div class="cl-card-inner">
                        <div class="cl-pos {{ $urgente ? 'urgente-pos' : '' }}">{{ $pos }}</div>
                        <div class="cl-content">
                            <div class="cl-row1">
                                <div class="cl-av {{ $avCls }}">{{ mb_strtoupper(mb_substr($ep->nome, 0, 1)) }}</div>
                                <div style="flex:1;min-width:0;">
                                    <div class="cl-nome" style="display:flex;align-items:center;gap:8px;">
                                        {{ $ep->nome }}
                                        @if($urgente)
                                            <span style="background:#fee2e2;color:#991b1b;padding:2px 9px;border-radius:20px;font-size:10px;font-weight:800;white-space:nowrap;">⚡ URGENTE</span>
                                        @endif
                                    </div>
                                    <div
                                        style="font-size:12px;color:#6b7280;margin-top:2px;display:flex;gap:10px;flex-wrap:wrap;">
                                        @if ($ep->data_nascimento)
                                            <span>{{ $ep->sexo === 'M' ? '♂' : '♀' }}
                                                {{ \Carbon\Carbon::parse($ep->data_nascimento)->age }} anos</span>
                                        @endif
                                        @if ($ep->numero_processo)
                                            <span># {{ $ep->numero_processo }}</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="cl-estado {{ $esCls }}">{{ $esLbl }}</span>
                            </div>
                            <div class="cl-chips">
                                <span class="cl-chip ch-hora">🕐
                                    {{ \Carbon\Carbon::parse($ep->created_at)->format('H:i') }}</span>
                                @if ($ep->pressao_arterial)
                                    <span class="cl-chip ch-info">🩺 {{ $ep->pressao_arterial }}</span>
                                @endif
                                @if ($ep->temperatura)
                                    <span class="cl-chip {{ $temFebre ? 'ch-danger' : 'ch-info' }}">
                                        🌡️ {{ $ep->temperatura }}°C {{ $temFebre ? '⚠️ FEBRE' : '' }}
                                    </span>
                                @endif
                                @if ($ep->obs_triagem)
                                    <span class="cl-chip ch-obs">💬 {{ \Str::limit($ep->obs_triagem, 45) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="cl-action">
                            <span class="{{ $btnCls }}">
                                <i class="feather {{ $btnIcon }}"></i>
                                {{ $btnTxt }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    {{-- CONCLUÍDOS HOJE --}}
    @if ($concluidos->isNotEmpty())
        <div class="sec-title">
            <span style="width:8px;height:8px;border-radius:50%;background:#1a6b2f;display:inline-block;"></span>
            Concluídos hoje ({{ $concluidos->count() }})
        </div>
        <div class="conc-list">
            @foreach ($concluidos as $c)
                <a href="{{ route('consultas.show', $c->episodio_id) }}" class="conc-item" style="text-decoration:none;">
                    <div
                        style="width:32px;height:32px;border-radius:50%;background:#d1fae5;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#065f46;flex-shrink:0;">
                        {{ mb_strtoupper(mb_substr($c->nome, 0, 1)) }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div
                            style="font-size:13px;font-weight:600;color:#1a2e1a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $c->nome }}</div>
                    </div>
                    <div style="font-size:11px;color:#9ca3af;">{{ \Carbon\Carbon::parse($c->updated_at)->format('H:i') }}
                    </div>
                    <span
                        style="padding:2px 10px;border-radius:20px;font-size:11px;font-weight:700;background:#d1fae5;color:#065f46;">✅
                        Concluído</span>
                    <i class="feather icon-chevron-right" style="color:#d1d5db;font-size:14px;"></i>
                </a>
            @endforeach
        </div>
    @endif

    <script>
        let filtroClEstado = 'todos';

        function setFCl(btn, estado) {
            filtroClEstado = estado;
            document.querySelectorAll('.cl-fbtn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            filtrarCl();
        }

        function filtrarCl() {
            const q = document.getElementById('inp-cl').value.toLowerCase().trim();
            const cards = document.querySelectorAll('#cl-list .cl-card');
            cards.forEach(c => {
                const nome    = c.dataset.nome    || '';
                const estado  = c.dataset.estado  || '';
                const urgente = c.dataset.urgente === '1';
                const matchQ  = !q || nome.includes(q);
                const matchE  = filtroClEstado === 'todos' ||
                    (filtroClEstado === 'urgente' && urgente) ||
                    estado === filtroClEstado ||
                    (filtroClEstado === 'em_consulta' && (estado === 'em_consulta' || estado === 'aguarda_exame'));
                c.style.display = matchQ && matchE ? '' : 'none';
            });
        }
    </script>
@endsection
