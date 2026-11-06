<?php namespace Controllers;

use System\Core\Controller;
use Models\Repacker;
use Models\Auth;

class RepackerController extends Controller {
    
    public function index(){}

    public function create()
    {
        return view('repacker/create');
    }

    public function store()
    {
        $this->validate( 
            $this->request->all(), 
            [
                'nombre' => 'required',
                'clave' => 'required',
            ]
        );

        $model = new Repacker;
        $passcode = Auth::hashToCode( $this->request->get('clave') );
        if( $repacker = $model->find($passcode, 'passcode') )
        {
            $this->message(['warning', 'Clave debe ser unica.']);
            return back();
        }

        $data = [
            'nombre' => $this->request->get('nombre'),
            'passcode' => $passcode,
        ];

        $msg = $model->store( $data )
             ? ['success', "Reempacador guardado"]
             : ['danger', "Error al guardar reempacador"];
        $this->message($msg);
        return redirect('reempaque');
    }

    public function edit($id = null)
    {
        $model = new Repacker;
        if( is_numeric($id) && $repacker = $model->find($id) )
            return view('repacker/edit', compact('repacker'));

        return back();
    }

    public function update($id = null)
    {
        if( !is_numeric($id) )
            return redirect('reempaque');

        $this->validate(
            $this->request->all(),
            [
                'nombre' => 'required'
            ]
        );

        $data = ['nombre' => $this->request->get('nombre') ];
        if( $this->request->has('clave') )
        {
            if( $repacker = Repacker::findByPasscode( $this->request->get('clave') ) )
            {
                if( $repacker->id !== $id )
                {
                    $this->message(['warning', 'Clave debe ser unica.']);
                    return back();
                }
            }
            $data['passcode'] = Auth::hashToCode( $this->request->get('clave') );
        }

        $model = new Repacker;
        $msg = $model->update($data, $id)
             ? ['success', 'Reempacador actualizado']
             : ['danger', 'Error al actualizar reempacador'];
        $this->message($msg);
        return back();
    }
}