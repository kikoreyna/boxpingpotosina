<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<div class="dropdown text-right mb-3">
    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-print align-middle"></i>
        <span class="pl-2 lign-middle">Imprimir</span>
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="<?= url("entradas/imprimir/{$entry->id}") ?>" target="_blank">Etiqueta de entrada</a>
        <?php if( is_object($wayout) ): ?>
        <a class="dropdown-item" href="<?= url("salidas/imprimir/{$wayout->id}") ?>" target="_blank">Guia de salida</a>
        <?php endif ?>
    </div>
</div>

<?php if( $open_tasks ): ?>
<div class="alert alert-warning small py-2">
    <i class="fas fa-tasks"></i> Tareas pendientes de revisión:
    <ul class="mb-0">
        <?php foreach($open_tasks as $open_task): ?>
        <li><strong><?= $open_task->usuario_nombre ?></strong> — <?= htmlspecialchars($open_task->descripcion) ?></li>
        <?php endforeach ?>
    </ul>
</div>
<?php endif ?>
<?php if( $user_type === 'administrador' && $documenters ): ?>
<form action="<?= url('tareas/guardar') ?>" method="post" class="form-inline mb-3">
    <input type="hidden" name="entrada" value="<?= $entry->numero ?>">
    <select name="documentador" class="form-control form-control-sm mr-2" required>
        <option value="" disabled selected>Asignar a documentador...</option>
        <?php foreach($documenters as $documenter): ?>
        <option value="<?= $documenter->id ?>"><?= $documenter->nombre ?></option>
        <?php endforeach ?>
    </select>
    <input type="text" name="descripcion" maxlength="255" class="form-control form-control-sm mr-2" placeholder="Necesidad / tarea" required>
    <button type="submit" class="btn btn-success btn-sm">Asignar tarea</button>
</form>
<?php endif ?>

