-- ============================================================================
-- MIGRACIÓN 011: ASIGNACIONES DE TUTOR Y GENERACIÓN DOCUMENTAL
-- ============================================================================

-- 1. Tabla de Historial de Asignaciones de Tutor (Inmutable)
CREATE TABLE IF NOT EXISTS asignaciones_tutor (
    id_asignacion INT AUTO_INCREMENT PRIMARY KEY,
    id_expediente INT NOT NULL,
    id_tutor INT NOT NULL,
    fecha_asignacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_fin DATETIME NULL,
    estado ENUM('vigente', 'finalizada', 'reemplazada') DEFAULT 'vigente',
    motivo_fin TEXT NULL,
    referencia_decanatura VARCHAR(100) NULL,
    disponibilidad_consultada TINYINT(1) DEFAULT 0,
    numero_carta VARCHAR(50) NULL,
    observaciones TEXT NULL,
    registrado_por INT NULL,
    FOREIGN KEY (id_expediente) REFERENCES expedientes_mg(id_expediente) ON DELETE CASCADE,
    FOREIGN KEY (id_tutor) REFERENCES tutores(id_tutor)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabla de Plantillas de Documento
CREATE TABLE IF NOT EXISTS plantillas_documento (
    id_plantilla INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    cuerpo_html LONGTEXT NOT NULL,
    version INT DEFAULT 1,
    activa TINYINT(1) DEFAULT 1,
    actualizado_por INT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Plantilla Inicial de Carta de Asignación de Tutor
INSERT INTO plantillas_documento (codigo, nombre, cuerpo_html) VALUES
('CARTA_ASIGNACION_TUTOR', 'Carta de Asignación de Tutor', 
'<div style="font-family: Arial, sans-serif; padding: 30px; line-height: 1.6;">
  <div style="text-align: center; border-bottom: 2px solid #0f172a; padding-bottom: 10px;">
    <h2>UNIVERSIDAD PRIVADA DOMINGO SAVIO</h2>
    <h3>FACULTAD DE INGENIERÍA - SEDE TARIJA</h3>
    <p><strong>CARTA DE ASIGNACIÓN DE TUTOR N° {{numero_carta}}</strong></p>
  </div>
  <br>
  <p><strong>Fecha:</strong> {{fecha_larga}}</p>
  <p><strong>A:</strong> {{tutor_nombre}} (Tutor Académico)</p>
  <p><strong>De:</strong> Coordinación de Modalidades de Grado</p>
  <hr>
  <p>Mediante la presente, se le notifica la asignación como <strong>TUTOR ACADÉMICO</strong> para el desarrollo de Modalidad de Grado del estudiante:</p>
  <ul>
    <li><strong>Estudiante:</strong> {{estudiante_nombre}}</li>
    <li><strong>Registro Universitario (RU):</strong> {{registro_universitario}}</li>
    <li><strong>Modalidad:</strong> {{modalidad}}</li>
    <li><strong>Tema/Título:</strong> {{tema}}</li>
    <li><strong>Cohorte:</strong> {{cohorte}}</li>
  </ul>
  <p>Referencia Decanatura / Resolución: {{referencia_decanatura}}</p>
  <br><br>
  <div style="margin-top: 50px; text-align: center;">
    <p>____________________________________<br>Coordinación de Modalidades de Grado<br>UPDS Tarija</p>
  </div>
</div>')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

-- 3. Tabla de Documentos Generados (Snapshots inmutables)
CREATE TABLE IF NOT EXISTS documentos_generados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_plantilla INT NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    id_expediente INT NOT NULL,
    destinatario VARCHAR(150) NOT NULL,
    numero_correlativo VARCHAR(50) NOT NULL,
    contenido_snapshot LONGTEXT NOT NULL,
    generado_por INT NOT NULL,
    fecha_generacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_plantilla) REFERENCES plantillas_documento(id_plantilla),
    FOREIGN KEY (id_expediente) REFERENCES expedientes_mg(id_expediente)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Contadores Correlativos de Documento
CREATE TABLE IF NOT EXISTS contadores_documento (
    tipo VARCHAR(50) NOT NULL,
    anio INT NOT NULL,
    ultimo_numero INT DEFAULT 0,
    PRIMARY KEY (tipo, anio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;