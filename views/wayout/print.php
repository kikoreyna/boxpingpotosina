<?php $template->fill('content') ?>
<div id="print-area" class="">
    <table class="table">
        <tbody>
            <tr>
                <td><?= $wayout->destinatario_nombre ?></td>
            </tr>
        <?php if( $wayout->cobertura == 'domicilio' ): ?>
            <tr>
                <td><?= $wayout->destinatario_direccion ?></td>
            </tr>
            <tr>
                <td>C.P. <?= $wayout->destinatario_postal ?></td>
            </tr>
            <tr>
                <td><em><?= $wayout->destinatario_referencias ?></em></td>
            </tr>
            <tr>
                <td><?= $wayout->destinatario_ciudad ?>, <?= $wayout->destinatario_estado ?></td>
            </tr>
            <tr>
                <td>Tel. <?= $wayout->destinatario_telefono ?></td>
            </tr>

        <?php else: ?>
            <tr>
                <td><?= $wayout->direccion ?>, <?= $wayout->postal ?></td>
            </tr>
                <td><?= $wayout->ciudad ?>, <?= $wayout->estado ?></td>
            </tr>

        <?php endif ?>

            <tr>
                <td class="font-bold"><?= ucfirst($wayout->cobertura) ?> / <?= $measure->peso ?> Lb / <?= $measure->peso_kg ?> Kg</td>
            </tr>
        </tbody>
    </table>
    <p class="font-bold"><?= $wayout->cliente_alias.$wayout->entrada_numero ?></p>
</div>
<?php $template->stop() ?>
<?php $template->expand('printables/estafeta') ?>