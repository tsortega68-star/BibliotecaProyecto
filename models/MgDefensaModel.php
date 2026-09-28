<?php
/**
 * Modelo para Programación de Defensas y Tribunales
 */
class MgDefensaModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function obtenerDefensasAgenda() {
        $sql = "SELECT d.*, 
                       u.nombre AS estudiante_nombre, u.apellido AS estudiante_apellido, u.usuario AS estudiante_ru,
                       m.nombre AS modalidad_nombre
                FROM defensas_mg d
                INNER JOIN expedientes_mg e ON d.id_expediente = e.id_expediente
                INNER JOIN estudiantes est ON e.id_estudiante = est.id_estudiante
                INNER JOIN usuarios u ON est.id_usuario = u.id_usuario
                INNER JOIN modalidades_grado m ON e.id_modalidad = m.id_modalidad
                ORDER BY d.fecha DESC, d.hora_inicio ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function hayCruceHorario($fecha, $horaInicio, $horaFin, $ambiente, $idDefensaIgnore = null) {
        $sql = "SELECT COUNT(*) FROM defensas_mg 
                WHERE fecha = ? AND ambiente = ? AND estado = 'programada'
                AND ((hora_inicio < ? AND hora_fin > ?))";
        $params = [$fecha, $ambiente, $horaFin, $horaInicio];

        if ($idDefensaIgnore) {
            $sql .= " AND id_defensa != ?";
            $params[] = $idDefensaIgnore;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return ((int)$stmt->fetchColumn()) > 0;
    }

    public function programarDefensa($idExpediente, $etapa, $fecha, $horaInicio, $horaFin, $ambiente, $registradoPor = null) {
        if ($this->hayCruceHorario($fecha, $horaInicio, $horaFin, $ambiente)) {
            throw new Exception("Existe un choque de horario en el ambiente '$ambiente' para esa fecha y hora.");
        }

        $sql = "INSERT INTO defensas_mg (id_expediente, etapa, fecha, hora_inicio, hora_fin, ambiente, estado, registrado_por)
                VALUES (?, ?, ?, ?, ?, ?, 'programada', ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idExpediente, $etapa, $fecha, $horaInicio, $horaFin, $ambiente, $registradoPor]);
        return $this->db->lastInsertId();
    }
}