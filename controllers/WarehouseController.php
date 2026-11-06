<?php namespace Controllers;

use System\Core\Controller;
use Models\Entry;
use Models\Measure;
use Models\Remitter;
use Models\Addressee;

class WarehouseController extends Controller
{
    use \Traits\WarehouseUsaTrait;
    use \Traits\WarehouseMexTrait;

    private $session;
    private $locations;
    private $comings;
    private $columns;
    private $defaults;

    public function __construct()
    {
        parent::__construct();

        $this->session = session_get('user');

        $this->locations = [
            'usa' => 'bodega_usa',
            'mex' => 'bodega_mex',
        ];

        $this->comings = [
            'entrada',
            'salida',
        ];

        $this->columns = [
            'numero',
            'id',
        ];

        $this->defaults = [
            'coming' => 'entrada',
            'column' => 'numero',
        ];
    }

    public function index($coming = 'entrada')
    {
        return $this->callActionTrait(__FUNCTION__, $coming);
    }

    public function register($column = 'numero', $coming = null)
    {
        if( !$this->validateColumn($column) || !$this->validateComing($coming) ) 
            return back();

        $trait_redirect = $this->callActionTrait(__FUNCTION__, [$column, $coming]);

        if( is_string($trait_redirect) )
            return redirect($trait_redirect);
        
        return back();
    }

    public function choose($number, $coming = 'entrada')
    {
        $model_entry = new Entry;
        $entries = $model_entry->withRelations($number, 'numero');
        return $this->callActionTrait(__FUNCTION__, [$number, $coming, array_reverse($entries)]);
    }

    public function takeMeasures($entry_id = null, $coming = null)
    {
        if( is_null($entry_id) || !$this->validateComing($coming) ) 
            return redirect('bodega/');

        $model_entry = new Entry;

        if(! $entry = $model_entry->find($entry_id) ) {
            $this->message(['danger', 'Numero de guia no sido registrado']);
            return redirect("bodega/{$coming}");
        }

        $measures_types = [
            'weight' => config('measures', 'peso'),
            'volume' => config('measures', 'volumen'),
        ];

        return $this->callActionTrait(__FUNCTION__, [$entry, $measures_types, $coming]);
    }



    // Entry --------------------------------------------------------------------------------------------------
    private function registerEntry($post, $column)
    {
        $model_entry = new Entry;
        $entries = $model_entry->where($column, '=', $post['numero'], false);

        if( is_array($entries) && count($entries) > 1 )
            return array_reverse($entries);

        if( count($entries) === 1 ) 
            return $this->updateEntry($post, $model_entry, array_pop($entries));
        
        return $this->storeEntry($post, $model_entry);
    }

    private function updateEntry($patch, $model_entry, $entry)
    {
        $data = $this->prepareDataToSaveEntry($patch, $entry);
        return $model_entry->update($data, $entry->id) ? $entry : false;
    }

    private function storeEntry($post, $model_entry)
    {
        $data = $this->prepareDataToSaveEntry($post);
        if(! $entry_id = $model_entry->store($data) )
            return false;

        $model_measure = new Measure;
        $model_measure->storeMeasures([], $entry_id, $this->session);

        $data_track = [
            'actualizado_by' => $this->session['id'],
            'entrada_id' => $entry_id,
        ];

        $model_remitter = new Remitter;
        $model_remitter->store($data_track);

        $model_addressee = new Addressee;
        $model_addressee->store($data_track);

        return $model_entry->find($entry_id);
    }

