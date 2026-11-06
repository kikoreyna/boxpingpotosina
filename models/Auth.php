<?php namespace Models;

use System\Core\Model;

class Auth extends Model
{
    static private $salt = 'pk';
    protected $table = 'usuarios';
    protected $timestamps = true;

    static public function hashToSign($value)
    {
        $value_salt = $value . self::$salt;
        return hasher($value_salt);
    }

    static public function hashToCode($value)
    {
        $value_salt = $value . self::$salt;
        return sha1($value_salt);
        // return md5($value_salt);
    }

    static public function getSalt()
    {
        return self::$salt;
    }
}

// potosina = $2y$10$gPYdwELIUR98NtNQKezniuCYklF86Tf6KMA9CbIVvyOnaBrE0GmQ6