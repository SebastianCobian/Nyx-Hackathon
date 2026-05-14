<?php
require_once 'includes/config.php';
$db = getDB();
$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }

$stmt = $db->prepare("
    SELECT p.*, c.name AS cat_name,
           COALESCE(AVG(r.rating),0) AS avg_rating,
           COUNT(r.id) AS review_count
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN reviews r ON r.product_id = p.id
    WHERE p.id = ? GROUP BY p.id
");
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) { header('Location: index.php'); exit; }

$reviews = $db->prepare("SELECT r.*, u.name AS user_name FROM reviews r JOIN users u ON r.user_id=u.id WHERE r.product_id=? ORDER BY r.created_at DESC");
$reviews->execute([$id]);
$reviews = $reviews->fetchAll();

$userReview = null;
if (isLoggedIn()) {
    $s = $db->prepare("SELECT * FROM reviews WHERE product_id=? AND user_id=?");
    $s->execute([$id, $_SESSION['user_id']]);
    $userReview = $s->fetch();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
    $rating  = (int)($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');
    if ($rating < 1 || $rating > 5) {
        $error = 'Selecciona una calificacion de 1 a 5 estrellas.';
    } else {
        if ($userReview) {
            $db->prepare("UPDATE reviews SET rating=?,comment=? WHERE id=?")->execute([$rating,$comment,$userReview['id']]);
        } else {
            $db->prepare("INSERT INTO reviews (product_id,user_id,rating,comment) VALUES (?,?,?,?)")->execute([$id,$_SESSION['user_id'],$rating,$comment]);
        }
        setFlash('success', 'Resena guardada!');
        header("Location: product.php?id=$id"); exit;
    }
}

$pageTitle = e($product['name']).' - NYX';
require_once 'includes/header.php';
?>
<div class="container">
<div class="page-content">

<a href="index.php" class="btn btn-secondary btn-sm" style="margin-bottom:1.5rem">Volver a la tienda</a>

<div class="grid-2" style="gap:2rem;align-items:start">

  <div class="card">
    <div style="height:260px;background:linear-gradient(135deg,rgba(16,16,42,0.9),rgba(8,8,24,0.95));display:flex;align-items:center;justify-content:center;font-size:5rem">
      <?php if ($product['image'] && file_exists('assets/images/'.$product['image'])): ?>
        <img src="assets/images/<?= e($product['image']) ?>" style="width:100%;height:100%;object-fit:cover">
      <?php else: ?> 🌙 <?php endif; ?>
    </div>
    <div class="card-body">
      <?php if ($product['cat_name']): ?>
        <div class="product-cat" style="margin-bottom:0.5rem"><?= e($product['cat_name']) ?></div>
      <?php endif; ?>
      <h1 style="font-family:'Cinzel',serif;font-size:1.4rem;font-weight:700;color:var(--moon);margin-bottom:0.8rem">
        <?= e($product['name']) ?>
      </h1>
      <div style="display:flex;align-items:center;gap:0.6rem;margin-bottom:1rem">
        <div class="stars">
          <?php for ($i=1;$i<=5;$i++): ?>
            <span class="star <?= $i<=round($product['avg_rating'])?'filled':'' ?>">&#9733;</span>
          <?php endfor; ?>
        </div>
        <span style="color:var(--muted);font-size:0.85rem">
          <?= number_format($product['avg_rating'],1) ?> (<?= $product['review_count'] ?> resenas)
        </span>
      </div>
      <p style="color:var(--muted);line-height:1.8;margin-bottom:1.2rem"><?= e($product['description']) ?></p>
      <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem">
        <span style="font-family:'Cinzel',serif;font-size:1.8rem;font-weight:700;color:var(--accent)"><?= precio($product['price']) ?></span>
        <?php if ($product['stock'] <= 0): ?>
          <span style="color:var(--danger);font-weight:700">Sin stock</span>
        <?php elseif ($product['stock'] <= 3): ?>
          <div class="stock-alert">Ultimas <?= $product['stock'] ?> unidades!</div>
        <?php else: ?>
          <span style="color:var(--muted);font-size:0.85rem"><?= $product['stock'] ?> disponibles</span>
        <?php endif; ?>
      </div>
      <?php if ($product['stock'] > 0): ?>
        <button class="btn btn-primary btn-block" style="margin-top:1.2rem;padding:0.9rem"
                data-product="<?= $product['id'] ?>" onclick="addToCart(<?= $product['id'] ?>)">
          🌙 Agregar al carrito
        </button>
      <?php endif; ?>
    </div>
  </div>

  <div>
    <?php if (isLoggedIn()): ?>
      <div class="card" style="margin-bottom:1.5rem">
        <div class="card-body">
          <h2 style="font-family:'Cinzel',serif;font-size:1rem;font-weight:700;color:var(--moon);margin-bottom:1rem">
            <?= $userReview ? 'Editar tu resena' : 'Escribir resena' ?>
          </h2>
          <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
          <form method="POST">
            <div class="form-group">
              <label class="form-label">Calificacion</label>
              <div class="stars-input">
                <?php for ($i=5;$i>=1;$i--): ?>
                  <input type="radio" name="rating" id="s<?= $i ?>" value="<?= $i ?>"
                    <?= ($userReview && $userReview['rating']==$i)?'checked':'' ?>>
                  <label for="s<?= $i ?>">&#9733;</label>
                <?php endfor; ?>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Comentario (opcional)</label>
              <textarea name="comment" class="form-control" rows="3"
                placeholder="Que te parecio el producto?"><?= e($userReview['comment'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Publicar resena</button>
          </form>
        </div>
      </div>
    <?php else: ?>
      <div class="card" style="margin-bottom:1.5rem">
        <div class="card-body" style="text-align:center;padding:2rem">
          <p style="color:var(--muted);margin-bottom:1rem">Inicia sesion para dejar una resena</p>
          <a href="login.php?redirect=product.php?id=<?= $id ?>" class="btn btn-primary">Iniciar sesion</a>
        </div>
      </div>
    <?php endif; ?>

    <div class="card">
      <div class="card-body">
        <h2 style="font-family:'Cinzel',serif;font-size:1rem;font-weight:700;color:var(--moon);margin-bottom:1rem">
          Resenas (<?= count($reviews) ?>)
        </h2>
        <?php if (empty($reviews)): ?>
          <div class="empty-state" style="padding:2rem">
            <div class="icon">&#9733;</div>
            <h3>Sin resenas aun</h3>
            <p>Se el primero en opinar</p>
          </div>
        <?php else: ?>
          <?php foreach ($reviews as $r): ?>
            <div style="padding:1rem 0;border-bottom:1px solid rgba(200,216,240,0.07)">
              <div style="display:flex;justify-content:space-between;margin-bottom:0.4rem">
                <strong style="font-size:0.9rem;color:var(--moon)"><?= e($r['user_name']) ?></strong>
                <span style="font-size:0.78rem;color:var(--muted)"><?= date('d/m/Y', strtotime($r['created_at'])) ?></span>
              </div>
              <div class="stars">
                <?php for ($i=1;$i<=5;$i++): ?>
                  <span class="star <?= $i<=$r['rating']?'filled':'' ?>">&#9733;</span>
                <?php endfor; ?>
              </div>
              <?php if ($r['comment']): ?>
                <p style="font-size:0.88rem;color:var(--text);margin-top:0.5rem;line-height:1.6"><?= e($r['comment']) ?></p>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

</div>
</div>
</div>
<?php require_once 'includes/footer.php'; ?>