<div class="row">

    <!-- ENTRADA -->
    <div class="col-sm col-sm-4 mb-3">
        <div class="card">
            <div class="card-header">
                <div class="float-right">
                    <a href="<?= url('entradas/editar/'.$entry->id) ?>" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="left" title="Editar entrada">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                </div>
                <span class="lead">Entrada</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm small m-0">
                        <tbody>

                            <!-- INFORMACION -->
                            <tr>
                                <th class="bg-light" colspan="2">Información</th>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Número</td>
                                <td class="d-none"><?= $entry->alias_cliente_numero ? stick($entry->cliente_alias, $entry->numero) : $entry->numero ?></td>
                                <td><?= $entry->numero ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Cliente</td>
                                <td>
                                    <?php if( $entry->cliente_id ): ?>
                                    <span><?= $entry->cliente_nombre ?> (<?= $entry->cliente_alias ?>)</span>

                                    <?php else: ?>
                                    <span class="">Ninguno</span>

                                    <?php endif ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Consolidado</td>
                                <td>
                                    <?php if( $entry->consolidado_id ): ?>
                                    <a href="<?= url('consolidados/mostrar/'.$entry->consolidado_id) ?>"><?= $entry->consolidado_numero ?></a>
                                    
                                    <?php else: ?>
                                    <span class="">Ninguno</span>

                                    <?php endif ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Creado por</td>
                                <td><?= $entry->creado_por_nombre ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Fecha de creado</td>
                                <td><?= $entry->created_at ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Actualizado por</td>
                                <td><?= $entry->actualizado_por_nombre ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Fecha de actualizado</td>
                                <td><?= $entry->updated_at ?></td>
                            </tr>

                            <!-- PROCESO -->
                            <tr>
                                <th class="bg-light" colspan="2">Proceso</th>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Recibido</td>
                                <td>
                                    <?php if( $entry->recibido_at ): ?>
                                    <span><?= $entry->recibido_at ?></span>
                                    <?php endif ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">En bodega USA por</td>
                                <td>
                                    <?php if( $entry->en_bodega_usa_by ): ?>
                                    <span><?= $entry->usuario_bodega_usa_nombre ?></span>
                                    <?php endif ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Conductor</td>
                                <td>
                                    <?php if( $entry->conductor_id ): ?>
                                    <span><?= $entry->conductor_nombre ?></span>
                                    <?php endif ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Vehiculo</td>
                                <td>
                                    <?php if( $entry->vehiculo_id ): ?>
                                    <span><?= $entry->vehiculo_alias ?></span>
                                    <?php endif ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Número de vuelta</td>
                                <td>
                                    <?php if( $entry->numero_vuelta ): ?>
                                    <span><?= $entry->numero_vuelta ?></span>
                                    <?php endif ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Fecha de cruce</td>
                                <td>
                                    <?php if( $entry->cruce_at ): ?>
                                    <span><?= $entry->cruce_at ?></span>
                                    <?php endif ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">En bodega MEX por</td>
                                <td>
                                    <?php if( $entry->en_bodega_mex_by ): ?>
                                    <span><?= $entry->usuario_bodega_mex_nombre ?></span>
                                    <?php endif ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Codigo de reempacado</td>
                                <td>
                                    <?php if( $entry->codigor_id ): ?>
                                    <span><?= $entry->reempacado_codigo ?></span>
                                    <?php endif ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Reempacado por</td>
                                <td>
                                    <?php if( $entry->reempacador_id ): ?>
                                    <span><?= $entry->reempacador_nombre ?></span>
                                    <?php endif ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Fecha de reempacado</td>
                                <td>
                                    <?php if( $entry->reempacado_at ): ?>
                                    <span><?= $entry->reempacado_at ?></span>
                                    <?php endif ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- TRAYECTORIA -->
    <div class="col-sm col-sm-4 mb-3">
        <div class="card">
            <div class="card-header">
                <span class="lead">Trayectoria</span>
            </div>
            <div class="card-body">
                
                <div class="table-responsive">
                    <table class="table table-bordered table-sm small m-0">
                        <tbody>

                            <!-- Remitente -->
                            <tr>
                                <th class="bg-light" colspan="2">Remitente</th>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Nombre</td>
                                <td><?= $entry->remitente_nombre ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Direccion</td>
                                <td><?= $entry->remitente_direccion ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Postal</td>
                                <td><?= $entry->remitente_postal ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Localidad</td>
                                <td><?= $entry->remitente_ciudad ?>, <?= $entry->remitente_estado ?>, <?= $entry->remitente_pais ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Teléfono</td>
                                <td><?= $entry->remitente_telefono ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Actualizado por</td>
                                <td><?= $entry->remitente_actualizado_nombre ?></td>
                            </tr>

                            <!-- Destinatario -->
                            <tr>
                                <th class="bg-light" colspan="2">Destinatario</th>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Nombre</td>
                                <td><?= $entry->destinatario_nombre ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Direccion</td>
                                <td><?= $entry->destinatario_direccion ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Postal</td>
                                <td><?= $entry->destinatario_postal ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Referencias</td>
                                <td><?= $entry->destinatario_referencias ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Localidad</td>
                                <td><?= $entry->destinatario_ciudad ?>, <?= $entry->destinatario_estado ?>, <?= $entry->destinatario_pais ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Teléfono</td>
                                <td><?= $entry->destinatario_telefono ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Actualizado por</td>
                                <td><?= $entry->destinatario_actualizado_nombre ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Fecha de verificado</td>
                                <td>
                                    <?php if( $entry->destinatario_verificacion_at ): ?>
                                    <span class=""><?= $entry->destinatario_verificacion_at ?></span>
                                    
                                    <?php endif ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- SALIDA -->
    <div class="col-sm col-sm-4 mb-3">
        <div class="card">
            <div class="card-header">
                <?php if( is_object($wayout) ): ?>
                <div class="float-right">
                    <a href="<?= url('salidas/editar/'.$wayout->id) ?>" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="left" title="Editar salida">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                </div>
                <?php endif ?>
                <span class="lead">Salida</span>
            </div>
            <div class="card-body">
            <?php if( is_object($wayout) ): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm small m-0">
                        <tbody>
                            <tr>
                                <th class="bg-light" colspan="2">Información</th>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Rastreo</td>
                                <td><?= $wayout->rastreo ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Confirmacion</td>
                                <td><?= $wayout->confirmacion ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Transportadora</td>
                                <td>
                                    <?php if( !empty($wayout->transportadora_web) ): ?>
                                    <a href="<?= $wayout->transportadora_web ?>" target="_blank"><?= $wayout->transportadora_nombre ?></a>

                                    <?php else: ?>
                                    <?= $wayout->transportadora_nombre ?>

                                    <?php endif ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Cobertura</td>
                                <td class="text-capitalize"><?= $wayout->cobertura ?></td>
                            </tr>
                            <?php if($wayout->cobertura === 'ocurre'): ?>
                            <tr>
                                <td class="bg-light text-secondary">Direccion de ocurre</td>
                                <td>
                                    <span class="d-block"><?= $wayout->direccion ?>, <?= $wayout->postal ?></span>
                                    <span class="d-block"><?= $wayout->ciudad ?>, <?= $wayout->estado ?>, <?= $wayout->pais ?></span>
                                </td>
                            </tr>
                            <?php endif ?>
                            <tr>
                                <td class="bg-light text-secondary">Status</td>
                                
                                <?php if(! empty($wayout->status) ): ?>
                                <td><?= ucfirst($wayout->status) ?></td>
                                <?php endif ?>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Incidente</td>
                                <td class="text-danger">
                                    <?php if(! empty($wayout->incidente) ): ?>
                                    <?= ucfirst($wayout->incidente) ?>
                                    <?php endif ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Notas</td>
                                <td><?= $wayout->notas ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Actualizado por</td>
                                <td><?= $wayout->usuario_nombre ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light text-secondary">Fecha de actualizado</td>
                                <td><?= $wayout->updated_at ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            <?php elseif( $entry->destinatario_verificacion_at ): ?>
                <p class="text-center">
                    <a href="<?= url('salidas/crear/'.$entry->id) ?>" class="btn btn-primary">
                        <span class="small">CREAR GUIA DE SALIDA</span>
                    </a>
                </p>

            <?php else: ?>
                <p class="text-center">Para crear la guia de salida, se requiere <b class="d-block">La verificación del destinatario</b></p>

            <?php endif ?>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">Carga y Descarga a Transportadora</div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm">
                <small class="d-block text-muted">Cargado</small>
                <?php if( isset($dropOffStage->fecha_carga ) ): ?>
                <?= $dropOffStage->fecha_carga ?> por <?= $dropOffStage->nombre_creador ?>
                <?php else: ?>
                Sin registro
                <?php endif ?>
            </div>
            <div class="col-sm">
                <small class="d-block text-muted">Descargado</small>
                <?php if( isset($dropOffStage->fecha_descarga) ): ?>
                <?= $dropOffStage->fecha_descarga ?> por <?= $dropOffStage->nombre_actualizador ?>
                <?php else: ?>
                Sin registro
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<!-- MEDIDAS -->
<div class="card mb-3">
    <div class="card-header">
        <span class="lead">Medidas</span>
    </div>
    <div class="card-body">
        <div class="table-responsive pb-2">
            <table class="table table-bordered table-hover table-sm small m-0">
                <tbody>
                    <tr>
                        <td class="text-nowrap">Etapas</td>
                        <td class="text-nowrap">Peso</td>
                        <td class="text-nowrap">Medida peso</td>
                        <td class="text-nowrap">Ancho</td>
                        <td class="text-nowrap">Altura</td>
                        <td class="text-nowrap">Profundidad</td>
                        <td class="text-nowrap">Medida volúmen</td>
                        <td class="text-nowrap">Actualizacion</td>
                        <td class="text-nowrap">Usuario</td>
                    </tr>
                    <?php foreach($stages as $stage): $matched = false ?>
                    <tr>
                        <td class="bg-light text-secondary text-capitalize text-nowrap"><?= str_replace('_', ' ', $stage) ?></td>

                        <?php foreach($measures as $measure): if($stage === $measure->etapa): $matched = true ?>
                        <td><?= $measure->peso <> 0.000 ? $measure->peso : '' ?></td>
                        <td class="text-nowrap"><?= $measure->medida_peso ?></td>
                        <td><?= $measure->ancho <> 0.000 ? $measure->ancho : '' ?></td>
                        <td><?= $measure->altura <> 0.000 ? $measure->altura : '' ?></td>
                        <td><?= $measure->profundidad <> 0.000 ? $measure->profundidad : '' ?></td>
                        <td class="text-nowrap"><?= $measure->medida_volumen ?></td>
                        <td class="text-nowrap"><?= $measure->updated_at ?></td>
                        <td class="text-nowrap"><?= $measure->usuario_nombre ?></td>
                        <?php endif; endforeach ?>

                        <?php if( !$matched ): ?>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <?php endif ?>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- OBSERVACIONES -->
