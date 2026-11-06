<?php namespace Traits;

use Models\Measure;
use Models\Entry;
use Models\Driver;
use Models\Car;
use Models\Depot;

trait WarehouseMexTrait
{
    private function mex_index($coming)
    {
        $crossing = $this->mex_getCrossingIndexIn(new Driver, new Car);
        $crossing['depots'] = (new Depot)->availables('ASC');
        $crossing['depot_selected'] = $this->request->has('bodega', 'get') ? $this->request->get('bodega', 'get') : session_get('bodega_mex_id');
        
        $view = $coming !== 'salida' ? 'in' : 'out';
        
        return view("warehouse/mex/index/{$view}", compact('crossing'));
    }

    private function mex_getCrossingIndexIn(Driver $model_driver, Car $model_car)
    {
        return [
            'drivers' => $model_driver->all(),
            'cars' => $model_car->availables(),
            'driver_selected' => $this->request->has('conductor', 'get') ? $this->request->get('conductor', 'get') : null,
            'car_selected' => $this->request->has('vehiculo', 'get') ? $this->request->get('vehiculo', 'get') : null,
            'return_number' => $this->request->has('numero_vuelta', 'get') ? $this->request->get('numero_vuelta', 'get') : null,
        ];
    }

    private function mex_register($column, $coming)
    {
        if( $coming === 'entrada' ) {
            return $this->mex_registerIn( $this->request->all(), $column );
        }
        
        return $this->mex_registerOut( $this->request->all(), $column );
    }

    private function mex_registerIn($post, $column)
    {
        $this->validate($post, [
            'numero'         => 'required',
            'conductor'      => 'required',
            'vehiculo'       => 'required',
            'numero_vuelta'  => 'required',
            'bodega'         => 'required',
        ]);

        if(! is_object((new Depot)->available($post['bodega'])) )
        {
            $this->message(['danger', 'Selecciona una bodega valida']);
            return false;
        }
        session_set('bodega_mex_id', (int) $post['bodega']);

        if(! $registered_entry = $this->registerEntry($post, $column) )
        {
            $this->message(['danger', '<b>Error al registrar numero de guia</b>. Notificar al administrador.']);
            return false;
        }

        if( is_array($registered_entry) )
        {
            $redirect = "bodega/seleccionar/{$post['numero']}/entrada?";

            $vars = array();
            foreach(['conductor', 'vehiculo', 'numero_vuelta'] as $key)
            {
                $key_value = "{$key}={$post[ $key ]}";
                array_push($vars, $key_value);
            }
            $redirect .= implode('&', $vars);

            if(! empty( $post['observaciones'] ) ) {
                $redirect .= "&observaciones={$post['observaciones']}";
            }

            return $redirect;
        }

        (new Entry)->update(['bodega_mex_id' => (int) $post['bodega']], $registered_entry->id);

        if( is_null($registered_entry->cruce_at) )
        {
            $datetime_now = DATETIME_NOW;
            $model_entry = new Entry;
            $model_entry->update(['cruce_at' => $datetime_now], $registered_entry->id);
            $registered_entry->cruce_at = $datetime_now;
        } 

        $this->message(['success', 'Numero de guia registrada']);

        return "bodega/medir/{$registered_entry->id}/entrada";
    }

    private function mex_registerOut($patch, $column)
    {
        $model_entry = new Entry;
        if(! $entries = $model_entry->numberWithRelations($patch['numero']) )
        {
            $this->message(['danger', 'Numero de guia no ha sido registrado']);
            return false;
        }

        if( count($entries) > 1 )
        {
            $redirect = "bodega/seleccionar/{$this->request->get('numero')}/salida";

            if( !$this->request->isEmpty('observaciones') )
                $redirect .= "&observaciones={$this->request->get('observaciones')}";
            return $redirect;
        }

        $registered_entry = array_pop($entries);
        if(! $this->request->isEmpty('observaciones') )
        {
            $patch['observaciones'] = $this->jsonEncodeObservationsEntry( $this->request->get('observaciones'), $registered_entry );
            $result_patch = $model_entry->update($patch, $registered_entry->id);
        }

        $this->message(['success', 'Numero de guia registrada']);

        return "bodega/medir/{$registered_entry->id}/salida";
    }

    private function mex_choose($number, $coming, $entries)
    {
        $driver = $this->request->has('conductor', 'get') ? $this->request->get('conductor', 'get') : false;
        $car = $this->request->has('vehiculo', 'get') ? $this->request->get('vehiculo', 'get') : false;
        $return_number = $this->request->has('numero_vuelta', 'get') ? $this->request->get('numero_vuelta', 'get') : false;
        $observations = $this->request->has('observaciones', 'get') ? $this->request->get('observaciones', 'get') : false;
        $depot = session_get('bodega_mex_id');
        
        return view('warehouse/mex/choose', compact(
            'number',
            'coming',
            'entries',
            'driver',
            'car',
            'return_number',
            'observations',
            'depot'
            )
        );
    }

    private function mex_takeMeasures($entry, $measures_types, $coming)
    {
        $warehouse_stages = ['bodega_mex_entrada', 'bodega_mex_salida'];

        $model_measure = new Measure;
        $measures_entry = $model_measure->where('entrada_id', '=', $entry->id);
        $measures_filtered = array_filter($measures_entry, function ($me) use ($warehouse_stages, $coming) {
            return $me->etapa === "bodega_mex_{$coming}";
            // return in_array($me->etapa, $warehouse_stages);
        });

        $measures_filtered_count = count($measures_filtered);
        if( $measures_filtered_count === 0 || $measures_filtered_count > count($warehouse_stages) )
        {
            $this->message(['danger', "<b>Error para medir el numero de guia</b>. Notificar al administrador. ({$measures_filtered_count})"]);
            return false;
        }

        $go_home = $coming === 'entrada'
                 ? "bodega/entrada?conductor={$entry->conductor_id}&vehiculo={$entry->vehiculo_id}&numero_vuelta={$entry->numero_vuelta}"
                 : 'bodega/salida';

        $measure_entry_mex = array_pop($measures_filtered);
        
        $view = $coming !== 'salida' ? 'in' : 'out';
        
        $info = $this->mex_entryInfo($entry->id);
        return view("warehouse/mex/take_measures/{$view}", compact('entry','measure_entry_mex','measures_types','coming', 'go_home', 'info'));
    }

    // Datos de consulta para que bodega MEX identifique la guia al escanearla
    private function mex_entryInfo($entry_id)
    {
        $rows = (new Entry)->withRelations($entry_id);
        $full = array_pop($rows);

        $previous_stages = ['cliente', 'bodega_usa'];
        $measures = array_values(array_filter((new Measure)->withRelations($entry_id, 'entrada_id'), function ($m) use ($previous_stages) {
            return in_array($m->etapa, $previous_stages, true);
        }));

        return ['entry' => $full, 'measures' => $measures];
    }
}