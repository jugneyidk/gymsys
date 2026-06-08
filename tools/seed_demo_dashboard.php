<?php

$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$admin = '28609560';
$now = new DateTimeImmutable('now');
$currentMonth = $now->format('Y-m-05');

$trainers = [
    ['50001001', 'Valeria', 'Rojas', 'Femenino', '1988-04-16', 'Barquisimeto', 'Soltera', '04120001001', 'valeria.rojas@demo.gym', 'Licenciada en Entrenamiento Deportivo'],
    ['50001002', 'Carlos', 'Mendoza', 'Masculino', '1983-09-21', 'Cabudare', 'Casado', '04120001002', 'carlos.mendoza@demo.gym', 'Magister en Ciencias del Deporte'],
    ['50001003', 'Mariana', 'Torres', 'Femenino', '1991-12-02', 'Quibor', 'Soltera', '04120001003', 'mariana.torres@demo.gym', 'TSU en Actividad Física'],
    ['50001004', 'Andres', 'Linares', 'Masculino', '1979-07-11', 'El Tocuyo', 'Casado', '04120001004', 'andres.linares@demo.gym', 'Especialista en Halterofilia'],
];

$athletes = [
    ['50002001', 'Sofia', 'Castillo', 'Femenino', '2007-02-18', 4, 58.4, 162.0, '50001001'],
    ['50002002', 'Diego', 'Salazar', 'Masculino', '2004-11-09', 3, 72.8, 171.0, '50001002'],
    ['50002003', 'Camila', 'Paredes', 'Femenino', '2006-05-24', 4, 63.2, 166.0, '50001001'],
    ['50002004', 'Luis', 'Herrera', 'Masculino', '2002-01-14', 3, 81.5, 178.0, '7376581'],
    ['50002005', 'Andrea', 'Gomez', 'Femenino', '2008-08-30', 4, 54.9, 160.0, '50001003'],
    ['50002006', 'Javier', 'Perez', 'Masculino', '2001-03-12', 3, 88.2, 182.0, '50001004'],
    ['50002007', 'Daniela', 'Molina', 'Femenino', '2005-10-07', 4, 59.6, 164.0, '50001001'],
    ['50002008', 'Samuel', 'Rivas', 'Masculino', '2003-06-19', 3, 69.4, 170.0, '50001002'],
    ['50002009', 'Paola', 'Vargas', 'Femenino', '2000-09-25', 3, 71.1, 168.0, '28609560'],
    ['50002010', 'Mateo', 'Leon', 'Masculino', '2009-04-03', 4, 52.7, 158.0, '50001003'],
    ['50002011', 'Gabriela', 'Nunez', 'Femenino', '2004-12-22', 3, 76.0, 169.0, '7376581'],
    ['50002012', 'Ricardo', 'Morales', 'Masculino', '1999-01-28', 3, 96.8, 186.0, '50001004'],
    ['50002013', 'Lucia', 'Sanchez', 'Femenino', '2007-07-17', 4, 49.9, 155.0, '50001003'],
    ['50002014', 'Fernando', 'Ortega', 'Masculino', '2005-05-08', 4, 61.5, 165.0, '50001002'],
    ['50002015', 'Valentina', 'Reyes', 'Femenino', '2002-03-29', 3, 82.3, 172.0, '50001001'],
    ['50002016', 'Emmanuel', 'Suarez', 'Masculino', '2006-06-06', 4, 67.2, 168.0, '28609560'],
    ['50002017', 'Natalia', 'Campos', 'Femenino', '2001-11-13', 3, 64.5, 167.0, '7376581'],
    ['50002018', 'Cristian', 'Figueroa', 'Masculino', '2000-08-01', 3, 73.6, 174.0, '50001004'],
    ['50002019', 'Ariana', 'Diaz', 'Femenino', '2008-02-05', 4, 47.8, 153.0, '50001003'],
    ['50002020', 'Sebastian', 'Acosta', 'Masculino', '2004-04-27', 3, 84.0, 179.0, '50001002'],
    ['50002021', 'Isabella', 'Medina', 'Femenino', '2003-09-15', 3, 69.7, 166.0, '50001001'],
    ['50002022', 'Victor', 'Ramirez', 'Masculino', '1998-12-10', 3, 102.4, 188.0, '50001004'],
    ['50002023', 'Elena', 'Gil', 'Femenino', '2006-01-20', 4, 55.3, 161.0, '50001003'],
    ['50002024', 'Manuel', 'Cabrera', 'Masculino', '2007-10-31', 4, 59.1, 163.0, '50001002'],
    ['50002025', 'Renata', 'Silva', 'Femenino', '2005-03-04', 4, 62.0, 165.0, '50001001'],
    ['50002026', 'Oscar', 'Navarro', 'Masculino', '2002-07-23', 3, 77.9, 176.0, '7376581'],
    ['50002027', 'Miranda', 'Lopez', 'Femenino', '2009-11-18', 4, 45.6, 151.0, '50001003'],
];

