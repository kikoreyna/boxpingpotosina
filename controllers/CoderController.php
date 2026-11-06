<?php namespace Controllers;

use System\Core\Controller;
use Models\Coder;
    
class CoderController extends Controller
{
    public function index(){}

    public function create()
    {
        return view('coder/create');
    }

    public function store()
    {
        $this->validate( $this->request->all(), [
            'codigo' => 'required;unique:codigos_reempacado',
            'descripcion' => 'required'
        ]);

        $model = new Coder;
        $msg = $model->store( $this->request->all() )
             ? ['success', 'Codigo de reempacado guardado']
             : ['danger', 'Error al guardar codigo de reempacado'];
        $this->message($msg);
        return redirect('reempaque');
    }

    public function edit($id = null)
    {
        if( !is_numeric($id) )
            return redirect('reempaque');

        $model = new Coder;
        if( $coder = $model->find($id) )
            return view('coder/edit', compact('coder'));
        
        return back();
    }

    public function update($id = null)
    {
        if( !is_numeric($id) )
            return redirect('reempaque');

        $this->validate($this->request->all(), [
            'codigo' => 'required;unique:codigos_reempacado-id,'.$id,
            'descripcion' => 'required'
        ]);

        $model = new Coder;
        $msg = $model->update($this->request->all(), $id)
             ? ['success', 'Codigo de reempacado actualizado']
             : ['danger', 'Error al actualizar codigo de reempacado'];
        $this->message($msg);
        return back();
    }

    public function erase($id = null)
    {
        if( !is_numeric($id) )
            return redirect('reempaque');

        $model = new Coder;
        if( $coder = $model->find($id) )
            return view('coder/erase', compact('coder'));
        
        return back();
    }

    public function deleteSoft($id = null)
    {
        if( !is_numeric($id) )
            return redirect('reempaque');

        $this->validate($this->request->all(), [
                'codigo' => 'required'
            ]
        );

        $model = new Coder;
        $msg = $model->deleteSoft($id)
             ? ['success', "Código {$this->request->get('codigo')} eliminado"]
             : ['danger', "Error al eliminar código {$this->request->get('codigo')}"];
        $this->message($msg);
        return redirect('reempaque');
    }
}