@extends('louyout.app')
@section('conteodo')
    <style>
        .ds-wrap {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 20px;
            align-items: start;
        }

        /* Banner */
        .ds-banner {
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
            box-shadow: 0 8px 24px rgba(26, 107, 47, .3);
        }

        .ds-banner::before {
            content: '';
            position: absolute;
            right: -30px;
            top: -30px;
            width: 130px;
            height: 130px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
        }

        .ds-av {
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

        .ds-nome {
            font-size: 20px;
            font-weight: 800;
        }

        .ds-meta {
            font-size: 13px;
            opacity: .8;
            margin-top: 4px;
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        /* Cards */
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

        /* Medicamentos */
        .med-disp-item {
            background: #f9fafb;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            padding: 16px 18px;
            margin-bottom: 12px;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 14px;
            align-items: start;
        }

        .med-disp-item:last-child {
            margin-bottom: 0;
        }

        .med-disp-item.sem-stock {
            background: #fef2f2;
            border-color: #fca5a5;
        }

        .mdi-nome {
            font-size: 14px;
            font-weight: 800;
            color: #1a2e1a;
            margin-bottom: 4px;
        }

        .mdi-sub {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .mdi-prescricao {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .mdi-presc-chip {
            background: #f0faf2;
            border: 1px solid #d1fae5;
            border-radius: 8px;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: 600;
            color: #1a6b2f;
        }

        .mdi-lote-select {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .mdi-lote-select label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #374151;
            white-space: nowrap;
        }

        .fc-sm {
            padding: 8px 12px;
            border: 1.5px solid #e5e7eb;
            border-radius: 9px;
            font-size: 13px;
            background: #fff;
            outline: none;
            font-family: 'Inter', sans-serif;
            transition: border-color .2s;
        }

        .fc-sm:focus {
            border-color: #1a6b2f;
            box-shadow: 0 0 0 3px rgba(26, 107, 47, .1);
        }

        .stock-badge {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .sb-ok {
            background: #d1fae5;
            color: #065f46;
        }

        .sb-warn {
            background: #fef3c7;
            color: #92400e;
        }

        .sb-bad {
            background: #fee2e2;
            color: #991b1b;
        }

        .mdi-qty {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .mdi-qty .big-num {
            font-size: 28px;
            font-weight: 900;
            color: #1a6b2f;
            line-height: 1;
        }

        .mdi-qty .big-lbl {
            font-size: 11px;
            color: #9ca3af;
            font-weight: 600;
            text-transform: uppercase;
        }

        /* Botões */
        .btn-g {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 11px 22px;
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

        .btn-verde {
            background: linear-gradient(135deg, #1a6b2f, #2d9e4a);
            color: #fff;
            box-shadow: 0 4px 14px rgba(26, 107, 47, .25);
        }

        .btn-outline {
            background: #fff;
            color: #374151;
            border: 2px solid #e5e7eb;
        }

        .btn-outline:hover {
            border-color: #1a6b2f;
            color: #1a6b2f;
        }

        /* Info lateral */
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
            font-size: 13px;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .ir-lbl {
            color: #9ca3af;
            font-size: 12px;
        }

        .ir-val {
            font-weight: 700;
            color: #1a2e1a;
        }

        /* Dispensada badge */
        .dispensada-banner {
            background: #d1fae5;
            border: 1px solid #6ee7b7;
            border-radius: 14px;
            padding: 16px 20px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        @media(max-width:900px) {
            .ds-wrap {
                grid-template-columns: 1fr;
            }
        }
    </style>

    {{-- BANNER PACIENTE --}}
    <div class="ds-banner">
        <div class="ds-av">{{ mb_strtoupper(mb_substr($receita->nome, 0, 1)) }}</div>
        <div style="flex:1;min-width:0;">
            <div
                style="font-size:11px;font-weight:700;opacity:.7;text-transform:uppercase;letter-spacing:.8px;margin-bottom:3px;">
                💊 Receita Médica
            </div>
            <div class="ds-nome">{{ $receita->nome }}</div>
            <div class="ds-meta">
                @if ($receita->data_nascimento)
                    <span>{{ $receita->sexo === 'M' ? '♂' : '♀' }}
                        {{ \Carbon\Carbon::parse($receita->data_nascimento)->age }} anos</span>
                @endif
                @if ($receita->numero_processo)
                    <span># {{ $receita->numero_processo }}</span>
                @endif
                <span>🩺 Dr. {{ $receita->medico }}</span>
                <span>📅 {{ \Carbon\Carbon::parse($receita->data)->format('d/m/Y') }}</span>
            </div>
        </div>
        <div style="display:flex;gap:8px;flex-shrink:0;align-items:center;">
            <span
                style="padding:5px 14px;border-radius:20px;font-size:12px;font-weight:700;background:{{ $receita->estado === 'dispensada' ? 'rgba(209,250,229,.9)' : 'rgba(254,243,199,.9)' }};color:{{ $receita->estado === 'dispensada' ? '#065f46' : '#92400e' }};">
                {{ $receita->estado === 'dispensada' ? '✅ Dispensada' : '⏳ Pendente' }}
            </span>
            <a href="{{ route('receitas.pdf', $receita->receita_id) }}" target="_blank"
                style="display:inline-flex;align-items:center;gap:5px;padding:8px 16px;background:rgba(255,255,255,.15);color:#fff;border-radius:9px;font-size:13px;font-weight:600;text-decoration:none;transition:background .2s;"
                onmouseover="this.style.background='rgba(255,255,255,.25)'"
                onmouseout="this.style.background='rgba(255,255,255,.15)'">
                <i class="feather icon-printer"></i> PDF
            </a>
            <a href="{{ route('receitas.index') }}"
                style="display:inline-flex;align-items:center;gap:5px;padding:8px 16px;background:rgba(255,255,255,.15);color:#fff;border-radius:9px;font-size:13px;font-weight:600;text-decoration:none;transition:background .2s;"
                onmouseover="this.style.background='rgba(255,255,255,.25)'"
                onmouseout="this.style.background='rgba(255,255,255,.15)'">
                <i class="feather icon-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    @include('louyout.flash')

    {{-- Já dispensada --}}
    @if ($receita->estado === 'dispensada')
        <div class="dispensada-banner">
            <span style="font-size:28px;">✅</span>
            <div>
                <div style="font-size:15px;font-weight:700;color:#065f46;">Receita já dispensada</div>
                <div style="font-size:12px;color:#065f46;opacity:.8;margin-top:2px;">
                    O paciente já levantou a medicação.
                    @if ($atendimento)
                        <a href="{{ route('atendimento.show', $atendimento->id) }}"
                            style="color:#1a6b2f;font-weight:600;margin-left:6px;">
                            Ver atendimento →
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="ds-wrap">

        {{-- COLUNA PRINCIPAL --}}
        <div>

            {{-- Diagnóstico (contexto) --}}
            @if ($receita->diagnostico)
                <div class="f-card">
                    <div class="f-card-head">
                        <i class="feather icon-clipboard"></i>
                        <span>Diagnóstico do Médico</span>
                    </div>
                    <div class="f-card-body" style="font-size:14px;color:#374151;line-height:1.6;">
                        {{ $receita->diagnostico }}
                    </div>
                </div>
            @endif

            {{-- Formulário de dispensa --}}
            @if ($receita->estado !== 'dispensada')
                <form action="{{ route('receitas.dispensar', $receita->receita_id) }}" method="POST" id="form-dispensar">
                    @csrf
            @endif

            <div class="f-card">
                <div class="f-card-head">
                    <i class="feather icon-package"></i>
                    <span>Medicamentos a Dispensar</span>
                    <span style="margin-left:auto;font-size:12px;font-weight:400;color:#6b7280;">{{ $itens->count() }}
                        item(ns)</span>
                </div>
                <div class="f-card-body">
                    @foreach ($itens as $item)
                        @php
                            $stockCls =
                                $item->stock >= $item->quantidade ? 'sb-ok' : ($item->stock > 0 ? 'sb-warn' : 'sb-bad');
                            $stockTxt =
                                $item->stock >= $item->quantidade
                                    ? "Stock OK: {$item->stock}"
                                    : ($item->stock > 0
                                        ? "Baixo: {$item->stock}"
                                        : 'Sem stock');
                        @endphp
                        <div class="med-disp-item {{ !$item->stock_suficiente ? 'sem-stock' : '' }}">
                            <div>
                                <div class="mdi-nome">💊 {{ $item->produto }}
                                    @if ($item->apresentacao)
                                        <span style="font-size:12px;color:#9ca3af;font-weight:400;"> —
                                            {{ $item->apresentacao }}</span>
                                    @endif
                                </div>
                                {{-- Prescrição --}}
                                <div class="mdi-prescricao">
                                    @if ($item->dose)
                                        <span class="mdi-presc-chip">💉 {{ $item->dose }}</span>
                                    @endif
                                    @if ($item->frequencia)
                                        <span class="mdi-presc-chip">🔄 {{ $item->frequencia }}</span>
                                    @endif
                                    @if ($item->duracao)
                                        <span class="mdi-presc-chip">📅 {{ $item->duracao }}</span>
                                    @endif
                                </div>
                                {{-- Selecção de lote --}}
                                @if ($receita->estado !== 'dispensada')
                                    <div class="mdi-lote-select">
                                        <label>Lote:</label>
                                        @php
                                            $lotes = \DB::table('lote')
                                                ->where('produto_id', $item->produto_id)
                                                ->where('departamento_id', auth()->user()->departamento_id)
                                                ->orderBy('validade')
                                                ->get();
                                        @endphp
                                        <select name="lote_id_{{ $item->produto_id }}" class="fc-sm" required>
                                            <option value="">— Seleccione —</option>
                                            @foreach ($lotes as $lote)
                                                @php
                                                    $stockLote = \DB::table('estoque')
                                                        ->where('lote_id', $lote->id)
                                                        ->where('departamento_id', auth()->user()->departamento_id)
                                                        ->sum(\DB::raw('entrada - saida'));
                                                    $stockLote = max(0, $stockLote);
                                                @endphp
                                                <option value="{{ $lote->id }}"
                                                    {{ $lote->id == $item->lote_id ? 'selected' : '' }}>
                                                    {{ $lote->lote }} — Val:
                                                    {{ $lote->validade ? \Carbon\Carbon::parse($lote->validade)->format('m/Y') : '—' }}
                                                    — Stock: {{ $stockLote }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="stock-badge {{ $stockCls }}">{{ $stockTxt }}</span>
                                    </div>
                                @else
                                    <div style="font-size:12px;color:#6b7280;margin-top:4px;">
                                        Lote: <strong>{{ $item->lote_num ?? '—' }}</strong>
                                        @if ($item->validade)
                                            · Val: {{ \Carbon\Carbon::parse($item->validade)->format('m/Y') }}
                                        @endif
                                    </div>
                                @endif
                            </div>
                            {{-- Quantidade --}}
                            <div class="mdi-qty">
                                <div>
                                    <div class="big-num">{{ $item->quantidade }}</div>
                                    <div class="big-lbl">unid.</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Observações e submit --}}
            @if ($receita->estado !== 'dispensada')
                <div class="f-card">
                    <div class="f-card-head">
                        <i class="feather icon-message-square"></i>
                        <span>Observações da Farmácia</span>
                    </div>
                    <div class="f-card-body">
                        <textarea name="observacao" class="fc-sm" style="width:100%;min-height:80px;resize:vertical;"
                            placeholder="Instruções ao paciente, notas da dispensa...">{{ $receita->observacao }}</textarea>
                    </div>
                </div>

                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <button type="submit" class="btn-g btn-verde" style="flex:1;" onclick="return confirmarDispensa()">
                        <i class="feather icon-check-circle"></i> Confirmar Dispensa e Registar Atendimento
                    </button>
                    <a href="{{ route('receitas.index') }}" class="btn-g btn-outline">
                        <i class="feather icon-x"></i> Cancelar
                    </a>
                </div>
                </form>
            @endif

        </div>

        {{-- COLUNA LATERAL --}}
        <div>

            {{-- Dados do paciente --}}
            <div class="f-card">
                <div class="f-card-head">
                    <i class="feather icon-user"></i>
                    <span>Paciente</span>
                </div>
                <div class="f-card-body" style="padding:14px 20px;">
                    <div class="info-row"><span class="ir-lbl">Nome</span><span class="ir-val">{{ $receita->nome }}</span>
                    </div>
                    @if ($receita->data_nascimento)
                        <div class="info-row">
                            <span class="ir-lbl">Idade</span>
                            <span class="ir-val">{{ \Carbon\Carbon::parse($receita->data_nascimento)->age }} anos</span>
                        </div>
                    @endif
                    <div class="info-row">
                        <span class="ir-lbl">Sexo</span>
                        <span class="ir-val">{{ $receita->sexo === 'M' ? 'Masculino' : 'Feminino' }}</span>
                    </div>
                    @if ($receita->numero_processo)
                        <div class="info-row"><span class="ir-lbl">Nº Processo</span><span class="ir-val"
                                style="font-family:monospace;">{{ $receita->numero_processo }}</span></div>
                    @endif
                    @if ($receita->telefone)
                        <div class="info-row"><span class="ir-lbl">Telefone</span><span
                                class="ir-val">{{ $receita->telefone }}</span></div>
                    @endif
                </div>
            </div>

            {{-- Info da receita --}}
            <div class="f-card">
                <div class="f-card-head">
                    <i class="feather icon-file-text"></i>
                    <span>Detalhes da Receita</span>
                </div>
                <div class="f-card-body" style="padding:14px 20px;">
                    <div class="info-row"><span class="ir-lbl">Médico</span><span class="ir-val">Dr.
                            {{ $receita->medico }}</span></div>
                    <div class="info-row"><span class="ir-lbl">Data</span><span
                            class="ir-val">{{ \Carbon\Carbon::parse($receita->data)->format('d/m/Y') }}</span></div>
                    <div class="info-row">
                        <span class="ir-lbl">Estado</span>
                        <span
                            style="padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;{{ $receita->estado === 'dispensada' ? 'background:#d1fae5;color:#065f46;' : 'background:#fef3c7;color:#92400e;' }}">
                            {{ $receita->estado === 'dispensada' ? '✅ Dispensada' : '⏳ Pendente' }}
                        </span>
                    </div>
                    @if ($receita->observacao)
                        <div
                            style="margin-top:10px;padding:10px;background:#fffbeb;border-radius:10px;font-size:12px;color:#92400e;border:1px solid #fde68a;">
                            <strong>Nota do médico:</strong> {{ $receita->observacao }}
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>

    <script>
        function confirmarDispensa() {
            // Verifica se todos os lotes foram seleccionados
            const selects = document.querySelectorAll('select[name^="lote_id_"]');
            for (const s of selects) {
                if (!s.value) {
                    s.style.borderColor = '#ef4444';
                    s.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    alert('Seleccione o lote para todos os medicamentos antes de confirmar.');
                    return false;
                }
            }
            return confirm(
                'Confirmar a dispensa desta receita?\n\nIsso irá baixar o stock e registar o atendimento do paciente.');
        }
    </script>
@endsection
