-- ============================================
-- BASE DE DATOS DE CONOCIMIENTO - MOTOR IA v3.0
-- ============================================
-- Base de datos independiente para almacenar
-- la base de conocimiento del sistema experto
-- ============================================

CREATE DATABASE IF NOT EXISTS `gymsys_kb` 
    DEFAULT CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

USE `gymsys_kb`;

-- ============================================
-- TABLA: kb_metadata
-- Almacena versión y metadatos de la KB
-- ============================================
CREATE TABLE IF NOT EXISTS `kb_metadata` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `version` VARCHAR(20) NOT NULL,
    `fecha_actualizacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `descripcion` TEXT,
    `activo` TINYINT(1) DEFAULT 1,
    UNIQUE KEY `uk_version` (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar versión inicial
INSERT INTO `kb_metadata` (`version`, `descripcion`) 
VALUES ('3.0.0', 'Fase 3 - Base de conocimiento con reglas compuestas y tendencias');

-- ============================================
-- TABLA: kb_ponderaciones
-- Pesos de cada módulo en el cálculo de riesgo
-- ============================================
CREATE TABLE IF NOT EXISTS `kb_ponderaciones` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `modulo` VARCHAR(50) NOT NULL,
    `peso` INT NOT NULL,
    `descripcion` VARCHAR(255),
    `activo` TINYINT(1) DEFAULT 1,
    UNIQUE KEY `uk_modulo` (`modulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar ponderaciones base
INSERT INTO `kb_ponderaciones` (`modulo`, `peso`, `descripcion`) VALUES
('fms', 30, 'Peso del Test FMS en el cálculo de riesgo'),
('postural', 30, 'Peso del análisis postural en el cálculo de riesgo'),
('lesiones', 30, 'Peso de las lesiones en el cálculo de riesgo'),
('asistencia', 10, 'Peso de la asistencia en el cálculo de riesgo');

