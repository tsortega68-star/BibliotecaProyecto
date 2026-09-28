<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../includes/funciones.php';
require_once __DIR__ . '/../includes/permisos.php';
require_once __DIR__ . '/../models/MgParametroModel.php';

iniciarSesion();
requerirPermiso('mg.parametros.ver');

$paramModel = new MgParametroModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requerirPermiso('mg.parametros.editar');
    csrf_validar();

    $parametros = $_POST['parametros'] ?? [];
    $idUsuario = $_SESSION['id_usuario'] ?? null;

    foreach ($parametros as $clave => $valor) {
        $paramModel->actualizar($clave, trim($valor), $idUsuario);
    }

    setFlash('exito', 'Parámetros institucionales actualizados correctamente.');
    header('Location: /controllers/mg_parametros.php');
    exit();
}

$listaParametros = $paramModel->obtenerTodos();
$tituloPagina = "Parámetros de Modalidades de Grado";

require_once __DIR__ . '/../views/mg/parametros.php';