<?php namespace Layers;

use System\Core\Layer;

class DepotLayer extends Layer
{
    static public function run( $action )
    {
        if( ! self::only(['administrador']) )
            return redirect('signout');
    }
}
