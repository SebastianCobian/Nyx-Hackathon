<?php
require_once 'includes/config.php';
requireLogin();
$db = getDB();
$stmt = $db->prepare("SELECT o.*, COUNT(oi.id) AS item_count FROM orders o LEFT JOIN order_items oi ON o.id=oi.order_id WHERE o.user_id=? GROUP BY o.id ORDER BY o.created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();

$pageTitle = 'Mis Pedidos - NYX';
require_once 'includes/header.php';
?>
<div class="container">
<div class="page-content">

<div class="page-header">
  <h1 class="page-title">Mis Pedidos</h1>
  <a href="index.php" class="btn btn-primary">Seguir comprando</a>
</div>

<?php if (empty($orders)): ?>
  <div class="empty-state">
    <div class="icon">🌙</div>
    <h3>Sin pedidos aun</h3>
    <p>Cuando hagas una compra aparecera aqui.</p>
    <a href="index.php" class="btn btn-primary" style="margin-top:1rem">Ir a la tienda</a>
  </div>
<?php else: ?>
  <div class="card">
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th># Pedido</th><th>Fecha</th><th>Productos</th><th>Envio</th><th>Total</th><th>Estado</th><th></th></tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $o): ?>
            <tr>
              <td><strong>#<?= $o['id'] ?></strong></td>
              <td><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
              <td><?= $o['item_count'] ?> producto(s)</td>
              <td><?= e($o['shipping_method']) ?></td>
              <td><strong><?= precio($o['total']) ?></strong></td>
              <td><span class="badge badge-<?= $o['status'] ?>"><?= e($o['status']) ?></span></td>
              <td><a href="order_detail.php?id=<?= $o['id'] ?>" class="btn btn-secondary btn-sm">Ver detalle</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>

</div>
</div>
<?php require_once 'includes/footer.php'; ?>
