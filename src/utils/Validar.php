<?php

namespace Gymsys\Utils;

use Gymsys\Core\Database;

class Validar
{
   protected \PDO $database;
   protected static $regex;
   // public function __construct(\PDO $database)
   // {
   //    self::$database = $database;
   //    self::$exp = ;
   // }
   private static function init()
   {
      self::$regex = require __DIR__ . "/regex.php";
   }
   public static function validar($campo, $valor)
   {
      self::init();
      if ($campo === "password" && $valor === null) {
         return true;
      }
      if (isset(self::$regex[$campo])) {
         $regex = self::$regex[$campo]['regex'];
         if (!preg_match($regex, $valor) || (strpos($campo, 'fecha') === true && !self::validarFecha($valor))) {
            ExceptionHandler::throwException(self::$regex[$campo]["mensaje"], 400, \UnexpectedValueException::class);
         }
         return true;
      }
      ExceptionHandler::throwException("No se encontró expresion regular para el campo '{$campo}'", 400, \InvalidArgumentException::class);
   }
   public static function validarDatos($datos): bool
   {
      self::init();
      foreach ($datos as $campo => $valor) {
         if ($campo === 'accion' || $campo === 'modificar_contraseña') {
            continue; // Salta a la siguiente iteración del bucle
         }
         if (strpos($campo, 'representante') === null) {
            continue; // Salta a la siguiente iteración del bucle
         }
         $resultado = self::validar($campo, $valor);
         if (!$resultado) {
            ExceptionHandler::throwException("El campo '{$campo}' es inválido: " . self::$regex[$campo]['mensaje'], 400, \InvalidArgumentException::class);
         }
      }
      return true;
   }
   public static function validarFecha(string $fecha): bool
   {
      self::init();
      if (preg_match(self::$regex["fecha_nacimiento"]["regex"], $fecha)) {
         list($year, $month, $day) = explode('-', $fecha);
         if (checkdate($month, $day, $year)) {
            $valida = true;
            return $valida;
         }
      }
      ExceptionHandler::throwException("La fecha introducida no es válida", 400, \InvalidArgumentException::class);
      return false;
   }

   public static function existe(Database $database, string $id, string $consulta): bool
   {
      $response = $database->query($consulta, [":id" => $id], true);
      return (bool) $response;
   }

   public static function validarAsistencias(array $asistencias)
   {
      if (empty($asistencias)) {
         ExceptionHandler::throwException("Las asistencias no son validas", 400, \InvalidArgumentException::class);
      }
      foreach ($asistencias as $asistencia => $valor) {
         if (!preg_match(self::$regex["cedula"]["regex"], $valor["id_atleta"])) {
            ExceptionHandler::throwException("La cedula del atleta '{$valor["id_atleta"]}' es invalida", 400, \InvalidArgumentException::class);
         }
         if (gettype($valor["asistio"]) !== "integer" || $valor["asistio"] < 0 || $valor["asistio"] > 1) {
            ExceptionHandler::throwException("El valor de asistencia del atleta '{$valor["id_atleta"]}' es invalido", 400, \InvalidArgumentException::class);
         }
         if (!preg_match(self::$regex["detalles"]["regex"], $valor["comentario"])) {
            ExceptionHandler::throwException("El comentario del atleta '{$valor["id_atleta"]}' es invalido", 400, \InvalidArgumentException::class);
         }
      }
      return true;
   }

   public static function validarFechaMayorHoy(string $fecha)
   {
      $fechaSeleccionada = new \DateTime($fecha);
      $fechaActual = new \DateTime();
      // Restablecer la hora a 00:00:00 para comparar solo la fecha
      $fechaActual->setTime(0, 0, 0);
      $fechaSeleccionada->setTime(0, 0, 0);
      if (!($fechaSeleccionada >= $fechaActual)) {
         ExceptionHandler::throwException("La fecha ingresada no es mayor a la actual", 400, \InvalidArgumentException::class);
      }
      return $fechaSeleccionada >= $fechaActual;
   }
   public static function validar_fechas_wada($conexion, $cedula, $inscripcion, $ultima_actualizacion, $vencimiento)
   {
      $consulta = "SELECT fecha_nacimiento FROM usuarios WHERE cedula = :cedula";
      $con = $conexion->prepare($consulta);
      $con->execute([":cedula" => $cedula]);
      $fecha_nacimiento = $con->fetchColumn();
      if (!$fecha_nacimiento) {
         return ["ok" => false, "mensaje" => "El atleta no existe"];
      }
      foreach ([$inscripcion, $ultima_actualizacion, $fecha_nacimiento, $vencimiento] as $fecha) {
         if (!self::validar_fecha($fecha)) {
            return ["ok" => false, "mensaje" => "Las fechas no son válidas"];
         }
      }
      // Convertir las fechas a objetos DateTime para hacer las comparaciones
      $fecha_nacimiento_obj = new DateTime($fecha_nacimiento);
      $fecha_inscripcion_obj = new DateTime($inscripcion);
      $ultima_actualizacion_obj = new DateTime($ultima_actualizacion);
      $fecha_vencimiento_obj = new DateTime($vencimiento);
      // Validar que la inscripción se pueda realizar a partir de los 15 años
      $edad_minima = 15;
      $fecha_nacimiento_obj->modify("+$edad_minima years");
      if ($fecha_inscripcion_obj < $fecha_nacimiento_obj) {
         $mensaje_error = 'La inscripción no es válida: el atleta debe tener al menos 15 años';
         return ["ok" => false, "mensaje" => $mensaje_error];
      }
      // Calcular la fecha de vencimiento (un trimestre después de la última actualización)
      $fecha_vencimiento_esperada = clone $ultima_actualizacion_obj;
      $fecha_vencimiento_esperada->modify('+3 months');
      // Validar si la fecha de vencimiento es correcta
      if ($fecha_inscripcion_obj > $ultima_actualizacion_obj) {
         $mensaje_error = 'La inscripción no es válida: la fecha de inscripción no puede ser posterior a la ultima actualización';
         return ["ok" => false, "mensaje" => $mensaje_error];
      }
      if ($fecha_vencimiento_esperada > $fecha_vencimiento_obj) {
         $mensaje_error = 'El vencimiento no es válido: la fecha de vencimiento debe ser al menos un trimestre después de la última actualización';
         return ["ok" => false, "mensaje" => $mensaje_error];
      }
      if ($fecha_inscripcion_obj > $fecha_vencimiento_esperada) {
         $mensaje_error = 'La inscripción no es válida: la fecha de inscripción no puede ser posterior al vencimiento (trimestre después de la última actualización)';
         return ["ok" => false, "mensaje" => $mensaje_error];
      }
      return ["ok" => true];
   }
   public static function validarArray(array $array, array $keys): array
   {
      $arrayFiltrado = array_intersect_key($array, array_flip($keys));
      $missingFields = [];
      foreach ($keys as $key) {
         if (!array_key_exists($key, $array) || $array[$key] === null || $array[$key] === "") {
            $missingFields[] = $key;
         }
      }
      if (!empty($missingFields)) {
         ExceptionHandler::throwException("Los siguientes campos faltan: " . json_encode($missingFields), 400, \InvalidArgumentException::class);
      }
      return $arrayFiltrado;
   }
}
