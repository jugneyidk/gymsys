<?php

namespace Gymsys\Core;

use Gymsys\Model\Rolespermisos;
use Gymsys\Utils\ExceptionHandler;

abstract class BaseController
{
   public function renderVista(string $vista, array $permisosNav, array $permisosModulo): never
   {
      if (session_status() === PHP_SESSION_NONE) {
         session_start();
      }
      header('Content-Type: text/html');
      extract($permisosNav);
      $data['controller'] = $this;
      extract($data);
      require dirname(__DIR__) . "/../src/view/{$vista}.php";
      require dirname(__DIR__) . "/../src/view/comunes/carga.php";
      exit;
   }
   public function sendResponse(int $statusCode, mixed $data, bool $exception = false): never
   {
      http_response_code($statusCode);
      if (!empty($data)) {
         header('Content-Type: application/json');
         $ok = $exception ? false : true;
         echo json_encode(["ok" => $ok, "data" => $data]);
      }
      exit;
   }
   public function getPostData(): array
   {
      $data = json_decode(file_get_contents('php://input'), true);
      return $data;
   }
   public function getClass(string $page): string
   {
      $class = "Gymsys\Controller\\" . ucwords($page);
      if (!class_exists($class)) {
         ExceptionHandler::throwException("No existe el controlador con este nombre", 400, \BadFunctionCallException::class);
      }
      return $class;
   }
   public function getModel(string $page): string
   {
      $class = "Gymsys\Model\\" . ucwords($page);
      if (!class_exists($class)) {
         ExceptionHandler::throwException("No existe el modelo con este nombre", 400, \BadFunctionCallException::class);
      }
      return $class;
   }
   public function validarMetodoRequest(string $metodo): void
   {
      if ($_SERVER['REQUEST_METHOD'] !== $metodo) {
         ExceptionHandler::throwException("Método no permitido", 405, \BadMethodCallException::class);
      }
      return;
   }
   protected function generateCsrfToken(): string
   {
      if (session_status() !== PHP_SESSION_ACTIVE) {
         session_start();
      }
      $lifetime = 30;
      if (
         isset($_SESSION['csrf_token'], $_SESSION['csrf_token_time'])
         && (time() - $_SESSION['csrf_token_time']) < $lifetime
      ) {

         return $_SESSION['csrf_token'];
      }
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      $_SESSION['csrf_token_time'] = time();
      return $_SESSION['csrf_token'];
   }
   protected function verifyCsrfToken(): bool
   {
      if (session_status() !== PHP_SESSION_ACTIVE) {
         session_start();
      }
      $submitted = $_POST['_csrf_token'] ?? '';
      $session = $_SESSION['csrf_token'] ?? '';
      return hash_equals($session, $submitted);
   }
   protected function cleanExpiredTokens(int $expirySeconds = 600): void
   {
      $now = time();
      $_SESSION['csrf_tokens'] = array_filter($_SESSION['csrf_tokens'] ?? [], function ($timestamp) use ($now, $expirySeconds) {
         return ($now - $timestamp) < $expirySeconds;
      });
   }
   protected function csrfField(): string
   {
      $token = $this->generateCsrfToken();
      return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars($token) . '">';
   }

   /*protected function requireCsrf(): void
   {
      if (!$this->verifyCsrfToken()) {
         ExceptionHandler::throwException(
            'Token CSRF inválido',
            403,
            \Exception::class
         );
      }
   }*/

        protected function requireCsrf(): void
   {
      // MODO TESTING: Desactivar CSRF temporalmente
      $config = require dirname(__DIR__) . '/config.php';
      if ($config['SECURITY_TESTING_MODE'] === true || $config['CSRF_ENABLED'] === false) {
         error_log("ADVERTENCIA: Validación CSRF desactivada - Modo Testing");
         return;
      }
      
      // Validación normal de CSRF
      if (!$this->verifyCsrfToken()) {
         ExceptionHandler::throwException(
            'Token CSRF inválido',
            403,
            \Exception::class
         );
      }
   }
public function accionCsrfGlobal(): array
{
    return [ 'csrf_token' => $this->generateCsrfToken() ];
}

   protected function obtenerPermisos(string $modulo, Database $database): array
   {
      $permisos = Rolespermisos::obtenerPermisosModulo($modulo, $database);
      if (empty($permisos)) {
         ExceptionHandler::throwException("Acceso no autorizado", 403, \Exception::class);
      }
      return $permisos;
   }
   protected function validarPermisos(array $permisos, string $permiso): void
   {
      if (empty($permisos[$permiso])) {
         ExceptionHandler::throwException("Acceso no autorizado", 403, \Exception::class);
      }
   }
   public function accionCsrfNuevo(): array
   {
      if (session_status() !== PHP_SESSION_ACTIVE) {
         session_start();
      }

      $this->cleanExpiredTokens();

      $nuevo = bin2hex(random_bytes(32));
      $_SESSION['csrf_tokens'][$nuevo] = time();

      return ['nuevo_csrf_token' => $nuevo];
   }
}
