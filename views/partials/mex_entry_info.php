<?php
// Datos de consulta de la guia para bodega MEX. Requiere $info y $coming.
$e = $info['entry'];
$h = function($v) { return htmlspecialchars((string) $v); };
$has_wayout = !empty($e->salida_id);
$verified = !empty($e->destinatario_verificacion_at);
$join = function(array $parts) use ($h) { return $h(implode(', ', array_filter($parts))); };
?>
<div class="card mb-3 border-info">
    <div class="card-header bg-info text-white py-2">Datos de la guía <?= $h($e->numero) ?></div>
    <div class="card-body small">

        <?php if( $coming === 'entrada' ): ?>
        <div class="row mb-2">
            <div class="col-sm mb-2">
                <span class="text-muted d-block">Verificación</span>
                <?php if( $verified ): ?>
                <span class="badge badge-success">Verificada</span>
                <?php else: ?>
                <span class="badge badge-warning">Pendiente de verificación</span>
                <?php endif ?>
            </div>
            <div class="col-sm mb-2">
                <span class="text-muted d-block">Transportadora</span>
                <?= $has_wayout && $e->transportadora_nombre ? $h($e->transportadora_nombre) : '<span class="text-muted">Sin asignar</span>' ?>
            </div>
            <div class="col-sm mb-2">
                <span class="text-muted d-block">Rastreo</span>
                <?= $has_wayout && $e->salida_rastreo ? $h($e->salida_rastreo) : '<span class="text-muted">Sin rastreo</span>' ?>
            </div>
        </div>

        <span class="text-muted d-block">Peso y medidas registrados</span>
        <?php if( $info['measures'] ): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-sm m-0">
                <thead class="thead-light">
                    <tr><th>Etapa</th><th>Peso</th><th>Ancho</th><th>Alto</th><th>Prof.</th><th>Registró</th><th>Fecha</th></tr>
                </thead>
                <tbody>
                <?php foreach($info['measures'] as $m): ?>
                    <tr>
                        <td class="text-capitalize"><?= str_replace('_', ' ', $m->etapa) ?></td>
                        <td><?= $m->peso > 0 ? $h($m->peso.' '.$m->medida_peso) : '' ?></td>
                        <td><?= $m->ancho > 0 ? $h($m->ancho.' '.$m->medida_volumen) : '' ?></td>
                        <td><?= $m->altura > 0 ? $h($m->altura.' '.$m->medida_volumen) : '' ?></td>
                        <td><?= $m->profundidad > 0 ? $h($m->profundidad.' '.$m->medida_volumen) : '' ?></td>
                        <td><?= $h($m->usuario_nombre) ?></td>
                        <td><?= $h($m->updated_at) ?></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <span class="text-muted">Sin peso ni medidas previas</span>
        <?php endif ?>

        <?php else: ?>
        <div class="row mb-2">
            <div class="col-sm mb-2">
                <span class="text-muted d-block">Transportadora (por donde sale)</span>
                <?= $has_wayout && $e->transportadora_nombre ? '<b>'.$h($e->transportadora_nombre).'</b>' : '<span class="text-danger">Sin guía de salida / transportadora</span>' ?>
            </div>
            <div class="col-sm mb-2">
                <span class="text-muted d-block">Cobertura</span>
                <?= $has_wayout ? '<span class="text-capitalize">'.$h($e->salida_cobertura).'</span>' : '' ?>
            </div>
            <div class="col-sm mb-2">
                <span class="text-muted d-block">Rastreo</span>
                <?= $has_wayout && $e->salida_rastreo ? $h($e->salida_rastreo) : '<span class="text-muted">Sin rastreo</span>' ?>
            </div>
            <div class="col-sm mb-2">
                <span class="text-muted d-block">Confirmación</span>
                <?= $has_wayout && $e->salida_confirmacion ? $h($e->salida_confirmacion) : '<span class="text-muted">Sin confirmación</span>' ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm mb-2">
                <span class="text-muted d-block">Destinatario</span>
                <b><?= $h($e->destinatario_nombre) ?></b><br>
                <?= $h($e->destinatario_telefono) ?>
            </div>
            <div class="col-sm mb-2">
                <?php if( $has_wayout && $e->salida_cobertura === 'ocurre' ): ?>
                <span class="text-muted d-block">Dirección de ocurre</span>
                <?= $join([$e->salida_direccion, $e->salida_postal ? 'C.P. '.$e->salida_postal : '']) ?><br>
                <?= $join([$e->salida_ciudad, $e->salida_estado, $e->salida_pais]) ?>
                <?php else: ?>
                <span class="text-muted d-block">Dirección de domicilio</span>
                <?= $join([$e->destinatario_direccion, $e->destinatario_postal ? 'C.P. '.$e->destinatario_postal : '']) ?><br>
                <?= $join([$e->destinatario_ciudad, $e->destinatario_estado, $e->destinatario_pais]) ?>
                <?php if( $e->destinatario_referencias ): ?>
                <br><span class="text-muted">Ref.: <?= $h($e->destinatario_referencias) ?></span>
                <?php endif ?>
                <?php endif ?>
            </div>
        </div>
        <?php endif ?>

    </div>
</div>
