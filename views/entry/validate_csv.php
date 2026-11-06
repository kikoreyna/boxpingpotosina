<?php $template->fill('content') ?>
<form action="<?= url('entradas/guardar/csv') ?>" method="post" class="text-right mb-3">
    <input type="hidden" name="csv" value='<?= json_encode($csv) ?>'>

    <?php if( is_object($consolidated) ): ?>
    <input type="hidden" name="consolidado" value='<?= $consolidated->id ?>'>
    <?php endif ?>
    
    <a class="btn btn-secondary" href="<?= url($goback) ?>">Regresar</a>
    <button class="btn btn-success" type="submit">Guardar entradas</button>
</form>

<?php if( isset($error_row) ): ?>
<div class="alert alert-warning text-center">
    <h4 class="alert-heading">Advertencia!</h4>
    <b class="text-danger">No se almacenará las filas rojas</b>
</div>
<?php endif?>

<div class="card">
    <div class="card-header row">
        <div class="col-sm">
            <span class="align-middle">Guias de entrada</span>
            <span class="badge badge-primary badge-pill align-middle"><?= count($csv) ?></span>
        </div>
        <div class="col-sm text-right">
            <span class="small d-block d-md-inline-block">Cliente: <b class="font-weight-bold"><?= $client->nombre ?>(<?= $client->alias ?>)</b></span> 
            <span class="d-none d-md-inline-block">/</span>
            <span class="small d-block d-md-inline-block">Numero con alias: <b class="font-weight-bold"><?= $alias_numero ? 'Si' : 'No' ?></b></span>
            <span class="d-none d-md-inline-block">/</span>
            <span class="small">Consolidado: <b class="font-weight-bold"><?= is_object($consolidated) ? $consolidated->numero : 'No' ?></b></span>
        </div>
    </div>

    <?php ob_start() ?>
    <div class="table-responsive pb-3">
        <table class="table table-hover table-borderedx table-sm small m-0">
            <thead class="text-white">
                <tr class="bg-dark">
                    <th class="border-0" colspan="8">GUIA</th>
                    <th class="border-0" colspan="7">REMITENTE</th>
                    <th class="border-0" colspan="8">DESTINATARIO</th>
                </tr>
                <tr class="bg-secondary">
                    <th class="border-0">#</th>
                    <th class="border-0">Número</th>
                    <th class="border-0">Peso</th>
                    <th class="border-0">Medida</th>
                    <th class="border-0">Ancho</th>
                    <th class="border-0">Altura</th>
                    <th class="border-0">Profundidad</th>
                    <th class="border-0">Medida</th>
                    <th class="border-0">Nombre</th>
                    <th class="border-0">Teléfono</th>
                    <th class="border-0">Dirección</th>
                    <th class="border-0">Postal</th>
                    <th class="border-0">Ciudad</th>
                    <th class="border-0">Estado</th>
                    <th class="border-0">Pais</th>
                    <th class="border-0">Nombre</th>
                    <th class="border-0">Teléfono</th>
                    <th class="border-0">Dirección</th>
                    <th class="border-0">Postal</th>
                    <th class="border-0">Referencias</th>
                    <th class="border-0">Ciudad</th>
                    <th class="border-0">Estado</th>
                    <th class="border-0">Pais</th>
                </tr>
            </thead>
            <tbody>
            <?php $i = 0; foreach($csv as $item): $i++; $bg_class = '' ?>
                <?php if( empty($item['numero']) ) $bg_class = 'table-danger' ?>

                <tr class="<?= $bg_class ?>">
                    <th class="text-muted"><?= $i ?></th>

                <?php // Propiedades de la guia ?>
                <?php foreach($guide_props as $prop): ?>
                    <?php if( !empty($item[$prop]) ): ?>
                    <td><?= $item[$prop] ?></td>

                    <?php else: ?>
                    <td class="text-center text-warning font-weight-bold">?</td>
                    <?php endif ?>
                <?php endforeach ?>

                <?php // Propiedades del remitente ?>
                <?php foreach($track_props as $prop): ?>
                    <?php if( !empty($item['remitente'][$prop]) ): ?>
                    <td><?= $item['remitente'][$prop] ?></td>

                    <?php else: ?>
                    <td class="text-center text-warning font-weight-bold">?</td>
                    <?php endif ?>
                <?php endforeach ?>

                <?php // Propiedades del destinatario ?>
                <?php foreach($track_props as $prop): ?>
                    <?php if( !empty($item['destinatario'][$prop]) ): ?>
                    <td><?= $item['destinatario'][$prop] ?></td>

                    <?php if( $prop === 'postal' ): ?>
                    <td><?= $item['destinatario']['referencias'] ?></td>
                    <?php endif ?>

                    <?php else: ?>
                    <td class="text-center text-warning font-weight-bold">?</td>
                    <?php endif ?>
                <?php endforeach ?>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?= ob_get_clean() ?>
</div>

<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>