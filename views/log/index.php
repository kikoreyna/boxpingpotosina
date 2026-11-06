<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>

<div class="card">
    <div class="card-header">
        <p class="m-0 lead">Bitácora de movimientos <span class="badge badge-primary badge-pill"><?= count($logs) ?></span></p>
        <small class="text-muted">Se muestran los últimos 500 movimientos que coinciden con el filtro.</small>
    </div>
    <div class="card-body border-bottom">
        <form method="get" action="<?= url('bitacora') ?>" class="form-row align-items-end">
            <div class="form-group col-md-2 mb-2">
                <label class="small m-0">Tipo</label>
                <select name="tabla" class="form-control form-control-sm">
                    <option value="">Todos</option>
                    <?php foreach($tables as $table => $label): ?>
                    <option value="<?= $table ?>" <?= $filters['table'] === $table ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="form-group col-md-2 mb-2">
                <label class="small m-0">Acción</label>
                <select name="accion" class="form-control form-control-sm">
                    <option value="">Todas</option>
                    <?php foreach($actions as $value => $label): ?>
                    <option value="<?= $value ?>" <?= $filters['action'] === $value ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="form-group col-md-2 mb-2">
                <label class="small m-0">Usuario</label>
                <select name="usuario" class="form-control form-control-sm">
                    <option value="">Todos</option>
                    <?php foreach($users as $user): ?>
                    <option value="<?= $user->id ?>" <?= (string) $filters['user_id'] === (string) $user->id ? 'selected' : '' ?>><?= htmlspecialchars($user->nombre) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="form-group col-md-2 mb-2">
                <label class="small m-0">Desde</label>
                <input type="date" name="desde" value="<?= $filters['from'] ?>" class="form-control form-control-sm">
            </div>
            <div class="form-group col-md-2 mb-2">
                <label class="small m-0">Hasta</label>
                <input type="date" name="hasta" value="<?= $filters['to'] ?>" class="form-control form-control-sm">
            </div>
            <div class="form-group col-md-2 mb-2">
                <label class="small m-0">Buscar en detalle</label>
                <input type="text" name="buscar" value="<?= htmlspecialchars((string) $filters['q']) ?>" class="form-control form-control-sm">
            </div>
            <div class="form-group col-md-2 mb-2">
                <button type="submit" class="btn btn-info btn-sm btn-block">Filtrar</button>
            </div>
        </form>
    </div>

    <?php if( $logs ): ?>
    <div class="table-responsive">
        <table class="table table-hover table-sm small m-0">
            <thead class="text-secondary">
                <tr>
                    <th class="border-0 pl-3">FECHA</th>
                    <th class="border-0">ACCIÓN</th>
                    <th class="border-0">TIPO</th>
                    <th class="border-0">DETALLE</th>
                    <th class="border-0">USUARIO</th>
                    <th class="border-0">IP</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($logs as $log): ?>
                <tr>
                    <td class="pl-3 text-nowrap"><?= $log->created_at ?></td>
                    <?php $badge = ['creacion' => 'success', 'edicion' => 'warning', 'eliminacion' => 'danger', 'observacion' => 'info'][$log->accion] ?? 'secondary' ?>
                    <td><span class="badge badge-<?= $badge ?>"><?= $actions[$log->accion] ?? $log->accion ?></span></td>
                    <td><span class="badge badge-secondary"><?= $tables[$log->tabla] ?? $log->tabla ?></span></td>
                    <td>
                        <?= htmlspecialchars($log->detalle) ?> <span class="text-muted">#<?= $log->registro_id ?></span>
                        <?php if( $log->cambios ): ?>
                        <div class="text-muted" style="white-space: pre-line"><?= htmlspecialchars($log->cambios) ?></div>
                        <?php endif ?>
                    </td>
                    <td>
                        <?= $log->usuario_nombre ? htmlspecialchars($log->usuario_nombre) : '<span class="text-muted">Sistema</span>' ?>
                        <?php if( $log->usuario_tipo ): ?><span class="text-muted">(<?= str_replace('_', ' ', $log->usuario_tipo) ?>)</span><?php endif ?>
                    </td>
                    <td><?= $log->ip ?></td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="card-body text-muted">No hay movimientos registrados.</div>
    <?php endif ?>
</div>

<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>
