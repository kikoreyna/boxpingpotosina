<?php namespace Controllers;

use System\Core\Controller;
use Models\Remitter;

class RemitterController extends Controller {

    public function index(){}
    
    public function update($id = null)
    {
        $model = new Remitter;
        if( ! $remitter = $model->find($id) )
            return back();

        $data = [
            'nombre'         => $this->request->isEmpty('nombre')    ? null : $this->request->get('nombre'),
            'direccion'      => $this->request->isEmpty('direccion') ? null : $this->request->get('direccion'),
            'postal'         => $this->request->isEmpty('postal')    ? null : $this->request->get('postal'),
            'ciudad'         => $this->request->isEmpty('ciudad')    ? null : $this->request->get('ciudad'),
            'estado'         => $this->request->isEmpty('estado')    ? null : $this->request->get('estado'),
            'pais'           => $this->request->isEmpty('pais')      ? null : $this->request->get('pais'),
            'telefono'       => $this->request->isEmpty('telefono')  ? null : $this->request->get('telefono'),
            'actualizado_by' => session_get('user','id'),
        ];

        $msg = $model->update($data, $remitter->id)
            ? ['success', 'Remitente actualizado']
            : ['danger', 'Error al actualizar remitente'];
        $this->message($msg);
        return back();
    }
}
