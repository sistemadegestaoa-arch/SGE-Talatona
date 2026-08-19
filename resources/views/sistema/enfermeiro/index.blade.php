@extends('louyout.app')
@section('conteodo')

<style>
.enf-wrap { max-width:100%; }
.enf-header { display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px; }
.enf-title  { font-size:22px;font-weight:800;color:#1a2e1a;margin:0; }
.enf-sub    { font-size:13px;color:#6b7280;margin:4px 0 0; }
.enf-stats  { display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px; }
.enf-stat   { border-radius:16px;padding:20px 16px 16px;color:#fff;position:relative;overflow:hidden;transition:transform .2s; }
.enf-stat:hover { transform:translateY(-3px); }
.enf-stat.es1   { background:linear-gradient(135deg,#1e3a8a,#3b82f6); }
.enf-stat.es2   { background:linear-gradient(135deg,#991b1b,#dc2626); }
.enf-stat.es3   { background:linear-gradient(135deg,#0e7490,#06b6d4); }
.enf-stat-num   { font-size:36px;font-weight:900;line-height:1; }
.enf-stat-lbl   { font-size:11px;font-weight:600;opacity:.85;margin-top:3px; }
.enf-stat-icon  { position:absolute;right:12px;top:12px;font-size:30px;opacity:.15; }

/* Tabs */
.enf-tabs { display:flex;border-bottom:2px solid #e5e7eb;margin-bottom:20px;gap:0; }
.enf-tab  { padding:11px 22px;background:none;border:none;border-bottom:3px solid transparent;margin-bottom:-2px;font-size:13px;font-weight:600;color:#6b7280;cursor:pointer;font-family:'Inter',sans-serif;transition:all .2s;display:flex;align-items:center;gap:7px; }
.enf-tab.active { color:#1a6b2f;border-bottom-color:#1a6b2f; }
.enf-tab:hover:not(.active) { color:#374151; }
.enf-tab .tb { background:#ef4444;color:#fff;font-size:10px;font-weight:800;padding:1px 7px;border-radius:20px; }
.enf-tab .tok { background:#1a6b2f;color:#fff;font-size:10px;font-weight:800;padding:1px 7px;border-radius:20px; }
.enf-panel { display:none; }
.enf-panel.active { display:block; }

/* Cartão de prescrição */
.presc-card { background:#fff;border-radius:16px;border:1px solid #e5e7eb;box-shadow:0 2px 10px rgba(0,0,0,.05);margin-bottom:14px;overflow:hidden;transition:box-shadow .2s; }
.presc-card:hover { box-shadow:0 6px 20px rgba(0,0,0,.1); }
.presc-card.urgente { border-left:5px solid #dc2626; }
.presc-head { display:flex;align-items:center;gap:14px;padding:16px 20px;background:#f9fafb;border-bottom:1px solid #f3f4f6; }
.presc-av { width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:17px;font-weight:800;color:#fff;flex-shrink:0; }
.av-m { background:linear-gradient(135deg,#1e3a8a,#3b82f6); }
.av-f { background:linear-gradient(135deg,#9d174d,#ec4899); }
.presc-body { padding:16px 20px; }
.presc-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:10px;margin-bottom:10px; }
.presc-med { background:#f0faf2;border-radius:10px;padding:10px 12px;border:1px solid #d1fae5; }
.presc-med-nome { font-size:13px;font-weight:700;color:#1a2e1a;margin-bottom:3px; }
.presc-med-info { font-size:11px;color:#6b7280;line-height:1.6; }
.presc-med-dose { display:inline-block;background:#fff;border:1px solid #d1fae5;border-radius:6px;padding:1px 7px;font-size:11px;font-weight:600;color:#065f46;margin-top:3px; }

/* Botões */
.qa-btn { display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:9px;font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;border:none;transition:.2s; }
.qa-btn:hover { opacity:.88;text-decoration:none; }
.qa-btn.green-dark { background:#1a6b2f;color:#fff; }
.qa-btn.blue-dark  { background:#1d4ed8;color:#fff; }
.empty-state { text-align:center;padding:48px 20px;background:#fff;border-radius:16px;border:1px solid #e5e7eb; }

@media(max-width:700px) { .enf-stats { grid-template-columns:1fr; } .presc-grid { grid-template-columns:1fr; } }
</style>

<div class="enf-wrap">
    {{-- HEADER --}}
    <div class="enf-header">
        <div>
            <h1 class="enf-title">🏥 S.O. — Sala de Observação</h1>
            <p class="enf-sub">{{ \Carbon\Carbon::today()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
        </div>
    </div>
    @include('louyout.flash')

    {{-- STATS --}}
    <div class="enf-stats">
        <div class="enf-stat es1">
            <div class="enf-stat-icon"><i class="feather icon-file-text"></i></div>
            <div class="enf-stat-num">{{ $totalPrescricoes }}</div>
            <div class="enf-stat-lbl">Prescrições Hoje</div>
        </div>
        <div class="enf-stat es2">
            <div class="enf-stat-icon"><i class="feather icon-alert-triangle"></i></div>
            <div class="enf-stat-num">{{ $totalUrgentes }}</div>
            <div class="enf-stat-lbl">Casos Urgentes</div>
        </div>
        <div class="enf-stat es3">
            <div class="enf-stat-icon"><i class="feather icon-shopping-cart"></i></div>
            <div class="enf-stat-num">{{ $reqPendentes }}</div>
            <div class="enf-stat-lbl">Req. Fármacos Pendentes</div>
        </div>
    </div>

    {{-- TABS --}}
    <div class="enf-tabs">
        <button class="enf-tab active" onclick="showEnfTab('prescricoes',this)">
            <i class="feather icon-file-text" style="font-size:13px;"></i> Prescrições Médicas
            @if($totalPrescricoes > 0)<span class="tok">{{ $totalPrescricoes }}</span>@endif
        </button>
        <button class="enf-tab" onclick="showEnfTab('requisicoes',this)">
            <i class="feather icon-shopping-cart" style="font-size:13px;"></i> Requisições de Fármacos
            @if($reqPendentes > 0)<span class="tb">{{ $reqPendentes }}</span>@endif
        </button>
    </div>

    {{-- PAINEL PRESCRIÇÕES --}}
    <div class="enf-panel active" id="panel-prescricoes">
        @if($prescricoes->isEmpty())
        <div class="empty-state">
            <div style="font-size:48px;margin-bottom:12px;">📋</div>
            <div style="font-size:16px;font-weight:700;color:#1a2e1a;">Nenhuma prescrição hoje</div>
            <div style="font-size:13px;color:#6b7280;margin-top:6px;">As prescrições emitidas pelos médicos aparecerão aqui.</div>
        </div>
        @else
        @foreach($prescricoes as $p)
        <div class="presc-card {{ $p->urgente ? 'urgente' : '' }}">
            <div class="presc-head">
                <div class="presc-av {{ $p->sexo === 'M' ? 'av-m' : 'av-f' }}">
                    {{ mb_strtoupper(mb_substr($p->nome, 0, 1)) }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                        <span style="font-size:15px;font-weight:800;color:#1a2e1a;">{{ $p->nome }}</span>
                        @if($p->urgente)
                            <span style="background:#fee2e2;color:#991b1b;padding:2px 9px;border-radius:20px;font-size:10px;font-weight:800;">⚡ URGENTE</span>
                        @endif
                        @php $esCls = ['em_espera'=>['bg'=>'#fef3c7','cor'=>'#92400e','txt'=>'Em Espera'],'em_consulta'=>['bg'=>'#dbeafe','cor'=>'#1d4ed8','txt'=>'Em Consulta'],'aguarda_exame'=>['bg'=>'#ede9fe','cor'=>'#5b21b6','txt'=>'Aguarda Exame'],'concluido'=>['bg'=>'#d1fae5','cor'=>'#065f46','txt'=>'Concluído']][$p->estado] ?? ['bg'=>'#f3f4f6','cor'=>'#6b7280','txt'=>ucfirst($p->estado)]; @endphp
                        <span style="background:{{ $esCls['bg'] }};color:{{ $esCls['cor'] }};padding:2px 9px;border-radius:20px;font-size:11px;font-weight:700;">{{ $esCls['txt'] }}</span>
                    </div>
                    <div style="font-size:12px;color:#6b7280;margin-top:3px;display:flex;gap:12px;flex-wrap:wrap;">
                        @if($p->data_nascimento)<span>{{ $p->sexo === 'M' ? '♂' : '♀' }} {{ \Carbon\Carbon::parse($p->data_nascimento)->age }} anos</span>@endif
                        @if($p->numero_processo)<span># {{ $p->numero_processo }}</span>@endif
                        <span>🩺 Dr. {{ $p->medico }}</span>
                        <span>🕐 {{ \Carbon\Carbon::parse($p->hora)->format('H:i') }}</span>
                    </div>
                </div>
            </div>
            <div class="presc-body">
                @if($p->diagnostico)
                <div style="background:#f0faf2;border:1px solid #d1fae5;border-radius:9px;padding:9px 13px;margin-bottom:12px;font-size:13px;color:#1a2e1a;">
                    <strong style="color:#1a6b2f;font-size:11px;text-transform:uppercase;letter-spacing:.5px;">Diagnóstico:</strong><br>
                    {{ $p->diagnostico }}
                </div>
                @endif
                @if($p->itens->isNotEmpty())
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;margin-bottom:8px;">Medicamentos Prescritos ({{ $p->itens->count() }})</div>
                <div class="presc-grid">
                    @foreach($p->itens as $item)
                    <div class="presc-med">
                        <div class="presc-med-nome">💊 {{ $item->medicamento }}</div>
                        <div class="presc-med-info">
                            @if($item->forma_farmaceutica || $item->dosagem)
                                {{ $item->forma_farmaceutica }}{{ $item->forma_farmaceutica && $item->dosagem ? ' · ' : '' }}{{ $item->dosagem }}<br>
                            @endif
                            @if($item->dose)<span class="presc-med-dose">{{ $item->dose }}</span>@endif
                            @if($item->frequencia) · {{ $item->frequencia }}@endif
                            @if($item->duracao) · {{ $item->duracao }}@endif
                        </div>
                        <div style="font-size:12px;font-weight:700;color:#1a6b2f;margin-top:4px;">Qtd: {{ $item->quantidade }}</div>
                        @if($item->instrucoes)
                        <div style="margin-top:4px;font-size:10px;color:#92400e;background:#fef3c7;border-radius:5px;padding:2px 6px;">📋 {{ $item->instrucoes }}</div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
                @if($p->observacao)
                <div style="margin-top:10px;padding:9px 13px;background:#fffbeb;border-radius:9px;border:1px solid #fde68a;font-size:12px;color:#92400e;">
                    <strong>Obs:</strong> {{ $p->observacao }}
                </div>
                @endif
            </div>
        </div>
        @endforeach
        @endif
    </div>

    {{-- PAINEL REQUISIÇÕES DE FÁRMACOS --}}
    <div class="enf-panel" id="panel-requisicoes">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
            <span style="font-size:14px;font-weight:600;color:#374151;">Requisições do seu departamento</span>
            <button onclick="abrirModalNovaReq()" class="qa-btn green-dark">
                <i class="feather icon-plus-circle"></i> Nova Requisição
            </button>
        </div>

        @if($requisicoes->isEmpty())
        <div class="empty-state">
            <div style="font-size:48px;margin-bottom:12px;">📦</div>
            <div style="font-size:16px;font-weight:700;color:#1a2e1a;">Nenhuma requisição enviada</div>
            <div style="font-size:13px;color:#6b7280;margin-top:6px;">Clique em "Nova Requisição" para solicitar fármacos à Farmácia.</div>
        </div>
        @else
        <div class="table-card">
            <div class="table-responsive">
                <table style="width:100%;border-collapse:collapse;font-size:13px;">
                    <thead>
                        <tr>
                            <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;color:#1a6b2f;background:#f0faf2;border-bottom:2px solid #d1fae5;">#</th>
                            <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;color:#1a6b2f;background:#f0faf2;border-bottom:2px solid #d1fae5;">Data</th>
                            <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;color:#1a6b2f;background:#f0faf2;border-bottom:2px solid #d1fae5;">Estado</th>
                            <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;color:#1a6b2f;background:#f0faf2;border-bottom:2px solid #d1fae5;">Atendido por</th>
                            <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;color:#1a6b2f;background:#f0faf2;border-bottom:2px solid #d1fae5;">Acções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requisicoes as $r)
                        <tr>
                            <td style="padding:10px 14px;border-bottom:1px solid #f3f4f6;">{{ $r->id }}</td>
                            <td style="padding:10px 14px;border-bottom:1px solid #f3f4f6;">{{ \Carbon\Carbon::parse($r->created_at)->format('d/m/Y H:i') }}</td>
                            <td style="padding:10px 14px;border-bottom:1px solid #f3f4f6;">
                                @if($r->estado === 'pendente')
                                    <span style="background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">⏳ Pendente</span>
                                @elseif($r->estado === 'atendida')
                                    <span style="background:#d1fae5;color:#065f46;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">✅ Atendida</span>
                                @else
                                    <span style="background:#fee2e2;color:#991b1b;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">❌ Rejeitada</span>
                                @endif
                            </td>
                            <td style="padding:10px 14px;border-bottom:1px solid #f3f4f6;">{{ $r->atendente ?? '—' }}</td>
                            <td style="padding:10px 14px;border-bottom:1px solid #f3f4f6;">
                                <div style="display:flex;gap:4px;">
                                    <a href="{{ route('enfermeiro.requisicao.pdf', $r->id) }}" class="qa-btn" style="padding:5px 10px;background:#ede9fe;color:#5b21b6;font-size:12px;" title="PDF">
                                        <i class="feather icon-download"></i>
                                    </a>
                                    @if($r->estado === 'pendente')
                                    <button onclick="carregarEdicaoReq({{ $r->id }})" class="qa-btn" style="padding:5px 10px;background:#dbeafe;color:#1d4ed8;font-size:12px;" title="Editar">
                                        <i class="feather icon-edit-2"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

</div>{{-- /enf-wrap --}}

{{-- MODAL NOVA / EDITAR REQUISIÇÃO --}}
<div id="modalReqEnf" class="modal-overlay" style="display:none;">
    <div style="background:#fff;border-radius:16px;width:100%;max-width:700px;box-shadow:0 20px 60px rgba(0,0,0,.2);overflow:hidden;max-height:90vh;display:flex;flex-direction:column;">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;background:#f0faf2;border-bottom:1px solid #d1fae5;flex-shrink:0;">
            <h5 id="modalReqEnfTitulo" style="margin:0;font-size:15px;font-weight:700;color:#1a2e1a;"><i class="feather icon-shopping-cart"></i> Nova Requisição de Fármacos</h5>
            <button onclick="fecharModalReq()" style="background:none;border:none;font-size:22px;cursor:pointer;color:#6b7280;line-height:1;">&times;</button>
        </div>
        <div style="padding:20px;overflow-y:auto;flex:1;">
            <div id="alertaBloqueadoEnf" style="display:none;background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;padding:12px 16px;margin-bottom:14px;color:#991b1b;font-size:13px;">
                <strong><i class="feather icon-alert-triangle"></i> Fármacos Bloqueados:</strong>
                <ul id="listaBloqueadosEnf" style="margin:6px 0 0 16px;"></ul>
            </div>
            <div style="margin-bottom:14px;">
                <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Observação (opcional)</label>
                <input type="text" id="obsGeralEnf" class="fc" style="width:100%;padding:9px 12px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:13px;" placeholder="Ex: Urgente, para paciente em S.O....">
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                <label style="font-size:12px;font-weight:600;color:#374151;">Fármacos Solicitados</label>
                <button type="button" onclick="addLinhaEnf()" style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:8px;background:#1a6b2f;color:#fff;border:none;font-size:12px;font-weight:600;cursor:pointer;">
                    <i class="feather icon-plus"></i> Adicionar
                </button>
            </div>
            <div id="listaItensEnf"></div>
            <div id="erroItensEnf" style="color:#dc2626;font-size:12px;margin-top:6px;display:none;">Adicione pelo menos um fármaco.</div>
        </div>
        <div style="padding:14px 20px;border-top:1px solid #f3f4f6;display:flex;justify-content:flex-end;gap:10px;flex-shrink:0;">
            <button onclick="fecharModalReq()" style="padding:8px 18px;border-radius:9px;border:1.5px solid #d1d5db;background:#fff;font-size:13px;font-weight:600;cursor:pointer;color:#374151;">Cancelar</button>
            <button onclick="submeterReqEnf()" id="btnSubmeterEnf" style="padding:8px 18px;border-radius:9px;border:none;background:#1a6b2f;color:#fff;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;">
                <i class="feather icon-send"></i> Enviar Requisição
            </button>
        </div>
    </div>
</div>

<style>
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;display:flex;align-items:center;justify-content:center;padding:16px;}
.table-card{background:#fff;border-radius:14px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.05);}
</style>

<script>
var farmacos  = @json($farmacos);
var urlEnf    = '{{ route("enfermeiro.requisicao.store") }}';
var urlUpdEnf = '{{ url("enfermeiro/requisicao") }}';
var csrf      = '{{ csrf_token() }}';
var bloqMap   = {};
farmacos.forEach(function(f){ bloqMap[f.id] = !!f.bloqueado; });

function showEnfTab(name, btn) {
    document.querySelectorAll('.enf-panel').forEach(function(p){ p.classList.remove('active'); });
    document.querySelectorAll('.enf-tab').forEach(function(b){ b.classList.remove('active'); });
    document.getElementById('panel-' + name).classList.add('active');
    btn.classList.add('active');
}

function abrirModalNovaReq() {
    document.getElementById('modalReqEnfTitulo').innerHTML = '<i class="feather icon-shopping-cart"></i> Nova Requisição de Fármacos';
    document.getElementById('reqIdEnf') && (document.getElementById('reqIdEnf').value = '');
    document.getElementById('obsGeralEnf').value = '';
    document.getElementById('listaItensEnf').innerHTML = '';
    document.getElementById('erroItensEnf').style.display = 'none';
    document.getElementById('alertaBloqueadoEnf').style.display = 'none';
    addLinhaEnf();
    document.getElementById('modalReqEnf').style.display = 'flex';
}

function fecharModalReq() { document.getElementById('modalReqEnf').style.display = 'none'; }

function addLinhaEnf(produtoId, quantidade, obs) {
    var idx = document.querySelectorAll('#listaItensEnf .item-linha').length;
    var optsDisp = '<optgroup label="— Disponíveis —">';
    var optsBloc = '<optgroup label="⛔ Bloqueados (não permitidos)">';
    farmacos.forEach(function(f) {
        var sel = (f.id == produtoId) ? 'selected' : '';
        var opt = '<option value="'+f.id+'" '+sel+(f.bloqueado?' data-bloqueado="1" style="color:#dc2626;"':'')+'>'
            +f.produto+' — '+f.apresentacao+(f.bloqueado?' [BLOQUEADO]':'')+'</option>';
        if (f.bloqueado) optsBloc += opt; else optsDisp += opt;
    });
    optsDisp += '</optgroup>'; optsBloc += '</optgroup>';
    var html = '<div class="item-linha" style="display:flex;gap:8px;align-items:center;margin-bottom:8px;background:#f9fafb;border-radius:9px;padding:8px 10px;">'
        +'<select class="sel-farm-enf" style="flex:2;padding:7px 10px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:13px;" required onchange="verificarBloqEnf(this)">'
        +'<option value="">— Seleccionar fármaco —</option>'+optsDisp+optsBloc+'</select>'
        +'<input type="number" min="1" value="'+(quantidade||1)+'" style="width:70px;padding:7px 10px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:13px;" required placeholder="Qtd">'
        +'<input type="text" value="'+(obs||'')+'" style="flex:1;padding:7px 10px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:13px;" placeholder="Obs.">'
        +'<button type="button" onclick="this.closest(\'.item-linha\').remove()" style="background:#fee2e2;border:none;border-radius:7px;width:30px;height:30px;cursor:pointer;color:#991b1b;flex-shrink:0;"><i class="feather icon-trash-2"></i></button>'
        +'<span class="aviso-bloq-enf" style="display:none;color:#dc2626;font-size:11px;white-space:nowrap;">⛔ Bloqueado</span>'
        +'</div>';
    document.getElementById('listaItensEnf').insertAdjacentHTML('beforeend', html);
}

function verificarBloqEnf(sel) {
    var aviso = sel.closest('.item-linha').querySelector('.aviso-bloq-enf');
    var pid = parseInt(sel.value);
    if (pid && bloqMap[pid]) { aviso.style.display='block'; sel.style.borderColor='#dc2626'; }
    else { aviso.style.display='none'; sel.style.borderColor=''; }
}

function submeterReqEnf() {
    document.getElementById('alertaBloqueadoEnf').style.display = 'none';
    var linhas = document.querySelectorAll('#listaItensEnf .item-linha');
    if (!linhas.length) { document.getElementById('erroItensEnf').style.display='block'; return; }
    document.getElementById('erroItensEnf').style.display = 'none';
    var itens = []; var ok = true;
    linhas.forEach(function(l){
        var pid = l.querySelector('select').value;
        var qty = l.querySelectorAll('input')[0].value;
        var obs = l.querySelectorAll('input')[1].value;
        if (!pid||!qty||qty<1) ok=false;
        itens.push({produto_id:pid,quantidade:qty,observacao_item:obs});
    });
    if (!ok) { alert('Preencha todos os campos.'); return; }
    var btn = document.getElementById('btnSubmeterEnf');
    btn.disabled=true; btn.innerHTML='<i class="feather icon-loader"></i> A enviar...';
    fetch(urlEnf,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
        body:JSON.stringify({observacao:document.getElementById('obsGeralEnf').value,itens:itens})})
    .then(function(r){return r.json().then(function(d){return {s:r.status,d:d};});})
    .then(function(res){
        btn.disabled=false; btn.innerHTML='<i class="feather icon-send"></i> Enviar Requisição';
        if (res.s===422&&res.d.bloqueados){
            var ul=document.getElementById('listaBloqueadosEnf');
            ul.innerHTML=res.d.bloqueados.map(function(n){return '<li>'+n+'</li>';}).join('');
            document.getElementById('alertaBloqueadoEnf').style.display='block'; return;
        }
        if (res.d.success){fecharModalReq();location.reload();}
        else{alert('Erro ao guardar.');}
    }).catch(function(){btn.disabled=false;btn.innerHTML='<i class="feather icon-send"></i> Enviar Requisição';alert('Erro de comunicação.');});
}

function carregarEdicaoReq(id) {
    fetch(urlUpdEnf+'/'+id+'/editar',{headers:{'Accept':'application/json','X-CSRF-TOKEN':csrf}})
    .then(function(r){return r.json();})
    .then(function(data){
        document.getElementById('modalReqEnfTitulo').innerHTML='<i class="feather icon-edit-2"></i> Editar Requisição #'+id;
        document.getElementById('obsGeralEnf').value=data.requisicao.observacao||'';
        document.getElementById('listaItensEnf').innerHTML='';
        document.getElementById('alertaBloqueadoEnf').style.display='none';
        data.itens.forEach(function(item){addLinhaEnf(item.produto_id,item.quantidade,item.observacao_item);});
        // override submit URL
        urlEnfEdit = id;
        document.getElementById('modalReqEnf').style.display='flex';
    });
}

document.getElementById('modalReqEnf').addEventListener('click',function(e){if(e.target===this)fecharModalReq();});
</script>

@endsection
