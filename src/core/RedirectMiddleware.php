<?php

namespace Gymsys\Core;

use Gymsys\Utils\Cipher;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\JWTHelper;

class RedirectMiddleware
{
   public static function handle(array $routes, string $page, string $method, bool $accion): void
   {
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
      // Verificar JWT sólo en llamadas a API (accion=true), ruta no pública, y no en login/accion
      $payload = null;
      if (!$esPublica && $accion === true && ($page !== 'login' && $page !== 'authrefresh' && $page !== 'logout')) {
         // API protegida: usa el access_token por defecto
         $payload = JWTHelper::obtenerPayload();
      }
      // Si la ruta no es pública y es carga de vista (accion=false), usar refresh_token
      elseif (!$esPublica && $accion === false) {
         $payload = JWTHelper::obtenerPayload(true);
      }
      // Siempre intentar refresh token en login y landing (para ocultar enlaces)
      elseif (in_array($page, ['login', 'landing', 'authrefresh', 'logout'])) {
         $payload = JWTHelper::obtenerPayload(true);
      }
      // Si la ruta es pública o es login/API, $payload queda null

      // Definir ID_USUARIO (o cadena vacía si no hay payload)
      $idUsuario = $payload ? Cipher::aesDecrypt($payload->sub) : '';
      define('ID_USUARIO', $idUsuario);

      //  Reglas de redirección/bloqueo

      // Regla 1: Si ya está logueado y va a login, redirecciono al dashboard
      if (!empty(ID_USUARIO) && $page === 'login') {
         header("Location: ?p=dashboard");
         exit;
      }

      // Regla 2: Si no está logueado y la ruta es protegida
      if (empty(ID_USUARIO) && !$esPublica) {
         if ($method === "GET" && $accion === false) {
            header("Location: ?p=login");
            exit;
         }
         ExceptionHandler::throwException("Acceso no autorizado", 403, \UnexpectedValueException::class);
      }

      // Regla 3: Si ya está logueado y va al landing
      if (!empty(ID_USUARIO) && $page === 'landing') {
         header("Location: ?p=dashboard");
         exit;
      }

      // Regla 4: Si la ruta no tiene vista y es carga de vista
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
