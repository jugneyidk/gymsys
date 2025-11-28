<?php

namespace Gymsys\Core\IA;

/**
 * Motor de Inteligencia Artificial para Análisis de Atletas
 * 
 * Sistema experto basado en reglas y ponderaciones para calcular
 * el riesgo de lesión y generar recomendaciones personalizadas.
 * 
 * Arquitectura:
 * - Motor de Inferencia: AnalizadorAtleta (este archivo)
 * - Base de Conocimiento: ModoBaseConocimiento (switch BD ↔ archivo)
 * - Hechos: Datos del atleta
 * 
 * FASE 3 - Mejoras avanzadas:
 * - Base de conocimiento en BD independiente (gymsys_kb)
 * - Switch automático BD ↔ archivo con fallback
 * - Reglas compuestas avanzadas
 * - Reglas de tendencia temporal
 * - Reglas por perfil de atleta
 * - Recomendaciones súper-detalladas por categoría
 * 
 * @author GymSys Development Team
 * @version 3.0
 */
class AnalizadorAtleta
{
    /**
     * Analiza la tarjeta completa de un atleta y calcula su riesgo de lesión
     * 
     * @param array $tarjetaAtleta Datos completos del atleta (tests, lesiones, asistencias)
     * @return array Resultado del análisis con score, nivel, factores y recomendaciones
     */
    public function analizarAtleta(array $tarjetaAtleta): array
    {
        // Extraer componentes del análisis
        $datosAtleta = $tarjetaAtleta['atleta'] ?? [];
        $testFms = $tarjetaAtleta['ultimo_test_fms'] ?? null;
        $testPostural = $tarjetaAtleta['ultimo_test_postural'] ?? null;
        $lesiones = $tarjetaAtleta['lesiones_recientes'] ?? [];
        $resumenLesiones = $tarjetaAtleta['resumen_lesiones'] ?? [];
        $asistencias = $tarjetaAtleta['asistencias_30_dias'] ?? null;

        // Obtener ponderaciones de la base de conocimiento (switch automático)
        $ponderaciones = ModoBaseConocimiento::obtenerPonderaciones();
        
        // Determinar qué módulos tienen datos disponibles
        $modulosDisponibles = [];
        $pesoMaximoPosible = 0;
        
        $tieneFms = ($testFms && isset($testFms['puntuacion_total']));
        $tienePostural = ($testPostural !== null);
        $tieneLesiones = true; // Siempre evaluamos lesiones (puede ser 0)
        $tieneAsistencia = ($asistencias && isset($asistencias['porcentaje_asistencia']) && $asistencias['total_sesiones'] > 0);
        
        if ($tieneFms) {
            $modulosDisponibles[] = 'fms';
            $pesoMaximoPosible += $ponderaciones['fms'];
        }
        if ($tienePostural) {
            $modulosDisponibles[] = 'postural';
            $pesoMaximoPosible += $ponderaciones['postural'];
        }
        if ($tieneLesiones) {
            $modulosDisponibles[] = 'lesiones';
            $pesoMaximoPosible += $ponderaciones['lesiones'];
        }
        if ($tieneAsistencia) {
            $modulosDisponibles[] = 'asistencia';
            $pesoMaximoPosible += $ponderaciones['asistencia'];
        }

        // Calcular riesgos por categoría
        $riesgoFms = $tieneFms ? $this->calcularRiesgoFMS($testFms) : 0;
        $riesgoPostural = $tienePostural ? $this->calcularRiesgoPostural($testPostural) : 0;
        $riesgoLesiones = $this->calcularRiesgoLesiones($lesiones, $resumenLesiones);
        $riesgoAsistencia = $tieneAsistencia ? $this->calcularRiesgoAsistencia($asistencias) : 0;

        // Score total SIN reescalar
        $riesgoTotalBruto = $riesgoFms + $riesgoPostural + $riesgoLesiones + $riesgoAsistencia;
        
        // REESCALADO: Ajustar score a 0-100 según módulos disponibles
        $riesgoScore = 0;
        if ($pesoMaximoPosible > 0) {
            // Proporción del riesgo obtenido respecto al máximo posible
            $riesgoScore = round(($riesgoTotalBruto / $pesoMaximoPosible) * 100);
            $riesgoScore = min(100, max(0, $riesgoScore));
        }

        // Clasificar nivel de riesgo
        $riesgoNivel = $this->clasificarRiesgo($riesgoScore);

        // Evaluar reglas de la base de conocimiento
        $factoresClave = $this->identificarFactoresClave(
            $testFms, 
            $testPostural, 
            $lesiones, 
            $resumenLesiones,
            $asistencias,
            $tieneFms,
            $tienePostural,
            $tieneAsistencia
        );

        $recomendaciones = $this->generarRecomendaciones(
            $testFms, 
            $testPostural, 
            $lesiones, 
            $resumenLesiones,
            $asistencias,
            $riesgoNivel,
            $tieneFms,
            $tienePostural,
            $tieneAsistencia
        );

        // Retornar análisis completo
        return [
            'riesgo_score' => $riesgoScore,
            'riesgo_nivel' => $riesgoNivel,
            'factores_clave' => $factoresClave,
            'recomendaciones' => $recomendaciones,
            'desglose' => [
                'fms' => $riesgoFms,
                'postural' => $riesgoPostural,
                'lesiones' => $riesgoLesiones,
                'asistencia' => $riesgoAsistencia
            ],
            'modulos_disponibles' => $modulosDisponibles,
            'peso_maximo_posible' => $pesoMaximoPosible
        ];
    }

