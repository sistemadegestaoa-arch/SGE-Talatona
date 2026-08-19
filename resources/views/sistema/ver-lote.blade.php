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

        .val-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .val-ok {
            background: #d1fae5;
            color: #065f46;
        }

        .val-warn {
            background: #fef3c7;
            color: #92400e;
        }

        .val-expired {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>

    @php
        $hoje = \Carbon\Carbon::today();
        $totalLotes = $lote->count();
        $expirados = $lote
            ->filter(
                fn($l) => $l->departamento_id == auth()->user()->departamento_id &&
                    $l->validade &&
                    \Carbon\Carbon::parse($l->validade)->isPast(),
            )
            ->count();
        $aExpirar = $lote
            ->filter(
                fn($l) => $l->departamento_id == auth()->user()->departamento_id &&
                    $l->validade &&
                    !\Carbon\Carbon::parse($l->validade)->isPast() &&
                    \Carbon\Carbon::parse($l->validade)->diffInDays($hoje) <= 90,
            )
            ->count();
        $meusDep = $lote->filter(fn($l) => $l->departamento_id == auth()->user()->departamento_id)->count();
    @endphp

    <div class="page-header-bar">
        <div>
            <h4 class="page-title"><i class="feather icon-layers" style="color:#1a6b2f;margin-right:8px;"></i>Lotes</h4>
            <p class="page-sub">Lotes registados no seu departamento</p>
        </div>
        <a href="{{ route('lote.create') }}" class="btn-new">
            <i class="feather icon-plus-circle"></i> Novo Lote
        </a>
    </div>

    @if (isset($sms))
        <div class="{{ str_contains(strtolower($sms), 'sucesso') ? 'alert-ok' : 'alert-err' }}">{{ $sms }}</div>
    @endif

    {{-- STATS --}}
    <div class="stat-strip">
        <div class="stat-box">
            <div class="sb-icon" style="background:#d1fae5;">
                <i class="feather icon-layers" style="color:#1a6b2f;"></i>
            </div>
            <div>
                <div class="sb-val">{{ $meusDep }}</div>
                <div class="sb-lbl">Total Lotes</div>
            </div>
        </div>
        <div class="stat-box">
            <div class="sb-icon" style="background:#fef3c7;">
                <i class="feather icon-alert-triangle" style="color:#92400e;"></i>
            </div>
            <div>
                <div class="sb-val" style="color:#92400e;">{{ $aExpirar }}</div>
                <div class="sb-lbl">A Expirar</div>
            </div>
        </div>
        <div class="stat-box">
            <div class="sb-icon" style="background:#fee2e2;">
                <i class="feather icon-x-circle" style="color:#991b1b;"></i>
            </div>
            <div>
                <div class="sb-val" style="color:#991b1b;">{{ $expirados }}</div>
                <div class="sb-lbl">Expirados</div>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="search-bar">
            <i class="feather icon-search" style="color:#9ca3af;font-size:15px;"></i>
            <input type="text" id="searchInput" placeholder="Pesquisar lote, produto, validade...">
        </div>
        <div class="table-responsive">
            <table class="sys-table" id="dataTable">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Produto</th>
                        <th>Lote</th>
                        <th>Código de Barras</th>
                        <th>Validade</th>
                        <th>Estado</th>
                        <th style="width:80px;">Acções</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @php $cont = 0; @endphp
                    @forelse($lote as $l)
                        @php
                            $cont++;
                            $diasVal = null;
                            $valClass = 'val-ok';
                            $valLabel = 'Válido';
                            if ($l->validade) {
                                $valDate = \Carbon\Carbon::parse($l->validade);
                                $diasVal = (int) $hoje->diffInDays($valDate, false);
                                if ($diasVal < 0) {
                                    $valClass = 'val-expired';
                                    $valLabel = 'Expirado';
                                } elseif ($diasVal <= 90) {
                                    $valClass = 'val-warn';
                                    $valLabel = $diasVal . ' dias';
                                } else {
                                    $valClass = 'val-ok';
                                    $valLabel = 'Válido';
                                }
                            }
                        @endphp
                        <tr>
                            <td style="color:#9ca3af;font-size:12px;">{{ $cont }}</td>
                            <td>
                                <div style="font-weight:600;color:#1a2e1a;">{{ $l->produto }}</div>
                            </td>
                            <td>
                                <span class="code-badge"
                                    style="font-size:12px;font-family:monospace;">{{ $l->lote }}</span>
                            </td>
                            <td>
                                @if ($l->codigo_barra)
                                    <span class="code-badge">{{ $l->codigo_barra }}</span>
                                @else
                                    <span style="color:#d1d5db;">—</span>
                                @endif
                            </td>
                            <td style="font-size:13px;">
                                {{ $l->validade ? \Carbon\Carbon::parse($l->validade)->format('d/m/Y') : '—' }}
                            </td>
                            <td>
                                @if ($l->validade)
                                    <span class="val-badge {{ $valClass }}">
                                        <i class="feather {{ $diasVal < 0 ? 'icon-x-circle' : ($diasVal <= 90 ? 'icon-alert-triangle' : 'icon-check-circle') }}"
                                            style="font-size:10px;"></i>
                                        {{ $valLabel }}
                                    </span>
                                @else
                                    <span style="color:#d1d5db;font-size:12px;">—</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('estoque.estoque', $l->id) }}" class="tbl-btn tbl-info"
                                    title="Gerir Stock">
                                    <i class="feather icon-activity"></i>
                                </a>
                                <a href="{{ route('editarlote.edit', $l->id) }}" class="tbl-btn tbl-edit"
                                    title="Editar Lote">
                                    <i class="feather icon-edit-2"></i>
                                </a>
                                @if(\App\Helpers\PermissaoHelper::podeEliminar())
                                <form action="{{ route('apagarlote.destroy', $l->id) }}" method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Eliminar o lote {{ addslashes($l->lote) }}?')">
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
                            <td colspan="7" style="text-align:center;padding:40px;color:#9ca3af;">
                                <i class="feather icon-layers" style="font-size:32px;display:block;margin-bottom:8px;"></i>
                                Nenhum lote registado.
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
