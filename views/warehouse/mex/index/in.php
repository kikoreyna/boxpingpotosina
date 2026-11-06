<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title text-uppercase mb-4">Entrada a bodega MEX</h5>
        <form id="frm-registrar" action="<?= url('bodega/registrar/numero/entrada') ?>" method="post" autocomplete="off">
            <div class="row">
                <div class="col-sm form-group">
                    <label for="conductor">Conductor</label>
                    <select name="conductor" id="conductor" class="form-control form-select" required>
                        <option selected disabled></option>
                    
                    <?php foreach($crossing['drivers'] as $driver): ?>
                        <?php $selected = $driver->id == $crossing['driver_selected'] ? 'selected' : '' ?>
                        <option value="<?= $driver->id ?>" <?= $selected ?>><?= $driver->nombre ?></option>
                    <?php endforeach ?>

                    </select>
                </div>
                <div class="col-sm form-group">
                    <label for="vehiculo">Vehiculo</label>
                    <select name="vehiculo" id="vehiculo" class="form-control" required>
                        <option selected disabled></option>
                    
                    <?php foreach($crossing['cars'] as $car): ?>
                        <?php $selected = $car->id == $crossing['car_selected'] ? 'selected' : '' ?>
                        <option value="<?= $car->id ?>" <?= $selected ?>><?= $car->alias ?></option>
                    <?php endforeach ?>
                    
                    </select>
                </div>
                <div class="col-sm form-group">
                    <label for="numero_vuelta">Número de vuelta</label>
                    <input name="numero_vuelta" value="<?= $crossing['return_number'] ?>"  type="number" step="1" min="1" id="numero_vuelta" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="bodega">Bodega donde se da la entrada</label>
                <select name="bodega" id="bodega" class="form-control" required>
                    <option selected disabled></option>
                    <?php foreach($crossing['depots'] as $depot): ?>
                    <?php $selected = $depot->id == $crossing['depot_selected'] ? 'selected' : '' ?>
                    <option value="<?= $depot->id ?>" <?= $selected ?>><?= htmlspecialchars($depot->nombre) ?></option>
                    <?php endforeach ?>
                </select>
                <?php if( !$crossing['depots'] ): ?>
                <small class="text-danger">No hay bodegas registradas. Pide al administrador que las dé de alta.</small>
                <?php endif ?>
            </div>
            <div class="form-group">
                <label>Numero de guia</label>
                <input type="text" class="form-control" name="numero" autofocus required>
            </div>
            <div class="form-group">
                <label for="">Observaciones</label>
                <textarea name="observaciones" id="observaciones" cols="30" rows="5" class="form-control"></textarea>
            </div>
            <button class="btn btn-success" type="submit" form="frm-registrar">Registrar entrada de guia</button>
        </form>
    </div>
    <div class="card-footer">
        Registar en
        <a href="<?= url('bodega/salida') ?>" class="text-primary">Salida de bodega MEX</a>
    </div>
</div>
<br>
<p class="text-right">
    Cambiar a
    <a href="<?= url('transportadora/carga') ?>" class="link-primary">Carga para transportadora</a>
</p>

<?php $template->stop() ?>
<?php $template->expand('layouts/navless') ?>
