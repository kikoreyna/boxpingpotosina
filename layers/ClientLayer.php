<?php namespace Layers;

use System\Core\Layer;

class ClientLayer extends Layer
{
    static public function run( $action )
    {
        $only = ['administrador','documentador'];
        if( ! self::only($only) )
            return redirect('signout');
        
        if( $action === 'editCredential' && !self::signed('administrador') )
            return redirect('dashboard');
    }
}