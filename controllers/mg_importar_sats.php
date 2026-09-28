<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../includes/funciones.php';
require_once __DIR__ . '/../includes/permisos.php';
require_once __DIR__ . '/../models/MgExpedienteModel.php';

iniciarSesion();
requerirPermiso('mg.expedientes.crear');

$resultadoImportacion = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_csv'])) {
    csrf_validar();

    $archivo = $_FILES['archivo_csv'];
    if ($archivo['error'] === UPLOAD_ERR_OK) {
        $handle = fopen($archivo['tmp_name'], 'r');
        $headers = fgetcsv($handle, 1000, ',');

        $exitos = 0;
        $errores = 0;
        $detalles = [];

        // Leer líneas
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            if (count($data) >= 5) {
                $ru = trim($data[0]);
                $nombre = trim($data[1]);
                $apellido = trim($data[2]);
                $correo = trim($data[3]);
                $codigoModalidad = strtoupper(trim($data[4]));
                $codigoCohorte = trim($data[5] ?? 'G1-2026-03');

                try {
                    // Buscar o crear usuario estudiante
                    $stmtU = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE usuario = ? OR correo = ?");
                    $stmtU->execute([$ru, $correo]);
                    $usr = $stmtU->fetch(PDO::FETCH_ASSOC);

                    if (!$usr) {
                        $hash = password_hash('123456', PASSWORD_DEFAULT);
                        $stmtInsU = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, usuario, correo, contraseña, contrasena_hash, rol_id, estado) VALUES (?, ?, ?, ?, ?, ?, 3, 'activo')");
                        $stmtInsU->execute([$nombre, $apellido, $ru, $correo, $hash, $hash]);
                        $idUsuario = $pdo->lastInsertId();

                        $stmtInsEst = $pdo->prepare("INSERT INTO estudiantes (id_usuario, id_carrera, semestre, registro_universitario) VALUES (?, 1, 9, ?)");
                        $stmtInsEst->execute([$idUsuario, $ru]);
                        $idEstudiante = $pdo->lastInsertId();
                    } else {
                        $stmtEst = $pdo->prepare("SELECT id_estudiante FROM estudiantes WHERE id_usuario = ?");
                        $stmtEst->execute([$usr['id_usuario']]);
                        $idEstudiante = $stmtEst->fetchColumn();
                    }

                    // Buscar modalidad y cohorte
                    $stmtM = $pdo->prepare("SELECT id_modalidad FROM modalidades_grado WHERE codigo = ?");
                    $stmtM->execute([$codigoModalidad]);
                    $idMod = $stmtM->fetchColumn() ?: 1;

                    $stmtC = $pdo->prepare("SELECT id_cohorte FROM cohortes_mg WHERE codigo = ?");
                    $stmtC->execute([$codigoCohorte]);
                    $idCoh = $stmtC->fetchColumn() ?: 1;

                    // Crear expediente
                    $expModel = new MgExpedienteModel($pdo);
                    $expModel->crearExpediente($idEstudiante, $idMod, $idCoh, "Trabajo de Grado - RU $ru");

                    $exitos++;
                    $detalles[] = ['ru' => $ru, 'estado' => 'OK', 'msj' => 'Expediente importado con éxito'];
                } catch (Exception $e) {
                    $errores++;
                    $detalles[] = ['ru' => $ru, 'estado' => 'ERROR', 'msj' => $e->getMessage()];
                }
            }
        }
        fclose($handle);

        $resultadoImportacion = [
            'exitos' => $exitos,
            'errores' => $errores,
            'detalles' => $detalles
        ];

        setFlash('exito', "Importación completada: $exitos procesados correctamente, $errores errores.");
    } else {
        setFlash('error', 'Error al subir el archivo CSV.');
    }
}

$tituloPagina = "Importar Padrón desde SATS";
require_once __DIR__ . '/../views/mg/importar_sats.php';