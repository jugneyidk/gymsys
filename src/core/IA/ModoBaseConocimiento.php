<?php

namespace Gymsys\Core\IA;


class ModoBaseConocimiento
{
    
    public static function usarBD(): bool
    {

        $modo = $_ENV['IA_KB_ORIGEN'] ?? 'bd';
        

        if (strtolower($modo) === 'bd') {

            if (BaseConocimientoBD::estaDisponible()) {
                return true;
            }
            

            error_log('[MOTOR_IA] BD de conocimiento no disponible, usando fallback a archivo');
            return false;
        }
        
        return false;
    }
    
    
    public static function usarArchivo(): bool
    {
        return !self::usarBD();
    }
    
    
    public static function obtenerFuenteActiva(): string
    {
        return self::usarBD() ? 'bd' : 'archivo';
    }
    
    
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
    
    
    public static function obtenerReglasCompuestas(): array
    {
        if (self::usarBD()) {
            try {
                return BaseConocimientoBD::obtenerReglasCompuestas();
            } catch (\Exception $e) {
                error_log('[MOTOR_IA] Error obteniendo reglas compuestas desde BD: ' . $e->getMessage());
            }
        }
        
        return [];
    }
    
    
    public static function obtenerReglasTendencia(): array
    {
        if (self::usarBD()) {
            try {
                return BaseConocimientoBD::obtenerReglasTendencia();
            } catch (\Exception $e) {
                error_log('[MOTOR_IA] Error obteniendo reglas de tendencia desde BD: ' . $e->getMessage());
            }
        }
        
        return [];
    }
    
    
    public static function obtenerReglasPerfil(): array
    {
        if (self::usarBD()) {
            try {
                return BaseConocimientoBD::obtenerReglasPerfil();
            } catch (\Exception $e) {
                error_log('[MOTOR_IA] Error obteniendo reglas de perfil desde BD: ' . $e->getMessage());
            }
        }
        
        return [];
    }
    
    
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
