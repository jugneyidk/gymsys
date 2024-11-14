<?php

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
            $consulta = "INSERT INTO competencia(tipo_competicion, nombre, categoria, subs, lugar_competencia, fecha_inicio, fecha_fin, estado) 
                         VALUES (:tipo_competencia, :nombre, :categoria, :subs, :lugar_competencia, :fecha_inicio, :fecha_fin, 'activo')";
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
    public function cerrar_evento($id_competencia)
{
    try {
        $consulta = "UPDATE competencia SET estado = 'inactivo' WHERE id_competencia = :id_competencia";
        $stmt = $this->conexion->prepare($consulta);
        $stmt->execute([':id_competencia' => $id_competencia]);
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
            $consulta = "SELECT c.*, 
                    (SELECT COUNT(*) FROM resultado_competencia rc WHERE rc.id_competencia = c.id_competencia) AS participantes, 
                    (10 - (SELECT COUNT(*) FROM resultado_competencia rc WHERE rc.id_competencia = c.id_competencia)) AS cupos_disponibles
                    FROM competencia c
                    WHERE c.estado = 'activo'"; 
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


    public function incluir_categoria($nombre, $pesoMinimo, $pesoMaximo) {
        try {
            if (empty($nombre) || strlen($nombre) < 2) {
                throw new Exception("El nombre es inválido.");
            }
            if (!is_numeric($pesoMinimo) || !is_numeric($pesoMaximo) || $pesoMinimo < 0 || $pesoMaximo <= $pesoMinimo) {
                throw new Exception("El rango de peso es inválido.");
            }
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
    public function listado_eventos_anteriores()
    {
        try {
            $consulta = "SELECT * FROM competencia WHERE fecha_fin < CURDATE() ORDER BY fecha_fin DESC LIMIT 5";
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
                SELECT a.*, u.nombre, u.apellido, u.fecha_nacimiento
                FROM atleta a
                LEFT JOIN resultado_competencia rc ON a.cedula = rc.id_atleta AND rc.id_competencia = :id_competencia
                JOIN usuarios u ON a.cedula = u.cedula
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

    public function listado_atletas_inscritos($id_competencia)
    {
        try {
            $consulta = "
            SELECT a.*, u.nombre, u.apellido, u.fecha_nacimiento, rc.id_competencia
            FROM resultado_competencia rc
            JOIN atleta a ON rc.id_atleta = a.cedula
            JOIN usuarios u ON a.cedula = u.cedula
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
// Modelo.php
public function obtenerCompetencia($id_competencia) {
    try {
        $consulta = "SELECT * FROM competencia WHERE id_competencia = :id_competencia";
        $respuesta = $this->conexion->prepare($consulta);
        $respuesta->execute([':id_competencia' => $id_competencia]);
        $resultado = $respuesta->fetch(PDO::FETCH_ASSOC);
        return ["ok" => true, "respuesta" => $resultado];
    } catch (Exception $e) {
        return ["ok" => false, "mensaje" => $e->getMessage()];
    }
}

public function modificarCompetencia($id_competencia, $nombre, $ubicacion, $fecha_inicio, $fecha_fin, $categoria, $subs, $tipo_competencia) {
    try {
        $consulta = "UPDATE competencia SET nombre = :nombre, lugar_competencia = :ubicacion, fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin, categoria = :categoria, subs = :subs, tipo_competicion = :tipo_competencia WHERE id_competencia = :id_competencia";
        $valores = [
            ':id_competencia' => $id_competencia,
            ':nombre' => $nombre,
            ':ubicacion' => $ubicacion,
            ':fecha_inicio' => $fecha_inicio,
            ':fecha_fin' => $fecha_fin,
            ':categoria' => $categoria,
            ':subs' => $subs,
            ':tipo_competicion' => $tipo_competencia
        ];
        $respuesta = $this->conexion->prepare($consulta);
        $respuesta->execute($valores);
        return ["ok" => true];
    } catch (Exception $e) {
        return ["ok" => false, "mensaje" => $e->getMessage()];
    }
}
public function eliminar_evento($id_competencia)
{
    try {
        $consulta = "DELETE FROM competencia WHERE id_competencia = :id_competencia";
        $stmt = $this->conexion->prepare($consulta);
        $stmt->execute([':id_competencia' => $id_competencia]);
        $resultado["ok"] = true;
    } catch (Exception $e) {
        $resultado["ok"] = false;
        $resultado["mensaje"] = $e->getMessage();
    }
    return $resultado;
}

    public function registrar_resultados($id_competencia, $id_atleta, $arranque, $envion, $medalla_arranque, $medalla_envion, $medalla_total, $total)
    {
        try {
            $consulta = "
                UPDATE resultado_competencia 
                SET arranque = :arranque, envion = :envion, medalla_arranque = :medalla_arranque, medalla_envion = :medalla_envion, medalla_total = :medalla_total, total = :total 
                WHERE id_competencia = :id_competencia AND id_atleta = :id_atleta";
            $valores = array(
                ':id_competencia' => $id_competencia,
                ':id_atleta' => $id_atleta,
                ':arranque' => $arranque,
                ':envion' => $envion,
                ':medalla_arranque' => $medalla_arranque,
                ':medalla_envion' => $medalla_envion,
                ':medalla_total' => $medalla_total,
                ':total' => $total
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

    public function __get($propiedad)
    {
        return $this->$propiedad;
    }

    public function __set($propiedad, $valor)
    {
        $this->$propiedad = $valor;
        return $this;
    }
    public function __destruct()
    {
        $this->conexion = null;  // Esto cierra la conexión
    }
}
