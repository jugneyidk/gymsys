<?php
require_once "modelo/datos.php";

try {
    $datos = new datos();
    $con = $datos->conecta();
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
        foreach ($respuesta as $valor) {
            $consulta = "INSERT INTO notificaciones(id_usuario,titulo,mensaje,objetivo)
            VALUES (:id_usuario,:titulo, :mensaje,:objetivo)";
            $stmt = $con->prepare($consulta);
            $valores = [
                ":id_usuario" => $valor["entrenador"],
                ":titulo" => "Una WADA vencerá pronto",
                ":mensaje" => "La WADA del atleta {$valor['nombre']} {$valor['apellido']} se vencerá el {$valor['vencimiento']}",
                ":objetivo" => "wada",
            ];
            $stmt->execute($valores);
            $stmt->closeCursor();
        }
        $con->commit();
    }
    $resultado["ok"] = true;
} catch (PDOException $e) {
    $con->rollBack();
    $resultado["ok"] = false;
    $resultado["mensaje"] = $e->getMessage();
}
$datos->desconecta();
echo json_encode($resultado);