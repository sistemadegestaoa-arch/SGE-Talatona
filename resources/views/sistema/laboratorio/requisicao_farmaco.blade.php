@extends('louyout.app')
@section('conteodo')

<div class="page-header-bar">
    <div>
        <h4 class="page-title"><i class="feather icon-shopping-cart"></i> Requisição de Fármacos</h4>
        <p class="page-sub">Gerir requisições ao departamento de Farmácia</p>
    </div>
    <button onclick="abrirModalNova()" class="qa-btn green-dark">
        <i class="feather icon-plus-circle"></i> Nova Requisição
    </button>
</div>

@if(session('success'))
    <div class="alert-success-bar"><i class="feather icon-check-circle"></i> {{ session('success') }}</div>
@endif

<div class="table-card">
    <div class="table-responsive">
        <table class="sys-table" id="dataTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Departamento</th>
                    <th>Solicitante</th>
                    <th>Data</th>
                    <th>Estado</th>
                    <th>Atendido por</th>
                    <th>Acções</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requisicoes as $r)
                <tr>
                    <td>{{ $r->id }}</td>
                    <td>{{ $r->dep_nome }}</td>
                    <td>{{ $r->solicitante }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->created_at)->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($r->estado === 'pendente')
                            <span class="status-badge status-pending">⏳ Pendente</span>
                        @elseif($r->estado === 'atendida')
                            <span class="status-badge status-ok">✅ Atendida</span>
                        @else
                            <span class="status-badge status-blocked">❌ Rejeitada</span>
                        @endif
                    </td>
                    <td>{{ $r->atendente ?? '—' }}</td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            {{-- Gerar PDF (download) --}}
                            <a href="{{ route('requisicao-farmaco.pdf', $r->id) }}"
                               class="tbl-btn tbl-info" title="Descarregar PDF">
                                <i class="feather icon-download"></i>
                            </a>
                            {{-- Editar só se pendente --}}
                            @if($r->estado === 'pendente')
                            <button class="tbl-btn tbl-edit" title="Editar"
                                    onclick="carregarEdicao({{ $r->id }})">
                                <i class="feather icon-edit-2"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;color:#9ca3af;padding:30px;">Nenhuma requisição encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ═══ MODAL NOVA / EDITAR REQUISIÇÃO ═══ --}}
<div id="modalReq" class="modal-overlay" style="display:none;">
    <div class="modal-box" style="max-width:720px;">
        <div class="modal-header">
            <h5 id="modalTitulo"><i class="feather icon-shopping-cart"></i> Nova Requisição</h5>
            <button onclick="fecharModal()" class="modal-close">&times;</button>
        </div>
        <form id="formReq">
            @csrf
            <input type="hidden" id="reqId" value="">
            <div class="modal-body">

                {{-- Alerta de fármaco bloqueado --}}
                <div id="alertaBloqueado" style="display:none;background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;padding:12px 16px;margin-bottom:14px;color:#991b1b;font-size:13px;">
                    <strong><i class="feather icon-alert-triangle"></i> Fármacos Bloqueados:</strong>
                    <ul id="listaBloqueados" style="margin:6px 0 0 16px;"></ul>
                    <p style="margin-top:6px;font-size:12px;">Estes fármacos não podem ser requisitados. Remova-os ou escolha outros.</p>
                </div>

                {{-- Observação geral --}}
                <div style="margin-bottom:16px;">
                    <label class="form-label">Observação (opcional)</label>
                    <input type="text" id="obsGeral" class="form-control" placeholder="Ex: Urgente, para análises de rotina...">
                </div>

                {{-- Lista de itens --}}
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <label class="form-label" style="margin:0;">Fármacos Solicitados</label>
                    <button type="button" onclick="addLinha()" class="qa-btn green-dark" style="padding:5px 12px;font-size:12px;">
                        <i class="feather icon-plus"></i> Adicionar Fármaco
                    </button>
                </div>

                <div id="listaItens"></div>

                <div id="erroItens" style="color:#dc2626;font-size:12px;margin-top:6px;display:none;">
                    Adicione pelo menos um fármaco.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="fecharModal()" class="btn-cancel">Cancelar</button>
                <button type="button" onclick="submeterReq()" id="btnSubmeter" class="btn-confirm">
                    <i class="feather icon-send"></i> Enviar Requisição
                </button>
            </div>
        </form>
    </div>
</div>

<script>
var farmacos  = @json($farmacos);
var urlStore  = '{{ url("requisicao-farmaco") }}';
var csrfToken = '{{ csrf_token() }}';

// Mapa de bloqueados para verificação instantânea no select
var bloqueadosMap = {};
farmacos.forEach(function(f){ bloqueadosMap[f.id] = !!f.bloqueado; });

function abrirModalNova() {
    document.getElementById('modalTitulo').innerHTML = '<i class="feather icon-shopping-cart"></i> Nova Requisição';
    document.getElementById('reqId').value = '';
    document.getElementById('obsGeral').value = '';
    document.getElementById('listaItens').innerHTML = '';
    document.getElementById('erroItens').style.display = 'none';
    esconderAlertaBloqueado();
    addLinha();
    document.getElementById('modalReq').style.display = 'flex';
}

