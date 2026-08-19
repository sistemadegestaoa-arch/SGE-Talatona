@extends('louyout.app')
@section('conteodo')
    <style>
        /* ── Layout ── */
        .tr-wrap {
            max-width: 900px;
            margin: 0 auto;
        }

        .tr-header {
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

        /* ── Steps ── */
        .steps {
            display: flex;
            align-items: center;
            gap: 0;
            margin-bottom: 28px;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .step-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
            flex-shrink: 0;
            transition: all .3s;
        }

        .step-circle.done {
            background: #1a6b2f;
            color: #fff;
        }

        .step-circle.active {
            background: #1a6b2f;
            color: #fff;
            box-shadow: 0 0 0 4px rgba(26, 107, 47, .2);
        }

        .step-circle.pending {
            background: #f3f4f6;
            color: #9ca3af;
        }

        .step-label {
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            white-space: nowrap;
        }

        .step-label.pending {
            color: #9ca3af;
        }

        .step-line {
            flex: 1;
            height: 2px;
            background: #e5e7eb;
            margin: 0 8px;
        }

        .step-line.done {
            background: #1a6b2f;
        }

        /* ── Card ── */
        .tr-card {
            background: #fff;
            border-radius: 18px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .06);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .tr-card-head {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 18px 24px;
            border-bottom: 2px solid #f0faf2;
        }

        .tr-card-head .icon-wrap {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #f0faf2;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #1a6b2f;
            flex-shrink: 0;
        }

        .tr-card-head h3 {
            font-size: 15px;
            font-weight: 700;
            color: #1a2e1a;
            margin: 0;
        }

        .tr-card-head p {
            font-size: 12px;
            color: #6b7280;
            margin: 2px 0 0;
        }

        .tr-card-body {
            padding: 24px;
        }

        /* ── Campos ── */
        .fg {
            margin-bottom: 16px;
        }

        .fg:last-child {
            margin-bottom: 0;
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

        .fc.fc-error {
            border-color: #ef4444;
            background: #fff8f8;
        }

        .row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .row-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
        }

        /* ── Pesquisa ── */
        .search-wrap {
            position: relative;
        }

        .search-wrap .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 16px;
            pointer-events: none;
        }

        .search-wrap .fc {
            padding-left: 38px;
        }

        .search-dropdown {
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            z-index: 200;
            background: #fff;
            border: 1.5px solid #1a6b2f;
            border-radius: 12px;
            max-height: 240px;
            overflow-y: auto;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
            display: none;
        }

        .sri {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            transition: background .15s;
        }

        .sri:last-child {
            border-bottom: none;
        }

        .sri:hover {
            background: #f0faf2;
        }

        .sri-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .sri-m {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            color: #fff;
        }

        .sri-f {
            background: linear-gradient(135deg, #9d174d, #ec4899);
            color: #fff;
        }

        .sri-info {
            flex: 1;
        }

        .sri-nome {
            font-size: 13px;
            font-weight: 600;
            color: #1a2e1a;
        }

        .sri-meta {
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
        }

        .sri-empty {
            padding: 16px;
            text-align: center;
            color: #9ca3af;
            font-size: 13px;
        }

        /* Paciente seleccionado */
        .pac-sel {
            display: none;
            align-items: center;
            gap: 12px;
            background: #f0faf2;
            border: 1px solid #d1fae5;
            border-radius: 12px;
            padding: 12px 16px;
            margin-top: 8px;
        }

        .pac-sel.show {
            display: flex;
        }

        .pac-sel-info strong {
            font-size: 13px;
            color: #1a2e1a;
        }

        .pac-sel-info span {
            font-size: 12px;
            color: #6b7280;
            display: block;
            margin-top: 2px;
        }

        .pac-sel-clear {
            margin-left: auto;
            background: none;
            border: none;
            color: #ef4444;
            cursor: pointer;
            font-size: 20px;
            line-height: 1;
            padding: 0 4px;
        }

        /* ── Vitais ── */
        .vital-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 12px;
        }

        .vital-input-wrap {
            position: relative;
        }

        .vital-input-wrap .vi-unit {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 11px;
            color: #9ca3af;
            font-weight: 600;
            pointer-events: none;
        }

        .vital-input-wrap .fc {
            padding-right: 42px;
        }

        .vital-card {
            background: #f9fafb;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            padding: 14px;
            transition: border-color .2s, box-shadow .2s;
        }

        .vital-card:focus-within {
            border-color: #1a6b2f;
            box-shadow: 0 0 0 3px rgba(26, 107, 47, .08);
            background: #fff;
        }

        .vital-card label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #6b7280;
            display: block;
            margin-bottom: 6px;
        }

        .vital-card .vi-val {
            font-size: 22px;
            font-weight: 800;
            color: #1a2e1a;
            line-height: 1;
            margin: 4px 0 2px;
        }

        .vital-card .vi-unit-big {
            font-size: 11px;
            color: #9ca3af;
        }

        .vital-card input {
            background: none;
            border: none;
            outline: none;
            width: 100%;
            font-size: 22px;
            font-weight: 800;
            color: #1a2e1a;
            font-family: 'Inter', sans-serif;
            padding: 0;
        }

        .vital-card input::placeholder {
            color: #d1d5db;
        }

        /* IMC badge */
        .imc-display {
            display: none;
            background: #f0faf2;
            border: 1px solid #d1fae5;
            border-radius: 10px;
            padding: 10px 16px;
            margin-top: 10px;
            font-size: 13px;
            color: #1a2e1a;
        }

        .imc-display.show {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* ── Submit ── */
        .btn-submit-tr {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #1a6b2f, #2d9e4a);
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: opacity .2s, transform .1s;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 4px 14px rgba(26, 107, 47, .3);
        }

        .btn-submit-tr:hover {
            opacity: .92;
            transform: translateY(-1px);
        }

        .btn-back-tr {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            color: #374151;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s;
        }

        .btn-back-tr:hover {
            border-color: #1a6b2f;
            color: #1a6b2f;
            text-decoration: none;
        }

        /* Urgência toggle */
        .urgencia-toggle {
            display: flex;
            gap: 10px;
        }

        .urg-btn {
            flex: 1;
            padding: 10px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            background: #fff;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            transition: all .2s;
            color: #374151;
            font-family: 'Inter', sans-serif;
        }

        .urg-btn.active-normal {
            border-color: #1a6b2f;
            background: #f0faf2;
            color: #1a6b2f;
        }

        .urg-btn.active-urgente {
            border-color: #dc2626;
            background: #fef2f2;
            color: #dc2626;
        }

        /* ── Separador OR ── */
        .or-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 16px 0;
        }

        .or-divider::before,
        .or-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        .or-divider span {
            font-size: 12px;
            color: #9ca3af;
            font-weight: 600;
            white-space: nowrap;
        }

        @media(max-width:700px) {

            .row-2,
            .row-3 {
                grid-template-columns: 1fr;
            }

            .vital-grid {
                grid-template-columns: 1fr 1fr;
            }

            .steps {
                display: none;
            }
        }
    </style>

    <form action="{{ route('triagem.store') }}" method="POST" id="triagem-form" autocomplete="off">
        @csrf
        <input type="hidden" name="paciente_id" id="fld-pac-id">

        <div class="tr-wrap">

            {{-- HEADER --}}
            <div class="tr-header">
                <div>
                    <h1 class="tr-title">Nova Triagem</h1>
                    <p class="tr-sub">{{ \Carbon\Carbon::today()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
                </div>
                <a href="{{ route('triagem.index') }}" class="btn-back-tr">
                    <i class="feather icon-arrow-left"></i> Voltar
                </a>
            </div>

            {{-- STEPS --}}
            <div class="steps">
                <div class="step">
                    <div class="step-circle active" id="step1-circle">1</div>
                    <span class="step-label" id="step1-label">Paciente</span>
                </div>
                <div class="step-line" id="line1"></div>
                <div class="step">
                    <div class="step-circle pending" id="step2-circle">2</div>
                    <span class="step-label pending" id="step2-label">Dados Vitais</span>
                </div>
                <div class="step-line" id="line2"></div>
                <div class="step">
                    <div class="step-circle pending" id="step3-circle">3</div>
                    <span class="step-label pending" id="step3-label">Confirmar</span>
                </div>
            </div>

            @include('louyout.flash')

            {{-- STEP 1: PACIENTE --}}
            <div id="panel-step1">

                {{-- Pesquisa --}}
                <div class="tr-card">
                    <div class="tr-card-head">
                        <div class="icon-wrap"><i class="feather icon-search"></i></div>
                        <div>
                            <h3>Pesquisar Paciente Existente</h3>
                            <p>Digite o nome ou nº de processo</p>
                        </div>
                    </div>
                    <div class="tr-card-body">
                        <div class="search-wrap">
                            <i class="feather icon-search search-icon"></i>
                            <input type="text" id="inp-pesquisa" class="fc"
                                placeholder="Ex: João Silva ou P-2024-001..." autocomplete="off">
                            <div class="search-dropdown" id="search-dropdown"></div>
                        </div>
                        <div class="pac-sel" id="pac-sel">
                            <div class="sri-avatar" id="pac-sel-avatar"></div>
                            <div class="pac-sel-info">
                                <strong id="pac-sel-nome"></strong>
                                <span id="pac-sel-meta"></span>
                            </div>
                            <button type="button" class="pac-sel-clear" onclick="limparPaciente()"
                                title="Remover">×</button>
                        </div>
                    </div>
                </div>

                <div class="or-divider"><span>OU REGISTE UM NOVO PACIENTE</span></div>

                {{-- Dados do paciente --}}
                <div class="tr-card">
                    <div class="tr-card-head">
                        <div class="icon-wrap"><i class="feather icon-user-plus"></i></div>
                        <div>
                            <h3>Dados do Paciente</h3>
                            <p>Preencha se for um paciente novo</p>
                        </div>
                    </div>
                    <div class="tr-card-body">
                        <div class="row-2" style="margin-bottom:16px;">
                            <div class="fg" style="margin:0;grid-column:1/-1;">
                                <label>Nome Completo <span class="req">*</span></label>
                                <input type="text" name="nome" id="fld-nome" class="fc"
                                    value="{{ old('nome') }}" placeholder="Nome do paciente" required>
                            </div>
                        </div>
                        <div class="row-2">
                            <div class="fg" style="margin:0;">
                                <label>Sexo <span class="req">*</span></label>
                                <select name="sexo" id="fld-sexo" class="fc" required>
                                    <option value="">— Seleccione —</option>
                                    <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                    <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Feminino</option>
                                </select>
                            </div>
                            <div class="fg" style="margin:0;">
                                <label>Data de Nascimento</label>
                                <input type="date" name="data_nascimento" id="fld-nasc" class="fc"
                                    value="{{ old('data_nascimento') }}" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="row-2" style="margin-top:16px;">
                            <div class="fg" style="margin:0;">
                                <label>Nº de Processo</label>
                                <input type="text" name="numero_processo" id="fld-proc" class="fc"
                                    value="{{ old('numero_processo') }}" placeholder="Opcional">
                            </div>
                            <div class="fg" style="margin:0;">
                                <label>Telefone</label>
                                <input type="text" name="telefone" id="fld-tel" class="fc"
                                    value="{{ old('telefone') }}" placeholder="Ex: 923 000 000">
                            </div>
                        </div>
                        <div class="fg" style="margin-top:16px;">
                            <label>Morada</label>
                            <input type="text" name="morada" id="fld-morada" class="fc"
                                value="{{ old('morada') }}" placeholder="Opcional">
                        </div>
                    </div>
                </div>

                <button type="button" onclick="irParaStep2()"
                    style="width:100%;padding:13px;background:linear-gradient(135deg,#1a6b2f,#2d9e4a);color:#fff;border:none;border-radius:12px;font-size:14px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;font-family:'Inter',sans-serif;box-shadow:0 4px 14px rgba(26,107,47,.25);">
                    Continuar para Dados Vitais <i class="feather icon-arrow-right"></i>
                </button>
            </div>

            {{-- STEP 2: DADOS VITAIS --}}
            <div id="panel-step2" style="display:none;">
                <div class="tr-card">
                    <div class="tr-card-head">
                        <div class="icon-wrap"><i class="feather icon-heart"></i></div>
                        <div>
                            <h3>Dados Vitais</h3>
                            <p>Registe os parâmetros medidos na triagem</p>
                        </div>
                    </div>
                    <div class="tr-card-body">

                        {{-- Pressão e Temperatura lado a lado --}}
                        <div class="row-2" style="margin-bottom:16px;">
                            <div class="vital-card">
                                <label>🩺 Pressão Arterial</label>
                                <input type="text" name="pressao_arterial" placeholder="120/80" class="fc"
                                    style="font-size:20px;font-weight:800;border:none;background:none;padding:4px 0;outline:none;"
                                    value="{{ old('pressao_arterial') }}">
                                <span class="vi-unit-big">mmHg</span>
                            </div>
                            <div class="vital-card" id="temp-card">
                                <label>🌡️ Temperatura</label>
                                <input type="number" name="temperatura" id="fld-temp" step="0.1" min="30"
                                    max="45" placeholder="37.0" class="fc"
                                    style="font-size:20px;font-weight:800;border:none;background:none;padding:4px 0;outline:none;"
                                    value="{{ old('temperatura') }}" oninput="checkTemp(this)">
                                <span class="vi-unit-big" id="temp-label">°C</span>
                            </div>
                        </div>

                        {{-- Grid de vitais --}}
                        <div class="vital-grid">
                            <div class="vital-card">
                                <label>⚖️ Peso</label>
                                <input type="number" name="peso" id="fld-peso" step="0.1" min="1"
                                    placeholder="70" value="{{ old('peso') }}" oninput="calcIMC()">
                                <span class="vi-unit-big">kg</span>
                            </div>
                            <div class="vital-card">
                                <label>📏 Altura</label>
                                <input type="number" name="altura" id="fld-altura" step="1" min="30"
                                    placeholder="170" value="{{ old('altura') }}" oninput="calcIMC()">
                                <span class="vi-unit-big">cm</span>
                            </div>
                            <div class="vital-card">
                                <label>❤️ Freq. Cardíaca</label>
                                <input type="number" name="frequencia_cardiaca" step="1" min="20"
                                    placeholder="72" value="{{ old('frequencia_cardiaca') }}">
                                <span class="vi-unit-big">bpm</span>
                            </div>
                            <div class="vital-card">
                                <label>🫁 Freq. Respiratória</label>
                                <input type="number" name="frequencia_respiratoria" step="1" min="5"
                                    placeholder="16" value="{{ old('frequencia_respiratoria') }}">
                                <span class="vi-unit-big">rpm</span>
                            </div>
                            <div class="vital-card">
                                <label>💨 Saturação O₂</label>
                                <input type="number" name="saturacao_oxigenio" step="1" min="1"
                                    max="100" placeholder="98" value="{{ old('saturacao_oxigenio') }}">
                                <span class="vi-unit-big">%</span>
                            </div>
                        </div>

                        {{-- IMC automático --}}
                        <div class="imc-display" id="imc-display">
                            <span style="font-size:28px;font-weight:900;" id="imc-val"></span>
                            <div>
                                <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:#6b7280;">IMC
                                </div>
                                <div id="imc-cat" style="font-size:13px;font-weight:600;"></div>
                            </div>
                        </div>

                        <div class="fg" style="margin-top:16px;">
                            <label>Queixas / Observações</label>
                            <textarea name="observacao" class="fc" rows="3"
                                placeholder="Descreva as queixas principais do paciente...">{{ old('observacao') }}</textarea>
                        </div>

                        {{-- Urgência --}}
                        <div class="fg" style="margin-top:16px;">
                            <label>Prioridade da Consulta</label>
                            <input type="hidden" name="urgente" id="fld-urgente" value="0">
                            <div class="urgencia-toggle">
                                <button type="button" id="btn-normal" class="urg-btn active-normal"
                                    onclick="setUrgencia(0)">
                                    ✅ Normal
                                </button>
                                <button type="button" id="btn-urgente" class="urg-btn"
                                    onclick="setUrgencia(1)">
                                    ⚡ Urgente
                                </button>
                            </div>
                            <p style="font-size:11px;color:#6b7280;margin-top:6px;">
                                Marque como urgente se o paciente necessita de atenção médica imediata.
                            </p>
                        </div>
                    </div>
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="button" onclick="irParaStep1()"
                        style="padding:13px 24px;border:2px solid #e5e7eb;border-radius:12px;background:#fff;font-size:14px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;">
                        <i class="feather icon-arrow-left"></i> Voltar
                    </button>
                    <button type="button" onclick="irParaStep3()"
                        style="flex:1;padding:13px;background:linear-gradient(135deg,#1a6b2f,#2d9e4a);color:#fff;border:none;border-radius:12px;font-size:14px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;font-family:'Inter',sans-serif;box-shadow:0 4px 14px rgba(26,107,47,.25);">
                        Rever e Confirmar <i class="feather icon-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- STEP 3: CONFIRMAR --}}
            <div id="panel-step3" style="display:none;">
                <div class="tr-card">
                    <div class="tr-card-head">
                        <div class="icon-wrap" style="background:#f0faf2;">✅</div>
                        <div>
                            <h3>Confirmar Triagem</h3>
                            <p>Verifique os dados antes de submeter</p>
                        </div>
                    </div>
                    <div class="tr-card-body">
                        <div id="resumo-paciente" style="margin-bottom:20px;"></div>
                        <div id="resumo-vitais"></div>
                    </div>
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="button" onclick="irParaStep2()"
                        style="padding:13px 24px;border:2px solid #e5e7eb;border-radius:12px;background:#fff;font-size:14px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;">
                        <i class="feather icon-arrow-left"></i> Corrigir
                    </button>
                    <button type="submit" class="btn-submit-tr" style="flex:1;">
                        <i class="feather icon-check-circle"></i> Confirmar e Registar Triagem
                    </button>
                </div>
            </div>

        </div>
    </form>

    <script>
        // ── Pesquisa de paciente ─────────────────────────────────────────────────────
        let searchTO;
        const inp = document.getElementById('inp-pesquisa');
        const drop = document.getElementById('search-dropdown');
        const pacSel = document.getElementById('pac-sel');

        inp.addEventListener('input', function() {
            clearTimeout(searchTO);
            const q = this.value.trim();
            if (q.length < 2) {
                drop.style.display = 'none';
                return;
            }
            searchTO = setTimeout(() => {
                fetch(`{{ route('triagem.pesquisar') }}?q=` + encodeURIComponent(q))
                    .then(r => r.json())
                    .then(data => renderResults(data));
            }, 280);
        });

        function renderResults(data) {
            drop.innerHTML = '';
            if (!data.length) {
                drop.innerHTML = '<div class="sri-empty">Nenhum paciente encontrado — registe como novo.</div>';
            } else {
                data.forEach(p => {
                    const div = document.createElement('div');
                    div.className = 'sri';
                    const ini = (p.nome || '?')[0].toUpperCase();
                    const cls = p.sexo === 'M' ? 'sri-m' : 'sri-f';
                    const idade = p.data_nascimento ? calcIdade(p.data_nascimento) + ' anos' : '';
                    div.innerHTML = `
                <div class="sri-avatar ${cls}">${ini}</div>
                <div class="sri-info">
                    <div class="sri-nome">${p.nome}</div>
                    <div class="sri-meta">${[p.numero_processo ? 'Proc: '+p.numero_processo : '', idade, p.sexo === 'M' ? 'Masculino' : 'Feminino'].filter(Boolean).join(' · ')}</div>
                </div>`;
                    div.addEventListener('click', () => selPaciente(p));
                    drop.appendChild(div);
                });
            }
            drop.style.display = 'block';
        }

        function selPaciente(p) {
            document.getElementById('fld-pac-id').value = p.id;
            document.getElementById('fld-nome').value = p.nome;
            document.getElementById('fld-sexo').value = p.sexo || '';
            document.getElementById('fld-nasc').value = p.data_nascimento || '';
            document.getElementById('fld-proc').value = p.numero_processo || '';
            document.getElementById('fld-tel').value = p.telefone || '';
            document.getElementById('fld-morada').value = p.morada || '';

            const ini = (p.nome || '?')[0].toUpperCase();
            const cls = p.sexo === 'M' ? 'sri-m' : 'sri-f';
            const meta = [p.numero_processo ? 'Proc: ' + p.numero_processo : '', p.data_nascimento ? calcIdade(p
                .data_nascimento) + ' anos' : ''].filter(Boolean).join(' · ');
            document.getElementById('pac-sel-avatar').className = `sri-avatar ${cls}`;
            document.getElementById('pac-sel-avatar').textContent = ini;
            document.getElementById('pac-sel-nome').textContent = p.nome;
            document.getElementById('pac-sel-meta').textContent = meta;

            pacSel.classList.add('show');
            drop.style.display = 'none';
            inp.value = '';
        }

        function limparPaciente() {
            document.getElementById('fld-pac-id').value = '';
            ['fld-nome', 'fld-sexo', 'fld-nasc', 'fld-proc', 'fld-tel', 'fld-morada']
            .forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
            pacSel.classList.remove('show');
        }

        document.addEventListener('click', e => {
            if (!e.target.closest('.search-wrap')) drop.style.display = 'none';
        });

        // ── IMC ──────────────────────────────────────────────────────────────────────
        function calcIMC() {
            const p = parseFloat(document.getElementById('fld-peso').value);
            const a = parseFloat(document.getElementById('fld-altura').value) / 100;
            const d = document.getElementById('imc-display');
            if (!p || !a || a <= 0) {
                d.classList.remove('show');
                return;
            }
            const imc = (p / (a * a)).toFixed(1);
            let cat = '',
                color = '#1a6b2f';
            if (imc < 18.5) {
                cat = 'Abaixo do peso';
                color = '#92400e';
            } else if (imc < 25) {
                cat = 'Peso normal';
                color = '#065f46';
            } else if (imc < 30) {
                cat = 'Sobrepeso';
                color = '#d97706';
            } else {
                cat = 'Obesidade';
                color = '#dc2626';
            }
            document.getElementById('imc-val').textContent = imc;
            document.getElementById('imc-val').style.color = color;
            document.getElementById('imc-cat').textContent = cat;
            document.getElementById('imc-cat').style.color = color;
            d.classList.add('show');
        }

        function checkTemp(inp) {
            const v = parseFloat(inp.value);
            const lbl = document.getElementById('temp-label');
            const card = document.getElementById('temp-card');
            if (v > 37.5) {
                lbl.textContent = '°C — FEBRE 🔴';
                lbl.style.color = '#dc2626';
                card.style.borderColor = '#fca5a5';
            } else {
                lbl.textContent = '°C';
                lbl.style.color = '#9ca3af';
                card.style.borderColor = '';
            }
        }

        // ── Steps ────────────────────────────────────────────────────────────────────
        function irParaStep1() {
            setStep(1);
        }

        function irParaStep2() {
            const nome = document.getElementById('fld-nome').value.trim();
            const sexo = document.getElementById('fld-sexo').value;
            if (!nome) {
                document.getElementById('fld-nome').classList.add('fc-error');
                document.getElementById('fld-nome').focus();
                return;
            }
            if (!sexo) {
                document.getElementById('fld-sexo').classList.add('fc-error');
                document.getElementById('fld-sexo').focus();
                return;
            }
            document.getElementById('fld-nome').classList.remove('fc-error');
            document.getElementById('fld-sexo').classList.remove('fc-error');
            setStep(2);
        }

        function irParaStep3() {
            setStep(3);
            renderResumo();
        }

        function setStep(n) {
            [1, 2, 3].forEach(i => {
                document.getElementById('panel-step' + i).style.display = i === n ? 'block' : 'none';
                const c = document.getElementById('step' + i + '-circle');
                const lb = document.getElementById('step' + i + '-label');
                if (i < n) {
                    c.className = 'step-circle done';
                    lb.className = 'step-label';
                } else if (i === n) {
                    c.className = 'step-circle active';
                    lb.className = 'step-label';
                } else {
                    c.className = 'step-circle pending';
                    lb.className = 'step-label pending';
                }
                if (i < 3) {
                    const ln = document.getElementById('line' + i);
                    ln.style.background = i < n ? '#1a6b2f' : '#e5e7eb';
                }
            });
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function renderResumo() {
            const nome = document.getElementById('fld-nome').value;
            const sexo = document.getElementById('fld-sexo').value;
            const nasc = document.getElementById('fld-nasc').value;
            const proc = document.getElementById('fld-proc').value;
            const tel = document.getElementById('fld-tel').value;
            const idade = nasc ? calcIdade(nasc) + ' anos' : '—';
            const ini = (nome || '?')[0].toUpperCase();
            const cls = sexo === 'M' ? 'sri-m' : 'sri-f';

            document.getElementById('resumo-paciente').innerHTML = `
        <div style="display:flex;align-items:center;gap:14px;padding:16px;background:#f0faf2;border-radius:12px;margin-bottom:4px;">
            <div class="sri-avatar ${cls}" style="width:48px;height:48px;font-size:18px;">${ini}</div>
            <div>
                <div style="font-size:16px;font-weight:700;color:#1a2e1a;">${nome}</div>
                <div style="font-size:12px;color:#6b7280;margin-top:4px;">${sexo === 'M' ? 'Masculino' : 'Feminino'} · ${idade}${proc ? ' · Proc: '+proc : ''}${tel ? ' · '+tel : ''}</div>
            </div>
        </div>`;

            const campos = [
                ['Pressão Arterial', document.querySelector('[name="pressao_arterial"]')?.value, 'mmHg'],
                ['Temperatura', document.getElementById('fld-temp')?.value, '°C'],
                ['Peso', document.getElementById('fld-peso')?.value, 'kg'],
                ['Altura', document.getElementById('fld-altura')?.value, 'cm'],
                ['Freq. Cardíaca', document.querySelector('[name="frequencia_cardiaca"]')?.value, 'bpm'],
                ['Sat. O₂', document.querySelector('[name="saturacao_oxigenio"]')?.value, '%'],
                ['Observações', document.querySelector('[name="observacao"]')?.value, ''],
            ].filter(c => c[1]);

            if (campos.length) {
                let html = '<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:10px;">';
                campos.forEach(([l, v, u]) => {
                    html += `<div style="background:#f9fafb;border-radius:10px;padding:12px;border:1px solid #e5e7eb;">
                <div style="font-size:11px;color:#6b7280;text-transform:uppercase;font-weight:600;margin-bottom:4px;">${l}</div>
                <div style="font-size:16px;font-weight:800;color:#1a2e1a;">${v}<span style="font-size:11px;color:#9ca3af;font-weight:400;"> ${u}</span></div>
            </div>`;
                });
                html += '</div>';
                document.getElementById('resumo-vitais').innerHTML = html;
            } else {
                document.getElementById('resumo-vitais').innerHTML =
                    '<p style="color:#9ca3af;font-size:13px;">Nenhum dado vital registado.</p>';
            }
        }

        function calcIdade(dob) {
            const d = new Date(dob),
                h = new Date();
            let a = h.getFullYear() - d.getFullYear();
            if (h.getMonth() < d.getMonth() || (h.getMonth() === d.getMonth() && h.getDate() < d.getDate())) a--;
            return a;
        }

        // ── Urgência ─────────────────────────────────────────────────────────────────
        function setUrgencia(val) {
            document.getElementById('fld-urgente').value = val;
            const btnN = document.getElementById('btn-normal');
            const btnU = document.getElementById('btn-urgente');
            if (val === 1) {
                btnN.className = 'urg-btn';
                btnU.className = 'urg-btn active-urgente';
            } else {
                btnN.className = 'urg-btn active-normal';
                btnU.className = 'urg-btn';
            }
        }

        // Restaura step se houver old() do Laravel (validação falhou)
        @if ($errors->any())
            irParaStep2(); // volta ao passo 1 com erros
        @endif
    </script>
@endsection
