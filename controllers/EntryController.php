<?php namespace Controllers;

use System\Core\Path;

use Models\Entry;
use Models\Measure;
use Models\Remitter;
use Models\Addressee;
use Models\Client;
use Models\Wayout;
use Models\Driver;
use Models\Car;
use Models\Coder;
use Models\Repacker;
use Models\User;
use Models\Consolidated;
use Models\DropOffStage;

use Ddeboer\DataImport\Reader\CsvReader;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\SyntaxError;

class EntryController extends \System\Core\Controller
{
    public function index()
    {
        $filters = $this->getFilters();
        $inputs  = $this->getInputs(new Client, $filters);

        $model = new Entry;
        $entries = $model->allWithRelationsFiltered($filters);
        $entries_result_count = count($entries);
        $entries_count = count( $model->all() );

        if( !is_null($filters['weighing']) )
        {
            $result = array();
            $weighing_dates_range = !is_null($filters['weighing_from']) && !is_null($filters['weighing_to'])
                                ? " AND DATE(updated_at) BETWEEN '{$filters['weighing_from']}' AND '{$filters['weighing_to']}' " 
                                : '';

            foreach($entries as $entry)
            {
                $query_measure = "SELECT id FROM entrada_medidas 
                                WHERE entrada_id = {$entry->id} 
                                AND etapa = '{$filters['weighing']}' 
                                AND peso IS NOT NULL AND peso > 0 
                                {$weighing_dates_range} 
                                GROUP BY id ORDER BY id DESC LIMIT 1";

                $result_filtered = $model->raw($query_measure, true);

                if( count($result_filtered) ) 
                    array_push($result, $entry);
            }

            $entries = $result;
            $entries_result_count = count($entries);
        }
        
        return view('entry/index', compact(
            'entries',
            'entries_result_count',
            'entries_count',
            'filters',
            'inputs')
        );
    }

    private function getFilters()
    {
        return [
            'client'       => $this->request->has('cliente', 'get')      ? $this->request->get('cliente','get')      : null,
            'consolidated' => $this->request->has('consolidados', 'get') ? $this->request->get('consolidados','get') : null,
            'wayout'       => $this->request->has('salidas', 'get')      ? $this->request->get('salidas','get')      : null,
            'warehouse'    => $this->request->has('bodega', 'get')       ? $this->request->get('bodega','get')       : null,
            'from'         => $this->request->has('desde', 'get')        ? $this->request->get('desde','get')        : date('Y-m-d'), // date('Y-01-01')
            'to'           => $this->request->has('hasta', 'get')        ? $this->request->get('hasta','get')        : date('Y-m-d'),
            'limit'        => $this->request->has('limite', 'get')       ? $this->request->get('limite','get')       : '1200',
            'driver'       => $this->request->has('conductor', 'get') && is_numeric($this->request->get('conductor', 'get')) ? $this->request->get('conductor','get') : null,
            'repacker'     => $this->request->has('reempacador', 'get') && is_numeric($this->request->get('reempacador', 'get')) ? $this->request->get('reempacador','get') : null,
            'weighing'      => $this->request->has('pesaje','get') && !$this->request->isEmpty('pesaje','get') ? $this->request->get('pesaje','get') : null,
            'weighing_from' => $this->request->has('pesaje_desde','get') && !$this->request->isEmpty('pesaje_desde','get') ? $this->request->get('pesaje_desde','get') : null,
            'weighing_to'   => $this->request->has('pesaje_hasta','get') && !$this->request->isEmpty('pesaje_hasta','get') ? $this->request->get('pesaje_hasta','get') : null,
        ];
    }
    
    private function getInputs(Client $model_client, $filters)
    {
        return [
            'clients' => $model_client->availables(),
            'consolidated' => [
                'cualquiera' => 'Cualquier entrada',
                'si' => 'Consolidados',
                'no' => 'Sin consolidar'
            ],
            'wayout' => [
                'cualquiera' => 'Cualquier salida',
                'crear' => 'Crear salida',
                'si' => 'Con salida',
                'no' => 'Sin salida'
            ],
            'warehouse' => [
                'cualquiera' => 'Cualquier bodega',
                'usa' => 'Bodega USA',
                'mex' => 'Bodega MEX',
            ],
            'from' => $filters['from'],
            'to' => $filters['to'],
            'driver' => $filters['driver'],
            'repacker' => $filters['repacker'],
            'limit' => ['25','50','75','100','todos'],
            'pesajes' => config('stages'),
        ];
    }

