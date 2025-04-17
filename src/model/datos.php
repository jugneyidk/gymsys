<?php
namespace Gymsys\Model;
class datos
{
  private \PDO|null $pdo;
  private string $host;
  private string $bd;
  private string $usuario;
  private string $password;
  private array $options;

  public function __construct()
  {
    $database = require_once dirname(__DIR__) . "/../config/database.php";
    $this->host = $database['host'];
    $this->bd = $database['bd'];
    $this->usuario = $database['usuario'];
    $this->password = $database['password'];
    $this->options = $database['options'];
  }
  public function conecta(): \PDO|null
  {
    $this->pdo = new \PDO("mysql:host={$this->host};dbname={$this->bd}", $this->usuario, $this->password);
    if (empty($this->pdo)) {
      throw new \PDOException("Error al conectar a la base de datos");
    }
    // $_SESSION["id_usuario"] = $_SESSION["id_usuario"] ?? "22222222";
    if (isset($_SESSION["id_usuario"])) {
      $usuario_actual = $_SESSION["id_usuario"];
      $this->pdo->exec("SET @usuario_actual = '$usuario_actual';");
    }
    foreach ($this->options as $option => $value) {
      $this->pdo->setAttribute($option, $value);
    }
    return $this->pdo;
  }

  protected function verificarConexion(): void
  {
    if ($this->pdo === null) {
      throw new \PDOException("Error: No se pudo establecer la conexión a la base de datos.");
    }
  }
  public function desconecta(): void
  {
    if ($this->pdo !== null) {
      $this->pdo = null;
    }
  }
}