<?php namespace Models;

use System\Core\Model;

class Repacker extends Model
{
    protected $table = 'reempacadores';
    protected $timestamps = true;

    static public function findByPasscode($planecode)
    {
        $instance = new self;
        $passcode = Auth::hashToCode($planecode);
        return $instance->find($passcode, 'passcode');
    }
}