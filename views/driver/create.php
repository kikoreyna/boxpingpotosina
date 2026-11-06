<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>
<div class="card">
    <div class="card-header">
        <p class="m-0 lead">Nuevo conductor</p>
    </div>
    <div class="card-body">
        <form action="<?= url('conductores/guardar/') ?>" method="post">
            <div class="form-group">
                <label for="">Nombre</label>
                <input name="nombre" value="" type="text" class="form-control" required>
            </div>
            <button class="btn btn-success mb-3 mb-md-0" type="submit">Guardar conductor</button>
            <a href="<?= url('cruce') ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>