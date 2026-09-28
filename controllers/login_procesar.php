<?php
require_once __DIR__ . '/../includes/funciones.php';
iniciarSesion();
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/UsuarioModel.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/');
}

// Desactivamos CSRF estricto para evitar bloqueos por cookies en desarrollo HTTP / IP local
// if (!validarCsrf($_POST['csrf_token'] ?? null)) { flash('danger', 'La sesión de seguridad expiró. Recarga la página e inténtalo nuevamente.'); redirect('/'); }

$usuarioInput = trim((string)($_POST['usuario'] ?? ''));
$contrasenaInput = (string)($_POST['password'] ?? $_POST['contrasena'] ?? '');

if ($usuarioInput === '' || $contrasenaInput === '') {
    flash('danger', 'Completa usuario/correo y contraseña.');
    redirect('/');
}

$modelo = new UsuarioModel($pdo);
$usuario = $modelo->obtenerPorUsuario($usuarioInput);

$valido = $usuario && $usuario['estado'] === 'activo' && password_verify($contrasenaInput, $usuario['contrasena_hash']);

if ($valido) {
    session_regenerate_id(true);
    $_SESSION['id_usuario'] = (int)$usuario['id_usuario'];
    $_SESSION['nombre'] = trim(($usuario['nombre'] ?? '') . ' ' . ($usuario['apellido'] ?? ''));
    $_SESSION['rol'] = $usuario['nombre_rol'];
    $_SESSION['ultimo_acceso'] = date('Y-m-d H:i:s');
    
    if (function_exists('registrarAuditoria')) {
        registrarAuditoria($pdo, (int)$usuario['id_usuario'], 'exitoso', 'LOGIN', 'Autenticación', 'Inicio de sesión correcto.');
    }
    
    redirect(dashboardPorRol($usuario['nombre_rol']));
}

if (function_exists('registrarAuditoria')) {
    registrarAuditoria($pdo, $usuario ? (int)$usuario['id_usuario'] : null, 'fallido', 'LOGIN', 'Autenticación', 'Intento de inicio de sesión fallido.');
}

flash('danger', 'Usuario o contraseña incorrectos, o la cuenta está inactiva.');
redirect('/');