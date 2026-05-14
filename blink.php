<?php
require_once 'includes/config.php';
$pageTitle = 'Blink Galaxy - NYX';
require_once 'includes/header.php';
?>
<div class="container">
<div class="page-content">

<!-- HERO BLINK -->
<div style="text-align:center;padding:3rem 0 2rem">
  <div style="display:inline-block;background:rgba(200,216,240,0.06);border:1px solid rgba(200,216,240,0.15);border-radius:20px;padding:0.4rem 1.2rem;font-size:0.78rem;font-weight:700;letter-spacing:0.15em;color:var(--accent);margin-bottom:1.5rem;text-transform:uppercase">
    ✦ Powered by SKALE Network
  </div>  
  <h1 style="font-family:'Cinzel',serif;font-size:clamp(2rem,5vw,3.5rem);font-weight:900;color:var(--moon);letter-spacing:0.1em;margin-bottom:0.8rem">
    BLINK GALAXY
  </h1>
  <p style="color:var(--muted);font-size:1rem;max-width:520px;margin:0 auto 0.6rem;line-height:1.8">
    La plataforma de juegos Web3 del ecosistema. NFTs, economía propia y misiones infinitas — todo desde NYX.
  </p>
  <p style="color:var(--accent);font-size:0.85rem;letter-spacing:0.1em;text-transform:uppercase">
    Free to play · NFT-powered · Player-driven
  </p>
</div>

<!-- STATS -->
<div class="stats-grid" style="max-width:700px;margin:0 auto 3rem">
  <div class="stat-card" style="text-align:center">
    <div class="stat-label">Juegos activos</div>
    <div class="stat-value">2</div>
  </div>
  <div class="stat-card" style="text-align:center">
    <div class="stat-label">$BG Supply</div>
    <div class="stat-value">10B</div>
  </div>
  <div class="stat-card" style="text-align:center">
    <div class="stat-label">Gas fees</div>
    <div class="stat-value" style="font-size:1.2rem">Zero</div>
  </div>
  <div class="stat-card" style="text-align:center">
    <div class="stat-label">Blockchain</div>
    <div class="stat-value" style="font-size:1.2rem">SKALE</div>
  </div>
</div>

<!-- DIVISOR -->
<div style="display:flex;align-items:center;gap:1rem;margin-bottom:2rem;color:var(--muted);font-size:0.75rem;letter-spacing:0.14em;text-transform:uppercase">
  <div style="flex:1;height:1px;background:linear-gradient(90deg,transparent,rgba(200,216,240,0.18),transparent)"></div>
  🎮 Juegos disponibles
  <div style="flex:1;height:1px;background:linear-gradient(90deg,transparent,rgba(200,216,240,0.18),transparent)"></div>
</div>

