<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="historyModalLabel">
            <div class="d-block" id="number_entry"></div>
            <div class="text-muted small" id="consolidated_entry"></div>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div class="text-center" id="loading">
            <div class="fa-3x text-primary">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
        </div>

        <div id="history-content" style="display:none">

          <label class="d-block">
            <div class="float-right">
              <a href="#editar" id="button_edit" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="left" title="Editar medidas">
                <i class="fas fa-pencil-alt"></i>
              </a>
            </div>
            <span class="align-middle">Medidas</span>
          </label>
          <div class="table-responsive mb-5">
              <table class="table table-bordered table-sm table-hover small m-0" id="measures-table">
                  <thead class="text-secondary">
                      <tr>
                          <th class="bg-light">Etapa</th>
                          <th class="bg-light">Peso</th>
                          <th class="bg-light text-nowrap">Volumen (Ancho * Altura * Profundidad)</th>
                          <th class="bg-light">Actualizacion</th>
                          <th class="bg-light">Usuario</th>
                      </tr>
                  </thead>
                  <tbody id="measures-content"></tbody>
              </table>
          </div>

        <div class="card mb-5" id="dropOffStage-content">
            <div class="card-header">Carga y Descarga a Transportadora</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm">
                        <small class="d-block text-muted">Cargado</small>
                        <span id="dropOffStageCarga"></span>
                    </div>
                    <div class="col-sm">
                        <small class="d-block text-muted">Descargado</small>
                        <span id="dropOffStageDescarga"></span>
                    </div>
                </div>
            </div>
        </div>
        
          <div class="row">
            <div class="col-sm mb-3">
              <div class="mb-2">Observaciones</div>
              <div class="border rounded" style="height:209px; overflow-y:auto">
                <ul id='observations-content' class="list-group list-group-flush">
                </ul>
              </div>
            </div>

            <div class="col-sm col-sm-4">
              <form action="#!" id="frm-new-observation">
                <div class="form-group">
                  <label class="d-block">
                    <span class="align-middle">Nueva observacion</span>
                  </label>
                  <textarea name="observacion" rows="6" class="form-control mb-3" placeholder="Escribe aqui..." required></textarea>
                  <button type="submit" class="btn btn-success btn-sm btn-block">
                    Guardar observacion
                  </button>
                  <div id="waiting-observation" class="text-center" style="display:none">
                      <div class="fa-3x text-primary">
                          <i class="fas fa-spinner fa-spin"></i>
                      </div>
                  </div>
                  <div id="response-observation" class="text-center text-uppercase font-weight-bold small" style="display:none"></div>
                </div>
              </form>
            </div>

          </div>
        </div>
      </div>

      <div id="error-entry" class="display-4 text-center mb-5"></div>
    </div>
  </div>
</div>
