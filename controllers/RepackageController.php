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
            'hora_desde' => $this->request->has('hora_desde', 'get') ? $this->request->get('hora_desde', 'get') : '00:00',
            'hora_hasta' => $this->request->has('hora_hasta', 'get') ? $this->request->get('hora_hasta', 'get') : '23:59',
        ];

        if (
            !$this->isValidDate($dates['desde'])
            || !$this->isValidDate($dates['hasta'])
            || $dates['desde'] > $dates['hasta']
            || !$this->isValidTime($dates['hora_desde'])
            || !$this->isValidTime($dates['hora_hasta'])
        ) {
            $this->message(['warning', 'Revisa el rango de fechas y horas del reporte']);
            return back();
        }

        $timeFilter = $dates['hora_desde'] <= $dates['hora_hasta']
            ? "TIME(reempacado_at) BETWEEN '{$dates['hora_desde']}:00' AND '{$dates['hora_hasta']}:59'"
            : "(TIME(reempacado_at) >= '{$dates['hora_desde']}:00' OR TIME(reempacado_at) <= '{$dates['hora_hasta']}:59')";

        $report = [];
        foreach($repackers as $repacker)
        {
            $result = $model_repacker->raw(
                "SELECT COUNT(*) AS entries_count FROM entradas "
                . "WHERE reempacado_at IS NOT NULL "
                . "AND DATE(reempacado_at) BETWEEN '{$dates['desde']}' AND '{$dates['hasta']}' "
                . "AND {$timeFilter} "
                . "AND reempacador_id = {$repacker->id}",
                TRUE
            );
            
            $item = [
                'id' => $repacker->id,
                'name' => $repacker->nombre,
                'entries_count' => (int) $result[0]->entries_count,
            ];

            array_push($report, $item);
        }

        view('repackage/index', compact('repackers','codesr','dates','report'));
    }

    private function isValidDate($value)
    {
        if (!is_string($value) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return false;
        }

        $date = \DateTime::createFromFormat('!Y-m-d', $value);
        return $date && $date->format('Y-m-d') === $value;
    }

    private function isValidTime($value)
    {
        if (!is_string($value) || !preg_match('/^\d{2}:\d{2}$/', $value)) {
            return false;
        }

        $time = \DateTime::createFromFormat('!H:i', $value);
        return $time && $time->format('H:i') === $value;
    }
}