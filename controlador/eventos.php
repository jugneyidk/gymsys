<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "Falta definir la clase " . $p;
    exit;
}
require_once ("modelo/permisos.php");
require_once("modelo/" . $p . ".php");
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
        } elseif ($accion == 'listado_atletas_disponibles') {
            $respuesta = $o->listado_atletas_disponibles($_POST['id_competencia']);
            echo json_encode($respuesta);
        } elseif ($accion == 'inscribir_atletas') {
            $respuesta = $o->inscribir_atletas(
                $_POST['id_competencia'],
                isset($_POST['atleta']) ? $_POST['atleta'] : []
            );
            echo json_encode($respuesta);
        } elseif ($accion == 'listado_atletas_inscritos') {
            $respuesta = $o->listado_atletas_inscritos($_POST['id_competencia']);
            echo json_encode($respuesta);
        }
        exit;
    }
    require_once("vista/" . $p . ".php");
} else {
    echo "pagina en construccion";
}
