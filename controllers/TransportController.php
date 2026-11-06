<?php namespace Controllers;

use System\Core\Controller;
use Models\Transport;

class TransportController extends Controller {
    
    public function index()
    {
        $model = new Transport;
        $transports = $model->all();
        return view('transport/index', compact('transports'));
    }
    
    public function create()
    {
        return view('transport/create');
    }
    
    public function store()
    {
        $this->validate($this->request->all(), [
            'nombre' => 'required'
        ]);

        $model = new Transport;
        if( $model->store( $this->request->all() ) )
        {
            $this->message(['success', "Transportadora {$this->request->get('nombre')} guardado"]);
            return redirect('transportadoras');
        }
         
        $this->message(['danger', 'Error al guardar transportadora']);
        return back();
    }

    public function edit($id = null)
    {
        $model = new Transport;
        if( $transport = $model->find($id) )
            return view('transport/edit', ['transport' => $transport]);

        return redirect('transportadoras');
    }

    public function update($id = null)
    {
        $model = new Transport;
        if( $transport = $model->find($id) )
        {
            $msg = $model->update($this->request->all(), $id)
                ? ['success', 'Transportadora actualizada']
                : ['danger', 'Error al actualizar transportadora'];
            $this->message($msg);
            return back();
        }

        return redirect('transportadoras');
    }
}