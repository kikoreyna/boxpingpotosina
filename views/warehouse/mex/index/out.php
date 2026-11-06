<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title text-uppercase mb-4">Salida de bodega MEX</h5>
        <form id="frm-registrar" action="<?= url('bodega/registrar/numero/salida') ?>" method="post" autocomplete="off">
            <div class="form-group">
                <label>Numero de guia</label>
                <input type="text" class="form-control" name="numero" autofocus required>
            </div>
            <div class="form-group">
                <label for="">Observaciones</label>
                <textarea name="observaciones" id="observaciones" cols="30" rows="5" class="form-control"></textarea>
            </div>
            <button class="btn btn-success" type="submit" form="frm-registrar">Registrar salida de guia</button>
        </form>
    </div>
    <div class="card-footer">
        Registrar en 
        <a href="<?= url('bodega/entrada') ?>" class="text-primary">Entrada a bodega MEX</a>
    </div>
</div>
<br>
<p class="text-right">
    Cambiar a
    <a href="<?= url('transportadora/carga') ?>" class="link-primary">Carga para transportadora</a>
</p>


<?php $template->stop() ?>
<?php $template->expand('layouts/navless') ?>
