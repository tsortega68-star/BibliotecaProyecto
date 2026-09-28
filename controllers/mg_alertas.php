<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../includes/funciones.php';
require_once __DIR__ . '/../includes/permisos.php';

iniciarSesion();
requerirPermiso('mg.reportes.ver');

// Generar Alertas Inteligentes en Tiempo Real
$alertas = [];

// A1: Expedientes activas en MG1 sin Tutor asignado
$sqlA1 = "SELECT e.id_expediente, u.nombre, u.apellido, u.usuario AS ru
          FROM expedientes_mg e
          INNER JOIN estudiantes est ON e.id_estudiante = est.id_estudiante
          INNER JOIN usuarios u ON est.id_usuario = u.id_usuario
          WHERE e.id_expediente NOT IN (SELECT id_expediente FROM asignaciones_tutor WHERE estado = 'vigente')
          AND e.estado = 'activo'";
$stmtA1 = $pdo->query($sqlA1);
while ($row = $stmtA1->fetch(PDO::FETCH_ASSOC)) {
    $alertas[] = [
        'tipo' => 'Sin Tutor Asignado',
        'severidad' => 'danger',
        'mensaje' => "El estudiante {$row['nombre']} {$row['apellido']} (RU: {$row['ru']}) no tiene un tutor asignado.",
        'enlace' => "/controllers/mg_asignar_tutor.php?id={$row['id_expediente']}"
    ];
}

// A8: Tutores con sobrecarga de estudiantes (>3)
$sqlA8 = "SELECT t.id_tutor, u.nombre, u.apellido, COUNT(a.id_asignacion) AS carga
          FROM tutores t
          INNER JOIN usuarios u ON t.id_usuario = u.id_usuario
          INNER JOIN asignaciones_tutor a ON t.id_tutor = a.id_tutor AND a.estado = 'vigente'
          GROUP BY t.id_tutor
          HAVING carga > 3";
$stmtA8 = $pdo->query($sqlA8);
while ($row = $stmtA8->fetch(PDO::FETCH_ASSOC)) {
    $alertas[] = [
        'tipo' => 'Sobrecarga de Tutor',
        'severidad' => 'warning',
        'mensaje' => "El docente {$row['nombre']} {$row['apellido']} supera la carga recomendada con {$row['carga']} estudiantes vigentes.",
        'enlace' => "/controllers/mg_expedientes.php"
    ];
}

$tituloPagina = "Panel de Alertas Académicas MG";
require_once __DIR__ . '/../views/mg/alertas.php';