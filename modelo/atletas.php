<?php
require_once('modelo/datos.php');
require_once('modelo/bitacora.php');
class Atleta extends datos
{
    private $conexion;
    private $id_atleta, $nombres, $apellidos, $cedula, $genero, $fecha_nacimiento, $lugar_nacimiento, $peso, $altura, $tipo_atleta, $estado_civil, $telefono, $correo, $entrenador_asignado, $password;

    public function __construct()
    {
        $this->conexion = $this->conecta();
    }

    public function incluir_atleta($nombres, $apellidos, $cedula, $genero, $fecha_nacimiento, $lugar_nacimiento, $peso, $altura, $tipo_atleta, $estado_civil, $telefono, $correo, $entrenador_asignado, $password, $cedula_representante = null, $nombre_representante = null, $telefono_representante = null, $parentesco_representante = null)
    {
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->cedula = $cedula;
        $this->genero = $genero;
        $this->fecha_nacimiento = $fecha_nacimiento;
        $this->lugar_nacimiento = $lugar_nacimiento;
        $this->peso = $peso;
        $this->altura = $altura;
        $this->tipo_atleta = $tipo_atleta;
        $this->estado_civil = $estado_civil;
        $this->telefono = $telefono;
        $this->correo = $correo;
        $this->entrenador_asignado = $entrenador_asignado;
        $this->password = password_hash($password, PASSWORD_DEFAULT);

        // Si hay un representante, incluirlo en la base de datos y asociarlo al atleta
        if (!empty($cedula_representante)) {
            $resultadoRepresentante = $this->incluirRepresentante($cedula_representante, $nombre_representante, $telefono_representante, $parentesco_representante);
            if (!$resultadoRepresentante['ok']) {
                return ['ok' => false, 'mensaje' => $resultadoRepresentante['mensaje']];
            }
        }


        return $this->incluir($cedula_representante); // Pasar la cédula del representante para asociarla al atleta
    }

    private function incluir($cedula_representante = null)
    {
        try {
            $this->conexion->beginTransaction();
            $id_rol = 0;
            $token = 0;
            $consulta = "
                INSERT INTO usuarios (cedula, nombre, apellido, genero, fecha_nacimiento, lugar_nacimiento, estado_civil, telefono, correo_electronico)
                VALUES (:cedula, :nombre, :apellido, :genero, :fecha_nacimiento, :lugar_nacimiento, :estado_civil, :telefono, :correo);
    
                INSERT INTO atleta (cedula, entrenador, tipo_atleta, peso, altura, representante)
                VALUES (:cedula, :id_entrenador, :tipo_atleta, :peso, :altura, :representante);
    
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
                ':correo' => $this->correo,
                ':id_entrenador' => $this->entrenador_asignado,
                ':tipo_atleta' => $this->tipo_atleta,
                ':peso' => $this->peso,
                ':altura' => $this->altura,
                ':id_rol' => $id_rol,
                ':password' => $this->password,
                ':token' => $token,
                ':representante' => $cedula_representante // Asociar el representante si existe
            );
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $respuesta->closeCursor();
            $this->conexion->commit();
            $this->desconecta();
            $resultado["ok"] = true;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }


    public function listado_atleta()
    {
        return $this->listado();
    }



