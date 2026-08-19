@extends('louyout.app')
@section('conteodo')

@php
    $hoje = \Carbon\Carbon::today();
    $dep  = \DB::table('departamento')->where('id', auth()->user()->departamento_id)->value('departamento') ?? 'S.O.';

    // Prescrições do dia
    $totalPresc = \DB::table('prescricao')
        ->join('consulta','consulta.id','=','prescricao.consulta_id')
        ->join('episodio','episodio.id','=','consulta.episodio_id')
        ->whereDate('episodio.data', $hoje)
        ->count();

    $prescUrgentes = \DB::table('prescricao')
        ->join('consulta','consulta.id','=','prescricao.consulta_id')
        ->join('episodio','episodio.id','=','consulta.episodio_id')
        ->whereDate('episodio.data', $hoje)
        ->where('episodio.urgente', 1)
        ->count();

    // Requisições pendentes do departamento
    $reqPend = \DB::table('requisicao_farmaco')
        ->where('departamento_id', auth()->user()->departamento_id)
        ->where('estado','pendente')
        ->count();

    // Última prescrição urgente
    $proximaPresc = \DB::table('prescricao')
        ->join('consulta','consulta.id','=','prescricao.consulta_id')
        ->join('episodio','episodio.id','=','consulta.episodio_id')
        ->join('paciente','paciente.id','=','episodio.paciente_id')
        ->join('users','users.id','=','prescricao.medico_id')
        ->whereDate('episodio.data', $hoje)
        ->orderByDesc('episodio.urgente')
        ->orderByDesc('prescricao.id')
        ->select(
            'prescricao.id as prescricao_id',
            'prescricao.diagnostico',
            'episodio.urgente',
            'paciente.nome',
            'paciente.sexo',
            'paciente.data_nascimento',
            'users.name as medico',
            'prescricao.created_at as hora'
        )
        ->first();
@endphp

