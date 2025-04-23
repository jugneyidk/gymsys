<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\Validar;

class Notificaciones
{
   private Database $database;

   public function __construct(Database $database)
   {
      $this->database = $database;
   }
   public function obtenerNotificaciones()
   {
      Validar::validar("cedula", $_SESSION['id_usuario']);
      return $this->_obtenerNotificaciones();
   }

   public function marcarLeida(array $datos)
   {
      $arrayFiltrado = Validar::validarArray($datos, ['id']);
      $idNotificacion = filter_var($arrayFiltrado['id'], FILTER_SANITIZE_NUMBER_INT);
      if (!is_numeric($idNotificacion)) {
         ExceptionHandler::throwException("El valor de 'id' no es válido", 400, \InvalidArgumentException::class);
      }
      return $this->_marcarLeida($idNotificacion);
   }
   public function marcarTodoLeido(): array
   {
      $idUsuario = $_SESSION['id_usuario'];
      Validar::validar("cedula", $idUsuario);
      return $this->_marcarTodoLeido($idUsuario);
   }
   public function verTodas(array $datos)
   {
      $keys = ['pagina'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $pagina = filter_var($arrayFiltrado['pagina'], FILTER_SANITIZE_NUMBER_INT);
      if (!is_numeric($pagina)) {
         ExceptionHandler::throwException("El valor de 'pagina' no es válido", 400, \InvalidArgumentException::class);
      }
      return $this->_verTodas($pagina);
   }

   private function _marcarLeida(int $idNotificacion)
   {

      $consulta = "SELECT id FROM notificaciones WHERE id = :id;";
      $existe = Validar::existe($this->database, $idNotificacion, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("La notificacion no existe", 404, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "UPDATE notificaciones SET leida = 1
            WHERE id = :id_notificacion AND id_usuario = :id_usuario;";
      $valores = [':id_notificacion' => $idNotificacion, ':id_usuario' => $_SESSION['id_usuario']];
      $this->database->query($consulta, $valores);
      $this->database->commit();
      $resultado["leido"] = true;
      return $resultado;
   }
   private function _marcarTodoLeido(int $idUsuario)
   {
      $consulta = "SELECT id FROM notificaciones WHERE id_usuario = :id;";
      $existe = Validar::existe($this->database, $idUsuario, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("No tiene notificaciones", 404, \UnexpectedValueException::class);
      }
      $this->database->beginTransaction();
      $consulta = "UPDATE notificaciones SET leida = 1
                      WHERE id_usuario = :id_usuario;";
      $this->database->query($consulta, [':id_usuario' => $idUsuario]);
      $this->database->commit();
      $resultado["leidas"] = true;
      return $resultado;
   }

   private function _obtenerNotificaciones(): array
   {
      $consulta = "SELECT id,
                        titulo,
                        mensaje,
                        leida,
                        objetivo,
                        fecha_creacion
                     FROM notificaciones n
                     INNER JOIN usuarios u ON n.id_usuario = u.cedula
                     WHERE n.id_usuario = :id_usuario 
                     ORDER BY id DESC
                     LIMIT 4;";
      $valores = [":id_usuario" => $_SESSION['id_usuario']];
      $response = $this->database->query($consulta, $valores);
      $resultado["notificaciones"] = $response;
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
                     FROM notificaciones n
                     WHERE n.id_usuario = :id_usuario 
                     ORDER BY id DESC
                     LIMIT $limite
                     OFFSET $diferencia;";
      $valores = [':id_usuario' => $_SESSION['id_usuario']];
      $response = $this->database->query($consulta, $valores);
      $consultaTotal = "SELECT COUNT(*) AS total FROM notificaciones WHERE id_usuario = :id_usuario";
      $notificacionesTotales = $this->database->query($consultaTotal, [':id_usuario' => $_SESSION['id_usuario']], true);
      $verMas = ($pagina * $limite) < $notificacionesTotales['total'];
      $resultado["notificaciones"] = $response;
      $resultado["ver_mas"] = $verMas;
      return $resultado;
   }

   public static function crearNotificaciones(Database $database)
   {
      $notificaciones_creadas = 0;
      $notificaciones_wada = self::crearNotificacionesWada($database);
      $notificaciones_mensualidad = self::crearNotificacionesMensualidad($database);
      if (is_int($notificaciones_wada)) {
         $notificaciones_creadas += $notificaciones_wada;
      }
      if (is_int($notificaciones_mensualidad)) {
         $notificaciones_creadas += $notificaciones_mensualidad;
      }
      $resultado["ok"] = true;
      $resultado["notificaciones_creadas"] = $notificaciones_creadas ?? 0;
      return $resultado;
   }
   private static function crearNotificacionesWada(Database $database)
   {
      // Consulta para obtener los atletas cuyo vencimiento de WADA está entre hoy y los próximos 30 días
      $consulta = "SELECT u.cedula, 
                                u.nombre, 
                                u.apellido, 
                                w.vencimiento,
                                a.entrenador
                        FROM atleta a
                        INNER JOIN usuarios u ON a.cedula = u.cedula
                        INNER JOIN wada w ON w.id_atleta = u.cedula
                        WHERE w.vencimiento >= CURDATE() 
                        AND w.vencimiento <= DATE_ADD(CURDATE(), INTERVAL 31 DAY)
                        ORDER BY w.vencimiento DESC;";

      $response = $database->query($consulta);
      $notificaciones_creadas = 0;
      if (count($response) > 0) {
         $database->beginTransaction();
         $notificaciones_creadas = 0;
         foreach ($response as $valor) {
            // Calcula la diferencia en días entre el vencimiento y la fecha actual
            $fecha_vencimiento = new \DateTime($valor['vencimiento']);
            $fecha_hoy = new \DateTime();
            $diferencia_dias = $fecha_hoy->diff($fecha_vencimiento)->days;
            // Verificar si la diferencia es 30, 15, 7 o 1 dias
            if (in_array($diferencia_dias, [30, 15, 7, 1])) {
               // Verifica si ya existe la notificacion para este dia
               $consultaExistente = "SELECT COUNT(*) as total
                                              FROM notificaciones 
                                              WHERE id_usuario = :id_usuario 
                                              AND objetivo = 'wada' 
                                              AND mensaje LIKE :mensaje";
               $mensaje = "La WADA del atleta {$valor['nombre']} {$valor['apellido']} se vencerá en {$diferencia_dias} día" . ($diferencia_dias > 1 ? "s" : "");
               $existe = $database->query($consultaExistente, [
                  ':id_usuario' => $valor["entrenador"],
                  ':mensaje' => $mensaje
               ], true);
               if ($existe['total'] == 0) {
                  // Inserta la notificación solo si la WADA vence en 30, 15, 7 o 1 día
                  $consulta = "INSERT INTO notificaciones(id_usuario, titulo, mensaje, objetivo)
                                        VALUES (:id_usuario, :titulo, :mensaje, :objetivo)";
                  $mensaje = "La WADA del atleta {$valor['nombre']} {$valor['apellido']} se vencerá en {$diferencia_dias} día" . ($diferencia_dias > 1 ? "s" : "");
                  $valores = [
                     ":id_usuario" => $valor["entrenador"],
                     ":titulo" => "Una WADA vencerá pronto",
                     ":mensaje" => $mensaje,
                     ":objetivo" => "wada",
                  ];
                  $response = $database->query($consulta, $valores);
                  $notificaciones_creadas++;
               }
            }
            if ($fecha_hoy->format('Y-m-d') === $fecha_vencimiento->format('Y-m-d')) {
               // Verifica si ya existe la notificacion de vencida
               $consultaExistente = "SELECT COUNT(*) as total
                                              FROM notificaciones 
                                              WHERE id_usuario = :id_usuario 
                                              AND objetivo = 'wada' 
                                              AND mensaje LIKE :mensaje";
               $mensaje = "La WADA del atleta {$valor['nombre']} {$valor['apellido']} se venció";
               $valores = [
                  ':id_usuario' => $valor["entrenador"],
                  ':mensaje' => $mensaje
               ];
               $existe = $database->query($consultaExistente, $valores, true);
               if ($existe['total'] == 0) {
                  $consulta = "INSERT INTO notificaciones(id_usuario, titulo, mensaje, objetivo)
                                VALUES (:id_usuario, :titulo, :mensaje, :objetivo)";
                  $valores = [
                     ":id_usuario" => $valor["entrenador"],
                     ":titulo" => "La WADA ha vencido hoy",
                     ":mensaje" => $mensaje,
                     ":objetivo" => "wada",
                  ];
                  $existe = $database->query($consulta, $valores);
                  $notificaciones_creadas++;
               }
            }
         }
      }
      $database->commit();
      return $notificaciones_creadas;
   }
   private static function crearNotificacionesMensualidad(Database $database)
   {
      $notificaciones_creadas = 0;
      // Consulta para obtener la cantidad de atletas que deben la mensualidad en el mes actual
      $consulta = "SELECT COUNT(u.cedula) AS cantidad_deudores
                    FROM atleta a
                    INNER JOIN usuarios u ON a.cedula = u.cedula
                    INNER JOIN tipo_atleta t ON a.tipo_atleta = t.id_tipo_atleta
                    LEFT JOIN mensualidades m ON a.cedula = m.id_atleta 
                    AND m.fecha >= DATE_FORMAT(NOW(), '%Y-%m-01') 
                    AND m.fecha <= LAST_DAY(NOW())
                    WHERE m.id_atleta IS NULL";
      $respuesta = $this->conexion->prepare($consulta);
      $respuesta->execute();
      $cantidad_deudores = $respuesta->fetchColumn();
      if ($cantidad_deudores > 0) {
         $this->conexion->beginTransaction();
         $mensaje = "Hay {$cantidad_deudores} atleta" . ($cantidad_deudores > 1 ? "s" : "") . " que debe" . ($cantidad_deudores > 1 ? "n" : "") . " la mensualidad este mes";
         // Verificar si ya existe una notificación reciente (dentro de los últimos 3 días)
         $consulta_existente = "SELECT COUNT(*) 
                                   FROM notificaciones 
                                   WHERE objetivo = 'mensualidad' 
                                   AND mensaje LIKE :mensaje 
                                   AND fecha_creacion >= CURDATE() - INTERVAL 3 DAY";

         $stmt_existente = $this->conexion->prepare($consulta_existente);
         $stmt_existente->execute([
            ':mensaje' => $mensaje
         ]);
         $existe = $stmt_existente->fetchColumn();
         if ($existe == 0) {
            $entrenadores = "SELECT cedula
                    FROM entrenador WHERE 1;";
            $lista_entrenadores = $this->conexion->prepare($entrenadores);
            $lista_entrenadores->execute();
            $lista_entrenadores = $lista_entrenadores->fetchAll(PDO::FETCH_ASSOC);
            print_r($lista_entrenadores);
            foreach ($lista_entrenadores as $entrenador) {
               $consulta = "INSERT INTO notificaciones(id_usuario, titulo, mensaje, objetivo)
                                VALUES (:id_usuario, :titulo, :mensaje, :objetivo)";
               $stmt = $this->conexion->prepare($consulta);
               $valores = [
                  ":id_usuario" => $entrenador["cedula"],
                  ":titulo" => "Atletas con mensualidad pendiente",
                  ":mensaje" => $mensaje,
                  ":objetivo" => "mensualidad",
               ];
               $stmt->execute($valores);
               $stmt->closeCursor();
               $notificaciones_creadas = 1;
            }
         }
         $this->conexion->commit();
      }
      return $notificaciones_creadas;
   }
}
