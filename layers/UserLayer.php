<?php namespace Layers;

class UserLayer extends \System\Core\Layer
{
    static public function run( $action )
    {
        if( ! self::signed('administrador') )
            return redirect('');
    }
}