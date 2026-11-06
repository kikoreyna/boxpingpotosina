<?php $template->fill('content') ?>
<?php $template->insert('partials/validation') ?>
<?php $template->insert('partials/alert') ?>
<div class="jumbotron text-center">
  <p class="display-4">Editar credencial de usuario</p>
  
  <hr class="my-4">

  <p class="lead text-secondary">Cliente <b class="text-dark"><?= $client->nombre ?></b></p>
  <form action="<?= url('clientes/actualizar/credencial/'.$client->id) ?>" method="post" class="row">
    <div class="col-sm-4 offset-md-4">
        <div class="form-group">
            <?php if( !is_null($client->correo) ): ?>
            <small class="d-block text-left text-muted">
                Opcional: <b><?= $client->correo ?></b>
            </small>
            <?php endif ?>
            <input type="email" class="form-control" name="email" value="<?= $client->cliente_usuario_email ?>" placeholder="Correo electronico" required>
        </div>
        <small class="d-block text-left text-secondary">Para mantener el password, ignorar estos campos.</small>
        <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Password">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" name="confirmar" placeholder="Confirmar password">
        </div>
        <button class="btn btn-warning mb-3 mb-md-0" type="submit">Actualizar credencial de usuario</button>
        <a class="btn btn-dark" href="<?= url('clientes/mostrar/'.$client->id) ?>" role="button">Regresar</a>

        <div class="alert alert-warning mt-3">
            <h5 class="alert-heading">IMPORTANTE</h5>
            <p class="m-0">Notificar al cliente de la actualizacion, de lo contrario no podra accesar a la aplicacion</p>
        </div>
    </div>
  </form>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>