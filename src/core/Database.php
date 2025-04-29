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
      if (isset($_SESSION['id_usuario'])) {
         $usuarioActual = $_SESSION['id_usuario'];
         $this->pdo->exec("SET @usuario_actual = '$usuarioActual';");
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
}
