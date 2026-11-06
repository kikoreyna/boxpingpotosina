<?php namespace Controllers;

use System\Core\Controller;

use Models\Task;
use Models\Entry;
use Models\User;

class TaskController extends Controller {

    public function index()
    {
        $is_admin = $this->isAdmin();
        $status = $this->request->has('estatus', 'get') ? $this->request->get('estatus', 'get') : 'pendiente';
        $status = in_array($status, ['pendiente', 'completada']) ? $status : '';

        $filters = ['status' => $status];
        $filters['user_id'] = $is_admin
            ? ($this->request->has('usuario', 'get') && is_numeric($this->request->get('usuario', 'get')) ? $this->request->get('usuario', 'get') : null)
            : session_get('user', 'id');

        $model = new Task;
        $tasks = $model->withRelations($filters);
        $documenters = $is_admin ? $this->documenters() : [];

        return view('task/index', compact('tasks', 'filters', 'documenters', 'is_admin'));
    }

    public function store()
    {
        if( !$this->isAdmin() )
            return redirect('tareas');

        $this->validate($this->request->all(), [
            'entrada'      => 'required',
            'documentador' => 'required',
            'descripcion'  => 'required',
        ]);

        $description = mb_substr(trim($this->request->get('descripcion')), 0, 255);
        if( $description === '' )
        {
            $this->message(['danger', 'Indique la necesidad de la tarea']);
            return back();
        }

        $model_entry = new Entry;
        $entry = $model_entry->find(trim($this->request->get('entrada')), 'numero');
        if( !is_object($entry) )
        {
            $this->message(['danger', 'No existe una entrada con ese número']);
            return back();
        }

        $user = (new User)->find($this->request->get('documentador'));
        if( !is_object($user) || $user->tipo !== 'documentador' )
        {
            $this->message(['danger', 'Seleccione un usuario documentador válido']);
            return back();
        }

        $model = new Task;
        $duplicated = $model->openDuplicate($entry->id, $user->id, $description);
        if( $duplicated )
        {
            $this->message(['warning', "{$user->nombre} ya tiene pendiente esa misma tarea en la entrada {$entry->numero}"]);
            return back();
        }

        $saved = $model->store([
            'entrada_id'  => $entry->id,
            'usuario_id'  => $user->id,
            'descripcion' => $description,
            'asignado_by' => session_get('user', 'id'),
            'estatus'     => 'pendiente',
        ]);

        $msg = $saved ? ['success', "Entrada {$entry->numero} asignada a {$user->nombre}"]
                      : ['danger', 'Error al asignar la tarea'];
        $this->message($msg);
        return back();
    }

    public function complete($id = null)
    {
        $model = new Task;
        $task = $model->find($id);

        if( !is_object($task) || $task->estatus !== 'pendiente' )
            return redirect('tareas');

        if( !$this->isAdmin() && (int) $task->usuario_id !== (int) session_get('user', 'id') )
            return redirect('tareas');

        $done = $model->update([
            'estatus'       => 'completada',
            'completado_at' => DATETIME_NOW,
        ], $task->id);

        $this->message($done ? ['success', 'Tarea marcada como completada'] : ['danger', 'Error al completar la tarea']);
        return back();
    }

    public function delete($id = null)
    {
        if( !$this->isAdmin() )
            return redirect('tareas');

        $model = new Task;
        if( is_object($task = $model->find($id)) )
        {
            $this->message($model->delete($task->id)
                ? ['success', 'Tarea eliminada']
                : ['danger', 'Error al eliminar la tarea']);
        }
        return back();
    }

    private function isAdmin()
    {
        return is_admin();
    }

    private function documenters()
    {
        return (new User)->where('tipo', '=', 'documentador');
    }
}
