<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../includes/funciones.php';
require_once __DIR__ . '/../includes/permisos.php';

iniciarSesion();
requerirPermiso('mg.calificaciones.editar');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validar();

    $idDefensa = (int)($_POST['id_defensa'] ?? 0);
    $nota = (float)($_POST['nota'] ?? 0);
    $observaciones = trim($_POST['observaciones'] ?? '');
    $publicada = isset($_POST['publicada']) ? 1 : 0;
    $registradaPor = $_SESSION['id_usuario'] ?? 1;

    try {
        $stmt = $pdo->prepare("INSERT INTO calificaciones_mg (id_defensa, nota, observaciones, publicada, registrada_por)
                               VALUES (?, ?, ?, ?, ?)
                               ON DUPLICATE KEY UPDATE nota = VALUES(nota), observaciones = VALUES(observaciones), publicada = VALUES(publicada)");
        $stmt->execute([$idDefensa, $nota, $observaciones, $publicada, $registradaPor]);

        setFlash('exito', 'Calificación de defensa registrada con éxito.');
        header('Location: /controllers/mg_calificaciones.php');
        exit();
    } catch (Exception $e) {
        setFlash('error', 'Error al guardar calificación: ' . $e->getMessage());
    }
}

// Consultar defensas para calificar
$sql = "SELECT d.id_defensa, d.etapa, d.fecha, d.ambiente, d.estado,
               u.nombre AS estudiante_nombre, u.apellido AS estudiante_apellido, u.usuario AS estudiante_ru,
               m.nombre AS modalidad_nombre,
               c.nota, c.publicada
        FROM defensas_mg d
        INNER JOIN expedientes_mg e ON d.id_expediente = e.id_expediente
        INNER JOIN estudiantes est ON e.id_estudiante = est.id_estudiante
        INNER JOIN usuarios u ON est.id_usuario = u.id_usuario
        INNER JOIN modalidades_grado m ON e.id_modalidad = m.id_modalidad
        LEFT JOIN calificaciones_mg c ON d.id_defensa = c.id_defensa
        ORDER BY d.fecha DESC";
$stmt = $pdo->query($sql);
$defensasCalificar = $stmt->fetchAll(PDO::FETCH_ASSOC);

$tituloPagina = "Calificaciones de Modalidades de Grado";
require_once __DIR__ . '/../views/mg/calificaciones.php';