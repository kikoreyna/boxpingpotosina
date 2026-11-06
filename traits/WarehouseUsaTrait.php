<?php namespace Traits;

use Models\Measure;
use Models\Entry;

trait WarehouseUsaTrait
{
    private function usa_index($coming)
    {
        $mode = session_get('bodega_modo') === 'escaneo' ? 'escaneo' : 'medidas';
        return view('warehouse/usa/index', compact('mode'));
    }

    private function usa_register($column, $coming = 'entrada')
    {
        $this->validate($this->request->all(), [
            'numero' => 'required'
        ]);
        
        if(! $registered_entry = $this->registerEntry($this->request->all(), $column) )
        {
            $this->message(['danger', '<b>Error al registrar numero de guia</b>. Notificar al administrador.']);
            return false;
        }

        $scan_only = $this->request->get('modo') === 'escaneo';
        session_set('bodega_modo', $scan_only ? 'escaneo' : 'medidas');

        if( is_array($registered_entry) )
        {
            $query = ['modo' => $scan_only ? 'escaneo' : 'medidas'];
            if( !$this->request->isEmpty('observaciones') )
                $query['observaciones'] = $this->request->get('observaciones');
            return "bodega/seleccionar/{$this->request->get('numero')}/entrada?" . http_build_query($query);
        }

        if( $scan_only )
        {
            $this->message(['success', "Guia <b>{$registered_entry->numero}</b> registrada como recibida"]);
            return 'bodega/entrada';
        }

        $this->message(['success', 'Numero de guia registrada']);

        return "bodega/medir/{$registered_entry->id}/entrada";
    }

    private function usa_choose($number, $coming, $entries)
    {
        $observations = $this->request->has('observaciones', 'get') ? $this->request->get('observaciones', 'get') : false;
        $mode = $this->request->get('modo', 'get') === 'escaneo' ? 'escaneo' : 'medidas';
        
        return view('warehouse/usa/choose', compact('number','coming','entries','observations','mode'));
    }

    private function usa_takeMeasures($entry, $measures_types, $coming)
    {
        $warehouse_stages = ['bodega_usa'];

        $model_measure = new Measure;
        $measures_entry = $model_measure->where('entrada_id', '=', $entry->id);
        $measures_filtered = array_filter($measures_entry, function ($me) use ($warehouse_stages) {
            return in_array($me->etapa, $warehouse_stages);
        });

        $measures_filtered_count = count($measures_filtered);
        if( $measures_filtered_count === 0 || $measures_filtered_count > count($warehouse_stages) )
        {
            $this->message(['danger', "<b>Error para medir el numero de guia</b>. Notificar al administrador. ({$measures_filtered_count})"]);
            return false;
        }

        $go_home = "bodega/entrada";
        
        $measure_entry_usa_in = array_pop($measures_filtered);
        
        return view('warehouse/usa/take_measures', compact('entry','measure_entry_usa_in','measures_types', 'go_home'));
    }
}
