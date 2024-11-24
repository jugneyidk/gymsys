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

        switch ($accion) {
            case 'listado_eventos':
                $respuesta = $o->listado_eventos();
                echo json_encode($respuesta);
                break;

            case 'incluir_evento':
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
                break;

            case 'incluir_categoria':
                $respuesta = $o->incluir_categoria(
                    $_POST['nombre'],
                    $_POST['pesoMinimo'],
                    $_POST['pesoMaximo']
                );
                echo json_encode($respuesta);
                break;

            case 'incluir_subs':
                $respuesta = $o->incluir_subs(
                    $_POST['nombre'],
                    $_POST['edadMinima'],
                    $_POST['edadMaxima']
                );
                echo json_encode($respuesta);
                break;

            case 'incluir_tipo':
                $respuesta = $o->incluir_tipo($_POST['nombre']);
                echo json_encode($respuesta);
                break;

            case 'listado_categoria':
                $respuesta = $o->listado_categoria();
                echo json_encode($respuesta);
                break;

            case 'listado_subs':
                $respuesta = $o->listado_subs();
                echo json_encode($respuesta);
                break;

            case 'listado_tipo':
                $respuesta = $o->listado_tipo();
                echo json_encode($respuesta);
                break;

            case 'listado_atletas_inscritos':
                $respuesta = $o->listado_atletas_inscritos($_POST['id_competencia']);
                echo json_encode($respuesta);
                break;

            case 'inscribir_atletas':
                file_put_contents("debug.log", print_r($_POST, true), FILE_APPEND);
                $id_competencia = $_POST['id_competencia'] ?? null;
                $atletas = $_POST['atletas'] ?? [];
                if (!$id_competencia || empty($atletas)) {
                    echo json_encode(["ok" => false, "mensaje" => "Datos insuficientes para inscribir."]);
                    exit;
                }
                $respuesta = $o->inscribir_atletas($id_competencia, $atletas);
                echo json_encode($respuesta);
                break;

            case 'registrar_resultados':
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
                break;

            case 'cerrar_evento':
                $id_competencia = $_POST['id_competencia'];
                $respuesta = $o->cerrar_evento($id_competencia);
                echo json_encode($respuesta);
                break;

            case 'modificar_resultados':
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
                break;

            case 'listado_eventos_anteriores':
                $respuesta = $o->listado_eventos_anteriores();
                echo json_encode($respuesta);
                break;

            case 'obtener_competencia':
                $id_competencia = $_POST['id_competencia'];
                $respuesta = $o->obtenerCompetencia($id_competencia);
                echo json_encode($respuesta);
                break;

            case 'modificar_competencia':
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
                break;

            case 'eliminar_evento':
                $id_competencia = $_POST['id_competencia'];
                $respuesta = $o->eliminar_evento($id_competencia);
                echo json_encode($respuesta);
                break;

            case 'listado_atletas_disponibles':
                $id_categoria = $_POST['id_categoria'];
                $id_sub = $_POST['id_sub'];
                $id_competencia = $_POST['id_competencia'];
                $respuesta = $o->listado_atletas_disponibles($id_categoria, $id_sub, $id_competencia);
                echo json_encode($respuesta);
                break;

            case 'eliminar_tipo':
                $id_tipo = $_POST['id_tipo'];
                $verificacion = $o->verificar_relacion_tipo($id_tipo);
                if (!$verificacion["ok"]) {
                    echo json_encode(["ok" => false, "mensaje" => $verificacion["mensaje"]]);
                    exit;
                }
                if ($verificacion["existe"]) {
                    echo json_encode(["ok" => false, "mensaje" => "No se puede eliminar este tipo porque está relacionado con competencias existentes."]);
                    exit;
                }
                $respuesta = $o->eliminar_tipo($id_tipo);
                echo json_encode($respuesta);
                break;

            case 'modificar_tipo':
                $respuesta = $o->modificar_tipo($_POST['id_tipo'], $_POST['nombre']);
                echo json_encode($respuesta);
                break;

            case 'eliminar_sub':
                $respuesta = $o->eliminar_sub($_POST['id_sub']);
                echo json_encode($respuesta);
                break;

            case 'modificar_sub':
                $respuesta = $o->modificar_sub(
                    $_POST['id_sub'],
                    $_POST['nombre'],
                    $_POST['edadMinima'],
                    $_POST['edadMaxima']
                );
                echo json_encode($respuesta);
                break;

            case 'modificar_categoria':
                $respuesta = $o->modificar_categoria(
                    $_POST['id_categoria'],
                    $_POST['nombre'],
                    $_POST['pesoMinimo'],
                    $_POST['pesoMaximo']
                );
                echo json_encode($respuesta);
                break;

            case 'eliminar_categoria':
                $respuesta = $o->eliminar_categoria($_POST['id_categoria']);
                echo json_encode($respuesta);
                break;
            case 'modificar_resultados':
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
                break;

        }

        exit;
    }
    require_once("vista/" . $p . ".php");
} else {
    echo "pagina en construccion";
}
?>