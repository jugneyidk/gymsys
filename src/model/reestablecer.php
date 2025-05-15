<?php

require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use Dompdf\Dompdf;


require_once 'lib/PHPMailer/src/Exception.php';
require_once 'lib/PHPMailer/src/PHPMailer.php';
require_once 'lib/PHPMailer/src/SMTP.php';

class Recuperacion
{
   private $conexion;
   private $username = "jugney.ap@gmail.com"; // Cambia esto
   private $password = "lquumsbwyxsfgnfj"; // Cambia esto
   private $name = "Gimnasio Eddie Suarez";

   public function __construct()
   {
      $this->conexion = $this->conecta();
   }

   public function generar_recuperacion($email, $cedula)
   {
      try {
         $consulta = "SELECT cedula FROM usuarios WHERE correo_electronico = :email AND cedula = :cedula";
         $valores = [':email' => $email, ':cedula' => $cedula];
         $resultado = $this->conexion->prepare($consulta);
         $resultado->execute($valores);

         if ($resultado->rowCount() > 0) {
            $token = bin2hex(random_bytes(16));
            $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

            $insertar = $this->conexion->prepare(
               "INSERT INTO `reset` (email, cedula, token, expira) VALUES (:email, :cedula, :token, :expira)"
            );
            $insertar->execute([
               ':email' => $email,
               ':cedula' => $cedula,
               ':token' => $token,
               ':expira' => $expira
            ]);

            if ($this->enviar_correo_recuperacion($email, $token)) {
               return ["ok" => true, "mensaje" => "Se ha enviado un enlace de recuperación a tu correo"];
            } else {
               return ["ok" => false, "mensaje" => "Error al enviar el correo. Intenta nuevamente"];
            }
         } else {
            return ["ok" => false, "mensaje" => "Los datos no coinciden"];
         }
      } catch (PDOException $e) {
         return ["ok" => false, "mensaje" => $e->getMessage()];
      }
   }
   private function enviar_correo_recuperacion($email, $token)
   {
      $enlace = "http://localhost/gymsys/?p=reestablecer&token=" . $token;
      $html = <<<END
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.5; }
                .container { margin: 20px; padding: 20px; border: 1px solid #ddd; border-radius: 10px; }
                .header { text-align: center; font-size: 20px; font-weight: bold; margin-bottom: 20px; }
                .footer { margin-top: 20px; font-size: 12px; text-align: center; color: #888; }
                .button { display: inline-block; padding: 10px 20px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">Recuperación de Contraseña</div>
                <p>Hola,</p>
                <p>Hemos recibido una solicitud para restablecer tu contraseña. Haz clic en el siguiente enlace para continuar:</p>
                <p><a href="$enlace" class="button">Restablecer Contraseña</a></p>
                <p>Este enlace es válido durante 1 hora.</p>
                <p>Si no solicitaste esta acción, ignora este mensaje.</p>
                <div class="footer">
                    Gimnasio Eddie Suarez | Todos los derechos reservados.
                </div>
            </div>
        </body>
        </html>
        END;

      $mail = new PHPMailer;
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

      if (!$mail->send()) {
         $mail->clearAllRecipients();
         $mail->clearAttachments();
         $mail->clearCustomHeaders();
         return false;
      } else {
         $mail->clearAllRecipients();
         $mail->clearAttachments();
         $mail->clearCustomHeaders();
         return true;
      }
   }

   public function verificar_token($token)
   {
      try {
         $consulta = "SELECT email, expira FROM `reset` WHERE token = :token";
         $resultado = $this->conexion->prepare($consulta);
         $resultado->execute([':token' => $token]);
         $datos = $resultado->fetch(PDO::FETCH_ASSOC);

         if ($datos) {
            $actual = date("Y-m-d H:i:s");
            if ($datos['expira'] > $actual) {
               return ["ok" => true, "email" => $datos['email']];
            } else {
               return ["ok" => false, "mensaje" => "El token ha expirado"];
            }
         } else {
            return ["ok" => false, "mensaje" => "Token inválido"];
         }
      } catch (PDOException $e) {
         return ["ok" => false, "mensaje" => $e->getMessage()];
      }
   }
   public function obtener_email_por_token($token)
   {
      try {
         $consulta = "SELECT email FROM `reset` WHERE token = :token";
         $resultado = $this->conexion->prepare($consulta);
         $resultado->execute([':token' => $token]);

         $email = $resultado->fetchColumn();

         if ($email) {
            return ["ok" => true, "email" => $email];
         } else {
            return ["ok" => false, "mensaje" => "Token inválido o no encontrado"];
         }
      } catch (PDOException $e) {
         return ["ok" => false, "mensaje" => $e->getMessage()];
      }
   }

   public function restablecer_contrasena($email, $nueva_contrasena)
   {
      try {
         $hash_password = password_hash($nueva_contrasena, PASSWORD_BCRYPT);
         $consulta = "UPDATE usuarios_roles SET password = :password WHERE id_usuario = :email";
         $valores = [':password' => $hash_password, ':email' => $email];
         $resultado = $this->conexion->prepare($consulta);
         $resultado->execute($valores);

         $this->conexion->prepare("DELETE FROM `reset` WHERE email = :email")
            ->execute([':email' => $email]);

         return ["ok" => true, "mensaje" => "Contraseña restablecida con éxito"];
      } catch (PDOException $e) {
         return ["ok" => false, "mensaje" => $e->getMessage()];
      }
   }
}
