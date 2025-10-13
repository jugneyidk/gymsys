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
       if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
      unset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']);
      $this->deleteRefreshToken();
       $this->destroySession();  
      if (isset($_COOKIE['refresh_token'])) {
         ExceptionHandler::throwException("Ocurrio un error al cerrar la sesión", \UnexpectedValueException::class, 403);
      }
      return true;
   }
   private function deleteRefreshTokenCookie(): void
   {
      setcookie('refresh_token', '', time() - 3600, '/');
      unset($_COOKIE['refresh_token']);
   }
   private function destroySession(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
                  $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

   private function deleteRefreshToken(): void
   {
      $consulta = "UPDATE {$_ENV['SECURE_DB']}.usuarios_roles SET token = NULL WHERE token = :token;";
      $valores = [':token' => $_COOKIE['refresh_token']];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrio un error al cerrar la sesión", \UnexpectedValueException::class, 403);
      }
      $this->deleteRefreshTokenCookie();
      return;
   }
}
