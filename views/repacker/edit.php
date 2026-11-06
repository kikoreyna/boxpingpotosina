<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>
<div class="card">
    <div class="card-header">
        <p class="m-0 lead">Editar reempacador</p>
    </div>
    <div class="card-body">
        <form action="<?= url('reempacadores/actualizar/'.$repacker->id) ?>" method="post">
            <div class="form-group">
                <label for="">Nombre</label>
                <input name="nombre" value="<?= $repacker->nombre ?>" type="text" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="">Nueva clave</label>
                <input name="clave" type="password" class="form-control" placeholder="Para mantener misma clave, ignorar este campo">
            </div>
            <button class="btn btn-warning mb-3 mb-md-0" type="submit">Actualizar reempacador</button>
            <a href="<?= url('reempaque') ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>