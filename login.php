<?php
require_once 'includes/config.php';
if (isLoggedIn()) { header('Location: index.php'); exit; }
$error = '';
$redirect = $_GET['redirect'] ?? 'index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $db    = getDB();
    if ($email && $pass) {
        $stmt = $db->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name']    = $user['name'];
            $_SESSION['role']    = $user['role'];
            setFlash('success', 'Bienvenido, '.$user['name'].'!');
            header('Location: '.$redirect); exit;
        }
        $error = 'Correo o contrasena incorrectos.';
    } else { $error = 'Completa todos los campos.'; }
}

$pageTitle = 'Iniciar sesion - NYX';
require_once 'includes/header.php';
?>
<div class="container">
<div class="page-content">
<div style="max-width:420px;margin:0 auto">
  <div style="text-align:center;margin-bottom:2rem">
    <h1 style="font-family:'Cinzel',serif;font-size:1.8rem;font-weight:800;color:var(--moon)">Iniciar sesion</h1>
    <p style="color:var(--muted);margin-top:0.4rem">No tienes cuenta?
      <a href="register.php" style="color:var(--accent)">Registrate</a>
    </p>
  </div>
  <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
  <div class="card">
    <div class="card-body">
      <form method="POST">
        <input type="hidden" name="redirect" value="<?= e($redirect) ?>">
        <div class="form-group">
          <label class="form-label">Correo electronico</label>
          <input type="email" name="email" class="form-control" placeholder="tu@correo.com" required value="<?= e($_POST['email']??'') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Contrasena</label>
          <input type="password" name="password" class="form-control" placeholder="Tu contrasena" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block" style="margin-top:0.5rem">Iniciar sesion</button>
      </form>
    </div>
  </div>
  <p style="text-align:center;margin-top:1rem;font-size:0.82rem;color:var(--muted)">
    Admin: <code>admin@nyx.com</code> / <code>password</code>
  </p>
</div>
</div>
</div>
<?php require_once 'includes/footer.php'; ?>
