<?php $template->fill('content') ?>
<div class="row mt-4">
    <div class="col-sm col-sm-6 offset-sm-3">
        <div class="card mb-3">
            <div class="card-header">
                <span class="lead">Area de reempacado</span>
            </div>
            <div class="card-body">
                <form action="<?= url('reempacado/actualizar/numero') ?>" method="post" autocomplete="off">
                    <div class="form-group">
                        <label for="">Número de guía</label>
                        <input type="text" name="numero" class="form-control" autofocus required>
                    </div>
                    <div class="form-group">
                        <label for="">Código de reempacado</label>
                        <select name="codigo_reempacado" id="" class="form-control" required>
                            <option disabled selected></option>
                            <?php foreach($coders as $coder): ?>
                            <option value="<?= $coder->id ?>"><?= $coder->codigo ?> (<?= $coder->descripcion ?>)</option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Clave de reempacador</label>
                        <input type="password" class="form-control" name="clave" required>
                    </div>
                    
                    <button class="btn btn-success btn-block btn-lg" type="submit">Actualizar número de guia</button>
                </form>
            </div>
            <div class="card-footer bg-white">
                <?php $template->insert('partials/validation') ?>
                <?php $template->insert('partials/alert') ?>
            </div>
        </div>

        <p class="">
            <a href="<?= home_url() ?>">Iniciar sesion</a>
        </p>
    </div>
</div>
<?php $template->stop() ?>
<?php $template->expand('layouts/navless') ?>