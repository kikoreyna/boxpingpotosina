<?php $template->fill('content') ?>
<div class="card">
    <div class="card-header">
        <p class="m-0">Resultados de busqueda
            <span class="badge badge-pill badge-dark"><?= $data ?></span>
            <span class="badge badge-pill badge-primary"><?= count($entries) ?></span>
        </span>
    </div>

    <?php $template->insert('entry/template_table_entries', compact('entries')) ?>

    <?php if(! $entries ): ?>
    <div class="card-body">
        <p class="lead text-muted m-0">Sin resultados</p>
    </div>

    <?php endif ?>
</div>
<?php $template->insert('modals/history') ?>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>