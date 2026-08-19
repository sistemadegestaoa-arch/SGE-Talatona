<ul class="sidenav-inner py-1">

    <li class="sidenav-item active">
        <a href="{{ route('enfermeiro.index') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-home"></i>
            <div>Início</div>
        </a>
    </li>

    <li class="sidenav-divider mb-1"></li>
    <li class="sidenav-header small font-weight-semibold"
        style="color:rgba(255,255,255,0.35);font-size:10px;padding:8px 20px 4px;letter-spacing:1px;text-transform:uppercase;">
        Enfermagem / S.O.
    </li>

    <li class="sidenav-item">
        <a href="{{ route('enfermeiro.index') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-activity"></i>
            <div>Painel S.O.
                @php
                    $prescHoje = \DB::table('prescricao')
                        ->join('consulta','consulta.id','=','prescricao.consulta_id')
                        ->join('episodio','episodio.id','=','consulta.episodio_id')
                        ->whereDate('episodio.data', today())
                        ->count();
                @endphp
                @if($prescHoje > 0)
                    <span class="badge-req">{{ $prescHoje }}</span>
                @endif
            </div>
        </a>
    </li>

    <li class="sidenav-item">
        <a href="{{ route('enfermeirorequisicao.index') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-shopping-cart"></i>
            <div>Req. de Fármacos
                @php
                    $reqEnf = \DB::table('requisicao_farmaco')
                        ->where('departamento_id', Auth::user()->departamento_id)
                        ->where('estado','pendente')->count();
                @endphp
                @if($reqEnf > 0)
                    <span class="badge-req">{{ $reqEnf }}</span>
                @endif
            </div>
        </a>
    </li>

    <li class="sidenav-divider mb-1"></li>

    <li class="sidenav-item">
        <a href="{{ url('perfil') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-user"></i>
            <div>Meu Perfil</div>
        </a>
    </li>

</ul>
