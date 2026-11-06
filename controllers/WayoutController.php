<?php namespace Controllers;

use System\Core\Controller;
use System\Core\Request;

use Models\Wayout;
use Models\Entry;
use Models\Measure;
use Models\Transport;
use Models\Client;
use Models\DropOffStage;

class WayoutController extends Controller
{
    public function index()
    {
        $filters = $this->getFilters();
        $inputs  = $this->getInputs(new Client, new Transport, $filters);

        $wayoutQuery = new Wayout;
        $wayouts = $wayoutQuery->allWithRelationsFiltered($filters);

        if( !is_null($filters['weighing']) )
        {
            $result = array();
            $weighing_dates_range = !is_null($filters['weighing_from']) && !is_null($filters['weighing_to'])
                                ? " AND DATE(updated_at) BETWEEN '{$filters['weighing_from']}' AND '{$filters['weighing_to']}' " 
                                : '';

            foreach($wayouts as $wayout)
            {
                $query_measure = "SELECT id FROM entrada_medidas 
                                WHERE entrada_id = {$wayout->entrada_id} 
                                AND etapa = '{$filters['weighing']}' 
                                AND peso IS NOT NULL AND peso > 0 
                                {$weighing_dates_range} 
                                GROUP BY id ORDER BY id DESC LIMIT 1";

                $result_filtered = $wayoutQuery->raw($query_measure, true);

                if( count($result_filtered) ) 
                    array_push($result, $wayout);
            }

            $wayouts = $result;
        }

        if( 
            isset($filters['carga_fecha_hora_desde']) || 
            isset($filters['carga_fecha_hora_hasta']) ||
            isset($filters['descarga_fecha_hora_desde']) ||
            isset($filters['descarga_fecha_hora_hasta'])
        )
        {
            $wayouts_id = array_map(fn($wayout) => $wayout->id, $wayouts);

            $dropOffStageQuery = new DropOffStage;

            $dropOffStageResult = null;

            if( isset($filters['carga_fecha_hora_desde']) && isset($filters['carga_fecha_hora_hasta']) ) {
                $dropOffStageResult = $dropOffStageQuery->cargaTransportadoraFechaHoraBetween($filters['carga_fecha_hora_desde'], $filters['carga_fecha_hora_hasta']);
            }

            if( isset($filters['carga_fecha_hora_desde']) &&! isset($filters['carga_fecha_hora_hasta']) ) {
                $dropOffStageResult = $dropOffStageQuery->cargaTransportadoraFechaHoraMin($filters['carga_fecha_hora_desde']);
            }

            if( !isset($filters['carga_fecha_hora_desde']) && isset($filters['carga_fecha_hora_hasta']) ) {
                $dropOffStageResult = $dropOffStageQuery->cargaTransportadoraFechaHoraMax($filters['carga_fecha_hora_hasta']);
            }

            if( isset($filters['descarga_fecha_hora_desde']) && isset($filters['descarga_fecha_hora_hasta']) ) {
                $dropOffStageResult = $dropOffStageQuery->descargaTransportadoraFechaHoraBetween($filters['descarga_fecha_hora_desde'], $filters['descarga_fecha_hora_hasta']);
            }

            if( isset($filters['descarga_fecha_hora_desde']) &&! isset($filters['descarga_fecha_hora_hasta']) ) {
                $dropOffStageResult = $dropOffStageQuery->descargaTransportadoraFechaHoraMin($filters['descarga_fecha_hora_desde']);
            }

            if( !isset($filters['descarga_fecha_hora_desde']) && isset($filters['descarga_fecha_hora_hasta']) ) {
                $dropOffStageResult = $dropOffStageQuery->descargaTransportadoraFechaHoraMax($filters['descarga_fecha_hora_hasta']);
            }

            $dropOffStage_entrada_ids = array_map(fn($dropOffStage) => $dropOffStage->entrada_id, $dropOffStageResult);

            // if( empty($wayouts) ) {
            //     $wayouts = $wayoutQuery->whereIn('entrada_id', $dropOffStage_entrada_ids);
            // }

            $wayouts = array_filter($wayouts, function($wayout) use ($dropOffStage_entrada_ids) {
                return in_array($wayout->entrada_id, $dropOffStage_entrada_ids);
            });
        }

        view('wayout/index', compact('wayouts', 'inputs', 'filters'));
    }

    private function getFilters()
    {
        return [
            'client'    => $this->request->has('cliente', 'get') ? $this->request->get('cliente', 'get') : null,
            'cover'     => $this->request->has('cobertura', 'get') ? $this->request->get('cobertura', 'get') : null,
            'from'      => $this->request->has('desde', 'get') ? $this->request->get('desde', 'get') : date('Y-m-d'),
            'incident'  => $this->request->has('incidente', 'get') ? $this->request->get('incidente', 'get') : null,
            'status'    => $this->request->has('status', 'get') ? $this->request->get('status', 'get') : null,
            'to'        => $this->request->has('hasta', 'get') ? $this->request->get('hasta', 'get') : date('Y-m-d'),
            'transport' => $this->request->has('transportadora', 'get') ? $this->request->get('transportadora', 'get') : null,
            'weighing'   => $this->request->has('pesaje','get') && !$this->request->isEmpty('pesaje','get') ? $this->request->get('pesaje','get') : null,
            'weighing_from' => $this->request->has('pesaje_desde','get') && !$this->request->isEmpty('pesaje_desde','get') ? $this->request->get('pesaje_desde','get') : null,
            'weighing_to' => $this->request->has('pesaje_hasta','get') && !$this->request->isEmpty('pesaje_hasta','get') ? $this->request->get('pesaje_hasta','get') : null,
            'carga_fecha_hora_desde' => $this->request->has('carga_fecha_hora_desde','get') && !$this->request->isEmpty('carga_fecha_hora_desde','get') ? $this->request->get('carga_fecha_hora_desde','get') : null,
            'carga_fecha_hora_hasta' => $this->request->has('carga_fecha_hora_hasta','get') && !$this->request->isEmpty('carga_fecha_hora_hasta','get') ? $this->request->get('carga_fecha_hora_hasta','get') : null,
            'descarga_fecha_hora_desde' => $this->request->has('descarga_fecha_hora_desde','get') && !$this->request->isEmpty('descarga_fecha_hora_desde','get') ? $this->request->get('descarga_fecha_hora_desde','get') : null,
            'descarga_fecha_hora_hasta' => $this->request->has('descarga_fecha_hora_hasta','get') && !$this->request->isEmpty('descarga_fecha_hora_hasta','get') ? $this->request->get('descarga_fecha_hora_hasta','get') : null,
        ];
    }

