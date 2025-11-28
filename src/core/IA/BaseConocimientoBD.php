<?php

namespace Gymsys\Core\IA;

use PDO;
use PDOException;


class BaseConocimientoBD
{
    private static ?PDO $conexion = null;
    private static array $cache = [];
    
    
    private static function obtenerConexion(): PDO
    {
        if (self::$conexion === null) {
            try {
                $host = $_ENV['DB_HOST'] ?? 'localhost';
                $charset = 'utf8mb4';
                $dsn = "mysql:host=$host;dbname=gymsys_kb;charset=$charset";
                
                $opciones = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
                
                self::$conexion = new PDO(
                    $dsn,
                    $_ENV['DB_USER'] ?? 'root',
                    $_ENV['DB_PASS'] ?? '',
                    $opciones
                );
            } catch (PDOException $e) {

                throw new \RuntimeException("Error conectando a BD de conocimiento: " . $e->getMessage());
            }
        }
        
        return self::$conexion;
    }
    
    
    public static function limpiarCache(): void
    {
        self::$cache = [];
    }
    
    
    public static function obtenerPonderaciones(): array
    {
        if (isset(self::$cache['ponderaciones'])) {
            return self::$cache['ponderaciones'];
        }
        
        try {
            $pdo = self::obtenerConexion();
            $stmt = $pdo->query("SELECT modulo, peso FROM kb_ponderaciones WHERE activo = 1");
            $resultado = $stmt->fetchAll();
            
            $ponderaciones = [];
            foreach ($resultado as $row) {
                $ponderaciones[$row['modulo']] = (int) $row['peso'];
            }
            
            self::$cache['ponderaciones'] = $ponderaciones;
            return $ponderaciones;
            
        } catch (\Exception $e) {

            return [
                'fms' => 30,
                'postural' => 30,
                'lesiones' => 30,
                'asistencia' => 10
            ];
        }
    }
    
    
    public static function obtenerUmbralesRiesgo(): array
    {
        if (isset(self::$cache['umbrales'])) {
            return self::$cache['umbrales'];
        }
        
        try {
            $pdo = self::obtenerConexion();
            $stmt = $pdo->query("SELECT nivel, min_score, max_score FROM kb_umbrales WHERE activo = 1");
            $resultado = $stmt->fetchAll();
            
            $umbrales = [];
            foreach ($resultado as $row) {
                $umbrales[$row['nivel']] = [
                    'min' => (int) $row['min_score'],
                    'max' => (int) $row['max_score']
                ];
            }
            
            self::$cache['umbrales'] = $umbrales;
            return $umbrales;
            
        } catch (\Exception $e) {

            return [
                'bajo' => ['min' => 0, 'max' => 33],
                'medio' => ['min' => 34, 'max' => 66],
                'alto' => ['min' => 67, 'max' => 100]
            ];
        }
    }
    
    
    public static function obtenerReglasFMS(): array
    {
        return self::obtenerReglasPorModulo('FMS');
    }
    
    
    public static function obtenerReglasPostural(): array
    {
        return self::obtenerReglasPorModulo('POSTURAL');
    }
    
    
    public static function obtenerReglasLesiones(): array
    {
        return self::obtenerReglasPorModulo('LESIONES');
    }
    
    
    public static function obtenerReglasAsistencia(): array
    {
        return self::obtenerReglasPorModulo('ASISTENCIA');
    }
    
    
    public static function obtenerReglasAusenciaDatos(): array
    {
        $cacheKey = 'reglas_ausencia_datos';
        if (isset(self::$cache[$cacheKey])) {
            return self::$cache[$cacheKey];
        }
        
        try {
            $pdo = self::obtenerConexion();
            $stmt = $pdo->prepare("
                SELECT * FROM kb_reglas 
                WHERE modulo = 'AUSENCIA_DATOS' AND activo = 1
                ORDER BY prioridad DESC
            ");
            $stmt->execute();
            $resultado = $stmt->fetchAll();
            
            $reglas = [];
            foreach ($resultado as $row) {
                $recomendacionesDetalladas = json_decode($row['recomendaciones_detalladas_json'], true) ?? [];
                

                $recomendaciones = [];
                foreach ($recomendacionesDetalladas as $categoria => $recs) {
                    $recomendaciones = array_merge($recomendaciones, $recs);
                }
                

                $modulo = 'general';
                if (strpos($row['codigo'], 'SIN_FMS') !== false) {
                    $modulo = 'fms';
                } elseif (strpos($row['codigo'], 'SIN_POSTURAL') !== false) {
                    $modulo = 'postural';
                } elseif (strpos($row['codigo'], 'SIN_ASISTENCIA') !== false) {
                    $modulo = 'asistencia';
                }
                
                $reglas[] = [
                    'id' => $row['codigo'],
                    'modulo' => $modulo,
                    'factor_mensaje' => $row['mensaje_factor'],
                    'recomendaciones' => $recomendaciones
                ];
            }
            
            self::$cache[$cacheKey] = $reglas;
            return $reglas;
            
        } catch (\Exception $e) {

            return BaseConocimientoAtleta::obtenerReglasAusenciaDatos();
        }
    }
    
    
    public static function obtenerReglasCompuestas(): array
    {
        return self::obtenerReglasPorModulo('COMPUESTA');
    }
    
    
    public static function obtenerReglasTendencia(): array
    {
        return self::obtenerReglasPorModulo('TENDENCIA');
    }
    
    
    public static function obtenerReglasPerfil(): array
    {
        return self::obtenerReglasPorModulo('PERFIL');
    }
    
    
    private static function obtenerReglasPorModulo(string $modulo): array
    {
        $cacheKey = 'reglas_' . strtolower($modulo);
        if (isset(self::$cache[$cacheKey])) {
            return self::$cache[$cacheKey];
        }
        
        try {
            $pdo = self::obtenerConexion();
            $stmt = $pdo->prepare("
                SELECT * FROM kb_reglas 
                WHERE modulo = :modulo AND activo = 1
                ORDER BY prioridad DESC, riesgo_puntos DESC
            ");
            $stmt->execute([':modulo' => $modulo]);
            $resultado = $stmt->fetchAll();
            
            $reglas = [];
            foreach ($resultado as $row) {
                $regla = [
                    'id' => $row['codigo'],
                    'descripcion' => $row['descripcion'],
                    'tipo_regla' => $row['tipo_regla'],
                    'riesgo_puntos' => (int) $row['riesgo_puntos'],
                    'prioridad' => $row['prioridad'],
                    'factor_mensaje' => $row['mensaje_factor'],
                    'recomendacion_base' => $row['recomendacion_base']
                ];
                

                if (!empty($row['campo'])) {
                    $valor = $row['valor'];

                    if ($row['operador'] === 'BETWEEN' && strpos($valor, ',') !== false) {
                        $valor = array_map('intval', explode(',', $valor));
                    } elseif (is_numeric($valor)) {
                        $valor = (int) $valor;
                    }
                    
                    $regla['condicion'] = [
                        'campo' => $row['campo'],
                        'operador' => $row['operador'],
                        'valor' => $valor
                    ];
                }
                

                if (!empty($row['condiciones_json'])) {
                    $regla['condiciones_json'] = json_decode($row['condiciones_json'], true);
                }
                

                $recomendacionesDetalladas = json_decode($row['recomendaciones_detalladas_json'], true) ?? [];
                $regla['recomendaciones_detalladas'] = $recomendacionesDetalladas;
                

                $recomendaciones = [];
                foreach ($recomendacionesDetalladas as $categoria => $recs) {
                    $recomendaciones = array_merge($recomendaciones, $recs);
                }
                $regla['recomendaciones'] = $recomendaciones;
                

                if (!empty($row['id_perfil'])) {
                    $regla['id_perfil'] = (int) $row['id_perfil'];
                }
                
                $reglas[] = $regla;
            }
            
            self::$cache[$cacheKey] = $reglas;
            return $reglas;
            
        } catch (\Exception $e) {

            $metodo = 'obtenerReglas' . ucfirst(strtolower($modulo));
            if (method_exists(BaseConocimientoAtleta::class, $metodo)) {
                return BaseConocimientoAtleta::$metodo();
            }
            return [];
        }
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
                '🔴 PRIORIDAD ALTA: Reducir intensidad del entrenamiento y enfocarse en trabajo correctivo.',
                'Consultar con profesional de la salud deportiva si síntomas persisten.'
            ],
            'medio' => [
                '🟡 Monitoreo cercano recomendado. Ajustar carga según tolerancia individual.',
                'Aumentar trabajo preventivo y de movilidad.'
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

        return BaseConocimientoAtleta::obtenerReglasCombinadas();
    }
    
    
    public static function obtenerPerfiles(): array
    {
        if (isset(self::$cache['perfiles'])) {
            return self::$cache['perfiles'];
        }
        
        try {
            $pdo = self::obtenerConexion();
            $stmt = $pdo->query("SELECT * FROM kb_perfiles WHERE activo = 1");
            $resultado = $stmt->fetchAll();
            
            $perfiles = [];
            foreach ($resultado as $row) {
                $perfiles[] = [
                    'id' => (int) $row['id'],
                    'codigo' => $row['codigo'],
                    'nombre' => $row['nombre'],
                    'descripcion' => $row['descripcion'],
                    'criterios' => json_decode($row['criterios_json'], true) ?? []
                ];
            }
            
            self::$cache['perfiles'] = $perfiles;
            return $perfiles;
            
        } catch (\Exception $e) {
            return [];
        }
    }
    
    
    public static function obtenerVersion(): string
    {
        try {
            $pdo = self::obtenerConexion();
            $stmt = $pdo->query("SELECT version FROM kb_metadata WHERE activo = 1 ORDER BY id DESC LIMIT 1");
            $resultado = $stmt->fetch();
            return $resultado['version'] ?? '3.0.0';
            
        } catch (\Exception $e) {
            return '3.0.0';
        }
    }
    
    
    public static function estaDisponible(): bool
    {
        try {
            self::obtenerConexion();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
