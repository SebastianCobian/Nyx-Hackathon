<?php
require_once 'includes/config.php';
requireLogin();
if (empty($_SESSION['cart'])) { setFlash('info','Tu carrito esta vacio.'); header('Location: index.php'); exit; }

$db = getDB();
$error = '';
$shippingMethods = [
    'standard' => ['name'=>'Envio Estandar', 'desc'=>'5-7 dias habiles', 'price'=>59.00],
    'express'  => ['name'=>'Envio Expres',   'desc'=>'1-2 dias habiles', 'price'=>149.00],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method  = $_POST['shipping_method'] ?? '';
    $name    = trim($_POST['shipping_name'] ?? '');
    $address = trim($_POST['shipping_address'] ?? '');
    $city    = trim($_POST['shipping_city'] ?? '');
    $zip     = trim($_POST['shipping_zip'] ?? '');

    if (!isset($shippingMethods[$method])) { $error = 'Selecciona un metodo de envio.'; }
    elseif (!$name || !$address || !$city) { $error = 'Completa todos los datos de envio.'; }
    else {
        $shippingCost = $shippingMethods[$method]['price'];
        $subtotal = 0;
        foreach ($_SESSION['cart'] as $item) { $subtotal += $item['price'] * $item['qty']; }
        $total = $subtotal + $shippingCost;

        $db->prepare("INSERT INTO orders (user_id,total,status,shipping_method,shipping_cost,shipping_name,shipping_address,shipping_city,shipping_zip) VALUES (?,?,'pending',?,?,?,?,?,?)")
           ->execute([$_SESSION['user_id'],$total,$method,$shippingCost,$name,$address,$city,$zip]);
        $orderId = $db->lastInsertId();

        foreach ($_SESSION['cart'] as $pid => $item) {
            $db->prepare("INSERT INTO order_items (order_id,product_id,product_name,quantity,price) VALUES (?,?,?,?,?)")
               ->execute([$orderId,$pid,$item['name'],$item['qty'],$item['price']]);
            $db->prepare("UPDATE products SET stock=stock-? WHERE id=? AND stock>=?")->execute([$item['qty'],$pid,$item['qty']]);
        }
        unset($_SESSION['cart']);
        header('Location: order_success.php?id='.$orderId); exit;
    }
}

$subtotal = 0;
foreach ($_SESSION['cart'] as $item) { $subtotal += $item['price'] * $item['qty']; }
$selectedMethod = $_POST['shipping_method'] ?? 'standard';
$shippingCost   = $shippingMethods[$selectedMethod]['price'];

$pageTitle = 'Checkout - NYX';
require_once 'includes/header.php';
?>
<div class="container">
<div class="page-content">

<div class="page-header">
  <h1 class="page-title">Finalizar compra</h1>
  <a href="index.php" class="btn btn-secondary">Seguir comprando</a>
</div>

