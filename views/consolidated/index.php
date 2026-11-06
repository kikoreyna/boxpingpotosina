<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>

<div class="form-row">
    <div class="col-sm mb-3">
        <div class="card">
            <div class="card-header">
                <div class='float-right'>
                    <a href="<?= url('consolidados/crear') ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="left" title="Nuevo consolidado">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
                <p class="lead m-0">
                    <span>Consolidados</span>
                    <span class="badge badge-primary badge-pill"><?= count($consolidated) ?></span>
                </p>
            </div>
            
            <?php if( $consolidated ): ?>
                <div class="table-responsive">
                    <table class="table table-sm table-hover m-0">
                        <thead class="text-secondary small">
                            <tr class="">
                                <th class="border-0 pl-3">NÚMERO</th>
                                <th class="border-0">CLIENTE</th>
                                <th class="border-0">PALETS</th>
                                <th class="border-0">ENTRADAS</th>
                                <th class="border-0">EN BODEGA USA</th>
                                <th class="border-0">EN BODEGA MEX</th>
                                <th class="border-0">REEMPACADOS</th>
                                <th class="border-0">PENDIENTES</th>
                                <th class="border-0 d-none">SALIDA</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($consolidated as $cs): ?>
                            <tr>
                                <td class="pl-3">
                                    <?php $alias_numero = $cs->cliente_alias ? stick($cs->cliente_alias, $cs->numero) : $cs->numero ?>
                                    <a href="<?= url('consolidados/mostrar/'.$cs->id) ?>"><?= $alias_numero ?></a>
                                </td>
                                <td>
                                    <a href="<?= url('clientes/mostrar/'.$cs->cliente_id) ?>"><?= $cs->cliente_nombre ?></a>
                                </td>
                                <td><?= $cs->palets ?></td>
                                <td><?= $cs->entries_count ?></td>
                                <td><?= $cs->entries_warehouse_usa ?></td>
                                <td><?= $cs->entries_warehouse_mex ?></td>
                                <td><?= $cs->entries_repackaged ?></td>
                                <td><?= $cs->pendientes ?></td>
                                <td class="d-none"><?= $cs->salida ?? '' ?></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php endif ?>
        </div>
    </div>

    <div class="col-sm col-sm-2">
        <?php $template->insert('consolidated/index_filters', compact('inputs', 'filters')) ?>
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>