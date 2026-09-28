<?php
/**
 * Modelo para la lectura y modificación de parámetros dinámicos de MG
 */
class MgParametroModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function obtenerTodos() {
        $stmt = $this->db->query("SELECT * FROM parametros_mg ORDER BY clave ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener($clave, $default = null) {
        $stmt = $this->db->prepare("SELECT valor FROM parametros_mg WHERE clave = ?");
        $stmt->execute([$clave]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['valor'] : $default;
    }

    public function actualizar($clave, $valor, $idUsuario = null) {
        $stmt = $this->db->prepare("UPDATE parametros_mg SET valor = ?, actualizado_por = ? WHERE clave = ?");
        return $stmt->execute([$valor, $idUsuario, $clave]);
    }
}