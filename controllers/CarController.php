<?php namespace Controllers;

use System\Core\Controller;
use System\Core\Request;
use Models\Car;
    
class CarController extends Controller
{
    public function index(){}

    public function create()
    {
        return view('car/create');
    }

    public function store()
    {
        $this->validate($this->request->all(), [
            'alias' => 'required',
        ]);

        $model = new Car;
        $msg = $model->store( $this->request->all() )
             ? ['success',"Vehiculo {$this->request->get('alias')} guardado"]
             : ['danger', 'Error al guardar vehiculo'];
        $this->message($msg);
        return redirect('cruce');
    }

    public function edit($id = null)
    {
        $model = new Car;
        if( $car = $model->find($id) )
            return view('car/edit', compact('car'));

        return redirect('cruce');
    }

    public function update($id = null)
    {
        $this->validate($this->request->all(), [
            'alias' => 'required;unique:vehiculos-id,'.$id,
        ]);

        $model = new Car;
        $msg = $model->update($this->request->all(), $id)
             ? ['success', 'Vehiculo actualizado']
             : ['danger', 'Error al actualizar vehiculo'];
        $this->message($msg);
        return back();
    }

    public function erase($id = null)
    {
        $model = new Car;
        if( $car = $model->find($id) )
            return view('car/erase', compact('car'));
        
        return redirect('cruce');
    }

    public function deleteSoft($id = null)
    {
        $this->validate($this->request->all(), [
                'alias' => 'required'
            ]
        );

        $model = new Car;
        $msg = $model->deleteSoft($id)
             ? ['success', "Vehiculo {$this->request->get('alias')} eliminado"]
             : ['danger', "Error al eliminar vehiculo {$this->request->get('alias')}"];
        $this->message($msg);
        return redirect('cruce');
    }
}