@extends('louyout.app')
@section('conteodo')

<div class="page-header-bar">
    <div>
        <h4 class="page-title"><i class="feather icon-user-plus"></i> Novo Utilizador</h4>
        <p class="page-sub">Preencha os dados para criar uma nova conta</p>
    </div>
    <a href="{{ route('verusuario.show') }}" class="btn-back">
        <i class="feather icon-arrow-left"></i> Voltar
    </a>
</div>

{{-- Flash de erro de validação --}}
@if($errors->any())
<div class="flash flash-err" id="flash-err">
    <i class="feather icon-alert-circle"></i>
    <div>
        <strong>Corrija os erros abaixo:</strong>
        <ul style="margin:4px 0 0 16px;padding:0;">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    <button onclick="document.getElementById('flash-err').remove()" class="flash-close">&times;</button>
</div>
@endif

<div class="reg-layout">

    {{-- Painel lateral informativo --}}
    <div class="reg-side">
        <div class="reg-side-icon"><i class="feather icon-user-check"></i></div>
        <h3>Criar conta</h3>
        <p>Defina o perfil e o departamento do novo utilizador. Após o registo, ele poderá aceder ao sistema com as credenciais criadas.</p>
        <div class="reg-tips">
            <div class="tip"><i class="feather icon-shield"></i><span><strong>Admin</strong> — acesso total ao sistema</span></div>
            <div class="tip"><i class="feather icon-user"></i><span><strong>Utilizador</strong> — acesso restrito ao seu departamento</span></div>
        </div>
    </div>

    {{-- Formulário --}}
    <div class="reg-form-wrap">
        <form method="POST" action="{{ route('createusuario.create') }}" id="regForm">
            @csrf

            {{-- Nome --}}
            <div class="fg">
                <label><i class="feather icon-user"></i> Nome completo</label>
                <input type="text" name="name" class="fc @error('name') fc-err @enderror"
                       value="{{ old('name') }}" placeholder="Ex: Maria Silva" required autofocus>
                @error('name')<span class="fc-msg">{{ $message }}</span>@enderror
            </div>

            {{-- Email --}}
            <div class="fg">
                <label><i class="feather icon-mail"></i> Email</label>
                <input type="email" name="email" class="fc @error('email') fc-err @enderror"
                       value="{{ old('email') }}" placeholder="email@exemplo.com" required>
                @error('email')<span class="fc-msg">{{ $message }}</span>@enderror
            </div>

            {{-- Senha + Confirmar --}}
            <div class="form-row-2">
                <div class="fg">
                    <label><i class="feather icon-lock"></i> Senha</label>
                    <div class="pw-wrap">
                        <input type="password" name="password" id="pw1"
                               class="fc @error('password') fc-err @enderror"
                               placeholder="Mínimo 6 caracteres" required>
                        <button type="button" class="pw-toggle" onclick="togglePw('pw1',this)">
                            <i class="feather icon-eye"></i>
                        </button>
                    </div>
                    @error('password')<span class="fc-msg">{{ $message }}</span>@enderror
                </div>
                <div class="fg">
                    <label><i class="feather icon-lock"></i> Confirmar senha</label>
                    <div class="pw-wrap">
                        <input type="password" name="password_confirmation" id="pw2"
                               class="fc" placeholder="Repita a senha" required>
                        <button type="button" class="pw-toggle" onclick="togglePw('pw2',this)">
                            <i class="feather icon-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Perfil + Departamento --}}
            <div class="form-row-2">
                <div class="fg">
                    <label><i class="feather icon-shield"></i> Perfil de acesso</label>
                    <select name="tipo" class="fc" required>
                        <option value="">Selecione...</option>
                        <option value="user"  {{ old('tipo')=='user'  ? 'selected' : '' }}>Utilizador Normal</option>
                        <option value="admin" {{ old('tipo')=='admin' ? 'selected' : '' }}>Administrador</option>
                    </select>
                    @error('tipo')<span class="fc-msg">{{ $message }}</span>@enderror
                </div>
                <div class="fg">
                    <label><i class="feather icon-grid"></i> Departamento</label>
                    <select name="departamento_id" class="fc" required>
                        <option value="">Selecione...</option>
                        @foreach ($Dp as $depa)
                            <option value="{{ $depa->id }}" {{ old('departamento_id')==$depa->id ? 'selected' : '' }}>
                                {{ $depa->departamento }}
                            </option>
                        @endforeach
                    </select>
                    @error('departamento_id')<span class="fc-msg">{{ $message }}</span>@enderror
                </div>
            </div>

            {{-- Preview do perfil seleccionado --}}
            <div class="role-preview" id="rolePreview" style="display:none;">
                <i class="feather icon-info" style="color:#1a6b2f;"></i>
                <span id="roleText"></span>
            </div>

            <div style="display:flex;gap:10px;margin-top:8px;flex-wrap:wrap;">
                <button type="submit" class="btn-save">
                    <i class="feather icon-user-plus"></i> Criar Utilizador
                </button>
                <a href="{{ route('verusuario.show') }}" class="btn-cancel">
                    <i class="feather icon-x"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

</div>

