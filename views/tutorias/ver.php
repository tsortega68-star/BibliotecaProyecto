<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Tutoría</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="mb-4 text-info">Detalle de Tutoría</h1>

        <?php if ($tutoria && $tutoria->num_rows > 0): ?>
            <?php $fila = $tutoria->fetch_assoc(); ?>
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Tutoría #<?= htmlspecialchars($fila['id_tutoria']) ?></h5>
                    <p><strong>Estudiante:</strong> <?= htmlspecialchars($fila['id_estudiante']) ?></p>
                    <p><strong>Tutor:</strong> <?= htmlspecialchars($fila['id_tutor']) ?></p>
                    <p><strong>Materia:</strong> <?= htmlspecialchars($fila['id_materia']) ?></p>
                    <p><strong>Fecha:</strong> <?= htmlspecialchars($fila['fecha']) ?></p>
                    <p><strong>Estado:</strong> 
                        <?php if ($fila['estado'] === 'pendiente'): ?>
                            <span class="badge bg-warning text-dark">Pendiente</span>
                        <?php elseif ($fila['estado'] === 'aprobada'): ?>
                            <span class="badge bg-success">Aprobada</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Otro</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger">No se encontró la tutoría solicitada.</div>
        <?php endif; ?>

        <a href="tutorias.php" class="btn btn-primary mt-3">Volver al listado</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
