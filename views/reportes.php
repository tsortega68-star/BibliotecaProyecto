<?php include __DIR__ . '/layouts/header.php'; ?>

<div class="main-card-container mt-4">
    <h1 class="page-title">Reportes del Sistema</h1>
    <p class="eyebrow-badge">Resumen general de tutorías</p>

    <div class="row mt-3">
        <div class="col-md-3">
            <div class="metric-card">
                <div class="metric-icon bg-primary text-white"><i class="bi bi-bar-chart"></i></div>
                <div>
                    <div class="metric-value"><?= $resumen['total'] ?></div>
                    <div class="metric-label">Total de Tutorías</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card">
                <div class="metric-icon bg-warning text-white"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <div class="metric-value"><?= $resumen['pendientes'] ?></div>
                    <div class="metric-label">Pendientes</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card">
                <div class="metric-icon bg-success text-white"><i class="bi bi-check-circle"></i></div>
                <div>
                    <div class="metric-value"><?= $resumen['realizadas'] ?></div>
                    <div class="metric-label">Realizadas</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card">
                <div class="metric-icon bg-danger text-white"><i class="bi bi-x-circle"></i></div>
                <div>
                    <div class="metric-value"><?= $resumen['canceladas'] ?></div>
                    <div class="metric-label">Canceladas</div>
                </div>
            </div>
        </div>
    </div>

    <h2 class="mt-5 eyebrow-badge">Desempeño de Tutores</h2>
    <table class="table-modern w-100 mt-3">
        <thead>
            <tr>
                <th>Tutor</th>
                <th>Tutorías</th>
                <th>Realizadas</th>
                <th>Promedio</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tutores as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['tutor']) ?></td>
                    <td><?= htmlspecialchars($t['tutorias']) ?></td>
                    <td><?= htmlspecialchars($t['realizadas']) ?></td>
                    <td><?= htmlspecialchars($t['promedio']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2 class="mt-5 eyebrow-badge">Desempeño por Materia</h2>
    <table class="table-modern w-100 mt-3">
        <thead>
            <tr>
                <th>Materia</th>
                <th>Tutorías</th>
                <th>Promedio</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($materias as $m): ?>
                <tr>
                    <td><?= htmlspecialchars($m['nombre_materia']) ?></td>
                    <td><?= htmlspecialchars($m['tutorias']) ?></td>
                    <td><?= htmlspecialchars($m['promedio']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>
