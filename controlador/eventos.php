<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "Falta definir la clase " . $p;
    exit;
}
if (is_file("vista/" . $p . ".php")) {
    $o = new Eventos();
    $permisos_o = new Permisos();
    $permisos = $permisos_o->chequear_permisos();
    if ($permisos["leer"] === 0) {
        header("Location: .");
    }
    if (!empty($_POST)) { 
        $accion = $_POST['accion'];
        if ($accion == 'listado_eventos') {
            $respuesta = $o->listado_eventos();
            echo json_encode($respuesta);
        } elseif ($accion == 'incluir_evento') {
            $respuesta = $o->incluir_evento(
                $_POST['nombre'],
                $_POST['lugar_competencia'],
                $_POST['fecha_inicio'],
                $_POST['fecha_fin'],
                $_POST['categoria'],
                $_POST['subs'],
                $_POST['tipo_competencia']
            );
            echo json_encode($respuesta);
        } elseif ($accion == 'incluir_categoria') {
            $respuesta = $o->incluir_categoria(
                $_POST['nombre'],
                $_POST['pesoMinimo'],
                $_POST['pesoMaximo']
            );
            echo json_encode($respuesta);
        } elseif ($accion == 'incluir_subs') {
            $respuesta = $o->incluir_subs(
                $_POST['nombre'],
                $_POST['edadMinima'],
                $_POST['edadMaxima']
            );
            echo json_encode($respuesta);
        } elseif ($accion == 'incluir_tipo') {
            $respuesta = $o->incluir_tipo($_POST['nombre']);
            echo json_encode($respuesta);
        } elseif ($accion == 'listado_categoria') {
            $respuesta = $o->listado_categoria();
            echo json_encode($respuesta);
        } elseif ($accion == 'listado_subs') {
            $respuesta = $o->listado_subs();
            echo json_encode($respuesta);
        } elseif ($accion == 'listado_tipo') {
            $respuesta = $o->listado_tipo();
            echo json_encode($respuesta);
        } elseif ($accion == 'listado_atletas_inscritos') {
            $respuesta = $o->listado_atletas_inscritos($_POST['id_competencia']);
            echo json_encode($respuesta);
        }elseif ($accion == 'inscribir_atletas') {
            file_put_contents("debug.log", print_r($_POST, true), FILE_APPEND); 
        
            $id_competencia = $_POST['id_competencia'] ?? null;
            $atletas = $_POST['atletas'] ?? [];
        
            if (!$id_competencia || empty($atletas)) {
                echo json_encode(["ok" => false, "mensaje" => "Datos insuficientes para inscribir."]);
                exit;
            }
        
            $respuesta = $o->inscribir_atletas($id_competencia, $atletas);
            echo json_encode($respuesta);
        }elseif ($accion == 'registrar_resultados') {
            $respuesta = $o->registrar_resultados(
                $_POST['id_competencia'],
                $_POST['id_atleta'],
                $_POST['arranque'],
                $_POST['envion'],
                $_POST['medalla_arranque'],
                $_POST['medalla_envion'],
                $_POST['medalla_total'],
                $_POST['total']
            );
            echo json_encode($respuesta);
        }elseif ($accion == 'cerrar_evento') {
            $id_competencia = $_POST['id_competencia'];
            $respuesta = $o->cerrar_evento($id_competencia); // Agregar método cerrar_evento en el modelo
            echo json_encode($respuesta);
        }elseif ($accion == 'modificar_resultados') {
            $respuesta = $o->modificar_resultados(
                $_POST['id_competencia'],
                $_POST['id_atleta'],
                $_POST['arranque'],
                $_POST['envion'],
                $_POST['medalla_arranque'],
                $_POST['medalla_envion'],
                $_POST['medalla_total'],
                $_POST['total']
            );
            echo json_encode($respuesta);
        }elseif ($accion == 'listado_eventos_anteriores') {
            $respuesta = $o->listado_eventos_anteriores();
            echo json_encode($respuesta);
        }elseif ($accion == 'obtener_competencia') {
            $id_competencia = $_POST['id_competencia'];
            $respuesta = $o->obtenerCompetencia($id_competencia);
            echo json_encode($respuesta);
        } elseif ($accion == 'modificar_competencia') {
            $respuesta = $o->modificarCompetencia(
                $_POST['id_competencia'],
                $_POST['nombre'],
                $_POST['lugar_competencia'],
                $_POST['fecha_inicio'],
                $_POST['fecha_fin'],
                $_POST['categoria'],
                $_POST['subs'],
                $_POST['tipo_competencia']
            );
            echo json_encode($respuesta);
        }elseif ($accion == 'eliminar_evento') {
            $id_competencia = $_POST['id_competencia'];
            
            $respuesta = $o->eliminar_evento($id_competencia);
            echo json_encode($respuesta);
        } elseif ($accion == 'listado_atletas_disponibles') {
            $id_categoria = $_POST['id_categoria'];
            $id_sub = $_POST['id_sub'];
            $id_competencia = $_POST['id_competencia'];  
            $respuesta = $o->listado_atletas_disponibles($id_categoria, $id_sub, $id_competencia);
            echo json_encode($respuesta);
        }        
           
        
        exit;
    }
    require_once("vista/" . $p . ".php");
} else {
    echo "pagina en construccion";
}
?>
