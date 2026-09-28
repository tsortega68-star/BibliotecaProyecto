<?php
$inc_file = __DIR__ . '/../includes/funciones.php';
if (file_exists($inc_file)) {
    require_once $inc_file;
} else {
    if (session_status() === PHP_SESSION_NONE) session_start();
    function limpiarEntrada($d) { return htmlspecialchars(trim($d)); }
    function estaAutenticado() { return isset($_SESSION['usuario_id']); }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Biblioteca - MVC</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f2f5; margin: 0; padding: 0; }
        .navbar { background-color: #1F3B6E; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; color: white; }
        .navbar a { color: white; text-decoration: none; margin-right: 15px; font-weight: 500; }
        .navbar a:hover { text-decoration: underline; }
        .container { max-width: 1000px; margin: 30px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        h1, h2, h3 { color: #1F3B6E; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e1e4e8; }
        th { background-color: #1F3B6E; color: white; }
        tr:hover { background-color: #f8f9fa; }
        .btn { padding: 8px 14px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 14px; font-weight: bold; }
        .btn-primary { background-color: #2B5DA6; color: white; }
        .btn-danger { background-color: #d9534f; color: white; }
        form { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; background: #f8f9fa; padding: 15px; border-radius: 6px; }
        input, select { padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="navbar">
        <div>
            <strong>📚 Sistema de Biblioteca (MVC)</strong> | 
            <a href="/BibliotecaProyecto/index.php?mod=dashboard">📊 Dashboard</a>
            <a href="/BibliotecaProyecto/index.php?mod=libros">📚 Libros</a>
        </div>
        <div>
            <?php if (function_exists('estaAutenticado') && estaAutenticado()): ?>
                Hola, <?= htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario') ?> | 
                <a href="/BibliotecaProyecto/controllers/logout.php" style="color: #ff6b6b;">Cerrar Sesión</a>
            <?php else: ?>
                <a href="/BibliotecaProyecto/index.php?mod=login">Iniciar Sesión</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="container">
