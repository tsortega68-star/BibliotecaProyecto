<?php
/**
 * Modelo de Gestión de Expedientes de Modalidades de Grado
 */
class MgExpedienteModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function listarTodos($filtros = []) {
        $sql = "SELECT e.*, 
                       u.nombre AS estudiante_nombre, u.apellido AS estudiante_apellido, u.usuario AS estudiante_ru, u.correo,
                       m.nombre AS modalidad_nombre, m.codigo AS modalidad_codigo,
                       c.nombre AS cohorte_nombre, c.codigo AS cohorte_codigo
                FROM expedientes_mg e
                INNER JOIN estudiantes est ON e.id_estudiante = est.id_estudiante
                INNER JOIN usuarios u ON est.id_usuario = u.id_usuario
                INNER JOIN modalidades_grado m ON e.id_modalidad = m.id_modalidad
                INNER JOIN cohortes_mg c ON e.id_cohorte = c.id_cohorte
                WHERE 1=1";
        $params = [];

        if (!empty($filtros['cohorte'])) {
            $sql .= " AND e.id_cohorte = ?";
            $params[] = $filtros['cohorte'];
        }

        if (!empty($filtros['modalidad'])) {
            $sql .= " AND e.id_modalidad = ?";
            $params[] = $filtros['modalidad'];
        }

        if (!empty($filtros['etapa'])) {
            $sql .= " AND e.etapa_actual = ?";
            $params[] = $filtros['etapa'];
        }

        $sql .= " ORDER BY e.id_expediente DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearExpediente($idEstudiante, $idModalidad, $idCohorte, $titulo = null) {
        $sql = "INSERT INTO expedientes_mg (id_estudiante, id_modalidad, id_cohorte, etapa_actual, estado, titulo_trabajo, fecha_inicio)
                VALUES (?, ?, ?, 'previa', 'activo', ?, CURDATE())
                ON DUPLICATE KEY UPDATE titulo_trabajo = VALUES(titulo_trabajo)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idEstudiante, $idModalidad, $idCohorte, $titulo]);
    }
}