    /**
     * Calcula el riesgo basado en el Test FMS
     * Ponderación: 0-30 puntos
     * 
     * @param array|null $testFms Último test FMS del atleta
     * @return int Puntos de riesgo (0-30)
     */
    private function calcularRiesgoFMS($testFms): int
    {
        if (!$testFms || !isset($testFms['puntuacion_total'])) {
            return 0; // Sin datos = sin riesgo
        }

        $puntuacion = (int) $testFms['puntuacion_total'];
        $reglas = ModoBaseConocimiento::obtenerReglasFMS();

        // Evaluar reglas FMS desde la base de conocimiento
        foreach ($reglas as $regla) {
            $condicion = $regla['condicion'];
            
            if ($condicion['operador'] === '<=') {
                if ($puntuacion <= $condicion['valor']) {
                    return $regla['riesgo_puntos'];
                }
            } elseif ($condicion['operador'] === 'BETWEEN') {
                if ($puntuacion >= $condicion['valor'][0] && $puntuacion <= $condicion['valor'][1]) {
                    return $regla['riesgo_puntos'];
                }
            }
        }

        return 0; // FMS óptimo (≥18)
    }

    /**
     * Calcula el riesgo basado en el Test Postural
     * Ponderación: 0-30 puntos
     * 
     * @param array|null $testPostural Último test postural del atleta
     * @return int Puntos de riesgo (0-30)
     */
    private function calcularRiesgoPostural($testPostural): int
    {
        if (!$testPostural) {
            return 0; // Sin datos = sin riesgo
        }

        $problemas = $this->contarProblemasPosturales($testPostural);
        $reglas = ModoBaseConocimiento::obtenerReglasPostural();

        // Evaluar reglas posturales desde la base de conocimiento
        foreach ($reglas as $regla) {
            $condicion = $regla['condicion'];
            
            if ($condicion['operador'] === '>=') {
                if ($problemas >= $condicion['valor']) {
                    return $regla['riesgo_puntos'];
                }
            } elseif ($condicion['operador'] === 'BETWEEN') {
                if ($problemas >= $condicion['valor'][0] && $problemas <= $condicion['valor'][1]) {
                    return $regla['riesgo_puntos'];
                }
            }
        }

        return 0; // Sin problemas posturales
    }

