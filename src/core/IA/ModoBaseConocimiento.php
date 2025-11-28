<?php

namespace Gymsys\Core\IA;

/**
 * GESTOR DE MODO DE BASE DE CONOCIMIENTO - MOTOR IA v3.0
 * 
 * Clase que determina qué fuente de conocimiento usar:
 * - ARCHIVO: BaseConocimientoAtleta.php (Fase 2)
 * - BD: BaseConocimientoBD.php conectado a gymsys_kb (Fase 3)
 * 
 * @version 3.0
 * @author GymSys Development Team
 */
class ModoBaseConocimiento
{
    /**
     * Determina si se debe usar la base de conocimiento desde BD
     * 
     * @return bool True si se usa BD, False si se usa archivo
     */
    public static function usarBD(): bool
    {
        // Verificar variable de entorno
        $modo = $_ENV['IA_KB_ORIGEN'] ?? 'bd';
        
        // Si está configurado para BD, verificar que esté disponible
        if (strtolower($modo) === 'bd') {
            // Intentar conectar
            if (BaseConocimientoBD::estaDisponible()) {
                return true;
            }
            
            // Si BD no está disponible, hacer fallback a archivo
            error_log('[MOTOR_IA] BD de conocimiento no disponible, usando fallback a archivo');
            return false;
        }
        
        return false;
    }
    
    /**
     * Determina si se debe usar la base de conocimiento desde archivo
     * 
     * @return bool True si se usa archivo
     */
    public static function usarArchivo(): bool
    {
        return !self::usarBD();
    }
    
    /**
     * Obtiene la fuente activa de conocimiento
     * 
     * @return string 'bd' o 'archivo'
     */
    public static function obtenerFuenteActiva(): string
    {
        return self::usarBD() ? 'bd' : 'archivo';
    }
    
    /**
     * Obtiene ponderaciones desde la fuente activa
     * 
     * @return array
     */
    public static function obtenerPonderaciones(): array
    {
        if (self::usarBD()) {
            try {
                return BaseConocimientoBD::obtenerPonderaciones();
            } catch (\Exception $e) {
                error_log('[MOTOR_IA] Error obteniendo ponderaciones desde BD: ' . $e->getMessage());
                return BaseConocimientoAtleta::obtenerPonderaciones();
            }
        }
        
        return BaseConocimientoAtleta::obtenerPonderaciones();
    }
    
    /**
     * Obtiene umbrales desde la fuente activa
     * 
     * @return array
     */
    public static function obtenerUmbralesRiesgo(): array
    {
        if (self::usarBD()) {
            try {
                return BaseConocimientoBD::obtenerUmbralesRiesgo();
            } catch (\Exception $e) {
                error_log('[MOTOR_IA] Error obteniendo umbrales desde BD: ' . $e->getMessage());
                return BaseConocimientoAtleta::obtenerUmbralesRiesgo();
            }
        }
        
        return BaseConocimientoAtleta::obtenerUmbralesRiesgo();
    }
    
    /**
     * Obtiene reglas FMS desde la fuente activa
     * 
     * @return array
     */
    public static function obtenerReglasFMS(): array
    {
        if (self::usarBD()) {
            try {
                return BaseConocimientoBD::obtenerReglasFMS();
            } catch (\Exception $e) {
                error_log('[MOTOR_IA] Error obteniendo reglas FMS desde BD: ' . $e->getMessage());
                return BaseConocimientoAtleta::obtenerReglasFMS();
            }
        }
        
        return BaseConocimientoAtleta::obtenerReglasFMS();
    }
    
    /**
     * Obtiene reglas posturales desde la fuente activa
     * 
     * @return array
     */
    public static function obtenerReglasPostural(): array
    {
        if (self::usarBD()) {
            try {
                return BaseConocimientoBD::obtenerReglasPostural();
            } catch (\Exception $e) {
                error_log('[MOTOR_IA] Error obteniendo reglas posturales desde BD: ' . $e->getMessage());
                return BaseConocimientoAtleta::obtenerReglasPostural();
            }
        }
        
        return BaseConocimientoAtleta::obtenerReglasPostural();
    }
    
