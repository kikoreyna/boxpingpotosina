<?php $template->fill('content') ?>
<?php $template->insert('partials/validation') ?>
<div class="card">
    <div class="card-header">
        <p class="m-0 lead">Nuevo vehiculo</p>
    </div>
    <div class="card-body">
        <form action="<?= url('vehiculos/guardar') ?>" method="post">
            <div class="form-group">
                <label for="">Alias</label>
                <input name="alias" type="text" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="">Descripción</label>
                <textarea name="descripcion" id="" class="form-control"></textarea>
            </div>
            <button class="btn btn-success mb-3 mb-md-0" type="submit">Guardar vehiculo</button>
            <a href="<?= url('requerimientos') ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>