    /**
     * Calcula el riesgo basado en lesiones
     * Ponderación: 0-30 puntos
     * 
     * @param array $lesiones Lista de lesiones recientes
     * @param array $resumenLesiones Resumen estadístico de lesiones
     * @return int Puntos de riesgo (0-30)
     */
    private function calcularRiesgoLesiones(array $lesiones, array $resumenLesiones): int
    {
        $riesgo = 0;
        $ponderacionGravedad = ModoBaseConocimiento::obtenerPonderacionGravedadLesiones();

        // Contar lesiones activas por gravedad
        $lesionesActivas = array_filter($lesiones, function($lesion) {
            return $lesion['estado_lesion'] === 'Activa';
        });

        foreach ($lesionesActivas as $lesion) {
            $gravedad = strtolower($lesion['gravedad'] ?? 'leve');
            $riesgo += $ponderacionGravedad[$gravedad] ?? 5;
        }

        // Capar el riesgo por lesiones activas a 30
        $riesgo = min(30, $riesgo);

        // Bonus: Si hubo lesión en los últimos 30 días (solo si no hay lesiones activas o riesgo <30)
        $hayLesionReciente = $this->hayLesionEnUltimos30Dias($lesiones);
        if ($hayLesionReciente && $riesgo < 30) {
            $riesgo = min(30, $riesgo + 10);
        }

        return $riesgo;
    }

    /**
     * Calcula el riesgo basado en asistencias
     * Ponderación: 0-10 puntos
     * 
     * @param array|null $asistencias Datos de asistencia de últimos 30 días
     * @return int Puntos de riesgo (0-10)
     */
    private function calcularRiesgoAsistencia($asistencias): int
    {
        if (!$asistencias || !isset($asistencias['porcentaje_asistencia'])) {
            return 0; // Sin datos = sin riesgo
        }

        $porcentaje = (float) $asistencias['porcentaje_asistencia'];
        $reglas = ModoBaseConocimiento::obtenerReglasAsistencia();

        // Evaluar reglas de asistencia desde la base de conocimiento
        foreach ($reglas as $regla) {
            $condicion = $regla['condicion'];
            
            if ($condicion['operador'] === '<') {
                if ($porcentaje < $condicion['valor']) {
                    return $regla['riesgo_puntos'];
                }
            } elseif ($condicion['operador'] === 'BETWEEN') {
                if ($porcentaje >= $condicion['valor'][0] && $porcentaje <= $condicion['valor'][1]) {
                    return $regla['riesgo_puntos'];
                }
            }
        }

        return 0; // Asistencia excelente (≥80%)
    }

