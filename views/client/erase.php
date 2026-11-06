<?php $template->fill('content') ?>
<div class="card">
    <div class="card-body p-5 text-center">
        <p class="lead">¿Quieres continuar para eliminar el cliente <b class="text-danger"><?= $client->nombre ?></b> con alias <span class="text-danger"><?= $client->alias ?></span>?</p>
    </div>
    <div class="card-footer text-right">
        <form action="<?= url('clientes/eliminar/'.$client->id) ?>" method="post">
            <input type="hidden" name="nombre" value="<?= $client->nombre ?>">
            <a class="btn btn-secondary" href="<?= url('clientes/mostrar/'.$client->id) ?>" role="button">Regresar</a>
            <button class="btn btn-outline-danger" type="submit">Si, eliminar cliente</button>
        </form>
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>