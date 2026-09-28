<?php
$error = $_GET['error'] ?? null;
$mensaje = $_GET['mensaje'] ?? null;
require_once __DIR__ . '/../layouts/header.php';
?>
<div class="container d-flex justify-content-center align-items-center min-vh-100 py-5">
  <div class="main-card-container shadow-lg p-4 p-md-5" style="max-width: 420px; width: 100%;">
    <div class="text-center mb-4">
      <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 65px; height: 65px;">
        <i class="bi bi-mortarboard-fill fs-2 text-warning"></i>
      </div>
      <h3 class="fw-bold text-dark mb-1">Acceso al Portal</h3>
      <p class="text-muted small mb-0">Universidad Privada Domingo Savio · Sede Tarija</p>
    </div>

    <?php mostrarFlash(); ?>
    <?php if ($error): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-1"></i> <?= e($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <form action="/controllers/login_procesar.php" method="POST">
      <?= csrf_campo() ?>
      
      <div class="mb-3">
        <label class="form-label fw-bold small text-muted">USUARIO O CORREO INSTITUCIONAL</label>
        <div class="input-group">
          <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-person"></i></span>
          <input type="text" name="usuario" class="form-control border-start-0 ps-0" placeholder="Ej: admin o usuario@upds.edu.bo" required autofocus>
        </div>
      </div>

      <div class="mb-4">
        <label class="form-label fw-bold small text-muted">CONTRASEÑA</label>
        <div class="input-group">
          <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-lock"></i></span>
          <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="••••••••" required>
        </div>
      </div>

      <button type="submit" class="btn-primary-custom w-100 justify-content-center py-2">
        <i class="bi bi-box-arrow-in-right me-1"></i> Ingresar al Sistema
      </button>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>