    private function listado()
    {
        try {
            $this->conexion->beginTransaction();
            $consulta = "SELECT * FROM lista_atletas";
            $con = $this->conexion->prepare($consulta);
            $con->execute();
            $respuesta = $con->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["devol"] = 'listado_atletas';
            $resultado["respuesta"] = $respuesta;
            $con->closeCursor();
            $this->conexion->commit();
            $this->desconecta();
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    public function obtener_atleta($cedula)
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
                    a.tipo_atleta AS id_tipo_atleta, 
                    a.peso, 
                    a.altura, 
                    a.entrenador
                FROM atleta a
                INNER JOIN usuarios u ON a.cedula = u.cedula
                WHERE u.cedula = :cedula
            ";
            $valores = array(':cedula' => $cedula);
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $atleta = $respuesta->fetch(PDO::FETCH_ASSOC);
            $this->desconecta();
            if ($atleta) {
                $resultado["ok"] = true;
                $resultado["atleta"] = $atleta;
            } else {
                $resultado["ok"] = false;
                $resultado["mensaje"] = "No se encontró el atleta";
            }
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }


    public function modificar_atleta($nombres, $apellidos, $cedula, $genero, $fecha_nacimiento, $lugar_nacimiento, $peso, $altura, $tipo_atleta, $estado_civil, $telefono, $correo, $entrenador_asignado, $modificar_contraseña, $password)
    {
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->cedula = $cedula;
        $this->genero = $genero;
        $this->fecha_nacimiento = $fecha_nacimiento;
        $this->lugar_nacimiento = $lugar_nacimiento;
        $this->peso = $peso;
        $this->altura = $altura;
        $this->tipo_atleta = $tipo_atleta;
        $this->estado_civil = $estado_civil;
        $this->telefono = $telefono;
        $this->correo = $correo;
        $this->entrenador_asignado = $entrenador_asignado;

        if ($modificar_contraseña) {
            $this->password = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $this->password = null;
        }

        return $this->modificar();
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
                UPDATE atleta 
                SET 
                    entrenador = :id_entrenador, 
                    tipo_atleta = :tipo_atleta, 
                    peso = :peso, 
                    altura = :altura 
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
                ':correo' => $this->correo,
                ':id_entrenador' => $this->entrenador_asignado,
                ':tipo_atleta' => $this->tipo_atleta,
                ':peso' => $this->peso,
                ':altura' => $this->altura
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
            $this->desconecta();
            $resultado["ok"] = true;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    public function eliminar_atleta($cedula)
    {
        $this->cedula = $cedula;
        return $this->eliminar();
    }
    private function eliminar()
    {
        try {
            $this->conexion->beginTransaction();
            $consulta = "
                DELETE FROM usuarios_roles WHERE id_usuario = :cedula;
                DELETE FROM atleta WHERE cedula = :cedula;
                DELETE FROM usuarios WHERE cedula = :cedula;
            ";
            $valores = array(':cedula' => $this->cedula);
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $respuesta->closeCursor();
            $this->conexion->commit();
            $this->desconecta();
            $resultado["ok"] = true;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }
    public function obtenerEntrenadores()
    {
        try {
            $consulta = "SELECT e.cedula, CONCAT(u.nombre, ' ', u.apellido) AS nombre_completo
                     FROM entrenador e
                     INNER JOIN usuarios u ON e.cedula = u.cedula";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute();
            $entrenadores = $respuesta->fetchAll(PDO::FETCH_ASSOC);
            return ['ok' => true, 'entrenadores' => $entrenadores];
        } catch (Exception $e) {
            return ['ok' => false, 'mensaje' => $e->getMessage()];
        }
    }

    public function obtenerTiposAtleta()
    {
        try {
            $consulta = "SELECT id_tipo_atleta, nombre_tipo_atleta FROM tipo_atleta";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute();
            $tiposAtleta = $respuesta->fetchAll(PDO::FETCH_ASSOC);
            return ['ok' => true, 'tipos' => $tiposAtleta];
        } catch (Exception $e) {
            return ['ok' => false, 'mensaje' => $e->getMessage()];
        }
    }

    public function incluirRepresentante($cedula, $nombreCompleto, $telefono, $parentesco)
    {
        try {
            $consulta = "INSERT INTO representantes (cedula, nombre_completo, telefono, parentesco) VALUES (:cedula, :nombreCompleto, :telefono, :parentesco)";
            $valores = array(
                ':cedula' => $cedula,
                ':nombreCompleto' => $nombreCompleto,
                ':telefono' => $telefono,
                ':parentesco' => $parentesco
            );
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            return ['ok' => true];
        } catch (Exception $e) {
            return ['ok' => false, 'mensaje' => $e->getMessage()];
        }
    }
    public function registrarTipoAtleta($nombreTipoAtleta, $tipoCobro)
    {
        try { 
            $consulta = "INSERT INTO tipo_atleta (nombre_tipo_atleta, tipo_cobro) VALUES (?, ?)";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute([$nombreTipoAtleta, $tipoCobro]); 
            return ['ok' => true];
        } catch (Exception $e) {
            return ['ok' => false, 'mensaje' => $e->getMessage()];
        }
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
