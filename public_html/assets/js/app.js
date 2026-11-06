$(function () {
    $('[data-toggle="tooltip"]').tooltip();
    
    $('#coverage_ocurre').on('click', function () {
        $('#address_ocurre').prop('disabled', false).fadeIn('fast');
    });
    
    $('#coverage_domicilio').on('click', function () {
        $('#address_ocurre').prop('disabled', true).fadeOut('fast');
    });

    $('#frm-new-observation').on('submit', function (event) {
        event.preventDefault()

        $this = $(this)
        $input_observation = $this.find('textarea[name="observacion"]')
        $waiting_observation = $this.find('#waiting-observation')
        $response_observation = $this.find('#response-observation')
        
        $.ajax({
            url: $this.attr('action'),
            method: 'POST',
            data: $this.serialize(),
            dataType: 'JSON',
            beforeSend: function () {
                $input_observation.fadeOut()
            }
        })
        .done( function (response) {
            let saved = JSON.parse(response.saved)
            let li = `<li class="list-group-item list-group-item-action">
                        <span class="d-block">${saved.observacion}</span>
                        <small class="text-secondary">${saved.nombre} | ${saved.creado_at}</small>
                      </li>`
            $('#observations-content').prepend(li)
            $this.trigger('reset')
        })
        .fail( function (response) {
            // response
        })
        .always( function (response) {
            $response_observation.html(response.message);
            $response_observation.fadeIn().delay(1000).fadeOut( function () {
                $input_observation.fadeIn()
            })
        })
    })
})
