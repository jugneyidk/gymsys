<?php

namespace Gymsys\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHelper
{
   public static function generarTokens(string $idUsuario): array
   {
      $now = time();
      $idUsuario = Cipher::aesEncrypt($idUsuario);
      $payloadAccess = [
         'sub' => $idUsuario,
         'iat' => $now,
         'exp' => $now + (30 * 60),
         'type' => 'access'
      ];
      $payloadRefresh = [
         'sub' => $idUsuario,
         'iat' => $now,
         'exp' => $now + (60 * 60 * 24 * 3),
         'type' => 'refresh'
      ];
      $accessToken = self::codificarToken($payloadAccess);
      $refreshToken = self::codificarToken($payloadRefresh);
      return ['accessToken' => $accessToken, 'refreshToken' => $refreshToken];
   }
   public static function obtenerPayload(bool $refreshToken = false): \stdClass|false
   {
      $token = $refreshToken ? ($_COOKIE['refresh_token'] ?? null) : self::obtenerBearerToken();
      if (!$token) return false;
      $payload = self::decodificarToken($token);
      $expected = $refreshToken ? 'refresh' : 'access';
      if (!isset($payload->type) || $payload->type !== $expected) {
         ExceptionHandler::throwException(
            "Token inválido para {$expected}",
            403,
            \Exception::class
         );
      }
      return $payload;
   }
  private static function obtenerBearerToken(): string|null
{
    $headers = function_exists('getallheaders') ? getallheaders() : [];
    $authHeader = $headers['Authorization'] ?? ($headers['authorization'] ?? null);
    if (empty($authHeader)) {
        ExceptionHandler::throwException('No se encontro el token', \Exception::class, 403);
    }
    $bearerToken = explode(' ', $authHeader);
    return $bearerToken[1] ?? null;
}


   public static function decodificarToken(string $token): \stdClass|false
   {
      $key = $_ENV['JWT_SECRET'];
      try {
         return JWT::decode($token, new Key($key, 'HS256'));
      } catch (\Firebase\JWT\ExpiredException $e) {
         ExceptionHandler::throwException(
            'Token expirado',
             \Exception::class,
             401
         );
         return false;
      } catch (\Exception $e) {
         return false;
      }
   }
   private static function codificarToken(array $payload): string
   {
      $key = $_ENV['JWT_SECRET'];
      return JWT::encode($payload, $key, 'HS256');
   }
   public static function guardarRefreshCookie(string $refreshToken): bool
   {
      if (empty($refreshToken)) {
         ExceptionHandler::throwException('No se proporcionó un token de refresco', \InvalidArgumentException::class, 401);
      }
      $options = [
         'expires' => time() + 60 * 60 * 24 * 3,
         'httponly' => true,  // solo servidor
         'secure' => ENVIRONMENT === 'PRODUCTION' ? true : false,    // solo HTTPS
         'samesite' => 'Strict',
         'path' => '/'
      ];
      try {
         setcookie("refresh_token", $refreshToken, $options);
         return true;
      } catch (\Exception $e) {
         ExceptionHandler::throwException('Error al guardar el token de refresco', \Exception::class, 500);
         return false;
      }
   }
}
