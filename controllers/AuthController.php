<?php namespace Controllers;

use Models\Auth;

class AuthController extends \System\Core\Controller {

    public function index($user = '')
    {
        return view('auth/index', compact('user'));
    }

    public function signing()
    {
        $this->validate($this->request->all(), [
            'usuario' => 'required;email',
            'password' => 'required',
        ]);

        $model = new Auth;
        if( $user = $model->find($this->request->get('usuario'), 'email') )
        {
            $password = $this->request->get('password') . Auth::getSalt();
            if( hasherVerify($password, $user->password) )
            {
                session_set('user', [
                    'id'    => $user->id,
                    'name'  => $user->nombre,
                    'email' => $user->email,
                    'type'  => $user->tipo,
                ]);

                $url = $this->home( $user->tipo );
                return redirect($url);
            }
        }
        
        $this->message(['danger', 'Usuario o contraseña incorrectos']);
        return redirect('identificar/'.$this->request->get('usuario'));
    }

    private function home($user_type)
    {
        if( $user_type === 'bodega_usa' || $user_type === 'bodega_mex' )
            return 'bodega/entrada';

        return 'dashboard';
    }

    public function signout()
    {
        session_finish();
        return redirect('identificar');
    }
}