-- ============================================
-- TABLA: kb_umbrales
-- Rangos de clasificación de riesgo
-- ============================================
CREATE TABLE IF NOT EXISTS `kb_umbrales` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `nivel` VARCHAR(20) NOT NULL,
    `min_score` INT NOT NULL,
    `max_score` INT NOT NULL,
    `color_badge` VARCHAR(50),
    `descripcion` VARCHAR(255),
    `activo` TINYINT(1) DEFAULT 1,
    UNIQUE KEY `uk_nivel` (`nivel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar umbrales base
INSERT INTO `kb_umbrales` (`nivel`, `min_score`, `max_score`, `color_badge`, `descripcion`) VALUES
('bajo', 0, 33, 'success', 'Riesgo bajo de lesión'),
('medio', 34, 66, 'warning', 'Riesgo moderado de lesión'),
('alto', 67, 100, 'danger', 'Riesgo alto de lesión');

-- ============================================
-- TABLA: kb_perfiles
-- Perfiles de atleta para reglas específicas
-- ============================================
CREATE TABLE IF NOT EXISTS `kb_perfiles` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `codigo` VARCHAR(50) NOT NULL,
    `nombre` VARCHAR(100) NOT NULL,
    `descripcion` TEXT,
    `criterios_json` JSON COMMENT 'Criterios de asignación del perfil',
    `activo` TINYINT(1) DEFAULT 1,
    UNIQUE KEY `uk_codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar perfiles base
INSERT INTO `kb_perfiles` (`codigo`, `nombre`, `descripcion`, `criterios_json`) VALUES
('ALTA_CARGA', 'Atleta de Alta Carga', 
 'Atleta con alto volumen de entrenamiento y competencias frecuentes',
 '{"asistencia_min": 80, "competencias_mes": 2}'),
('INTERMITENTE', 'Atleta Intermitente',
 'Atleta con asistencia irregular y pausas frecuentes',
 '{"asistencia_max": 60, "variacion_alta": true}'),
('RECUPERACION', 'En Recuperación',
 'Atleta retornando de lesión o período de inactividad',
 '{"lesion_reciente_dias": 60, "reduccion_carga": true}'),
('PRINCIPIANTE', 'Principiante',
 'Atleta nuevo con menos de 6 meses de entrenamiento',
 '{"antiguedad_meses_max": 6}'),
('COMPETIDOR', 'Competidor Activo',
 'Atleta con competencias próximas o en temporada competitiva',
 '{"dias_proxima_competencia_max": 30}');

-- ============================================
-- TABLA: kb_reglas
-- Reglas del sistema experto
-- ============================================
CREATE TABLE IF NOT EXISTS `kb_reglas` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `codigo` VARCHAR(100) NOT NULL,
    `modulo` ENUM('FMS', 'POSTURAL', 'LESIONES', 'ASISTENCIA', 'COMPUESTA', 'TENDENCIA', 'PERFIL', 'AUSENCIA_DATOS') NOT NULL,
    `tipo_regla` ENUM('simple', 'compuesta', 'tendencia', 'perfil') DEFAULT 'simple',
    `descripcion` VARCHAR(255) NOT NULL,
    
    -- Condiciones (para reglas simples)
    `campo` VARCHAR(100),
    `operador` VARCHAR(20),
    `valor` VARCHAR(100),
    
    -- Para reglas compuestas y tendencias
    `condiciones_json` JSON COMMENT 'Condiciones complejas en formato JSON',
    
    -- Resultados
    `riesgo_puntos` INT DEFAULT 0,
    `prioridad` ENUM('baja', 'media', 'alta', 'critica') DEFAULT 'media',
    `mensaje_factor` TEXT,
    `recomendacion_base` TEXT,
    `recomendaciones_detalladas_json` JSON COMMENT 'Recomendaciones estructuradas por categoría',
    
    -- Perfil asociado (si aplica)
    `id_perfil` INT NULL,
    
    -- Control
    `activo` TINYINT(1) DEFAULT 1,
    `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `fecha_modificacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY `uk_codigo` (`codigo`),
    KEY `idx_modulo` (`modulo`),
    KEY `idx_tipo_regla` (`tipo_regla`),
    KEY `idx_activo` (`activo`),
    FOREIGN KEY (`id_perfil`) REFERENCES `kb_perfiles`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- INSERCIÓN DE REGLAS BASE - FMS
-- ============================================
INSERT INTO `kb_reglas` 
(`codigo`, `modulo`, `tipo_regla`, `descripcion`, `campo`, `operador`, `valor`, `riesgo_puntos`, `prioridad`, `mensaje_factor`, `recomendacion_base`, `recomendaciones_detalladas_json`) 
VALUES
('R1_FMS_CRITICO', 'FMS', 'simple', 
 'Puntuación FMS crítica (≤12): Alto riesgo inmediato',
 'puntuacion_total', '<=', '12', 30, 'critica',
 'Puntuación FMS crítica ({score}/21). Los patrones de movimiento básicos presentan deficiencias severas que requieren intervención inmediata antes de progresiones de carga.',
 'Implementar programa intensivo de movilidad y estabilidad',
 JSON_OBJECT(
    'tecnica', JSON_ARRAY(
        'Revisión completa de técnica en todos los movimientos básicos',
        'Evitar cargas superiores al 60% hasta mejorar patrones'
    ),
    'movilidad_estabilidad', JSON_ARRAY(
        'Bloque de movilidad activa 15-20 min pre-entrenamiento',
        'Trabajo específico en pruebas FMS con score ≤1',
        'Ejercicios correctivos 3-4x por semana'
    ),
    'seguimiento', JSON_ARRAY(
        'Reevaluación FMS en 4 semanas',
        'Seguimiento semanal de patrones corregidos'
    )
 )),

('R2_FMS_BAJO', 'FMS', 'simple',
 'Puntuación FMS baja (13-14): Riesgo elevado',
 'puntuacion_total', 'BETWEEN', '13,14', 20, 'alta',
 'Puntuación FMS baja ({score}/21). Se detectan limitaciones importantes en patrones de movimiento que incrementan el riesgo de lesión.',
 'Incorporar trabajo correctivo progresivo',
 JSON_OBJECT(
    'tecnica', JSON_ARRAY(
        'Énfasis en progresión técnica sobre carga',
        'Limitar cargas al 70-75% hasta mejora'
    ),
    'movilidad_estabilidad', JSON_ARRAY(
        'Programa de movilidad dirigida a patrones deficientes',
        'Trabajo de estabilidad core 2-3x por semana'
    ),
    'seguimiento', JSON_ARRAY(
        'Reevaluar FMS en 6-8 semanas',
        'Monitoreo bi-semanal de progreso'
    )
 )),

('R3_FMS_MODERADO', 'FMS', 'simple',
 'Puntuación FMS moderada (15-17): Riesgo moderado',
 'puntuacion_total', 'BETWEEN', '15,17', 10, 'media',
 'Puntuación FMS moderada ({score}/21). Algunos patrones de movimiento presentan compensaciones menores.',
 'Mantener trabajo preventivo y corregir patrones específicos',
 JSON_OBJECT(
    'movilidad_estabilidad', JSON_ARRAY(
        'Trabajo preventivo de movilidad 10 min pre-entrenamiento',
        'Enfoque en pruebas con score 1-2'
    ),
    'seguimiento', JSON_ARRAY(
        'Reevaluación FMS en 8-12 semanas'
    )
 ));

-- ============================================
-- INSERCIÓN DE REGLAS BASE - POSTURAL
-- ============================================
INSERT INTO `kb_reglas` 
(`codigo`, `modulo`, `tipo_regla`, `descripcion`, `campo`, `operador`, `valor`, `riesgo_puntos`, `prioridad`, `mensaje_factor`, `recomendacion_base`, `recomendaciones_detalladas_json`) 
VALUES
('R10_POSTURAL_SEVERO', 'POSTURAL', 'simple',
 'Múltiples alteraciones posturales severas (≥5 problemas)',
 'num_problemas', '>=', '5', 30, 'critica',
 'Múltiples alteraciones posturales severas detectadas ({count} problemas). Se requiere evaluación biomecánica urgente y plan correctivo integral.',
 'Evaluación biomecánica especializada urgente',
 JSON_OBJECT(
    'tecnica', JSON_ARRAY(
        'Revisión completa de postura en ejercicios básicos',
        'Evitar ejercicios que comprometan áreas afectadas'
    ),
    'movilidad_estabilidad', JSON_ARRAY(
        'Programa correctivo postural intensivo 4-5x semana',
        'Trabajo de cadena posterior y anterior según alteraciones',
        'Fortalecimiento de musculatura estabilizadora'
    ),
    'seguimiento', JSON_ARRAY(
        'Reevaluación postural en 4-6 semanas',
        'Considerar derivación a fisioterapia especializada'
    )
 )),

('R11_POSTURAL_MODERADO', 'POSTURAL', 'simple',
 'Alteraciones posturales moderadas (3-4 problemas)',
 'num_problemas', 'BETWEEN', '3,4', 20, 'alta',
 'Se detectan varias alteraciones posturales moderadas o severas ({count} problemas). Trabajo correctivo necesario para prevenir compensaciones.',
 'Implementar trabajo postural correctivo',
 JSON_OBJECT(
    'movilidad_estabilidad', JSON_ARRAY(
        'Trabajo correctivo postural 3x semana',
        'Estiramientos específicos en áreas comprometidas',
        'Fortalecimiento de grupos musculares débiles'
    ),
    'seguimiento', JSON_ARRAY(
        'Reevaluación postural en 6-8 semanas'
    )
 )),

('R12_POSTURAL_LEVE', 'POSTURAL', 'simple',
 'Alteraciones posturales leves (1-2 problemas)',
 'num_problemas', 'BETWEEN', '1,2', 10, 'media',
 'Alteraciones posturales leves detectadas ({count} problemas).',
 'Mantener trabajo preventivo postural',
 JSON_OBJECT(
    'movilidad_estabilidad', JSON_ARRAY(
        'Trabajo preventivo de postura 2x semana'
    )
 ));

-- ============================================
-- INSERCIÓN DE REGLAS BASE - LESIONES
-- ============================================
INSERT INTO `kb_reglas` 
(`codigo`, `modulo`, `tipo_regla`, `descripcion`, `riesgo_puntos`, `prioridad`, `mensaje_factor`, `recomendacion_base`, `recomendaciones_detalladas_json`) 
VALUES
('R20_LESIONES_MULTIPLES_ACTIVAS', 'LESIONES', 'simple',
 'Múltiples lesiones activas simultáneas',
 25, 'critica',
 'Múltiples lesiones activas ({count}). Alto riesgo de compensaciones y nuevas lesiones por alteración de patrones motores.',
 'Reducir carga y priorizar rehabilitación',
 JSON_OBJECT(
    'carga_programacion', JSON_ARRAY(
        'Reducción de carga del 40-50% en ejercicios generales',
        'Evitar completamente zonas lesionadas',
        'Enfoque en trabajo unilateral para equilibrar compensaciones'
    ),
    'recuperacion', JSON_ARRAY(
        'Priorizar recuperación sobre rendimiento',
        'Considerar fisioterapia multimodal',
        'Aumentar días de recuperación activa'
    ),
    'seguimiento', JSON_ARRAY(
        'Monitoreo diario de sintomatología',
        'Reevaluación médica cada 2 semanas'
    )
 )),

('R21_LESION_ACTIVA_UNICA', 'LESIONES', 'simple',
 'Lesión activa única',
 12, 'alta',
 'Lesión activa ({gravedad}) en {zona}. Limitar carga en área afectada y evitar compensaciones.',
 'Modificar programa según lesión específica',
 JSON_OBJECT(
    'carga_programacion', JSON_ARRAY(
        'Adaptar ejercicios evitando rango doloroso',
        'Limitar carga en área afectada según tolerancia',
        'Priorizar ejercicios accesorios no comprometidos'
    ),
    'recuperacion', JSON_ARRAY(
        'Implementar protocolos de recuperación específicos',
        'Considerar terapias complementarias'
    )
 )),

('R22_LESION_RECIENTE', 'LESIONES', 'simple',
 'Lesión reciente recuperada (últimos 30 días)',
 10, 'media',
 'Lesión reciente recuperada. Fase de readaptación progresiva para prevenir recaídas.',
 'Ajustar progresivamente la carga',
 JSON_OBJECT(
    'carga_programacion', JSON_ARRAY(
        'Incrementos de carga no superiores al 10% semanal',
        'Priorizar volumen sobre intensidad en fase inicial',
        'Trabajo técnico exhaustivo en movimientos afectados'
    ),
    'seguimiento', JSON_ARRAY(
        'Monitoreo estrecho de respuesta adaptativa',
        'Reevaluar zona afectada cada 2-3 semanas'
    )
 )),

('R23_HISTORIAL_LESIONES', 'LESIONES', 'simple',
 'Historial significativo de lesiones (≥3 registradas)',
 8, 'media',
 'Historial de múltiples lesiones ({count} registradas). Patrón que requiere análisis de causas subyacentes y estrategias preventivas reforzadas.',
 'Implementar estrategias preventivas reforzadas',
 JSON_OBJECT(
    'movilidad_estabilidad', JSON_ARRAY(
        'Programa preventivo permanente de movilidad',
        'Trabajo de estabilidad articular reforzado'
    ),
    'seguimiento', JSON_ARRAY(
        'Análisis de patrones de lesión por zona anatómica',
        'Evaluación de factores técnicos o biomecánicos recurrentes'
    )
 ));

-- ============================================
-- INSERCIÓN DE REGLAS BASE - ASISTENCIA
-- ============================================
INSERT INTO `kb_reglas` 
(`codigo`, `modulo`, `tipo_regla`, `descripcion`, `campo`, `operador`, `valor`, `riesgo_puntos`, `prioridad`, `mensaje_factor`, `recomendacion_base`, `recomendaciones_detalladas_json`) 
VALUES
('R30_ASISTENCIA_MUY_BAJA', 'ASISTENCIA', 'simple',
 'Asistencia muy irregular (<50%)',
 'porcentaje_asistencia', '<', '50', 10, 'alta',
 'Asistencia muy irregular ({porcentaje}% últimos 30 días). La inconsistencia es un factor de riesgo principal por pérdida de adaptaciones.',
 'Establecer plan de adherencia',
 JSON_OBJECT(
    'carga_programacion', JSON_ARRAY(
        'Ajustar programa a disponibilidad real',
        'Reducir complejidad y carga en retornos',
        'Enfatizar calidad sobre cantidad'
    ),
    'seguimiento', JSON_ARRAY(
        'Revisar causas de ausencias con atleta',
        'Establecer metas de asistencia progresivas',
        'Considerar ajustes de horarios si es necesario'
    )
 )),

('R31_ASISTENCIA_SUBOPTIMA', 'ASISTENCIA', 'simple',
 'Asistencia por debajo de lo óptimo (50-79%)',
 'porcentaje_asistencia', 'BETWEEN', '50,79', 5, 'media',
 'Asistencia por debajo de lo óptimo ({porcentaje}%). Revisar adherencia al plan de entrenamiento.',
 'Mejorar adherencia al programa',
 JSON_OBJECT(
    'seguimiento', JSON_ARRAY(
        'Revisar adherencia y establecer metas',
        'Identificar barreras de asistencia'
    )
 ));

-- ============================================
-- INSERCIÓN DE REGLAS - AUSENCIA DE DATOS
-- ============================================
INSERT INTO `kb_reglas` 
(`codigo`, `modulo`, `tipo_regla`, `descripcion`, `prioridad`, `mensaje_factor`, `recomendacion_base`, `recomendaciones_detalladas_json`) 
VALUES
('R40_SIN_FMS', 'AUSENCIA_DATOS', 'simple',
 'Ausencia de Test FMS reciente',
 'alta',
 'No se encontró un Test FMS reciente. Esta evaluación es fundamental para establecer una línea base de patrones de movimiento y detectar deficiencias que incrementen el riesgo de lesión.',
 'Realizar Test FMS completo',
 JSON_OBJECT(
    'seguimiento', JSON_ARRAY(
        'Realizar Test FMS completo para establecer línea base',
        'Priorizar esta evaluación en los próximos 7 días',
        'La ausencia de datos FMS limita significativamente la precisión del análisis de riesgo'
    )
 )),

('R41_SIN_POSTURAL', 'AUSENCIA_DATOS', 'simple',
 'Ausencia de evaluación postural reciente',
 'alta',
 'No se encontró una evaluación postural reciente. El análisis postural es crítico para identificar desbalances estructurales que pueden predisponer a lesiones.',
 'Realizar evaluación postural completa',
 JSON_OBJECT(
    'seguimiento', JSON_ARRAY(
        'Realizar evaluación postural completa en los próximos 7 días',
        'Incluir análisis estático y dinámico',
        'Documentar fotográficamente para seguimiento'
    )
 )),

('R42_SIN_ASISTENCIA', 'AUSENCIA_DATOS', 'simple',
 'Ausencia de registros de asistencia',
 'media',
 'No hay registros de asistencia en los últimos 30 días. El patrón de asistencia es un indicador clave de riesgo por adaptación inadecuada.',
 'Mantener registro sistemático de asistencias',
 JSON_OBJECT(
    'seguimiento', JSON_ARRAY(
        'Implementar registro sistemático de asistencias',
        'Utilizar sistema de control de asistencia del gimnasio',
        'La ausencia de estos datos impide evaluar riesgo por desadaptación'
    )
 )),

('R43_ATLETA_NUEVO_INCOMPLETO', 'AUSENCIA_DATOS', 'simple',
 'Atleta con evaluación inicial incompleta',
 'critica',
 'Atleta con evaluación inicial incompleta. La ausencia de datos base impide realizar un análisis de riesgo preciso y establecer un programa seguro.',
 'Completar batería completa de evaluaciones',
 JSON_OBJECT(
    'seguimiento', JSON_ARRAY(
        'Completar batería completa de evaluaciones (FMS + Postural + Historial)',
        'Establecer línea base antes de progresiones significativas de carga',
        'Priorizar esta acción en los próximos 3-5 días'
    )
 ));

-- ============================================
-- REGLAS COMPUESTAS AVANZADAS
-- ============================================
INSERT INTO `kb_reglas` 
(`codigo`, `modulo`, `tipo_regla`, `descripcion`, `condiciones_json`, `riesgo_puntos`, `prioridad`, `mensaje_factor`, `recomendacion_base`, `recomendaciones_detalladas_json`) 
VALUES
('RC1_FMS_BAJO_POSTURAL_MULTIPLE', 'COMPUESTA', 'compuesta',
 'FMS bajo + múltiples problemas posturales',
 JSON_OBJECT(
    'reglas', JSON_ARRAY('R2_FMS_BAJO', 'R11_POSTURAL_MODERADO'),
    'operador', 'AND'
 ),
 15, 'critica',
 'Combinación crítica: FMS bajo ({score_fms}/21) + múltiples alteraciones posturales ({count_postural} problemas). Alto riesgo por interacción de deficiencias funcionales y estructurales.',
 'Intervención correctiva integral urgente',
 JSON_OBJECT(
    'tecnica', JSON_ARRAY(
        'Reducción severa de cargas (no superior al 50-60% RM)',
        'Revisión exhaustiva de patrones técnicos',
        'Evitar ejercicios complejos hasta mejora'
    ),
    'movilidad_estabilidad', JSON_ARRAY(
        'Programa correctivo intensivo combinando FMS y corrección postural',
        'Sesiones específicas de trabajo correctivo 4-5x semana',
        'Evaluación funcional semanal para ajustar progresión'
    ),
    'seguimiento', JSON_ARRAY(
        'Reevaluación FMS y postural conjunta en 4 semanas',
        'Considerar evaluación biomecánica especializada',
        'Monitoreo estrecho de respuesta adaptativa'
    )
 )),

('RC2_LESION_RECURRENTE_MISMA_ZONA', 'COMPUESTA', 'compuesta',
 'Historial de lesión en misma zona + nueva lesión activa',
 JSON_OBJECT(
    'condiciones', JSON_OBJECT(
        'historial_zona_match', true,
        'lesion_activa', true
    )
 ),
 20, 'critica',
 'Patrón de lesión recurrente en {zona}. La recidiva en la misma articulación/zona sugiere factores biomecánicos o técnicos no resueltos.',
 'Evaluación especializada de causas subyacentes',
 JSON_OBJECT(
    'tecnica', JSON_ARRAY(
        'Análisis video exhaustivo de técnica en movimientos que involucren zona afectada',
        'Identificar compensaciones o vicios técnicos específicos',
        'Corrección técnica supervisada en cada sesión'
    ),
    'carga_programacion', JSON_ARRAY(
        'Evitar completamente ejercicios que involucren zona hasta resolución',
        'Diseño de progresión específica con énfasis en tolerancia de tejido',
        'Considerar variantes biomecánicas de ejercicios'
    ),
    'recuperacion', JSON_ARRAY(
        'Protocolo de rehabilitación específico por articulación',
        'Terapias físicas complementarias (fisioterapia, osteopatía)',
        'Trabajo preventivo permanente en esa zona'
    ),
    'seguimiento', JSON_ARRAY(
        'Evaluación médica o fisioterapéutica especializada obligatoria',
        'Análisis biomecánico en centro especializado si disponible',
        'Monitoreo continuo de síntomas incluso post-recuperación'
    )
 )),

('RC3_FMS_EMPEORANDO_LESION', 'COMPUESTA', 'tendencia',
 'FMS con tendencia negativa + lesión activa',
 JSON_OBJECT(
    'condiciones', JSON_OBJECT(
        'fms_tendencia', 'negativa',
        'lesion_activa', true
    )
 ),
 18, 'alta',
 'FMS mostrando empeoramiento en últimas evaluaciones mientras presenta lesión activa. Patrón de deterioro funcional progresivo.',
 'Detener progresiones y priorizar recuperación funcional',
 JSON_OBJECT(
    'carga_programacion', JSON_ARRAY(
        'Suspender progresiones de carga inmediatamente',
        'Reducción de volumen e intensidad del 30-40%',
        'Enfoque exclusivo en recuperación y corrección'
    ),
    'movilidad_estabilidad', JSON_ARRAY(
        'Programa intensivo de recuperación funcional',
        'Trabajo correctivo diario',
        'Priorizar patrones FMS que mostraron mayor deterioro'
    ),
    'seguimiento', JSON_ARRAY(
        'Reevaluación FMS en 2-3 semanas para verificar reversión de tendencia',
        'Evaluación médica si deterioro continúa'
    )
 ));

-- ============================================
-- REGLAS DE TENDENCIA TEMPORAL
-- ============================================
INSERT INTO `kb_reglas` 
(`codigo`, `modulo`, `tipo_regla`, `descripcion`, `condiciones_json`, `riesgo_puntos`, `prioridad`, `mensaje_factor`, `recomendacion_base`, `recomendaciones_detalladas_json`) 
VALUES
('RT1_FMS_DETERIORO_PROGRESIVO', 'TENDENCIA', 'tendencia',
 'FMS mostrando deterioro progresivo en últimas 3 evaluaciones',
 JSON_OBJECT(
    'campo', 'puntuacion_total_fms',
    'evaluaciones', 3,
    'tendencia', 'descendente',
    'umbral_cambio', -2
 ),
 12, 'alta',
 'FMS mostrando deterioro progresivo: {score_anterior} → {score_actual} en últimas evaluaciones. Indica acumulación de fatiga o desarrollo de compensaciones.',
 'Revisar carga de entrenamiento y descanso',
 JSON_OBJECT(
    'carga_programacion', JSON_ARRAY(
        'Análisis de periodización: revisar si hay sobrecarga acumulada',
        'Considerar semana de descarga inmediata',
        'Reducir volumen/intensidad 20-30% por 1-2 semanas'
    ),
    'recuperacion', JSON_ARRAY(
        'Aumentar días de recuperación activa',
        'Implementar técnicas de recuperación (masaje, crioterapia, estiramientos)',
        'Revisar calidad de sueño y nutrición'
    ),
    'seguimiento', JSON_ARRAY(
        'Reevaluar FMS después de período de descarga',
        'Si persiste deterioro, considerar evaluación médica',
        'Monitoreo de síntomas de sobreentrenamiento'
    )
 )),

('RT2_ASISTENCIA_CAIDA_PROGRESIVA', 'TENDENCIA', 'tendencia',
 'Asistencia mostrando caída progresiva últimos meses',
 JSON_OBJECT(
    'campo', 'porcentaje_asistencia',
    'periodo_meses', 3,
    'tendencia', 'descendente',
    'umbral_cambio_porcentual', -15
 ),
 8, 'media',
 'Asistencia mostrando caída progresiva: de {asistencia_anterior}% a {asistencia_actual}%. Patrón de desvinculación o pérdida de motivación.',
 'Intervención para mejorar adherencia',
 JSON_OBJECT(
    'seguimiento', JSON_ARRAY(
        'Conversación con atleta para identificar causas (motivación, lesiones, tiempo)',
        'Ajustar programa según disponibilidad real',
        'Establecer metas de asistencia realistas y progresivas',
        'Considerar modificación de horarios o días de entrenamiento'
    ),
    'carga_programacion', JSON_ARRAY(
        'Adaptar volumen y frecuencia a asistencia real',
        'Simplificar programa si complejidad es barrera',
        'Priorizar sesiones esenciales'
    )
 )),

('RT3_LESIONES_FRECUENTES_ULTIMOS_6_MESES', 'TENDENCIA', 'tendencia',
 'Múltiples lesiones en últimos 6 meses (≥3)',
 JSON_OBJECT(
    'periodo_meses', 6,
    'min_lesiones', 3
 ),
 15, 'alta',
 'Patrón de lesiones frecuentes en últimos 6 meses ({count} lesiones). Sugiere factores sistémicos no resueltos: sobrecarga, técnica deficiente, o condiciones predisponentes.',
 'Análisis profundo de causas sistémicas',
 JSON_OBJECT(
    'tecnica', JSON_ARRAY(
        'Análisis técnico exhaustivo en todos los movimientos base',
        'Identificar compensaciones o patrones incorrectos recurrentes',
        'Considerar coaching técnico reforzado'
    ),
    'carga_programacion', JSON_ARRAY(
        'Revisión completa de periodización y progresiones',
        'Evaluar si incrementos de carga fueron demasiado agresivos',
        'Implementar progresión más conservadora (5-7% semanal máximo)',
        'Aumentar días de recuperación en microciclo'
    ),
    'movilidad_estabilidad', JSON_ARRAY(
        'Programa preventivo permanente de movilidad y estabilidad',
        'Trabajo correctivo post-calentamiento en cada sesión',
        'Énfasis en fortalecimiento de áreas débiles identificadas'
    ),
    'recuperacion', JSON_ARRAY(
        'Protocolos de recuperación sistemáticos',
        'Revisar estrategias nutricionales y de descanso',
        'Considerar suplementación específica si hay deficiencias'
    ),
    'seguimiento', JSON_ARRAY(
        'Evaluación médica general para descartar condiciones subyacentes',
        'Análisis de factores externos (estrés, sueño, nutrición)',
        'Monitoreo mensual de tendencia'
    )
 ));

-- ============================================
-- REGLAS POR PERFIL
-- ============================================
INSERT INTO `kb_reglas` 
(`codigo`, `modulo`, `tipo_regla`, `descripcion`, `condiciones_json`, `id_perfil`, `riesgo_puntos`, `prioridad`, `mensaje_factor`, `recomendacion_base`, `recomendaciones_detalladas_json`) 
VALUES
('RP1_ALTA_CARGA_ESTABILIDAD_BAJA', 'PERFIL', 'perfil',
 'Atleta de alta carga + baja estabilidad (FMS ≤15)',
 JSON_OBJECT(
    'perfil', 'ALTA_CARGA',
    'fms_max', 15
 ),
 (SELECT id FROM kb_perfiles WHERE codigo = 'ALTA_CARGA'),
 16, 'alta',
 'Perfil de riesgo: Atleta de alta carga con deficiencias de estabilidad (FMS {score}/21). Combinación peligrosa que predispone a lesiones por sobreuso y compensaciones.',
 'Reducir carga y reforzar estabilidad',
 JSON_OBJECT(
    'carga_programacion', JSON_ARRAY(
        'Reducción temporal de volumen de entrenamiento (20-30%)',
        'Priorizar calidad sobre cantidad en sesiones',
        'Evitar competencias no prioritarias en próximo mes',
        'Redistribuir carga semanal con más días de recuperación'
    ),
    'movilidad_estabilidad', JSON_ARRAY(
        'Bloque intensivo de estabilidad 3-4x semana',
        'Trabajo de core y estabilidad escapular diario',
        'Ejercicios unilaterales para corregir asimetrías'
    ),
    'recuperacion', JSON_ARRAY(
        'Priorizar recuperación en este período',
        'Técnicas de recuperación activa obligatorias',
        'Monitoreo de signos de sobreentrenamiento'
    ),
    'seguimiento', JSON_ARRAY(
        'Reevaluar FMS en 4-6 semanas',
        'Retomar progresión de carga solo con FMS ≥16',
        'Considerar reducción de frecuencia competitiva'
    )
 )),

('RP2_INTERMITENTE_HISTORIAL_LESIONES', 'PERFIL', 'perfil',
 'Atleta intermitente + historial de lesiones',
 JSON_OBJECT(
    'perfil', 'INTERMITENTE',
    'historial_lesiones_min', 2
 ),
 (SELECT id FROM kb_perfiles WHERE codigo = 'INTERMITENTE'),
 14, 'alta',
 'Perfil de riesgo: Atleta intermitente con historial de lesiones. Patrón de desadaptación crónica que incrementa vulnerabilidad.',
 'Programa adaptado a disponibilidad real',
 JSON_OBJECT(
    'carga_programacion', JSON_ARRAY(
        'Diseño de programa flexible adaptado a asistencia irregular',
        'Sesiones completas autocontenidas (no dependientes de sesión anterior)',
        'Reducción de complejidad técnica',
        'Calentamiento extendido obligatorio (15-20 min) en cada retorno',
        'Cargas conservadoras: no superar 70% en primeras 2 sesiones tras ausencia >5 días'
    ),
    'seguimiento', JSON_ARRAY(
        'Reunión para establecer expectativas realistas',
        'Plan de retorno progresivo tras ausencias prolongadas',
        'Identificar y resolver barreras de asistencia'
    )
 )),

('RP3_COMPETIDOR_PROXIMO_EVENTO', 'PERFIL', 'perfil',
 'Competidor con evento próximo (<30 días) y riesgo medio-alto',
 JSON_OBJECT(
    'perfil', 'COMPETIDOR',
    'dias_competencia_max', 30,
    'riesgo_min', 'medio'
 ),
 (SELECT id FROM kb_perfiles WHERE codigo = 'COMPETIDOR'),
 10, 'alta',
 'Competidor con evento próximo ({dias_competencia} días) presentando riesgo {nivel_riesgo}. Requiere manejo cuidadoso del balance rendimiento-seguridad.',
 'Gestión de riesgo pre-competencia',
 JSON_OBJECT(
    'carga_programacion', JSON_ARRAY(
        'Reducir volumen pero mantener intensidad específica',
        'Evitar introducir ejercicios nuevos o variantes técnicas',
        'Priorizar técnica y velocidad sobre carga absoluta',
        'Planificar taper adecuado (7-10 días pre-competencia)'
    ),
    'recuperacion', JSON_ARRAY(
        'Maximizar recuperación: sueño, nutrición, hidratación',
        'Técnicas de recuperación activa diarias',
        'Manejo de estrés y ansiedad pre-competitiva'
    ),
    'seguimiento', JSON_ARRAY(
        'Monitoreo diario de estado de readiness',
        'Ajustes de carga según respuesta individual',
        'Evaluación post-competencia obligatoria',
        'Plan de descarga post-evento'
    )
 ));

-- ============================================
-- ÍNDICES ADICIONALES PARA OPTIMIZACIÓN
-- ============================================
CREATE INDEX idx_kb_reglas_modulo_activo ON kb_reglas(modulo, activo);
CREATE INDEX idx_kb_reglas_tipo_activo ON kb_reglas(tipo_regla, activo);
CREATE INDEX idx_kb_reglas_prioridad ON kb_reglas(prioridad);

-- ============================================
-- VISTA: Reglas activas por módulo
-- ============================================
CREATE VIEW v_reglas_activas AS
SELECT 
    r.id,
    r.codigo,
    r.modulo,
    r.tipo_regla,
    r.descripcion,
    r.campo,
    r.operador,
    r.valor,
    r.condiciones_json,
    r.riesgo_puntos,
    r.prioridad,
    r.mensaje_factor,
    r.recomendacion_base,
    r.recomendaciones_detalladas_json,
    p.codigo as perfil_codigo,
    p.nombre as perfil_nombre
FROM kb_reglas r
LEFT JOIN kb_perfiles p ON r.id_perfil = p.id
WHERE r.activo = 1
ORDER BY r.modulo, r.prioridad DESC, r.riesgo_puntos DESC;

-- ============================================
-- FIN DEL SCRIPT
-- ============================================
