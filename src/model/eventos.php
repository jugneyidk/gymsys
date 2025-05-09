<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\Validar;

class Eventos
{
   private Database $database;

   public function __construct(Database $database)
   {
      $this->database = $database;
   }

   public function incluirEvento(array $datos): array
   {
      $keys = ['nombre', 'lugar_competencia', 'fecha_inicio', 'fecha_fin', 'categoria', 'subs', 'tipo_competencia'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::validar("nombre_evento", $arrayFiltrado["nombre"]);
      Validar::validar("lugar_competencia", $arrayFiltrado["lugar_competencia"]);
      Validar::validarFecha($arrayFiltrado["fecha_inicio"]);
      Validar::validarFecha($arrayFiltrado["fecha_fin"]);
      if ($arrayFiltrado["fecha_inicio"] > $arrayFiltrado["fecha_fin"]) {
         ExceptionHandler::throwException("La fecha de inicio no puede ser mayor que la fecha de fin", 400, \InvalidArgumentException::class);
      }
      if (!Validar::sanitizarYValidar($arrayFiltrado["categoria"], 'int')) {
         ExceptionHandler::throwException("La categoria no es un valor válido", 400, \InvalidArgumentException::class);
      }
      if (!Validar::sanitizarYValidar($arrayFiltrado["subs"], 'int')) {
         ExceptionHandler::throwException("La sub no es un valor válido", 400, \InvalidArgumentException::class);
      }
      if (!Validar::sanitizarYValidar($arrayFiltrado["tipo_competencia"], 'int')) {
         ExceptionHandler::throwException("El tipo de competencia no es un valor válido", 400, \InvalidArgumentException::class);
      }
      return $this->_incluirEvento($arrayFiltrado);
   }

   public function listadoEventos(): array
   {
      return $this->_listadoEventos();
   }

   public function obtenerResultadosCompetencia(array $datos): array
   {
      $keys = ['id'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $idCompetencia = filter_var($arrayFiltrado['id'], FILTER_SANITIZE_NUMBER_INT);
      return $this->_obtenerResultadosCompetencia($idCompetencia);
   }
   private function _incluirEvento(array $datos): array
   {
      $consulta = "SELECT id_competencia FROM competencia WHERE nombre = :id;";
      $existe = Validar::existe($this->database, $datos['nombre'], $consulta);
      if ($existe) {
         ExceptionHandler::throwException("Ya existe una competencia con el nombre introducido", 400, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "INSERT INTO competencia(tipo_competicion, nombre, categoria, subs, lugar_competencia, fecha_inicio, fecha_fin, estado)
         VALUES (:tipo_competencia, :nombre, :categoria, :subs, :lugar_competencia, :fecha_inicio, :fecha_fin, 'activo')";
      $valores = [
         ':nombre' => $datos['nombre'],
         ':lugar_competencia' => $datos['lugar_competencia'],
         ':fecha_inicio' => $datos['fecha_inicio'],
         ':fecha_fin' => $datos['fecha_fin'],
         ':categoria' => $datos['categoria'],
         ':subs' => $datos['subs'],
         ':tipo_competencia' => $datos['tipo_competencia']
      ];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al incluir el evento", 500, \InvalidArgumentException::class);
      }
      $this->database->commit();
      $resultado["mensaje"] = "El evento se registró exitosamente";
      return $resultado;
   }
   public function cerrarEvento(array $datos): array
   {
      $keys = ['id'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $idCompetencia = Validar::sanitizarYValidar($arrayFiltrado['id'], 'int');
      if (!$idCompetencia) {
         ExceptionHandler::throwException("La competencia ingresada es invalida", 400, \InvalidArgumentException::class);
      }
      return $this->_cerrarEvento($arrayFiltrado['id']);
   }
   private function _cerrarEvento(int $idCompetencia): array
   {
      $consulta = "SELECT id_competencia FROM competencia WHERE id_competencia = :id;";
      $existe = Validar::existe($this->database, $idCompetencia, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("La competencia introducida no existe", 404, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "UPDATE competencia SET estado = 'inactivo' WHERE id_competencia = :id_competencia";
      $response = $this->database->query($consulta, [':id_competencia' => $idCompetencia]);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al cerrar el evento", 500, \Exception::class);
      }
      $this->database->commit();
      $resultado["mensaje"] = "El evento se cerró exitosamente";
      return $resultado;
   }
   private function _obtenerResultadosCompetencia(int $idCompetencia): array
   {
      $consulta = "SELECT id_competencia FROM competencia WHERE id_competencia = :id;";
      $existe = Validar::existe($this->database, $idCompetencia, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("La competencia introducida no existe", 404, \InvalidArgumentException::class);
      }
      $consulta = "SELECT rc.id_competencia, u.cedula, u.nombre, u.apellido, rc.arranque, rc.envion, rc.medalla_arranque, rc.medalla_envion, rc.medalla_total, rc.total FROM resultado_competencia rc
         INNER JOIN usuarios u ON  rc.id_atleta = u.cedula
         WHERE rc.id_competencia = :id_competencia;";
      $response = $this->database->query($consulta, [':id_competencia' => $idCompetencia]);
      $resultado["resultados"] = $response ?: [];
      return $resultado;
   }
   private function _listadoEventos(): array
   {
      $consulta = "SELECT * FROM lista_eventos_activos;";
      $response = $this->database->query($consulta);
      $resultado["eventos"] = $response ?: [];
      return $resultado;
   }
   public function modificarResultados(array $datos): array
   {
      $keys = ['id_competencia', 'id_atleta', 'arranque', 'envion', 'medalla_arranque', 'medalla_envion', 'medalla_total', 'total'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::sanitizarYValidar($arrayFiltrado['id_competencia'], 'int');
      return $this->_modificarResultados($datos);
   }
   public function _modificarResultados(array $datos): array
   {
      $consulta = "SELECT id_competencia FROM resultado_competencia WHERE id_competencia = " . $datos['id_competencia'] . " AND id_atleta = :id;";
      $existe = Validar::existe($this->database, $datos['id_atleta'], $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("El atleta o competencia no existe", 404, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "UPDATE resultado_competencia 
                     SET arranque = :arranque, 
                        envion = :envion, 
                        medalla_arranque = :medalla_arranque, 
                        medalla_envion = :medalla_envion, 
                        medalla_total = :medalla_total, 
                        total = :total 
                     WHERE id_competencia = :id_competencia AND id_atleta = :id_atleta";
      $valores = [
         ':id_competencia' => $datos['id_competencia'],
         ':id_atleta' => $datos['id_atleta'],
         ':arranque' => $datos['arranque'],
         ':envion' => $datos['envion'],
         ':medalla_arranque' => $datos['medalla_arranque'],
         ':medalla_envion' => $datos['medalla_envion'],
         ':medalla_total' => $datos['medalla_total'],
         ':total' => $datos['total']
      ];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al modificar el resultado", 500, \Exception::class);
      }
      $this->database->commit();
      $respuesta["mensaje"] = "El resultado se modificó exitosamente";
      return $respuesta;
   }
   public function listadoEventosAnteriores(): array
   {
      return $this->_listadoEventosAnteriores();
   }
   private function _listadoEventosAnteriores(): array
   {
      $consulta = "SELECT * FROM competencia WHERE fecha_fin < CURDATE() OR estado = 'inactivo' ORDER BY fecha_fin DESC LIMIT 5";
      $response = $this->database->query($consulta);
      $resultado["eventos"] = $response ?: [];
      return $resultado;
   }

   public function listadoAtletasInscritos(array $datos): array
   {
      $keys = ['id_competencia'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::sanitizarYValidar($arrayFiltrado['id_competencia'], 'int');
      return $this->_listadoAtletasInscritos($arrayFiltrado['id_competencia']);
   }

   private function _listadoAtletasInscritos(int $idCompetencia): array
   {
      $consulta = "SELECT id_competencia FROM competencia WHERE id_competencia = :id;";
      $existe = Validar::existe($this->database, $idCompetencia, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("No existe la competencia ingresada", 404, \InvalidArgumentException::class);
      }
      $consulta = "SELECT 
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
      $response = $this->database->query($consulta, [':id_competencia' => $idCompetencia]);
      $resultado["atletas"] = $response ?: [];
      return $resultado;
   }
   public function inscribirAtletas(array $datos): array
   {
      $keys = ['id_competencia', 'atletas'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::sanitizarYValidar($arrayFiltrado['id_competencia'], 'int');
      if (!is_string($arrayFiltrado['atletas'])) {
         ExceptionHandler::throwException("El formato de los datos de atletas no es válido", 400, \InvalidArgumentException::class);
      }
      $atletas = json_decode($arrayFiltrado['atletas'], true);
      if (!is_array($atletas)) {
         ExceptionHandler::throwException("El formato de los datos de atletas no es válido", 400, \InvalidArgumentException::class);
      }
      return $this->_inscribirAtletas($arrayFiltrado['id_competencia'], $atletas);
   }
   private function _inscribirAtletas(int $idCompetencia, array $atletas): array
   {
      $consulta = "SELECT id_competencia FROM competencia WHERE id_competencia = :id;";
      $existe = Validar::existe($this->database, $idCompetencia, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("Esta competencia no existe", 400, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "INSERT INTO resultado_competencia (id_competencia, id_atleta) VALUES (:id_competencia, :id_atleta)";
      foreach ($atletas as $id_atleta) {
         $valores = [
            ':id_competencia' => $idCompetencia,
            ':id_atleta' => $id_atleta
         ];
         $response = $this->database->query($consulta, $valores);
         if (empty($response)) {
            ExceptionHandler::throwException("Ocurrió un error al inscribir los atletas", 500, \Exception::class);
         }
      }
      $this->database->commit();
      $respuesta["mensaje"] = "Los atletas se inscribieron exitosamente";
      return $respuesta;
   }
   public function obtenerCompetencia(array $datos): array
   {
      $keys = ['id'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      if (!filter_var($arrayFiltrado['id'], FILTER_VALIDATE_INT)) {
         ExceptionHandler::throwException("La competencia ingresada es invalida", 400, \InvalidArgumentException::class);
      }
      return $this->_obtenerCompetencia($arrayFiltrado['id']);
   }
   private function _obtenerCompetencia(int $idCompetencia): array
   {
      $consulta = "SELECT id_competencia FROM competencia WHERE id_competencia = :id;";
      $existe = Validar::existe($this->database, $idCompetencia, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("La competencia ingresada no existe", 404, \InvalidArgumentException::class);
      }
      $consulta = "SELECT * FROM competencia WHERE id_competencia = :id_competencia";
      $response = $this->database->query($consulta, [':id_competencia' => $idCompetencia], true);
      $respuesta["competencia"] = $response ?: [];
      return $respuesta;
   }
   public function modificarEvento(array $datos): array
   {
      $keys = ['id_competencia', 'nombre', 'lugar_competencia', 'fecha_inicio', 'fecha_fin', 'categoria', 'subs', 'tipo_competencia'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::validar("nombre_evento", $arrayFiltrado["nombre"]);
      Validar::validar("lugar_competencia", $arrayFiltrado["lugar_competencia"]);
      Validar::validarFecha($arrayFiltrado["fecha_inicio"]);
      Validar::validarFecha($arrayFiltrado["fecha_fin"]);
      if ($arrayFiltrado["fecha_inicio"] > $arrayFiltrado["fecha_fin"]) {
         ExceptionHandler::throwException("La fecha de inicio no puede ser mayor que la fecha de fin", 400, \InvalidArgumentException::class);
      }
      if (!Validar::sanitizarYValidar($arrayFiltrado["id_competencia"], 'int')) ExceptionHandler::throwException("La competencia ingresada no es un valor válido", 400, \InvalidArgumentException::class);
      if (!Validar::sanitizarYValidar($arrayFiltrado["categoria"], 'int')) {
         ExceptionHandler::throwException("La categoria no es un valor válido", 400, \InvalidArgumentException::class);
      }
      if (!Validar::sanitizarYValidar($arrayFiltrado["subs"], 'int')) {
         ExceptionHandler::throwException("La sub no es un valor válido", 400, \InvalidArgumentException::class);
      }
      if (!Validar::sanitizarYValidar($arrayFiltrado["tipo_competencia"], 'int')) {
         ExceptionHandler::throwException("El tipo de competencia no es un valor válido", 400, \InvalidArgumentException::class);
      }
      return $this->_modificarEvento($arrayFiltrado);
   }
   private function _modificarEvento(array $datos): array
   {
      $consulta = "SELECT id_competencia FROM competencia WHERE id_competencia = :id;";
      $existe = Validar::existe($this->database, $datos['id_competencia'], $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("La competencia ingresada no existe", 404, \InvalidArgumentException::class);
      }
      $consulta = "SELECT id_competencia FROM competencia WHERE nombre = :id AND id_competencia != " . $datos['id_competencia'] . ";";
      $existe = Validar::existe($this->database, $datos['nombre'], $consulta);
      if ($existe) {
         ExceptionHandler::throwException("Ya existe una competencia con este nombre", 400, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
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
         ':id_competencia' => $datos['id_competencia'],
         ':nombre' => $datos['nombre'],
         ':lugar_competencia' => $datos['lugar_competencia'],
         ':fecha_inicio' => $datos['fecha_inicio'],
         ':fecha_fin' => $datos['fecha_fin'],
         ':categoria' => $datos['categoria'],
         ':subs' => $datos['subs'],
         ':tipo_competencia' => $datos['tipo_competencia']
      ];
      $response = $this->database->query($consulta, $valores);
      $this->database->commit();
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al modificar la competencia", 500, \Exception::class);
      }
      $respuesta["mensaje"] = "La competencia se modificó exitosamente";
      return $respuesta;
   }
   public function eliminarEvento(array $datos): array
   {
      $keys = ['id'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $idCompetencia = Validar::sanitizarYValidar($arrayFiltrado['id'], 'int');
      if (!$idCompetencia) {
         ExceptionHandler::throwException("La competencia ingresada es invalida", 400, \InvalidArgumentException::class);
      }
      return $this->_eliminarEvento($arrayFiltrado['id']);
   }
   private function _eliminarEvento(int $idCompetencia): array
   {
      $consulta = "SELECT id_competencia FROM competencia WHERE id_competencia = :id;";
      $existe = Validar::existe($this->database, $idCompetencia, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("La competencia ingresada no existe", 404, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "DELETE FROM competencia WHERE id_competencia = :id_competencia";
      $response = $this->database->query($consulta, [':id_competencia' => $idCompetencia]);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al eliminar la competencia", 500, \Exception::class);
      }
      $this->database->commit();
      $resultado["mensaje"] = "La competencia se eliminó exitosamente";
      return $resultado;
   }
   public function registrarResultados(array $datos): array
   {
      $keys = ['id_competencia', 'id_atleta', 'arranque', 'envion', 'medalla_arranque', 'medalla_envion', 'medalla_total', 'total'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::sanitizarYValidar($arrayFiltrado['id_competencia'], 'int');
      return $this->_registrarResultados($arrayFiltrado);
   }
   private function _registrarResultados(array $datos): array
   {
      $consulta = "SELECT id_competencia FROM resultado_competencia WHERE id_competencia = " . $datos['id_competencia'] . " AND id_atleta = :id;";
      $existe = Validar::existe($this->database, $datos['id_atleta'], $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("El atleta o competencia no existe", 404, \InvalidArgumentException::class);
      }
      $consulta = "UPDATE resultado_competencia 
                     SET arranque = :arranque, 
                        envion = :envion, 
                        medalla_arranque = :medalla_arranque, 
                        medalla_envion = :medalla_envion, 
                        medalla_total = :medalla_total, 
                        total = :total 
                     WHERE id_competencia = :id_competencia AND id_atleta = :id_atleta";
      $valores = [
         ':id_competencia' => $datos['id_competencia'],
         ':id_atleta' => $datos['id_atleta'],
         ':arranque' => $datos['arranque'],
         ':envion' => $datos['envion'],
         ':medalla_arranque' => $datos['medalla_arranque'],
         ':medalla_envion' => $datos['medalla_envion'],
         ':medalla_total' => $datos['medalla_total'],
         ':total' => $datos['total']
      ];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al registrar el resultado", 500, \Exception::class);
      }
      $this->database->commit();
      $respuesta["mensaje"] = "El resultado se registró exitosamente";
      return $respuesta;
   }
   public function listadoAtletasDisponibles(array $datos): array
   {
      $keys = ['categoria', 'sub', 'idCompetencia'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::sanitizarYValidar($arrayFiltrado['categoria'], 'int');
      Validar::sanitizarYValidar($arrayFiltrado['sub'], 'int');
      Validar::sanitizarYValidar($arrayFiltrado['idCompetencia'], 'int');
      return $this->_listadoAtletasDisponibles($arrayFiltrado);
   }
   private function _listadoAtletasDisponibles(array $datos): array
   {
      $consultaCategoria = "SELECT peso_minimo, peso_maximo FROM categorias WHERE id_categoria = :id_categoria";
      $responseCategoria = $this->database->query($consultaCategoria, [':id_categoria' => $datos['categoria']], true);
      if (empty($responseCategoria)) {
         ExceptionHandler::throwException("La categoria ingresada no fue encontrada", 404, \InvalidArgumentException::class);
      }
      $consultaSub = "SELECT edad_minima, edad_maxima FROM subs WHERE id_sub = :id_sub";
      $responseSub = $this->database->query($consultaSub, [':id_sub' => $datos['sub']], true);
      if (empty($responseSub)) {
         ExceptionHandler::throwException("La sub ingresada no fue encontrada", 404, \InvalidArgumentException::class);
      }
      $consulta = "SELECT 
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
                      WHERE rc.id_competencia = :id_competencia AND rc.id_atleta = a.cedula)";
      $valores = [
         ':peso_minimo' => $responseCategoria['peso_minimo'],
         ':peso_maximo' => $responseCategoria['peso_maximo'],
         ':edad_minima' => $responseSub['edad_minima'],
         ':edad_maxima' => $responseSub['edad_maxima'],
         ':id_competencia' => $datos['idCompetencia']
      ];
      $response = $this->database->query($consulta, $valores);
      $resultado['atletas'] = $response ?: [];
      return $resultado;
   }
}
