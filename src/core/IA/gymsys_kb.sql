-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-11-2025 a las 12:45:29
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gymsys_kb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kb_metadata`
--

CREATE TABLE `kb_metadata` (
  `id` int(11) NOT NULL,
  `version` varchar(20) NOT NULL,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `kb_metadata`
--

INSERT INTO `kb_metadata` (`id`, `version`, `fecha_actualizacion`, `descripcion`, `activo`) VALUES
(1, '3.0.0', '2025-11-27 18:22:51', 'Fase 3 - Base de conocimiento con reglas compuestas y tendencias', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kb_perfiles`
--

CREATE TABLE `kb_perfiles` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `criterios_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Criterios de asignación del perfil' CHECK (json_valid(`criterios_json`)),
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `kb_perfiles`
--

INSERT INTO `kb_perfiles` (`id`, `codigo`, `nombre`, `descripcion`, `criterios_json`, `activo`) VALUES
(1, 'ALTA_CARGA', 'Atleta de Alta Carga', 'Atleta con alto volumen de entrenamiento y competencias frecuentes', '{\"asistencia_min\": 80, \"competencias_mes\": 2}', 1),
(2, 'INTERMITENTE', 'Atleta Intermitente', 'Atleta con asistencia irregular y pausas frecuentes', '{\"asistencia_max\": 60, \"variacion_alta\": true}', 1),
(3, 'RECUPERACION', 'En Recuperación', 'Atleta retornando de lesión o período de inactividad', '{\"lesion_reciente_dias\": 60, \"reduccion_carga\": true}', 1),
(4, 'PRINCIPIANTE', 'Principiante', 'Atleta nuevo con menos de 6 meses de entrenamiento', '{\"antiguedad_meses_max\": 6}', 1),
(5, 'COMPETIDOR', 'Competidor Activo', 'Atleta con competencias próximas o en temporada competitiva', '{\"dias_proxima_competencia_max\": 30}', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kb_ponderaciones`
--

