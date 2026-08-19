@extends('louyout.app')
@section('conteodo')
    @include('louyout.styles')
    @include('louyout.flash')

    <style>
        .stat-strip {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }

        .stat-box {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            padding: 18px 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .stat-box .sb-icon {
            width: 42px;
            height: 42px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .stat-box .sb-val {
            font-size: 22px;
            font-weight: 800;
            color: #1a2e1a;
            line-height: 1;
        }

        .stat-box .sb-lbl {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #9ca3af;
            margin-top: 2px;
        }

        .search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 18px;
            border-bottom: 1px solid #f3f4f6;
        }

        .search-bar input {
            flex: 1;
            padding: 8px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 9px;
            font-size: 13px;
            outline: none;
            font-family: 'Inter', sans-serif;
        }

        .search-bar input:focus {
            border-color: #1a6b2f;
            box-shadow: 0 0 0 3px rgba(26, 107, 47, .1);
        }

        .fornec-avatar {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: linear-gradient(135deg, #1a6b2f, #2d9e4a);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .contact-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: #6b7280;
        }

        .contact-chip i {
            font-size: 11px;
            color: #9ca3af;
        }
    </style>

    @php
        $total = $Fornecedor->count();
    @endphp

    <div class="page-header-bar">
        <div>
            <h4 class="page-title"><i class="feather icon-truck" style="color:#1a6b2f;margin-right:8px;"></i>Fornecedores</h4>
            <p class="page-sub">Gerencie os fornecedores do sistema</p>
        </div>
        <a href="{{ route('createfornecedor.create') }}" class="btn-new">
            <i class="feather icon-plus-circle"></i> Novo Fornecedor
        </a>
    </div>

    {{-- STATS --}}
    <div class="stat-strip">
        <div class="stat-box">
            <div class="sb-icon" style="background:#d1fae5;">
                <i class="feather icon-truck" style="color:#1a6b2f;"></i>
            </div>
            <div>
                <div class="sb-val">{{ $total }}</div>
                <div class="sb-lbl">Fornecedores</div>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="search-bar">
            <i class="feather icon-search" style="color:#9ca3af;font-size:15px;"></i>
            <input type="text" id="searchInput" placeholder="Pesquisar fornecedor, NIF, email...">
        </div>
        <div class="table-responsive">
            <table class="sys-table" id="dataTable">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Fornecedor</th>
                        <th>Contacto</th>
                        <th>NIF</th>
                        <th>Endereço</th>
                        <th style="width:60px;">Acções</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($Fornecedor as $i => $f)
                        <tr>
                            <td style="color:#9ca3af;font-size:12px;">{{ $i + 1 }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div class="fornec-avatar">{{ strtoupper(substr($f->fornecedor, 0, 1)) }}</div>
                                    <div>
                                        <div style="font-weight:600;color:#1a2e1a;font-size:13px;">{{ $f->fornecedor }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="display:flex;flex-direction:column;gap:3px;">
                                    @if ($f->telefone)
                                        <span class="contact-chip"><i
                                                class="feather icon-phone"></i>{{ $f->telefone }}</span>
                                    @endif
                                    @if ($f->email)
                                        <span class="contact-chip"><i
                                                class="feather icon-mail"></i>{{ $f->email }}</span>
                                    @endif
                                </div>
                            </td>
                            <td><span class="code-badge">{{ $f->nif ?? '—' }}</span></td>
                            <td
                                style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#6b7280;font-size:12px;">
                                {{ $f->endereco ?? '—' }}
                            </td>
                            <td>
                                @if(\App\Helpers\PermissaoHelper::podeEliminar())
                                    <form action="{{ route('apagarfornecedor.destroy', $f->id) }}" method="post"
                                        onsubmit="return confirm('Eliminar o fornecedor {{ addslashes($f->fornecedor) }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="tbl-btn tbl-del" title="Eliminar">
                                            <i class="feather icon-trash-2"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;padding:40px;color:#9ca3af;">
                                <i class="feather icon-truck" style="font-size:32px;display:block;margin-bottom:8px;"></i>
                                Nenhum fornecedor registado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#tableBody tr').forEach(function(row) {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    </script>

@endsection
