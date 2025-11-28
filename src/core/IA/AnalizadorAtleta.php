<?php

namespace Gymsys\Core\IA;


class AnalizadorAtleta
{
    private $lesionesTemp;
    private $testPosturalTemp;
    private $asistenciasTemp;
    
    public function analizarAtleta(array $tarjetaAtleta): array
    {

        $datosAtleta = $tarjetaAtleta['atleta'] ?? [];
        $testFms = $tarjetaAtleta['ultimo_test_fms'] ?? null;
        $testPostural = $tarjetaAtleta['ultimo_test_postural'] ?? null;
        $lesiones = $tarjetaAtleta['lesiones_recientes'] ?? [];
        $resumenLesiones = $tarjetaAtleta['resumen_lesiones'] ?? [];
        $asistencias = $tarjetaAtleta['asistencias_30_dias'] ?? null;
        
        $this->lesionesTemp = $lesiones;
        $this->testPosturalTemp = $testPostural;
        $this->asistenciasTemp = $asistencias;


        $ponderaciones = ModoBaseConocimiento::obtenerPonderaciones();
        

        $modulosDisponibles = [];
        
        $tieneFms = ($testFms && isset($testFms['puntuacion_total']));
        $tienePostural = ($testPostural !== null);
        $tieneLesiones = true;
        $tieneAsistencia = ($asistencias && isset($asistencias['porcentaje_asistencia']) && $asistencias['total_sesiones'] > 0);
        
        if ($tieneFms) $modulosDisponibles[] = 'fms';
        if ($tienePostural) $modulosDisponibles[] = 'postural';
        if ($tieneLesiones) $modulosDisponibles[] = 'lesiones';
        if ($tieneAsistencia) $modulosDisponibles[] = 'asistencia';


        $riesgoFms = $tieneFms ? $this->calcularRiesgoFMS($testFms) : 0;
        $riesgoPostural = $tienePostural ? $this->calcularRiesgoPostural($testPostural) : 0;
        $riesgoLesiones = $this->calcularRiesgoLesiones($lesiones, $resumenLesiones);
        $riesgoAsistencia = $tieneAsistencia ? $this->calcularRiesgoAsistencia($asistencias) : 0;


        $riesgoScore = $this->calcularScoreFinal(
            $riesgoFms, 
            $riesgoPostural, 
            $riesgoLesiones, 
            $riesgoAsistencia,
            $tieneFms,
            $tienePostural,
            $tieneAsistencia
        );
        

        $pesoMaximoPosible = 100;


        $riesgoNivel = $this->clasificarRiesgo($riesgoScore);


        if ($this->aplicarReglaCombinadadaCritica($testFms, $lesiones, $tieneFms)) {
            $riesgoNivel = 'alto';
        }


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

    
    private function calcularRiesgoFMS($testFms): int
    {
        if (!$testFms || !isset($testFms['puntuacion_total'])) {
            return 0;
        }

        $puntuacion = (int) $testFms['puntuacion_total'];
        $reglas = ModoBaseConocimiento::obtenerReglasFMS();


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

        return 0;
    }

    
    private function calcularRiesgoPostural($testPostural): int
    {
        if (!$testPostural) {
            return 0;
        }

        $problemas = $this->contarProblemasPosturales($testPostural);
        $reglas = ModoBaseConocimiento::obtenerReglasPostural();


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

        return 0;
    }

    
    private function calcularRiesgoLesiones(array $lesiones, array $resumenLesiones): int
    {
        $riesgo = 0;
        $ponderacionGravedad = ModoBaseConocimiento::obtenerPonderacionGravedadLesiones();


        $lesionesActivas = array_filter($lesiones, function($lesion) {
            return $lesion['estado_lesion'] === 'Activa';
        });

        foreach ($lesionesActivas as $lesion) {
            $gravedad = strtolower($lesion['gravedad'] ?? 'leve');
            $riesgo += $ponderacionGravedad[$gravedad] ?? 5;
        }


        $riesgo = min(30, $riesgo);


        $hayLesionReciente = $this->hayLesionEnUltimos30Dias($lesiones);
        if ($hayLesionReciente && $riesgo < 30) {
            $riesgo = min(30, $riesgo + 10);
        }

        return $riesgo;
    }

    
    private function calcularRiesgoAsistencia($asistencias): int
    {
        if (!$asistencias || !isset($asistencias['porcentaje_asistencia'])) {
            return 0;
        }

        $porcentaje = (float) $asistencias['porcentaje_asistencia'];
        $reglas = ModoBaseConocimiento::obtenerReglasAsistencia();


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

        return 0;
    }

    
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

    
    private function clasificarRiesgo(int $score): string
    {
        $umbrales = ModoBaseConocimiento::obtenerUmbralesRiesgo();
        
        foreach ($umbrales as $nivel => $rango) {
            if ($score >= $rango['min'] && $score <= $rango['max']) {
                return $nivel;
            }
        }
        
        return 'medio';
    }

    
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


        

        if (!$tieneFms) {
            foreach ($reglasFaltantes as $regla) {
                if ($regla['modulo'] === 'fms') {
                    $factores[] = $regla['factor_mensaje'];
                    break;
                }
            }
        }
        

        if (!$tienePostural) {
            foreach ($reglasFaltantes as $regla) {
                if ($regla['modulo'] === 'postural') {
                    $factores[] = $regla['factor_mensaje'];
                    break;
                }
            }
        }
        

        if (!$tieneAsistencia) {
            foreach ($reglasFaltantes as $regla) {
                if ($regla['modulo'] === 'asistencia') {
                    $factores[] = $regla['factor_mensaje'];
                    break;
                }
            }
        }


        

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
                        break;
                    }
                }
            }
        }


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
                if ($regla['id'] !== 'R23_HISTORIAL_LESIONES') break;
            }
        }


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


        

        if ($tieneFms && $testFms) {
            $puntuacionFms = (int) $testFms['puntuacion_total'];
            
            if ($puntuacionFms <= 12) {

                foreach ($lesionesActivas as $lesion) {
                    $gravedad = ucfirst(strtolower($lesion['gravedad'] ?? 'desconocida'));
                    $zona = $lesion['zona_afectada'] ?? 'no especificada';
                    
                    if ($gravedad === 'Moderada' || $gravedad === 'Severa' || $gravedad === 'Grave') {

                        $factorCritico = "Combinación crítica: FMS muy bajo ({$puntuacionFms}/21) junto con lesión activa de gravedad {$gravedad} en {$zona}. Esto incrementa significativamente el riesgo de recaída o nueva lesión debido a la debilidad en patrones fundamentales de movimiento.";
                        array_unshift($factores, $factorCritico);
                        break;
                    }
                }
            }
        }
        
        $reglasCombinadas = ModoBaseConocimiento::obtenerReglasCombinadas();
        
        foreach ($reglasCombinadas as $reglaCombinada) {
            $todasCondicionesCumplen = true;
            
            foreach ($reglaCombinada['condiciones'] as $condicion) {
                $moduloCumple = false;
                

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
            

            if ($todasCondicionesCumplen) {
                $factores[] = $reglaCombinada['factor_mensaje'];
            }
        }


        if (empty($factores)) {
            $factores[] = "Perfil de riesgo general dentro de parámetros normales. Mantener seguimiento preventivo.";
        }

        return $factores;
    }

    
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

    
    private function esLesionReciente(array $lesion): bool
    {
        $hace30Dias = new \DateTime('-30 days');
        $fechaLesion = new \DateTime($lesion['fecha_lesion']);
        return $fechaLesion >= $hace30Dias;
    }

    
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

        $recomendacionesEstructuradas = [];
        
        $recomendacionesPorNivel = ModoBaseConocimiento::obtenerRecomendacionesPorNivel();
        $reglasFaltantes = ModoBaseConocimiento::obtenerReglasAusenciaDatos();


        $lesionesActivas = array_filter($lesiones, fn($l) => $l['estado_lesion'] === 'Activa');
        
        if ($tieneFms && $testFms) {
            $puntuacionFms = (int) $testFms['puntuacion_total'];
            
            if ($puntuacionFms <= 12 && !empty($lesionesActivas)) {
                foreach ($lesionesActivas as $lesion) {
                    $gravedad = strtolower($lesion['gravedad'] ?? '');
                    $zona = $lesion['zona_afectada'] ?? 'no especificada';
                    
                    if ($gravedad === 'moderada' || $gravedad === 'severa' || $gravedad === 'grave') {

                        $recomendacionesEstructuradas[] = [
                            'texto' => "🔴 PRIORIDAD ALTA: Ajustar temporalmente la carga de entrenamiento (volumen e intensidad), priorizando ejercicios que no agraven la lesión en {$zona}. Evitar movimientos explosivos o con impacto directo sobre la zona afectada.",
                            'prioridad' => 1,
                            'tipo' => 'especifica',
                            'origen' => 'combinacion_critica'
                        ];
                        
                        $pruebasBajas = $this->identificarPruebasFmsDeficientes($testFms);
                        if (!empty($pruebasBajas)) {
                            $pruebasTexto = implode(', ', $pruebasBajas);
                            $recomendacionesEstructuradas[] = [
                                'texto' => "Reforzar el trabajo de movilidad y estabilidad en los patrones donde el FMS salió más bajo ({$pruebasTexto}), utilizando progresiones controladas y supervisadas antes de aumentar cargas.",
                                'prioridad' => 1,
                                'tipo' => 'especifica',
                                'origen' => 'combinacion_critica'
                            ];
                        }
                        
                        $recomendacionesEstructuradas[] = [
                            'texto' => "Implementar un programa de rehabilitación funcional que integre ejercicios correctivos para mejorar los patrones de movimiento deficientes mientras la lesión en {$zona} se recupera completamente.",
                            'prioridad' => 1,
                            'tipo' => 'especifica',
                            'origen' => 'combinacion_critica'
                        ];
                        break;
                    }
                }
            }
        }


        $reglasCombinadas = ModoBaseConocimiento::obtenerReglasCombinadas();
        
        foreach ($reglasCombinadas as $reglaCombinada) {
            $todasCondicionesCumplen = true;
            
            foreach ($reglaCombinada['condiciones'] as $condicion) {
                $moduloCumple = false;
                
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
            
            if ($todasCondicionesCumplen && !empty($reglaCombinada['recomendaciones'])) {
                foreach ($reglaCombinada['recomendaciones'] as $rec) {
                    $recomendacionesEstructuradas[] = [
                        'texto' => $rec,
                        'prioridad' => 1,
                        'tipo' => 'especifica',
                        'origen' => 'combinada'
                    ];
                }
            }
        }


        if (count($lesionesActivas) > 0) {
            $reglasLesiones = ModoBaseConocimiento::obtenerReglasLesiones();
            foreach ($reglasLesiones as $regla) {
                if (($regla['id'] === 'R20_LESIONES_MULTIPLES_ACTIVAS' && count($lesionesActivas) > 1) ||
                    ($regla['id'] === 'R21_LESION_ACTIVA_UNICA' && count($lesionesActivas) === 1)) {
                    foreach ($regla['recomendaciones'] as $rec) {
                        $esEspecifica = (strpos($rec, 'zona') !== false || strpos($rec, 'lesión') !== false);
                        $recomendacionesEstructuradas[] = [
                            'texto' => $rec,
                            'prioridad' => $esEspecifica ? 1 : 2,
                            'tipo' => $esEspecifica ? 'especifica' : 'media',
                            'origen' => 'lesiones'
                        ];
                    }
                    break;
                }
            }
        }


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
                    $prioridadFms = ($puntuacionFms <= 12) ? 1 : 2;
                    foreach ($regla['recomendaciones'] as $rec) {
                        $recomendacionesEstructuradas[] = [
                            'texto' => $rec,
                            'prioridad' => $prioridadFms,
                            'tipo' => 'especifica',
                            'origen' => 'fms'
                        ];
                    }
                    

                    if ($puntuacionFms <= 14) {
                        $pruebasBajas = $this->identificarPruebasFmsDeficientes($testFms);
                        if (!empty($pruebasBajas)) {
                            $recomendacionesEstructuradas[] = [
                                'texto' => "Foco en: " . implode(', ', $pruebasBajas) . ".",
                                'prioridad' => 1,
                                'tipo' => 'especifica',
                                'origen' => 'fms'
                            ];
                        }
                    }
                    break;
                }
            }
        }


        if ($tienePostural) {
            $problemasEspecificos = $this->analizarProblemasPosturalesEspecificos($testPostural);
            if (!empty($problemasEspecificos)) {
                $recomendacionesEstructuradas[] = [
                    'texto' => "Trabajo postural enfocado en: " . implode(', ', $problemasEspecificos) . ".",
                    'prioridad' => 2,
                    'tipo' => 'especifica',
                    'origen' => 'postural'
                ];
            }
        }


        if ($this->hayLesionEnUltimos30Dias($lesiones) && count($lesionesActivas) === 0) {
            $reglasLesiones = ModoBaseConocimiento::obtenerReglasLesiones();
            foreach ($reglasLesiones as $regla) {
                if ($regla['id'] === 'R22_LESION_RECIENTE') {
                    foreach ($regla['recomendaciones'] as $rec) {
                        $recomendacionesEstructuradas[] = [
                            'texto' => $rec,
                            'prioridad' => 2,
                            'tipo' => 'media',
                            'origen' => 'lesiones'
                        ];
                    }
                    break;
                }
            }
        }


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
                    $prioridadAsist = ($porcentaje < 50) ? 2 : 3;
                    foreach ($regla['recomendaciones'] as $rec) {
                        $recomendacionesEstructuradas[] = [
                            'texto' => $rec,
                            'prioridad' => $prioridadAsist,
                            'tipo' => 'generica',
                            'origen' => 'asistencia'
                        ];
                    }
                    break;
                }
            }
        }


        if (isset($recomendacionesPorNivel[$nivelRiesgo])) {
            foreach ($recomendacionesPorNivel[$nivelRiesgo] as $rec) {
                $recomendacionesEstructuradas[] = [
                    'texto' => $rec,
                    'prioridad' => 3,
                    'tipo' => 'generica',
                    'origen' => 'nivel_riesgo'
                ];
            }
        }


        if (!$tieneFms) {
            foreach ($reglasFaltantes as $regla) {
                if ($regla['modulo'] === 'fms') {
                    foreach ($regla['recomendaciones'] as $rec) {
                        $recomendacionesEstructuradas[] = [
                            'texto' => $rec,
                            'prioridad' => 3,
                            'tipo' => 'generica',
                            'origen' => 'datos_faltantes'
                        ];
                    }
                    break;
                }
            }
        }
        
        if (!$tienePostural) {
            foreach ($reglasFaltantes as $regla) {
                if ($regla['modulo'] === 'postural') {
                    foreach ($regla['recomendaciones'] as $rec) {
                        $recomendacionesEstructuradas[] = [
                            'texto' => $rec,
                            'prioridad' => 3,
                            'tipo' => 'generica',
                            'origen' => 'datos_faltantes'
                        ];
                    }
                    break;
                }
            }
        }


        return $this->optimizarRecomendaciones($recomendacionesEstructuradas, $nivelRiesgo, $lesionesActivas);
    }

    
    private function optimizarRecomendaciones(array $recomendacionesEstructuradas, string $nivelRiesgo, array $lesionesActivas): array
    {

        $textos = [];
        $recomendacionesUnicas = [];
        foreach ($recomendacionesEstructuradas as $rec) {
            $textoLimpio = trim($rec['texto']);
            if (!in_array($textoLimpio, $textos)) {
                $textos[] = $textoLimpio;
                $recomendacionesUnicas[] = $rec;
            }
        }


        usort($recomendacionesUnicas, function($a, $b) {
            if ($a['prioridad'] !== $b['prioridad']) {
                return $a['prioridad'] - $b['prioridad'];
            }

            if ($a['tipo'] === 'especifica' && $b['tipo'] !== 'especifica') return -1;
            if ($a['tipo'] !== 'especifica' && $b['tipo'] === 'especifica') return 1;
            return 0;
        });


        $prioridad1 = array_filter($recomendacionesUnicas, fn($r) => $r['prioridad'] === 1);
        $prioridad2 = array_filter($recomendacionesUnicas, fn($r) => $r['prioridad'] === 2);
        $prioridad3 = array_filter($recomendacionesUnicas, fn($r) => $r['prioridad'] === 3);


        $recomendacionesFinales = [];


        foreach ($prioridad1 as $rec) {
            $recomendacionesFinales[] = $rec['texto'];
        }

        $espacioRestante = 10 - count($recomendacionesFinales);


        $maxPrioridad2 = min(3, $espacioRestante);
        $contador = 0;
        foreach ($prioridad2 as $rec) {
            if ($contador >= $maxPrioridad2) break;
            $recomendacionesFinales[] = $rec['texto'];
            $contador++;
        }

        $espacioRestante = 10 - count($recomendacionesFinales);


        if ($espacioRestante > 0 && count($prioridad3) > 0) {
            $recomendacionesGlobales = $this->generarRecomendacionesGlobales(
                $prioridad3, 
                $nivelRiesgo, 
                $lesionesActivas,
                min(2, $espacioRestante)
            );
            
            foreach ($recomendacionesGlobales as $recGlobal) {
                $recomendacionesFinales[] = $recGlobal;
            }
        }


        return array_slice($recomendacionesFinales, 0, 10);
    }

    
    private function generarRecomendacionesGlobales(array $recomendacionesGenericas, string $nivelRiesgo, array $lesionesActivas, int $maxBloques): array
    {
        $bloques = [];
        

        $temasCarga = ['carga', 'volumen', 'intensidad', 'progresión', 'técnica', 'control'];
        $temasAsistencia = ['asistencia', 'adherencia', 'horarios', 'sesiones', 'constancia'];
        $temasMonitoreo = ['monitoreo', 'evaluación', 'seguimiento', 'reevaluar', 'control'];
        
        $recsProgresion = [];
        $recsAsistencia = [];
        $recsMonitoreo = [];
        $recsOtras = [];
        
        foreach ($recomendacionesGenericas as $rec) {
            $texto = strtolower($rec['texto']);
            $clasificada = false;
            
            foreach ($temasCarga as $palabra) {
                if (strpos($texto, $palabra) !== false) {
                    $recsProgresion[] = $rec['texto'];
                    $clasificada = true;
                    break;
                }
            }
            
            if (!$clasificada) {
                foreach ($temasAsistencia as $palabra) {
                    if (strpos($texto, $palabra) !== false) {
                        $recsAsistencia[] = $rec['texto'];
                        $clasificada = true;
                        break;
                    }
                }
            }
            
            if (!$clasificada) {
                foreach ($temasMonitoreo as $palabra) {
                    if (strpos($texto, $palabra) !== false) {
                        $recsMonitoreo[] = $rec['texto'];
                        $clasificada = true;
                        break;
                    }
                }
            }
            
            if (!$clasificada) {
                $recsOtras[] = $rec['texto'];
            }
        }
        

        if ((count($recsProgresion) > 0 || count($lesionesActivas) > 0) && $nivelRiesgo !== 'bajo') {
            $zonaLesion = '';
            if (!empty($lesionesActivas)) {
                $zonaLesion = $lesionesActivas[0]['zona_afectada'] ?? '';
                $zonaLesion = $zonaLesion ? " especialmente en ejercicios que involucren {$zonaLesion}" : '';
            }
            
            $bloques[] = "Mantener un enfoque progresivo en la carga de entrenamiento, revisando técnica y controlando síntomas durante las próximas 4-6 semanas. Evitar aumentos bruscos de volumen e intensidad{$zonaLesion}, priorizando la calidad del movimiento sobre la cantidad de trabajo.";
        }
        

        if (count($recsAsistencia) > 0 && count($bloques) < $maxBloques) {
            $bloques[] = "Coordinar con el atleta ajustes realistas en horarios, metas de asistencia y disponibilidad, priorizando la adherencia sostenida al plan correctivo antes que la cantidad total de sesiones. Identificar y resolver barreras que dificulten la constancia.";
        }
        

        if (count($recsMonitoreo) > 0 && count($bloques) < $maxBloques) {
            $bloques[] = "Realizar evaluaciones funcionales periódicas (cada 4-6 semanas) para monitorear progreso en patrones de movimiento, rango articular y control neuromuscular. Ajustar el plan según la evolución observada y respuesta del atleta.";
        }
        

        if (empty($bloques) && count($recomendacionesGenericas) > 0) {
            $bloques[] = "Mantener una comunicación constante con el atleta sobre síntomas, molestias y progreso percibido. Ajustar el programa de entrenamiento de forma individualizada según la respuesta y necesidades específicas.";
        }
        
        return $bloques;
    }

    
    private function contarProblemasPosturales(array $testPostural): int
    {
        $problemas = 0;
        
        $pesosCriticos = [
            'cifosis_dorsal' => ['severa' => 2.5, 'moderada' => 1.2],
            'lordosis_lumbar' => ['severa' => 2.5, 'moderada' => 1.2],
            'escoliosis' => ['severa' => 2.3, 'moderada' => 1.2],
            'inclinacion_pelvis' => ['severa' => 2.0, 'moderada' => 1.0],
            'valgo_rodilla' => ['severa' => 2.2, 'moderada' => 1.1],
            'varo_rodilla' => ['severa' => 2.2, 'moderada' => 1.1],
            'rotacion_hombros' => ['severa' => 1.8, 'moderada' => 0.9],
            'desnivel_escapulas' => ['severa' => 1.8, 'moderada' => 0.9]
        ];

        foreach ($pesosCriticos as $campo => $pesos) {
            $valor = $testPostural[$campo] ?? 'ninguna';
            if ($valor === 'severa') {
                $problemas += $pesos['severa'];
            } elseif ($valor === 'moderada') {
                $problemas += $pesos['moderada'];
            }
        }

        return (int) round($problemas);
    }

    
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

    
    private function calcularScoreFinal(
        int $riesgoFms,
        int $riesgoPostural,
        int $riesgoLesiones,
        int $riesgoAsistencia,
        bool $tieneFms,
        bool $tienePostural,
        bool $tieneAsistencia
    ): int {
        $lesiones = $this->lesionesTemp ?? [];
        $testPostural = $this->testPosturalTemp ?? null;
        $asistencias = $this->asistenciasTemp ?? null;
        $modulosCriticos = ($tieneFms ? 1 : 0) + ($tienePostural ? 1 : 0);
        
        if ($modulosCriticos === 0) {
            $fmsAsumido = 10;
            $posturalAsumido = 10;
        } elseif ($modulosCriticos === 1) {
            $fmsAsumido = !$tieneFms ? 8 : 0;
            $posturalAsumido = !$tienePostural ? 8 : 0;
        } else {
            $fmsAsumido = 0;
            $posturalAsumido = 0;
        }
        
        $riesgoFmsFinal = $tieneFms ? $riesgoFms : $fmsAsumido;
        $riesgoPosturalFinal = $tienePostural ? $riesgoPostural : $posturalAsumido;
        $riesgoAsistenciaFinal = $tieneAsistencia ? $riesgoAsistencia : 3;
        
        $sumaTotal = $riesgoFmsFinal + $riesgoPosturalFinal + $riesgoLesiones + $riesgoAsistenciaFinal;
        
        $hayRiesgoCritico = ($riesgoFmsFinal >= 20 || $riesgoPosturalFinal >= 30 || $riesgoLesiones >= 20);
        
        if ($hayRiesgoCritico && $modulosCriticos === 2) {
            $sumaTotal = $sumaTotal * 1.20;
        } elseif ($hayRiesgoCritico && $modulosCriticos === 1) {
            $sumaTotal = $sumaTotal * 1.12;
        }
        
        if ($this->hayCorrelacionPosturalLesion($testPostural, $lesiones)) {
            $sumaTotal = $sumaTotal * 1.15;
        }
        
        $factorAsistencia = $this->calcularFactorAsistencia($tieneAsistencia, $asistencias, $riesgoPosturalFinal, $riesgoFmsFinal);
        $sumaTotal = $sumaTotal * $factorAsistencia;
        
        $denominadorBase = 90;
        
        $riesgoScore = round(($sumaTotal / $denominadorBase) * 100);
        $riesgoScore = min(100, max(0, $riesgoScore));
        
        return $riesgoScore;
    }

    
    private function aplicarReglaCombinadadaCritica(?array $testFms, array $lesiones, bool $tieneFms): bool
    {

        if (!$tieneFms || empty($testFms)) {
            return false;
        }


        $puntuacionFms = $testFms['puntuacion_total'] ?? 0;
        if ($puntuacionFms > 12) {
            return false;
        }


        foreach ($lesiones as $lesion) {
            $esActiva = empty($lesion['fecha_recuperacion']);
            $gravedad = strtolower($lesion['gravedad'] ?? '');
            
            if ($esActiva && ($gravedad === 'moderada' || $gravedad === 'severa' || $gravedad === 'grave')) {

                return true;
            }
        }

        return false;
    }

    private function hayCorrelacionPosturalLesion($testPostural, array $lesiones): bool
    {
        if (!$testPostural || empty($lesiones)) {
            return false;
        }

        $lesionesActivas = array_filter($lesiones, fn($l) => $l['estado_lesion'] === 'Activa');
        if (empty($lesionesActivas)) {
            return false;
        }

        $correlaciones = [
            'cifosis_dorsal' => ['dorsal', 'cervical', 'cuello', 'hombro', 'espalda alta'],
            'lordosis_lumbar' => ['lumbar', 'espalda baja', 'cadera'],
            'escoliosis' => ['lumbar', 'dorsal', 'espalda'],
            'inclinacion_pelvis' => ['lumbar', 'cadera', 'pelvis'],
            'valgo_rodilla' => ['rodilla', 'menisco', 'ligamento'],
            'varo_rodilla' => ['rodilla', 'menisco', 'ligamento'],
            'rotacion_hombros' => ['hombro', 'manguito', 'deltoides'],
            'desnivel_escapulas' => ['hombro', 'escapula', 'dorsal']
        ];

        foreach ($correlaciones as $campo => $zonasRelacionadas) {
            $valor = $testPostural[$campo] ?? 'ninguna';
            if ($valor === 'severa' || $valor === 'moderada') {
                foreach ($lesionesActivas as $lesion) {
                    $zona = strtolower($lesion['zona_afectada'] ?? '');
                    foreach ($zonasRelacionadas as $palabraClave) {
                        if (strpos($zona, $palabraClave) !== false) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    private function calcularFactorAsistencia($tieneAsistencia, $asistencias, int $riesgoPostural, int $riesgoFms): float
    {
        if (!$tieneAsistencia || !$asistencias) {
            return 1.0;
        }

        $porcentaje = (float) ($asistencias['porcentaje_asistencia'] ?? 80);
        $hayProblemas = ($riesgoPostural >= 20 || $riesgoFms >= 15);

        if (!$hayProblemas) {
            return 1.0;
        }

        if ($porcentaje < 50) {
            return 1.15;
        } elseif ($porcentaje < 70) {
            return 1.08;
        } elseif ($porcentaje >= 85) {
            return 0.95;
        }

        return 1.0;
    }
}
