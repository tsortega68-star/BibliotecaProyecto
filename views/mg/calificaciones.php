<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid px-4 py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <span class="eyebrow-badge mb-2 d-inline-block"><i class="bi bi-award-fill me-1"></i> Evaluación Académica</span>
      <h2 class="fw-bold text-dark mb-1">Registro de Calificaciones MG1 / MG2</h2>
      <p class="text-muted mb-0">Consignación de notas de defensas y publicación oficial para el estudiante.</p>
    </div>
  </div>

  <?php mostrarFlash(); ?>

  <div class="main-card-container">
    <div class="table-responsive">
      <table class="table table-modern align-middle">
        <thead>
          <tr>
            <th>ESTUDIANTE / RU</th>
            <th>MODALIDAD / ETAPA</th>
            <th>DEFENSA</th>
            <th>NOTA (0 - 100)</th>
            <th>ESTADO NOTA</th>
            <th class="text-end">ACCIONES</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($defensasCalificar)): ?>
            <?php foreach ($defensasCalificar as $item): ?>
              <tr>
                <td>
                  <strong class="text-dark d-block"><?= e($item['estudiante_nombre'] . ' ' . $item['estudiante_apellido']) ?></strong>
                  <small class="text-muted">RU: <?= e($item['estudiante_ru']) ?></small>
                </td>
                <td>
                  <span class="badge-soft-primary"><?= e($item['modalidad_nombre']) ?></span>
                  <span class="badge bg-secondary ms-1"><?= strtoupper(e($item['etapa'])) ?></span>
                </td>
                <td><small class="text-muted"><?= e($item['fecha']) ?> en <?= e($item['ambiente']) ?></small></td>
                <td>
                  <?php if ($item['nota'] !== null): ?>
                    <span class="fw-bold fs-5 text-<?= $item['nota'] >= 51 ? 'success' : 'danger' ?>"><?= number_format((float)$item['nota'], 1) ?> pts</span>
                  <?php else: ?>
                    <span class="text-muted">Sin calificar</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if ($item['publicada'] == 1): ?>
                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Publicada</span>
                  <?php else: ?>
                    <span class="badge bg-warning text-dark"><i class="bi bi-lock me-1"></i> Borrador</span>
                  <?php endif; ?>
                </td>
                <td class="text-end">
                  <button type="button" class="btn btn-sm btn-outline-primary rounded-3" data-bs-toggle="modal" data-bs-target="#modalNota<?= $item['id_defensa'] ?>">
                    <i class="bi bi-pencil-square me-1"></i> Registrar Nota
                  </button>
                </td>
              </tr>

              <!-- Modal Registrar Nota -->
              <div class="modal fade" id="modalNota<?= $item['id_defensa'] ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <form action="/controllers/mg_calificaciones.php" method="POST">
                      <?= csrf_campo() ?>
                      <input type="hidden" name="id_defensa" value="<?= $item['id_defensa'] ?>">
                      <div class="modal-header">
                        <h5 class="modal-header-title fw-bold">Calificar Defensa: <?= e($item['estudiante_nombre']) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <div class="mb-3">
                          <label class="form-label fw-bold">Nota de Defensa (0 a 100)</label>
                          <input type="number" step="0.1" min="0" max="100" name="nota" value="<?= e($item['nota'] ?? '') ?>" class="form-control" placeholder="Ej: 85.5" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label fw-bold">Observaciones del Tribunal</label>
                          <textarea name="observaciones" class="form-control" rows="3" placeholder="Comentarios de forma o fondo del tribunal evaluador..."></textarea>
                        </div>
                        <div class="form-check mb-2">
                          <input type="checkbox" name="publicada" class="form-check-input" id="pub<?= $item['id_defensa'] ?>" <?= ($item['publicada'] == 1) ? 'checked' : '' ?>>
                          <label class="form-check-label fw-semibold" for="pub<?= $item['id_defensa'] ?>">Publicar nota para visibilidad del estudiante</label>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn-primary-custom">Guardar Calificación</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="6" class="text-center py-4 text-muted">No existen defensas pendientes de calificación.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>