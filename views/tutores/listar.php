<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid px-4 py-4">
  
  <!-- Encabezado del Módulo -->
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
      <span class="eyebrow-badge mb-2 d-inline-block">
        <i class="bi bi-person-video3 me-1"></i> Equipo Académico
      </span>
      <h2 class="fw-bold text-dark mb-1">Docentes y Tutores</h2>
      <p class="text-muted mb-0">Docentes habilitados para brindar acompañamiento en materias específicas.</p>
    </div>
    <div>
      <a href="/controllers/tutores_crear.php" class="btn-primary-custom text-decoration-none">
        <i class="bi bi-person-plus-fill"></i> Nuevo Tutor
      </a>
    </div>
  </div>

  <!-- Tarjeta Contenedora Principal -->
  <div class="main-card-container">
    
    <!-- Filtro y Buscador Rápido -->
    <div class="row g-3 mb-4 align-items-center">
      <div class="col-md-5 col-lg-4">
        <div class="input-group">
          <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
          <input type="text" class="form-control border-start-0 ps-0 shadow-none" placeholder="Buscar tutor por nombre o especialidad...">
        </div>
      </div>
    </div>

    <!-- Tabla de Tutores Estilizada -->
    <div class="table-responsive">
      <table class="table table-modern align-middle">
        <thead>
          <tr>
            <th>TUTOR</th>
            <th>ESPECIALIDAD</th>
            <th>MATERIAS</th>
            <th>PROMEDIO</th>
            <th>ESTADO</th>
            <th class="text-end">ACCIONES</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($tutores)): ?>
            <?php foreach ($tutores as $tutor): ?>
              <tr>
                <td>
                  <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                      <?= strtoupper(substr(e($tutor['nombre'] ?? 'T'), 0, 1)) ?>
                    </div>
                    <div>
                      <div class="fw-bold text-dark"><?= e(($tutor['nombre'] ?? '') . ' ' . ($tutor['apellido'] ?? '')) ?></div>
                      <small class="text-muted">@<?= e($tutor['usuario'] ?? 'tutor') ?></small>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="text-secondary"><?= e($tutor['especialidad'] ?? 'Sin especialidad') ?></span>
                </td>
                <td>
                  <span class="badge-soft-primary"><?= (int)($tutor['total_materias'] ?? 0) ?></span>
                </td>
                <td>
                  <span class="fw-bold text-warning"><i class="bi bi-star-fill me-1"></i><?= number_format((float)($tutor['promedio'] ?? 0), 1) ?> / 5</span>
                </td>
                <td>
                  <span class="badge-soft-success"><?= ucfirst(e($tutor['estado'] ?? 'activo')) ?></span>
                </td>
                <td class="text-end">
                  <div class="d-inline-flex gap-1">
                    <a href="/controllers/tutor_materias.php?id=<?= $tutor['id_tutor'] ?>" class="action-btn" title="Asignar Materias">
                      <i class="bi bi-journal-plus"></i>
                    </a>
                    <a href="/controllers/tutores_editar.php?id=<?= $tutor['id_tutor'] ?>" class="action-btn" title="Editar">
                      <i class="bi bi-pencil-fill"></i>
                    </a>
                    <a href="/controllers/tutores_eliminar.php?id=<?= $tutor['id_tutor'] ?>" class="action-btn btn-danger-soft" title="Eliminar" onclick="return confirm('¿Seguro de eliminar este tutor?');">
                      <i class="bi bi-trash-fill"></i>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="text-center py-4 text-muted">No hay tutores registrados en el sistema.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>