function fecharModal() {
    document.getElementById('modalReq').style.display = 'none';
}

function esconderAlertaBloqueado() {
    document.getElementById('alertaBloqueado').style.display = 'none';
    document.getElementById('listaBloqueados').innerHTML = '';
}

function mostrarAlertaBloqueado(nomes) {
    var ul = document.getElementById('listaBloqueados');
    ul.innerHTML = nomes.map(function(n){ return '<li>' + n + '</li>'; }).join('');
    document.getElementById('alertaBloqueado').style.display = 'block';
}

function addLinha(produtoId, quantidade, obs) {
    var idx = document.querySelectorAll('.item-linha').length;

    // Agrupa por bloqueados/disponíveis no select
    var optionsDisp = '<optgroup label="— Disponíveis —">';
    var optionsBloc = '<optgroup label="⛔ Bloqueados (não permitidos)">';

    farmacos.forEach(function(f) {
        var sel = (f.id == produtoId) ? 'selected' : '';
        var opt = '<option value="' + f.id + '" ' + sel
            + (f.bloqueado ? ' data-bloqueado="1" style="color:#dc2626;"' : '')
            + '>' + f.produto + ' — ' + f.apresentacao
            + (f.bloqueado ? ' [BLOQUEADO]' : '') + '</option>';
        if (f.bloqueado) optionsBloc += opt;
        else optionsDisp += opt;
    });
    optionsDisp += '</optgroup>';
    optionsBloc += '</optgroup>';

    var html = '<div class="item-linha" data-idx="' + idx + '" '
        + 'style="display:flex;gap:8px;align-items:center;margin-bottom:8px;background:#f9fafb;border-radius:9px;padding:8px 10px;">'
        + '<select name="itens[' + idx + '][produto_id]" class="form-control sel-farmaco" style="flex:2;" required '
        + 'onchange="verificarBloqueioLinha(this)">'
        +   '<option value="">— Seleccionar fármaco —</option>'
        +   optionsDisp
        +   optionsBloc
        + '</select>'
        + '<input type="number" name="itens[' + idx + '][quantidade]" class="form-control" '
        +   'placeholder="Qtd" min="1" value="' + (quantidade || 1) + '" style="width:70px;" required>'
        + '<input type="text" name="itens[' + idx + '][observacao_item]" class="form-control" '
        +   'placeholder="Obs." value="' + (obs || '') + '" style="flex:1;">'
        + '<button type="button" onclick="removerLinha(this)" '
        +   'style="background:#fee2e2;border:none;border-radius:7px;width:32px;height:32px;cursor:pointer;color:#991b1b;flex-shrink:0;">'
        +   '<i class="feather icon-trash-2"></i>'
        + '</button>'
        + '<div class="aviso-bloqueado" style="display:none;color:#dc2626;font-size:11px;white-space:nowrap;">⛔ Bloqueado</div>'
        + '</div>';

    document.getElementById('listaItens').insertAdjacentHTML('beforeend', html);
}

function verificarBloqueioLinha(sel) {
    var linha  = sel.closest('.item-linha');
    var aviso  = linha.querySelector('.aviso-bloqueado');
    var pid    = parseInt(sel.value);
    if (pid && bloqueadosMap[pid]) {
        aviso.style.display = 'block';
        sel.style.borderColor = '#dc2626';
        sel.style.background  = '#fff5f5';
    } else {
        aviso.style.display = 'none';
        sel.style.borderColor = '';
        sel.style.background  = '';
    }
}

function removerLinha(btn) {
    btn.closest('.item-linha').remove();
    esconderAlertaBloqueado();
}

