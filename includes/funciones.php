<?php
/**
 * Funciones auxiliares y de seguridad globales del sistema
 */

if (!function_exists('iniciarSesion')) {
    function iniciarSesion() {
        if (session_status() === PHP_SESSION_NONE) {
            if (!headers_sent()) {
                session_set_cookie_params([
                    'lifetime' => 0,
                    'path' => '/',
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]);
            }
            @session_start();
        }
    }
}

if (!function_exists('redirect')) {
    function redirect($url) {
        header('Location: ' . $url);
        exit();
    }
}

if (!function_exists('redireccionar')) {
    function redireccionar($url) {
        redirect($url);
    }
}

if (!function_exists('e')) {
    function e($data) {
        return htmlspecialchars($data ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token() {
        iniciarSesion();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrfToken')) {
    function csrfToken() {
        return csrf_token();
    }
}

if (!function_exists('csrf_campo')) {
    function csrf_campo() {
        return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('csrfField')) {
    function csrfField() {
        return csrf_campo();
    }
}

if (!function_exists('csrf_validar')) {
    function csrf_validar() {
        iniciarSesion();
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (empty($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            return true;
        }
    }
}

if (!function_exists('validarCsrf')) {
    function validarCsrf() {
        return csrf_validar();
    }
}

if (!function_exists('setFlash')) {
    function setFlash($tipo, $mensaje) {
        iniciarSesion();
        $_SESSION['flash'][$tipo][] = $mensaje;
    }
}

if (!function_exists('flash')) {
    function flash($tipo = null, $mensaje = null) {
        if ($tipo === null) {
            mostrarFlash();
        } elseif ($mensaje === null) {
            mostrarFlash();
        } else {
            setFlash($tipo, $mensaje);
        }
    }
}

if (!function_exists('mostrarFlash')) {
    function mostrarFlash() {
        iniciarSesion();
        if (!empty($_SESSION['flash'])) {
            foreach ($_SESSION['flash'] as $tipo => $mensajes) {
                $clase = ($tipo === 'exito' || $tipo === 'success') ? 'success' : 'danger';
                foreach ($mensajes as $msj) {
                    echo '<div class="alert alert-' . $clase . ' alert-dismissible fade show" role="alert">';
                    echo e($msj);
                    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '</div>';
                }
            }
            unset($_SESSION['flash']);
        }
    }
}

if (!function_exists('requireRole')) {
    function requireRole($rolesPermitidos) {
        iniciarSesion();
        $rolActual = $_SESSION['rol'] ?? $_SESSION['nombre_rol'] ?? '';
        $rolId = $_SESSION['rol_id'] ?? $_SESSION['id_rol'] ?? 0;

        if ($rolActual === 'administrador' || $rolActual === 'admin' || $rolId == 1 || ($_SESSION['usuario'] ?? '') === 'admin') {
            return true;
        }

        $rolesPermitidos = (array)$rolesPermitidos;
        if (!in_array($rolActual, $rolesPermitidos)) {
            http_response_code(403);
            require_once __DIR__ . '/../views/errors/403.php';
            exit();
        }
    }
}

if (!function_exists('dashboardPorRol')) {
    function dashboardPorRol($rol) {
        switch ($rol) {
            case 'administrador':
            case 'admin':
            case 'coordinador_mg':
            case 'auxiliar_mg':
                return '/controllers/dashboard.php';
            case 'tutor':
                return '/controllers/tutorias_listar.php';
            case 'estudiante':
                return '/controllers/tutorias_listar.php';
            default:
                return '/controllers/dashboard.php';
        }
    }
}