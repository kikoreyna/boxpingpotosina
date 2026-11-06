<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>

<?php if( $consolidated_alert ): ?>
<div class="alert alert-danger" role="alert">
    <h4 class="alert-heading lead">Número de consolidado no existe</h4>
    <hr>
    <p class="m-0">Las guias de entrada se guardaran <b>sin consolidar</b>.</p>
</div>
<?php endif ?>

<div class="card">
    <div class="card-header">
        <p class="lead">Entradas</p>
        <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
          <li class="nav-item ml-2">
            <a class="nav-link active" id="multiple-tab" data-toggle="tab" href="#multiple" role="tab" aria-controls="multiple" aria-selected="true">
                <b class="small">MULTIPLE</b>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="solouna-tab" data-toggle="tab" href="#solouna" role="tab" aria-controls="solouna" aria-selected="false">
                <b class="small">SOLO UNA</b>
            </a>
          </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="myTabContent">
         
            <!-- Multiple -->
            <div class="tab-pane fade show active" id="multiple" role="tabpanel" aria-labelledby="multiple-tab">
                <p class="text-right">
                    <a href="<?= url('descargar/plantilla_csv') ?>" target="_blank" class="btn btn-info btn-sm">
                        <i class="fas fa-file-download"></i>
                        <span class="align-middle small ml-1">DESCARGAR PLANTILLA CSV</span>
                    </a>
                </p>
                <form action="<?= url('entradas/validacion_csv') ?>" id="form_csv_x" method="post" enctype="multipart/form-data">

                    <?php if( $consolidated ): ?>
                    <div class="form-group">
                        <label class="text-muted">Número de consolidado</label>
                        <input type="text" class="form-control" name="numero_consolidado" value="<?= $consolidated->alias_cliente_numero ? stick($consolidated->cliente_alias, $consolidated->numero) : $consolidated->numero ?>" disabled>
                        <input type="hidden" name="consolidado" value="<?= $consolidated->id ?>">
                    </div>
                    <div class="form-group">
                        <label class="text-muted">Cliente</label>
                        <input type="text" class="form-control" name="cliente_nombre" value="<?= $consolidated->cliente_nombre ?> (<?= $consolidated->cliente_alias ?>)" disabled>
                        <input type="hidden" name="cliente" value="<?= $consolidated->cliente_id ?>">
                    </div>

                    <?php else: ?>
                    <div class="form-group">
                        <label class="">Cliente</label>
                        <select name="cliente" class="form-control" required>
                            <option selected disabled></option>
                            <?php foreach($options_clients as $client): ?>
                            <option value="<?= $client->id ?>"><?= $client->nombre ?> (<?= $client->alias ?>)</option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <?php endif ?>

                    <div class="form-group m-0">
                        <label for="">Seleccionar archivo *.csv</label>
                        <div class="custom-file">
                            <input type="file" class="form-control" name="csv" id="csv" acept="*.csv" required>
                            <label class="custom-file-label text-muted d-none" for="csv">Solo archivos con extension CSV</label>
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="alias_numero" value="1" id="alias_numero" checked>                        
                        <label class="form-check-label small" for="alias_numero">
                            <span>Usar alias del cliente antes del número de guia de entrada.</span>
                            <em class="text-muted small">Ejemplo: ALIAS#####</em>
                        </label>
                    </div>
                    
                    <button class="btn btn-primary" type="submit">Validar archivo .csv</button>
                    <a href="<?= url($redirect) ?>" class="btn btn-secondary">Regresar</a>
                    <button class="d-none" type="button" id="btnPreviewModal" data-toggle="modal" data-target="#previewModal"></button>
                </form>
            </div>
            
          
            <!-- Una -->
            <div class="tab-pane fade" id="solouna" role="tabpanel" aria-labelledby="solouna-tab">
                <form action="<?= url('entradas/guardar') ?>" method="post">

                    <?php if( $consolidated ): ?>
                    <div class="form-group">
                        <label class="text-muted">Número de consolidado</label>
                        <input type="text" class="form-control" name="numero_consolidado" value="<?= $consolidated->alias_cliente_numero ? stick($consolidated->cliente_alias, $consolidated->numero) : $consolidated->numero ?>" disabled>
                        <input type="hidden" name="consolidado" value="<?= $consolidated->id ?>">
                    </div>
                    <div class="form-group">
                        <label class="text-muted">Cliente</label>
                        <input type="text" class="form-control" name="cliente_nombre" value="<?= $consolidated->cliente_nombre ?> (<?= $consolidated->cliente_alias ?>)" disabled>
                        <input type="hidden" name="cliente" value="<?= $consolidated->cliente_id ?>">
                    </div>

                    <?php else: ?>
                    <div class="form-group">
                        <label class="">Cliente</label>
                        <select name="cliente" class="form-control" required>
                            <option selected disabled></option>
                            <?php foreach($options_clients as $client): ?>
                            <option value="<?= $client->id ?>"><?= $client->nombre ?> (<?= $client->alias ?>)</option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <?php endif ?>

                    <div class="form-group mb-0">
                        <label for="">Número de guia</label>
                        <input type="text" class="form-control" name="numero" required>
                    </div>

                    <div class="form-check mb-5">
                        <input class="form-check-input" type="checkbox" name="alias_numero" value="1" id="alias_numero" checked>
                        <label class="form-check-label small" for="alias_numero">
                            <span>Usar alias del cliente antes del número de guia de entrada.</span>
                            <em class="text-muted">Ejemplo: ALIAS#####</em>
                        </label>
                    </div>

                    <p class="lead">Medidas</p>
                    <div class="row mb-5">
                        <div class="col-sm col-sm-6">
                            <div class="form-group">
                                <label for="">Peso</label>
                                <input type="number" step="0.01" min="0.01" class="form-control" name="peso">
                            </div>
                        </div>
                        <div class="col-sm col-sm-6">
                            <div class="form-group">
                                <label for="">Medida de peso</label>
                                <select name="medida_peso" class="form-control">
                                    <?php foreach($options_measure['peso'] as $option): ?>
                                    <option value="<?= $option ?>"><?= ucfirst($option) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm col-sm-2">
                            <div class="form-group">
                                <label for="">Ancho</label>
                                <input type="number" step="0.01" min="0.01" class="form-control" name="ancho">
                            </div>
                        </div>
                        <div class="col-sm col-sm-2">
                            <div class="form-group">
                                <label for="">Altura</label>
                                <input type="number" step="0.01" min="0.01" class="form-control" name="altura">
                            </div>
                        </div>
                        <div class="col-sm col-sm-2">
                            <div class="form-group">
                                <label for="">Profundidad</label>
                                <input type="number" step="0.01" min="0.01" class="form-control" name="profundidad">
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Medida de volúmen</label>
                                <select name="medida_volumen" class="form-control">
                                <?php foreach($options_measure['volumen'] as $option): ?>
                                    <option value="<?= $option ?>"><?= ucfirst($option) ?></option>
                                <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <p class="lead">Remitente</p>
                    <div class="row mb-5">
                        <div class="col-sm col-sm-8">
                            <div class="form-group">
                                <label for="">Nombre</label>
                                <input type="text" class="form-control" name="remitente[nombre]">
                            </div>
                        </div>
                        <div class="col-sm col-sm-4">
                            <div class="form-group">
                                <label for="">Teléfono</label>
                                <input type="text" class="form-control" name="remitente[telefono]">
                            </div> 
                        </div>
                        <div class="col-sm col-sm-8">
                            <div class="form-group">
                                <label for="">Direccion</label>
                                <input type="text" class="form-control" name="remitente[direccion]">
                            </div>
                        </div>
                        <div class="col-sm col-sm-4">
                            <div class="form-group">
                                <label for="">Codigo postal</label>
                                <input type="text" class="form-control" name="remitente[postal]">
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Ciudad</label>
                                <input type="text" class="form-control" name="remitente[ciudad]">
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Estado</label>
                                <input type="text" class="form-control" name="remitente[estado]">
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Pais</label>
                                <input type="text" class="form-control" name="remitente[pais]" value="USA">
                            </div>
                        </div>
                    </div>
                    
                    <p class="lead">Destinatario</p>
                    <div class="row mb-3">
                        <div class="col-sm col-sm-8">
                            <div class="form-group">
                                <label for="">Nombre</label>
                                <input type="text" class="form-control" name="destinatario[nombre]">
                            </div>
                        </div>
                        <div class="col-sm col-sm-4">
                            <div class="form-group">
                                <label for="">Teléfono</label>
                                <input type="text" class="form-control" name="destinatario[telefono]">
                            </div> 
                        </div>
                        <div class="col-sm col-sm-8">
                            <div class="form-group">
                                <label for="">Direccion</label>
                                <input type="text" class="form-control" name="destinatario[direccion]">
                            </div>
                        </div>
                        <div class="col-sm col-sm-4">
                            <div class="form-group">
                                <label for="">Codigo postal</label>
                                <input type="text" class="form-control" name="destinatario[postal]">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="">Referencias</label>
                                <textarea class="form-control" rows="5" name="destinatario[referencias]"></textarea>
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Ciudad</label>
                                <input type="text" class="form-control" name="destinatario[ciudad]">
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Estado</label>
                                <input type="text" class="form-control" name="destinatario[estado]">
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Pais</label>
                                <input type="text" class="form-control" name="destinatario[pais]" value="Mexico">
                            </div>
                        </div>
                    </div>
                    
                    <button class="btn btn-success" type="submit">Guardar entrada</button>
                    <a href="<?= url($redirect) ?>" class="btn btn-secondary">Regresar</a>
                </form>
            </div>
            
        </div><!-- End Tab content -->
    </div><!-- End Card body -->
</div><!-- End Card -->

<?php // $template->insert('modals/preview') ?>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>