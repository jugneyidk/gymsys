<?php
namespace Gymsys\Core;
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
      throw new \UnexpectedValueException("Ruta no encontrada", 404);
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
      throw new \UnexpectedValueException("Acceso no autorizado", 403);
    }
    // Regla 3: Si hay sesión activa y va al landing redirige al dashboard
    if (isset($_SESSION['id_usuario']) && $page === 'landing') {
      header("Location: ?p=dashboard");
      exit;
    }
  }
  private static function routeExists(array $routes, string $page): bool
  {
    return array_key_exists($page, $routes) || !empty($routes[$page]["enabled"]);
  }
}