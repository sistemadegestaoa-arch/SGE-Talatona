<style>
.flash-wrap { position:fixed; top:20px; right:20px; z-index:99998; display:flex; flex-direction:column; gap:10px; pointer-events:none; }
.flash-toast {
    display:flex; align-items:center; gap:12px;
    padding:14px 18px; border-radius:13px;
    font-size:13px; font-weight:500;
    box-shadow:0 8px 24px rgba(0,0,0,.12);
    pointer-events:all; min-width:280px; max-width:380px;
    animation: toastIn .35s cubic-bezier(.34,1.56,.64,1) both;
    font-family:'Inter',sans-serif;
}
.flash-toast.hiding { animation: toastOut .3s ease forwards; }
.flash-ok  { background:#fff; border-left:4px solid #1a6b2f; color:#1a2e1a; }
.flash-err { background:#fff; border-left:4px solid #ef4444; color:#1a2e1a; }
.flash-warn{ background:#fff; border-left:4px solid #f59e0b; color:#1a2e1a; }
.flash-icon { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:15px; }
.flash-ok   .flash-icon { background:#d1fae5; color:#1a6b2f; }
.flash-err  .flash-icon { background:#fee2e2; color:#ef4444; }
.flash-warn .flash-icon { background:#fef3c7; color:#f59e0b; }
.flash-msg  { flex:1; line-height:1.4; }
.flash-close { background:none; border:none; cursor:pointer; color:#9ca3af; font-size:16px; padding:0; line-height:1; flex-shrink:0; }
.flash-close:hover { color:#374151; }
.flash-progress { position:absolute; bottom:0; left:0; height:3px; border-radius:0 0 0 13px; }
.flash-ok   .flash-progress { background:#1a6b2f; animation: toastProgress 4s linear forwards; }
.flash-err  .flash-progress { background:#ef4444; animation: toastProgress 8s linear forwards; }
.flash-warn .flash-progress { background:#f59e0b; animation: toastProgress 4s linear forwards; }

@keyframes toastIn  { from { opacity:0; transform:translateX(40px) scale(.95); } to { opacity:1; transform:translateX(0) scale(1); } }
@keyframes toastOut { from { opacity:1; transform:translateX(0); } to { opacity:0; transform:translateX(40px); } }
@keyframes toastProgress { from { width:100%; } to { width:0%; } }
</style>

<div class="flash-wrap" id="flash-wrap"></div>

<script>
(function() {
    var messages = [
        @if(session('success'))
            { type: 'ok',   icon: 'icon-check-circle', text: @json(session('success')) },
        @endif
        @if(session('error'))
            { type: 'err',  icon: 'icon-alert-circle',   text: @json(session('error')) },
        @endif
        @if(session('warning'))
            { type: 'warn', icon: 'icon-alert-triangle', text: @json(session('warning')) },
        @endif
    ];

    function showToast(msg) {
        var wrap = document.getElementById('flash-wrap');
        var el = document.createElement('div');
        el.className = 'flash-toast flash-' + msg.type;
        el.style.position = 'relative';
        el.style.overflow = 'hidden';
        el.innerHTML =
            '<div class="flash-icon"><i class="feather ' + msg.icon + '"></i></div>' +
            '<div class="flash-msg">' + msg.text + '</div>' +
            '<button class="flash-close" onclick="closeToast(this)">&#x2715;</button>' +
            '<div class="flash-progress"></div>';
        wrap.appendChild(el);

        // Auto-remove após 4s (erros ficam mais tempo)
        var duration = msg.type === 'err' ? 8000 : 4000;
        setTimeout(function() { closeToast(el.querySelector('.flash-close')); }, duration);
    }

    window.closeToast = function(btn) {
        var toast = btn.closest ? btn.closest('.flash-toast') : btn.parentElement;
        if (!toast || toast.classList.contains('hiding')) return;
        toast.classList.add('hiding');
        setTimeout(function() { if (toast.parentNode) toast.parentNode.removeChild(toast); }, 300);
    };

    // Mostrar após o DOM estar pronto
    document.addEventListener('DOMContentLoaded', function() {
        messages.forEach(function(m) { showToast(m); });
    });
})();
</script>
