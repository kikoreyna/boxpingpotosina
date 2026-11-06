<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>

<div class="card">
    <div class="card-header">
        <div class='float-right'>
            <a href="<?= url('transportadoras/nuevo') ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="right" title="Nuevo transportadora">
                <i class="fas fa-plus"></i>
            </a>
        </div>
        <p class="m-0 lead">Tranportadoras <span class="badge badge-primary badge-pill"><?= count($transports) ?></span></p>
    </div>

    <?php if( $transports ): ?>
        <div class="table-responsive">
            <table class="table table-hover table-sm m-0">
                <thead class="text-secondary small">
                    <tr>
                        <th class="border-0 pl-3">NOMBRE</th>
                        <th class="border-0">WEB</th>
                        <th class="border-0">TELEFONOS</th>
                        <th class="border-0"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($transports as $transport): ?>
                    <tr>
                        <td class="pl-3"><?= $transport->nombre ?></td>
                        <td>
                            <a href="<?= $transport->web ?>" target="_blank"><?= $transport->web ?></a>
                        </td>
                        <td>
                            <span><?= $transport->telefonos ?></span>
                        </td>
                        <td class="text-right pr-3">
                            <a href="<?= url('transportadoras/editar/'.$transport->id) ?>" class="btn btn-warning btn-sm">
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