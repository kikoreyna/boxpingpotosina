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
    ];

    // Columnas que identifican el registro, en orden de preferencia (nunca contraseñas)
    const LABEL_COLUMNS = ['numero', 'nombre', 'codigo', 'alias', 'email', 'descripcion'];

    const IGNORED_COLUMNS = ['updated_at', 'created_at'];

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

            $changes[] = self::isSecret($column)
                ? "{$column}: (modificado)"
                : "{$column}: " . self::short($before[$column]) . ' → ' . self::short($value);
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

            if( $detail === '' && !empty($data['entrada_id']) )
                $detail = 'Entrada #' . $data['entrada_id'];

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
        }
    }

    public function search(array $filters = [])
    {
        $where = [];
        $params = [];

        if( !empty($filters['table']) && self::audits($filters['table']) )
        {
            $where[] = 'tabla = ?';
            $params[] = $filters['table'];
        }

        if( !empty($filters['action']) && array_key_exists($filters['action'], self::ACTIONS) )
        {
            $where[] = 'accion = ?';
            $params[] = $filters['action'];
        }

        if( !empty($filters['user_id']) && is_numeric($filters['user_id']) )
        {
            $where[] = 'usuario_id = ?';
            $params[] = (int) $filters['user_id'];
        }

        if( !empty($filters['from']) )
        {
            $where[] = 'created_at >= ?';
            $params[] = $filters['from'] . ' 00:00:00';
        }

        if( !empty($filters['to']) )
        {
            $where[] = 'created_at <= ?';
            $params[] = $filters['to'] . ' 23:59:59';
        }

        if( !empty($filters['q']) )
        {
            $where[] = 'detalle LIKE ?';
            $params[] = '%' . $filters['q'] . '%';
        }

        $sql = 'SELECT * FROM bitacora' . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY id DESC LIMIT 500';
        $stmt = Connection::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