<!-- JUEGOS -->
<div class="grid-2" style="gap:2rem;margin-bottom:3rem">

  <!-- RACERLOOP -->
  <div class="card" style="overflow:hidden">
    <div style="height:220px;background:linear-gradient(135deg,#0a0a1a,#1a0a2a,#0a1a3a);display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden">
      <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 30% 50%,rgba(100,50,200,0.3),transparent 60%)"></div>
      <div style="text-align:center;position:relative;z-index:1">
        <div style="font-size:4rem;margin-bottom:0.5rem">🏎️</div>
        <div style="font-family:'Cinzel',serif;font-size:1.4rem;font-weight:900;color:#fff;letter-spacing:0.1em">RACERLOOP</div>
        <div style="font-size:0.75rem;color:rgba(255,255,255,0.5);letter-spacing:0.12em;text-transform:uppercase;margin-top:0.3rem">Racing Game</div>
      </div>
    </div>
    <div class="card-body">
      <div style="display:flex;gap:0.5rem;margin-bottom:1rem;flex-wrap:wrap">
        <span style="background:rgba(100,50,200,0.15);border:1px solid rgba(100,50,200,0.3);color:#a78bfa;font-size:0.72rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:20px;text-transform:uppercase">Racing</span>
        <span style="background:rgba(200,216,240,0.08);border:1px solid rgba(200,216,240,0.15);color:var(--muted);font-size:0.72rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:20px;text-transform:uppercase">NFT</span>
        <span style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.25);color:#4ade80;font-size:0.72rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:20px;text-transform:uppercase">Free to Play</span>
      </div>
      <p style="color:var(--muted);font-size:0.9rem;line-height:1.7;margin-bottom:1.5rem">
        Un juego de carreras Web3 donde tus vehiculos son NFTs reales. Compite, gana $BG y domina las pistas del universo Blink Galaxy.
      </p>
      <div style="display:flex;gap:0.8rem">
        <a href="https://blinkgalaxy.com/racing/racerloop" target="_blank"
           class="btn btn-primary" style="flex:2;justify-content:center">
          🚀 Jugar ahora
        </a>
        <a href="https://blinkgalaxy.com/racing/racerloop" target="_blank"
           class="btn btn-secondary" style="flex:1;justify-content:center">
          Ver más
        </a>
      </div>
    </div>
  </div>

  <!-- OUTER RING -->
  <div class="card" style="overflow:hidden">
    <div style="height:220px;background:linear-gradient(135deg,#0a1a0a,#0a2a1a,#1a2a0a);display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden">
      <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 70% 50%,rgba(50,200,100,0.25),transparent 60%)"></div>
      <div style="text-align:center;position:relative;z-index:1">
        <div style="font-size:4rem;margin-bottom:0.5rem">⚔️</div>
        <div style="font-family:'Cinzel',serif;font-size:1.4rem;font-weight:900;color:#fff;letter-spacing:0.1em">OUTER RING</div>
        <div style="font-size:0.75rem;color:rgba(255,255,255,0.5);letter-spacing:0.12em;text-transform:uppercase;margin-top:0.3rem">MMO Game</div>
      </div>
    </div>
    <div class="card-body">
      <div style="display:flex;gap:0.5rem;margin-bottom:1rem;flex-wrap:wrap">
        <span style="background:rgba(50,200,100,0.12);border:1px solid rgba(50,200,100,0.3);color:#4ade80;font-size:0.72rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:20px;text-transform:uppercase">MMO</span>
        <span style="background:rgba(200,216,240,0.08);border:1px solid rgba(200,216,240,0.15);color:var(--muted);font-size:0.72rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:20px;text-transform:uppercase">NFT</span>
        <span style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.25);color:#4ade80;font-size:0.72rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:20px;text-transform:uppercase">Free to Play</span>
      </div>
      <p style="color:var(--muted);font-size:0.9rem;line-height:1.7;margin-bottom:1.5rem">
        Un MMO de ciencia ficción donde tus armas, naves y personajes son NFTs. Comercia en el marketplace, completa misiones y conquista el universo.
      </p>
      <div style="display:flex;gap:0.8rem">
        <a href="https://blinkgalaxy.com/mmo/outer-ring" target="_blank"
           class="btn btn-primary" style="flex:2;justify-content:center">
          🚀 Jugar ahora
        </a>
        <a href="https://dapp.outerringmmo.com/marketplace" target="_blank"
           class="btn btn-secondary" style="flex:1;justify-content:center">
          Marketplace
        </a>
      </div>
    </div>
  </div>

</div>

