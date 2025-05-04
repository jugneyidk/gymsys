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
   public function incluirCategoria(array $datos): array
   {
      $keys = ['nombre', 'pesoMinimo', 'pesoMaximo'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::validar("nombre_evento", $arrayFiltrado['nombre']);
      Validar::validar("peso", $arrayFiltrado['pesoMinimo']);
      Validar::validar("peso", $arrayFiltrado['pesoMaximo']);
      if ($arrayFiltrado['pesoMaximo'] <= $arrayFiltrado['pesoMinimo']) {
         ExceptionHandler::throwException("El peso máximo no puede ser menor o igual al peso mínimo", 400, \InvalidArgumentException::class);
      }
      return $this->_incluirCategoria($arrayFiltrado);
   }
   private function _incluirCategoria(array $datos): array
   {
      $consulta = "SELECT id_categoria FROM categorias WHERE nombre = :id;";
      $existe = Validar::existe($this->database, $datos['nombre'], $consulta);
      if ($existe) {
         ExceptionHandler::throwException("Ya existe una categoria con este nombre", 404, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "INSERT INTO categorias (nombre, peso_minimo, peso_maximo) VALUES (:nombre, :pesoMinimo, :pesoMaximo)";
      $valores = [
         ':nombre' => $datos['nombre'],
         ':pesoMinimo' => $datos['pesoMinimo'],
         ':pesoMaximo' => $datos['pesoMaximo']
      ];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al agregar la categoría", 500, \InvalidArgumentException::class);
      }
      $this->database->commit();
      $respuesta["mensaje"] = "La categoría se registró exitosamente";
      return $respuesta;
   }
   public function modificarCategoria(array $datos): array
   {
      $keys = ['id_categoria', 'nombre', 'pesoMinimo', 'pesoMaximo'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::validar("nombre_categoria", $arrayFiltrado['nombre']);
      Validar::validar("peso", $arrayFiltrado['pesoMinimo']);
      Validar::validar("peso", $arrayFiltrado['pesoMaximo']);
      Validar::sanitizarYValidar($arrayFiltrado['id_categoria'], 'int');
      if ($arrayFiltrado['pesoMaximo'] <= $arrayFiltrado['pesoMinimo']) {
         ExceptionHandler::throwException("El peso máximo no puede ser menor o igual al peso mínimo", 400, \InvalidArgumentException::class);
      }
      return $this->_modificarCategoria($arrayFiltrado);
   }
   private function _modificarCategoria(array $datos): array
   {
      $consulta = "SELECT id_categoria FROM categorias WHERE id_categoria = :id;";
      $existe = Validar::existe($this->database, $datos['id_categoria'], $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("No existe la categoria ingresada", 404, \InvalidArgumentException::class);
      }
      $consulta = "SELECT id_categoria FROM categorias WHERE nombre = :id AND id_categoria != " . $datos["id_categoria"] . ";";
      $existe = Validar::existe($this->database, $datos['nombre'], $consulta);
      if ($existe) {
         ExceptionHandler::throwException("Ya existe una categoria con este nombre", 400, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "UPDATE categorias 
                     SET nombre = :nombre, peso_minimo = :pesoMinimo, peso_maximo = :pesoMaximo 
                     WHERE id_categoria = :id";
      $valores = [
         ':id' => $datos['id_categoria'],
         ':nombre' => $datos['nombre'],
         ':pesoMinimo' => $datos['pesoMinimo'],
         ':pesoMaximo' => $datos['pesoMaximo']
      ];
      $response = $this->database->query($consulta, $valores);
      if ($response) {
         ExceptionHandler::throwException("Ocurrió un error al modificar la categoria", 500, \Exception::class);
      }
      $this->database->commit();
      $respuesta["ok"] = true;
      return $respuesta;
   }
   public function eliminarCategoria(array $datos): array
   {
      $keys = ['id_categoria'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::sanitizarYValidar($arrayFiltrado['id_categoria'], 'int');
      return $this->_eliminarCategoria($arrayFiltrado['id_categoria']);
   }
   private function _eliminarCategoria(int $idCategoria): array
   {
      $consulta = "SELECT id_categoria FROM categorias WHERE id_categoria = :id;";
      $existe = Validar::existe($this->database, $idCategoria, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("La categoria ingresada no existe", 404, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "DELETE FROM categorias WHERE id_categoria = :id";
      $response = $this->database->query($consulta, [':id' => $idCategoria]);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al eliminar la categoria", 500, \Exception::class);
      }
      $this->database->commit();
      $respuesta["mensaje"] = "La categoria se eliminó exitosamente";
      return $respuesta;
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

   public function incluirSubs(array $datos): array
   {
      $keys = ['nombre', 'edadMinima', 'edadMaxima'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::validar("nombre_sub", $arrayFiltrado['nombre']);
      Validar::sanitizarYValidar($arrayFiltrado['edadMinima'], 'int');
      Validar::sanitizarYValidar($arrayFiltrado['edadMaxima'], 'int');
      if ($arrayFiltrado['edadMaxima'] <= $arrayFiltrado['edadMinima']) {
         ExceptionHandler::throwException("La edad máxima no puede ser menor o igual a la edad mínima", 400, \InvalidArgumentException::class);
      }
      return $this->_incluirSubs($arrayFiltrado);
   }
   private function _incluirSubs(array $datos): array
   {
      $consulta = "SELECT id_sub FROM subs WHERE nombre = :id;";
      $existe = Validar::existe($this->database, $datos['nombre'], $consulta);
      if ($existe) {
         ExceptionHandler::throwException("Ya existe una sub con este nombre", 400, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "INSERT INTO subs (nombre, edad_minima, edad_maxima) 
                     VALUES (:nombre, :edadMinima, :edadMaxima)";
      $valores = [
         ':nombre' => $datos['nombre'],
         ':edadMinima' => $datos['edadMinima'],
         ':edadMaxima' => $datos['edadMaxima']
      ];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al incluir la sub", 500, \Exception::class);
      }
      $this->database->commit();
      $respuesta["mensaje"] = "La sub se registró exitosamente";
      return $respuesta;
   }
   public function modificarSub(array $datos): array
   {
      $keys = ['id_sub', 'nombre', 'edadMinima', 'edadMaxima'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::sanitizarYValidar($arrayFiltrado['id_sub'], 'int');
      Validar::sanitizarYValidar($arrayFiltrado['edadMinima'], 'int');
      Validar::sanitizarYValidar($arrayFiltrado['edadMaxima'], 'int');
      Validar::validar("nombre_sub", $arrayFiltrado['nombre']);
      if ($arrayFiltrado['edadMaxima'] <= $arrayFiltrado['edadMinima']) {
         ExceptionHandler::throwException("La edad máxima no puede ser menor o igual a la edad mínima", 400, \InvalidArgumentException::class);
      }
      return $this->_modificarSub($arrayFiltrado);
   }
   public function _modificarSub(array $datos): array
   {
      $consulta = "SELECT id_sub FROM subs WHERE id_sub = :id;";
      $existe = Validar::existe($this->database, $datos['id_sub'], $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("La sub ingresada no existe", 404, \InvalidArgumentException::class);
      }
      $consulta = "SELECT id_sub FROM subs WHERE nombre = :id AND id_sub != {" . $datos['id_sub'] . "};";
      $existe = Validar::existe($this->database, $datos['nombre'], $consulta);
      if ($existe) {
         ExceptionHandler::throwException("Ya existe una sub con este nombre", 400, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "UPDATE subs 
                     SET nombre = :nombre, edad_minima = :edadMinima, edad_maxima = :edadMaxima 
                     WHERE id_sub = :id";
      $valores = [
         ':id' => $datos['id_sub'],
         ':nombre' => $datos['nombre'],
         ':edadMinima' => $datos['edadMinima'],
         ':edadMaxima' => $datos['edadMaxima']
      ];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al modificar la sub", 500, \Exception::class);
      }
      $this->database->commit();
      $respuesta["mensaje"] = "La sub se modificó exitosamente";
      return $respuesta;
   }
   public function eliminarSub(array $datos): array
   {
      $keys = ['id_sub'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::sanitizarYValidar($arrayFiltrado['id_sub'], 'int');
      return $this->_eliminarSub($arrayFiltrado['id_sub']);
   }

   private function _eliminarSub(int $idSub): array
   {
      $consulta = "SELECT id_sub FROM subs WHERE id_sub = :id;";
      $existe = Validar::existe($this->database, $idSub, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("La sub ingresada no existe", 404, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "DELETE FROM subs WHERE id_sub = :id";
      $response = $this->database->query($consulta, [':id' => $idSub]);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al eliminar la sub", 500, \Exception::class);
      }
      $this->database->commit();
      $respuesta["mensaje"] = "La sub se eliminó exitosamente";
      return $respuesta;
   }
   public function incluirTipo(array $datos): array
   {
      $keys = ['nombre'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::validar("nombre_tipo", $arrayFiltrado['nombre']);
      return $this->_incluirTipo($arrayFiltrado['nombre']);
   }
   private function _incluirTipo(string $nombreTipo): array
   {
      $consulta = "SELECT id_tipo_competencia FROM tipo_competencia WHERE nombre = :id;";
      $existe = Validar::existe($this->database, $nombreTipo, $consulta);
      if ($existe) {
         ExceptionHandler::throwException("Ya existe este tipo de competencia", 400, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "INSERT INTO tipo_competencia (nombre) VALUES (:nombre)";
      $response = $this->database->query($consulta, [':nombre' => $nombreTipo]);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al incluir el tipo de competencia", 500, \Exception::class);
      }
      $this->database->commit();
      $resultado["mensaje"] = "El tipo de competencia se registró exitosamente";
      return $resultado;
   }
   public function listadoCategorias(): array
   {
      return $this->_listadoCategorias();
   }
   private function _listadoCategorias(): array
   {
      $consulta = "SELECT * FROM categorias";
      $response = $this->database->query($consulta);
      $resultado["categorias"] = $response;
      return $resultado;
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
   public function listadoSubs(): array
   {
      return $this->_listadoSubs();
   }
   private function _listadoSubs(): array
   {
      $consulta = "SELECT * FROM subs";
      $response = $this->database->query($consulta);
      $resultado["subs"] = $response ?: [];
      return $resultado;
   }
   public function listadoTipos(): array
   {
      return $this->_listadoTipos();
   }
   private function _listadoTipos(): array
   {
      $consulta = "SELECT * FROM tipo_competencia";
      $response = $this->database->query($consulta);
      $resultado["tipos"] = $response ?: [];
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
      $response = $this->database->query($consulta, [':id_competencia' => $idCompetencia]);
      $respuesta["competencia"] = $response ?: [];
      return $respuesta;
   }
   public function modificarCompetencia(array $datos): array
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
      return $this->_modificarCompetencia($arrayFiltrado);
   }
   private function _modificarCompetencia(array $datos): array
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
   public function eliminarTipo(array $datos)
   {
      $keys = ['id'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      if (!Validar::sanitizarYValidar($arrayFiltrado['id'], "int")) {
         ExceptionHandler::throwException("El ID del tipo de competencia no es válido", 400, \InvalidArgumentException::class);
      }
      return $this->_eliminarTipo($arrayFiltrado['id']);
   }
   private function _eliminarTipo(int $idTipo): array
   {
      $consulta = "SELECT id_tipo_competencia FROM tipo_competencia WHERE id_tipo_competencia = :id;";
      $existe = Validar::existe($this->database, $idTipo, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("Este tipo de competencia no existe", 404, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "DELETE FROM tipo_competencia WHERE id_tipo_competencia = :id_tipo";
      $response = $this->database->query($consulta, [':id_tipo' => $idTipo]);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al eliminar el tipo de competencia", 500, \Exception::class);
      }
      $this->database->commit();
      $respuesta["mensaje"] = "El tipo de competencia se eliminó exitosamente";
      return $respuesta;
   }
   public function modificarTipo(array $datos): array
   {
      $keys = ['id_tipo', 'nombre'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::sanitizarYValidar($arrayFiltrado['id_tipo'], 'int');
      Validar::validar("nombre_tipo", $arrayFiltrado['nombre']);
      return $this->_modificarTipo($arrayFiltrado);
   }
   public function _modificarTipo(array $datos): array
   {
      $consulta = "SELECT id_tipo_competencia FROM tipo_competencia WHERE id_tipo_competencia = :id;";
      $existe = Validar::existe($this->database, $datos['id_tipo'], $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("El tipo de competencia ingresado no existe", 404, \InvalidArgumentException::class);
      }
      $consulta = "SELECT id_tipo_competencia FROM tipo_competencia WHERE nombre = :id AND id_tipo_competencia != " . $datos['id_tipo'] . ";";
      $existe = Validar::existe($this->database, $datos['nombre'], $consulta);
      if ($existe) {
         ExceptionHandler::throwException("Ya existe un tipo de competencia con este nombre", 400, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "UPDATE tipo_competencia SET nombre = :nombre WHERE id_tipo_competencia = :id_tipo";
      $valores = [
         ':nombre' => $datos['nombre'],
         ':id_tipo' => $datos['id_tipo']
      ];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al modificar el tipo de competencia", 500, \Exception::class);
      }
      $this->database->commit();
      $respuesta["mensaje"] = "El tipo de competencia se modificó exitosamente";
      return $respuesta;
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
      $keys = ['id_categoria', 'id_sub', 'id_competencia'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::sanitizarYValidar($arrayFiltrado['id_categoria'], 'int');
      Validar::sanitizarYValidar($arrayFiltrado['id_sub'], 'int');
      Validar::sanitizarYValidar($arrayFiltrado['id_competencia'], 'int');
      return $this->_listadoAtletasDisponibles($arrayFiltrado);
   }
   private function _listadoAtletasDisponibles(array $datos): array
   {
      $consultaCategoria = "SELECT peso_minimo, peso_maximo FROM categorias WHERE id_categoria = :id_categoria";
      $responseCategoria = $this->database->query($consultaCategoria, [':id_categoria' => $datos['id_categoria']], true);
      if (empty($responseCategoria)) {
         ExceptionHandler::throwException("La categoria ingresada no fue encontrada", 404, \InvalidArgumentException::class);
      }
      $consultaSub = "SELECT edad_minima, edad_maxima FROM subs WHERE id_sub = :id_sub";
      $responseSub = $this->database->query($consultaSub, [':id_sub' => $datos['id_sub']], true);
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
         ':id_competencia' => $datos['id_competencia']
      ];
      $response = $this->database->query($consulta, $valores);
      $resultado['atletas'] = $response ?: [];
      return $resultado;
   }
}