function submeterReq() {
    esconderAlertaBloqueado();
    var linhas = document.querySelectorAll('.item-linha');
    if (linhas.length === 0) {
        document.getElementById('erroItens').style.display = 'block';
        return;
    }
    document.getElementById('erroItens').style.display = 'none';

    var itens  = [];
    var valido = true;
    linhas.forEach(function(l) {
        var pid = l.querySelector('select').value;
        var qty = l.querySelector('input[type="number"]').value;
        var obs = l.querySelector('input[type="text"]').value;
        if (!pid || !qty || qty < 1) valido = false;
        itens.push({ produto_id: pid, quantidade: qty, observacao_item: obs });
    });

    if (!valido) { alert('Preencha todos os campos obrigatórios.'); return; }

    var reqId  = document.getElementById('reqId').value;
    var url    = reqId ? urlStore + '/' + reqId : urlStore;
    var method = reqId ? 'PUT' : 'POST';

    var btn = document.getElementById('btnSubmeter');
    btn.disabled = true;
    btn.innerHTML = '<i class="feather icon-loader"></i> A enviar...';

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            observacao: document.getElementById('obsGeral').value,
            itens: itens
        })
    })
    .then(function(r) { return r.json().then(function(d){ return {status: r.status, data: d}; }); })
    .then(function(res) {
        btn.disabled = false;
        btn.innerHTML = '<i class="feather icon-send"></i> Enviar Requisição';

        if (res.status === 422 && res.data.bloqueados) {
            mostrarAlertaBloqueado(res.data.bloqueados);
            // Marcar visualmente as linhas com fármacos bloqueados
            document.querySelectorAll('.item-linha').forEach(function(l){
                var sel = l.querySelector('select');
                var opt = sel.options[sel.selectedIndex];
                if (opt && opt.dataset.bloqueado === '1') {
                    sel.style.borderColor = '#dc2626';
                    sel.style.background  = '#fff5f5';
                    l.querySelector('.aviso-bloqueado').style.display = 'block';
                }
            });
            return;
        }
        if (res.data.success) {
            fecharModal();
            location.reload();
        } else {
            alert('Erro ao guardar a requisição.');
        }
    })
    .catch(function() {
        btn.disabled = false;
        btn.innerHTML = '<i class="feather icon-send"></i> Enviar Requisição';
        alert('Erro de comunicação.');
    });
}

function carregarEdicao(id) {
    fetch(urlStore + '/' + id + '/editar', {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
    })
    .then(function(r){ return r.json(); })
    .then(function(data) {
        document.getElementById('modalTitulo').innerHTML = '<i class="feather icon-edit-2"></i> Editar Requisição #' + id;
        document.getElementById('reqId').value = id;
        document.getElementById('obsGeral').value = data.requisicao.observacao || '';
        document.getElementById('listaItens').innerHTML = '';
        esconderAlertaBloqueado();
        data.itens.forEach(function(item) {
            addLinha(item.produto_id, item.quantidade, item.observacao_item);
        });
        document.getElementById('modalReq').style.display = 'flex';
    });
}

document.getElementById('modalReq').addEventListener('click', function(e) {
    if (e.target === this) fecharModal();
});
</script>

<style>
.page-header-bar{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:10px;}
.page-title{font-size:20px;font-weight:700;color:#1a2e1a;margin:0;}
.page-sub{font-size:13px;color:#6b7280;margin:3px 0 0;}
.qa-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:9px;font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;border:none;transition:.2s;}
.qa-btn:hover{opacity:.88;text-decoration:none;}
.qa-btn.green-dark{background:#1a6b2f;color:#fff;}
.alert-success-bar{background:#d1fae5;color:#065f46;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:13px;font-weight:500;display:flex;align-items:center;gap:8px;}
.table-card{background:#fff;border-radius:14px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.05);}
.sys-table{width:100%;border-collapse:collapse;font-size:13px;}
.sys-table thead th{padding:11px 14px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#1a6b2f;background:#f0faf2;border-bottom:2px solid #d1fae5;white-space:nowrap;}
.sys-table tbody td{padding:10px 14px;color:#374151;border-bottom:1px solid #f3f4f6;vertical-align:middle;}
.sys-table tbody tr:last-child td{border-bottom:none;}
.sys-table tbody tr:hover td{background:#f0faf2;}
.status-badge{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;}
.status-pending{background:#fef3c7;color:#92400e;}
.status-ok{background:#d1fae5;color:#065f46;}
.status-blocked{background:#fee2e2;color:#991b1b;}
.tbl-btn{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:7px;border:none;cursor:pointer;font-size:13px;transition:.2s;text-decoration:none;}
.tbl-btn:hover{opacity:.8;}
.tbl-edit{background:#dbeafe;color:#1d4ed8;}
.tbl-info{background:#d1fae5;color:#065f46;}
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;display:flex;align-items:center;justify-content:center;padding:16px;}
.modal-box{background:#fff;border-radius:16px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.2);overflow:hidden;max-height:90vh;display:flex;flex-direction:column;}
.modal-header{display:flex;align-items:center;justify-content:space-between;padding:16px 20px;background:#f0faf2;border-bottom:1px solid #d1fae5;flex-shrink:0;}
.modal-header h5{margin:0;font-size:15px;font-weight:700;color:#1a2e1a;}
.modal-close{background:none;border:none;font-size:22px;cursor:pointer;color:#6b7280;line-height:1;}
.modal-body{padding:20px;overflow-y:auto;flex:1;}
.modal-footer{padding:14px 20px;border-top:1px solid #f3f4f6;display:flex;justify-content:flex-end;gap:10px;flex-shrink:0;}
.form-label{font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;}
.btn-cancel{padding:8px 18px;border-radius:9px;border:1.5px solid #d1d5db;background:#fff;font-size:13px;font-weight:600;cursor:pointer;color:#374151;}
.btn-confirm{padding:8px 18px;border-radius:9px;border:none;background:#1a6b2f;color:#fff;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;}
.btn-confirm:hover{background:#2d9e4a;}
.btn-confirm:disabled{background:#9ca3af;cursor:not-allowed;}
</style>
@endsection
