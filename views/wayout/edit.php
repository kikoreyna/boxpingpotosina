<?php $template->fill('content') ?>
<?php $template->insert('partials/validation') ?>
<?php $template->insert('partials/alert') ?>
<div class="row">
    <div class="col-sm">
        <form action="<?= url('salidas/actualizar/'.$wayout->id) ?>" method="post" class='card'>
            <div class="card-header">
                <p class="m-0 lead">Editar guia de salida</p>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="">Transportadora</label>
                    <select name="transportadora" id="" class="form-control">
                        <option label="Ninguna" selected></option>
                        <?php foreach($transports as $transport): ?>
                        <?php $selected = $transport->id === $wayout->transportadora_id ? 'selected' : '' ?>
                        <option value="<?= $transport->id ?>" <?= $selected ?>><?= $transport->nombre ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Número de rastreo</label>
                    <input name="rastreo" value="<?= $wayout->rastreo ?>"  type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Número de confirmación</label>
                    <input name="confirmacion" value="<?= $wayout->confirmacion ?>"  type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label class="d-block">Cobertura</label>
                    <div class="form-check form-check-inline">
                    <?php $checked_domicilio = $wayout->cobertura === 'domicilio' ? 'checked' : '' ?>
                    <input class="form-check-input" type="radio" name="cobertura" id="coverage_domicilio" value="domicilio" <?= $checked_domicilio ?>>
                    <label class="form-check-label" for="coverage_domicilio">Domicilio</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <?php $checked_ocurre = empty($checked_domicilio) ? 'checked' : '' ?>
                    <input class="form-check-input" type="radio" name="cobertura" id="coverage_ocurre" value="ocurre" <?= $checked_ocurre ?>>
                    <label class="form-check-label" for="coverage_ocurre">Ocurre</label>
                    </div>
                </div>
                <fieldset class="" id="address_ocurre" style="<?= empty($checked_ocurre) ? 'display:none' : '' ?>" <?= empty($checked_ocurre) ? 'disabled' : '' ?>>
                    <div class="form-row">
                        <div class="col-sm col-sm-8">
                            <div class="form-group">
                                <label for="">Dirección</label>
                                <input name="direccion" value="<?= $wayout->direccion ?>" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm col-sm-4">
                            <div class="form-group">
                                <label for="">Postal</label>
                                <input name="postal" value="<?= $wayout->postal ?>" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Ciudad</label>
                                <input name="ciudad" value="<?= $wayout->ciudad ?>" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Estado</label>
                                <input name="estado" value="<?= $wayout->estado ?>" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Pais</label>
                                <input name="pais" value="<?= $wayout->pais ?>" type="text" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <br>

                <div class="form-group">
                    <label for="">Status</label>
                    <select name="status" class="form-control">
                        <option label="Ninguno" selected></option>
                    <?php foreach($array_status as $status): ?>
                        <?php $selected = $status === $wayout->status ? 'selected' : '' ?>
                        <option value="<?= $status ?>" <?= $selected ?>><?= ucfirst($status) ?></option>
                    <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Incidente</label>
                    <select name="incidente" class="form-control">
                        <option label="Ninguno" selected></option>
                    <?php foreach($array_incidents as $incident): ?>
                        <?php $selected = $incident === $wayout->incidente ? 'selected' : '' ?>
                        <option value="<?= $incident ?>" <?= $selected ?>><?= ucfirst($incident) ?></option>
                    <?php endforeach ?>
                    </select>
                </div>
                <br>
                
                <button class="btn btn-warning" type="submit">Actualizar guia de salida</button>
                <a href="<?= url('entradas/mostrar/'.$entry->id) ?>" class="btn btn-secondary">Regresar</a>
            </div>
        </form>
    </div>

    <div class="col-sm col-sm-4">
        <?php $template->insert('wayout/template_summary_entry', compact('entry')) ?>
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>