<style>
.so-wrap  { max-width:100%; }
.so-hdr   { display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:12px; }
.so-title { font-size:22px;font-weight:800;color:#1a2e1a;margin:0; }
.so-sub   { font-size:13px;color:#6b7280;margin:4px 0 0; }
.so-stats { display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px; }
.so-stat  { border-radius:16px;padding:20px 16px 16px;color:#fff;position:relative;overflow:hidden;transition:transform .2s;cursor:default; }
.so-stat:hover { transform:translateY(-3px); }
.so-stat.ss1 { background:linear-gradient(135deg,#1e3a8a,#3b82f6); }
.so-stat.ss2 { background:linear-gradient(135deg,#991b1b,#dc2626); }
.so-stat.ss3 { background:linear-gradient(135deg,#0e7490,#06b6d4); }
.so-stat-num  { font-size:36px;font-weight:900;line-height:1; }
.so-stat-lbl  { font-size:11px;font-weight:600;opacity:.85;margin-top:3px; }
.so-stat-icon { position:absolute;right:12px;top:12px;font-size:30px;opacity:.15; }

.so-banner { border-radius:18px;padding:24px 28px;color:#fff;display:flex;align-items:center;gap:20px;margin-bottom:24px;position:relative;overflow:hidden;box-shadow:0 8px 24px rgba(0,0,0,.2); }
.so-banner.normal  { background:linear-gradient(135deg,#1e3a5f,#1d4ed8); }
.so-banner.urgente { background:linear-gradient(135deg,#7f1d1d,#dc2626); }
.so-banner::before { content:'';position:absolute;right:-30px;top:-30px;width:150px;height:150px;border-radius:50%;background:rgba(255,255,255,.05); }
.so-av { width:58px;height:58px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:900;flex-shrink:0;background:rgba(255,255,255,.2); }
.so-tag { font-size:11px;font-weight:700;opacity:.7;text-transform:uppercase;letter-spacing:.8px;margin-bottom:4px; }
.so-nome { font-size:20px;font-weight:800; }
.so-meta { font-size:13px;opacity:.8;margin-top:5px;display:flex;gap:14px;flex-wrap:wrap; }
.btn-ver-presc { display:inline-flex;align-items:center;gap:8px;padding:13px 26px;background:#fff;border-radius:12px;font-size:14px;font-weight:800;text-decoration:none;flex-shrink:0;box-shadow:0 4px 14px rgba(0,0,0,.15);transition:transform .2s; }
.btn-ver-presc:hover { transform:translateY(-2px);text-decoration:none; }
.btn-ver-presc.blue { color:#1d4ed8; }
.btn-ver-presc.red  { color:#dc2626; }

.so-acoes { display:grid;grid-template-columns:1fr 1fr;gap:16px; }
.so-acao-card { background:#fff;border-radius:16px;border:1px solid #e5e7eb;box-shadow:0 2px 10px rgba(0,0,0,.04);padding:24px;text-decoration:none;color:inherit;transition:box-shadow .2s,transform .2s;display:flex;flex-direction:column;gap:8px; }
.so-acao-card:hover { box-shadow:0 8px 24px rgba(0,0,0,.1);transform:translateY(-3px);text-decoration:none;color:inherit; }
.so-acao-icon { width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;margin-bottom:4px; }
.so-acao-titulo { font-size:15px;font-weight:700;color:#1a2e1a; }
.so-acao-desc   { font-size:13px;color:#6b7280; }
.so-acao-badge  { display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;width:fit-content; }

@media(max-width:700px) { .so-stats,.so-acoes { grid-template-columns:1fr; } }
</style>

<div class="so-wrap">
    <div class="so-hdr">
        <div>
            <h1 class="so-title">🏥 {{ $dep }}</h1>
            <p class="so-sub">{{ $hoje->isoFormat('dddd, D [de] MMMM [de] YYYY') }} · {{ \Carbon\Carbon::now()->format('H:i') }}</p>
        </div>
    </div>

    @include('louyout.flash')

    {{-- STATS --}}
    <div class="so-stats">
        <div class="so-stat ss1">
            <div class="so-stat-icon"><i class="feather icon-file-text"></i></div>
            <div class="so-stat-num">{{ $totalPresc }}</div>
            <div class="so-stat-lbl">Prescrições Hoje</div>
        </div>
        <div class="so-stat ss2">
            <div class="so-stat-icon"><i class="feather icon-alert-triangle"></i></div>
            <div class="so-stat-num">{{ $prescUrgentes }}</div>
            <div class="so-stat-lbl">Urgentes</div>
        </div>
        <div class="so-stat ss3">
            <div class="so-stat-icon"><i class="feather icon-shopping-cart"></i></div>
            <div class="so-stat-num">{{ $reqPend }}</div>
            <div class="so-stat-lbl">Req. Fármacos Pendentes</div>
        </div>
    </div>

    {{-- BANNER ÚLTIMA PRESCRIÇÃO --}}
    @if($proximaPresc)
    <div class="so-banner {{ $proximaPresc->urgente ? 'urgente' : 'normal' }}">
        <div class="so-av">{{ mb_strtoupper(mb_substr($proximaPresc->nome, 0, 1)) }}</div>
        <div style="flex:1;min-width:0;">
            <div class="so-tag">{{ $proximaPresc->urgente ? '⚡ PRESCRIÇÃO URGENTE' : '📋 Última Prescrição do Dia' }}</div>
            <div class="so-nome">{{ $proximaPresc->nome }}</div>
            <div class="so-meta">
                @if($proximaPresc->data_nascimento)
                    <span>{{ $proximaPresc->sexo === 'M' ? '♂' : '♀' }} {{ \Carbon\Carbon::parse($proximaPresc->data_nascimento)->age }} anos</span>
                @endif
                <span>🩺 Dr. {{ $proximaPresc->medico }}</span>
                <span>🕐 {{ \Carbon\Carbon::parse($proximaPresc->hora)->format('H:i') }}</span>
                @if($proximaPresc->diagnostico)
                    <span>📋 {{ \Str::limit($proximaPresc->diagnostico, 50) }}</span>
                @endif
            </div>
        </div>
        <a href="{{ route('enfermeiro.index') }}" class="btn-ver-presc {{ $proximaPresc->urgente ? 'red' : 'blue' }}">
            <i class="feather icon-eye"></i> Ver Prescrições
        </a>
    </div>
    @else
    <div style="background:#f0faf2;border-radius:16px;border:2px dashed #a7f3c0;padding:28px;text-align:center;margin-bottom:24px;">
        <div style="font-size:44px;margin-bottom:10px;">📋</div>
        <div style="font-size:16px;font-weight:700;color:#1a2e1a;">Nenhuma prescrição hoje</div>
        <div style="font-size:13px;color:#6b7280;margin-top:4px;">As prescrições emitidas pelos médicos aparecerão aqui.</div>
    </div>
    @endif

    {{-- ACÇÕES RÁPIDAS --}}
    <div style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.8px;margin-bottom:12px;display:flex;align-items:center;gap:8px;">
        <span style="width:8px;height:8px;border-radius:50%;background:#1a6b2f;display:inline-block;"></span>
        Acções Rápidas
    </div>
    <div class="so-acoes">
        <a href="{{ route('enfermeiro.index') }}" class="so-acao-card">
            <div class="so-acao-icon" style="background:#dbeafe;color:#1d4ed8;">
                <i class="feather icon-file-text"></i>
            </div>
            <div class="so-acao-titulo">Prescrições Médicas</div>
            <div class="so-acao-desc">Ver todas as prescrições emitidas hoje pelos médicos.</div>
            @if($totalPresc > 0)
                <span class="so-acao-badge" style="background:#dbeafe;color:#1d4ed8;">{{ $totalPresc }} prescrição(ões)</span>
            @endif
        </a>

        <a href="{{ route('enfermeiro.index') }}#requisicoes" onclick="setTimeout(function(){document.querySelectorAll('.enf-tab')[1].click();},200);" class="so-acao-card">
            <div class="so-acao-icon" style="background:#d1fae5;color:#065f46;">
                <i class="feather icon-shopping-cart"></i>
            </div>
            <div class="so-acao-titulo">Requisição de Fármacos</div>
            <div class="so-acao-desc">Solicitar medicamentos à Farmácia para o departamento.</div>
            @if($reqPend > 0)
                <span class="so-acao-badge" style="background:#fef3c7;color:#92400e;">{{ $reqPend }} pendente(s)</span>
            @endif
        </a>
    </div>
</div>

@endsection
