<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;

class EvaluacionesAtleta extends BaseController
{
   private Database $database;
   private object $model;
   private array $permisos;

   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("EvaluacionesAtleta");
      $this->model = new $modelClass((object) $this->database);
      // Los permisos se cargan del módulo Atletas porque las evaluaciones son parte de su gestión
      $this->permisos = $this->obtenerPermisos("Atletas", $this->database);
   }

   /**
    * Verifica si un usuario es Administrador o Superusuario
    * 
    * @return bool True si es admin/superusuario
    */
   private function esAdministrador(): bool
   {
      $roles = $_SESSION['roles'] ?? [];
      return in_array('Administrador', $roles) || in_array('Superusuario', $roles);
   }

   /**
    * Verifica si han pasado más de 24 horas desde la creación de un test
    * 
    * @param string $fechaCreacion Fecha de creación del test
    * @return bool True si han pasado más de 24 horas
    */
   private function hanPasado24Horas(string $fechaCreacion): bool
   {
      $fecha = new \DateTime($fechaCreacion);
      $ahora = new \DateTime();
      $diferencia = $ahora->getTimestamp() - $fecha->getTimestamp();
      return $diferencia > 86400; // 86400 segundos = 24 horas
   }

   /**
    * Registra un nuevo test postural para un atleta
    * 
    * @param array $datos Datos del test postural
    * @return array Respuesta del modelo
    */
   public function registrarTestPostural(array $datos): array
   {
      $this->validarPermisos($this->permisos, "crear");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      
      return $this->model->crearTestPostural($datos);
   }

   /**
    * Registra un nuevo test FMS para un atleta
    * 
    * @param array $datos Datos del test FMS
    * @return array Respuesta del modelo
    */
   public function registrarTestFms(array $datos): array
   {
      $this->validarPermisos($this->permisos, "crear");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      
      return $this->model->crearTestFms($datos);
   }

   /**
    * Registra una nueva lesión de un atleta
    * 
    * @param array $datos Datos de la lesión
    * @return array Respuesta del modelo
    */
   public function registrarLesion(array $datos): array
   {
      $this->validarPermisos($this->permisos, "crear");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      
      return $this->model->crearLesion($datos);
   }

   /**
    * Obtiene la tarjeta completa del atleta con todas sus evaluaciones y lesiones
    * 
    * @param array $datos Datos con el ID del atleta
    * @return array Tarjeta del atleta con historial completo
    */
   public function obtenerTarjetaAtleta(array $datos): array
   {
      $this->validarPermisos($this->permisos, "leer");
      $this->validarMetodoRequest("GET");
      
      return $this->model->obtenerTarjetaAtleta($datos);
   }

   /**
    * Actualiza un test postural existente
    * 
    * @param array $datos Datos del test postural a actualizar
    * @return array Respuesta del modelo
    */
   public function actualizarTestPostural(array $datos): array
   {
      $this->validarPermisos($this->permisos, "actualizar");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      
      if (!$this->esAdministrador()) {
         $testActual = $this->model->obtenerTestPostural(['id' => $datos['id_test_postural']]);
         if ($testActual['exito'] && isset($testActual['datos']['fecha_creacion'])) {
            if ($this->hanPasado24Horas($testActual['datos']['fecha_creacion'])) {
               return [
                  'exito' => false,
                  'mensaje' => 'No se puede editar este test después de 24 horas. Solo un administrador puede hacerlo.'
               ];
            }
         }
      }
      
      return $this->model->actualizarTestPostural($datos);
   }

   /**
    * Elimina un test postural
    * 
    * @param array $datos ID del test postural a eliminar
    * @return array Respuesta del modelo
    */
   public function eliminarTestPostural(array $datos): array
   {
      $this->validarPermisos($this->permisos, "eliminar");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      
      if (!$this->esAdministrador()) {
         $testActual = $this->model->obtenerTestPostural(['id' => $datos['id_test_postural']]);
         if ($testActual['exito'] && isset($testActual['datos']['fecha_creacion'])) {
            if ($this->hanPasado24Horas($testActual['datos']['fecha_creacion'])) {
               return [
                  'exito' => false,
                  'mensaje' => 'No se puede eliminar este test después de 24 horas. Solo un administrador puede hacerlo.'
               ];
            }
         }
      }
      
      return $this->model->eliminarTestPostural($datos);
   }

   /**
    * Lista todos los tests posturales de un atleta
    * 
    * @param array $datos ID del atleta
    * @return array Lista de tests posturales
    */
   public function listarTestsPosturalesPorAtleta(array $datos): array
   {
      $this->validarPermisos($this->permisos, "leer");
      $this->validarMetodoRequest("GET");
      
      return $this->model->listarTestsPosturalesPorAtleta($datos);
   }

   /**
    * Actualiza un test FMS existente
    * 
    * @param array $datos Datos del test FMS a actualizar
    * @return array Respuesta del modelo
    */
   public function actualizarTestFms(array $datos): array
   {
      $this->validarPermisos($this->permisos, "actualizar");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      
      if (!$this->esAdministrador()) {
         $testActual = $this->model->obtenerTestFms(['id' => $datos['id_test_fms']]);
         if ($testActual['exito'] && isset($testActual['datos']['fecha_creacion'])) {
            if ($this->hanPasado24Horas($testActual['datos']['fecha_creacion'])) {
               return [
                  'exito' => false,
                  'mensaje' => 'No se puede editar este test después de 24 horas. Solo un administrador puede hacerlo.'
               ];
            }
         }
      }
      
      return $this->model->actualizarTestFms($datos);
   }

   /**
    * Elimina un test FMS
    * 
    * @param array $datos ID del test FMS a eliminar
    * @return array Respuesta del modelo
    */
   public function eliminarTestFms(array $datos): array
   {
      $this->validarPermisos($this->permisos, "eliminar");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      
      if (!$this->esAdministrador()) {
         $testActual = $this->model->obtenerTestFms(['id' => $datos['id_test_fms']]);
         if ($testActual['exito'] && isset($testActual['datos']['fecha_creacion'])) {
            if ($this->hanPasado24Horas($testActual['datos']['fecha_creacion'])) {
               return [
                  'exito' => false,
                  'mensaje' => 'No se puede eliminar este test después de 24 horas. Solo un administrador puede hacerlo.'
               ];
            }
         }
      }
      
      return $this->model->eliminarTestFms($datos);
   }

   /**
    * Lista todos los tests FMS de un atleta
    * 
    * @param array $datos ID del atleta
    * @return array Lista de tests FMS
    */
   public function listarTestsFmsPorAtleta(array $datos): array
   {
      $this->validarPermisos($this->permisos, "leer");
      $this->validarMetodoRequest("GET");
      
      return $this->model->listarTestsFmsPorAtleta($datos);
   }

   // ========================================
   // CRUD COMPLETO - LESIONES
   // ========================================

   /**
    * Actualiza una lesión existente
    * 
    * @param array $datos Datos de la lesión a actualizar
    * @return array Respuesta del modelo
    */
   public function actualizarLesion(array $datos): array
   {
      $this->validarPermisos($this->permisos, "actualizar");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      
      return $this->model->actualizarLesion($datos);
   }

   /**
    * Elimina una lesión
    * 
    * @param array $datos ID de la lesión a eliminar
    * @return array Respuesta del modelo
    */
   public function eliminarLesion(array $datos): array
   {
      $this->validarPermisos($this->permisos, "eliminar");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      
      return $this->model->eliminarLesion($datos);
   }

   /**
    * Lista todas las lesiones de un atleta
    * 
    * @param array $datos ID del atleta
    * @return array Lista de lesiones
    */
   public function listarLesionesPorAtleta(array $datos): array
   {
      $this->validarPermisos($this->permisos, "leer");
      $this->validarMetodoRequest("GET");
      
      return $this->model->listarLesionesPorAtleta($datos);
   }

   /**
    * Obtiene un test postural específico
    * 
    * @param array $datos ID del test postural
    * @return array Datos del test postural
    */
   public function obtenerTestPostural(array $datos): array
   {
      $this->validarPermisos($this->permisos, "leer");
      $this->validarMetodoRequest("GET");
      
      return $this->model->obtenerTestPostural($datos);
   }

   /**
    * Obtiene un test FMS específico
    * 
    * @param array $datos ID del test FMS
    * @return array Datos del test FMS
    */
   public function obtenerTestFms(array $datos): array
   {
      $this->validarPermisos($this->permisos, "leer");
      $this->validarMetodoRequest("GET");
      
      return $this->model->obtenerTestFms($datos);
   }

   /**
    * Obtiene una lesión específica
    * 
    * @param array $datos ID de la lesión
    * @return array Datos de la lesión
    */
   public function obtenerLesion(array $datos): array
   {
      $this->validarPermisos($this->permisos, "leer");
      $this->validarMetodoRequest("GET");
      
      return $this->model->obtenerLesion($datos);
   }
}
