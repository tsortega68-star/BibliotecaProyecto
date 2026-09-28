-- ============================================================================
-- MIGRACIÓN 012: TRIBUNALES, DEFENSAS Y CALIFICACIONES MG
-- ============================================================================

-- 1. Tabla de Tribunales Asignados
CREATE TABLE IF NOT EXISTS tribunales_defensa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_expediente INT NOT NULL,
    etapa ENUM('mg1', 'mg2') NOT NULL,
    id_tutor INT NOT NULL,
    orden TINYINT DEFAULT 1,
    fecha_asignacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('vigente', 'reemplazado') DEFAULT 'vigente',
    registrado_por INT NULL,
    FOREIGN KEY (id_expediente) REFERENCES expedientes_mg(id_expediente) ON DELETE CASCADE,
    FOREIGN KEY (id_tutor) REFERENCES tutores(id_tutor)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabla de Defensas Programadas
CREATE TABLE IF NOT EXISTS defensas_mg (
    id_defensa INT AUTO_INCREMENT PRIMARY KEY,
    id_expediente INT NOT NULL,
    etapa ENUM('mg1', 'mg2') NOT NULL,
    fecha DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    ambiente VARCHAR(100) NOT NULL,
    estado ENUM('programada', 'realizada', 'reprogramada', 'cancelada') DEFAULT 'programada',
    obs_fondo TEXT NULL,
    obs_forma TEXT NULL,
    registrado_por INT NULL,
    CHECK (hora_fin > hora_inicio),
    FOREIGN KEY (id_expediente) REFERENCES expedientes_mg(id_expediente) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabla de Calificaciones de Defensa
CREATE TABLE IF NOT EXISTS calificaciones_mg (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_defensa INT UNIQUE NOT NULL,
    nota DECIMAL(5,2) NOT NULL,
    observaciones TEXT NULL,
    publicada TINYINT(1) DEFAULT 0,
    registrada_por INT NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_defensa) REFERENCES defensas_mg(id_defensa) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Plantilla Inicial de Citación a Defensa
INSERT INTO plantillas_documento (codigo, nombre, cuerpo_html) VALUES
('CITACION_TRIBUNAL', 'Citación a Defensa para Tribunal', 
'<div style="font-family: Arial, sans-serif; padding: 30px; line-height: 1.6;">
  <div style="text-align: center; border-bottom: 2px solid #0f172a; padding-bottom: 10px;">
    <h2>UNIVERSIDAD PRIVADA DOMINGO SAVIO</h2>
    <h3>CITACIÓN OFICIAL A DEFENSA - TRIBUNAL EVALUADOR</h3>
    <p><strong>Citación N° {{numero_carta}}</strong></p>
  </div>
  <br>
  <p><strong>A:</strong> {{tutor_nombre}} (Tribunal Evaluador)</p>
  <p><strong>De:</strong> Coordinación de Modalidades de Grado</p>
  <hr>
  <p>Se le cita oficialmente como <strong>TRIBUNAL EVALUADOR</strong> para la defensa de la modalidad <strong>{{modalidad}}</strong> del estudiante:</p>
  <ul>
    <li><strong>Estudiante:</strong> {{estudiante_nombre}} (RU: {{registro_universitario}})</li>
    <li><strong>Tema:</strong> {{tema}}</li>
    <li><strong>Fecha de Defensa:</strong> {{fecha_defensa}}</li>
    <li><strong>Horario:</strong> {{hora_inicio}} a {{hora_fin}}</li>
    <li><strong>Ambiente / Aula:</strong> {{ambiente}}</li>
  </ul>
  <br><br>
  <div style="margin-top: 50px; text-align: center;">
    <p>____________________________________<br>Coordinación de Modalidades de Grado<br>UPDS Tarija</p>
  </div>
</div>')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);