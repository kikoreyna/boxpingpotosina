<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>
<div class="row">
    <div class="col-sm col-sm-12">
        <div class="card mb-3">
            <div class="card-header">
                <div class="float-right">
                    <a class="btn btn-warning btn-sm" href="<?= url('clientes/editar/'.$client->id) ?>">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                    <a class="btn btn-danger btn-sm" href="<?= url('clientes/borrar/'.$client->id) ?>">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
                <span class="lead">Cliente</span>
            </div>
            <div class="card-body p-0 m-0">
                <div class="table-responsive">
                    <table class="table table-sm border-0 m-0">
                        <tbody>
                            <tr>
                                <td class="pl-3 border-right bg-light" style="width:0.1%">Nombre</td>
                                <td class="pr-3"><?= $client->nombre ?> </td>
                            </tr>
                            <tr>
                                <td class="pl-3 border-right bg-light">Alias</td>
                                <td class="pr-3"><?= $client->alias ?> </td>
                            </tr>
                            <tr>
                                <td class="pl-3 border-right bg-light">Telefono</td>
                                <td class="pr-3"><?= $client->telefono ?> </td>
                            </tr>
                            <tr>
                                <td class="pl-3 border-right bg-light">Correo</td>
                                <td class="pr-3"><?= $client->correo ?> </td>
                            </tr>
                            <tr>
                                <td class="pl-3 border-right bg-light">Direccion</td>
                                <td class="pr-3"><?= $client->direccion ?>, <?= $client->postal ?></td>
                            </tr>
                            <tr>
                                <td class="pl-3 border-right bg-light">Localidad</td>
                                <td class="pr-3"><?= $client->ciudad ?>, <?= $client->estado ?>, <?= $client->pais ?></td>
                            </tr>
                            <tr>
                                <td class="pl-3 border-right bg-light">Notas</td>
                                <td class="pr-3"><?= $client->notas ?></td>
                            </tr>
                            <tr class='d-none'>
                                <td class="pl-3 border-right bg-light">Consolidados</td>
                                <td class="pr-3"><?= $consolidated_count ?></td>
                            </tr>
                            <tr class="d-none">
                                <td class="pl-3 border-right bg-light">Entradas</td>
                                <td class="pr-3"><?= $entries_count ?></td>
                            </tr>
                            <tr>
                                <td class="pl-3 border-right bg-light">Creado</td>
                                <td class="pr-3"><?= $client->created_at ?></td>
                            </tr>
                            <tr>
                                <td class="pl-3 border-right bg-light">Actualizado</td>
                                <td class="pr-3"><?= $client->updated_at ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm d-none">
        <div class="card border-0 bg-transparent text-center">
            <div class="card-header bg-transparent">
                <span class="lead">Credencial de usuario</span>
            </div>
            <div class="card-body">
                <?php if( is_null($client->cliente_usuario_id) ): ?>
                <p>
                    <a href="<?= url('clientes/crear/credencial/'.$client->id) ?>" class="btn btn-primary">
                        <span>Crear</span>
                    </a>
                </p>
                <?php else: ?>
                <p>
                    <small class="text-secondary">Correo electronico</small>
                    <?= $client->cliente_usuario_email ?>
                </p>

                    <?php if( $session_type === 'administrador' ): ?>
                    <a href="<?= url('clientes/editar/credencial/'.$client->id) ?>" class="btn btn-warning">
                        <span>Editar</span>
                    </a>
                    <?php else: ?>
                    <p>
                        <em class="small text-muted">Solo administradores pueden modificar el correo electronico o la contraseña de la credencial de usuario.</em>
                    </p>
                    <?php endif ?>
                    
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>