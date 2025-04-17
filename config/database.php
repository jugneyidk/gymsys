<?php
return [
  'host' => $_ENV['DB_HOST'] ?? 'localhost',
  'bd' => $_ENV['DB_NAME'] ?? 'gymsys',
  'usuario' => $_ENV['DB_USER'] ?? 'root',
  'password' => $_ENV['DB_PASS'] ?? '',
  'options' => [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Manejo de errores
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch como array asociativo
  ]
];