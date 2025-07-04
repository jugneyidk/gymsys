<?php

namespace Gymsys\Core;

use Gymsys\Utils\ExceptionHandler;

class Database
{
   private \PDO|null $pdo;
   private bool $inTransaction = false;
   public function __construct()
   {
      $database = require_once dirname(__DIR__) . "/../config/database.php";
      $this->pdo = new \PDO("mysql:host={$database['host']};dbname={$database['bd']}", $database['usuario'], $database['password'], $database['options']);
      if (empty($this->pdo)) {
         ExceptionHandler::throwException("Error al conectar a la base de datos", 500, \PDOException::class);
      }
      $this->pdo->exec("SET NAMES utf8");
      $this->setIsolationLevel('REPEATABLE READ');
      if (defined('ID_USUARIO')) {
         $usuarioActual = ID_USUARIO;
         $this->pdo->exec("SET @usuario_actual = '$usuarioActual';");
         $this->pdo->exec("SET @secure_db = '$_ENV[SECURE_DB]';");
      }
   }
   public function query(string $sql, array $params = [], bool $uniqueFetch = false): array|bool
   {
      if (empty($sql)) {
         ExceptionHandler::throwException("Error de la query SQL", 400, \InvalidArgumentException::class);
      }
      try {
         $stmt = $this->pdo->prepare($sql);
         if (!empty($params)) {
            $parametros = $this->obtenerParametros($sql, $params);
         }
         foreach (($parametros ?? $params) as $clave => $valor) {
            $stmt->bindValue($clave, $valor);
         }
         $stmt->execute();
         $affected = $stmt->rowCount();
         if ($uniqueFetch === true) {
            return $stmt->fetch();
         }
         $result = $stmt->fetchAll();
         return $result ?: ($affected > 0);
      } catch (\PDOException $e) {
         if ($this->inTransaction) {
            $this->rollBack();
         }
         if (ENVIRONMENT != 'DEVELOPMENT') {
            ExceptionHandler::throwException($this->mensajesDeError($e->getCode()), 500, \RuntimeException::class);
         }
         ExceptionHandler::throwException($e->getMessage(), 500, \RuntimeException::class);
         return false;
      }
   }
   public function beginTransaction(): bool
   {
      if (!$this->inTransaction) {
         $this->inTransaction = $this->pdo->beginTransaction();
         return $this->inTransaction;
      }
      return false;
   }
   public function commit(): bool
   {
      if ($this->inTransaction) {
         $result = $this->pdo->commit();
         $this->inTransaction = false;
         return $result;
      }
      return false;
   }
   public function rollBack(): bool
   {
      if ($this->inTransaction) {
         $result = $this->pdo->rollBack();
         $this->inTransaction = false;
         return $result;
      }
      return false;
   }
   private function obtenerParametros(string $consulta, array $valores): array
   {
      preg_match_all('/(:\w+)/', $consulta, $matches);
      $placeholders = array_flip($matches[1]);
      // se obtienen las claves que esten en ambos arreglos
      $params = array_intersect_key($valores, $placeholders);
      return $params;
   }
   public function lastInsertId(): int|bool
   {
      if ($this->inTransaction) {
         return $this->pdo->lastInsertId();
      }
      return false;
   }
   public function desconecta(): void
   {
      if ($this->pdo !== null) {
         $this->pdo = null;
      }
   }
   public function setIsolationLevel(string $level): void
   {
      $this->pdo->exec("SET TRANSACTION ISOLATION LEVEL $level;");
   }
   private function mensajesDeError(string $codigo): string
   {
      $pdoErrorMessages = [
         // Conexión
         '1045' => 'No se pudo conectar a la base de datos. Verifica tus credenciales.',
         '1049' => 'La base de datos especificada no existe.',
         '2002' => 'No se puede establecer conexión con el servidor. Intenta más tarde.',

         // Integridad de datos
         '23000' => 'No se puede completar la operación porque ya existe un registro relacionado o se violan restricciones de integridad.',
         '1451' => 'No puedes eliminar este registro porque está relacionado con otros datos.',
         '1452' => 'No se puede guardar el registro porque hace referencia a datos inexistentes.',
         '1062' => 'Ya existe un registro con esta información. Verifica los datos ingresados.',

         // Sintaxis SQL
         '42000' => 'Hubo un error al procesar tu solicitud. Verifica los datos e inténtalo nuevamente.',

         // Error general
         'HY000' => 'Ocurrió un error inesperado en el servidor. Por favor, inténtalo más tarde.',

         // Por defecto
         'default' => 'Ha ocurrido un problema al procesar tu solicitud. Intenta nuevamente o contacta al administrador.',
      ];
      return $pdoErrorMessages[$codigo] ?? $pdoErrorMessages['default'];
   }
}
