@extends('louyout.app')
@section('conteodo')
@include('louyout.styles')
@include('louyout.flash')

<style>
.at-layout { display:grid; grid-template-columns:1fr 360px; gap:20px; align-items:start; }

.f-card { background:#fff; border-radius:16px; border:1px solid #e5e7eb; box-shadow:0 2px 10px rgba(0,0,0,.05); overflow:hidden; margin-bottom:20px; }
.f-card-header { display:flex; align-items:center; gap:10px; padding:15px 22px; background:#f0faf2; border-bottom:2px solid #d1fae5; }
.f-card-header i    { font-size:15px; color:#1a6b2f; }
.f-card-header span { font-size:14px; font-weight:700; color:#1a6b2f; }
.f-card-body { padding:22px; }

.fg { margin-bottom:16px; }
.fg label { display:block; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.6px; color:#374151; margin-bottom:6px; }
.fc { width:100%; padding:10px 14px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:13px; color:#1a2332; background:#f9fafb; outline:none; transition:border-color .2s, box-shadow .2s; font-family:'Inter',sans-serif; }
.fc:focus { border-color:#1a6b2f; background:#fff; box-shadow:0 0 0 3px rgba(26,107,47,.1); }
.row-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }

/* Requisição card */
.req-card { background:#f0faf2; border:1px solid #d1fae5; border-radius:12px; padding:16px; margin-bottom:20px; }
.req-card .req-title { font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#1a6b2f; margin-bottom:8px; }
.req-card .req-body  { font-size:13px; color:#374151; line-height:1.6; background:#fff; border-radius:8px; padding:12px; border:1px solid #d1fae5; }
.req-card .req-meta  { display:flex; gap:16px; margin-top:10px; flex-wrap:wrap; }
.req-card .req-meta span { font-size:11px; color:#6b7280; display:flex; align-items:center; gap:4px; }

/* Tabela de medicamentos */
.med-table { width:100%; border-collapse:collapse; }
.med-table th { padding:9px 12px; text-align:left; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.4px; color:#1a6b2f; background:#f0faf2; border-bottom:2px solid #d1fae5; }
.med-table td { padding:10px 12px; border-bottom:1px solid #f3f4f6; vertical-align:middle; }
.med-table tr:last-child td { border-bottom:none; }

.stock-pill { display:inline-flex; align-items:center; gap:4px; padding:2px 8px; border-radius:20px; font-size:11px; font-weight:700; }
.sp-ok  { background:#d1fae5; color:#065f46; }
.sp-low { background:#fef3c7; color:#92400e; }
.sp-out { background:#fee2e2; color:#991b1b; }

/* Linha de medicamento */
.med-row { background:#fff; border-radius:12px; border:1px solid #e5e7eb; padding:14px; margin-bottom:10px; position:relative; }
.med-row .remove-btn { position:absolute; top:10px; right:10px; background:#fee2e2; border:none; border-radius:7px; width:28px; height:28px; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#991b1b; font-size:13px; }
.med-row .remove-btn:hover { background:#fca5a5; }
.med-row-grid { display:grid; grid-template-columns:2fr 1fr 80px; gap:10px; align-items:end; }

/* Botão adicionar */
.btn-add-med { display:flex; align-items:center; gap:8px; padding:10px 18px; background:#f0faf2; border:2px dashed #1a6b2f; border-radius:10px; color:#1a6b2f; font-size:13px; font-weight:600; cursor:pointer; width:100%; justify-content:center; transition:background .2s; font-family:'Inter',sans-serif; }
.btn-add-med:hover { background:#d1fae5; }

/* Submit */
.btn-submit { width:100%; padding:13px; border:none; border-radius:11px; background:linear-gradient(135deg,#1a6b2f,#2d9e4a); color:#fff; font-size:14px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:opacity .2s; font-family:'Inter',sans-serif; margin-top:8px; }
.btn-submit:hover { opacity:.9; }

/* Resumo lateral */
.resumo-item { display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:1px solid #f3f4f6; font-size:13px; }
.resumo-item:last-child { border-bottom:none; }
.resumo-empty { text-align:center; padding:20px; color:#9ca3af; font-size:13px; }

@media(max-width:900px) { .at-layout { grid-template-columns:1fr; } .med-row-grid { grid-template-columns:1fr; } .row-2 { grid-template-columns:1fr; } }
</style>

<div class="page-header-bar">
    <div>
        <h4 class="page-title"><i class="feather icon-user-check" style="color:#1a6b2f;margin-right:8px;"></i>Atendimento ao Utente</h4>
        <p class="page-sub">Dispense medicamentos e registe o atendimento</p>
    </div>
    <a href="{{ route('atendimento.index') }}" class="btn-back">
        <i class="feather icon-arrow-left"></i> Histórico
    </a>
</div>

{{-- Requisição associada --}}
@if($requisicao)
<div class="req-card">
    <div class="req-title"><i class="feather icon-file-text" style="margin-right:5px;"></i>Requisição associada</div>
    <div class="req-body">{!! $requisicao->requisicao !!}</div>
    <div class="req-meta">
        <span><i class="feather icon-home"></i>{{ $requisicao->departamento }}</span>
        <span><i class="feather icon-user"></i>{{ $requisicao->name }}</span>
        <span><i class="feather icon-calendar"></i>{{ $requisicao->data }}</span>
    </div>
</div>
@endif

<form action="{{ route('atendimento.store') }}" method="POST" id="atend-form">
@csrf
<input type="hidden" name="requisicao_id" value="{{ $requisicao_id }}">

<div class="at-layout">

    {{-- COLUNA PRINCIPAL --}}
    <div>

        {{-- Dados do utente --}}
        <div class="f-card">
            <div class="f-card-header">
                <i class="feather icon-user"></i>
                <span>Dados do Utente</span>
            </div>
            <div class="f-card-body">
                <div class="row-2">
                    <div class="fg">
                        <label>Nome do Utente <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="utente" class="fc" required
                               value="{{ old('utente') }}" placeholder="Nome completo">
                    </div>
                    <div class="fg">
                        <label>Nº de Processo</label>
                        <input type="text" name="processo" class="fc"
                               value="{{ old('processo') }}" placeholder="Opcional">
                    </div>
                </div>
                <div class="fg">
                    <label>Observações</label>
                    <textarea name="observacao" class="fc" rows="2" placeholder="Diagnóstico, notas clínicas...">{{ old('observacao') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Medicamentos --}}
        <div class="f-card">
            <div class="f-card-header">
                <i class="feather icon-package"></i>
                <span>Medicamentos a Dispensar</span>
            </div>
            <div class="f-card-body">
                <div id="med-list"></div>

                <button type="button" class="btn-add-med" onclick="addMed()">
                    <i class="feather icon-plus-circle"></i> Adicionar Medicamento
                </button>
            </div>
        </div>

        <button type="submit" class="btn-submit" onclick="return validateForm()">
            <i class="feather icon-check-circle"></i> Confirmar Atendimento
        </button>
    </div>

    {{-- COLUNA LATERAL — Resumo --}}
    <div>
        <div class="f-card">
            <div class="f-card-header">
                <i class="feather icon-list"></i>
                <span>Resumo da Dispensa</span>
            </div>
            <div class="f-card-body" id="resumo-body">
                <div class="resumo-empty" id="resumo-empty">
                    <i class="feather icon-package" style="font-size:28px;display:block;margin-bottom:8px;color:#d1d5db;"></i>
                    Nenhum medicamento adicionado
                </div>
                <div id="resumo-list"></div>
            </div>
        </div>

        <div class="f-card">
            <div class="f-card-header">
                <i class="feather icon-info"></i>
                <span>Medicamentos Disponíveis</span>
            </div>
            <div class="f-card-body" style="padding:0;max-height:320px;overflow-y:auto;">
                <table class="med-table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Lote</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produtosAgrupados as $pid => $lotes)
                            @foreach($lotes as $l)
                            @if($l->stock > 0)
                            <tr>
                                <td style="font-size:12px;font-weight:600;">{{ $l->produto }}</td>
                                <td style="font-size:11px;color:#6b7280;font-family:monospace;">{{ $l->lote }}</td>
                                <td>
                                    <span class="stock-pill {{ $l->stock <= 10 ? 'sp-low' : 'sp-ok' }}">
                                        {{ $l->stock }}
                                    </span>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</form>

{{-- Dados dos produtos para o JS --}}
<script>
const produtos = {!! json_encode($produtosJs) !!};

let medCount = 0;

function addMed() {
    medCount++;
    const idx = medCount;
    const div = document.createElement('div');
    div.className = 'med-row';
    div.id = 'med-row-' + idx;

    let opts = '<option value="">— Selecione —</option>';
    produtos.forEach(function(p) {
        opts += `<option value="${p.produto_id}" data-lote="${p.lote_id}" data-stock="${p.stock}" data-nome="${p.nome}" data-lote-num="${p.lote}">${p.nome}${p.apresentacao ? ' — '+p.apresentacao : ''} (Lote: ${p.lote} | Stock: ${p.stock})</option>`;
    });

    div.innerHTML = `
        <button type="button" class="remove-btn" onclick="removeMed(${idx})">
            <i class="feather icon-x"></i>
        </button>
        <div class="med-row-grid">
            <div class="fg" style="margin:0;">
                <label>Medicamento</label>
                <select name="produto_id[]" class="fc" id="prod-sel-${idx}" onchange="onProdChange(${idx})" required>
                    ${opts}
                </select>
                <input type="hidden" name="lote_id[]" id="lote-hid-${idx}">
            </div>
            <div class="fg" style="margin:0;">
                <label>Quantidade</label>
                <input type="number" name="quantidade[]" id="qty-${idx}" class="fc" min="1" value="1" required onchange="updateResumo()">
            </div>
            <div style="padding-bottom:2px;">
                <span class="stock-pill sp-ok" id="stock-pill-${idx}" style="display:none;"></span>
            </div>
        </div>
    `;
    document.getElementById('med-list').appendChild(div);
    updateResumo();
}

function onProdChange(idx) {
    const sel   = document.getElementById('prod-sel-' + idx);
    const opt   = sel.options[sel.selectedIndex];
    const loteH = document.getElementById('lote-hid-' + idx);
    const pill  = document.getElementById('stock-pill-' + idx);
    const qty   = document.getElementById('qty-' + idx);

    if (sel.value) {
        const lote  = opt.getAttribute('data-lote');
        const stock = parseInt(opt.getAttribute('data-stock'));
        loteH.value = lote;
        pill.textContent = 'Stock: ' + stock;
        pill.className = 'stock-pill ' + (stock <= 10 ? 'sp-low' : 'sp-ok');
        pill.style.display = 'inline-flex';
        qty.max = stock;
    } else {
        loteH.value = '';
        pill.style.display = 'none';
    }
    updateResumo();
}

function removeMed(idx) {
    const row = document.getElementById('med-row-' + idx);
    if (row) row.remove();
    updateResumo();
}

function updateResumo() {
    const rows  = document.querySelectorAll('.med-row');
    const list  = document.getElementById('resumo-list');
    const empty = document.getElementById('resumo-empty');
    list.innerHTML = '';

    if (rows.length === 0) {
        empty.style.display = 'block';
        return;
    }
    empty.style.display = 'none';

    rows.forEach(function(row) {
        const id  = row.id.replace('med-row-','');
        const sel = document.getElementById('prod-sel-' + id);
        const qty = document.getElementById('qty-' + id);
        if (!sel || !sel.value) return;
        const opt  = sel.options[sel.selectedIndex];
        const nome = opt.getAttribute('data-nome');
        const div  = document.createElement('div');
        div.className = 'resumo-item';
        div.innerHTML = `<span style="font-weight:600;color:#1a2e1a;">${nome}</span><span style="background:#d1fae5;color:#065f46;padding:2px 10px;border-radius:20px;font-size:12px;font-weight:700;">${qty ? qty.value : 1}</span>`;
        list.appendChild(div);
    });
}

function validateForm() {
    const rows = document.querySelectorAll('.med-row');
    if (rows.length === 0) {
        alert('Adicione pelo menos um medicamento.');
        return false;
    }
    return true;
}
</script>

@endsection
