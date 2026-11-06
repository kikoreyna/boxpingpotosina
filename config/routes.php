<?php

return [
    // Auth
    'identificar' => 'AuthController:index',
    'signing' => 'AuthController:signing',
    'signout' => 'AuthController:signout',

    // General
    'dashboard' => 'DashboardController:index',
    'descargar/' => 'DashboardController:download',
    'buscar' => 'SearchController:index',

    // Consolidados
    'consolidados' => 'ConsolidatedController:index',
    'consolidados/crear' => 'ConsolidatedController:create',
    'consolidados/guardar' => 'ConsolidatedController:store',
    'consolidados/mostrar/' => 'ConsolidatedController:show',
    'consolidados/editar/' => 'ConsolidatedController:edit',
    'consolidados/actualizar/' => 'ConsolidatedController:update',
    'consolidados/advertencia_eliminar/' => 'ConsolidatedController:warningDelete',
    'consolidados/eliminar' => 'ConsolidatedController:delete',
    'consolidados/imprimir/' => 'ConsolidatedController:toPrint',

    // Entradas
    'entradas' => 'EntryController:index',
    'entradas/crear' => 'EntryController:create',
    'entradas/validacion_csv' => 'EntryController:validateCSV',
    'entradas/guardar' => 'EntryController:store',
    'entradas/mostrar/' => 'EntryController:show',
    'entradas/editar/' => 'EntryController:edit',   
    'entradas/actualizar/' => 'EntryController:update',
    'entradas/actualizar_observaciones/' => 'EntryController:updateObservations',  
    'entradas/imprimir/' => 'EntryController:toPrint',
    'entradas/advertencia_eliminar/' => 'EntryController:warningDelete',
    'entradas/eliminar' => 'EntryController:delete',
 
    // Entrada - Medidas
    'medidas/actualizar/' => 'MeasureController:update',

    // Entrada - Remitente
    'remitentes/actualizar/' => 'RemitterController:update',

    // Entrada - Destinatario
    'destinatarios/actualizar/' => 'AddresseeController:update',

    // Entrada - Historial
    'historial/mostrar/' => 'HistoryController:show',

    // Salidas
    'salidas' => 'WayoutController:index',
    'salidas/crear' => 'WayoutController:create',
    'salidas/guardar' => 'WayoutController:store',
    'salidas/editar/' => 'WayoutController:edit',
    'salidas/actualizar/' => 'WayoutController:update',
    'salidas/imprimir/' => 'WayoutController:toPrint',

    // Clientes
    'clientes' => 'ClientController:index',
    'clientes/nuevo' => 'ClientController:create',
    'clientes/guardar' => 'ClientController:store',
    'clientes/mostrar/' => 'ClientController:show',
    'clientes/editar/' => 'ClientController:edit',
    'clientes/actualizar/' => 'ClientController:update',
    'clientes/borrar/' => 'ClientController:erase',
    'clientes/eliminar/' => 'ClientController:deleteSoft',
    'clientes/crear/credencial' => 'ClientController:createCredential',
    'clientes/guardar/credencial' => 'ClientController:storeCredential',
    'clientes/editar/credencial' => 'ClientController:editCredential',
    'clientes/actualizar/credencial' => 'ClientController:updateCredential',

    // Transportadoras
    'transportadoras' => 'TransportController:index',
    'transportadoras/nuevo' => 'TransportController:create',
    'transportadoras/guardar' => 'TransportController:store',
    'transportadoras/mostrar/' => 'TransportController:show',
    'transportadoras/editar/' => 'TransportController:edit',
    'transportadoras/actualizar/' => 'TransportController:update',
    
    // Reempaque
    'reempaque' => 'RepackageController:index',

    // Reempacadores
    'reempacadores/nuevo' => 'RepackerController:create',
    'reempacadores/guardar' => 'RepackerController:store',
    'reempacadores/editar/' => 'RepackerController:edit',
    'reempacadores/actualizar/' => 'RepackerController:update',

    // Codigos de reempacado
    'codigos_reempacado/nuevo' => 'CoderController:create',
    'codigos_reempacado/guardar' => 'CoderController:store',
    'codigos_reempacado/editar/' => 'CoderController:edit',
    'codigos_reempacado/actualizar/' => 'CoderController:update',
    'codigos_reempacado/borrar/' => 'CoderController:erase',
    'codigos_reempacado/eliminar/' => 'CoderController:deleteSoft',
    
    // Cruce (Conductor & Vehiculo)
    'cruce' => 'CrossingController:index',

    // Conductores
    'conductores/nuevo' => 'DriverController:create',
    'conductores/guardar' => 'DriverController:store',
    'conductores/editar/' => 'DriverController:edit',
    'conductores/actualizar/' => 'DriverController:update',

    // Vehiculos
    'vehiculos/nuevo' => 'CarController:create',
    'vehiculos/guardar' => 'CarController:store',
    'vehiculos/editar/' => 'CarController:edit',
    'vehiculos/actualizar/' => 'CarController:update',
    'vehiculos/borrar/' => 'CarController:erase',
    'vehiculos/eliminar/' => 'CarController:deleteSoft',
    
    // Tareas
    'tareas' => 'TaskController:index',
    'tareas/guardar' => 'TaskController:store',
    'tareas/completar/' => 'TaskController:complete',
    'tareas/eliminar/' => 'TaskController:delete',

    // Usuarios
    'usuarios' => 'UserController:index',  
    'usuarios/nuevo' => 'UserController:create',  
    'usuarios/guardar' => 'UserController:store',  
    'usuarios/editar/' => 'UserController:edit',  
    'usuarios/actualizar/' => 'UserController:update',  
    
    // Usuarios de bodega y reempacado
    // Bodega USA o MEX by Session live
    'bodega/' => 'WarehouseController:index',
    'bodega/registrar/' => 'WarehouseController:register',
    'bodega/seleccionar/' => 'WarehouseController:choose',
    'bodega/medir/' => 'WarehouseController:takeMeasures',
    'bodega/actualizar/' => 'WarehouseController:updateMeasures',

    // Reempacado
    'reempacado' => 'RepackagedController:index',
    'reempacado/seleccionar/' => 'RepackagedController:choose',
    'reempacado/actualizar/' => 'RepackagedController:update',

    // Carga y descarga de paquetes a transportadora(paqueteria)
    'transportadora/carga' => 'DropOffStageController:load',
    'transportadora/cargando' => 'DropOffStageController:loading',
    'transportadora/descarga' => 'DropOffStageController:unload',
    'transportadora/descargando' => 'DropOffStageController:unloading',


    // Redirect to default-route if the user-route is not found
    // It is necessary to assign the keyname of some route of this array;
    'default' => 'dashboard' 
];
