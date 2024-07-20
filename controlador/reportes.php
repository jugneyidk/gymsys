<?php
if (!is_file("modelo/reportes.php")) {
    echo json_encode(['error' => 'Falta definir la clase Reporte']);
    exit;
}
require_once("modelo/reportes.php");
require_once ("modelo/permisos.php");
$o = new Reporte();
$permisos_o = new Permisos();
    $permisos = $permisos_o->chequear_permisos();
    if ($permisos["leer"] === 0) {
        header("Location: .");
    }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['tipo'])) {
        $tipo = $_GET['tipo'];
        $resultados = [];

        switch ($tipo) {
            case 'edad_atletas':
                $resultados = $o->obtener_datos_edad_atletas();
                break;
            case 'genero':
                $resultados = $o->obtener_datos_genero();
                break;
            case 'asistencias':
                $resultados = $o->obtener_datos_asistencias();
                break;
            case 'wada':
                $resultados = $o->obtener_datos_wada();
                break;
            default:
                $resultados = ['error' => 'Tipo de gráfico no válido'];
                break;
        }

        header('Content-Type: application/json');
        echo json_encode($resultados);
        exit;
    }
}

if (is_file("vista/reportes.php")) {
    require_once("vista/reportes.php");
} else {
    echo "pagina en construccion";
}
?>
