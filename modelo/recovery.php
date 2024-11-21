<?php
class Recuperacion extends datos
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = $this->conecta();
    }
 
    public function generar_recuperacion($email)
    {
        try { 
            $consulta = "SELECT id_usuario FROM usuarios WHERE email = :email";
            $valores = array(':email' => $email);
            $resultado = $this->conexion->prepare($consulta);
            $resultado->execute($valores);

            if ($resultado->rowCount() > 0) { 
                $token = bin2hex(random_bytes(16));
                $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));
 
                $insertar = $this->conexion->prepare(
                    "INSERT INTO password_resets (email, token, expira) VALUES (:email, :token, :expira)"
                );
                $insertar->execute([':email' => $email, ':token' => $token, ':expira' => $expira]);
 
                if ($this->enviar_correo_recuperacion($email, $token)) {
                    return ["ok" => true, "mensaje" => "Se ha enviado un enlace de recuperación a tu correo"];
                } else {
                    return ["ok" => false, "mensaje" => "Error al enviar el correo. Intenta nuevamente"];
                }
            } else {
                return ["ok" => false, "mensaje" => "El correo no está registrado"];
            }
        } catch (PDOException $e) {
            return ["ok" => false, "mensaje" => $e->getMessage()];
        }
    }
 
    public function verificar_token($token)
    {
        try {
            $consulta = "SELECT email, expira FROM password_resets WHERE token = :token";
            $valores = array(':token' => $token);
            $resultado = $this->conexion->prepare($consulta);
            $resultado->execute($valores);
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
 
    public function restablecer_contraseña($email, $nueva_contraseña)
    {
        try { 
            $hash_password = password_hash($nueva_contraseña, PASSWORD_BCRYPT);
            $consulta = "UPDATE usuarios SET password = :password WHERE email = :email";
            $valores = array(':password' => $hash_password, ':email' => $email);
            $resultado = $this->conexion->prepare($consulta);
            $resultado->execute($valores);
 
            $this->conexion->prepare("DELETE FROM password_resets WHERE email = :email")
                ->execute([':email' => $email]);

            return ["ok" => true, "mensaje" => "Contraseña restablecida con éxito"];
        } catch (PDOException $e) {
            return ["ok" => false, "mensaje" => $e->getMessage()];
        }
    }
 
    private function enviar_correo_recuperacion($email, $token)
    {
        $enlace = "http://tusitio.com/restablecer.php?token=" . $token;
        $asunto = "Recuperación de contraseña";
        $mensaje = "Haz clic en el siguiente enlace para restablecer tu contraseña: " . $enlace;
 
        return mail($email, $asunto, $mensaje, "From: no-reply@tusitio.com");
    }
}
