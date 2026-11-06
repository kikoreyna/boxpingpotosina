<?php namespace Models;

use System\Core\Model;

class Consolidated extends Model
{
    protected $table = 'consolidados';
    protected $entries_table = 'entradas';
    protected $clients_table = 'clientes';
    protected $entriesdest_table = 'entrada_destinatario'; //kiko
    protected $timestamps = true;
    
    public function allWithRelations($order = 'DESC')
    {
        $query = $this->getQueryRelations( " ORDER BY cs.id {$order}" );
        return $this->raw($query, true);
    }

    public function allWithRelationsFiltered(array $filters = [], $order = 'DESC')
    {
        $concat = " WHERE DATE(cs.created_at) BETWEEN '{$filters['from']}' AND '{$filters['to']}'";
        // $concat = " WHERE cs.created_at BETWEEN '{$filters['from']} 00:00:00' AND '{$filters['to']} 23:59:59'";

        if( !is_null($filters['client']) && $filters['client'] <> 'todos' )
            $concat .= " AND cs.cliente_id = {$filters['client']}";

        if( $filters['status'] <> 'cualquiera' )
        {
            $filter = $filters['status'] === 'abierto' ? 'IS NULL' : 'IS NOT NULL';
            $concat .= " AND cs.cerrado_at {$filter}";
        }

        $concat .= " ORDER BY cs.id {$order}";

        $query = $this->getQueryRelations( $concat );

        // die( var_dump($query) );

        return $this->raw($query, true);
    }

    public function withRelations($id, $column = 'id')
    {
        $query = "SELECT cs.*, 
                    c.id AS 'cliente_id', c.nombre AS 'cliente_nombre', c.alias AS 'cliente_alias' 
                FROM {$this->table} AS cs 
                INNER JOIN {$this->clients_table} AS c
                ON c.id = cs.cliente_id
                WHERE cs.{$column} = '{$id}' LIMIT 1";
        $result = $this->raw($query, true);
        return array_shift( $result );       
    }

    private function getQueryRelations( $concat = '' )
    {

        // OLD
        return "
            SELECT 
                cs.*,
                c.id AS 'cliente_id', 
                c.nombre AS 'cliente_nombre', 
                c.alias AS 'cliente_alias',
                (SELECT COUNT(*) FROM {$this->entries_table} WHERE consolidado_id = cs.id) AS 'entries_count',
                (SELECT COUNT(*) FROM {$this->entries_table} WHERE consolidado_id = cs.id AND en_bodega_usa_by IS NOT NULL) AS 'entries_warehouse_usa',
                (SELECT COUNT(*) FROM {$this->entries_table} WHERE consolidado_id = cs.id AND en_bodega_mex_by IS NOT NULL) AS 'entries_warehouse_mex',
                (SELECT COUNT(*) FROM {$this->entries_table} WHERE consolidado_id = cs.id AND codigor_id IS NOT NULL) AS 'entries_repackaged'
            FROM {$this->table} AS cs
            INNER JOIN {$this->clients_table} AS c ON cs.cliente_id = c.id 
        " . $concat;
        // PROBLEMA LAS 4 SUBCONSULTAS SELECT COUNT(*)... HACE LENTO LA QUERY MAIN"

        // NEW 30-06-2025
        return "
            SELECT 
                cs.*,
                c.id AS 'cliente_id', 
                c.nombre AS 'cliente_nombre', 
                c.alias AS 'cliente_alias'
            FROM {$this->table} AS cs
            INNER JOIN {$this->clients_table} AS c ON cs.cliente_id = c.id 
        " . $concat;
        
        // GPT
        // return "
        //     SELECT 
        //         cs.*, 
        //         c.id AS cliente_id, 
        //         c.nombre AS cliente_nombre, 
        //         c.alias AS cliente_alias,
        //         COUNT(e.id) AS entries_count,
        //         COUNT(e.en_bodega_usa_by) AS entries_warehouse_usa,
        //         COUNT(e.en_bodega_mex_by) AS entries_warehouse_mex,
        //         COUNT(e.codigor_id) AS entries_repackaged
        //     FROM {$this->table} AS cs
        //     INNER JOIN $this->clients_table AS c ON cs.cliente_id = c.id
        //     LEFT JOIN $this->entries_table AS e ON e.consolidado_id = cs.id 
        // " . $concat; 
    }
    
    
}




# $query .= " WHERE CAST(cs.created_at AS DATE) BETWEEN '{$filters['from']}' AND '{$filters['to']}'";