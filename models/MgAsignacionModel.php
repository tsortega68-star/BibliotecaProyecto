<?php
/**
 * Modelo para Asignaciones de Tutores e Historial inmutable
 */
class MgAsignacionModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function obtenerCargaTutor($idTutor) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM asignaciones_tutor WHERE id_tutor = ? AND estado = 'vigente'");
        $stmt->execute([$idTutor]);
        return (int)$stmt->fetchColumn();
    }

    public function obtenerAsignacionVigente($idExpediente) {
        $sql = "SELECT a.*, t.id_tutor, u.nombre AS tutor_nombre, u.apellido AS tutor_apellido, u.correo AS tutor_correo
                FROM asignaciones_tutor a
                INNER JOIN tutores t ON a.id_tutor = t.id_tutor
                INNER JOIN usuarios u ON t.id_usuario = u.id_usuario
                WHERE a.id_expediente = ? AND a.estado = 'vigente' LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idExpediente]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function asignarTutor($idExpediente, $idTutor, $referenciaDecanatura, $disponibilidadConsultada, $motivoFinAnterior = null, $registradoPor = null) {
        $this->db->beginTransaction();
        try {
            // Cerrar asignación anterior si existe
            $stmtCerrar = $this->db->prepare("UPDATE asignaciones_tutor SET estado = 'reemplazada', fecha_fin = NOW(), motivo_fin = ? WHERE id_expediente = ? AND estado = 'vigente'");
            $stmtCerrar->execute([$motivoFinAnterior ?: 'Cambio de tutor asignado', $idExpediente]);

            // Generar número de carta correlativo
            $anio = (int)date('Y');
            $stmtNum = $this->db->prepare("INSERT INTO contadores_documento (tipo, anio, ultimo_numero) VALUES ('CARTA_TUTOR', ?, 1) ON DUPLICATE KEY UPDATE ultimo_numero = ultimo_numero + 1");
            $stmtNum->execute([$anio]);

            $stmtGetNum = $this->db->prepare("SELECT ultimo_numero FROM contadores_documento WHERE tipo = 'CARTA_TUTOR' AND anio = ?");
            $stmtGetNum->execute([$anio]);
            $num = $stmtGetNum->fetchColumn();
            $numeroCarta = sprintf("UPDS-MG-%d-%04d", $anio, $num);

            // Crear nueva asignación
            $sqlIns = "INSERT INTO asignaciones_tutor (id_expediente, id_tutor, fecha_asignacion, estado, referencia_decanatura, disponibilidad_consultada, numero_carta, registrado_por)
                       VALUES (?, ?, NOW(), 'vigente', ?, ?, ?, ?)";
            $stmtIns = $this->db->prepare($sqlIns);
            $stmtIns->execute([$idExpediente, $idTutor, $referenciaDecanatura, $disponibilidadConsultada ? 1 : 0, $numeroCarta, $registradoPor]);

            $this->db->commit();
            return $numeroCarta;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}