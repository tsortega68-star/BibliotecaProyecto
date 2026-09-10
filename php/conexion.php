<?php
$conexion = new mysql("localhost", "biblioteca_user","12345","testdb");
if ($conexion->connect_error) {
    die("Error de conexion: " . $conexio->connect_error);
}
echo "Conexion exitosa a la base de datos";
?>
