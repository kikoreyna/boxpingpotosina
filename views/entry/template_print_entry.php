<table class="table">
    <tbody>
        <tr class="">
            <td class="font-bold"><?= !is_null($entry->transportadora_nombre) ? $entry->transportadora_nombre : 'PXD' ?></td>
            <td class="font-bold"><?= $entry->alias_cliente_numero ? stick($entry->cliente_alias, $entry->numero) : $entry->numero ?></td>
        </tr>
        <tr>
            <td class="bg-light">Destinatario</td>
            <td class=""><?= $entry->destinatario_nombre ?></td>
        </tr>
        <tr>
            <td class="bg-light">Teléfono</td>
            <td><?= $entry->destinatario_telefono ?></td>
        </tr>
        <?php if( $entry->salida_cobertura === 'domicilio' ): ?>
        <tr>
            <td class="bg-light">Domicilio</td>
            <td><?= $entry->destinatario_direccion ?></td>
        </tr>
        <tr>
            <td class="bg-light">Referencias</td>
            <td><?= $entry->destinatario_referencias ?></td>
        </tr>
        <tr>
            <td class="bg-light">Ciudad</td>
            <td><?= $entry->destinatario_ciudad ?></td>
        </tr>
        <tr>
            <td class="bg-light">Estado</td>
            <td><?= $entry->destinatario_estado ?></td>
        </tr>
        <tr>
            <td class="bg-light">Postal</td>
            <td><?= $entry->destinatario_postal ?></td>
        </tr>

        <?php else: ?>
        <tr>
            <td colspan="2" class="bg-light" style="text-align:center">Información de ocurre</td>
        </tr>
        <tr>
            <td class="bg-light">Dirección</td>
            <td><?= $entry->salida_direccion ?></td>
        </tr>
        <tr>
            <td class="bg-light">Postal</td>
            <td><?= $entry->salida_postal ?></td>
        </tr>
        <tr>
            <td class="bg-light">Ciudad</td>
            <td><?= $entry->salida_ciudad ?></td>
        </tr>
        <tr>
            <td class="bg-light">Estado</td>
            <td><?= $entry->salida_estado ?></td>
        </tr>
        <tr>
            <td class="bg-light">Pais</td>
            <td><?= $entry->salida_pais ?></td>
        </tr>

        <?php endif ?>
        <tr>
            <td class="bg-light">Peso</td>
            <td><?= $entry->medidas->peso ?> <?= $entry->medidas->medida_peso ?> / <?= $entry->medidas->libras_a_kilos ?> kg</td>
        </tr>
        <tr>
            <td class="bg-light">Volúmen</td>
            <td><?= $entry->medidas->ancho ?> * <?= $entry->medidas->altura ?> * <?= $entry->medidas->profundidad ?> <?= $entry->medidas->medida_volumen ?></td>
        </tr>
        <tr>
            <td class="bg-light">Medidas de</td>
            <td>Cliente</td>
        </tr>
        <tr>
            <td class="bg-light">Cobertura</td>
            <td class="">
             <?php if(! empty($entry->salida_cobertura) ): ?>
            <?= ucfirst($entry->salida_cobertura)  ?></td>
            <?php endif ?>
        </tr>
    </tbody>
</table>
<p class="barcode barcode39 text-center"><?= "*".$entry->numero."*" ?></p>

