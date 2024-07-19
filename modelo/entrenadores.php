<?php
require_once('modelo/datos.php');

class Entrenador extends datos
{
    private $conexion;
    private $id_entrenador, $nombres, $apellidos, $cedula, $genero, $fecha_nacimiento, $lugar_nacimiento, $estado_civil, $telefono, $correo_electronico, $grado_instruccion, $password;

    public function __construct()
    {
        $this->conexion = $this->conecta(); 
    }

    public function incluir_entrenador($nombres, $apellidos, $cedula, $genero, $fecha_nacimiento, $lugar_nacimiento, $estado_civil, $telefono, $correo_electronico, $grado_instruccion, $password)
    {
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->cedula = $cedula;
        $this->genero = $genero;
        $this->fecha_nacimiento = $fecha_nacimiento;
        $this->lugar_nacimiento = $lugar_nacimiento;
        $this->estado_civil = $estado_civil;
        $this->telefono = $telefono;
        $this->correo_electronico = $correo_electronico;
        $this->grado_instruccion = $grado_instruccion;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this->incluir();
    }

    public function modificar_entrenador($nombres, $apellidos, $cedula, $genero, $fecha_nacimiento, $lugar_nacimiento, $estado_civil, $telefono, $correo_electronico, $grado_instruccion, $password)
    {
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->cedula = $cedula;
        $this->genero = $genero;
        $this->fecha_nacimiento = $fecha_nacimiento;
        $this->lugar_nacimiento = $lugar_nacimiento;
        $this->estado_civil = $estado_civil;
        $this->telefono = $telefono;
        $this->correo_electronico = $correo_electronico;
        $this->grado_instruccion = $grado_instruccion;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this->modificar();
    }

    public function obtener_entrenador($cedula)
    {
        try {
            $consulta = "
                SELECT 
                    u.cedula, 
                    u.nombre, 
                    u.apellido, 
                    u.genero, 
                    u.fecha_nacimiento, 
                    u.lugar_nacimiento, 
                    u.estado_civil, 
                    u.telefono, 
                    u.correo_electronico, 
                    e.grado_instruccion
                FROM entrenador e
                INNER JOIN usuarios u ON e.cedula = u.cedula
                WHERE u.cedula = :cedula
            ";
            $valores = array(':cedula' => $cedula);

            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $entrenador = $respuesta->fetch(PDO::FETCH_ASSOC);
            
            if ($entrenador) {
                $resultado["ok"] = true;
                $resultado["entrenador"] = $entrenador;
            } else {
                $resultado["ok"] = false;
                $resultado["mensaje"] = "No se encontró el entrenador";
            }
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    public function listado_entrenador()
    {
        return $this->listado();
    }

    public function eliminar_entrenador($cedula)
    {
        try {
            $consulta = "
                DELETE FROM entrenador WHERE cedula = :cedula;
                DELETE FROM usuarios WHERE cedula = :cedula;
                DELETE FROM usuarios_roles WHERE id_usuario = :cedula;
            ";
            $valores = array(':cedula' => $cedula);

            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $respuesta->closeCursor();

            $resultado["ok"] = true;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    private function incluir() 
    {
        try {
            $this->conexion->beginTransaction();

            $id_rol = 1;  
            $token = 0;

            $consulta = "
                INSERT INTO usuarios (cedula, nombre, apellido, genero, fecha_nacimiento, lugar_nacimiento, estado_civil, telefono, correo_electronico)
                VALUES (:cedula, :nombre, :apellido, :genero, :fecha_nacimiento, :lugar_nacimiento, :estado_civil, :telefono, :correo);
                
                INSERT INTO entrenador (cedula, grado_instruccion)
                VALUES (:cedula, :grado_instruccion);

                INSERT INTO usuarios_roles (id_usuario, id_rol, password, token)
                VALUES (:cedula, :id_rol, :password, :token);
            ";

            $valores = array(
                ':cedula' => $this->cedula,
                ':nombre' => $this->nombres,
                ':apellido' => $this->apellidos,
                ':genero' => $this->genero,
                ':fecha_nacimiento' => $this->fecha_nacimiento,
                ':lugar_nacimiento' => $this->lugar_nacimiento,
                ':estado_civil' => $this->estado_civil,
                ':telefono' => $this->telefono,
                ':correo' => $this->correo_electronico,
                ':grado_instruccion' => $this->grado_instruccion,
                ':id_rol' => $id_rol,
                ':password' => $this->password,
                ':token' => $token
            );

            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $respuesta->closeCursor(); 

            $this->conexion->commit();
            $resultado["ok"] = true;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    private function modificar()
    {
        try {
            $this->conexion->beginTransaction();

            $consulta = "
                UPDATE usuarios 
                SET 
                    nombre = :nombre, 
                    apellido = :apellido, 
                    genero = :genero, 
                    fecha_nacimiento = :fecha_nacimiento, 
                    lugar_nacimiento = :lugar_nacimiento, 
                    estado_civil = :estado_civil, 
                    telefono = :telefono, 
                    correo_electronico = :correo 
                WHERE cedula = :cedula;
        
                UPDATE entrenador 
                SET 
                    grado_instruccion = :grado_instruccion
                WHERE cedula = :cedula;
            ";

            $valores = array(
                ':cedula' => $this->cedula,
                ':nombre' => $this->nombres,
                ':apellido' => $this->apellidos,
                ':genero' => $this->genero,
                ':fecha_nacimiento' => $this->fecha_nacimiento,
                ':lugar_nacimiento' => $this->lugar_nacimiento,
                ':estado_civil' => $this->estado_civil,
                ':telefono' => $this->telefono,
                ':correo' => $this->correo_electronico,
                ':grado_instruccion' => $this->grado_instruccion
            );

            $respuesta1 = $this->conexion->prepare($consulta);
            $respuesta1->execute($valores);
            $respuesta1->closeCursor();

            if ($this->password !== null) {
                $consulta_password = "
                    UPDATE usuarios_roles
                    SET password = :password
                    WHERE id_usuario = :cedula;
                ";
                $valores_password = array(
                    ':cedula' => $this->cedula,
                    ':password' => $this->password
                );

                $respuesta2 = $this->conexion->prepare($consulta_password);
                $respuesta2->execute($valores_password);
                $respuesta2->closeCursor();
            }

            $this->conexion->commit();
            $resultado["ok"] = true;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    private function listado()
    {
        try {
            $consulta = "
                SELECT 
                    u.cedula, 
                    u.nombre, 
                    u.apellido, 
                    u.genero, 
                    u.fecha_nacimiento, 
                    u.lugar_nacimiento, 
                    u.estado_civil, 
                    u.telefono, 
                                       u.correo_electronico, 
                    e.grado_instruccion 
                FROM entrenador e
                INNER JOIN usuarios u ON e.cedula = u.cedula
                ORDER BY u.cedula DESC
            ";
            $con = $this->conexion->prepare($consulta);
            $con->execute();
            $respuesta = $con->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["devol"] = 'listado_entrenadores';
            $resultado["respuesta"] = $respuesta;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    public function __get($propiedad)
    {
        return $this->$propiedad;
    }

    public function __set($propiedad, $valor) 
    {
        $this->$propiedad = $valor;
        return $this;
    }
}
?>

