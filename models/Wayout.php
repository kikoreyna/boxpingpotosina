<?php namespace Models;

use System\Core\Model;

class Wayout extends Model
{
    protected $table = 'salidas';
    protected $timestamps = true;

    public function allWithRelations($order = 'DESC')
    {
        $query = $this->getQueryRelations( "ORDER BY s.id {$order}" );
        return $this->raw($query, true);
    }

    public function allWithRelationsFiltered(array $filters, $order = 'DESC')
    {
        $concat= " WHERE DATE(s.created_at) BETWEEN '{$filters['from']}' AND '{$filters['to']}'";

        if( !is_null($filters['client']) && $filters['client'] <> 'todos' )
            $concat .= " AND e.cliente_id = {$filters['client']}";

        if( !is_null($filters['transport']) && $filters['transport'] <> 'cualquier' )
            $concat .= " AND s.transportadora_id = {$filters['transport']}";

        if( !is_null($filters['cover']) && $filters['cover'] <> 'cualquier' )
            $concat .= " AND s.cobertura = '{$filters['cover']}'";

        if( !is_null($filters['status']) && $filters['status'] <> 'cualquier' )
            $concat .= " AND s.status = '{$filters['status']}'";

        if( !is_null($filters['incident']) && $filters['incident'] <> 'cualquier' )
            $concat .= " AND s.incidente = '{$filters['incident']}'";

        $concat .= " ORDER BY s.id {$order}";
        $query = $this->getQueryRelations( $concat );
        return $this->raw($query, true);
    }

    public function withRelations($value, $column = 'id')
    {
        $query = $this->getQueryRelations( "WHERE s.{$column} = '{$value}'");
        return $this->raw($query, true);
    }

    private function getQueryRelations( $concat = '' )
    {
        return "SELECT s.*,
                u.nombre AS 'usuario_nombre', u.email AS 'usuario_email',
                t.nombre AS 'transportadora_nombre', t.web AS 'transportadora_web', 
                e.numero AS 'entrada_numero', e.alias_cliente_numero AS 'entrada_alias_cliente_numero',
                c.id AS 'cliente_id', c.nombre AS 'cliente_nombre', c.alias AS 'cliente_alias',
                cs.id AS 'consolidado_id', cs.numero AS 'consolidado_numero', cs.alias_cliente_numero AS 'consolidado_alias_cliente_numero',
                a.nombre AS 'destinatario_nombre', a.telefono AS 'destinatario_telefono', a.direccion AS 'destinatario_direccion', a.postal AS 'destinatario_postal', a.referencias AS 'destinatario_referencias', a.ciudad AS 'destinatario_ciudad', a.estado AS 'destinatario_estado', a.pais AS 'destinatario_pais' 
                FROM {$this->table} AS s
                LEFT JOIN transportadoras AS t ON s.transportadora_id = t.id
                INNER JOIN entradas AS e ON s.entrada_id = e.id
                INNER JOIN usuarios AS u ON s.actualizado_by = u.id
                LEFT JOIN clientes AS c ON e.cliente_id = c.id 
                LEFT JOIN consolidados AS cs ON e.consolidado_id = cs.id
                LEFT JOIN entrada_destinatario AS a ON e.id = a.entrada_id "
                . $concat;
    }
}