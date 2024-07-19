<?php
require_once ('modelo/datos.php');

class Roles extends datos
{
    private $conexion;
    private $id_rol, $nombre, $centrenadores, $rentrenadores, $uentrenadores, $dentrenadores, $catletas, $ratletas, $uatletas, $datletas, $crolespermisos, $rrolespermisos, $urolespermisos, $drolespermisos, $casistencias, $rasistencias, $uasistencias, $dasistencias, $ceventos, $reventos, $ueventos, $deventos, $cmensualidad, $rmensualidad, $umensualidad, $dmensualidad, $cwada, $rwada, $uwada, $dwada, $creportes, $rreportes, $ureportes, $dreportes;

    public function __construct()
    {
        $this->conexion = $this->conecta();
    }

    public function incluir_rol($nombre, $valores)
    {
        $this->nombre = $nombre;
        $this->centrenadores = $valores['centrenadores'];
        $this->rentrenadores = $valores['rentrenadores'];
        $this->uentrenadores = $valores['uentrenadores'];
        $this->dentrenadores = $valores['dentrenadores'];
        $this->catletas = $valores['catletas'];
        $this->ratletas = $valores['ratletas'];
        $this->uatletas = $valores['uatletas'];
        $this->datletas = $valores['datletas'];
        $this->crolespermisos = $valores['crolespermisos'];
        $this->rrolespermisos = $valores['rrolespermisos'];
        $this->urolespermisos = $valores['urolespermisos'];
        $this->drolespermisos = $valores['drolespermisos'];
        $this->casistencias = $valores['casistencias'];
        $this->rasistencias = $valores['rasistencias'];
        $this->uasistencias = $valores['uasistencias'];
        $this->dasistencias = $valores['dasistencias'];
        $this->ceventos = $valores['ceventos'];
        $this->reventos = $valores['reventos'];
        $this->ueventos = $valores['ueventos'];
        $this->deventos = $valores['deventos'];
        $this->cmensualidad = $valores['cmensualidad'];
        $this->rmensualidad = $valores['rmensualidad'];
        $this->umensualidad = $valores['umensualidad'];
        $this->dmensualidad = $valores['dmensualidad'];
        $this->cwada = $valores['cwada'];
        $this->rwada = $valores['rwada'];
        $this->uwada = $valores['uwada'];
        $this->dwada = $valores['dwada'];
        $this->creportes = $valores['creportes'];
        $this->rreportes = $valores['rreportes'];
        $this->ureportes = $valores['ureportes'];
        $this->dreportes = $valores['dreportes'];
        return $this->incluir();
    }

    private function incluir()
    {
        try {
            $this->conexion->beginTransaction();
            $consulta = "
                INSERT INTO roles (nombre)
                VALUES (:nombre);
            ";
            $valores = array(
                ':nombre' => $this->nombre,
            );
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $id_rol = $this->conexion->lastInsertId();
            $consulta_permisos = "
            INSERT INTO permisos (id_rol,modulo,crear,leer,actualizar,eliminar)
            VALUES (:id_rol,:moduloentrenadores,:centrenadores,:rentrenadores,:uentrenadores,:dentrenadores);
            INSERT INTO permisos (id_rol,modulo,crear,leer,actualizar,eliminar)
            VALUES (:id_rol,:moduloatletas,:catletas,:ratletas,:uatletas,:datletas);
            INSERT INTO permisos (id_rol,modulo,crear,leer,actualizar,eliminar)
            VALUES (:id_rol,:modulorolespermisos,:crolespermisos,:rrolespermisos,:urolespermisos,:drolespermisos);
            INSERT INTO permisos (id_rol,modulo,crear,leer,actualizar,eliminar)
            VALUES (:id_rol,:moduloasistencias,:casistencias,:rasistencias,:uasistencias,:dasistencias);
            INSERT INTO permisos (id_rol,modulo,crear,leer,actualizar,eliminar)
            VALUES (:id_rol,:moduloeventos,:ceventos,:reventos,:ueventos,:deventos);
            INSERT INTO permisos (id_rol,modulo,crear,leer,actualizar,eliminar)
            VALUES (:id_rol,:modulomensualidad,:cmensualidad,:rmensualidad,:umensualidad,:dmensualidad);
            INSERT INTO permisos (id_rol,modulo,crear,leer,actualizar,eliminar)
            VALUES (:id_rol,:modulowada,:cwada,:rwada,:uwada,:dwada);
            INSERT INTO permisos (id_rol,modulo,crear,leer,actualizar,eliminar)
            VALUES (:id_rol,:moduloreportes,:creportes,:rreportes,:ureportes,:dreportes);
            ";
            $valores_permisos = array(
                ':id_rol' => $id_rol,
                ':moduloentrenadores' => 1,
                ':centrenadores' => $this->centrenadores,
                ':rentrenadores' => $this->rentrenadores,
                ':uentrenadores' => $this->uentrenadores,
                ':dentrenadores' => $this->dentrenadores,
                ':moduloatletas' => 2,
                ':catletas' => $this->catletas,
                ':ratletas' => $this->ratletas,
                ':uatletas' => $this->uatletas,
                ':datletas' => $this->datletas,
                ':modulorolespermisos' => 3,
                ':crolespermisos' => $this->crolespermisos,
                ':rrolespermisos' => $this->rrolespermisos,
                ':urolespermisos' => $this->urolespermisos,
                ':drolespermisos' => $this->drolespermisos,
                ':moduloasistencias' => 4,
                ':casistencias' => $this->casistencias,
                ':rasistencias' => $this->rasistencias,
                ':uasistencias' => $this->uasistencias,
                ':dasistencias' => $this->dasistencias,
                ':moduloeventos' => 5,
                ':ceventos' => $this->ceventos,
                ':reventos' => $this->reventos,
                ':ueventos' => $this->ueventos,
                ':deventos' => $this->deventos,
                ':modulomensualidad' => 6,
                ':cmensualidad' => $this->cmensualidad,
                ':rmensualidad' => $this->rmensualidad,
                ':umensualidad' => $this->umensualidad,
                ':dmensualidad' => $this->dmensualidad,
                ':modulowada' => 7,
                ':cwada' => $this->cwada,
                ':rwada' => $this->rwada,
                ':uwada' => $this->uwada,
                ':dwada' => $this->dwada,
                ':moduloreportes' => 8,
                ':creportes' => $this->creportes,
                ':rreportes' => $this->rreportes,
                ':ureportes' => $this->ureportes,
                ':dreportes' => $this->dreportes,
            );
            $respuesta = $this->conexion->prepare($consulta_permisos);
            $respuesta->execute($valores_permisos);
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
                    a.tipo_atleta, 
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
        try {
            $this->conexion->beginTransaction();


            $consulta = "
                DELETE FROM usuarios_roles WHERE id_usuario = :cedula;
                DELETE FROM atleta WHERE cedula = :cedula;
                DELETE FROM usuarios WHERE cedula = :cedula;
            ";

            $valores = array(':cedula' => $cedula);

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

    public function listado_roles()
    {
        try {
            $consulta = "
                SELECT *                
                FROM roles                
                ORDER BY id_rol DESC
            ";
            $con = $this->conexion->prepare($consulta);
            $con->execute();
            $respuesta = $con->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["devol"] = 'listado_roles';
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