    public function create($consolidated_id = null)
    {
        $options_measure = config('measures');
        $options_clients = false;
        $redirect = 'consolidados/mostrar/'.$consolidated_id;

        $model_consolidated = new Consolidated;
        if( ! $consolidated = $model_consolidated->withRelations($consolidated_id) )
        {
            $model_client = new Client;
            $options_clients = $model_client->availables();  
            $redirect = 'entradas';
        }

        $consolidated_alert = is_string($consolidated_id) && !is_object($consolidated);
        
        return view('entry/create', compact('consolidated_id','consolidated','consolidated_alert','options_clients','options_measure','redirect'));
    }

    public function validateCSV()
    {
        $this->validate($this->request->all(), [
            'cliente' => 'required'
        ]);
        
        $model_client = new Client;
        $client = $model_client->find( $this->request->get('cliente') );

        $consolidated_id = $this->request->has('consolidado') ? $this->request->get('consolidado') : null;
        $model_consolidated = new Consolidated;
        $consolidated = $model_consolidated->find($consolidated_id);

        $goback = is_object($consolidated) ? 'entradas/crear/'.$consolidated->id : 'entradas/crear';

        $alias_numero = $this->request->has('alias_numero') ? 1 : 0;

        $csv = [];
        $parsed = $this->parseCSV( $_FILES['csv']['tmp_name'] );

        $count_cells_parsed = count($parsed->header);
        $count_cells_csv = 22;
        $error_row = false;
        $measures_type_weight = config('measures', 'peso');
        $measures_type_volume = config('measures', 'volumen');

        if( $count_cells_parsed !== $count_cells_csv ) {
            $this->message(['danger', "Plantilla CSV incorrecto <b>({$count_cells_parsed} / {$count_cells_csv})</b>"]);
            return back();
        }

        foreach($parsed->reader as $row)
        {           
            $row = array_map(function ($value) {
                return mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
            }, $row); // Cuando el CSV no esta codificado en UTF-8

            $medida_peso = strtolower( trim($row['Medida de peso']) );
            $medida_volumen = strtolower( trim($row['Medida de volumen']) ); 

            $item = array(
                'numero'         => htmlspecialchars($row['Numero de entrada']),
                'alias_numero'   => $alias_numero,
                'cliente'        => $client->id,
                'consolidado'    => is_object($consolidated) ? $consolidated->id : null,
                'etapa'          => 'cliente',
                'peso'           => is_numeric($row['Peso']) ? $row['Peso'] : null,
                'medida_peso'    => in_array($medida_peso, $measures_type_weight) ? $medida_peso : 'libras',
                'ancho'          => is_numeric($row['Ancho']) ? $row['Ancho'] : null,
                'altura'         => is_numeric($row['Altura']) ? $row['Altura'] : null,
                'profundidad'    => is_numeric($row['Profundidad']) ? $row['Profundidad'] : null,
                'medida_volumen' => in_array($medida_volumen, $measures_type_volume) ? $medida_volumen : 'pulgadas',
                'remitente' => [
                    'nombre'    => $row['(R)Nombre'],
                    'telefono'  => $row['(R)Telefono'],
                    'direccion' => $row['(R)Direccion'],
                    'postal'    => $row['(R)Codigo Postal'],
                    'ciudad'    => $row['(R)Ciudad'],
                    'estado'    => $row['(R)Estado'],
                    'pais'      => $row['(R)Pais'],
                ],
                'destinatario' => [
                    'nombre'      => $row['(D)Nombre'],
                    'telefono'    => $row['(D)Telefono'],
                    'direccion'   => $row['(D)Direccion'],
                    'postal'      => $row['(D)Codigo Postal'],
                    'referencias' => $row['(D)Referencias'],
                    'ciudad'      => $row['(D)Ciudad'],
                    'estado'      => $row['(D)Estado'],
                    'pais'        => $row['(D)Pais'],
                ],
            );

            if( empty($item['numero']) ) {
                $error_row = true;
            }

            array_push($csv, $item);
        }

        $guide_props = ['numero','peso','medida_peso','ancho','altura','profundidad','medida_volumen'];
        $track_props = ['nombre','telefono','direccion','postal','ciudad','estado','pais'];

        return view('entry/validate_csv', compact(
            'csv',
            'client',
            'consolidated',
            'alias_numero',
            'goback',
            'error_row',
            'guide_props',
            'track_props'
            )
        );
    }
    
