<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\JWTHelper;

class AuthRefresh
{
   private Database $database;
   public function __construct(Database $database)
   {
      $this->database = $database;
   }
   public function refreshToken(): array
   {
      $tokenRefresh = JWTHelper::obtenerPayload(true);
      $idUsuario = Cipher::aesDecrypt($tokenRefresh->sub) ?? null;
      $consultaToken = "SELECT token FROM {$_ENV['SECURE_DB']}.usuarios_roles WHERE id_usuario = :id_usuario;";
      $valoresToken = [':id_usuario' => $idUsuario];
      $tokenRefreshGuardado = $this->database->query($consultaToken, $valoresToken, true)['token'] ?? null;
      if (!$tokenRefreshGuardado) {
         setcookie('refresh_token', '', time() - 3600, '/');
         unset($_COOKIE['refresh_token']);
         ExceptionHandler::throwException('Token no encontrado', \InvalidArgumentException::class, 403);
      }
      $tokenDescodificado = JWTHelper::decodificarToken($tokenRefreshGuardado);
      $idTokenDB = Cipher::aesDecrypt($tokenDescodificado->sub) ?? null;
      if ($idTokenDB !== $idUsuario) {
         ExceptionHandler::throwException('Token inválido', \InvalidArgumentException::class, 403);
      }
      $tokens = JWTHelper::generarTokens($idUsuario);
      $this->database->beginTransaction();
      $consultaToken = "UPDATE {$_ENV['SECURE_DB']}.usuarios_roles SET token = :token WHERE id_usuario = :id_usuario;";
      $valoresToken = [':id_usuario' => $idUsuario, ':token' => $tokens['refreshToken']];
      $response = $this->database->query($consultaToken, $valoresToken);
      if (empty($response)) {
         ExceptionHandler::throwException('Error al actualizar el token', \RuntimeException::class, 500);
      }
      $this->database->commit();
      $clientType = $_SERVER['HTTP_X_CLIENT_TYPE'] ?? 'web';
      $esWeb = $clientType === 'web';
      $resultado["auth"] = true;
      if ($esWeb) {
         JWTHelper::guardarRefreshCookie($tokens['refreshToken']);
         $resultado = array_merge($resultado, ['accessToken' => $tokens['accessToken']]);
      } else {
         $resultado = array_merge($resultado, [
            'accessToken' => $tokens['accessToken'],
            'refreshToken' => $tokens['refreshToken']
         ]);
      }
      return $resultado;
   }
}
