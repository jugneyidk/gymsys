<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
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
         ExceptionHandler::throwException("La fecha de inicio no puede ser mayor que la fecha de fin", \InvalidArgumentException::class);
      }
      $arrayFiltrado['categoria'] = Cipher::aesDecrypt($arrayFiltrado['categoria']);
      $arrayFiltrado['subs'] = Cipher::aesDecrypt($arrayFiltrado['subs']);
      $arrayFiltrado['tipo_competencia'] = Cipher::aesDecrypt($arrayFiltrado['tipo_competencia']);
      if (!Validar::sanitizarYValidar($arrayFiltrado["categoria"], 'int')) {
         ExceptionHandler::throwException("La categoria no es un valor válido", \InvalidArgumentException::class);
      }
      if (!Validar::sanitizarYValidar($arrayFiltrado["subs"], 'int')) {
         ExceptionHandler::throwException("La sub no es un valor válido", \InvalidArgumentException::class);
      }
      if (!Validar::sanitizarYValidar($arrayFiltrado["tipo_competencia"], 'int')) {
         ExceptionHandler::throwException("El tipo de competencia no es un valor válido", \InvalidArgumentException::class);
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
      $idCompetencia = Cipher::aesDecrypt($arrayFiltrado['id']);
      Validar::sanitizarYValidar($idCompetencia, 'int');
      return $this->_obtenerResultadosCompetencia($idCompetencia);
   }
   private function _incluirEvento(array $datos): array
   {
      $consulta = "SELECT id_competencia FROM competencia WHERE nombre = :id;";
      $existe = Validar::existe($this->database, $datos['nombre'], $consulta);
      if ($existe) {
         ExceptionHandler::throwException("Ya existe una competencia con el nombre introducido", \InvalidArgumentException::class);
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
         ExceptionHandler::throwException("Ocurrió un error al incluir el evento", \InvalidArgumentException::class, 500);
      }
      $this->database->commit();
      $resultado["mensaje"] = "El evento se registró exitosamente";
      return $resultado;
   }
   public function cerrarEvento(array $datos): array
   {
      $keys = ['id_competencia'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $arrayFiltrado['id_competencia'] = Cipher::aesDecrypt($arrayFiltrado['id_competencia']);
      $idCompetencia = Validar::sanitizarYValidar($arrayFiltrado['id_competencia'], 'int');
      if (!$idCompetencia) {
         ExceptionHandler::throwException("La competencia ingresada es invalida", \InvalidArgumentException::class);
      }
      return $this->_cerrarEvento($arrayFiltrado['id_competencia']);
   }
   private function _cerrarEvento(int $idCompetencia): array
   {
      $consulta = "SELECT id_competencia FROM competencia WHERE id_competencia = :id;";
      $existe = Validar::existe($this->database, $idCompetencia, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("La competencia introducida no existe", \InvalidArgumentException::class, 404);
      }
      $this->database->beginTransaction();
      $consulta = "UPDATE competencia SET estado = 'inactivo' WHERE id_competencia = :id_competencia";
      $response = $this->database->query($consulta, [':id_competencia' => $idCompetencia]);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al cerrar el evento", \Exception::class, 500);
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
         ExceptionHandler::throwException("La competencia introducida no existe", \InvalidArgumentException::class, 404);
      }
      $consulta = "SELECT rc.id_competencia, u.cedula, u.nombre, u.apellido, rc.arranque, rc.envion, rc.medalla_arranque, rc.medalla_envion, rc.medalla_total, rc.total FROM resultado_competencia rc
         INNER JOIN {$_ENV['SECURE_DB']}.usuarios u ON  rc.id_atleta = u.cedula
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
      if (!empty($resultado["eventos"])) {
         Cipher::crearHashArray($resultado['eventos'], "subs", true);
         Cipher::crearHashArray($resultado['eventos'], "categoria", true);
         Cipher::crearHashArray($resultado['eventos'], "tipo_competicion", true);
         Cipher::encriptarCampoArray($resultado['eventos'], "subs", false);
         Cipher::encriptarCampoArray($resultado['eventos'], "categoria", false);
         Cipher::encriptarCampoArray($resultado['eventos'], "tipo_competicion", false);
         Cipher::encriptarCampoArray($resultado["eventos"], "id_competencia", false);
      }
      return $resultado;
   }
   public function modificarResultados(array $datos): array
   {
      $keys = ['id_competencia', 'id_atleta', 'arranque', 'envion', 'medalla_arranque', 'medalla_envion', 'medalla_total'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $arrayFiltrado['id_competencia'] = Cipher::aesDecrypt($arrayFiltrado['id_competencia']);
      Validar::sanitizarYValidar($arrayFiltrado['id_competencia'], 'int');
      $arrayFiltrado['id_atleta'] = Cipher::aesDecrypt($arrayFiltrado['id_atleta']);
      Validar::validar("cedula", $arrayFiltrado['id_atleta']);
      Validar::sanitizarYValidar($arrayFiltrado['id_atleta'], 'int');
      Validar::sanitizarYValidar($arrayFiltrado['arranque'], 'float');
      Validar::sanitizarYValidar($arrayFiltrado['envion'], 'float');
      Validar::validar("medalla", $arrayFiltrado['medalla_arranque']);
      Validar::validar("medalla", $arrayFiltrado['medalla_envion']);
      Validar::validar("medalla", $arrayFiltrado['medalla_total']);
      return $this->_modificarResultados($arrayFiltrado);
   }
   public function _modificarResultados(array $datos): array
   {
      $consulta = "SELECT id_competencia FROM resultado_competencia WHERE id_competencia = " . $datos['id_competencia'] . " AND id_atleta = :id;";
      $existe = Validar::existe($this->database, $datos['id_atleta'], $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("El atleta o competencia no existe", \InvalidArgumentException::class, 404);
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
         ':total' => $datos['arranque'] + $datos['envion']
      ];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al modificar el resultado", \RuntimeException::class, 500);
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
      $consulta = "SELECT * FROM lista_eventos_anteriores;";
      $response = $this->database->query($consulta);
      $resultado["eventos"] = $response ?: [];
      if (!empty($resultado["eventos"])) {
         Cipher::crearHashArray($resultado['eventos'], "subs", true);
         Cipher::crearHashArray($resultado['eventos'], "categoria", true);
         Cipher::crearHashArray($resultado['eventos'], "tipo_competicion", true);
         Cipher::encriptarCampoArray($resultado['eventos'], "subs", false);
         Cipher::encriptarCampoArray($resultado['eventos'], "categoria", false);
         Cipher::encriptarCampoArray($resultado['eventos'], "tipo_competicion", false);
         Cipher::encriptarCampoArray($resultado['eventos'], "id_competencia", false);
      }
      return $resultado;
   }

   public function listadoAtletasInscritos(array $datos): array
   {
      $keys = ['id_competencia'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $idCompetencia = Cipher::aesDecrypt($arrayFiltrado['id_competencia']);
      Validar::sanitizarYValidar($idCompetencia, 'int');
      return $this->_listadoAtletasInscritos($idCompetencia);
   }

   private function _listadoAtletasInscritos(int $idCompetencia): array
   {
      $consulta = "SELECT id_competencia FROM competencia WHERE id_competencia = :id;";
      $existe = Validar::existe($this->database, $idCompetencia, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("No existe la competencia ingresada", \InvalidArgumentException::class, 404);
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
                  JOIN {$_ENV['SECURE_DB']}.usuarios u ON a.cedula = u.cedula
                  WHERE rc.id_competencia = :id_competencia";
      $response = $this->database->query($consulta, [':id_competencia' => $idCompetencia]);
      $resultado["atletas"] = $response ?: [];
      if (!empty($resultado["atletas"])) {
         Cipher::encriptarCampoArray($resultado["atletas"], "id_atleta");
      }
      return $resultado;
   }
   public function inscribirAtletas(array $datos): array
   {
      $keys = ['id_competencia', 'atletas'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $idCompetencia = Cipher::aesDecrypt($arrayFiltrado['id_competencia']);
      Validar::sanitizarYValidar($idCompetencia, 'int');
      if (!is_string($arrayFiltrado['atletas'])) {
         ExceptionHandler::throwException("El formato de los datos de atletas no es válido", \InvalidArgumentException::class);
      }
      $atletas = json_decode($arrayFiltrado['atletas'], true);
      if (!is_array($atletas)) {
         ExceptionHandler::throwException("El formato de los datos de atletas no es válido", \InvalidArgumentException::class);
      }
      foreach ($atletas as &$idAtleta) {
         $idAtleta = Cipher::aesDecrypt($idAtleta);
         Validar::validar("cedula", $idAtleta);
      }
      return $this->_inscribirAtletas($idCompetencia, $atletas);
   }
   private function _inscribirAtletas(int $idCompetencia, array $atletas): array
   {
      $consulta = "SELECT id_competencia FROM competencia WHERE id_competencia = :id;";
      $existe = Validar::existe($this->database, $idCompetencia, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("Esta competencia no existe", \InvalidArgumentException::class);
      }

      // Verificar que todos los atletas existen
      $atletasNoExistentes = [];
      foreach ($atletas as $idAtleta) {
         $consultaAtleta = "SELECT cedula FROM atleta WHERE cedula = :id";
         $atletaExiste = Validar::existe($this->database, $idAtleta, $consultaAtleta);
         if (!$atletaExiste) {
            $atletasNoExistentes[] = $idAtleta;
         }
      }

      if (!empty($atletasNoExistentes)) {
         ExceptionHandler::throwException("Los siguientes atletas no están registrados en el sistema: " . implode(', ', $atletasNoExistentes) . ". Por favor, registre primero a estos atletas antes de inscribirlos en la competencia.", \InvalidArgumentException::class);
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
            ExceptionHandler::throwException("Ocurrió un error al inscribir los atletas", \RuntimeException::class, 500);
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
      $idCompetencia = Cipher::aesDecrypt($arrayFiltrado['id']);
      Validar::sanitizarYValidar($idCompetencia, 'int');
      return $this->_obtenerCompetencia($idCompetencia);
   }
   private function _obtenerCompetencia(int $idCompetencia): array
   {
      $consulta = "SELECT id_competencia FROM competencia WHERE id_competencia = :id;";
      $existe = Validar::existe($this->database, $idCompetencia, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("La competencia ingresada no existe", \InvalidArgumentException::class, 404);
      }
      $consulta = "SELECT * FROM competencia WHERE id_competencia = :id_competencia";
      $response = $this->database->query($consulta, [':id_competencia' => $idCompetencia], true);
      $respuesta["competencia"] = $response ?: [];
      if (!empty($respuesta["competencia"])) {
         Cipher::crearHashArray($respuesta, "subs", true);
         Cipher::crearHashArray($respuesta, "categoria", true);
         Cipher::crearHashArray($respuesta, "tipo_competicion", true);
         Cipher::encriptarCampoArray($respuesta, "subs", false);
         Cipher::encriptarCampoArray($respuesta, "categoria", false);
         Cipher::encriptarCampoArray($respuesta, "tipo_competicion", false);
         Cipher::encriptarCampoArray($respuesta, "id_competencia", false);
      }
      return $respuesta;
   }
   public function modificarEvento(array $datos): array
   {
      $keys = ['id_competencia', 'nombre', 'lugar_competencia', 'fecha_inicio', 'fecha_fin', 'categoria', 'subs', 'tipo_competencia'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $arrayFiltrado["id_competencia"] = Cipher::aesDecrypt($arrayFiltrado["id_competencia"]);
      $arrayFiltrado['categoria'] = Cipher::aesDecrypt($arrayFiltrado['categoria']);
      $arrayFiltrado['subs'] = Cipher::aesDecrypt($arrayFiltrado['subs']);
      $arrayFiltrado['tipo_competencia'] = Cipher::aesDecrypt($arrayFiltrado['tipo_competencia']);
      Validar::validar("nombre_evento", $arrayFiltrado["nombre"]);
      Validar::validar("lugar_competencia", $arrayFiltrado["lugar_competencia"]);
      Validar::validarFecha($arrayFiltrado["fecha_inicio"]);
      Validar::validarFecha($arrayFiltrado["fecha_fin"]);
      if ($arrayFiltrado["fecha_inicio"] > $arrayFiltrado["fecha_fin"]) {
         ExceptionHandler::throwException("La fecha de inicio no puede ser mayor que la fecha de fin", \InvalidArgumentException::class);
      }
      if (!Validar::sanitizarYValidar($arrayFiltrado["id_competencia"], 'int')) ExceptionHandler::throwException("La competencia ingresada no es un valor válido", \InvalidArgumentException::class);
      if (!Validar::sanitizarYValidar($arrayFiltrado["categoria"], 'int')) {
         ExceptionHandler::throwException("La categoria no es un valor válido", \InvalidArgumentException::class);
      }
      if (!Validar::sanitizarYValidar($arrayFiltrado["subs"], 'int')) {
         ExceptionHandler::throwException("La sub no es un valor válido", \InvalidArgumentException::class);
      }
      if (!Validar::sanitizarYValidar($arrayFiltrado["tipo_competencia"], 'int')) {
         ExceptionHandler::throwException("El tipo de competencia no es un valor válido", \InvalidArgumentException::class);
      }
      return $this->_modificarEvento($arrayFiltrado);
   }
   private function _modificarEvento(array $datos): array
   {
      $consulta = "SELECT id_competencia FROM competencia WHERE id_competencia = :id;";
      $existe = Validar::existe($this->database, $datos['id_competencia'], $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("La competencia ingresada no existe", \InvalidArgumentException::class, 404);
      }
      $consulta = "SELECT id_competencia FROM competencia WHERE nombre = :id AND id_competencia != " . $datos['id_competencia'] . ";";
      $existe = Validar::existe($this->database, $datos['nombre'], $consulta);
      if ($existe) {
         ExceptionHandler::throwException("Ya existe una competencia con este nombre", \InvalidArgumentException::class);
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
         ExceptionHandler::throwException("Ocurrió un error al modificar la competencia", \RuntimeException::class, 500);
      }
      $respuesta["mensaje"] = "La competencia se modificó exitosamente";
      return $respuesta;
   }
   public function eliminarEvento(array $datos): array
   {
      $keys = ['id_competencia'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $arrayFiltrado["id_competencia"] = Cipher::aesDecrypt($arrayFiltrado["id_competencia"]);
      $idCompetencia = Validar::sanitizarYValidar($arrayFiltrado['id_competencia'], 'int');
      if (!$idCompetencia) {
         ExceptionHandler::throwException("La competencia ingresada es invalida", \InvalidArgumentException::class);
      }
      return $this->_eliminarEvento($arrayFiltrado['id_competencia']);
   }
   private function _eliminarEvento(int $idCompetencia): array
   {
      $consulta = "SELECT id_competencia FROM competencia WHERE id_competencia = :id;";
      $existe = Validar::existe($this->database, $idCompetencia, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("La competencia ingresada no existe", \InvalidArgumentException::class, 404);
      }
      $this->database->beginTransaction();
      $consulta = "DELETE FROM competencia WHERE id_competencia = :id_competencia";
      $response = $this->database->query($consulta, [':id_competencia' => $idCompetencia]);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al eliminar la competencia", \RuntimeException::class, 500);
      }
      $this->database->commit();
      $resultado["mensaje"] = "La competencia se eliminó exitosamente";
      return $resultado;
   }
   public function registrarResultados(array $datos): array
   {
      $keys = ['id_competencia', 'id_atleta', 'arranque', 'envion', 'medalla_arranque', 'medalla_envion', 'medalla_total'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $arrayFiltrado['id_competencia'] = Cipher::aesDecrypt($arrayFiltrado['id_competencia']);
      Validar::sanitizarYValidar($arrayFiltrado['id_competencia'], 'int');
      $arrayFiltrado['id_atleta'] = Cipher::aesDecrypt($arrayFiltrado['id_atleta']);
      Validar::validar("cedula", $arrayFiltrado['id_atleta']);
      Validar::sanitizarYValidar($arrayFiltrado['id_atleta'], 'int');
      Validar::sanitizarYValidar($arrayFiltrado['arranque'], 'float');
      Validar::sanitizarYValidar($arrayFiltrado['envion'], 'float');
      Validar::validar("medalla", $arrayFiltrado['medalla_arranque']);
      Validar::validar("medalla", $arrayFiltrado['medalla_envion']);
      Validar::validar("medalla", $arrayFiltrado['medalla_total']);
      return $this->_registrarResultados($arrayFiltrado);
   }
   private function _registrarResultados(array $datos): array
   {
      $consulta = "SELECT id_competencia FROM resultado_competencia WHERE id_competencia = " . $datos['id_competencia'] . " AND id_atleta = :id;";
      $existe = Validar::existe($this->database, $datos['id_atleta'], $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("El atleta o competencia no existe", \InvalidArgumentException::class, 404);
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
         ':total' => $datos['envion'] + $datos['arranque']
      ];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al registrar el resultado", \RuntimeException::class, 500);
      }
      $this->database->commit();
      $respuesta["mensaje"] = "El resultado se registró exitosamente";
      return $respuesta;
   }
   public function listadoAtletasDisponibles(array $datos): array
   {
      $keys = ['id'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $idCompetencia = Cipher::aesDecrypt($arrayFiltrado['id']);
      Validar::sanitizarYValidar($idCompetencia, 'int');
      return $this->_listadoAtletasDisponibles($idCompetencia);
   }
   private function _listadoAtletasDisponibles(int $idCompetencia): array
   {
      $existe = Validar::existe($this->database, $idCompetencia, "SELECT id_competencia FROM competencia WHERE id_competencia = :id");
      if (!$existe) {
         ExceptionHandler::throwException("La competencia ingresada no existe", \InvalidArgumentException::class, 404);
      }
      $consulta = "SELECT c.peso_minimo, c.peso_maximo, s.edad_minima, s.edad_maxima from competencia cm
                     JOIN categorias c ON cm.categoria = c.id_categoria
                     JOIN subs s ON cm.subs = s.id_sub
                     WHERE cm.id_competencia = :id_competencia;";
      $response = $this->database->query($consulta, [':id_competencia' => $idCompetencia], true);
      $consulta = "SELECT 
                     a.cedula AS id_atleta,
                     u.nombre,
                     u.apellido,
                     a.peso,
                     u.fecha_nacimiento
                  FROM atleta a
                  INNER JOIN {$_ENV['SECURE_DB']}.usuarios u ON u.cedula = a.cedula
                  LEFT JOIN resultado_competencia rc 
                     ON rc.id_atleta = a.cedula AND rc.id_competencia = :id_competencia
                  WHERE 
                     a.peso BETWEEN :peso_minimo AND :peso_maximo
                     AND TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE()) BETWEEN :edad_minima AND :edad_maxima
                     AND rc.id_atleta IS NULL";
      $valores = [
         ':peso_minimo' => $response['peso_minimo'],
         ':peso_maximo' => $response['peso_maximo'],
         ':edad_minima' => $response['edad_minima'],
         ':edad_maxima' => $response['edad_maxima'],
         ':id_competencia' => $idCompetencia
      ];
      $response = $this->database->query($consulta, $valores);
      $resultado['atletas'] = $response ?: [];
      if (!empty($resultado['atletas'])) {
         Cipher::encriptarCampoArray($resultado['atletas'], 'id_atleta');
      }
      return $resultado;
   }
}
