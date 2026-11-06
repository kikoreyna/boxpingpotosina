<?php namespace Controllers;

use System\Core\Controller;
use System\Core\Request;
use Models\Car;
use Models\Coder;
    
class RequerimentController extends Controller {
    
    public $requeriments = [
        'vehiculo' => 'car',
        'codigor' => 'coder'
    ];
    
    public function index()
    {
        $modelCar = new Car;
        $modelCoder = new Coder;
        
        $cars = $modelCar->all();
        $codes = $modelCoder->all();
        
        view('requeriment/index', compact('cars','codes'));
    }
    
    public function create($key)
    {
        $requeriment = $this->requeriments[$key];
        $path = "requeriment/{$requeriment}/create";
        return view($path);
    }
    
    public function store()
    {
        $redirect = 'requerimientos';
        $request = new Request;
        
        if( $request->has('requerimiento') )
        {
            if( $model = $this->getModel( $request->get('requerimiento') ) )
            {
                $request->erase('requerimiento');
                $requeriment = strpos(get_class($model), 'Car') ? 'Vehiculo' : 'Código de reempacado';
                
                $data = [$request->all()];
                $find = ['nombre' => $request->get('nombre')];
                $notduplicated = $model->unduplicated('store', $data, $find);
                if( !is_object($notduplicated) )
                {
                    $msg = !is_bool($notduplicated)
                         ? ['success', "{$requeriment} guardado con exito!"]
                         : ['danger', "Error al guardar {$requeriment}"];
                }
                else
                {
                    $msg = ['warning', "{$requeriment} ya existe, intenta con otro nombre!"];
                    $param = $requeriment === 'Vehiculo' ? 'vehiculo': 'codigor';
                    $redirect = "requerimientos/nuevo/{$param}";
                }
                $this->message($msg);
            }
        }
        
        redirect($redirect);
    }
    
    public function edit($id = null, $requeriment = null)
    {
        if( !is_null($id) && $model = $this->getModel($requeriment) )
        {
            $path = "requeriment/{$this->requeriments[$requeriment]}/edit";
            if( $requeriment = $model->find($id) )
                return view($path, compact('requeriment'));
        }
        
        redirect('requerimientos');
    }
    
    public function update($id = null)
    {
        $redirect = 'requerimientos';
        $request = new Request;
        
        if( !is_null($id) && $request->has('requerimiento') )
        {
            $model = $this->getModel($request->get('requerimiento'));
            if( !is_null($model) && $model->find($id) )
            {
                $request->erase('requerimiento');
                    
                $data = [$request->all(), $id];
                $find = ['nombre' => $request->get('nombre')];
                $except = ['id' => $id];
                $result = $model->unduplicated('update', $data, $find, $except);
                
                $requeriment = strpos(get_class($model), 'Car') ? 'Vehiculo' : 'Código de reempacado';
                if( !is_object($result) )
                {
                    $msg = !is_bool($result)
                         ? ['success', "{$requeriment} se actualizo correctamente!"]
                         : ['danger', "{$requeriment} no pudo actualizarse"];
                }
                else
                {
                    $msg = ['warning', "{$requeriment} ya existe! intenta de nuevo"];
                    $param = $requeriment === 'Vehiculo' ? 'vehiculo': 'codigor';
                    $redirect = "requerimientos/editar/{$id}/{$param}";
                }
                $this->message($msg);
            }
        }
        
        redirect($redirect);
    }
    
    public function erase($id = null, $requeriment = null)
    {
        $path = "requeriment/{$this->requeriments[$requeriment]}/erase";
        view($path);        
    }
    
    private function getModel($requeriment)
    {
        if( isset( $this->requeriments[ $requeriment ] ) )
        {
            return $this->requeriments[ $requeriment ] === 'car' ? new Car : new Coder;
        }
        return false;
    }
}