<!-- PAGAR CON $BG -->
<div class="card" style="margin-bottom:2rem;border-color:rgba(100,60,200,0.3)">
  <div class="card-body" style="text-align:center;padding:2.5rem">
    <div style="display:inline-block;background:rgba(100,60,200,0.1);border:1px solid rgba(100,60,200,0.3);border-radius:20px;padding:0.4rem 1.2rem;font-size:0.78rem;font-weight:700;letter-spacing:0.15em;color:#a78bfa;margin-bottom:1.2rem;text-transform:uppercase">
      ⬡ Nuevo — Pago Web3
    </div>
    <h2 style="font-family:'Cinzel',serif;font-size:1.3rem;font-weight:700;color:var(--moon);margin-bottom:0.8rem;letter-spacing:0.08em">
      Paga tus compras con <span style="color:#a78bfa">$BG</span>
    </h2>
    <p style="color:var(--muted);max-width:520px;margin:0 auto 1.5rem;line-height:1.8;font-size:0.95rem">
      NYX acepta pagos en <strong style="color:#a78bfa">$BG Token</strong> — la moneda nativa del ecosistema Blink Galaxy. Conecta tu wallet y paga tus productos favoritos directamente desde la blockchain, sin intermediarios y sin gas fees.
    </p>

    <!-- WALLETS COMPATIBLES -->
    <div style="display:flex;gap:0.8rem;justify-content:center;flex-wrap:wrap;margin-bottom:1.8rem">
      <?php
      $wallets = [
        ['name'=>'MetaMask',       'emoji'=>'🦊'],
        ['name'=>'WalletConnect',  'emoji'=>'🔗'],
        ['name'=>'Trust Wallet',   'emoji'=>'🛡️'],
        ['name'=>'Binance Wallet', 'emoji'=>'🟡'],
        ['name'=>'SafePal',        'emoji'=>'🔐'],
      ];
      foreach ($wallets as $w): ?>
        <div style="background:rgba(200,216,240,0.05);border:1px solid rgba(200,216,240,0.1);border-radius:10px;padding:0.5rem 1rem;font-size:0.82rem;color:var(--muted)">
          <?= $w['emoji'] ?> <?= $w['name'] ?>
        </div>
      <?php endforeach; ?>
    </div>

    <a href="https://portal.blinkgalaxy.com" target="_blank"
       class="btn" style="background:linear-gradient(135deg,#7c3aed,#a78bfa);color:#fff;padding:0.85rem 2.5rem;font-size:1rem;box-shadow:0 0 24px rgba(124,58,237,0.35)">
      ⬡ Conectar wallet y pagar con $BG
    </a>
    <p style="font-size:0.75rem;color:var(--muted);margin-top:1rem">
      Powered by SKALE Network · Zero gas fees · Transacciones instantáneas
    </p>
  </div>
</div>

<!-- ECOSISTEMA -->
<div class="card" style="margin-bottom:2rem">
  <div class="card-body" style="text-align:center;padding:2.5rem">
    <h2 style="font-family:'Cinzel',serif;font-size:1.3rem;font-weight:700;color:var(--moon);margin-bottom:0.8rem;letter-spacing:0.08em">
      ✦ El ecosistema Blink Galaxy
    </h2>
    <p style="color:var(--muted);max-width:580px;margin:0 auto 2rem;line-height:1.8;font-size:0.95rem">
      Una sola identidad para todo — tu <strong style="color:var(--accent)">Blink Passport</strong> te da acceso a todos los juegos, assets NFT, wallet y marketplace del ecosistema. Sin gas fees, con economía propia en $BG.
    </p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
      <a href="https://blinkgalaxy.com/signup" target="_blank" class="btn btn-primary" style="padding:0.75rem 2rem">
        Crear Blink Passport gratis
      </a>
      <a href="https://blinkgalaxy.com/games" target="_blank" class="btn btn-secondary" style="padding:0.75rem 2rem">
        Explorar todos los juegos
      </a>
      <a href="https://portal.blinkgalaxy.com" target="_blank" class="btn btn-secondary" style="padding:0.75rem 2rem">
        Entrar al Portal
      </a>
    </div>
  </div>
</div>

<p style="text-align:center;font-size:0.78rem;color:var(--muted)">
  NYX no es afiliado oficial de Blink Galaxy. Los juegos y assets son propiedad de SOREDI GAMES.
  <a href="https://blinkgalaxy.com" target="_blank" style="color:var(--accent)">blinkgalaxy.com</a>
</p>

</div>
</div>
<?php require_once 'includes/footer.php'; ?>