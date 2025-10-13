<?php

namespace Gymsys\Utils;

use Exception;

class ExceptionHandler extends Exception
{
   private function __construct() {}
   /**
    * The function `throwException` throws an exception with a specified message, HTTP code, exception
    * type, and exception code.
    * 
    * @param string message The `message` parameter is a string that represents the error message to be
    * included in the exception. It provides information about what went wrong during the execution of
    * the code.
    * @param int httpCode The `httpCode` parameter in the `throwException` function is used to specify
    * the HTTP status code that will be returned when the exception is thrown. By default, it is set to
    * 400, which corresponds to the "Bad Request" status code. You can override this default value by
    * providing
    * @param string exceptionType The `exceptionType` parameter in the `throwException` function is
    * expected to be a string representing the name of the exception class that you want to throw. This
    * function checks if the specified exception class exists and then throws an instance of that
    * exception class with the provided message, HTTP code, and exception
    * @param int exceptionCode The `exceptionCode` parameter in the `throwException` function is an
    * optional integer parameter that represents the error code associated with the exception being
    * thrown. If provided, it will be used as the error code for the exception. If not provided, the
    * default value of 0 will be used.
    */
   public static function throwException(string $message, string $exceptionType, int $httpCode = 400, int $exceptionCode = 0)
   {
      if (class_exists($exceptionType)) {
         throw new $exceptionType(json_encode(["error" => $message, "code" => $httpCode]), $exceptionCode);
      }
      throw new Exception(json_encode(["error" => $message, "code" => $httpCode]), $exceptionCode);
   }
   public static function parseTypeErrorMessage(string $message): string
   {
      if (preg_match('/Argument #\d+ \(\$(\w+)\) must be of type (\w+), (.+?) given/', $message, $matches)) {
         $argName = $matches[1];  // Ej: "userId"
         $expectedType = $matches[2]; // Ej: "string"
         $givenType = $matches[3];   // Ej: "null"
         return sprintf("El parámetro '%s' debe ser de tipo %s (se recibió %s)", $argName, $expectedType, $givenType);
      }
      return "Tipo de parámetro inválido";
   }
}
