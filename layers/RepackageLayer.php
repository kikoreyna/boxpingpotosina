<?php namespace Layers;

class RepackageLayer extends \System\Core\Layer
{
    static public function run( $action )
    {
        if(! self::only(['administrador','documentador']) )
            return redirect('signout');
    }
}