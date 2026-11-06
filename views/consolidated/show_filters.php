<div class="card border-info bg-transparent">
    <form method="get" class="card-body">
        <div class="form-group">
            <label class="text-secondary small m-0">Existentes en</label>
            <select name="bodega" class="form-control form-control-sm">
                <option value="cualquier">Cualquiera</option>
                <?php foreach($inputs_filter['warehouses'] as $value => $label): ?>
                <?php $select = $filters['warehouse'] === $value ? 'selected' : '' ?>
                <option value="<?= $value ?>" <?= $select ?>><?= $label ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="form-group">
            <label class="text-secondary small m-0">Faltantes en</label>
            <select name="faltante" class="form-control form-control-sm">
                <option value="cualquier">Cualquiera</option>
                <?php foreach($inputs_filter['warehouses'] as $value => $label): ?>
                <?php $select = $filters['missing'] === $value ? 'selected' : '' ?>
                <option value="<?= $value ?>" <?= $select ?>><?= $label ?></option>
                <?php endforeach ?>
            </select>
        </div>        
        <div class="form-group">
            <label class="text-secondary small m-0">Reempacado</label>
            <select name="reempacado" class="form-control form-control-sm">
                <option value="cualquiera">Cualquiera</option>
                <?php foreach($inputs_filter['repackaged'] as $value): ?>
                <?php $select = $filters['repackaged'] === $value ? 'selected' : '' ?>
                <option value="<?= $value ?>" <?= $select ?>><?= ucfirst($value) ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="form-group">
            <label class="text-secondary small m-0">Cobertura</label>
            <select name="cobertura" class="form-control form-control-sm">
                <option value="cualquier">Cualquiera</option>
                <?php foreach($inputs_filter['covers'] as $value): ?>
                <?php $select = $filters['cover'] === $value ? 'selected' : '' ?>
                <option value="<?= $value ?>" <?= $select ?>><?= ucfirst($value) ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="form-group">
            <label class="text-secondary small m-0">Transportadora</label>
            <select name="transportadora" class="form-control form-control-sm">
                <option value="cualquier">Todas</option>
                <?php foreach($inputs_filter['transports'] as $transport): ?>
                <?php $select = $filters['transport'] === $transport->id ? 'selected' : '' ?>
                <option value="<?= $transport->id ?>" <?= $select ?>><?= $transport->nombre ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="form-group">
            <label class="text-secondary small m-0">Incidente</label>
            <select name="incidente" class="form-control form-control-sm">
                <option value="ninguno">Ninguno</option>
                <?php foreach($inputs_filter['incidents'] as $value): ?>
                <?php $select = $filters['incident'] === $value ? 'selected' : '' ?>
                <option value="<?= $value ?>" <?= $select ?>><?= ucfirst($value) ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <hr>

        <div class="form-group">
            <label class="text-secondary small m-0">Pesaje de</label>
            <select name="pesaje" class="form-control form-control-sm">
                <option value="ninguno">Ninguno</option>
                <?php foreach($inputs_filter['weighings'] as $value): ?>
                <?php $select = $filters['weighing'] === $value ? 'selected' : '' ?>
                <option value="<?= $value ?>" <?= $select ?>><?= str_replace('_',' ', ucfirst($value)) ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="form-group">
            <label class="text-secondary small m-0">Pesaje desde</label>
            <input type="date" name="pesaje_desde" value="<?= $filters['weighing_from'] ?>" class="form-control form-control-sm">
        </div>
        <div class="form-group">
            <label class="text-secondary small m-0">Pesaje hasta</label>
            <input type="date" name="pesaje_hasta" value="<?= $filters['weighing_to'] ?>" class="form-control form-control-sm">
        </div>

        <button type="submit" class="btn btn-info btn-sm btn-block">Filtrar entradas</button>
    </form>
</div>