    private function parseCSV( $csvfile )
    {
        $reader = Reader::createFromPath($csvfile, 'r');
                
        $reader->setHeaderOffset(0); // valid offset but the record contain duplicates
                
        // $resultSet = Statement::create()->process($csv);

        try {
            return (object) [
                'header' => $reader->getHeader(),
                'reader' => $reader
            ];

            // return $reader->getRecords(); // throws a SyntaxError exception
            // return $resultSet->getRecords();
            // OLD: return $reader->setOffset(2)->fetchAll();

        } catch (SyntaxError $exception) {
            $duplicates = $exception->duplicateColumnNames(); // returns ['field1']
            exit('Error de columnas repetidas en el archivo CSV');
        }
        

        /*
        $file = new \SplFileObject($_FILES['csv']['tmp_name']);
        $reader = new CsvReader($file);
        $reader->setHeaderRowNumber(0);
        foreach($reader as $row){ #code };
        */
    }

    public function store($multi = false)
    {
        $session = session_get('user');

        $post = $this->request->all();

        // die( var_dump($post) );

        if( $multi === 'csv')
        {
            $results = $this->storeMulti($post, $session);

            if( count( $results['fails'] ) ) {
                $msg = ['danger', 'Algunas guias de entrada no se guardaron: '.implode(', ', $results['fails'])];
            } else {
                $msg = ['success', 'Guias de entrada guardadas: '.implode(', ', $results['success'])];
            }
        } 
        elseif( $entry_id = $this->storeSingle($post, $session) ) 
        {
            $msg = ['success', 'Guia de entrada guardada'];
        }
        else 
        {
            $msg = ['danger', 'Ya existe número o error al guardar guia(s) de entrada'];
        }

        $this->message($msg);
        
        $redirect = 'entradas'; 

        if( isset($post['consolidado']) ) {
            $redirect = 'consolidados/mostrar/'.$post['consolidado'];
        }

        return redirect($redirect);
    }
    
    private function storeMulti($post, $session)
    {
        $this->validate($post, [
            'csv' => 'required'
        ]);

        $results = array(
            'success' => [],
            'fails'   => [],
        );
        
        $csv = json_decode($post['csv']);
        
        foreach($csv as $row)
        {
            $guide = (array) $row; // Convert object to array assoc
            if( empty($guide['numero']) ) continue;

            $guide['remitente'] = (array) $row->remitente;
            $guide['destinatario'] = (array) $row->destinatario;
            $entry_id = $this->storeSingle($guide, $session, false);

            $happen = $entry_id ? 'success' : 'fails';

            array_push($results[ $happen ], $guide['numero']);
        }

        return $results;
    }

    private function storeSingle($post, $session, $validate = true)
    {
        if( $validate )
        {
            $this->validate($post, [
                'numero' => 'required'
            ]);
        }
        
        $model = new Entry;

        $client = isset($post['cliente']) && is_numeric($post['cliente']) ? $post['cliente'] : false;
        
        // if( $model->searchEntries(['numero_entrada'], $post['numero']) )
        
        if( $model->existsNumberWithSameClient($post['numero'], $client) ) {
            return false;
        }

        $data = $this->prepareData($post, $session);
        
        if( $entry_id = $model->store( $data ) )
        {
            $results = array();
            $results['measures']  = $this->storeMeasures($post, $entry_id, $session);
            $results['remitter']  = $this->storeRemitter($post, $entry_id, $session);
            $results['addressee'] = $this->storeAddressee($post, $entry_id, $session);
        }

        return $entry_id;
    }

    public function show($id = null)
    {
        $model = new Entry;
        if( ! $entries = $model->withRelations($id) )
            return back();

        $entry = array_shift($entries);

        $model_measure = new Measure;
        $measures = $model_measure->withRelations($entry->id, 'entrada_id');

        $model_wayout = new Wayout;
        $result_wayout = $model_wayout->withRelations($entry->id, 'entrada_id');
        $wayout = array_shift($result_wayout); 

        $dropOffStageQuery = new DropOffStage;
        if( $dropOffStage = $dropOffStageQuery->find($entry->id, 'entrada_id') )
        {
            $dropOffStage->nombre_creador = (new User)->find($dropOffStage->creado_por)->nombre ?? 'Desconocido';
            $dropOffStage->nombre_actualizador = (new User)->find($dropOffStage->actualizado_por)->nombre ?? 'Desconocido';
        }

        $stages = config('stages');
        $user_type = session_get('user','type');

        return view('entry/show', compact(
            'entry',
            'measures',
            'wayout',
            'dropOffStage',
            'stages',
            'user_type'
        ));
    }

