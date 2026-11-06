<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>
<div class="card">
    <div class="card-header">
        <p class="m-0 lead">Editar vehiculo</p>
    </div>
    <div class="card-body">
        <form action="<?= url('vehiculos/actualizar/'.$car->id) ?>" method="post">
            <div class="form-group">
                <label for="">Alias</label>
                <input name="alias" value="<?= $car->alias ?>" type="text" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="">Descripción</label>
                <textarea name="descripcion" class="form-control"><?= $car->descripcion ?></textarea>
            </div>
            <button class="btn btn-warning mb-3 mb-md-0" type="submit">Actualizar vehiculo</button>
            <a href="<?= url('cruce') ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>