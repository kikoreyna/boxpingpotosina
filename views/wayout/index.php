<?php $template->fill('content') ?>
<div class="form-row">
    <div class="col-sm mb-3">
        <div class="card">
            <div class="card-header">
                <div class="lead">
                    <span class="align-middle">Salidas</span>
                    <span class="badge badge-primary badge-pill align-middle"><?= count($wayouts) ?></span>
                </div>
            </div>
            <?php if($wayouts): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-sm small m-0">
                        <thead>
                            <tr class="text-secondary">
                                <th class="border-0"></th>
                                <th class="border-0"></th>
                                <th class="border-0">RASTREO</th>
                                <th class="border-0">CONFIRMACION</th>
                                <th class="border-0">COBERTURA</th>
                                <th class="border-0">DESTINATARIO</th>
                                <th class="border-0">ENTRADA</th>
                                <th class="border-0">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($wayouts as $index => $wayout): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td class="">
                                    <a href="<?= url('salidas/editar/'.$wayout->id) ?>" class="btn btn-warning btn-sm" target="_blank">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </td>
                                <td>
                                    <span class=""><?= $wayout->rastreo ?></span>
                                </td>
                                <td>
                                    <span class=""><?= $wayout->confirmacion ?></span>  
                                </td>
                                <td>
                                    <span class="text-uppercase"><?= $wayout->cobertura ?></span>                          
                                    <a href="<?= $wayout->transportadora_web ?>" target="_blank" class="d-block small"><?= $wayout->transportadora_nombre ?></a>
                                </td>
                                <td>
                                    <?php if( $wayout->cobertura === 'ocurre' ): ?>
                                    <p class="m-0">
                                        <?= $wayout->direccion ?>, C.P. <?= $wayout->postal ?>
                                        <span class="d-block small"><?= $wayout->ciudad ?>, <?= $wayout->estado ?>, <?= $wayout->pais ?></span>
                                    </p>
                                    <small class="text-info">Direccion de Ocurre</small>

                                    <?php else: ?>
                                    <p class="m-0">
                                        <?= $wayout->destinatario_direccion ?>, C.P. <?= $wayout->destinatario_postal ?>
                                        <span class="d-block small"><?= $wayout->destinatario_ciudad ?>, <?= $wayout->destinatario_estado ?>, <?= $wayout->destinatario_pais ?></span>
                                        <span class="d-block small"><?= $wayout->destinatario_nombre ?> / <?= $wayout->destinatario_telefono ?></span>
                                    </p>

                                    <?php endif ?>
                                </td>
                                <td>
                                    <?php $alias_numero = $wayout->entrada_alias_cliente_numero ? stick($wayout->cliente_alias, $wayout->entrada_numero) : $wayout->entrada_numero ?>
                                    <a href="<?= url('entradas/mostrar/'.$wayout->entrada_id) ?>"><?= $alias_numero ?></a>
                                    
                                    <p class="m-0 small">
                                        <?php 
                                            if( !empty($wayout->consolidado_id) ): 
                                                $alias_numero = $wayout->consolidado_alias_cliente_numero ? stick($wayout->cliente_alias, $wayout->consolidado_numero) : $wayout->entrada_numero;
                                        ?>
                                        <a href="<?= url('consolidados/mostrar/'.$wayout->consolidado_id) ?>"><?= $alias_numero ?></a>
                                    
                                        <?php else: ?>
                                        <span class="text-muted">SIN CONSOLIDAR</span>
                                        
                                        <?php endif ?>
                                    </p>
                                </td>
                                <td>
                                    <?php if(! empty($wayout->status) ): ?>
                                    <p class="m-0"><?= ucfirst($wayout->status) ?></p>
                                    <?php endif ?>

                                    <?php if( isset($wayout->incidente) && $wayout->incidente !== 'ninguna' ): ?>
                                    <span class="badge badge-danger"><?= ucfirst($wayout->incidente) ?></span>
                                    <?php endif ?>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div> 
            <?php endif ?>       
        </div>    
    </div>

    <div class="col-sm col-sm-2">
        <?php $template->insert('wayout/index_filters', compact('inputs','filters')) ?>
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>
