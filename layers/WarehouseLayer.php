<?php namespace Layers;

class WarehouseLayer extends \System\Core\Layer
{
    static public function run( $action )
    {
        if(! self::only(['bodega_usa','bodega_mex']) )
            return redirect('signout');
    }
}