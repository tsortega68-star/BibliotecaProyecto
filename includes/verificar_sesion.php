<?php
require_once __DIR__ . '/funciones.php';
iniciarSesion();

if (!isset($_SESSION['usuario_id']) && !isset($_SESSION['id_usuario']) && !isset($_SESSION['usuario'])) {
    header('Location: /views/login/login.php');
    exit();
}
