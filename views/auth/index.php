<?php $template->fill('content') ?>
<div class="row mt-4">
    <div class="col-sm col-sm-4 offset-sm-4">
        <div class="card">
            <div class="card-body">
                <form action="<?= url('signing') ?>" method='post' autocomplete="off" class="mb-3">
                    <p class="lead text-muted font-weight-bold"><?= config('app', 'name') ?></p>
                    <div class="form-group">
                        <input type="email" class="form-control" name="usuario" value="<?= $user ?>" placeholder="Usuario" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
                    </div>
                    <p class="text-right m-0">
                        <button type="submit" class="btn btn-primary btn-outline-primaryx">Iniciar sesion</button>
                    </p>
                </form>

                <?php // $template->insert('partials/message') ?>
                <?php $template->insert('partials/alert') ?>
                <?php $template->insert('partials/validation') ?>

            </div>
        </div>
        <a href="<?= url('reempacado') ?>" class="d-block mt-2">Área de reempacado</a>
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/login') ?>