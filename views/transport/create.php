<?php $template->fill('content') ?>
<?php $template->insert('partials/validation') ?>
<div class="card">
    <div class="card-header">
        <p class="m-0 lead">Nueva transportadora</p>
    </div>
    <div class="card-body">
        <form action="<?= url('transportadoras/guardar') ?>" method='post'>
            <div class="form-group">
                <label for="">Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="">Web</label>
                <input type="text" name="web" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Teléfono(s)</label>
                <input type="text" name="telefonos" class="form-control">
            </div>
            <button class="btn btn-success" type="submit">Guardar transportadora</button>
            <a href="<?= url('transportadoras') ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>