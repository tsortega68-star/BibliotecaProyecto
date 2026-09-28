<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="container-fluid px-4 py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <span class="eyebrow-badge mb-2 d-inline-block"><i class="bi bi-folder-fill me-1"></i> Gestión Académica</span>
      <h2 class="fw-bold text-dark mb-1">Expedientes de Modalidades de Grado</h2>
      <p class="text-muted mb-0">Listado general de estudiantes en proceso de grado (MG1 / MG2).</p>
    </div>
    <div class="d-flex gap-2">
      <a href="/controllers/mg_importar_sats.php" class="btn btn-outline-primary rounded-3"><i class="bi bi-file-earmark-spreadsheet me-1"></i> Importar desde SATS (CSV)</a>
    </div>
  </div>

  <?php mostrarFlash(); ?>

  <div class="main-card-container">
    <div class="table-responsive">
      <table class="table table-modern align-middle">
        <thead>
          <tr>
            <th>ESTUDIANTE / RU</th>
            <th>MODALIDAD</th>
            <th>COHORTE</th>
            <th>ETAPA</th>
            <th>ESTADO</th>
            <th class="text-end">ACCIONES</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($expedientes)): ?>
            <?php foreach ($expedientes as $exp): ?>
              <tr>
                <td>
                  <strong class="text-dark d-block"><?= e($exp['estudiante_nombre'] . ' ' . $exp['estudiante_apellido']) ?></strong>
                  <small class="text-muted">RU: <?= e($exp['estudiante_ru']) ?> | <?= e($exp['correo']) ?></small>
                </td>
                <td><span class="badge-soft-primary"><?= e($exp['modalidad_nombre']) ?></span></td>
                <td><small class="text-secondary"><?= e($exp['cohorte_nombre']) ?></small></td>
                <td><span class="badge bg-info-subtle text-dark"><?= strtoupper(e($exp['etapa_actual'])) ?></span></td>
                <td><span class="badge-soft-success"><?= ucfirst(e($exp['estado'])) ?></span></td>
                <td class="text-end">
                  <a href="/controllers/mg_expedientes.php?id=<?= $exp['id_expediente'] ?>" class="action-btn" title="Ver Ficha"><i class="bi bi-eye-fill"></i></a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="6" class="text-center py-4 text-muted">No se encontraron expedientes de grado registrados.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>