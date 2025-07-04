<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\JWTHelper;
use Gymsys\Utils\Validar;
use Gymsys\Utils\LoginAttempts;

class Login
{
   private Database $database;
   public function __construct(Database $database)
   {
      $this->database = $database;
   }
   public function authUsuario(array $datos): array
   {
      $aesKey = base64_decode(Cipher::desencriptarRSA($datos['encryptedKey']));
      $decoded = Cipher::descodificarBase64($datos['encryptedData']);
      $iv_length = openssl_cipher_iv_length('AES-256-CTR');
      $iv = substr($decoded, 0, $iv_length);
      $ciphertext = substr($decoded, $iv_length);
      if (strlen($aesKey) !== 32) {
         throw new \RuntimeException('La clave AES no tiene 32 bytes, tiene: ' . strlen($aesKey));
      }
      $datos = json_decode(Cipher::aesDecrypt($datos['encryptedData'], false, $aesKey), true);
      Validar::validar("cedula", $datos['id_usuario']);
      Validar::validar("password", $datos['password']);
      LoginAttempts::checkAttempts();
      try {
         $response = $this->_autenticarUsuario($datos['id_usuario'], $datos['password']);
         // Si la autenticación es exitosa, limpiar los intentos fallidos
         LoginAttempts::clearAttempts();
         return $response;
      } catch (\Exception $e) {
         // Registrar intento fallido
         LoginAttempts::recordFailedAttempt();
         ExceptionHandler::throwException($e->getMessage(), 400, $e);
         return [];
      }
   }
   private function _autenticarUsuario(string $id_usuario, string $password): array
   {
      $consulta = "SELECT id_rol, `password` FROM {$_ENV['SECURE_DB']}.usuarios_roles WHERE id_usuario = :id_usuario";
      $valores = [':id_usuario' => $id_usuario];
      $resultado = $this->database->query($consulta, $valores, true);
      if (!empty($resultado) && password_verify($password, $resultado['password'])) {
         $tokens = JWTHelper::generarTokens($id_usuario);
         $response["auth"] = true;
         $clientType = $_SERVER['HTTP_X_CLIENT_TYPE'] ?? 'web';
         $esWeb = $clientType === 'web';
         $this->_saveRefreshToken($tokens['refreshToken'], $id_usuario);
         if ($esWeb) {
            JWTHelper::guardarRefreshCookie($tokens['refreshToken']);
            $response = array_merge($response, ['accessToken' => $tokens['accessToken']]);
         } else {
            $response = array_merge($response, [
               'accessToken' => $tokens['accessToken'],
               'refreshToken' => $tokens['refreshToken']
            ]);
         }
      } else {
         throw new \InvalidArgumentException("Los datos de usuario ingresado son incorrectos");
      }
      return $response;
   }
   private function _saveRefreshToken(string $refreshToken, string $idUsuario): bool
   {
      $consulta = "UPDATE {$_ENV['SECURE_DB']}.usuarios_roles SET token = :token WHERE id_usuario = :id_usuario;";
      $valores = [':token' => $refreshToken, ':id_usuario' => $idUsuario];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrio un error al guardar la sesión", 500, \UnexpectedValueException::class);
         return false;
      }
      return true;
   }
}
