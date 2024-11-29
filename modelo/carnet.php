<?php
class Carnet extends datos
{
    private $conexion;
    private $cedula;

    public function __construct()
    {
        $this->conexion = $this->conecta();
    }

    public function obtener_carnet($cedula)
    {
        $validacion = Validar::validar("cedula", $cedula);
        if (!$validacion["ok"]) {
            return $validacion;
        }
        $this->cedula = $cedula;
        $usuario = $this->obtener();
        if ($usuario["ok"]) {
            $carnet = $this->crear_carnet($usuario["usuario"]);
            return $carnet;
        }
    }

    private function obtener()
    {
        try {
            $consulta = "
                SELECT 
                    u.cedula, 
                    u.nombre, 
                    u.apellido,
                    r.nombre AS nombre_rol
                FROM usuarios u
                INNER JOIN usuarios_roles ur ON u.cedula = ur.id_usuario
                INNER JOIN roles r ON ur.id_rol = r.id_rol
                WHERE u.cedula = :cedula;";
            $valores = array(':cedula' => $this->cedula);
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $usuario = $respuesta->fetch(PDO::FETCH_ASSOC);
            if ($usuario) {
                $resultado["ok"] = true;
                $resultado["usuario"] = $usuario;
            } else {
                $resultado["ok"] = false;
                $resultado["mensaje"] = "No se encontró el usuario";
            }
        } catch (PDOException $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        $this->desconecta();
        return $resultado;
    }
    private function crear_carnet($usuario)
    {
        try {
            $data = 'http://' . $_SERVER['HTTP_HOST'] . '/gymsys/?p=perfil_atleta&id=' . $usuario["cedula"];
            $qrFile = 'qr.png';
            QRcode::png($data, $qrFile, 'h', 20, 1);
            // Dimensiones del carnet
            $ancho = 637;
            $alto = 1011;
            // Crear un lienzo
            $imagen = imagecreatetruecolor($ancho, $alto);
            if (!$imagen) {
                die("No se pudo crear la imagen");
            }
            imagesavealpha($imagen, false);
            // Colores
            $colorFondo = imagecolorallocate($imagen, 250, 250, 250); // Gris claro
            $colorBorde = imagecolorallocate($imagen, 0, 0, 0); // Negro
            $colorTexto = imagecolorallocate($imagen, 50, 50, 50); // Gris oscuro
            $colorBlanco = imagecolorallocate($imagen, 255, 255, 255); // Gris oscuro
            $colorTitulo = imagecolorallocate($imagen, 0, 102, 204); // Azul

            // Crear un fondo blanco
            imagefilledrectangle($imagen, 0, 0, $ancho, $alto, $colorFondo);

            // Crear un borde negro
            imagerectangle($imagen, 0, 0, $ancho - 1, $alto - 1, $colorBorde);

            // Agregar un área de encabezado
            imagefilledrectangle($imagen, 0, 0, $ancho, 350, $colorTitulo);

            // Agregar un área del pie
            imagefilledrectangle($imagen, 0, 940, $ancho, 1011, $colorTitulo);

            // Cargar una fuente personalizada
            $fuenteRegular = 'webfonts/Roboto-Regular.ttf'; // Asegúrate de que esta fuente exista en tu servidor
            $fuenteBold = 'webfonts/Roboto-Bold.ttf'; // Asegúrate de que esta fuente exista en tu servidor
            if (!file_exists($fuenteRegular) || !file_exists($fuenteBold)) {
                die("Fuente no encontrada");
            }

            // Agregar texto del título
            $texto = "Gimnasio de Halterofilia";
            $tamanoFuente = 32;

            // Obtener dimensiones del texto
            $cajaTexto = imagettfbbox($tamanoFuente, 0, $fuenteBold, $texto);
            $anchoTexto = abs($cajaTexto[4] - $cajaTexto[0]);
            $altoTexto = abs($cajaTexto[5] - $cajaTexto[1]);
            // Calcular coordenadas para centrar
            $x = ($ancho - $anchoTexto) / 2; // Coordenada X
            $y = (130 + $altoTexto) / 2;  // Coordenada Y (ajustando la línea base)
            // Dibujar el texto centrado
            imagettftext($imagen, $tamanoFuente, 0, $x, $y, $colorBlanco, $fuenteRegular, $texto);

            // Agregar texto del título
            $texto = '"Eddie Suarez"';
            $tamanoFuente = 32;

            // Obtener dimensiones del texto
            $cajaTexto = imagettfbbox($tamanoFuente, 0, $fuenteBold, $texto);
            $anchoTexto = abs($cajaTexto[4] - $cajaTexto[0]);
            $altoTexto = abs($cajaTexto[5] - $cajaTexto[1]);
            // Calcular coordenadas para centrar
            $x = ($ancho - $anchoTexto) / 2; // Coordenada X
            $y = (250 + $altoTexto) / 2;  // Coordenada Y (ajustando la línea base)
            // Dibujar el texto centrado
            imagettftext($imagen, $tamanoFuente, 0, $x, $y, $colorBlanco, $fuenteRegular, $texto);

            // Coordenadas y dimensiones del círculo
            $cx = $ancho / 2; // Centro X
            $cy = 320; // Centro Y
            $diametro = 320; // Diámetro

            // Dibujar el círculo
            imagefilledellipse($imagen, $cx, $cy, $diametro, $diametro, $colorTitulo);

            // Coordenadas y dimensiones del círculo
            $cx = $ancho / 2; // Centro X
            $cy = 320; // Centro Y
            $diametro = 290; // Diámetro

            // Dibujar el círculo
            imagefilledellipse($imagen, $cx, $cy, $diametro, $diametro, $colorFondo);


            // Nombre
            $nombre = $usuario["nombre"];
            $tamanoFuente = 28;
            $cajaNombre = imagettfbbox($tamanoFuente, 0, $fuenteBold, $nombre);
            $anchoNombre = abs($cajaNombre[4] - $cajaNombre[0]);
            $altoNombre = abs($cajaNombre[5] - $cajaNombre[1]);
            // Calcular coordenadas para centrar
            $x = ($ancho - $anchoNombre) / 2; // Coordenada X
            $y = (1050 + $altoNombre) / 2;  // Coordenada Y (ajustando la línea base)
            imagettftext($imagen, $tamanoFuente, 0, $x, $y, $colorTexto, $fuenteBold, "$nombre");

            // Apellido
            $apellido = $usuario["apellido"];
            $tamanoFuente = 28;
            $cajaApellido = imagettfbbox($tamanoFuente, 0, $fuenteBold, $apellido);
            $anchoApellido = abs($cajaApellido[4] - $cajaApellido[0]);
            $altoApellido = abs($cajaApellido[5] - $cajaApellido[1]);
            // Calcular coordenadas para centrar
            $x = ($ancho - $anchoApellido) / 2; // Coordenada X
            $y = (1150 + $altoApellido) / 2;  // Coordenada Y (ajustando la línea base)
            imagettftext($imagen, $tamanoFuente, 0, $x, $y, $colorTexto, $fuenteBold, "$apellido");

            // Cedula
            $cedula = "V-" . $usuario["cedula"];
            $tamanoFuente = 26;
            $cajaCedula = imagettfbbox($tamanoFuente, 0, $fuenteRegular, $cedula);
            $anchoCedula = abs($cajaCedula[4] - $cajaCedula[0]);
            $altoCedula = abs($cajaCedula[5] - $cajaCedula[1]);
            // Calcular coordenadas para centrar
            $x = ($ancho - $anchoCedula) / 2; // Coordenada X
            $y = (1250 + $altoCedula) / 2;  // Coordenada Y (ajustando la línea base)
            imagettftext($imagen, $tamanoFuente, 0, $x, $y, $colorTexto, $fuenteRegular, "$cedula");

            // Rol del usuario
            $rol = $usuario["nombre_rol"];
            $tamanoFuente = 26;
            $cajaRol = imagettfbbox($tamanoFuente, 0, $fuenteBold, $rol);
            $anchoRol = abs($cajaRol[4] - $cajaRol[0]);
            $altoRol = abs($cajaRol[5] - $cajaRol[1]);
            // Calcular coordenadas para centrar
            $x = ($ancho - $anchoRol) / 2; // Coordenada X
            $y = (1340 + $altoRol) / 2;  // Coordenada Y (ajustando la línea base)
            imagettftext($imagen, $tamanoFuente, 0, $x, $y, $colorTexto, $fuenteBold, "$rol");

            // Verificar y agregar foto de perfil
            $rutaFotoPerfil = 'img/diego.jpg'; // Ruta a la foto del perfil

            if (file_exists($rutaFotoPerfil)) {
                $ext = pathinfo($rutaFotoPerfil, PATHINFO_EXTENSION);
                if ($ext == "jpg" || $ext == "jpeg") {
                    $fotoPerfil = imagecreatefromjpeg($rutaFotoPerfil);
                } else if ($ext == "png") {
                    $fotoPerfil = imagecreatefrompng($rutaFotoPerfil);
                }
                if ($fotoPerfil === false) {
                    die("Error al cargar la imagen: $rutaFotoPerfil");
                }
                // Configuración
                $diametro = 270;

                // Obtener dimensiones originales de la imagen
                $anchoFoto = imagesx($fotoPerfil);
                $altoFoto = imagesy($fotoPerfil);

                // Crear lienzo para la imagen circular
                $imagenCircular = imagecreatetruecolor($diametro, $diametro);
                imagesavealpha($imagenCircular, true); // Mantener transparencia
                $transparente = imagecolorallocatealpha($imagenCircular, 0, 255, 0, 0);
                imagefill($imagenCircular, 0, 0, $transparente);

                // Redimensionar la foto original al tamaño del círculo
                imagecopyresampled(
                    $imagenCircular,
                    $fotoPerfil,
                    0,
                    0,
                    0,
                    0,
                    $diametro,
                    $diametro,
                    $anchoFoto,
                    $altoFoto
                );
                // Crear máscara circular
                $mascara = imagecreatetruecolor($diametro, $diametro);
                imagesavealpha($mascara, true); // Mantener transparencia
                imagefill($mascara, 0, 0, $transparente); // Fondo transparente
                $colorBlanco = imagecolorallocate($mascara, 255, 255, 255);
                imagefilledellipse($mascara, $diametro / 2, $diametro / 2, $diametro, $diametro, $colorBlanco);
                // Aplicar máscara circular
                for ($x = 0; $x < $diametro; $x++) {
                    for ($y = 0; $y < $diametro; $y++) {
                        // Calcular distancia desde el centro del círculo
                        $distancia = sqrt(pow($x - $diametro / 2, 2) + pow($y - $diametro / 2, 2));
                        if ($distancia > $diametro / 2) { // Fuera del círculo
                            imagesetpixel($imagenCircular, $x, $y, $transparente);
                        }
                    }
                }
                // Agregar la imagen circular al lienzo principal
                $x = ($ancho - $diametro) / 2; // Centrado horizontal
                imagecolortransparent($imagenCircular, $transparente);
                imagecopymerge($imagen, $imagenCircular, $x, 185, 0, 0, $diametro, $diametro, 100);
                // Liberar memoria
                imagedestroy($fotoPerfil);
                imagedestroy($mascara);
                imagedestroy($imagenCircular);
            } else {
                error_log("Archivo no encontrado: $rutaFotoPerfil");
            }

            // Verificar y agregar logo de la UPTAEB
            $rutaLogo = 'img/logo-uptaeb.png'; // Ruta al logo
            if (file_exists($rutaLogo)) {
                $logo = @imagecreatefrompng($rutaLogo);
                if ($logo) {
                    imagecopyresized($imagen, $logo, $ancho / 2 - 50, 942, 0, 0, 100, 65, imagesx($logo), imagesy($logo));
                    imagedestroy($logo); // Liberar memoria
                } else {
                    error_log("Error al cargar el logo: $rutaLogo");
                }
            } else {
                error_log("Archivo no encontrado: $rutaFotoPerfil");
            }

            if (file_exists($qrFile)) {
                $qr = imagecreatefrompng($qrFile);
                imagecopyresized($imagen, $qr, $ancho / 2 - 110, 705, 0, 0, 220, 220, imagesx($qr), imagesy($qr));
                imagedestroy($qr);
                unlink($qrFile);
            }
            $resultado["ok"] = true;
            $resultado["imagen"] = $imagen;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }
    public function __get($propiedad)
    {
        return $this->$propiedad;
    }

    public function __set($propiedad, $valor)
    {
        $this->$propiedad = $valor;
        return $this;
    }
}
