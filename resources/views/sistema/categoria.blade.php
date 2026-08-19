@extends('louyout.app')
@section('conteodo')
    @include('louyout.styles')
    @include('louyout.flash')

    <style>
        .cat-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            align-items: start;
        }

        .f-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .f-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            background: #f0faf2;
            border-bottom: 2px solid #d1fae5;
        }

        .f-card-header-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .f-card-header i {
            font-size: 15px;
            color: #1a6b2f;
        }

        .f-card-header span {
            font-size: 14px;
            font-weight: 700;
            color: #1a6b2f;
        }

        .f-card-body {
            padding: 0;
        }

        /* Form inline */
        .inline-form {
            display: flex;
            gap: 8px;
            padding: 14px 18px;
            border-bottom: 1px solid #f3f4f6;
            background: #fafafa;
        }

        .inline-form input,
        .inline-form select {
            flex: 1;
            padding: 8px 12px;
            border: 1.5px solid #e5e7eb;
            border-radius: 9px;
            font-size: 13px;
            outline: none;
            font-family: 'Inter', sans-serif;
            background: #fff;
        }

        .inline-form input:focus,
        .inline-form select:focus {
            border-color: #1a6b2f;
            box-shadow: 0 0 0 3px rgba(26, 107, 47, .1);
        }

        .btn-inline {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 14px;
            background: #1a6b2f;
            color: #fff;
            border: none;
            border-radius: 9px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
            font-family: 'Inter', sans-serif;
        }

        .btn-inline:hover {
            background: #2d9e4a;
        }

        /* Table */
        .cat-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .cat-table th {
            padding: 9px 14px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: #1a6b2f;
            background: #f0faf2;
            border-bottom: 2px solid #d1fae5;
        }

        .cat-table td {
            padding: 9px 14px;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
            vertical-align: middle;
        }

        .cat-table tr:last-child td {
            border-bottom: none;
        }

        .cat-table tr:hover td {
            background: #f9fafb;
        }

        /* Edit inline */
        .edit-row {
            display: none;
            background: #f0faf2;
        }

        .edit-row td {
            padding: 10px 14px;
        }

        .edit-row form {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .edit-row input,
        .edit-row select {
            padding: 7px 11px;
            border: 1.5px solid #d1fae5;
            border-radius: 8px;
            font-size: 13px;
            outline: none;
            font-family: 'Inter', sans-serif;
            flex: 1;
            min-width: 120px;
        }

        .edit-row input:focus,
        .edit-row select:focus {
            border-color: #1a6b2f;
        }

        .cat-geral-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            background: #ede9fe;
            color: #5b21b6;
        }

        .count-badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            background: #d1fae5;
            color: #065f46;
        }

        .search-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-bottom: 1px solid #f3f4f6;
        }

        .search-bar input {
            flex: 1;
            padding: 7px 12px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 12px;
            outline: none;
            font-family: 'Inter', sans-serif;
        }

        .search-bar input:focus {
            border-color: #1a6b2f;
        }

        @media(max-width:900px) {
            .cat-layout {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="page-header-bar">
        <div>
            <h4 class="page-title"><i class="feather icon-tag" style="color:#1a6b2f;margin-right:8px;"></i>Categorias</h4>
            <p class="page-sub">Gerencie categorias gerais e subcategorias</p>
        </div>
    </div>

    <div class="cat-layout">

        {{-- ── CATEGORIAS GERAIS ── --}}
        <div>
            <div class="f-card">
                <div class="f-card-header">
                    <div class="f-card-header-left">
                        <i class="feather icon-grid"></i>
                        <span>Categorias Gerais</span>
                        <span class="count-badge" style="margin-left:4px;">{{ $gerais->count() }}</span>
                    </div>
                </div>

                {{-- Form criar --}}
                <form action="{{ route('categoria-geral.store') }}" method="POST" class="inline-form">
                    @csrf
                    <input type="text" name="categoria_geral" placeholder="Nova categoria geral..." required
                        value="{{ old('categoria_geral') }}">
                    <button type="submit" class="btn-inline">
                        <i class="feather icon-plus"></i> Adicionar
                    </button>
                </form>

                <div class="search-bar">
                    <i class="feather icon-search" style="color:#9ca3af;font-size:13px;"></i>
                    <input type="text" id="searchGeral" placeholder="Pesquisar...">
                </div>

                <div style="overflow-x:auto;max-height:480px;overflow-y:auto;">
                    <table class="cat-table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th style="width:60px;">Sub</th>
                                <th style="width:70px;">Acções</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyGeral">
                            @forelse($gerais as $g)
                                @php $nSub = \DB::table('categoria')->where('categoria_geral_id',$g->id)->count(); @endphp
                                <tr id="geral-row-{{ $g->id }}">
                                    <td><strong>{{ $g->categoria_geral }}</strong></td>
                                    <td><span class="count-badge">{{ $nSub }}</span></td>
                                    <td>
                                        <div style="display:flex;gap:4px;">
                                            <button type="button" class="tbl-btn tbl-edit" title="Editar"
                                                onclick="toggleEditGeral({{ $g->id }})">
                                                <i class="feather icon-edit-2"></i>
                                            </button>
                                            @if(\App\Helpers\PermissaoHelper::podeEliminar())
                                                <form action="{{ route('categoria-geral.destroy', $g->id) }}" method="POST"
                                                    onsubmit="return confirm('Eliminar {{ addslashes($g->categoria_geral) }}?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="tbl-btn tbl-del" title="Eliminar">
                                                        <i class="feather icon-trash-2"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <tr class="edit-row" id="geral-edit-{{ $g->id }}">
                                    <td colspan="3">
                                        <form action="{{ route('categoria-geral.update', $g->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="text" name="categoria_geral" value="{{ $g->categoria_geral }}"
                                                required>
                                            <button type="submit" class="btn-inline">
                                                <i class="feather icon-save"></i> Guardar
                                            </button>
                                            <button type="button" class="btn-inline" style="background:#6b7280;"
                                                onclick="toggleEditGeral({{ $g->id }})">
                                                Cancelar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align:center;padding:30px;color:#9ca3af;">Nenhuma
                                        categoria geral.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── SUBCATEGORIAS ── --}}
        <div>
            <div class="f-card">
                <div class="f-card-header">
                    <div class="f-card-header-left">
                        <i class="feather icon-tag"></i>
                        <span>Subcategorias</span>
                        <span class="count-badge" style="margin-left:4px;">{{ $categorias->count() }}</span>
                    </div>
                </div>

                {{-- Form criar --}}
                <form action="{{ route('createcategoria.store') }}" method="POST" class="inline-form"
                    style="flex-wrap:wrap;gap:6px;">
                    @csrf
                    <select name="categoria_geral_id" required style="flex:1;min-width:140px;">
                        <option value="">— Categoria Geral —</option>
                        @foreach ($gerais as $g)
                            <option value="{{ $g->id }}">{{ $g->categoria_geral }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="categoria" placeholder="Nome da subcategoria..." required
                        value="{{ old('categoria') }}" style="flex:2;min-width:140px;">
                    <button type="submit" class="btn-inline">
                        <i class="feather icon-plus"></i> Adicionar
                    </button>
                </form>

                <div class="search-bar">
                    <i class="feather icon-search" style="color:#9ca3af;font-size:13px;"></i>
                    <input type="text" id="searchSub" placeholder="Pesquisar...">
                </div>

                <div style="overflow-x:auto;max-height:480px;overflow-y:auto;">
                    <table class="cat-table">
                        <thead>
                            <tr>
                                <th>Subcategoria</th>
                                <th>Categoria Geral</th>
                                <th style="width:70px;">Acções</th>
                            </tr>
                        </thead>
                        <tbody id="tbodySub">
                            @forelse($categorias as $cat)
                                <tr id="sub-row-{{ $cat->id }}">
                                    <td><strong>{{ $cat->categoria }}</strong></td>
                                    <td>
                                        <span class="cat-geral-badge">
                                            <i class="feather icon-grid" style="font-size:10px;"></i>
                                            {{ $cat->categoria_geral ?? '—' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:4px;">
                                            <button type="button" class="tbl-btn tbl-edit" title="Editar"
                                                onclick="toggleEditSub({{ $cat->id }})">
                                                <i class="feather icon-edit-2"></i>
                                            </button>
                                            @if(\App\Helpers\PermissaoHelper::podeEliminar())
                                                <form action="{{ route('apagarcategoria.destroy', $cat->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Eliminar {{ addslashes($cat->categoria) }}?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="tbl-btn tbl-del" title="Eliminar">
                                                        <i class="feather icon-trash-2"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <tr class="edit-row" id="sub-edit-{{ $cat->id }}">
                                    <td colspan="3">
                                        <form action="{{ route('updatecategoria.update', $cat->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <select name="categoria_geral_id" required>
                                                @foreach ($gerais as $g)
                                                    <option value="{{ $g->id }}"
                                                        {{ $g->id == $cat->categoria_geral_id ? 'selected' : '' }}>
                                                        {{ $g->categoria_geral }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="text" name="categoria" value="{{ $cat->categoria }}"
                                                required>
                                            <button type="submit" class="btn-inline">
                                                <i class="feather icon-save"></i> Guardar
                                            </button>
                                            <button type="button" class="btn-inline" style="background:#6b7280;"
                                                onclick="toggleEditSub({{ $cat->id }})">
                                                Cancelar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align:center;padding:30px;color:#9ca3af;">Nenhuma
                                        subcategoria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script>
        function toggleEditGeral(id) {
            var row = document.getElementById('geral-edit-' + id);
            row.style.display = row.style.display === 'table-row' ? 'none' : 'table-row';
        }

        function toggleEditSub(id) {
            var row = document.getElementById('sub-edit-' + id);
            row.style.display = row.style.display === 'table-row' ? 'none' : 'table-row';
        }

        document.getElementById('searchGeral').addEventListener('input', function() {
            var q = this.value.toLowerCase();
            document.querySelectorAll('#tbodyGeral tr[id^="geral-row"]').forEach(function(r) {
                r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
        document.getElementById('searchSub').addEventListener('input', function() {
            var q = this.value.toLowerCase();
            document.querySelectorAll('#tbodySub tr[id^="sub-row"]').forEach(function(r) {
                r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    </script>

@endsection
