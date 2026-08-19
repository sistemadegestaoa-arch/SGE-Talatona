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
        Consultas
    </li>

    <li class="sidenav-item">
        <a href="{{ route('consultas.index') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-users"></i>
            <div>Lista de Espera
                @php
                    $espera = \DB::table('episodio')
                        ->whereDate('data', today())
                        ->whereIn('estado', ['em_espera', 'em_consulta', 'aguarda_exame'])
                        ->count();
                    $resultados = \DB::table('pedido_exame')
                        ->join('consulta', 'consulta.id', '=', 'pedido_exame.consulta_id')
                        ->join('episodio', 'episodio.id', '=', 'consulta.episodio_id')
                        ->where('pedido_exame.estado', 'concluido')
                        ->whereDate('episodio.data', today())
                        ->where('consulta.medico_id', Auth::id())
                        ->whereIn('episodio.estado', ['em_consulta', 'aguarda_exame'])
                        ->count();
                @endphp
                @if ($espera > 0)
                    <span class="badge-req">{{ $espera }}</span>
                @endif
            </div>
        </a>
    </li>

    @if ($resultados > 0)
        <li class="sidenav-item">
            <a href="{{ route('consultas.index') }}" class="sidenav-link" style="color:#fbbf24 !important;">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Resultados de Exame
                    <span class="badge-req">{{ $resultados }}</span>
                </div>
            </a>
        </li>
    @endif

    <li class="sidenav-divider mb-1"></li>

    <li class="sidenav-item">
        <a href="{{ route('relatorio.hospitalar') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-file-text"></i>
            <div>Relatorios</div>
        </a>
    </li>

    <li class="sidenav-item">
        <a href="{{ route('chamadas.painel') }}" target="_blank" class="sidenav-link">
            <i class="sidenav-icon feather icon-monitor"></i>
            <div>Painel de Chamadas</div>
        </a>
    </li>

    <li class="sidenav-item">
        <a href="{{ url('perfil') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-user"></i>
            <div>Meu Perfil</div>
        </a>
    </li>

</ul>
