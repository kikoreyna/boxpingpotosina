<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>

<div class="card">
    <div class="card-header">
        <p class="m-0 lead">Editar transportadora</p>
    </div>
    <div class="card-body">
        <form action="<?= url('transportadoras/actualizar/'.$transport->id) ?>" method='post'>
            <div class="form-group">
                <label for="">Nombre</label>
                <input type="text" name="nombre" value="<?= $transport->nombre ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="">Web</label>
                <input type="text" name="web" value="<?= $transport->web ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Teléfono</label>
                <input type="text" name="telefonos" value="<?= $transport->telefonos ?>" class="form-control">
            </div>
            <button class="btn btn-warning" type="submit">Actualizar transportadora</button>
            <a href="<?= url('transportadoras') ?>" class="btn btn-secondary">Regresar</a>
        </form>
    </div>
</div>

<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>