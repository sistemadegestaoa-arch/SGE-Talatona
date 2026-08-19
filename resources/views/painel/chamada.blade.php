<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Chamadas — Kifica</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --green-dark: #0f3d1e;
            --green-mid:  #1a6b2f;
            --green-light:#2d9e4a;
            --red:        #dc2626;
            --red-dark:   #991b1b;
        }

        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--green-dark);
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* ── CABEÇALHO ── */
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 40px;
            background: rgba(0,0,0,.25);
            border-bottom: 1px solid rgba(255,255,255,.08);
            flex-shrink: 0;
        }
        .brand { display:flex; align-items:center; gap:14px; }
        .brand-logo {
            width:48px; height:48px; border-radius:12px;
            background:rgba(255,255,255,.1);
            display:flex; align-items:center; justify-content:center;
            font-size:22px; font-weight:900; color:#fff;
        }
        .brand-name { font-size:20px; font-weight:800; letter-spacing:1px; }
        .brand-sub  { font-size:12px; opacity:.6; margin-top:2px; }
        .header-clock {
            font-size:36px; font-weight:900; letter-spacing:2px;
            font-variant-numeric: tabular-nums;
        }
        .header-date { font-size:13px; opacity:.6; text-align:right; margin-top:3px; }

        /* ── ÁREA PRINCIPAL ── */
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px 40px;
            position: relative;
        }

        /* ── CARD DE CHAMADA ── */
        .chamada-card {
            width: 100%;
            max-width: 900px;
            border-radius: 28px;
            padding: 60px 50px;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: all .5s ease;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.1);
        }

        .chamada-card.urgente {
            background: rgba(220,38,38,.12);
            border-color: rgba(220,38,38,.4);
        }

        /* Pulse ring para urgente */
        .chamada-card.urgente::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 32px;
            border: 3px solid #dc2626;
            animation: urgentePulse 1.2s ease-in-out infinite;
        }

        @keyframes urgentePulse {
            0%,100% { opacity:.3; transform:scale(1); }
            50%      { opacity:1;  transform:scale(1.01); }
        }

        .chamada-label {
            font-size:13px;
            font-weight:700;
            text-transform:uppercase;
            letter-spacing:3px;
            opacity:.6;
            margin-bottom:20px;
        }
        .chamada-label.urgente { color:#fca5a5; opacity:1; }

        .chamada-senha {
            font-size: clamp(80px, 18vw, 180px);
            font-weight: 900;
            line-height: 1;
            color: #fff;
            letter-spacing: -2px;
            text-shadow: 0 0 60px rgba(93,216,126,.4);
            animation: senhaEntrada .6s cubic-bezier(.34,1.56,.64,1) both;
        }
        .chamada-senha.urgente {
            color: #fca5a5;
            text-shadow: 0 0 60px rgba(220,38,38,.5);
        }

        @keyframes senhaEntrada {
            from { opacity:0; transform:scale(.6) translateY(30px); }
            to   { opacity:1; transform:scale(1) translateY(0); }
        }

        .chamada-nome {
            font-size: clamp(28px, 4vw, 56px);
            font-weight: 800;
            margin-top: 20px;
            color: #fff;
            letter-spacing: -.5px;
            animation: nomeEntrada .6s ease .2s both;
        }

        @keyframes nomeEntrada {
            from { opacity:0; transform:translateY(20px); }
            to   { opacity:1; transform:translateY(0); }
        }

        .chamada-hora {
            font-size:15px;
            opacity:.5;
            margin-top:12px;
            animation: nomeEntrada .6s ease .4s both;
        }

        .urgente-badge {
            display:inline-flex;
            align-items:center;
            gap:8px;
            background: rgba(220,38,38,.25);
            border: 1px solid rgba(220,38,38,.5);
            color:#fca5a5;
            padding:6px 18px;
            border-radius:30px;
            font-size:14px;
            font-weight:800;
            letter-spacing:1px;
            margin-bottom:16px;
            animation: nomeEntrada .4s ease both;
        }

        /* ── ESTADO AGUARDA ── */
        .aguarda {
            text-align:center;
            animation: fadeIn 1s ease both;
        }

        @keyframes fadeIn {
            from { opacity:0; } to { opacity:1; }
        }

        .aguarda-icon {
            font-size:80px;
            display:block;
            margin-bottom:20px;
            animation: iconFloat 3s ease-in-out infinite;
        }

        @keyframes iconFloat {
            0%,100% { transform:translateY(0); }
            50%      { transform:translateY(-12px); }
        }

        .aguarda-titulo {
            font-size:28px;
            font-weight:700;
            opacity:.6;
        }

        .aguarda-sub {
            font-size:15px;
            opacity:.35;
            margin-top:8px;
        }

        /* ── BARRA INFERIOR ── */
        footer {
            flex-shrink:0;
            padding:14px 40px;
            background:rgba(0,0,0,.25);
            border-top:1px solid rgba(255,255,255,.06);
            display:flex;
            align-items:center;
            justify-content:space-between;
        }

        .footer-info {
            font-size:12px;
            opacity:.4;
        }

        /* Fila de espera pequena */
        .fila-lista {
            display:flex;
            gap:8px;
            flex-wrap:wrap;
            justify-content:flex-end;
        }

        .fila-item {
            background:rgba(255,255,255,.08);
            border-radius:8px;
            padding:4px 12px;
            font-size:13px;
            font-weight:700;
            opacity:.7;
        }

        .fila-item.urgente-fila {
            background:rgba(220,38,38,.2);
            color:#fca5a5;
        }

        /* Círculos de fundo decorativos */
        .bg-circle {
            position:fixed;
            border-radius:50%;
            background:rgba(255,255,255,.02);
            pointer-events:none;
        }
        .bg-c1 { width:500px; height:500px; top:-150px; right:-150px; }
        .bg-c2 { width:350px; height:350px; bottom:-100px; left:-80px; }

        /* Barra de progresso da chamada */
        .progresso-wrap {
            position:absolute;
            bottom:0; left:0; right:0;
            height:5px;
            background:rgba(255,255,255,.1);
            border-radius:0 0 28px 28px;
            overflow:hidden;
        }
        .progresso-bar {
            height:100%;
            background:linear-gradient(90deg, #5dd87e, #1a6b2f);
            border-radius:0 0 28px 28px;
            transition: width linear;
        }
        .progresso-bar.urgente {
            background:linear-gradient(90deg, #fca5a5, #dc2626);
        }
    </style>
</head>
<body>

<div class="bg-circle bg-c1"></div>
<div class="bg-circle bg-c2"></div>

{{-- CABEÇALHO --}}
<header>
    <div class="brand">
        <div class="brand-logo">K</div>
        <div>
            <div class="brand-name">KIFICA</div>
            <div class="brand-sub">Centro de Referência · Sala de Espera</div>
        </div>
    </div>
    <div style="text-align:right;">
        <div class="header-clock" id="relógio">--:--:--</div>
        <div class="header-date" id="data-hoje"></div>
    </div>
</header>

{{-- ÁREA PRINCIPAL --}}
<main id="main-area">
    {{-- Estado inicial: aguarda chamada --}}
    <div class="aguarda" id="estado-aguarda">
        <span class="aguarda-icon">🏥</span>
        <div class="aguarda-titulo">A aguardar chamada...</div>
        <div class="aguarda-sub">O painel actualizará automaticamente</div>
    </div>

    {{-- Card de chamada (oculto inicialmente) --}}
    <div class="chamada-card" id="chamada-card" style="display:none;">
        <div class="urgente-badge" id="urgente-badge" style="display:none;">⚡ URGENTE — Prioridade Máxima</div>
        <div class="chamada-label" id="chamada-label">POR FAVOR DIRIJA-SE AO CONSULTÓRIO</div>
        <div class="chamada-senha" id="chamada-senha">---</div>
        <div class="chamada-nome"  id="chamada-nome">---</div>
        <div class="chamada-hora"  id="chamada-hora"></div>
        <div class="progresso-wrap">
            <div class="progresso-bar" id="progresso-bar"></div>
        </div>
    </div>
</main>

{{-- RODAPÉ --}}
<footer>
    <div class="footer-info">Painel de Chamadas · Actualização automática em tempo real</div>
    <div class="fila-lista" id="fila-lista"></div>
</footer>

<script>
// ── Relógio ──────────────────────────────────────────────────────────────────
function atualizarRelogio() {
    const agora = new Date();
    document.getElementById('relógio').textContent =
        agora.toLocaleTimeString('pt-AO', { hour:'2-digit', minute:'2-digit', second:'2-digit' });
    document.getElementById('data-hoje').textContent =
        agora.toLocaleDateString('pt-AO', { weekday:'long', day:'2-digit', month:'long', year:'numeric' });
}
setInterval(atualizarRelogio, 1000);
atualizarRelogio();

// ── Text-to-Speech ───────────────────────────────────────────────────────────
function falar(nome, senha, urgente) {
    if (!window.speechSynthesis) return;
    window.speechSynthesis.cancel();

    const urgTxt = urgente ? 'Atenção, chamada urgente! ' : '';
    const texto  = `${urgTxt}Senha ${senha.split('').join(' ')} — ${nome}. Por favor dirija-se ao consultório.`;

    const msg = new SpeechSynthesisUtterance(texto);
    msg.lang  = 'pt-PT';
    msg.rate  = 0.88;
    msg.pitch = urgente ? 1.3 : 1.0;
    msg.volume = 1;

    // Repetir 2x
    msg.onend = () => {
        setTimeout(() => {
            const msg2 = new SpeechSynthesisUtterance(texto);
            msg2.lang  = msg.lang;
            msg2.rate  = msg.rate;
            msg2.pitch = msg.pitch;
            window.speechSynthesis.speak(msg2);
        }, 800);
    };

    window.speechSynthesis.speak(msg);
}

// ── Som de atenção ───────────────────────────────────────────────────────────
function tocarSom(urgente) {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const notas = urgente
            ? [880, 880, 1100, 880]
            : [660, 880, 660];

        notas.forEach((freq, i) => {
            const o = ctx.createOscillator();
            const g = ctx.createGain();
            o.connect(g); g.connect(ctx.destination);
            o.type = urgente ? 'square' : 'sine';
            o.frequency.setValueAtTime(freq, ctx.currentTime + i * 0.22);
            g.gain.setValueAtTime(0.5, ctx.currentTime + i * 0.22);
            g.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + i * 0.22 + 0.2);
            o.start(ctx.currentTime + i * 0.22);
            o.stop(ctx.currentTime + i * 0.22 + 0.22);
        });
    } catch(e) {}
}

// ── Mostrar chamada ──────────────────────────────────────────────────────────
var temporizador = null;
var duracao = 18000; // 18 segundos visível

function mostrarChamada(dados) {
    const card   = document.getElementById('chamada-card');
    const aguarda= document.getElementById('estado-aguarda');
    const senha  = document.getElementById('chamada-senha');
    const nome   = document.getElementById('chamada-nome');
    const hora   = document.getElementById('chamada-hora');
    const label  = document.getElementById('chamada-label');
    const badge  = document.getElementById('urgente-badge');
    const barra  = document.getElementById('progresso-bar');

    // Resetar animações
    card.style.display = 'none';
    card.offsetHeight; // force reflow

    // Aplicar dados
    senha.textContent = dados.senha;
    nome.textContent  = dados.nome;
    hora.textContent  = 'Chamado às ' + dados.hora;

    if (dados.urgente) {
        card.classList.add('urgente');
        senha.classList.add('urgente');
        label.classList.add('urgente');
        badge.style.display = 'inline-flex';
    } else {
        card.classList.remove('urgente');
        senha.classList.remove('urgente');
        label.classList.remove('urgente');
        badge.style.display = 'none';
    }

    // Barra de progresso
    barra.className = 'progresso-bar' + (dados.urgente ? ' urgente' : '');
    barra.style.width = '100%';
    barra.style.transition = 'none';
    barra.offsetHeight;
    barra.style.transition = `width ${duracao}ms linear`;
    barra.style.width = '0%';

    aguarda.style.display = 'none';
    card.style.display    = 'block';

    // Som + voz
    tocarSom(dados.urgente);
    setTimeout(() => falar(dados.nome, dados.senha, dados.urgente), 400);

    // Desaparece após duração
    clearTimeout(temporizador);
    temporizador = setTimeout(() => {
        card.style.opacity = '0';
        card.style.transition = 'opacity 1s ease';
        setTimeout(() => {
            card.style.display   = 'none';
            card.style.opacity   = '1';
            card.style.transition= '';
            aguarda.style.display= 'block';
        }, 1000);
    }, duracao);
}

// ── Actualizar fila de espera ─────────────────────────────────────────────────
function atualizarFila() {
    fetch('{{ url("api/fila-espera") }}')
        .then(r => r.json())
        .then(data => {
            const div = document.getElementById('fila-lista');
            if (!data.length) { div.innerHTML = ''; return; }
            div.innerHTML = data.slice(0, 8).map(p =>
                `<div class="fila-item ${p.urgente ? 'urgente-fila' : ''}">
                    ${p.urgente ? '⚡ ' : ''}${p.senha || '#'+p.id}
                </div>`
            ).join('');
        })
        .catch(() => {});
}
setInterval(atualizarFila, 10000);
atualizarFila();

// ── SSE ──────────────────────────────────────────────────────────────────────
function conectarSSE() {
    const evtSource = new EventSource('{{ route("chamadas.sse") }}');

    evtSource.addEventListener('chamada', function(e) {
        try {
            const dados = JSON.parse(e.data);
            if (dados.reconectar) { evtSource.close(); setTimeout(conectarSSE, 2000); return; }
            mostrarChamada(dados);
        } catch(err) {}
    });

    evtSource.onerror = function() {
        evtSource.close();
        setTimeout(conectarSSE, 5000);
    };
}

// Iniciar SSE após interacção do utilizador (necessário para o áudio funcionar)
document.body.addEventListener('click', function onFirstClick() {
    document.body.removeEventListener('click', onFirstClick);
}, { once: true });

conectarSSE();
</script>
</body>
</html>
