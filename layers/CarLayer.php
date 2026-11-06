<?php namespace Layers;

use System\Core\Layer;

class CarLayer extends Layer
{
    static public function run( $action )
    {
        $only = ['administrador','documentador'];
        if( ! self::only($only) )
            return redirect('signout');
    }
}