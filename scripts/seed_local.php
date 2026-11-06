<?php

$config = require dirname(__DIR__) . '/config/database.php';
$apply = in_array('--apply', $argv, true);

if (!in_array($config['host'], ['localhost', '127.0.0.1', '::1'], true)
    || $config['name'] !== 'boxpingpotosina'
) {
    fwrite(STDERR, "Este seeder solo se permite para la base local boxpingpotosina.\n");
    exit(1);
}

$timezone = new DateTimeZone('America/Chicago');
$now = new DateTimeImmutable('now', $timezone);
$date = static function (DateTimeImmutable $value): string {
    return $value->format('Y-m-d H:i:s');
};

if (!$apply) {
    echo "Vista previa: 10 clientes, 25 consolidados con 30 entradas cada uno, 10 reempacadores, 2 codigos y Estafeta, DHL y Paquetexpress.\n";
    echo "Cada entrada tendra remitente, destinatario, cinco etapas de medidas, carga/descarga y salida entregada.\n";
    echo "Los procesos ocurriran en la misma semana. No se modificaran ni eliminaran datos existentes ni cuentas de usuario.\n";
    echo "Ejecuta con --apply para insertar los datos.\n";
    exit(0);
}

function findOrCreate(PDO $pdo, string $table, array $identity, array $data, array &$created): int
{
    $conditions = [];
    $parameters = [];
    foreach (array_keys($identity) as $index => $column) {
        $placeholder = ':identity' . $index;
        $conditions[] = '`' . $column . '` = ' . $placeholder;
        $parameters[$placeholder] = array_values($identity)[$index];
    }

    $select = $pdo->prepare(
        'SELECT id FROM `' . $table . '` WHERE ' . implode(' AND ', $conditions) . ' LIMIT 1'
    );
    $select->execute($parameters);
    $id = $select->fetchColumn();
    if ($id !== false) {
        return (int) $id;
    }

    $columns = array_keys($data);
    $placeholders = [];
    $values = [];
    foreach (array_values($data) as $index => $value) {
        $placeholder = ':value' . $index;
        $placeholders[] = $placeholder;
        $values[$placeholder] = $value;
    }
    $quotedColumns = array_map(static function (string $column): string {
        return '`' . $column . '`';
    }, $columns);

    $insert = $pdo->prepare(
        'INSERT INTO `' . $table . '` (' . implode(', ', $quotedColumns) . ') VALUES (' . implode(', ', $placeholders) . ')'
    );
    $insert->execute($values);
    $created[$table] = ($created[$table] ?? 0) + 1;

    return (int) $pdo->lastInsertId();
}

