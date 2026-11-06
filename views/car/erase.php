<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>
<div class="card">
    <div class="card-header bg-danger text-white">
        <span class="text-uppercase">Advertencia</span>
    </div>
    <div class="card-body">
        <p class="lead text-center m-0">¿Deseas eliminar vehiculo <b class="text-danger"><?= $car->alias ?></b>?</p>
    </div>
    <div class="card-footer text-right">
        <form action="<?= url('vehiculos/eliminar/'.$car->id) ?>" method="post">
            <input type="hidden" name="alias" value="<?= $car->alias ?>">
            <a href="<?= url('vehiculos') ?>" class="btn btn-secondary">Regresar</a>
            <button class="btn btn-outline-danger" type="submit">Si, eliminar vehiculo</button>
        </form>
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>