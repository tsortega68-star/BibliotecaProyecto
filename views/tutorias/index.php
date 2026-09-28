<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Tutorías</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="mb-4 text-primary">Listado de Tutorías</h1>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Estudiante</th>
                        <th>Tutor</th>
                        <th>Materia</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th> <!-- Nueva columna -->
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = $tutorias->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($fila['id_tutoria']) ?></td>
                            <td><?= htmlspecialchars($fila['id_estudiante']) ?></td>
                            <td><?= htmlspecialchars($fila['id_tutor']) ?></td>
                            <td><?= htmlspecialchars($fila['id_materia']) ?></td>
                            <td><?= htmlspecialchars($fila['fecha']) ?></td>
                            <td>
                                <?php if ($fila['estado'] === 'pendiente'): ?>
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                <?php elseif ($fila['estado'] === 'aprobada'): ?>
                                    <span class="badge bg-success">Aprobada</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Otro</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="ver.php?id=<?= $fila['id_tutoria'] ?>" class="btn btn-sm btn-info">Ver</a>
                                <a href="editar.php?id=<?= $fila['id_tutoria'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                <a href="eliminar.php?id=<?= $fila['id_tutoria'] ?>" class="btn btn-sm btn-danger"
                                   onclick="return confirm('¿Seguro que deseas eliminar esta tutoría?');">
                                   Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
