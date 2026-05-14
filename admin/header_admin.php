<?php
$flash = getFlash();
$bp = $basePath ?? '../';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle ?? 'Admin - NYX') ?></title>
  <link rel="stylesheet" href="<?= $bp ?>assets/css/style.css">
</head>
<body>

<div class="page-bg-glow"></div>

<nav class="navbar">
  <div class="navbar-inner">
    <a href="<?= $bp ?>admin/index.php" class="navbar-brand">
      <img src="<?= $bp ?>assets/images/logo.png" alt="NYX">
      <div>
        <span class="navbar-brand-name">NYX</span>
        <span class="navbar-brand-slogan">Panel de administracion</span>
      </div>
    </a>

    <a href="<?= $bp ?>admin/index.php" class="nav-link <?= basename($_SERVER['PHP_SELF'])=='index.php'?'admin-link':'' ?>">Dashboard</a>
    <a href="<?= $bp ?>admin/products.php" class="nav-link <?= basename($_SERVER['PHP_SELF'])=='products.php'?'admin-link':'' ?>">Productos</a>
    <a href="<?= $bp ?>admin/orders.php" class="nav-link <?= basename($_SERVER['PHP_SELF'])=='orders.php'?'admin-link':'' ?>">Pedidos</a>
    <a href="<?= $bp ?>admin/categories.php" class="nav-link <?= basename($_SERVER['PHP_SELF'])=='categories.php'?'admin-link':'' ?>">Categorias</a>
    <a href="<?= $bp ?>admin/users.php" class="nav-link <?= basename($_SERVER['PHP_SELF'])=='users.php'?'admin-link':'' ?>">Usuarios</a>

    <div style="display:flex;gap:0.8rem;margin-left:auto">
      <a href="<?= $bp ?>logout.php" class="btn btn-secondary btn-sm">Salir</a>
    </div>
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
