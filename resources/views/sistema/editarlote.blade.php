@extends('louyout.app')
@section('conteodo')
@include('louyout.styles')
@include('louyout.flash')

<style>
.edit-layout { display:grid; grid-template-columns:1fr 280px; gap:20px; align-items:start; }
.f-card { background:#fff; border-radius:16px; border:1px solid #e5e7eb; box-shadow:0 2px 10px rgba(0,0,0,.05); overflow:hidden; margin-bottom:20px; }
.f-card-header { display:flex; align-items:center; gap:10px; padding:15px 22px; background:#f0faf2; border-bottom:2px solid #d1fae5; }
.f-card-header i    { font-size:15px; color:#1a6b2f; }
.f-card-header span { font-size:14px; font-weight:700; color:#1a6b2f; }
.f-card-body { padding:22px; }
.fg { margin-bottom:16px; }
.fg:last-child { margin-bottom:0; }
.fg label { display:block; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.6px; color:#374151; margin-bottom:6px; }
.fc { width:100%; padding:10px 14px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:13px; color:#1a2332; background:#f9fafb; outline:none; transition:border-color .2s, box-shadow .2s; font-family:'Inter',sans-serif; }
.fc:focus { border-color:#1a6b2f; background:#fff; box-shadow:0 0 0 3px rgba(26,107,47,.1); }
.row-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.btn-submit { width:100%; padding:12px; border:none; border-radius:10px; background:linear-gradient(135deg,#1a6b2f,#2d9e4a); color:#fff; font-size:14px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:opacity .2s; font-family:'Inter',sans-serif; }
.btn-submit:hover { opacity:.9; }
.val-warn { display:none; align-items:center; gap:6px; padding:8px 12px; border-radius:8px; font-size:11px; margin-top:6px; }
.val-warn.show { display:flex; }
@media(max-width:768px){ .edit-layout{ grid-template-columns:1fr; } .row-2{ grid-template-columns:1fr; } }
</style>

<div class="page-header-bar">
    <div>
        <h4 class="page-title"><i class="feather icon-edit-2" style="color:#1a6b2f;margin-right:8px;"></i>Editar Lote</h4>
        <p class="page-sub">Actualize os dados do lote</p>
    </div>
    <a href="{{ route('ver-lotes.index') }}" class="btn-back">
        <i class="feather icon-arrow-left"></i> Voltar
    </a>
</div>


<div class="edit-layout">
    <div>
        <div class="f-card">
            <div class="f-card-header">
                <i class="feather icon-package"></i>
                <span>Produto Associado</span>
            </div>
            <div class="f-card-body">
                <div style="display:flex;align-items:center;gap:12px;padding:12px 16px;background:#f0faf2;border-radius:10px;border:1px solid #d1fae5;">
                    <i class="feather icon-package" style="color:#1a6b2f;font-size:20px;"></i>
                    <div>
                        <div style="font-size:14px;font-weight:700;color:#1a2e1a;">{{ $lote->produto }}</div>
                        <div style="font-size:11px;color:#6b7280;margin-top:2px;">Produto não pode ser alterado</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="f-card">
            <div class="f-card-header">
                <i class="feather icon-tag"></i>
                <span>Dados do Lote</span>
            </div>
            <div class="f-card-body">
                <form action="{{ route('updatelote.update', $lote->id) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="row-2">
                        <div class="fg">
                            <label>Número do Lote <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="lote" class="fc" required
                                   value="{{ old('lote', $lote->lote) }}"
                                   placeholder="Ex: LOT-2024-001">
                            @error('lote')
                                <p style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="fg">
                            <label>Código de Barras</label>
                            <input type="text" name="codigo_barra" class="fc"
                                   value="{{ old('codigo_barra', $lote->codigo_barra) }}"
                                   placeholder="Opcional">
                        </div>
                    </div>

                    <div class="fg">
                        <label>Data de Validade <span style="color:#ef4444;">*</span></label>
                        <input type="date" name="validade" id="validade_input" class="fc" required
                               value="{{ old('validade', $lote->validade) }}"
                               onchange="checkValidade(this)">
                        <div class="val-warn" id="val-warn">
                            <i class="feather icon-alert-triangle" style="font-size:13px;"></i>
                            <span id="val-warn-text"></span>
                        </div>
                        @error('validade')
                            <p style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="feather icon-save"></i> Guardar Alterações
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div>
        <div class="f-card">
            <div class="f-card-header">
                <i class="feather icon-info"></i>
                <span>Informações</span>
            </div>
            <div class="f-card-body">
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <div>
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#9ca3af;margin-bottom:4px;">ID do Lote</div>
                        <span style="display:inline-flex;align-items:center;gap:6px;padding:5px 12px;background:#f3f4f6;border-radius:8px;font-size:12px;font-weight:600;color:#374151;">
                            <i class="feather icon-hash" style="font-size:11px;"></i> {{ $lote->id }}
                        </span>
                    </div>
                    <div>
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#9ca3af;margin-bottom:4px;">Lote Actual</div>
                        <div style="padding:8px 12px;background:#f0faf2;border-radius:9px;border:1px solid #d1fae5;font-size:13px;font-weight:600;color:#1a2e1a;font-family:monospace;">
                            {{ $lote->lote }}
                        </div>
                    </div>
                    <div>
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#9ca3af;margin-bottom:4px;">Validade Actual</div>
                        @php
                            $hoje   = \Carbon\Carbon::today();
                            $valDt  = $lote->validade ? \Carbon\Carbon::parse($lote->validade) : null;
                            $dias   = $valDt ? (int)$hoje->diffInDays($valDt, false) : null;
                            $vbg    = $dias === null ? '#f3f4f6' : ($dias < 0 ? '#fee2e2' : ($dias <= 90 ? '#fef3c7' : '#d1fae5'));
                            $vc     = $dias === null ? '#374151' : ($dias < 0 ? '#991b1b' : ($dias <= 90 ? '#92400e' : '#065f46'));
                        @endphp
                        <div style="padding:8px 12px;background:{{ $vbg }};border-radius:9px;font-size:13px;font-weight:600;color:{{ $vc }};">
                            {{ $valDt ? $valDt->format('d/m/Y') : '—' }}
                            @if($dias !== null)
                                <span style="font-size:11px;opacity:.8;">
                                    ({{ $dias < 0 ? 'Expirado' : $dias.' dias' }})
                                </span>
                            @endif
                        </div>
                    </div>
                    @php
                        $movs = \DB::table('estoque')->where('lote_id', $lote->id)->get();
                        $stockLote = $movs->sum('entrada') - $movs->sum('saida');
                    @endphp
                    <div>
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#9ca3af;margin-bottom:4px;">Stock Actual</div>
                        <div style="padding:8px 12px;background:#f0faf2;border-radius:9px;border:1px solid #d1fae5;font-size:18px;font-weight:800;color:#1a6b2f;">
                            {{ $stockLote }} <span style="font-size:11px;font-weight:500;color:#6b7280;">unidades</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function checkValidade(input) {
    const warn = document.getElementById('val-warn');
    const warnText = document.getElementById('val-warn-text');
    if (!input.value) { warn.classList.remove('show'); return; }
    const hoje = new Date(); hoje.setHours(0,0,0,0);
    const val  = new Date(input.value);
    const diff = Math.round((val - hoje) / (1000*60*60*24));
    if (diff < 0) {
        warnText.textContent = 'Atenção: esta data já expirou!';
        warn.style.cssText = 'background:#fee2e2;color:#991b1b;';
        warn.classList.add('show');
    } else if (diff <= 90) {
        warnText.textContent = 'Atenção: expira em ' + diff + ' dias.';
        warn.style.cssText = 'background:#fef3c7;color:#92400e;';
        warn.classList.add('show');
    } else {
        warn.classList.remove('show');
    }
}
// Verificar validade ao carregar
window.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('validade_input');
    if (input && input.value) checkValidade(input);
});
</script>

@endsection
