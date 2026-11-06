<div class="card">
    <div class="card-header">
        <p class="lead m-0">Guia de entrada</p>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-bordered small">
                <thead>
                    <tr>
                        <th colspan="2" class="bg-light">Entrada</th>
                    </tr>
                </thead>
                <tbody class="">
                    <tr>
                        <td class="bg-light text-secondary">Número</td>
                        <?php $alias_numero = $entry->cliente_alias ? stick($entry->cliente_alias, $entry->numero) : $entry->numero ?>
                        <td class="text-capitalize"><?= $alias_numero ?></td>
                    </tr>
                    <tr>
                        <td class="bg-light text-secondary">Consolidado</td>
                        <?php $alias_numero = $entry->consolidado_alias_cliente_numero ? stick($entry->cliente_alias, $entry->consolidado_numero) : $entry->consolidado_numero ?>
                        <td><?= $alias_numero ?></td>
                    </tr>
                    <tr>
                        <td class="bg-light text-secondary">Cliente</td>
                        <td><?= $entry->cliente_nombre ?></td>
                    </tr>
                    <tr>
                        <td class="bg-light text-secondary">Código de reempacado</td>
                        <td><?= $entry->reempacado_codigo ?></td>
                    </tr>
                    <tr>
                        <td class="bg-light text-secondary">Creación</td>
                        <td><?= $entry->created_at ?></td>
                    </tr>
                    <tr>
                        <td class="bg-light text-secondary">Actualización</td>
                        <td><?= $entry->updated_at ?></td>
                    </tr>
                    <tr>
                        <th colspan="3" class="bg-light">Medidas</th>
                    </tr>
                    <tr>
                        <td class="bg-light text-secondary align-middle">Etapa</td>
                        <td class="text-secondary">Peso <br> Volúmen</td>
                    </tr>
                    <?php foreach($entry->medidas as $measure): ?>
                    <tr>
                        <td class="bg-light text-secondary text-capitalize align-middle"><?= str_replace('_', ' ', $measure->etapa) ?></td>
                        <td class="text-nowrap">
                        <?php if( !is_null($measure->peso) ): ?>
                            <?= $measure->peso ?> <?= $measure->medida_peso ?>
                        <?php endif ?>
                        <br>
                        <?php if( !is_null($measure->ancho) || !is_null($measure->altura) || !is_null($measure->profundidad) ): ?>
                            <?= $measure->ancho ?> * <?= $measure->altura ?> * <?= $measure->profundidad ?> <?= $measure->medida_volumen ?>
                        <?php endif ?>
                        </td>
                    </tr>
                    <?php endforeach ?>
                    <tr>
                        <th colspan="2" class="bg-light">Destinatario</th>
                    </tr>
                    <tr>
                        <td class="bg-light text-secondary">Nombre</td>
                        <td><?= $entry->destinatario_nombre ?></td>
                    </tr>
                    <tr>
                        <td class="bg-light text-secondary">Teléfono</td>
                        <td><?= $entry->destinatario_telefono ?></td>
                    </tr>
                    <tr>
                        <td class="bg-light text-secondary">Dirección</td>
                        <td><?= $entry->destinatario_direccion ?></td>
                    </tr>
                    <tr>
                        <td class="bg-light text-secondary">Referencias</td>
                        <td><?= $entry->destinatario_referencias ?></td>
                    </tr>
                    <tr>
                        <td class="bg-light text-secondary">Postal</td>
                        <td><?= $entry->destinatario_postal ?></td>
                    </tr>
                    <tr>
                        <td class="bg-light text-secondary">Localidad</td>
                        <td><?= $entry->destinatario_ciudad ?>, <?= $entry->destinatario_estado ?>, <?= $entry->destinatario_pais ?></td>
                    </tr>
                    <tr>
                        <td class="bg-light text-secondary">Verificación</td>
                        <td class="font-weight-bold">
                            <?php if( !is_null($entry->destinatario_verificacion_at) ): ?>
                            <span class="text-success">Si</span>

                            <?php else: ?>
                            <span class="text-muted">No</span>

                            <?php endif ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>