@extends('louyout.app')
@section('conteodo')

    <style>
        /* ── Layout ── */
        .cs-wrap {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 20px;
            align-items: start;
        }

        /* ── Header paciente ── */
        .cs-pac-banner {
            background: linear-gradient(135deg, #0f3d1e, #1a6b2f);
            border-radius: 18px;
            padding: 22px 26px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .cs-pac-banner::before {
            content: '';
            position: absolute;
            right: -30px;
            top: -30px;
            width: 130px;
            height: 130px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
        }

        .cs-pac-av {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 900;
            flex-shrink: 0;
            background: rgba(255, 255, 255, .2);
        }

        .cs-pac-nome {
            font-size: 20px;
            font-weight: 800;
        }

        .cs-pac-meta {
            font-size: 13px;
            opacity: .8;
            margin-top: 4px;
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .cs-ep-badge {
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            margin-left: auto;
            flex-shrink: 0;
        }

        .cs-ep-espera {
            background: rgba(254, 243, 199, .9);
            color: #92400e;
        }

        .cs-ep-consult {
            background: rgba(219, 234, 254, .9);
            color: #1d4ed8;
        }

        .cs-ep-exame {
            background: rgba(237, 233, 254, .9);
            color: #5b21b6;
        }

        .cs-ep-conc {
            background: rgba(209, 250, 229, .9);
            color: #065f46;
        }

        /* ── Tabs ── */
        .cs-tabs {
            display: flex;
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 20px;
            gap: 0;
        }

        .cs-tab {
            padding: 11px 22px;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: all .2s;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .cs-tab.active {
            color: #1a6b2f;
            border-bottom-color: #1a6b2f;
        }

        .cs-tab:hover:not(.active) {
            color: #374151;
        }

        .cs-tab .tab-badge {
            background: #ef4444;
            color: #fff;
            font-size: 10px;
            font-weight: 800;
            padding: 1px 7px;
            border-radius: 20px;
        }

        .cs-tab .tab-ok {
            background: #1a6b2f;
            color: #fff;
            font-size: 10px;
            font-weight: 800;
            padding: 1px 7px;
            border-radius: 20px;
        }

        .cs-panel {
            display: none;
        }

        .cs-panel.active {
            display: block;
        }

        /* ── Card genérico ── */
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

        /* ── Formulário ── */
        .fg {
            margin-bottom: 14px;
        }

        .fg label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #374151;
            margin-bottom: 6px;
        }

        .fg label .req {
            color: #ef4444;
        }

        .fc {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 11px;
            font-size: 13px;
            color: #1a2332;
            background: #f9fafb;
            outline: none;
            transition: all .2s;
            font-family: 'Inter', sans-serif;
        }

        .fc:focus {
            border-color: #1a6b2f;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(26, 107, 47, .1);
        }

        .row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        /* ── Botões ── */
        .btn-g {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: all .2s;
        }

        .btn-g:hover {
            opacity: .9;
            transform: translateY(-1px);
        }

        .btn-primary-g {
            background: #1a6b2f;
            color: #fff;
        }

        .btn-blue-g {
            background: #3b82f6;
            color: #fff;
        }

        .btn-slate-g {
            background: #334155;
            color: #fff;
        }

        .btn-outline-g {
            background: #fff;
            color: #374151;
            border: 2px solid #e5e7eb;
        }

        .btn-outline-g:hover {
            border-color: #1a6b2f;
            color: #1a6b2f;
        }

        /* ── Pedidos de exame ── */
        .pe-card {
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            margin-bottom: 10px;
            transition: box-shadow .2s;
        }

        .pe-card:hover {
            box-shadow: 0 4px 14px rgba(0, 0, 0, .08);
        }

        .pe-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 18px;
            background: #f9fafb;
            border-bottom: 1px solid #f3f4f6;
            gap: 10px;
        }

        .pe-nome {
            font-size: 14px;
            font-weight: 700;
            color: #1a2e1a;
        }

        .pe-body {
            padding: 12px 18px;
            font-size: 13px;
            color: #374151;
        }

        .pe-resultado {
            padding: 14px 18px;
            background: #f0faf2;
            border-top: 1px solid #d1fae5;
        }

        .pe-resultado-head {
            font-size: 11px;
            font-weight: 700;
            color: #1a6b2f;
            text-transform: uppercase;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .badge-pend {
            background: #fef3c7;
            color: #92400e;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-conc {
            background: #d1fae5;
            color: #065f46;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-urg {
            background: #fee2e2;
            color: #991b1b;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
        }

        /* Checkbox urgente */
        .urg-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 10px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            transition: all .2s;
        }

        .urg-label:has(input:checked) {
            border-color: #dc2626;
            background: #fef2f2;
            color: #dc2626;
        }

        /* ── Medicamentos receita ── */
        .med-item {
            background: #f9fafb;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            padding: 16px;
            margin-bottom: 10px;
            position: relative;
            transition: box-shadow .2s;
        }

        .med-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
        }

        .med-del {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #fee2e2;
            border: none;
            border-radius: 8px;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #991b1b;
            font-size: 13px;
            transition: background .2s;
        }

        .med-del:hover {
            background: #fca5a5;
        }

        .add-med-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 11px;
            background: #f0faf2;
            border: 2px dashed #1a6b2f;
            border-radius: 12px;
            color: #1a6b2f;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s;
            font-family: 'Inter', sans-serif;
        }

        .add-med-btn:hover {
            background: #d1fae5;
        }

        /* ── Vitais lateral ── */
        .vt-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 0;
            border-bottom: 1px solid #f3f4f6;
            font-size: 13px;
        }

        .vt-row:last-child {
            border-bottom: none;
        }

        .vt-lbl2 {
            color: #9ca3af;
            font-size: 12px;
        }

        .vt-val2 {
            font-weight: 700;
            color: #1a2e1a;
        }

        .vt-val2.warn {
            color: #92400e;
        }

        .vt-val2.danger {
            color: #991b1b;
        }

        /* Obs triagem */
        .obs-box {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 13px;
            color: #92400e;
            margin-top: 10px;
        }

        /* Diagnóstico guardado */
        .diag-saved {
            background: #f0faf2;
            border: 1px solid #d1fae5;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 14px;
        }

        .diag-saved-lbl {
            font-size: 11px;
            font-weight: 700;
            color: #1a6b2f;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .diag-saved-txt {
            font-size: 14px;
            color: #1a2e1a;
            line-height: 1.6;
        }

        @media(max-width:900px) {
            .cs-wrap {
                grid-template-columns: 1fr;
            }

            .row-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>

    {{-- BANNER PACIENTE --}}
    @php
        $esCls = ['em_espera'=>'cs-ep-espera','em_consulta'=>'cs-ep-consult','aguarda_exame'=>'cs-ep-exame','concluido'=>'cs-ep-conc'][$episodio->ep_estado] ?? 'cs-ep-consult';
        $esLbl = ['em_espera'=>'⏳ Em Espera','em_consulta'=>'🩺 Em Consulta','aguarda_exame'=>'🔬 Aguarda Exame','concluido'=>'✅ Concluído'][$episodio->ep_estado] ?? $episodio->ep_estado;
        $epUrgente = (bool)\DB::table('episodio')->where('id',$episodio->episodio_id)->value('urgente');
    @endphp

    @if($epUrgente)
    <div style="background:linear-gradient(135deg,#7f1d1d,#dc2626);border-radius:14px;padding:12px 20px;margin-bottom:14px;display:flex;align-items:center;gap:12px;color:#fff;">
        <span style="font-size:26px;">⚡</span>
        <div>
            <div style="font-size:14px;font-weight:800;letter-spacing:.5px;">CONSULTA URGENTE</div>
            <div style="font-size:12px;opacity:.85;">Este paciente foi marcado como urgente na triagem. Atendimento prioritário.</div>
        </div>
    </div>
    @endif

    <div class="cs-pac-banner">
        <div class="cs-pac-av">{{ mb_strtoupper(mb_substr($episodio->nome, 0, 1)) }}</div>
        <div style="flex:1;min-width:0;">
            <div
                style="font-size:11px;font-weight:700;opacity:.7;text-transform:uppercase;letter-spacing:.8px;margin-bottom:3px;">
                Episódio #{{ $episodio->episodio_id }}
            </div>
            <div class="cs-pac-nome">{{ $episodio->nome }}</div>
            <div class="cs-pac-meta">
                @if ($episodio->data_nascimento)
                    <span>{{ $episodio->sexo === 'M' ? '♂' : '♀' }}
                        {{ \Carbon\Carbon::parse($episodio->data_nascimento)->age }} anos</span>
                @endif
                @if ($episodio->numero_processo)
                    <span># {{ $episodio->numero_processo }}</span>
                @endif
                <span>📅 {{ \Carbon\Carbon::parse($episodio->data)->format('d/m/Y') }}</span>
            </div>
        </div>
        <span class="cs-ep-badge {{ $esCls }}">{{ $esLbl }}</span>
        <a href="{{ route('consultas.index') }}"
            style="display:inline-flex;align-items:center;gap:5px;padding:8px 16px;background:rgba(255,255,255,.15);color:#fff;border-radius:9px;font-size:13px;font-weight:600;text-decoration:none;margin-left:8px;transition:background .2s;"
            onmouseover="this.style.background='rgba(255,255,255,.25)'"
            onmouseout="this.style.background='rgba(255,255,255,.15)'">
            <i class="feather icon-arrow-left"></i> Voltar
        </a>
    </div>

    @include('louyout.flash')

    <div class="cs-wrap">

        {{-- COLUNA PRINCIPAL --}}
        <div>

            {{-- TABS --}}
            <div class="cs-tabs">
                <button class="cs-tab active" onclick="showTab('diag',this)">
                    <i class="feather icon-clipboard" style="font-size:13px;"></i> Diagnóstico
                    @if ($consulta && $consulta->diagnostico)
                        <span class="tab-ok">✓</span>
                    @endif
                </button>
                <button class="cs-tab" onclick="showTab('exames',this)">
                    <i class="feather icon-activity" style="font-size:13px;"></i> Exames
                    @if ($pedidos->where('estado', 'pendente')->count() > 0)
                        <span class="tab-badge">{{ $pedidos->where('estado', 'pendente')->count() }}</span>
                    @elseif($pedidos->count() > 0)
                        <span class="tab-ok">{{ $pedidos->count() }}</span>
                    @endif
                </button>
                <button class="cs-tab" onclick="showTab('receita',this)">
                    <i class="feather icon-file-text" style="font-size:13px;"></i> Receita
                    @if ($receita)
                        <span class="{{ $receita->estado === 'dispensada' ? 'tab-ok' : 'tab-badge' }}">
                            {{ $receita->estado === 'dispensada' ? '✓' : '!' }}
                        </span>
                    @endif
                </button>
                <button class="cs-tab" onclick="showTab('prescricao',this)">
                    <i class="feather icon-edit" style="font-size:13px;"></i> Prescrição
                    @if ($prescricao)
                        <span class="tab-ok">✓</span>
                    @endif
                </button>
            </div>

            {{-- PAINEL: DIAGNÓSTICO ─────────────────────────────────────── --}}
            <div class="cs-panel active" id="panel-diag">
                <form action="{{ route('consultas.diagnostico', $episodio->episodio_id) }}" method="POST">
                    @csrf
                    <div class="f-card">
                        <div class="f-card-head">
                            <i class="feather icon-edit-3"></i>
                            <span>Diagnóstico</span>
                            @if ($consulta && $consulta->updated_at)
                                <span style="margin-left:auto;font-size:11px;color:#9ca3af;font-weight:400;">
                                    Última actualização: {{ \Carbon\Carbon::parse($consulta->updated_at)->format('H:i') }}
                                </span>
                            @endif
                        </div>
                        <div class="f-card-body">
                            @if ($consulta && $consulta->diagnostico)
                                <div class="diag-saved">
                                    <div class="diag-saved-lbl"><i class="feather icon-check-circle"
                                            style="margin-right:4px;"></i>Diagnóstico guardado</div>
                                    <div class="diag-saved-txt">{{ $consulta->diagnostico }}</div>
                                </div>
                            @endif
                            <div class="fg">
                                <label>{{ $consulta && $consulta->diagnostico ? 'Actualizar Diagnóstico' : 'Diagnóstico' }}
                                    <span class="req">*</span></label>
                                <textarea name="diagnostico" class="fc" rows="4" required
                                    placeholder="Registe o diagnóstico clínico, CID se aplicável...">{{ old('diagnostico', optional($consulta)->diagnostico) }}</textarea>
                            </div>
                            <div class="fg" style="margin-bottom:0;">
                                <label>Observações Clínicas</label>
                                <textarea name="observacao" class="fc" rows="3" placeholder="Plano terapêutico, notas adicionais...">{{ old('observacao', optional($consulta)->observacao) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        <button type="submit" class="btn-g btn-primary-g">
                            <i class="feather icon-save"></i> Guardar Diagnóstico
                        </button>
                        @if ($episodio->ep_estado !== 'concluido')
                            <button type="button" onclick="confirmarConcluir()" class="btn-g btn-slate-g">
                                <i class="feather icon-check-square"></i> Concluir sem Receita
                            </button>
                        @endif
                    </div>
                </form>
            </div>

            {{-- PAINEL: EXAMES ──────────────────────────────────────────── --}}
            <div class="cs-panel" id="panel-exames">

                {{-- Pedidos existentes --}}
                @if ($pedidos->isNotEmpty())
                    <div style="margin-bottom:20px;">
                        <div
                            style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.8px;margin-bottom:10px;">
                            Pedidos enviados ({{ $pedidos->count() }})
                        </div>
                        @foreach ($pedidos as $p)
                            <div class="pe-card">
                                <div class="pe-head">
                                    <div style="flex:1;min-width:0;">
                                        <div style="display:flex;align-items:center;gap:8px;">
                                            <div class="pe-nome">🔬 {{ $p->descricao_exame }}</div>
                                            @if ($p->urgente)
                                                <span class="badge-urg">⚡ URGENTE</span>
                                            @endif
                                        </div>
                                        @if ($p->observacao)
                                            <div style="font-size:12px;color:#6b7280;margin-top:3px;font-style:italic;">
                                                {{ $p->observacao }}</div>
                                        @endif
                                    </div>
                                    <span class="{{ $p->estado === 'pendente' ? 'badge-pend' : 'badge-conc' }}">
                                        {{ $p->estado === 'pendente' ? '⏳ Pendente' : '✅ Resultado Disponível' }}
                                    </span>
                                </div>
                                @if ($p->resultado)
                                    <div class="pe-resultado">
                                        <div class="pe-resultado-head">
                                            <i class="feather icon-check-circle"></i>
                                            Resultado — {{ \Carbon\Carbon::parse($p->data_resultado)->format('d/m/Y') }}
                                        </div>
                                        <div style="font-size:13px;color:#374151;white-space:pre-wrap;line-height:1.6;">
                                            {{ $p->resultado }}</div>
                                        @if ($p->ficheiro_path)
                                            <a href="{{ asset('storage/' . $p->ficheiro_path) }}" target="_blank"
                                                style="display:inline-flex;align-items:center;gap:5px;margin-top:10px;padding:6px 14px;background:#1a6b2f;color:#fff;border-radius:8px;font-size:12px;font-weight:600;text-decoration:none;">
                                                <i class="feather icon-download"></i> Descarregar ficheiro
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Novo pedido --}}
                @if ($episodio->ep_estado !== 'concluido')
                    <div class="f-card">
                        <div class="f-card-head">
                            <i class="feather icon-plus-circle"></i>
                            <span>Solicitar Novo Exame</span>
                        </div>
                        <div class="f-card-body">
                            <form action="{{ route('consultas.exame', $episodio->episodio_id) }}" method="POST">
                                @csrf
                                <div class="fg">
                                    <label>Exame Solicitado <span class="req">*</span></label>
                                    <input type="text" name="descricao_exame" class="fc" required
                                        placeholder="Ex: Hemograma completo, Glicemia em jejum, Radiografia tórax...">
                                </div>
                                <div class="row-2">
                                    <div class="fg" style="margin:0;">
                                        <label>Instruções ao Laboratório</label>
                                        <input type="text" name="observacao" class="fc" placeholder="Opcional...">
                                    </div>
                                    <div class="fg"
                                        style="margin:0;display:flex;align-items:flex-end;padding-bottom:1px;">
                                        <label class="urg-label">
                                            <input type="checkbox" name="urgente" value="1"
                                                style="accent-color:#dc2626;width:16px;height:16px;">
                                            ⚡ Marcar como Urgente
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="btn-g btn-blue-g" style="margin-top:6px;">
                                    <i class="feather icon-send"></i> Enviar ao Laboratório
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            {{-- PAINEL: RECEITA ─────────────────────────────────────────── --}}
            <div class="cs-panel" id="panel-receita">

                @if ($receita && $receita->estado === 'dispensada')
                    <div
                        style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:14px;padding:16px 20px;margin-bottom:16px;display:flex;align-items:center;gap:12px;">
                        <span style="font-size:28px;">✅</span>
                        <div>
                            <div style="font-size:15px;font-weight:700;color:#065f46;">Receita dispensada pela Farmácia
                            </div>
                            <div style="font-size:12px;color:#065f46;opacity:.8;margin-top:2px;">O paciente já levantou a
                                medicação.</div>
                        </div>
                    </div>
                @endif

                {{-- Medicamentos prescritos (readonly se dispensada) --}}
                @if ($receita && $receita->itens->isNotEmpty())
                    <div class="f-card">
                        <div class="f-card-head">
                            <i class="feather icon-list"></i>
                            <span>Medicamentos Prescritos</span>
                            <span
                                style="margin-left:auto;font-size:12px;font-weight:400;color:#6b7280;">{{ $receita->itens->count() }}
                                item(ns)</span>
                        </div>
                        <div style="overflow-x:auto;">
                            <table style="width:100%;border-collapse:collapse;font-size:13px;">
                                <thead>
                                    <tr style="background:#f0faf2;">
                                        <th
                                            style="padding:10px 16px;text-align:left;font-size:11px;font-weight:700;text-transform:uppercase;color:#1a6b2f;border-bottom:2px solid #d1fae5;">
                                            Medicamento</th>
                                        <th
                                            style="padding:10px 16px;text-align:left;font-size:11px;font-weight:700;text-transform:uppercase;color:#1a6b2f;border-bottom:2px solid #d1fae5;">
                                            Dose</th>
                                        <th
                                            style="padding:10px 16px;text-align:left;font-size:11px;font-weight:700;text-transform:uppercase;color:#1a6b2f;border-bottom:2px solid #d1fae5;">
                                            Frequência</th>
                                        <th
                                            style="padding:10px 16px;text-align:left;font-size:11px;font-weight:700;text-transform:uppercase;color:#1a6b2f;border-bottom:2px solid #d1fae5;">
                                            Duração</th>
                                        <th
                                            style="padding:10px 16px;text-align:center;font-size:11px;font-weight:700;text-transform:uppercase;color:#1a6b2f;border-bottom:2px solid #d1fae5;">
                                            Qtd</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($receita->itens as $item)
                                        <tr>
                                            <td
                                                style="padding:11px 16px;border-bottom:1px solid #f3f4f6;font-weight:700;color:#1a2e1a;">
                                                💊 {{ optional($item->produto)->produto ?? '—' }}
                                                @if (optional($item->produto)->apresentacao)
                                                    <span style="font-size:11px;color:#9ca3af;font-weight:400;"> —
                                                        {{ $item->produto->apresentacao }}</span>
                                                @endif
                                            </td>
                                            <td style="padding:11px 16px;border-bottom:1px solid #f3f4f6;color:#6b7280;">
                                                {{ $item->dose ?? '—' }}</td>
                                            <td style="padding:11px 16px;border-bottom:1px solid #f3f4f6;color:#6b7280;">
                                                {{ $item->frequencia ?? '—' }}</td>
                                            <td style="padding:11px 16px;border-bottom:1px solid #f3f4f6;color:#6b7280;">
                                                {{ $item->duracao ?? '—' }}</td>
                                            <td
                                                style="padding:11px 16px;border-bottom:1px solid #f3f4f6;text-align:center;font-weight:800;font-size:16px;color:#1a6b2f;">
                                                {{ $item->quantidade }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                {{-- Formulário nova/actualizar receita --}}
                @if ($episodio->ep_estado !== 'concluido' || ($receita && $receita->estado !== 'dispensada'))
                    <div class="f-card">
                        <div class="f-card-head">
                            <i class="feather icon-file-text"></i>
                            <span>{{ $receita ? 'Actualizar Receita' : 'Nova Receita Médica' }}</span>
                        </div>
                        <div class="f-card-body">
                            <form action="{{ route('consultas.receita', $episodio->episodio_id) }}" method="POST"
                                id="form-receita">
                                @csrf

                                <div id="lista-med" style="margin-bottom:12px;"></div>

                                <button type="button" class="add-med-btn" onclick="addMed()"
                                    style="margin-bottom:16px;">
                                    <i class="feather icon-plus-circle"></i> Adicionar Medicamento
                                </button>

                                <div class="fg" style="margin-bottom:0;">
                                    <label>Observações para a Farmácia</label>
                                    <textarea name="observacao" class="fc" rows="2"
                                        placeholder="Instruções especiais, alergias, substituições...">{{ optional($receita)->observacao }}</textarea>
                                </div>

                                <div style="margin-top:16px;display:flex;gap:10px;">
                                    <button type="submit" class="btn-g btn-primary-g" style="flex:1;"
                                        onclick="return validarReceita()">
                                        <i class="feather icon-send"></i> Enviar à Farmácia e Concluir
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            {{-- PAINEL: PRESCRIÇÃO MÉDICA ──────────────────────────────── --}}
            <div class="cs-panel" id="panel-prescricao">

                @if($prescricao)
                {{-- Prescrição existente --}}
                <div class="f-card" style="margin-bottom:16px;">
                    <div class="f-card-head">
                        <i class="feather icon-check-circle"></i>
                        <span>Prescrição Guardada</span>
                        <a href="{{ route('consultas.prescricao.pdf', $episodio->episodio_id) }}" target="_blank"
                           style="margin-left:auto;display:inline-flex;align-items:center;gap:5px;padding:6px 14px;background:#ede9fe;color:#5b21b6;border-radius:8px;font-size:12px;font-weight:600;text-decoration:none;">
                            <i class="feather icon-eye"></i> Ver PDF
                        </a>
                    </div>
                    <div style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;font-size:13px;">
                            <thead>
                                <tr style="background:#f0faf2;">
                                    <th style="padding:9px 14px;text-align:left;font-size:11px;font-weight:700;text-transform:uppercase;color:#1a6b2f;border-bottom:2px solid #d1fae5;">#</th>
                                    <th style="padding:9px 14px;text-align:left;font-size:11px;font-weight:700;text-transform:uppercase;color:#1a6b2f;border-bottom:2px solid #d1fae5;">Medicamento</th>
                                    <th style="padding:9px 14px;text-align:left;font-size:11px;font-weight:700;text-transform:uppercase;color:#1a6b2f;border-bottom:2px solid #d1fae5;">Dosagem</th>
                                    <th style="padding:9px 14px;text-align:left;font-size:11px;font-weight:700;text-transform:uppercase;color:#1a6b2f;border-bottom:2px solid #d1fae5;">Frequência</th>
                                    <th style="padding:9px 14px;text-align:left;font-size:11px;font-weight:700;text-transform:uppercase;color:#1a6b2f;border-bottom:2px solid #d1fae5;">Duração</th>
                                    <th style="padding:9px 14px;text-align:center;font-size:11px;font-weight:700;text-transform:uppercase;color:#1a6b2f;border-bottom:2px solid #d1fae5;">Qtd</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($prescricao->itens as $i => $it)
                                <tr>
                                    <td style="padding:10px 14px;border-bottom:1px solid #f3f4f6;color:#9ca3af;font-weight:700;">{{ $i+1 }}</td>
                                    <td style="padding:10px 14px;border-bottom:1px solid #f3f4f6;font-weight:700;color:#1a2e1a;">
                                        💊 {{ $it->medicamento }}
                                        @if($it->forma_farmaceutica)
                                            <span style="font-size:11px;color:#9ca3af;font-weight:400;"> — {{ $it->forma_farmaceutica }}</span>
                                        @endif
                                        @if($it->dosagem)
                                            <span style="font-size:11px;color:#1a6b2f;font-weight:600;"> {{ $it->dosagem }}</span>
                                        @endif
                                    </td>
                                    <td style="padding:10px 14px;border-bottom:1px solid #f3f4f6;color:#6b7280;">{{ $it->dose ?? '—' }}</td>
                                    <td style="padding:10px 14px;border-bottom:1px solid #f3f4f6;color:#6b7280;">{{ $it->frequencia ?? '—' }}</td>
                                    <td style="padding:10px 14px;border-bottom:1px solid #f3f4f6;color:#6b7280;">{{ $it->duracao ?? '—' }}</td>
                                    <td style="padding:10px 14px;border-bottom:1px solid #f3f4f6;text-align:center;font-weight:800;font-size:16px;color:#1a6b2f;">{{ $it->quantidade }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($prescricao->observacao)
                    <div style="padding:12px 16px;background:#fffbeb;border-top:1px solid #fde68a;font-size:13px;color:#92400e;">
                        <strong>Obs:</strong> {{ $prescricao->observacao }}
                    </div>
                    @endif
                </div>
                @endif

                {{-- Formulário nova / actualizar prescrição --}}
                <div class="f-card">
                    <div class="f-card-head">
                        <i class="feather icon-edit"></i>
                        <span>{{ $prescricao ? 'Actualizar Prescrição' : 'Nova Prescrição Médica' }}</span>
                    </div>
                    <div class="f-card-body">
                        <form action="{{ route('consultas.prescricao.store', $episodio->episodio_id) }}" method="POST" id="form-prescricao">
                            @csrf

                            <div class="fg" style="margin-bottom:14px;">
                                <label>Diagnóstico (para a prescrição)</label>
                                <input type="text" name="diagnostico" class="fc"
                                    value="{{ old('diagnostico', optional($prescricao)->diagnostico ?? optional($consulta)->diagnostico) }}"
                                    placeholder="Diagnóstico associado...">
                            </div>

                            {{-- Itens --}}
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                                <label class="fg" style="margin:0;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#374151;">Medicamentos <span style="color:#ef4444;">*</span></label>
                                <button type="button" class="add-med-btn" onclick="addPrescMed()"
                                    style="width:auto;padding:7px 16px;font-size:12px;">
                                    <i class="feather icon-plus-circle"></i> Adicionar
                                </button>
                            </div>

                            <div id="lista-presc-med" style="margin-bottom:14px;"></div>

                            <div class="fg" style="margin-bottom:0;">
                                <label>Observações / Instruções Gerais</label>
                                <textarea name="observacao" class="fc" rows="2"
                                    placeholder="Informações adicionais para o paciente ou farmácia...">{{ old('observacao', optional($prescricao)->observacao) }}</textarea>
                            </div>

                            <div style="margin-top:16px;display:flex;gap:10px;flex-wrap:wrap;">
                                <button type="submit" class="btn-g btn-primary-g" onclick="return validarPrescricao()">
                                    <i class="feather icon-save"></i>
                                    {{ $prescricao ? 'Actualizar Prescrição' : 'Guardar Prescrição' }}
                                </button>
                                @if($prescricao)
                                <a href="{{ route('consultas.prescricao.pdf', $episodio->episodio_id) }}" target="_blank"
                                   class="btn-g" style="background:#ede9fe;color:#5b21b6;">
                                    <i class="feather icon-eye"></i> Ver em PDF
                                </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>{{-- /coluna principal --}}

        {{-- COLUNA LATERAL --}}
        <div>

            {{-- Dados vitais da triagem --}}
            @if ($triagem)
                <div class="f-card">
                    <div class="f-card-head">
                        <i class="feather icon-heart"></i>
                        <span>Sinais Vitais</span>
                    </div>
                    <div class="f-card-body" style="padding:16px 20px;">
                        @php $temFebre = $triagem->temperatura && $triagem->temperatura > 37.5; @endphp
                        @if ($temFebre)
                            <div
                                style="background:#fee2e2;border-radius:10px;padding:10px 14px;margin-bottom:12px;display:flex;align-items:center;gap:8px;font-size:13px;font-weight:700;color:#991b1b;">
                                <span style="font-size:18px;">🌡️</span> FEBRE — {{ $triagem->temperatura }}°C
                            </div>
                        @endif
                        @if ($triagem->pressao_arterial)
                            <div class="vt-row"><span class="vt-lbl2">🩺 Pressão Arterial</span><span
                                    class="vt-val2">{{ $triagem->pressao_arterial }}</span></div>
                        @endif
                        @if ($triagem->temperatura)
                            <div class="vt-row"><span class="vt-lbl2">🌡️ Temperatura</span><span
                                    class="vt-val2 {{ $temFebre ? 'danger' : '' }}">{{ $triagem->temperatura }}°C</span>
                            </div>
                        @endif
                        @if ($triagem->peso)
                            <div class="vt-row"><span class="vt-lbl2">⚖️ Peso</span><span
                                    class="vt-val2">{{ $triagem->peso }} kg</span></div>
                        @endif
                        @if ($triagem->altura)
                            <div class="vt-row"><span class="vt-lbl2">📏 Altura</span><span
                                    class="vt-val2">{{ $triagem->altura }} cm</span></div>
                        @endif
                        @if ($triagem->peso && $triagem->altura)
                            @php $imc = round($triagem->peso / pow($triagem->altura/100,2),1); @endphp
                            <div class="vt-row"><span class="vt-lbl2">📊 IMC</span><span
                                    class="vt-val2 {{ $imc >= 25 ? 'warn' : '' }}">{{ $imc }}</span></div>
                        @endif
                        @if ($triagem->frequencia_cardiaca)
                            @php $fcWarn = $triagem->frequencia_cardiaca < 60 || $triagem->frequencia_cardiaca > 100; @endphp
                            <div class="vt-row"><span class="vt-lbl2">❤️ Freq. Cardíaca</span><span
                                    class="vt-val2 {{ $fcWarn ? 'warn' : '' }}">{{ $triagem->frequencia_cardiaca }}
                                    bpm</span></div>
                        @endif
                        @if ($triagem->saturacao_oxigenio)
                            @php $satBad = $triagem->saturacao_oxigenio < 95; @endphp
                            <div class="vt-row"><span class="vt-lbl2">💨 Saturação O₂</span><span
                                    class="vt-val2 {{ $satBad ? 'danger' : '' }}">{{ $triagem->saturacao_oxigenio }}%</span>
                            </div>
                        @endif
                        @if ($triagem->observacao)
                            <div class="obs-box"><strong>Queixas:</strong> {{ $triagem->observacao }}</div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Acções rápidas --}}
            <div class="f-card">
                <div class="f-card-head">
                    <i class="feather icon-zap"></i>
                    <span>Acções Rápidas</span>
                </div>
                <div class="f-card-body" style="padding:14px;display:flex;flex-direction:column;gap:8px;">
                    <button onclick="showTab('exames', document.querySelectorAll('.cs-tab')[1])"
                        style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:#f0faf2;border:none;border-radius:10px;color:#1a6b2f;font-size:13px;font-weight:600;cursor:pointer;transition:background .2s;font-family:'Inter',sans-serif;text-align:left;"
                        onmouseover="this.style.background='#d1fae5'" onmouseout="this.style.background='#f0faf2'">
                        <i class="feather icon-activity"></i> Pedir Exame
                    </button>
                    <button onclick="showTab('receita', document.querySelectorAll('.cs-tab')[2])"
                        style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:#f0faf2;border:none;border-radius:10px;color:#1a6b2f;font-size:13px;font-weight:600;cursor:pointer;transition:background .2s;font-family:'Inter',sans-serif;text-align:left;"
                        onmouseover="this.style.background='#d1fae5'" onmouseout="this.style.background='#f0faf2'">
                        <i class="feather icon-file-text"></i> Criar Receita
                    </button>
                    <button onclick="showTab('prescricao', document.querySelectorAll('.cs-tab')[3])"
                        style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:#f5f3ff;border:none;border-radius:10px;color:#5b21b6;font-size:13px;font-weight:600;cursor:pointer;transition:background .2s;font-family:'Inter',sans-serif;text-align:left;"
                        onmouseover="this.style.background='#ede9fe'" onmouseout="this.style.background='#f5f3ff'">
                        <i class="feather icon-edit"></i> Prescrição Médica
                    </button>
                    @if($receita)
                    <a href="{{ route('consultas.receita.pdf', $episodio->episodio_id) }}" target="_blank"
                        style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:#ede9fe;border:none;border-radius:10px;color:#5b21b6;font-size:13px;font-weight:600;text-decoration:none;transition:background .2s;"
                        onmouseover="this.style.background='#ddd6fe'" onmouseout="this.style.background='#ede9fe'">
                        <i class="feather icon-eye"></i> Ver Receita em PDF
                    </a>
                    @endif
                    @if($pedidos->isNotEmpty())
                    <a href="{{ route('consultas.pedido-exame.pdf', $episodio->episodio_id) }}" target="_blank"
                        style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:#dbeafe;border:none;border-radius:10px;color:#1d4ed8;font-size:13px;font-weight:600;text-decoration:none;transition:background .2s;"
                        onmouseover="this.style.background='#bfdbfe'" onmouseout="this.style.background='#dbeafe'">
                        <i class="feather icon-eye"></i> Ver Pedido de Exame em PDF
                    </a>
                    @endif
                    {{-- Re-chamar paciente — só se em_espera ou em_consulta --}}
                    @if(in_array($episodio->ep_estado, ['em_espera', 'em_consulta']))
                    <button onclick="rechamarPaciente()"
                        style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:#fef3c7;border:none;border-radius:10px;color:#92400e;font-size:13px;font-weight:600;cursor:pointer;transition:background .2s;font-family:'Inter',sans-serif;"
                        onmouseover="this.style.background='#fde68a'" onmouseout="this.style.background='#fef3c7'">
                        <i class="feather icon-volume-2"></i> Re-chamar Paciente
                    </button>
                    @else
                    <div style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:#f3f4f6;border-radius:10px;color:#9ca3af;font-size:13px;font-weight:600;">
                        <i class="feather icon-volume-x"></i>
                        @if($episodio->ep_estado === 'aguarda_exame')
                            A aguardar exame
                        @else
                            Consulta concluída
                        @endif
                    </div>
                    @endif
                    @if ($episodio->ep_estado !== 'concluido')
                        <a href="{{ route('consultas.concluir', $episodio->episodio_id) }}"
                            onclick="return confirm('Concluir episódio sem receita?')"
                            style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;color:#374151;font-size:13px;font-weight:600;text-decoration:none;transition:all .2s;"
                            onmouseover="this.style.borderColor='#1a6b2f';this.style.color='#1a6b2f'"
                            onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#374151'">
                            <i class="feather icon-check-square"></i> Concluir sem Receita
                        </a>
                    @endif
                </div>
            </div>

        </div>{{-- /coluna lateral --}}

    </div>{{-- /cs-wrap --}}

    {{-- JS: Tabs + Medicamentos --}}
    <script>
        // ── Tabs ─────────────────────────────────────────────────────────────────────
        function showTab(name, btn) {
            document.querySelectorAll('.cs-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.cs-tab').forEach(b => b.classList.remove('active'));
            document.getElementById('panel-' + name).classList.add('active');
            btn.classList.add('active');
        }

        // ── Adicionar medicamento ─────────────────────────────────────────────────────
        const produtos = {!! json_encode(
            $produtos->map(fn($p) => [
                'id'        => $p->id,
                'nome'      => $p->produto . ($p->apresentacao ? ' — ' . $p->apresentacao : ''),
                'bloqueado' => (bool)$p->bloqueado,
                'motivo'    => $p->motivo_bloqueio ?? '',
                'stock'     => (int)$p->quantidade,
                'minimo'    => (int)$p->stokminimo,
            ])
        ) !!};

        let medIdx = 0;

        function addMed(prefill) {
            medIdx++;
            const idx = medIdx;
            const div = document.createElement('div');
            div.className = 'med-item';
            div.id = 'med-' + idx;

            // Agrupa por disponíveis / bloqueados / stock baixo
            let optsDisp = '<optgroup label="— Disponíveis —">';
            let optsLow  = '<optgroup label="⚠️ Stock Baixo (atenção)">';
            let optsBloc = '<optgroup label="⛔ Bloqueados (não permitidos)">';

            produtos.forEach(p => {
                const sel = prefill && prefill.produto_id == p.id ? 'selected' : '';
                const stockInfo = p.bloqueado ? '' : ` [stock: ${p.stock}]`;
                if (p.bloqueado) {
                    optsBloc += `<option value="${p.id}" ${sel} data-bloqueado="1" data-motivo="${p.motivo}" style="color:#dc2626;">${p.nome} [BLOQUEADO]</option>`;
                } else if (p.stock <= p.minimo) {
                    optsLow += `<option value="${p.id}" ${sel} data-stock="${p.stock}" data-minimo="${p.minimo}" style="color:#92400e;">${p.nome}${stockInfo}</option>`;
                } else {
                    optsDisp += `<option value="${p.id}" ${sel}>${p.nome}</option>`;
                }
            });
            optsDisp += '</optgroup>';
            optsLow  += '</optgroup>';
            optsBloc += '</optgroup>';

            div.innerHTML = `
        <button type="button" class="med-del" onclick="document.getElementById('med-${idx}').remove()" title="Remover">
            <i class="feather icon-x"></i>
        </button>
        <div style="margin-bottom:10px;">
            <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#374151;display:block;margin-bottom:6px;">💊 Medicamento <span style="color:#ef4444;">*</span></label>
            <select name="produto_id[]" class="fc sel-med" required style="padding-right:30px;"
                onchange="verificarMed(this)">${optsDisp}${optsLow}${optsBloc}</select>
            <div class="med-aviso" style="display:none;margin-top:5px;padding:6px 10px;border-radius:8px;font-size:12px;font-weight:600;"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
            <div>
                <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#374151;display:block;margin-bottom:5px;">Dose</label>
                <input type="text" name="dose[]" class="fc" placeholder="Ex: 500mg">
            </div>
            <div>
                <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#374151;display:block;margin-bottom:5px;">Frequência</label>
                <input type="text" name="frequencia[]" class="fc" placeholder="Ex: 3x ao dia">
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <div>
                <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#374151;display:block;margin-bottom:5px;">Duração</label>
                <input type="text" name="duracao[]" class="fc" placeholder="Ex: 7 dias">
            </div>
            <div>
                <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#374151;display:block;margin-bottom:5px;">Quantidade <span style="color:#ef4444;">*</span></label>
                <input type="number" name="quantidade[]" class="fc" min="1" value="1" required style="max-width:120px;">
            </div>
        </div>`;

            document.getElementById('lista-med').appendChild(div);

            if (prefill) {
                div.querySelector('select').value = prefill.produto_id;
                div.querySelectorAll('input')[0].value = prefill.dose || '';
                div.querySelectorAll('input')[1].value = prefill.frequencia || '';
                div.querySelectorAll('input')[2].value = prefill.duracao || '';
                div.querySelectorAll('input')[3].value = prefill.quantidade || 1;
                verificarMed(div.querySelector('select'));
            }
        }

        function verificarMed(sel) {
            const aviso = sel.closest('.med-item').querySelector('.med-aviso');
            const opt   = sel.options[sel.selectedIndex];
            if (!opt || !opt.value) { aviso.style.display = 'none'; return; }

            if (opt.dataset.bloqueado === '1') {
                const motivo = opt.dataset.motivo ? ` Motivo: ${opt.dataset.motivo}.` : '';
                aviso.style.display = 'block';
                aviso.style.background = '#fee2e2';
                aviso.style.color = '#991b1b';
                aviso.innerHTML = `⛔ Este fármaco está <strong>BLOQUEADO</strong>.${motivo} Não pode ser prescrito.`;
                sel.style.borderColor = '#dc2626';
            } else if (opt.dataset.stock !== undefined) {
                aviso.style.display = 'block';
                aviso.style.background = '#fef3c7';
                aviso.style.color = '#92400e';
                aviso.innerHTML = `⚠️ Stock baixo: <strong>${opt.dataset.stock} unidades</strong> (mínimo: ${opt.dataset.minimo}). A receita pode não ser dispensada.`;
                sel.style.borderColor = '#f59e0b';
            } else {
                aviso.style.display = 'none';
                sel.style.borderColor = '';
            }
        }

        function validarReceita() {
            const rows = document.querySelectorAll('.med-item');
            if (!rows.length) {
                alert('Adicione pelo menos um medicamento à receita.');
                return false;
            }
            // Bloquear submit se houver fármacos bloqueados seleccionados
            let temBloqueado = false;
            rows.forEach(r => {
                const opt = r.querySelector('select').options[r.querySelector('select').selectedIndex];
                if (opt && opt.dataset.bloqueado === '1') temBloqueado = true;
            });
            if (temBloqueado) {
                alert('A receita contém fármacos bloqueados. Remova-os antes de continuar.');
                return false;
            }
            return true;
        }

        // Pré-preenche receita existente para edição
        @if ($receita && $receita->itens->isNotEmpty() && $receita->estado !== 'dispensada')
            @foreach ($receita->itens as $item)
                addMed({
                    produto_id: {{ $item->produto_id }},
                    dose: "{{ addslashes($item->dose ?? '') }}",
                    frequencia: "{{ addslashes($item->frequencia ?? '') }}",
                    duracao: "{{ addslashes($item->duracao ?? '') }}",
                    quantidade: {{ $item->quantidade }},
                });
            @endforeach
        @endif

        // Abrir tab de exames se há resultado novo
        @if ($pedidos->where('estado', 'concluido')->where('resultado_id', '!=', null)->count() > 0)
            // Destaca tab de exames
            document.querySelectorAll('.cs-tab')[1].style.animation = 'pulse-tab .8s 3';
        @endif

        function confirmarConcluir() {
            if (confirm('Concluir episódio sem criar receita?\n\nO paciente não receberá medicação pela farmácia.')) {
                window.location.href = '{{ route('consultas.concluir', $episodio->episodio_id) }}';
            }
        }

        // ── Prescrição ────────────────────────────────────────────────────────────
        let prescIdx = 0;

        const formasFarm = [
            'Comprimido','Cápsula','Xarope','Solução oral','Injectável','Supositório',
            'Creme','Pomada','Colírio','Spray nasal','Inalação','Ampola','Saqueta','Suspensão'
        ];

        function addPrescMed(prefill) {
            prescIdx++;
            const idx = prescIdx;
            const div = document.createElement('div');
            div.className = 'med-item';
            div.id = 'presc-' + idx;
            div.style.position = 'relative';

            const formasOpts = formasFarm.map(f =>
                `<option value="${f}" ${prefill && prefill.forma === f ? 'selected' : ''}>${f}</option>`
            ).join('');

            div.innerHTML = `
            <button type="button" class="med-del" onclick="document.getElementById('presc-${idx}').remove()" title="Remover">
                <i class="feather icon-x"></i>
            </button>
            <div style="display:grid;grid-template-columns:2fr 1fr 1fr;gap:10px;margin-bottom:10px;">
                <div>
                    <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#374151;display:block;margin-bottom:5px;">💊 Medicamento <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="medicamentos[${idx}][medicamento]" class="fc" required
                        placeholder="Ex: Amoxicilina, Paracetamol..." value="${prefill ? (prefill.medicamento||'') : ''}">
                </div>
                <div>
                    <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#374151;display:block;margin-bottom:5px;">Forma</label>
                    <select name="medicamentos[${idx}][forma_farmaceutica]" class="fc">
                        <option value="">— Seleccione —</option>
                        ${formasOpts}
                    </select>
                </div>
                <div>
                    <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#374151;display:block;margin-bottom:5px;">Dosagem</label>
                    <input type="text" name="medicamentos[${idx}][dosagem]" class="fc"
                        placeholder="Ex: 500mg, 250mg/5ml" value="${prefill ? (prefill.dosagem||'') : ''}">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr 80px;gap:10px;">
                <div>
                    <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#374151;display:block;margin-bottom:5px;">Dose</label>
                    <input type="text" name="medicamentos[${idx}][dose]" class="fc"
                        placeholder="Ex: 1 comprimido" value="${prefill ? (prefill.dose||'') : ''}">
                </div>
                <div>
                    <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#374151;display:block;margin-bottom:5px;">Frequência</label>
                    <input type="text" name="medicamentos[${idx}][frequencia]" class="fc"
                        placeholder="Ex: 3x ao dia" value="${prefill ? (prefill.frequencia||'') : ''}">
                </div>
                <div>
                    <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#374151;display:block;margin-bottom:5px;">Duração</label>
                    <input type="text" name="medicamentos[${idx}][duracao]" class="fc"
                        placeholder="Ex: 7 dias" value="${prefill ? (prefill.duracao||'') : ''}">
                </div>
                <div>
                    <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#374151;display:block;margin-bottom:5px;">Qtd <span style="color:#ef4444;">*</span></label>
                    <input type="number" name="medicamentos[${idx}][quantidade]" class="fc"
                        min="1" value="${prefill ? (prefill.quantidade||1) : 1}" required>
                </div>
            </div>
            <div style="margin-top:10px;">
                <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#374151;display:block;margin-bottom:5px;">Instruções Especiais</label>
                <input type="text" name="medicamentos[${idx}][instrucoes]" class="fc"
                    placeholder="Ex: Tomar após as refeições, evitar álcool..." value="${prefill ? (prefill.instrucoes||'') : ''}">
            </div>`;

            document.getElementById('lista-presc-med').appendChild(div);
        }

        function validarPrescricao() {
            const rows = document.querySelectorAll('#lista-presc-med .med-item');
            if (!rows.length) {
                alert('Adicione pelo menos um medicamento à prescrição.');
                return false;
            }
            return true;
        }

        // Pré-preenche prescrição existente para edição
        @if($prescricao && $prescricao->itens->isNotEmpty())
            @foreach($prescricao->itens as $pit)
                addPrescMed({
                    medicamento:       "{{ addslashes($pit->medicamento) }}",
                    forma:             "{{ addslashes($pit->forma_farmaceutica ?? '') }}",
                    dosagem:           "{{ addslashes($pit->dosagem ?? '') }}",
                    dose:              "{{ addslashes($pit->dose ?? '') }}",
                    frequencia:        "{{ addslashes($pit->frequencia ?? '') }}",
                    duracao:           "{{ addslashes($pit->duracao ?? '') }}",
                    quantidade:        {{ $pit->quantidade }},
                    instrucoes:        "{{ addslashes($pit->instrucoes ?? '') }}",
                });
            @endforeach
        @else
            // Começa com uma linha em branco
            addPrescMed();
        @endif

        function rechamarPaciente() {
            @if(in_array($episodio->ep_estado, ['aguarda_exame', 'concluido']))
            alert('Este paciente está a aguardar exame ou a consulta já foi concluída.\nNão é possível chamar novamente.');
            return;
            @endif
            fetch('{{ route("chamadas.rechamar", $episodio->episodio_id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    const btn = event.currentTarget;
                    const orig = btn.innerHTML;
                    btn.innerHTML = '<i class="feather icon-check"></i> Chamado!';
                    btn.style.background = '#d1fae5';
                    btn.style.color = '#065f46';
                    setTimeout(() => {
                        btn.innerHTML = orig;
                        btn.style.background = '';
                        btn.style.color = '';
                    }, 2500);
                } else {
                    alert(d.motivo || 'Não foi possível chamar o paciente.');
                }
            })
            .catch(() => alert('Erro de comunicação.'));
        }
    </script>

@endsection
