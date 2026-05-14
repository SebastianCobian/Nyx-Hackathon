<?php
require_once 'includes/config.php';
requireLogin();
$db = getDB();
$orderId = (int)($_GET['id'] ?? 0);

$stmt = $db->prepare("SELECT * FROM orders WHERE id=? AND user_id=?");
$stmt->execute([$orderId, $_SESSION['user_id']]);
$order = $stmt->fetch();
if (!$order) { setFlash('error','Pedido no encontrado.'); header('Location: orders.php'); exit; }

$items = $db->prepare("SELECT * FROM order_items WHERE order_id=?");
$items->execute([$orderId]);
$items = $items->fetchAll();

$allStatuses   = ['pending','processing','shipped','delivered'];
$statusLabels  = [
    'pending'    => ['label'=>'Pedido recibido', 'icon'=>'🌙', 'desc'=>'Tu orden fue registrada'],
    'processing' => ['label'=>'En preparacion',  'icon'=>'⚙️', 'desc'=>'Estamos preparando tu pedido'],
    'shipped'    => ['label'=>'En camino',        'icon'=>'🚀', 'desc'=>'Tu pedido va en camino'],
    'delivered'  => ['label'=>'Entregado',        'icon'=>'✦',  'desc'=>'Pedido entregado con exito'],
];
$currentIndex = array_search($order['status'], $allStatuses);
$qrData = urlencode('NYX-ORDER-'.$orderId.'-'.strtoupper(substr(md5($orderId.'nyx'),0,8)));
$qrUrl  = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data='.$qrData;

$pageTitle = "Pedido #$orderId - NYX";
require_once 'includes/header.php';
?>
<div class="container">
<div class="page-content">

<div class="page-header">
  <h1 class="page-title">Pedido #<?= $orderId ?></h1>
  <a href="orders.php" class="btn btn-secondary">Mis pedidos</a>
</div>

<?php if ($order['status'] !== 'cancelled'): ?>
<div class="card" style="margin-bottom:1.5rem">
  <div class="card-body">
    <h2 style="font-family:'Cinzel',serif;font-size:1rem;font-weight:700;color:var(--moon);margin-bottom:1.5rem">Seguimiento del pedido</h2>
    <div class="order-timeline">
      <?php foreach ($allStatuses as $i => $status):
        $done    = $i <= $currentIndex;
        $current = $i === $currentIndex;
        $info    = $statusLabels[$status]; ?>
        <div class="timeline-step <?= $done?'done':'' ?> <?= $current?'current':'' ?>">
          <div class="timeline-icon"><?= $info['icon'] ?></div>
          <div class="timeline-info">
            <div class="timeline-label"><?= $info['label'] ?></div>
            <div class="timeline-desc"><?= $info['desc'] ?></div>
          </div>
          <?php if ($i < count($allStatuses)-1): ?>
            <div class="timeline-line <?= $i<$currentIndex?'done':'' ?>"></div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="grid-2" style="align-items:start">

  <div class="card">
    <div class="card-body">
      <h2 style="font-family:'Cinzel',serif;font-size:1rem;font-weight:700;color:var(--moon);margin-bottom:1rem">Productos</h2>
      <?php foreach ($items as $item): ?>
        <div style="display:flex;justify-content:space-between;padding:0.8rem 0;border-bottom:1px solid rgba(200,216,240,0.07)">
          <div>
            <div style="font-weight:600;color:var(--moon)"><?= e($item['product_name']) ?></div>
            <div style="font-size:0.82rem;color:var(--muted)"><?= precio($item['price']) ?> x <?= $item['quantity'] ?></div>
          </div>
          <div style="font-weight:700;color:var(--accent)"><?= precio($item['price']*$item['quantity']) ?></div>
        </div>
      <?php endforeach; ?>
      <div style="display:flex;justify-content:space-between;padding-top:1rem;font-weight:700">
        <span>Total</span>
        <span style="color:var(--accent);font-family:'Cinzel',serif;font-size:1.1rem"><?= precio($order['total']) ?></span>
      </div>
    </div>
  </div>

  <div style="display:flex;flex-direction:column;gap:1rem">

    <div class="card">
      <div class="card-body">
        <h2 style="font-family:'Cinzel',serif;font-size:0.9rem;font-weight:700;color:var(--moon);margin-bottom:0.8rem">Estado</h2>
        <span class="badge badge-<?= $order['status'] ?>" style="font-size:0.85rem;padding:0.4rem 1rem"><?= e($order['status']) ?></span>
        <p style="color:var(--muted);font-size:0.82rem;margin-top:0.8rem"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <h2 style="font-family:'Cinzel',serif;font-size:0.9rem;font-weight:700;color:var(--moon);margin-bottom:0.8rem">Envio</h2>
        <p style="font-size:0.88rem;line-height:1.9">
          <strong style="color:var(--moon)"><?= e($order['shipping_name']) ?></strong><br>
          <?= e($order['shipping_address']) ?><br>
          <?= e($order['shipping_city']) ?> <?= e($order['shipping_zip']) ?>
        </p>
        <div style="margin-top:0.6rem;font-size:0.83rem;color:var(--accent)">
          <?= $order['shipping_method']==='express'?'Expres':'Estandar' ?> (+<?= precio($order['shipping_cost']) ?>)
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body" style="text-align:center">
        <h2 style="font-family:'Cinzel',serif;font-size:0.9rem;font-weight:700;color:var(--moon);margin-bottom:1rem">QR de entrega</h2>
        <div style="background:#fff;padding:0.7rem;border-radius:8px;display:inline-block;margin-bottom:0.8rem">
          <img src="<?= $qrUrl ?>" alt="QR #<?= $orderId ?>" width="160" height="160">
        </div>
        <p style="font-size:0.78rem;color:var(--muted)">El repartidor escanea este codigo para confirmar la entrega</p>
        <code style="font-size:0.72rem;color:var(--muted)">NYX-ORDER-<?= $orderId ?></code>
      </div>
    </div>

  </div>
</div>

</div>
</div>
<?php require_once 'includes/footer.php'; ?>
