<?php

namespace Gymsys\Core\IA;


class BaseConocimientoAtleta
{
    
    public static function obtenerPonderaciones(): array
    {
        return [
            'fms' => 30,       
            'postural' => 30,   
            'lesiones' => 30,  
            'asistencia' => 10
        ];
    }

    
    public static function obtenerUmbralesRiesgo(): array
    {
        return [
            'bajo' => ['min' => 0, 'max' => 40],
            'medio' => ['min' => 41, 'max' => 60],
            'alto' => ['min' => 61, 'max' => 100]
        ];
    }

    
    public static function obtenerReglasFMS(): array
    {
        return [
            [
                'id' => 'R1_FMS_CRITICO',
                'categoria' => 'tecnica',
                'descripcion' => 'Puntuación FMS crítica (≤12): Alto riesgo de lesión por patrones fundamentales comprometidos',
                'condicion' => [
                    'campo' => 'puntuacion_total',
                    'operador' => '<=',
                    'valor' => 12
                ],
                'riesgo_puntos' => 30,
                'factor_mensaje' => 'La puntuación FMS de {score}/21 indica patrones de movimiento fundamentales severamente comprometidos. Esto sugiere que el atleta está utilizando estrategias compensatorias importantes durante los movimientos básicos, lo que incrementa exponencialmente el riesgo de lesión cuando se añade carga externa o velocidad al gesto técnico.',
                'recomendaciones' => [
                    '🔧 Detener temporalmente progresiones de carga máxima y enfocarse en restaurar patrones fundamentales de movimiento mediante trabajo correctivo diario (15-20 minutos por sesión).',
                    '🧘 Implementar protocolo de movilidad articular y control motor en cadenas cinéticas deficientes, priorizando movimientos lentos y controlados antes de añadir complejidad o resistencia.',
                    '📅 Programar reevaluación FMS completa en 4 semanas para verificar respuesta a las intervenciones correctivas y ajustar el plan de progresión según los resultados obtenidos.'
                ],
                'prioridad' => 'alta'
            ],
            [
                'id' => 'R2_FMS_BAJO',
                'categoria' => 'movilidad',
                'descripcion' => 'Puntuación FMS baja (13-14): Riesgo alto, requiere corrección inmediata',
                'condicion' => [
                    'campo' => 'puntuacion_total',
                    'operador' => 'BETWEEN',
                    'valor' => [13, 14]
                ],
                'riesgo_puntos' => 20,
                'factor_mensaje' => 'Con una puntuación FMS de {score}/21, se observan limitaciones significativas en los patrones de movimiento. Las compensaciones detectadas sugieren restricciones en movilidad articular o déficits en el control neuromuscular, lo que predispone a patrones de carga anormales durante ejercicios complejos como sentadillas profundas, peso muerto o movimientos olímpicos.',
                'recomendaciones' => [
                    '🧘 Priorizar trabajo de movilidad activa en rangos completos de movimiento, con énfasis en cadera, columna torácica y tobillo (3-4 sesiones por semana, 10-15 minutos).',
                    '🏋️ Limitar temporalmente ejercicios con cargas superiores al 70% del 1RM hasta que se corrijan los patrones deficientes. Utilizar variantes con menor demanda técnica o mayor feedback propioceptivo.',
                    '📅 Establecer protocolo de reevaluación cada 3-4 semanas para monitorear progreso y autorizar incrementos graduales de intensidad una vez alcanzados scores superiores a 15 puntos.'
                ],
                'prioridad' => 'alta'
            ],
            [
                'id' => 'R3_FMS_MODERADO',
                'categoria' => 'planificacion',
                'descripcion' => 'Puntuación FMS moderada (15-17): Compensaciones presentes',
                'condicion' => [
                    'campo' => 'puntuacion_total',
                    'operador' => 'BETWEEN',
                    'valor' => [15, 17]
                ],
                'riesgo_puntos' => 10,
                'factor_mensaje' => 'Puntuación FMS de {score}/21 refleja patrones de movimiento funcionalmente aceptables pero con compensaciones menores identificadas. Aunque esto permite entrenar con cargas moderadas-altas, es importante abordar estas limitaciones para prevenir desarrollo de patrones disfuncionales crónicos.',
                'recomendaciones' => [
                    '🔧 Incluir trabajo correctivo específico en patrones que obtuvieron scores de 1 o asimetrías bilaterales, dedicando 8-10 minutos al inicio de cada sesión de entrenamiento.',
                    '🧘 Mantener rutina de movilidad preventiva 2-3 veces por semana, enfocándose en las pruebas con menor puntuación para optimizar los rangos de movimiento disponibles.',
                    '📅 Reevaluar FMS cada 8-10 semanas como parte del seguimiento preventivo, con objetivo de alcanzar scores ≥18 que indiquen patrones óptimos.'
                ],
                'prioridad' => 'media'
            ]
        ];
    }

    
    public static function obtenerReglasPostural(): array
    {
        return [
            [
                'id' => 'R10_POSTURAL_SEVERO',
                'categoria' => 'lesion',
                'descripcion' => 'Múltiples alteraciones posturales severas (≥5)',
                'condicion' => [
                    'campo' => 'problemas_moderados_severos',
                    'operador' => '>=',
                    'valor' => 5
                ],
                'riesgo_puntos' => 30,
                'factor_mensaje' => 'La evaluación postural revela {count} alteraciones moderadas o severas en diferentes segmentos corporales. Este patrón de múltiples desalineaciones estructurales genera cadenas de compensación biomecánicas complejas que redistribuyen las cargas de manera no fisiológica, incrementando drásticamente el riesgo de lesión por sobreuso en articulaciones y tejidos blandos.',
                'recomendaciones' => [
                    '🤕 PRIORITARIO: Derivar a evaluación biomecánica especializada con enfoque en cadenas cinéticas globales. Se requiere análisis vídeo y valoración funcional avanzada para determinar jerarquía de intervención.',
                    '🧘 Suspender temporalmente ejercicios de alta demanda técnica (levantamientos olímpicos, pliometría intensa) y enfocarse en trabajo correctivo intensivo con énfasis en control postural estático y dinámico (sesiones diarias de 20-25 minutos).',
                    '🔧 Implementar programa de liberación miofascial, fortalecimiento de musculatura estabilizadora profunda y reeducación de patrones motores durante un ciclo mínimo de 6-8 semanas antes de retomar cargas competitivas.',
                    '📅 Establecer reevaluaciones posturales quincenales para monitorear respuesta a intervenciones y ajustar estrategias según evolución clínica.'
                ],
                'prioridad' => 'alta'
            ],
            [
                'id' => 'R11_POSTURAL_MODERADO',
                'categoria' => 'tecnica',
                'descripcion' => 'Varias alteraciones posturales (3-4)',
                'condicion' => [
                    'campo' => 'problemas_moderados_severos',
                    'operador' => 'BETWEEN',
                    'valor' => [3, 4]
                ],
                'riesgo_puntos' => 20,
                'factor_mensaje' => 'Se identifican {count} alteraciones posturales que, aunque permiten el entrenamiento, requieren atención correctiva para prevenir cronificación de patrones compensatorios. Estas desalineaciones pueden generar distribuciones asímetricas de carga durante ejercicios bilaterales y restricciones en rangos de movimiento óptimos.',
                'recomendaciones' => [
                    '🧘 Incorporar trabajo postural específico al inicio de cada sesión de entrenamiento (10-12 minutos), enfocándose en las zonas identificadas con alteraciones moderadas o severas.',
                    '🔧 Ajustar selección de ejercicios para minimizar estrés en segmentos comprometidos: por ejemplo, si hay cifosis torácica severa, limitar press de banca horizontal y enfatizar variantes con inclinación o trabajo unilateral.',
                    '🏋️ Incluir ejercicios de activación selectiva para musculatura inhibida y estiramientos de cadenas musculares acortadas, siguiendo principios de inhibición recíproca (3-4 sesiones por semana).',
                    '📅 Programar reevaluación postural completa cada 6-8 semanas para verificar efectividad de las intervenciones correctivas implementadas.'
                ],
                'prioridad' => 'media'
            ],
            [
                'id' => 'R12_POSTURAL_LEVE',
                'categoria' => 'planificacion',
                'descripcion' => 'Pocas alteraciones posturales (1-2)',
                'condicion' => [
                    'campo' => 'problemas_moderados_severos',
                    'operador' => 'BETWEEN',
                    'valor' => [1, 2]
                ],
                'riesgo_puntos' => 10,
                'factor_mensaje' => 'Se observan alteraciones posturales menores que no representan contraindicación para el entrenamiento actual, pero conviene abordarlas preventivamente para evitar su progresión.',
                'recomendaciones' => [
                    '🧘 Mantener rutina preventiva de movilidad y control postural 2-3 veces por semana, con atención especial a las zonas identificadas con alteraciones.',
                    '📅 Reevaluar postura cada 10-12 semanas como parte del seguimiento preventivo estándar.'
                ],
                'prioridad' => 'baja'
            ]
        ];
    }

    
    public static function obtenerReglasLesiones(): array
    {
        return [
            [
                'id' => 'R20_LESIONES_MULTIPLES_ACTIVAS',
                'categoria' => 'lesion',
                'descripcion' => 'Múltiples lesiones activas simultáneas',
                'condicion' => [
                    'campo' => 'num_lesiones_activas',
                    'operador' => '>',
                    'valor' => 1
                ],
                'riesgo_base' => 'CALCULADO',
                'factor_mensaje' => 'Se detectan {count} lesiones activas simultáneamente. Esta situación multilesional incrementa drásticamente el riesgo de compensaciones biomecánicas, donde el cuerpo redistribuye las cargas hacia estructuras no lesionadas que pueden no estar preparadas para asumir esa demanda adicional. Esto crea un ciclo de riesgo donde nuevas lesiones pueden desarrollarse en zonas compensatorias.',
                'recomendaciones' => [
                    '🤕 URGENTE: Reducir volumen e intensidad de entrenamiento de forma significativa. Evitar completamente ejercicios multiarticulares complejos que comprometan cualquiera de las zonas lesionadas.',
                    '💡 Consultar con fisioterapeuta o especialista en medicina deportiva para establecer protocolo de recuperación coordinado que aborde todas las lesiones de forma integrada, no aislada.',
                    '🏋️ Implementar programa de fortalecimiento progresivo y altamente controlado, iniciando con ejercicios isoláticos de baja carga y progresando solo cuando haya evidencia clínica de mejora en todas las áreas afectadas.',
                    '📊 Realizar análisis de carga de entrenamiento de las últimas 8-12 semanas para identificar picos de volumen o intensidad que puedan explicar el patrón multilesional y ajustar planificación futura.',
                    '📅 Reevaluación clínica semanal obligatoria hasta resolución de al menos el 50% de las lesiones activas.'
                ],
                'prioridad' => 'alta'
            ],
            [
                'id' => 'R21_LESION_ACTIVA_UNICA',
                'categoria' => 'lesion',
                'descripcion' => 'Una lesión activa',
                'condicion' => [
                    'campo' => 'num_lesiones_activas',
                    'operador' => '==',
                    'valor' => 1
                ],
                'riesgo_base' => 'CALCULADO',
                'factor_mensaje' => 'Lesión activa de gravedad {gravedad} en {zona}. La presencia de una lesión activa requiere gestión cuidadosa de la carga mecánica sobre la estructura afectada, así como monitoreo de posibles compensaciones que el atleta pueda desarrollar para evitar el dolor o la limitación funcional.',
                'recomendaciones' => [
                    '🤕 Evitar ejercicios que generen carga directa o estrés mecánico sobre la zona lesionada. En lesiones de miembro inferior, limitar impactos, cambios de dirección rápidos y rangos extremos de movimiento. En lesiones de miembro superior, reducir cargas en press, tracciones o movimientos overhead según la zona específica.',
                    '🏋️ Mantener trabajo de zonas no afectadas con intensidad moderada para preservar condición física general, pero con especial atención a no generar fatiga sistémica excesiva que comprometa el proceso de recuperación tisular.',
                    '📊 Implementar ejercicios de fortalecimiento progresivo en la zona lesionada solo cuando haya ausencia de dolor en reposo y en movimientos básicos. Iniciar con contracciones isométricas, progresar a concéntricas controladas y finalmente a excéntricas con incrementos graduales de resistencia.',
                    '📅 Programar reevaluación clínica cada 7-10 días para ajustar el plan de retorno progresivo al entrenamiento según la evolución de los síntomas y la capacidad funcional.'
                ],
                'prioridad' => 'alta'
            ],
            [
                'id' => 'R22_LESION_RECIENTE',
                'categoria' => 'planificacion',
                'descripcion' => 'Lesión recuperada en últimos 30 días',
                'condicion' => [
                    'campo' => 'hay_lesion_reciente',
                    'operador' => '==',
                    'valor' => true
                ],
                'riesgo_puntos' => 10,
                'factor_mensaje' => 'Lesión reciente en fase de recuperación. Aunque la sintomatología aguda puede haber remitido, el tejido lesionado requiere tiempo adicional para completar su proceso de remodelación y alcanzar propiedades biomecánicas óptimas. Un retorno demasiado rápido a cargas elevadas es el principal factor de riesgo para recidiva lesional.',
                'recomendaciones' => [
                    '📈 Implementar progresión gradual y sistemática de carga en la zona previamente lesionada: semana 1-2 post-recuperación (30-40% intensidad habitual), semana 3-4 (50-70%), semana 5-6 (80-90%), retorno completo solo si no hay síntomas ni signos clínicos de alerta.',
                    '🔍 Monitoreo cercano de respuesta tisular: evaluar presencia de dolor tardío (24-48h post-entrenamiento), inflamación, rigidez matutina o pérdida de rango de movimiento como indicadores de sobrecarga temprana.',
                    '🏋️ Mantener trabajo específico de fortalecimiento y control motor de la zona afectada como parte permanente del calentamiento (8-10 minutos) durante al menos 6-8 semanas post-recuperación.',
                    '📅 Reevaluación funcional completa de la zona lesionada a las 4-6 semanas del retorno para confirmar recuperación de capacidades físicas y autorización de cargas máximas.'
                ],
                'prioridad' => 'media'
            ],
            [
                'id' => 'R23_HISTORIAL_LESIONES',
                'categoria' => 'planificacion',
                'descripcion' => 'Historial de múltiples lesiones',
                'condicion' => [
                    'campo' => 'total_lesiones',
                    'operador' => '>=',
                    'valor' => 3
                ],
                'riesgo_puntos' => 0,
                'factor_mensaje' => 'El historial revela {count} lesiones registradas. Este patrón recurrente sugiere la existencia de factores predisponentes subyacentes que pueden ser biomecánicos (alteraciones posturales, asimetrías, déficits de movilidad), metodológicos (errores de programación, progresiones inadecuadas) o multifactoriales que requieren análisis sistemático.',
                'recomendaciones' => [
                    '🔬 Realizar análisis retrospectivo detallado de las lesiones: ¿hay zonas corporales recurrentes? ¿se relacionan con momentos específicos del ciclo de entrenamiento? ¿coinciden con incrementos de carga o cambios metodológicos?',
                    '🧘 Solicitar evaluación biomecánica integral (FMS completo si no está actualizado, análisis postural dinámico, screening de asimetrías de fuerza y movilidad) para identificar limitaciones estructurales o funcionales.',
                    '📊 Revisar y optimizar metodología de entrenamiento: verificar adecuación de volúmenes, intensidades, densidades de carga, períodos de recuperación y variabilidad de estímulos. Considerar implementar monitorización objetiva de carga (RPE, TUT, tonnage semanal).',
                    '📅 Establecer programa preventivo permanente que incluya trabajo correctivo específico según hallazgos, con reevaluaciones trimestrales para ajustar estrategias.'
                ],
                'prioridad' => 'media'
            ]
        ];
    }

    
    public static function obtenerReglasAsistencia(): array
    {
        return [
            [
                'id' => 'R30_ASISTENCIA_MUY_BAJA',
                'categoria' => 'planificacion',
                'descripcion' => 'Asistencia muy irregular (<50%)',
                'condicion' => [
                    'campo' => 'porcentaje_asistencia',
                    'operador' => '<',
                    'valor' => 50
                ],
                'riesgo_puntos' => 10,
                'factor_mensaje' => 'Se observa una asistencia muy irregular ({porcentaje}% en los últimos 30 días). La falta de consistencia representa uno de los factores de riesgo más importantes para lesión, ya que impide que el cuerpo desarrolle las adaptaciones fisiológicas necesarias (neuromuscular, tendinosa, ósea) para tolerar las cargas de entrenamiento. Los períodos prolongados de inactividad seguidos de retornos bruscos generan picos de estrés tisular que el organismo no puede gestionar adecuadamente.',
                'recomendaciones' => [
                    '📅 PRIORITARIO: Establecer compromiso mínimo de frecuencia semanal realista y sostenible (por ejemplo, 2-3 sesiones por semana como base), priorizando la constancia sobre la intensidad o el volumen en esta etapa inicial.',
                    '💬 Realizar entrevista motivacional para identificar barreras específicas que están impidiendo la asistencia regular: ¿obstáculos logísticos (horarios, transporte)? ¿falta de motivación intrínseca? ¿dolor o malestar durante entrenamientos? ¿expectativas no realistas?',
                    '🎯 Ajustar programa de entrenamiento para hacerlo más atractivo y sostenible: reducir duración de sesiones si es necesario (sesiones de 45-50 minutos pueden ser más adheribles que sesiones de 90 minutos), incorporar ejercicios que el atleta disfrute, establecer metas a corto plazo alcanzables.',
                    '📊 Implementar sistema de seguimiento y accountability (check-ins semanales, registros de progreso visibles, compañero de entrenamiento) para reforzar el hábito de asistencia.',
                    '🔄 Si la irregularidad persiste tras intervenciones conductuales, considerar reducción temporal de complejidad e intensidad del programa para evitar sobrecarga cuando el atleta asista tras períodos de inactividad.'
                ],
                'prioridad' => 'alta'
            ],
            [
                'id' => 'R31_ASISTENCIA_SUBOPTIMA',
                'categoria' => 'planificacion',
                'descripcion' => 'Asistencia por debajo de lo óptimo (50-79%)',
                'condicion' => [
                    'campo' => 'porcentaje_asistencia',
                    'operador' => 'BETWEEN',
                    'valor' => [50, 79]
                ],
                'riesgo_puntos' => 5,
                'factor_mensaje' => 'La asistencia registrada ({porcentaje}% en los últimos 30 días) está por debajo del umbral óptimo para maximizar adaptaciones fisiológicas. Aunque no representa un riesgo crítico inmediato, esta irregularidad moderada puede limitar el progreso y dificultar que el atleta alcance los objetivos planteados en los plazos estimados. Además, la variabilidad en la exposición a las cargas puede generar ciclos de desentrenamiento parcial y re-adaptación que no permiten consolidar mejoras.',
                'recomendaciones' => [
                    '📅 Revisar planificación de frecuencia semanal y ajustar expectativas de progreso según asistencia real. Si el objetivo inicial era entrenar 5-6 veces/semana pero la asistencia real es 3-4 veces/semana, replantear objetivos y periodización para que sean coherentes con la disponibilidad efectiva.',
                    '💬 Conversar con el atleta sobre factores que están interfiriendo con la asistencia consistente y explorar soluciones prácticas: ajustes de horario, mayor flexibilidad en la planificación, estrategias para gestionar fatiga o compromiso con otras responsabilidades.',
                    '🎯 Establecer “ventanas de consistencia”: comprometerse a bloques de 4-6 semanas de asistencia regular antes de tomar períodos de descanso planificado, en lugar de patrones irregulares que no permiten consolidar adaptaciones.',
                    '📈 Optimizar estimulación por sesión para compensar parcialmente la menor frecuencia, pero con cuidado de no sobrecargar en cada sesión (aumentar ligeramente volumen o intensidad por sesión, pero manteniendo márgenes de seguridad).'
                ],
                'prioridad' => 'media'
            ]
        ];
    }

    
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

    
    public static function obtenerPonderacionGravedadLesiones(): array
    {
        return [
            'leve' => 5,
            'moderada' => 8,
            'severa' => 10,
            'grave' => 10
        ];
    }

    
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

    
    public static function obtenerReglasCombinadas(): array
    {
        return [
            [
                'id' => 'RC1_FMS_BAJO_LESION_ACTIVA',
                'descripcion' => 'FMS bajo + lesión activa = Riesgo muy elevado',
                'condiciones' => [
                    ['modulo' => 'fms', 'campo' => 'puntuacion_total', 'operador' => '<=', 'valor' => 14],
                    ['modulo' => 'lesiones', 'campo' => 'num_lesiones_activas', 'operador' => '>=', 'valor' => 1]
                ],
                'factor_mensaje' => '⚠️ ALERTA CRÍTICA: La combinación de patrones de movimiento comprometidos (FMS ≤14) con lesión activa representa un escenario de riesgo muy elevado. Los patrones compensatorios ya presentes se magnifican al intentar evitar dolor o limitación de la zona lesionada, creando un círculo vicioso de disfunción biomecánica que predispone a nuevas lesiones en estructuras compensatorias.',
                'recomendaciones' => [
                    '🛑 DETENER inmediatamente progresiones de carga y enfocarse exclusivamente en: (1) protocolo de recuperación de la lesión activa, y (2) trabajo correctivo fundamental de los patrones FMS deficientes, de forma coordinada y no simultánea si generan conflicto.',
                    '🔬 Solicitar evaluación interdisciplinaria (entrenador + fisioterapeuta) para diseñar protocolo de recuperación que integre corrección de patrones de movimiento con manejo de la lesión, estableciendo prioridades claras y secuencia de intervención.',
                    '📅 No autorizar retorno a cargas significativas hasta que: (a) la lesión esté resuelta clínicamente, Y (b) el FMS haya mejorado al menos a 15 puntos, Y (c) haya ausencia de compensaciones evidentes en evaluación funcional dinámica.'
                ],
                'prioridad' => 'critica'
            ],
            [
                'id' => 'RC2_POSTURAL_SEVERO_FMS_BAJO',
                'descripcion' => 'Alteraciones posturales severas + FMS bajo = Alto riesgo de compensaciones',
                'condiciones' => [
                    ['modulo' => 'postural', 'campo' => 'problemas_moderados_severos', 'operador' => '>=', 'valor' => 4],
                    ['modulo' => 'fms', 'campo' => 'puntuacion_total', 'operador' => '<=', 'valor' => 16]
                ],
                'factor_mensaje' => 'La presencia simultánea de múltiples alteraciones posturales estructurales y patrones de movimiento funcional comprometidos indica que las desalineaciones estáticas se están traduciendo en disfunciones dinámicas significativas. Esta combinación sugiere que las limitaciones estructurales están restringiendo la capacidad del atleta de ejecutar patrones de movimiento óptimos, forzando estrategias compensatorias que incrementan exponencialmente el estrés sobre tejidos vulnerables.',
                'recomendaciones' => [
                    '🧘 Implementar programa correctivo integral que aborde simultáneamente: (a) liberación de restricciones miofasciales y articulares relacionadas con las alteraciones posturales, y (b) reeducación de patrones de movimiento mediante drills correctivos específicos del FMS (mínimo 20 minutos diarios).',
                    '🔧 Priorizar ejercicios que "desbloqueen" las restricciones posturales antes de cargar patrones complejos: si hay cifosis torácica severa con FMS bajo en movilidad de hombro, trabajar extensión torácica y activación de retractores escapulares antes de intentar press overhead.',
                    '📊 Realizar seguimiento integrado: reevaluar postura y FMS cada 4 semanas para verificar que las mejoras posturales se están traduciendo en mejoras funcionales en los patrones de movimiento.'
                ],
                'prioridad' => 'alta'
            ],
            [
                'id' => 'RC3_LESIONES_RECURRENTES_ASISTENCIA_BAJA',
                'descripcion' => 'Historial de lesiones + baja asistencia = Círculo vicioso',
                'condiciones' => [
                    ['modulo' => 'lesiones', 'campo' => 'total_lesiones', 'operador' => '>=', 'valor' => 3],
                    ['modulo' => 'asistencia', 'campo' => 'porcentaje_asistencia', 'operador' => '<', 'valor' => 60]
                ],
                'factor_mensaje' => 'Se identifica un patrón problemático: historial recurrente de lesiones combinado con asistencia irregular. Esto sugiere un posible círculo vicioso donde las lesiones interrumpen la asistencia, la falta de consistencia impide adaptaciones protectoras, y el retorno abrupto tras inactividad genera nuevas lesiones. Este patrón requiere intervención tanto física como conductual para romper el ciclo.',
                'recomendaciones' => [
                    '💬 PRIORITARIO: Entrevista profunda para identificar la relación entre lesiones y asistencia: ¿las lesiones son consecuencia de la irregularidad (retornos bruscos)? ¿la irregularidad es consecuencia de las lesiones (miedo, dolor)? ¿ambas comparten causas comunes (sobrecarga, recuperación insuficiente)?',
                    '📅 Establecer "contrato de consistencia mínima": comprometerse a un mínimo de 2-3 sesiones semanales de baja-moderada intensidad durante 6-8 semanas, con el objetivo prioritario de restaurar hábito de asistencia antes que progresiones de rendimiento.',
                    '🔧 Diseñar programa "a prueba de irregularidad": sesiones modulares que permitan entrenar de forma efectiva incluso con gaps de 4-7 días entre sesiones, con énfasis en patrones fundamentales, movilidad y trabajo preventivo más que en cargas pesadas.',
                    '📊 Implementar monitoreo semanal de percepción de carga, dolor y barreras para asistencia, ajustando el programa de forma reactiva según el feedback para maximizar adherencia y minimizar riesgo lesional.'
                ],
                'prioridad' => 'alta'
            ],
            [
                'id' => 'RC4_LESION_LUMBAR_ESTABILIDAD_TRONCO_BAJA',
                'descripcion' => 'Lesión lumbar + baja estabilidad de tronco en FMS = Causa-efecto',
                'condiciones' => [
                    ['modulo' => 'lesiones', 'campo' => 'lesion_zona_lumbar', 'operador' => '==', 'valor' => true],
                    ['modulo' => 'fms', 'campo' => 'estabilidad_tronco', 'operador' => '<=', 'valor' => 1]
                ],
                'factor_mensaje' => 'La presencia de lesión lumbar activa o reciente combinada con déficits severos en la prueba de estabilidad de tronco del FMS (score ≤1) sugiere una relación causa-efecto directa. La incapacidad de mantener estabilidad lumbopélvica durante el patrón de extensión de tronco indica que el atleta está cargando estructuras pasivas (discos, ligamentos) en lugar de distribuir fuerzas a través de la musculatura estabilizadora, perpetuando el ciclo lesional.',
                'recomendaciones' => [
                    '🩹 Enfocar protocolo de rehabilitación en restauración prioritaria de estabilidad lumbopélvica mediante: (a) activación de musculatura profunda (transverso, multífidos), (b) control motor en patrones de anti-extensión y anti-rotación, (c) progresión gradual desde posiciones de bajo estrés (cuadrupedia, decúbito) hacia bipedestación con carga.',
                    '🛑 Prohibición ESTRICTA de ejercicios que demanden extensión lumbar significativa o control rotacional bajo carga hasta que la prueba de estabilidad de tronco alcance mínimo score de 2, y la lesión esté resuelta clínicamente.',
                    '🏋️ Cuando sea apropiado reintroducir carga axial, utilizar EXCLUSIVAMENTE variantes que minimicen demanda sobre columna lumbar: sentadilla frontal en lugar de back squat, peso muerto rumano con cargas moderadas antes que convencional pesado, press de banca en lugar de overhead press.',
                    '📅 Reevaluación bisemanal de estabilidad de tronco y estado clínico de la lesión lumbar hasta confirmación de resolución completa y recuperación de capacidades de estabilización.'
                ],
                'prioridad' => 'critica'
            ]
        ];
    }
}
