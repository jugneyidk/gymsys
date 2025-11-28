<?php

namespace Gymsys\Core\IA;

use Gymsys\Core\Database;
use Gymsys\Model\Notificaciones;

/**
 * NOTIFICADOR DE RIESGO IA - MOTOR IA v3.0
 * 
 * Integra el análisis de riesgo del Motor IA con el sistema 
 * de notificaciones existente de GymSys.
 * 
 * Genera notificaciones inteligentes cuando:
 * - Atleta presenta riesgo ALTO
 * - Atleta presenta riesgo MEDIO con patrones negativos
 * 
 * Evita spam mediante control de frecuencia.
 * 
 * @version 3.0
 * @author GymSys Development Team
 */
class NotificadorRiesgoIA
{
    private Database $database;
    private const DIAS_COOLDOWN = 7; // Días entre notificaciones del mismo tipo para mismo atleta
    
    public function __construct(Database $database)
    {
        $this->database = $database;
    }
    
    /**
     * Procesa el análisis IA y genera notificaciones si es necesario
     * 
     * @param array $datosAtleta Datos básicos del atleta (nombre, apellido, cedula, entrenador)
     * @param array $analisisIA Resultado del análisis IA completo
     * @return bool True si se generó notificación
     */
    public function procesarYNotificar(array $datosAtleta, array $analisisIA): bool
    {
        $nivelRiesgo = $analisisIA['riesgo_nivel'] ?? 'bajo';
        $score = $analisisIA['riesgo_score'] ?? 0;
        $factores = $analisisIA['factores_clave'] ?? [];
        
        // Solo notificar si hay riesgo significativo
        if ($nivelRiesgo === 'bajo') {
            return false;
        }
        
        $cedulaAtleta = $datosAtleta['cedula'] ?? null;
        $entrenador = $datosAtleta['entrenador'] ?? null;
        
        if (!$cedulaAtleta || !$entrenador) {
            return false;
        }
        
        // Verificar si ya existe notificación reciente (evitar spam)
        if ($this->existeNotificacionReciente($cedulaAtleta, $entrenador)) {
            return false;
        }
        
        // Determinar si debe notificar según nivel y patrones
        $debeNotificar = false;
        $titulo = '';
        $mensaje = '';
        
        if ($nivelRiesgo === 'alto') {
            $debeNotificar = true;
            $titulo = '🔴 Alerta de Riesgo Alto de Lesión';
            $mensaje = $this->construirMensajeRiesgoAlto($datosAtleta, $score, $factores);
            
        } elseif ($nivelRiesgo === 'medio') {
            // Solo notificar riesgo medio si hay patrones específicos preocupantes
            $patronesPreocupantes = $this->detectarPatronesPreocupantes($factores, $analisisIA);
            if ($patronesPreocupantes) {
                $debeNotificar = true;
                $titulo = '🟡 Alerta de Riesgo Moderado - Atención Requerida';
                $mensaje = $this->construirMensajeRiesgoMedio($datosAtleta, $score, $factores, $patronesPreocupantes);
            }
        }
        
        if ($debeNotificar) {
            return $this->crearNotificacion($entrenador, $titulo, $mensaje, $cedulaAtleta);
        }
        
        return false;
    }
    
    /**
     * Verifica si existe una notificación reciente de IA para este atleta
     * 
     * @param string $cedulaAtleta Cédula del atleta
     * @param string $entrenador Cédula del entrenador
     * @return bool
     */
    private function existeNotificacionReciente(string $cedulaAtleta, string $entrenador): bool
    {
        $consulta = "SELECT COUNT(*) as total 
                     FROM {$_ENV['SECURE_DB']}.notificaciones 
                     WHERE id_usuario = :entrenador 
                     AND objetivo = 'ia_riesgo'
                     AND mensaje LIKE :patron
                     AND fecha_creacion >= DATE_SUB(NOW(), INTERVAL :dias DAY)";
        
        $patron = '%' . $cedulaAtleta . '%';
        $valores = [
            ':entrenador' => $entrenador,
            ':patron' => $patron,
            ':dias' => self::DIAS_COOLDOWN
        ];
        
        $resultado = $this->database->query($consulta, $valores, true);
        return ($resultado['total'] ?? 0) > 0;
    }
    
