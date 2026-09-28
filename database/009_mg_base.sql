-- ============================================================================
-- MIGRACIÓN 009: BASE PARA MODALIDADES DE GRADO (MG)
-- ============================================================================

-- 1. Agregar nuevos roles si no existen (soporta nombre_rol o nombre)
SET @col_exists = (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = 'tutorias_db' AND table_name = 'roles' AND column_name = 'nombre_rol');

SET @sql1 = IF(@col_exists > 0, 
    'INSERT IGNORE INTO roles (nombre_rol) VALUES (\'coordinador_mg\'), (\'auxiliar_mg\')', 
    'INSERT IGNORE INTO roles (nombre) VALUES (\'coordinador_mg\'), (\'auxiliar_mg\')');
PREPARE stmt1 FROM @sql1;
EXECUTE stmt1;
DEALLOCATE PREPARE stmt1;

-- 2. Tabla de Parámetros Configurables de MG
CREATE TABLE IF NOT EXISTS parametros_mg (
    clave VARCHAR(60) PRIMARY KEY,
    valor VARCHAR(100) NULL,
    descripcion TEXT,
    fuente VARCHAR(100) DEFAULT 'ENT-03',
    estado_evidencia ENUM('confirmado', 'pendiente', 'propuesta') DEFAULT 'confirmado',
    actualizado_por INT NULL,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Semillas iniciales de parámetros configurables
INSERT INTO parametros_mg (clave, valor, descripcion, estado_evidencia) VALUES
('reuniones_min_semana_perfil', '2', 'Mínimo de reuniones por semana exigidas en etapa de perfil (MG1)', 'confirmado'),
('dias_alerta_sin_reunion', '10', 'Días sin reunión registrada para disparar alerta', 'propuesta'),
('tutor_carga_recomendada', '3', 'Número de estudiantes recomendado por tutor (advertencia, no bloqueo)', 'confirmado'),
('dias_anticipacion_tribunal', '14', 'Días de anticipación para asignar tribunales antes de la defensa', 'confirmado'),
('tribunales_por_defensa_mg1', '2', 'Número de tribunales requeridos en defensa de MG1', 'confirmado'),
('duracion_mg1_meses', '2', 'Duración aproximada de la etapa MG1 en meses', 'confirmado'),
('duracion_mg2_meses', '4', 'Duración aproximada de la etapa MG2 en meses', 'confirmado')
ON DUPLICATE KEY UPDATE valor = VALUES(valor);

-- 3. Tabla de Modalidades de Grado
CREATE TABLE IF NOT EXISTS modalidades_grado (
    id_modalidad INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    requiere_tutor TINYINT(1) DEFAULT 1,
    flujo ENUM('perfil_mg', 'examen_areas', 'excelencia') DEFAULT 'perfil_mg',
    activa TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Semilla de las 5 Modalidades ofertadas por la carrera
INSERT INTO modalidades_grado (codigo, nombre, requiere_tutor, flujo) VALUES
('PROYECTO', 'Proyecto de Grado', 1, 'perfil_mg'),
('TESIS', 'Tesis de Grado', 1, 'perfil_mg'),
('DIRIGIDO', 'Trabajo Dirigido', 1, 'perfil_mg'),
('EXAMEN', 'Examen de Grado', 0, 'examen_areas'),
('EXCELENCIA', 'Graduación por Excelencia', 0, 'excelencia')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

-- 4. Tabla de Cohortes
CREATE TABLE IF NOT EXISTS cohortes_mg (
    id_cohorte INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(30) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NULL,
    activa TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Cohorte Semilla inicial
INSERT INTO cohortes_mg (codigo, nombre, fecha_inicio, activa) VALUES
('G1-2026-03', 'Grupo 1 - Marzo 2026', '2026-03-01', 1)
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

-- 5. Tabla de Calendario e Hitos por Cohorte
CREATE TABLE IF NOT EXISTS calendario_mg (
    id_hito INT AUTO_INCREMENT PRIMARY KEY,
    id_cohorte INT NOT NULL,
    etapa ENUM('previa', 'mg1', 'mg2') NOT NULL,
    tipo ENUM('taller', 'asignacion_tutor', 'asignacion_tribunal', 'informe', 'defensa', 'ingreso_mg2', 'otro') NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    orden INT DEFAULT 1,
    fecha_limite DATE NOT NULL,
    avance_esperado_pct TINYINT NULL,
    FOREIGN KEY (id_cohorte) REFERENCES cohortes_mg(id_cohorte) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;