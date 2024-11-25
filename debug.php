<?php
// include 'lib/phpqrcode-master/qrlib.php';
// $data = 'https://192.168.0.115/gymsys/?p=perfil_atleta&id=99389012';
// $qrFile = 'qr.png';
// QRcode::png($data, $qrFile, 'h', 20, 1);
// // Dimensiones del carnet
// $ancho = 637;
// $alto = 1011;

// // Crear un lienzo
// $imagen = imagecreate($ancho, $alto);
// if (!$imagen) {
//     die("No se pudo crear la imagen");
// }
// imagesavealpha($imagen, false);

// // Colores
// $colorFondo = imagecolorallocate($imagen, 250, 250, 250); // Gris claro
// $colorBorde = imagecolorallocate($imagen, 0, 0, 0); // Negro
// $colorTexto = imagecolorallocate($imagen, 50, 50, 50); // Gris oscuro
// $colorBlanco = imagecolorallocate($imagen, 255, 255, 255); // Gris oscuro
// $colorTitulo = imagecolorallocate($imagen, 0, 102, 204); // Azul

// // Crear un borde negro
// imagerectangle($imagen, 0, 0, $ancho - 1, $alto - 1, $colorBorde);

// // Agregar un área de encabezado
// imagefilledrectangle($imagen, 0, 0, $ancho, 350, $colorTitulo);

// // Agregar un área del pie
// imagefilledrectangle($imagen, 0, 940, $ancho, 1011, $colorTitulo);

// // Cargar una fuente personalizada
// $fuenteRegular = __DIR__ . '/webfonts/Roboto-Regular.ttf'; // Asegúrate de que esta fuente exista en tu servidor
// $fuenteBold = __DIR__ . '/webfonts/Roboto-Bold.ttf'; // Asegúrate de que esta fuente exista en tu servidor
// if (!file_exists($fuenteRegular) || !file_exists($fuenteBold)) {
//     die("Fuente no encontrada");
// }

// // Agregar texto del título
// $texto = "Gimnasio de Halterofilia";
// $tamanoFuente = 32;

// // Obtener dimensiones del texto
// $cajaTexto = imagettfbbox($tamanoFuente, 0, $fuenteBold, $texto);
// $anchoTexto = abs($cajaTexto[4] - $cajaTexto[0]);
// $altoTexto = abs($cajaTexto[5] - $cajaTexto[1]);
// // Calcular coordenadas para centrar
// $x = ($ancho - $anchoTexto) / 2; // Coordenada X
// $y = (130 + $altoTexto) / 2;  // Coordenada Y (ajustando la línea base)
// // Dibujar el texto centrado
// imagettftext($imagen, $tamanoFuente, 0, $x, $y, $colorBlanco, $fuenteRegular, $texto);

// // Agregar texto del título
// $texto = '"Eddie Suarez"';
// $tamanoFuente = 32;

// // Obtener dimensiones del texto
// $cajaTexto = imagettfbbox($tamanoFuente, 0, $fuenteBold, $texto);
// $anchoTexto = abs($cajaTexto[4] - $cajaTexto[0]);
// $altoTexto = abs($cajaTexto[5] - $cajaTexto[1]);
// // Calcular coordenadas para centrar
// $x = ($ancho - $anchoTexto) / 2; // Coordenada X
// $y = (250 + $altoTexto) / 2;  // Coordenada Y (ajustando la línea base)
// // Dibujar el texto centrado
// imagettftext($imagen, $tamanoFuente, 0, $x, $y, $colorBlanco, $fuenteRegular, $texto);

// // Coordenadas y dimensiones del círculo
// $cx = $ancho / 2; // Centro X
// $cy = 320; // Centro Y
// $diametro = 320; // Diámetro

// // Dibujar el círculo
// imagefilledellipse($imagen, $cx, $cy, $diametro, $diametro, $colorTitulo);

// // Coordenadas y dimensiones del círculo
// $cx = $ancho / 2; // Centro X
// $cy = 320; // Centro Y
// $diametro = 290; // Diámetro

// // Dibujar el círculo
// imagefilledellipse($imagen, $cx, $cy, $diametro, $diametro, $colorFondo);


