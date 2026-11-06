<?php namespace Models;

use System\Core\Model;

class Entry extends Model
{
    protected $timestamps = true;
    protected $table = 'entradas';
    private $remitter_table = 'entrada_remitente';
    private $addressee_table = 'entrada_destinatario';
    private $measures_table = 'entrada_medidas';
    private $clients_table = 'clientes';
    private $consolidated_table = 'consolidados';
    private $drivers_table = 'conductores';
    private $vehicles_table = 'vehiculos';
    private $repackers_table = 'reempacadores';
    private $codesr_table = 'codigos_reempacado';
    private $users_table = 'usuarios';
    private $wayouts_table = 'salidas';
    private $transports_table = 'transportadoras';
    
    public function allWithRelations($order = 'DESC')
    {
        $query = $this->getQueryRelations( " ORDER BY e.id {$order} GROUP BY e.id " ); 
        return $this->raw($query, true);
    }

    public function allWithRelationsFiltered(array $filters = [], $order = 'DESC')
    {
        $column_at = 'created_at';
        $query_id = '';

        if( !is_null($filters['driver']) )
        {
            $column_at = 'cruce_at';
            $query_id = " AND e.conductor_id = {$filters['driver']}";
        }

        if( !is_null($filters['repacker']) )
        {
            $column_at = 'reempacado_at';
            $query_id = " AND e.reempacador_id = {$filters['repacker']}";
        }

        $concat = " WHERE DATE(e.{$column_at}) BETWEEN '{$filters['from']}' AND '{$filters['to']}' {$query_id} ";
        if( !is_null($filters['repacker']) )
        {
            $time_from = isset($filters['repacked_from_time']) ? $filters['repacked_from_time'] : '00:00';
            $time_to = isset($filters['repacked_to_time']) ? $filters['repacked_to_time'] : '23:59';
            $time_filter = $time_from <= $time_to
                ? "TIME(e.reempacado_at) BETWEEN '{$time_from}:00' AND '{$time_to}:59'"
                : "(TIME(e.reempacado_at) >= '{$time_from}:00' OR TIME(e.reempacado_at) <= '{$time_to}:59')";
            $concat .= " AND {$time_filter} ";
        }

        if( !is_null($filters['client']) && $filters['client'] <> 'todos' )
        {
            $concat .= is_numeric($filters['client'])
                    ? " AND e.cliente_id = {$filters['client']}"
                    : " AND e.cliente_id IS NULL";
        }

        if( !is_null($filters['consolidated']) && $filters['consolidated'] <> 'cualquiera' )
        {
            $filter = $filters['consolidated'] === 'si' ? 'IS NOT NULL' : 'IS NULL';
            $concat .= " AND e.consolidado_id {$filter}";
        }

        if( !is_null($filters['wayout']) && $filters['wayout'] <> 'cualquiera' )
        {
            if( $filters['wayout'] === 'crear' )
            {
                $concat .= " AND a.verificacion_at IS NOT NULL
                            AND wo.rastreo IS NULL";
            }
            elseif( $filters['wayout'] === 'no' )
            {
                $concat .= " AND a.verificacion_at IS NULL";
            }
            else
            {
                $concat .= " AND wo.rastreo IS NOT NULL";
            }
        }

        if( !is_null($filters['warehouse']) && $filters['warehouse'] <> 'cualquiera' )
        {
            $filter = $filters['warehouse'] === 'usa' ? 'en_bodega_usa_by' : 'en_bodega_mex_by';
            $concat .= " AND e.{$filter} IS NOT NULL";
        }

        $order_by = !is_null($filters['repacker'])
            ? 'e.reempacado_at ASC, e.id ASC'
            : "e.id {$order}";
        $concat .= " GROUP BY e.id ORDER BY {$order_by} ";

        if( is_numeric($filters['limit']) )
            $concat .= " LIMIT {$filters['limit']} ";

        $query  = $this->getQueryRelations( $concat );
        // showme($query);
        return $this->raw($query, true);
    }
    
    public function withRelations($value, $column = 'id')
    {
        $query = $this->getQueryRelations( " WHERE e.{$column} = '{$value}' GROUP BY e.id " );
        return $this->raw($query, true);
    }

