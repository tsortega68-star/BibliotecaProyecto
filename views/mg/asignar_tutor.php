<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid px-4 py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <span class="eyebrow-badge mb-2 d-inline-block"><i class="bi bi-person-check-fill me-1"></i> Asignación Académica</span>
      <h2 class="fw-bold text-dark mb-1">Asignar / Cambiar Tutor</h2>
      <p class="text-muted mb-0">Selecciona el docente para el acompañamiento en Modalidades de Grado.</p>
    </div>
    <a href="/controllers/mg_expedientes.php" class="btn btn-outline-secondary rounded-3"><i class="bi bi-arrow-left me-1"></i> Volver a Expedientes</a>
  </div>

  <?php mostrarFlash(); ?>

  <div class="row g-4">
    <div class="col-lg-7">
      <div class="main-card-container">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-person-badge me-2 text-primary"></i> Formulario de Asignación</h5>
        
        <form action="/controllers/mg_asignar_tutor.php?id=<?= $idExpediente ?>" method="POST">
          <?= csrf_campo() ?>
          
          <div class="mb-3">
            <label class="form-label fw-bold">Seleccionar Tutor Académico</label>
            <select name="id_tutor" class="form-select" required>
              <option value="">-- Seleccionar Tutor --</option>
              <?php foreach ($tutoresList as $tut): ?>
                <option value="<?= $tut['id_tutor'] ?>">
                  <?= e($tut['nombre'] . ' ' . $tut['apellido']) ?> (<?= e($tut['especialidad']) ?>) — [Estudiantes asignados: <?= $tut['carga'] ?>]
                </option>
              <?php endforeach; ?>
            </select>
            <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle me-1"></i> Carga recomendada: 3 estudiantes por tutor (Entrevista ENT-03).</small>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Referencia Decanatura / Nota de Aprobación</label>
            <input type="text" name="referencia_decanatura" class="form-control" placeholder="Ej: Nota DEC-042/2026 o Resolución N° 12" required>
          </div>

          <?php if (!empty($asignacionActual)): ?>
            <div class="mb-3">
              <label class="form-label fw-bold text-danger">Motivo del Cambio de Tutor</label>
              <textarea name="motivo_cambio" class="form-control" rows="2" placeholder="Describa el motivo del cambio o renuncia del tutor anterior..." required></textarea>
            </div>
          <?php endif; ?>

          <div class="mb-4 form-check">
            <input type="checkbox" name="disponibilidad_consultada" class="form-check-input" id="dispCheck" required>
            <label class="form-check-label fw-semibold" for="dispCheck">
              Confirmo que se consultó previamente la disponibilidad del docente (RN-MG-06)
            </label>
          </div>

          <button type="submit" class="btn-primary-custom w-100 justify-content-center">
            <i class="bi bi-file-earmark-text-fill me-1"></i> Confirmar Asignación y Generar Carta
          </button>
        </form>
      </div>
    </div>

    <!-- Panel Lateral: Asignación Vigente -->
    <div class="col-lg-5">
      <div class="main-card-container">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-clock-history me-2 text-primary"></i> Asignación Actual</h5>
        <?php if (!empty($asignacionActual)): ?>
          <div class="p-3 bg-light rounded-3 border">
            <div class="fw-bold text-primary fs-5"><?= e($asignacionActual['tutor_nombre'] . ' ' . $asignacionActual['tutor_apellido']) ?></div>
            <div class="text-muted small mb-2"><?= e($asignacionActual['tutor_correo']) ?></div>
            <hr>
            <div><strong>Carta N°:</strong> <?= e($asignacionActual['numero_carta']) ?></div>
            <div><strong>Fecha Asignación:</strong> <?= e($asignacionActual['fecha_asignacion']) ?></div>
            <div><strong>Referencia:</strong> <?= e($asignacionActual['referencia_decanatura']) ?></div>
            <span class="badge bg-success mt-2">Vigente</span>
          </div>
        <?php else: ?>
          <div class="text-muted py-4 text-center">Este expediente aún no tiene un tutor asignado.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>