<?php

namespace Gymsys\Core;

use Gymsys\Utils\Cipher;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\JWTHelper;

class RedirectMiddleware
{
   public static function handle(array $routes, string $page, string $method, bool $accion): void
   {
      //  MODO TESTING
      $config = require dirname(__DIR__) . '/config.php';
      $testingMode = $config['SECURITY_TESTING_MODE'] ?? false;
      $jwtEnabled = $config['JWT_ENABLED'] ?? true;

      // Validar existencia de la ruta
      if (!self::routeExists($routes, $page)) {
         if ($method === "GET") {
            header("Location: ?p=error404");
            exit;
         }
         ExceptionHandler::throwException("Ruta no encontrada", 404, \UnexpectedValueException::class);
      }

      // Determinar si la ruta es pública
      $esPublica = !empty($routes[$page]["public"]);
      
      // MODO TESTING: Bypass de JWT
      if ($testingMode === true || $jwtEnabled === false) {
         error_log("ADVERTENCIA: Validación JWT desactivada - Modo Testing");
         // Definir ID_USUARIO vacío o con un usuario de prueba
         define('ID_USUARIO', '28609560'); // Usuario de prueba para testing
         
         // Permitir acceso sin más validaciones
         if (empty($routes[$page]['hasView']) && $accion === false) {
            header("Location: .");
            exit;
         }
         return;
      }


      // CÓDIGO ORIGINAL EJECUCION NORMAL
      
      $payload = null;
      if (!$esPublica && $accion === true && ($page !== 'login' && $page !== 'authrefresh' && $page !== 'logout')) {
         $payload = JWTHelper::obtenerPayload();
      }
      elseif (!$esPublica && $accion === false) {
         $payload = JWTHelper::obtenerPayload(true);
      }
      elseif (in_array($page, ['login', 'landing', 'authrefresh', 'logout'])) {
         $payload = JWTHelper::obtenerPayload(true);
      }

      $idUsuario = $payload ? Cipher::aesDecrypt($payload->sub) : '';
      define('ID_USUARIO', $idUsuario);

      // Reglas de redirección
      if (!empty(ID_USUARIO) && $page === 'login') {
         header("Location: ?p=dashboard");
         exit;
      }

      if (empty(ID_USUARIO) && !$esPublica) {
         if ($method === "GET" && $accion === false) {
            header("Location: ?p=login");
            exit;
         }
         ExceptionHandler::throwException("Acceso no autorizado", 403, \UnexpectedValueException::class);
      }

      if (!empty(ID_USUARIO) && $page === 'landing') {
         header("Location: ?p=dashboard");
         exit;
      }

      if (empty($routes[$page]['hasView']) && $accion === false) {
         header("Location: .");
         exit;
      }
   }

   private static function routeExists(array $routes, string $page): bool
   {
      return array_key_exists($page, $routes) && !empty($routes[$page]["enabled"]);
   }
}
