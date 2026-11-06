<?php namespace Controllers;

use System\Core\Controller;
use Models\Entry;
use Models\Measure;
use Models\Coder;

class RepackagedController extends Controller
{
    private $model_entry;

    public function __construct()
    {
        parent::__construct();
        $this->model_entry = new Entry;
    }

    public function index()
    {
        $model_coder = new Coder;
        $coders = $model_coder->all();
        return view('repackaged/index', compact('coders'));
    }

    public function choose($number)
    {
        if(! $this->request->has('codigo_reempacado', 'get') ) {
            $this->message(['warning', 'Codigo de reempacado es obligatorio']);
            return back();
        }

        $entries  = $this->model_entry->withRelations($number, 'numero');
        $coder    = $this->request->has('codigo_reempacado', 'get');
        $passcode = session_get('repacker_passcode');
        return view('repackaged/choose', compact('number','entries', 'coder', 'passcode'));
    }

    public function update($field = 'numero')
    {
        if(! $validated = $this->validateProcess($this->request->all(), $field) ) {
            $this->message('warning','Revisa de nuevo la informacion de la guia');
            return back();
        }

        $msg = $this->updateEntry($this->request->all(), $validated)
            ? ['success', 'Numero de guia actualizado']
            : ['danger','Error al actualizar numero de guia'];

        $this->message($msg);
        return back();
    }

    public function updateEntry($post, $validated)
    {
        $patch = $this->prepareDataToSaveEntry($post, $validated['repacker_id']);
        return $this->model_entry->update($patch, $validated['entry_id']);
    }

    private function prepareDataToSaveEntry($post, $repacker_id)
    {
        return [
            'reempacador_id' => $repacker_id,
            'codigor_id'     => $post['codigo_reempacado'],
            'reempacado_at'  => DATETIME_NOW,
        ];
    }

    private function validateProcess($post, $column)
    {
        $this->validate($post, [
            'numero'            => 'required',
            'codigo_reempacado' => 'required',
            'clave'             => 'required',
        ]);

        if(! $repacker = $this->authRepackerPasscode($post['clave']) ) {
            $this->message(['danger', 'Clave de reempacador incorrecto']);
            return back();
        }

        if(! $entries = $this->model_entry->where($column,'=',$post['numero'],false) ) {
            $this->message(['danger', "Guia <b>{$post['numero']}</b> no ha sido registrada"]);
            return back();
        }

        if( count($entries) > 1 ) {
            session_set('repacker_passcode', $post['clave']);
            return redirect("reempacado/seleccionar/{$post['numero']}/?codigo_reempacado={$post['codigo_reempacado']}");
        }
        $entry = array_pop($entries);

        $model_measure = new Measure;
        $measures_entry = $model_measure->where('entrada_id','=',$entry->id);
        // $measure_bmex_entrada = array_filter($measures_entry, function ($me) {
        //     return $me->etapa === 'bodega_mex_entrada';
        // });

        if( is_null($entry->en_bodega_mex_by) ) {
            $this->message(['warning', "Guia <b>{$post['numero']}</b> Require registro y medidas de entrada en bodega Mex"]);
            return back();
        }

        if( session_has('repacker_passcode') ) session_erase('repacker_passcode');

        return ['repacker_id' => $repacker->id, 'entry_id' => $entry->id];
    }

    private function authRepackerPasscode($value)
    {
        $passcode = \Models\Auth::hashToCode( $value );
        $model_repacker = new \Models\Repacker;
        return $model_repacker->find($passcode, 'passcode');
    }
}

/*

[16-Apr-2019 07:31:37 America/Chicago] PHP Fatal error:  Uncaught exception 'PDOException' with message 'SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'LIMIT 1' at line 1' in /home/lapotosina/app.lapotosinaexpress.com/system/core/Model.php:217
Stack trace:
#0 /home/lapotosina/app.lapotosinaexpress.com/system/core/Model.php(217): PDOStatement->execute(Array)
#1 /home/lapotosina/app.lapotosinaexpress.com/controllers/RepackagedController.php(52): System\Core\Model->update(Array, NULL)
#2 /home/lapotosina/app.lapotosinaexpress.com/controllers/RepackagedController.php(41): Controllers\RepackagedController->updateEntry(Array, NULL)
#3 [internal function]: Controllers\RepackagedController->update('numero')
#4 /home/lapotosina/app.lapotosinaexpress.com/system/core/Xarvis.php(49): call_user_func_array(Array, Array)
#5 /home/lapotosina/app.lapotosinaexpress.com/public/index.php(13): System\Core\Xarvis->attend()
#6 {main}
  thrown in /home/lapotosina/app.lapotosinaexpress.com/system/core/Model.php on line 217

*/