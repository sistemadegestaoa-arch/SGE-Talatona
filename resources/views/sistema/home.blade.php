@extends('louyout.app')
@section('conteodo')

@php
    $totalProdutos   = \DB::table('produto')->count();
    $totalLotes      = \DB::table('lote')->count();
    $totalFornecedor = \DB::table('fornecedor')->count();
    $totalDepa       = \DB::table('departamento')->count();
    $totalUsers      = \DB::table('users')->count();
    $requisPendentes = \DB::table('requisicao')->where('statos','Pendente')->count();
    $estoqueMinimo   = \DB::table('produto')->whereRaw('quantidade <= stokminimo')->count();

    // Últimas movimentações
    $movimentos = \DB::table('estoque')
        ->join('produto','produto.id','=','estoque.produto_id')
        ->select('produto.produto','estoque.situacao','estoque.entrada','estoque.saida','estoque.data','estoque.qfinal')
        ->orderByDesc('estoque.id')
        ->limit(8)
        ->get();

    // Lotes a expirar nos próximos 90 dias
    $aExpirar = \DB::table('lote')
        ->join('produto','produto.id','=','lote.produto_id')
        ->select('produto.produto','lote.lote','lote.validade')
        ->whereNotNull('lote.validade')
        ->whereRaw("lote.validade <= DATE_ADD(CURDATE(), INTERVAL 90 DAY)")
        ->whereRaw("lote.validade >= CURDATE()")
        ->orderBy('lote.validade')
        ->limit(5)
        ->get();
@endphp

