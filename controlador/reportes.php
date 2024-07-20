<?php
if (!is_file("modelo/reportes.php")) {
    echo "Falta definir la clase Reporte";
    exit;
}
require_once("modelo/reportes.php");

if (is_file("vista/reportes.php")) {
    $o = new Reporte();
    if (!empty($_POST)) {
        $accion = $_POST['accion'];
        if ($accion == 'obtener_reportes') {
            $filtros = [
                'edadMin' => $_POST['edadMin'] ?? null,
                'edadMax' => $_POST['edadMax'] ?? null,
                'genero' => $_POST['genero'] ?? null,
                'tipoAtleta' => $_POST['tipoAtleta'] ?? null,
                'pesoMin' => $_POST['pesoMin'] ?? null,
                'pesoMax' => $_POST['pesoMax'] ?? null,
                'edadMinEntrenador' => $_POST['edadMinEntrenador'] ?? null,
                'edadMaxEntrenador' => $_POST['edadMaxEntrenador'] ?? null,
                'gradoInstruccion' => $_POST['gradoInstruccion'] ?? null,
                'periodoMensualidades' => $_POST['periodoMensualidades'] ?? null,
                'periodoWada' => $_POST['periodoWada'] ?? null,
                'periodoEventos' => $_POST['periodoEventos'] ?? null,
                'periodoAsistencias' => $_POST['periodoAsistencias'] ?? null,
            ];
            $respuesta = $o->obtener_reportes($_POST['tipoReporte'], $filtros);
            echo json_encode($respuesta);
        }
        exit;
    }
    require_once("vista/reportes.php");
} else {
    echo "pagina en construccion";
}
?>
