<div class="card bg-transparent border-info text-muted">
    <div class="card-body">
        <form action="<?= url('entradas') ?>" method='get'>

            <div class="form-group">
                <label for="filter-cliente" class="m-0 small text-secondary">Cliente</label>
                <select class="form-control form-control-sm" id="filter-cliente" name="cliente" required>
                    <option value="todos" selected>Todos los clientes</option>
                    
                    <?php $selected = $filters['client'] === 'sin_cliente' ? 'selected' : '' ?>
                    <option value="sin_cliente" <?= $selected ?>>Sin cliente</option>

                    <?php foreach($inputs['clients'] as $client): ?>
                    <?php $selected = $client->id == $filters['client'] ? 'selected' : '' ?>
                    <option value="<?= $client->id ?>" <?= $selected ?>><?= $client->nombre ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label for="filter-entradas" class="m-0 small text-secondary">Entradas</label>
                <select class="form-control form-control-sm" id="filter-entradas" name="consolidados" required>
                    <?php foreach($inputs['consolidated'] as $value => $label): ?>
                    <?php $selected = $value == $filters['consolidated'] ? 'selected' : '' ?>
                    <option value="<?= $value ?>" <?= $selected ?>><?= $label ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label for="filter-salidas" class="m-0 small text-secondary">Salida</label>
                <select class="form-control form-control-sm" id="filter-salidas" name="salidas" required>
                    <?php foreach($inputs['wayout'] as $value => $label): ?>
                    <?php $selected = $value == $filters['wayout'] ? 'selected' : '' ?>
                    <option value="<?= $value ?>" <?= $selected ?>><?= $label ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label for="filter-bodegas" class="m-0 small text-secondary">Bodega</label>
                <select name="bodega" id="filter-bodegas" class="form-control form-control-sm">
                    <?php foreach($inputs['warehouse'] as $value => $label): ?>
                    <?php $selected = $value == $filters['warehouse'] ? 'selected' : '' ?>
                    <option value="<?= $value ?>" <?= $selected ?>><?= $label ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label for="filter-desde" class="m-0 small text-secondary">Desde</label>
                <input class="form-control form-control-sm" type="date" id="filter-desde" name="desde" value="<?= $filters['from'] ?>" required>
            </div>

            <div class="form-group">
                <label for="filter-hasta" class="m-0 small text-secondary">Hasta</label>
                <input class="form-control form-control-sm" type="date" id="filter-hasta" name="hasta" value="<?= $filters['to'] ?>" required>
            </div>

            <div class="form-group">
                <label for="filter-limite" class="m-0 small text-secondary">Limite de resultados</label>
                <select class="form-control form-control-sm" id="filter-limite" name="limite" required>
                    <?php foreach($inputs['limit'] as $value): ?>
                    <?php $selected = $value == $filters['limit'] ? 'selected' : '' ?>
                    <option value="<?= $value ?>" <?= $selected ?>><?= ucfirst($value) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            
            <hr>

            <div class="form-group">
                <label class="text-secondary small m-0">Pesaje de</label>
                <select name="pesaje" class="form-control form-control-sm">
                    <option value="" selected>Ninguno</option>
                    <?php foreach($inputs['pesajes'] as $value): ?>
                    <?php $select = $filters['weighing'] == $value ? 'selected' : '' ?>
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

            <button class="btn btn-info btn-sm btn-block" type="submit">Filtrar entradas</button>
        </form>
    
    </div>
</div>