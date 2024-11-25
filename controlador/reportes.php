<?php
$pathToModelReportes = __DIR__ . '/../modelo/reportes.php';
$pathToModelPermisos = __DIR__ . '/../modelo/permisos.php';

if (file_exists($pathToModelReportes)) {
    require_once $pathToModelReportes;
} else {
    die("El archivo 'modelo/reportes.php' no se encuentra en la ruta especificada: " . $pathToModelReportes);
}

if (file_exists($pathToModelPermisos)) {
    require_once $pathToModelPermisos;
} else {
    die("El archivo 'modelo/permisos.php' no se encuentra en la ruta especificada: " . $pathToModelPermisos);
}

$o = new Reporte();
$permisos_o = new Permisos();
$permisos = $permisos_o->chequear_permisos();

if ($permisos["leer"] === 0) {
    header("Location: .");
    exit();
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
                'fechaInicioEventos' => $_POST['fechaInicioEventos'] ?? null,
                'fechaFinEventos' => $_POST['fechaFinEventos'] ?? null,
                'fechaInicioMensualidades' => $_POST['fechaInicioMensualidades'] ?? null,
                'fechaFinMensualidades' => $_POST['fechaFinMensualidades'] ?? null,
            ];
            $respuesta = $o->obtener_reportes($_POST['tipoReporte'], $filtros);
            header('Content-Type: application/json');
            echo json_encode($respuesta);
            exit();
        }

        if ($accion == 'obtener_resultados_competencias') {
            $filtros = [
                'fechaInicioEventos' => $_POST['fechaInicioEventos'] ?? null,
                'fechaFinEventos' => $_POST['fechaFinEventos'] ?? null,
            ];
            $respuesta = $o->obtener_resultados_competencias($filtros);
            header('Content-Type: application/json');
            echo json_encode($respuesta);
            exit();
        }
        if ($accion === 'obtenerDatosEstadisticos') {
            $tipo = $_POST['tipo'] ?? '';
            $respuesta = $o->obtenerDatosEstadisticos($tipo);
            header('Content-Type: application/json');
            echo json_encode($respuesta);
            exit();
        }
        if ($accion === 'obtenerProgresoAsistencias') {
            $respuesta = $o->obtenerProgresoAsistenciasMensuales();
            header('Content-Type: application/json');
            echo json_encode($respuesta);
            exit();
        }
        if ($accion === 'obtenerCumplimientoWADA') {
            $respuesta = $o->obtenerCumplimientoWADA();
            header('Content-Type: application/json');
            echo json_encode($respuesta);
            exit();
        }

        if ($accion === 'obtenerVencimientosWADA') {
            $respuesta = $o->obtenerVencimientosWADA();
            header('Content-Type: application/json');
            echo json_encode($respuesta);
            exit();
        }
    }
}

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    if (is_file("vista/" . $p . ".php")) {
        require_once("vista/" . $p . ".php");
    } else {
        require_once("comunes/404.php");
    }
}
