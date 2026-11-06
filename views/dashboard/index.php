<?php $template->fill('content') ?>
<h1 class="d-none d-md-block">Escritorio</h1>
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