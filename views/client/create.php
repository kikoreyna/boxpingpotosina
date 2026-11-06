<?php $template->fill('content') ?>
<?php $template->insert('partials/validation') ?>

<div class="card">
    <div class="card-header">
        <p class="m-0 lead">Nuevo cliente</p>
    </div>
    <div class="card-body">
        <form action="<?= url('clientes/guardar') ?>" method="post">
            <div class="row">
                <div class="col-sm col-md-8">
                    <div class="form-group">
                        <label for="">Nombre</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>            
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label for="">Alias</label>
                        <input type="text" class="form-control" name="alias" required>
                        <p class="m-0 small text-danger">
                            <span>*</span>
                            Debe ser único.
                        </p>
                    </div>           
                </div>
                <div class="col-sm col-md-6">
                    <div class="form-group">
                        <label for="">Teléfono</label>
                        <input type="text" class="form-control" name="telefono" required>
                    </div>            
                </div>
                <div class="col-sm col-md-6">
                    <div class="form-group">
                        <label for="">Correos electrónico</label>
                        <input type="email" class="form-control" name="correo">
                    </div>
                </div>
                <div class="col-sm col-md-12">
                    <div class="form-group">
                        <label for="">Direccion</label>
                        <input type="text" class="form-control" name="direccion">
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label for="">Ciudad</label>
                        <input type="text" class="form-control" name="ciudad" required>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label for="">Estado</label>
                        <input type="text" class="form-control" name="estado" required>
                    </div>         
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label for="">Pais</label>
                        <input type="text" class="form-control" name="pais" required>
                    </div>         
                </div>
            </div>
            <div class="form-group">
                <label for="">Notas</label>
                <textarea name="notas" rows="5" class="form-control"></textarea>
            </div>

            <button class="btn btn-success" type="submit">Guardar cliente</button>
            <a href="<?= url('clientes') ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/main') ?>