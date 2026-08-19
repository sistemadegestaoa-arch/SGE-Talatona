@extends('louyout.app')
@section('conteodo')

@php
$perfilLabel = [
    'admin'      => ['label'=>'Administrador',          'cor'=>'#065f46', 'bg'=>'#d1fae5', 'icone'=>'icon-shield'],
    'armazem'    => ['label'=>'Armazém Central',        'cor'=>'#1d4ed8', 'bg'=>'#dbeafe', 'icone'=>'icon-package'],
    'farmacia'   => ['label'=>'Técnico de Farmácia',    'cor'=>'#5b21b6', 'bg'=>'#ede9fe', 'icone'=>'icon-shopping-bag'],
    'laboratorio'=> ['label'=>'Técnico de Laboratório / Diagnóstico', 'cor'=>'#0e7490', 'bg'=>'#ecfeff', 'icone'=>'icon-activity'],
    'medico'     => ['label'=>$departamento->departamento ?? 'Clínico', 'cor'=>'#991b1b', 'bg'=>'#fee2e2', 'icone'=>'icon-heart'],
    'triagem'    => ['label'=>'Triagem / Catalogação',  'cor'=>'#92400e', 'bg'=>'#fef3c7', 'icone'=>'icon-clipboard'],
][$perfil] ?? ['label'=>ucfirst($perfil), 'cor'=>'#374151', 'bg'=>'#f3f4f6', 'icone'=>'icon-user'];
@endphp

