$( function () {
    $('#historyModal').on('show.bs.modal', function (event) {
        $button = $(event.relatedTarget)
        data_entry = $button.data('entry')
        
        $this = $(this);
        $this.find('#number_entry').text(data_entry.number_entry)
        $this.find('#consolidated_entry').text(data_entry.consolidated_entry)
        $this.find('#button_edit').attr('href', data_entry.edit_url)        
        $this.find('#frm-new-observation').attr('action', data_entry.update_observations_url)        

        $.getJSON(data_entry.show_url)
        .done( function (response) {
            appendMeasuresModal(response.measures, $this.find('#measures-content'));
            appendDropOffStageModal(response.dropOffStage, $this.find('#dropOffStage-content'));
            appendObservationsModal(response.observations, $this.find('#observations-content'));

            $this.find('#loading').fadeOut( function () {
                $this.find('#history-content').fadeIn()
            })
        })
        .fail( function( jqxhr, textStatus, error ) {
            $this.find('#loading').fadeOut( function () {
                $this.find('#error-entry').text('Guida de entrada no existe').fadeIn()
            })
        })
        .always()
    });

    $('#historyModal').on('hidden.bs.modal', function (event) {
        $this = $(this)
        $this.find('#dropOffStage-content').find('#dropOffStageCarga').text('')
        $this.find('#dropOffStage-content').find('#dropOffStageDescarga').text('')
        $this.find('#measures-content').empty()
        $this.find('#observations-content').empty()
        $this.find('#error-entry').text('')
        $this.find('#history-content').hide()
        $this.find('#loading').fadeIn()
    })
})

function appendMeasuresModal(measures, $measures_content)
{
    let content = '';
    $.each(measures, function (pos, m) {
        tr = `<tr>
                <td class='bg-light text-capitalize'>${m.etapa.replace('_', ' ')}</td>
                <td class='text-capitalize'>${m.peso ? m.peso : ''} ${m.medida_peso ? m.medida_peso : ''}</td>
                <td class='text-capitalize'>${m.ancho ? m.ancho : ''} * ${m.altura ? m.altura : ''} * ${m.profundidad ? m.profundidad : ''} ${m.medida_volumen ? m.medida_volumen : ''}</td>
                <td class='text-capitalize'>${m.updated_at}</td>
                <td class=''>${m.usuario_nombre}</td>
              </tr>`
        content += tr
    })
    return $measures_content.append(content);
}

function appendDropOffStageModal($dropOffStage, $dropOffStage_template)
{
    console.log($dropOffStage_template.find('#dropOffStageFechaCarga'))
    if( $dropOffStage === false ) {
        $dropOffStage = {}
    }

    if( $dropOffStage.fecha_carga != null ) {
        $dropOffStage_template.find('#dropOffStageCarga').text(`${$dropOffStage.fecha_carga} por ${$dropOffStage.nombre_creador}`)
    } else {
        $dropOffStage_template.find('#dropOffStageCarga').text('Sin registro')
    }

    if( $dropOffStage.fecha_descarga != null ) {
        $dropOffStage_template.find('#dropOffStageDescarga').text(`${$dropOffStage.fecha_descarga} por ${$dropOffStage.nombre_actualizador}`)
    } else {
        $dropOffStage_template.find('#dropOffStageDescarga').text('Sin registro')
    }

    // console.log( $dropOffStage)
}

function appendObservationsModal(observations, $observations_content)
{
    let content = '';
    $.each(observations, function (pos, o) {
        li = `<li class="list-group-item list-group-item-action">
                <span class="d-block">${o.observacion}</span>
                <small class="text-secondary">${o.nombre} | ${o.creado_at}</small>
              </li>`
        content += li
    })
    return $observations_content.append(content);
}