    private function getInputs(Client $model_client, Transport $model_transport, $filters)
    {
        return [
            'clientes'        => $model_client->availables(),
            'coberturas'      => config('wayouts','covers'),
            'desde'           => $filters['from'],
            'hasta'           => $filters['to'],
            'incidentes'      => config('wayouts','incidents'),
            'pesajes'         => config('stages'),
            'status'          => config('wayouts','status'),
            'transportadoras' => $model_transport->all(),
        ];
    }

    public function create($entry_id = null)
    {
        $model_entry = new Entry;
        if( ! $entry = $model_entry->withRelations($entry_id)[0] )
            return back();

        if( !is_null($entry->salida_id) )
        {
            $this->message(['info', 'Guida de salida ya existe']);
            return redirect('entradas/mostrar/'.$entry->id);
        }

        $model_transport = new Transport;
        $transports = $model_transport->all();

        $model_measure = new Measure;
        $entry->medidas = $model_measure->withRelations($entry->id, 'entrada_id');
        return view('wayout/create', compact('entry','transports'));
    }

    public function store()
    {        
        $this->validate($this->request->all(), [
            'cobertura' => 'required',
            'entrada' => 'required'
        ]);
        $entry_id = $this->request->get('entrada');

        $data = $this->prepareData( $this->request->all(), $entry_id );
        $model = new Wayout;
        if( $id = $model->store($data) )
        {
            $this->message(['success', 'Guia de salida guardada']);
            return redirect('entradas/mostrar/'.$entry_id);
        }
        
        $this->message(['danger', 'Error al guardar guia de salida']);
        return back();
    }

    public function edit($id = null)
    {
        $model = new Wayout;

        if( ! $wayout = $model->withRelations($id)[0] )
            return back();

        $model_transport = new Transport;
        $transports = $model_transport->all();

        $model_entry = new Entry;
        $entry = $model_entry->withRelations($wayout->entrada_id)[0];

        $model_measure = new Measure;
        $entry->medidas = $model_measure->where('entrada_id', '=', $entry->id);

        $array_status = config('wayouts','status');
        $array_incidents = config('wayouts','incidents');
        return view('wayout/edit', compact('wayout','transports','array_status','array_incidents','entry'));
    }

    public function update($id = null)
    {
        $model = new Wayout;
        if( ! $wayout = $model->withRelations($id)[0] )
            return back();

        // crumble( $this->request->all() );

        $this->validate($this->request->all(), [
            'cobertura' => 'required',
        ]);

        $data = $this->prepareData($this->request->all(), $wayout->entrada_id);
        $msg = $model->update($data, $wayout->id)
            ? ['success', 'Guia de salida actualizada']
            : ['danger', 'Error al actualizar guia de salida'];
        $this->message($msg);
        return back();
    }

    public function toPrint($id = null)
    {
        $model = new Wayout;
        if( ! $wayouts = $model->withRelations($id) ) {            
            echo 'GUIA DE SALIDA INCORRECTA. Reportelo con el responsable de area y cierre esta ventana';
            return;
        }
        $wayout = array_pop($wayouts);

        $model_measure = new Measure;
        $measure = $model_measure->getStage($wayout->entrada_id, 'cliente');
        $measure->peso_kg = $measure->medida_peso === 'libras'
                        ? convert_units($measure->peso, 'libras', 'kilogramos')
                        : null;
        return view('wayout/print', compact('wayout','measure'));
    }

    private function prepareData($post, $entry_id)
    {
        return [
            'rastreo'           => isset($post['rastreo']) ? $post['rastreo'] : null,
            'confirmacion'      => isset($post['confirmacion']) ? $post['confirmacion'] : null,
            'status'            => isset($post['status']) && !empty( trim($post['status']) ) ? $post['status'] : null,
            'incidente'         => isset($post['incidente']) ? $post['incidente'] : null,
            'cobertura'         => $post['cobertura'],
            'direccion'         => isset($post['direccion']) ? $post['direccion'] : null,
            'postal'            => isset($post['postal']) ? $post['postal'] : null,
            'ciudad'            => isset($post['ciudad']) ? $post['ciudad'] : null,
            'estado'            => isset($post['estado']) ? $post['estado'] : null,
            'pais'              => isset($post['pais']) ? $post['pais'] : null,
            'notas'             => isset($post['notas']) ? $post['notas'] : null,
            'transportadora_id' => !empty($post['transportadora']) ? $post['transportadora'] : null,
            'actualizado_by'    => session_get('user','id'),
            'entrada_id'        => $entry_id,
        ];
    }
}