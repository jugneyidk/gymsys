<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';
// Configuración para la clave RSA 4096
putenv("OPENSSL_CONF=C:\\xampp\\php\\extras\\openssl\\openssl.cnf");
var_dump($_ENV["OPENSSL_CONF"]);
$config = [
    // "config" => $_ENV["OPENSSL_DIR"],s
    "digest_alg" => "sha512",
    "private_key_bits" => 4096,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
];

$res = openssl_pkey_new($config);

if (!$res) {
    while ($msg = openssl_error_string()) {
        echo "OpenSSL error (key_new): $msg\n";
    }
    die("❌ Error al generar las claves RSA.\n");
}

// Exportar la clave privada
$privateKey = '';
if (!openssl_pkey_export($res, $privateKey)) {
    while ($msg = openssl_error_string()) {
        echo "OpenSSL error (export): $msg\n";
    }
    die("❌ Error al exportar la clave privada.\n");
}

// Obtener la clave pública
$keyDetails = openssl_pkey_get_details($res);
$publicKey = $keyDetails['key'];

// Guardar ambas claves
file_put_contents("private.key", $privateKey);
file_put_contents("public.key", $publicKey);

echo "✅ Claves RSA 4096 generadas exitosamente.\n";