<?php namespace Controllers;

use System\Core\Controller;

use Models\Entry;
use Models\Driver;
use Models\Car;

class CrossingController extends Controller {

    public function index()
    {
        $model_car = new Car;
        $cars = $model_car->availables();

        $model_driver = new Driver;
        $drivers = $model_driver->availables();

        $dates = [
            'desde_fecha' => $this->request->has('desde_fecha', 'get') ? $this->request->get('desde_fecha', 'get') : date('Y-m-d'),
            'desde_hora'  => $this->request->has('desde_hora', 'get') ? $this->request->get('desde_hora', 'get') : '00:00',
            'hasta_fecha' => $this->request->has('hasta_fecha', 'get') ? $this->request->get('hasta_fecha', 'get') : date('Y-m-d'),
            'hasta_hora'  => $this->request->has('hasta_hora', 'get') ? $this->request->get('hasta_hora', 'get') : '23:59',
        ];
        
        $desde = "{$dates['desde_fecha']} {$dates['desde_hora']}";
        $hasta = "{$dates['hasta_fecha']} {$dates['hasta_hora']}:00";
        $report = array();

        foreach($drivers as $driver)
        {
            // showme("SELECT * FROM entradas WHERE cruce_at BETWEEN '{$desde}' AND '{$hasta}' AND conductor_id = {$driver->id} ORDER BY id DESC");
            // break;
            $result = $model_driver->raw(
                "SELECT * FROM entradas WHERE cruce_at BETWEEN '{$desde}' AND '{$hasta}' AND conductor_id = {$driver->id} ORDER BY id DESC", 
                TRUE
                );
               
              
            $item = [
                'id' => $driver->id,
                'name' => $driver->nombre,
                'entries_count' => count($result),
                'return_max' => 0,
                //'numero'=> ($result),
            ];
            
             
            foreach($result as $entry)
            {
                if( !is_null($entry->numero_vuelta) && $entry->numero_vuelta > $item['return_max'] )
                    $item['return_max'] = $entry->numero_vuelta;
            }

            array_push($report, $item);
        }

        return view('crossing/index', compact('drivers','cars','dates','report'));
    }
}