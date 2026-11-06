<?php namespace Layers;

class EntryLayer extends \System\Core\Layer
{
    static public function run( $action )
    {
        if(! self::only(['administrador','documentador']) )
            return redirect('signout');
    }
}