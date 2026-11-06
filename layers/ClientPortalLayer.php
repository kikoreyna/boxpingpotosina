<?php namespace Layers;

use System\Core\Layer;

class ClientPortalLayer extends Layer
{
    static public function run( $action )
    {
        if( ! self::only(['cliente']) )
            return redirect('signout');
    }
}