<style>
.pg-wrap { max-width:100%; }
.pg-header { display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px; }
.pg-title  { font-size:20px;font-weight:700;color:#1a2e1a;margin:0; }
.pg-sub    { font-size:13px;color:#6b7280;margin:3px 0 0; }

/* Stats grid */
.stats-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(160px,1fr));
    gap:12px;
    margin-bottom:24px;
}
.stat-box {
    border-radius:16px;
    border:1px solid #e5e7eb;
    background:#fff;
    padding:18px 16px 14px;
    box-shadow:0 2px 8px rgba(0,0,0,.04);
    transition:transform .2s, box-shadow .2s;
}
.stat-box:hover { transform:translateY(-3px); box-shadow:0 6px 20px rgba(0,0,0,.1); }
.stat-icon-wrap {
    width:36px; height:36px; border-radius:10px;
    display:flex; align-items:center; justify-content:center;
    font-size:16px; margin-bottom:12px;
}
.stat-num  { font-size:28px; font-weight:900; line-height:1; }
.stat-lbl  { font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#9ca3af; margin-top:4px; }

/* Layout */
.perfil-layout {
    display:grid;
    grid-template-columns:300px 1fr;
    gap:20px;
    align-items:start;
}

/* Card */
.p-card { background:#fff;border-radius:16px;border:1px solid #e5e7eb;box-shadow:0 2px 10px rgba(0,0,0,.05);overflow:hidden;margin-bottom:20px; }
.p-card-header { display:flex;align-items:center;gap:10px;padding:14px 22px;background:#f0faf2;border-bottom:2px solid #d1fae5; }
.p-card-header i    { font-size:15px;color:#1a6b2f; }
.p-card-header span { font-size:13px;font-weight:700;color:#1a6b2f; }
.p-card-body { padding:22px; }

/* Avatar */
.avatar-wrap { display:flex;flex-direction:column;align-items:center;padding:28px 22px 20px;text-align:center;border-bottom:1px solid #f3f4f6; }
.avatar { width:90px;height:90px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:34px;font-weight:700;color:#fff;box-shadow:0 6px 20px rgba(26,107,47,.3);margin-bottom:14px; }
.avatar-name  { font-size:16px;font-weight:700;color:#1a2e1a;margin-bottom:4px; }
.avatar-email { font-size:12px;color:#6b7280;margin-bottom:10px; }
.perfil-badge { display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:700; }

/* Info list */
.info-list { list-style:none;padding:0;margin:0; }
.info-list li { display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid #f3f4f6;font-size:13px; }
.info-list li:last-child { border-bottom:none;padding-bottom:0; }
.info-list li i { font-size:15px;color:#1a6b2f;width:20px;flex-shrink:0; }
.il-label { font-size:11px;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:.4px;display:block;margin-bottom:1px; }
.il-val   { font-size:13px;color:#1a2e1a;font-weight:600; }

/* Form */
.fg { margin-bottom:16px; }
.fg:last-child { margin-bottom:0; }
.fg label { display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#374151;margin-bottom:6px; }
.fc { width:100%;padding:10px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:13px;color:#1a2332;background:#f9fafb;outline:none;transition:border-color .2s,box-shadow .2s;font-family:'Inter',sans-serif; }
.fc:focus { border-color:#1a6b2f;background:#fff;box-shadow:0 0 0 3px rgba(26,107,47,.1); }
.row-2 { display:grid;grid-template-columns:1fr 1fr;gap:14px; }
.btn-save { display:inline-flex;align-items:center;gap:7px;padding:10px 22px;border:none;border-radius:10px;background:linear-gradient(135deg,#1a6b2f,#2d9e4a);color:#fff;font-size:13px;font-weight:700;cursor:pointer;transition:opacity .2s,transform .1s;font-family:'Inter',sans-serif; }
.btn-save:hover { opacity:.9;transform:translateY(-1px); }

/* Histórico */
.hist-table { width:100%;border-collapse:collapse;font-size:13px; }
.hist-table th { padding:9px 12px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.4px;color:#1a6b2f;background:#f0faf2;border-bottom:2px solid #d1fae5;white-space:nowrap; }
.hist-table td { padding:10px 12px;border-bottom:1px solid #f3f4f6;color:#374151;vertical-align:middle; }
.hist-table tr:last-child td { border-bottom:none; }
.hist-table tr:hover td { background:#f9fafb; }

/* Flash */
.flash { display:flex;align-items:center;gap:10px;padding:13px 18px;border-radius:12px;font-size:13px;font-weight:500;margin-bottom:20px; }
.flash-ok  { background:#d1fae5;border:1px solid #6ee7b7;color:#065f46; }
.flash-err { background:#fee2e2;border:1px solid #fca5a5;color:#991b1b; }

@media(max-width:900px) {
    .perfil-layout { grid-template-columns:1fr; }
    .stats-grid { grid-template-columns:1fr 1fr; }
    .row-2 { grid-template-columns:1fr; }
}
</style>

<div class="pg-wrap">

<div class="pg-header">
    <div>
        <h4 class="pg-title"><i class="feather icon-user" style="color:#1a6b2f;margin-right:8px;"></i>Meu Perfil</h4>
        <p class="pg-sub">{{ $perfilLabel['label'] }} — {{ $departamento->departamento ?? '—' }}</p>
    </div>
</div>

@if(session('success'))
    <div class="flash flash-ok"><i class="feather icon-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('success_senha'))
    <div class="flash flash-ok"><i class="feather icon-check-circle"></i> {{ session('success_senha') }}</div>
@endif
@if(session('error_senha'))
    <div class="flash flash-err"><i class="feather icon-alert-circle"></i> {{ session('error_senha') }}</div>
@endif

{{-- ESTATÍSTICAS CONTEXTUAIS --}}
<div class="stats-grid">
    @foreach($stats as $s)
    <div class="stat-box">
        <div class="stat-icon-wrap" style="background:{{ $s['bg'] }};color:{{ $s['cor'] }};">
            <i class="feather {{ $s['icone'] }}"></i>
        </div>
        <div class="stat-num" style="color:{{ $s['cor'] }};">{{ $s['valor'] }}</div>
        <div class="stat-lbl">{{ $s['label'] }}</div>
    </div>
    @endforeach
</div>

<div class="perfil-layout">

    {{-- COLUNA ESQUERDA — identidade --}}
    <div>
        <div class="p-card">
            <div class="avatar-wrap">
                <div class="avatar" style="background:linear-gradient(135deg,{{ $perfilLabel['cor'] }},#2d9e4a);">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="avatar-name">{{ $user->name }}</div>
                <div class="avatar-email">{{ $user->email }}</div>
                <span class="perfil-badge"
                      style="background:{{ $perfilLabel['bg'] }};color:{{ $perfilLabel['cor'] }};">
                    <i class="feather {{ $perfilLabel['icone'] }}" style="font-size:11px;"></i>
                    {{ $perfilLabel['label'] }}
                </span>
            </div>
            <div class="p-card-body">
                <ul class="info-list">
                    <li>
                        <i class="feather icon-home"></i>
                        <div>
                            <span class="il-label">Departamento</span>
                            <span class="il-val">{{ $departamento->departamento ?? '—' }}</span>
                        </div>
                    </li>
                    <li>
                        <i class="feather {{ $perfilLabel['icone'] }}"></i>
                        <div>
                            <span class="il-label">Função</span>
                            <span class="il-val">{{ $perfilLabel['label'] }}</span>
                        </div>
                    </li>
                    <li>
                        <i class="feather icon-calendar"></i>
                        <div>
                            <span class="il-label">Membro desde</span>
                            <span class="il-val">{{ $user->created_at ? $user->created_at->format('d/m/Y') : '—' }}</span>
                        </div>
                    </li>
                    <li>
                        <i class="feather icon-clock"></i>
                        <div>
                            <span class="il-label">Último acesso</span>
                            <span class="il-val">{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : '—' }}</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- COLUNA DIREITA --}}
    <div>

        {{-- Editar nome --}}
        <div class="p-card">
            <div class="p-card-header">
                <i class="feather icon-edit-2"></i>
                <span>Informações Pessoais</span>
            </div>
            <div class="p-card-body">
                <form action="{{ route('perfil.nome') }}" method="POST">
                    @csrf
                    <div class="row-2">
                        <div class="fg">
                            <label>Nome completo</label>
                            <input type="text" name="name" class="fc" value="{{ $user->name }}" required>
                        </div>
                        <div class="fg">
                            <label>Email</label>
                            <input type="email" class="fc" value="{{ $user->email }}" disabled
                                   style="background:#f3f4f6;color:#9ca3af;cursor:not-allowed;">
                        </div>
                    </div>
                    @error('name')
                        <p style="color:#ef4444;font-size:12px;margin-top:-8px;margin-bottom:12px;">{{ $message }}</p>
                    @enderror
                    <button type="submit" class="btn-save">
                        <i class="feather icon-save"></i> Guardar
                    </button>
                </form>
            </div>
        </div>

        {{-- Alterar senha --}}
        <div class="p-card" id="seguranca">
            <div class="p-card-header">
                <i class="feather icon-lock"></i>
                <span>Segurança — Alterar Senha</span>
            </div>
            <div class="p-card-body">
                <form action="{{ route('perfil.senha') }}" method="POST">
                    @csrf
                    <div class="fg">
                        <label>Senha Actual</label>
                        <input type="password" name="senha_actual" class="fc" required placeholder="A sua senha actual">
                    </div>
                    <div class="row-2">
                        <div class="fg">
                            <label>Nova Senha</label>
                            <input type="password" name="nova_senha" class="fc" required placeholder="Mínimo 6 caracteres">
                        </div>
                        <div class="fg">
                            <label>Confirmar Nova Senha</label>
                            <input type="password" name="nova_senha_confirmation" class="fc" required placeholder="Repita a nova senha">
                        </div>
                    </div>
                    @error('nova_senha')
                        <p style="color:#ef4444;font-size:12px;margin-top:-8px;margin-bottom:12px;">{{ $message }}</p>
                    @enderror
                    <button type="submit" class="btn-save" style="background:linear-gradient(135deg,#dc2626,#ef4444);">
                        <i class="feather icon-lock"></i> Alterar Senha
                    </button>
                </form>
            </div>
        </div>

        {{-- Histórico contextual --}}
        @if($historico->isNotEmpty())
        <div class="p-card">
            <div class="p-card-header">
                <i class="feather icon-clock"></i>
                <span>{{ $historicoTitulo }}</span>
            </div>
            <div class="p-card-body" style="padding:0;">
                <table class="hist-table">
                    <thead>
                        <tr>
                            <th>Data</th>
                            @if(in_array($perfil, ['admin','armazem','farmacia']))
                                <th>Produto</th>
                                <th>Tipo</th>
                                <th>Qtd</th>
                            @elseif($perfil === 'laboratorio')
                                <th>Exame</th>
                                <th>Paciente</th>
                                <th>Prior.</th>
                            @elseif($perfil === 'medico')
                                <th>Paciente</th>
                                <th>Diagnóstico</th>
                                <th>Estado</th>
                            @elseif($perfil === 'triagem')
                                <th>Paciente</th>
                                <th>Dados</th>
                                <th>Estado</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($historico as $h)
                        <tr>
                            <td style="white-space:nowrap;color:#9ca3af;font-size:12px;">
                                {{ \Carbon\Carbon::parse($h->data)->format('d/m/Y') }}
                            </td>

                            @if(in_array($perfil, ['admin','armazem','farmacia']))
                                <td style="font-weight:600;">{{ $h->produto }}</td>
                                <td>
                                    @if($h->situacao === 'Entrada')
                                        <span style="background:#d1fae5;color:#065f46;padding:2px 9px;border-radius:20px;font-size:11px;font-weight:700;">
                                            <i class="feather icon-arrow-down" style="font-size:10px;"></i> Entrada
                                        </span>
                                    @else
                                        <span style="background:#fee2e2;color:#991b1b;padding:2px 9px;border-radius:20px;font-size:11px;font-weight:700;">
                                            <i class="feather icon-arrow-up" style="font-size:10px;"></i> Saída
                                        </span>
                                    @endif
                                </td>
                                <td style="font-weight:800;color:#1a2e1a;">{{ $h->entrada > 0 ? $h->entrada : $h->saida }}</td>

                            @elseif($perfil === 'laboratorio')
                                <td style="font-weight:600;">🔬 {{ $h->produto }}</td>
                                <td>{{ $h->paciente }}</td>
                                <td>
                                    @if($h->urgente)
                                        <span style="background:#fee2e2;color:#991b1b;padding:2px 8px;border-radius:20px;font-size:10px;font-weight:800;">⚡ URG</span>
                                    @else
                                        <span style="background:#f0faf2;color:#1a6b2f;padding:2px 8px;border-radius:20px;font-size:10px;font-weight:700;">Normal</span>
                                    @endif
                                </td>

                            @elseif($perfil === 'medico')
                                <td style="font-weight:600;">{{ $h->paciente }}</td>
                                <td style="color:#6b7280;font-style:italic;">
                                    {{ $h->produto ? \Str::limit($h->produto, 50) : '—' }}
                                </td>
                                <td>
                                    @php
                                        $cl = ['em_espera'=>['bg'=>'#fef3c7','cor'=>'#92400e','txt'=>'Em Espera'],
                                               'em_consulta'=>['bg'=>'#dbeafe','cor'=>'#1d4ed8','txt'=>'Consulta'],
                                               'concluido'=>['bg'=>'#d1fae5','cor'=>'#065f46','txt'=>'Concluído']][$h->estado ?? '']
                                              ?? ['bg'=>'#f3f4f6','cor'=>'#6b7280','txt'=>ucfirst($h->estado ?? '—')];
                                    @endphp
                                    <span style="background:{{ $cl['bg'] }};color:{{ $cl['cor'] }};padding:2px 8px;border-radius:20px;font-size:11px;font-weight:700;">
                                        {{ $cl['txt'] }}
                                    </span>
                                </td>

                            @elseif($perfil === 'triagem')
                                <td style="font-weight:600;">{{ $h->paciente }}</td>
                                <td style="color:#6b7280;">{{ $h->produto }}</td>
                                <td>
                                    @php
                                        $cl = ['em_espera'=>['bg'=>'#fef3c7','cor'=>'#92400e','txt'=>'Espera'],
                                               'em_consulta'=>['bg'=>'#dbeafe','cor'=>'#1d4ed8','txt'=>'Consulta'],
                                               'concluido'=>['bg'=>'#d1fae5','cor'=>'#065f46','txt'=>'✓']][$h->estado ?? '']
                                              ?? ['bg'=>'#f3f4f6','cor'=>'#6b7280','txt'=>'—'];
                                    @endphp
                                    <span style="background:{{ $cl['bg'] }};color:{{ $cl['cor'] }};padding:2px 8px;border-radius:20px;font-size:11px;font-weight:700;">
                                        {{ $cl['txt'] }}
                                    </span>
                                    @if(!empty($h->urgente))
                                        <span style="background:#fee2e2;color:#991b1b;padding:2px 6px;border-radius:20px;font-size:10px;font-weight:800;margin-left:4px;">⚡</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</div>
</div>

@endsection
