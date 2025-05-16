<?php
require_once "modelo/datos.php";

use Gymsys\Core\Database;
use Gymsys\Model\Notificaciones;

try {
   $database = new Database();
   Notificaciones::crearNotificaciones($database);
   // Consulta para obtener los atletas cuyo vencimiento de WADA está entre hoy y los próximos 30 días
   $consulta = "SELECT u.cedula, 
                        u.nombre, 
                        u.apellido, 
                        w.vencimiento,
                        a.entrenador
                FROM atleta a
                INNER JOIN usuarios u ON a.cedula = u.cedula
                INNER JOIN wada w ON w.id_atleta = u.cedula
                WHERE w.vencimiento > CURDATE() 
                AND w.vencimiento <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
                ORDER BY w.vencimiento DESC;";

   $respuesta = $con->prepare($consulta);
   $respuesta->execute();
   $respuesta = $respuesta->fetchAll(PDO::FETCH_ASSOC);

   if (count($respuesta) > 0) {
      $con->beginTransaction();
      $notificaciones_creadas = 0;
      foreach ($respuesta as $valor) {
         // Calcula la diferencia en días entre el vencimiento y la fecha actual
         $fecha_vencimiento = new DateTime($valor['vencimiento']);
         $fecha_hoy = new DateTime();
         $diferencia_dias = $fecha_hoy->diff($fecha_vencimiento)->days;
         // Verificar si la diferencia es 30, 15, 7 o 1 dias
         if (in_array($diferencia_dias, [30, 15, 7, 1, 0])) {
            // Verifica si ya existe la notificacion para este dia
            $consulta_existente = "SELECT COUNT(*) 
                                      FROM notificaciones 
                                      WHERE id_usuario = :id_usuario 
                                      AND objetivo = 'wada' 
                                      AND mensaje LIKE :mensaje";
            $stmt_existente = $con->prepare($consulta_existente);
            if ($diferencia_dias == 0) {
               $mensaje = "La WADA del atleta {$valor['nombre']} {$valor['apellido']} se venció";
            } else {
               $mensaje = "La WADA del atleta {$valor['nombre']} {$valor['apellido']} se vencerá en {$diferencia_dias} día" . ($diferencia_dias > 1 ? "s" : "");
            }
            $stmt_existente->execute([
               ':id_usuario' => $valor["entrenador"],
               ':mensaje' => $mensaje
            ]);
            $existe = $stmt_existente->fetchColumn();
            if ($existe == 0) {
               // Inserta la notificación solo si la WADA vence en 30, 15, 7 o 1 día
               $consulta = "INSERT INTO notificaciones(id_usuario, titulo, mensaje, objetivo)
                                VALUES (:id_usuario, :titulo, :mensaje, :objetivo)";
               $stmt = $con->prepare($consulta);
               $mensaje = "La WADA del atleta {$valor['nombre']} {$valor['apellido']} se vencerá en {$diferencia_dias} día" . ($diferencia_dias > 1 ? "s" : "");
               $valores = [
                  ":id_usuario" => $valor["entrenador"],
                  ":titulo" => "Una WADA vencerá pronto",
                  ":mensaje" => $mensaje,
                  ":objetivo" => "wada",
               ];
               $stmt->execute($valores);
               $stmt->closeCursor();
               $notificaciones_creadas++;
            }
         }
      }
      $con->commit();
   }
   $resultado["ok"] = true;
   $resultado["notificaciones_creadas"] = $notificaciones_creadas ?? 0;
} catch (PDOException $e) {
   $con->rollBack();
   $resultado["ok"] = false;
   $resultado["mensaje"] = $e->getMessage();
}

$database->desconecta();
echo json_encode($resultado);
