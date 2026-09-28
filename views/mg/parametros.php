<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid px-4 py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <span class="eyebrow-badge mb-2 d-inline-block"><i class="bi bi-sliders me-1"></i> Configuración MG</span>
      <h2 class="fw-bold text-dark mb-1">Parámetros Institucionales</h2>
      <p class="text-muted mb-0">Ajusta las variables de control y reglas configurables de Modalidades de Grado.</p>
    </div>
  </div>

  <?php mostrarFlash(); ?>

  <div class="main-card-container">
    <form action="/controllers/mg_parametros.php" method="POST">
      <?= csrf_campo() ?>
      <div class="table-responsive">
        <table class="table table-modern align-middle">
          <thead>
            <tr>
              <th>PARÁMETRO / CLAVE</th>
              <th>DESCRIPCIÓN</th>
              <th>ESTADO EVIDENCIA</th>
              <th style="width: 200px;">VALOR CONFIGURADO</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($listaParametros as $p): ?>
              <tr>
                <td>
                  <strong class="text-dark d-block"><?= e($p['clave']) ?></strong>
                  <small class="text-muted">Fuente: <?= e($p['fuente']) ?></small>
                </td>
                <td><small class="text-secondary"><?= e($p['descripcion']) ?></small></td>
                <td>
                  <span class="badge bg-<?= $p['estado_evidencia'] === 'confirmado' ? 'success' : ($p['estado_evidencia'] === 'pendiente' ? 'warning' : 'info') ?>-subtle text-dark">
                    <?= ucfirst(e($p['estado_evidencia'])) ?>
                  </span>
                </td>
                <td>
                  <input type="text" name="parametros[<?= e($p['clave']) ?>]" value="<?= e($p['valor']) ?>" class="form-control form-control-sm">
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="text-end mt-3">
        <button type="submit" class="btn-primary-custom"><i class="bi bi-save me-1"></i> Guardar Cambios</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>