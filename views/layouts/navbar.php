<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
  <a class="navbar-brand" href="<?= url('') ?>">Boxping | <?= config('app','name') ?></a>

  <?php if( session_has('user') ): $session_type = session_get('user', 'type') ?>
  <div class="navbar-text text-white">
    <span class="mx-1">-</span>
    <span class="small"><?= session_get('user', 'name') ?></span> 
    <small class="d-none d-md-inline-block-x text-capitalize">(<?= $session_type ?>)</small>
  </div>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
    <ul class="navbar-nav small">
      <?php if( $session_type === 'administrador' || $session_type === 'documentador' ): ?>
      <li class="nav-item">
        <a href="<?= url('consolidados') ?>" class="nav-link">Consolidados</a>
      </li>
      <li class="nav-item">
        <a href="<?= url('entradas') ?>" class="nav-link">Entradas</a>
      </li>
      <li class="nav-item">
        <a href="<?= url('salidas') ?>" class="nav-link">Salidas</a>
      </li>
      <li class="nav-item">
        <a href="<?= url('clientes') ?>" class="nav-link">Clientes</a>
      </li>
      <li class="nav-item">
        <a href="<?= url('transportadoras') ?>" class="nav-link">Transportadoras</a>
      </li>
      <li class="nav-item">
        <a href="<?= url('reempaque') ?>" class="nav-link">Reempaque</a>
      </li>
      <li class="nav-item">
        <a href="<?= url('tareas') ?>" class="nav-link">Tareas</a>
      </li>
      <li class="nav-item">
        <a href="<?= url('cruce') ?>" class="nav-link">Cruce</a>
      </li>

      <?php if( $session_type === 'administrador'): ?>
      <li class="nav-item">
        <a href="<?= url('usuarios') ?>" class="nav-link">Usuarios</a>
      </li>
      <?php endif ?>

      <li class="nav-item">
        <span data-toggle="tooltip" data-placement="bottom" title="Buscar">
        <a href="#!" class="nav-link d-none d-md-block" data-toggle="modal" data-target="#busquedaModal">
          <i class="fas fa-search"></i>
        </a>
        </span>
        <a href="#!" class="nav-link d-md-none" data-toggle="modal" data-target="#busquedaModal">
          <span>Busqueda</span>
        </a>
      </li>
      <?php endif ?>

      <li class="nav-item">
        <a href="<?= url('signout') ?>" class="nav-link d-none d-md-block" data-toggle="tooltip" data-placement="bottom" title="Cerrar sesión">
          <i class="fas fa-sign-out-alt"></i>
        </a>
        <a href="<?= url('signout') ?>" class="nav-link d-md-none">
          Cerrar sesión
        </a>
      </li>
    </ul> 
  </div>
  <?php endif ?>
</nav>