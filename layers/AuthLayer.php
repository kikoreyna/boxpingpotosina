<?php namespace Layers;

use \System\Core\Layer;

class AuthLayer extends Layer
{
    static public function run( $action )
    {
        switch( $action )
        {
            case 'index':
            case 'signing':
                if( self::signed() )
                    return redirect('signout');
                break;
        }
    }
}