    private function prepareDataToSaveEntry($post, $entry = false)
    {
        // If not exists entry, then will prepare data to create
        if(! is_object($entry) ) {
            return [
                'numero' => $post['numero'],
                'actualizado_by' => $this->session['id'],
                'conductor_id' => isset($post['conductor']) && is_numeric($post['conductor']) ? $post['conductor'] : null,
                'creado_by' => $this->session['id'],
                'en_bodega_mex_by' => $this->locationSessionIs('mex') ? $this->session['id'] : null,
                'en_bodega_usa_by' => $this->locationSessionIs('usa') ? $this->session['id'] : null,
                'numero_vuelta' => isset($post['numero_vuelta']) && is_numeric($post['numero_vuelta']) ? $post['numero_vuelta'] : null,
                'observaciones' => isset($post['observaciones']) ? $this->jsonEncodeObservationsEntry($post['observaciones']) : null,
                'recibido_at' => DATETIME_NOW,
                'vehiculo_id' => isset($post['vehiculo']) && is_numeric($post['vehiculo']) ? $post['vehiculo'] : null,
            ];
        }

        // If exists entry, then will prepare data to update
        return [
            'actualizado_by' => $this->session['id'],
            'conductor_id' => isset($post['conductor']) && is_numeric($post['conductor']) ? $post['conductor'] : $entry->conductor_id,
            'creado_by' => is_null($entry->creado_by) ? $this->session['id'] : $entry->creado_by,
            'en_bodega_mex_by' => $this->locationSessionIs('mex') && is_null($entry->en_bodega_mex_by) ? $this->session['id'] : $entry->en_bodega_mex_by,
            'en_bodega_usa_by' => $this->locationSessionIs('usa') && is_null($entry->en_bodega_usa_by) ? $this->session['id'] : $entry->en_bodega_usa_by,
            'numero_vuelta' => isset($post['numero_vuelta']) && is_numeric($post['numero_vuelta']) ? $post['numero_vuelta'] : $entry->numero_vuelta,
            'observaciones' => isset($post['observaciones']) ? $this->jsonEncodeObservationsEntry($post['observaciones'], $entry) : $entry->observaciones,
            'recibido_at' => is_null($entry->recibido_at) ? DATETIME_NOW : $entry->recibido_at,
            'vehiculo_id' => isset($post['vehiculo']) && is_numeric($post['vehiculo']) ? $post['vehiculo'] : $entry->vehiculo_id,
        ];
    }

    private function jsonEncodeObservationsEntry($post_observations, $entry = false)
    {
        if( empty( trim($post_observations) ) )
            return is_object($entry) ? $entry->observaciones : null;

        $new_observation = [
            'observacion' => $post_observations,
            'creado_at'   => DATETIME_NOW,
            'nombre'      => $this->session['name'],
            'usuario'     => $this->session['id'],
        ];

        if(! is_object($entry) )
            return json_encode([$new_observation]);

        $observations_entry = !is_null($entry->observaciones) ? json_decode($entry->observaciones) : [];
        array_unshift($observations_entry, $new_observation);
        return json_encode($observations_entry);
    }

    


    // Measures --------------------------------------------------------------------------------------------------
    public function updateMeasures($measure_id = null, $coming = null)
    {
        if( is_null($measure_id) || !$this->validateComing($coming) ) 
            return redirect('bodega/');

        $this->validate($this->request->all(), [
            'numero'      => 'required',
            'peso'        => 'required',
            'medida_peso' => 'required',
            'go_home'     => 'required',
        ]);

        $model_measure = new Measure;
        if(! $measure = $model_measure->find($measure_id) ) {
            $this->message(['danger', '<b>No existe registro de medidas para actualizar</b>. Notificar al administrador']);
            return back();
        }

        $data = [
            'peso'           => $this->request->get('peso'),
            'medida_peso'    => $this->request->get('medida_peso'),
            'ancho'          => $this->request->isEmpty('ancho') ? null : $this->request->get('ancho'),
            'altura'         => $this->request->isEmpty('altura') ? null : $this->request->get('altura'),
            'profundidad'    => $this->request->isEmpty('profundidad') ? null : $this->request->get('profundidad'),
            'medida_volumen' => $this->request->has('medida_volumen') ? $this->request->get('medida_volumen') : null,
            'actualizado_by' => $this->session['id'],
        ];

        if(! $model_measure->update($data, $measure->id) ) {
            $this->message(['danger', 'Error al actualizar medidas del paquete. Notificar al administrador']);
            return back();
        }
        
        $this->message(['success', "Medidas del paquete del numero de guia <b>{$this->request->get('numero')}</b> actualizada"]);
        return redirect( $this->request->get('go_home') );
    }




    // Helpers Warehouse -----------------------------------------------------------------------------------------------
    private function callActionTrait($function_name, $arguments)
    {   
        $trait_method_name   = $this->getMethodNameTrait($function_name);
        $trait_method_params = $this->getMethodParamsTrait($arguments);
        return call_user_func_array([$this, $trait_method_name], $trait_method_params);
    }

    private function getMethodNameTrait($function_name)
    {
        $prefix = $this->locationSessionIs('usa') ? 'usa_' : 'mex_';
        return $prefix.$function_name;
    }

    private function getMethodParamsTrait($arguments)
    {
        return is_array($arguments) ? $arguments : [$arguments];
    }

    private function locationSessionIs($location)
    {
        return $this->locations[ $location ] === $this->session['type'];
    }

    private function validateComing($coming)
    {
        return in_array($coming, $this->comings);
    }

    private function validateColumn($column)
    {
        return in_array($column, $this->columns);
    }

    private function getComingDefaultIf($coming)
    {
        return $this->validateComing($coming) ? $coming : $this->defaults['coming'];
    }

    private function getColumnDefaultIf($column)
    {
        return $this->validateColumn($column) ? $column : $this->defaults['column'];
    }
}