    /**
     * Detecta patrones preocupantes en riesgo medio
     * 
     * @param array $factores Factores de riesgo identificados
     * @param array $analisisIA Análisis completo
     * @return string|false Descripción del patrón o false si no hay
     */
    private function detectarPatronesPreocupantes(array $factores, array $analisisIA): string|false
    {
        // Patrón 1: Múltiples factores combinados (≥3)
        if (count($factores) >= 3) {
            return 'múltiples factores de riesgo combinados';
        }
        
        // Patrón 2: FMS crítico o muy bajo
        foreach ($factores as $factor) {
            if (stripos($factor, 'FMS crítica') !== false || stripos($factor, 'FMS baja') !== false) {
                return 'deficiencias severas en patrones de movimiento';
            }
        }
        
        // Patrón 3: Lesiones múltiples o recurrentes
        foreach ($factores as $factor) {
            if (stripos($factor, 'Múltiples lesiones activas') !== false || 
                stripos($factor, 'recurrente') !== false) {
                return 'patrón de lesiones múltiples o recurrentes';
            }
        }
        
        // Patrón 4: Problemas posturales severos
        foreach ($factores as $factor) {
            if (stripos($factor, 'alteraciones posturales severas') !== false ||
                stripos($factor, 'Múltiples alteraciones posturales') !== false) {
                return 'alteraciones biomecánicas significativas';
            }
        }
        
        // Patrón 5: Asistencia muy irregular con otros factores
        $tieneAsistenciaIrregular = false;
        foreach ($factores as $factor) {
            if (stripos($factor, 'Asistencia muy irregular') !== false) {
                $tieneAsistenciaIrregular = true;
                break;
            }
        }
        
        if ($tieneAsistenciaIrregular && count($factores) >= 2) {
            return 'inconsistencia de asistencia con otros factores de riesgo';
        }
        
        return false;
    }
    
    /**
     * Construye mensaje detallado para riesgo alto
     * 
     * @param array $datosAtleta Datos del atleta
     * @param int $score Score de riesgo
     * @param array $factores Factores identificados
     * @return string
     */
    private function construirMensajeRiesgoAlto(array $datosAtleta, int $score, array $factores): string
    {
        $nombre = trim(($datosAtleta['nombre'] ?? '') . ' ' . ($datosAtleta['apellido'] ?? ''));
        $cedula = $datosAtleta['cedula'] ?? 'N/A';
        
        $mensaje = "El atleta {$nombre} (CI: {$cedula}) presenta un RIESGO ALTO de lesión según el análisis del Motor IA.\n\n";
        $mensaje .= "📊 Score de Riesgo: {$score}/100\n\n";
        $mensaje .= "⚠️ Factores Críticos Identificados:\n";
        
        $numFactores = min(count($factores), 4); // Máximo 4 factores en notificación
        for ($i = 0; $i < $numFactores; $i++) {
            $mensaje .= "• " . $factores[$i] . "\n";
        }
        
        if (count($factores) > 4) {
            $mensaje .= "• ... y " . (count($factores) - 4) . " factores adicionales.\n";
        }
        
        $mensaje .= "\n🔴 ACCIÓN REQUERIDA:\n";
        $mensaje .= "• Revisar inmediatamente el análisis completo en la tarjeta del atleta.\n";
        $mensaje .= "• Considerar ajustes en el programa de entrenamiento.\n";
        $mensaje .= "• Implementar trabajo correctivo preventivo urgente.\n";
        $mensaje .= "• Evaluar derivación a profesional de salud deportiva si es necesario.";
        
        return $mensaje;
    }
    
    /**
     * Construye mensaje para riesgo medio con patrones preocupantes
     * 
     * @param array $datosAtleta Datos del atleta
     * @param int $score Score de riesgo
     * @param array $factores Factores identificados
     * @param string $patron Descripción del patrón preocupante
     * @return string
     */
    private function construirMensajeRiesgoMedio(array $datosAtleta, int $score, array $factores, string $patron): string
    {
        $nombre = trim(($datosAtleta['nombre'] ?? '') . ' ' . ($datosAtleta['apellido'] ?? ''));
        $cedula = $datosAtleta['cedula'] ?? 'N/A';
        
        $mensaje = "El atleta {$nombre} (CI: {$cedula}) presenta un RIESGO MODERADO de lesión con patrones que requieren atención.\n\n";
        $mensaje .= "📊 Score de Riesgo: {$score}/100\n";
        $mensaje .= "🔍 Patrón Detectado: {$patron}\n\n";
        $mensaje .= "⚠️ Factores Identificados:\n";
        
        $numFactores = min(count($factores), 3);
        for ($i = 0; $i < $numFactores; $i++) {
            $mensaje .= "• " . $factores[$i] . "\n";
        }
        
        if (count($factores) > 3) {
            $mensaje .= "• ... y " . (count($factores) - 3) . " factores adicionales.\n";
        }
        
        $mensaje .= "\n🟡 RECOMENDACIONES:\n";
        $mensaje .= "• Revisar análisis IA completo en tarjeta del atleta.\n";
        $mensaje .= "• Monitorear evolución en próximas sesiones.\n";
        $mensaje .= "• Considerar ajustes preventivos en el programa.\n";
        $mensaje .= "• Mantener comunicación cercana con el atleta sobre síntomas.";
        
        return $mensaje;
    }
    
