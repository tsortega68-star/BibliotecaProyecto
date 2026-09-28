<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="container-fluid px-4 py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <span class="eyebrow-badge mb-2 d-inline-block"><i class="bi bi-calendar-event-fill me-1"></i> Programación Oficial</span>
      <h2 class="fw-bold text-dark mb-1">Agenda de Defensas MG1 / MG2</h2>
      <p class="text-muted mb-0">Programación de tribunal, fechas, horarios y ambientes para defensas de grado.</p>
    </div>
  </div>

  <?php mostrarFlash(); ?>

  <div class="row g-4">
    <!-- Formulario de Agendamiento -->
    <div class="col-lg-5">
      <div class="main-card-container">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-clock-history me-2 text-primary"></i> Programar Nueva Defensa</h5>

        <form action="/controllers/mg_programar_defensa.php" method="POST">
          <?= csrf_campo() ?>

          <div class="mb-3">
            <label class="form-label fw-bold">Seleccionar Expediente</label>
            <select name="id_expediente" class="form-select" required>
              <option value="">-- Seleccionar Estudiante --</option>
              <?php foreach ($expedientes as $exp): ?>
                <option value="<?= $exp['id_expediente'] ?>">
                  <?= e($exp['estudiante_nombre'] . ' ' . $exp['estudiante_apellido']) ?> (<?= e($exp['modalidad_codigo']) ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Etapa de Defensa</label>
            <select name="etapa" class="form-select" required>
              <option value="mg1">MG1 (Defensa de Perfil)</option>
              <option value="mg2">MG2 (Defensa Final)</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Fecha de Defensa</label>
            <input type="date" name="fecha" class="form-control" required>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label fw-bold">Hora Inicio</label>
              <input type="time" name="hora_inicio" class="form-control" required>
            </div>
            <div class="col-6">
              <label class="form-label fw-bold">Hora Fin</label>
              <input type="time" name="hora_fin" class="form-control" required>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label fw-bold">Ambiente / Aula</label>
            <input type="text" name="ambiente" class="form-control" placeholder="Ej: Aula Magna 2 / Auditorio UPDS" required>
          </div>

          <button type="submit" class="btn-primary-custom w-100 justify-content-center">
            <i class="bi bi-calendar-check me-1"></i> Programar Defensa (Validar Choques)
          </button>
        </form>
      </div>
    </div>

    <!-- Lista / Agenda de Defensas -->
    <div class="col-lg-7">
      <div class="main-card-container">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-list-stars me-2 text-primary"></i> Próximas Defensas Programadas</h5>

        <div class="table-responsive">
          <table class="table table-modern align-middle">
            <thead>
              <tr>
                <th>FECHA / HORA</th>
                <th>ESTUDIANTE</th>
                <th>MODALIDAD / ETAPA</th>
                <th>AMBIENTE</th>
                <th>ESTADO</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($defensas)): ?>
                <?php foreach ($defensas as $def): ?>
                  <tr>
                    <td>
                      <strong class="text-dark d-block"><?= e($def['fecha']) ?></strong>
                      <small class="text-muted"><?= e(substr($def['hora_inicio'],0,5)) ?> - <?= e(substr($def['hora_fin'],0,5)) ?></small>
                    </td>
                    <td>
                      <span class="fw-bold"><?= e($def['estudiante_nombre'] . ' ' . $def['estudiante_apellido']) ?></span>
                      <small class="text-muted d-block">RU: <?= e($def['estudiante_ru']) ?></small>
                    </td>
                    <td>
                      <span class="badge-soft-primary"><?= e($def['modalidad_nombre']) ?></span>
                      <span class="badge bg-secondary ms-1"><?= strtoupper(e($def['etapa'])) ?></span>
                    </td>
                    <td><small class="text-dark fw-semibold"><?= e($def['ambiente']) ?></small></td>
                    <td><span class="badge-soft-success"><?= ucfirst(e($def['estado'])) ?></span></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="5" class="text-center py-4 text-muted">No hay defensas agendadas actualmente.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>