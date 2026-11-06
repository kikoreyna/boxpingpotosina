<?php namespace Layers;

class RepackagedLayer extends \System\Core\Layer
{
    static public function run( $action )
    {
        if( self::signed() )
            return redirect('signout');
    }
}