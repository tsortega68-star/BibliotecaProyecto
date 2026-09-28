<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../includes/funciones.php';
require_once __DIR__ . '/../includes/permisos.php';
require_once __DIR__ . '/../models/MgExpedienteModel.php';

iniciarSesion();
requerirPermiso('mg.expedientes.ver');

$expModel = new MgExpedienteModel($pdo);

$filtros = [
    'cohorte' => $_GET['cohorte'] ?? '',
    'modalidad' => $_GET['modalidad'] ?? '',
    'etapa' => $_GET['etapa'] ?? ''
];

$expedientes = $expModel->listarTodos($filtros);
$tituloPagina = "Expedientes de Modalidades de Grado";

require_once __DIR__ . '/../views/mg/expedientes/listar.php';