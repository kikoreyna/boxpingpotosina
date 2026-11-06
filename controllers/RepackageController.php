<?php namespace Controllers;

use System\Core\Controller;
use Models\Repacker;
use Models\Coder;

class RepackageController extends Controller {
    
    public function index()
    {
        $model_repacker = new Repacker;
        $repackers = $model_repacker->availables();

        $model_coder = new Coder;
        $codesr = $model_coder->availables();
        
        $dates = [
            'desde' => $this->request->has('desde', 'get') ? $this->request->get('desde', 'get') : date('Y-m-d'),
            'hasta' => $this->request->has('hasta', 'get') ? $this->request->get('hasta', 'get') : date('Y-m-d'),
        ];
        
        $report = array();
        foreach($repackers as $repacker)
        {
            $result = $model_repacker->raw(
                "SELECT * FROM entradas WHERE CAST(reempacado_at AS DATE) BETWEEN '{$dates['desde']}' AND '{$dates['hasta']}' AND reempacador_id = {$repacker->id} ORDER BY id DESC", 
                TRUE
            );
            
            $item = [
                'id' => $repacker->id,
                'name' => $repacker->nombre,
                'entries_count' => count($result),
            ];

            array_push($report, $item);
        }

        view('repackage/index', compact('repackers','codesr','dates','report'));
    }
}