<?php namespace Controllers;

use System\Core\Controller;
use Models\Client;
use Models\User;
use Models\Auth;
use Models\Consolidated;
use Models\Entry;

class ClientController extends Controller {

    public function index()
    {
        $model = new Client();
        $all = $model->allWithRelations();
        $clients = is_array($all) ? $all : [$all];
        $count = count($clients);
        return view('client/index', compact('clients','count'));
    }
    
    public function create()
    {
        return view('client/create');
    }
 
    public function store()
    {
        $this->validate($this->request->all(), [
            'nombre' => 'required;string;unique:clientes',
            'alias' => 'unique:clientes',
        ]);
        
        $model = new Client;
        if( ! $client_id = $model->store( $this->request->all() ) )
        {
            $this->message(['danger', 'Error al guardar nuevo cliente']);
            return back();
        }

        $msg = ['success','Nuevo cliente guardado'];
        $this->message($msg);
        return redirect('clientes');
        // return redirect('clientes/crear/credencial/'.$client_id);
    }
    
    public function show($id = null)
    {
        $model = new Client;
        if( !$client = $model->withRelations($id)  )
            return redirect('clientes');

        $consolidated_model = new Consolidated;
        $consolidated_count = count( $consolidated_model->where('cliente_id', '=', $client->id) );

        $entry_model = new Entry;
        $entries_count = count( $entry_model->where('cliente_id', '=', $client->id) );

        $session_type = session_get('user','type');
        return view('client/show', compact('client', 'session_type', 'consolidated_count', 'entries_count') );
    }
    
    public function edit($id = null)
    {
        $model = new Client;
        if( !$client = $model->find($id) )
            return redirect('clientes');

        return view('client/edit', ['cliente' => $client]); 
    }
    
    public function update($id = null)
    {
        $this->validate($this->request->all(), [
            'nombre' => 'required;string',
            'alias' => "required;unique:clientes-id,{$id}",
        ]);

        $model = new Client;
        if( !$client = $model->find($id) ) 
            return redirect('clientes');
        
        $msg = $model->update($this->request->all(), $id)
            ? ['success', 'Cliente actualizado']
            : ['danger', 'Error al actualizar cliente'];
        
        // $model_user = new User;
        // $updated_user = $model_user->update(['nombre' => $this->request->get('nombre')], $client->usuario_id);

        $this->message($msg);
        return back();        
    }
    
    public function erase($id = null)
    {
        $model = new Client;
        if( !$client = $model->find($id))
            return redirect('clientes');
        
        return view('client/erase', compact('client')); 
    }
    
    public function deleteSoft($id = null)
    {
        $this->validate($this->request->all(), [
            'nombre' => 'required'
        ]);

        $model = new Client; 
        if( ! $client = $model->find($id) )
            return back();
        
        $result = $model->deleteSoft($id);
        $msg = !is_bool( $result )
            ? ['success', "Cliente {$this->request->get('nombre')} eliminado"]
            : ['danger', "Error al eliminar cliente {$this->request->get('nombre')}"];
        $this->message($msg);
        return redirect('clientes');
    }
    
    public function delete($id = null)
    {
        $this->validate($this->request->all(), [
            'nombre' => 'required'
        ]);

        $model = new Client;
        if( !$client = $model->find($id) )
            return back();

        $result = $model->delete($id);
        $msg = !is_bool($result)
            ? ['success', "Cliente {$this->request->get('nombre')} eliminado"]
            : ['danger', "Error al eliminar cliente {$this->request->get('nombre')}"];
        $this->message($msg);
        return redirect('clientes');
    }


    // ----------------------------------------------------------------------------------------------


    public function createCredential($id = null)
    {
        $model = new Client;
        if( !$client = $model->find($id) )
            return redirect('clientes');
        
        return view('client/create_credential', compact('client'));
    }

    public function storeCredential($id = null)
    {
        $model = new Client;
        if( !$client = $model->find($id) || !is_null($client->usuario_id) )
            return redirect('clientes');

        $this->validate($this->request->all(), [
            'email'     => 'required;email;unique:usuarios',
            'password'  => 'required',
            'confirmar' => 'required;equal:password',
        ]);

        $data = [
            'nombre'   => $client->nombre,
            'email'    => $this->request->get('email'),
            'password' => Auth::hashToSign( $this->request->get('confirmar') ),
            'tipo'     => 'cliente',
        ];

        $model_user = new User;
        if( $user_id = $model_user->store($data) )
        {
            $updated = $model->update(['usuario_id' => $user_id], $client->id);
            $msg = ['success', 'Credencial de usuario guardado'];
            $redirect = 'clientes/mostrar/'.$client->id;
        }
        else
        {
            $msg = ['danger', 'Error al guardar credencial de usuario'];
            $redirect = 'clientes/crear/usuario/'.$client->id;
        }

        $this->message($msg);
        return redirect($redirect);
    }

    public function editCredential($id = null)
    {
        $model = new Client;
        if( ! $client = $model->withRelations($id) )
            return back();
        
        return view('client/edit_credential', compact('client'));
    }

    public function updateCredential($id = null)
    {
        $model = new Client;
        if( ! $client = $model->withRelations($id) )
            return back();
        
        $this->validate($this->request->all(), [
            'email' => 'required;email;unique:usuarios-id,'.$client->usuario_id,
        ]);
        
        $data = [
            'email' => $this->request->get('email'),
        ];

        if( !$this->request->isEmpty('password') || !$this->request->isEmpty('confirmar') )
        {
            $this->validate($this->request->all(), [
                'password'  => 'required',
                'confirmar' => 'required;equal:password',
            ]);

            $data['password'] = Auth::hashToSign( $this->request->get('confirmar') );
        }
        
        $model_user = new User;
        $msg = $model_user->update($data, $client->usuario_id)
            ? ['success', 'Credencial de usuario actualizado']
            : ['danger', 'Error al actualizar la credencial de usuario'];
        $this->message($msg);
        return back();
    }
}