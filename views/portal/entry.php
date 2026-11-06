<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>

<?php
$e = $entry;
$yn = function($v) { return $v ? 'Sí' : 'No'; };
$h = function($v) { return htmlspecialchars((string) $v); };
$place = function($p, $e) use ($h) {
    return $h(implode(', ', array_filter([$e->{$p.'_direccion'}, $e->{$p.'_ciudad'}, $e->{$p.'_estado'}, $e->{$p.'_pais'}])));
};
?>

<p><a href="<?= url('cliente') ?>" class="small">&larr; Volver</a></p>

<div class="card mb-3">
    <div class="card-header">Guía <?= $h($e->numero) ?></div>
    <div class="card-body small">
        <div class="row">
            <div class="col-sm mb-2"><span class="text-muted d-block">Consolidado</span>
                <?php if( $e->consolidado_id ): ?>
                <a href="<?= url('cliente/consolidados/mostrar/'.$e->consolidado_id) ?>"><?= $h($e->consolidado_numero) ?></a>
                <?php else: ?>Sin consolidado<?php endif ?>
            </div>
            <div class="col-sm mb-2"><span class="text-muted d-block">Creada</span><?= $e->created_at ?></div>
            <div class="col-sm mb-2"><span class="text-muted d-block">En bodega USA</span><?= $yn($e->en_bodega_usa) ?></div>
            <div class="col-sm mb-2"><span class="text-muted d-block">En bodega MEX</span><?= $yn($e->en_bodega_mex) ?></div>
            <div class="col-sm mb-2"><span class="text-muted d-block">Cruce</span><?= $e->cruce_at ?: 'Pendiente' ?></div>
            <div class="col-sm mb-2"><span class="text-muted d-block">Reempacada</span><?= $e->reempacado_at ?: 'Pendiente' ?></div>
        </div>
    </div>
</div>

<div class="form-row">
    <div class="col-sm mb-3">
        <div class="card h-100">
            <div class="card-header">Remitente</div>
            <div class="card-body small">
                <b><?= $h($e->remitente_nombre) ?></b><br>
                <?= $place('remitente', $e) ?><br>
                <?= $h($e->remitente_telefono) ?>
            </div>
        </div>
    </div>
    <div class="col-sm mb-3">
        <div class="card h-100">
            <div class="card-header">Destinatario</div>
            <div class="card-body small">
                <b><?= $h($e->destinatario_nombre) ?></b><br>
                <?= $place('destinatario', $e) ?><br>
                <?= $h($e->destinatario_telefono) ?>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">Salida</div>
    <div class="card-body small">
        <?php if( $e->rastreo || $e->transportadora ): ?>
        <div class="row">
            <div class="col-sm"><span class="text-muted d-block">Transportadora</span><?= $h($e->transportadora) ?></div>
            <div class="col-sm"><span class="text-muted d-block">Rastreo</span><?= $h($e->rastreo) ?></div>
            <div class="col-sm"><span class="text-muted d-block">Confirmación</span><?= $h($e->confirmacion) ?></div>
            <div class="col-sm"><span class="text-muted d-block">Estatus</span><?= $h($e->status) ?></div>
            <div class="col-sm"><span class="text-muted d-block">Cobertura</span><?= ucfirst($h($e->cobertura)) ?></div>
        </div>
        <?php if( $e->cobertura === 'ocurre' ): ?>
        <div class="mt-3">
            <span class="text-muted d-block">Dirección de ocurre</span>
            <?= $h($e->salida_direccion) ?><?= $e->salida_postal ? ', C.P. '.$h($e->salida_postal) : '' ?><br>
            <?= $h(implode(', ', array_filter([$e->salida_ciudad, $e->salida_estado, $e->salida_pais]))) ?>
        </div>
        <?php endif ?>
        <?php else: ?>
        <span class="text-muted">Aún sin guía de salida</span>
        <?php endif ?>
    </div>
</div>

<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>
