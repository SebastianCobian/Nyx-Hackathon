<?php
require_once '../includes/config.php';
requireAdmin();
$basePath = '../';
$db = getDB();
$error = '';

// Carpeta de imagenes
$uploadDir = __DIR__ . '/../assets/images/products/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM products WHERE id=?")->execute([(int)$_GET['delete']]);
    setFlash('success','Producto eliminado.');
    header('Location: products.php'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = (int)($_POST['id'] ?? 0);
    $name  = trim($_POST['name'] ?? '');
    $desc  = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $catId = (int)($_POST['category_id'] ?? 0) ?: null;
    $image = null;

    // Subida de imagen
    if (!empty($_FILES['image']['name'])) {
        $ext      = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed  = ['jpg','jpeg','png','webp','gif'];
        if (!in_array($ext, $allowed)) {
            $error = 'Solo se permiten imagenes JPG, PNG, WEBP o GIF.';
        } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {
            $error = 'La imagen no puede superar 5MB.';
        } else {
            $filename = 'producto_' . time() . '_' . rand(100,999) . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
                $image = 'products/' . $filename;
            } else {
                $error = 'Error al subir la imagen. Verifica permisos de la carpeta.';
            }
        }
    }

    if (!$error) {
        if (!$name || $price <= 0) { $error = 'Nombre y precio son obligatorios.'; }
        else {
            if ($id) {
                if ($image) {
                    $db->prepare("UPDATE products SET name=?,description=?,price=?,stock=?,category_id=?,image=? WHERE id=?")
                       ->execute([$name,$desc,$price,$stock,$catId,$image,$id]);
                } else {
                    $db->prepare("UPDATE products SET name=?,description=?,price=?,stock=?,category_id=? WHERE id=?")
                       ->execute([$name,$desc,$price,$stock,$catId,$id]);
                }
                setFlash('success','Producto actualizado.');
            } else {
                $db->prepare("INSERT INTO products (name,description,price,stock,category_id,image) VALUES (?,?,?,?,?,?)")
                   ->execute([$name,$desc,$price,$stock,$catId,$image]);
                setFlash('success','Producto creado.');
            }
            header('Location: products.php'); exit;
        }
    }
}

$editing = null;
if (isset($_GET['edit'])) {
    $s = $db->prepare("SELECT * FROM products WHERE id=?");
    $s->execute([(int)$_GET['edit']]);
    $editing = $s->fetch();
}

$products   = $db->query("SELECT p.*, c.name AS cat_name FROM products p LEFT JOIN categories c ON p.category_id=c.id ORDER BY p.created_at DESC")->fetchAll();
$categories = $db->query("SELECT * FROM categories ORDER BY name")->fetchAll();

$pageTitle = 'Productos - Admin NYX';
require_once 'header_admin.php';
?>
<div class="container">
<div class="page-content">

<div class="page-header">
  <h1 class="page-title">Productos</h1>
  <a href="index.php" class="btn btn-secondary">Dashboard</a>
</div>

<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<div class="card" style="margin-bottom:2rem">
  <div class="card-body">
    <h2 style="font-family:'Cinzel',serif;font-size:1rem;font-weight:700;color:var(--moon);margin-bottom:1.2rem">
      <?= $editing ? 'Editar producto' : 'Nuevo producto' ?>
    </h2>
    <form method="POST" enctype="multipart/form-data">
      <?php if ($editing): ?><input type="hidden" name="id" value="<?= $editing['id'] ?>"><?php endif; ?>
      <div class="grid-3" style="gap:1rem">
        <div class="form-group" style="margin:0">
          <label class="form-label">Nombre *</label>
          <input type="text" name="name" class="form-control" required value="<?= e($editing['name']??'') ?>">
        </div>
        <div class="form-group" style="margin:0">
          <label class="form-label">Precio *</label>
          <input type="number" name="price" class="form-control" step="0.01" min="0.01" required value="<?= e($editing['price']??'') ?>">
        </div>
        <div class="form-group" style="margin:0">
          <label class="form-label">Stock</label>
          <input type="number" name="stock" class="form-control" min="0" value="<?= e($editing['stock']??0) ?>">
        </div>
      </div>
      <div class="grid-2" style="gap:1rem;margin-top:1rem">
        <div class="form-group" style="margin:0">
          <label class="form-label">Categoria</label>
          <select name="category_id" class="form-control">
            <option value="">Sin categoria</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>" <?= (($editing['category_id']??'')==$cat['id'])?'selected':'' ?>>
                <?= e($cat['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group" style="margin:0">
          <label class="form-label">Descripcion</label>
          <input type="text" name="description" class="form-control" value="<?= e($editing['description']??'') ?>">
        </div>
      </div>
      <div class="form-group" style="margin-top:1rem">
        <label class="form-label">Imagen del producto</label>
        <div style="display:flex;gap:1rem;align-items:center;flex-wrap:wrap">
          <?php if (!empty($editing['image']) && file_exists('../assets/images/'.$editing['image'])): ?>
            <img src="../assets/images/<?= e($editing['image']) ?>"
                 style="width:70px;height:70px;object-fit:cover;border-radius:8px;border:1px solid rgba(200,216,240,0.15)">
          <?php endif; ?>
          <div style="flex:1">
            <input type="file" name="image" accept="image/*" class="form-control" style="padding:0.5rem;cursor:pointer">
            <p style="font-size:0.75rem;color:var(--muted);margin-top:0.3rem">
              JPG, PNG, WEBP — maximo 5MB
              <?= $editing && $editing['image'] ? '· Deja vacio para mantener imagen actual' : '' ?>
            </p>
          </div>
        </div>
      </div>
      <div style="margin-top:1.2rem;display:flex;gap:0.8rem">
        <button type="submit" class="btn btn-primary"><?= $editing ? 'Guardar cambios' : 'Crear producto' ?></button>
        <?php if ($editing): ?><a href="products.php" class="btn btn-secondary">Cancelar</a><?php endif; ?>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="table-wrap">
    <table>
      <thead><tr><th>ID</th><th>Nombre</th><th>Categoria</th><th>Precio</th><th>Stock</th><th>Acciones</th></tr></thead>
      <tbody>
        <?php foreach ($products as $p): ?>
          <tr>
            <td><?= $p['id'] ?></td>
            <td><strong style="color:var(--moon)"><?= e($p['name']) ?></strong></td>
            <td><?= e($p['cat_name']??'—') ?></td>
            <td><?= precio($p['price']) ?></td>
            <td><span style="color:<?= $p['stock']<=3?'var(--danger)':'var(--success)' ?>"><?= $p['stock'] ?></span></td>
            <td style="display:flex;gap:0.5rem;padding-block:0.65rem">
              <a href="products.php?edit=<?= $p['id'] ?>" class="btn btn-secondary btn-sm">Editar</a>
              <a href="products.php?delete=<?= $p['id'] ?>" class="btn btn-danger btn-sm"
                 onclick="return confirm('Eliminar este producto?')">Eliminar</a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($products)): ?>
          <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:2rem">Sin productos.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

</div>
</div>
<?php require_once '../includes/footer.php'; // footer ?>
