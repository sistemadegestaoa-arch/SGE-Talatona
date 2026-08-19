<ul class="sidenav-inner py-1">

    <li class="sidenav-item active">
        <a href="{{ route('home.index') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-home"></i>
            <div>Início
                @php $req = \DB::table('requisicao')->where('statos','Pendente')->count(); @endphp
                @if ($req > 0)
                    <span class="badge-req">{{ $req }}</span>
                @endif
            </div>
        </a>
    </li>

    <li class="sidenav-divider mb-1"></li>
    <li class="sidenav-header small font-weight-semibold"
        style="color:rgba(255,255,255,0.35);font-size:10px;padding:8px 20px 4px;letter-spacing:1px;text-transform:uppercase;">
        Gestão</li>

    <li class="sidenav-item">
        <a href="{{ route('departamento.index') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-grid"></i>
            <div>Departamentos</div>
        </a>
    </li>

    <li class="sidenav-item">
        <a href="{{ route('categoria.index') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-tag"></i>
            <div>Categorias</div>
        </a>
    </li>

    <li class="sidenav-item">
        <a href="{{ route('fornecedor.index') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-truck"></i>
            <div>Fornecedores</div>
        </a>
    </li>

    <li class="sidenav-item">
        <a href="{{ route('verusuario.show') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-users"></i>
            <div>Utilizadores</div>
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
        <a href="{{ route('ver-lotes.index') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-layers"></i>
            <div>Lotes</div>
        </a>
    </li>

    @php
        $depAtual = \DB::table('departamento')
            ->where('id', Auth::user()->departamento_id)
            ->value('departamento');
    @endphp

    {{-- ── MÓDULO HOSPITALAR ── --}}
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

    <li class="sidenav-item">
        <a href="{{ route('requisicao-farmaco.farmacia') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-inbox"></i>
            <div>Requisições de Fármacos
                @php
                    $reqFarmAdmin = \DB::table('requisicao_farmaco')->where('estado','pendente')->count();
                @endphp
                @if($reqFarmAdmin > 0)
                    <span class="badge-req">{{ $reqFarmAdmin }}</span>
                @endif
            </div>
        </a>
    </li>

    <li class="sidenav-item">
        <a href="{{ route('produto-bloqueio.index') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-lock"></i>
            <div>Bloqueio de Fármacos</div>
        </a>
    </li>

    <li class="sidenav-item">
        <a href="javascript:" class="sidenav-link sidenav-toggle">
            <i class="sidenav-icon feather icon-alert-triangle"></i>
            <div>Alertas</div>
        </a>
        <ul class="sidenav-menu">
            <li class="sidenav-item">
                <a href="{{ route('estoqueminimo.estoqueminimo') }}" class="sidenav-link">
                    <div>Estoque mínimo</div>
                </a>
            </li>
            <li class="sidenav-item">
                <a href="{{ route('expirados.expirados') }}" class="sidenav-link">
                    <div>Expirados / A expirar</div>
                </a>
            </li>
        </ul>
    </li>

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

    <li class="sidenav-item">
        <a href="{{ route('relatorio.hospitalar') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-activity"></i>
            <div>Relatórios Hospitalares</div>
        </a>
    </li>

    <li class="sidenav-divider mb-1"></li>

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