$pdo->exec('SET @usuario_actual = ' . $pdo->quote($admin));
$pdo->exec("DELETE FROM gymsys_secure.bitacora WHERE registro_modificado LIKE 'DEMO-%'");
$pdo->exec("DELETE FROM gymsys.asistencias WHERE id_atleta LIKE '50002%' OR id_atleta = '29831802'");
$pdo->exec("DELETE FROM gymsys.mensualidades WHERE id_atleta LIKE '50002%' OR id_atleta = '29831802'");
$pdo->exec("DELETE FROM gymsys.wada WHERE id_atleta LIKE '50002%' OR id_atleta = '29831802'");

$insertUser = $pdo->prepare("INSERT INTO gymsys_secure.usuarios (cedula, nombre, apellido, genero, fecha_nacimiento, lugar_nacimiento, estado_civil, telefono, correo_electronico)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE nombre = VALUES(nombre), apellido = VALUES(apellido), genero = VALUES(genero), fecha_nacimiento = VALUES(fecha_nacimiento), lugar_nacimiento = VALUES(lugar_nacimiento), estado_civil = VALUES(estado_civil), telefono = VALUES(telefono), correo_electronico = VALUES(correo_electronico)");
$insertTrainer = $pdo->prepare("INSERT INTO gymsys.entrenador (cedula, grado_instruccion) VALUES (?, ?) ON DUPLICATE KEY UPDATE grado_instruccion = VALUES(grado_instruccion)");
$insertAthlete = $pdo->prepare("INSERT INTO gymsys.atleta (cedula, entrenador, tipo_atleta, peso, altura, representante) VALUES (?, ?, ?, ?, ?, NULL) ON DUPLICATE KEY UPDATE entrenador = VALUES(entrenador), tipo_atleta = VALUES(tipo_atleta), peso = VALUES(peso), altura = VALUES(altura)");
$insertPayment = $pdo->prepare("INSERT INTO gymsys.mensualidades (id_atleta, fecha, monto, detalles) VALUES (?, ?, ?, ?)");
$insertWada = $pdo->prepare("INSERT INTO gymsys.wada (id_atleta, inscrito, vencimiento, ultima_actualizacion, estado) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE inscrito = VALUES(inscrito), vencimiento = VALUES(vencimiento), ultima_actualizacion = VALUES(ultima_actualizacion), estado = VALUES(estado)");
$insertAttendance = $pdo->prepare("INSERT INTO gymsys.asistencias (id_atleta, asistio, fecha, comentario) VALUES (?, ?, ?, ?)");
$insertLog = $pdo->prepare("INSERT INTO gymsys_secure.bitacora (id_usuario, accion, modulo, registro_modificado, detalles, fecha) VALUES (?, ?, ?, ?, ?, ?)");

foreach ($trainers as $trainer) {
    [$cedula, $nombre, $apellido, $genero, $nacimiento, $lugar, $estadoCivil, $telefono, $correo, $grado] = $trainer;
    $insertUser->execute([$cedula, $nombre, $apellido, $genero, $nacimiento, $lugar, $estadoCivil, $telefono, $correo]);
    $insertTrainer->execute([$cedula, $grado]);
}

