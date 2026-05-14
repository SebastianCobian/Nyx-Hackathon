<?php
require_once '../includes/config.php';
requireAdmin();
$basePath = '../';
$db = getDB();

$viewId    = (int)($_GET['id'] ?? 0);
$viewOrder = null;
$viewItems = [];

if ($viewId) {
    $s = $db->prepare("SELECT o.*, u.name AS user_name, u.email AS user_email FROM orders o LEFT JOIN users u ON o.user_id=u.id WHERE o.id=?");
    $s->execute([$viewId]);
    $viewOrder = $s->fetch();
    if ($viewOrder) {
        $s2 = $db->prepare("SELECT * FROM order_items WHERE order_id=?");
        $s2->execute([$viewId]);
        $viewItems = $s2->fetchAll();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'], $_POST['order_id'])) {
    $valid = ['pending','processing','shipped','delivered','cancelled'];
    $oid   = (int)$_POST['order_id'];
    if (in_array($_POST['status'], $valid)) {
        $db->prepare("UPDATE orders SET status=? WHERE id=?")->execute([$_POST['status'], $oid]);
        setFlash('success', "Estado del pedido #$oid actualizado.");
    }
    header("Location: orders.php?id=$oid"); exit;
}

$orders = $db->query("SELECT o.*, u.name AS user_name FROM orders o LEFT JOIN users u ON o.user_id=u.id ORDER BY o.created_at DESC")->fetchAll();

$pageTitle = 'Pedidos - Admin NYX';
require_once 'header_admin.php';
?>
<div class="container">
<div class="page-content">

<div class="page-header">
  <h1 class="page-title">Pedidos</h1>
  <a href="index.php" class="btn btn-secondary">Dashboard</a>
</div>

<?php if ($viewOrder): ?>
<div class="card" style="margin-bottom:2rem">
  <div class="card-body">
    <div style="display:flex;justify-content:space-between;align-items:start;flex-wrap:wrap;gap:1rem">
      <div>
        <h2 style="font-family:'Cinzel',serif;font-size:1.1rem;font-weight:700;color:var(--moon)">Pedido #<?= $viewOrder['id'] ?></h2>
        <p style="color:var(--muted);font-size:0.85rem;margin-top:0.3rem"><?= e($viewOrder['user_name']??'Sin usuario') ?> — <?= e($viewOrder['user_email']??'') ?></p>
      </div>
      <form method="POST" style="display:flex;gap:0.8rem;align-items:center">
        <input type="hidden" name="order_id" value="<?= $viewOrder['id'] ?>">
        <select name="status" class="form-control" style="width:auto">
          <?php foreach (['pending','processing','shipped','delivered','cancelled'] as $s): ?>
            <option value="<?= $s ?>" <?= $viewOrder['status']===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Actualizar</button>
      </form>
    </div>
    <div class="grid-2" style="margin-top:1.5rem;gap:1.5rem">
      <div>
        <h3 style="font-size:0.85rem;font-weight:700;color:var(--muted);margin-bottom:0.8rem;text-transform:uppercase">Productos</h3>
        <?php foreach ($viewItems as $item): ?>
          <div style="display:flex;justify-content:space-between;padding:0.5rem 0;border-bottom:1px solid rgba(200,216,240,0.07);font-size:0.9rem">
            <span style="color:var(--moon)"><?= e($item['product_name']) ?> x <?= $item['quantity'] ?></span>
            <span style="color:var(--accent)"><?= precio($item['price']*$item['quantity']) ?></span>
          </div>
        <?php endforeach; ?>
        <div style="display:flex;justify-content:space-between;padding-top:0.8rem;font-weight:700">
          <span>Total</span><span style="color:var(--accent)"><?= precio($viewOrder['total']) ?></span>
        </div>
      </div>
      <div>
        <h3 style="font-size:0.85rem;font-weight:700;color:var(--muted);margin-bottom:0.8rem;text-transform:uppercase">Envio</h3>
        <p style="font-size:0.9rem;line-height:1.9">
          <strong style="color:var(--moon)"><?= e($viewOrder['shipping_name']) ?></strong><br>
          <?= e($viewOrder['shipping_address']) ?><br>
          <?= e($viewOrder['shipping_city']) ?> <?= e($viewOrder['shipping_zip']) ?>
        </p>
        <div style="margin-top:0.6rem;font-size:0.85rem;color:var(--accent)">
          <?= $viewOrder['shipping_method']==='express'?'Expres':'Estandar' ?> (+<?= precio($viewOrder['shipping_cost']) ?>)
        </div>
      </div>
    </div>
    <a href="orders.php" class="btn btn-secondary btn-sm" style="margin-top:1rem">Ver todos los pedidos</a>
  </div>
</div>
<?php endif; ?>

<div class="card">
  <div class="table-wrap">
    <table>
      <thead><tr><th>#</th><th>Cliente</th><th>Total</th><th>Envio</th><th>Estado</th><th>Fecha</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($orders as $o): ?>
          <tr>
            <td><strong>#<?= $o['id'] ?></strong></td>
            <td><?= e($o['user_name']??'Invitado') ?></td>
            <td><?= precio($o['total']) ?></td>
            <td><?= e($o['shipping_method']) ?></td>
            <td><span class="badge badge-<?= $o['status'] ?>"><?= $o['status'] ?></span></td>
            <td><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
            <td><a href="orders.php?id=<?= $o['id'] ?>" class="btn btn-secondary btn-sm">Ver</a></td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($orders)): ?>
          <tr><td colspan="7" style="text-align:center;color:var(--muted);padding:2rem">Sin pedidos.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

</div>
</div>
<?php require_once '../includes/footer.php'; // footer ?>
