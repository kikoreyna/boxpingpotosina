<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>

<div class="card">
    <div class="card-header bg-whitex">
        <p class="m-0 lead">Editar consolidado</p>
    </div>
    <div class="card-body">
        <form action="<?= url('consolidados/actualizar/'.$consolidated->id) ?>" method="post">
            <div class="form-group">
                <label for="">Cliente</label>
                <select name="cliente" class="form-control" required>
                    <?php foreach($clients as $client): ?>
                    <?php $selected = $consolidated->cliente_id === $client->id ? 'selected' : '' ?>
                    <option value="<?= $client->id ?>" <?= $selected ?>><?= $client->nombre ?> (<?= $client->alias ?>)</option>                 
                    <?php endforeach ?>                    
                </select>
            </div> 
            <div class="form-group mb-0">
                <label for="">Número de consolidado</label>
                <input type="text" class="form-control" name="numero" value="<?= $consolidated->numero ?>" required>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="alias" value="1" id="alias" <?= $consolidated->alias_cliente_numero ? 'checked' : '' ?>>
                <label class="form-check-label small" for="alias">
                    <span>Usar alias del cliente antes del número de consolidado.</span>
                    <em class="text-muted">Ejemplo: ALIAS#####</em>
                </label>
            </div>
            <div class="form-group">
                <label for="">Cantidad de palets</label>
                <input type="number" step="1" min="0" name="palets" value="<?= $consolidated->palets ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Notificación</label>
                <div class="form-row">
                    <div class="col-sm">
                        <input type="date" class="form-control" name="notificacion_fecha" value="<?= $consolidated->notificacion_fecha ?>">
                    </div>
                    <div class="col-sm">
                        <input type="time" class="form-control" name="notificacion_hora" value="<?= $consolidated->notificacion_hora ?>">
                    </div>
                    
                </div>
                   
            </div>
            

            <div class="input-group my-5">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <?php $checked = !is_null($consolidated->cerrado_at) ? 'checked' : '' ?>
                        <input type="checkbox" name="cerrar" id="cerrar_consolidado" value="1" aria-label="Cerrar consolidado" <?= $checked ?>>
                    </div>
                </div>
                <label for="cerrar_consolidado" class="form-control">
                    <b class="text-danger">CERRAR CONSOLIDADO</b>
                    <?php if(! is_null($consolidated->cerrado_at) ): ?>
                    <span class="small">(<?= $consolidated->cerrado_at ?>)</spab>
                    <?php endif ?>
                </label>
            </div>

            <button class="btn btn-warning" type="submit">Actualizar consolidado</button>
            <a href="<?= url('consolidados/mostrar/'.$consolidated->id) ?>" class="btn btn-secondary">Regresar</a>
        </form>
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>