<?php $template->fill('content') ?>
<?php $template->insert('partials/validation') ?>
<?php $template->insert('partials/alert') ?>
<div class="jumbotron text-center">
  <p class="display-4">Credencial de usuario</p>
  <hr class="my-4">
  <p class="lead text-secondary">Cliente <b class="text-dark"><?= $client->nombre ?></b> tendra acceso a la aplicacion.</p>
  <form action="<?= url('clientes/guardar/usuario/'.$client->id) ?>" method="post" class="row">
    <div class="col-sm-4 offset-md-4">
        <div class="form-group">
            <?php if( !is_null($client->correo) ): ?>
            <small class="d-block text-left text-muted">
                Opcional: <b><?= $client->correo ?></b>
            </small>
            <?php endif ?>
            <input type="email" class="form-control" name="email" placeholder="Correo electronico" required>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" name="confirmar" placeholder="Confirmar contraseña" required>
        </div>
        <button class="btn btn-success mb-3 mb-md-0" type="submit">Crear credencial de usuario</button>
        <a class="btn btn-dark" href="<?= url('clientes') ?>" role="button">En otra ocasión</a>
    </div>
  </form>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>