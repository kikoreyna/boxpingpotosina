<?php namespace Controllers;

use System\Core\Controller;
use Models\Addressee;

class AddresseeController extends Controller {

    public function index(){}
    
    public function update($id = null)
    {
        $model = new Addressee;
        if( ! $addressee = $model->find($id) )
            return back();

        $this->validate($this->request->all(), [
            'postal' => 'required',
            'pais' => 'required',
        ]);

        $data = [
            'nombre'          => $this->request->isEmpty('nombre')      ? null : $this->request->get('nombre'),
            'direccion'       => $this->request->isEmpty('direccion')   ? null : $this->request->get('direccion'),
            'postal'          => $this->request->isEmpty('postal')      ? null : $this->request->get('postal'),
            'referencias'     => $this->request->isEmpty('referencias') ? null : $this->request->get('referencias'),
            'ciudad'          => $this->request->isEmpty('ciudad')      ? null : $this->request->get('ciudad'),
            'estado'          => $this->request->isEmpty('estado')      ? null : $this->request->get('estado'),
            'pais'            => $this->request->isEmpty('pais')        ? null : $this->request->get('pais'),
            'telefono'        => $this->request->isEmpty('telefono')    ? null : $this->request->get('telefono'),
            'verificacion_at' => $this->request->exists('verificacion') ? DATETIME_NOW : null,
            'actualizado_by'  => session_get('user','id'),
        ];

        $msg = $model->update($data, $addressee->id)
            ? ['success', 'Destinatario actualizado']
            : ['danger', 'Error al actualizar destinatario'];
        $this->message($msg);
        return back();
    }
}
