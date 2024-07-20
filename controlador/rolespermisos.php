<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "Falta definir la clase " . $p;
    exit;
}
require_once ("modelo/" . $p . ".php");
if (is_file("vista/" . $p . ".php")) {
    $o = new Roles();
    if (!empty($_POST)) {
        $accion = $_POST['accion'];
        if ($accion == 'listado_roles') {
            $respuesta = $o->listado_roles();
            echo json_encode($respuesta);
        } elseif ($accion == 'incluir') {
            $valores = [
                "centrenadores" => isset($_POST['centrenadores']) ? $_POST['centrenadores'] : 0,
                "rentrenadores" => isset($_POST['rentrenadores']) ? $_POST['rentrenadores'] : 0,
                "uentrenadores" => isset($_POST['uentrenadores']) ? $_POST['uentrenadores'] : 0,
                "dentrenadores" => isset($_POST['dentrenadores']) ? $_POST['dentrenadores'] : 0,
                "catletas" => isset($_POST['catletas']) ? $_POST['catletas'] : 0,
                "ratletas" => isset($_POST['ratletas']) ? $_POST['ratletas'] : 0,
                "uatletas" => isset($_POST['uatletas']) ? $_POST['uatletas'] : 0,
                "datletas" => isset($_POST['datletas']) ? $_POST['datletas'] : 0,
                "crolespermisos" => isset($_POST['crolespermisos']) ? $_POST['crolespermisos'] : 0,
                "rrolespermisos" => isset($_POST['rrolespermisos']) ? $_POST['rrolespermisos'] : 0,
                "urolespermisos" => isset($_POST['urolespermisos']) ? $_POST['urolespermisos'] : 0,
                "drolespermisos" => isset($_POST['drolespermisos']) ? $_POST['drolespermisos'] : 0,
                "casistencias" => isset($_POST['casistencias']) ? $_POST['casistencias'] : 0,
                "rasistencias" => isset($_POST['rasistencias']) ? $_POST['rasistencias'] : 0,
                "uasistencias" => isset($_POST['uasistencias']) ? $_POST['uasistencias'] : 0,
                "dasistencias" => isset($_POST['dasistencias']) ? $_POST['dasistencias'] : 0,
                "ceventos" => isset($_POST['ceventos']) ? $_POST['ceventos'] : 0,
                "reventos" => isset($_POST['reventos']) ? $_POST['reventos'] : 0,
                "ueventos" => isset($_POST['ueventos']) ? $_POST['ueventos'] : 0,
                "deventos" => isset($_POST['deventos']) ? $_POST['deventos'] : 0,
                "cmensualidad" => isset($_POST['cmensualidad']) ? $_POST['cmensualidad'] : 0,
                "rmensualidad" => isset($_POST['rmensualidad']) ? $_POST['rmensualidad'] : 0,
                "umensualidad" => isset($_POST['umensualidad']) ? $_POST['umensualidad'] : 0,
                "dmensualidad" => isset($_POST['dmensualidad']) ? $_POST['dmensualidad'] : 0,
                "cwada" => isset($_POST['cwada']) ? $_POST['cwada'] : 0,
                "rwada" => isset($_POST['rwada']) ? $_POST['rwada'] : 0,
                "uwada" => isset($_POST['uwada']) ? $_POST['uwada'] : 0,
                "dwada" => isset($_POST['dwada']) ? $_POST['dwada'] : 0,
                "creportes" => isset($_POST['creportes']) ? $_POST['creportes'] : 0,
                "rreportes" => isset($_POST['rreportes']) ? $_POST['rreportes'] : 0,
                "ureportes" => isset($_POST['ureportes']) ? $_POST['ureportes'] : 0,
                "dreportes" => isset($_POST['dreportes']) ? $_POST['dreportes'] : 0,
            ];
            $respuesta = $o->incluir_rol(
                $_POST['nombre'],
                $valores
            );
            echo json_encode($respuesta);
        } elseif ($accion == 'modificar') {
            $valores = [
                "centrenadores" => isset($_POST['centrenadores']) ? $_POST['centrenadores'] : 0,
                "rentrenadores" => isset($_POST['rentrenadores']) ? $_POST['rentrenadores'] : 0,
                "uentrenadores" => isset($_POST['uentrenadores']) ? $_POST['uentrenadores'] : 0,
                "dentrenadores" => isset($_POST['dentrenadores']) ? $_POST['dentrenadores'] : 0,
                "catletas" => isset($_POST['catletas']) ? $_POST['catletas'] : 0,
                "ratletas" => isset($_POST['ratletas']) ? $_POST['ratletas'] : 0,
                "uatletas" => isset($_POST['uatletas']) ? $_POST['uatletas'] : 0,
                "datletas" => isset($_POST['datletas']) ? $_POST['datletas'] : 0,
                "crolespermisos" => isset($_POST['crolespermisos']) ? $_POST['crolespermisos'] : 0,
                "rrolespermisos" => isset($_POST['rrolespermisos']) ? $_POST['rrolespermisos'] : 0,
                "urolespermisos" => isset($_POST['urolespermisos']) ? $_POST['urolespermisos'] : 0,
                "drolespermisos" => isset($_POST['drolespermisos']) ? $_POST['drolespermisos'] : 0,
                "casistencias" => isset($_POST['casistencias']) ? $_POST['casistencias'] : 0,
                "rasistencias" => isset($_POST['rasistencias']) ? $_POST['rasistencias'] : 0,
                "uasistencias" => isset($_POST['uasistencias']) ? $_POST['uasistencias'] : 0,
                "dasistencias" => isset($_POST['dasistencias']) ? $_POST['dasistencias'] : 0,
                "ceventos" => isset($_POST['ceventos']) ? $_POST['ceventos'] : 0,
                "reventos" => isset($_POST['reventos']) ? $_POST['reventos'] : 0,
                "ueventos" => isset($_POST['ueventos']) ? $_POST['ueventos'] : 0,
                "deventos" => isset($_POST['deventos']) ? $_POST['deventos'] : 0,
                "cmensualidad" => isset($_POST['cmensualidad']) ? $_POST['cmensualidad'] : 0,
                "rmensualidad" => isset($_POST['rmensualidad']) ? $_POST['rmensualidad'] : 0,
                "umensualidad" => isset($_POST['umensualidad']) ? $_POST['umensualidad'] : 0,
                "dmensualidad" => isset($_POST['dmensualidad']) ? $_POST['dmensualidad'] : 0,
                "cwada" => isset($_POST['cwada']) ? $_POST['cwada'] : 0,
                "rwada" => isset($_POST['rwada']) ? $_POST['rwada'] : 0,
                "uwada" => isset($_POST['uwada']) ? $_POST['uwada'] : 0,
                "dwada" => isset($_POST['dwada']) ? $_POST['dwada'] : 0,
                "creportes" => isset($_POST['creportes']) ? $_POST['creportes'] : 0,
                "rreportes" => isset($_POST['rreportes']) ? $_POST['rreportes'] : 0,
                "ureportes" => isset($_POST['ureportes']) ? $_POST['ureportes'] : 0,
                "dreportes" => isset($_POST['dreportes']) ? $_POST['dreportes'] : 0,
            ];
            $respuesta = $o->modificar_rol(
                $_POST['id_rol'],
                $_POST['nombre'],
                $valores
            );
            echo json_encode($respuesta);
        } elseif ($accion == 'eliminar_rol') {
            $respuesta = $o->eliminar_rol($_POST['id_rol']);
            echo json_encode($respuesta);
        } elseif ($accion == 'consultar_rol') {
            $respuesta = $o->consultar_rol($_POST['id_rol']);
            echo json_encode($respuesta);
        }
        exit;
    }
    require_once ("vista/" . $p . ".php");
} else {
    echo "pagina en construccion";
}
