<?php

namespace Gymsys\Core\IA;

/**
 * Base de Conocimiento del Sistema Experto de Análisis de Atletas
 * 
 * Contiene las reglas clínicas y de experto que el motor de inferencia
 * utiliza para identificar factores de riesgo y generar recomendaciones.
 * 
 * Arquitectura:
 * - Motor de Inferencia: AnalizadorAtleta.php
 * - Base de Conocimiento: BaseConocimientoAtleta.php (este archivo)
 * - Hechos: Datos del atleta (tests, lesiones, asistencias)
 * 
 * @author GymSys Development Team
 * @version 2.0
 */
class BaseConocimientoAtleta
{
    /**
     * Ponderaciones base para cada módulo del análisis
     * Total: 100 puntos distribuidos según importancia clínica
     * 
     * @return array Pesos por módulo
     */
    public static function obtenerPonderaciones(): array
    {
        return [
            'fms' => 30,        // Patrones de movimiento funcional
            'postural' => 30,   // Alteraciones biomecánicas
            'lesiones' => 30,   // Historial y estado actual de lesiones
            'asistencia' => 10  // Adherencia y regularidad de entrenamiento
        ];
    }

    /**
     * Umbrales de clasificación de riesgo
     * 
     * @return array Rangos de score para cada nivel
     */
    public static function obtenerUmbralesRiesgo(): array
    {
        return [
            'bajo' => ['min' => 0, 'max' => 33],
            'medio' => ['min' => 34, 'max' => 66],
            'alto' => ['min' => 67, 'max' => 100]
        ];
    }

    /**
     * Reglas de inferencia para el Test FMS
     * 
     * @return array Reglas estructuradas
     */
    public static function obtenerReglasFMS(): array
    {
        return [
            [
                'id' => 'R1_FMS_CRITICO',
                'descripcion' => 'Puntuación FMS crítica (≤12): Alto riesgo de lesión por patrones fundamentales comprometidos',
                'condicion' => [
                    'campo' => 'puntuacion_total',
                    'operador' => '<=',
                    'valor' => 12
                ],
                'riesgo_puntos' => 30,
                'factor_mensaje' => 'Puntuación FMS crítica ({score}/21). Los patrones de movimiento básicos requieren atención inmediata.',
                'recomendaciones' => [
                    'Implementar programa de movilidad y estabilidad antes de progresiones de carga.',
                    'Reevaluar FMS en 4 semanas tras intervención correctiva.'
                ],
                'prioridad' => 'alta'
            ],
            [
                'id' => 'R2_FMS_BAJO',
                'descripcion' => 'Puntuación FMS baja (13-14): Riesgo alto, requiere corrección inmediata',
                'condicion' => [
                    'campo' => 'puntuacion_total',
                    'operador' => 'BETWEEN',
                    'valor' => [13, 14]
                ],
                'riesgo_puntos' => 20,
                'factor_mensaje' => 'Puntuación FMS crítica ({score}/21). Los patrones de movimiento básicos requieren atención inmediata.',
                'recomendaciones' => [
                    'Implementar programa de movilidad y estabilidad antes de progresiones de carga.',
                    'Reevaluar FMS en 4 semanas tras intervención correctiva.'
                ],
                'prioridad' => 'alta'
            ],
            [
                'id' => 'R3_FMS_MODERADO',
                'descripcion' => 'Puntuación FMS moderada (15-17): Compensaciones presentes',
                'condicion' => [
                    'campo' => 'puntuacion_total',
                    'operador' => 'BETWEEN',
                    'valor' => [15, 17]
                ],
                'riesgo_puntos' => 10,
                'factor_mensaje' => 'Puntuación FMS moderada ({score}/21). Revisar patrones de movimiento y corregir compensaciones.',
                'recomendaciones' => [
                    'Trabajo correctivo en patrones deficientes identificados.'
                ],
                'prioridad' => 'media'
            ]
        ];
    }

    /**
     * Reglas de inferencia para Test Postural
     * 
     * @return array Reglas estructuradas
     */
    public static function obtenerReglasPostural(): array
    {
        return [
            [
                'id' => 'R10_POSTURAL_SEVERO',
                'descripcion' => 'Múltiples alteraciones posturales severas (≥5)',
                'condicion' => [
                    'campo' => 'problemas_moderados_severos',
                    'operador' => '>=',
                    'valor' => 5
                ],
                'riesgo_puntos' => 30,
                'factor_mensaje' => 'Múltiples alteraciones posturales severas detectadas ({count} problemas). Evaluación biomecánica urgente recomendada.',
                'recomendaciones' => [
                    'Evaluación biomecánica especializada urgente.',
                    'Trabajo correctivo intensivo antes de retomar cargas altas.'
                ],
                'prioridad' => 'alta'
            ],
            [
                'id' => 'R11_POSTURAL_MODERADO',
                'descripcion' => 'Varias alteraciones posturales (3-4)',
                'condicion' => [
                    'campo' => 'problemas_moderados_severos',
                    'operador' => 'BETWEEN',
                    'valor' => [3, 4]
                ],
                'riesgo_puntos' => 20,
                'factor_mensaje' => 'Se detectan varias alteraciones posturales moderadas o severas ({count} problemas). Trabajo correctivo necesario.',
                'recomendaciones' => [
                    'Trabajo postural enfocado en áreas específicas identificadas.'
                ],
                'prioridad' => 'media'
            ],
            [
                'id' => 'R12_POSTURAL_LEVE',
                'descripcion' => 'Pocas alteraciones posturales (1-2)',
                'condicion' => [
                    'campo' => 'problemas_moderados_severos',
                    'operador' => 'BETWEEN',
                    'valor' => [1, 2]
                ],
                'riesgo_puntos' => 10,
                'factor_mensaje' => null, // No se reporta como factor si solo es leve
                'recomendaciones' => [],
                'prioridad' => 'baja'
            ]
        ];
    }