// // Nombre
// $nombre = "Ho Wong";
// $tamanoFuente = 28;
// $cajaNombre = imagettfbbox($tamanoFuente, 0, $fuenteBold, $nombre);
// $anchoNombre = abs($cajaNombre[4] - $cajaNombre[0]);
// $altoNombre = abs($cajaNombre[5] - $cajaNombre[1]);
// // Calcular coordenadas para centrar
// $x = ($ancho - $anchoNombre) / 2; // Coordenada X
// $y = (1050 + $altoNombre) / 2;  // Coordenada Y (ajustando la línea base)
// imagettftext($imagen, $tamanoFuente, 0, $x, $y, $colorTexto, $fuenteBold, "$nombre");

// // Apellido
// $apellido = "Wing Orellana";
// $tamanoFuente = 28;
// $cajaApellido = imagettfbbox($tamanoFuente, 0, $fuenteBold, $apellido);
// $anchoApellido = abs($cajaApellido[4] - $cajaApellido[0]);
// $altoApellido = abs($cajaApellido[5] - $cajaApellido[1]);
// // Calcular coordenadas para centrar
// $x = ($ancho - $anchoApellido) / 2; // Coordenada X
// $y = (1140 + $altoApellido) / 2;  // Coordenada Y (ajustando la línea base)
// imagettftext($imagen, $tamanoFuente, 0, $x, $y, $colorTexto, $fuenteBold, "$apellido");

// // Cedula
// $cedula = "12345678";
// $tamanoFuente = 26;
// $cajaCedula = imagettfbbox($tamanoFuente, 0, $fuenteRegular, $cedula);
// $anchoCedula = abs($cajaCedula[4] - $cajaCedula[0]);
// $altoCedula = abs($cajaCedula[5] - $cajaCedula[1]);
// // Calcular coordenadas para centrar
// $x = ($ancho - $anchoCedula) / 2; // Coordenada X
// $y = (1250 + $altoCedula) / 2;  // Coordenada Y (ajustando la línea base)
// imagettftext($imagen, $tamanoFuente, 0, $x, $y, $colorTexto, $fuenteRegular, "$cedula");

// // Rol del usuario
// $rol = "Entrenador";
// $tamanoFuente = 26;
// $cajaRol = imagettfbbox($tamanoFuente, 0, $fuenteBold, $rol);
// $anchoRol = abs($cajaRol[4] - $cajaRol[0]);
// $altoRol = abs($cajaRol[5] - $cajaRol[1]);
// // Calcular coordenadas para centrar
// $x = ($ancho - $anchoRol) / 2; // Coordenada X
// $y = (1330 + $altoRol) / 2;  // Coordenada Y (ajustando la línea base)
// imagettftext($imagen, $tamanoFuente, 0, $x, $y, $colorTexto, $fuenteBold, "$rol");

// // Verificar y agregar foto de perfil
// $rutaFotoPerfil = 'foto.jpg'; // Ruta a la foto del perfil
// if (file_exists($rutaFotoPerfil)) {
//     $fotoPerfil = @imagecreatefromjpeg($rutaFotoPerfil);
//     if ($fotoPerfil) {
//         imagecopyresized($imagen, $fotoPerfil, 400, 100, 0, 0, 150, 150, imagesx($fotoPerfil), imagesy($fotoPerfil));
//         imagedestroy($fotoPerfil); // Liberar memoria
//     } else {
//         error_log("Error al cargar la foto de perfil: $rutaFotoPerfil");
//     }
// } else {
//     error_log("Archivo no encontrado: $rutaFotoPerfil");
// }

// // Verificar y agregar logo de la UPTAEB
// $rutaLogo = __DIR__ . '/img/logo-uptaeb.png'; // Ruta al logo
// if (file_exists($rutaLogo)) {
//     $logo = @imagecreatefrompng($rutaLogo);
//     if ($logo) {
//         imagecopyresized($imagen, $logo, $ancho / 2 - 50, 942, 0, 0, 100, 65, imagesx($logo), imagesy($logo));
//         imagedestroy($logo); // Liberar memoria
//     } else {
//         error_log("Error al cargar el logo: $rutaLogo");
//     }
// } else {
//     error_log("Archivo no encontrado: $rutaFotoPerfil");
// }

// if (file_exists($qrFile)) {
//     $qr = imagecreatefrompng($qrFile);
//     imagecopyresized($imagen, $qr, $ancho / 2 - 110, 705, 0, 0, 220, 220, imagesx($qr), imagesy($qr));
//     imagedestroy($qr);
//     unlink($qrFile);
// }


// // Guardar o mostrar el carné
// header('Content-Type: image/png');
// imagepng($imagen); // Enviar al navegador

// // Liberar memoria
// imagedestroy($imagen);

echo getHostByName(getHostName());
?>