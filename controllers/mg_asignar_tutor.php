<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../includes/funciones.php';
require_once __DIR__ . '/../includes/permisos.php';
require_once __DIR__ . '/../models/MgAsignacionModel.php';
require_once __DIR__ . '/../models/TutorModel.php';

iniciarSesion();
requerirPermiso('mg.tutor.asignar');

$asigModel = new MgAsignacionModel($pdo);
$tutorModel = new TutorModel($pdo);

$idExpediente = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validar();

    $idTutor = (int)($_POST['id_tutor'] ?? 0);
    $referencia = trim($_POST['referencia_decanatura'] ?? '');
    $disponibilidad = isset($_POST['disponibilidad_consultada']);
    $motivo = trim($_POST['motivo_cambio'] ?? '');
    $registradoPor = $_SESSION['id_usuario'] ?? null;

    if ($idTutor <= 0) {
        setFlash('error', 'Debe seleccionar un tutor válido.');
    } else {
        try {
            $numCarta = $asigModel->asignarTutor($idExpediente, $idTutor, $referencia, $disponibilidad, $motivo, $registradoPor);
            setFlash('exito', "Tutor asignado exitosamente. Carta emitida N° $numCarta");
            header("Location: /controllers/mg_expedientes.php");
            exit();
        } catch (Exception $e) {
            setFlash('error', 'Error al asignar tutor: ' . $e->getMessage());
        }
    }
}

// Cargar tutores con su conteo de carga
$stmtT = $pdo->query("SELECT t.id_tutor, u.nombre, u.apellido, t.especialidad FROM tutores t INNER JOIN usuarios u ON t.id_usuario = u.id_usuario WHERE u.estado = 'activo'");
$tutoresRaw = $stmtT->fetchAll(PDO::FETCH_ASSOC);

$tutoresList = [];
foreach ($tutoresRaw as $t) {
    $t['carga'] = $asigModel->obtenerCargaTutor($t['id_tutor']);
    $tutoresList[] = $t;
}

$asignacionActual = $asigModel->obtenerAsignacionVigente($idExpediente);
$tituloPagina = "Asignación de Tutor";

require_once __DIR__ . '/../views/mg/asignar_tutor.php';