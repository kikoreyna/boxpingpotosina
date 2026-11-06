<?php namespace Controllers;

use Models\Entry;

class DashboardController extends \System\Core\Controller {

    public function index()
    {
        $model = new Entry;
        $result_counters = $model->getCounters();
        $counters = array_pop($result_counters);
        // showme($counters);
        view('dashboard/index', compact('counters'));
    }

    public function download($file = null)
    {
        $filename = 'plantilla-potosina.csv';
        $filepath = path("store/files/{$filename}");
        $filesize = filesize($filepath);
        return view('dashboard/download', compact('filename','filepath','filesize'));
    }
}