    /**
     * Crea la notificación en el sistema usando el modelo existente
     * 
     * @param string $idEntrenador Cédula del entrenador
     * @param string $titulo Título de la notificación
     * @param string $mensaje Mensaje completo
     * @param string $cedulaAtleta Cédula del atleta (para referencia)
     * @return bool
     */
    private function crearNotificacion(string $idEntrenador, string $titulo, string $mensaje, string $cedulaAtleta): bool
    {
        try {
            $this->database->beginTransaction();
            
            $consulta = "INSERT INTO {$_ENV['SECURE_DB']}.notificaciones(id_usuario, titulo, mensaje, objetivo)
                         VALUES (:id_usuario, :titulo, :mensaje, :objetivo)";
            
            $valores = [
                ':id_usuario' => $idEntrenador,
                ':titulo' => $titulo,
                ':mensaje' => $mensaje,
                ':objetivo' => 'ia_riesgo'
            ];
            
            $this->database->query($consulta, $valores);
            $this->database->commit();
            
            return true;
            
        } catch (\Exception $e) {
            $this->database->rollBack();
            error_log('[NOTIFICADOR_IA] Error creando notificación: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Genera notificaciones masivas para todos los atletas con riesgo alto/medio preocupante
     * Útil para ejecución programada (cron job)
     * 
     * @return array Estadísticas de notificaciones generadas
     */
    public static function generarNotificacionesMasivas(Database $database): array
    {
        $notificador = new self($database);
        $analizador = new AnalizadorAtleta();
        
        $estadisticas = [
            'procesados' => 0,
            'notificaciones_generadas' => 0,
            'riesgo_alto' => 0,
            'riesgo_medio' => 0,
            'errores' => 0
        ];
        
        try {
            // Obtener todos los atletas activos con sus entrenadores
            $consulta = "SELECT DISTINCT 
                            a.cedula,
                            u.nombre,
                            u.apellido,
                            a.entrenador
                         FROM atleta a
                         INNER JOIN {$_ENV['SECURE_DB']}.usuarios u ON a.cedula = u.cedula
                         WHERE a.entrenador IS NOT NULL";
            
            $atletas = $database->query($consulta);
            
            if (!$atletas) {
                return $estadisticas;
            }
            
            foreach ($atletas as $datosAtleta) {
                try {
                    $estadisticas['procesados']++;
                    
                    // Obtener tarjeta del atleta (necesitaríamos acceso al modelo)
                    // Por ahora, esto debe ser llamado desde un controlador que tenga acceso al modelo
                    // Este es un placeholder
                    
                    // $tarjeta = $modeloEvaluaciones->obtenerTarjetaAtleta(['id_atleta' => $datosAtleta['cedula']]);
                    // $analisis = $analizador->analizarAtleta($tarjeta);
                    // 
                    // if ($notificador->procesarYNotificar($datosAtleta, $analisis)) {
                    //     $estadisticas['notificaciones_generadas']++;
                    //     if ($analisis['riesgo_nivel'] === 'alto') {
                    //         $estadisticas['riesgo_alto']++;
                    //     } else {
                    //         $estadisticas['riesgo_medio']++;
                    //     }
                    // }
                    
                } catch (\Exception $e) {
                    $estadisticas['errores']++;
                    error_log('[NOTIFICADOR_IA] Error procesando atleta ' . $datosAtleta['cedula'] . ': ' . $e->getMessage());
                }
            }
            
        } catch (\Exception $e) {
            error_log('[NOTIFICADOR_IA] Error en generación masiva: ' . $e->getMessage());
        }
        
        return $estadisticas;
    }
}
