@extends('louyout.app')
@section('conteodo')

    <div class="page-header-bar">
        <div>
            <h4 class="page-title"><i class="feather icon-users"></i> Utilizadores</h4>
            <p class="page-sub">Gerir contas de acesso ao sistema</p>
        </div>
        <a href="{{ route('createusuario.registar') }}" class="btn-new">
            <i class="feather icon-user-plus"></i> Novo Utilizador
        </a>
    </div>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="flash flash-ok" id="flash-msg">
            <i class="feather icon-check-circle"></i> {{ session('success') }}
            <button onclick="document.getElementById('flash-msg').remove()" class="flash-close">&times;</button>
        </div>
    @endif
    @if (session('error'))
        <div class="flash flash-err" id="flash-msg">
            <i class="feather icon-alert-circle"></i> {{ session('error') }}
            <button onclick="document.getElementById('flash-msg').remove()" class="flash-close">&times;</button>
        </div>
    @endif

    <div class="table-card">
        <div class="table-responsive">
            <table class="sys-table" id="dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Departamento</th>
                        <th>Perfil</th>
                        <th>Acções</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($User as $usuario)
                        <tr>
                            <td>{{ $usuario->id }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div class="avatar-circle">{{ strtoupper(substr($usuario->name, 0, 1)) }}</div>
                                    <strong>{{ $usuario->name }}</strong>
                                </div>
                            </td>
                            <td>
                                <span style="color:#6b7280;font-size:13px;">
                                    <i class="feather icon-mail" style="font-size:12px;margin-right:4px;"></i>
                                    {{ $usuario->email }}
                                </span>
                            </td>
                            <td>
                                <span class="dept-badge">
                                    <i class="feather icon-grid" style="font-size:11px;"></i>
                                    {{ $usuario->departamento }}
                                </span>
                            </td>
                            <td>
                                @if ($usuario->tipo == 'admin')
                                    <span class="role-badge role-admin"><i class="feather icon-shield"></i> Admin</span>
                                @else
                                    <span class="role-badge role-user"><i class="feather icon-user"></i> Utilizador</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('apagaruser.destroy', ['id' => $usuario->id]) }}" method="post"
                                    style="display:inline;"
                                    onsubmit="return confirm('Eliminar o utilizador {{ addslashes($usuario->name) }}?')">
                                    @csrf @method('DELETE')
                                    @if(\App\Helpers\PermissaoHelper::podeEliminar())
                                        <button type="submit" class="tbl-btn tbl-del" title="Eliminar">
                                            <i class="feather icon-trash-2"></i>
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;padding:32px;color:#9ca3af;">
                                <i class="feather icon-users" style="font-size:32px;display:block;margin-bottom:8px;"></i>
                                Nenhum utilizador registado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .page-header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .page-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a2e1a;
            margin: 0;
        }

        .page-sub {
            font-size: 13px;
            color: #6b7280;
            margin: 3px 0 0;
        }

        .btn-new {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            background: #1a6b2f;
            color: #fff;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: background .2s;
        }

        .btn-new:hover {
            background: #2d9e4a;
            color: #fff;
            text-decoration: none;
        }

        /* Flash */
        .flash {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 18px;
            position: relative;
            animation: slideIn .3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .flash-ok {
            background: #ecfdf5;
            border: 1px solid #6ee7b7;
            color: #065f46;
        }

        .flash-err {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        .flash-close {
            margin-left: auto;
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: inherit;
            opacity: .6;
            line-height: 1;
        }

        .flash-close:hover {
            opacity: 1;
        }

        /* Table */
        .table-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
        }

        .sys-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .sys-table thead th {
            padding: 11px 14px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #1a6b2f;
            background: #f0faf2;
            border-bottom: 2px solid #d1fae5;
            white-space: nowrap;
        }

        .sys-table tbody td {
            padding: 11px 14px;
            color: #374151;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }

        .sys-table tbody tr:last-child td {
            border-bottom: none;
        }

        .sys-table tbody tr:hover td {
            background: #f9fafb;
        }

        /* Avatar */
        .avatar-circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1a6b2f, #3aad5e);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Badges */
        .dept-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            background: #f0faf2;
            color: #1a6b2f;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            border: 1px solid #d1fae5;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .role-admin {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .role-user {
            background: #f3f4f6;
            color: #374151;
        }

        /* Action buttons */
        .tbl-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 13px;
            text-decoration: none;
            transition: opacity .2s, transform .1s;
        }

        .tbl-btn:hover {
            opacity: .8;
            transform: scale(1.05);
        }

        .tbl-del {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>

@endsection
