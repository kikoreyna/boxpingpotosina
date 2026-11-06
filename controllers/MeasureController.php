<?php namespace Controllers;

use System\Core\Controller;
use Models\Measure;
use Models\Entry;

class MeasureController extends Controller {

    public function index(){}

    public function update($entry_id = null)
    {
        $model_entry = new Entry;
        if( ! $entry = $model_entry->find($entry_id) )
            return back();

        $model = new Measure;
        $measures_stored = $model->where('entrada_id', '=', $entry->id);
        $measures_entry = $this->getMeasuresEntryArray($measures_stored);

        $updated_fails = [];
        foreach($this->request->all() as $stage => $measures_post)
        {   
            /**
             * Si la suma de todas las medidas dan 0, no actualizara
             * Ejemplo, si ancho se habia guardado con valor de 12, despues se modifca a vacio, no actualizara ya que la suma de las medidas dan 0
             * Al menos que otra medida como peso, produndida, altura tenga un valor diferente a vacio, entonces si hara la actualizacion.
             * 
             * Se requiere mejorar este proceso de actualizacion. 19/02/2020 - 4:00am
             */
            // if( ! $this->validateToUpdate($measures_entry[ $stage ], $measures_post) ) continue;

            $data = [
                'peso'           => empty($measures_post['peso']) ? null : $measures_post['peso'], // Si el valor es vacio(''), entonces convertir a null
                'medida_peso'    => $measures_post['medida_peso'],
                'ancho'          => empty($measures_post['ancho']) ? null : $measures_post['ancho'],
                'altura'         => empty($measures_post['altura']) ? null : $measures_post['altura'],
                'profundidad'    => empty($measures_post['profundidad']) ? null : $measures_post['profundidad'],
                'medida_volumen' => $measures_post['medida_volumen'],
                'actualizado_by' => session_get('user','id'),
            ];

            if( ! $model->update($data, $measures_post['id']) )
            {
                $stage_fail = str_replace('_', ' ', $measures_post['etapa']);
                array_push($updated_fails, $stage_fail);
            }
        }

        $msg = count($updated_fails) === 0
            ? ['success', 'Medidas actualizadas']
            : ['warning', 'Algunas medidas no se actualizaron: '.implode(', ', $updated_fails)];
        $this->message($msg);
        return back();
    }

    private function getMeasuresEntryArray($measures)
    {
        $array = [];
        foreach($measures as $m)
        {
            $array[ $m->etapa ] = [
                'peso'           => $m->peso,
                'medida_peso'    => $m->medida_peso,
                'ancho'          => $m->ancho,
                'altura'         => $m->altura,
                'profundidad'    => $m->profundidad,
                'medida_volumen' => $m->medida_volumen,
            ];
        }
        return $array;
    }

    private function validateToUpdate($measures_stage, $measures_post)
    {
        $something_change = array_filter($measures_post, function ($value, $key) use ($measures_stage) {
            return isset($measures_stage[$key]) && $measures_stage[$key] <> $value;
        }, ARRAY_FILTER_USE_BOTH);

        return (bool) count($something_change);

        // BEFORE 21/03/2022...
        // if( $measures_post['peso'] + $measures_post['ancho'] + $measures_post['altura'] + $measures_post['profundidad'] == 0 )
        //     return false;
    
        // return $measures_stage['peso']           <> $measures_post['peso']
        //     || $measures_stage['medida_peso']    <> $measures_post['medida_peso']
        //     || $measures_stage['ancho']          <> $measures_post['ancho']
        //     || $measures_stage['altura']         <> $measures_post['altura']
        //     || $measures_stage['profundidad']    <> $measures_post['profundidad']
        //     || $measures_stage['medida_volumen'] <> $measures_post['medida_volumen'];
    }
}
