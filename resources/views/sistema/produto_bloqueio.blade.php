@extends('louyout.app')
@section('conteodo')

<div class="page-header-bar">
    <div>
        <h4 class="page-title"><i class="feather icon-lock"></i> Bloqueio de Fármacos</h4>
        <p class="page-sub">Gerir bloqueio, desbloqueio e monitorização de stock</p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
        <span class="stat-badge red"><i class="feather icon-lock"></i> Bloqueados: {{ $totalBloqueados }}</span>
        <span class="stat-badge green"><i class="feather icon-unlock"></i> Disponíveis: {{ $totalDesbloqueados }}</span>
        @if($totalStockBaixo > 0)
        <span class="stat-badge orange"><i class="feather icon-alert-triangle"></i> Stock Baixo: {{ $totalStockBaixo }}</span>
        @endif
    </div>
</div>

@if($totalStockBaixo > 0)
<div class="alerta-stock">
    <i class="feather icon-alert-triangle" style="font-size:18px;flex-shrink:0;"></i>
    <div>
        <strong>{{ $totalStockBaixo }} fármaco(s) com stock no mínimo ou abaixo.</strong>
        Estes fármacos ainda estão disponíveis mas podem não ser dispensados se o stock for insuficiente.
        Solicite reposição ao Armazém.
    </div>
</div>
@endif

{{-- Filtro rápido --}}
<div class="filter-bar">
    <button class="filter-btn active" data-filter="all">Todos</button>
    <button class="filter-btn" data-filter="bloqueado">⛔ Bloqueados</button>
    <button class="filter-btn" data-filter="stock_baixo">⚠️ Stock Baixo</button>
    <button class="filter-btn" data-filter="disponivel">✅ Disponíveis</button>
    <input type="text" id="searchInput" class="filter-search" placeholder="Pesquisar fármaco...">
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="sys-table" id="tblBloqueio">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fármaco</th>
                    <th>Apresentação</th>
                    <th>Categoria</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Motivo</th>
                    <th>Bloqueado por</th>
                    <th>Data</th>
                    <th>Acção</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produtos as $p)
                @php
                    $stockBaixo = !$p->bloqueado && $p->quantidade <= $p->stokminimo;
                    $statusFilter = $p->bloqueado ? 'bloqueado' : ($stockBaixo ? 'stock_baixo' : 'disponivel');
                @endphp
                <tr class="prod-row" data-status="{{ $statusFilter }}">
                    <td>{{ $p->id }}</td>
                    <td><strong>{{ $p->produto }}</strong></td>
                    <td>{{ $p->apresentacao }}</td>
                    <td>{{ $p->categoria }}</td>
                    <td>
                        @if($p->bloqueado)
                            <span class="qty-badge qty-blocked">—</span>
                        @elseif($stockBaixo)
                            <span class="qty-badge qty-low" title="Mínimo: {{ $p->stokminimo }}">
                                {{ $p->quantidade }} <i class="feather icon-alert-triangle" style="font-size:10px;"></i>
                            </span>
                        @else
                            <span class="qty-badge qty-ok">{{ $p->quantidade }}</span>
                        @endif
                    </td>
                    <td>
                        @if($p->bloqueado)
                            <span class="status-badge status-blocked"><i class="feather icon-lock"></i> Bloqueado</span>
                        @elseif($stockBaixo)
                            <span class="status-badge status-warning"><i class="feather icon-alert-triangle"></i> Stock Baixo</span>
                        @else
                            <span class="status-badge status-ok"><i class="feather icon-unlock"></i> Disponível</span>
                        @endif
                    </td>
                    <td style="max-width:180px;font-size:12px;color:#6b7280;">
                        @if($p->bloqueado)
                            {{ $p->motivo_bloqueio ?? '—' }}
                        @elseif($stockBaixo)
                            <span style="color:#92400e;">Stock: {{ $p->quantidade }} / Mín: {{ $p->stokminimo }}</span>
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ $p->bloqueado_por_nome ?? '—' }}</td>
                    <td>{{ $p->bloqueado_em ? \Carbon\Carbon::parse($p->bloqueado_em)->format('d/m/Y H:i') : '—' }}</td>
                    <td>
                        @if($p->bloqueado)
                            {{-- Desbloquear (só farmácia/armazém) --}}
                            <form action="{{ route('produto-bloqueio.desbloquear', $p->id) }}" method="POST"
                                  onsubmit="return confirm('Desbloquear o fármaco &quot;{{ addslashes($p->produto) }}&quot;?')">
                                @csrf
                                <button type="submit" class="tbl-btn tbl-unlock" title="Desbloquear">
                                    <i class="feather icon-unlock"></i>
                                </button>
                            </form>
                        @else
                            {{-- Bloquear --}}
                            <button type="button" class="tbl-btn tbl-lock" title="Bloquear"
                                    onclick="abrirModal({{ $p->id }}, '{{ addslashes($p->produto) }}')">
                                <i class="feather icon-lock"></i>
                            </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modal de Bloqueio --}}
