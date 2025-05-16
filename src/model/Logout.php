<?php

namespace Gymsys\Model;

use Exception;
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
      session_unset();
      session_destroy();
      if (!empty($_SESSION["id_usuario"])) {
         ExceptionHandler::throwException("Ocurrio un error al cerrar la sesión", 500, \UnexpectedValueException::class);
      }
      return true;
   }
}
