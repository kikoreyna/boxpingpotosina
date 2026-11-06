<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>
<div class="card">
    <div class="card-header bg-danger text-white">
        <span class="text-uppercase">Advertencia</span>
    </div>
    <div class="card-body">
        <p class="lead text-center m-0">¿Deseas eliminar código de reempacado <b class="text-danger">código <?= $coder->codigo ?></b>?</p>
    </div>
    <div class="card-footer text-right">
        <form action="<?= url('codigos_reempacado/eliminar/'.$coder->id) ?>" method="post">
            <input type="hidden" name="codigo" value="<?= $coder->codigo ?>">
            <a href="<?= url('reempaque') ?>" class="btn btn-secondary">Regresar</a>
            <button class="btn btn-outline-danger" type="submit">Si, eliminar codigo de reempacado</button>
        </form>
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>