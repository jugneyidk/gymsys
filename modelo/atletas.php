<?php
class Atleta extends datos
{
    private $conexion;
    private $id_atleta, $nombres, $apellidos, $cedula, $genero, $fecha_nacimiento, $lugar_nacimiento, $peso, $altura, $tipo_atleta, $estado_civil, $telefono, $correo_electronico, $entrenador_asignado, $cedula_representante, $nombre_representante, $telefono_representante, $parentesco_representante, $password;

    public function __construct()
    {
        $this->conexion = $this->conecta();
    }

    public function incluir_atleta($datos)
    {
        $validacion = Validar::validar_datos($datos);
        if (is_array($validacion)) {
            $respuesta["ok"] = false;
            $respuesta["mensaje"] = $validacion;
            return $respuesta;
        }
        foreach ($datos as $campo => $valor) {
            if (property_exists($this, $campo)) {
                $this->$campo = $valor;
            }
        }
        return $this->incluir();
    }

    public function obtener_atleta($cedula)
    {
        $validacion = Validar::validar("cedula", $cedula);
        if (!$validacion["ok"]) {
            return $validacion;
        }
        $this->cedula = $cedula;
        return $this->obtener();
    }

    public function modificar_atleta($datos)
    {
        $validacion = Validar::validar_datos($datos);
        if (is_array($validacion)) {
            $respuesta["ok"] = false;
            $respuesta["mensaje"] = $validacion;
            return $respuesta;
        }
        foreach ($datos as $campo => $valor) {
            if (property_exists($this, $campo)) {
                $this->$campo = $valor;
            }
        }
        return $this->modificar();
    }

    public function eliminar_atleta($cedula)
    {
        $validacion = Validar::validar("cedula", $cedula);
        if (!$validacion["ok"]) {
            return $validacion;
        }
        $this->cedula = $cedula;
        return $this->eliminar();
    }
    public function listado_atleta()
    {
        return $this->listado();
    }
    private function incluir()
    {
        try {
            $consulta = "SELECT cedula FROM atleta WHERE cedula = ?;";
            $existe = Validar::existe($this->conexion, $this->cedula, $consulta);
            if ($existe["ok"]) {
                $resultado["ok"] = false;
                $resultado["mensaje"] = "Ya existe un atleta con esta cedula";
                return $resultado;
            }
            if (!empty($this->cedula_representante)) {
                $resultadoRepresentante = $this->incluirRepresentante($this->cedula_representante, $this->nombre_representante, $this->telefono_representante, $this->parentesco_representante);
                if (!$resultadoRepresentante['ok']) {
                    return ['ok' => false, 'mensaje' => $resultadoRepresentante['mensaje']];
                }
            }
            $this->conexion->beginTransaction();
            $id_rol = 0;
            $token = 0;
            $consulta = "
                INSERT INTO usuarios (cedula, nombre, apellido, genero, fecha_nacimiento, lugar_nacimiento, estado_civil, telefono, correo_electronico)
                VALUES (:cedula, :nombre, :apellido, :genero, :fecha_nacimiento, :lugar_nacimiento, :estado_civil, :telefono, :correo_electronico);
    
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
                ':correo_electronico' => $this->correo_electronico,
                ':id_entrenador' => $this->entrenador_asignado,
                ':tipo_atleta' => $this->tipo_atleta,
                ':peso' => $this->peso,
                ':altura' => $this->altura,
                ':id_rol' => $id_rol,
                ':password' => password_hash($this->password, PASSWORD_DEFAULT),
                ':token' => $token,
                ':representante' => $this->cedula_representante
            );
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $respuesta->closeCursor();
            $this->conexion->commit();
            $resultado["ok"] = true;
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        $this->desconecta();
        return $resultado;
    }

