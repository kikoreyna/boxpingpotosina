<?php $template->fill('content') ?>
<h1 class="d-none d-md-block">Escritorio</h1>

<!-- Tareas -->
<div class="card mb-3 <?= $pending_tasks ? 'border-warning' : '' ?>">
    <div class="card-header">
        <a href="<?= url('tareas') ?>" class="btn btn-sm btn-outline-secondary float-right">Ver tareas</a>
        <span class="lead"><?= $is_documenter ? 'Mis entradas pendientes de revisión' : 'Tareas pendientes de revisión' ?></span>
        <span class="badge badge-warning badge-pill"><?= $pending_tasks ?></span>
    </div>
    <?php if( $is_documenter && $my_tasks ): ?>
    <div class="table-responsive">
        <table class="table table-sm table-hover m-0">
            <tbody>
                <?php foreach($my_tasks as $task): ?>
                <tr>
                    <td class="pl-3"><a href="<?= url('entradas/mostrar/'.$task->entrada_id) ?>"><?= $task->entrada_numero ?></a></td>
                    <td><?= htmlspecialchars($task->descripcion) ?></td>
                    <td class="small text-muted">Asignada <?= $task->created_at ?> por <?= $task->asignado_por_nombre ?></td>
                    <td class="text-right pr-3">
                        <form action="<?= url('tareas/completar/'.$task->id) ?>" method="post" class="d-inline">
                            <button type="submit" class="btn btn-sm btn-success" title="Marcar como completada"><i class="fas fa-check"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?php elseif( $is_documenter ): ?>
    <div class="card-body text-muted small">No tienes entradas pendientes.</div>
    <?php endif ?>
</div>
<!-- Counters -->
<div class="row mb-3">
    <div class="col-sm">
        <div class="card">
            <div class="card-body text-center">
                <h3><?= $counters->consolidados_count ?></h3>
                <p class="small m-0">GUIAS CONSOLIDADAS</p>
            </div>
        </div>
    </div>
    <div class="col-sm">
        <div class="card">
            <div class="card-body text-center">
                <h3><?= $counters->sin_consolidar_count ?></h3>
                <p class="small m-0">GUIAS SIN CONSOLIDAR</p>
            </div>
        </div>
    </div>
    <div class="col-sm">
        <div class="card">
            <div class="card-body text-center">
                <h3><?= $counters->sin_cliente_count ?></h3>
                <p class="small m-0">GUIAS SIN CLIENTE</p>
            </div>
        </div>
    </div>
</div>

<!-- Progress -->
<div class="card">
    <div class="card-body">
        <label>En bodega USA</label>
        <?php $percent = percentage($counters->en_bodega_usa, $counters->entradas_total) ?>
        <div class="progress mb-3">
            <div class="progress-bar" role="progressbar" style="width: <?= $percent ?>%;" aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100"><?= $percent ?>%</div>
        </div>

        <label>En bodega MEX</label>
        <?php $percent = percentage($counters->en_bodega_mex, $counters->entradas_total) ?>
        <div class="progress mb-3">
            <div class="progress-bar" role="progressbar" style="width: <?= $percent ?>%;" aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100"><?= $percent ?>%</div>
        </div>
        <label>Reempacados</label>
        <?php $percent = percentage($counters->reempacados, $counters->entradas_total) ?>
        <div class="progress mb-3">
            <div class="progress-bar" role="progressbar" style="width: <?= $percent ?>%;" aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100"><?= $percent ?>%</div>
        </div>

        <!-- <label>Con salida</label>
        <div class="progress mb-3">
            <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
        </div> -->
    </div>
</div>

<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>