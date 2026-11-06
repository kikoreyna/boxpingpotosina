<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>
<div class="card">
    <div class="card-header">
        <p class="m-0 lead">Editar usuario</p>
    </div>
    <div class="card-body">
        <form action="<?= url('usuarios/actualizar/'.$user->id) ?>" method="post">
            <div class="form-group">
                <label for="">Nombre</label>
                <input type="text" name="nombre" value="<?= $user->nombre ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="">Tipo</label>
                <select name="tipo" class="form-control" required>
                    <?php foreach($types as $type): if($type !== 'cliente'): ?>
                    <?php $selected = $user->tipo === $type ? 'selected' : '' ?>
                    <option value="<?= $type ?>" <?= $selected ?>><?= ucfirst( str_replace('_', ' ', $type) ) ?></option>
                    <?php endif; endforeach ?>
                </select>
            </div>
            <div class="form-group">
                <label for="">Email</label>
                <input name="email" value="<?= $user->email ?>" type="email" class="form-control" placeholder="Correo electrónico" required>
            </div>
            <div class="form-group">
                <label for="">Nueva contraseña</label>
                <input name="password" type="password" placeholder="Ignorar este campo para mantener contraseña actual" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Confirmar nueva contraseña</label>
                <input name="confirmar" type="password" class="form-control">
            </div>

            <button class="btn btn-warning" type="submit">Actualizar usuario</button>
            <a href="<?= url('usuarios') ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>