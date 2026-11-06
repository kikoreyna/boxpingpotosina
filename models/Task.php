<?php namespace Models;

use System\Core\Model;

class Task extends Model
{
    protected $table = 'tareas';
    protected $timestamps = true;

    public function withRelations(array $filters = [])
    {
        $where = [];
        if( !empty($filters['user_id']) )
            $where[] = 't.usuario_id = ' . (int) $filters['user_id'];

        if( !empty($filters['status']) )
            $where[] = "t.estatus = '" . ($filters['status'] === 'completada' ? 'completada' : 'pendiente') . "'";

        $concat = $where ? ' WHERE ' . implode(' AND ', $where) : '';

        $query = "SELECT t.*, e.numero AS 'entrada_numero', e.consolidado_id AS 'consolidado_id',
                    u.nombre AS 'usuario_nombre', a.nombre AS 'asignado_por_nombre'
                FROM {$this->table} AS t
                INNER JOIN entradas AS e ON e.id = t.entrada_id
                INNER JOIN usuarios AS u ON u.id = t.usuario_id
                LEFT JOIN usuarios AS a ON a.id = t.asignado_by
                {$concat}
                ORDER BY t.estatus = 'completada', t.created_at DESC, t.id DESC
                LIMIT 500";

        return $this->raw($query, true);
    }

    public function openDuplicate($entry_id, $user_id, $description)
    {
        $stmt = $this->pdo->prepare(
            "SELECT id FROM {$this->table}
            WHERE entrada_id = :entry AND usuario_id = :user AND descripcion = :description AND estatus = 'pendiente'
            LIMIT 1"
        );
        $stmt->execute([':entry' => $entry_id, ':user' => $user_id, ':description' => $description]);
        return (bool) $stmt->fetch();
    }

    public function openForEntry($entry_id)
    {
        $query = "SELECT t.*, u.nombre AS 'usuario_nombre'
                FROM {$this->table} AS t
                INNER JOIN usuarios AS u ON u.id = t.usuario_id
                WHERE t.entrada_id = " . (int) $entry_id . " AND t.estatus = 'pendiente'
                ORDER BY t.id DESC";

        return $this->raw($query, true);
    }

    public function pendingCount($user_id = null)
    {
        $query = "SELECT COUNT(*) AS total FROM {$this->table} WHERE estatus = 'pendiente'";
        if( !is_null($user_id) )
            $query .= ' AND usuario_id = ' . (int) $user_id;

        $result = $this->raw($query, true);
        return (int) $result[0]->total;
    }
}
