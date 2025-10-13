<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\Validar;
use PHPMailer\PHPMailer\PHPMailer;

class Recovery
{
   private Database $database;
   private string $username = "jugney.ap@gmail.com";
   private string $password = "lquumsbwyxsfgnfj";
   private string $name = "Gimnasio Eddie Suarez";

   public function __construct(Database $database)
   {
      $this->database = $database;
   }
   public function generarRecuperacion(array $datos): array
   {
      $keys = ['email', 'cedula'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::validar('correo', $arrayFiltrado['email']);
      Validar::validar('cedula', $arrayFiltrado['cedula']);
      return $this->_generarRecuperacion($arrayFiltrado['email'], $arrayFiltrado['cedula']);
   }
   private function _generarRecuperacion(string $email, string $cedula): array
   {
      $consulta = "SELECT cedula FROM {$_ENV['SECURE_DB']}.usuarios WHERE correo_electronico = :email AND cedula = :cedula";
      $valores = [':email' => $email, ':cedula' => $cedula];
      $usuario = $this->database->query($consulta, $valores, true);
      if (empty($usuario)) {
         ExceptionHandler::throwException("Los datos no coinciden", \InvalidArgumentException::class, 404);
      }
      $token = bin2hex(random_bytes(16));
      $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));
      $this->database->beginTransaction();
      $consulta = "INSERT INTO `reset` (email, cedula, token, expira) VALUES (:email, :cedula, :token, :expira)";
      $this->database->query($consulta, [
         ':email' => $email,
         ':cedula' => $cedula,
         ':token' => $token,
         ':expira' => $expira
      ]);
      $nombreCompleto = $usuario['nombre'] . ' ' . $usuario['apellido'];
      if (!$this->enviarCorreoRecuperacion($email, $token, $nombreCompleto)) {
         $this->database->rollBack();
         ExceptionHandler::throwException("Error al enviar el correo. Intenta nuevamente", \Exception::class, 500);
      }
      $this->database->commit();
      return ["mensaje" => "Se ha enviado un enlace de recuperación a tu correo"];
   }

   private function enviarCorreoRecuperacion(string $email, string $token, string $nombreCompleto): bool
   {
      if (empty($email) || empty($token)) {
         ExceptionHandler::throwException("No se han proporcionado los datos necesarios para enviar el correo", \InvalidArgumentException::class);
      }
      $tokenCodificado = Cipher::codificarBase64($token);
      $enlace = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
         . '://' . $_SERVER['HTTP_HOST']
         . preg_replace('#/public$#', '', dirname($_SERVER['SCRIPT_NAME']))
         . '/?p=restablecer&token=' . $tokenCodificado;
      $tiempo = time();
      $year = date("Y", $tiempo);
      $fecha = date("d-m-Y", $tiempo);
      $hora = date("H:i:s", $tiempo);
      $html = <<<END
            <!DOCTYPE html>
            <html lang="es">
            <head>
            <meta charset="UTF-8">
            <title>Recuperar contraseña</title>
            </head>
            <body
            bgcolor="#121212"
            style="margin:0; padding:0; background-color:#121212; color:#e0e0e0; font-family:'Roboto', sans-serif;"
            >
            <table
               width="100%"
               cellpadding="0"
               cellspacing="0"
               border="0"
               bgcolor="#121212"
               style="background-color:#121212; padding:40px;"
            >
               <tr>
                  <td align="center">
                  <table
                     width="600"
                     cellpadding="0"
                     cellspacing="0"
                     border="0"
                     style="max-width:600px; width:100%; background:#1e1e1e; border-radius:8px; overflow:hidden;"
                  >
                     <tr>
                        <td
                         style="
                           background:linear-gradient(90deg,#6a11cb,#2575fc) !important;
                           padding:25px !important;
                           text-align:center !important;
                           font-size:22px !important;
                           font-weight:bold !important;
                           color:#ffffff !important;
                           -webkit-text-fill-color:#ffffff !important;
                           mix-blend-mode:normal !important;
                        ">
                           Restablece tu contraseña
                        </td>
                     </tr>
                     <tr>
                        <td
                        style="
                           padding:30px;
                           font-size:15px;
                           line-height:1.6;
                           color:#e2e2e2;
                        ">
                        <p style="margin:0 0 1em;">
                           Hola <strong style="color:#ffffff;">$nombreCompleto</strong>,
                        </p>
                        <p style="margin:0 0 1em; color:#e2e2e2;">
                           Para restablecer tu contraseña, solo haz clic en el botón siguiente. El enlace expirará en 60 minutos.
                        </p>
                        <p style="text-align:center; margin:30px 0;">
                           <a
                              href="$enlace"
                              style="
                              display:inline-block;
                              padding:12px 28px;
                              background:#03dac6;
                              color:#000000 !important;
                              text-decoration:none !important;
                              border-radius:4px;
                              font-weight:bold;
                              "
                              target="_blank"
                           >
                              Restablecer ahora
                           </a>
                        </p>
                        <p style="margin:0;">
                           Si no reconoces esta solicitud, ignora este mensaje.
                        </p>
                        </td>
                     </tr>
                     <tr>
                        <td
                        style="
                           padding:20px;
                           text-align:center;
                           font-size:12px;
                           color:#777777;
                        ">
                        <span style="display:block; color:#777777;">
                           &copy; $year – $this->name – Mantén tu cuenta segura
                        </span>
                        <br>
                        <span style="display:block; margin-top:0; font-size:10px; color:#777777;">
                           Enviado el $fecha a las $hora
                        </span>
                        </td>
                     </tr>
                  </table>
                  </td>
               </tr>
            </table>
            </body>
            </html>
        END;
      $mail = new PHPMailer(true);
      try {
         $mail->isSMTP();
         $mail->SMTPDebug = 0;
         $mail->Host = 'smtp.gmail.com';
         $mail->Port = 587;
         $mail->SMTPSecure = 'tls';
         $mail->SMTPAuth = true;
         $mail->Username = $this->username;
         $mail->Password = $this->password;
         $mail->setFrom($this->username, $this->name);
         $mail->addAddress($email);
         $mail->CharSet = 'UTF-8';
         $mail->isHTML(true);
         $mail->Subject = 'Recuperación de Contraseña';
         $mail->Body = $html;
         $mail->AltBody = strip_tags("Haz clic en este enlace para restablecer tu contraseña: $enlace");
         $mail->send();
         $mail->clearAllRecipients();
         $mail->clearAttachments();
         $mail->clearCustomHeaders();
         return true;
      } catch (\Exception $e) {
         $mail->clearAllRecipients();
         $mail->clearAttachments();
         $mail->clearCustomHeaders();
         ExceptionHandler::throwException("Error al enviar el correo. Intenta nuevamente", \Exception::class, 500);
         return false;
      }
   }
}
