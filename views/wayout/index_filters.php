<div class="card bg-transparent border-info text-muted">
    <div class="card-body">
        <form action="<?= url('salidas') ?>" method="get">

            <div class="form-group">
                <label for="filter-cliente" class="m-0 small text-secondary">Cliente</label>
                <select class="form-control form-control-sm" id="filter-cliente" name="cliente" required>
                    <option value="todos" selected>Todos los clientes</option>
                    <?php foreach($inputs['clientes'] as $client): ?>
                    <?php $selected = $client->id == $filters['client'] ? 'selected' : '' ?>
                    <option value="<?= $client->id ?>" <?= $selected ?>><?= $client->nombre ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label for="filter-transportadora" class="m-0 small text-secondary">Transportadora</label>
                <select class="form-control form-control-sm" id="filter-transportadora" name="transportadora" required>
                    <option value="cualquier" selected>Cualquier transportadora</option>
                    <?php foreach($inputs['transportadoras'] as $transport): ?>
                    <?php $selected = $transport->id == $filters['transport'] ? 'selected' : '' ?>
                    <option value="<?= $transport->id ?>" <?= $selected ?>><?= $transport->nombre ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label for="filter-cobertura" class="m-0 small text-secondary">Cobertura</label>
                <select class="form-control form-control-sm" id="filter-cobertura" name="cobertura" required>
                    <option value="cualquier" selected>Cualquier cobertura</option>
                    <?php foreach($inputs['coberturas'] as $cover): ?>
                    <?php $selected = $cover == $filters['cover'] ? 'selected' : '' ?>
                    <option value="<?= $cover ?>" <?= $selected ?>><?= ucfirst($cover) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="filter-status" class="m-0 small text-secondary">Status</label>
                <select class="form-control form-control-sm" id="filter-status" name="status" required>
                    <option value="cualquier" selected>Cualquier status</option>
                    <?php foreach($inputs['status'] as $status): ?>
                    <?php $selected = $status == $filters['status'] ? 'selected' : '' ?>
                    <option value="<?= $status ?>" <?= $selected ?>><?= ucwords($status)?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label for="filter-incidente" class="m-0 small text-secondary">Incidente</label>
                <select class="form-control form-control-sm" id="filter-incidente" name="incidente" required>
                    <option value="cualquier" selected>Cualquier incidente</option>
                    <?php foreach($inputs['incidentes'] as $incident): ?>
                    <?php $selected = $incident == $filters['incident'] ? 'selected' : '' ?>
                    <option value="<?= $incident ?>" <?= $selected ?>><?= ucwords($incident) ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label for="filter-desde" class="m-0 small text-secondary">Desde</label>
                <input class="form-control form-control-sm" type="date" id="filter-desde" name="desde" value="<?= $inputs['desde'] ?>" required>
            </div>

            <div class="form-group">
                <label for="filter-hasta" class="m-0 small text-secondary">Hasta</label>
                <input class="form-control form-control-sm" type="date" id="filter-hasta" name="hasta" value="<?= $inputs['hasta'] ?>" required>
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

            <hr>
            <div class="form-group">
                <label class="text-secondary small m-0">Carga desde</label>
                <input type="datetime-local" name="carga_fecha_hora_desde" value="<?= $filters['carga_fecha_hora_desde'] ?? '' ?>" class="form-control form-control-sm">
            </div>
            <div class="form-group">
                <label class="text-secondary small m-0">Carga hasta</label>
                <input type="datetime-local" name="carga_fecha_hora_hasta" value="<?= $filters['carga_fecha_hora_hasta'] ?? '' ?>" class="form-control form-control-sm">
            </div>

            <hr>
            <div class="form-group">
                <label class="text-secondary small m-0">Descarga desde</label>
                <input type="datetime-local" name="descarga_fecha_hora_desde" value="<?= $filters['descarga_fecha_hora_desde'] ?? '' ?>" class="form-control form-control-sm">
            </div>
            <div class="form-group">
                <label class="text-secondary small m-0">Descarga hasta</label>
                <input type="datetime-local" name="descarga_fecha_hora_hasta" value="<?= $filters['descarga_fecha_hora_hasta'] ?? '' ?>" class="form-control form-control-sm">
            </div>

            <button class="btn btn-info btn-sm btn-block" type="submit">Filtrar salidas</button>
        </form>
    </div>
</div>
