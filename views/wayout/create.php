<?php $template->fill('content') ?>
<?php $template->insert('partials/validation') ?>
<div class="row">
    <div class="col-sm">
        <form action="<?= url('salidas/guardar') ?>" method="post" class="card">
            <div class="card-header">
                <span class="lead">Crear salida</span>
            </div>
            <div class="card-body">     
                <div class="form-group">
                    <label for="">Transportadoras</label>
                    <select name="transportadora" id="" class="form-control">
                        <option disabled selected></option>
                        <?php foreach($transports as $transport): ?>
                        <option value="<?= $transport->id ?>"><?= $transport->nombre ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Número de rastreo</label>
                    <input name="rastreo" type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Número de confirmación</label>
                    <input name="confirmacion" type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label class="d-block">Cobertura</label>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="cobertura" id="coverage_domicilio" value="domicilio" checked>
                    <label class="form-check-label" for="coverage_domicilio">Domicilio</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="cobertura" id="coverage_ocurre" value="ocurre">
                    <label class="form-check-label" for="coverage_ocurre">Ocurre</label>
                    </div>
                </div>
                <fieldset class="" id="address_ocurre" style="display:none" disabled>
                    <div class="form-row">
                        <div class="col-sm col-sm-8">
                            <div class="form-group">
                                <label for="">Dirección</label>
                                <input name="direccion" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm col-sm-4">
                            <div class="form-group">
                                <label for="">Postal</label>
                                <input name="postal" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Ciudad</label>
                                <input name="ciudad" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Estado</label>
                                <input name="estado" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Pais</label>
                                <input name="pais" value="Mexico" type="text" class="form-control" value="Mexico" required>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <br>
                
                <input type="hidden" name="entrada" value="<?= $entry->id ?>">
                <button class="btn btn-success" type="submit">Guardar salida</button>
                <a href="<?= url('entradas/mostrar/'.$entry->id) ?>" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <!-- GUIDA DE ENTRADA -->
    <div class="col-sm col-sm-4">
        <?php $template->insert('wayout/template_summary_entry', compact('entry')) ?>
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>
