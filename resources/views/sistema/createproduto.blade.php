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

        .flash i {
            font-size: 16px;
        }

        /* Layout 2 colunas */
        .form-layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 20px;
            align-items: start;
        }

        /* Card genérico */
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
            padding: 16px 22px;
            background: #f0faf2;
            border-bottom: 2px solid #d1fae5;
        }

        .f-card-header i {
            font-size: 16px;
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

        /* Campos */
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

        .fg label span.req {
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

        .fc.is-loading {
            color: #9ca3af;
        }

        .row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        /* Hint */
        .field-hint {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 4px;
        }

        /* Botão submit */
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
            letter-spacing: .3px;
            font-family: 'Inter', sans-serif;
        }

        .btn-submit:hover {
            opacity: .92;
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Info box lateral */
        .info-box {
            background: #f0faf2;
            border-radius: 12px;
            border: 1px solid #d1fae5;
            padding: 16px;
            font-size: 12px;
            color: #374151;
            line-height: 1.6;
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
            <h4 class="pg-title"><i class="feather icon-plus-circle" style="color:#1a6b2f;margin-right:8px;"></i>Novo Fármaco
            </h4>
            <p class="pg-sub">Preencha os dados para registar um novo produto no sistema</p>
        </div>
        <a href="{{ route('produto.verp') }}" class="btn-back">
            <i class="feather icon-arrow-left"></i> Voltar
        </a>
    </div>

    @if (isset($sms))
        <div class="flash {{ $sms === 'Sucesso!' ? 'flash-ok' : 'flash-err' }}">
            <i class="feather {{ $sms === 'Sucesso!' ? 'icon-check-circle' : 'icon-alert-circle' }}"></i>
            {{ $sms }}
        </div>
    @endif

    <form action="{{ route('createproduto.create') }}" method="post">
        @csrf

        <div class="form-layout">

            {{-- COLUNA PRINCIPAL --}}
            <div>

                {{-- Classificação --}}
                <div class="f-card">
                    <div class="f-card-header">
                        <i class="feather icon-tag"></i>
                        <span>Classificação</span>
                    </div>
                    <div class="f-card-body">
                        <div class="fg">
                            <label>Categoria Geral <span class="req">*</span></label>
                            <select id="especialidade" name="setor_id" class="fc" required>
                                <option value="">— Selecione —</option>
                                @foreach ($Cageral as $geral)
                                    <option value="{{ $geral->id }}">{{ $geral->categoria_geral }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fg">
                            <label>Sub-Categoria <span class="req">*</span></label>
                            <select name="categoria_id" id="categoria_id" class="fc" required>
                                <option value="">— Selecione a categoria geral primeiro —</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Identificação --}}
                <div class="f-card">
                    <div class="f-card-header">
                        <i class="feather icon-package"></i>
                        <span>Identificação do Produto</span>
                    </div>
                    <div class="f-card-body">
                        <div class="fg">
                            <label>Descrição / Nome <span class="req">*</span></label>
                            <input type="text" name="produto" class="fc" required
                                placeholder="Ex: Amoxicilina 500mg">
                        </div>
                        <div class="row-2">
                            <div class="fg">
                                <label>Apresentação <span class="req">*</span></label>
                                <input type="text" name="apresentacao" class="fc" required
                                    placeholder="Ex: Comprimido, Injectável">
                            </div>
                            <div class="fg">
                                <label>Código de Barras</label>
                                <input type="text" name="codigo" class="fc" placeholder="Opcional">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stock --}}
                <div class="f-card">
                    <div class="f-card-header">
                        <i class="feather icon-layers"></i>
                        <span>Stock</span>
                    </div>
                    <div class="f-card-body">
                        <div class="row-2">
                            <div class="fg">
                                <label>Quantidade Inicial <span class="req">*</span></label>
                                <input type="number" name="quantidade" class="fc" required placeholder="0"
                                    min="0">
                                <p class="field-hint">Quantidade actual em armazém</p>
                            </div>
                            <div class="fg">
                                <label>Stock Mínimo <span class="req">*</span></label>
                                <input type="number" name="stokminimo" class="fc" required placeholder="0"
                                    min="0">
                                <p class="field-hint">Alerta quando atingir este valor</p>
                            </div>
                        </div>
                        <div class="fg">
                            <label>Data de Aquisição</label>
                            <input type="date" name="data_aquisicao" class="fc" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="feather icon-save"></i> Guardar Produto
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
                            <strong>Como preencher:</strong>
                            <ul>
                                <li>Selecione primeiro a <strong>Categoria Geral</strong> para carregar as sub-categorias.
                                </li>
                                <li>A <strong>Descrição</strong> deve incluir o nome e dosagem (ex: Amoxicilina 500mg).</li>
                                <li>O <strong>Stock Mínimo</strong> activa alertas quando o stock fica abaixo desse valor.
                                </li>
                                <li>Campos com <span style="color:#ef4444;">*</span> são obrigatórios.</li>
                                <li>O produto fica disponível em <strong>todos os departamentos</strong> após ser criado.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>

    <script>
        document.getElementById('especialidade').addEventListener('change', function() {
            var setor_id = this.value;
            var cat = document.getElementById('categoria_id');

            if (!setor_id) {
                cat.innerHTML = '<option value="">— Selecione a categoria geral primeiro —</option>';
                cat.disabled = false;
                return;
            }

            cat.innerHTML = '<option value="">A carregar...</option>';
            cat.disabled = true;

            var xhr = new XMLHttpRequest();
            xhr.open('GET', '{{ url('get-state-list') }}?setor_id=' + setor_id, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    cat.disabled = false;
                    if (xhr.status === 200) {
                        var res = JSON.parse(xhr.responseText);
                        cat.innerHTML = '<option value="">— Selecione —</option>';
                        if (res.length === 0) {
                            cat.innerHTML += '<option value="">Sem subcategorias</option>';
                        } else {
                            res.forEach(function(item) {
                                cat.innerHTML += '<option value="' + item.id + '">' + item.categoria +
                                    '</option>';
                            });
                        }
                    } else {
                        cat.innerHTML = '<option value="">Erro ao carregar</option>';
                    }
                }
            };
            xhr.send();
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            var cat = document.getElementById('categoria_id');
            if (!cat.value) {
                e.preventDefault();
                cat.style.borderColor = '#ef4444';
                cat.focus();
                alert('Por favor selecione uma sub-categoria.');
            }
        });
    </script>
@endsection
