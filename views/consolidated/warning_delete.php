<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>

<div class="jumbotron text-center">
    <h1 class="display-4 text-danger">Advertencia</h1>
    <p class="lead">Deseas continuar para eliminar el consolidado <b><?= $consolidated->numero ?></b>?</p>
    <hr class="my-4">
    <p>Se eliminara permanentemente entradas que pertenecen a este consolidado, asi como medidas, observaciones, salida de cada entrada.</p>
    <form action="<?= url('consolidados/eliminar') ?>" method="post">
        <input type="hidden" name="consolidado" value="<?= $consolidated->id ?>">
        <input type="hidden" name="numero" value="<?= $consolidated->numero ?>">
        <a class="btn btn-primary btn-lg mb-3 mb-md-0" href="<?= url('consolidados/mostrar/'.$consolidated->id) ?>" role="button">No, regresar</a>
        <button class="btn btn-outline-danger btn-lg" type="submit">Si, eliminar consolidado</button>
    </form>
</div>

<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>