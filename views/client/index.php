<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<div class="card">
    <div class="card-header">
        <div class='float-right'>
            <a href="<?= url('clientes/nuevo') ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="right" title="Nuevo cliente">
                <i class="fas fa-plus"></i>
            </a>
        </div>
        <p class="m-0 lead">
            Clientes <span class="badge badge-primary badge-pill"><?= $count ?></span>
        </p>        
    </div>
    <?php if($count): ?>
        <div class="table-responsive">
            <table class="table table-hover table-sm m-0">
                <thead class="text-secondary small">
                    <tr>
                        <th class="border-0 pl-3">NOMBRE</th>
                        <th class="border-0">ALIAS</th>
                        <th class="border-0">DIRECCION</th>
                        <th class="border-0">LOCALIDAD</th>
                        <th class="border-0">TELEFONO</th>
                        <th class="border-0">CORREO ELECTRONICO</th>
                        <!-- <th class="border-0">CREDENCIAL DE USUARIO</th> -->
                        <th class="border-0"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($clients as $client): ?>
                    <tr>
                        <td class="text-nowrap pl-3">
                            <a href="<?= url('clientes/mostrar/'.$client->id) ?>"><?= $client->nombre ?></a>
                        </td>
                        <td><?= $client->alias ?></td>
                        <td><?= $client->direccion ?>, <?= $client->postal ?></td>        
                        <td><?= $client->ciudad ?>, <?= $client->estado ?>, <?= $client->pais ?></td>
                        <td><?= $client->telefono ?></td>
                        <td><?= $client->correo ?></td>
                        <!-- <td><?= $client->cliente_usuario_email ?></td> -->
                        <td class="text-right pr-3">
                            <a href="<?= url('clientes/editar/'.$client->id) ?>" class="btn btn-warning btn-sm d-none">
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