<?php $observaciones = $entry->observaciones ? json_decode($entry->observaciones) : [] ?>
<div class="card mb-3">
    <div class="card-header">
        <span class="lead">Observaciones</span>
        <span class="badge badge-primary"><?= count($observaciones) ?></span>
    </div>
    <div class="card-body p-0">
        <div class="row no-gutters">
            <div class="col-sm col-sm-8" style="height:264px; overflow-y:auto">
            
            <?php if( $observaciones ): ?>
                <ul class="list-group list-group-flush">
                    <?php foreach($observaciones as $elem): ?>
                    <li class="list-group-item list-group-item-action">
                        <p class="m-0"><?= $elem->observacion ?></p>
                        <small class="text-muted"><?= $elem->nombre ?> | <?= $elem->creado_at ?></small>
                    </li>
                    <?php endforeach ?>
                </ul>
            <?php endif ?>

            </div>
            <div class="col-sm p-3 border-top border-left">
                <form action="<?= url('entradas/actualizar_observaciones/'.$entry->id) ?>" method="post">
                    <div class="form-group">
                        <textarea name="observacion" id="observacion" cols="30" rows="7" class="form-control" placeholder="Escribe aqui..." required></textarea>
                    </div>
                    <div class="text-right">
                        <button class="btn btn-success btn-sm btn-block">Guardar observacion</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if( $user_type === 'administrador' ): ?>
<p class="text-right mb-5">
    <a href="<?= url('entradas/advertencia_eliminar/'.$entry->id) ?>" class="text-danger text-decoration-none">
        <i class="fas fa-trash align-middle"></i>
        <span class="align-middle">Eliminar esta guia de entrada</span>
    </a>
</p>
<?php endif ?>

<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>