    /**
     * Reglas de inferencia para Lesiones
     * 
     * @return array Reglas estructuradas
     */
    public static function obtenerReglasLesiones(): array
    {
        return [
            [
                'id' => 'R20_LESIONES_MULTIPLES_ACTIVAS',
                'descripcion' => 'Múltiples lesiones activas simultáneas',
                'condicion' => [
                    'campo' => 'num_lesiones_activas',
                    'operador' => '>',
                    'valor' => 1
                ],
                'riesgo_base' => 'CALCULADO', // Se calcula según gravedad
                'factor_mensaje' => 'Múltiples lesiones activas ({count}). Alto riesgo de compensaciones y nuevas lesiones.',
                'recomendaciones' => [
                    'Evitar ejercicios que comprometan zona(s) lesionada(s). Consultar con fisioterapeuta.',
                    'Implementar ejercicios de fortalecimiento progresivo para área afectada.',
                    'Revisar patrón de entrenamiento para identificar causas subyacentes.'
                ],
                'prioridad' => 'alta'
            ],
            [
                'id' => 'R21_LESION_ACTIVA_UNICA',
                'descripcion' => 'Una lesión activa',
                'condicion' => [
                    'campo' => 'num_lesiones_activas',
                    'operador' => '==',
                    'valor' => 1
                ],
                'riesgo_base' => 'CALCULADO',
                'factor_mensaje' => 'Lesión activa ({gravedad}) en {zona}. Limitar carga en área afectada.',
                'recomendaciones' => [
                    'Evitar ejercicios que comprometan zona lesionada.',
                    'Implementar ejercicios de fortalecimiento progresivo.'
                ],
                'prioridad' => 'alta'
            ],
            [
                'id' => 'R22_LESION_RECIENTE',
                'descripcion' => 'Lesión recuperada en últimos 30 días',
                'condicion' => [
                    'campo' => 'hay_lesion_reciente',
                    'operador' => '==',
                    'valor' => true
                ],
                'riesgo_puntos' => 10,
                'factor_mensaje' => 'Lesión reciente recuperada. Ajustar progresivamente la carga para prevenir recaídas.',
                'recomendaciones' => [
                    'Progresión gradual de carga en zona previamente lesionada.',
                    'Monitoreo cercano de síntomas durante 4-6 semanas.'
                ],
                'prioridad' => 'media'
            ],
            [
                'id' => 'R23_HISTORIAL_LESIONES',
                'descripcion' => 'Historial de múltiples lesiones',
                'condicion' => [
                    'campo' => 'total_lesiones',
                    'operador' => '>=',
                    'valor' => 3
                ],
                'riesgo_puntos' => 0, // No suma puntos, solo genera factor
                'factor_mensaje' => 'Historial de múltiples lesiones ({count} registradas). Patrón que requiere análisis de causas subyacentes.',
                'recomendaciones' => [
                    'Análisis biomecánico completo para identificar patrones de lesión.',
                    'Evaluación de programa de entrenamiento y técnica.'
                ],
                'prioridad' => 'media'
            ]
        ];
    }

    /**
     * Reglas de inferencia para Asistencias
     * 
     * @return array Reglas estructuradas
     */
    public static function obtenerReglasAsistencia(): array
    {
        return [
            [
                'id' => 'R30_ASISTENCIA_MUY_BAJA',
                'descripcion' => 'Asistencia muy irregular (<50%)',
                'condicion' => [
                    'campo' => 'porcentaje_asistencia',
                    'operador' => '<',
                    'valor' => 50
                ],
                'riesgo_puntos' => 10,
                'factor_mensaje' => 'Asistencia muy irregular ({porcentaje}% últimos 30 días). La inconsistencia aumenta el riesgo de lesión.',
                'recomendaciones' => [
                    'Establecer plan de adherencia. La inconsistencia es factor de riesgo principal.',
                    'Revisar barreras que impiden asistencia regular.'
                ],
                'prioridad' => 'alta'
            ],
            [
                'id' => 'R31_ASISTENCIA_SUBOPTIMA',
                'descripcion' => 'Asistencia por debajo de lo óptimo (50-79%)',
                'condicion' => [
                    'campo' => 'porcentaje_asistencia',
                    'operador' => 'BETWEEN',
                    'valor' => [50, 79]
                ],
                'riesgo_puntos' => 5,
                'factor_mensaje' => 'Asistencia por debajo de lo óptimo ({porcentaje}%). Revisar adherencia al plan de entrenamiento.',
                'recomendaciones' => [
                    'Mejorar regularidad de asistencia para optimizar adaptaciones.'
                ],
                'prioridad' => 'media'
            ]
        ];
    }

