<?php
require_once __DIR__ . '/vendor/autoload.php';

use Gymsys\Utils\LoginAttempts;

// Limpiar archivos antiguos de intentos de login
$archivosLimpiados = LoginAttempts::limpiarCacheIntentos();
echo "$archivosLimpiados Archivos de intentos de login antiguos han sido limpiados.";
