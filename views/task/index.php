<?php $template->fill('content') ?>
<?php $template->insert('partials/alert') ?>
<?php $template->insert('partials/validation') ?>

<?php if( $is_admin ): ?>
<div class="card mb-3">
    <div class="card-header">
        <p class="m-0 lead">Asignar entrada a documentador</p>
    </div>
    <div class="card-body">
        <form action="<?= url('tareas/guardar') ?>" method="post" class="form-row align-items-end">
            <div class="form-group col-md-3">
                <label class="small m-0">Número de entrada</label>
                <input type="text" name="entrada" class="form-control form-control-sm" required>
            </div>
            <div class="form-group col-md-3">
                <label class="small m-0">Documentador</label>
                <select name="documentador" class="form-control form-control-sm" required>
                    <option value="" disabled selected></option>
                    <?php foreach($documenters as $user): ?>
                    <option value="<?= $user->id ?>"><?= $user->nombre ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label class="small m-0">Necesidad / tarea</label>
                <input type="text" name="descripcion" maxlength="255" class="form-control form-control-sm" placeholder="Ej. Revisar destinatario" required>
            </div>
            <div class="form-group col-md-2">
                <button type="submit" class="btn btn-success btn-sm btn-block">Asignar</button>
            </div>
        </form>
    </div>
</div>
<?php endif ?>

<div class="card">
    <div class="card-header">
        <form method="get" action="<?= url('tareas') ?>" class="form-inline float-right">
            <select name="estatus" class="form-control form-control-sm mr-2">
                <option value="" <?= $filters['status'] === '' ? 'selected' : '' ?>>Todas</option>
                <option value="pendiente" <?= $filters['status'] === 'pendiente' ? 'selected' : '' ?>>Pendientes</option>
                <option value="completada" <?= $filters['status'] === 'completada' ? 'selected' : '' ?>>Completadas</option>
            </select>
            <?php if( $is_admin ): ?>
            <select name="usuario" class="form-control form-control-sm mr-2">
                <option value="">Todos los documentadores</option>
                <?php foreach($documenters as $user): ?>
                <option value="<?= $user->id ?>" <?= (string) $filters['user_id'] === (string) $user->id ? 'selected' : '' ?>><?= $user->nombre ?></option>
                <?php endforeach ?>
            </select>
            <?php endif ?>
            <button type="submit" class="btn btn-info btn-sm">Filtrar</button>
        </form>
        <p class="m-0 lead"><?= $is_admin ? 'Tareas asignadas' : 'Mis tareas' ?> <span class="badge badge-primary badge-pill"><?= count($tasks) ?></span></p>
    </div>

    <?php if( $tasks ): ?>
    <div class="table-responsive">
        <table class="table table-hover table-sm m-0">
            <thead class="text-secondary small">
                <tr>
                    <th class="border-0 pl-3">ENTRADA</th>
                    <th class="border-0">NECESIDAD</th>
                    <?php if( $is_admin ): ?><th class="border-0">DOCUMENTADOR</th><?php endif ?>
                    <th class="border-0">ASIGNADA POR</th>
                    <th class="border-0">ASIGNADA</th>
                    <th class="border-0">ESTATUS</th>
                    <th class="border-0"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($tasks as $task): ?>
                <tr>
                    <td class="pl-3"><a href="<?= url('entradas/mostrar/'.$task->entrada_id) ?>"><?= $task->entrada_numero ?></a></td>
                    <td><?= htmlspecialchars($task->descripcion) ?></td>
                    <?php if( $is_admin ): ?><td><?= $task->usuario_nombre ?></td><?php endif ?>
                    <td><?= $task->asignado_por_nombre ?></td>
                    <td><?= $task->created_at ?></td>
                    <td>
                        <?php if( $task->estatus === 'pendiente' ): ?>
                        <span class="badge badge-warning">Pendiente de revisión</span>
                        <?php else: ?>
                        <span class="badge badge-success">Completada</span>
                        <small class="text-muted"><?= $task->completado_at ?></small>
                        <?php endif ?>
                    </td>
                    <td class="text-right pr-3">
                        <?php if( $task->estatus === 'pendiente' ): ?>
                        <form action="<?= url('tareas/completar/'.$task->id) ?>" method="post" class="d-inline">
                            <button type="submit" class="btn btn-sm btn-success" title="Marcar como completada"><i class="fas fa-check"></i></button>
                        </form>
                        <?php endif ?>
                        <?php if( $is_admin ): ?>
                        <form action="<?= url('tareas/eliminar/'.$task->id) ?>" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar esta tarea?')">
                            <button type="submit" class="btn btn-sm btn-danger" title="Eliminar tarea"><i class="fas fa-trash"></i></button>
                        </form>
                        <?php endif ?>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="card-body text-muted">No hay tareas.</div>
    <?php endif ?>
</div>

<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>
