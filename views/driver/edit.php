<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>
<div class="card">
    <div class="card-header">
        <p class="m-0 lead">Editar conductor</p>
    </div>
    <div class="card-body">
        <form action="<?= url('conductores/actualizar/'.$driver->id) ?>" method="post">
            <div class="form-group">
                <label for="">Nombre</label>
                <input name="nombre" value="<?= $driver->nombre ?>" type="text" class="form-control" required>
            </div>
            <button class="btn btn-warning mb-3 mb-md-0" type="submit">Actualizar conductor</button>
            <a href="<?= url('cruce') ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>