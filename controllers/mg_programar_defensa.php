<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../includes/funciones.php';
require_once __DIR__ . '/../includes/permisos.php';
require_once __DIR__ . '/../models/MgDefensaModel.php';
require_once __DIR__ . '/../models/MgExpedienteModel.php';

iniciarSesion();
requerirPermiso('mg.defensas.programar');

$defensaModel = new MgDefensaModel($pdo);
$expModel = new MgExpedienteModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validar();

    $idExpediente = (int)($_POST['id_expediente'] ?? 0);
    $etapa = trim($_POST['etapa'] ?? 'mg1');
    $fecha = trim($_POST['fecha'] ?? '');
    $horaInicio = trim($_POST['hora_inicio'] ?? '');
    $horaFin = trim($_POST['hora_fin'] ?? '');
    $ambiente = trim($_POST['ambiente'] ?? '');
    $registradoPor = $_SESSION['id_usuario'] ?? null;

    try {
        $idDefensa = $defensaModel->programarDefensa($idExpediente, $etapa, $fecha, $horaInicio, $horaFin, $ambiente, $registradoPor);
        setFlash('exito', "Defensa de $etapa programada exitosamente para la fecha $fecha en $ambiente.");
        header('Location: /controllers/mg_programar_defensa.php');
        exit();
    } catch (Exception $e) {
        setFlash('error', $e->getMessage());
    }
}

$defensas = $defensaModel->obtenerDefensasAgenda();
$expedientes = $expModel->listarTodos();
$tituloPagina = "Agenda de Defensas de Modalidades de Grado";

require_once __DIR__ . '/../views/mg/defensas/agenda.php';