    /**
     * Verifica si hay alguna lesión en los últimos 30 días
     * 
     * @param array $lesiones Lista de lesiones
     * @return bool True si hay lesión reciente
     */
    private function hayLesionEnUltimos30Dias(array $lesiones): bool
    {
        $hace30Dias = new \DateTime('-30 days');
        
        foreach ($lesiones as $lesion) {
            $fechaLesion = new \DateTime($lesion['fecha_lesion']);
            if ($fechaLesion >= $hace30Dias) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Clasifica el nivel de riesgo según el score
     * 
     * @param int $score Score de riesgo (0-100)
     * @return string Nivel: 'bajo', 'medio' o 'alto'
     */
    private function clasificarRiesgo(int $score): string
    {
        $umbrales = ModoBaseConocimiento::obtenerUmbralesRiesgo();
        
        foreach ($umbrales as $nivel => $rango) {
            if ($score >= $rango['min'] && $score <= $rango['max']) {
                return $nivel;
            }
        }
        
        return 'medio'; // Fallback
    }

    /**
     * BASE DE CONOCIMIENTO: Identifica factores clave de riesgo
     * Aplica reglas expertas para determinar qué está incrementando el riesgo
     * 
     * @param array|null $testFms Test FMS
     * @param array|null $testPostural Test Postural
     * @param array $lesiones Lesiones
     * @param array $resumenLesiones Resumen de lesiones
     * @param array|null $asistencias Asistencias
     * @param bool $tieneFms Indica si hay datos FMS
     * @param bool $tienePostural Indica si hay datos posturales
     * @param bool $tieneAsistencia Indica si hay datos de asistencia
     * @return array Lista de factores clave identificados
     */
    private function identificarFactoresClave(
        $testFms, 
        $testPostural, 
        array $lesiones, 
        array $resumenLesiones,
        $asistencias,
        bool $tieneFms,
        bool $tienePostural,
        bool $tieneAsistencia
    ): array {
        $factores = [];
        $reglasFaltantes = ModoBaseConocimiento::obtenerReglasAusenciaDatos();

        // ========================================
        // VERIFICAR DATOS FALTANTES PRIMERO
        // ========================================
        
        // Verificar si falta FMS
        if (!$tieneFms) {
            foreach ($reglasFaltantes as $regla) {
                if ($regla['modulo'] === 'fms') {
                    $factores[] = $regla['factor_mensaje'];
                    break;
                }
            }
        }
        
        // Verificar si falta Postural
        if (!$tienePostural) {
            foreach ($reglasFaltantes as $regla) {
                if ($regla['modulo'] === 'postural') {
                    $factores[] = $regla['factor_mensaje'];
                    break;
                }
            }
        }
        
        // Verificar si falta Asistencia
        if (!$tieneAsistencia) {
            foreach ($reglasFaltantes as $regla) {
                if ($regla['modulo'] === 'asistencia') {
                    $factores[] = $regla['factor_mensaje'];
                    break;
                }
            }
        }

        // ========================================
        // EVALUAR REGLAS DE RIESGO (DATOS EXISTENTES)
        // ========================================
        
        // REGLA FMS: Usar base de conocimiento
        if ($tieneFms) {
            $puntuacionFms = (int) $testFms['puntuacion_total'];
            $reglasFms = ModoBaseConocimiento::obtenerReglasFMS();
            
            foreach ($reglasFms as $regla) {
                if ($regla['prioridad'] === 'alta' || $regla['prioridad'] === 'media') {
                    $mensaje = str_replace('{score}', $puntuacionFms, $regla['factor_mensaje']);
                    
                    $condicion = $regla['condicion'];
                    $cumple = false;
                    
                    if ($condicion['operador'] === '<=') {
                        $cumple = $puntuacionFms <= $condicion['valor'];
                    } elseif ($condicion['operador'] === 'BETWEEN') {
                        $cumple = $puntuacionFms >= $condicion['valor'][0] && $puntuacionFms <= $condicion['valor'][1];
                    }
                    
                    if ($cumple && $mensaje) {
                        $factores[] = $mensaje;
                        break; // Solo el primer match
                    }
                }
            }
        }

        // REGLA POSTURAL: Usar base de conocimiento
        if ($tienePostural) {
            $problemas = $this->contarProblemasPosturales($testPostural);
            $reglasPostural = ModoBaseConocimiento::obtenerReglasPostural();
            
            foreach ($reglasPostural as $regla) {
                if ($regla['factor_mensaje']) {
                    $mensaje = str_replace('{count}', $problemas, $regla['factor_mensaje']);
                    
                    $condicion = $regla['condicion'];
                    $cumple = false;
                    
                    if ($condicion['operador'] === '>=') {
                        $cumple = $problemas >= $condicion['valor'];
                    } elseif ($condicion['operador'] === 'BETWEEN') {
                        $cumple = $problemas >= $condicion['valor'][0] && $problemas <= $condicion['valor'][1];
                    }
                    
                    if ($cumple && $mensaje) {
                        $factores[] = $mensaje;
                        break;
                    }
                }
            }
        }

        // REGLA LESIONES: Usar base de conocimiento
        $lesionesActivas = array_filter($lesiones, function($lesion) {
            return $lesion['estado_lesion'] === 'Activa';
        });
        $numLesionesActivas = count($lesionesActivas);
        $reglasLesiones = ModoBaseConocimiento::obtenerReglasLesiones();
        
        foreach ($reglasLesiones as $regla) {
            $cumple = false;
            $mensaje = $regla['factor_mensaje'];
            
            if ($regla['id'] === 'R20_LESIONES_MULTIPLES_ACTIVAS' && $numLesionesActivas > 1) {
                $mensaje = str_replace('{count}', $numLesionesActivas, $mensaje);
                $cumple = true;
            } elseif ($regla['id'] === 'R21_LESION_ACTIVA_UNICA' && $numLesionesActivas === 1) {
                $gravedad = $lesionesActivas[0]['gravedad'] ?? 'desconocida';
                $zona = $lesionesActivas[0]['zona_afectada'] ?? 'no especificada';
                $mensaje = str_replace(['{gravedad}', '{zona}'], [$gravedad, $zona], $mensaje);
                $cumple = true;
            } elseif ($regla['id'] === 'R22_LESION_RECIENTE' && $this->hayLesionEnUltimos30Dias($lesiones) && $numLesionesActivas === 0) {
                $cumple = true;
            } elseif ($regla['id'] === 'R23_HISTORIAL_LESIONES') {
                $totalLesiones = $resumenLesiones['total_lesiones'] ?? 0;
                if ($totalLesiones >= 3) {
                    $mensaje = str_replace('{count}', $totalLesiones, $mensaje);
                    $cumple = true;
                }
            }
            
            if ($cumple && $mensaje) {
                $factores[] = $mensaje;
                if ($regla['id'] !== 'R23_HISTORIAL_LESIONES') break; // Historial no es excluyente
            }
        }

        // REGLA ASISTENCIA: Usar base de conocimiento
        if ($tieneAsistencia) {
            $porcentaje = (float) $asistencias['porcentaje_asistencia'];
            $reglasAsistencia = ModoBaseConocimiento::obtenerReglasAsistencia();
            
            foreach ($reglasAsistencia as $regla) {
                $mensaje = str_replace('{porcentaje}', number_format($porcentaje, 1), $regla['factor_mensaje']);
                
                $condicion = $regla['condicion'];
                $cumple = false;
                
                if ($condicion['operador'] === '<') {
                    $cumple = $porcentaje < $condicion['valor'];
                } elseif ($condicion['operador'] === 'BETWEEN') {
                    $cumple = $porcentaje >= $condicion['valor'][0] && $porcentaje <= $condicion['valor'][1];
                }
                
                if ($cumple && $mensaje) {
                    $factores[] = $mensaje;
                    break;
                }
            }
        }

        // ========================================
        // EVALUAR REGLAS COMBINADAS (INTERACCIONES ENTRE MÓDULOS)
        // ========================================
        $reglasCombinadas = ModoBaseConocimiento::obtenerReglasCombinadas();
        
        foreach ($reglasCombinadas as $reglaCombinada) {
            $todasCondicionesCumplen = true;
            
            foreach ($reglaCombinada['condiciones'] as $condicion) {
                $moduloCumple = false;
                
                // Evaluar cada condición según el módulo
                if ($condicion['modulo'] === 'fms' && $tieneFms) {
                    $valor = (int) $testFms[$condicion['campo']];
                    $moduloCumple = $this->evaluarCondicion($valor, $condicion['operador'], $condicion['valor']);
                } elseif ($condicion['modulo'] === 'postural' && $tienePostural) {
                    if ($condicion['campo'] === 'problemas_moderados_severos') {
                        $valor = $this->contarProblemasPosturales($testPostural);
                        $moduloCumple = $this->evaluarCondicion($valor, $condicion['operador'], $condicion['valor']);
                    }
                } elseif ($condicion['modulo'] === 'lesiones') {
                    if ($condicion['campo'] === 'num_lesiones_activas') {
                        $valor = count(array_filter($lesiones, fn($l) => $l['estado_lesion'] === 'Activa'));
                        $moduloCumple = $this->evaluarCondicion($valor, $condicion['operador'], $condicion['valor']);
                    } elseif ($condicion['campo'] === 'total_lesiones') {
                        $valor = $resumenLesiones['total_lesiones'] ?? 0;
                        $moduloCumple = $this->evaluarCondicion($valor, $condicion['operador'], $condicion['valor']);
                    } elseif ($condicion['campo'] === 'lesion_zona_lumbar') {
                        // Verificar si hay lesión lumbar activa o reciente
                        $hayLesionLumbar = false;
                        foreach ($lesiones as $lesion) {
                            $zona = strtolower($lesion['zona_afectada'] ?? '');
                            if (($lesion['estado_lesion'] === 'Activa' || $this->esLesionReciente($lesion)) && 
                                (strpos($zona, 'lumbar') !== false || strpos($zona, 'espalda baja') !== false)) {
                                $hayLesionLumbar = true;
                                break;
                            }
                        }
                        $moduloCumple = $hayLesionLumbar;
                    }
                } elseif ($condicion['modulo'] === 'asistencia' && $tieneAsistencia) {
                    $valor = (float) $asistencias['porcentaje_asistencia'];
                    $moduloCumple = $this->evaluarCondicion($valor, $condicion['operador'], $condicion['valor']);
                }
                
                if (!$moduloCumple) {
                    $todasCondicionesCumplen = false;
                    break;
                }
            }
            
            // Si todas las condiciones se cumplen, agregar el factor combinado
            if ($todasCondicionesCumplen) {
                $factores[] = $reglaCombinada['factor_mensaje'];
            }
        }

        // Si no se identificaron factores críticos
        if (empty($factores)) {
            $factores[] = "Perfil de riesgo general dentro de parámetros normales. Mantener seguimiento preventivo.";
        }

        return $factores;
    }

    /**
     * Evalúa una condición genérica
     * 
     * @param mixed $valor Valor a evaluar
     * @param string $operador Operador de comparación
     * @param mixed $valorComparacion Valor de referencia
     * @return bool True si la condición se cumple
     */
    private function evaluarCondicion($valor, string $operador, $valorComparacion): bool
    {
        switch ($operador) {
            case '<=':
                return $valor <= $valorComparacion;
            case '<':
                return $valor < $valorComparacion;
            case '>=':
                return $valor >= $valorComparacion;
            case '>':
                return $valor > $valorComparacion;
            case '==':
                return $valor == $valorComparacion;
            default:
                return false;
        }
    }

    /**
     * Verifica si una lesión es reciente (últimos 30 días)
     * 
     * @param array $lesion Datos de la lesión
     * @return bool True si es reciente
     */
    private function esLesionReciente(array $lesion): bool
    {
        $hace30Dias = new \DateTime('-30 days');
        $fechaLesion = new \DateTime($lesion['fecha_lesion']);
        return $fechaLesion >= $hace30Dias;
    }

    /**
     * BASE DE CONOCIMIENTO: Genera recomendaciones personalizadas
     * 
     * @param array|null $testFms Test FMS
     * @param array|null $testPostural Test Postural
     * @param array $lesiones Lesiones
     * @param array $resumenLesiones Resumen de lesiones
     * @param array|null $asistencias Asistencias
     * @param string $nivelRiesgo Nivel de riesgo calculado
     * @param bool $tieneFms Indica si hay datos FMS
     * @param bool $tienePostural Indica si hay datos posturales
     * @param bool $tieneAsistencia Indica si hay datos de asistencia
     * @return array Lista de recomendaciones
     */
    private function generarRecomendaciones(
        $testFms, 
        $testPostural, 
        array $lesiones, 
        array $resumenLesiones,
        $asistencias,
        string $nivelRiesgo,
        bool $tieneFms,
        bool $tienePostural,
        bool $tieneAsistencia
    ): array {
        $recomendaciones = [];
        $recomendacionesPorNivel = ModoBaseConocimiento::obtenerRecomendacionesPorNivel();
        $reglasFaltantes = ModoBaseConocimiento::obtenerReglasAusenciaDatos();

        // ========================================
        // RECOMENDACIONES POR DATOS FALTANTES
        // ========================================
        if (!$tieneFms) {
            foreach ($reglasFaltantes as $regla) {
                if ($regla['modulo'] === 'fms') {
                    $recomendaciones = array_merge($recomendaciones, $regla['recomendaciones']);
                    break;
                }
            }
        }
        
        if (!$tienePostural) {
            foreach ($reglasFaltantes as $regla) {
                if ($regla['modulo'] === 'postural') {
                    $recomendaciones = array_merge($recomendaciones, $regla['recomendaciones']);
                    break;
                }
            }
        }
        
        if (!$tieneAsistencia) {
            foreach ($reglasFaltantes as $regla) {
                if ($regla['modulo'] === 'asistencia') {
                    $recomendaciones = array_merge($recomendaciones, $regla['recomendaciones']);
                    break;
                }
            }
        }

        // ========================================
        // RECOMENDACIONES SEGÚN NIVEL DE RIESGO
        // ========================================
        if (isset($recomendacionesPorNivel[$nivelRiesgo])) {
            $recomendaciones = array_merge($recomendaciones, $recomendacionesPorNivel[$nivelRiesgo]);
        }

        // ========================================
        // RECOMENDACIONES ESPECÍFICAS POR MÓDULO
        // ========================================
        
        // FMS
        if ($tieneFms) {
            $puntuacionFms = (int) $testFms['puntuacion_total'];
            $reglasFms = ModoBaseConocimiento::obtenerReglasFMS();
            
            foreach ($reglasFms as $regla) {
                $condicion = $regla['condicion'];
                $cumple = false;
                
                if ($condicion['operador'] === '<=') {
                    $cumple = $puntuacionFms <= $condicion['valor'];
                } elseif ($condicion['operador'] === 'BETWEEN') {
                    $cumple = $puntuacionFms >= $condicion['valor'][0] && $puntuacionFms <= $condicion['valor'][1];
                }
                
                if ($cumple && !empty($regla['recomendaciones'])) {
                    $recomendaciones = array_merge($recomendaciones, $regla['recomendaciones']);
                    
                    // Agregar pruebas deficientes
                    if ($puntuacionFms <= 14) {
                        $pruebasBajas = $this->identificarPruebasFmsDeficientes($testFms);
                        if (!empty($pruebasBajas)) {
                            $recomendaciones[] = "Foco en: " . implode(', ', $pruebasBajas) . ".";
                        }
                    }
                    break;
                }
            }
        }

        // POSTURAL
        if ($tienePostural) {
            $problemasEspecificos = $this->analizarProblemasPosturalesEspecificos($testPostural);
            if (!empty($problemasEspecificos)) {
                $recomendaciones[] = "Trabajo postural enfocado en: " . implode(', ', $problemasEspecificos) . ".";
            }
        }

        // LESIONES
        $lesionesActivas = array_filter($lesiones, function($lesion) {
            return $lesion['estado_lesion'] === 'Activa';
        });
        
        if (count($lesionesActivas) > 0) {
            $reglasLesiones = ModoBaseConocimiento::obtenerReglasLesiones();
            foreach ($reglasLesiones as $regla) {
                if (($regla['id'] === 'R20_LESIONES_MULTIPLES_ACTIVAS' && count($lesionesActivas) > 1) ||
                    ($regla['id'] === 'R21_LESION_ACTIVA_UNICA' && count($lesionesActivas) === 1)) {
                    $recomendaciones = array_merge($recomendaciones, $regla['recomendaciones']);
                    break;
                }
            }
        }
        
        // Lesión reciente
        if ($this->hayLesionEnUltimos30Dias($lesiones) && count($lesionesActivas) === 0) {
            $reglasLesiones = ModoBaseConocimiento::obtenerReglasLesiones();
            foreach ($reglasLesiones as $regla) {
                if ($regla['id'] === 'R22_LESION_RECIENTE') {
                    $recomendaciones = array_merge($recomendaciones, $regla['recomendaciones']);
                    break;
                }
            }
        }

        // ASISTENCIA
        if ($tieneAsistencia) {
            $porcentaje = (float) $asistencias['porcentaje_asistencia'];
            $reglasAsistencia = ModoBaseConocimiento::obtenerReglasAsistencia();
            
            foreach ($reglasAsistencia as $regla) {
                $condicion = $regla['condicion'];
                $cumple = false;
                
                if ($condicion['operador'] === '<') {
                    $cumple = $porcentaje < $condicion['valor'];
                } elseif ($condicion['operador'] === 'BETWEEN') {
                    $cumple = $porcentaje >= $condicion['valor'][0] && $porcentaje <= $condicion['valor'][1];
                }
                
                if ($cumple && !empty($regla['recomendaciones'])) {
                    $recomendaciones = array_merge($recomendaciones, $regla['recomendaciones']);
                    break;
                }
            }
        }

        // ========================================
        // RECOMENDACIONES DE REGLAS COMBINADAS
        // ========================================
        $reglasCombinadas = ModoBaseConocimiento::obtenerReglasCombinadas();
        
        foreach ($reglasCombinadas as $reglaCombinada) {
            $todasCondicionesCumplen = true;
            
            foreach ($reglaCombinada['condiciones'] as $condicion) {
                $moduloCumple = false;
                
                // Evaluar cada condición según el módulo
                if ($condicion['modulo'] === 'fms' && $tieneFms) {
                    $valor = (int) $testFms[$condicion['campo']];
                    $moduloCumple = $this->evaluarCondicion($valor, $condicion['operador'], $condicion['valor']);
                } elseif ($condicion['modulo'] === 'postural' && $tienePostural) {
                    if ($condicion['campo'] === 'problemas_moderados_severos') {
                        $valor = $this->contarProblemasPosturales($testPostural);
                        $moduloCumple = $this->evaluarCondicion($valor, $condicion['operador'], $condicion['valor']);
                    }
                } elseif ($condicion['modulo'] === 'lesiones') {
                    if ($condicion['campo'] === 'num_lesiones_activas') {
                        $lesionesActivas = array_filter($lesiones, fn($l) => $l['estado_lesion'] === 'Activa');
                        $valor = count($lesionesActivas);
                        $moduloCumple = $this->evaluarCondicion($valor, $condicion['operador'], $condicion['valor']);
                    } elseif ($condicion['campo'] === 'total_lesiones') {
                        $valor = $resumenLesiones['total_lesiones'] ?? 0;
                        $moduloCumple = $this->evaluarCondicion($valor, $condicion['operador'], $condicion['valor']);
                    } elseif ($condicion['campo'] === 'lesion_zona_lumbar') {
                        $hayLesionLumbar = false;
                        foreach ($lesiones as $lesion) {
                            $zona = strtolower($lesion['zona_afectada'] ?? '');
                            if (($lesion['estado_lesion'] === 'Activa' || $this->esLesionReciente($lesion)) && 
                                (strpos($zona, 'lumbar') !== false || strpos($zona, 'espalda baja') !== false)) {
                                $hayLesionLumbar = true;
                                break;
                            }
                        }
                        $moduloCumple = $hayLesionLumbar;
                    }
                } elseif ($condicion['modulo'] === 'asistencia' && $tieneAsistencia) {
                    $valor = (float) $asistencias['porcentaje_asistencia'];
                    $moduloCumple = $this->evaluarCondicion($valor, $condicion['operador'], $condicion['valor']);
                }
                
                if (!$moduloCumple) {
                    $todasCondicionesCumplen = false;
                    break;
                }
            }
            
            // Si todas las condiciones se cumplen, agregar las recomendaciones combinadas
            if ($todasCondicionesCumplen && !empty($reglaCombinada['recomendaciones'])) {
                // Las recomendaciones combinadas tienen prioridad, agregarlas al principio
                $recomendaciones = array_merge($reglaCombinada['recomendaciones'], $recomendaciones);
            }
        }

        // Eliminar duplicados manteniendo el orden (las combinadas primero)
        $recomendaciones = array_values(array_unique($recomendaciones));

        return $recomendaciones;
    }

    /**
     * Cuenta problemas posturales moderados o severos
     * 
     * @param array $testPostural Datos del test postural
     * @return int Cantidad de problemas
     */
    private function contarProblemasPosturales(array $testPostural): int
    {
        $problemas = 0;
        $campos = [
            'cifosis_dorsal', 'lordosis_lumbar', 'escoliosis',
            'inclinacion_pelvis', 'valgo_rodilla', 'varo_rodilla',
            'rotacion_hombros', 'desnivel_escapulas'
        ];

        foreach ($campos as $campo) {
            $valor = $testPostural[$campo] ?? 'ninguna';
            if ($valor === 'moderada' || $valor === 'severa') {
                $problemas++;
            }
        }

        return $problemas;
    }

    /**
     * Identifica pruebas FMS con scores deficientes (≤1)
     * 
     * @param array $testFms Datos del test FMS
     * @return array Lista de pruebas deficientes
     */
    private function identificarPruebasFmsDeficientes(array $testFms): array
    {
        $deficientes = [];
        $pruebas = ModoBaseConocimiento::obtenerMapaPruebasFMS();

        foreach ($pruebas as $campo => $nombre) {
            if (isset($testFms[$campo]) && (int)$testFms[$campo] <= 1) {
                $deficientes[] = $nombre;
            }
        }

        return $deficientes;
    }

    /**
     * Analiza problemas posturales específicos para recomendaciones
     * 
     * @param array $testPostural Datos del test postural
     * @return array Lista de áreas problemáticas
     */
    private function analizarProblemasPosturalesEspecificos(array $testPostural): array
    {
        $problemas = [];
        $mapa = ModoBaseConocimiento::obtenerMapaProblemasPosturales();

        foreach ($mapa as $campo => $descripcion) {
            $valor = $testPostural[$campo] ?? 'ninguna';
            if ($valor === 'moderada' || $valor === 'severa') {
                $problemas[] = $descripcion;
            }
        }

        return $problemas;
    }
}
