<?php namespace Models;

use System\Core\Model;

class DropOffStage extends Model
{
    protected $table = 'entradas_drop_off_stage';
    protected $timestamps = true;

    public function cargaDescargaTransportadoraFechaHoraBetween($cargaFrom, $descargaTo)
    {
        $cargaFrom = str_replace('T', ' ', $cargaFrom);
        $descargaTo = str_replace('T', ' ', $descargaTo);
        
        return $this->raw("
            SELECT * FROM {$this->table}
            WHERE fecha_carga >= '{$cargaFrom}' AND fecha_descarga <= '{$descargaTo}'
            ORDER BY id
        ", true);
    }

    public function cargaTransportadoraFechaHoraBetween($from, $to)
    {
        $from = str_replace('T', ' ', $from);
        $to = str_replace('T', ' ', $to);

        return $this->raw("
            SELECT * FROM {$this->table}
            WHERE fecha_carga BETWEEN '{$from}' AND '{$to}'
            ORDER BY id
        ", true);
    }

    public function cargaTransportadoraFechaHoraMax($value)
    {
        $value = str_replace('T', ' ', $value);

        return $this->raw("
            SELECT * FROM {$this->table}
            WHERE fecha_carga <= '{$value}'
            ORDER BY id
        ", true);
    }

    public function cargaTransportadoraFechaHoraMin($value)
    {
        $value = str_replace('T', ' ', $value);

        return $this->raw("
            SELECT * FROM {$this->table}
            WHERE fecha_carga >= '{$value}'
            ORDER BY id
        ", true);
    }

    public function descargaTransportadoraFechaHoraBetween($from, $to)
    {
        $from = str_replace('T', ' ', $from);
        $to = str_replace('T', ' ', $to);

        return $this->raw("
            SELECT * FROM {$this->table}
            WHERE fecha_descarga BETWEEN '{$from}' AND '{$to}'
            ORDER BY id
        ", true);
    }

    public function descargaTransportadoraFechaHoraMax($value)
    {
        $value = str_replace('T', ' ', $value);

        return $this->raw("
            SELECT * FROM {$this->table}
            WHERE fecha_descarga <= '{$value}'
            ORDER BY id
        ", true);
    }

    public function descargaTransportadoraFechaHoraMin($value)
    {
        $value = str_replace('T', ' ', $value);

        return $this->raw("
            SELECT * FROM {$this->table}
            WHERE fecha_descarga >= '{$value}'
            ORDER BY id
        ", true);
    }
}
