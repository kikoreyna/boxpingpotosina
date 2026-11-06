<?php namespace Controllers;

use System\Core\Controller;
use Models\Consolidated;
use Models\Client;
use Models\Entry;
use Models\Measure;
use Models\Transport;
use Models\Addressee;

class ConsolidatedController extends Controller {

    public function index()
    {
        $filters = $this->getFilters();

        // die(
        //     var_dump($filters)
        // );

        $model = new Consolidated;
        $model_entry = new Entry;
        $result = $model->allWithRelationsFiltered($filters);
        $consolidated = is_array($result) ? $result : [$result];
        $inputs = $this->getInputs(new Client, $filters);

        foreach($consolidated as $c)
        {
            // $entradas = $model_entry->where('consolidado_id', '=', $c->id);
            // $c->entries_count = count($entradas);
            // $c->entries_warehouse_usa = 0;
            // $c->entries_warehouse_mex = 0;
            // $c->entries_repackaged = 0;

            $entradas_sin_verificacion = $model_entry->getWithDestinatarioWhereConsolidado($c->id);
            $c->pendientes = count($entradas_sin_verificacion);
        }

        // die( print_r($consolidated) );
        
        return view('consolidated/index', compact('consolidated','filters', 'inputs'));
    }

    private function getFilters()
    {
        return [
            'client' => $this->request->has('cliente', 'get') ? $this->request->get('cliente','get') : null,
            'from' => $this->request->has('desde', 'get') ? $this->request->get('desde','get') : date('Y-m-01'),
            'to' => $this->request->has('hasta', 'get') ? $this->request->get('hasta','get') : date('Y-m-d'),
            'status' => $this->request->has('estatus', 'get') && in_array($this->request->get('estatus', 'get'), config('consolidated', 'status')) ? $this->request->get('estatus', 'get') :             'abierto',
                    ];
    }

    private function getInputs(Client $model_client, $filters)
    {
        return [
            'clients' => $model_client->availables(),
            'from' => $filters['from'],
            'to' => $filters['to'],
            'status' => config('consolidated', 'status'),
        ];
    }

    public function create()
    {
        $model_client = new Client;
        $clients = $model_client->availables();
        return view('consolidated/create', compact('clients'));
    }
    
    public function store()
    {
        $this->validate($this->request->all(), [
            'cliente' => 'required',
            'numero' => 'required;unique:consolidados'
        ]);
        
        $model = new Consolidated;
        $data = $this->prepareData( $this->request->all() );
        $consolidated_id = $model->store($data);
        $msg = $consolidated_id ? ['success', "Consoldidado {$data['numero']} guardado"]
                                : ['danger', "Error al guardar consolidado {$data['numero']}"];
        $this->message($msg);
        return redirect("entradas/crear/{$consolidated_id}");
    }
    
    public function show($id = null)
    {
        $model = new Consolidated;
        if( ! $consolidated = $model->withRelations($id) )
            return redirect('consolidados');

        $filters = $this->getFiltersShow();
        $inputs_filter = $this->getFilterInputsShow();
        $stages = config('stages');

        $model_entry = new Entry;
        $entries = $model_entry->consolidatedEntriesFilter($consolidated->id, $filters);     

        if( !is_null($filters['weighing']) && in_array($filters['weighing'], $stages) )
        {
            $weighing_dates_range = !is_null($filters['weighing_from']) && !is_null($filters['weighing_to'])
                                ? " AND DATE(updated_at) BETWEEN '{$filters['weighing_from']}' AND '{$filters['weighing_to']}'" 
                                : '';

            $model_measure = new Measure;
            $result = array();
            foreach($entries as $entry)
            {
                $query_measure = "SELECT id FROM entrada_medidas 
                                WHERE entrada_id = {$entry->id} 
                                AND etapa = '{$filters['weighing']}' 
                                AND peso IS NOT NULL AND peso > 0 
                                {$weighing_dates_range}
                                GROUP BY id ORDER BY id DESC LIMIT 1";

                $result_filtered = $model_measure->raw($query_measure, true);

                if( count($result_filtered) ) 
                    array_push($result, $entry);
            }

            $entries = $result;
        }

        $all_entries_consolidated = $model_entry->where('consolidado_id', '=', $consolidated->id);
        $all_entries_consolidated_count = is_array($all_entries_consolidated) ? count($all_entries_consolidated) : 0;
        $entries_warehouse_usa = 0;
        $entries_warehouse_mex = 0;
        $entries_repackaged = 0;
        $entries_filtered_count = count($entries);

        foreach($all_entries_consolidated as $entry)
        {
            if( $entry->en_bodega_usa_by )
                $entries_warehouse_usa++;

            if( $entry->en_bodega_mex_by )
                $entries_warehouse_mex++;

            if( $entry->codigor_id )
                $entries_repackaged++;
        }

        return view('consolidated/show', compact('consolidated','all_entries_consolidated_count','entries_warehouse_usa','entries_warehouse_mex','entries_repackaged','entries','entries_filtered_count','filters','inputs_filter'));
    }
    
