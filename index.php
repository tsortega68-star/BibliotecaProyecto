<?php
require_once __DIR__ . '/includes/funciones.php';
iniciarSesion();

// Si el usuario ya inició sesión, enviarlo directo al Dashboard del Administrador
if (!empty($_SESSION['id_usuario'])) {
    $rol = $_SESSION['rol'] ?? 'administrador';
    redirect(dashboardPorRol($rol));
    exit;
}

// Si no ha iniciado sesión, mostrar la pantalla de acceso
require_once __DIR__ . '/views/login/login.php';