<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<form method="POST" id="checkoutForm">
<div class="checkout-grid">

  <div>
    <div class="card" style="margin-bottom:1.5rem">
      <div class="card-body">
        <h2 style="font-family:'Cinzel',serif;font-size:1rem;font-weight:700;color:var(--moon);margin-bottom:1.2rem">Datos de envio</h2>
        <div class="form-group">
          <label class="form-label">Nombre completo</label>
          <input type="text" name="shipping_name" class="form-control" required
                 value="<?= e($_POST['shipping_name'] ?? $_SESSION['name'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Direccion</label>
          <input type="text" name="shipping_address" class="form-control" required
                 placeholder="Calle, numero, colonia"
                 value="<?= e($_POST['shipping_address'] ?? '') ?>">
        </div>
        <div class="grid-2">
          <div class="form-group">
            <label class="form-label">Ciudad</label>
            <input type="text" name="shipping_city" class="form-control" required
                   value="<?= e($_POST['shipping_city'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Codigo postal</label>
            <input type="text" name="shipping_zip" class="form-control"
                   value="<?= e($_POST['shipping_zip'] ?? '') ?>">
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <h2 style="font-family:'Cinzel',serif;font-size:1rem;font-weight:700;color:var(--moon);margin-bottom:1.2rem">Metodo de envio</h2>
        <div class="shipping-options">
          <?php foreach ($shippingMethods as $key => $m): ?>
            <label class="shipping-opt">
              <input type="radio" name="shipping_method" value="<?= $key ?>"
                     <?= $selectedMethod===$key?'checked':'' ?>
                     onchange="updateShipping(<?= $m['price'] ?>)">
              <div class="shipping-opt-info">
                <div class="shipping-opt-name"><?= e($m['name']) ?></div>
                <div class="shipping-opt-desc"><?= e($m['desc']) ?></div>
              </div>
              <div class="shipping-opt-price"><?= precio($m['price']) ?></div>
            </label>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- METODO DE PAGO -->
    <div class="card">
      <div class="card-body">
        <h2 style="font-family:'Cinzel',serif;font-size:1rem;font-weight:700;color:var(--moon);margin-bottom:1.2rem">Metodo de pago</h2>
        <div class="shipping-options">

          <!-- PAGO CON TARJETA -->
          <label class="shipping-opt" id="payNormal">
            <input type="radio" name="payment_method" value="normal" checked onchange="togglePayment('normal')">
            <div class="shipping-opt-info">
              <div class="shipping-opt-name">💳 Pago con tarjeta</div>
              <div class="shipping-opt-desc">Visa, Mastercard, American Express</div>
            </div>
            <div class="shipping-opt-price" style="color:var(--accent)">Seguro</div>
          </label>

          <!-- PAGO CON $BG -->
          <label class="shipping-opt" id="payBG" style="border-color:rgba(124,58,237,0.3)">
            <input type="radio" name="payment_method" value="bg" onchange="togglePayment('bg')">
            <div class="shipping-opt-info">
              <div class="shipping-opt-name" style="color:#a78bfa">⬡ Pagar con $BG Token</div>
              <div class="shipping-opt-desc">Blink Galaxy · Zero gas fees · SKALE Network</div>
            </div>
            <div class="shipping-opt-price" style="color:#a78bfa">Web3</div>
          </label>

        </div>

        <!-- FORMULARIO TARJETA -->
        <div id="cardSection" style="margin-top:1.2rem">
          <div style="background:linear-gradient(135deg,#1a1a3a,#0d0d20);border:1px solid rgba(200,216,240,0.15);border-radius:12px;padding:1.5rem;margin-bottom:1rem">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem">
              <span style="font-size:0.75rem;color:var(--muted);letter-spacing:0.1em;text-transform:uppercase">Tarjeta de crédito / débito</span>
              <div style="display:flex;gap:0.5rem">
                <span style="background:rgba(255,255,255,0.1);border-radius:4px;padding:0.2rem 0.5rem;font-size:0.7rem;color:var(--muted)">VISA</span>
                <span style="background:rgba(255,255,255,0.1);border-radius:4px;padding:0.2rem 0.5rem;font-size:0.7rem;color:var(--muted)">MC</span>
                <span style="background:rgba(255,255,255,0.1);border-radius:4px;padding:0.2rem 0.5rem;font-size:0.7rem;color:var(--muted)">AMEX</span>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Nombre en la tarjeta</label>
              <input type="text" class="form-control" placeholder="NOMBRE APELLIDO" style="text-transform:uppercase">
            </div>
            <div class="form-group">
              <label class="form-label">Número de tarjeta</label>
              <input type="text" class="form-control" placeholder="0000 0000 0000 0000" maxlength="19"
                     oninput="this.value=this.value.replace(/\D/g,'').replace(/(.{4})/g,'$1 ').trim()">
            </div>
            <div class="grid-2" style="gap:1rem">
              <div class="form-group" style="margin:0">
                <label class="form-label">Fecha de expiración</label>
                <input type="text" class="form-control" placeholder="MM/AA" maxlength="5"
                       oninput="this.value=this.value.replace(/\D/g,'').replace(/^(\d{2})(\d)/,'$1/$2')">
              </div>
              <div class="form-group" style="margin:0">
                <label class="form-label">CVV</label>
                <input type="password" class="form-control" placeholder="•••" maxlength="4">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Dirección de facturación</label>
            <input type="text" class="form-control" placeholder="Calle, número, colonia">
          </div>
          <p style="font-size:0.75rem;color:var(--muted);text-align:center">
            🔒 Pago seguro con encriptación SSL
          </p>
        </div>

        <!-- BOTON BG (aparece al seleccionar $BG) -->
        <div id="bgPaySection" style="display:none;margin-top:1.2rem;text-align:center">
          <p style="color:var(--muted);font-size:0.85rem;margin-bottom:1rem">
            Serás redirigido al portal de Blink Galaxy para completar tu pago con $BG Token de forma segura.
          </p>
          <a href="https://portal.blinkgalaxy.com" target="_blank"
             class="btn btn-block"
             style="background:linear-gradient(135deg,#7c3aed,#a78bfa);color:#fff;padding:0.85rem;font-size:0.95rem;box-shadow:0 0 20px rgba(124,58,237,0.3)">
            ⬡ Conectar wallet y pagar con $BG
          </a>
          <div style="display:flex;gap:0.5rem;justify-content:center;flex-wrap:wrap;margin-top:0.8rem">
            <?php foreach(['🦊 MetaMask','🔗 WalletConnect','🛡️ Trust Wallet'] as $w): ?>
              <span style="font-size:0.72rem;color:var(--muted);background:rgba(200,216,240,0.05);border:1px solid rgba(200,216,240,0.1);padding:0.2rem 0.6rem;border-radius:20px"><?= $w ?></span>
            <?php endforeach; ?>
          </div>
        </div>

      </div>
    </div>
  </div>

  <div class="order-summary">
    <h2 style="font-family:'Cinzel',serif;font-size:1rem;font-weight:700;color:var(--moon);margin-bottom:1.2rem">Resumen del pedido</h2>
    <?php foreach ($_SESSION['cart'] as $pid => $item): ?>
      <div class="summary-item">
        <span><?= e($item['name']) ?> x<?= $item['qty'] ?></span>
        <span><?= precio($item['price'] * $item['qty']) ?></span>
      </div>
    <?php endforeach; ?>
    <div class="summary-item">
      <span>Subtotal</span><span><?= precio($subtotal) ?></span>
    </div>
    <div class="summary-item">
      <span>Envio</span><span id="shippingDisplay"><?= precio($shippingCost) ?></span>
    </div>
    <div class="summary-total">
      <span>Total</span><span id="totalDisplay"><?= precio($subtotal + $shippingCost) ?></span>
    </div>
    <button type="submit" class="btn btn-primary btn-block" style="margin-top:1.5rem;padding:0.85rem">
      Confirmar pedido
    </button>
  </div>

</div>
</form>

</div>
</div>
<script>
var subtotal = <?= $subtotal ?>;
function updateShipping(cost) {
  document.getElementById('shippingDisplay').textContent = '$' + cost.toFixed(2);
  document.getElementById('totalDisplay').textContent = '$' + (subtotal + cost).toFixed(2);
}
function togglePayment(method) {
  var bgSection   = document.getElementById('bgPaySection');
  var cardSection = document.getElementById('cardSection');
  var bgLabel     = document.getElementById('payBG');
  if (method === 'bg') {
    bgSection.style.display   = 'block';
    cardSection.style.display = 'none';
    bgLabel.style.borderColor = 'rgba(124,58,237,0.6)';
    bgLabel.style.background  = 'rgba(124,58,237,0.08)';
  } else {
    bgSection.style.display   = 'none';
    cardSection.style.display = 'block';
    bgLabel.style.borderColor = 'rgba(124,58,237,0.3)';
    bgLabel.style.background  = '';
  }
}
</script>
<?php require_once 'includes/footer.php'; ?>