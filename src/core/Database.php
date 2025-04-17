<?php
namespace Gymsys\Core;
class Database
{
  private \PDO|null $pdo;
  private bool $inTransaction = false;
  public function __construct()
  {
    $database = require_once dirname(__DIR__) . "/../config/database.php";
    $this->pdo = new \PDO("mysql:host={$database['host']};dbname={$database['bd']}", $database['usuario'], $database['password']);
    if (empty($this->pdo)) {
      throw new \PDOException("Error al conectar a la base de datos");
    }
    foreach ($database['options'] as $option => $value) {
      $this->pdo->setAttribute($option, $value);
    }
    $this->pdo->exec("SET NAMES utf8");
  }
  public function query(string $sql, array $params = [], bool $uniqueFetch = false): array
  {
    if (empty($sql)) {
      throw new \InvalidArgumentException("Error de la query SQL", 400);
    }
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    if ($uniqueFetch === true) {
      return $stmt->fetch();
    }
    return $stmt->fetchAll();
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

  public function desconecta(): void
  {
    if ($this->pdo !== null) {
      $this->pdo = null;
    }
  }
}