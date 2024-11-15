<?php
class Login extends datos
{
    private $conexion, $id_usuario, $password;
    public function __construct()
    {
        $this->conexion = $this->conecta();
    }
    public function iniciar_sesion($id_usuario, $password)
    {
        $validacion_usuario = Validar::validar("cedula", $id_usuario);
        if (!$validacion_usuario["ok"]) {
            return $validacion_usuario;
        }
        $validacion_password = Validar::validar("password", $password);
        if (!$validacion_password["ok"]) {
            return $validacion_password;
        }
        $this->id_usuario = $id_usuario;
        $this->password = $password;
        return $this->login();
    }
    private function login()
    {
        if (!empty($this->id_usuario) && !empty($this->password)) {
            try {
                $consulta = "SELECT id_rol, `password` FROM usuarios_roles WHERE id_usuario = :id_usuario";
                $valores = array(':id_usuario' => $this->id_usuario);
                $resultado = $this->conexion->prepare($consulta);
                $resultado->execute($valores);
                $resultado = $resultado->fetch(PDO::FETCH_ASSOC);
                if ($resultado && password_verify($this->password, $resultado['password'])) {
                    session_start();
                    $_SESSION['rol'] = $resultado['id_rol'];
                    $_SESSION['id_usuario'] = $this->id_usuario;
                    $respuesta["ok"] = true;
                } else {
                    $respuesta['ok'] = false;
                    $respuesta['mensaje'] = "Los datos ingresados son incorrectos";
                }
                return $respuesta;
            } catch (PDOException $e) {
                $respuesta['resultado'] = false;
                $respuesta['mensaje'] = $e->getMessage();
                $this->desconecta();
                return $respuesta;
            }
        }
    }
}