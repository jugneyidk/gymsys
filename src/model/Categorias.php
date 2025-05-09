<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\Validar;

class Categorias
{

   private Database $database;
   public function __construct(Database $database)
   {
      $this->database = $database;
   }
   public function listadoCategorias(): array
   {
      return $this->_listadoCategorias();
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
   public function eliminarCategoria(array $datos): array
   {
      $keys = ['id_categoria'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::sanitizarYValidar($arrayFiltrado['id_categoria'], 'int');
      return $this->_eliminarCategoria($arrayFiltrado['id_categoria']);
   }
   private function _listadoCategorias(): array
   {
      $consulta = "SELECT * FROM categorias";
      $response = $this->database->query($consulta);
      $resultado["categorias"] = $response ?: [];
      return $resultado;
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
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al modificar la categoria", 500, \Exception::class);
      }
      $this->database->commit();
      $respuesta["mensaje"] = "La categoría se modificó exitosamente";
      return $respuesta;
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
}
