<?php
require_once 'includes/config.php';
if (isLoggedIn()) { header('Location: index.php'); exit; }
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $pass2 = $_POST['password2'] ?? '';
    $db    = getDB();

    if (!$name || !$email || !$pass) { $error = 'Completa todos los campos.'; }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $error = 'Correo no valido.'; }
    elseif (strlen($pass) < 6) { $error = 'La contrasena debe tener al menos 6 caracteres.'; }
    elseif ($pass !== $pass2) { $error = 'Las contrasenas no coinciden.'; }
    else {
        $stmt = $db->prepare("SELECT id FROM users WHERE email=?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) { $error = 'Este correo ya esta registrado.'; }
        else {
            $hash = password_hash($pass, PASSWORD_BCRYPT);
            $db->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,'customer')")->execute([$name,$email,$hash]);
            $_SESSION['user_id'] = $db->lastInsertId();
            $_SESSION['name']    = $name;
            $_SESSION['role']    = 'customer';
            setFlash('success', 'Bienvenido, '.$name.'!');
            header('Location: index.php'); exit;
        }
    }
}

$pageTitle = 'Crear cuenta - NYX';
require_once 'includes/header.php';
?>
<div class="container">
<div class="page-content">
<div style="max-width:460px;margin:0 auto">
  <div style="text-align:center;margin-bottom:2rem">
    <h1 style="font-family:'Cinzel',serif;font-size:1.8rem;font-weight:800;color:var(--moon)">Crear cuenta</h1>
    <p style="color:var(--muted);margin-top:0.4rem">Ya tienes cuenta?
      <a href="login.php" style="color:var(--accent)">Inicia sesion</a>
    </p>
  </div>
  <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
  <div class="card">
    <div class="card-body">
      <form method="POST">
        <div class="form-group">
          <label class="form-label">Nombre completo</label>
          <input type="text" name="name" class="form-control" placeholder="Tu nombre" required value="<?= e($_POST['name']??'') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Correo electronico</label>
          <input type="email" name="email" class="form-control" placeholder="tu@correo.com" required value="<?= e($_POST['email']??'') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Contrasena</label>
          <input type="password" name="password" class="form-control" placeholder="Minimo 6 caracteres" required>
        </div>
        <div class="form-group">
          <label class="form-label">Confirmar contrasena</label>
          <input type="password" name="password2" class="form-control" placeholder="Repite tu contrasena" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block" style="margin-top:0.5rem">Crear cuenta</button>
      </form>
    </div>
  </div>
</div>
</div>
</div>
<?php require_once 'includes/footer.php'; ?>
