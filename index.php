<?php
require_once 'includes/config.php';
$pageTitle = 'NYX - La noche que ilumina tus compras';
$db = getDB();

$search   = trim($_GET['q'] ?? '');
$category = (int)($_GET['cat'] ?? 0);
$cats     = $db->query("SELECT * FROM categories ORDER BY name")->fetchAll();

$sql = "SELECT p.*, c.name AS cat_name,
               COALESCE(AVG(r.rating),0) AS avg_rating,
               COUNT(r.id) AS review_count
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN reviews r ON r.product_id = p.id
        WHERE p.stock > 0";
$params = [];

if ($search) {
    $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($category) {
    $sql .= " AND p.category_id = ?";
    $params[] = $category;
}
$sql .= " GROUP BY p.id ORDER BY p.created_at DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

require_once 'includes/header.php';
?>
<div class="container">
<div class="page-content">

<div class="hero">
  <img src="assets/images/logo.png" alt="NYX" class="hero-logo">
  <h1>NYX</h1>
  <div class="hero-slogan">La noche que ilumina tus compras</div>
</div>

<form method="GET" class="search-bar">
  <input type="text" name="q" class="form-control"
         placeholder="Buscar productos..."
         value="<?= e($search) ?>">
  <select name="cat" class="form-control" style="max-width:180px" onchange="this.form.submit()">
    <option value="0">Todas las categorias</option>
    <?php foreach ($cats as $cat): ?>
      <option value="<?= $cat['id'] ?>" <?= $category==$cat['id']?'selected':'' ?>>
        <?= e($cat['name']) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <button type="submit" class="btn btn-primary">Buscar</button>
  <?php if ($search || $category): ?>
    <a href="index.php" class="btn btn-secondary">Limpiar</a>
  <?php endif; ?>
</form>

<div class="category-tabs">
  <a href="index.php" class="category-tab <?= !$category&&!$search?'active':'' ?>">Todos</a>
  <?php foreach ($cats as $cat): ?>
    <a href="index.php?cat=<?= $cat['id'] ?>"
       class="category-tab <?= $category==$cat['id']?'active':'' ?>">
      <?= e($cat['name']) ?>
    </a>
  <?php endforeach; ?>
</div>

<?php if ($search || $category): ?>
  <p style="color:var(--muted);margin-bottom:1rem;font-size:0.9rem">
    <?= count($products) ?> resultado(s)
  </p>
<?php endif; ?>

<?php if (empty($products)): ?>
  <div class="empty-state">
    <div class="icon">🌙</div>
    <h3>Sin resultados</h3>
    <p>Intenta con otro termino de busqueda.</p>
  </div>
<?php else: ?>
  <div class="grid-4">
    <?php foreach ($products as $p): ?>
      <div class="product-card">
        <div class="product-img">
          <?php if ($p['image'] && file_exists('assets/images/'.$p['image'])): ?>
            <img src="assets/images/<?= e($p['image']) ?>" alt="<?= e($p['name']) ?>">
          <?php else: ?>
            🌙
          <?php endif; ?>
        </div>
        <div class="product-body">
          <?php if ($p['cat_name']): ?>
            <div class="product-cat"><?= e($p['cat_name']) ?></div>
          <?php endif; ?>
          <div class="product-name"><?= e($p['name']) ?></div>
          <div class="product-desc"><?= e(mb_strimwidth($p['description']??'',0,75,'…')) ?></div>
          <div class="stars">
            <?php for ($i=1;$i<=5;$i++): ?>
              <span class="star <?= $i<=round($p['avg_rating'])?'filled':'' ?>">&#9733;</span>
            <?php endfor; ?>
            <span style="font-size:0.75rem;color:var(--muted);margin-left:0.3rem">(<?= $p['review_count'] ?>)</span>
          </div>
          <div class="product-price"><?= precio($p['price']) ?></div>
          <?php if ($p['stock'] <= 3): ?>
            <div class="stock-alert">Ultimas <?= $p['stock'] ?> unidades!</div>
          <?php else: ?>
            <div class="product-stock"><?= $p['stock'] ?> disponibles</div>
          <?php endif; ?>
        </div>
        <div class="product-actions">
          <a href="product.php?id=<?= $p['id'] ?>" class="btn btn-secondary">Ver</a>
          <button class="btn btn-primary" data-product="<?= $p['id'] ?>"
                  onclick="addToCart(<?= $p['id'] ?>)">Agregar</button>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

</div>
</div>
<?php require_once 'includes/footer.php'; ?>
