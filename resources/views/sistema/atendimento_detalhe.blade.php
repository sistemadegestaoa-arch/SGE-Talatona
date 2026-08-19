@extends('louyout.app')
@section('conteodo')
@include('louyout.styles')

<style>
.det-layout { display:grid; grid-template-columns:1fr 300px; gap:20px; align-items:start; }
.f-card { background:#fff; border-radius:16px; border:1px solid #e5e7eb; box-shadow:0 2px 10px rgba(0,0,0,.05); overflow:hidden; margin-bottom:20px; }
.f-card-header { display:flex; align-items:center; gap:10px; padding:15px 22px; background:#f0faf2; border-bottom:2px solid #d1fae5; }
.f-card-header i    { font-size:15px; color:#1a6b2f; }
.f-card-header span { font-size:14px; font-weight:700; color:#1a6b2f; }
.f-card-body { padding:22px; }
.info-row { display:flex; justify-content:space-between; align-items:center; padding:9px 0; border-bottom:1px solid #f3f4f6; font-size:13px; }
.info-row:last-child { border-bottom:none; }
.info-row .lbl { font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.4px; color:#9ca3af; }
.info-row .val { font-weight:600; color:#1a2e1a; }
.med-table { width:100%; border-collapse:collapse; }
.med-table th { padding:10px 14px; text-align:left; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.4px; color:#1a6b2f; background:#f0faf2; border-bottom:2px solid #d1fae5; }
.med-table td { padding:11px 14px; border-bottom:1px solid #f3f4f6; font-size:13px; color:#374151; vertical-align:middle; }
.med-table tr:last-child td { border-bottom:none; }
.utente-avatar { width:56px; height:56px; border-radius:50%; background:linear-gradient(135deg,#1a6b2f,#2d9e4a); display:flex; align-items:center; justify-content:center; color:#fff; font-size:22px; font-weight:700; margin:0 auto 12px; }
@media(max-width:768px){ .det-layout{ grid-template-columns:1fr; } }
</style>

<div class="page-header-bar">
    <div>
        <h4 class="page-title"><i class="feather icon-user-check" style="color:#1a6b2f;margin-right:8px;"></i>Detalhe do Atendimento</h4>
        <p class="page-sub">Atendimento #{{ $atend->id }}</p>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('atendimento.pdf', $atend->id) }}" class="btn-new" target="_blank">
            <i class="feather icon-printer"></i> Imprimir
        </a>
        <a href="{{ route('atendimento.index') }}" class="btn-back">
            <i class="feather icon-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="det-layout">

    {{-- COLUNA PRINCIPAL --}}
    <div>
        <div class="f-card">
            <div class="f-card-header">
                <i class="feather icon-package"></i>
                <span>Medicamentos Dispensados</span>
            </div>
            <div style="overflow-x:auto;">
                <table class="med-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Medicamento</th>
                            <th>Apresentação</th>
                            <th>Lote</th>
                            <th>Quantidade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($itens as $i => $item)
                        <tr>
                            <td style="color:#9ca3af;">{{ $i+1 }}</td>
                            <td><strong>{{ $item->produto }}</strong></td>
                            <td style="color:#6b7280;">{{ $item->apresentacao ?? '—' }}</td>
                            <td><span class="code-badge">{{ $item->lote_num }}</span></td>
                            <td>
                                <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 12px;background:#d1fae5;color:#065f46;border-radius:20px;font-size:12px;font-weight:700;">
                                    {{ $item->quantidade }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($atend->observacao)
        <div class="f-card">
            <div class="f-card-header">
                <i class="feather icon-file-text"></i>
                <span>Observações</span>
            </div>
            <div class="f-card-body">
                <p style="font-size:13px;color:#374151;line-height:1.6;margin:0;">{{ $atend->observacao }}</p>
            </div>
        </div>
        @endif
    </div>

    {{-- COLUNA LATERAL --}}
    <div>
        <div class="f-card">
            <div class="f-card-body" style="text-align:center;padding-top:24px;">
                <div class="utente-avatar">{{ strtoupper(substr($atend->utente,0,1)) }}</div>
                <div style="font-size:16px;font-weight:700;color:#1a2e1a;">{{ $atend->utente }}</div>
                @if($atend->processo)
                <div style="font-size:12px;color:#6b7280;margin-top:4px;">Processo: {{ $atend->processo }}</div>
                @endif
            </div>
            <div class="f-card-body" style="padding-top:0;">
                <div class="info-row">
                    <span class="lbl">Data</span>
                    <span class="val">{{ \Carbon\Carbon::parse($atend->data)->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Técnico</span>
                    <span class="val">{{ $atend->tecnico }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Departamento</span>
                    <span class="val">{{ $atend->departamento }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Itens dispensados</span>
                    <span class="val" style="color:#1a6b2f;">{{ $itens->count() }}</span>
                </div>
                @if($atend->requisicao_id)
                <div class="info-row">
                    <span class="lbl">Requisição</span>
                    <span class="val">#{{ $atend->requisicao_id }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection
