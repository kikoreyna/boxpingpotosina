<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title text-uppercase mb-4">Carga para transportadora</h5>
        <form action="<?= url('transportadora/cargando') ?>" method="post" autcomplete="off">
            <div class="form-group">
                <label for="numeroGuiaInput">Número de guia</label>
                <input class="form-control" type="text" id="numeroGuiaInput" name="numero_entrada" required autofocus>
            </div>
            <button class="btn btn-success">Registrar carga de guia</button>
        </form>
    </div>
    <div class="card-footer">
        Registrar en 
        <a href="<?= url('transportadora/descarga') ?>" class="text-primary">Descarga para transportadora</a>
    </div>
</div>
<br>
<p class="text-right">
    Cambiar a 
    <a href="<?= url('bodega/entrada') ?>" class="link-primary">Entrada a bodega MEX</a>
</p>

<?php $template->insert('modals/history') ?>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>