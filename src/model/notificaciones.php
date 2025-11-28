<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\JWTHelper;
use Gymsys\Utils\Validar;

class Notificaciones
{
   private Database $database;
   private const DIAS_RECORDATORIO_WADA = [30, 15, 7, 1];
   private const DIAS_VERIFICACION_MENSUALIDAD = 3;

   public function __construct(Database $database)
   {
      $this->database = $database;
   }
   public function obtenerNotificaciones(string $userId = ""): array
   {
      if (empty($userId)) {
         $decoded = JWTHelper::obtenerPayload();
         $userId = $decoded->sub;
         $userId = Cipher::aesDecrypt($userId);
      }
      Validar::validar("cedula", $userId);
      return $this->_obtenerNotificaciones($userId);
   }

   public function marcarLeida(array $datos): array
   {
      $arrayFiltrado = Validar::validarArray($datos, ['id']);
      $idNotificacion = Cipher::aesDecrypt($arrayFiltrado['id']);
      Validar::sanitizarYValidar($idNotificacion, "int");
      return $this->_marcarLeida($idNotificacion);
   }
   public function marcarTodoLeido(): array
   {
      $idUsuario = ID_USUARIO;
      if (empty($idUsuario)) {
         ExceptionHandler::throwException("No se ha iniciado sesión", \UnexpectedValueException::class, 403);
      }
      Validar::validar("cedula", $idUsuario);
      return $this->_marcarTodoLeido($idUsuario);
   }
   public function verTodas(array $datos): array
   {
      $keys = ['pagina'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $pagina = filter_var($arrayFiltrado['pagina'], FILTER_SANITIZE_NUMBER_INT);
      if (!is_numeric($pagina)) {
         ExceptionHandler::throwException("El valor de 'pagina' no es válido", \InvalidArgumentException::class);
      }
      return $this->_verTodas($pagina);
   }

   private function _marcarLeida(int $idNotificacion): array
   {

      $consulta = "SELECT id FROM {$_ENV['SECURE_DB']}.notificaciones WHERE id = :id;";
      $existe = Validar::existe($this->database, $idNotificacion, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("La notificacion no existe", \RuntimeException::class, 404);
      }
      $this->database->beginTransaction();
      $consulta = "UPDATE {$_ENV['SECURE_DB']}.notificaciones SET leida = 1
            WHERE id = :id_notificacion AND id_usuario = :id_usuario;";
      $valores = [':id_notificacion' => $idNotificacion, ':id_usuario' => ID_USUARIO];
      $this->database->query($consulta, $valores);
      $this->database->commit();
      $resultado["leido"] = true;
      return $resultado;
   }
   private function _marcarTodoLeido(int $idUsuario): array
   {
      $consulta = "SELECT id FROM {$_ENV['SECURE_DB']}.notificaciones WHERE id_usuario = :id;";
      $existe = Validar::existe($this->database, $idUsuario, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("No tiene notificaciones", \UnexpectedValueException::class, 404);
      }
      $this->database->beginTransaction();
      $consulta = "UPDATE {$_ENV['SECURE_DB']}.notificaciones SET leida = 1
                      WHERE id_usuario = :id_usuario;";
      $this->database->query($consulta, [':id_usuario' => $idUsuario]);
      $this->database->commit();
      $resultado["leidas"] = true;
      return $resultado;
   }

   private function _obtenerNotificaciones(string $idUsuario): array
   {
      $consulta = "SELECT id,
                        titulo,
                        mensaje,
                        leida,
                        objetivo,
                        fecha_creacion
                     FROM {$_ENV['SECURE_DB']}.notificaciones n
                     INNER JOIN {$_ENV['SECURE_DB']}.usuarios u ON n.id_usuario = u.cedula
                     WHERE n.id_usuario = :id_usuario 
                     ORDER BY id DESC
                     LIMIT 4;";
      $valores = [":id_usuario" => $idUsuario];
      $response = $this->database->query($consulta, $valores);
      $resultado["notificaciones"] = $response ?: [];
      Cipher::encriptarCampoArray($resultado["notificaciones"], "id", false);
      return $resultado;
   }
   private function _verTodas(int $pagina): array
   {
      $limite = 10;
      $diferencia = ($pagina - 1) * $limite;
      $consulta = "SELECT id,
                        titulo,
                        mensaje,
                        leida,
                        objetivo,
                        fecha_creacion
                     FROM {$_ENV['SECURE_DB']}.notificaciones n
                     WHERE n.id_usuario = :id_usuario 
                     ORDER BY id DESC
                     LIMIT $limite
                     OFFSET $diferencia;";
      $valores = [':id_usuario' => ID_USUARIO];
      $response = $this->database->query($consulta, $valores);
      $consultaTotal = "SELECT COUNT(*) AS total FROM {$_ENV['SECURE_DB']}.notificaciones WHERE id_usuario = :id_usuario";
      $notificacionesTotales = $this->database->query($consultaTotal, [':id_usuario' => ID_USUARIO], true);
      $verMas = ($pagina * $limite) < $notificacionesTotales['total'];
      $resultado["notificaciones"] = $response ?: [];
      $resultado["ver_mas"] = $verMas;
      if (!empty($resultado["notificaciones"])) {
         Cipher::encriptarCampoArray($resultado["notificaciones"], "id", false);
      }
      return $resultado;
   }

   private function notificacionExiste(string $mensaje, string $idUsuario, string $objetivo): bool
   {
      $consulta = "SELECT COUNT(*) as total 
                     FROM {$_ENV['SECURE_DB']}.notificaciones 
                     WHERE id_usuario = :id_usuario 
                     AND objetivo = :objetivo 
                     AND mensaje LIKE :mensaje";

      $valores = [
         ':id_usuario' => $idUsuario,
         ':objetivo' => $objetivo,
         ':mensaje' => $mensaje
      ];

      $resultado = $this->database->query($consulta, $valores, true);
      return $resultado['total'] > 0;
   }

   /**
    * Crea una nueva notificación en la base de datos.
    *
    * @param array $datos Un array asociativo con los datos de la notificación (id_usuario, titulo, mensaje, objetivo).
    * @return array Un array asociativo con los datos de la notificación creada, incluyendo su ID y fecha de creación.
    */
   private function crearNotificacion(array $datos): array
   {
      $consulta = "INSERT INTO {$_ENV['SECURE_DB']}.notificaciones(id_usuario, titulo, mensaje, objetivo)
                     VALUES (:id_usuario, :titulo, :mensaje, :objetivo)";
      $this->database->query($consulta, $datos);
      $id = $this->database->lastInsertId();

      $consulta = "SELECT id, titulo, mensaje, objetivo, fecha_creacion, id_usuario FROM {$_ENV['SECURE_DB']}.notificaciones WHERE id = :id";
      $notification = $this->database->query($consulta, [':id' => $id], true);

      return $notification;
   }

   /**
    * Crea notificaciones de WADA y mensualidades pendientes.
    *
    * @param Database $database Instancia de la base de datos.
    * @return array Un array con el estado de la operación, el número de notificaciones creadas
    *               y un array de notificaciones agrupadas por ID de usuario.
    */
   public static function crearNotificaciones(Database $database): array
   {
      $notificador = new self($database);
      $all_created_notifications = []; // Array para almacenar todas las notificaciones creadas

      try {
         $database->beginTransaction();

         $notificaciones_wada = $notificador->crearNotificacionesWada();
         $notificaciones_mensualidad = $notificador->crearNotificacionesMensualidad();

         $all_created_notifications = array_merge($notificaciones_wada, $notificaciones_mensualidad);

         $grouped_notifications = [];
         foreach ($all_created_notifications as $notification) {
            $userId = $notification['id_usuario'];
            if (!isset($grouped_notifications[$userId])) {
               $grouped_notifications[$userId] = [];
            }
            unset($notification['id_usuario']);
            $grouped_notifications[$userId][] = $notification;
         }

         $database->commit();

         return [
            "ok" => true,
            "notificaciones_creadas" => count($all_created_notifications),
            "grouped_notifications" => $grouped_notifications
         ];
      } catch (\Exception $e) {
         $database->rollBack();
         throw $e;
      }
   }

   /**
    * Crea notificaciones relacionadas con la fecha de vencimiento de la WADA de los atletas.
    *
    * @return array Un array de notificaciones creadas.
    */
   private function crearNotificacionesWada(): array
   {
      $consulta = "SELECT u.cedula, 
                           u.nombre, 
                           u.apellido, 
                           w.vencimiento,
                           a.entrenador
                    FROM atleta a
                    INNER JOIN {$_ENV['SECURE_DB']}.usuarios u ON a.cedula = u.cedula
                    INNER JOIN wada w ON w.id_atleta = u.cedula
                    WHERE w.vencimiento >= CURDATE() 
                    AND w.vencimiento <= DATE_ADD(CURDATE(), INTERVAL 31 DAY)
                    ORDER BY w.vencimiento DESC;";

      $response = $this->database->query($consulta);
      $created_notifications = []; // Array para almacenar las notificaciones creadas

      if (empty($response)) {
         return $created_notifications; // Retorna un array vacío si no hay atletas
      }

      foreach ($response as $atleta) {
         $fecha_vencimiento = new \DateTime($atleta['vencimiento']);
         $fecha_hoy = new \DateTime();
         $diferencia_dias = $fecha_hoy->diff($fecha_vencimiento)->days;

         // Notificaciones para días específicos antes del vencimiento
         if (in_array($diferencia_dias, self::DIAS_RECORDATORIO_WADA)) {
            $mensaje = sprintf(
               "La WADA del atleta %s %s se vencerá en %d día%s",
               $atleta['nombre'],
               $atleta['apellido'],
               $diferencia_dias,
               $diferencia_dias > 1 ? "s" : ""
            );

            if (!$this->notificacionExiste($mensaje, $atleta["entrenador"], "wada")) {
               $new_notification = $this->crearNotificacion([
                  ":id_usuario" => $atleta["entrenador"],
                  ":titulo" => "Una WADA vencerá pronto",
                  ":mensaje" => $mensaje,
                  ":objetivo" => "wada"
               ]);
               $created_notifications[] = $new_notification; // Almacena la notificación creada
            }
         }

         // Notificación el día del vencimiento
         if ($fecha_hoy->format('Y-m-d') === $fecha_vencimiento->format('Y-m-d')) {
            $mensaje = sprintf(
               "La WADA del atleta %s %s se venció",
               $atleta['nombre'],
               $atleta['apellido']
            );

            if (!$this->notificacionExiste($mensaje, $atleta["entrenador"], "wada")) {
               $new_notification = $this->crearNotificacion([
                  ":id_usuario" => $atleta["entrenador"],
                  ":titulo" => "La WADA ha vencido hoy",
                  ":mensaje" => $mensaje,
                  ":objetivo" => "wada"
               ]);
               $created_notifications[] = $new_notification; // Almacena la notificación creada
            }
         }
      }

      return $created_notifications; // Retorna el array de notificaciones creadas
   }

   /**
    * Crea notificaciones relacionadas con mensualidades pendientes.
    *
    * @return array Un array de notificaciones creadas.
    */
   private function crearNotificacionesMensualidad(): array
   {
      $consulta = "SELECT COUNT(u.cedula) AS cantidad_deudores
                    FROM atleta a
                    INNER JOIN {$_ENV['SECURE_DB']}.usuarios u ON a.cedula = u.cedula
                    INNER JOIN tipo_atleta t ON a.tipo_atleta = t.id_tipo_atleta
                    LEFT JOIN mensualidades m ON a.cedula = m.id_atleta 
                    AND m.fecha >= DATE_FORMAT(NOW(), '%Y-%m-01') 
                    AND m.fecha <= LAST_DAY(NOW())
                    WHERE m.id_atleta IS NULL";

      $resultado = $this->database->query($consulta, [], true);
      $cantidad_deudores = $resultado['cantidad_deudores'];

      if ($cantidad_deudores === 0) {
         return []; // Retorna un array vacío si no hay deudores
      }

      $mensaje = sprintf(
         "Hay %d atleta%s que debe%s la mensualidad este mes",
         $cantidad_deudores,
         $cantidad_deudores > 1 ? "s" : "",
         $cantidad_deudores > 1 ? "n" : ""
      );

      $consulta = "SELECT cedula FROM entrenador;";
      $entrenadores = $this->database->query($consulta);

      $created_notifications = []; // Array para almacenar las notificaciones creadas

      foreach ($entrenadores as $entrenador) {
         if (!$this->notificacionExiste($mensaje, $entrenador["cedula"], "mensualidad")) {
            $new_notification = $this->crearNotificacion([
               ":id_usuario" => $entrenador["cedula"],
               ":titulo" => "Atletas con mensualidad pendiente",
               ":mensaje" => $mensaje,
               ":objetivo" => "mensualidad"
            ]);
            $created_notifications[] = $new_notification; // Almacena la notificación creada
         }
      }

      return $created_notifications; // Retorna el array de notificaciones creadas
   }
}
