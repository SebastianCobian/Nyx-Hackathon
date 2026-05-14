<?php
require_once '../includes/config.php';
requireAdmin();
$basePath = '../';
$db = getDB();

// Eliminar usuario
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($id !== $_SESSION['user_id']) {
        $db->prepare("DELETE FROM users WHERE id=? AND role='customer'")->execute([$id]);
        setFlash('success', 'Usuario eliminado.');
    } else {
        setFlash('error', 'No puedes eliminarte a ti mismo.');
    }
    header('Location: users.php'); exit;
}

$users = $db->query("SELECT u.*, COUNT(o.id) AS order_count, COALESCE(SUM(o.total),0) AS total_spent FROM users u LEFT JOIN orders o ON o.user_id=u.id GROUP BY u.id ORDER BY u.created_at DESC")->fetchAll();

$pageTitle = 'Usuarios - Admin NYX';
require_once 'header_admin.php';
?>
<div class="container">
<div class="page-content">
<div class="page-header">
  <h1 class="page-title">Usuarios</h1>
  <a href="index.php" class="btn btn-secondary">Dashboard</a>
</div>
<div class="card">
  <div class="table-wrap">
    <table>
      <thead><tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Pedidos</th><th>Total gastado</th><th>Registro</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($users as $u): ?>
          <tr>
            <td><?= $u['id'] ?></td>
            <td><strong style="color:var(--moon)"><?= e($u['name']) ?></strong></td>
            <td><?= e($u['email']) ?></td>
            <td>
              <?php if ($u['role']==='admin'): ?>
                <span class="badge" style="background:rgba(200,216,240,0.15);color:var(--accent)">Admin</span>
              <?php else: ?>
                <span class="badge" style="background:rgba(200,216,240,0.05);color:var(--muted)">Cliente</span>
              <?php endif; ?>
            </td>
            <td><?= $u['order_count'] ?></td>
            <td><?= precio($u['total_spent']) ?></td>
            <td><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
            <td>
              <?php if ($u['role'] !== 'admin'): ?>
                <a href="users.php?delete=<?= $u['id'] ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Eliminar a <?= e($u['name']) ?>?')">
                  Eliminar
                </a>
              <?php else: ?>
                <span style="font-size:0.75rem;color:var(--muted)">—</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
</div>
</div>
<?php require_once '../includes/footer.php'; ?>