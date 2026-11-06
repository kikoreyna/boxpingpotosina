<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>

<div class="jumbotron text-center">
    <h1 class="display-4 text-danger">Advertencia</h1>
    <p class="lead">Deseas continuar para eliminar la guia de entrada <b><?= $number ?></b>?</p>
    <hr class="my-4">
    <p>Se eliminara permanentemente entrada, proceso, trayectoria, medidas, observaciones y salida...</p>
    <form action="<?= url('entradas/eliminar') ?>" method="post">
        <input type="hidden" name="entrada" value="<?= $id ?>">
        <input type="hidden" name="numero" value="<?= $number ?>">
        <a class="btn btn-primary btn-lg mb-3 mb-md-0" href="<?= url('entradas/mostrar/'.$id) ?>" role="button">No, regresar</a>
        <button class="btn btn-outline-danger btn-lg" type="submit">Si, eliminar entrada</button>
    </form>
</div>

<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>