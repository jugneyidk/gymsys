<?php
require_once('modelo/datos.php');

class Eventos extends datos
{
    private $conexion, $nombre, $lugar_competencia, $fecha_inicio, $fecha_fin, $categoria, $subs, $tipo_competencia;

    public function __construct()
    {
        $this->conexion = $this->conecta();
    }

    public function incluir_evento($nombre, $lugar_competencia, $fecha_inicio, $fecha_fin, $categoria, $subs, $tipo_competencia)
    {
        $this->nombre = $nombre;
        $this->lugar_competencia = $lugar_competencia;
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
        $this->categoria = $categoria;
        $this->subs = $subs;
        $this->tipo_competencia = $tipo_competencia;
        return $this->incluir();
    }

    public function listado_eventos()
    {
        return $this->listado();
    }

    private function incluir()
    {
        try {
            $consulta = "INSERT INTO competencia(tipo_competicion, nombre, categoria, subs, lugar_competencia, fecha_inicio, fecha_fin) 
                         VALUES (:tipo_competencia, :nombre, :categoria, :subs, :lugar_competencia, :fecha_inicio, :fecha_fin)";
            $valores = array(
                ':nombre' => $this->nombre,
                ':lugar_competencia' => $this->lugar_competencia,
                ':fecha_inicio' => $this->fecha_inicio,
                ':fecha_fin' => $this->fecha_fin,
                ':categoria' => $this->categoria,
                ':subs' => $this->subs,
                ':tipo_competencia' => $this->tipo_competencia
            );
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $resultado["ok"] = true;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    private function listado()
    {
        try {
            $consulta = "SELECT * FROM competencia";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute();
            $respuesta = $respuesta->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["respuesta"] = $respuesta;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    public function incluir_categoria($nombre, $pesoMinimo, $pesoMaximo)
    {
        try {
            $consulta = "INSERT INTO categorias (nombre, peso_minimo, peso_maximo) VALUES (:nombre, :pesoMinimo, :pesoMaximo)";
            $valores = array(
                ':nombre' => $nombre,
                ':pesoMinimo' => $pesoMinimo,
                ':pesoMaximo' => $pesoMaximo
            );
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $resultado["ok"] = true;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    public function incluir_subs($nombre, $edadMinima, $edadMaxima)
    {
        try {
            $consulta = "INSERT INTO subs (nombre, edad_minima, edad_maxima) VALUES (:nombre, :edadMinima, :edadMaxima)";
            $valores = array(
                ':nombre' => $nombre,
                ':edadMinima' => $edadMinima,
                ':edadMaxima' => $edadMaxima
            );
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $resultado["ok"] = true;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    public function incluir_tipo($nombre)
    {
        try {
            $consulta = "INSERT INTO tipo_competencia (nombre) VALUES (:nombre)";
            $valores = array(':nombre' => $nombre);
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $resultado["ok"] = true;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    public function listado_categoria()
    {
        try {
            $consulta = "SELECT * FROM categorias";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute();
            $respuesta = $respuesta->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["respuesta"] = $respuesta;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    public function listado_subs()
    {
        try {
            $consulta = "SELECT * FROM subs";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute();
            $respuesta = $respuesta->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["respuesta"] = $respuesta;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    public function listado_tipo()
    {
        try {
            $consulta = "SELECT * FROM tipo_competencia";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute();
            $respuesta = $respuesta->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["respuesta"] = $respuesta;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    public function listado_atletas_disponibles($id_competencia)
    {
        try {
            $consulta = "
                SELECT a.cedula, u.nombre, u.apellido, u.fecha_nacimiento, a.peso, a.altura 
                FROM atleta a
                LEFT JOIN resultado_competencia rc ON a.cedula = rc.id_atleta AND rc.id_competencia = :id_competencia
                INNER JOIN usuarios u ON a.cedula = u.cedula
                WHERE rc.id_atleta IS NULL";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute([':id_competencia' => $id_competencia]);
            $respuesta = $respuesta->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["respuesta"] = $respuesta;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    public function inscribir_atletas($id_competencia, $atletas)
    {
        try {
            $this->conexion->beginTransaction();
            foreach ($atletas as $id_atleta) {
                $consulta = "INSERT INTO resultado_competencia (id_competencia, id_atleta) VALUES (:id_competencia, :id_atleta)";
                $valores = array(
                    ':id_competencia' => $id_competencia,
                    ':id_atleta' => $id_atleta
                );
                $respuesta = $this->conexion->prepare($consulta);
                $respuesta->execute($valores);
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

    public function listado_atletas_inscritos($id_competencia)
    {
        try {
            $consulta = "
                SELECT u.nombre, u.apellido, a.cedula, u.fecha_nacimiento, a.peso, a.altura 
                FROM resultado_competencia rc
                INNER JOIN atleta a ON rc.id_atleta = a.cedula
                INNER JOIN usuarios u ON a.cedula = u.cedula
                WHERE rc.id_competencia = :id_competencia";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute([':id_competencia' => $id_competencia]);
            $respuesta = $respuesta->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
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

