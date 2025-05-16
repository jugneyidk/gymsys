<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\Validar;

class Login
{
   private Database $database;
   public function __construct(Database $database)
   {
      $this->database = $database;
   }
   public function authUsuario(string $id_usuario, string $password): array
   {
      Validar::validar("cedula", $id_usuario);
      Validar::validar("password", $password);
      return $this->_autenticarUsuario($id_usuario, $password);
   }
   private function _autenticarUsuario(string $id_usuario, string $password): array
   {
      $consulta = "SELECT id_rol, `password` FROM usuarios_roles WHERE id_usuario = :id_usuario";
      $valores = [':id_usuario' => $id_usuario];
      $resultado = $this->database->query($consulta, $valores, true);
      if (!empty($resultado) && password_verify($password, $resultado['password'])) {
         $_SESSION['rol'] = $resultado['id_rol'];
         $_SESSION['id_usuario'] = $id_usuario;
         $response = ["auth" => true];
      } else {
         ExceptionHandler::throwException("Los datos de usuario ingresado son incorrectos", 401, \InvalidArgumentException::class);
      }
      return $response;
   }
}
