<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\Validar;
use Gymsys\Utils\Cipher;

class EvaluacionesAtleta
{
   private Database $database;

   public function __construct(Database $database)
   {
      $this->database = $database;
   }

   /**
    * Obtiene la tarjeta completa del atleta con todas sus evaluaciones y lesiones
    */
   public function obtenerTarjetaAtleta(array $datos): array
   {
      $keys = ['id_atleta'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $idAtleta = Cipher::aesDecrypt($arrayFiltrado['id_atleta']);
      Validar::validar("cedula", $idAtleta);

      $consultaAtleta = "SELECT 
         u.cedula,
         u.nombre,
         u.apellido,
         u.fecha_nacimiento,
         a.peso,
         a.altura
      FROM {$_ENV['SECURE_DB']}.usuarios u
      INNER JOIN atleta a ON u.cedula = a.cedula
      WHERE u.cedula = :id_atleta";
      
      $atleta = $this->database->query($consultaAtleta, [':id_atleta' => $idAtleta]);
      
      if (empty($atleta)) {
         ExceptionHandler::throwException("No se encontró el atleta", \InvalidArgumentException::class, 404);
      }

      $consultaUltimoPostural = "SELECT * FROM test_postural 
         WHERE id_atleta = :id_atleta 
         ORDER BY fecha_evaluacion DESC, fecha_registro DESC 
         LIMIT 1";
      $ultimoTestPosturalRaw = $this->database->query($consultaUltimoPostural, [':id_atleta' => $idAtleta]);
      $ultimoTestPostural = is_array($ultimoTestPosturalRaw) ? $ultimoTestPosturalRaw : [];

      $consultaUltimoFms = "SELECT * FROM test_fms 
         WHERE id_atleta = :id_atleta 
         ORDER BY fecha_evaluacion DESC, fecha_registro DESC 
         LIMIT 1";
      $ultimoTestFmsRaw = $this->database->query($consultaUltimoFms, [':id_atleta' => $idAtleta]);
      $ultimoTestFms = is_array($ultimoTestFmsRaw) ? $ultimoTestFmsRaw : [];

      $consultaLesionesRecientes = "SELECT 
         *,
         CASE 
            WHEN fecha_recuperacion IS NULL THEN 'Activa'
            ELSE 'Recuperada'
         END as estado_lesion
      FROM lesiones 
      WHERE id_atleta = :id_atleta 
      ORDER BY fecha_lesion DESC
      LIMIT 10";
      $lesionesRecientesRaw = $this->database->query($consultaLesionesRecientes, [':id_atleta' => $idAtleta]);
      $lesionesRecientes = is_array($lesionesRecientesRaw) ? $lesionesRecientesRaw : [];

      $consultaResumenLesiones = "SELECT 
         COUNT(*) as total_lesiones,
         SUM(CASE WHEN fecha_recuperacion IS NULL THEN 1 ELSE 0 END) as lesiones_activas
      FROM lesiones 
      WHERE id_atleta = :id_atleta";
      $resumenLesionesRaw = $this->database->query($consultaResumenLesiones, [':id_atleta' => $idAtleta]);
      $resumenLesiones = is_array($resumenLesionesRaw) ? $resumenLesionesRaw : [];

      $consultaAsistencias = "SELECT 
         COUNT(*) as total_sesiones,
         SUM(CASE WHEN estado_asistencia = 'presente' THEN 1 ELSE 0 END) as presentes
      FROM asistencias 
      WHERE id_atleta = :id_atleta 
      AND fecha >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
      $asistenciasRaw = $this->database->query($consultaAsistencias, [':id_atleta' => $idAtleta]);
      $asistencias = is_array($asistenciasRaw) ? $asistenciasRaw : [];
      
      $totalSesiones = $asistencias[0]['total_sesiones'] ?? 0;
      $presentes = $asistencias[0]['presentes'] ?? 0;
      $porcentajeAsistencia = $totalSesiones > 0 ? round(($presentes / $totalSesiones) * 100, 2) : 0;

      $tarjeta = [
         'atleta' => $atleta[0] ?? null,
         'ultimo_test_postural' => $ultimoTestPostural[0] ?? null,
         'ultimo_test_fms' => $ultimoTestFms[0] ?? null,
         'lesiones_recientes' => $lesionesRecientes ?? [],
         'resumen_lesiones' => [
            'total_lesiones' => (int)($resumenLesiones[0]['total_lesiones'] ?? 0),
            'lesiones_activas' => (int)($resumenLesiones[0]['lesiones_activas'] ?? 0)
         ],
         'asistencias_30_dias' => [
            'total_sesiones' => (int)$totalSesiones,
            'presentes' => (int)$presentes,
            'porcentaje_asistencia' => (float)$porcentajeAsistencia
         ],
         'ia' => null
      ];

      try {
         if (class_exists('Gymsys\Core\IA\AnalizadorAtleta')) {
            $analizador = new \Gymsys\Core\IA\AnalizadorAtleta();
            $analisisIA = $analizador->analizarAtleta($tarjeta);
            $tarjeta['ia'] = $analisisIA;
         }
      } catch (\Exception $e) {
         error_log('[EvaluacionesAtleta] Error en análisis IA: ' . $e->getMessage());
         $tarjeta['ia'] = null;
      }

      return $tarjeta;
   }

   /**
    * Obtiene SOLO el análisis de riesgo IA de un atleta (endpoint ligero)
    * Reutiliza la lógica de obtenerTarjetaAtleta pero devuelve solo la parte IA
    * 
    * @param array $datos Debe contener 'id_atleta' cifrado
    * @return array Solo el análisis IA o null si no hay datos
    */
   public function obtenerRiesgoAtleta(array $datos): array
   {
      $keys = ['id_atleta'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $idAtleta = Cipher::aesDecrypt($arrayFiltrado['id_atleta']);
      Validar::validar("cedula", $idAtleta);

      // Construir mini-tarjeta con solo lo necesario para la IA
      $consultaUltimoPostural = "SELECT * FROM test_postural 
         WHERE id_atleta = :id_atleta 
         ORDER BY fecha_evaluacion DESC, fecha_registro DESC 
         LIMIT 1";
      $ultimoTestPosturalRaw = $this->database->query($consultaUltimoPostural, [':id_atleta' => $idAtleta]);
      $ultimoTestPostural = is_array($ultimoTestPosturalRaw) ? $ultimoTestPosturalRaw : [];

      $consultaUltimoFms = "SELECT * FROM test_fms 
         WHERE id_atleta = :id_atleta 
         ORDER BY fecha_evaluacion DESC, fecha_registro DESC 
         LIMIT 1";
      $ultimoTestFmsRaw = $this->database->query($consultaUltimoFms, [':id_atleta' => $idAtleta]);
      $ultimoTestFms = is_array($ultimoTestFmsRaw) ? $ultimoTestFmsRaw : [];

      $consultaLesionesRecientes = "SELECT 
         *,
         CASE 
            WHEN fecha_recuperacion IS NULL THEN 'Activa'
            ELSE 'Recuperada'
         END as estado_lesion
      FROM lesiones 
      WHERE id_atleta = :id_atleta 
      ORDER BY fecha_lesion DESC
      LIMIT 10";
      $lesionesRecientesRaw = $this->database->query($consultaLesionesRecientes, [':id_atleta' => $idAtleta]);
      $lesionesRecientes = is_array($lesionesRecientesRaw) ? $lesionesRecientesRaw : [];

      $consultaResumenLesiones = "SELECT 
         COUNT(*) as total_lesiones,
         SUM(CASE WHEN fecha_recuperacion IS NULL THEN 1 ELSE 0 END) as lesiones_activas
      FROM lesiones 
      WHERE id_atleta = :id_atleta";
      $resumenLesionesRaw = $this->database->query($consultaResumenLesiones, [':id_atleta' => $idAtleta]);
      $resumenLesiones = is_array($resumenLesionesRaw) ? $resumenLesionesRaw : [];

      $consultaAsistencias = "SELECT 
         COUNT(*) as total_sesiones,
         SUM(CASE WHEN estado_asistencia = 'presente' THEN 1 ELSE 0 END) as presentes
      FROM asistencias 
      WHERE id_atleta = :id_atleta 
      AND fecha >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
      $asistenciasRaw = $this->database->query($consultaAsistencias, [':id_atleta' => $idAtleta]);
      $asistencias = is_array($asistenciasRaw) ? $asistenciasRaw : [];
      
      $totalSesiones = $asistencias[0]['total_sesiones'] ?? 0;
      $presentes = $asistencias[0]['presentes'] ?? 0;
      $porcentajeAsistencia = $totalSesiones > 0 ? round(($presentes / $totalSesiones) * 100, 2) : 0;

      // Mini-tarjeta solo con datos necesarios para IA
      $miniTarjeta = [
         'atleta' => [],  // La IA no necesita datos personales
         'ultimo_test_postural' => $ultimoTestPostural[0] ?? null,
         'ultimo_test_fms' => $ultimoTestFms[0] ?? null,
         'lesiones_recientes' => $lesionesRecientes ?? [],
         'resumen_lesiones' => [
            'total_lesiones' => (int)($resumenLesiones[0]['total_lesiones'] ?? 0),
            'lesiones_activas' => (int)($resumenLesiones[0]['lesiones_activas'] ?? 0)
         ],
         'asistencias_30_dias' => [
            'total_sesiones' => (int)$totalSesiones,
            'presentes' => (int)$presentes,
            'porcentaje_asistencia' => (float)$porcentajeAsistencia
         ]
      ];

      // Ejecutar análisis IA
      $riesgo = null;
      try {
         if (class_exists('Gymsys\Core\IA\AnalizadorAtleta')) {
            $analizador = new \Gymsys\Core\IA\AnalizadorAtleta();
            $riesgo = $analizador->analizarAtleta($miniTarjeta);
         }
      } catch (\Exception $e) {
         error_log('[EvaluacionesAtleta] Error en análisis IA para riesgo: ' . $e->getMessage());
         $riesgo = null;
      }

      return ['riesgo' => $riesgo];
   }

   public function crearTestPostural(array $datos): array
   {
      $keys = [
         'id_atleta', 'fecha_evaluacion', 'cifosis_dorsal', 'lordosis_lumbar',
         'escoliosis', 'inclinacion_pelvis', 'valgo_rodilla', 'varo_rodilla',
         'rotacion_hombros', 'desnivel_escapulas', 'observaciones'
      ];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      
      $idAtleta = Cipher::aesDecrypt($arrayFiltrado['id_atleta']);
      Validar::validar("cedula", $idAtleta);
      Validar::validarFecha($arrayFiltrado['fecha_evaluacion']);
      
      $evaluador = defined('ID_USUARIO') ? ID_USUARIO : null;
      if (!$evaluador) {
         ExceptionHandler::throwException("No se pudo identificar el evaluador", \RuntimeException::class, 401);
      }

      $consulta = "INSERT INTO test_postural (
         id_atleta, fecha_evaluacion, cifosis_dorsal, lordosis_lumbar,
         escoliosis, inclinacion_pelvis, valgo_rodilla, varo_rodilla,
         rotacion_hombros, desnivel_escapulas, observaciones, evaluador
      ) VALUES (
         :id_atleta, :fecha_evaluacion, :cifosis_dorsal, :lordosis_lumbar,
         :escoliosis, :inclinacion_pelvis, :valgo_rodilla, :varo_rodilla,
         :rotacion_hombros, :desnivel_escapulas, :observaciones, :evaluador
      )";

      $valores = [
         ':id_atleta' => $idAtleta,
         ':fecha_evaluacion' => $arrayFiltrado['fecha_evaluacion'],
         ':cifosis_dorsal' => $arrayFiltrado['cifosis_dorsal'],
         ':lordosis_lumbar' => $arrayFiltrado['lordosis_lumbar'],
         ':escoliosis' => $arrayFiltrado['escoliosis'],
         ':inclinacion_pelvis' => $arrayFiltrado['inclinacion_pelvis'],
         ':valgo_rodilla' => $arrayFiltrado['valgo_rodilla'],
         ':varo_rodilla' => $arrayFiltrado['varo_rodilla'],
         ':rotacion_hombros' => $arrayFiltrado['rotacion_hombros'],
         ':desnivel_escapulas' => $arrayFiltrado['desnivel_escapulas'],
         ':observaciones' => $arrayFiltrado['observaciones'] ?? null,
         ':evaluador' => $evaluador
      ];

      $response = $this->database->query($consulta, $valores);
      
      if (empty($response)) {
         ExceptionHandler::throwException("Error al registrar el test postural", \Exception::class, 500);
      }

      // 🔔 NOTIFICACIÓN IA: Analizar riesgo y notificar si es necesario
      $this->evaluarYNotificarRiesgoIA($idAtleta);

      return ['mensaje' => 'Test postural registrado con éxito'];
   }

   public function obtenerTestPostural(array $datos): array
   {
      $keys = ['id'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      
      $consulta = "SELECT * FROM test_postural WHERE id_test_postural = :id";
      $result = $this->database->query($consulta, [':id' => $arrayFiltrado['id']]);
      
      if (empty($result)) {
         return ['exito' => false, 'mensaje' => 'Test postural no encontrado'];
      }

      $result[0]['id_atleta'] = Cipher::aesEncrypt($result[0]['id_atleta']);
      return ['exito' => true, 'datos' => $result[0]];
   }

   public function actualizarTestPostural(array $datos): array
   {
      $keys = [
         'id_test_postural', 'id_atleta', 'fecha_evaluacion', 'cifosis_dorsal', 
         'lordosis_lumbar', 'escoliosis', 'inclinacion_pelvis', 'valgo_rodilla', 
         'varo_rodilla', 'rotacion_hombros', 'desnivel_escapulas', 'observaciones'
      ];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      
      $idAtleta = Cipher::aesDecrypt($arrayFiltrado['id_atleta']);
      Validar::validar("cedula", $idAtleta);
      Validar::validarFecha($arrayFiltrado['fecha_evaluacion']);

      $consulta = "UPDATE test_postural SET 
         fecha_evaluacion = :fecha_evaluacion,
         cifosis_dorsal = :cifosis_dorsal,
         lordosis_lumbar = :lordosis_lumbar,
         escoliosis = :escoliosis,
         inclinacion_pelvis = :inclinacion_pelvis,
         valgo_rodilla = :valgo_rodilla,
         varo_rodilla = :varo_rodilla,
         rotacion_hombros = :rotacion_hombros,
         desnivel_escapulas = :desnivel_escapulas,
         observaciones = :observaciones
      WHERE id_test_postural = :id_test_postural AND id_atleta = :id_atleta";

      $valores = [
         ':id_test_postural' => $arrayFiltrado['id_test_postural'],
         ':id_atleta' => $idAtleta,
         ':fecha_evaluacion' => $arrayFiltrado['fecha_evaluacion'],
         ':cifosis_dorsal' => $arrayFiltrado['cifosis_dorsal'],
         ':lordosis_lumbar' => $arrayFiltrado['lordosis_lumbar'],
         ':escoliosis' => $arrayFiltrado['escoliosis'],
         ':inclinacion_pelvis' => $arrayFiltrado['inclinacion_pelvis'],
         ':valgo_rodilla' => $arrayFiltrado['valgo_rodilla'],
         ':varo_rodilla' => $arrayFiltrado['varo_rodilla'],
         ':rotacion_hombros' => $arrayFiltrado['rotacion_hombros'],
         ':desnivel_escapulas' => $arrayFiltrado['desnivel_escapulas'],
         ':observaciones' => $arrayFiltrado['observaciones'] ?? null
      ];

      $response = $this->database->query($consulta, $valores);
      
      if (empty($response)) {
         ExceptionHandler::throwException("Error al actualizar el test postural", \Exception::class, 500);
      }

      return ['mensaje' => 'Test postural actualizado con éxito'];
   }

   public function eliminarTestPostural(array $datos): array
   {
      $keys = ['id_test_postural'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      
      $consulta = "DELETE FROM test_postural WHERE id_test_postural = :id";
      $response = $this->database->query($consulta, [':id' => $arrayFiltrado['id_test_postural']]);
      
      if (empty($response)) {
         ExceptionHandler::throwException("Error al eliminar el test postural", \Exception::class, 500);
      }

      return ['mensaje' => 'Test postural eliminado con éxito'];
   }

   public function listarTestsPosturalesPorAtleta(array $datos): array
   {
      $keys = ['id_atleta'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $idAtleta = Cipher::aesDecrypt($arrayFiltrado['id_atleta']);
      
      $consulta = "SELECT * FROM test_postural 
         WHERE id_atleta = :id_atleta 
         ORDER BY fecha_evaluacion DESC, fecha_registro DESC";
      
      $result = $this->database->query($consulta, [':id_atleta' => $idAtleta]);
      
      return ['tests_posturales' => $result ?? []];
   }

   public function crearTestFms(array $datos): array
   {
      $keys = [
         'id_atleta', 'fecha_evaluacion', 'sentadilla_profunda', 'paso_valla',
         'estocada_en_linea', 'movilidad_hombro', 'elevacion_pierna_recta',
         'estabilidad_tronco', 'estabilidad_rotacional', 'puntuacion_total',
         'observaciones'
      ];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      
      $idAtleta = Cipher::aesDecrypt($arrayFiltrado['id_atleta']);
      Validar::validar("cedula", $idAtleta);
      Validar::validarFecha($arrayFiltrado['fecha_evaluacion']);
      
      $evaluador = defined('ID_USUARIO') ? ID_USUARIO : null;
      if (!$evaluador) {
         ExceptionHandler::throwException("No se pudo identificar el evaluador", \RuntimeException::class, 401);
      }

      $consulta = "INSERT INTO test_fms (
         id_atleta, fecha_evaluacion, sentadilla_profunda, paso_valla,
         estocada_en_linea, movilidad_hombro, elevacion_pierna_recta,
         estabilidad_tronco, estabilidad_rotacional, puntuacion_total,
         observaciones, evaluador
      ) VALUES (
         :id_atleta, :fecha_evaluacion, :sentadilla_profunda, :paso_valla,
         :estocada_en_linea, :movilidad_hombro, :elevacion_pierna_recta,
         :estabilidad_tronco, :estabilidad_rotacional, :puntuacion_total,
         :observaciones, :evaluador
      )";

      $valores = [
         ':id_atleta' => $idAtleta,
         ':fecha_evaluacion' => $arrayFiltrado['fecha_evaluacion'],
         ':sentadilla_profunda' => $arrayFiltrado['sentadilla_profunda'],
         ':paso_valla' => $arrayFiltrado['paso_valla'],
         ':estocada_en_linea' => $arrayFiltrado['estocada_en_linea'],
         ':movilidad_hombro' => $arrayFiltrado['movilidad_hombro'],
         ':elevacion_pierna_recta' => $arrayFiltrado['elevacion_pierna_recta'],
         ':estabilidad_tronco' => $arrayFiltrado['estabilidad_tronco'],
         ':estabilidad_rotacional' => $arrayFiltrado['estabilidad_rotacional'],
         ':puntuacion_total' => $arrayFiltrado['puntuacion_total'],
         ':observaciones' => $arrayFiltrado['observaciones'] ?? null,
         ':evaluador' => $evaluador
      ];

      $response = $this->database->query($consulta, $valores);
      
      if (empty($response)) {
         ExceptionHandler::throwException("Error al registrar el test FMS", \Exception::class, 500);
      }

      // 🔔 NOTIFICACIÓN IA: Analizar riesgo y notificar si es necesario
      $this->evaluarYNotificarRiesgoIA($idAtleta);

      return ['mensaje' => 'Test FMS registrado con éxito'];
   }

   public function obtenerTestFms(array $datos): array
   {
      $keys = ['id'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      
      $consulta = "SELECT * FROM test_fms WHERE id_test_fms = :id";
      $result = $this->database->query($consulta, [':id' => $arrayFiltrado['id']]);
      
      if (empty($result)) {
         return ['exito' => false, 'mensaje' => 'Test FMS no encontrado'];
      }

      $result[0]['id_atleta'] = Cipher::aesEncrypt($result[0]['id_atleta']);
      return ['exito' => true, 'datos' => $result[0]];
   }

   public function actualizarTestFms(array $datos): array
   {
      $keys = [
         'id_test_fms', 'id_atleta', 'fecha_evaluacion', 'sentadilla_profunda', 
         'paso_valla', 'estocada_en_linea', 'movilidad_hombro', 
         'elevacion_pierna_recta', 'estabilidad_tronco', 'estabilidad_rotacional', 
         'puntuacion_total', 'observaciones'
      ];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      
      $idAtleta = Cipher::aesDecrypt($arrayFiltrado['id_atleta']);
      Validar::validar("cedula", $idAtleta);
      Validar::validarFecha($arrayFiltrado['fecha_evaluacion']);

      $consulta = "UPDATE test_fms SET 
         fecha_evaluacion = :fecha_evaluacion,
         sentadilla_profunda = :sentadilla_profunda,
         paso_valla = :paso_valla,
         estocada_en_linea = :estocada_en_linea,
         movilidad_hombro = :movilidad_hombro,
         elevacion_pierna_recta = :elevacion_pierna_recta,
         estabilidad_tronco = :estabilidad_tronco,
         estabilidad_rotacional = :estabilidad_rotacional,
         puntuacion_total = :puntuacion_total,
         observaciones = :observaciones
      WHERE id_test_fms = :id_test_fms AND id_atleta = :id_atleta";

      $valores = [
         ':id_test_fms' => $arrayFiltrado['id_test_fms'],
         ':id_atleta' => $idAtleta,
         ':fecha_evaluacion' => $arrayFiltrado['fecha_evaluacion'],
         ':sentadilla_profunda' => $arrayFiltrado['sentadilla_profunda'],
         ':paso_valla' => $arrayFiltrado['paso_valla'],
         ':estocada_en_linea' => $arrayFiltrado['estocada_en_linea'],
         ':movilidad_hombro' => $arrayFiltrado['movilidad_hombro'],
         ':elevacion_pierna_recta' => $arrayFiltrado['elevacion_pierna_recta'],
         ':estabilidad_tronco' => $arrayFiltrado['estabilidad_tronco'],
         ':estabilidad_rotacional' => $arrayFiltrado['estabilidad_rotacional'],
         ':puntuacion_total' => $arrayFiltrado['puntuacion_total'],
         ':observaciones' => $arrayFiltrado['observaciones'] ?? null
      ];

      $response = $this->database->query($consulta, $valores);
      
      if (empty($response)) {
         ExceptionHandler::throwException("Error al actualizar el test FMS", \Exception::class, 500);
      }

      return ['mensaje' => 'Test FMS actualizado con éxito'];
   }

   public function eliminarTestFms(array $datos): array
   {
      $keys = ['id_test_fms'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      
      $consulta = "DELETE FROM test_fms WHERE id_test_fms = :id";
      $response = $this->database->query($consulta, [':id' => $arrayFiltrado['id_test_fms']]);
      
      if (empty($response)) {
         ExceptionHandler::throwException("Error al eliminar el test FMS", \Exception::class, 500);
      }

      return ['mensaje' => 'Test FMS eliminado con éxito'];
   }

   public function listarTestsFmsPorAtleta(array $datos): array
   {
      $keys = ['id_atleta'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $idAtleta = Cipher::aesDecrypt($arrayFiltrado['id_atleta']);
      
      $consulta = "SELECT * FROM test_fms 
         WHERE id_atleta = :id_atleta 
         ORDER BY fecha_evaluacion DESC, fecha_registro DESC";
      
      $result = $this->database->query($consulta, [':id_atleta' => $idAtleta]);
      
      return ['tests_fms' => $result ?? []];
   }

   public function crearLesion(array $datos): array
   {
      $keys = [
         'id_atleta', 'fecha_lesion', 'tipo_lesion', 'zona_afectada',
         'gravedad', 'mecanismo_lesion', 'tiempo_estimado_recuperacion',
         'tratamiento_realizado', 'observaciones'
      ];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      
      $idAtleta = Cipher::aesDecrypt($arrayFiltrado['id_atleta']);
      Validar::validar("cedula", $idAtleta);
      Validar::validarFecha($arrayFiltrado['fecha_lesion']);
      
      $registradoPor = defined('ID_USUARIO') ? ID_USUARIO : null;
      if (!$registradoPor) {
         ExceptionHandler::throwException("No se pudo identificar el usuario que registra", \RuntimeException::class, 401);
      }

      $consulta = "INSERT INTO lesiones (
         id_atleta, fecha_lesion, tipo_lesion, zona_afectada,
         gravedad, mecanismo_lesion, tiempo_estimado_recuperacion,
         tratamiento_realizado, observaciones, registrado_por
      ) VALUES (
         :id_atleta, :fecha_lesion, :tipo_lesion, :zona_afectada,
         :gravedad, :mecanismo_lesion, :tiempo_estimado_recuperacion,
         :tratamiento_realizado, :observaciones, :registrado_por
      )";

      $valores = [
         ':id_atleta' => $idAtleta,
         ':fecha_lesion' => $arrayFiltrado['fecha_lesion'],
         ':tipo_lesion' => $arrayFiltrado['tipo_lesion'],
         ':zona_afectada' => $arrayFiltrado['zona_afectada'],
         ':gravedad' => $arrayFiltrado['gravedad'],
         ':mecanismo_lesion' => $arrayFiltrado['mecanismo_lesion'],
         ':tiempo_estimado_recuperacion' => $arrayFiltrado['tiempo_estimado_recuperacion'] ?? null,
         ':tratamiento_realizado' => $arrayFiltrado['tratamiento_realizado'] ?? null,
         ':observaciones' => $arrayFiltrado['observaciones'] ?? null,
         ':registrado_por' => $registradoPor
      ];

      $response = $this->database->query($consulta, $valores);
      
      if (empty($response)) {
         ExceptionHandler::throwException("Error al registrar la lesión", \Exception::class, 500);
      }

      // 🔔 NOTIFICACIÓN IA: Analizar riesgo y notificar si es necesario
      $this->evaluarYNotificarRiesgoIA($idAtleta);

      return ['mensaje' => 'Lesión registrada con éxito'];
   }

   public function obtenerLesion(array $datos): array
   {
      $keys = ['id'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      
      $consulta = "SELECT * FROM lesiones WHERE id_lesion = :id";
      $result = $this->database->query($consulta, [':id' => $arrayFiltrado['id']]);
      
      if (empty($result)) {
         return ['exito' => false, 'mensaje' => 'Lesión no encontrada'];
      }

      $result[0]['id_atleta'] = Cipher::aesEncrypt($result[0]['id_atleta']);
      return ['exito' => true, 'datos' => $result[0]];
   }

   public function actualizarLesion(array $datos): array
   {
      $keys = [
         'id_lesion', 'id_atleta', 'fecha_lesion', 'tipo_lesion', 'zona_afectada',
         'gravedad', 'mecanismo_lesion', 'tiempo_estimado_recuperacion',
         'fecha_recuperacion', 'tratamiento_realizado', 'observaciones'
      ];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      
      $idAtleta = Cipher::aesDecrypt($arrayFiltrado['id_atleta']);
      Validar::validar("cedula", $idAtleta);
      Validar::validarFecha($arrayFiltrado['fecha_lesion']);

      $consulta = "UPDATE lesiones SET 
         fecha_lesion = :fecha_lesion,
         tipo_lesion = :tipo_lesion,
         zona_afectada = :zona_afectada,
         gravedad = :gravedad,
         mecanismo_lesion = :mecanismo_lesion,
         tiempo_estimado_recuperacion = :tiempo_estimado_recuperacion,
         fecha_recuperacion = :fecha_recuperacion,
         tratamiento_realizado = :tratamiento_realizado,
         observaciones = :observaciones
      WHERE id_lesion = :id_lesion AND id_atleta = :id_atleta";

      $valores = [
         ':id_lesion' => $arrayFiltrado['id_lesion'],
         ':id_atleta' => $idAtleta,
         ':fecha_lesion' => $arrayFiltrado['fecha_lesion'],
         ':tipo_lesion' => $arrayFiltrado['tipo_lesion'],
         ':zona_afectada' => $arrayFiltrado['zona_afectada'],
         ':gravedad' => $arrayFiltrado['gravedad'],
         ':mecanismo_lesion' => $arrayFiltrado['mecanismo_lesion'],
         ':tiempo_estimado_recuperacion' => $arrayFiltrado['tiempo_estimado_recuperacion'] ?? null,
         ':fecha_recuperacion' => $arrayFiltrado['fecha_recuperacion'] ?? null,
         ':tratamiento_realizado' => $arrayFiltrado['tratamiento_realizado'] ?? null,
         ':observaciones' => $arrayFiltrado['observaciones'] ?? null
      ];

      $response = $this->database->query($consulta, $valores);
      
      if (empty($response)) {
         ExceptionHandler::throwException("Error al actualizar la lesión", \Exception::class, 500);
      }

      return ['mensaje' => 'Lesión actualizada con éxito'];
   }

   public function eliminarLesion(array $datos): array
   {
      $keys = ['id_lesion'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      
      $consulta = "DELETE FROM lesiones WHERE id_lesion = :id";
      $response = $this->database->query($consulta, [':id' => $arrayFiltrado['id_lesion']]);
      
      if (empty($response)) {
         ExceptionHandler::throwException("Error al eliminar la lesión", \Exception::class, 500);
      }

      return ['mensaje' => 'Lesión eliminada con éxito'];
   }

   public function listarLesionesPorAtleta(array $datos): array
   {
      $keys = ['id_atleta'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $idAtleta = Cipher::aesDecrypt($arrayFiltrado['id_atleta']);
      
      $consulta = "SELECT 
         *,
         CASE 
            WHEN fecha_recuperacion IS NULL THEN 'Activa'
            ELSE 'Recuperada'
         END as estado_lesion
      FROM lesiones 
      WHERE id_atleta = :id_atleta 
      ORDER BY fecha_lesion DESC, fecha_registro DESC";
      
      $result = $this->database->query($consulta, [':id_atleta' => $idAtleta]);
      
      return ['lesiones' => $result ?? []];
   }

   /**
    * Evalúa el riesgo IA del atleta y crea notificación si es necesario
    * Se ejecuta después de crear/actualizar evaluaciones (FMS, Postural, Lesión)
    * 
    * @param string $cedulaAtleta Cédula del atleta (sin cifrar)
    */
   private function evaluarYNotificarRiesgoIA(string $cedulaAtleta): void
   {
      try {
         // Obtener datos del atleta para la notificación
         $consultaAtleta = "SELECT nombre, apellido FROM {$_ENV['SECURE_DB']}.usuarios WHERE cedula = :cedula";
         $atleta = $this->database->query($consultaAtleta, [':cedula' => $cedulaAtleta], true);
         
         if (empty($atleta)) {
            error_log('[EvaluacionesAtleta] No se encontró atleta con cédula ' . $cedulaAtleta);
            return;
         }

         $nombreCompleto = trim($atleta['nombre'] . ' ' . $atleta['apellido']);

         // Construir mini-tarjeta para análisis IA (reutilizando lógica de obtenerRiesgoAtleta)
         $consultaUltimoPostural = "SELECT * FROM test_postural 
            WHERE id_atleta = :id_atleta 
            ORDER BY fecha_evaluacion DESC, fecha_registro DESC 
            LIMIT 1";
         $ultimoTestPosturalRaw = $this->database->query($consultaUltimoPostural, [':id_atleta' => $cedulaAtleta]);
         $ultimoTestPostural = is_array($ultimoTestPosturalRaw) ? $ultimoTestPosturalRaw : [];

         $consultaUltimoFms = "SELECT * FROM test_fms 
            WHERE id_atleta = :id_atleta 
            ORDER BY fecha_evaluacion DESC, fecha_registro DESC 
            LIMIT 1";
         $ultimoTestFmsRaw = $this->database->query($consultaUltimoFms, [':id_atleta' => $cedulaAtleta]);
         $ultimoTestFms = is_array($ultimoTestFmsRaw) ? $ultimoTestFmsRaw : [];

         $consultaLesionesRecientes = "SELECT 
            *,
            CASE 
               WHEN fecha_recuperacion IS NULL THEN 'Activa'
               ELSE 'Recuperada'
            END as estado_lesion
         FROM lesiones 
         WHERE id_atleta = :id_atleta 
         ORDER BY fecha_lesion DESC
         LIMIT 10";
         $lesionesRecientesRaw = $this->database->query($consultaLesionesRecientes, [':id_atleta' => $cedulaAtleta]);
         $lesionesRecientes = is_array($lesionesRecientesRaw) ? $lesionesRecientesRaw : [];

         $consultaResumenLesiones = "SELECT 
            COUNT(*) as total_lesiones,
            SUM(CASE WHEN fecha_recuperacion IS NULL THEN 1 ELSE 0 END) as lesiones_activas
         FROM lesiones 
         WHERE id_atleta = :id_atleta";
         $resumenLesionesRaw = $this->database->query($consultaResumenLesiones, [':id_atleta' => $cedulaAtleta]);
         $resumenLesiones = is_array($resumenLesionesRaw) ? $resumenLesionesRaw : [];

         $consultaAsistencias = "SELECT 
            COUNT(*) as total_sesiones,
            SUM(CASE WHEN estado_asistencia = 'presente' THEN 1 ELSE 0 END) as presentes
         FROM asistencias 
         WHERE id_atleta = :id_atleta 
         AND fecha >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
         $asistenciasRaw = $this->database->query($consultaAsistencias, [':id_atleta' => $cedulaAtleta]);
         $asistencias = is_array($asistenciasRaw) ? $asistenciasRaw : [];
         
         $totalSesiones = $asistencias[0]['total_sesiones'] ?? 0;
         $presentes = $asistencias[0]['presentes'] ?? 0;
         $porcentajeAsistencia = $totalSesiones > 0 ? round(($presentes / $totalSesiones) * 100, 2) : 0;

         // Mini-tarjeta para IA
         $miniTarjeta = [
            'atleta' => [],
            'ultimo_test_postural' => $ultimoTestPostural[0] ?? null,
            'ultimo_test_fms' => $ultimoTestFms[0] ?? null,
            'lesiones_recientes' => $lesionesRecientes,
            'resumen_lesiones' => [
               'total_lesiones' => (int)($resumenLesiones[0]['total_lesiones'] ?? 0),
               'lesiones_activas' => (int)($resumenLesiones[0]['lesiones_activas'] ?? 0)
            ],
            'asistencias_30_dias' => [
               'total_sesiones' => (int)$totalSesiones,
               'presentes' => (int)$presentes,
               'porcentaje_asistencia' => (float)$porcentajeAsistencia
            ]
         ];

         // Ejecutar análisis IA
         if (!class_exists('Gymsys\Core\IA\AnalizadorAtleta')) {
            error_log('[EvaluacionesAtleta] Clase AnalizadorAtleta no encontrada');
            return;
         }

         $analizador = new \Gymsys\Core\IA\AnalizadorAtleta();
         $analisisIA = $analizador->analizarAtleta($miniTarjeta);

         if (empty($analisisIA) || !isset($analisisIA['riesgo_nivel'])) {
            error_log('[EvaluacionesAtleta] Análisis IA no devolvió datos válidos');
            return;
         }

         // Preparar datos para notificación
         $datosNotificacion = [
            'cedula_atleta' => $cedulaAtleta,
            'nombre_atleta' => $nombreCompleto,
            'riesgo_nivel' => $analisisIA['riesgo_nivel'],
            'riesgo_score' => $analisisIA['riesgo_score'] ?? 0,
            'primer_factor' => $analisisIA['factores_clave'][0] ?? 'Sin factores identificados'
         ];

         // Crear notificación si aplica
         $notificacionesModel = new Notificaciones($this->database);
         $notificacionesModel->crearNotificacionRiesgoIA($datosNotificacion);

      } catch (\Exception $e) {
         // No lanzar excepción para no romper el flujo principal
         error_log('[EvaluacionesAtleta] Error evaluando riesgo IA: ' . $e->getMessage());
      }
   }
}