    /**
     * Obtiene reglas de lesiones desde la fuente activa
     * 
     * @return array
     */
    public static function obtenerReglasLesiones(): array
    {
        if (self::usarBD()) {
            try {
                return BaseConocimientoBD::obtenerReglasLesiones();
            } catch (\Exception $e) {
                error_log('[MOTOR_IA] Error obteniendo reglas de lesiones desde BD: ' . $e->getMessage());
                return BaseConocimientoAtleta::obtenerReglasLesiones();
            }
        }
        
        return BaseConocimientoAtleta::obtenerReglasLesiones();
    }
    
    /**
     * Obtiene reglas de asistencia desde la fuente activa
     * 
     * @return array
     */
    public static function obtenerReglasAsistencia(): array
    {
        if (self::usarBD()) {
            try {
                return BaseConocimientoBD::obtenerReglasAsistencia();
            } catch (\Exception $e) {
                error_log('[MOTOR_IA] Error obteniendo reglas de asistencia desde BD: ' . $e->getMessage());
                return BaseConocimientoAtleta::obtenerReglasAsistencia();
            }
        }
        
        return BaseConocimientoAtleta::obtenerReglasAsistencia();
    }
    
    /**
     * Obtiene reglas de ausencia de datos desde la fuente activa
     * 
     * @return array
     */
    public static function obtenerReglasAusenciaDatos(): array
    {
        if (self::usarBD()) {
            try {
                return BaseConocimientoBD::obtenerReglasAusenciaDatos();
            } catch (\Exception $e) {
                error_log('[MOTOR_IA] Error obteniendo reglas de ausencia desde BD: ' . $e->getMessage());
                return BaseConocimientoAtleta::obtenerReglasAusenciaDatos();
            }
        }
        
        return BaseConocimientoAtleta::obtenerReglasAusenciaDatos();
    }
    
    /**
     * Obtiene reglas compuestas desde la fuente activa
     * Solo disponible desde BD
     * 
     * @return array
     */
    public static function obtenerReglasCompuestas(): array
    {
        if (self::usarBD()) {
            try {
                return BaseConocimientoBD::obtenerReglasCompuestas();
            } catch (\Exception $e) {
                error_log('[MOTOR_IA] Error obteniendo reglas compuestas desde BD: ' . $e->getMessage());
            }
        }
        
        return []; // Las reglas compuestas solo existen en BD
    }
    
    /**
     * Obtiene reglas de tendencia desde la fuente activa
     * Solo disponible desde BD
     * 
     * @return array
     */
    public static function obtenerReglasTendencia(): array
    {
        if (self::usarBD()) {
            try {
                return BaseConocimientoBD::obtenerReglasTendencia();
            } catch (\Exception $e) {
                error_log('[MOTOR_IA] Error obteniendo reglas de tendencia desde BD: ' . $e->getMessage());
            }
        }
        
        return []; // Las reglas de tendencia solo existen en BD
    }
    
    /**
     * Obtiene reglas por perfil desde la fuente activa
     * Solo disponible desde BD
     * 
     * @return array
     */
    public static function obtenerReglasPerfil(): array
    {
        if (self::usarBD()) {
            try {
                return BaseConocimientoBD::obtenerReglasPerfil();
            } catch (\Exception $e) {
                error_log('[MOTOR_IA] Error obteniendo reglas de perfil desde BD: ' . $e->getMessage());
            }
        }
        
        return []; // Las reglas de perfil solo existen en BD
    }
    
    /**
     * Obtiene perfiles de atleta desde la fuente activa
     * Solo disponible desde BD
     * 
     * @return array
     */
    public static function obtenerPerfiles(): array
    {
        if (self::usarBD()) {
            try {
                return BaseConocimientoBD::obtenerPerfiles();
            } catch (\Exception $e) {
                error_log('[MOTOR_IA] Error obteniendo perfiles desde BD: ' . $e->getMessage());
            }
        }
        
        return [];
    }
    
