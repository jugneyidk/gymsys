<?php

if (is_file("vista/" . $p . ".php")) {
    $permisos_o = new Permisos();
    $permisos = $permisos_o->chequear_permisos();
    if (!isset($_SESSION["rol"])) {
        header("Location: .");
    }
    if (!is_file("modelo/" . $p . ".php")) {
        echo "No existe el modelo.";
        exit;
    }
    $o = new Dashboard();  
    if (!empty($_POST)) {
        if ($_POST["accion"] == "estadisticas") {    
            $medallas_data = $o->obtener_medallas_por_mes();
            $progreso_data = $o->obtener_progreso_semanal();

            $response = [
                'labels_medallas' => $medallas_data['labels'],
                'medallas_por_mes' => $medallas_data['medallas'],
                'labels_progreso' => $progreso_data['labels'],
                'progreso_semanal' => $progreso_data['progreso']
            ];
            echo json_encode($response);
            exit;

        } else if ($_POST["accion"] == "incluir_wada") {
           
            $response = $o->incluir_wada($_POST["id_atleta"], $_POST["estado"], $_POST["ultima_actualizacion"]);
            echo json_encode($response);
            exit;

        } else if ($_POST["accion"] == "listadowada") {
          
            $response = $o->listado_wada();
            echo json_encode($response);
            exit;
        }
    }

    // Asignar los datos obtenidos a variables para la vista del dashboard
    $atletas = $o->total_atletas(); 
    $entrenadores = $o->total_entrenadores(); 
    $reportes = $o->total_reportes(); 
    $wadas_pendientes = $o->total_wadas_pendientes(); 
    $ultimos_atletas = $o->obtener_ultimos_atletas();
 
    $ultimas_acciones = $o->obtener_ultimas_acciones();
 
    require_once("vista/" . $p . ".php");
} else {
    require_once("comunes/404.php");
}

?>
