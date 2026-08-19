@extends('louyout.app')
@section('conteodo')

<style>
    .rh-wrap { max-width: 100%; }
    .rh-header { margin-bottom: 28px; }
    .rh-title { font-size: 22px; font-weight: 800; color: #1a2e1a; margin: 0; }
    .rh-sub { font-size: 13px; color: #6b7280; margin: 4px 0 0; }
    .rh-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .rh-card { background: #fff; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 2px 10px rgba(0,0,0,.04); overflow: hidden; }
    .rh-card-head { padding: 16px 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid #f3f4f6; }
    .rh-card-head .icon-wrap { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
    .rh-card-head h3 { font-size: 14px; font-weight: 700; color: #1a2e1a; margin: 0; }
    .rh-card-head p { font-size: 12px; color: #6b7280; margin: 2px 0 0; }
    .rh-card-body { padding: 20px; }
    .rh-form-group { margin-bottom: 14px; }
    .rh-form-group label { display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 5px; text-transform: uppercase; letter-spacing: .4px; }
    .rh-form-group input, .rh-form-group select { width: 100%; padding: 9px 12px; border: 1.5px solid #e5e7eb; border-radius: 9px; font-size: 13px; color: #1a2e1a; background: #fff; }
    .rh-form-group input:focus, .rh-form-group select:focus { border-color: #1a6b2f; outline: none; }
    .rh-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .btn-gerar { width: 100%; padding: 11px; border: none; border-radius: 10px; color: #fff; font-size: 13px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .c1 .icon-wrap { background: #f0faf2; color: #1a6b2f; }
    .c2 .icon-wrap { background: #eff6ff; color: #1d4ed8; }
    .c3 .icon-wrap { background: #fef3c7; color: #92400e; }
    .c4 .icon-wrap { background: #fdf4ff; color: #7e22ce; }
    .c5 .icon-wrap { background: #fff1f2; color: #be123c; }
    .c1 .btn-gerar { background: linear-gradient(135deg,#1a6b2f,#2d9e4a); }
    .c2 .btn-gerar { background: linear-gradient(135deg,#1e3a8a,#3b82f6); }
    .c3 .btn-gerar { background: linear-gradient(135deg,#92400e,#f59e0b); }
    .c4 .btn-gerar { background: linear-gradient(135deg,#6b21a8,#a855f7); }
    .c5 .btn-gerar { background: linear-gradient(135deg,#9f1239,#f43f5e); }
    @media(max-width:900px){ .rh-grid{ grid-template-columns:1fr; } }
</style>

<div class="rh-wrap">
    <div class="rh-header">
        <h1 class="rh-title">Relatorios Hospitalares</h1>
        <p class="rh-sub">Gere relatorios em PDF para analise clinica e administrativa.</p>
    </div>
    @include('louyout.flash')
    <div class="rh-grid">

        @if(in_array($perfil, ['triagem','admin']))
        <div class="rh-card c1">
            <div class="rh-card-head">
                <div class="icon-wrap">&#128101;</div>
                <div><h3>Atendimentos de Pacientes</h3><p>Lista detalhada por periodo</p></div>
            </div>
            <div class="rh-card-body">
                <form action="{{ route('relatorio.atendimentos') }}" method="POST" target="_blank">
                    @csrf
                    <div class="rh-row">
                        <div class="rh-form-group"><label>Data Inicio</label><input type="date" name="data_inicio" value="{{ date('Y-m-01') }}" required></div>
                        <div class="rh-form-group"><label>Data Fim</label><input type="date" name="data_fim" value="{{ date('Y-m-d') }}" required></div>
                    </div>
                    <button type="submit" class="btn-gerar"><i class="feather icon-file-text"></i> Gerar PDF</button>
                </form>
            </div>
        </div>

        <div class="rh-card c2">
            <div class="rh-card-head">
                <div class="icon-wrap">&#128197;</div>
                <div><h3>Atendimentos por Data</h3><p>Resumo diario com totais</p></div>
            </div>
            <div class="rh-card-body">
                <form action="{{ route('relatorio.por-data') }}" method="POST" target="_blank">
                    @csrf
                    <div class="rh-row">
                        <div class="rh-form-group"><label>Data Inicio</label><input type="date" name="data_inicio" value="{{ date('Y-m-01') }}" required></div>
                        <div class="rh-form-group"><label>Data Fim</label><input type="date" name="data_fim" value="{{ date('Y-m-d') }}" required></div>
                    </div>
                    <button type="submit" class="btn-gerar"><i class="feather icon-file-text"></i> Gerar PDF</button>
                </form>
            </div>
        </div>

        <div class="rh-card c3">
            <div class="rh-card-head">
                <div class="icon-wrap">&#128100;</div>
                <div><h3>Por Data e Funcionario</h3><p>Filtra por colaborador</p></div>
            </div>
            <div class="rh-card-body">
                <form action="{{ route('relatorio.por-funcionario') }}" method="POST" target="_blank">
                    @csrf
                    <div class="rh-row">
                        <div class="rh-form-group"><label>Data Inicio</label><input type="date" name="data_inicio" value="{{ date('Y-m-01') }}" required></div>
                        <div class="rh-form-group"><label>Data Fim</label><input type="date" name="data_fim" value="{{ date('Y-m-d') }}" required></div>
                    </div>
                    <div class="rh-form-group">
                        <label>Funcionario (opcional)</label>
                        <select name="funcionario_id">
                            <option value="">Todos</option>
                            @foreach($medicos as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->departamento }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-gerar"><i class="feather icon-file-text"></i> Gerar PDF</button>
                </form>
            </div>
        </div>
        @endif

        {{-- Farmácia: relatórios de dispensa e requisições --}}
        @if(in_array($perfil, ['farmacia','admin']))
        <div class="rh-card c1">
            <div class="rh-card-head">
                <div class="icon-wrap">&#128101;</div>
                <div><h3>Atendimentos de Pacientes</h3><p>Lista detalhada por periodo</p></div>
            </div>
            <div class="rh-card-body">
                <form action="{{ route('relatorio.atendimentos') }}" method="POST" target="_blank">
                    @csrf
                    <div class="rh-row">
                        <div class="rh-form-group"><label>Data Inicio</label><input type="date" name="data_inicio" value="{{ date('Y-m-01') }}" required></div>
                        <div class="rh-form-group"><label>Data Fim</label><input type="date" name="data_fim" value="{{ date('Y-m-d') }}" required></div>
                    </div>
                    <button type="submit" class="btn-gerar"><i class="feather icon-file-text"></i> Gerar PDF</button>
                </form>
            </div>
        </div>

        <div class="rh-card c2">
            <div class="rh-card-head">
                <div class="icon-wrap">&#128197;</div>
                <div><h3>Atendimentos por Data</h3><p>Resumo diario com totais</p></div>
            </div>
            <div class="rh-card-body">
                <form action="{{ route('relatorio.por-data') }}" method="POST" target="_blank">
                    @csrf
                    <div class="rh-row">
                        <div class="rh-form-group"><label>Data Inicio</label><input type="date" name="data_inicio" value="{{ date('Y-m-01') }}" required></div>
                        <div class="rh-form-group"><label>Data Fim</label><input type="date" name="data_fim" value="{{ date('Y-m-d') }}" required></div>
                    </div>
                    <button type="submit" class="btn-gerar"><i class="feather icon-file-text"></i> Gerar PDF</button>
                </form>
            </div>
        </div>

        <div class="rh-card c3">
            <div class="rh-card-head">
                <div class="icon-wrap">&#128100;</div>
                <div><h3>Por Data e Funcionario</h3><p>Filtra por colaborador</p></div>
            </div>
            <div class="rh-card-body">
                <form action="{{ route('relatorio.por-funcionario') }}" method="POST" target="_blank">
                    @csrf
                    <div class="rh-row">
                        <div class="rh-form-group"><label>Data Inicio</label><input type="date" name="data_inicio" value="{{ date('Y-m-01') }}" required></div>
                        <div class="rh-form-group"><label>Data Fim</label><input type="date" name="data_fim" value="{{ date('Y-m-d') }}" required></div>
                    </div>
                    <div class="rh-form-group">
                        <label>Funcionario (opcional)</label>
                        <select name="funcionario_id">
                            <option value="">Todos</option>
                            @foreach($medicos as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->departamento }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-gerar"><i class="feather icon-file-text"></i> Gerar PDF</button>
                </form>
            </div>
        </div>
        @endif

        {{-- Farmácia: relatórios de dispensa e requisições --}}
        @if(in_array($perfil, ['farmacia','admin']))
        <div class="rh-card" style="--c:#5b21b6;">
            <div class="rh-card-head">
                <div class="icon-wrap" style="background:#ede9fe;color:#5b21b6;">&#128138;</div>
                <div><h3>Receitas Dispensadas</h3><p>Histórico de dispensas por período</p></div>
            </div>
            <div class="rh-card-body">
                <form action="{{ route('relatorio.atendimentos') }}" method="POST" target="_blank">
                    @csrf
                    <div class="rh-row">
                        <div class="rh-form-group"><label>Data Inicio</label><input type="date" name="data_inicio" value="{{ date('Y-m-01') }}" required></div>
                        <div class="rh-form-group"><label>Data Fim</label><input type="date" name="data_fim" value="{{ date('Y-m-d') }}" required></div>
                    </div>
                    <button type="submit" class="btn-gerar" style="background:linear-gradient(135deg,#5b21b6,#8b5cf6);">
                        <i class="feather icon-file-text"></i> Gerar PDF
                    </button>
                </form>
            </div>
        </div>

        <div class="rh-card" style="--c:#0e7490;">
            <div class="rh-card-head">
                <div class="icon-wrap" style="background:#ecfeff;color:#0e7490;">&#129514;</div>
                <div><h3>Requisições de Fármacos</h3><p>Requisições recebidas dos departamentos</p></div>
            </div>
            <div class="rh-card-body">
                <form action="{{ route('relatorio.requisicoes-farmaco') }}" method="POST">
                    @csrf
                    <div class="rh-row">
                        <div class="rh-form-group"><label>Data Inicio</label><input type="date" name="data_inicio" value="{{ date('Y-m-01') }}" required></div>
                        <div class="rh-form-group"><label>Data Fim</label><input type="date" name="data_fim" value="{{ date('Y-m-d') }}" required></div>
                    </div>
                    <div class="rh-form-group">
                        <label>Estado</label>
                        <select name="estado">
                            <option value="">Todos</option>
                            <option value="pendente">Pendente</option>
                            <option value="atendida">Atendida</option>
                            <option value="rejeitada">Rejeitada</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-gerar" style="background:linear-gradient(135deg,#0e7490,#06b6d4);">
                        <i class="feather icon-download"></i> Descarregar PDF
                    </button>
                </form>
            </div>
        </div>
        @endif

        @if(in_array($perfil, ['medico','admin']))
        <div class="rh-card c4">
            <div class="rh-card-head">
                <div class="icon-wrap">&#129690;</div>
                <div><h3>Relatorio do Medico</h3><p>Consultas, diagnosticos e exames</p></div>
            </div>
            <div class="rh-card-body">
                <form action="{{ route('relatorio.medico') }}" method="POST" target="_blank">
                    @csrf
                    <div class="rh-row">
                        <div class="rh-form-group"><label>Data Inicio</label><input type="date" name="data_inicio" value="{{ date('Y-m-01') }}" required></div>
                        <div class="rh-form-group"><label>Data Fim</label><input type="date" name="data_fim" value="{{ date('Y-m-d') }}" required></div>
                    </div>
                    <div class="rh-form-group">
                        <label>Medico</label>
                        <select name="medico_id" required>
                            <option value="">Seleccione</option>
                            @foreach($medicos as $u)
                                <option value="{{ $u->id }}"
                                    @if($perfil === 'medico' &&  $u->id == auth()->id()) selected @endif>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-gerar"><i class="feather icon-file-text"></i> Gerar PDF</button>
                </form>
            </div>
        </div>
        @endif

        @if(in_array($perfil, ['laboratorio','admin']))
        <div class="rh-card c5">
            <div class="rh-card-head">
                <div class="icon-wrap">&#128300;</div>
                <div><h3>Relatorio do Laboratorio</h3><p>Exames solicitados e resultados</p></div>
            </div>
            <div class="rh-card-body">
                <form action="{{ route('relatorio.laboratorio') }}" method="POST" target="_blank">
                    @csrf
                    <div class="rh-row">
                        <div class="rh-form-group"><label>Data Inicio</label><input type="date" name="data_inicio" value="{{ date('Y-m-01') }}" required></div>
                        <div class="rh-form-group"><label>Data Fim</label><input type="date" name="data_fim" value="{{ date('Y-m-d') }}" required></div>
                    </div>
                    <button type="submit" class="btn-gerar"><i class="feather icon-file-text"></i> Gerar PDF</button>
                </form>
            </div>
        </div>

        <div class="rh-card" style="--c:#0e7490;">
            <div class="rh-card-head">
                <div class="icon-wrap" style="background:#ecfeff;color:#0e7490;">&#129514;</div>
                <div><h3>Requisições de Fármacos</h3><p>Requisições enviadas à Farmácia</p></div>
            </div>
            <div class="rh-card-body">
                <form action="{{ route('relatorio.requisicoes-farmaco') }}" method="POST">
                    @csrf
                    <div class="rh-row">
                        <div class="rh-form-group"><label>Data Inicio</label><input type="date" name="data_inicio" value="{{ date('Y-m-01') }}" required></div>
                        <div class="rh-form-group"><label>Data Fim</label><input type="date" name="data_fim" value="{{ date('Y-m-d') }}" required></div>
                    </div>
                    <div class="rh-form-group">
                        <label>Estado</label>
                        <select name="estado">
                            <option value="">Todos</option>
                            <option value="pendente">Pendente</option>
                            <option value="atendida">Atendida</option>
                            <option value="rejeitada">Rejeitada</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-gerar" style="background:linear-gradient(135deg,#0e7490,#06b6d4);">
                        <i class="feather icon-download"></i> Descarregar PDF
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection
