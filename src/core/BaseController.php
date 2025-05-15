<?php

namespace Gymsys\Core;

use Gymsys\Model\Rolespermisos;
use Gymsys\Utils\ExceptionHandler;

abstract class BaseController
{
   public function renderVista(string $vista, array $permisosNav, array $permisosModulo): never
   {
      header('Content-Type: text/html');
      extract($permisosNav);
      extract($permisosModulo);
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
      $this->cleanExpiredTokens();
      $token = bin2hex(random_bytes(32));
      $_SESSION['csrf_tokens'][$token] = time(); // puedes limitar por tiempo si quieres
      return $token;
   }
   protected function verifyCsrfToken(): bool
   {
      $submittedToken = $_POST['_csrf_token'] ?? '';
      if (empty($submittedToken)) return false;
      if (isset($_SESSION['csrf_tokens'][$submittedToken])) {
         unset($_SESSION['csrf_tokens'][$submittedToken]);
         return true;
      }
      return false;
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
      return '<input type="hidden" name="_csrf_token" value=' . htmlspecialchars($this->generateCsrfToken()) . '>';
   }
   protected function obtenerPermisos(string $modulo, Database $database): array
   {
      $permisos = Rolespermisos::obtenerPermisosModulo($modulo, $database);
      if (empty($permisos)) {
         ExceptionHandler::throwException("Acceso no autorizado", 403, \Exception::class);
      }
      return $permisos;
   }
}