    public function edit($id = null)
    {
        $model = new Entry;
        if( ! $entries = $model->withRelations($id) )
            return redirect('entradas');
        $entry = array_pop($entries);

        $model_client = new Client;
        $clients = $model_client->availables();
        
        $model_measure = new Measure;
        $measures = $model_measure->withRelations($entry->id, 'entrada_id');

        $model_user = new User;
        $options_warehouse_usa = $model_user->where('tipo', '=', 'bodega_usa');
        $options_warehouse_mex = $model_user->where('tipo', '=', 'bodega_mex');

        $model_driver = new Driver;
        $options_drivers = $model_driver->all();

        $model_car = new Car;
        $options_cars = $model_car->all();

        $model_coder = new Coder;
        $options_codesr = $model_coder->all();

        $model_repacker = new Repacker;
        $options_repackers = $model_repacker->all();

        $stages = config('stages');
        $options_measure = config('measures');
        return view('entry/edit', compact(
            'entry',
            'clients',
            'measures',
            'stages',
            'options_measure',
            'options_warehouse_usa',
            'options_drivers',
            'options_cars',
            'options_warehouse_mex',
            'options_codesr',
            'options_repackers')
        );
    }
    
    public function update($id = null)
    {
        $model = new Entry;
        if( ! $entry = $model->find($id) )
            return back();

        if( $this->request->has('numero') )      
        {
            $this->validate($this->request->all(), [
                'numero' => 'required',
            ]);
    
            $data = [
                'numero'               => $this->request->get('numero'),
                'alias_cliente_numero' => $this->request->has('alias_numero') ? 1 : 0,
                'cliente_id'           => $this->request->has('cliente') ? $this->request->get('cliente') : null,
                'actualizado_by'       => session_get('user', 'id'),
            ];
    
            $msg = $model->update($data, $entry->id) 
                 ? ['success', 'Guia de entrada actualizada']
                 : ['warning', 'Error al actualizar guia de entrada'];
        }  
        else
        {
            $msg = $this->updateProcess($this->request->all(), $entry, $model)
                 ? ['success', 'Proceso de guia de entrada actualizada']
                 : ['warning', 'Error al actualizar proceso de guia de entrada'];
        }

        $this->message($msg);
        return back();
    }

    public function updateProcess(array $post, $entry, $model)
    {
        $data = [
            'actualizado_by'   => session_get('user', 'id'),
            'codigor_id'       => isset($post['codigo_reempacado']) ? $post['codigo_reempacado'] : null,
            'conductor_id'     => isset($post['conductor']) ? $post['conductor'] : null,
            'cruce_at'         => !empty($post['cruce_date']) && !empty($post['cruce_time']) ? "{$post['cruce_date']}  {$post['cruce_time']}" : null,
            'en_bodega_mex_by' => isset($post['bodega_mex']) ? $post['bodega_mex'] : null,
            'en_bodega_usa_by' => isset($post['bodega_usa']) ? $post['bodega_usa'] : null,
            'numero_vuelta'    => !empty($post['numero_vuelta'] ) ? $post['numero_vuelta'] : null,
            'recibido_at'      => !empty($post['recibido_date']) && !empty($post['recibido_time']) ? $post['recibido_date'].' '.$post['recibido_time'] : null,
            'reempacado_at'    => !empty($post['reempacado_date']) && !empty($post['reempacado_time']) ? $post['reempacado_date'].' '.$post['reempacado_time'] : null,
            'reempacador_id'   => isset($post['reempacador']) ? $post['reempacador'] : null,
            'vehiculo_id'      => isset($post['vehiculo']) ? $post['vehiculo'] : null,
        ];        

        return $model->update($data, $entry->id);
    }

    public function updateObservations($id = null, $ajax = false)
    {
        $model = new Entry;

        if( $ajax )                        return $this->updateObservationsAjax($id, $model);
        if( ! $entry = $model->find($id) ) return back();

        $this->validate($this->request->all(), [
            'observacion' => 'required',
        ]);

        $observations = $this->prepareDataToUpdateObservations($this->request->all(), $entry);
        $msg = $model->update(['observaciones' => json_encode($observations)], $entry->id)
             ? ['success', 'Nueva observacion guardada']
             : ['danger', 'Error al guardar nueva observacion'];
        $this->message($msg);
        return back();
    }

