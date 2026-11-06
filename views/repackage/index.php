<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<div class="card">
    <div class="card-header">
        <p class="lead">Reempaque</p>
        <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="reporte-tab" data-toggle="tab" href="#reporte" role="tab" aria-controls="reporte" aria-selected="true">
                    <span class="mr-2">Reporte</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="reempacadores-tab" data-toggle="tab" href="#reempacadores" role="tab" aria-controls="reempacadores" aria-selected="true">
                    <span class="mr-2">Reempacadores</span>
                    <span class="badge badge-primary badge-pill"><?= count($repackers) ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="codigos-tab" data-toggle="tab" href="#codigos" role="tab" aria-controls="codigos" aria-selected="false">
                    <span class="mr-2">Códigos de reempacado</span>
                    <span class="badge badge-primary badge-pill"><?= count($codesr) ?></span>
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="myTabContent">

        <!-- Reporte -->
        <div class="tab-pane fade show active" id="reporte" role="tabpanel" aria-labelledby="reporte-tab">
            <div class="card-body">
                <form action="<?= url('reempaque') ?>" method="GET" class="form-row">
                    <div class="col-sm input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="desde-addon">Desde</span>
                        </div>
                        <input type="date" name="desde" value="<?= $dates['desde'] ?>" class="form-control" placeholder="Desde" aria-label="Desde" aria-describedby="desde-addon">
                    </div>
                    <div class="col-sm input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="hasta-addon">Hasta</span>
                        </div>
                        <input type="date" name="hasta" value="<?= $dates['hasta'] ?>" class="form-control" placeholder="Hasta" aria-label="Hasta" aria-describedby="hasta-addon">
                    </div>
                    <div class="col-sm col-sm-2">
                        <button class="btn btn-info btn-block" type="submit">Generar</button>
                    </div>
                </form>
            </div>

            <div class="table-resposive">
                <table class="table table-hover table-sm m-0">
                    <thead class="text-secondary small">
                        <tr>
                            <th class="pl-3">REEMPACADOR</th>
                            <th class="pr-3">TOTAL DE REEMPAQUES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($report as $item): ?>
                        <tr>
                            <td class="pl-3"><?= $item['name'] ?></td>
                            <td class="pr-3">
                                <?php if( $item['entries_count'] ): ?>
                                <a href="<?= url('entradas', ['desde' => $dates['desde'], 'hasta' => $dates['hasta'], 'reempacador' => $item['id']]) ?>" target="_blank"><?= $item['entries_count'] ?></a>
                                <?php else: ?>
                                <span>0</span>
                                <?php endif ?>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Reempacadores -->
        <div class="tab-pane fade" id="reempacadores" role="tabpanel" aria-labelledby="reempacadores-tab">
            <div class="card-body py-2">
                <div class="text-right">
                    <a href="<?= url('reempacadores/nuevo') ?>" class="btn btn-primary btn-sm">Nuevo reempacador</a>
                </div>
            </div>

            <?php if( count($repackers) ): ?>
            <div class="table-responsive">
                <table class="table table-hover table-sm m-0">
                    <thead class="text-secondary small">
                        <tr>
                            <th class="border-0 pl-3">NOMBRE</th>
                            <th class="border-0"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($repackers as $repacker): ?>
                        <tr>
                            <td class="text-nowrap pl-3"><?= $repacker->nombre ?></td>
                            <td class="text-nowrap text-right pr-3">
                                <a href="<?= url("reempacadores/editar/{$repacker->id}") ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-pencil-alt"></i>
                                </a> 
                                <a href="<?= url("reempacadores/borrar/{$repacker->id}") ?>" class="btn btn-sm btn-danger d-none">
                                    <i class="fas fa-trash-alt"></i>
                                </a> 
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <?php endif ?>
        </div>

        <!-- Codigos de reempacado -->
        <div class="tab-pane fade" id="codigos" role="tabpanel" aria-labelledby="codigos-tab">
            <div class="card-body py-2">
                <div class="text-right">
                    <a href="<?= url('codigos_reempacado/nuevo') ?>" class="btn btn-primary btn-sm">Nuevo codigo</a>
                </div>
            </div>
            <?php if( count($codesr) ): ?>
            <div class="table-responsive">
                <table class="table table-hover table-sm m-0">
                    <thead class="text-secondary small">
                        <tr>
                            <th class="border-0 pl-3">CODIGO</th>
                            <th class="border-0">DESCRIPCION</th>
                            <th class="border-0"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($codesr as $coder): ?>
                        <tr>
                            <td class="text-nowrap pl-3"><?= $coder->codigo ?></td>
                            <td class="small"><?= $coder->descripcion ?></td>
                            <td class="text-nowrap text-right pr-3">
                                <a href="<?= url("codigos_reempacado/editar/{$coder->id}") ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-pencil-alt"></i>
                                </a> 
                                <a href="<?= url("codigos_reempacado/borrar/{$coder->id}") ?>" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash-alt"></i>
                                </a> 
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>  
            <?php endif ?>
        </div>
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>