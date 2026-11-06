<?php namespace Controllers;

use System\Core\Controller;
use Models\Depot;

class DepotController extends Controller
{
    public function index()
    {
        $depots = (new Depot)->availables('ASC');
        return view('depot/index', compact('depots'));
    }

    public function store()
    {
        $this->validate($this->request->all(), [
            'nombre' => 'required;unique:bodegas_mex',
        ]);

        $msg = (new Depot)->store($this->data())
             ? ['success', 'Bodega guardada']
             : ['danger', 'Error al guardar bodega'];
        $this->message($msg);
        return redirect('almacenes');
    }

    public function edit($id = null)
    {
        $depot = (new Depot)->available($id);
        if( !is_object($depot) )
            return redirect('almacenes');

        return view('depot/edit', compact('depot'));
    }

    public function update($id = null)
    {
        $model = new Depot;
        if( !is_object($model->available($id)) )
            return redirect('almacenes');

        $this->validate($this->request->all(), [
            'nombre' => 'required;unique:bodegas_mex-id,'.$id,
        ]);

        $msg = $model->update($this->data(), $id)
             ? ['success', 'Bodega actualizada']
             : ['danger', 'Error al actualizar bodega'];
        $this->message($msg);
        return redirect('almacenes');
    }

    public function delete($id = null)
    {
        $model = new Depot;
        if( is_object($model->available($id)) )
            $this->message($model->deleteSoft($id) ? ['success', 'Bodega eliminada'] : ['danger', 'Error al eliminar bodega']);

        return redirect('almacenes');
    }

    private function data()
    {
        return [
            'nombre'    => trim($this->request->get('nombre')),
            'direccion' => trim((string) $this->request->get('direccion')) ?: null,
        ];
    }
}
