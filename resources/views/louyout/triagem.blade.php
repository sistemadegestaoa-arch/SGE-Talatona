<ul class="sidenav-inner py-1">

    <li class="sidenav-item active">
        <a href="{{ route('home.index') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-home"></i>
            <div>Início</div>
        </a>
    </li>

    <li class="sidenav-divider mb-1"></li>
    <li class="sidenav-header small font-weight-semibold"
        style="color:rgba(255,255,255,0.35);font-size:10px;padding:8px 20px 4px;letter-spacing:1px;text-transform:uppercase;">
        Triagem
    </li>

    <li class="sidenav-item">
        <a href="{{ route('triagem.index') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-clipboard"></i>
            <div>Pacientes do Dia
                @php
                    $emEsp = \DB::table('episodio')->whereDate('data', today())->where('estado', 'em_espera')->count();
                @endphp
                @if ($emEsp > 0)
                    <span class="badge-req">{{ $emEsp }}</span>
                @endif
            </div>
        </a>
    </li>

    <li class="sidenav-item">
        <a href="{{ route('triagem.create') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-user-plus"></i>
            <div>Nova Triagem</div>
        </a>
    </li>

    <li class="sidenav-item">
        <a href="{{ route('triagem.estatisticas') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-bar-chart-2"></i>
            <div>Estatísticas</div>
        </a>
    </li>

    <li class="sidenav-divider mb-1"></li>

    <li class="sidenav-item">
        <a href="{{ route('relatorio.hospitalar') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-file-text"></i>
            <div>Relatorios</div>
        </a>
    </li>

    <li class="sidenav-item">
        <a href="{{ url('perfil') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-user"></i>
            <div>Meu Perfil</div>
        </a>
    </li>

</ul>
