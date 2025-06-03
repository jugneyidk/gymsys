<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;

class Logout
{
   private Database $database;
   public function __construct(Database $database)
   {
      $this->database = $database;
   }
   public function logOut(): bool
   {
      $this->deleteRefreshToken();
      if (isset($_COOKIE['refresh_token'])) {
         ExceptionHandler::throwException("Ocurrio un error al cerrar la sesión", 500, \UnexpectedValueException::class);
      }
      return true;
   }
   private function deleteRefreshTokenCookie(): void
   {
      setcookie('refresh_token', '', time() - 3600, '/');
      unset($_COOKIE['refresh_token']);
   }
   private function deleteRefreshToken(): void
   {
      $consulta = "UPDATE {$_ENV['SECURE_DB']}.usuarios_roles SET token = NULL WHERE token = :token;";
      $valores = [':token' => $_COOKIE['refresh_token']];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrio un error al cerrar la sesión", 500, \UnexpectedValueException::class);
      }
      $this->deleteRefreshTokenCookie();
      return;
   }
}
