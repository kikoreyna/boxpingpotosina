<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>

        <div class="card">
            <div class="card-header">
                <p class="lead">Cruce</p>
                <ul class="nav nav-tabs nav-fillx card-header-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#reporte" id="reporte-tab" data-toggle="tab" href="#reporte" role="tab" aria-controls="reporte" aria-selected="false">
                            <span class="mr-2">Reporte</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#conductores" id="conductores-tab" data-toggle="tab" href="#conductores" role="tab" aria-controls="conductores" aria-selected="true">
                            <span class="mr-2">Conductores</span>
                            <span class="badge badge-primary badge-pill"><?= count($drivers) ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#vehiculos" id="vehiculos-tab" data-toggle="tab" href="#vehiculos" role="tab" aria-controls="vehiculos" aria-selected="false">
                            <span class="mr-2">Vehiculos</span>
                            <span class="badge badge-primary badge-pill"><?= count($cars) ?></span>
                        </a>
                    </li>
                </ul>
            </div>


            <div class="tab-content" id="myTabContent">
                
                <!-- Reporte -->
                <div class="tab-pane fade active show" id="reporte" role="tabpanel" aria-labelledby="reporte-tab">
                    <div class="card-body">
                        <form action="<?= url('cruce') ?>" method="GET" class="form-row align-items-end">

                            <div class="col-sm">
                                <label class="font-weight-bold text-muted">Desde</label>
                                <div class="form-row">
                                    <div class="col-sm input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="desde_fecha-addon">Fecha</span>
                                        </div>
                                        <input type="date" name="desde_fecha" value="<?= $dates['desde_fecha'] ?>" class="form-control" placeholder="Desde" aria-label="Desde" aria-describedby="desde_fecha-addon">
                                    </div>
                                    <div class="col-sm input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="desde_hora-addon">Hora</span>
                                        </div>
                                        <input type="time" name="desde_hora" value="<?= $dates['desde_hora'] ?>" class="form-control" placeholder="Desde" aria-label="Desde" aria-describedby="desde_hora-addon">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm">
                                <label class="font-weight-bold text-muted">Hasta</label>
                                <div class="form-row">
                                    <div class="col-sm input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="hasta_fecha-addon">Fecha</span>
                                        </div>
                                        <input type="date" name="hasta_fecha" value="<?= $dates['hasta_fecha'] ?>" class="form-control" placeholder="Hasta" aria-label="Hasta" aria-describedby="hasta_fecha-addon">
                                    </div>
                                    <div class="col-sm input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="hasta_hora-addon">Hora</span>
                                        </div>
                                        <input type="time" name="hasta_hora" value="<?= $dates['hasta_hora'] ?>" class="form-control" placeholder="Hasta" aria-label="Hasta" aria-describedby="hasta_hora-addon">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm col-sm-2">
                                <button class="btn btn-info btn-block" style="margin-bottom:1rem" type="submit">Generar</button>
                            </div>
                        </form>
                    </div>

                    <div class="table-resposive">
                        <table class="table table-hover table-sm m-0">
                            <thead class="text-secondary small">
                                <tr>
                                    <th class="pl-3">CONDUCTOR</th>
                                    <th>VUELTAS</th>
                                    <th class="pr-3">GUIAS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($report as $item): ?>
                                <tr>
                                    <td class="pl-3"><?= $item['name'] ?></td>
                                    <td><?= $item['return_max'] ?></td>
                                    <td class="pr-3">
                                        <?php if( $item['entries_count'] ): ?>
                                        <?php
                                            $uri = [
                                                'desde' => $dates['desde_fecha'],
                                                'desde_hora' => $dates['desde_hora'],
                                                'hasta' => $dates['hasta_fecha'],
                                                'hasta_hora' => $dates['hasta_hora'],
                                                'conductor' => $item['id'],
                                            ];
                                        ?>
                                        <a href='<?= url('entradas', $uri)?>' target="_blank"><?= $item['entries_count'] ?></a>
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

                <!-- Conductores -->
                <div class="tab-pane fade" id="conductores" role="tabpanel" aria-labelledby="conductores-tab">
                    <div class="card-body py-2 text-right">
                        <a href="<?= url('conductores/nuevo') ?>" class="btn btn-primary btn-sm">Nuevo conductor</a>
                    </div>

                    <?php if( count($drivers) ): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm m-0">
                            <thead class="text-secondary small">
                                <tr>
                                    <th class="border-0 pl-3">NOMBRE</th>
                                    <th class="border-0"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($drivers as $d): ?>
                                <tr>
                                    <td class="pl-3"><?= $d->nombre ?></td>
                                    <td class="pr-3 text-right">
                                        <a href="<?= url('conductores/editar/'.$d->id) ?>" class="btn btn-warning btn-sm">
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

                <!-- Vehiculos -->
                <div class="tab-pane fade" id="vehiculos" role="tabpanel" aria-labelledby="vehiculos-tab">
                    <div class="card-body py-2 text-right">
                        <a href="<?= url('vehiculos/nuevo') ?>" class="btn btn-primary btn-sm">Nuevo vehiculo</a>
                    </div>

                    <?php if( count($cars) ): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm m-0">
                            <thead class="text-secondary small">
                                <tr>
                                    <th class="border-0 pl-3">ALIAS (<small>Nombre</small>)</th>
                                    <th class="border-0">DESCRIPCION</th>
                                    <th class="border-0"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($cars as $c): ?>
                                <tr>
                                    <td class="pl-3"><?= $c->alias ?></td>
                                    <td><?= $c->descripcion ?></td>
                                    <td class="pr-3 text-right">
                                        <a href="<?= url('vehiculos/editar/'.$c->id) ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a href="<?= url('vehiculos/borrar/'.$c->id) ?>" class="btn btn-danger btn-sm">
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