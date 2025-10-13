<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\Validar;

class Restablecer
{
   private Database $database;

   public function __construct(Database $database)
   {
      $this->database = $database;
   }
   public function verificarToken(array $datos): array
   {
      $keys = ['token'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $tokenDecodificado = Cipher::descodificarBase64($arrayFiltrado['token']);
      return $this->_verificarToken($tokenDecodificado);
   }
   public function restablecerPassword(array $datos): array
   {
      $keys = ['token', 'nueva_password'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::validar("password", $arrayFiltrado['nueva_password']);
      $password = $arrayFiltrado['nueva_password'];
      $tokenDecodificado = Cipher::descodificarBase64($arrayFiltrado['token']);
      return $this->_restablecerPassword($tokenDecodificado, $password);
   }
   private function _verificarToken(string $token): array
   {
      $consulta = "SELECT expira FROM {$_ENV['SECURE_DB']}.`reset` WHERE token = :token";
      $resultado = $this->database->query($consulta, [':token' => $token], true);
      if (empty($resultado)) {
         ExceptionHandler::throwException("Token inválido", \Exception::class, 500);
      }
      $actual = date("Y-m-d H:i:s");
      if ($resultado['expira'] < $actual) {
         $this->eliminarTokensExpirados();
         ExceptionHandler::throwException("Token expirado", \Exception::class, 500);
      }
      return ['token' => $token];
   }
   private function _restablecerPassword(string $token, string $nuevaPassword): array|bool
   {
      $this->_verificarToken($token);
      try {
         $this->database->beginTransaction();
         $idUsuario = $this->obtenerIdUsuarioConToken($token);
         $hashPassword = password_hash($nuevaPassword, PASSWORD_DEFAULT);
         $this->actualizarPasswordDeUsuario($idUsuario, $hashPassword);
         $this->eliminarTokenDeRecuperacion($token);
         $this->database->commit();
         return ['mensaje' => 'Contraseña restablecida con éxito'];
      } catch (\Throwable $th) {
         $this->database->rollBack();
         $decoded = json_decode($th->getMessage(), true);
         ExceptionHandler::throwException($decoded['error'], \RuntimeException::class, $decoded['code']);
         return false;
      }
   }

   private function obtenerIdUsuarioConToken(string $token): string
   {
      $consulta = "SELECT cedula FROM {$_ENV['SECURE_DB']}.`reset` WHERE token = :token";
      $resultado = $this->database->query($consulta, [':token' => $token], true);
      if (empty($resultado)) {
         throw new \InvalidArgumentException(json_encode(["error" => "Token inválido", "code" => 400]));
      }
      return $resultado['cedula'];
   }

   private function actualizarPasswordDeUsuario(string $idUsuario, string $hashPassword): bool
   {
      $this->database->query("SET @usuario_actual = :id_usuario;", [':id_usuario' => $idUsuario]);
      $consulta = "UPDATE {$_ENV['SECURE_DB']}.usuarios_roles SET `password` = :new_password WHERE id_usuario = :id_usuario;";
      $response = $this->database->query($consulta, [':new_password' => $hashPassword, ':id_usuario' => $idUsuario]);
      if (empty($response)) {
         throw new \RuntimeException(json_encode(["error" => "Error al restablecer la contraseña", "code" => 500]));
      }
      return true;
   }

   private function eliminarTokenDeRecuperacion(string $token): bool
   {
      $consulta = "DELETE FROM `reset` WHERE token = :token";
      $response = $this->database->query($consulta, [':token' => $token]);
      if (empty($response)) {
         throw new \RuntimeException(json_encode(["error" => "Error al restablecer la contraseña", "code" => 500]));
      }
      return true;
   }
   private function eliminarTokensExpirados(): bool
   {
      $ahora = date('Y-m-d H:i:s');
      $this->database->beginTransaction();
      $consulta = "DELETE FROM `reset` WHERE expira < :ahora";
      $response = $this->database->query($consulta, [':ahora' => $ahora]);
      if (empty($response)) {
         throw new \RuntimeException(json_encode(["error" => "Error al restablecer la contraseña", "code" => 500]));
      }
      $this->database->commit();
      return true;
   }
}
