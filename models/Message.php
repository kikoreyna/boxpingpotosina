<?php namespace Models;

use System\Core\Model;

class Message extends Model
{
    protected $table = 'entrada_mensajes';
    protected $timestamps = true;

    public function withRelations($value, $column = 'entrada_id')
    {
        $query = "SELECT m.*,
                  u.nombre AS 'usuario_nombre', u.tipo AS 'usuario_tipo' 
                  FROM {$this->table} AS m
                  INNER JOIN usuarios AS u
                  ON u.id = m.usuario_id
                  WHERE m.{$column} = {$value}";
        return $this->raw($query, true);
    }
}