    /**
     * Obtiene ponderación de gravedad de lesiones desde la fuente activa
     * 
     * @return array
     */
    public static function obtenerPonderacionGravedadLesiones(): array
    {
        if (self::usarBD()) {
            try {
                return BaseConocimientoBD::obtenerPonderacionGravedadLesiones();
            } catch (\Exception $e) {
                error_log('[MOTOR_IA] Error obteniendo ponderación de lesiones desde BD: ' . $e->getMessage());
                return BaseConocimientoAtleta::obtenerPonderacionGravedadLesiones();
            }
        }
        
        return BaseConocimientoAtleta::obtenerPonderacionGravedadLesiones();
    }
    
    /**
     * Obtiene recomendaciones por nivel de riesgo desde la fuente activa
     * 
     * @return array
     */
    public static function obtenerRecomendacionesPorNivel(): array
    {
        if (self::usarBD()) {
            try {
                return BaseConocimientoBD::obtenerRecomendacionesPorNivel();
            } catch (\Exception $e) {
                error_log('[MOTOR_IA] Error obteniendo recomendaciones por nivel desde BD: ' . $e->getMessage());
                return BaseConocimientoAtleta::obtenerRecomendacionesPorNivel();
            }
        }
        
        return BaseConocimientoAtleta::obtenerRecomendacionesPorNivel();
    }
    
    /**
     * Obtiene mapa de problemas posturales desde la fuente activa
     * 
     * @return array
     */
    public static function obtenerMapaProblemasPosturales(): array
    {
        if (self::usarBD()) {
            try {
                return BaseConocimientoBD::obtenerMapaProblemasPosturales();
            } catch (\Exception $e) {
                error_log('[MOTOR_IA] Error obteniendo mapa postural desde BD: ' . $e->getMessage());
                return BaseConocimientoAtleta::obtenerMapaProblemasPosturales();
            }
        }
        
        return BaseConocimientoAtleta::obtenerMapaProblemasPosturales();
    }
    
    /**
     * Obtiene mapa de pruebas FMS desde la fuente activa
     * 
     * @return array
     */
    public static function obtenerMapaPruebasFMS(): array
    {
        if (self::usarBD()) {
            try {
                return BaseConocimientoBD::obtenerMapaPruebasFMS();
            } catch (\Exception $e) {
                error_log('[MOTOR_IA] Error obteniendo mapa FMS desde BD: ' . $e->getMessage());
                return BaseConocimientoAtleta::obtenerMapaPruebasFMS();
            }
        }
        
        return BaseConocimientoAtleta::obtenerMapaPruebasFMS();
    }
    
    /**
     * Obtiene reglas combinadas (interacciones entre módulos) desde la fuente activa
     * 
     * @return array
     */
    public static function obtenerReglasCombinadas(): array
    {
        if (self::usarBD()) {
            try {
                return BaseConocimientoBD::obtenerReglasCombinadas();
            } catch (\Exception $e) {
                error_log('[MOTOR_IA] Error obteniendo reglas combinadas desde BD: ' . $e->getMessage());
                return BaseConocimientoAtleta::obtenerReglasCombinadas();
            }
        }
        
        return BaseConocimientoAtleta::obtenerReglasCombinadas();
    }
    
    /**
     * Obtiene información sobre la fuente activa
     * 
     * @return array
     */
    public static function obtenerInfoFuente(): array
    {
        $fuente = self::obtenerFuenteActiva();
        $info = [
            'fuente' => $fuente,
            'disponible' => true
        ];
        
        if ($fuente === 'bd') {
            try {
                $info['version'] = BaseConocimientoBD::obtenerVersion();
                $info['descripcion'] = 'Base de conocimiento en BD gymsys_kb';
            } catch (\Exception $e) {
                $info['disponible'] = false;
                $info['error'] = $e->getMessage();
            }
        } else {
            $info['version'] = '2.0.0';
            $info['descripcion'] = 'Base de conocimiento en archivo PHP';
        }
        
        return $info;
    }
}
