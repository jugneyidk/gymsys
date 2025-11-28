<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\JWTHelper;
use Gymsys\Utils\Validar;
use Gymsys\Utils\LoginAttempts;

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
      LoginAttempts::checkAttempts();
      try {
         $response = $this->_autenticarUsuario($id_usuario, $password);
         LoginAttempts::clearAttempts();
         return $response;
      } catch (\Exception $e) {
         LoginAttempts::recordFailedAttempt();
         ExceptionHandler::throwException($e->getMessage(), get_class($e));
      }
   }
   private function _autenticarUsuario(string $id_usuario, string $password): array
   {
      $consulta = "SELECT id_rol, `password` FROM {$_ENV['SECURE_DB']}.usuarios_roles WHERE id_usuario = :id_usuario";
      $valores = [':id_usuario' => $id_usuario];
      $resultado = $this->database->query($consulta, $valores, true);
      if (!empty($resultado) && password_verify($password, $resultado['password'])) {
         $tokens = JWTHelper::generarTokens($id_usuario);
         $response["auth"] = true;
         $clientType = $_SERVER['HTTP_X_CLIENT_TYPE'] ?? 'web';
         $esWeb = $clientType === 'web';
         $this->_saveRefreshToken($tokens['refreshToken'], $id_usuario);
         if ($esWeb) {
            JWTHelper::guardarRefreshCookie($tokens['refreshToken']);
            $response = array_merge($response, ['accessToken' => $tokens['accessToken']]);
         } else {
            $response = array_merge($response, [
               'accessToken' => $tokens['accessToken'],
               'refreshToken' => $tokens['refreshToken']
            ]);
         }
      } else {
         throw new \InvalidArgumentException("Los datos de usuario ingresado son incorrectos");
      }
      return $response;
   }
   private function _saveRefreshToken(string $refreshToken, string $idUsuario): bool
   {
      $consulta = "UPDATE {$_ENV['SECURE_DB']}.usuarios_roles SET token = :token WHERE id_usuario = :id_usuario;";
      $valores = [':token' => $refreshToken, ':id_usuario' => $idUsuario];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrio un error al guardar la sesión", \UnexpectedValueException::class, 500);
         return false;
      }
      return true;
   }
}
