@extends('louyout.app')
@section('conteodo')

<div class="page-header-bar">
    <div>
        <h4 class="page-title"><i class="feather icon-inbox"></i> Requisições de Fármacos</h4>
        <p class="page-sub">Requisições recebidas de outros departamentos</p>
    </div>
    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
        @php $pendentes = $requisicoes->where('estado','pendente')->count(); @endphp
        @if($pendentes > 0)
            <span class="stat-badge orange">⏳ {{ $pendentes }} pendente(s)</span>
        @endif
        <button onclick="document.getElementById('painelRelatorio').classList.toggle('hidden')" class="qa-btn outline-teal">
            <i class="feather icon-bar-chart-2"></i> Relatório por Data
        </button>
    </div>
</div>

{{-- Painel de Relatório por Data --}}
<div id="painelRelatorio" class="hidden" style="margin-bottom:20px;">
    <div class="rel-painel">
        <h6 style="font-size:13px;font-weight:700;color:#0e7490;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
            <i class="feather icon-bar-chart-2"></i> Gerar Relatório de Requisições
        </h6>
        <form action="{{ route('relatorio.requisicoes-farmaco') }}" method="POST" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
            @csrf
            <div>
                <label class="form-label" style="font-size:11px;">Data Início</label>
                <input type="date" name="data_inicio" class="form-control" value="{{ date('Y-m-01') }}" required style="width:150px;">
            </div>
            <div>
                <label class="form-label" style="font-size:11px;">Data Fim</label>
                <input type="date" name="data_fim" class="form-control" value="{{ date('Y-m-d') }}" required style="width:150px;">
            </div>
            <div>
                <label class="form-label" style="font-size:11px;">Estado</label>
                <select name="estado" class="form-control" style="width:140px;">
                    <option value="">Todos</option>
                    <option value="pendente">Pendente</option>
                    <option value="atendida">Atendida</option>
                    <option value="rejeitada">Rejeitada</option>
                </select>
            </div>
            <button type="submit" class="qa-btn teal" style="margin-bottom:1px;">
                <i class="feather icon-download"></i> Descarregar PDF
            </button>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert-success-bar"><i class="feather icon-check-circle"></i> {{ session('success') }}</div>
@endif

<div class="table-card">
    <div class="table-responsive">
        <table class="sys-table" id="dataTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Departamento</th>
                    <th>Solicitante</th>
                    <th>Data</th>
                    <th>Estado</th>
                    <th>Atendido por</th>
                    <th>Acções</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requisicoes as $r)
                <tr>
                    <td>{{ $r->id }}</td>
                    <td>{{ $r->dep_nome }}</td>
                    <td>{{ $r->solicitante }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->created_at)->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($r->estado === 'pendente')
                            <span class="status-badge status-pending">⏳ Pendente</span>
                        @elseif($r->estado === 'atendida')
                            <span class="status-badge status-ok">✅ Atendida</span>
                        @else
                            <span class="status-badge status-blocked">❌ Rejeitada</span>
                        @endif
                    </td>
                    <td>{{ $r->atendente ?? '—' }}</td>
                    <td>
                        <div style="display:flex;gap:4px;flex-wrap:wrap;">
                            <a href="{{ route('requisicao-farmaco.pdf', $r->id) }}"
                               class="tbl-btn tbl-info" title="Descarregar PDF">
                                <i class="feather icon-download"></i>
                            </a>
                            @if($r->estado === 'pendente')
                            <form action="{{ route('requisicao-farmaco.atender', $r->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="acao" value="atendida">
                                <button type="submit" class="tbl-btn tbl-ok" title="Atender"
                                        onclick="return confirm('Confirmar atendimento desta requisição?')">
                                    <i class="feather icon-check"></i>
                                </button>
                            </form>
                            <form action="{{ route('requisicao-farmaco.atender', $r->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="acao" value="rejeitada">
                                <button type="submit" class="tbl-btn tbl-del" title="Rejeitar"
                                        onclick="return confirm('Rejeitar esta requisição?')">
                                    <i class="feather icon-x"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;color:#9ca3af;padding:30px;">Nenhuma requisição encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
.page-header-bar{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:10px;}
.page-title{font-size:20px;font-weight:700;color:#1a2e1a;margin:0;}
.page-sub{font-size:13px;color:#6b7280;margin:3px 0 0;}
.stat-badge{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:20px;font-size:13px;font-weight:600;}
.stat-badge.orange{background:#fef3c7;color:#92400e;}
.qa-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:9px;font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;transition:.2s;}
.alert-success-bar{background:#d1fae5;color:#065f46;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:13px;font-weight:500;display:flex;align-items:center;gap:8px;}
.table-card{background:#fff;border-radius:14px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.05);}
.sys-table{width:100%;border-collapse:collapse;font-size:13px;}
.sys-table thead th{padding:11px 14px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#1a6b2f;background:#f0faf2;border-bottom:2px solid #d1fae5;white-space:nowrap;}
.sys-table tbody td{padding:10px 14px;color:#374151;border-bottom:1px solid #f3f4f6;vertical-align:middle;}
.sys-table tbody tr:last-child td{border-bottom:none;}
.sys-table tbody tr:hover td{background:#f0faf2;}
.status-badge{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;}
.status-pending{background:#fef3c7;color:#92400e;}
.status-ok{background:#d1fae5;color:#065f46;}
.status-blocked{background:#fee2e2;color:#991b1b;}
.tbl-btn{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:7px;border:none;cursor:pointer;font-size:13px;transition:.2s;}
.tbl-btn:hover{opacity:.8;}
.tbl-ok{background:#d1fae5;color:#065f46;}
.tbl-del{background:#fee2e2;color:#991b1b;}
.tbl-info{background:#ede9fe;color:#5b21b6;}
.hidden{display:none!important;}
.qa-btn.outline-teal{background:transparent;color:#0e7490;border:1.5px solid #0e7490;}
.qa-btn.outline-teal:hover{background:#0e7490;color:#fff;}
.qa-btn.teal{background:#0e7490;color:#fff;border:none;}
.qa-btn.teal:hover{background:#0891b2;}
.rel-painel{background:#ecfeff;border:1px solid #a5f3fc;border-radius:12px;padding:16px 20px;}

</style>
@endsection
