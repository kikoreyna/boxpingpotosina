<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>
<div class="form-row">
    <div class="col-sm mb-3">
        <div class="card">
            <div class="card-header">
                <div class='float-right'>
                    <a href="<?= url('entradas/crear') ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="left" title="Nueva entrada(s)">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
                <p class="m-0 lead">
                    <span>Entradas</span>
                    <span class="badge badge-primary badge-pill align-middle"><?= $entries_count ?> total</span>
                    <span class="badge badge-info badge-pill align-middle"><?= $entries_result_count ?> <?= $entries_result_count >= $filters['limit'] ? '+' : '' ?></span>
                </p>
            </div>
            <?php $template->insert('entry/template_table_entries', compact('entries')) ?>   
        </div>
    </div>

   <div class="col-sm col-sm-2">
        <?php $template->insert('entry/index_filters', compact('inputs', 'filters')) ?>
    </div> 
</div>
<?php $template->insert('modals/history') ?>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>