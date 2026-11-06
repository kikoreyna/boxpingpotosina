<?php if($entries): ?>
    <div class="table-responsive">
        <table class="table table-hover table-sm small m-0">
            <thead class="text-secondary">
                <tr>
                    <th class="border-0"></th>
                    <th class="border-0"></th>
                    <th class="border-0">NÚMERO</th>
                    <th class="border-0">REMITENTE</th>
                    <th class="border-0">DESTINATARIO</th>
                    <th class="border-0">SALIDA</th>
                    <th class="border-0">STATUS</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($entries as $index => $entry): ?>
                <?php $entrada     = $entry->alias_cliente_numero ? stick($entry->cliente_alias, $entry->numero) : $entry->numero ?>
                <?php $consolidado = $entry->consolidado_alias_cliente_numero ? stick($entry->cliente_alias, $entry->consolidado_numero) : $entry->consolidado_numero ?>

                <tr>
                    <?php $data_entry = [
                        'number_entry'       => $entrada,
                        'consolidated_entry' => !is_null($entry->consolidado_id) ? $consolidado : 'SIN CONSOLIDAR',
                        'show_url' => url('historial/mostrar/'.$entry->id),
                        'edit_url' => url('entradas/editar/'.$entry->id.'#medidas'),
                        'update_observations_url' => url('entradas/actualizar_observaciones/'.$entry->id.'/ajax'),
                    ] ?>
                    <td class="text-muted" style="width:0.1%; white-space:nowrap"><?= $index + 1 ?></td>
                    <td style="width:0.1%; white-space:nowrap">
                        <span data-toggle="tooltip" data-placement="left" title="Historial">
                            <a href="#historial" class="btn btn-info btn-sm" data-toggle="modal" data-target="#historyModal" data-entry='<?= json_encode($data_entry) ?>'>
                                <i class="fas fa-history"></i>
                            </a>
                        </span>
                        <a href="<?= url('entradas/editar/'.$entry->id) ?>" target="_blank" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="right" title="Editar">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                    </td>

                    <?php // Entry guide and consolidated ?>
                    <td class="text-nowrap">
                        <a href="<?= url('entradas/mostrar/'.$entry->id) ?>" class="d-block"><?= $entrada ?></a>
                        
                        <?php if( !is_null($entry->consolidado_id) ): ?>
                        <a class="text-link small" href="<?= url('consolidados/mostrar/'.$entry->consolidado_id) ?>"><?= $consolidado ?></a>

                        <?php else: ?>
                        <span class="text-muted text-uppercase small">Sin consolidar</span>
                        
                        <?php endif ?>
                    </td>

                    <?php // Remitter ?>
                    <td style="min-width:256px">
                        <?php if( !empty($entry->remitente_direccion) || !empty($entry->remitente_postal) ): ?>
                        <span class="d-block"><?= $entry->remitente_direccion ?>, <?= $entry->remitente_postal?></span>
                        <?php endif ?>

                        <?php if( !empty($entry->remitente_ciudad) || !empty($entry->remitente_estado) ): ?>
                        <span class="d-block small"><?= $entry->remitente_ciudad ?>, <?= $entry->remitente_estado ?>, <?= $entry->remitente_pais ?></span>
                        <?php endif ?>

                        <?php if( !empty($entry->remitente_nombre) || !empty($entry->remitente_telefono) ): ?>
                        <span class="d-block small"><?= $entry->remitente_nombre ?> | <?= $entry->remitente_telefono ?></span>
                        <?php endif ?>
                    </td>

                    <?php // Addressee or Ocurre wayout ?>
                    <td style="min-width:256px">
                    <?php if( $entry->salida_cobertura === 'ocurre' ): ?>
                        <small class="text-info">INFORMACION DE OCURRE</small>

                        <?php if( !empty($entry->salida_direccion) || !empty($entry->salida_postal) ): ?>
                        <span class="d-block"><?= $entry->salida_direccion ?>, <?= $entry->salida_postal ?></span>
                        <?php endif ?>

                        <?php if( !empty($entry->salida_ciudad) || !empty($entry->salida_estado) || !empty($entry->salida_pais) ): ?>
                        <span class="d-block small"><?= $entry->salida_ciudad ?>, <?= $entry->salida_estado ?>, <?= $entry->salida_pais ?></span>
                        <?php endif ?>
                        
                    <?php else: ?>  
                        <?php if( !empty($entry->destinatario_direccion) || !empty($entry->destinatario_postal) ): ?>
                        <span class="d-block"><?= $entry->destinatario_direccion ?>, <?= $entry->destinatario_postal ?></span>
                        <?php endif ?>

                        <?php if( !empty($entry->destinatario_ciudad) || !empty($entry->destinatario_estado) ): ?>
                        <span class="d-block small"><?= $entry->destinatario_ciudad ?>, <?= $entry->destinatario_estado ?>, <?= $entry->destinatario_pais ?></span>
                        <?php endif ?>

                        <?php if( !empty($entry->destinatario_nombre) || !empty($entry->destinatario_telefono) ): ?>
                        <span class="d-block small"><?= $entry->destinatario_nombre ?> | <?= $entry->destinatario_telefono ?></span>                                       
                        <?php endif ?>

                    <?php endif ?>                                    
                    </td>

                    <?php // Wayout ?>
                    <td style="min-width:128px">
                    <?php if( is_null($entry->salida_id) ): ?>
                        <?php if( $entry->destinatario_verificacion_at ): ?>
                        <a href="<?= url('salidas/crear/'.$entry->id) ?>" class="badge badge-primary p-2">CREAR GUIA DE SALIDA</a>
                        
                        <?php else: ?>
                        <span class="badge badge-secondary text-uppercase">Verificacion</span>

                        <?php endif ?>
                    <?php else: ?>
                        <span class="d-block">Rastreo: <?= $entry->salida_rastreo ?></span>
                        <span class="d-block">Confirmacion: <?= $entry->salida_confirmacion ?></span>
                        <span class="d-block">Cobertura: <?= $entry->salida_cobertura ?></span>
                        <a href="<?= $entry->transportadora_web ?>" class="text-link d-block" target="_blank"><?= $entry->transportadora_nombre ?></a>
                    
                    <?php endif ?>
                    </td>

                    <?php // Status ?>
                    <td class="text-nowrap">
                        <?php if(! empty($entry->salida_status) ): ?>
                        <span class="d-block"><?= ucfirst($entry->salida_status) ?></span>
                        <?php endif ?>

                        <?php if(! empty($entry->salida_incidente) ): ?>
                        <span class="badge badge-danger badge-pill"><?= ucfirst($entry->salida_incidente) ?></span>
                        <?php endif ?>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>    
<?php endif ?>
