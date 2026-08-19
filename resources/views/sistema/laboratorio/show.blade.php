@extends('louyout.app')
@section('conteodo')

    <style>
        .ls-wrap {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 20px;
            align-items: start;
        }

        /* Banner pedido */
        .ls-banner {
            background: linear-gradient(135deg, #1e3a5f, #1d4ed8);
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

        .ls-banner.urgente {
            background: linear-gradient(135deg, #7f1d1d, #dc2626);
        }

        .ls-banner::before {
            content: '';
            position: absolute;
            right: -30px;
            top: -30px;
            width: 130px;
            height: 130px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
        }

        .ls-av {
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

        .ls-nome {
            font-size: 20px;
            font-weight: 800;
        }

        .ls-meta {
            font-size: 13px;
            opacity: .8;
            margin-top: 4px;
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .ls-exame-label {
            font-size: 11px;
            font-weight: 700;
            opacity: .7;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 3px;
        }

        .ls-exame-nome {
            font-size: 18px;
            font-weight: 800;
        }

        /* Card */
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

        .f-card-head.blue-head {
            background: #eff6ff;
            border-bottom-color: #bfdbfe;
        }

        .f-card-head i {
            font-size: 15px;
            color: #1a6b2f;
        }

        .f-card-head.blue-head i {
            color: #1d4ed8;
        }

        .f-card-head span {
            font-size: 14px;
            font-weight: 700;
            color: #1a6b2f;
        }

        .f-card-head.blue-head span {
            color: #1d4ed8;
        }

        .f-card-body {
            padding: 22px;
        }

        /* Form */
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

        /* Upload */
        .upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: all .2s;
            background: #f9fafb;
        }

        .upload-area:hover {
            border-color: #1a6b2f;
            background: #f0faf2;
        }

        .upload-area.has-file {
            border-color: #1a6b2f;
            background: #f0faf2;
        }

        .upload-area input {
            display: none;
        }

        /* Resultado guardado */
        .res-saved {
            background: #f0faf2;
            border: 1px solid #d1fae5;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 14px;
        }

        .res-saved-head {
            font-size: 11px;
            font-weight: 700;
            color: #1a6b2f;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 8px;
        }

        .res-saved-txt {
            font-size: 13px;
            color: #374151;
            line-height: 1.7;
            white-space: pre-wrap;
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
            background: #1a6b2f;
            color: #fff;
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

        /* Vitais */
        .vt-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
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

        /* Histórico */
        .hist-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 0;
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
            margin-top: 3px;
        }

        @media(max-width:900px) {
            .ls-wrap {
                grid-template-columns: 1fr;
            }
        }
    </style>

    {{-- BANNER --}}
    @php $urgente = (bool)$pedido->urgente; @endphp
    <div class="ls-banner {{ $urgente ? 'urgente' : '' }}">
        <div class="ls-av">{{ mb_strtoupper(mb_substr($pedido->nome, 0, 1)) }}</div>
        <div style="flex:1;min-width:0;">
            <div class="ls-meta" style="margin-bottom:6px;">
                <span>👤 {{ $pedido->nome }}</span>
                @if ($pedido->data_nascimento)
                    <span>{{ \Carbon\Carbon::parse($pedido->data_nascimento)->age }} anos</span>
                @endif
                @if ($pedido->numero_processo)
                    <span># {{ $pedido->numero_processo }}</span>
                @endif
            </div>
            <div class="ls-exame-label">🔬 Pedido de Exame</div>
            <div class="ls-exame-nome">
                {{ $pedido->descricao_exame }}
                @if ($urgente)
                    <span
                        style="background:rgba(255,255,255,.2);padding:3px 10px;border-radius:20px;font-size:12px;margin-left:8px;">⚡
                        URGENTE</span>
                @endif
            </div>
            <div class="ls-meta" style="margin-top:6px;">
                <span>🩺 Dr. {{ $pedido->medico }}</span>
                <span>📅 {{ \Carbon\Carbon::parse($pedido->data_pedido)->format('d/m/Y') }}</span>
                <span>🕐 {{ \Carbon\Carbon::parse($pedido->hora_pedido)->format('H:i') }}
                    ({{ \Carbon\Carbon::parse($pedido->hora_pedido)->diffForHumans() }})
                </span>
            </div>
        </div>
        <a href="{{ route('laboratorio.index') }}"
            style="display:inline-flex;align-items:center;gap:5px;padding:8px 16px;background:rgba(255,255,255,.15);color:#fff;border-radius:9px;font-size:13px;font-weight:600;text-decoration:none;transition:background .2s;flex-shrink:0;"
            onmouseover="this.style.background='rgba(255,255,255,.25)'"
            onmouseout="this.style.background='rgba(255,255,255,.15)'">
            <i class="feather icon-arrow-left"></i> Voltar
        </a>
    </div>

    @include('louyout.flash')

    @if ($pedido->observacao)
        <div
            style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:14px 18px;margin-bottom:16px;display:flex;align-items:center;gap:10px;font-size:13px;color:#92400e;">
            <span style="font-size:20px;">💬</span>
            <div><strong>Nota do Médico:</strong> {{ $pedido->observacao }}</div>
        </div>
    @endif

    <div class="ls-wrap">

        {{-- COLUNA PRINCIPAL --}}
        <div>

            {{-- Resultado já registado --}}
            @if ($resultado)
                <div class="f-card">
                    <div class="f-card-head">
                        <i class="feather icon-check-circle"></i>
                        <span>Resultado Registado</span>
                        <span style="margin-left:auto;font-size:12px;color:#6b7280;font-weight:400;">
                            {{ \Carbon\Carbon::parse($resultado->created_at)->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    <div class="f-card-body">
                        <div class="res-saved">
                            <div class="res-saved-head">
                                <i class="feather icon-file-text"></i> Resultado
                            </div>
                            <div class="res-saved-txt">{{ $resultado->resultado }}</div>
                        </div>
                        @if ($resultado->ficheiro_path)
                            <a href="{{ asset('storage/' . $resultado->ficheiro_path) }}" target="_blank"
                                style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;background:#1a6b2f;color:#fff;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;margin-top:4px;">
                                <i class="feather icon-download"></i> Descarregar Ficheiro Anexo
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Formulário de resultado --}}
            <div class="f-card">
                <div class="f-card-head blue-head">
                    <i class="feather icon-edit-3"></i>
                    <span>{{ $resultado ? 'Actualizar Resultado' : 'Registar Resultado' }}</span>
                </div>
                <div class="f-card-body">
                    <form action="{{ route('laboratorio.store', $pedido->pedido_id) }}" method="POST"
                        enctype="multipart/form-data" id="form-resultado">
                        @csrf

                        <div class="fg">
                            <label>Resultado do Exame <span class="req">*</span></label>
                            <textarea name="resultado" class="fc" rows="8" required
                                placeholder="Descreva o resultado detalhado do exame...&#10;&#10;Valores de referência, interpretação, observações...">{{ old('resultado', optional($resultado)->resultado) }}</textarea>
                        </div>

                        {{-- Upload de ficheiro --}}
                        <div class="fg">
                            <label>Ficheiro Anexo <span style="font-weight:400;color:#9ca3af;">(PDF, JPG, PNG — máx.
                                    5MB)</span></label>
                            <div class="upload-area" id="upload-area" onclick="document.getElementById('inp-file').click()">
                                <input type="file" name="ficheiro" id="inp-file" accept=".pdf,.jpg,.jpeg,.png"
                                    onchange="fileSelected(this)">
                                <div id="upload-default">
                                    <div style="font-size:32px;margin-bottom:8px;">📎</div>
                                    <div style="font-size:14px;font-weight:600;color:#374151;">Clique para anexar ficheiro
                                    </div>
                                    <div style="font-size:12px;color:#9ca3af;margin-top:4px;">PDF, JPG ou PNG até 5MB</div>
                                </div>
                                <div id="upload-preview" style="display:none;">
                                    <div style="font-size:32px;margin-bottom:6px;">✅</div>
                                    <div id="upload-filename" style="font-size:14px;font-weight:700;color:#1a6b2f;"></div>
                                    <div style="font-size:12px;color:#6b7280;margin-top:3px;">Clique para alterar</div>
                                </div>
                            </div>
                            @if ($resultado && $resultado->ficheiro_path)
                                <div style="margin-top:8px;font-size:12px;color:#6b7280;">
                                    <i class="feather icon-paperclip" style="font-size:11px;"></i>
                                    Ficheiro actual: {{ basename($resultado->ficheiro_path) }}
                                    (deixe em branco para manter)
                                </div>
                            @endif
                        </div>

                        <div style="display:flex;gap:10px;flex-wrap:wrap;">
                            <button type="submit" class="btn-g btn-verde" style="flex:1;">
                                <i class="feather icon-send"></i>
                                {{ $resultado ? 'Actualizar e Notificar Médico' : 'Registar e Notificar Médico' }}
                            </button>
                            <a href="{{ route('laboratorio.index') }}" class="btn-g btn-outline">
                                <i class="feather icon-x"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        {{-- COLUNA LATERAL --}}
        <div>

            {{-- Dados vitais --}}
            @if ($pedido->pressao_arterial || $pedido->temperatura || $pedido->peso)
                <div class="f-card">
                    <div class="f-card-head">
                        <i class="feather icon-heart"></i>
                        <span>Sinais Vitais</span>
                    </div>
                    <div class="f-card-body" style="padding:14px 20px;">
                        @php $temFebre = $pedido->temperatura && $pedido->temperatura > 37.5; @endphp
                        @if ($temFebre)
                            <div
                                style="background:#fee2e2;border-radius:10px;padding:10px;margin-bottom:10px;display:flex;align-items:center;gap:8px;font-size:13px;font-weight:700;color:#991b1b;">
                                🌡️ FEBRE — {{ $pedido->temperatura }}°C
                            </div>
                        @endif
                        @if ($pedido->pressao_arterial)
                            <div class="vt-row"><span class="vt-lbl2">🩺 Pressão Arterial</span><span
                                    class="vt-val2">{{ $pedido->pressao_arterial }}</span></div>
                        @endif
                        @if ($pedido->temperatura)
                            <div class="vt-row"><span class="vt-lbl2">🌡️ Temperatura</span><span
                                    class="vt-val2 {{ $temFebre ? 'danger' : '' }}">{{ $pedido->temperatura }}°C</span>
                            </div>
                        @endif
                        @if ($pedido->peso)
                            <div class="vt-row"><span class="vt-lbl2">⚖️ Peso</span><span
                                    class="vt-val2">{{ $pedido->peso }} kg</span></div>
                        @endif
                        @if ($pedido->altura)
                            <div class="vt-row"><span class="vt-lbl2">📏 Altura</span><span
                                    class="vt-val2">{{ $pedido->altura }} cm</span></div>
                        @endif
                        @if ($pedido->obs_triagem)
                            <div
                                style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:10px;margin-top:10px;font-size:12px;color:#92400e;">
                                <strong>Queixas:</strong> {{ $pedido->obs_triagem }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Histórico de exames do paciente --}}
            <div class="f-card">
                <div class="f-card-head">
                    <i class="feather icon-clock"></i>
                    <span>Exames Anteriores</span>
                </div>
                <div class="f-card-body" style="padding:8px 16px;">
                    @forelse($historico as $h)
                        @php $dot = $h->estado === 'concluido' ? '#1a6b2f' : '#f59e0b'; @endphp
                        <div class="hist-item">
                            <div class="hist-dot" style="background:{{ $dot }};"></div>
                            <div>
                                <div style="font-size:13px;font-weight:600;color:#1a2e1a;">{{ $h->descricao_exame }}</div>
                                <div style="font-size:11px;color:#9ca3af;margin-top:2px;">
                                    {{ \Carbon\Carbon::parse($h->data_pedido)->format('d/m/Y') }}
                                    · {{ $h->estado === 'concluido' ? '✅ Concluído' : '⏳ Pendente' }}
                                </div>
                                @if ($h->resultado)
                                    <div style="font-size:12px;color:#6b7280;margin-top:3px;font-style:italic;">
                                        {{ \Str::limit($h->resultado, 60) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div style="padding:16px 4px;text-align:center;color:#9ca3af;font-size:13px;">
                            Primeiro exame deste paciente.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    <script>
        function fileSelected(input) {
            const file = input.files[0];
            if (!file) return;

            const maxSize = 5 * 1024 * 1024;
            if (file.size > maxSize) {
                alert('Ficheiro muito grande. Máximo 5MB.');
                input.value = '';
                return;
            }

            document.getElementById('upload-default').style.display = 'none';
            document.getElementById('upload-preview').style.display = 'block';
            document.getElementById('upload-filename').textContent = file.name + ' (' + (file.size / 1024).toFixed(0) +
                ' KB)';
            document.getElementById('upload-area').classList.add('has-file');
        }
    </script>

@endsection
