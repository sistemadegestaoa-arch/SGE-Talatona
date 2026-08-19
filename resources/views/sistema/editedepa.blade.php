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
.btn-submit { width:100%; padding:12px; border:none; border-radius:10px; background:linear-gradient(135deg,#1a6b2f,#2d9e4a); color:#fff; font-size:14px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:opacity .2s; font-family:'Inter',sans-serif; }
.btn-submit:hover { opacity:.9; }
@media(max-width:768px){ .edit-layout{ grid-template-columns:1fr; } }
</style>

<div class="page-header-bar">
    <div>
        <h4 class="page-title"><i class="feather icon-edit-2" style="color:#1a6b2f;margin-right:8px;"></i>Editar Departamento</h4>
        <p class="page-sub">Actualize o nome do departamento</p>
    </div>
    <a href="{{ route('departamento.index') }}" class="btn-back">
        <i class="feather icon-arrow-left"></i> Voltar
    </a>
</div>


<div class="edit-layout">
    <div>
        <div class="f-card">
            <div class="f-card-header">
                <i class="feather icon-grid"></i>
                <span>Dados do Departamento</span>
            </div>
            <div class="f-card-body">
                <form action="{{ route('updatedepa.update', $depa->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="fg">
                        <label>Nome do Departamento <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="departamento" class="fc"
                               value="{{ old('departamento', $depa->departamento) }}" required
                               placeholder="Ex: Farmácia">
                        @error('departamento')
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
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#9ca3af;margin-bottom:4px;">ID</div>
                        <span style="display:inline-flex;align-items:center;gap:6px;padding:5px 12px;background:#f3f4f6;border-radius:8px;font-size:12px;font-weight:600;color:#374151;">
                            <i class="feather icon-hash" style="font-size:11px;"></i> {{ $depa->id }}
                        </span>
                    </div>
                    <div>
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#9ca3af;margin-bottom:4px;">Nome Actual</div>
                        <div style="padding:8px 12px;background:#f0faf2;border-radius:9px;border:1px solid #d1fae5;font-size:13px;font-weight:600;color:#1a2e1a;">
                            {{ $depa->departamento }}
                        </div>
                    </div>
                    @php
                        $nUsers = \DB::table('users')->where('departamento_id', $depa->id)->count();
                    @endphp
                    <div>
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#9ca3af;margin-bottom:4px;">Utilizadores</div>
                        <div style="padding:8px 12px;background:#f0faf2;border-radius:9px;border:1px solid #d1fae5;font-size:13px;font-weight:700;color:#1a6b2f;">
                            {{ $nUsers }} utilizador{{ $nUsers != 1 ? 'es' : '' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
