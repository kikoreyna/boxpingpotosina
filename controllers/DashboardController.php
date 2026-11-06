<?php namespace Controllers;

use Models\Entry;
use Models\Task;

class DashboardController extends \System\Core\Controller {

    public function index()
    {
        $model = new Entry;
        $result_counters = $model->getCounters();
        $counters = array_pop($result_counters);

        $model_task = new Task;
        $is_documenter = session_get('user', 'type') === 'documentador';
        $my_tasks = $is_documenter
            ? $model_task->withRelations(['user_id' => session_get('user', 'id'), 'status' => 'pendiente'])
            : [];
        $pending_tasks = $is_documenter ? count($my_tasks) : $model_task->pendingCount();

        // showme($counters);
        view('dashboard/index', compact('counters', 'my_tasks', 'pending_tasks', 'is_documenter'));
    }

    public function download($file = null)
    {
        $filename = 'plantilla-potosina.csv';
        $filepath = path("store/files/{$filename}");
        $filesize = filesize($filepath);
        return view('dashboard/download', compact('filename','filepath','filesize'));
    }
}