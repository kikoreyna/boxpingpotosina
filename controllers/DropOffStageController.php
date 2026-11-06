<?php 

namespace Controllers;

use Models\DropOffStage;
use Models\Entry;
use Models\User;
use System\Core\Controller;

class DropOffStageController extends Controller {
    
    public function index()
    {

    }
    
    public function load()
    {
        view('dropoff-stage/carga');
    }

    public function loading()
    {
        $this->validate($this->request->all(), [
            'numero_entrada' => 'required',
        ]);

        $entryQuery = new Entry;

        if(! $entry = $entryQuery->find($this->request->get('numero_entrada'), 'numero') ) {
            $this->message(['danger', 'Numero de entrada no encontrada']);
            return back();
        }

        $dropOffStageQuery = new DropOffStage;

        if( $dropOffStage = $dropOffStageQuery->find($entry->id, 'entrada_id') )
        {
            $dropOffStageQuery->update([
                'fecha_carga' => DATETIME_NOW,
                'actualizado_por' => session_get('user', 'id'),
            ], $dropOffStage->id);

            $this->message(['success', "Numero de guia [{$entry->numero}] ya habia sido registrado, fecha de carga actualizado"]);

            return back();
        } 

        $dropOffStageQuery->store([
            'entrada_id' => $entry->id,
            'fecha_carga' => DATETIME_NOW,
            'creado_por' => session_get('user', 'id'),
            'actualizado_por' => session_get('user', 'id'),
        ]);

        $this->message(['success', "Numero de guia [{$entry->numero}] registrado como cargado"]);

        return back();
    }

    public function unload()
    {
        view('dropoff-stage/descarga');
    }

    public function unloading()
    {
        $this->validate($this->request->all(), [
            'numero_entrada' => 'required',
        ]);

        $entryQuery = new Entry;

        if(! $entry = $entryQuery->find($this->request->get('numero_entrada'), 'numero') ) {
            $this->message(['danger', 'Numero de entrada no encontrada']);
            return back();
        }

        $dropOffStageQuery = new DropOffStage;

        if( $dropOffStage = $dropOffStageQuery->find($entry->id, 'entrada_id') )
        {
            $dropOffStageQuery->update([
                'fecha_descarga' => DATETIME_NOW,
                'actualizado_por' => session_get('user', 'id'),
            ], $dropOffStage->id);

            $this->message(['success', "Numero de guia [{$entry->numero}] registrado como descargado"]);

            return back();
        } 

        $dropOffStageQuery->store([
            'entrada_id' => $entry->id,
            'fecha_descarga' => DATETIME_NOW,
            'creado_por' => session_get('user', 'id'),
            'actualizado_por' => session_get('user', 'id'),
        ]);

        $this->message(['success', "Numero de guia [{$entry->numero}] no habia sido registrado, fecha de descarga actualizado"]);

        return back();
    }
}