try {
    $dsn = $config['driver'] . ':host=' . $config['host']
        . ';port=' . $config['port']
        . ';dbname=' . $config['name']
        . ';charset=' . $config['char'];
    $pdo = new PDO($dsn, $config['user'], $config['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $adminQuery = $pdo->query("SELECT id FROM usuarios WHERE tipo = 'administrador' ORDER BY id LIMIT 1");
    $adminId = $adminQuery->fetchColumn();
    if ($adminId === false) {
        throw new RuntimeException('No existe una cuenta administradora; no se insertaron datos.');
    }
    $adminId = (int) $adminId;
    if ($adminId > 255) {
        throw new RuntimeException('El id del administrador no cabe en las columnas tinyint del esquema.');
    }

    $created = [];
    $clients = [];
    $repackers = [];
    $codes = [];
    $transports = [];
    $currentWeek = $now->setTime(0, 0)->modify('monday this week');

    for ($i = 1; $i <= 10; $i++) {
        $suffix = str_pad((string) $i, 2, '0', STR_PAD_LEFT);
        $stamp = $date($now->modify('-' . (20 - $i) . ' days'));
        $clients[$i] = findOrCreate($pdo, 'clientes', ['alias' => 'LOTE' . $suffix], [
            'nombre' => 'Cliente Lote ' . $suffix,
            'alias' => 'LOTE' . $suffix,
            'direccion' => $i . ' Calle de Prueba',
            'postal' => str_pad((string) (10000 + $i), 5, '0', STR_PAD_LEFT),
            'ciudad' => 'San Luis Potosi',
            'estado' => 'San Luis Potosi',
            'pais' => 'Mexico',
            'telefono' => '444555' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
            'correo' => 'cliente.lote' . $suffix . '@example.test',
            'notas' => 'Cliente de demostracion.',
            'usuario_id' => null,
            'created_at' => $stamp,
            'updated_at' => $stamp,
        ], $created);
    }

    for ($i = 1; $i <= 10; $i++) {
        $suffix = str_pad((string) $i, 2, '0', STR_PAD_LEFT);
        $stamp = $date($currentWeek->modify('-' . (20 - $i) . ' days'));
        $passcode = sha1('demo-lote-reempacador-' . $suffix . 'pk');
        $repackers[$i] = findOrCreate($pdo, 'reempacadores', ['passcode' => $passcode], [
            'nombre' => 'Reempacador Lote ' . $suffix,
            'passcode' => $passcode,
            'created_at' => $stamp,
            'updated_at' => $stamp,
            'deleted_at' => null,
        ], $created);
    }

    for ($i = 1; $i <= 2; $i++) {
        $suffix = str_pad((string) $i, 2, '0', STR_PAD_LEFT);
        $stamp = $date($currentWeek->modify('-' . (10 - $i) . ' days'));
        $codes[$i] = findOrCreate($pdo, 'codigos_reempacado', ['codigo' => 'LOTE' . $suffix], [
            'codigo' => 'LOTE' . $suffix,
            'descripcion' => 'Codigo de reempacado para datos de demostracion ' . $suffix,
            'created_at' => $stamp,
            'updated_at' => $stamp,
            'deleted_at' => null,
        ], $created);
    }

    $carrierData = [
        ['nombre' => 'Estafeta', 'web' => 'https://www.estafeta.com', 'telefonos' => '5550001000'],
        ['nombre' => 'DHL', 'web' => 'https://www.dhl.com', 'telefonos' => '5550002000'],
        ['nombre' => 'Paquetexpress', 'web' => 'https://www.paquetexpress.com.mx', 'telefonos' => '5550003000'],
    ];
    foreach ($carrierData as $index => $carrier) {
        $stamp = $date($currentWeek->modify('-' . (5 - $index) . ' days'));
        $transports[$index + 1] = findOrCreate($pdo, 'transportadoras', ['nombre' => $carrier['nombre']], [
            'nombre' => $carrier['nombre'],
            'web' => $carrier['web'],
            'telefonos' => $carrier['telefonos'],
            'created_at' => $stamp,
            'updated_at' => $stamp,
        ], $created);
    }

    $stageNames = ['cliente', 'bodega_usa', 'bodega_mex_entrada', 'bodega_mex_salida', 'transportadora'];
    $totalEntries = 25 * 30;

    for ($consolidatedNumber = 1; $consolidatedNumber <= 25; $consolidatedNumber++) {
        $suffix = str_pad((string) $consolidatedNumber, 2, '0', STR_PAD_LEFT);
        $weekIndex = intdiv($consolidatedNumber - 1, 5);
        $candidateWeekStart = $currentWeek->modify('-' . (4 - $weekIndex) . ' weeks');
        $candidateFirstEntryDay = $weekIndex === 0 ? 2 : 0;
        $candidateLastEntryDay = $weekIndex === 4
            ? max(0, min(2, (int) $now->format('N') - 3))
            : 2;
        $clientId = $clients[(($consolidatedNumber - 1) % count($clients)) + 1];
        $consolidatedCreatedAt = $candidateWeekStart->modify('+' . $candidateFirstEntryDay . ' days')->setTime(7, 0);
        $lastEntryAt = $candidateWeekStart->modify('+' . $candidateLastEntryDay . ' days')->setTime(9, 15);
        $consolidatedClosedAt = $lastEntryAt->modify('+40 hours');

        $consolidatedId = findOrCreate(
            $pdo,
            'consolidados',
            ['numero' => 'LOTE-CON-' . $suffix],
            [
                'numero' => 'LOTE-CON-' . $suffix,
                'alias_cliente_numero' => 0,
                'notificacion_at' => $date($consolidatedCreatedAt->modify('+30 minutes')),
                'palets' => ($consolidatedNumber % 8) + 1,
                'cliente_id' => $clientId,
                'cerrado_at' => $date($consolidatedClosedAt),
                'created_at' => $date($consolidatedCreatedAt),
                'updated_at' => $date($consolidatedClosedAt),
                'rastreador' => 'LOTE-TRACK-' . $suffix,
            ],
            $created
        );

        $storedConsolidatedQuery = $pdo->prepare('SELECT created_at FROM consolidados WHERE id = :id');
        $storedConsolidatedQuery->execute(['id' => $consolidatedId]);
        $storedConsolidatedAt = new DateTimeImmutable($storedConsolidatedQuery->fetchColumn(), $timezone);
        $weekStart = $storedConsolidatedAt->setTime(0, 0)->modify('monday this week');
        $firstEntryDay = (int) $storedConsolidatedAt->format('N') - 1;
        $isCurrentWeek = $weekStart->format('Y-m-d') === $currentWeek->format('Y-m-d');
        $lastEntryDay = $isCurrentWeek ? $candidateLastEntryDay : 2;
        $availableDays = max(1, $lastEntryDay - $firstEntryDay + 1);

        for ($entryNumber = 1; $entryNumber <= 30; $entryNumber++) {
            $globalEntryNumber = (($consolidatedNumber - 1) * 30) + $entryNumber;
            $entrySuffix = str_pad((string) $globalEntryNumber, 5, '0', STR_PAD_LEFT);
            $entryDay = $firstEntryDay + intdiv(($entryNumber - 1) * $availableDays, 30);
            $minuteOffset = (($entryNumber - 1) % 6) * 15;
            $entryCreatedAt = $weekStart->modify('+' . $entryDay . ' days')
                ->setTime(8, 0)
                ->modify('+' . $minuteOffset . ' minutes');

            $usaAt = $entryCreatedAt->modify('+2 hours');
            $mexEntryAt = $entryCreatedAt->modify('+12 hours');
            $crossingAt = $entryCreatedAt->modify('+10 hours');
            $repackedAt = $entryCreatedAt->modify('+22 hours');
            $mexExitAt = $entryCreatedAt->modify('+28 hours');
            $carrierAt = $entryCreatedAt->modify('+36 hours');
            $receivedAt = $entryCreatedAt->modify('+40 hours');

            $repackerNumber = (($globalEntryNumber - 1) % count($repackers)) + 1;
            $codeNumber = (($globalEntryNumber - 1) % count($codes)) + 1;
            $carrierNumber = (($globalEntryNumber - 1) % count($transports)) + 1;
            $entryStamp = $date($entryCreatedAt);

            $entryId = findOrCreate($pdo, 'entradas', ['numero' => 'LOTE-ENT-' . $entrySuffix], [
                'numero' => 'LOTE-ENT-' . $entrySuffix,
                'observaciones' => json_encode([[
                    'observacion' => 'Entrada de demostracion del lote ' . $suffix . '.',
                    'creado_at' => $entryStamp,
                    'nombre' => 'kiko reyna',
                    'usuario' => $adminId,
                ]], JSON_UNESCAPED_UNICODE),
                'alias_cliente_numero' => $globalEntryNumber % 2,
                'cliente_id' => $clientId,
                'consolidado_id' => $consolidatedId,
                'en_bodega_usa_by' => $adminId,
                'en_bodega_mex_by' => $adminId,
                'conductor_id' => null,
                'vehiculo_id' => null,
                'numero_vuelta' => (($globalEntryNumber - 1) % 5) + 1,
                'cruce_at' => $date($crossingAt),
                'reempacador_id' => $repackers[$repackerNumber],
                'codigor_id' => $codes[$codeNumber],
                'reempacado_at' => $date($repackedAt),
                'recibido_at' => $date($receivedAt),
                'creado_by' => $adminId,
                'actualizado_by' => $adminId,
                'created_at' => $entryStamp,
                'updated_at' => $date($receivedAt),
            ], $created);

            findOrCreate($pdo, 'entrada_remitente', ['entrada_id' => $entryId], [
                'nombre' => 'Remitente Lote ' . $entrySuffix,
                'telefono' => '555' . str_pad((string) $globalEntryNumber, 7, '0', STR_PAD_LEFT),
                'direccion' => $globalEntryNumber . ' Avenida de Prueba',
                'postal' => str_pad((string) (20000 + ($globalEntryNumber % 70000)), 5, '0', STR_PAD_LEFT),
                'ciudad' => 'Laredo',
                'estado' => 'Texas',
                'pais' => 'Estados Unidos',
                'actualizado_by' => $adminId,
                'entrada_id' => $entryId,
                'created_at' => $entryStamp,
                'updated_at' => $entryStamp,
            ], $created);

            findOrCreate($pdo, 'entrada_destinatario', ['entrada_id' => $entryId], [
                'nombre' => 'Destinatario Lote ' . $entrySuffix,
                'telefono' => '444' . str_pad((string) $globalEntryNumber, 7, '0', STR_PAD_LEFT),
                'direccion' => $globalEntryNumber . ' Calle de Entrega',
                'postal' => str_pad((string) (30000 + ($globalEntryNumber % 60000)), 5, '0', STR_PAD_LEFT),
                'referencias' => 'Domicilio de demostracion.',
                'ciudad' => 'San Luis Potosi',
                'estado' => 'San Luis Potosi',
                'pais' => 'Mexico',
                'verificacion_at' => $date($receivedAt),
                'actualizado_by' => $adminId,
                'entrada_id' => $entryId,
                'created_at' => $entryStamp,
                'updated_at' => $date($receivedAt),
            ], $created);

            $stageDates = [$entryCreatedAt, $usaAt, $mexEntryAt, $mexExitAt, $carrierAt];
            foreach ($stageNames as $stageIndex => $stage) {
                findOrCreate(
                    $pdo,
                    'entrada_medidas',
                    ['entrada_id' => $entryId, 'etapa' => $stage],
                    [
                        'etapa' => $stage,
                        'peso' => number_format(5 + (($globalEntryNumber + $stageIndex) % 30) / 2, 3, '.', ''),
                        'medida_peso' => $globalEntryNumber % 2 === 0 ? 'libras' : 'kilogramos',
                        'ancho' => 10 + ($globalEntryNumber % 15),
                        'altura' => 12 + ($globalEntryNumber % 12),
                        'profundidad' => 8 + ($globalEntryNumber % 10),
                        'medida_volumen' => $globalEntryNumber % 2 === 0 ? 'pulgadas' : 'centimetros',
                        'actualizado_by' => $adminId,
                        'entrada_id' => $entryId,
                        'created_at' => $date($stageDates[$stageIndex]),
                        'updated_at' => $date($stageDates[$stageIndex]),
                    ],
                    $created
                );
            }

            findOrCreate($pdo, 'entradas_drop_off_stage', ['entrada_id' => $entryId], [
                'entrada_id' => $entryId,
                'fecha_carga' => $date($mexExitAt),
                'fecha_descarga' => $date($carrierAt),
                'creado_por' => $adminId,
                'actualizado_por' => $adminId,
                'created_at' => $entryStamp,
                'updated_at' => $date($carrierAt),
            ], $created);

            findOrCreate($pdo, 'salidas', ['entrada_id' => $entryId], [
                'rastreo' => 'LOTE-TRK-' . $entrySuffix,
                'confirmacion' => 'LOTE-CONF-' . $entrySuffix,
                'status' => 'entregado',
                'incidente' => null,
                'cobertura' => $globalEntryNumber % 2 === 0 ? 'domicilio' : 'ocurre',
                'direccion' => $globalEntryNumber . ' Calle de Entrega',
                'postal' => str_pad((string) (30000 + ($globalEntryNumber % 60000)), 5, '0', STR_PAD_LEFT),
                'ciudad' => 'San Luis Potosi',
                'estado' => 'San Luis Potosi',
                'pais' => 'Mexico',
                'notas' => 'Salida de demostracion con ' . $carrierData[$carrierNumber - 1]['nombre'] . '.',
                'transportadora_id' => $transports[$carrierNumber],
                'actualizado_by' => $adminId,
                'impresiones' => 0,
                'entrada_id' => $entryId,
                'created_at' => $date($mexExitAt),
                'updated_at' => $date($receivedAt),
            ], $created);
        }
    }

    echo "Seeder aplicado en {$config['name']}.\n";
    echo "Se conservaron las cuentas existentes; las referencias de proceso usan el administrador ID {$adminId}.\n";
    foreach ($created as $table => $count) {
        echo "{$table}: {$count} nuevos registros\n";
    }

    $totals = $pdo->query(
        "SELECT r.nombre, COUNT(e.id) AS total "
        . "FROM reempacadores r "
        . "LEFT JOIN entradas e ON e.reempacador_id = r.id AND e.numero LIKE 'LOTE-ENT-%' "
        . "WHERE r.nombre LIKE 'Reempacador Lote %' "
        . "GROUP BY r.id, r.nombre ORDER BY r.nombre"
    );
    echo "Reempaques por reempacador:\n";
    foreach ($totals as $row) {
        echo $row['nombre'] . ': ' . $row['total'] . "\n";
    }
    echo "25 consolidados x 30 entradas = {$totalEntries} entradas. Reejecucion segura: los registros LOTE existentes no se duplican.\n";
} catch (Throwable $e) {
    fwrite(STDERR, 'Error al ejecutar el seeder: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}
