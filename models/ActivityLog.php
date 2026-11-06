<?php namespace Models;

use \PDO;
use \Throwable;
use System\Core\Connection;

class ActivityLog
{
    // Tablas auditadas y su nombre legible
    const TABLES = [
        'entradas'                => 'Entrada',
        'consolidados'            => 'Consolidado',
        'usuarios'                => 'Usuario',
        'clientes'                => 'Cliente',
        'salidas'                 => 'Salida',
        'transportadoras'         => 'Transportadora',
        'reempacadores'           => 'Reempacador',
        'codigos_reempacado'      => 'Código de reempacado',
        'conductores'             => 'Conductor',
        'vehiculos'               => 'Vehículo',
        'tareas'                  => 'Tarea',
        'bodegas_mex'             => 'Bodega MEX',
    ];

    // Tablas que solo se auditan en ediciones/eliminaciones (partes de una entrada)
    const EDIT_ONLY_TABLES = [
        'entrada_medidas'         => 'Medidas de entrada',
        'entrada_remitente'       => 'Remitente de entrada',
        'entrada_destinatario'    => 'Destinatario de entrada',
        'entradas_drop_off_stage' => 'Carga a transportadora',
    ];

    const ACTIONS = [
        'creacion'    => 'Creación',
        'edicion'     => 'Edición',
        'eliminacion' => 'Eliminación',
        'observacion' => 'Observación',
    ];

    // Columnas que identifican el registro, en orden de preferencia (nunca contraseñas)
    const LABEL_COLUMNS = ['numero', 'nombre', 'codigo', 'alias', 'email', 'descripcion'];

    const IGNORED_COLUMNS = ['updated_at', 'created_at', 'observaciones'];

    const REFERENCES = [
        'entrada_id'       => ['Entrada', 'entradas', 'numero'],
        'cliente_id'       => ['Cliente', 'clientes', 'nombre'],
        'consolidado_id'   => ['Consolidado', 'consolidados', 'numero'],
        'transportadora_id'=> ['Transportadora', 'transportadoras', 'nombre'],
        'bodega_mex_id'    => ['Bodega MEX', 'bodegas_mex', 'nombre'],
        'conductor_id'     => ['Conductor', 'conductores', 'nombre'],
        'vehiculo_id'      => ['Vehículo', 'vehiculos', 'alias'],
        'reempacador_id'   => ['Reempacador', 'reempacadores', 'nombre'],
        'codigor_id'       => ['Código de reempacado', 'codigos_reempacado', 'codigo'],
        'usuario_id'       => ['Usuario', 'usuarios', 'nombre'],
        'en_bodega_usa_by' => ['En bodega USA por', 'usuarios', 'nombre'],
        'en_bodega_mex_by' => ['En bodega MEX por', 'usuarios', 'nombre'],
        'creado_by'        => ['Creado por', 'usuarios', 'nombre'],
        'actualizado_by'   => ['Actualizado por', 'usuarios', 'nombre'],
        'asignado_by'      => ['Asignado por', 'usuarios', 'nombre'],
        'creado_por'       => ['Creado por', 'usuarios', 'nombre'],
        'actualizado_por'  => ['Actualizado por', 'usuarios', 'nombre'],
    ];

    private static $referenceCache = [];

    // Las observaciones se registran aparte, con su texto completo
    static public function recordObservation($entry_id, $number, $text)
    {
        self::record('observacion', 'entradas', $entry_id, ['numero' => $number], (string) $text);
    }

    static public function labels()
    {
        return self::TABLES + self::EDIT_ONLY_TABLES;
    }

    static public function audits($table)
    {
        return array_key_exists($table, self::labels());
    }

    static public function auditsCreation($table)
    {
        return array_key_exists($table, self::TABLES);
    }

