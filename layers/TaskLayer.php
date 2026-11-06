<?php namespace Layers;

class TaskLayer extends \System\Core\Layer
{
    static public function run( $action )
    {
        if(! self::only(['administrador','documentador']) )
            return redirect('signout');
    }
}
