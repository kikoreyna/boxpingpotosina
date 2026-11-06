<?php namespace Models;

use System\Core\Model;
use \PDO;

class Client extends Model
{
    protected $table = 'clientes';
    protected $timestamps = true;
    
    public function allWithRelations()
    {
        $query = "SELECT c.*,
                    u.id AS 'cliente_usuario_id', u.email AS 'cliente_usuario_email', u.updated_at AS 'cliente_usuario_updated_at'
                FROM {$this->table} AS c
                LEFT JOIN usuarios AS u
                ON c.usuario_id = u.id AND u.tipo = 'cliente'
                WHERE c.deleted_at IS NULL ORDER BY c.id DESC";
        return $this->raw($query, true);        
    }

    public function withRelations($id, $column = 'id')
    {
        $query = "SELECT c.*,
                    u.id AS 'cliente_usuario_id', u.email AS 'cliente_usuario_email', u.updated_at AS 'cliente_usuario_updated_at'
                FROM {$this->table} AS c
                LEFT JOIN usuarios AS u
                ON c.usuario_id = u.id
                AND u.tipo = 'cliente'
                WHERE c.id = {$id} LIMIT 1";
        $result = $this->raw($query, true);
        return array_pop( $result );  
    }

    public function save($data)
    {
        $this->query = "INSERT INTO {$this->table} (nombre,alias,direccion,ciudad,estado,pais,telefono,correo,notas)
                        VALUES (:nombre,:alias,:direccion,:ciudad,:estado,:pais,:telefono,:correo,:notas)";
        $stmt = $this->pdo->prepare( $this->query );
        $stmt->bindValue(':nombre', $data['nombre']);
        $stmt->bindValue(':alias', $data['alias']);
        $stmt->bindValue(':direccion', $data['direccion']);
        $stmt->bindValue(':ciudad', $data['ciudad']);
        $stmt->bindValue(':estado', $data['estado']);
        $stmt->bindValue(':pais', $data['pais']);
        $stmt->bindValue(':telefono', $data['telefono']);
        $stmt->bindValue(':correo', $data['correo']);
        $stmt->bindValue(':notas', $data['notas']);
        
        if( $result = $stmt->execute() )
            return $this->pdo->lastInsertId();
        
        return $result;
    }
}