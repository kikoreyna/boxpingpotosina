<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>

<div class="form-row">
    <div class="col-sm col-sm-4 mb-3">
        <div class="card">
            <div class="card-header">Nueva bodega MEX</div>
            <div class="card-body">
                <form action="<?= url('almacenes/guardar') ?>" method="post">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input name="nombre" type="text" maxlength="60" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Dirección</label>
                        <input name="direccion" type="text" maxlength="160" class="form-control">
                    </div>
                    <button class="btn btn-success" type="submit">Guardar bodega</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm mb-3">
        <div class="card">
            <div class="card-header">Bodegas MEX <span class="badge badge-primary badge-pill"><?= count($depots) ?></span></div>
            <div class="card-body p-0">
                <table class="table table-hover table-sm small m-0">
                    <thead>
                        <tr><th class="pl-3">NOMBRE</th><th>DIRECCIÓN</th><th></th></tr>
                    </thead>
                    <tbody>
                    <?php foreach($depots as $depot): ?>
                        <tr>
                            <td class="pl-3"><?= htmlspecialchars($depot->nombre) ?></td>
                            <td><?= htmlspecialchars((string) $depot->direccion) ?></td>
                            <td class="text-right pr-3">
                                <a href="<?= url('almacenes/editar/'.$depot->id) ?>" class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"></i></a>
                                <form action="<?= url('almacenes/eliminar/'.$depot->id) ?>" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar la bodega?')">
                                    <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    <?php if( !$depots ): ?>
                        <tr><td colspan="3" class="text-center text-muted">Aún no hay bodegas. Agrega una para que bodega MEX pueda seleccionarla.</td></tr>
                    <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>
