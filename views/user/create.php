<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>

<div class="card">
    <div class="card-header">
        <p class="m-0 lead">Nuevo usuario</p>
    </div>
    <div class="card-body">
        <form action="<?= url('usuarios/guardar') ?>" method="post">
            <div class="form-group">
                <label for="">Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="">Tipo</label>
                <select name="tipo" class="form-control" required>
                    <option disabled selected></option>
                    <?php foreach($types as $type): if($type !== 'cliente'): ?>
                    <option value="<?= $type ?>"><?= ucwords( str_replace('_', ' ', $type) ) ?></option>
                    <?php endif; endforeach ?>
                </select>
            </div>
            <div class="form-group">
                <label for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Correo electrónico" required>
            </div>
            <div class="form-group">
                <label for="">Contraseña</label>
                <input name="password" type="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="">Confirmar contraseña</label>
                <input name="confirmar" type="password" class="form-control" required>
            </div>

            <button class="btn btn-success" type="submit">Guardar usuario</button>
            <a href="<?= url('usuarios') ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>