    private function getFiltersShow()
    {        
        return [
            'cover'      => $this->request->has('cobertura','get') ? $this->request->get('cobertura','get') : null,
            'incident'   => $this->request->has('incidente','get') ? $this->request->get('incidente','get') : null,
            'missing'    => $this->request->has('faltante','get') ? $this->request->get('faltante','get') : null,
            'repackaged' => $this->request->has('reempacado','get') ? $this->request->get('reempacado','get') : null,
            'transport'  => $this->request->has('transportadora','get') ? $this->request->get('transportadora','get') : null,
            'warehouse'  => $this->request->has('bodega','get') ? $this->request->get('bodega','get') : null,
            'verification' => $this->request->has('verificacion','get') && $this->request->get('verificacion','get') === '1',
            'no_exit_weighing' => $this->request->has('sin_pesaje_salida','get') && $this->request->get('sin_pesaje_salida','get') === '1',
            'weighing'   => $this->request->has('pesaje','get') ? $this->request->get('pesaje','get') : null,
            'weighing_from' => $this->request->has('pesaje_desde','get') && !$this->request->isEmpty('pesaje_desde','get') ? $this->request->get('pesaje_desde','get') : null,
            'weighing_to' => $this->request->has('pesaje_hasta','get') && !$this->request->isEmpty('pesaje_hasta','get') ? $this->request->get('pesaje_hasta','get') : null,
        ];
    }

    private function getFilterInputsShow()
    {
        $model_transport = new Transport;

        return [
            'covers'     => config('wayouts','covers'),
            'incidents'  => config('wayouts','incidents'),
            // 'missing'    => config('warehouses'),
            'repackaged' => ['si', 'no'],
            'transports' => $model_transport->all(),
            'warehouses' => config('warehouses'),
            'weighings'  => config('stages'),
        ];
    }

    public function edit($id = null)
    {
        $model = new Consolidated;
        if( ! $consolidated = $model->find($id) )
            return redirect('consolidados');

        list($consolidated->notificacion_fecha, $consolidated->notificacion_hora) = explode(' ', $consolidated->notificacion_at);
        
        $model_clients = new Client;
        $clients = $model_clients->availables();
        return view('consolidated/edit', compact('consolidated', 'clients'));
    }
    
    public function update($id = null)
    { 
        $model = new Consolidated;
        if( ! $consolidated = $model->find($id) )
            return redirect('consolidados');

        $this->validate($this->request->all(), [
            'cliente' => 'required',
            'numero' => 'required;unique:consolidados-id,'.$consolidated->id,
        ]);
        $data = $this->prepareData( $this->request->all() );

        $msg = $model->update($data, $consolidated->id) 
            ? ['success','Consolidado actualizado']
            : ['danger','Error al actualizar consolidado'];
        
        if( $data['cliente_id'] <> $consolidated->cliente_id )
        {
            $model_entry = new Entry;
            $update_client_id = $data['cliente_id'];
            $result = $model_entry->update(
                ['cliente_id' => $update_client_id],
                ['consolidado_id' => $consolidated->id],
                false
            );
        }

        $this->message($msg);
        return back();
    }

    public function warningDelete($id = null)
    {
        $model = new Consolidated;
        if(! $consolidated = $model->find($id) ) {
            $this->message(['warning', 'Consolidado no existe para eliminar']);
            return back();
        }

        return view('consolidated/warning_delete', compact('consolidated'));
    }

    public function delete()
    {
        $this->validate($this->request->all(), [
            'consolidado' => 'required',
            'numero' => 'required',
        ]);

        $model = new Consolidated;
        $consolidated = $model->find( $this->request->get('consolidado') );
        
        if(! $consolidated = $model->find( $this->request->get('consolidado') ) ) {
            $this->message(['warning', 'Consolidado no existe para eliminar']);
            return back();
        }
        
        if(! $model->delete($consolidated->id) ) {
            $this->message(['danger', "Error al eliminar consolidado"]);
            return back();
        }

        $model_entry = new Entry;
        $entries_deleted = $model_entry->raw("SELECT id, numero FROM entradas WHERE consolidado_id = {$consolidated->id}", true);
        $result = $model_entry->raw("DELETE FROM entradas WHERE consolidado_id = {$consolidated->id}");
        foreach($entries_deleted as $entry_deleted)
            \Models\ActivityLog::recordDelete('entradas', $entry_deleted, "Eliminada junto con el consolidado {$consolidated->numero}");

        $this->message(['success', "Consolidado {$consolidated->numero} eliminado"]);
        return redirect('consolidados');
    }

    public function toPrint($id = null)
    {
        $model = new Consolidated;
        if( ! $consolidated = $model->find($id) ) {
            echo 'CONSOLIDADO INCORRECTO. Reportarlo con el responsable de area y cierre esta ventana';
            return;
        }

        $model_entry = new Entry;
        $consolidated_entries = $model_entry->withRelations($consolidated->id, 'consolidado_id');
        
        $entries = [];
        $model_measure = new Measure;
        foreach($consolidated_entries as $entry)
        {
            $measures = $model_measure->where('entrada_id', '=', $entry->id);
            $measure_client_entry = array_filter($measures, function ($m) {
                return $m->etapa === 'cliente';
            });
            $entry->medidas = array_pop($measure_client_entry);
    
            $entry->medidas->libras_a_kilos = $entry->medidas->medida_peso === 'libras'
                                            ? convert_units($entry->medidas->peso, 'libras', 'kilogramos')
                                            : null;
            array_push($entries, $entry);
        }

        return view('consolidated/print', compact('entries'));
    }
    
    private function prepareData($post)
    {
        $data = [
            'numero' => $post['numero'],
            'cliente_id' => $post['cliente'],
            'alias_cliente_numero' => isset($post['alias']) ? 1 : 0,
            'palets' => is_numeric($post['palets']) ? $post['palets'] : null,
            'cerrado_at' => isset($post['cerrar']) ? DATETIME_NOW : null,
        ]; 
        
        $data['notificacion_at']  = !empty($post['notificacion_fecha']) ? $post['notificacion_fecha'].' ' : DATE_ZERO.' ';
        $data['notificacion_at'] .= !empty($post['notificacion_hora'])  ? $post['notificacion_hora'] : TIME_ZERO;
        return $data;
    }
}