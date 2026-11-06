<div class="card bg-transparent border-info text-muted ">
    <div class="card-body">
        <form action="<?= url('consolidados') ?>" method="get" id="filters-form">
            <div class="form-group">
                <label for="filter-cliente" class='m-0 small'>Cliente</label>
                <select class="form-control form-control-sm" id="filter-cliente" name="cliente" required>
                    <option value="todos">Todos los clientes</option>
                    <?php foreach($inputs['clients'] as $client): ?>
                    <?php $selected = $filters['client'] == $client->id ? 'selected' : '' ?>
                    <option value="<?= $client->id ?>" <?= $selected ?>><?= $client->nombre ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label for="filter-desde" class="m-0 small">Desde</label>
                <input class="form-control form-control-sm" type="date" id="filter-desde" name="desde" value="<?= $filters['from'] ?>" required>
            </div>

            <div class="form-group">
                <label for="filter-hasta" class="m-0 small">Hasta</label>
                <input class="form-control form-control-sm" type="date" id="filter-hasta" name="hasta" value="<?= $filters['to'] ?>" required>
            </div>

            <div class="form-group">
                <label for="filter-estatus" class='m-0 small'>Estatus</label>
                <select class="form-control form-control-sm" id="filter-estatus" name="estatus" required>
                    <?php foreach($inputs['status'] as $status): ?>
                    <?php $selected = $filters['status'] == $status ? 'selected' : '' ?>
                    <option value="<?= $status ?>" <?= $selected ?>><?= ucfirst($status) ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <button class="btn btn-info btn-sm" type="submit" form="filters-form">Filtrar consolidados</button>
        </form>
    </div>
</div>