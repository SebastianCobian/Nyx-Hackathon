<?php
$cartCount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) { $cartCount += $item['qty']; }
}
$flash = getFlash();
$bp = $basePath ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle ?? 'NYX — La noche que ilumina tus compras') ?></title>
  <link rel="icon" type="image/png" href="<?= $bp ?>assets/images/logo.png">
  <link rel="stylesheet" href="<?= $bp ?>assets/css/style.css">
</head>
<body>

<div class="page-bg-glow"></div>

<nav class="navbar">
  <div class="navbar-inner">
    <a href="<?= $bp ?>index.php" class="navbar-brand">
      <img src="<?= $bp ?>assets/images/logo.png" alt="NYX">
      <div>
        <span class="navbar-brand-name">NYX</span>
        <span class="navbar-brand-slogan">La noche que ilumina tus compras</span>
      </div>
    </a>
    <a href="<?= $bp ?>blink.php" class="nav-link" style="color:#a78bfa">🎮 Blink Galaxy</a>
    <a href="<?= $bp ?>index.php" class="nav-link">Tienda</a>
    <?php if (isLoggedIn()): ?>
      <a href="<?= $bp ?>orders.php" class="nav-link">Mis Pedidos</a>
      <?php if (isAdmin()): ?>
        <a href="<?= $bp ?>admin/index.php" class="nav-link admin-link">✦ Admin</a>
      <?php endif; ?>
      <a href="<?= $bp ?>logout.php" class="nav-link">Salir</a>
    <?php else: ?>
      <a href="<?= $bp ?>login.php" class="nav-link">Iniciar sesion</a>
      <a href="<?= $bp ?>register.php" class="nav-link">Registro</a>
    <?php endif; ?>
    <button class="cart-btn" onclick="toggleCart()">
      🌙 Carrito <span class="cart-count" id="cartCount"><?= $cartCount ?></span>
    </button>
  </div>
</nav>

<?php if ($flash): ?>
<div style="position:relative;z-index:2">
  <div class="container" style="padding-top:1rem">
    <div class="alert alert-<?= $flash['type']==='error'?'danger':$flash['type'] ?>">
      <?= e($flash['msg']) ?>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="cart-overlay" id="cartOverlay" onclick="toggleCart()"></div>
<div class="cart-sidebar" id="cartSidebar">
  <div class="cart-header">
    <h2>✦ Tu Carrito</h2>
    <button class="cart-close" onclick="toggleCart()">✕</button>
  </div>
  <div class="cart-items" id="cartItems">
    <?php
    $cartTotal = 0;
    if (!empty($_SESSION['cart'])): ?>
      <?php foreach ($_SESSION['cart'] as $pid => $item):
        $cartTotal += $item['price'] * $item['qty']; ?>
      <div class="cart-item" id="ci-<?= $pid ?>">
        <div class="cart-item-img">🌙</div>
        <div class="cart-item-info">
          <div class="cart-item-name"><?= e($item['name']) ?></div>
          <div class="cart-item-price"><?= precio($item['price']) ?> c/u</div>
          <div class="cart-item-qty">
            <button class="qty-btn" onclick="updateCart(<?= $pid ?>, -1)">-</button>
            <span class="qty-val" id="qty-<?= $pid ?>"><?= $item['qty'] ?></span>
            <button class="qty-btn" onclick="updateCart(<?= $pid ?>, 1)">+</button>
          </div>
        </div>
        <button class="cart-item-remove" onclick="removeCart(<?= $pid ?>)">x</button>
      </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="empty-state">
        <div class="icon">🌙</div>
        <h3>Carrito vacio</h3>
        <p>Agrega productos para comenzar</p>
      </div>
    <?php endif; ?>
  </div>
  <div class="cart-footer">
    <div class="cart-total">
      <span>Total</span>
      <span id="cartTotal"><?= precio($cartTotal) ?></span>
    </div>
    <?php if (!empty($_SESSION['cart'])): ?>
      <a href="<?= $bp ?>checkout.php" class="btn btn-primary btn-block">Finalizar compra</a>
    <?php else: ?>
      <button class="btn btn-secondary btn-block" disabled>Carrito vacio</button>
    <?php endif; ?>
  </div>
</div>
