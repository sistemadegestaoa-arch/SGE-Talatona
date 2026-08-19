@extends('louyout.app')
@section('conteodo')
    <style>
        .pg-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 24px;
        }

        .pg-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a2e1a;
            margin: 0;
        }

        .pg-sub {
            font-size: 13px;
            color: #6b7280;
            margin: 3px 0 0;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border: 2px solid #1a6b2f;
            border-radius: 10px;
            color: #1a6b2f;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: background .2s, color .2s;
        }

        .btn-back:hover {
            background: #1a6b2f;
            color: #fff;
            text-decoration: none;
        }

        .flash {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 18px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .flash-ok {
            background: #d1fae5;
            border: 1px solid #6ee7b7;
            color: #065f46;
        }

        .flash-err {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        .form-layout {
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
            margin-bottom: 20px;
        }

        .f-card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px 22px;
            background: #f0faf2;
            border-bottom: 2px solid #d1fae5;
        }

        .f-card-header i {
            font-size: 15px;
            color: #1a6b2f;
        }

        .f-card-header span {
            font-size: 14px;
            font-weight: 700;
            color: #1a6b2f;
        }

        .f-card-body {
            padding: 22px;
        }

        .fg {
            margin-bottom: 18px;
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
            margin-left: 2px;
        }

        .fc {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 13px;
            color: #1a2332;
            background: #f9fafb;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
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

        .field-hint {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 4px;
        }

        /* Validade warning */
        .val-warn {
            display: none;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            background: #fef3c7;
            border-radius: 8px;
            font-size: 11px;
            color: #92400e;
            margin-top: 6px;
        }

        .val-warn.show {
            display: flex;
        }

        .btn-submit {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 11px;
            background: linear-gradient(135deg, #1a6b2f, #2d9e4a);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: opacity .2s, transform .1s;
            font-family: 'Inter', sans-serif;
        }

        .btn-submit:hover {
            opacity: .92;
            transform: translateY(-1px);
        }

        /* Info box */
        .info-box {
            background: #f0faf2;
            border-radius: 12px;
            border: 1px solid #d1fae5;
            padding: 16px;
            font-size: 12px;
            color: #374151;
            line-height: 1.7;
        }

        .info-box strong {
            color: #1a6b2f;
        }

        .info-box ul {
            margin: 8px 0 0 16px;
            padding: 0;
        }

        .info-box ul li {
            margin-bottom: 4px;
        }

        /* Produto preview */
        .prod-preview {
            display: none;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            background: #f0faf2;
            border-radius: 10px;
            border: 1px solid #d1fae5;
            margin-top: 8px;
        }

        .prod-preview.show {
            display: flex;
        }

        .prod-preview i {
            color: #1a6b2f;
            font-size: 16px;
        }

        .prod-preview span {
            font-size: 13px;
            font-weight: 600;
            color: #1a2e1a;
        }

        @media(max-width:768px) {
            .form-layout {
                grid-template-columns: 1fr;
            }

            .row-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="pg-header">
        <div>
            <h4 class="pg-title">
                <i class="feather icon-layers" style="color:#1a6b2f;margin-right:8px;"></i>
                Novo Lote
            </h4>
            <p class="pg-sub">Associe um lote a um produto do seu departamento</p>
        </div>
        <a href="{{ route('ver-lotes.index') }}" class="btn-back">
            <i class="feather icon-arrow-left"></i> Ver Lotes
        </a>
    </div>

    @if (session('success'))
        <div class="flash flash-ok"><i class="feather icon-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="flash flash-err"><i class="feather icon-alert-circle"></i> {{ session('error') }}</div>
    @endif
    @if (isset($sms))
        <div class="flash {{ str_contains($sms, 'sucesso') ? 'flash-ok' : 'flash-err' }}">
            <i class="feather {{ str_contains($sms, 'sucesso') ? 'icon-check-circle' : 'icon-alert-circle' }}"></i>
            {{ $sms }}
        </div>
    @endif

    <form action="{{ route('lote.store') }}" method="post">
        @csrf
        <input type="hidden" name="departamento_id" value="{{ Auth::user()->departamento_id }}">

        <div class="form-layout">

            {{-- COLUNA PRINCIPAL --}}
            <div>

                {{-- Produto --}}
                <div class="f-card">
                    <div class="f-card-header">
                        <i class="feather icon-package"></i>
                        <span>Produto</span>
                    </div>
                    <div class="f-card-body">
                        <div class="fg">
                            <label>Selecionar Produto <span class="req">*</span></label>
                            <select name="produto_id" id="produto_select" class="fc" required
                                onchange="showPreview(this)">
                                <option value="">— Selecione um produto —</option>
                                @foreach ($pro as $produto)
                                    <option value="{{ $produto->id }}" data-nome="{{ $produto->produto }}"
                                        data-apres="{{ $produto->apresentacao ?? '' }}">
                                        {{ $produto->produto }}{{ $produto->apresentacao ? ' — ' . $produto->apresentacao : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="prod-preview" id="prod-preview">
                                <i class="feather icon-package"></i>
                                <span id="prod-preview-text"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Identificação do Lote --}}
                <div class="f-card">
                    <div class="f-card-header">
                        <i class="feather icon-tag"></i>
                        <span>Identificação do Lote</span>
                    </div>
                    <div class="f-card-body">
                        <div class="row-2">
                            <div class="fg">
                                <label>Número do Lote <span class="req">*</span></label>
                                <input type="text" name="lote" class="fc" required value="{{ old('lote') }}"
                                    placeholder="Ex: LOT-2024-001">
                                <p class="field-hint">Número identificador do lote</p>
                            </div>
                            <div class="fg">
                                <label>Código de Barras</label>
                                <input type="text" name="codigo_barra" class="fc" value="{{ old('codigo_barra') }}"
                                    placeholder="Opcional">
                            </div>
                        </div>

                        <div class="fg">
                            <label>Data de Validade <span class="req">*</span></label>
                            <input type="date" name="validade" id="validade_input" class="fc" required
                                value="{{ old('validade') }}" onchange="checkValidade(this)">
                            <div class="val-warn" id="val-warn">
                                <i class="feather icon-alert-triangle" style="font-size:13px;"></i>
                                <span id="val-warn-text"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="feather icon-save"></i> Guardar Lote
                </button>

            </div>

            {{-- COLUNA LATERAL --}}
            <div>
                <div class="f-card">
                    <div class="f-card-header">
                        <i class="feather icon-info"></i>
                        <span>Instruções</span>
                    </div>
                    <div class="f-card-body">
                        <div class="info-box">
                            <strong>O que é um lote?</strong>
                            <ul>
                                <li>Um lote identifica um conjunto de unidades do mesmo produto com a mesma data de
                                    validade.</li>
                                <li>Cada lote é único por produto.</li>
                                <li>Após criar o lote, pode registar entradas de stock a partir da página do produto.</li>
                            </ul>
                            <br>
                            <strong>Campos obrigatórios:</strong>
                            <ul>
                                <li>Produto</li>
                                <li>Número do Lote</li>
                                <li>Data de Validade</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="f-card">
                    <div class="f-card-header">
                        <i class="feather icon-map-pin"></i>
                        <span>Departamento</span>
                    </div>
                    <div class="f-card-body">
                        @php
                            $depNome = DB::table('departamento')
                                ->where('id', Auth::user()->departamento_id)
                                ->value('departamento');
                        @endphp
                        <div
                            style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:#f0faf2;border-radius:10px;border:1px solid #d1fae5;">
                            <i class="feather icon-home" style="color:#1a6b2f;font-size:18px;"></i>
                            <div>
                                <div style="font-size:13px;font-weight:700;color:#1a2e1a;">{{ $depNome ?? '—' }}</div>
                                <div style="font-size:11px;color:#6b7280;">Departamento actual</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>

    <script>
        function showPreview(sel) {
            const opt = sel.options[sel.selectedIndex];
            const preview = document.getElementById('prod-preview');
            const text = document.getElementById('prod-preview-text');
            if (sel.value) {
                text.textContent = opt.getAttribute('data-nome') + (opt.getAttribute('data-apres') ? ' — ' + opt
                    .getAttribute('data-apres') : '');
                preview.classList.add('show');
            } else {
                preview.classList.remove('show');
            }
        }

        function checkValidade(input) {
            const warn = document.getElementById('val-warn');
            const warnText = document.getElementById('val-warn-text');
            if (!input.value) {
                warn.classList.remove('show');
                return;
            }

            const hoje = new Date();
            hoje.setHours(0, 0, 0, 0);
            const val = new Date(input.value);
            const diff = Math.round((val - hoje) / (1000 * 60 * 60 * 24));

            if (diff < 0) {
                warnText.textContent = 'Atenção: esta data já expirou!';
                warn.style.background = '#fee2e2';
                warn.style.color = '#991b1b';
                warn.classList.add('show');
            } else if (diff <= 90) {
                warnText.textContent = 'Atenção: este lote expira em ' + diff + ' dias.';
                warn.style.background = '#fef3c7';
                warn.style.color = '#92400e';
                warn.classList.add('show');
            } else {
                warn.classList.remove('show');
            }
        }
    </script>
@endsection
