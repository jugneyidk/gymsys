<?php

class Eventos extends datos
{
   private $conexion, $nombre, $lugar_competencia, $fecha_inicio, $fecha_fin, $categoria, $subs, $tipo_competencia, $id_competencia;

   public function __construct()
   {
      $this->conexion = $this->conecta();
   }

   public function incluir_evento($datos)
   {
      $validacion = Validar::validar("nombre_evento", $datos["nombre"]);
      if (!$validacion["ok"]) {
         return $validacion;
      }
      $validacion = Validar::validar("lugar_competencia", $datos["lugar_competencia"]);
      if (!$validacion["ok"]) {
         return $validacion;
      }
      if (!Validar::validar_fecha($datos["fecha_inicio"])) {
         return ["ok" => false, "mensaje" => "La fecha de apertura no es valida"];
      }
      if (!Validar::validar_fecha($datos["fecha_fin"])) {
         return ["ok" => false, "mensaje" => "La fecha de clausura no es valida"];
      }
      if ($datos["fecha_inicio"] > $datos["fecha_fin"]) {
         return ["ok" => false, "mensaje" => "La fecha de inicio no puede ser mayor que la fecha de fin"];
      }
      if (!filter_var($datos["categoria"], FILTER_VALIDATE_INT)) {
         return ["ok" => false, "mensaje" => "La categoria no es un valor válido"];
      }
      if (!filter_var($datos["subs"], FILTER_VALIDATE_INT)) {
         return ["ok" => false, "mensaje" => "La sub no es un valor válido"];
      }
      if (!filter_var($datos["tipo_competencia"], FILTER_VALIDATE_INT)) {
         return ["ok" => false, "mensaje" => "El tipo de competencia no es un valor válido"];
      }
      foreach ($datos as $campo => $valor) {
         if (property_exists($this, $campo)) {
            $this->$campo = $valor;
         }
      }
      return $this->incluir();
   }

   public function listado_eventos()
   {
      return $this->listado();
   }

