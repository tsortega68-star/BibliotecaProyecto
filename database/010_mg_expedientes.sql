-- ============================================================================
-- MIGRACIÓN 010: EXPEDIENTES E IMPORTACIONES DE SATS
-- ============================================================================

-- 1. Tabla de Expedientes de Modalidades de Grado
CREATE TABLE IF NOT EXISTS expedientes_mg (
    id_expediente INT AUTO_INCREMENT PRIMARY KEY,
    id_estudiante INT NOT NULL,
    id_modalidad INT NOT NULL,
    id_cohorte INT NOT NULL,
    etapa_actual ENUM('previa', 'mg1', 'mg2', 'finalizado') DEFAULT 'previa',
    estado ENUM('activo', 'aprobado', 'reprobado', 'abandono', 'retirado') DEFAULT 'activo',
    titulo_trabajo VARCHAR(255) NULL,
    fecha_inicio DATE NOT NULL,
    fecha_cierre DATE NULL,
    observaciones TEXT NULL,
    UNIQUE KEY uq_estudiante_modalidad_cohorte (id_estudiante, id_modalidad, id_cohorte),
    FOREIGN KEY (id_modalidad) REFERENCES modalidades_grado(id_modalidad),
    FOREIGN KEY (id_cohorte) REFERENCES cohortes_mg(id_cohorte)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Historial de Etapas del Expediente
CREATE TABLE IF NOT EXISTS expediente_etapas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_expediente INT NOT NULL,
    etapa ENUM('previa', 'mg1', 'mg2', 'finalizado') NOT NULL,
    fecha_inicio DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_fin DATETIME NULL,
    resultado VARCHAR(100) NULL,
    registrado_por INT NULL,
    FOREIGN KEY (id_expediente) REFERENCES expedientes_mg(id_expediente) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Registro de Importaciones SATS
CREATE TABLE IF NOT EXISTS importaciones_mg (
    id_importacion INT AUTO_INCREMENT PRIMARY KEY,
    nombre_archivo VARCHAR(150) NOT NULL,
    total_filas INT DEFAULT 0,
    exitosos INT DEFAULT 0,
    errores INT DEFAULT 0,
    importado_por INT NOT NULL,
    fecha_importacion DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;