    private function listado()
    {
        try {
            $consulta = "SELECT * FROM lista_atletas";
            $con = $this->conexion->prepare($consulta);
            $con->execute();
            $respuesta = $con->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["respuesta"] = $respuesta;
        } catch (PDOException $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        $this->desconecta();
        return $resultado;
    }

    private function obtener()
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
            $valores = array(':cedula' => $this->cedula);
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $atleta = $respuesta->fetch(PDO::FETCH_ASSOC);
            if ($atleta) {
                $resultado["ok"] = true;
                $resultado["atleta"] = $atleta;
            } else {
                $resultado["ok"] = false;
                $resultado["mensaje"] = "No se encontró el atleta";
            }
        } catch (PDOException $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        $this->desconecta();
        return $resultado;
    }

    private function modificar()
    {
        try {
            $consulta = "SELECT cedula FROM atleta WHERE cedula = ?;";
            $existe = Validar::existe($this->conexion, $this->cedula, $consulta);
            if (!$existe["ok"]) {
                $resultado["ok"] = false;
                $resultado["mensaje"] = "No existe ningun atleta con esta cedula";
                return $resultado;
            }
            if (!empty($this->cedula_representante)) {
                $resultadoRepresentante = $this->incluirRepresentante($this->cedula_representante, $this->nombre_representante, $this->telefono_representante, $this->parentesco_representante);
                if (!$resultadoRepresentante['ok']) {
                    return ['ok' => false, 'mensaje' => $resultadoRepresentante['mensaje']];
                }
            }
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
                    correo_electronico = :correo_electronico 
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
                ':correo_electronico' => $this->correo_electronico,
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
            $resultado["ok"] = true;
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        $this->desconecta();
        return $resultado;
    }

    private function eliminar()
    {
        try {
            $consulta = "SELECT cedula FROM atleta WHERE cedula = ?;";
            $existe = Validar::existe($this->conexion, $this->cedula, $consulta);
            if (!$existe["ok"]) {
                $resultado["ok"] = false;
                $resultado["mensaje"] = "No existe ningún atleta con esta cedula";
                return $resultado;
            }
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
            $resultado["ok"] = true;
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        $this->desconecta();
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
            $resultado["ok"] = true;
            $resultado["entrenadores"] = $entrenadores;
        } catch (PDOException $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        $this->desconecta();
        return $resultado;
    }

    public function obtenerTiposAtleta()
    {
        try {
            $consulta = "SELECT id_tipo_atleta, nombre_tipo_atleta FROM tipo_atleta";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute();
            $tiposAtleta = $respuesta->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["tipos"] = $tiposAtleta;
        } catch (PDOException $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        $this->desconecta();
        return $resultado;
    }

    public function incluirRepresentante($cedula, $nombreCompleto, $telefono, $parentesco)
    {
        try {
            $this->conexion->beginTransaction();
            $consulta = "INSERT INTO representantes (cedula, nombre_completo, telefono, parentesco) VALUES (:cedula, :nombreCompleto, :telefono, :parentesco)";
            // $consulta = "INSERT INTO representantes (cedula, nombre_completo, telefono, parentesco) 
            //     VALUES (:cedula, :nombreCompleto, :telefono, :parentesco)
            //     ON DUPLICATE KEY UPDATE 
            //     nombre_completo = VALUES(nombre_completo), 
            //     telefono = VALUES(telefono), 
            //     parentesco = VALUES(parentesco);";
            $valores = array(
                ':cedula' => $cedula,
                ':nombreCompleto' => $nombreCompleto,
                ':telefono' => $telefono,
                ':parentesco' => $parentesco
            );
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $respuesta->closeCursor();
            $this->conexion->commit();
            $resultado["ok"] = true;
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        $this->desconecta();
        return $resultado;
    }
    public function registrarTipoAtleta($nombreTipoAtleta, $tipoCobro)
    {
        try {
            $this->conexion->beginTransaction();
            $consulta = "INSERT INTO tipo_atleta (nombre_tipo_atleta, tipo_cobro) VALUES (?, ?)";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute([$nombreTipoAtleta, $tipoCobro]);
            $respuesta->closeCursor();
            $this->conexion->commit();
            $resultado["ok"] = true;
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        $this->desconecta();
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
