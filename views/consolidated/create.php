<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<div class="card">
    <div class="card-header">
        <p class="m-0 lead">Nuevo consolidado</p>
    </div>
    <div class="card-body">
        <form action="<?= url('consolidados/guardar') ?>" method="post">
            <div class="form-group">
                <label for="">Cliente</label>
                <select name="cliente" class="form-control" required>
                    <option disabled selected></option>
                    <?php foreach($clients as $client): ?>
                    <option value="<?= $client->id ?>"><?= $client->nombre ?> (<?= $client->alias ?>)</option>
                    <?php endforeach ?>
                </select>
            </div> 
            <div class="form-group mb-0">
                <label for="">Número de consolidado</label>
                <input type="text" class="form-control" name="numero">
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="alias" value="1" id="alias" checked>
                <label class="form-check-label small" for="alias">
                    <span>Usar alias del cliente antes del número de consolidado.</span>
                    <em class="text-muted">Ejemplo: ALIAS#####</em>
                </label>
            </div>
            <div class="form-group">
                <label for="">Cantidad de palets</label>
                <input type="number" step="1" min="0" name="palets" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Notificación</label>
                <div class="form-row">
                    <div class="col-sm">
                        <input type="date" class="form-control" name="notificacion_fecha">
                    </div>
                    <div class="col-sm">
                        <input type="time" class="form-control" name="notificacion_hora">
                    </div>
                </div>
            </div>
            <button class="btn btn-success" type="submit">Crear consolidado</button>
            <a href="<?= url('consolidados') ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>