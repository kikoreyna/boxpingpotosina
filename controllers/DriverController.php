<?php namespace Controllers;

use System\Core\Controller;
use System\Core\Request;
use Models\Driver;

class DriverController extends Controller {

    public function index(){}

    public function create()
    {
        return view('driver/create');
    }

    public function store()
    {
        $this->validate($this->request->all(), [
            'nombre' => 'required'
        ]);

        $model = new Driver;        
        $msg = $model->store( $this->request->all() ) 
             ? ['success', 'Nuevo conductor guardado']
             : ['danger', 'Error al guardar nuevo conductor'];
        $this->message($msg);
        return redirect('cruce');
    }

    public function edit($id)
    {
        $model = new Driver;
        if( $driver = $model->find($id) )
            return view('driver/edit', compact('driver'));
        
        return redirect('cruce');
    }

    public function update($id)
    {
        if( !is_numeric($id) )
            return redirect('cruce');
        
        $this->validate($this->request->all(), [
            'nombre' => 'required'
            ]);
            
        $model = new Driver;
        $msg = $model->update($this->request->all(), $id) 
             ? ['success', 'Conductor actualizado']
             : ['danger', 'Error al actualizar conductor'];
        $this->message($msg);
        return back();
    }
}