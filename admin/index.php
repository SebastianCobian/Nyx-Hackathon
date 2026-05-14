<?php
require_once '../includes/config.php';
requireAdmin();
$basePath = '../';
$db = getDB();

$stats = [
    'products' => $db->query("SELECT COUNT(*) FROM products")->fetchColumn(),
    'orders'   => $db->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
    'users'    => $db->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetchColumn(),
    'revenue'  => $db->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status!='cancelled'")->fetchColumn(),
];
$recentOrders = $db->query("SELECT o.*, u.name AS user_name FROM orders o LEFT JOIN users u ON o.user_id=u.id ORDER BY o.created_at DESC LIMIT 10")->fetchAll();

$pageTitle = 'Admin - NYX';
require_once '../includes/header.php';
?>
<div class="container">
<div class="page-content">

<div class="page-header">
  <h1 class="page-title">Panel de administracion</h1>
  <a href="../index.php" class="btn btn-secondary">Ver tienda</a>
</div>

<div class="stats-grid">
  <div class="stat-card"><div class="stat-label">Productos</div><div class="stat-value"><?= $stats['products'] ?></div></div>
  <div class="stat-card"><div class="stat-label">Pedidos</div><div class="stat-value"><?= $stats['orders'] ?></div></div>
  <div class="stat-card"><div class="stat-label">Clientes</div><div class="stat-value"><?= $stats['users'] ?></div></div>
  <div class="stat-card"><div class="stat-label">Ingresos</div><div class="stat-value"><?= precio($stats['revenue']) ?></div></div>
</div>

<div style="display:flex;gap:1rem;margin-bottom:2rem;flex-wrap:wrap">
  <a href="products.php"   class="btn btn-primary">Productos</a>
  <a href="orders.php"     class="btn btn-secondary">Pedidos</a>
  <a href="categories.php" class="btn btn-secondary">Categorias</a>
  <a href="users.php"      class="btn btn-secondary">Usuarios</a>
</div>

<div class="card">
  <div class="card-body">
    <h2 style="font-family:'Cinzel',serif;font-size:1rem;font-weight:700;color:var(--moon);margin-bottom:1.2rem">Pedidos recientes</h2>
    <?php if (empty($recentOrders)): ?>
      <p style="color:var(--muted)">Sin pedidos aun.</p>
    <?php else: ?>
      <div class="table-wrap">
        <table>
          <thead><tr><th>#</th><th>Cliente</th><th>Total</th><th>Envio</th><th>Estado</th><th>Fecha</th><th></th></tr></thead>
          <tbody>
            <?php foreach ($recentOrders as $o): ?>
              <tr>
                <td><strong>#<?= $o['id'] ?></strong></td>
                <td><?= e($o['user_name'] ?? 'Invitado') ?></td>
                <td><?= precio($o['total']) ?></td>
                <td><?= e($o['shipping_method']) ?></td>
                <td><span class="badge badge-<?= $o['status'] ?>"><?= e($o['status']) ?></span></td>
                <td><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
                <td><a href="orders.php?id=<?= $o['id'] ?>" class="btn btn-secondary btn-sm">Ver</a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

</div>
</div>
<?php require_once '../includes/footer.php'; ?>
