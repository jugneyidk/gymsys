<?php

namespace Gymsys\Core;

use Gymsys\Utils\ExceptionHandler;

class RedirectMiddleware
{
   public static function handle(array $routes, string $page, string $method, bool $accion): void
   {
      $exists = self::routeExists($routes, $page);
      if (!$exists) {
         if ($method === "GET") {
            header("Location: ?p=error404");
            exit;
         }
         ExceptionHandler::throwException("Ruta no encontrada", 404, \UnexpectedValueException::class);
      }
      // Regla 1: Si hay sesión activa y va al login redirige al dashboard
      if (isset($_SESSION['id_usuario']) && $page === 'login') {
         header("Location: ?p=dashboard");
         exit;
      }
      // Regla 2: Si no hay sesión y va a una ruta protegida redirige a login
      if (!isset($_SESSION['id_usuario']) && empty($routes[$page]["public"])) {
         if ($method === "GET" && empty($accion)) {
            header("Location: ?p=login");
            exit;
         }
         ExceptionHandler::throwException("Acceso no autorizado", 403, \UnexpectedValueException::class);
      }
      // Regla 3: Si hay sesión activa y va al landing redirige al dashboard
      if (isset($_SESSION['id_usuario']) && $page === 'landing') {
         header("Location: ?p=dashboard");
         exit;
      }
      // Regla 4: Si hay sesión activa y va al landing redirige al dashboard
      if (empty($routes[$page]['hasView']) && empty($accion)) {
         header("Location: .");
         exit;
      }
      // Regla 4: Si no hay sesión y no hay accion (se esta viendo el sitio en el navegador), redirige al login
      // if (empty($_SESSION) && empty($accion) && $page !== "login") {
      //    header("Location: ?p=login");
      //    exit;
      // }
   }
   private static function routeExists(array $routes, string $page): bool
   {
      return array_key_exists($page, $routes) && !empty($routes[$page]["enabled"]);
   }
}
