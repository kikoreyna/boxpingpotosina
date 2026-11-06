<!-- Modal -->
<div class="modal fade" id="busquedaModal" tabindex="-1" role="dialog" aria-labelledby="busquedaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="busquedaModalLabel">Busqueda</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="<?= url('buscar') ?>" method="post" id="formSearch">
                <input type="text" class="form-control mb-4" name="datos" placeholder="Escribe tu busqueda aqui..." required>
                <div class="form-group small">
                    <span class="mb-2">Filtrar por:</span>
                    <div class="form-check">
                        <input class="form-check-input align-middle" type="checkbox" id="numero_entrada" name="numero_entrada" value="1" checked>
                        <label class="form-check-label align-middle" for="numero_entrada">Número de entrada</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input align-middle" type="checkbox" id="numero_consolidado" name="numero_consolidado" value="1">
                        <label class="form-check-label align-middle" for="numero_consolidado">Número de consolidado</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input align-middle" type="checkbox" id="numero_rastreo" name="numero_rastreo" value="1">
                        <label class="form-check-label align-middle" for="numero_rastreo">Número de rastreo</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input align-middle" type="checkbox" id="remitente" name="remitente" value="1">
                        <label class="form-check-label align-middle" for="remitente">Remitente</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input align-middle" type="checkbox" id="destinatario" name="destinatario" value="1">
                        <label class="form-check-label align-middle" for="destinatario">Destinatario</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary" form="formSearch">Buscar</button>
        </div>
        </div>
    </div>
</div>