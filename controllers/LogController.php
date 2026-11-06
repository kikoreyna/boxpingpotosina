<?php namespace Controllers;

use System\Core\Controller;
use Models\ActivityLog;
use Models\User;

class LogController extends Controller {

    public function index()
    {
        $filters = [
            'table'   => $this->get('tabla'),
            'action'  => $this->get('accion'),
            'user_id' => $this->get('usuario'),
            'from'    => $this->validDate($this->get('desde')),
            'to'      => $this->validDate($this->get('hasta')),
            'q'       => $this->get('buscar'),
        ];

        $logs = (new ActivityLog)->search($filters);
        $tables = ActivityLog::labels();
        $actions = ActivityLog::ACTIONS;
        $users = (new User)->where('tipo', '<>', 'cliente');

        return view('log/index', compact('logs', 'filters', 'tables', 'actions', 'users'));
    }

    private function get($key)
    {
        return $this->request->has($key, 'get') ? trim($this->request->get($key, 'get')) : null;
    }

    private function validDate($value)
    {
        $date = $value ? \DateTime::createFromFormat('Y-m-d', $value) : false;
        return $date && $date->format('Y-m-d') === $value ? $value : null;
    }
}
