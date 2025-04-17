<?php
namespace Gymsys\Model;
use Gymsys\Core\Database;
use Gymsys\Utils\Validar;
class Login
{
  private Database $database;
  private string $id_usuario;
  private string $password;
  public function __construct(Database $database)
  {
    $this->database = $database;
  }
  public function auth(string $id_usuario, string $password): array
  {
    // $validacion_usuario = Validar::validar("cedula", $id_usuario);
    // if (!$validacion_usuario["ok"]) {
    //   return $validacion_usuario;
    // }
    // $validacion_password = Validar::validar("password", $password);
    // if (!$validacion_password["ok"]) {
    //   return $validacion_password;
    // }
    $this->id_usuario = $id_usuario;
    $this->password = $password;
    return $this->_autenticarUsuario();
  }
  private function _autenticarUsuario(): array
  {
    // session_unset();
    // session_destroy();
    $consulta = "SELECT id_rol, `password` FROM usuarios_roles WHERE id_usuario = :id_usuario";
    $valores = [':id_usuario' => $this->id_usuario];
    $resultado = $this->database->query($consulta, $valores, true);
    if (!empty($resultado) && password_verify($this->password, $resultado['password'])) {
      // session_start();
      $_SESSION['rol'] = $resultado['id_rol'];
      $_SESSION['id_usuario'] = $this->id_usuario;
      $response = ["auth" => true];
    } else {
      throw new \InvalidArgumentException("Los datos de usuario ingresado son incorrectos");
    }
    return $response;
  }
}