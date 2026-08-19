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
        Estoque</li>

    <li class="sidenav-item">
        <a href="{{ route('produto.verp') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-package"></i>
            <div>Fármacos</div>
        </a>
    </li>

    <li class="sidenav-item">
        <a href="{{ route('alerte.alert') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-alert-triangle"></i>
            <div>Produtos em risco</div>
        </a>
    </li>

    <li class="sidenav-item">
        <a href="{{ route('lerrequisicao.show') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-file-text"></i>
            <div>Requisições</div>
        </a>
    </li>

    @php
        $depAtual = \DB::table('departamento')
            ->where('id', Auth::user()->departamento_id)
            ->value('departamento');
    @endphp
    @php
        $depNorm = mb_strtolower($depAtual ?? '');
        $depNorm = strtr($depNorm, [
            'á' => 'a',
            'à' => 'a',
            'â' => 'a',
            'ã' => 'a',
            'é' => 'e',
            'è' => 'e',
            'ê' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ú' => 'u',
            'ç' => 'c',
        ]);
        $isTriagem =
            str_contains($depNorm, 'catalogac') ||
            str_contains($depNorm, 'consultas') ||
            str_contains($depNorm, 'triag');
        $isMedico =
            str_contains($depNorm, 'banco') ||
            str_contains($depNorm, 'medic') ||
            str_contains($depNorm, 'pediatr') ||
            str_contains($depNorm, 'intern') ||
            str_contains($depNorm, 'cirurg') ||
            str_contains($depNorm, 'puerp');
        $isLaboratorio = str_contains($depNorm, 'lab');
        $isFarmacia = str_contains($depNorm, 'farm');
        $temModuloHosp = $isTriagem || $isMedico || $isLaboratorio || $isFarmacia;
    @endphp

    @if ($temModuloHosp)
        <li class="sidenav-divider mb-1"></li>
        <li class="sidenav-header small font-weight-semibold"
            style="color:rgba(255,255,255,0.35);font-size:10px;padding:8px 20px 4px;letter-spacing:1px;text-transform:uppercase;">
            Módulo Hospitalar</li>

        @if ($isTriagem)
            <li class="sidenav-item">
                <a href="{{ route('triagem.index') }}" class="sidenav-link">
                    <i class="sidenav-icon feather icon-clipboard"></i>
                    <div>Triagem</div>
                </a>
            </li>
        @endif

        @if ($isFarmacia)
            <li class="sidenav-item">
                <a href="{{ url('atendimento') }}" class="sidenav-link">
                    <i class="sidenav-icon feather icon-user-check"></i>
                    <div>Atendimentos</div>
                </a>
            </li>
        @endif
    @endif

    <li class="sidenav-divider mb-1"></li>
    <li class="sidenav-header small font-weight-semibold"
        style="color:rgba(255,255,255,0.35);font-size:10px;padding:8px 20px 4px;letter-spacing:1px;text-transform:uppercase;">
        Relatórios</li>

    <li class="sidenav-item">
        <a href="{{ route('relatorio.index') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-bar-chart-2"></i>
            <div>Entradas e Saídas</div>
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
