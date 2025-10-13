<?php

namespace Gymsys\Utils;

use Gymsys\Core\Database;

class Validar
{
   protected \PDO $database;
   protected static $regex;
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
            ExceptionHandler::throwException(self::$regex[$campo]["mensaje"], \UnexpectedValueException::class);
         }
         return true;
      }
      ExceptionHandler::throwException("No se encontró expresion regular para el campo '{$campo}'", \InvalidArgumentException::class);
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
            ExceptionHandler::throwException("El campo '{$campo}' es inválido: " . self::$regex[$campo]['mensaje'], \InvalidArgumentException::class);
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
      ExceptionHandler::throwException("La fecha introducida no es válida", \InvalidArgumentException::class);
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
         ExceptionHandler::throwException("Las asistencias no son validas", \InvalidArgumentException::class);
      }
      foreach ($asistencias as $asistencia => $valor) {
         if (!preg_match(self::$regex["cedula"]["regex"], $valor["id_atleta"])) {
            ExceptionHandler::throwException("La cedula del atleta '{$valor["id_atleta"]}' es invalida", \InvalidArgumentException::class);
         }
         if (gettype($valor["asistio"]) !== "integer" || $valor["asistio"] < 0 || $valor["asistio"] > 1) {
            ExceptionHandler::throwException("El valor de asistencia del atleta '{$valor["id_atleta"]}' es invalido", \InvalidArgumentException::class);
         }
         if (!preg_match(self::$regex["detalles"]["regex"], $valor["comentario"])) {
            ExceptionHandler::throwException("El comentario del atleta '{$valor["id_atleta"]}' es invalido", \InvalidArgumentException::class);
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
         ExceptionHandler::throwException("La fecha ingresada no es mayor a la actual", \InvalidArgumentException::class);
      }
      return $fechaSeleccionada >= $fechaActual;
   }
   public static function validarFechasWada(Database $database, string $cedula, string $inscripcion, string $ultimaActualizacion, string $vencimiento): void
   {
      $consulta = "SELECT fecha_nacimiento FROM {$_ENV['SECURE_DB']}.usuarios WHERE cedula = :cedula";
      $response = $database->query($consulta, [":cedula" => $cedula], true);
      $fechaNacimiento = $response['fecha_nacimiento'] ?: false;
      if (empty($fechaNacimiento)) {
         ExceptionHandler::throwException("El atleta no existe", \InvalidArgumentException::class);
      }
      foreach ([$inscripcion, $ultimaActualizacion, $fechaNacimiento, $vencimiento] as $fecha) {
         if (!self::validarFecha($fecha)) {
            ExceptionHandler::throwException("Las fechas no son válidas", \InvalidArgumentException::class);
         }
      }
      // Convertir las fechas a objetos DateTime para hacer las comparaciones
      $fechaNacimientoObj = new \DateTime($fechaNacimiento);
      $fechaInscripcionObj = new \DateTime($inscripcion);
      $ultimaActualizacionObj = new \DateTime($ultimaActualizacion);
      $fechaVencimientoObj = new \DateTime($vencimiento);
      // Validar que la inscripción se pueda realizar a partir de los 15 años
      $edad_minima = 15;
      $fechaNacimientoObj->modify("+$edad_minima years");
      if ($fechaInscripcionObj < $fechaNacimientoObj) {
         ExceptionHandler::throwException("La inscripción no es válida: el atleta debe tener al menos 15 años", \InvalidArgumentException::class);
      }
      // Calcular la fecha de vencimiento (un trimestre después de la última actualización)
      $fechaVencimientoEsperada = clone $ultimaActualizacionObj;
      $fechaVencimientoEsperada->modify('+3 months');
      // Validar si la fecha de vencimiento es correcta
      if ($fechaInscripcionObj > $ultimaActualizacionObj) {
         ExceptionHandler::throwException("La inscripción no es válida: la fecha de inscripción no puede ser posterior a la ultima actualización", \InvalidArgumentException::class);
      }
      if ($fechaVencimientoEsperada > $fechaVencimientoObj) {
         ExceptionHandler::throwException("El vencimiento no es válido: la fecha de vencimiento debe ser al menos un trimestre después de la última actualización", \InvalidArgumentException::class);
      }
      if ($fechaInscripcionObj > $fechaVencimientoEsperada) {
         ExceptionHandler::throwException("La inscripción no es válida: la fecha de inscripción no puede ser posterior al vencimiento (trimestre después de la última actualización)", \InvalidArgumentException::class);
      }
      return;
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
         ExceptionHandler::throwException("Los siguientes campos faltan: " . json_encode($missingFields), \InvalidArgumentException::class);
      }
      return $arrayFiltrado;
   }
   public static function sanitizarYValidar(mixed &$valor, string $tipoEsperado): bool
   {
      $valor = trim($valor);
      switch ($tipoEsperado) {
         case 'int':
            $valor = filter_var($valor, FILTER_SANITIZE_NUMBER_INT);
            if (filter_var($valor, FILTER_VALIDATE_INT) !== false) {
               $valor = (int)$valor;
               return true;
            }
            ExceptionHandler::throwException("El valor ingresado no es un número entero válido", \InvalidArgumentException::class);

         case 'float':
            $valor = filter_var($valor, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            if (filter_var($valor, FILTER_VALIDATE_FLOAT) !== false) {
               $valor = (float)$valor;
               return true;
            }
            ExceptionHandler::throwException("El valor ingresado no es un número decimal válido", \InvalidArgumentException::class);

         case 'string':
            $valor = htmlspecialchars($valor);
            return true;

         case 'email':
            $valor = filter_var($valor, FILTER_SANITIZE_EMAIL);
            if (filter_var($valor, FILTER_VALIDATE_EMAIL) !== false) {
               return true;
            }
            ExceptionHandler::throwException("El correo electrónico ingresado no es válido", \InvalidArgumentException::class);

         case 'url':
            $valor = filter_var($valor, FILTER_SANITIZE_URL);
            if (filter_var($valor, FILTER_VALIDATE_URL) !== false) {
               return true;
            }
            ExceptionHandler::throwException("La URL ingresada no es válida", \InvalidArgumentException::class);

         case 'bool':
            $valor = filter_var($valor, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($valor !== null) {
               return true;
            }
            ExceptionHandler::throwException("El valor booleano ingresado no es válido", \InvalidArgumentException::class);
         default:
            ExceptionHandler::throwException("Tipo de dato no soportado para validación", \InvalidArgumentException::class);
      }
   }
}
