<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>
<div class="card">
    <div class="card-header mb-3">
        <div class="float-right">
            <a href="<?= url('entradas/mostrar/'.$entry->id) ?>" class="btn btn-secondary btn-sm">Regresar</a>
        </div>
        <p class="m-0 lead">Editar entrada</p>
    </div>

    <!-- TABS -->
    <ul class="nav nav-pills nav-tabs-x pl-3" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="guide-tab" data-toggle="tab" href="#guide" role="tab" aria-controls="guide" aria-selected="true">Guia</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="measures-tab" data-toggle="tab" href="#measures" role="tab" aria-controls="measures" aria-selected="false">Medidas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="remitter-tab" data-toggle="tab" href="#remitter" role="tab" aria-controls="remitter" aria-selected="false">Remitente</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="addressee-tab" data-toggle="tab" href="#addressee" role="tab" aria-controls="addressee" aria-selected="false">Destinatario</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="process-tab" data-toggle="tab" href="#process" role="tab" aria-controls="process" aria-selected="false">Proceso</a>
        </li>
    </ul>

    <div class="card-body">
        <div class="tab-content" id="myTabContent">
         
            <!-- ENTRADA -->
          <div class="tab-pane fade show active" id="guide" role="tabpanel" aria-labelledby="guide-tab">
            <form action="<?= url('entradas/actualizar/'.$entry->id) ?>" method="post">

                <div class="form-group">
                    <label for="">Número de guia</label>
                    <input type="text" class="form-control" name="numero" value="<?= $entry->numero ?>" required>
                </div>

                <?php if( is_null($entry->consolidado_id) ): ?>
                <div class="form-group m-0">
                    <label for="">Cliente</label>
                    <select name="cliente" class="form-control">
                        <?php foreach($clients as $client): ?>
                        <?php $selected = $client->id !== $entry->cliente_id ?: 'selected' ?>
                        <option value="<?= $client->id ?>" <?= $selected ?>><?= $client->nombre ?> (<?= $client->alias ?>)</option>
                        <?php endforeach ?>
                    </select>
                </div>
                
                <?php else: ?>
                <div class="form-group m-0">
                    <label for="">Cliente</label>
                    <input type="text" class="form-control" value="<?= $entry->cliente_nombre ?> (<?= $entry->cliente_alias ?>)" disabled>
                    <input type="hidden" name="cliente" value="<?= $entry->cliente_id ?>">
                </div>
                <?php endif ?>

                <div class="form-check">
                    <?php $checked = $entry->alias_cliente_numero ? 'checked' : '' ?>
                    <input class="form-check-input" type="checkbox" name="alias_numero" value="1" id="alias_numero" <?= $checked ?>>
                    <label class="form-check-label" for="alias_numero">
                        <span>Usar alias del cliente antes del número de guia de entrada.</span>
                        <em class="text-muted small">Ejemplo: ALIAS#####</em>
                    </label>
                </div>

                <br>
                <button class="btn btn-warning" type="submit">Actualizar guia</button>
            </form>
          </div>
          
          <!-- MEDIDAS -->
          <div class="tab-pane fade show" id="measures" role="tabpanel" aria-labelledby="measures-tab">
            <p class="lead d-none">Medidas</p>
            <form action="<?= url('medidas/actualizar/'.$entry->id) ?>" method="post">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead>
                            <tr>
                                <th class="border-bottom-0">Etapa</th>
                                <th class="border-bottom-0" colspan="2">Peso</th>
                                <th class="border-bottom-0" colspan="4">Volúmen</th>
                                <th class="border-bottom-0">Actualizacion</th>
                                <th class="border-bottom-0">Usuario</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($stages as $stage): 
                                $result = array_filter($measures, function ($item) use ($stage) {
                                    return $item->etapa === $stage;
                                });
                                $measure = array_pop($result);
                            ?>
                            <tr>
                                <td class="bg-light text-capitalize text-nowrap align-middle"><?= str_replace('_', ' ', $stage)?></td>
                                <td>
                                    <input type="hidden" name="<?= $stage ?>[id]" value="<?= $measure->id ?>">
                                    <input type="hidden" name="<?= $stage ?>[etapa]" value="<?= $measure->etapa ?>">
                                    <input name="<?= $stage?>[peso]" value="<?= $measure->peso ?>" type="number" step="0.01" min="0" class="form-control">
                                </td>
                                <td>
                                    <select name="<?= $stage ?>[medida_peso]" class="form-control">
                                        <?php foreach($options_measure['peso'] as $option): ?>
                                        <?php $selected = $option === $measure->medida_peso ? 'selected' : '' ?>
                                        <option value="<?= $option ?>" <?= $selected ?>><?= ucfirst($option) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="">Ancho</span>
                                        </div>
                                        <input name="<?= $stage ?>[ancho]" value="<?= $measure->ancho ?>" type="number" step="0.01" min="0" class="form-control">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="">Altura</span>
                                        </div>
                                        <input name="<?= $stage ?>[altura]" value="<?= $measure->altura ?>" type="number" step="0.01" min="0" class="form-control">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="">Profundidad</span>
                                        </div>
                                        <input name="<?= $stage ?>[profundidad]" value="<?= $measure->profundidad ?>" type="number" step="0.01" min="0" class="form-control">
                                    </div>
                                </td>
                                <td>
                                    <select name="<?= $stage ?>[medida_volumen]" class="form-control">
                                        <?php foreach($options_measure['volumen'] as $option): ?>
                                        <?php $selected = $option === $measure->medida_volumen ? 'selected' : '' ?>
                                        <option value="<?= $option ?>" <?= $selected ?>><?= ucfirst($option) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </td>
                                <td class="small"><?= $measure->updated_at ?></td>
                                <td class="text-nowrap"><?= $measure->usuario_nombre ?></td>
                                <?php endforeach ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button class="btn btn-warning" type="submit">Actualizar medidas</button>
            </form>
          </div>

          <!-- REMITENTE -->
          <div class="tab-pane fade" id="remitter" role="tabpanel" aria-labelledby="remitter-tab">
              <p class="lead d-none">Remitente</p>
              <form action="<?= url('remitentes/actualizar/'.$entry->remitente_id) ?>" method="post">
                  <div class="row">
                      <div class="col-sm col-sm-8">
                          <div class="form-group">
                              <label for="">Nombre</label>
                              <input type="text" class="form-control" name="nombre" value="<?= $entry->remitente_nombre ?>">
                          </div>
                      </div>
                      <div class="col-sm col-sm-4">
                          <div class="form-group">
                              <label for="">Teléfono</label>
                              <input type="text" class="form-control" name="telefono" value="<?= $entry->remitente_telefono ?>">
                          </div> 
                      </div>
                      <div class="col-sm col-sm-8">
                          <div class="form-group">
                              <label for="">Direccion</label>
                              <input type="text" class="form-control" name="direccion" value="<?= $entry->remitente_direccion ?>">
                          </div>
                      </div>
                      <div class="col-sm col-sm-4">
                          <div class="form-group">
                              <label for="">Codigo postal</label>
                              <input type="text" class="form-control" name="postal" value="<?= $entry->remitente_postal ?>">
                          </div>
                      </div>
                      <div class="col-sm">
                          <div class="form-group">
                              <label for="">Ciudad</label>
                              <input type="text" class="form-control" name="ciudad" value="<?= $entry->remitente_ciudad ?>">
                          </div>
                      </div>
                      <div class="col-sm">
                          <div class="form-group">
                              <label for="">Estado</label>
                              <input type="text" class="form-control" name="estado" value="<?= $entry->remitente_estado ?>">
                          </div>
                      </div>
                      <div class="col-sm">
                          <div class="form-group">
                              <label for="">Pais</label>
                              <input type="text" class="form-control" name="pais" value="<?= $entry->remitente_pais ?>">
                          </div>
                      </div>
                  </div>
                  <button class="btn btn-warning" type="submit">Actualizar remitente</button>
              </form>
          </div>
          
          <!-- DESTINATARIO -->
          <div class="tab-pane fade" id="addressee" role="tabpanel" aria-labelledby="addressee-tab">
              <p class="lead d-none">Destinatario</p>
              <form action="<?= url('destinatarios/actualizar/'.$entry->destinatario_id) ?>" method="post">
                  <div class="row">
                      <div class="col-sm col-sm-8">
                          <div class="form-group">
                              <label for="">Nombre</label>
                              <input type="text" class="form-control" name="nombre" value="<?= $entry->destinatario_nombre ?>">
                          </div>
                      </div>
                      <div class="col-sm col-sm-4">
                          <div class="form-group">
                              <label for="">Teléfono</label>
                              <input type="text" class="form-control" name="telefono" value="<?= $entry->destinatario_telefono ?>">
                          </div> 
                      </div>
                      <div class="col-sm col-sm-8">
                          <div class="form-group">
                              <label for="">Direccion</label>
                              <input type="text" class="form-control" name="direccion" value="<?= $entry->destinatario_direccion ?>">
                          </div>
                      </div>
                      <div class="col-sm col-sm-4">
                          <div class="form-group">
                              <label for="">Codigo postal</label>
                              <input type="text" class="form-control" name="postal" value="<?= $entry->destinatario_postal ?>" required>
                          </div>
                      </div>
                      <div class="col-sm-12">
                          <div class="form-group">
                              <label for="">Referencias</label>
                              <textarea name="referencias" id="" class="form-control"><?= $entry->destinatario_referencias ?></textarea>
                          </div>
                      </div>
                      <div class="col-sm">
                          <div class="form-group">
                              <label for="">Ciudad</label>
                              <input type="text" class="form-control" name="ciudad" value="<?= $entry->destinatario_ciudad ?>">
                          </div>
                      </div>
                      <div class="col-sm">
                          <div class="form-group">
                              <label for="">Estado</label>
                              <input type="text" class="form-control" name="estado" value="<?= $entry->destinatario_estado ?>">
                          </div>
                      </div>
                      <div class="col-sm">
                          <div class="form-group">
                              <label for="">Pais</label>
                              <input type="text" class="form-control" name="pais" value="<?= $entry->destinatario_pais ?>" required>
                          </div>
                      </div>
                  </div>
                  
                  <div class="alert alert-warning">
                    <div class="form-check">
                        <?php $checked = !is_null($entry->destinatario_verificacion_at) ? 'checked' : '' ?>
                        <input class="form-check-input" type="checkbox" name="verificacion" value="1" id="check_addressee" <?= $checked ?>>
                        <label class="form-check-label" for="check_addressee">La verificación del destinatario se ha realizado correctamente</label>
                    </div>
                  </div>

                  <button class="btn btn-warning" type="submit">Actualizar destinatario</button>
              </form>
          </div>

          <!-- PROCESO -->
          <div class="tab-pane fade show" id="process" role="tabpanel" aria-labelledby="process-tab">
            <p class="lead d-none">Proceso</p>
            <form action="<?= url('entradas/actualizar/'.$entry->id) ?>" method="post">

                <!-- Bodega USA -->
                <div class="form-group">
                    <?php 
                    $recibido_date = '';
                    $recibido_time = '';
                    if( !is_null($entry->recibido_at) )
                        list($recibido_date, $recibido_time) = explode(' ', $entry->recibido_at);
                    ?>
                    <label for="">Recibido</label>
                    <div class="d-flex">
                        <input type="date" class="form-control mr-2" name="recibido_date" value="<?= $recibido_date ?>">
                        <input type="time" class="form-control" step="any" name="recibido_time" value="<?= $recibido_time ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Bodega USA</label>
                    <select name="bodega_usa" class="form-control">
                        <option disabled selected></option>

                        <?php foreach($options_warehouse_usa as $user_usa): ?>
                        <?php $selected = $user_usa->id === $entry->en_bodega_usa_by ? 'selected' : '' ?>
                        <option value="<?= $user_usa->id ?>" <?= $selected ?>><?= $user_usa->nombre ?></option>
                        <?php endforeach ?>

                    </select>
                </div>

                <!-- Cruce -->
                <hr class="my-5">
                <div class="form-group">
                    <label for="">Conductor</label>
                    <select name="conductor" class="form-control">
                        <option disabled selected></option>

                        <?php foreach($options_drivers as $driver): ?>
                        <?php $selected = $driver->id === $entry->conductor_id ? 'selected' : '' ?>
                        <option value="<?= $driver->id ?>" <?= $selected ?>><?= $driver->nombre ?></option>
                        <?php endforeach ?>

                    </select>
                </div>
                <div class="form-group">
                    <label for="">Vehículo</label>
                    <select name="vehiculo" class="form-control">
                        <option disabled selected></option>

                        <?php foreach($options_cars as $car): ?>
                        <?php $selected = $car->id === $entry->vehiculo_id ? 'selected' : '' ?>
                        <option value="<?= $car->id ?>" <?= $selected ?>><?= $car->alias ?></option>
                        <?php endforeach ?>

                    </select>
                </div>
                <div class="form-group">
                    <label for="">Numero de vuelta</label>
                    <input type="number" name="numero_vuelta" value="<?= $entry->numero_vuelta ? $entry->numero_vuelta : '' ?>" class="form-control" step="1" min="1">
                </div>
                <div class="form-group">
                    <?php 
                    $cruce_date = '';
                    $cruce_time = '';
                    if( !is_null($entry->cruce_at) )
                        list($cruce_date, $cruce_time) = explode(' ', $entry->cruce_at);
                    ?>
                    <label for="">Fecha y hora de cruce</label>
                    <div class="d-flex">
                        <input type="date" class="form-control mr-2" name="cruce_date" value="<?= $cruce_date ?>">
                        <input type="time" class="form-control" name="cruce_time" value="<?= $cruce_time ?>" step="any">
                    </div>
                </div>

                <!-- Bodega MEX -->
                <hr class="my-5">
                <div class="form-group">
                    <label for="">Bodega MEX</label>
                    <select name="bodega_mex" class="form-control">
                        <option disabled selected></option>

                        <?php foreach($options_warehouse_mex as $user_mex): ?>
                        <?php $selected = $user_mex->id === $entry->en_bodega_mex_by ? 'selected' : '' ?>
                        <option value="<?= $user_mex->id ?>" <?= $selected ?>><?= $user_mex->nombre ?></option>
                        <?php endforeach ?>

                    </select>
                </div>

                <!-- Reempacado -->
                <hr class="my-5">
                <div class="form-group">
                    <label for="">Reempacador</label>
                    <select name="reempacador" class="form-control">
                        <option disabled selected></option>

                        <?php foreach($options_repackers as $repackaged): ?>
                        <?php $selected = $repackaged->id === $entry->reempacador_id ? 'selected' : '' ?>
                        <option value="<?= $repackaged->id ?>" <?= $selected ?>><?= $repackaged->nombre ?></option>
                        <?php endforeach ?>

                    </select>
                </div>
                <div class="form-group">
                    <label for="">Código de reempacado</label>
                    <select name="codigo_reempacado" class="form-control">
                        <option disabled selected></option>

                        <?php foreach($options_codesr as $coder): ?>
                        <?php $selected = $coder->id === $entry->codigor_id ? 'selected' : '' ?>
                        <option value="<?= $coder->id ?>" <?= $selected ?>><?= $coder->codigo ?> (<?= $coder->descripcion ?>)</option>
                        <?php endforeach ?>

                    </select>
                </div>
                <div class="form-group">
                    <?php 
                    $reempacado_date = '';
                    $reempacado_time = '';
                    if( !is_null($entry->reempacado_at) )
                        list($reempacado_date, $reempacado_time) = explode(' ', $entry->reempacado_at);
                    ?>
                    <label for="">Fecha y hora de reempacado</label>
                    <div class="d-flex">
                        <input type="date" class="form-control mr-2" name="reempacado_date" value="<?= $reempacado_date ?>">
                        <input type="time" class="form-control" step="any" name="reempacado_time" value="<?= $reempacado_time ?>">
                    </div>
                </div>

                <button class="btn btn-warning" type="submit">Actualizar proceso</button>
            </form>
          </div>
        </div>     
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>