@extends('louyout.app')
@section('conteodo')

<style>
.pg-header { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-bottom:24px; }
.pg-title   { font-size:20px; font-weight:700; color:#1a2e1a; margin:0; }
.pg-sub     { font-size:13px; color:#6b7280; margin:3px 0 0; }

.btn-back {
    display:inline-flex; align-items:center; gap:6px;
    padding:9px 18px; border:2px solid #1a6b2f; border-radius:10px;
    color:#1a6b2f; font-size:13px; font-weight:600; text-decoration:none;
    transition:background .2s, color .2s;
}
.btn-back:hover { background:#1a6b2f; color:#fff; text-decoration:none; }

.flash { display:flex; align-items:center; gap:10px; padding:13px 18px; border-radius:12px; font-size:13px; font-weight:500; margin-bottom:20px; }
.flash-err { background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; }
.flash i   { font-size:16px; }

.form-layout {
    display:grid;
    grid-template-columns:1fr 320px;
    gap:20px;
    align-items:start;
}

.f-card {
    background:#fff; border-radius:16px; border:1px solid #e5e7eb;
    box-shadow:0 2px 10px rgba(0,0,0,.05); overflow:hidden;
    margin-bottom:20px;
}
.f-card-header {
    display:flex; align-items:center; gap:10px;
    padding:16px 22px; background:#f0faf2;
    border-bottom:2px solid #d1fae5;
}
.f-card-header i    { font-size:16px; color:#1a6b2f; }
.f-card-header span { font-size:14px; font-weight:700; color:#1a6b2f; }
.f-card-body { padding:22px; }

.fg { margin-bottom:18px; }
.fg:last-child { margin-bottom:0; }
.fg label {
    display:block; font-size:11px; font-weight:700;
    text-transform:uppercase; letter-spacing:.6px;
    color:#374151; margin-bottom:6px;
}
.fg label span.req { color:#ef4444; margin-left:2px; }
.fc {
    width:100%; padding:10px 14px;
    border:1.5px solid #e5e7eb; border-radius:10px;
    font-size:13px; color:#1a2332; background:#f9fafb;
    outline:none; transition:border-color .2s, box-shadow .2s;
    font-family:'Inter',sans-serif;
}
.fc:focus { border-color:#1a6b2f; background:#fff; box-shadow:0 0 0 3px rgba(26,107,47,.1); }

.row-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.field-hint { font-size:11px; color:#9ca3af; margin-top:4px; }

.btn-submit {
    width:100%; padding:13px; border:none; border-radius:11px;
    background:linear-gradient(135deg,#1a6b2f,#2d9e4a);
    color:#fff; font-size:14px; font-weight:700;
    cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px;
    transition:opacity .2s, transform .1s; letter-spacing:.3px;
    font-family:'Inter',sans-serif;
}
.btn-submit:hover { opacity:.92; transform:translateY(-1px); }

/* Badge de ID */
.id-badge {
    display:inline-flex; align-items:center; gap:6px;
    padding:6px 14px; background:#f3f4f6; border-radius:8px;
    font-size:12px; font-weight:600; color:#374151;
}

/* Aviso de campo sensível */
.field-warn {
    display:flex; align-items:center; gap:6px;
    padding:8px 12px; background:#fef3c7; border-radius:8px;
    font-size:11px; color:#92400e; margin-top:6px;
}

@media(max-width:768px) {
    .form-layout { grid-template-columns:1fr; }
    .row-2 { grid-template-columns:1fr; }
}
</style>

<div class="pg-header">
    <div>
        <h4 class="pg-title">
            <i class="feather icon-edit-2" style="color:#1a6b2f;margin-right:8px;"></i>
            Editar Fármaco
        </h4>
        <p class="pg-sub">Actualize os dados do produto</p>
    </div>
    <a href="{{ route('produto.verp') }}" class="btn-back">
        <i class="feather icon-arrow-left"></i> Voltar
    </a>
</div>

@if(isset($sms))
<div class="flash flash-err">
    <i class="feather icon-alert-circle"></i> {{ $sms }}
</div>
@endif

@if(session('success'))
<div class="flash" style="background:#d1fae5;border:1px solid #6ee7b7;color:#065f46;">
    <i class="feather icon-check-circle"></i> {{ session('success') }}
</div>
@endif

<form action="{{ route('produtoupdate.produtoupdate', $Products->id) }}" method="POST">
@csrf
@method('PUT')

<div class="form-layout">

    {{-- COLUNA PRINCIPAL --}}
    <div>

        {{-- Identificação --}}
        <div class="f-card">
            <div class="f-card-header">
                <i class="feather icon-package"></i>
                <span>Identificação do Produto</span>
            </div>
            <div class="f-card-body">
                <div class="fg">
                    <label>Descrição / Nome <span class="req">*</span></label>
                    <input type="text" name="produto" class="fc" required value="{{ $Products->produto }}" placeholder="Ex: Amoxicilina 500mg">
                </div>
                <div class="row-2">
                    <div class="fg">
                        <label>Apresentação <span class="req">*</span></label>
                        <input type="text" name="apresentacao" class="fc" required value="{{ $Products->apresentacao }}" placeholder="Ex: Comprimido">
                    </div>
                    <div class="fg">
                        <label>Código de Barras</label>
                        <input type="text" name="codigo" class="fc" value="{{ $Products->codigo }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Categoria --}}
        <div class="f-card">
            <div class="f-card-header">
                <i class="feather icon-tag"></i>
                <span>Categoria</span>
            </div>
            <div class="f-card-body">
                <div class="fg">
                    <label>Categoria Geral <span class="req">*</span></label>
                    <select id="categoria_geral_select" name="categoria_geral_id" class="fc" required>
                        <option value="">— Selecione —</option>
                        @foreach($Cageral as $geral)
                            <option value="{{ $geral->id }}" {{ $geral->id == $Products->categoria_geral_id ? 'selected' : '' }}>
                                {{ $geral->categoria_geral }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="fg">
                    <label>Sub-Categoria <span class="req">*</span></label>
                    <select id="categoria_id" name="categoria_id" class="fc" required>
                        @foreach($Ct as $categoria)
                            <option value="{{ $categoria->id }}" {{ $categoria->id == $Products->categoria_id ? 'selected' : '' }}>
                                {{ $categoria->categoria }}
                            </option>
                        @endforeach
                    </select>
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
                        <label>Quantidade</label>
                        <input type="number" name="quantidade" class="fc" value="{{ $Products->quantidade }}" min="0">
                        <div class="field-warn">
                            <i class="feather icon-alert-triangle" style="font-size:12px;"></i>
                            Altere apenas para correcções manuais
                        </div>
                    </div>
                    <div class="fg">
                        <label>Stock Mínimo <span class="req">*</span></label>
                        <input type="number" name="stokminimo" class="fc" required value="{{ $Products->stokminimo }}" min="0">
                        <p class="field-hint">Alerta quando atingir este valor</p>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-submit">
            <i class="feather icon-save"></i> Guardar Alterações
        </button>

    </div>

    {{-- COLUNA LATERAL --}}
    <div>
        <div class="f-card">
            <div class="f-card-header">
                <i class="feather icon-info"></i>
                <span>Informações</span>
            </div>
            <div class="f-card-body">
                <div style="display:flex;flex-direction:column;gap:12px;">

                    <div>
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#9ca3af;margin-bottom:4px;">ID do Produto</div>
                        <span class="id-badge"><i class="feather icon-hash" style="font-size:11px;"></i> {{ $Products->id }}</span>
                    </div>

                    @php
                        $depNome = DB::table('departamento')->where('id', $Products->departamento_id)->value('departamento');
                        $catNome = DB::table('categoria')->where('id', $Products->categoria_id)->value('categoria');
                    @endphp

                    <div>
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#9ca3af;margin-bottom:4px;">Departamento</div>
                        <div style="display:flex;align-items:center;gap:8px;padding:8px 12px;background:#f0faf2;border-radius:9px;border:1px solid #d1fae5;">
                            <i class="feather icon-home" style="color:#1a6b2f;font-size:14px;"></i>
                            <span style="font-size:13px;font-weight:600;color:#1a2e1a;">{{ $depNome ?? '—' }}</span>
                        </div>
                    </div>

                    <div>
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#9ca3af;margin-bottom:4px;">Categoria Actual</div>
                        <div style="display:flex;align-items:center;gap:8px;padding:8px 12px;background:#f0faf2;border-radius:9px;border:1px solid #d1fae5;">
                            <i class="feather icon-tag" style="color:#1a6b2f;font-size:14px;"></i>
                            <span style="font-size:13px;font-weight:600;color:#1a2e1a;">{{ $catNome ?? '—' }}</span>
                        </div>
                    </div>

                    @php
                        $movs   = DB::table('estoque')->where('produto_id', $Products->id)->get();
                        $stockR = $movs->sum('entrada') - $movs->sum('saida');
                        $sc     = $stockR <= 0 ? '#991b1b' : ($stockR <= $Products->stokminimo ? '#92400e' : '#065f46');
                        $sbg    = $stockR <= 0 ? '#fee2e2' : ($stockR <= $Products->stokminimo ? '#fef3c7' : '#d1fae5');
                    @endphp

                    <div>
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#9ca3af;margin-bottom:4px;">Stock Actual</div>
                        <div style="display:inline-flex;align-items:center;gap:8px;padding:8px 16px;background:{{ $sbg }};border-radius:9px;">
                            <i class="feather icon-layers" style="color:{{ $sc }};font-size:14px;"></i>
                            <span style="font-size:18px;font-weight:800;color:{{ $sc }};">{{ $stockR }}</span>
                            <span style="font-size:11px;color:{{ $sc }};opacity:.7;">unidades</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
</form>

@endsection

<script>
    document.getElementById('categoria_geral_select').addEventListener('change', function () {
        var setor_id = this.value;
        var cat = document.getElementById('categoria_id');

        if (!setor_id) {
            cat.innerHTML = '<option value="">— Selecione a categoria geral primeiro —</option>';
            return;
        }

        cat.innerHTML = '<option value="">A carregar...</option>';

        var xhr = new XMLHttpRequest();
        xhr.open('GET', '{{ url('get-state-list') }}?setor_id=' + setor_id, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                cat.innerHTML = '';
                if (data.length === 0) {
                    cat.innerHTML = '<option value="">— Sem subcategorias —</option>';
                } else {
                    data.forEach(function (item) {
                        var opt = document.createElement('option');
                        opt.value = item.id;
                        opt.textContent = item.categoria;
                        cat.appendChild(opt);
                    });
                }
            }
        };
        xhr.send();
    });
</script>
