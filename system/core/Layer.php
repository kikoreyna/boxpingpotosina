<?php namespace System\Core;

use System\Interfaces\iLayer;

abstract class Layer implements iLayer
{
    static public function signed($type = null)
    {
        if( $signed = session_exists('user') )
        {
            if( !is_null($type) )
                $signed = in_array(session_get('user', 'type'), self::expand([$type]), true);
        }
        return $signed;
    }

    // El superadministrador tiene todos los permisos del administrador
    static private function expand(array $types)
    {
        if( in_array('administrador', $types, true) )
            $types[] = 'superadministrador';
        return $types;
    }

    static public function unsigned($type = null)
    {
        return ! self::signed($type);
    }

    static public function only(array $only)
    {
        $type = session_get('user', 'type');
        $matched = array_filter(self::expand($only), function($i) use ($type) {
            return $i === $type;
        });
        return $matched;
    }
}