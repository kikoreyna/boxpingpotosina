<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>

<div class="card">
    <div class="card-header">
        <div class="float-right">
            <a href="<?= url('usuarios/nuevo') ?>" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="right" title="Nuevo usuario">
                <i class="fas fa-plus"></i>
            </a>
        </div>
        <p class="m-0 lead">Usuarios <span class="badge badge-primary badge-pill"><?= count($users) ?></span></p>
    </div>

    <?php if( $users ): ?>
        <div class="table-responsive">
            <table class="table table-hover table-sm m-0">
                <thead class="text-secondary small">
                    <tr>
                        <th class="border-0 pl-3">NOMBRE</th>
                        <th class="border-0">EMAIL</th>
                        <th class="border-0">TIPO</th>
                        <th class="border-0"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                    <tr>
                        <td class='pl-3'><?= $user->nombre ?> </td>
                        <td><?= $user->email ?></td>
                        <td><?= ucfirst( str_replace('_', ' ', $user->tipo) ) ?></td>
                        <td class="text-right pr-3">
                            <a href="<?= url('usuarios/editar/'.$user->id) ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    <?php endif ?>
</div>

<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>