    static public function recordUpdate($table, $before, array $data)
    {
        $before = (array) $before;
        $changes = [];

        foreach($data as $column => $value)
        {
            if( in_array($column, self::IGNORED_COLUMNS) || !array_key_exists($column, $before) )
                continue;

            if( (string) $before[$column] === (string) $value )
                continue;

            if( self::isSecret($column) )
                $changes[] = "{$column}: (modificado)";
            elseif( isset(self::REFERENCES[$column]) )
                $changes[] = self::REFERENCES[$column][0] . ': '
                    . self::referenceValue($column, $before[$column]) . ' → ' . self::referenceValue($column, $value);
            else
                $changes[] = "{$column}: " . self::short($before[$column]) . ' → ' . self::short($value);
        }

        if( $changes )
            self::record('edicion', $table, $before['id'] ?? null, array_merge($before, $data), implode("\n", $changes));
    }

    static public function recordDelete($table, $before, $note = '')
    {
        $before = (array) $before;
        self::record('eliminacion', $table, $before['id'] ?? null, $before, $note);
    }

    static private function isSecret($column)
    {
        return stripos($column, 'pass') !== false;
    }

    static private function short($value)
    {
        if( is_null($value) )
            return 'vacío';

        $value = trim((string) $value);
        return $value === '' ? 'vacío' : (mb_strlen($value) > 60 ? mb_substr($value, 0, 57) . '...' : $value);
    }

    static private function referenceValue($column, $value)
    {
        if( $value === null || $value === '' || $value === 'vacío' )
            return 'vacío';

        if( !is_scalar($value) || !ctype_digit((string) $value) )
            return self::short($value);

        [, $table, $label] = self::REFERENCES[$column];
        $id = (string) $value;
        if( !array_key_exists($id, self::$referenceCache[$table] ?? []) )
        {
            try
            {
                $stmt = Connection::getInstance()->prepare("SELECT {$label} FROM {$table} WHERE id = ?");
                $stmt->execute([$id]);
                self::$referenceCache[$table][$id] = $stmt->fetchColumn();
            }
            catch(\PDOException $e)
            {
                error_log('Error al consultar referencia de bitácora: ' . $e->getMessage());
                return "no disponible (ID {$id})";
            }
        }

        $name = self::$referenceCache[$table][$id];
        return $name !== false && $name !== null && $name !== ''
            ? self::short($name) : "no disponible (ID {$id})";
    }

    private function historicalChanges($changes)
    {
        if( !$changes )
            return $changes;

        return preg_replace_callback('/^([a-z_]+): ([^\r\n]*?) → ([^\r\n]*)$/mu', function($match) {
            $column = $match[1];
            if( !isset(self::REFERENCES[$column]) )
                return $match[0];

            return self::REFERENCES[$column][0] . ': '
                . self::referenceValue($column, $match[2]) . ' → ' . self::referenceValue($column, $match[3]);
        }, $changes);
    }

    private function preloadHistoricalReferences(array $logs)
    {
        $references = [];
        foreach( $logs as $log )
        {
            if( $log->accion !== 'edicion' || !$log->cambios )
                continue;

            preg_match_all('/^([a-z_]+): ([^\r\n]*?) → ([^\r\n]*)$/mu', $log->cambios, $matches, PREG_SET_ORDER);
            foreach( $matches as $match )
            {
                if( !isset(self::REFERENCES[$match[1]]) )
                    continue;

                [, $table, $label] = self::REFERENCES[$match[1]];
                foreach( [$match[2], $match[3]] as $id )
                {
                    if( ctype_digit($id) && !array_key_exists($id, self::$referenceCache[$table] ?? []) )
                        $references[$table]['ids'][$id] = $id;
                }
                if( isset($references[$table]) )
                    $references[$table]['label'] = $label;
            }
        }

        foreach( $references as $table => $reference )
        {
            foreach( array_chunk(array_values($reference['ids']), 500) as $ids )
            {
                try
                {
                    $placeholders = implode(',', array_fill(0, count($ids), '?'));
                    $stmt = Connection::getInstance()->prepare("SELECT id, {$reference['label']} AS label FROM {$table} WHERE id IN ({$placeholders})");
                    $stmt->execute($ids);
                    foreach( $ids as $id )
                        self::$referenceCache[$table][$id] = false;
                    while( $row = $stmt->fetch(PDO::FETCH_ASSOC) )
                        self::$referenceCache[$table][$row['id']] = $row['label'];
                }
                catch(\PDOException $e)
                {
                    error_log('Error al consultar referencias históricas de bitácora: ' . $e->getMessage());
                }
            }
        }
    }

