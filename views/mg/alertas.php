<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid px-4 py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <span class="eyebrow-badge mb-2 d-inline-block"><i class="bi bi-bell-fill me-1"></i> Monitoreo Activo</span>
      <h2 class="fw-bold text-dark mb-1">Panel de Alertas Académicas</h2>
      <p class="text-muted mb-0">Detección de sobrecarga, plazos vencidos e hitos pendientes en Modalidades de Grado.</p>
    </div>
  </div>

  <div class="main-card-container">
    <?php if (!empty($alertas)): ?>
      <div class="list-group list-group-flush">
        <?php foreach ($alertas as $alt): ?>
          <div class="list-group-item d-flex justify-content-between align-items-center py-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
              <span class="badge bg-<?= $alt['severidad'] ?> p-2 fs-6"><i class="bi bi-exclamation-triangle-fill"></i></span>
              <div>
                <strong class="d-block text-dark"><?= e($alt['tipo']) ?></strong>
                <span class="text-secondary small"><?= e($alt['mensaje']) ?></span>
              </div>
            </div>
            <a href="<?= $alt['enlace'] ?>" class="btn btn-sm btn-outline-primary rounded-3">Atender Alerta <i class="bi bi-arrow-right ms-1"></i></a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="text-center py-5 text-success">
        <i class="bi bi-check-circle-fill display-4 d-block mb-3"></i>
        <h5 class="fw-bold">¡Todo en orden!</h5>
        <p class="text-muted mb-0">No se detectaron alertas críticas ni retrasos académicos pendientes de atención.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>