foreach ($athletes as $athlete) {
    [$cedula, $nombre, $apellido, $genero, $nacimiento, $tipo, $peso, $altura, $entrenador] = $athlete;
    $insertUser->execute([$cedula, $nombre, $apellido, $genero, $nacimiento, 'Lara', 'Soltero', '0412' . substr($cedula, -7), strtolower($nombre . '.' . $apellido) . '@demo.gym']);
    $insertAthlete->execute([$cedula, $entrenador, $tipo, $peso, $altura]);
}

$allAthleteIds = array_merge(['29831802'], array_column($athletes, 0));
$paidAthletes = array_slice($allAthleteIds, 0, 21);
foreach ($paidAthletes as $index => $cedula) {
    $monto = $index % 3 === 0 ? 5.00 : 2.00;
    $insertPayment->execute([$cedula, $currentMonth, $monto, 'Pago mensual demo registrado para muestra del sistema']);
}

foreach ($allAthleteIds as $index => $cedula) {
    if ($index < 19) {
        $vencimiento = $now->modify('+' . (45 + $index * 3) . ' days');
        $estado = 1;
    } elseif ($index < 24) {
        $vencimiento = $now->modify('+' . (5 + ($index - 19) * 4) . ' days');
        $estado = 1;
    } else {
        $vencimiento = $now->modify('-' . (3 + ($index - 24) * 5) . ' days');
        $estado = 0;
    }
    $inscrito = $vencimiento->modify('-1 year');
    $insertWada->execute([$cedula, $inscrito->format('Y-m-d'), $vencimiento->format('Y-m-d'), $now->format('Y-m-d'), $estado]);
}

$monthlyAttendanceTargets = [41, 57, 49, 68, 54, 76, 63, 72, 45, 80, 61, 74];

for ($monthOffset = 11; $monthOffset >= 0; $monthOffset--) {
    $base = $now->modify('first day of this month')->modify("-$monthOffset months");
    $monthIndex = 11 - $monthOffset;
    $attendanceCount = 0;
    $target = $monthlyAttendanceTargets[$monthIndex];

    foreach ([6, 14, 22] as $dayIndex => $day) {
        $fecha = $base->setDate((int)$base->format('Y'), (int)$base->format('m'), min($day, (int)$base->format('t')))->format('Y-m-d');
        foreach ($allAthleteIds as $athleteIndex => $cedula) {
            $recordIndex = ($dayIndex * count($allAthleteIds)) + $athleteIndex;
            $weightedIndex = ($recordIndex * 17 + $monthIndex * 11) % (count($allAthleteIds) * 3);
            $asistio = $weightedIndex < $target && $attendanceCount < $target ? 1 : 0;
            $attendanceCount += $asistio;
            $insertAttendance->execute([$cedula, $asistio, $fecha, $asistio ? 'Entrenamiento completado' : 'Ausencia justificada']);
        }
    }
}

$logs = [
    ['Modificar', 'Atletas', 'DEMO-ATLETAS', 'Actualización de datos antropométricos de atletas activos.', '-2 hours'],
    ['Incluir', 'Mensualidad', 'DEMO-PAGOS', 'Registro de pagos de mensualidad del mes actual.', '-5 hours'],
    ['Incluir', 'Asistencias', 'DEMO-ASIST', 'Carga de asistencia del bloque de entrenamiento de la tarde.', '-1 day'],
    ['Modificar', 'WADA', 'DEMO-WADA', 'Revisión de vigencias WADA próximas a vencer.', '-2 days'],
    ['Incluir', 'Eventos', 'DEMO-EVENTOS', 'Planificación de competencia interna de control técnico.', '-3 days'],
    ['Modificar', 'Entrenadores', 'DEMO-STAFF', 'Actualización de asignaciones de entrenadores por grupo.', '-4 days'],
];

foreach ($logs as [$accion, $modulo, $registro, $detalle, $relative]) {
    $insertLog->execute([$admin, $accion, $modulo, $registro, $detalle, $now->modify($relative)->format('Y-m-d H:i:s')]);
}

$counts = $pdo->query("SELECT * FROM gymsys.estadisticas_dashboard")->fetch();
echo 'Demo data ready: ' . json_encode($counts, JSON_UNESCAPED_UNICODE) . PHP_EOL;
