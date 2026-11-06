<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>
<div class="card mb-3">
    <div class="card-header">
        <span class="lead">Bodega MEX | <?= ucfirst($coming) ?></span>
    </div>
    <div class="card-body">
        <form action="<?= url("bodega/actualizar/{$measure_entry_mex->id}/{$coming}") ?>" method="post">
            <p class="text-right">
                <span class="d-block ont-weight-bold"><?= $entry->numero ?></span>
                <span class="text-secondary small">Número de guia</span>
            </p>

            <p class="font-weight-bold">Pesaje</p>
            <div class="form-row mb-3">
                <div class="col-sm form-group">
                    <label for="">Peso</label>
                    <input class="form-control" type="number" step="0.001" min="0.000" name="peso" value="<?= $measure_entry_mex->peso ?>" required>
                </div>
                <div class="col-sm form-group">
                    <label for="">Medida de peso</label>
                    <select name="medida_peso" id="medida_peso" class="form-control" required>
                        <?php foreach($measures_types['weight'] as $weight_type): ?>
                        <?php $selected = $measure_entry_mex->medida_peso === $weight_type ? 'selected' : '' ?>
                        <option value="<?= $weight_type ?>" <?= $selected ?>><?= ucfirst($weight_type) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>

            <p class="font-weight-bold">Volumen</p>
            <div class="form-row mb-3">
                <div class="col-sm form-group">
                    <label for="">Ancho</label>
                    <input class="form-control" type="number" step="0.001" min="0.000" name="ancho" value="<?= $measure_entry_mex->ancho ?>">
                </div>
                <div class="col-sm form-group">
                    <label for="">Altura</label>
                    <input class="form-control" type="number" step="0.001" min="0.000" name="altura" value="<?= $measure_entry_mex->altura ?>">
                </div>
                <div class="col-sm form-group">
                    <label for="">Profundidad</label>
                    <input class="form-control" type="number" step="0.001" min="0.000" name="profundidad" value="<?= $measure_entry_mex->profundidad ?>">
                </div>
                <div class="col-sm form-group">
                    <label for="">Medida de volumen</label>
                    <select name="medida_volumen" id="medida_volumen" class="form-control">
                        <?php foreach($measures_types['volume'] as $volume_type): ?>
                        <?php $selected = $measure_entry_mex->medida_volumen === $volume_type ? 'selected' : '' ?>
                        <option value="<?= $volume_type ?>" <?= $selected ?>><?= ucfirst($volume_type) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>

            <input type="hidden" name="numero" value="<?= $entry->numero ?>">
            <input type="hidden" name="go_home" value="<?= $go_home ?>">
            <button class="btn btn-success" type="submit">Actualizar medidas de entrada</button>
        </form>
    </div>
</div>
<p class="text-right">
    <a href="<?= url($go_home) ?>" class="text-danger">[ Cancelar actualizacion de medidas de entrada ]</a>
</p>
<?php $template->stop() ?>
<?php $template->expand('layouts/navless') ?>