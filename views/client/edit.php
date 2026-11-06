<?php $template->fill('content') ?>
<?php $template->insert('partials/validation') ?>
<?php $template->insert('partials/alert') ?>
<div class="card">
    <div class="card-header">
        <p class="m-0 lead">Editar cliente</p>
    </div>
    <div class="card-body">
        <form action="<?= url('clientes/actualizar/'.$cliente->id) ?>" method="post">
            <div class="row">
                <div class="col-sm col-md-8">
                    <div class="form-group">
                        <label for="">Nombre</label>
                        <input type="text" class="form-control" name="nombre" value="<?= $cliente->nombre ?>" required>
                    </div>            
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label for="">Alias</label>
                        <input type="text" class="form-control" name="alias" value="<?= $cliente->alias ?>" required>
                        <p class="m-0 small text-muted">Debe ser único.</p>
                    </div>           
                </div>
                <div class="col-sm col-md-6">
                    <div class="form-group">
                        <label for="">Teléfono</label>
                        <input type="text" class="form-control" name="telefono" value="<?= $cliente->telefono ?>" required>
                    </div>            
                </div>
                <div class="col-sm col-md-6">
                    <div class="form-group">
                        <label for="">Correo electrónico</label>
                        <input type="email" class="form-control" name="correo" value="<?= $cliente->correo ?>">
                    </div>
                </div>
                <div class="col-sm col-md-12">
                    <div class="form-group">
                        <label for="">Direccion</label>
                        <input type="text" class="form-control" name="direccion" value="<?= $cliente->direccion ?>">
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label for="">Ciudad</label>
                        <input type="text" class="form-control" name="ciudad" value="<?= $cliente->ciudad ?>" required>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label for="">Estado</label>
                        <input type="text" class="form-control" name="estado" value="<?= $cliente->estado ?>" required>
                    </div>         
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label for="">Pais</label>
                        <input type="text" class="form-control" name="pais" value="<?= $cliente->pais ?>" required>
                    </div>         
                </div>
            </div>
            <div class="form-group">
                <label for="">Notas</label>
                <textarea name="notas" rows="5" class="form-control"><?= $cliente->notas ?></textarea>
            </div>
            <button class="btn btn-warning" type="submit">Actualizar cliente</button>
            <a href="<?= url('clientes/mostrar/'.$cliente->id) ?>" class="btn btn-secondary">Regresar</a>
        </form>
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>