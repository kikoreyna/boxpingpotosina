<?php namespace Controllers;

use System\Core\Controller;

use Models\User;
use Models\Auth;

class UserController extends Controller {

    const PRIVILEGED = ['administrador', 'superadministrador'];
    
    public function index()
    {
        $model = new User;
        $result = $model->where('tipo', '<>', 'cliente');
        if( !is_super_admin() && is_array($result) )
            $result = array_values(array_filter($result, function($u) { return !in_array($u->tipo, self::PRIVILEGED); }));
        $users = is_array($result) ? array_reverse($result) : [$result];
        view('user/index', compact('users'));
    }
    
    public function create()
    {
        $types = $this->allowedTypes();
        return view('user/create', compact('types'));
    }
    
    public function store()
    {
        $this->validate($this->request->all(), [
            'nombre'    => 'required',
            'tipo'      => 'required',
            'email'     => 'required;email;unique:usuarios',
            'password'  => 'required',
            'confirmar' => 'required;equal:password',
        ]);

        if( !in_array($this->request->get('tipo'), $this->allowedTypes(), true) )
        {
            $this->message(['danger', 'No tienes permiso para asignar ese tipo de usuario']);
            return back();
        }

        $data = [
            'nombre'   => $this->request->get('nombre'),
            'email'    => $this->request->get('email'),
            'password' => Auth::hashToSign( $this->request->get('confirmar') ),
            'tipo'     => $this->request->get('tipo'),
        ];

        $model = new User;
        if( $id = $model->store($data) )
        {
            $this->message(['success', 'Nuevo usuario guardado']);
            return redirect('usuarios');
        }

        $this->message(['danger', 'Error al guardar nuevo usuario']);
        return back();
    }

    public function edit($id = null)
    {
        $model = new User;
        $user = $model->find($id);

        if( !is_object($user) || !$this->canManage($user) )
            return back();

        $types = $this->allowedTypes();
        return view('user/edit', compact('types', 'user'));
    }

    public function update($id = null)
    {
        $model = new User;
        if( !($user = $model->find($id)) || !$this->canManage($user) )
            return back();

        $this->validate($this->request->all(), [
            'nombre' => 'required',
            'tipo'   => 'required',
            'email'  => 'required;unique:usuarios-id,'.$id,
        ]);
        
        if( !$this->request->isEmpty('password') && !$this->request->isEmpty('confirmar') )
            $this->validate($this->request->all(), [
                'confirmar' => 'equal:password'
            ]);
        
        if( !in_array($this->request->get('tipo'), $this->allowedTypes(), true) )
        {
            $this->message(['danger', 'No tienes permiso para asignar ese tipo de usuario']);
            return back();
        }

        $data = [
            'nombre'   => $this->request->get('nombre'),
            'tipo'     => $this->request->get('tipo'),
            'email'    => $this->request->get('email'),
        ];

        if( !$this->request->isEmpty('password') && !$this->request->isEmpty('confirmar') )
            $data['password'] = Auth::hashToSign( $this->request->get('confirmar') );

        $msg = $model->update($data, $user->id)
             ? ['success', 'Usuario actualizado!']
             : ['danger', 'Error al actualizar usuario'];
        $this->message($msg);
        return redirect('usuarios/editar/'.$user->id);
    }

    // El administrador solo gestiona los demas roles; el superadministrador gestiona todos
    private function allowedTypes()
    {
        return array_values(array_filter(config('users'), function($t) {
            return $t !== 'cliente' && (is_super_admin() || !in_array($t, self::PRIVILEGED));
        }));
    }

    private function canManage($user)
    {
        return in_array($user->tipo, $this->allowedTypes(), true);
    }
}