<div class="dashboard-wrap">

    <!-- Saudação -->
    <div class="dash-header">
        <div>
            <h1 class="dash-title">Olá, {{ Auth::user()->name }} 👋</h1>
            <p class="dash-sub">{{ \Carbon\Carbon::now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }} &mdash; Visão geral do sistema</p>
        </div>
        <a href="{{ route('relatorio.index') }}" class="btn-green-outline">
            <i class="feather icon-bar-chart-2"></i> Relatórios
        </a>
    </div>

    <!-- Cards de estatísticas -->
    <div class="stats-grid">

        <div class="stat-card green-dark">
            <div class="stat-icon"><i class="feather icon-package"></i></div>
            <div class="stat-info">
                <span class="stat-num">{{ $totalProdutos }}</span>
                <span class="stat-label">Fármacos</span>
            </div>
            <a href="{{ route('produto.verp') }}" class="stat-link">Ver todos →</a>
        </div>

        <div class="stat-card green-light">
            <div class="stat-icon"><i class="feather icon-layers"></i></div>
            <div class="stat-info">
                <span class="stat-num">{{ $totalLotes }}</span>
                <span class="stat-label">Lotes</span>
            </div>
            <a href="{{ route('ver-lotes.index') }}" class="stat-link">Ver todos →</a>
        </div>

        <div class="stat-card teal">
            <div class="stat-icon"><i class="feather icon-truck"></i></div>
            <div class="stat-info">
                <span class="stat-num">{{ $totalFornecedor }}</span>
                <span class="stat-label">Fornecedores</span>
            </div>
            <a href="{{ route('fornecedor.index') }}" class="stat-link">Ver todos →</a>
        </div>

        <div class="stat-card orange {{ $requisPendentes > 0 ? 'pulse' : '' }}">
            <div class="stat-icon"><i class="feather icon-bell"></i></div>
            <div class="stat-info">
                <span class="stat-num">{{ $requisPendentes }}</span>
                <span class="stat-label">Requisições pendentes</span>
            </div>
            @if(Auth::user()->tipo == 'admin')
            <a href="{{ route('verrequisicao.showr') }}" class="stat-link">Atender →</a>
            @endif
        </div>

        <div class="stat-card red {{ $estoqueMinimo > 0 ? 'pulse' : '' }}">
            <div class="stat-icon"><i class="feather icon-alert-triangle"></i></div>
            <div class="stat-info">
                <span class="stat-num">{{ $estoqueMinimo }}</span>
                <span class="stat-label">Estoque abaixo do mínimo</span>
            </div>
            <a href="{{ route('estoqueminimo.estoqueminimo') }}" class="stat-link">Ver →</a>
        </div>

        @if(Auth::user()->tipo == 'admin')
        <div class="stat-card slate">
            <div class="stat-icon"><i class="feather icon-users"></i></div>
            <div class="stat-info">
                <span class="stat-num">{{ $totalUsers }}</span>
                <span class="stat-label">Utilizadores</span>
            </div>
            <a href="{{ route('verusuario.show') }}" class="stat-link">Gerir →</a>
        </div>
        @endif

    </div>

    <!-- Ações rápidas -->
    <div class="section-title">Ações rápidas</div>
    <div class="quick-actions">
        @if(Auth::user()->tipo == 'admin')
        <a href="{{ route('createproduto.registar') }}" class="qa-btn green-dark">
            <i class="feather icon-plus-circle"></i> Novo Fármaco
        </a>
        <a href="{{ route('lote.create') }}" class="qa-btn green-light">
            <i class="feather icon-layers"></i> Novo Lote
        </a>
        <a href="{{ route('createfornecedor.create') }}" class="qa-btn teal">
            <i class="feather icon-truck"></i> Novo Fornecedor
        </a>
        <a href="{{ route('createusuario.registar') }}" class="qa-btn slate">
            <i class="feather icon-user-plus"></i> Novo Utilizador
        </a>
        @endif
        <a href="{{ route('requisicao.requisicao') }}" class="qa-btn orange">
            <i class="feather icon-file-text"></i> Nova Requisição
        </a>
        <a href="{{ route('relatorio.index') }}" class="qa-btn outline">
            <i class="feather icon-bar-chart-2"></i> Relatórios
        </a>
    </div>

    <!-- Tabela + Alertas -->
    <div class="dash-bottom">

        <!-- Últimas movimentações -->
        <div class="dash-card">
            <div class="dash-card-header">
                <span><i class="feather icon-activity"></i> Últimas movimentações</span>
            </div>
            <div class="table-wrap">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Tipo</th>
                            <th>Qtd</th>
                            <th>Saldo</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movimentos as $m)
                        <tr>
                            <td>{{ $m->produto }}</td>
                            <td>
                                <span class="badge-mov {{ $m->situacao == 'Entrada' ? 'badge-entrada' : 'badge-saida' }}">
                                    {{ $m->situacao }}
                                </span>
                            </td>
                            <td>{{ $m->situacao == 'Entrada' ? $m->entrada : $m->saida }}</td>
                            <td>{{ $m->qfinal }}</td>
                            <td>{{ $m->data }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="empty-row">Nenhuma movimentação registada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Lotes a expirar -->
        <div class="dash-card">
            <div class="dash-card-header warn">
                <span><i class="feather icon-clock"></i> Lotes a expirar (90 dias)</span>
            </div>
            <div class="table-wrap">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Lote</th>
                            <th>Validade</th>
                            <th>Dias</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aExpirar as $l)
                        @php
                            $dias = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($l->validade), false);
                        @endphp
                        <tr>
                            <td>{{ $l->produto }}</td>
                            <td>{{ $l->lote }}</td>
                            <td>{{ \Carbon\Carbon::parse($l->validade)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge-dias {{ $dias <= 30 ? 'badge-critico' : 'badge-aviso' }}">
                                    {{ $dias }}d
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="empty-row">Nenhum lote a expirar em breve.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="padding:12px 16px;">
                <a href="{{ route('expirados.expirados') }}" class="btn-green-outline" style="font-size:12px;">
                    Ver relatório completo →
                </a>
            </div>
        </div>

    </div>
</div>

<style>
/* ── DASHBOARD ── */
.dashboard-wrap { padding: 24px 8px 40px; }

.dash-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 12px;
}

.dash-title { font-size: 22px; font-weight: 700; color: #1a2e1a; margin: 0; }
.dash-sub   { font-size: 13px; color: #6b7280; margin: 4px 0 0; text-transform: capitalize; }

/* ── STATS GRID ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
}

.stat-card {
    border-radius: 14px;
    padding: 20px 18px 14px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    position: relative;
    overflow: hidden;
    transition: transform .2s, box-shadow .2s;
    cursor: default;
}

.stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }

.stat-card.green-dark  { background: linear-gradient(135deg,#1a6b2f,#2d9e4a); color:#fff; }
.stat-card.green-light { background: linear-gradient(135deg,#3aad5e,#5dd87e); color:#fff; }
.stat-card.teal        { background: linear-gradient(135deg,#0d7a6b,#14b89e); color:#fff; }
.stat-card.orange      { background: linear-gradient(135deg,#c0620a,#f08030); color:#fff; }
.stat-card.red         { background: linear-gradient(135deg,#b91c1c,#ef4444); color:#fff; }
.stat-card.slate       { background: linear-gradient(135deg,#334155,#64748b); color:#fff; }

.stat-icon { font-size: 28px; opacity: .85; }
.stat-icon i { font-size: 28px; }

.stat-num   { font-size: 32px; font-weight: 700; line-height: 1; }
.stat-label { font-size: 12px; opacity: .85; font-weight: 500; }
.stat-info  { display: flex; flex-direction: column; gap: 2px; }

.stat-link {
    font-size: 11px;
    color: rgba(255,255,255,.8);
    text-decoration: none;
    margin-top: 6px;
    font-weight: 600;
    letter-spacing: .3px;
}
.stat-link:hover { color: #fff; }

/* Pulse para alertas */
@keyframes pulse-ring {
    0%   { box-shadow: 0 0 0 0 rgba(255,255,255,.4); }
    70%  { box-shadow: 0 0 0 10px rgba(255,255,255,0); }
    100% { box-shadow: 0 0 0 0 rgba(255,255,255,0); }
}
.stat-card.pulse { animation: pulse-ring 2s infinite; }

/* ── AÇÕES RÁPIDAS ── */
.section-title {
    font-size: 13px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: .8px;
    margin-bottom: 12px;
}

.quick-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 28px;
}

.qa-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 18px;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: opacity .2s, transform .1s;
    white-space: nowrap;
}
.qa-btn:hover { opacity: .88; transform: translateY(-1px); text-decoration: none; }

.qa-btn.green-dark  { background: #1a6b2f; color: #fff; }
.qa-btn.green-light { background: #3aad5e; color: #fff; }
.qa-btn.teal        { background: #0d7a6b; color: #fff; }
.qa-btn.orange      { background: #c0620a; color: #fff; }
.qa-btn.slate       { background: #334155; color: #fff; }
.qa-btn.outline     { background: transparent; color: #1a6b2f; border: 2px solid #1a6b2f; }
.qa-btn.outline:hover { background: #1a6b2f; color: #fff; }

/* ── BOTTOM GRID ── */
.dash-bottom {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

@media (max-width: 900px) {
    .dash-bottom { grid-template-columns: 1fr; }
}

.dash-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
}

.dash-card-header {
    padding: 14px 18px;
    font-size: 14px;
    font-weight: 600;
    color: #1a2e1a;
    background: #f0faf2;
    border-bottom: 1px solid #d1fae5;
    display: flex;
    align-items: center;
    gap: 8px;
}

.dash-card-header.warn {
    background: #fffbeb;
    border-bottom-color: #fde68a;
    color: #78350f;
}

.dash-card-header i { color: #1a6b2f; }
.dash-card-header.warn i { color: #d97706; }

.table-wrap { overflow-x: auto; }

.dash-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.dash-table thead th {
    padding: 10px 14px;
    text-align: left;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #6b7280;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.dash-table tbody td {
    padding: 10px 14px;
    color: #374151;
    border-bottom: 1px solid #f3f4f6;
}

.dash-table tbody tr:last-child td { border-bottom: none; }
.dash-table tbody tr:hover td { background: #f0faf2; }

.empty-row { text-align: center; color: #9ca3af; padding: 24px !important; }

.badge-mov {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}
.badge-entrada { background: #d1fae5; color: #065f46; }
.badge-saida   { background: #fee2e2; color: #991b1b; }

.badge-dias {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}
.badge-critico { background: #fee2e2; color: #991b1b; }
.badge-aviso   { background: #fef3c7; color: #92400e; }

/* Botão outline verde */
.btn-green-outline {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border: 2px solid #1a6b2f;
    border-radius: 9px;
    color: #1a6b2f;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: background .2s, color .2s;
}
.btn-green-outline:hover { background: #1a6b2f; color: #fff; text-decoration: none; }

/* Responsivo geral */
@media (max-width: 600px) {
    .stats-grid { grid-template-columns: 1fr 1fr; }
    .dash-title { font-size: 18px; }
}
</style>

@endsection