    private function updateObservationsAjax($id, $model)
    {
        if( ! $entry = $model->find($id) )
        {
            header("HTTP/1.1 404 Not Found");
            echo json_encode([
                'status' => 'error',
                'message' => 'Guia de entrada no existe',
            ]);
            die;
        }

        if( !$this->request->has('observacion') || $this->request->isEmpty('observacion') ) 
        {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                'status' => 'error',
                'message' => 'Observacion sin contenido',
            ]);
            die;
        }

        $observations = $this->prepareDataToUpdateObservations($this->request->all(), $entry);
        
        if( ! $model->update(['observaciones' => json_encode($observations)], $entry->id) )
        {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al guardar observacion',
            ]);
            die;
        }

        echo json_encode([
            'status'  => 'success', 
            'message' => 'Observacion guardada',
            'saved'   => json_encode( array_shift($observations) ),
        ]);
        return;
    }
    
    private function prepareDataToUpdateObservations($post, $entry)
    {
        $new = [
            'observacion' => $post['observacion'],
            'creado_at'   => DATETIME_NOW,
            'nombre'      => session_get('user','name'),
            'usuario'     => session_get('user','id'),
        ];

        $observations = !is_null($entry->observaciones) ? json_decode($entry->observaciones) : [];
        array_unshift($observations, $new);
        return $observations;
    }

    public function toPrint($id = null)
    {
        $model = new Entry;

        if( ! $entries = $model->withRelations($id) )
        {
            echo 'GUIA DE ENTRADA INCORRECTA. Reportelo con el responsable de area y cierre esta ventana';
            return;
        }

        $entry = array_pop($entries);
        
        $model_measure = new Measure;
        $entry->medidas = $model_measure->getStage($entry->id, 'cliente');
        $entry->medidas->libras_a_kilos = $entry->medidas->medida_peso === 'libras'
                                        ? convert_units($entry->medidas->peso, 'libras', 'kilogramos')
                                        : null;

        return view('entry/print', compact('entry'));
    }

    public function warningDelete($id = null)
    {
        $model = new Entry;
        if( ! $entry = $model->withRelations($id)[0] )
            return back();

        $number = $entry->alias_cliente_numero ? stick($entry->cliente_alias, $entry->numero) : $entry->numero;
        
        return view('entry/warning_delete', compact('id','number'));
    }

    public function delete()
    {
        $this->validate($this->request->all(), [
            'entrada' => 'required',
            'numero' => 'required',
        ]);

        $model = new Entry;
        if( $model->delete($this->request->get('entrada')) )
        {
            $this->message(['success', "Guia de entrada <b>{$this->request->get('numero')}</b> eliminada"]);
            return redirect('entradas');
        }

        $this->message(['danger', 'Error al eliminar guia de entrada']);

        return back();
    }

    private function prepareData( array $post, $session )
    {
        return [
            'numero'               => htmlspecialchars($post['numero']),
            'alias_cliente_numero' => isset($post['alias_numero']) ? 1 : 0,
            'cliente_id'           => isset($post['cliente']) ? $post['cliente'] : null,
            'consolidado_id'       => isset($post['consolidado']) ? $post['consolidado'] : null,
            'en_bodega_usa_by'     => $session['type'] === 'bodega_usa' ? $session['id']: null,
            'en_bodega_mex_by'     => $session['type'] === 'bodega_mex' ? $session['id'] : null,
            'conductor_id'         => isset($post['conductor']) ? $post['conductor'] : null,
            'vehiculo_id'          => isset($post['vehiculo']) ? $post['vehiculo'] : null,
            'creado_by'            => $session['id'],
            'actualizado_by'       => $session['id'],
        ];
    }

    private function storeMeasures( array $post, $entry_id, $session )
    {
        $model_measure = new Measure;

        return $model_measure->storeMeasures($post, $entry_id, $session);
    }
    
    private function storeRemitter( array $post, $entry_id, $session )
    {
        $remitter = $post['remitente'];

        $data = [
            'nombre'         => $remitter['nombre'],
            'telefono'       => $remitter['telefono'],
            'direccion'      => $remitter['direccion'],
            'postal'         => $remitter['postal'],
            'ciudad'         => $remitter['ciudad'],
            'estado'         => $remitter['estado'],
            'pais'           => $remitter['pais'],
            'actualizado_by' => $session['id'],
            'entrada_id'     => $entry_id,
        ];

        $model_remitter = new Remitter;

        return $model_remitter->store($data);
    }

    private function storeAddressee( array $post, $entry_id, $session )
    {
        $addressee = $post['destinatario'];

        $data = [
            'nombre'         => $addressee['nombre'],
            'telefono'       => $addressee['telefono'],
            'direccion'      => $addressee['direccion'],
            'postal'         => $addressee['postal'],
            'referencias'    => $addressee['referencias'],
            'ciudad'         => $addressee['ciudad'],
            'estado'         => $addressee['estado'],
            'pais'           => $addressee['pais'],
            'actualizado_by' => $session['id'],
            'entrada_id'     => $entry_id,
        ];

        $model_addressee = new Addressee;

        return $model_addressee->store($data);
    }
}