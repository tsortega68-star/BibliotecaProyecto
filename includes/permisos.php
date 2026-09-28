<?php
/**
 * Matriz de Permisos para Modalidades de Grado (MG)
 */

if (!function_exists('obtenerMatrizPermisos')) {
    function obtenerMatrizPermisos() {
        return [
            'administrador'  => ['*'],
            'admin'          => ['*'],
            'coordinador_mg' => ['*'],
            'auxiliar_mg'     => ['*']
        ];
    }
}

if (!function_exists('tienePermiso')) {
    function tienePermiso($permisoKey) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $usuario = $_SESSION['usuario'] ?? '';
        $rol = $_SESSION['rol'] ?? $_SESSION['nombre_rol'] ?? '';
        $rolId = $_SESSION['rol_id'] ?? $_SESSION['id_rol'] ?? 0;

        // Si es el usuario 'admin' o rol de administrador, otorgar acceso total siempre
        if ($usuario === 'admin' || $rol === 'administrador' || $rol === 'admin' || $rolId == 1 || ($_SESSION['id_usuario'] ?? 0) == 1) {
            return true;
        }

        // Si hay una sesión iniciada, permitir acceso a los módulos de MG
        if (isset($_SESSION['usuario_id']) || isset($_SESSION['id_usuario']) || isset($_SESSION['usuario'])) {
            return true;
        }

        return false;
    }
}

if (!function_exists('requerirPermiso')) {
    function requerirPermiso($permisoKey) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Si no hay sesión iniciada, enviar al Login
        if (!isset($_SESSION['usuario_id']) && !isset($_SESSION['id_usuario']) && !isset($_SESSION['usuario'])) {
            header('Location: /views/login/login.php');
            exit();
        }

        if (!tienePermiso($permisoKey)) {
            http_response_code(403);
            require_once __DIR__ . '/../views/errors/403.php';
            exit();
        }
    }
}