    /**
     * Reglas de datos faltantes
     * 
     * @return array Reglas para manejar ausencia de datos
     */
    public static function obtenerReglasAusenciaDatos(): array
    {
        return [
            [
                'id' => 'R40_SIN_FMS',
                'modulo' => 'fms',
                'factor_mensaje' => 'No se encontró un Test FMS reciente. Se recomienda realizar la evaluación funcional de movimiento.',
                'recomendaciones' => [
                    'Realizar Test FMS para obtener línea base de patrones de movimiento.',
                    'Evaluación FMS recomendada antes de progresiones de carga significativas.'
                ],
                'prioridad' => 'alta'
            ],
            [
                'id' => 'R41_SIN_POSTURAL',
                'modulo' => 'postural',
                'factor_mensaje' => 'No se encontró una evaluación postural reciente. La alineación estructural no ha sido evaluada.',
                'recomendaciones' => [
                    'Realizar evaluación postural completa.',
                    'Análisis visual estático y dinámico recomendado.'
                ],
                'prioridad' => 'media'
            ],
            [
                'id' => 'R42_SIN_ASISTENCIAS',
                'modulo' => 'asistencia',
                'factor_mensaje' => 'No hay registros de asistencia suficientes en los últimos 30 días. La estimación de riesgo puede no ser precisa.',
                'recomendaciones' => [
                    'Mantener registro de asistencias para evaluación más precisa del riesgo.'
                ],
                'prioridad' => 'baja'
            ],
            [
                'id' => 'R43_ATLETA_NUEVO',
                'modulo' => 'general',
                'factor_mensaje' => 'Atleta con evaluación incompleta. Se requiere batería completa de tests para análisis preciso.',
                'recomendaciones' => [
                    'Completar batería de evaluaciones: FMS, Postural, y seguimiento de asistencias.',
                    'Establecer línea base antes de progresiones de entrenamiento.'
                ],
                'prioridad' => 'alta'
            ]
        ];
    }

    /**
     * Ponderación de gravedad de lesiones
     * 
     * @return array Puntos por tipo de gravedad
     */
    public static function obtenerPonderacionGravedadLesiones(): array
    {
        return [
            'leve' => 5,
            'moderada' => 8,
            'severa' => 10,
            'grave' => 10
        ];
    }

    /**
     * Recomendaciones generales por nivel de riesgo
     * 
     * @return array Recomendaciones según clasificación
     */
    public static function obtenerRecomendacionesPorNivel(): array
    {
        return [
            'alto' => [
                '🔴 PRIORIDAD ALTA: Reducir intensidad del entrenamiento y enfocarse en trabajo correctivo.'
            ],
            'medio' => [
                '🟡 Monitoreo cercano recomendado. Ajustar carga según tolerancia individual.'
            ],
            'bajo' => [
                'Mantener programa de entrenamiento actual con progresiones controladas.',
                'Reevaluación periódica cada 8-12 semanas para seguimiento preventivo.'
            ]
        ];
    }

    /**
     * Mapeo de campos posturales a descripciones
     * 
     * @return array Mapa campo => descripción
     */
    public static function obtenerMapaProblemasPosturales(): array
    {
        return [
            'cifosis_dorsal' => 'cifosis dorsal',
            'lordosis_lumbar' => 'lordosis lumbar',
            'escoliosis' => 'escoliosis',
            'inclinacion_pelvis' => 'alineación pélvica',
            'valgo_rodilla' => 'valgo de rodilla',
            'varo_rodilla' => 'varo de rodilla',
            'rotacion_hombros' => 'rotación de hombros',
            'desnivel_escapulas' => 'alineación escapular'
        ];
    }

    /**
     * Mapeo de pruebas FMS a nombres descriptivos
     * 
     * @return array Mapa campo => nombre
     */
    public static function obtenerMapaPruebasFMS(): array
    {
        return [
            'sentadilla_profunda' => 'sentadilla profunda',
            'paso_valla' => 'paso de valla',
            'estocada_en_linea' => 'estocada en línea',
            'movilidad_hombro' => 'movilidad de hombro',
            'elevacion_pierna_recta' => 'elevación de pierna',
            'estabilidad_tronco' => 'estabilidad de tronco',
            'estabilidad_rotacional' => 'estabilidad rotacional'
        ];
    }
}
