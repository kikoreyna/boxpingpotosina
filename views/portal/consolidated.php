<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>

<p><a href="<?= url('cliente') ?>" class="small">&larr; Volver</a></p>

<div class="card mb-3">
    <div class="card-header">Consolidado <?= htmlspecialchars($consolidated->numero) ?></div>
    <div class="card-body small">
        <div class="row">
            <div class="col-sm"><span class="text-muted d-block">Creado</span><?= $consolidated->created_at ?></div>
            <div class="col-sm"><span class="text-muted d-block">Cerrado</span><?= $consolidated->cerrado_at ?: 'Abierto' ?></div>
            <div class="col-sm"><span class="text-muted d-block">Guías</span><?= count($entries) ?></div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">Guías</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm small m-0">
                <thead>
                    <tr><th>GUÍA</th><th>TRANSPORTADORA</th><th>RASTREO</th><th>ESTATUS</th><th>CREADA</th><th></th></tr>
                </thead>
                <tbody>
                <?php foreach($entries as $e): ?>
                    <tr>
                        <td><?= htmlspecialchars($e->numero) ?></td>
                        <td><?= htmlspecialchars((string) $e->transportadora) ?></td>
                        <td><?= htmlspecialchars((string) $e->rastreo) ?></td>
                        <td><?= htmlspecialchars((string) $e->status) ?></td>
                        <td><?= $e->created_at ?></td>
                        <td class="text-right"><a href="<?= url('cliente/entradas/mostrar/'.$e->id) ?>" class="btn btn-sm btn-primary">Ver</a></td>
                    </tr>
                <?php endforeach ?>
                <?php if( !$entries ): ?>
                    <tr><td colspan="6" class="text-center text-muted">Sin guías</td></tr>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>
