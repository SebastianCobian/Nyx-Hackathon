<?php
require_once 'includes/config.php';
requireLogin();

$orderId = (int)($_GET['id'] ?? 0);
if (!$orderId) { header('Location: index.php'); exit; }

//Verificar que la orden pertenece al usuario
$db = getDB();
$stmt = $db->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$orderId, $_SESSION['user_id']]);
$order = $stmt->fetch();
if (!$order) { header('Location: index.php'); exit; }

$pageTitle = 'Pedido confirmado - NYX';
require_once 'includes/header.php';
?>

<style>
/* OVERLAY DE CELEBRACION */
.success-overlay {
  position: fixed; inset: 0; z-index: 500;
  background: var(--bg);
  display: flex; align-items: center; justify-content: center;
  flex-direction: column;
  animation: fadeOutOverlay 0.8s ease 3.5s forwards;
}
@keyframes fadeOutOverlay {
  0%   { opacity: 1; pointer-events: all; }
  100% { opacity: 0; pointer-events: none; }
}

/* LOGO CENTRAL */
.success-logo {
  width: 130px; height: 130px; object-fit: contain;
  filter: drop-shadow(0 0 40px rgba(200,216,240,0.8));
  animation: logoPop 0.6s cubic-bezier(0.34,1.56,0.64,1) 0.3s both;
}
@keyframes logoPop {
  0%   { transform: scale(0); opacity: 0; }
  100% { transform: scale(1); opacity: 1; }
}

.success-text {
  font-family: 'Cinzel', serif;
  font-size: clamp(1.5rem, 4vw, 2.5rem);
  font-weight: 900;
  color: var(--moon);
  letter-spacing: 0.15em;
  margin-top: 1.5rem;
  animation: fadeUp 0.6s ease 0.8s both;
}
.success-sub {
  color: var(--muted);
  font-size: 0.95rem;
  margin-top: 0.5rem;
  letter-spacing: 0.05em;
  animation: fadeUp 0.6s ease 1s both;
}
.success-order {
  font-family: 'Cinzel', serif;
  font-size: 1.1rem;
  color: var(--accent);
  margin-top: 0.8rem;
  animation: fadeUp 0.6s ease 1.2s both;
}
@keyframes fadeUp {
  0%   { opacity: 0; transform: translateY(20px); }
  100% { opacity: 1; transform: translateY(0); }
}

/* ESTRELLAS QUE CAEN */
.star-particle {
  position: fixed;
  top: -20px;
  font-size: 1.2rem;
  animation: fallStar linear forwards;
  pointer-events: none;
  z-index: 501;
}
@keyframes fallStar {
  0%   { transform: translateY(0) rotate(0deg); opacity: 1; }
  100% { transform: translateY(110vh) rotate(720deg); opacity: 0; }
}

/* CONTENIDO PRINCIPAL */
.success-content {
  opacity: 0;
  animation: fadeIn 0.8s ease 4s forwards;
}
@keyframes fadeIn {
  0%   { opacity: 0; transform: translateY(30px); }
  100% { opacity: 1; transform: translateY(0); }
}

.success-card {
  max-width: 560px;
  margin: 0 auto;
  text-align: center;
}
.checkmark {
  width: 80px; height: 80px;
  background: rgba(34,197,94,0.1);
  border: 2px solid rgba(34,197,94,0.4);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 2.2rem;
  margin: 0 auto 1.5rem;
  box-shadow: 0 0 30px rgba(34,197,94,0.2);
}
</style>

<!-- OVERLAY ANIMADO -->
<div class="success-overlay" id="successOverlay">
  <img src="assets/images/logo.png" alt="NYX" class="success-logo">
  <div class="success-text">¡PEDIDO CONFIRMADO!</div>
  <div class="success-sub">La noche que ilumina tus compras</div>
  <div class="success-order">Orden #<?= $orderId ?> registrada ✦</div>
</div>

<!-- CONTENIDO REAL -->
<div class="container">
<div class="page-content success-content">
  <div class="success-card">

    <div class="checkmark">✓</div>

    <h1 style="font-family:'Cinzel',serif;font-size:1.8rem;font-weight:900;color:var(--moon);letter-spacing:0.1em;margin-bottom:0.8rem">
      ¡Gracias por tu compra!
    </h1>
    <p style="color:var(--muted);font-size:1rem;line-height:1.8;margin-bottom:2rem">
      Tu pedido <strong style="color:var(--accent)">#<?= $orderId ?></strong> ha sido confirmado.<br>
      Recibirás tu orden pronto. La noche cuida tu compra. 🌙
    </p>

    <!-- RESUMEN -->
    <div class="card" style="margin-bottom:1.5rem;text-align:left">
      <div class="card-body">
        <div style="display:flex;justify-content:space-between;margin-bottom:0.8rem">
          <span style="color:var(--muted);font-size:0.85rem">Número de orden</span>
          <strong style="color:var(--accent);font-family:'Cinzel',serif">#<?= $orderId ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;margin-bottom:0.8rem">
          <span style="color:var(--muted);font-size:0.85rem">Envío a</span>
          <span style="color:var(--moon);font-size:0.85rem"><?= e($order['shipping_name']) ?></span>
        </div>
        <div style="display:flex;justify-content:space-between;margin-bottom:0.8rem">
          <span style="color:var(--muted);font-size:0.85rem">Método</span>
          <span style="color:var(--moon);font-size:0.85rem"><?= $order['shipping_method']==='express'?'Expres':'Estandar' ?></span>
        </div>
        <div style="display:flex;justify-content:space-between;padding-top:0.8rem;border-top:1px solid rgba(200,216,240,0.1)">
          <span style="font-weight:700">Total pagado</span>
          <strong style="color:var(--accent);font-family:'Cinzel',serif;font-size:1.1rem"><?= precio($order['total']) ?></strong>
        </div>
      </div>
    </div>

    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
      <a href="order_detail.php?id=<?= $orderId ?>" class="btn btn-primary" style="padding:0.8rem 2rem">
        Ver detalle del pedido
      </a>
      <a href="index.php" class="btn btn-secondary" style="padding:0.8rem 2rem">
        Seguir comprando
      </a>
    </div>

  </div>
</div>
</div>

<script>
//Generar estrellas que caen
function createStar() {
  var star = document.createElement('div');
  star.className = 'star-particle';
  var symbols = ['★', '✦', '✧', '⭐', '🌟', '💫'];
  star.textContent = symbols[Math.floor(Math.random() * symbols.length)];
  star.style.left = Math.random() * 100 + 'vw';
  star.style.animationDuration = (Math.random() * 2 + 1.5) + 's';
  star.style.animationDelay = Math.random() * 2 + 's';
  star.style.fontSize = (Math.random() * 1.2 + 0.6) + 'rem';
  star.style.opacity = Math.random() * 0.8 + 0.2;
  document.body.appendChild(star);
  setTimeout(function() { star.remove(); }, 4000);
}

//Lanzar 60 estrellas
for (var i = 0; i < 60; i++) {
  setTimeout(createStar, i * 60);
}

//Quitar overlay despues de la animacion
setTimeout(function() {
  var overlay = document.getElementById('successOverlay');
  if (overlay) overlay.remove();
}, 4400);
</script>

<?php require_once 'includes/footer.php'; ?>
