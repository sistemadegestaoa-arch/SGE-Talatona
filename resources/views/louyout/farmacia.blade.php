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
        Farmácia
    </li>

    {{-- Receitas pendentes — item principal ─────────────────── --}}
    <li class="sidenav-item">
        <a href="{{ route('receitas.index') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-file-text"></i>
            <div>Receitas Pendentes
                @php
                    $recPend = \DB::table('receita')->where('estado', 'pendente')->count();
                @endphp
                @if ($recPend > 0)
                    <span class="badge-req">{{ $recPend }}</span>
                @endif
            </div>
        </a>
    </li>

    {{-- Requisições de Fármacos ─────────────────────────────── --}}
    <li class="sidenav-item">
        <a href="{{ route('requisicao-farmaco.farmacia') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-inbox"></i>
            <div>Requisições de Fármacos
                @php
                    $reqFarmPend = \DB::table('requisicao_farmaco')->where('estado', 'pendente')->count();
                @endphp
                @if ($reqFarmPend > 0)
                    <span class="badge-req">{{ $reqFarmPend }}</span>
                @endif
            </div>
        </a>
    </li>

    {{-- Atendimentos (histórico + manual) ──────────────────── --}}
    <li class="sidenav-item">
        <a href="{{ route('atendimento.index') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-user-check"></i>
            <div>Histórico de Atendimentos</div>
        </a>
    </li>

    <li class="sidenav-item">
        <a href="{{ route('atendimento.create') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-plus-circle"></i>
            <div>Atendimento Manual</div>
        </a>
    </li>

    <li class="sidenav-divider mb-1"></li>
    <li class="sidenav-header small font-weight-semibold"
        style="color:rgba(255,255,255,0.35);font-size:10px;padding:8px 20px 4px;letter-spacing:1px;text-transform:uppercase;">
        Stock
    </li>

    <li class="sidenav-item">
        <a href="{{ route('produto.verp') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-package"></i>
            <div>Fármacos em Stock</div>
        </a>
    </li>

    <li class="sidenav-divider mb-1"></li>
    <li class="sidenav-header small font-weight-semibold"
        style="color:rgba(255,255,255,0.35);font-size:10px;padding:8px 20px 4px;letter-spacing:1px;text-transform:uppercase;">
        Relatórios
    </li>

    <li class="sidenav-item">
        <a href="{{ route('relatorio.index') }}" class="sidenav-link">
            <i class="sidenav-icon feather icon-file-text"></i>
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