   private function incluir()
   {
      try {
         $consulta = "SELECT id_competencia FROM competencia WHERE nombre = ?;";
         $existe = Validar::existe($this->conexion, $this->nombre, $consulta);
         if ($existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "Ya existe una competencia con este nombre";
            return $resultado;
         }
         $this->conexion->beginTransaction();
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
   public function cerrar_evento($id_competencia)
   {
      if (!filter_var($id_competencia, FILTER_VALIDATE_INT)) {
         return ["ok" => false, "mensaje" => "La competencia ingresada no es un válida"];
      }
      $this->id_competencia = $id_competencia;
      return $this->cerrar();
   }
   private function cerrar()
   {
      try {
         $consulta = "SELECT id_competencia FROM competencia WHERE id_competencia = ?;";
         $existe = Validar::existe($this->conexion, $this->id_competencia, $consulta);
         if (!$existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "Esta competencia no existe";
            return $resultado;
         }
         $this->conexion->beginTransaction();
         $consulta = "UPDATE competencia SET estado = 'inactivo' WHERE id_competencia = :id_competencia";
         $stmt = $this->conexion->prepare($consulta);
         $stmt->execute([':id_competencia' => $this->id_competencia]);
         $stmt->closeCursor();
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
         $consulta = "SELECT 
                        c.id_competencia,
                        c.nombre,
                        c.categoria,  
                        c.subs,  
                        c.lugar_competencia,
                        c.fecha_inicio,
                        c.fecha_fin,
                        (SELECT COUNT(*) FROM resultado_competencia rc WHERE rc.id_competencia = c.id_competencia) AS participantes,
                        (10 - (SELECT COUNT(*) FROM resultado_competencia rc WHERE rc.id_competencia = c.id_competencia)) AS cupos_disponibles
                    FROM competencia c
                    WHERE c.estado = 'activo'";
         $respuesta = $this->conexion->prepare($consulta);
         $respuesta->execute();
         $respuesta = $respuesta->fetchAll(PDO::FETCH_ASSOC);
         $resultado["ok"] = true;
         $resultado["respuesta"] = $respuesta;
      } catch (PDOException $e) {
         $resultado["ok"] = false;
         $resultado["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $resultado;
   }
   public function incluir_categoria($nombre, $pesoMinimo, $pesoMaximo)
   {
      try {
         $validacion = Validar::validar("nombre_evento", $nombre);
         if (!$validacion["ok"]) {
            return $validacion;
         }
         $validacion = Validar::validar("peso", $pesoMinimo);
         if (!$validacion["ok"]) {
            return $validacion;
         }
         $validacion = Validar::validar("peso", $pesoMaximo);
         if (!$validacion["ok"]) {
            return $validacion;
         }
         if ($pesoMaximo <= $pesoMinimo) {
            return ["ok" => false, "mensaje" => "El peso máximo no puede ser menor o igual al peso mínimo"];
         }
         $consulta = "SELECT id_categoria FROM categorias WHERE nombre = ?;";
         $existe = Validar::existe($this->conexion, $nombre, $consulta);
         if ($existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "Ya existe una categoria con este nombre";
            return $resultado;
         }
         $this->conexion->beginTransaction();
         $consulta = "INSERT INTO categorias (nombre, peso_minimo, peso_maximo) VALUES (:nombre, :pesoMinimo, :pesoMaximo)";
         $valores = [
            ':nombre' => $nombre,
            ':pesoMinimo' => $pesoMinimo,
            ':pesoMaximo' => $pesoMaximo
         ];
         $stmt = $this->conexion->prepare($consulta);
         $stmt->execute($valores);
         $stmt->closeCursor();
         $this->conexion->commit();
         $respuesta["ok"] = true;
      } catch (PDOException $e) {
         $this->conexion->rollBack();
         $respuesta["ok"] = false;
         $respuesta["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $respuesta;
   }

   public function modificar_categoria($id, $nombre, $pesoMinimo, $pesoMaximo)
   {
      try {
         $consulta = "SELECT id_categoria FROM categorias WHERE id_categoria = ?;";
         $existe = Validar::existe($this->conexion, $id, $consulta);
         if (!$existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "No existe esta categoria";
            return $resultado;
         }
         $consulta = "SELECT id_categoria FROM categorias WHERE nombre = ? AND id_categoria != '$id';";
         $existe = Validar::existe($this->conexion, $nombre, $consulta);
         if ($existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "Ya existe una categoria con este nombre";
            return $resultado;
         }
         $validacion = Validar::validar("nombre_categoria", $nombre);
         if (!$validacion["ok"]) {
            return $validacion;
         }
         $validacion = Validar::validar("peso", $pesoMinimo);
         if (!$validacion["ok"]) {
            return $validacion;
         }
         $validacion = Validar::validar("peso", $pesoMaximo);
         if (!$validacion["ok"]) {
            return $validacion;
         }
         if ($pesoMaximo <= $pesoMinimo) {
            return ["ok" => false, "mensaje" => "El peso máximo no puede ser menor o igual al peso mínimo"];
         }
         $this->conexion->beginTransaction();
         $consulta = "UPDATE categorias 
                     SET nombre = :nombre, peso_minimo = :pesoMinimo, peso_maximo = :pesoMaximo 
                     WHERE id_categoria = :id";
         $valores = [
            ':id' => $id,
            ':nombre' => $nombre,
            ':pesoMinimo' => $pesoMinimo,
            ':pesoMaximo' => $pesoMaximo
         ];
         $stmt = $this->conexion->prepare($consulta);
         $stmt->execute($valores);
         $stmt->closeCursor();
         $this->conexion->commit();
         $respuesta["ok"] = true;
      } catch (PDOException $e) {
         $this->conexion->rollBack();
         $respuesta["ok"] = false;
         $respuesta["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $respuesta;
   }
   public function eliminar_categoria($id)
   {
      try {
         if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return ["ok" => false, "mensaje" => "La categoria no es un valor válido"];
         }
         $consulta = "SELECT id_categoria FROM categorias WHERE id_categoria = ?;";
         $existe = Validar::existe($this->conexion, $id, $consulta);
         if (!$existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "La categoria no existe";
            return $resultado;
         }
         $this->conexion->beginTransaction();
         $consulta = "DELETE FROM categorias WHERE id_categoria = :id";
         $stmt = $this->conexion->prepare($consulta);
         $stmt->execute([':id' => $id]);
         $stmt->closeCursor();
         $this->conexion->commit();
         $respuesta["ok"] = true;
      } catch (PDOException $e) {
         $this->conexion->rollBack();
         $respuesta["ok"] = false;
         $respuesta["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $respuesta;
   }

   public function modificar_resultados($id_competencia, $id_atleta, $arranque, $envion, $medalla_arranque, $medalla_envion, $medalla_total, $total)
   {
      try {
         if (!filter_var($id_competencia, FILTER_VALIDATE_INT)) {
            return ["ok" => false, "mensaje" => "El ID de competencia no es un valor válido"];
         }
         $consulta = "SELECT id_competencia FROM resultado_competencia WHERE id_competencia = ? AND id_atleta = '$id_atleta';";
         $existe = Validar::existe($this->conexion, $id_competencia, $consulta);
         if (!$existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "Este atleta o competencia no existe";
            return $resultado;
         }
         $this->conexion->beginTransaction();
         $consulta = "
            UPDATE resultado_competencia 
            SET arranque = :arranque, 
                envion = :envion, 
                medalla_arranque = :medalla_arranque, 
                medalla_envion = :medalla_envion, 
                medalla_total = :medalla_total, 
                total = :total 
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
         $resultado = $this->conexion->prepare($consulta);
         $resultado->execute($valores);
         $resultado->closeCursor();
         $this->conexion->commit();
         $respuesta["ok"] = true;
      } catch (PDOException $e) {
         $this->conexion->rollBack();
         $respuesta["ok"] = false;
         $respuesta["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $respuesta;
   }


   public function incluir_subs($nombre, $edadMinima, $edadMaxima)
   {
      try {
         $validacion = Validar::validar("nombre_sub", $nombre);
         if (!$validacion["ok"]) {
            return $validacion;
         }
         if (!filter_var($edadMinima, FILTER_VALIDATE_INT)) {
            return ["ok" => false, "mensaje" => "La edad mínima no es un valor válido"];
         }
         if (!filter_var($edadMaxima, FILTER_VALIDATE_INT)) {
            return ["ok" => false, "mensaje" => "La edad máxima no es un valor válido"];
         }
         if ($edadMaxima <= $edadMinima) {
            return ["ok" => false, "mensaje" => "La edad máxima no puede ser menor o igual a la edad mínima"];
         }
         $consulta = "SELECT id_sub FROM subs WHERE nombre = ?;";
         $existe = Validar::existe($this->conexion, $nombre, $consulta);
         if ($existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "Ya existe una sub con este nombre";
            return $resultado;
         }
         $this->conexion->beginTransaction();
         $consulta = "INSERT INTO subs (nombre, edad_minima, edad_maxima) 
                     VALUES (:nombre, :edadMinima, :edadMaxima)";
         $valores = [
            ':nombre' => $nombre,
            ':edadMinima' => $edadMinima,
            ':edadMaxima' => $edadMaxima
         ];
         $stmt = $this->conexion->prepare($consulta);
         $stmt->execute($valores);
         $stmt->closeCursor();
         $this->conexion->commit();
         $respuesta["ok"] = true;
      } catch (Exception $e) {
         $this->conexion->rollBack();
         $respuesta["ok"] = false;
         $respuesta["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $respuesta;
   }
   public function modificar_sub($id, $nombre, $edadMinima, $edadMaxima)
   {
      try {
         if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return ["ok" => false, "mensaje" => "El ID de la sub no es válido"];
         }
         $validacion = Validar::validar("nombre_sub", $nombre);
         if (!$validacion["ok"]) {
            return $validacion;
         }
         if (!filter_var($edadMinima, FILTER_VALIDATE_INT)) {
            return ["ok" => false, "mensaje" => "La edad mínima no es un valor válido"];
         }
         if (!filter_var($edadMaxima, FILTER_VALIDATE_INT)) {
            return ["ok" => false, "mensaje" => "La edad máxima no es un valor válido"];
         }
         if ($edadMaxima <= $edadMinima) {
            return ["ok" => false, "mensaje" => "La edad máxima no puede ser menor o igual a la edad mínima"];
         }
         $consulta = "SELECT id_sub FROM subs WHERE id_sub = ?;";
         $existe = Validar::existe($this->conexion, $id, $consulta);
         if (!$existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "Esta sub no existe";
            return $resultado;
         }
         $consulta = "SELECT id_sub FROM subs WHERE nombre = ? AND id_sub != '$id';";
         $existe = Validar::existe($this->conexion, $nombre, $consulta);
         if ($existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "Ya existe una sub con este nombre";
            return $resultado;
         }
         $this->conexion->beginTransaction();
         $consulta = "UPDATE subs 
                     SET nombre = :nombre, edad_minima = :edadMinima, edad_maxima = :edadMaxima 
                     WHERE id_sub = :id";
         $valores = [
            ':id' => $id,
            ':nombre' => $nombre,
            ':edadMinima' => $edadMinima,
            ':edadMaxima' => $edadMaxima
         ];
         $stmt = $this->conexion->prepare($consulta);
         $stmt->execute($valores);
         $stmt->closeCursor();
         $this->conexion->commit();
         $respuesta["ok"] = true;
      } catch (PDOException $e) {
         $this->conexion->rollBack();
         $respuesta["ok"] = false;
         $respuesta["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $respuesta;
   }

   public function eliminar_sub($id)
   {
      try {
         if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return ["ok" => false, "mensaje" => "El ID de la sub no es válido"];
         }
         $consulta = "SELECT id_sub FROM subs WHERE id_sub = ?;";
         $existe = Validar::existe($this->conexion, $id, $consulta);
         if (!$existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "Esta sub no existe";
            return $resultado;
         }
         $this->conexion->beginTransaction();
         $consulta = "DELETE FROM subs WHERE id_sub = :id";
         $stmt = $this->conexion->prepare($consulta);
         $stmt->execute([':id' => $id]);
         $stmt->closeCursor();
         $this->conexion->commit();
         $respuesta["ok"] = true;
      } catch (PDOException $e) {
         $this->conexion->rollBack();
         $respuesta["ok"] = false;
         $respuesta["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $respuesta;
   }

   public function incluir_tipo($nombre)
   {
      try {
         $validacion = Validar::validar("nombre_tipo", $nombre);
         if (!$validacion["ok"]) {
            return $validacion;
         }
         $consulta = "SELECT id_tipo_competencia FROM tipo_competencia WHERE nombre = ?;";
         $existe = Validar::existe($this->conexion, $nombre, $consulta);
         if ($existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "Ya existe este tipo de competencia";
            return $resultado;
         }
         $this->conexion->beginTransaction();
         $consulta = "INSERT INTO tipo_competencia (nombre) VALUES (:nombre)";
         $valores = array(':nombre' => $nombre);
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

   public function listado_categoria()
   {
      try {
         $consulta = "SELECT * FROM categorias";
         $respuesta = $this->conexion->prepare($consulta);
         $respuesta->execute();
         $respuesta = $respuesta->fetchAll(PDO::FETCH_ASSOC);
         $resultado["ok"] = true;
         $resultado["respuesta"] = $respuesta;
      } catch (PDOException $e) {
         $resultado["ok"] = false;
         $resultado["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $resultado;
   }
   public function listado_eventos_anteriores()
   {
      try {
         $consulta = "SELECT * FROM competencia WHERE fecha_fin < CURDATE() OR estado = 'inactivo' ORDER BY fecha_fin DESC LIMIT 5";
         $respuesta = $this->conexion->prepare($consulta);
         $respuesta->execute();
         $respuesta = $respuesta->fetchAll(PDO::FETCH_ASSOC);
         $resultado["ok"] = true;
         $resultado["respuesta"] = $respuesta;
      } catch (PDOException $e) {
         $resultado["ok"] = false;
         $resultado["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
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
      } catch (PDOException $e) {
         $resultado["ok"] = false;
         $resultado["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
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
      } catch (PDOException $e) {
         $resultado["ok"] = false;
         $resultado["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $resultado;
   }

   public function listado_atletas_inscritos($id_competencia)
   {
      try {
         if (!filter_var($id_competencia, FILTER_VALIDATE_INT)) {
            return ["ok" => false, "mensaje" => "El ID de la competencia no es válido"];
         }
         $consulta = "SELECT id_competencia FROM competencia WHERE id_competencia = ?;";
         $existe = Validar::existe($this->conexion, $id_competencia, $consulta);
         if (!$existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "No existe esta competencia";
            return $resultado;
         }
         $consulta = "
            SELECT 
                a.cedula AS id_atleta,
                u.nombre,
                u.apellido,
                rc.arranque,
                rc.envion,
                rc.medalla_arranque,
                rc.medalla_envion,
                rc.medalla_total,
                rc.total
            FROM resultado_competencia rc
            JOIN atleta a ON rc.id_atleta = a.cedula
            JOIN usuarios u ON a.cedula = u.cedula
            WHERE rc.id_competencia = :id_competencia";
         $respuesta = $this->conexion->prepare($consulta);
         $respuesta->execute([':id_competencia' => $id_competencia]);
         $respuesta = $respuesta->fetchAll(PDO::FETCH_ASSOC);
         $resultado["ok"] = true;
         $resultado["respuesta"] = $respuesta;
      } catch (PDOException $e) {
         $resultado["ok"] = false;
         $resultado["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $resultado;
   }


   public function inscribir_atletas($id_competencia, $atletas)
   {
      try {
         if (!filter_var($id_competencia, FILTER_VALIDATE_INT)) {
            return ["ok" => false, "mensaje" => "El ID de la competencia no es válido"];
         }
         if (!is_string($atletas)) {
            throw new Exception("El formato de los datos de atletas no es válido.");
         }
         $atletas = json_decode($atletas, true);
         if (!is_array($atletas)) {
            throw new Exception("El formato de los datos de atletas no es válido.");
         }
         $consulta = "SELECT id_competencia FROM competencia WHERE id_competencia = ?;";
         $existe = Validar::existe($this->conexion, $id_competencia, $consulta);
         if (!$existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "Esta competencia no existe";
            return $resultado;
         }
         $this->conexion->beginTransaction();
         foreach ($atletas as $id_atleta) {
            $consulta = "INSERT INTO resultado_competencia (id_competencia, id_atleta) VALUES (:id_competencia, :id_atleta)";
            $valores = array(
               ':id_competencia' => $id_competencia,
               ':id_atleta' => $id_atleta
            );
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute($valores);
            $stmt->closeCursor();
         }
         $this->conexion->commit();
         $respuesta["ok"] = true;
      } catch (PDOException $e) {
         $this->conexion->rollBack();
         $respuesta["ok"] = false;
         $respuesta["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $respuesta;
   }

   public function obtenerCompetencia($id_competencia)
   {
      try {
         if (!filter_var($id_competencia, FILTER_VALIDATE_INT)) {
            return ["ok" => false, "mensaje" => "El ID de la competencia no es válido"];
         }
         $consulta = "SELECT id_competencia FROM competencia WHERE id_competencia = ?;";
         $existe = Validar::existe($this->conexion, $id_competencia, $consulta);
         if (!$existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "Esta competencia no existe";
            return $resultado;
         }
         $consulta = "SELECT * FROM competencia WHERE id_competencia = :id_competencia";
         $resultado = $this->conexion->prepare($consulta);
         $resultado->execute([':id_competencia' => $id_competencia]);
         $resultado = $resultado->fetch(PDO::FETCH_ASSOC);
         $respuesta["ok"] = true;
         $respuesta["respuesta"] = $resultado;
      } catch (PDOException $e) {
         $respuesta["ok"] = false;
         $respuesta["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $respuesta;
   }

   public function modificarCompetencia($id_competencia, $nombre, $lugar_competencia, $fecha_inicio, $fecha_fin, $categoria, $subs, $tipo_competencia)
   {
      try {
         if (!filter_var($id_competencia, FILTER_VALIDATE_INT)) {
            return ["ok" => false, "mensaje" => "El ID de la competencia no es válido"];
         }
         $validacion = Validar::validar("nombre_evento", $nombre);
         if (!$validacion["ok"]) {
            return $validacion;
         }
         $validacion = Validar::validar("lugar_competencia", $lugar_competencia);
         if (!$validacion["ok"]) {
            return $validacion;
         }
         if (!Validar::validar_fecha($fecha_inicio)) {
            return ["ok" => false, "mensaje" => "La fecha de apertura no es valida"];
         }
         if (!Validar::validar_fecha($fecha_fin)) {
            return ["ok" => false, "mensaje" => "La fecha de clausura no es valida"];
         }
         if ($fecha_inicio > $fecha_fin) {
            return ["ok" => false, "mensaje" => "La fecha de inicio no puede ser mayor que la fecha de fin"];
         }
         if (!filter_var($categoria, FILTER_VALIDATE_INT)) {
            return ["ok" => false, "mensaje" => "La categoria no es un valor válido"];
         }
         if (!filter_var($subs, FILTER_VALIDATE_INT)) {
            return ["ok" => false, "mensaje" => "La sub no es un valor válido"];
         }
         if (!filter_var($tipo_competencia, FILTER_VALIDATE_INT)) {
            return ["ok" => false, "mensaje" => "El tipo de competencia no es un valor válido"];
         }
         $consulta = "SELECT id_competencia FROM competencia WHERE id_competencia = ?;";
         $existe = Validar::existe($this->conexion, $id_competencia, $consulta);
         if (!$existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "Esta competencia no existe";
            return $resultado;
         }
         $consulta = "SELECT id_competencia FROM competencia WHERE nombre = ? AND id_competencia != '$id_competencia';";
         $existe = Validar::existe($this->conexion, $nombre, $consulta);
         if ($existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "Ya existe una competencia con este nombre";
            return $resultado;
         }
         $this->conexion->beginTransaction();
         $consulta = "UPDATE competencia 
                     SET nombre = :nombre, 
                         lugar_competencia = :lugar_competencia, 
                         fecha_inicio = :fecha_inicio, 
                         fecha_fin = :fecha_fin, 
                         categoria = :categoria, 
                         subs = :subs, 
                         tipo_competicion = :tipo_competencia 
                     WHERE id_competencia = :id_competencia";
         $valores = [
            ':id_competencia' => $id_competencia,
            ':nombre' => $nombre,
            ':lugar_competencia' => $lugar_competencia,
            ':fecha_inicio' => $fecha_inicio,
            ':fecha_fin' => $fecha_fin,
            ':categoria' => $categoria,
            ':subs' => $subs,
            ':tipo_competencia' => $tipo_competencia
         ];
         $resultado = $this->conexion->prepare($consulta);
         $resultado->execute($valores);
         $resultado->closeCursor();
         $this->conexion->commit();
         $respuesta["ok"] = true;
      } catch (PDOException $e) {
         $this->conexion->rollBack();
         $respuesta["ok"] = false;
         $respuesta["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $respuesta;
   }

   public function eliminar_evento($id_competencia)
   {
      try {
         if (!filter_var($id_competencia, FILTER_VALIDATE_INT)) {
            return ["ok" => false, "mensaje" => "El ID de la competencia no es válido"];
         }
         $consulta = "SELECT id_competencia FROM competencia WHERE id_competencia = ?;";
         $existe = Validar::existe($this->conexion, $id_competencia, $consulta);
         if (!$existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "Esta competencia no existe";
            return $resultado;
         }
         $this->conexion->beginTransaction();
         $consultaRelacionada = "DELETE FROM resultado_competencia WHERE id_competencia = :id_competencia";
         $stmtRelacionada = $this->conexion->prepare($consultaRelacionada);
         $stmtRelacionada->execute([':id_competencia' => $id_competencia]);
         $consulta = "DELETE FROM competencia WHERE id_competencia = :id_competencia";
         $stmt = $this->conexion->prepare($consulta);
         $stmt->execute([':id_competencia' => $id_competencia]);
         $stmt->closeCursor();
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

   public function eliminar_tipo($id_tipo)
   {
      try {
         if (!filter_var($id_tipo, FILTER_VALIDATE_INT)) {
            return ["ok" => false, "mensaje" => "El ID del tipo de competencia no es válido"];
         }

         $consulta = "SELECT id_tipo_competencia FROM tipo_competencia WHERE id_tipo_competencia = ?;";
         $existe = Validar::existe($this->conexion, $id_tipo, $consulta);
         if (!$existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "Este tipo de competencia no existe";
            return $resultado;
         }
         $this->conexion->beginTransaction();
         $consulta = "DELETE FROM tipo_competencia WHERE id_tipo_competencia = :id_tipo";
         $stmt = $this->conexion->prepare($consulta);
         $stmt->execute([':id_tipo' => $id_tipo]);
         $stmt->closeCursor();
         $this->conexion->commit();
         $respuesta["ok"] = true;
      } catch (PDOException $e) {
         $this->conexion->rollBack();
         $respuesta["ok"] = false;
         $respuesta["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $respuesta;
   }

   public function modificar_tipo($id_tipo, $nombre)
   {
      try {
         if (!filter_var($id_tipo, FILTER_VALIDATE_INT)) {
            return ["ok" => false, "mensaje" => "El ID del tipo de competencia no es válido"];
         }
         $validacion = Validar::validar("nombre_tipo", $nombre);
         if (!$validacion["ok"]) {
            return $validacion;
         }
         $consulta = "SELECT id_tipo_competencia FROM tipo_competencia WHERE id_tipo_competencia = ?;";
         $existe = Validar::existe($this->conexion, $id_tipo, $consulta);
         if (!$existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "Este tipo de competencia no existe";
            return $resultado;
         }
         $consulta = "SELECT id_tipo_competencia FROM tipo_competencia WHERE nombre = ? AND id_tipo_competencia != '$id_tipo';";
         $existe = Validar::existe($this->conexion, $nombre, $consulta);
         if ($existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "Ya existe un tipo de competencia con este nombre";
            return $resultado;
         }
         $this->conexion->beginTransaction();
         $consulta = "UPDATE tipo_competencia SET nombre = :nombre WHERE id_tipo_competencia = :id_tipo";
         $stmt = $this->conexion->prepare($consulta);
         $stmt->execute([':nombre' => $nombre, ':id_tipo' => $id_tipo]);
         $stmt->closeCursor();
         $this->conexion->commit();
         $respuesta["ok"] = true;
      } catch (PDOException $e) {
         $this->conexion->rollBack();
         $respuesta["ok"] = false;
         $respuesta["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $respuesta;
   }
   public function verificar_relacion_tipo($id_tipo)
   {
      try {
         if (!filter_var($id_tipo, FILTER_VALIDATE_INT)) {
            return ["ok" => false, "mensaje" => "El ID del tipo de competencia no es válido"];
         }
         $consulta = "SELECT id_tipo_competencia FROM tipo_competencia WHERE id_tipo_competencia = ?;";
         $existe = Validar::existe($this->conexion, $id_tipo, $consulta);
         if (!$existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "Este tipo de competencia no existe";
            return $resultado;
         }
         $consulta = "SELECT COUNT(*) AS total FROM competencia WHERE tipo_competicion = :id_tipo";
         $stmt = $this->conexion->prepare($consulta);
         $stmt->execute([':id_tipo' => $id_tipo]);
         $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
         $respuesta["ok"] = true;
         $respuesta["existe"] = $resultado["total"] > 0;
      } catch (Exception $e) {
         $respuesta["ok"] = false;
         $respuesta["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $respuesta;
   }

   public function registrar_resultados($id_competencia, $id_atleta, $arranque, $envion, $medalla_arranque, $medalla_envion, $medalla_total, $total)
   {
      try {
         $consulta = "
            UPDATE resultado_competencia 
            SET arranque = :arranque, 
                envion = :envion, 
                medalla_arranque = :medalla_arranque, 
                medalla_envion = :medalla_envion, 
                medalla_total = :medalla_total, 
                total = :total 
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
         return ["ok" => true, "mensaje" => "Resultados registrados correctamente."];
      } catch (Exception $e) {
         return ["ok" => false, "mensaje" => $e->getMessage()];
      }
   }

   public function listado_atletas_disponibles($id_categoria, $id_sub, $id_competencia)
   {
      try {
         $consultaCategoria = "SELECT peso_minimo, peso_maximo FROM categorias WHERE id_categoria = :id_categoria";
         $stmtCategoria = $this->conexion->prepare($consultaCategoria);
         $stmtCategoria->execute([':id_categoria' => $id_categoria]);
         $categoria = $stmtCategoria->fetch(PDO::FETCH_ASSOC);

         if (!$categoria) {
            throw new Exception("Categoría no encontrada.");
         }

         $consultaSub = "SELECT edad_minima, edad_maxima FROM subs WHERE id_sub = :id_sub";
         $stmtSub = $this->conexion->prepare($consultaSub);
         $stmtSub->execute([':id_sub' => $id_sub]);
         $sub = $stmtSub->fetch(PDO::FETCH_ASSOC);

         if (!$sub) {
            throw new Exception("Subcategoría no encontrada.");
         }

         $consultaAtletas = "
                SELECT 
                    a.cedula AS id_atleta,
                    u.nombre,
                    u.apellido,
                    a.peso,
                    u.fecha_nacimiento
                FROM atleta a
                JOIN usuarios u ON a.cedula = u.cedula
                WHERE a.peso BETWEEN :peso_minimo AND :peso_maximo
                  AND TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE()) BETWEEN :edad_minima AND :edad_maxima
                  AND NOT EXISTS (
                      SELECT 1 
                      FROM resultado_competencia rc 
                      WHERE rc.id_competencia = :id_competencia AND rc.id_atleta = a.cedula
                  )";

         $stmtAtletas = $this->conexion->prepare($consultaAtletas);
         $stmtAtletas->execute([
            ':peso_minimo' => $categoria['peso_minimo'],
            ':peso_maximo' => $categoria['peso_maximo'],
            ':edad_minima' => $sub['edad_minima'],
            ':edad_maxima' => $sub['edad_maxima'],
            ':id_competencia' => $id_competencia
         ]);

         $atletas = $stmtAtletas->fetchAll(PDO::FETCH_ASSOC);

         return ["ok" => true, "respuesta" => $atletas];
      } catch (Exception $e) {
         return ["ok" => false, "mensaje" => $e->getMessage()];
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
   public function __destruct()
   {
      $this->conexion = null;
   }
}
