<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>
<div class="card">
    <div class="card-header">Editar bodega MEX</div>
    <div class="card-body">
        <form action="<?= url('almacenes/actualizar/'.$depot->id) ?>" method="post">
            <div class="form-group">
                <label>Nombre</label>
                <input name="nombre" type="text" maxlength="60" value="<?= htmlspecialchars($depot->nombre) ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Dirección</label>
                <input name="direccion" type="text" maxlength="160" value="<?= htmlspecialchars((string) $depot->direccion) ?>" class="form-control">
            </div>
            <button class="btn btn-warning" type="submit">Actualizar bodega</button>
            <a href="<?= url('almacenes') ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>
