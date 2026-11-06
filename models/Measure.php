<?php namespace Models;

use System\Core\Model;

class Measure extends Model
{
    protected $table = 'entrada_medidas';
    protected $timestamps = true;

    public function withRelations($value, $column = 'id')
    {
        $query = $this->getQueryRelations( " WHERE {$column} = '{$value}'" );
        return $this->raw($query, true);
    }

    private function getQueryRelations( $concat = '' )
    {
        return "SELECT m.*,
                u.nombre AS 'usuario_nombre', u.email AS 'usuario_email',
                e.numero AS 'entrada_numero', e.alias_cliente_numero AS 'entrada_alias_cliente_numero' 
                FROM {$this->table} AS m
                LEFT JOIN usuarios AS u ON m.actualizado_by = u.id
                INNER JOIN entradas AS e ON m.entrada_id = e.id " . $concat;
    }

    public function storeMeasures(array $post, $entry_id, $session)
    {
        $measures_types = config('measures');
        $stages = config('stages');
        $stored = array();
        $post_stage = !isset($post['etapa']) || !in_array($post['etapa'], $stages) 
                    ? $this->getStageBySession( $session['type'] )
                    : $post['etapa'];
        
        foreach($stages as $stage)
        {
            $data = [
                'actualizado_by' => $session['id'],
                'entrada_id'     => $entry_id,
                'etapa'          => $stage,
            ];

            if( $stage === $post_stage )
            {
                $post_measures = [
                    'peso'           => isset($post['peso'])            && is_numeric($post['peso'])                                     ? $post['peso']           : null,
                    'medida_peso'    => !empty($post['medida_peso'])    && in_array($post['medida_peso'], $measures_types['peso'])       ? $post['medida_peso']    : 'libras',
                    'ancho'          => isset($post['ancho'])           && is_numeric($post['ancho'])                                    ? $post['ancho']          : null,
                    'altura'         => isset($post['altura'])          && is_numeric($post['altura'])                                   ? $post['altura']         : null,
                    'profundidad'    => isset($post['profundidad'])     && is_numeric($post['profundidad'])                              ? $post['profundidad']    : null,
                    'medida_volumen' => !empty($post['medida_volumen']) && in_array($post['medida_volumen'], $measures_types['volumen']) ? $post['medida_volumen'] : 'pulgadas',
                ];
                $data = array_merge($data, $post_measures);
            }

            $measure_id = $this->store($data);
            array_push($stored, $measure_id);
        }
        return $stored;
    }

    public function storeMeasuresUpdateStage(array $post, $entry_id, $session)
    {
        $measures_types = config('measures');
        $stages = config('stages');
        $stored = array();
        $data = [
            'actualizado_by' => $session['id'],
            'entrada_id'     => $entry_id,
        ];

        // Store measures
        foreach($stages as $stage)
        {
            $data['etapa'] = $stage;
            $measure_id = $this->store($data);
            array_push($stored, $measure_id);
        }

        if(! isset($post['etapa']) )
            return $stored;

        // Update a specific stage
        $stage_to_patch = !in_array($post['etapa'], $stages) ? $this->getStageBySession( $session['type'] ) : $post['etapa'];
        $stages_entry = $this->where('entrada_id', '=', $entry_id);
        $stages_filtered = array($stages_entry, function($se) use ($stage_to_patch) {
            return $se === $stage_to_patch;
        });

        if( is_array($stages_filtered) )
        {
            $patch_stage = array_pop($stages_filtered);
            $data_patch = [
                'peso'           => isset($post['peso']) && is_numeric($post['peso']) ? $post['peso'] : null,
                'medida_peso'    => !empty($post['medida_peso']) && in_array($post['medida_peso'], $measures_types['peso']) ? $post['medida_peso'] : 'libras',
                'ancho'          => isset($post['ancho']) && is_numeric($post['ancho']) ? $post['ancho'] : null,
                'altura'         => isset($post['altura']) && is_numeric($post['altura']) ? $post['altura'] : null,
                'profundidad'    => isset($post['profundidad']) && is_numeric($post['profundidad']) ? $post['profundidad'] : null,
                'medida_volumen' => !empty($post['medida_volumen']) && in_array($post['medida_volumen'], $measures_types['volumen']) ? $post['medida_volumen'] : 'pulgadas',
                'actualizado_by' => $session['id'],
            ];
            $this->update($data_patch, $patch_stage->id);
            return $patch_stage->id;
        }

        return false;
    }

    private function getStageBySession( $session_type )
    {
        if( $session_type === 'bodega_usa' )
            return 'bodega_usa';

        if( $session_type === 'bodega_mex' )
            return 'bodega_mex_entrada';

        return 'cliente';
    }

    public function getStage($entry_id, $stage_name)
    {
        $query = "SELECT * FROM {$this->table} WHERE entrada_id = {$entry_id} AND etapa = '{$stage_name}' LIMIT 1";
        if( $result = $this->raw($query, true) )
            return array_pop($result);
        
        return false;
    }
}