    public function numberWithRelations($value)
    {
        $query = $this->getQueryRelations( " WHERE UPPER(e.numero) = UPPER('{$value}') GROUP BY e.id " );
        return $this->raw($query, true);
    }

    public function existsNumberWithSameClient($number, $client)
    {
        $query = "SELECT id FROM {$this->table} WHERE UPPER(numero) LIKE UPPER('%{$number}%')";

        if( is_numeric($client) )
            $query .= " AND cliente_id = {$client}";

        return $this->raw($query, true);
    }

    public function searchEntries($filters, $data)
    {
        $query_filtered = [];

        if( in_array('numero_entrada', $filters) )
            array_push($query_filtered, "UPPER(e.numero) LIKE UPPER('%{$data}%')");

        if( in_array('numero_consolidado', $filters) )
            array_push($query_filtered, "UPPER(cs.numero) LIKE UPPER('%{$data}%')");
        
        if( in_array('numero_rastreo', $filters) )
            array_push($query_filtered, "wo.rastreo LIKE '%{$data}%'");

        if( in_array('remitente', $filters) )
            array_push($query_filtered, "LOWER(r.nombre) LIKE LOWER('%{$data}%') 
                                        OR LOWER(r.direccion) LIKE LOWER('%{$data}%')
                                        OR r.postal LIKE '%{$data}%' 
                                        OR r.ciudad LIKE '%{$data}%' 
                                        OR r.estado LIKE '%{$data}%' 
                                        OR r.telefono LIKE '%{$data}%'");

        if( in_array('destinatario', $filters) )
            array_push($query_filtered, "LOWER(a.nombre) LIKE LOWER('%{$data}%') 
                                        OR LOWER(a.direccion) LIKE ('%{$data}%')
                                        OR a.postal LIKE '%{$data}%' 
                                        OR a.ciudad LIKE '%{$data}%' 
                                        OR a.estado LIKE '%{$data}%' 
                                        OR a.telefono LIKE '%{$data}%'");
        
        $filtered  = 'WHERE ' . implode(' OR ', $query_filtered);
        $filtered .= " GROUP BY e.id ORDER BY e.id DESC";

        $query = $this->getQueryRelations( $filtered );
        // showme($query);

        return $this->raw($query, true);
    }
    
    public function consolidatedEntriesFilter($consolidated_id, $filters)
    {
        $warehouses = config('warehouses');
        $incidents = config('wayouts', 'incidents');
        $query_filtered = [];

        if( !is_null($filters['warehouse']) && array_key_exists($filters['warehouse'], $warehouses) ) {
            $column_warehouse = $filters['warehouse'] === 'usa' ? 'en_bodega_usa_by' : 'en_bodega_mex_by';
            array_push($query_filtered, " e.{$column_warehouse} IS NOT NULL");
        }

        if( !is_null($filters['missing']) && array_key_exists($filters['missing'], $warehouses) ) {
            $column_warehouse = $filters['missing'] === 'usa' ? 'en_bodega_usa_by' : 'en_bodega_mex_by';
            array_push($query_filtered, " e.{$column_warehouse} IS NULL");
        }

        if( !is_null($filters['repackaged']) && $filters['repackaged'] === 'si' || $filters['repackaged'] === 'no' ) 
        {
            $nullable =  $filters['repackaged'] === 'si' ? 'IS NOT NULL' : 'IS NULL';
            array_push($query_filtered, " e.codigor_id {$nullable}");
        }

        if( !is_null($filters['cover']) && $filters['cover'] <> 'cualquier' ) 
            array_push($query_filtered, " wo.cobertura = '{$filters['cover']}'");
            
        if( !is_null($filters['transport']) && is_numeric($filters['transport']) ) 
            array_push($query_filtered, " wo.transportadora_id = {$filters['transport']}");

        if( !is_null($filters['incident']) && in_array($filters['incident'], $incidents) ) 
            array_push($query_filtered, " wo.incidente = '{$filters['incident']}'");
        
        if( !empty($filters['verification']) )
            array_push($query_filtered, " a.verificacion_at IS NULL");

        if( !empty($filters['no_wayout']) )
            array_push($query_filtered, " wo.id IS NULL");

        if( !empty($filters['no_exit_weighing']) )
            array_push($query_filtered, " NOT EXISTS (SELECT 1 FROM entrada_medidas AS m
                WHERE m.entrada_id = e.id AND m.etapa = 'bodega_mex_salida' AND m.peso IS NOT NULL AND m.peso > 0)");

        $filter_string = " WHERE e.consolidado_id = {$consolidated_id} ";
        if( count($query_filtered) ) 
            $filter_string .= ' AND '.implode(' AND ', $query_filtered);

        $filter_string .= " GROUP BY e.id ORDER BY e.id DESC";
        $query = $this->getQueryRelations( $filter_string );
        return $this->raw($query, true);
    }

    private function getQueryRelations( $concat = '' )
    {
        return "SELECT e.*,
                c.nombre AS 'cliente_nombre', c.alias AS 'cliente_alias',
                cs.numero AS 'consolidado_numero', cs.alias_cliente_numero AS 'consolidado_alias_cliente_numero',
                uwusa.nombre AS 'usuario_bodega_usa_nombre', uwusa.email AS 'usuario_bodega_usa_email',
                uwmex.nombre AS 'usuario_bodega_mex_nombre', uwmex.email AS 'usuario_bodega_mex_email',
                d.nombre AS 'conductor_nombre',
                v.alias AS 'vehiculo_alias',
                rpkr.nombre AS 'reempacador_nombre',
                cr.codigo AS 'reempacado_codigo', cr.descripcion AS 'reempacado_descripcion',
                createdby.nombre AS 'creado_por_nombre', createdby.email AS 'creado_por_email',
                updatedby.nombre AS 'actualizado_por_nombre', updatedby.email AS 'actualizado_por_email', 
                r.id AS 'remitente_id', r.nombre AS 'remitente_nombre', r.telefono AS 'remitente_telefono', r.direccion AS 'remitente_direccion', r.postal AS 'remitente_postal', r.ciudad AS 'remitente_ciudad', r.estado AS 'remitente_estado', r.pais AS 'remitente_pais', r.actualizado_by AS 'remitente_actualizado_por',
                ur.nombre AS 'remitente_actualizado_nombre',
                a.id AS 'destinatario_id', a.nombre AS 'destinatario_nombre', a.telefono AS 'destinatario_telefono', a.direccion AS 'destinatario_direccion', a.postal AS 'destinatario_postal', a.ciudad AS 'destinatario_ciudad', a.estado AS 'destinatario_estado', a.pais AS 'destinatario_pais', a.actualizado_by AS 'destinatario_actualizado_por', a.referencias AS 'destinatario_referencias', a.verificacion_at AS 'destinatario_verificacion_at',
                ua.nombre AS 'destinatario_actualizado_nombre',
                wo.id AS 'salida_id', wo.rastreo AS 'salida_rastreo', wo.confirmacion AS 'salida_confirmacion', wo.status AS 'salida_status', wo.incidente AS 'salida_incidente', wo.cobertura AS 'salida_cobertura', wo.direccion AS 'salida_direccion', wo.postal AS 'salida_postal', wo.ciudad AS 'salida_ciudad', wo.estado AS 'salida_estado', wo.pais AS 'salida_pais', wo.notas AS 'salida_notas',
                t.nombre AS 'transportadora_nombre', t.web AS 'transportadora_web' 
                FROM {$this->table} AS e
                LEFT JOIN {$this->clients_table} AS c ON e.cliente_id = c.id 
                LEFT JOIN {$this->consolidated_table} AS cs ON e.consolidado_id = cs.id 
                LEFT JOIN {$this->users_table} AS uwusa ON e.en_bodega_usa_by = uwusa.id 
                LEFT JOIN {$this->users_table} AS uwmex ON e.en_bodega_mex_by = uwmex.id 
                LEFT JOIN {$this->drivers_table} AS d ON e.conductor_id = d.id 
                LEFT JOIN {$this->vehicles_table} AS v ON e.vehiculo_id = v.id 
                LEFT JOIN {$this->repackers_table} AS rpkr ON e.reempacador_id = rpkr.id 
                LEFT JOIN {$this->codesr_table} AS cr ON e.codigor_id = cr.id 
                INNER JOIN {$this->users_table} AS createdby ON e.creado_by = createdby.id 
                INNER JOIN {$this->users_table} AS updatedby ON e.actualizado_by = updatedby.id 
                INNER JOIN {$this->remitter_table} AS r ON e.id = r.entrada_id 
                INNER JOIN {$this->addressee_table} AS a ON e.id = a.entrada_id
                LEFT JOIN {$this->users_table} AS ur ON r.actualizado_by = ur.id 
                LEFT JOIN {$this->users_table} AS ua ON a.actualizado_by = ua.id 
                LEFT JOIN {$this->wayouts_table} AS wo ON e.id = wo.entrada_id 
                LEFT JOIN {$this->transports_table} AS t ON wo.transportadora_id = t.id " 
                . $concat;
    }

    public function getCounters()
    {
        $query = "SELECT COUNT(*) AS 'entradas_total',
                (SELECT COUNT(*) FROM entradas WHERE consolidado_id IS NOT NULL) AS 'consolidados_count',
                (SELECT COUNT(*) FROM entradas WHERE consolidado_id IS NULL) AS 'sin_consolidar_count',
                (SELECT COUNT(*) FROM entradas WHERE cliente_id IS NULL) AS 'sin_cliente_count',
                (SELECT COUNT(*) FROM entradas WHERE recibido_at IS NOT NULL) AS 'recibidos',
                (SELECT COUNT(*) FROM entradas WHERE en_bodega_usa_by IS NOT NULL) AS 'en_bodega_usa',
                (SELECT COUNT(*) FROM entradas WHERE en_bodega_mex_by IS NOT NULL) AS 'en_bodega_mex',
                (SELECT COUNT(*) FROM entradas WHERE codigor_id IS NOT NULL) AS 'reempacados'
                FROM entradas";
        return $this->raw($query, true);
    }

    //funcion nueva para pxd
    public function countWithoutWayout($consolidated_id)
    {
        $id = (int) $consolidated_id;
        $rows = $this->raw("SELECT COUNT(*) AS total FROM {$this->table} AS e
                            WHERE e.consolidado_id = {$id}
                            AND NOT EXISTS (SELECT 1 FROM salidas AS wo WHERE wo.entrada_id = e.id)", true);
        return (int) $rows[0]->total;
    }

    public function getWithDestinatarioWhereConsolidado($consolidado_id)
    {
        $query = "SELECT e.id, ed.verificacion_at 
                    FROM {$this->table} AS e
                    INNER JOIN {$this->addressee_table} AS ed ON e.id = ed.entrada_id
                    WHERE consolidado_id = {$consolidado_id} AND ed.verificacion_at IS NULL";

        return $this->raw($query, true);
    }
}




/*

use \PDO;

public function updateFromTo($data, $table, $id)
{
    $table_fromto = $table === 'remitente' ? $this->table_remitter : $this->table_addressee;
    $this->query = "UPDATE {$table_fromto}
                    SET nombre = :nombre, telefono = :telefono, direccion = :direccion, postal = :postal, ciudad = :ciudad, estado = :estado, pais = :pais, verificacion = :verificacion, updated_at = :updated
                    WHERE id = :id LIMIT 1";
    $stmt = $this->prepare($this->query);
    $stmt->bindValue(':nombre', $data['nombre']);
    $stmt->bindValue(':telefono', $data['telefono']);
    $stmt->bindValue(':direccion', $data['direccion']);
    $stmt->bindValue(':postal', $data['postal']);
    $stmt->bindValue(':ciudad', $data['ciudad']);
    $stmt->bindValue(':estado', $data['estado']);
    $stmt->bindValue(':pais', $data['pais']);
    $stmt->bindValue(':verificacion', $data['verificacion']);
    $stmt->bindValue(':updated', DATETIME_NOW);
    $stmt->bindValue(':id', $id);
    
    $stmt->execute();
    return $stmt->rowCount();
}

// AND m.id = (SELECT MAX(msub.id) FROM {$this->table_measures} AS msub WHERE msub.entrada_id = e.id)
*/