<div id="modalBloqueio" class="modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h5><i class="feather icon-lock"></i> Bloquear Fármaco</h5>
            <button onclick="fecharModal()" class="modal-close">&times;</button>
        </div>
        <form id="formBloqueio" method="POST">
            @csrf
            <div class="modal-body">
                <div class="modal-aviso">
                    <i class="feather icon-alert-triangle"></i>
                    <div>
                        <strong>Atenção:</strong> Ao bloquear este fármaco, nenhum departamento
                        poderá prescrevê-lo, requisitá-lo ou movimentá-lo até ser desbloqueado
                        pela Farmácia ou Armazém.
                    </div>
                </div>
                <p style="margin-bottom:12px;font-size:13px;">A bloquear: <strong id="modalNomeProduto"></strong></p>
                <label class="form-label">Motivo do bloqueio <span style="color:#dc2626">*</span></label>
                <textarea name="motivo" class="form-control" rows="3"
                          placeholder="Ex: Produto fora de validade, recall do fabricante, contaminação..."
                          required maxlength="255"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="fecharModal()" class="btn-cancel">Cancelar</button>
                <button type="submit" class="btn-confirm">
                    <i class="feather icon-lock"></i> Confirmar Bloqueio
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.page-header-bar{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:10px;}
.page-title{font-size:20px;font-weight:700;color:#1a2e1a;margin:0;}
.page-sub{font-size:13px;color:#6b7280;margin:3px 0 0;}
.stat-badge{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:20px;font-size:13px;font-weight:600;}
.stat-badge.red{background:#fee2e2;color:#991b1b;}
.stat-badge.green{background:#d1fae5;color:#065f46;}
.stat-badge.orange{background:#fef3c7;color:#92400e;}

/* Alerta de stock global */
.alerta-stock{display:flex;align-items:flex-start;gap:12px;background:#fefce8;border:1px solid #fde68a;border-radius:12px;padding:14px 18px;margin-bottom:16px;font-size:13px;color:#92400e;}

/* Filtros */
.filter-bar{display:flex;align-items:center;gap:8px;margin-bottom:16px;flex-wrap:wrap;}
.filter-btn{padding:6px 16px;border-radius:20px;border:1.5px solid #d1d5db;background:#fff;font-size:12px;font-weight:600;cursor:pointer;color:#374151;transition:.2s;}
.filter-btn.active,.filter-btn:hover{background:#1a6b2f;border-color:#1a6b2f;color:#fff;}
.filter-search{margin-left:auto;padding:7px 14px;border-radius:9px;border:1.5px solid #e5e7eb;font-size:13px;min-width:220px;}
.filter-search:focus{outline:none;border-color:#2d9e4a;box-shadow:0 0 0 3px rgba(45,158,74,.12);}

/* Tabela */
.table-card{background:#fff;border-radius:14px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.05);}
.sys-table{width:100%;border-collapse:collapse;font-size:13px;}
.sys-table thead th{padding:11px 14px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#1a6b2f;background:#f0faf2;border-bottom:2px solid #d1fae5;white-space:nowrap;}
.sys-table tbody td{padding:10px 14px;color:#374151;border-bottom:1px solid #f3f4f6;vertical-align:middle;}
.sys-table tbody tr:last-child td{border-bottom:none;}
.sys-table tbody tr:hover td{background:#f9fafb;}
.sys-table tbody tr[data-status="bloqueado"]:hover td{background:#fff5f5;}
.sys-table tbody tr[data-status="stock_baixo"]:hover td{background:#fffbeb;}

/* Badges de estado */
.status-badge{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;}
.status-blocked{background:#fee2e2;color:#991b1b;}
.status-ok{background:#d1fae5;color:#065f46;}
.status-warning{background:#fef3c7;color:#92400e;}

/* Stock badges */
.qty-badge{display:inline-flex;align-items:center;gap:4px;padding:2px 9px;border-radius:20px;font-size:12px;font-weight:700;}
.qty-ok{background:#d1fae5;color:#065f46;}
.qty-low{background:#fef3c7;color:#92400e;}
.qty-blocked{background:#f3f4f6;color:#9ca3af;}

/* Botões */
.tbl-btn{display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:none;cursor:pointer;font-size:13px;transition:.2s;}
.tbl-btn:hover{opacity:.8;}
.tbl-lock{background:#fee2e2;color:#991b1b;}
.tbl-unlock{background:#d1fae5;color:#065f46;}

/* Modal */
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;display:flex;align-items:center;justify-content:center;padding:16px;}
.modal-box{background:#fff;border-radius:16px;width:100%;max-width:480px;box-shadow:0 20px 60px rgba(0,0,0,.2);overflow:hidden;}
.modal-header{display:flex;align-items:center;justify-content:space-between;padding:16px 20px;background:#fff5f5;border-bottom:1px solid #fecaca;}
.modal-header h5{margin:0;font-size:15px;font-weight:700;color:#991b1b;}
.modal-close{background:none;border:none;font-size:22px;cursor:pointer;color:#6b7280;line-height:1;}
.modal-body{padding:20px;}
.modal-aviso{display:flex;align-items:flex-start;gap:10px;background:#fef3c7;border:1px solid #fde68a;border-radius:10px;padding:12px 14px;margin-bottom:16px;font-size:12px;color:#92400e;}
.modal-footer{padding:14px 20px;border-top:1px solid #f3f4f6;display:flex;justify-content:flex-end;gap:10px;}
.form-label{font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;}
.form-control{width:100%;padding:9px 12px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:13px;resize:vertical;font-family:'Inter',sans-serif;}
.form-control:focus{outline:none;border-color:#1a6b2f;box-shadow:0 0 0 3px rgba(26,107,47,.1);}
.btn-cancel{padding:8px 18px;border-radius:9px;border:1.5px solid #d1d5db;background:#fff;font-size:13px;font-weight:600;cursor:pointer;color:#374151;}
.btn-cancel:hover{background:#f3f4f6;}
.btn-confirm{padding:8px 18px;border-radius:9px;border:none;background:#dc2626;color:#fff;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;}
.btn-confirm:hover{background:#b91c1c;}
</style>

<script>
var baseUrl  = '{{ url("produto-bloqueio") }}';
var csrfMeta = document.querySelector('meta[name="csrf-token"]');
var csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '{{ csrf_token() }}';

function abrirModal(id, nome) {
    document.getElementById('modalNomeProduto').textContent = nome;
    document.getElementById('formBloqueio').action = baseUrl + '/' + id + '/bloquear';
    document.getElementById('modalBloqueio').style.display = 'flex';
    setTimeout(function(){ document.querySelector('#modalBloqueio textarea[name="motivo"]').value = ''; document.querySelector('#modalBloqueio textarea[name="motivo"]').focus(); }, 100);
}

function fecharModal() {
    document.getElementById('modalBloqueio').style.display = 'none';
}

document.getElementById('modalBloqueio').addEventListener('click', function(e){
    if (e.target === this) fecharModal();
});

// ── Filtros ────────────────────────────────────────────────────────────────
document.querySelectorAll('.filter-btn').forEach(function(btn){
    btn.addEventListener('click', function(){
        document.querySelectorAll('.filter-btn').forEach(function(b){ b.classList.remove('active'); });
        this.classList.add('active');
        var f = this.dataset.filter;
        document.querySelectorAll('.prod-row').forEach(function(row){
            if (f === 'all' || row.dataset.status === f) row.style.display = '';
            else row.style.display = 'none';
        });
    });
});

// ── Pesquisa ───────────────────────────────────────────────────────────────
document.getElementById('searchInput').addEventListener('input', function(){
    var q = this.value.toLowerCase();
    document.querySelectorAll('.prod-row').forEach(function(row){
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>

@endsection
