<?php

namespace Gymsys\Utils;

class LoginAttempts
{
   private const MAX_ATTEMPTS = 3;
   private const BLOCK_TIME = 300; // 5 minutos en segundos
   private const CACHE_DIR = __DIR__ . '/../../cache/login_attempts';

   private static function getIpAddress(): string
   {
      return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
   }

   private static function getCacheFile(): string
   {
      return self::CACHE_DIR . '/login_attempts_' . md5(self::getIpAddress()) . '.txt';
   }

   public static function checkAttempts(): bool
   {
      $file = self::getCacheFile();
      if (!file_exists($file)) {
         return true;
      }

      $data = json_decode(file_get_contents($file), true);
      if (!$data) {
         return true;
      }

      // Si el tiempo de bloqueo ha pasado, eliminar el archivo
      if (time() - $data['timestamp'] >= self::BLOCK_TIME) {
         self::clearAttempts();
         return true;
      }

      // Si hay demasiados intentos y no ha pasado el tiempo de bloqueo
      if ($data['attempts'] >= self::MAX_ATTEMPTS) {
         $timeLeft = self::BLOCK_TIME - (time() - $data['timestamp']);
         ExceptionHandler::throwException(
            "Demasiados intentos fallidos. Por favor, espere " . ceil($timeLeft / 60) . " minutos antes de intentar de nuevo.",
            \RuntimeException::class,
            429
         );
      }

      return true;
   }

   private static function verificarDirectorioCache(): void
   {
      if (!file_exists(self::CACHE_DIR)) {
         mkdir(self::CACHE_DIR, 0755, true);
      }
   }

   public static function recordFailedAttempt(): void
   {
      $file = self::getCacheFile();
      $data = ['attempts' => 1, 'timestamp' => time()];
      
      self::verificarDirectorioCache();

      if (file_exists($file)) {
         $currentData = json_decode(file_get_contents($file), true);
         if ($currentData) {
            // Si el tiempo de bloqueo ha pasado, reiniciar los intentos
            if (time() - $currentData['timestamp'] >= self::BLOCK_TIME) {
               $data['attempts'] = 1;
            } else {
               $data['attempts'] = $currentData['attempts'] + 1;
            }
         }
      }

      file_put_contents($file, json_encode($data));
   }

   public static function clearAttempts(): void
   {
      $file = self::getCacheFile();
      if (file_exists($file)) {
         unlink($file);
      }
   }

   public static function limpiarCacheIntentos(): int
   {
      if (!file_exists(self::CACHE_DIR)) {
         return 0;
      }
      
      $files = glob(self::CACHE_DIR . '/login_attempts_*.txt');
      if ($files === false) {
         return 0;
      }
      
      $now = time();
      $archivosLimpiados = 0;
      foreach ($files as $file) {
         if (filemtime($file) < ($now - self::BLOCK_TIME)) {
            unlink($file);
            $archivosLimpiados++;
         }
      }
      return $archivosLimpiados;
   }
}
