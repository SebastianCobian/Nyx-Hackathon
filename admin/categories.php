<?php
require_once '../includes/config.php';
requireAdmin();
$basePath = '../';
$db = getDB();
$error = '';

if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM categories WHERE id=?")->execute([(int)$_GET['delete']]);
    setFlash('success','Categoria eliminada.');
    header('Location: categories.php'); exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id   = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? strtolower(str_replace(' ','-',$name)));
    if (!$name || !$slug) { $error = 'Nombre y slug son obligatorios.'; }
    else {
        if ($id) {
            $db->prepare("UPDATE categories SET name=?,slug=? WHERE id=?")->execute([$name,$slug,$id]);
            setFlash('success','Categoria actualizada.');
        } else {
            $db->prepare("INSERT INTO categories (name,slug) VALUES (?,?)")->execute([$name,$slug]);
            setFlash('success','Categoria creada.');
        }
        header('Location: categories.php'); exit;
    }
}
$editing = null;
if (isset($_GET['edit'])) {
    $s = $db->prepare("SELECT * FROM categories WHERE id=?");
    $s->execute([(int)$_GET['edit']]);
    $editing = $s->fetch();
}
$categories = $db->query("SELECT c.*, COUNT(p.id) AS product_count FROM categories c LEFT JOIN products p ON p.category_id=c.id GROUP BY c.id ORDER BY c.name")->fetchAll();

$pageTitle = 'Categorias - Admin NYX';
require_once 'header_admin.php';
?>
<div class="container">
<div class="page-content">
<div class="page-header">
  <h1 class="page-title">Categorias</h1>
  <a href="index.php" class="btn btn-secondary">Dashboard</a>
</div>
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<div style="display:grid;grid-template-columns:340px 1fr;gap:1.5rem;align-items:start">
  <div class="card">
    <div class="card-body">
      <h2 style="font-family:'Cinzel',serif;font-size:1rem;font-weight:700;color:var(--moon);margin-bottom:1.2rem">
        <?= $editing ? 'Editar' : 'Nueva categoria' ?>
      </h2>
      <form method="POST">
        <?php if ($editing): ?><input type="hidden" name="id" value="<?= $editing['id'] ?>"><?php endif; ?>
        <div class="form-group">
          <label class="form-label">Nombre *</label>
          <input type="text" name="name" class="form-control" required
                 value="<?= e($editing['name']??'') ?>"
                 oninput="this.form.slug.value=this.value.toLowerCase().replace(/\s+/g,'-').replace(/[^a-z0-9-]/g,'')">
        </div>
        <div class="form-group">
          <label class="form-label">Slug</label>
          <input type="text" name="slug" class="form-control" value="<?= e($editing['slug']??'') ?>">
        </div>
        <div style="display:flex;gap:0.8rem">
          <button type="submit" class="btn btn-primary"><?= $editing ? 'Guardar' : 'Crear' ?></button>
          <?php if ($editing): ?><a href="categories.php" class="btn btn-secondary">Cancelar</a><?php endif; ?>
        </div>
      </form>
    </div>
  </div>
  <div class="card">
    <div class="table-wrap">
      <table>
        <thead><tr><th>ID</th><th>Nombre</th><th>Slug</th><th>Productos</th><th></th></tr></thead>
        <tbody>
          <?php foreach ($categories as $cat): ?>
            <tr>
              <td><?= $cat['id'] ?></td>
              <td><strong style="color:var(--moon)"><?= e($cat['name']) ?></strong></td>
              <td style="color:var(--muted);font-size:0.85rem"><?= e($cat['slug']) ?></td>
              <td><?= $cat['product_count'] ?></td>
              <td style="display:flex;gap:0.5rem;padding-block:0.65rem">
                <a href="categories.php?edit=<?= $cat['id'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                <a href="categories.php?delete=<?= $cat['id'] ?>" class="btn btn-danger btn-sm"
                   onclick="return confirm('Eliminar?')">Eliminar</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
</div>
<?php require_once '../includes/footer.php'; // footer ?>
