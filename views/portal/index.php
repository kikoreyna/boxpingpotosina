<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>

<div class="card mb-3">
    <div class="card-header">Mis consolidados <span class="badge badge-primary badge-pill"><?= count($consolidated) ?></span></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm small m-0">
                <thead>
                    <tr><th>NÚMERO</th><th>GUÍAS</th><th>CREADO</th><th>CERRADO</th><th></th></tr>
                </thead>
                <tbody>
                <?php foreach($consolidated as $cs): ?>
                    <tr>
                        <td><?= htmlspecialchars($cs->numero) ?></td>
                        <td><?= $cs->entries_count ?></td>
                        <td><?= $cs->created_at ?></td>
                        <td><?= $cs->cerrado_at ?: 'Abierto' ?></td>
                        <td class="text-right"><a href="<?= url('cliente/consolidados/mostrar/'.$cs->id) ?>" class="btn btn-sm btn-primary">Ver</a></td>
                    </tr>
                <?php endforeach ?>
                <?php if( !$consolidated ): ?>
                    <tr><td colspan="5" class="text-center text-muted">Sin consolidados</td></tr>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <form action="<?= url('cliente') ?>" method="get" class="form-inline">
            <span class="mr-3">Mis guías <span class="badge badge-primary badge-pill"><?= count($entries) ?></span></span>
            <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" class="form-control form-control-sm mr-2" placeholder="Guía, consolidado o rastreo">
            <button class="btn btn-sm btn-secondary">Buscar</button>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm small m-0">
                <thead>
                    <tr><th>GUÍA</th><th>CONSOLIDADO</th><th>TRANSPORTADORA</th><th>RASTREO</th><th>ESTATUS</th><th>CREADA</th><th></th></tr>
                </thead>
                <tbody>
                <?php foreach($entries as $e): ?>
                    <tr>
                        <td><?= htmlspecialchars($e->numero) ?></td>
                        <td><?= htmlspecialchars((string) $e->consolidado_numero) ?></td>
                        <td><?= htmlspecialchars((string) $e->transportadora) ?></td>
                        <td><?= htmlspecialchars((string) $e->rastreo) ?></td>
                        <td><?= htmlspecialchars((string) $e->status) ?></td>
                        <td><?= $e->created_at ?></td>
                        <td class="text-right"><a href="<?= url('cliente/entradas/mostrar/'.$e->id) ?>" class="btn btn-sm btn-primary">Ver</a></td>
                    </tr>
                <?php endforeach ?>
                <?php if( !$entries ): ?>
                    <tr><td colspan="7" class="text-center text-muted">Sin guías</td></tr>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>