    static public function record($action, $table, $record_id, array $data = [], $changes = '')
    {
        if( !self::audits($table) )
            return;

        try
        {
            $detail = '';
            foreach(self::LABEL_COLUMNS as $column)
            {
                if( isset($data[$column]) && $data[$column] !== '' )
                {
                    $detail = (string) $data[$column];
                    break;
                }
            }

            if( !empty($data['entrada_id']) )
            {
                $entry_detail = 'Entrada: ' . self::referenceValue('entrada_id', $data['entrada_id']);
                $detail = $detail !== '' ? $entry_detail . ' | ' . $detail : $entry_detail;
            }

            $user = isset($_SESSION['user']) && is_array($_SESSION['user']) ? $_SESSION['user'] : [];

            $stmt = Connection::getInstance()->prepare(
                "INSERT INTO bitacora (accion, tabla, registro_id, detalle, cambios, usuario_id, usuario_nombre, usuario_tipo, ip, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $action,
                $table,
                $record_id ?: null,
                mb_substr($detail, 0, 255),
                $changes !== '' ? $changes : null,
                $user['id'] ?? null,
                $user['name'] ?? null,
                $user['type'] ?? null,
                $_SERVER['REMOTE_ADDR'] ?? null,
                date('Y-m-d H:i:s'),
            ]);
        }
        catch(Throwable $e)
        {
            // La bitácora nunca debe interrumpir la operación original
            error_log('Error al registrar movimiento de bitácora: ' . $e->getMessage());
        }
    }

    public function search(array $filters = [])
    {
        $where = [];
        $params = [];

        if( !empty($filters['table']) && self::audits($filters['table']) )
        {
            $where[] = 'b.tabla = ?';
            $params[] = $filters['table'];
        }

        if( !empty($filters['action']) && array_key_exists($filters['action'], self::ACTIONS) )
        {
            $where[] = 'b.accion = ?';
            $params[] = $filters['action'];
        }

        if( !empty($filters['user_id']) && is_numeric($filters['user_id']) )
        {
            $where[] = 'b.usuario_id = ?';
            $params[] = (int) $filters['user_id'];
        }

        if( !empty($filters['from']) )
        {
            $where[] = 'b.created_at >= ?';
            $params[] = $filters['from'] . ' 00:00:00';
        }

        if( !empty($filters['to']) )
        {
            $where[] = 'b.created_at <= ?';
            $params[] = $filters['to'] . ' 23:59:59';
        }

        // Los registros anteriores guardaban "Entrada #ID" en lugar del número.
        $detail = "CASE WHEN b.detalle REGEXP '^Entrada #[0-9]+$'
                   AND b.tabla IN ('salidas', 'entrada_medidas', 'entrada_remitente', 'entrada_destinatario', 'entradas_drop_off_stage')
                   THEN CONCAT('Entrada: ', COALESCE(e.numero, CONCAT('no disponible (ID ', SUBSTRING(b.detalle, 10), ')')))
                   ELSE b.detalle END";
        $from = " FROM bitacora AS b
                  LEFT JOIN entradas AS e ON b.detalle REGEXP '^Entrada #[0-9]+$'
                  AND e.id = CAST(SUBSTRING(b.detalle, 10) AS UNSIGNED)
                  AND b.tabla IN ('salidas', 'entrada_medidas', 'entrada_remitente', 'entrada_destinatario', 'entradas_drop_off_stage')";

        if( !empty($filters['q']) )
        {
            $where[] = "{$detail} LIKE ?";
            $params[] = '%' . $filters['q'] . '%';
        }

        $sql = "SELECT b.*, {$detail} AS detalle_visible" . $from
             . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY b.id DESC LIMIT 500';
        $stmt = Connection::getInstance()->prepare($sql);
        $stmt->execute($params);
        $logs = $stmt->fetchAll(PDO::FETCH_OBJ);
        $this->preloadHistoricalReferences($logs);
        foreach( $logs as $log )
        {
            $log->cambios_visible = $log->accion === 'edicion'
                ? $this->historicalChanges($log->cambios) : $log->cambios;
        }
        return $logs;
    }
}
