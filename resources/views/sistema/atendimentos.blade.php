@extends('louyout.app')
@section('conteodo')
@include('louyout.styles')
@include('louyout.flash')

<style>
.stat-strip { display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:14px; margin-bottom:24px; }
.stat-box { background:#fff; border-radius:14px; border:1px solid #e5e7eb; padding:18px 20px; box-shadow:0 2px 8px rgba(0,0,0,.04); display:flex; align-items:center; gap:14px; }
.stat-box .sb-icon { width:42px; height:42px; border-radius:11px; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
.stat-box .sb-val  { font-size:22px; font-weight:800; color:#1a2e1a; line-height:1; }
.stat-box .sb-lbl  { font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#9ca3af; margin-top:2px; }
.search-bar { display:flex; align-items:center; gap:10px; padding:14px 18px; border-bottom:1px solid #f3f4f6; }
.search-bar input { flex:1; padding:8px 14px; border:1.5px solid #e5e7eb; border-radius:9px; font-size:13px; outline:none; font-family:'Inter',sans-serif; }
.search-bar input:focus { border-color:#1a6b2f; box-shadow:0 0 0 3px rgba(26,107,47,.1); }
</style>

@php
    $total  = $atendimentos->total();
@endphp

<div class="page-header-bar">
    <div>
        <h4 class="page-title"><i class="feather icon-user-check" style="color:#1a6b2f;margin-right:8px;"></i>Atendimentos</h4>
        <p class="page-sub">Histórico de dispensas ao utente</p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
        {{-- Relatório por período --}}
        <form action="{{ route('atendimento.relatorio') }}" method="GET" style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
            <input type="date" name="data1" class="fc" value="{{ date('Y-m-01') }}"
                   style="padding:7px 10px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:12px;outline:none;font-family:'Inter',sans-serif;">
            <input type="date" name="data2" class="fc" value="{{ date('Y-m-d') }}"
                   style="padding:7px 10px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:12px;outline:none;font-family:'Inter',sans-serif;">
            <button type="submit" style="display:inline-flex;align-items:center;gap:5px;padding:8px 14px;background:#f0faf2;border:1.5px solid #1a6b2f;border-radius:9px;color:#1a6b2f;font-size:12px;font-weight:600;cursor:pointer;">
                <i class="feather icon-printer"></i> Relatório PDF
            </button>
        </form>
        <a href="{{ route('atendimento.create') }}" class="btn-new">
            <i class="feather icon-plus-circle"></i> Novo Atendimento
        </a>
    </div>
</div>

<div class="stat-strip">
    <div class="stat-box">
        <div class="sb-icon" style="background:#d1fae5;"><i class="feather icon-user-check" style="color:#1a6b2f;"></i></div>
        <div><div class="sb-val">{{ $total }}</div><div class="sb-lbl">Total</div></div>
    </div>
    <div class="stat-box">
        <div class="sb-icon" style="background:#dbeafe;"><i class="feather icon-calendar" style="color:#1d4ed8;"></i></div>
        <div><div class="sb-val" style="color:#1d4ed8;">{{ $hoje }}</div><div class="sb-lbl">Hoje</div></div>
    </div>
</div>

<div class="table-card">
    <div class="search-bar">
        <i class="feather icon-search" style="color:#9ca3af;font-size:15px;"></i>
        <input type="text" id="searchInput" placeholder="Pesquisar utente, processo...">
    </div>
    <div class="table-responsive">
        <table class="sys-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Utente</th>
                    <th>Processo</th>
                    <th>Técnico</th>
                    <th>Data</th>
                    <th>Itens</th>
                    <th>Acções</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($atendimentos as $a)
                @php
                    $nItens = DB::table('atendimento_item')->where('atendimento_id',$a->id)->count();
                @endphp
                <tr>
                    <td style="color:#9ca3af;font-size:12px;">{{ $a->id }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:9px;">
                            <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#1a6b2f,#2d9e4a);display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;font-weight:700;flex-shrink:0;">
                                {{ strtoupper(substr($a->utente,0,1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;color:#1a2e1a;">{{ $a->utente }}</div>
                                @if($a->observacao)
                                <div style="font-size:11px;color:#9ca3af;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $a->observacao }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td><span class="code-badge">{{ $a->processo ?? '—' }}</span></td>
                    <td style="font-size:12px;color:#6b7280;">{{ $a->tecnico }}</td>
                    <td style="font-size:13px;">{{ \Carbon\Carbon::parse($a->data)->format('d/m/Y') }}</td>
                    <td>
                        <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;background:#d1fae5;color:#065f46;border-radius:20px;font-size:11px;font-weight:700;">
                            <i class="feather icon-package" style="font-size:10px;"></i> {{ $nItens }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            <a href="{{ route('atendimento.show', $a->id) }}" class="tbl-btn tbl-view" title="Ver detalhe">
                                <i class="feather icon-eye"></i>
                            </a>
                            <a href="{{ route('atendimento.pdf', $a->id) }}" class="tbl-btn tbl-info" title="Imprimir" target="_blank">
                                <i class="feather icon-printer"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:48px;color:#9ca3af;">
                        <i class="feather icon-user-check" style="font-size:36px;display:block;margin-bottom:10px;"></i>
                        Nenhum atendimento registado.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:16px 18px;border-top:1px solid #f3f4f6;">
        {{ $atendimentos->links() }}
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#tableBody tr').forEach(r => r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none');
});
</script>

@endsection
