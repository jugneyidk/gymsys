<?php

namespace Gymsys\Utils;

class Cipher
{
   private const CIPHER = "AES-256-CTR";
   private static string $key;
   private static function init(): void
   {
      self::$key = $_ENV["AES_KEY"];
   }
   /**
    * Cifra una cadena con el algoritmo configurado y devuelve el resultado codificado en base64.
    * El vector de inicialización (IV) se genera aleatoriamente y se codifica
    * en base64 junto con el texto cifrado.
    *
    * @param string $data  Cadena a cifrar.
    * @param bool $ivFijo            Determina si se usa un IV aleatorio.
    * @return string       Cadena cifrada y codificada en base64.
    */
   public static function aesEncrypt(string $data, bool $ivFijo = false): string
   {
      self::init();
      if (!$ivFijo) {
         $iv_length = openssl_cipher_iv_length(self::CIPHER);
         $iv = openssl_random_pseudo_bytes($iv_length); // IV aleatorio
         $encrypted = openssl_encrypt($data, self::CIPHER, self::$key, OPENSSL_RAW_DATA, $iv);
         $resultado = $iv . $encrypted;
      } else {
         $resultado = openssl_encrypt($data, self::CIPHER, self::$key, OPENSSL_RAW_DATA);
      }
      // Codificamos ambos en base64 para usarlos en URL
      return self::codificarBase64($resultado);
   }

   /**
    * Descifra una cadena con el algoritmo configurado. La cadena cifrada y el IV se
    * esperan codificados en base64 y concatenados en un solo string.
    *
    * @param string $encryptedData  Cadena cifrada y codificada en base64.
    * @param bool $ivFijo            Determina si se usa un IV aleatorio.
    * @return string|bool            Cadena descifrada o false en caso de error.
    */
   public static function aesDecrypt(string $encryptedData, bool $ivFijo = false): bool|string
   {
      self::init();
      try {
         if (!$ivFijo) {
            $iv_length = openssl_cipher_iv_length(self::CIPHER);
            $decoded = self::descodificarBase64($encryptedData);
            if (strlen($decoded) < $iv_length) {
               throw new \UnexpectedValueException("Datos cifrados inválidos: longitud insuficiente para contener el IV");
            }
            $iv = substr($decoded, 0, $iv_length);
            if (strlen($iv) !== $iv_length) {
               throw new \UnexpectedValueException("IV inválido: se esperaban $iv_length bytes, se obtuvieron " . strlen($iv) . " bytes");
            }
            $ciphertext = substr($decoded, $iv_length);
            return openssl_decrypt($ciphertext, self::CIPHER, self::$key, OPENSSL_RAW_DATA, $iv);
         }
         $decoded = self::descodificarBase64($encryptedData);
         return openssl_decrypt($decoded, self::CIPHER, self::$key, OPENSSL_RAW_DATA);
      } catch (\Throwable $th) {
         ExceptionHandler::throwException($th->getMessage(), 500, \UnexpectedValueException::class);
         return false;
      }
   }

   /**
    * @param string $cadena
    * @return string
    */
   public static function descodificarBase64(string $cadena): string
   {
      $remainder = strlen($cadena) % 4;
      if ($remainder > 0) {
         $cadena .= str_repeat('=', 4 - $remainder);
      }
      return base64_decode(strtr($cadena, '-_', '+/'));
   }
   /**
    * @param string $cadena
    * @return string
    */
   public static function codificarBase64(string $cadena): string
   {
      return rtrim(strtr(base64_encode($cadena), '+/', '-_'), '=');
   }
   public static function encriptarCampoArray(array &$datos, string $nombreCampo, bool $clonarValor = true): bool
   {
      self::init();
      $sufijo = "";
      if ($clonarValor) {
         $sufijo = "_encriptado";
      }
      foreach ($datos as $index => $row) {
         $datos[$index]["$nombreCampo$sufijo"] = self::aesEncrypt($row[$nombreCampo]);
      }
      return true;
   }
   public static function desencriptarCampoArray(array &$datos, string $nombreCampo): bool
   {
      self::init();
      foreach ($datos as $index => $row) {
         $datos[$index][$nombreCampo] = self::aesdecrypt($row[$nombreCampo]);
      }
      return true;
   }
   public static function crearHash(string|int &$data): string
   {
      $data = hash("md5", $data);
      return $data;
   }
   public static function crearHashArray(array &$datos, string $nombreCampo, bool $clonarValor = true): bool
   {
      foreach ($datos as $index => $row) {
         $datos[$index][$nombreCampo . "_hash"] = self::crearHash($row[$nombreCampo]);
         if (!$clonarValor) {
            unset($datos[$index][$nombreCampo]);
         }
      }
      return true;
   }
}
