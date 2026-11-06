<?php namespace Controllers;

use System\Core\Controller;

use Models\Entry;

// Portal de solo lectura para el rol cliente. Nunca consulta ni muestra observaciones.
class ClientPortalController extends Controller {

    public function index()
    {
        if( ! $client_id = $this->clientId() )
            return redirect('signout');

        $search = $this->request->has('q', 'get') ? $this->request->get('q', 'get') : '';
        if( !is_string($search) )
        {
            $this->message(['danger', 'El término de búsqueda no es válido']);
            return redirect('cliente');
        }
        $search = trim($search);
        $db = new Entry;

        $consolidated = $db->raw(
            "SELECT cs.id, cs.numero, cs.created_at, cs.cerrado_at,
                (SELECT COUNT(*) FROM entradas WHERE consolidado_id = cs.id) AS entries_count
             FROM consolidados cs WHERE cs.cliente_id = {$client_id} ORDER BY cs.id DESC", true);

        $where = '';
        if( $search !== '' ) {
            $like = \System\Core\Connection::getInstance()->quote('%'.$search.'%');
            $where = " AND (e.numero LIKE {$like} OR cs.numero LIKE {$like} OR wo.rastreo LIKE {$like})";
        }

        $entries = $db->raw(
            "SELECT e.id, e.numero, e.created_at, cs.numero AS consolidado_numero,
                wo.rastreo, wo.status, t.nombre AS transportadora
             FROM entradas e
             LEFT JOIN consolidados cs ON cs.id = e.consolidado_id
             LEFT JOIN salidas wo ON wo.entrada_id = e.id
             LEFT JOIN transportadoras t ON t.id = wo.transportadora_id
             WHERE e.cliente_id = {$client_id} {$where}
             ORDER BY e.id DESC LIMIT 200", true);

        return view('portal/index', compact('consolidated', 'entries', 'search'));
    }

    public function showConsolidated($id = null)
    {
        if( ! $client_id = $this->clientId() )
            return redirect('signout');

        $db = new Entry;
        $id = (int) $id;
        $rows = $db->raw("SELECT id, numero, created_at, cerrado_at FROM consolidados
                          WHERE id = {$id} AND cliente_id = {$client_id} LIMIT 1", true);
        if( ! $rows )
            return redirect('cliente');

        $consolidated = array_pop($rows);
        $entries = $db->raw(
            "SELECT e.id, e.numero, e.created_at, wo.rastreo, wo.status, t.nombre AS transportadora
             FROM entradas e
             LEFT JOIN salidas wo ON wo.entrada_id = e.id
             LEFT JOIN transportadoras t ON t.id = wo.transportadora_id
             WHERE e.consolidado_id = {$id} AND e.cliente_id = {$client_id}
             ORDER BY e.id DESC", true);

        return view('portal/consolidated', compact('consolidated', 'entries'));
    }

    public function showEntry($id = null)
    {
        if( ! $client_id = $this->clientId() )
            return redirect('signout');

        $db = new Entry;
        $id = (int) $id;
        $rows = $db->raw(
            "SELECT e.id, e.numero, e.created_at, e.cruce_at, e.reempacado_at, e.recibido_at,
                e.en_bodega_usa_by IS NOT NULL AS en_bodega_usa, e.en_bodega_mex_by IS NOT NULL AS en_bodega_mex,
                cs.id AS consolidado_id, cs.numero AS consolidado_numero,
                r.nombre AS remitente_nombre, r.telefono AS remitente_telefono, r.direccion AS remitente_direccion,
                r.ciudad AS remitente_ciudad, r.estado AS remitente_estado, r.pais AS remitente_pais,
                a.nombre AS destinatario_nombre, a.telefono AS destinatario_telefono, a.direccion AS destinatario_direccion,
                a.ciudad AS destinatario_ciudad, a.estado AS destinatario_estado, a.pais AS destinatario_pais,
                wo.rastreo, wo.status, wo.confirmacion, wo.cobertura,
                wo.direccion AS salida_direccion, wo.postal AS salida_postal, wo.ciudad AS salida_ciudad,
                wo.estado AS salida_estado, wo.pais AS salida_pais, t.nombre AS transportadora
             FROM entradas e
             LEFT JOIN consolidados cs ON cs.id = e.consolidado_id
             LEFT JOIN entrada_remitente r ON r.entrada_id = e.id
             LEFT JOIN entrada_destinatario a ON a.entrada_id = e.id
             LEFT JOIN salidas wo ON wo.entrada_id = e.id
             LEFT JOIN transportadoras t ON t.id = wo.transportadora_id
             WHERE e.id = {$id} AND e.cliente_id = {$client_id} LIMIT 1", true);
        if( ! $rows )
            return redirect('cliente');

        $entry = array_pop($rows);
        return view('portal/entry', compact('entry'));
    }

    private function clientId()
    {
        $user_id = (int) session_get('user', 'id');
        $rows = (new Entry)->raw("SELECT id FROM clientes WHERE usuario_id = {$user_id} AND deleted_at IS NULL LIMIT 1", true);
        return $rows ? (int) array_pop($rows)->id : 0;
    }
}
