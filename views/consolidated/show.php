<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>

<!-- Consolidado -->
<div class="card">
    <div class="card-header">
        <div class='float-right'>
            <a href="<?= url('consolidados/editar/'.$consolidated->id) ?>" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="left" title="Editar consolidado">
                <i class="fas fa-pencil-alt"></i>
            </a>
            <a href="<?= url('consolidados/advertencia_eliminar/'.$consolidated->id) ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="left" title="Eliminar consolidado">
                <i class="fas fa-trash"></i>
            </a>
        </div>
        <p class="m-0 lead">Consolidado</p>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm">
                <p class="small mb-1"><b>INFORMACIÓN</b></p>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered small m-0">
                        <tbody>
                            <tr>
                                <td class="bg-light">Número</td>
                                <td><?= $consolidated->numero ?></td>
                                <!-- <td><?= $consolidated->alias_cliente_numero ? stick($consolidated->cliente_alias, $consolidated->numero) : $consolidated->numero ?></td> -->
                            </tr>
                            <tr>
                                <td class="bg-light">Cliente</td>
                                <td><?= $consolidated->cliente_nombre ?> (<?= $consolidated->cliente_alias ?>)</td>
                            </tr>
                            <tr>
                                <td class="bg-light">Notificación</td>
                                <td><?= $consolidated->notificacion_at ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light">Creación</td>
                                <td><?= $consolidated->created_at ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light">Actualización</td>
                                <td><?= $consolidated->updated_at ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>  
            </div>

            <div class="col-sm">
                <p class="small mb-1"><b>CONTENIDO</b></p>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered small m-0">
                        <tbody>
                            <tr>
                                <td class="bg-light">Palets</td>
                                <td><?= $consolidated->palets ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light">Entradas</td>
                                <td><?= $all_entries_consolidated_count ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light">En bodega USA</td>
                                <td><?= $entries_warehouse_usa ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light">En bodega MEX</td>
                                <td><?= $entries_warehouse_mex ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light">Reempacados</td>
                                <td><?= $entries_repackaged ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<br>

<div class="form-row">
    <!-- Entradas -->
    <div class="col-sm">
        <div class="card">
            <div class="card-header">
                <div class='float-right'>
                    <a href="<?= url("consolidados/imprimir/{$consolidated->id}") ?>" class="btn btn-primary btn-sm" target="_blank" data-toggle="tooltip" data-placement="left" title="Imprimir labels">
                        <i class="fas fa-print"></i>
                    </a>
                    <a href="<?= url('entradas/crear/'.$consolidated->id) ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="left" title="Agregar entrada(s)">
                        <i class="fas fa-plus"></i>
                    </a>
                   <!-- test kiko  <a href="<?= url("consolidados/imprimir/{$consolidated->id}") ?>" class="btn btn-primary btn-sm" target="_blank" data-toggle="tooltip" data-placement="left" title="Imprimir filtro">
                        <i class="fas fa-print"></i>
                    </a> -->
                </div>
                <p class="m-0 lead">
                    <span>Entradas</span>
                    <span class="badge badge-info badge-pill"><?= $entries_filtered_count ?></span>
                </p>
            </div>
            <?php $template->insert('entry/template_table_entries', compact('entries')) ?>
        </div>
    </div>
    
    <!-- Filtros -->
    <div class="col-sm col-sm-2">
        <?php $template->insert('consolidated/show_filters', compact('filters','inputs_filter')) ?>
    </div>
</div>

<?php $template->insert('modals/history') ?>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>