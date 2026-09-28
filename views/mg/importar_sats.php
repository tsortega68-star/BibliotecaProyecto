<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid px-4 py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <span class="eyebrow-badge mb-2 d-inline-block"><i class="bi bi-file-earmark-spreadsheet me-1"></i> Integración SATS</span>
      <h2 class="fw-bold text-dark mb-1">Importar Padrón de Estudiantes (CSV)</h2>
      <p class="text-muted mb-0">Carga masiva de estudiantes inscritos en Modalidades de Grado desde el sistema SATS.</p>
    </div>
    <a href="/controllers/mg_expedientes.php" class="btn btn-outline-secondary rounded-3"><i class="bi bi-arrow-left me-1"></i> Volver a Expedientes</a>
  </div>

  <?php mostrarFlash(); ?>

  <div class="row g-4">
    <div class="col-lg-6">
      <div class="main-card-container">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-cloud-upload me-2 text-primary"></i> Subir Archivo CSV</h5>
        
        <form action="/controllers/mg_importar_sats.php" method="POST" enctype="multipart/form-data">
          <?= csrf_campo() ?>
          <div class="mb-3">
            <label class="form-label fw-bold">Seleccionar archivo .CSV exportado de SATS</label>
            <input type="file" name="archivo_csv" class="form-control" accept=".csv" required>
            <small class="text-muted mt-1 d-block">Formato de columnas esperadas: <code>RU, Nombres, Apellidos, Correo, Modalidad (PROYECTO/TESIS/DIRIGIDO), Cohorte</code></small>
          </div>

          <button type="submit" class="btn-primary-custom w-100 justify-content-center">
            <i class="bi bi-upload me-1"></i> Procesar e Importar Padrón
          </button>
        </form>
      </div>
    </div>

    <!-- Guía y Resultados -->
    <div class="col-lg-6">
      <div class="main-card-container">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle me-2 text-primary"></i> Formato del CSV</h5>
        <p class="text-muted small">Ejemplo de contenido para el archivo CSV:</p>
        <pre class="bg-light p-3 rounded-3 border small">
1009281,Carlos,Mendoza,carlos.m@upds.edu.bo,PROYECTO,G1-2026-03
1009282,María,Gonzales,maria.g@upds.edu.bo,TESIS,G1-2026-03
1009283,Juan,Pérez,juan.p@upds.edu.bo,DIRIGIDO,G1-2026-03
        </pre>

        <?php if (!empty($resultadoImportacion)): ?>
          <hr>
          <h6 class="fw-bold">Resumen de Importación</h6>
          <div class="d-flex gap-3 mb-3">
            <span class="badge bg-success p-2">Éxitos: <?= $resultadoImportacion['exitos'] ?></span>
            <span class="badge bg-danger p-2">Errores: <?= $resultadoImportacion['errores'] ?></span>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>