<?php namespace Controllers;

use System\Core\Controller;
use Models\Entry;
use Models\Measure;
use Models\DropOffStage;
use Models\User;

class HistoryController extends Controller {

    public function index(){}

    public function show($entry_id = null)
    {
        $model_entry = new Entry;
        if( ! $entry = $model_entry->find($entry_id) )
        {
            header("HTTP/1.0 404 Not Found");
            die('Guia de entrada no existe');
        }

        $dropOffStageQuery = new DropOffStage;
        if( $dropOffStage = $dropOffStageQuery->find($entry->id, 'entrada_id') )
        {
            $dropOffStage->nombre_creador = (new User)->find($dropOffStage->creado_por)->nombre;
            $dropOffStage->nombre_actualizador = (new User)->find($dropOffStage->actualizado_por)->nombre;
        }

        $model_measure = new Measure;

        echo json_encode([
            'measures' => $model_measure->withRelations($entry_id, 'entrada_id'),
            'dropOffStage' => $dropOffStage,
            'observations' => json_decode($entry->observaciones ?? ''),
        ]);
        
        exit;
    }
}

/*
// header('HTTP/1.1 400 Bad Request');
// header('Content-Type: application/json; charset=UTF-8');
// die( json_encode( ['message' => 'ERROR', 'code' => 1337] ) );
// isset($_SERVER['HTTP_X_REQUESTED_WITH'])
// $_SERVER['HTTP_X_REQUESTED_WITH']  == 'XMLHttpRequest'

// header('Cache-Control: no-cache, must-revalidate');
// header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
// header('Content-type: application/json');
// die(json_encode($return));
*/