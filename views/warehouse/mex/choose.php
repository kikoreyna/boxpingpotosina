<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>
<!-- Bodega MEX -->
<div class="card">
    <div class="card-header">
        <div class="form-row">
            <div class="col">
                <span class="lead align-middle">Número de guia</span>
                <span class="badge badge-dark align-middle"><?= $number ?>
            </div>
            <div class="col-4 text-right">
                <a href="<?= url("bodega/{$coming}?conductor={$driver}&vehiculo={$car}") ?>" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="left" title="Regresar">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="alert alert-warning rounded-0 m-0">
        <h5>
            <span class="badge badge-warning badge-pill align-middle"><?= count($entries) ?> </span>
            <span class="align-middle">Guias con mismo numero!</span>
        </h5>
        <p class="small m-0">Comunicarse con el encargado de area y registrar la guia correspondiente.</p>
    </div>
    <form action="<?= url("bodega/registrar/id/{$coming}") ?>" id="frm-registro-entrada" method="post">
        <?php if( $driver ): ?>
        <input type="hidden" name="conductor" value="<?= $driver ?>">
        <?php endif ?>

        <?php if( $car ): ?>
        <input type="hidden" name="vehiculo" value="<?= $car ?>">
        <?php endif ?>

        <?php if( $observations ): ?>
        <input type="hidden" name="observaciones" value="<?= $observations ?>">
        <?php endif ?>
    </form>

    <div class="table-responsive">
        <table class="table table-hover table-sm m-0">
            <thead class="table-dark small">
                <tr>
                    <th class="pl-3">Consolidado</th>
                    <th class="">Cliente</th>
                    <th class=""></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($entries as $entry): ?>
                <tr>
                    <td class="align-middle pl-3"">
                        <?php if( !is_null($entry->consolidado_numero) ): ?>
                        <span><?= $entry->consolidado_numero ?></span>

                        <?php else: ?>
                        <span class="text-secondary small">Sin consolidar</span>

                        <?php endif ?>
                    <td class="align-middle"><?= $entry->cliente_nombre ?></td>
                    <td class="text-right pr-3">
                        <button class="btn btn-success btn-sm" form="frm-registro-entrada" name="numero" value="<?= $entry->id ?>">Registrar</button>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/navless') ?>