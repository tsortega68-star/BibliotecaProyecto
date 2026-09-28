-- ============================================================================
-- MIGRACIÓN 013: REUNIONES, INFORMES Y ALERTAS DE SEGUIMIENTO
-- ============================================================================

-- 1. Tabla de Registro de Reuniones
CREATE TABLE IF NOT EXISTS reuniones_mg (
    id_reunion INT AUTO_INCREMENT PRIMARY KEY,
    id_asignacion INT NOT NULL,
    fecha DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    modalidad ENUM('presencial', 'virtual') DEFAULT 'presencial',
    lugar_o_enlace VARCHAR(255) NULL,
    temas TEXT NOT NULL,
    avance_sesion TEXT NULL,
    observaciones TEXT NULL,
    asistio_estudiante ENUM('si', 'no') DEFAULT 'si',
    asistio_tutor ENUM('si', 'no') DEFAULT 'si',
    estado_validacion ENUM('registrada', 'validada', 'observada') DEFAULT 'registrada',
    registrada_por INT NOT NULL,
    validada_por INT NULL,
    fecha_validacion DATETIME NULL,
    FOREIGN KEY (id_asignacion) REFERENCES asignaciones_tutor(id_asignacion) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabla de Informes de Avance Presentados
CREATE TABLE IF NOT EXISTS informes_avance (
    id_informe INT AUTO_INCREMENT PRIMARY KEY,
    id_expediente INT NOT NULL,
    id_hito INT NOT NULL,
    porcentaje_avance TINYINT NOT NULL,
    fecha_presentacion DATE NOT NULL,
    formato ENUM('digital', 'fisico') DEFAULT 'digital',
    respaldo_fisico TINYINT(1) DEFAULT 0,
    presentado_por INT NOT NULL,
    observaciones TEXT NULL,
    registrado_por INT NOT NULL,
    UNIQUE KEY uq_expediente_hito (id_expediente, id_hito),
    FOREIGN KEY (id_expediente) REFERENCES expedientes_mg(id_expediente) ON DELETE CASCADE,
    FOREIGN KEY (id_hito) REFERENCES calendario_mg(id_hito)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabla de Alertas Atendidas
CREATE TABLE IF NOT EXISTS alertas_atendidas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_alerta VARCHAR(50) NOT NULL,
    id_referencia INT NOT NULL,
    atendida_por INT NOT NULL,
    nota TEXT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;