<style>
.page-header-bar { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap; gap:10px; }
.page-title { font-size:20px; font-weight:700; color:#1a2e1a; margin:0; }
.page-sub   { font-size:13px; color:#6b7280; margin:3px 0 0; }

.btn-back {
    display:inline-flex; align-items:center; gap:6px;
    padding:8px 16px; border:2px solid #1a6b2f; border-radius:9px;
    color:#1a6b2f; font-size:13px; font-weight:600; text-decoration:none;
    transition:background .2s, color .2s;
}
.btn-back:hover { background:#1a6b2f; color:#fff; text-decoration:none; }

/* Flash */
.flash {
    display:flex; align-items:flex-start; gap:10px;
    padding:13px 16px; border-radius:10px; font-size:13px;
    margin-bottom:18px; animation:slideIn .3s ease;
}
@keyframes slideIn { from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:translateY(0)} }
.flash-err { background:#fef2f2; border:1px solid #fca5a5; color:#991b1b; }
.flash-close { margin-left:auto; background:none; border:none; font-size:18px; cursor:pointer; color:inherit; opacity:.6; }
.flash-close:hover { opacity:1; }

/* Layout 2 colunas */
.reg-layout {
    display:grid;
    grid-template-columns: 280px 1fr;
    gap:24px;
    align-items:start;
}

/* Painel lateral */
.reg-side {
    background:linear-gradient(145deg,#1a6b2f,#0f3d1e);
    border-radius:14px; padding:28px 22px; color:#fff;
}
.reg-side-icon {
    width:52px; height:52px; border-radius:14px;
    background:rgba(255,255,255,.15);
    display:flex; align-items:center; justify-content:center;
    font-size:24px; margin-bottom:16px;
}
.reg-side h3 { font-size:18px; font-weight:700; margin:0 0 10px; }
.reg-side p  { font-size:13px; color:rgba(255,255,255,.75); line-height:1.6; margin:0 0 20px; }
.reg-tips { display:flex; flex-direction:column; gap:10px; }
.tip { display:flex; align-items:center; gap:10px; font-size:12px; color:rgba(255,255,255,.85); }
.tip i { font-size:16px; opacity:.8; flex-shrink:0; }

/* Form */
.reg-form-wrap {
    background:#fff; border-radius:14px;
    border:1px solid #e5e7eb; padding:28px 24px;
    box-shadow:0 2px 8px rgba(0,0,0,.05);
}

.fg { margin-bottom:16px; }
.fg label {
    display:flex; align-items:center; gap:6px;
    font-size:12px; font-weight:600; color:#374151;
    margin-bottom:6px; text-transform:uppercase; letter-spacing:.4px;
}
.fg label i { font-size:13px; color:#1a6b2f; }

.fc {
    width:100%; padding:10px 14px;
    border:1.5px solid #e5e7eb; border-radius:9px;
    font-size:13px; color:#1a2332; background:#f9fafb;
    transition:border-color .2s, box-shadow .2s; outline:none;
}
.fc:focus { border-color:#1a6b2f; background:#fff; box-shadow:0 0 0 3px rgba(26,107,47,.1); }
.fc-err   { border-color:#ef4444 !important; }
.fc-msg   { display:block; font-size:11px; color:#ef4444; margin-top:4px; }

.form-row-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }

/* Password toggle */
.pw-wrap { position:relative; }
.pw-wrap .fc { padding-right:42px; }
.pw-toggle {
    position:absolute; right:12px; top:50%; transform:translateY(-50%);
    background:none; border:none; cursor:pointer; color:#9ca3af;
    font-size:15px; padding:0; transition:color .2s;
}
.pw-toggle:hover { color:#1a6b2f; }

/* Role preview */
.role-preview {
    display:flex; align-items:center; gap:8px;
    padding:10px 14px; background:#f0faf2;
    border:1px solid #d1fae5; border-radius:9px;
    font-size:13px; color:#1a6b2f; margin-bottom:16px;
    animation:slideIn .2s ease;
}

/* Buttons */
.btn-save {
    display:inline-flex; align-items:center; gap:7px;
    padding:11px 24px; background:#1a6b2f; color:#fff;
    border:none; border-radius:9px; font-size:14px;
    font-weight:600; cursor:pointer; transition:background .2s;
}
.btn-save:hover { background:#2d9e4a; }
.btn-cancel {
    display:inline-flex; align-items:center; gap:7px;
    padding:11px 20px; background:transparent; color:#6b7280;
    border:1.5px solid #e5e7eb; border-radius:9px; font-size:14px;
    font-weight:600; text-decoration:none; transition:border-color .2s, color .2s;
}
.btn-cancel:hover { border-color:#9ca3af; color:#374151; text-decoration:none; }

@media(max-width:768px) {
    .reg-layout { grid-template-columns:1fr; }
    .reg-side { display:none; }
    .form-row-2 { grid-template-columns:1fr; }
}
</style>

<script>
function togglePw(id, btn) {
    var input = document.getElementById(id);
    var icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'feather icon-eye-off';
    } else {
        input.type = 'password';
        icon.className = 'feather icon-eye';
    }
}

// Preview do perfil seleccionado
document.querySelector('select[name="tipo"]').addEventListener('change', function() {
    var preview = document.getElementById('rolePreview');
    var text    = document.getElementById('roleText');
    var msgs = {
        'admin': 'Este utilizador terá acesso total ao sistema, incluindo gestão de utilizadores e relatórios.',
        'user':  'Este utilizador terá acesso restrito ao seu departamento (fármacos, requisições e relatórios).'
    };
    if (this.value && msgs[this.value]) {
        text.textContent = msgs[this.value];
        preview.style.display = 'flex';
    } else {
        preview.style.display = 'none';
    }
});

// Auto-fechar flash após 5 segundos
setTimeout(function() {
    var f = document.getElementById('flash-err');
    if (f) f.style.opacity = '0', f.style.transition = 'opacity .5s', setTimeout(function(){ f.remove(); }, 500);
}, 5000);
</script>

@endsection
