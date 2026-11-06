<?php namespace Layers;

class DashboardLayer extends \System\Core\Layer
{
    static public function run( $action )
    {
        if( self::only(['bodega_usa','bodega_mex']) )
            return redirect('bodega/entrada');

        if(! self::only(['administrador','documentador']) )
            return redirect('signout');
    }
}