CREATE TABLE `kb_ponderaciones` (
  `id` int(11) NOT NULL,
  `modulo` varchar(50) NOT NULL,
  `peso` int(11) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `kb_ponderaciones`
--

INSERT INTO `kb_ponderaciones` (`id`, `modulo`, `peso`, `descripcion`, `activo`) VALUES
(1, 'fms', 30, 'Peso del Test FMS en el cálculo de riesgo', 1),
(2, 'postural', 30, 'Peso del análisis postural en el cálculo de riesgo', 1),
(3, 'lesiones', 30, 'Peso de las lesiones en el cálculo de riesgo', 1),
(4, 'asistencia', 10, 'Peso de la asistencia en el cálculo de riesgo', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kb_reglas`
--

CREATE TABLE `kb_reglas` (
  `id` int(11) NOT NULL,
  `codigo` varchar(100) NOT NULL,
  `modulo` enum('FMS','POSTURAL','LESIONES','ASISTENCIA','COMPUESTA','TENDENCIA','PERFIL','AUSENCIA_DATOS') NOT NULL,
  `tipo_regla` enum('simple','compuesta','tendencia','perfil') DEFAULT 'simple',
  `descripcion` varchar(255) NOT NULL,
  `campo` varchar(100) DEFAULT NULL,
  `operador` varchar(20) DEFAULT NULL,
  `valor` varchar(100) DEFAULT NULL,
  `condiciones_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Condiciones complejas en formato JSON' CHECK (json_valid(`condiciones_json`)),
  `riesgo_puntos` int(11) DEFAULT 0,
  `prioridad` enum('baja','media','alta','critica') DEFAULT 'media',
  `mensaje_factor` text DEFAULT NULL,
  `recomendacion_base` text DEFAULT NULL,
  `recomendaciones_detalladas_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Recomendaciones estructuradas por categoría' CHECK (json_valid(`recomendaciones_detalladas_json`)),
  `id_perfil` int(11) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `kb_reglas`
--

INSERT INTO `kb_reglas` (`id`, `codigo`, `modulo`, `tipo_regla`, `descripcion`, `campo`, `operador`, `valor`, `condiciones_json`, `riesgo_puntos`, `prioridad`, `mensaje_factor`, `recomendacion_base`, `recomendaciones_detalladas_json`, `id_perfil`, `activo`, `fecha_creacion`, `fecha_modificacion`) VALUES
(1, 'R1_FMS_CRITICO', 'FMS', 'simple', 'Puntuación FMS crítica (≤12): Alto riesgo inmediato', 'puntuacion_total', '<=', '12', NULL, 30, 'critica', 'Puntuación FMS crítica ({score}/21). Los patrones de movimiento básicos presentan deficiencias severas que requieren intervención inmediata antes de progresiones de carga.', 'Implementar programa intensivo de movilidad y estabilidad', '{\"tecnica\": [\"Revisión completa de técnica en todos los movimientos básicos\", \"Evitar cargas superiores al 60% hasta mejorar patrones\"], \"movilidad_estabilidad\": [\"Bloque de movilidad activa 15-20 min pre-entrenamiento\", \"Trabajo específico en pruebas FMS con score ≤1\", \"Ejercicios correctivos 3-4x por semana\"], \"seguimiento\": [\"Reevaluación FMS en 4 semanas\", \"Seguimiento semanal de patrones corregidos\"]}', NULL, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(2, 'R2_FMS_BAJO', 'FMS', 'simple', 'Puntuación FMS baja (13-14): Riesgo elevado', 'puntuacion_total', 'BETWEEN', '13,14', NULL, 20, 'alta', 'Puntuación FMS baja ({score}/21). Se detectan limitaciones importantes en patrones de movimiento que incrementan el riesgo de lesión.', 'Incorporar trabajo correctivo progresivo', '{\"tecnica\": [\"Énfasis en progresión técnica sobre carga\", \"Limitar cargas al 70-75% hasta mejora\"], \"movilidad_estabilidad\": [\"Programa de movilidad dirigida a patrones deficientes\", \"Trabajo de estabilidad core 2-3x por semana\"], \"seguimiento\": [\"Reevaluar FMS en 6-8 semanas\", \"Monitoreo bi-semanal de progreso\"]}', NULL, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(3, 'R3_FMS_MODERADO', 'FMS', 'simple', 'Puntuación FMS moderada (15-17): Riesgo moderado', 'puntuacion_total', 'BETWEEN', '15,17', NULL, 10, 'media', 'Puntuación FMS moderada ({score}/21). Algunos patrones de movimiento presentan compensaciones menores.', 'Mantener trabajo preventivo y corregir patrones específicos', '{\"movilidad_estabilidad\": [\"Trabajo preventivo de movilidad 10 min pre-entrenamiento\", \"Enfoque en pruebas con score 1-2\"], \"seguimiento\": [\"Reevaluación FMS en 8-12 semanas\"]}', NULL, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(4, 'R10_POSTURAL_SEVERO', 'POSTURAL', 'simple', 'Múltiples alteraciones posturales severas (≥5 problemas)', 'num_problemas', '>=', '5', NULL, 30, 'critica', 'Múltiples alteraciones posturales severas detectadas ({count} problemas). Se requiere evaluación biomecánica urgente y plan correctivo integral.', 'Evaluación biomecánica especializada urgente', '{\"tecnica\": [\"Revisión completa de postura en ejercicios básicos\", \"Evitar ejercicios que comprometan áreas afectadas\"], \"movilidad_estabilidad\": [\"Programa correctivo postural intensivo 4-5x semana\", \"Trabajo de cadena posterior y anterior según alteraciones\", \"Fortalecimiento de musculatura estabilizadora\"], \"seguimiento\": [\"Reevaluación postural en 4-6 semanas\", \"Considerar derivación a fisioterapia especializada\"]}', NULL, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(5, 'R11_POSTURAL_MODERADO', 'POSTURAL', 'simple', 'Alteraciones posturales moderadas (3-4 problemas)', 'num_problemas', 'BETWEEN', '3,4', NULL, 20, 'alta', 'Se detectan varias alteraciones posturales moderadas o severas ({count} problemas). Trabajo correctivo necesario para prevenir compensaciones.', 'Implementar trabajo postural correctivo', '{\"movilidad_estabilidad\": [\"Trabajo correctivo postural 3x semana\", \"Estiramientos específicos en áreas comprometidas\", \"Fortalecimiento de grupos musculares débiles\"], \"seguimiento\": [\"Reevaluación postural en 6-8 semanas\"]}', NULL, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(6, 'R12_POSTURAL_LEVE', 'POSTURAL', 'simple', 'Alteraciones posturales leves (1-2 problemas)', 'num_problemas', 'BETWEEN', '1,2', NULL, 10, 'media', 'Alteraciones posturales leves detectadas ({count} problemas).', 'Mantener trabajo preventivo postural', '{\"movilidad_estabilidad\": [\"Trabajo preventivo de postura 2x semana\"]}', NULL, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(7, 'R20_LESIONES_MULTIPLES_ACTIVAS', 'LESIONES', 'simple', 'Múltiples lesiones activas simultáneas', NULL, NULL, NULL, NULL, 25, 'critica', 'Múltiples lesiones activas ({count}). Alto riesgo de compensaciones y nuevas lesiones por alteración de patrones motores.', 'Reducir carga y priorizar rehabilitación', '{\"carga_programacion\": [\"Reducción de carga del 40-50% en ejercicios generales\", \"Evitar completamente zonas lesionadas\", \"Enfoque en trabajo unilateral para equilibrar compensaciones\"], \"recuperacion\": [\"Priorizar recuperación sobre rendimiento\", \"Considerar fisioterapia multimodal\", \"Aumentar días de recuperación activa\"], \"seguimiento\": [\"Monitoreo diario de sintomatología\", \"Reevaluación médica cada 2 semanas\"]}', NULL, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(8, 'R21_LESION_ACTIVA_UNICA', 'LESIONES', 'simple', 'Lesión activa única', NULL, NULL, NULL, NULL, 12, 'alta', 'Lesión activa ({gravedad}) en {zona}. Limitar carga en área afectada y evitar compensaciones.', 'Modificar programa según lesión específica', '{\"carga_programacion\": [\"Adaptar ejercicios evitando rango doloroso\", \"Limitar carga en área afectada según tolerancia\", \"Priorizar ejercicios accesorios no comprometidos\"], \"recuperacion\": [\"Implementar protocolos de recuperación específicos\", \"Considerar terapias complementarias\"]}', NULL, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(9, 'R22_LESION_RECIENTE', 'LESIONES', 'simple', 'Lesión reciente recuperada (últimos 30 días)', NULL, NULL, NULL, NULL, 10, 'media', 'Lesión reciente recuperada. Fase de readaptación progresiva para prevenir recaídas.', 'Ajustar progresivamente la carga', '{\"carga_programacion\": [\"Incrementos de carga no superiores al 10% semanal\", \"Priorizar volumen sobre intensidad en fase inicial\", \"Trabajo técnico exhaustivo en movimientos afectados\"], \"seguimiento\": [\"Monitoreo estrecho de respuesta adaptativa\", \"Reevaluar zona afectada cada 2-3 semanas\"]}', NULL, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(10, 'R23_HISTORIAL_LESIONES', 'LESIONES', 'simple', 'Historial significativo de lesiones (≥3 registradas)', NULL, NULL, NULL, NULL, 8, 'media', 'Historial de múltiples lesiones ({count} registradas). Patrón que requiere análisis de causas subyacentes y estrategias preventivas reforzadas.', 'Implementar estrategias preventivas reforzadas', '{\"movilidad_estabilidad\": [\"Programa preventivo permanente de movilidad\", \"Trabajo de estabilidad articular reforzado\"], \"seguimiento\": [\"Análisis de patrones de lesión por zona anatómica\", \"Evaluación de factores técnicos o biomecánicos recurrentes\"]}', NULL, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(11, 'R30_ASISTENCIA_MUY_BAJA', 'ASISTENCIA', 'simple', 'Asistencia muy irregular (<50%)', 'porcentaje_asistencia', '<', '50', NULL, 10, 'alta', 'Asistencia muy irregular ({porcentaje}% últimos 30 días). La inconsistencia es un factor de riesgo principal por pérdida de adaptaciones.', 'Establecer plan de adherencia', '{\"carga_programacion\": [\"Ajustar programa a disponibilidad real\", \"Reducir complejidad y carga en retornos\", \"Enfatizar calidad sobre cantidad\"], \"seguimiento\": [\"Revisar causas de ausencias con atleta\", \"Establecer metas de asistencia progresivas\", \"Considerar ajustes de horarios si es necesario\"]}', NULL, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(12, 'R31_ASISTENCIA_SUBOPTIMA', 'ASISTENCIA', 'simple', 'Asistencia por debajo de lo óptimo (50-79%)', 'porcentaje_asistencia', 'BETWEEN', '50,79', NULL, 5, 'media', 'Asistencia por debajo de lo óptimo ({porcentaje}%). Revisar adherencia al plan de entrenamiento.', 'Mejorar adherencia al programa', '{\"seguimiento\": [\"Revisar adherencia y establecer metas\", \"Identificar barreras de asistencia\"]}', NULL, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(13, 'R40_SIN_FMS', 'AUSENCIA_DATOS', 'simple', 'Ausencia de Test FMS reciente', NULL, NULL, NULL, NULL, 0, 'alta', 'No se encontró un Test FMS reciente. Esta evaluación es fundamental para establecer una línea base de patrones de movimiento y detectar deficiencias que incrementen el riesgo de lesión.', 'Realizar Test FMS completo', '{\"seguimiento\": [\"Realizar Test FMS completo para establecer línea base\", \"Priorizar esta evaluación en los próximos 7 días\", \"La ausencia de datos FMS limita significativamente la precisión del análisis de riesgo\"]}', NULL, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(14, 'R41_SIN_POSTURAL', 'AUSENCIA_DATOS', 'simple', 'Ausencia de evaluación postural reciente', NULL, NULL, NULL, NULL, 0, 'alta', 'No se encontró una evaluación postural reciente. El análisis postural es crítico para identificar desbalances estructurales que pueden predisponer a lesiones.', 'Realizar evaluación postural completa', '{\"seguimiento\": [\"Realizar evaluación postural completa en los próximos 7 días\", \"Incluir análisis estático y dinámico\", \"Documentar fotográficamente para seguimiento\"]}', NULL, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(15, 'R42_SIN_ASISTENCIA', 'AUSENCIA_DATOS', 'simple', 'Ausencia de registros de asistencia', NULL, NULL, NULL, NULL, 0, 'media', 'No hay registros de asistencia en los últimos 30 días. El patrón de asistencia es un indicador clave de riesgo por adaptación inadecuada.', 'Mantener registro sistemático de asistencias', '{\"seguimiento\": [\"Implementar registro sistemático de asistencias\", \"Utilizar sistema de control de asistencia del gimnasio\", \"La ausencia de estos datos impide evaluar riesgo por desadaptación\"]}', NULL, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(16, 'R43_ATLETA_NUEVO_INCOMPLETO', 'AUSENCIA_DATOS', 'simple', 'Atleta con evaluación inicial incompleta', NULL, NULL, NULL, NULL, 0, 'critica', 'Atleta con evaluación inicial incompleta. La ausencia de datos base impide realizar un análisis de riesgo preciso y establecer un programa seguro.', 'Completar batería completa de evaluaciones', '{\"seguimiento\": [\"Completar batería completa de evaluaciones (FMS + Postural + Historial)\", \"Establecer línea base antes de progresiones significativas de carga\", \"Priorizar esta acción en los próximos 3-5 días\"]}', NULL, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(17, 'RC1_FMS_BAJO_POSTURAL_MULTIPLE', 'COMPUESTA', 'compuesta', 'FMS bajo + múltiples problemas posturales', NULL, NULL, NULL, '{\"reglas\": [\"R2_FMS_BAJO\", \"R11_POSTURAL_MODERADO\"], \"operador\": \"AND\"}', 15, 'critica', 'Combinación crítica: FMS bajo ({score_fms}/21) + múltiples alteraciones posturales ({count_postural} problemas). Alto riesgo por interacción de deficiencias funcionales y estructurales.', 'Intervención correctiva integral urgente', '{\"tecnica\": [\"Reducción severa de cargas (no superior al 50-60% RM)\", \"Revisión exhaustiva de patrones técnicos\", \"Evitar ejercicios complejos hasta mejora\"], \"movilidad_estabilidad\": [\"Programa correctivo intensivo combinando FMS y corrección postural\", \"Sesiones específicas de trabajo correctivo 4-5x semana\", \"Evaluación funcional semanal para ajustar progresión\"], \"seguimiento\": [\"Reevaluación FMS y postural conjunta en 4 semanas\", \"Considerar evaluación biomecánica especializada\", \"Monitoreo estrecho de respuesta adaptativa\"]}', NULL, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(18, 'RC2_LESION_RECURRENTE_MISMA_ZONA', 'COMPUESTA', 'compuesta', 'Historial de lesión en misma zona + nueva lesión activa', NULL, NULL, NULL, '{\"condiciones\": {\"historial_zona_match\": true, \"lesion_activa\": true}}', 20, 'critica', 'Patrón de lesión recurrente en {zona}. La recidiva en la misma articulación/zona sugiere factores biomecánicos o técnicos no resueltos.', 'Evaluación especializada de causas subyacentes', '{\"tecnica\": [\"Análisis video exhaustivo de técnica en movimientos que involucren zona afectada\", \"Identificar compensaciones o vicios técnicos específicos\", \"Corrección técnica supervisada en cada sesión\"], \"carga_programacion\": [\"Evitar completamente ejercicios que involucren zona hasta resolución\", \"Diseño de progresión específica con énfasis en tolerancia de tejido\", \"Considerar variantes biomecánicas de ejercicios\"], \"recuperacion\": [\"Protocolo de rehabilitación específico por articulación\", \"Terapias físicas complementarias (fisioterapia, osteopatía)\", \"Trabajo preventivo permanente en esa zona\"], \"seguimiento\": [\"Evaluación médica o fisioterapéutica especializada obligatoria\", \"Análisis biomecánico en centro especializado si disponible\", \"Monitoreo continuo de síntomas incluso post-recuperación\"]}', NULL, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(19, 'RC3_FMS_EMPEORANDO_LESION', 'COMPUESTA', 'tendencia', 'FMS con tendencia negativa + lesión activa', NULL, NULL, NULL, '{\"condiciones\": {\"fms_tendencia\": \"negativa\", \"lesion_activa\": true}}', 18, 'alta', 'FMS mostrando empeoramiento en últimas evaluaciones mientras presenta lesión activa. Patrón de deterioro funcional progresivo.', 'Detener progresiones y priorizar recuperación funcional', '{\"carga_programacion\": [\"Suspender progresiones de carga inmediatamente\", \"Reducción de volumen e intensidad del 30-40%\", \"Enfoque exclusivo en recuperación y corrección\"], \"movilidad_estabilidad\": [\"Programa intensivo de recuperación funcional\", \"Trabajo correctivo diario\", \"Priorizar patrones FMS que mostraron mayor deterioro\"], \"seguimiento\": [\"Reevaluación FMS en 2-3 semanas para verificar reversión de tendencia\", \"Evaluación médica si deterioro continúa\"]}', NULL, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(20, 'RT1_FMS_DETERIORO_PROGRESIVO', 'TENDENCIA', 'tendencia', 'FMS mostrando deterioro progresivo en últimas 3 evaluaciones', NULL, NULL, NULL, '{\"campo\": \"puntuacion_total_fms\", \"evaluaciones\": 3, \"tendencia\": \"descendente\", \"umbral_cambio\": -2}', 12, 'alta', 'FMS mostrando deterioro progresivo: {score_anterior} → {score_actual} en últimas evaluaciones. Indica acumulación de fatiga o desarrollo de compensaciones.', 'Revisar carga de entrenamiento y descanso', '{\"carga_programacion\": [\"Análisis de periodización: revisar si hay sobrecarga acumulada\", \"Considerar semana de descarga inmediata\", \"Reducir volumen/intensidad 20-30% por 1-2 semanas\"], \"recuperacion\": [\"Aumentar días de recuperación activa\", \"Implementar técnicas de recuperación (masaje, crioterapia, estiramientos)\", \"Revisar calidad de sueño y nutrición\"], \"seguimiento\": [\"Reevaluar FMS después de período de descarga\", \"Si persiste deterioro, considerar evaluación médica\", \"Monitoreo de síntomas de sobreentrenamiento\"]}', NULL, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(21, 'RT2_ASISTENCIA_CAIDA_PROGRESIVA', 'TENDENCIA', 'tendencia', 'Asistencia mostrando caída progresiva últimos meses', NULL, NULL, NULL, '{\"campo\": \"porcentaje_asistencia\", \"periodo_meses\": 3, \"tendencia\": \"descendente\", \"umbral_cambio_porcentual\": -15}', 8, 'media', 'Asistencia mostrando caída progresiva: de {asistencia_anterior}% a {asistencia_actual}%. Patrón de desvinculación o pérdida de motivación.', 'Intervención para mejorar adherencia', '{\"seguimiento\": [\"Conversación con atleta para identificar causas (motivación, lesiones, tiempo)\", \"Ajustar programa según disponibilidad real\", \"Establecer metas de asistencia realistas y progresivas\", \"Considerar modificación de horarios o días de entrenamiento\"], \"carga_programacion\": [\"Adaptar volumen y frecuencia a asistencia real\", \"Simplificar programa si complejidad es barrera\", \"Priorizar sesiones esenciales\"]}', NULL, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(22, 'RT3_LESIONES_FRECUENTES_ULTIMOS_6_MESES', 'TENDENCIA', 'tendencia', 'Múltiples lesiones en últimos 6 meses (≥3)', NULL, NULL, NULL, '{\"periodo_meses\": 6, \"min_lesiones\": 3}', 15, 'alta', 'Patrón de lesiones frecuentes en últimos 6 meses ({count} lesiones). Sugiere factores sistémicos no resueltos: sobrecarga, técnica deficiente, o condiciones predisponentes.', 'Análisis profundo de causas sistémicas', '{\"tecnica\": [\"Análisis técnico exhaustivo en todos los movimientos base\", \"Identificar compensaciones o patrones incorrectos recurrentes\", \"Considerar coaching técnico reforzado\"], \"carga_programacion\": [\"Revisión completa de periodización y progresiones\", \"Evaluar si incrementos de carga fueron demasiado agresivos\", \"Implementar progresión más conservadora (5-7% semanal máximo)\", \"Aumentar días de recuperación en microciclo\"], \"movilidad_estabilidad\": [\"Programa preventivo permanente de movilidad y estabilidad\", \"Trabajo correctivo post-calentamiento en cada sesión\", \"Énfasis en fortalecimiento de áreas débiles identificadas\"], \"recuperacion\": [\"Protocolos de recuperación sistemáticos\", \"Revisar estrategias nutricionales y de descanso\", \"Considerar suplementación específica si hay deficiencias\"], \"seguimiento\": [\"Evaluación médica general para descartar condiciones subyacentes\", \"Análisis de factores externos (estrés, sueño, nutrición)\", \"Monitoreo mensual de tendencia\"]}', NULL, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(23, 'RP1_ALTA_CARGA_ESTABILIDAD_BAJA', 'PERFIL', 'perfil', 'Atleta de alta carga + baja estabilidad (FMS ≤15)', NULL, NULL, NULL, '{\"perfil\": \"ALTA_CARGA\", \"fms_max\": 15}', 16, 'alta', 'Perfil de riesgo: Atleta de alta carga con deficiencias de estabilidad (FMS {score}/21). Combinación peligrosa que predispone a lesiones por sobreuso y compensaciones.', 'Reducir carga y reforzar estabilidad', '{\"carga_programacion\": [\"Reducción temporal de volumen de entrenamiento (20-30%)\", \"Priorizar calidad sobre cantidad en sesiones\", \"Evitar competencias no prioritarias en próximo mes\", \"Redistribuir carga semanal con más días de recuperación\"], \"movilidad_estabilidad\": [\"Bloque intensivo de estabilidad 3-4x semana\", \"Trabajo de core y estabilidad escapular diario\", \"Ejercicios unilaterales para corregir asimetrías\"], \"recuperacion\": [\"Priorizar recuperación en este período\", \"Técnicas de recuperación activa obligatorias\", \"Monitoreo de signos de sobreentrenamiento\"], \"seguimiento\": [\"Reevaluar FMS en 4-6 semanas\", \"Retomar progresión de carga solo con FMS ≥16\", \"Considerar reducción de frecuencia competitiva\"]}', 1, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(24, 'RP2_INTERMITENTE_HISTORIAL_LESIONES', 'PERFIL', 'perfil', 'Atleta intermitente + historial de lesiones', NULL, NULL, NULL, '{\"perfil\": \"INTERMITENTE\", \"historial_lesiones_min\": 2}', 14, 'alta', 'Perfil de riesgo: Atleta intermitente con historial de lesiones. Patrón de desadaptación crónica que incrementa vulnerabilidad.', 'Programa adaptado a disponibilidad real', '{\"carga_programacion\": [\"Diseño de programa flexible adaptado a asistencia irregular\", \"Sesiones completas autocontenidas (no dependientes de sesión anterior)\", \"Reducción de complejidad técnica\", \"Calentamiento extendido obligatorio (15-20 min) en cada retorno\", \"Cargas conservadoras: no superar 70% en primeras 2 sesiones tras ausencia >5 días\"], \"seguimiento\": [\"Reunión para establecer expectativas realistas\", \"Plan de retorno progresivo tras ausencias prolongadas\", \"Identificar y resolver barreras de asistencia\"]}', 2, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52'),
(25, 'RP3_COMPETIDOR_PROXIMO_EVENTO', 'PERFIL', 'perfil', 'Competidor con evento próximo (<30 días) y riesgo medio-alto', NULL, NULL, NULL, '{\"perfil\": \"COMPETIDOR\", \"dias_competencia_max\": 30, \"riesgo_min\": \"medio\"}', 10, 'alta', 'Competidor con evento próximo ({dias_competencia} días) presentando riesgo {nivel_riesgo}. Requiere manejo cuidadoso del balance rendimiento-seguridad.', 'Gestión de riesgo pre-competencia', '{\"carga_programacion\": [\"Reducir volumen pero mantener intensidad específica\", \"Evitar introducir ejercicios nuevos o variantes técnicas\", \"Priorizar técnica y velocidad sobre carga absoluta\", \"Planificar taper adecuado (7-10 días pre-competencia)\"], \"recuperacion\": [\"Maximizar recuperación: sueño, nutrición, hidratación\", \"Técnicas de recuperación activa diarias\", \"Manejo de estrés y ansiedad pre-competitiva\"], \"seguimiento\": [\"Monitoreo diario de estado de readiness\", \"Ajustes de carga según respuesta individual\", \"Evaluación post-competencia obligatoria\", \"Plan de descarga post-evento\"]}', 5, 1, '2025-11-27 18:22:52', '2025-11-27 18:22:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kb_umbrales`
--

CREATE TABLE `kb_umbrales` (
  `id` int(11) NOT NULL,
  `nivel` varchar(20) NOT NULL,
  `min_score` int(11) NOT NULL,
  `max_score` int(11) NOT NULL,
  `color_badge` varchar(50) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `kb_umbrales`
--

INSERT INTO `kb_umbrales` (`id`, `nivel`, `min_score`, `max_score`, `color_badge`, `descripcion`, `activo`) VALUES
(1, 'bajo', 0, 33, 'success', 'Riesgo bajo de lesión', 1),
(2, 'medio', 34, 66, 'warning', 'Riesgo moderado de lesión', 1),
(3, 'alto', 67, 100, 'danger', 'Riesgo alto de lesión', 1);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_reglas_activas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_reglas_activas` (
`id` int(11)
,`codigo` varchar(100)
,`modulo` enum('FMS','POSTURAL','LESIONES','ASISTENCIA','COMPUESTA','TENDENCIA','PERFIL','AUSENCIA_DATOS')
,`tipo_regla` enum('simple','compuesta','tendencia','perfil')
,`descripcion` varchar(255)
,`campo` varchar(100)
,`operador` varchar(20)
,`valor` varchar(100)
,`condiciones_json` longtext
,`riesgo_puntos` int(11)
,`prioridad` enum('baja','media','alta','critica')
,`mensaje_factor` text
,`recomendacion_base` text
,`recomendaciones_detalladas_json` longtext
,`perfil_codigo` varchar(50)
,`perfil_nombre` varchar(100)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `v_reglas_activas`
--
DROP TABLE IF EXISTS `v_reglas_activas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_reglas_activas`  AS SELECT `r`.`id` AS `id`, `r`.`codigo` AS `codigo`, `r`.`modulo` AS `modulo`, `r`.`tipo_regla` AS `tipo_regla`, `r`.`descripcion` AS `descripcion`, `r`.`campo` AS `campo`, `r`.`operador` AS `operador`, `r`.`valor` AS `valor`, `r`.`condiciones_json` AS `condiciones_json`, `r`.`riesgo_puntos` AS `riesgo_puntos`, `r`.`prioridad` AS `prioridad`, `r`.`mensaje_factor` AS `mensaje_factor`, `r`.`recomendacion_base` AS `recomendacion_base`, `r`.`recomendaciones_detalladas_json` AS `recomendaciones_detalladas_json`, `p`.`codigo` AS `perfil_codigo`, `p`.`nombre` AS `perfil_nombre` FROM (`kb_reglas` `r` left join `kb_perfiles` `p` on(`r`.`id_perfil` = `p`.`id`)) WHERE `r`.`activo` = 1 ORDER BY `r`.`modulo` ASC, `r`.`prioridad` DESC, `r`.`riesgo_puntos` DESC ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `kb_metadata`
--
ALTER TABLE `kb_metadata`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_version` (`version`);

--
-- Indices de la tabla `kb_perfiles`
--
ALTER TABLE `kb_perfiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_codigo` (`codigo`);

--
-- Indices de la tabla `kb_ponderaciones`
--
ALTER TABLE `kb_ponderaciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_modulo` (`modulo`);

--
-- Indices de la tabla `kb_reglas`
--
ALTER TABLE `kb_reglas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_codigo` (`codigo`),
  ADD KEY `idx_modulo` (`modulo`),
  ADD KEY `idx_tipo_regla` (`tipo_regla`),
  ADD KEY `idx_activo` (`activo`),
  ADD KEY `id_perfil` (`id_perfil`),
  ADD KEY `idx_kb_reglas_modulo_activo` (`modulo`,`activo`),
  ADD KEY `idx_kb_reglas_tipo_activo` (`tipo_regla`,`activo`),
  ADD KEY `idx_kb_reglas_prioridad` (`prioridad`);

--
-- Indices de la tabla `kb_umbrales`
--
ALTER TABLE `kb_umbrales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_nivel` (`nivel`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `kb_metadata`
--
ALTER TABLE `kb_metadata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `kb_perfiles`
--
ALTER TABLE `kb_perfiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `kb_ponderaciones`
--
ALTER TABLE `kb_ponderaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `kb_reglas`
--
ALTER TABLE `kb_reglas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `kb_umbrales`
--
ALTER TABLE `kb_umbrales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `kb_reglas`
--
ALTER TABLE `kb_reglas`
  ADD CONSTRAINT `kb_reglas_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `kb_perfiles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
