<?php namespace Controllers;

use System\Core\Controller;

use Models\User;
use Models\Auth;

class UserController extends Controller {
    
    public function index()
    {
        $model = new User;
        $result = $model->where('tipo', '<>', 'cliente');
        $users = is_array($result) ? array_reverse($result) : [$result];
        view('user/index', compact('users'));
    }
    
    public function create()
    {
        $types = config('users');
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

        if( !is_object($user) || $user->tipo === 'cliente' )
            return back();

        $types = config('users');
        return view('user/edit', compact('types', 'user'));
    }

    public function update($id = null)
    {
        $model = new User;
        if( !$user = $model->find($id) )
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
        
        $data = [
            'nombre'   => $this->request->get('nombre'),
            'tipo'     => $this->request->get('tipo'),
            'email'    => $this->request->get('email'),
            'password' => Auth::hashToSign( $this->request->get('confirmar') ),
        ];

        $msg = $model->update($data, $user->id)
             ? ['success', 'Usuario actualizado!']
             : ['danger', 'Error al actualizar usuario'];
        $this->message($msg);
        return redirect('usuarios/editar/'.$user->id);
    }
}