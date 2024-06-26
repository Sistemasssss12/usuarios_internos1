<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cat_cliente_model extends CI_Model{

  function getTotal(){
    $this->db
    ->select("c.id")
    ->from('cliente as c')
    ->where('c.ingles', 0)
    ->where('c.eliminado', 0);

    $query = $this->db->get();
    return $query->num_rows();
  }
  function get(){
    $this->db
    ->select("c.*, CONCAT(uc.nombre,' ',uc.paterno) as referente, uc.correo as ref_correo, uc.nombre as nombreCliente, uc.paterno as paternoCliente, uc.id as idUsuarioCliente, uc.status as statusUsuario, COUNT(uc.id) as numero_accesos")
    ->from('cliente as c')
    ->join('usuario as u','u.id = c.id_usuario',"left")
    ->join('usuario_cliente as uc','uc.id_cliente = c.id',"left")
    ->where('c.eliminado', 0)
    ->order_by('c.creacion','ASC')
    ->group_by('c.id');

    $query = $this->db->get();
    if($query->num_rows() > 0){
      return $query->result();
    }else{
      return FALSE;
    }
  }
  function existe($nombre, $clave, $id){
    $this->db
    ->select('id')
    ->from('cliente')
    ->or_where('nombre', $nombre)
    ->or_where('clave', $clave)
    ->where_not_in('id',$id);
    
    $query = $this->db->get();
    return $query->num_rows();
  }
  function check($id){
    $this->db
    ->select('id')
    ->from('cliente')
    ->where('id', $id);
    
    $query = $this->db->get();
    return $query->num_rows();
  }

  function add($cliente){
    $this->db->insert("cliente", $cliente);
    return $this->db->insert_id();
  }
  function addPermiso($permiso){
    $this->db->insert("permiso", $permiso);
  }
  function edit($cliente, $id){
    $this->db
    ->where('id', $id)
    ->update('cliente', $cliente);
  }
  function editPermiso($permiso, $id_cliente){
    $this->db
    ->where('id_cliente', $id_cliente)
    ->update('permiso', $permiso);
  }
  function getById($idCliente){
    $this->db
    ->select('*')
    ->from('cliente')
    ->where('id',$idCliente);

    $query = $this->db->get();
    return $query->row();
  }
  function checkPermisosByCliente($id_cliente){
    $this->db
    ->select("id")
    ->from('permiso')
    ->where('id_cliente', $id_cliente);

    $query = $this->db->get();
    return $query->num_rows();
  }
  function getAccesos($id_cliente){
    $this->db
    ->select("c.*,CONCAT(u.nombre,' ',u.paterno) as usuario, CONCAT(uc.nombre,' ',uc.paterno) as usuario_cliente, uc.correo as correo_usuario, uc.creacion as alta, uc.id as idUsuarioCliente, uc.privacidad")
    ->from("cliente as c")
    ->join("usuario_cliente as uc","uc.id_cliente = c.id")
    ->join("usuario as u","u.id = uc.id_usuario")
    ->where("c.id", $id_cliente)
    ->order_by("uc.id", 'desc');

    $query = $this->db->get();
    if($query->num_rows() > 0){
      return $query->result();
    }else{
      return FALSE;
    }
  }
  function editAccesoUsuarioCliente($usuario, $idCliente){
    $this->db
    ->where('id_cliente', $idCliente)
    ->update('usuario_cliente', $usuario);
  }
  function editAccesoUsuarioSubcliente($usuario, $idCliente){
    $this->db
    ->where('id_cliente', $idCliente)
    ->update('usuario_subcliente', $usuario);
  }
  function addUsuario($usuario){
    $this->db->insert("usuario_cliente", $usuario);
  }
  function deleteAccesoUsuarioCliente($idUsuarioCliente){
    $this->db
    ->where('id', $idUsuarioCliente)
    ->delete('usuario_cliente');
  }

  
  
  function getActivos(){
    $this->db
    ->select("c.*") 
    ->from('cliente as c')
    ->where('c.status', 1)
    ->where('c.eliminado', 0)
    ->order_by('c.nombre','ASC');

    $query = $this->db->get();
    if($query->num_rows() > 0){
      return $query->result();
    }else{
      return FALSE;
    }
  }





  // obtener  el id  de la  tabla  permisos  con respecto al id  del cliente 
  function getIdPermisocliente($id_cliente) {
    $this->db
        ->select("id") 
        ->from('permiso')
        ->where('id_cliente', $id_cliente)
        ->limit(1); // Limita la consulta a un solo resultado
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
        $row = $query->row(); // Obtiene el primer resultado
        return $row->id; // Devuelve el valor del ID directamente
    } else {
        return FALSE;
    }
}
/********Insertar datos en la tabla usuario_permiso para ver clientes**************************************************************/      
  public function guardarAccesosClientes($datos) {

      $this->db->insert('usuario_permiso', $datos);
      
      if ($this->db->affected_rows() > 0) {
          return true; 
      } else {
          return false; 
      }
  }




/********Guardar psicometria***************************************************/
public function addPsicometria($datos) {

 try{

  $this->db->insert('cliente_control', $datos);
  

 }catch(exception $e){
  return false.$e; 

 }
  if ($this->db->affected_rows() > 0) {
      return true; 
  } else {
      return false; 
  }
}


/********Guardar id de antidoping seleccionado ***************************************************/
public function guadarAntidoping_y_Proyecto($paquete_antidoping_seleccionado,$datos_doping = null) {
  $datos = $paquete_antidoping_seleccionado;
 

  $this->db->insert('cliente_doping', $datos);
  
  if ($this->db->affected_rows() > 0) {
      return true; 
  } else {
      return false; 
  }
}
/**************************************************************************************/
public function get_Proyecto_Y_SubClients($id_cliente) {
  $this->db
      ->select("id, nombre")
      ->from('subcliente')
      ->where('id_cliente', $id_cliente)
      ->where('status', 1)
      ->where('eliminado', 0);

  $query = $this->db->get();
  $subcliente = $query->result();

  $this->db->reset_query();

  // Consulta de proyectos que contienen subclientes y proyectos que no contienen subclientes
  $this->db
      ->select("id, nombre")
      ->from('proyecto')
      ->where('id_cliente', $id_cliente)
      ->where('status', 1)
      ->where('eliminado', 0)
      ->where('id_subcliente', 0);
      
  $query = $this->db->get();
  $proyectos = $query->result();

  return array('subclientes' => $subcliente, 'proyectos' => $proyectos);
}


function get_Proyectos($id_subcliente) {
  // Consulta de proyectos que contienen subclientes y proyectos que no contienen subclientes
  $this->db
      ->select("id, nombre")
      ->from('proyecto')
      ->where('id_subcliente', $id_subcliente)
      ->where('status', 1)
      ->where('eliminado', 0);
  
  $query = $this->db->get();
  $proyectos = $query->result();

  return $proyectos; 
}

/***********************************************************************************/
function getPaquetesAntidoping(){
  $this->db
    ->select("doping_paq.id, doping_paq.nombre, doping_paq.conjunto")
    ->from('antidoping_paquete as doping_paq')
    ->where('status', 1)
    ->where('eliminado', 0)
    ->order_by('id','ASC');

  $query = $this->db->get();
  if($query->num_rows() > 0){
      return $query->result();
  }else{
      return FALSE;
  }
}
/*********************************************************************************/
public function  getVisibilidad() {
  $this->db
      ->select("c.id as cliente_id, c.nombre as cliente_nombre")
      ->from('cliente as c')
      ->where('c.status', 1)
      ->where('c.eliminado', 0)
      ->order_by('c.nombre', 'ASC');

  $query = $this->db->get();
  $clientes = $query->result();

  // Restablecer la consulta para obtener los datos de usuarios con los IDs seleccionados
  $this->db->reset_query();
  
  $this->db
      ->select("u.id as usuario_id, u.nombre as usuario_nombre, u.paterno as usuario_paterno")
      ->from('usuario as u')
      ->where('u.status', 1)
      ->where('u.eliminado', 0)
      ->where_in('u.id_rol', array(1, 2, 6, 9)) // Filtrar por roles específicos
      ->order_by('u.nombre', 'ASC')
      ->order_by('u.paterno', 'ASC'); 

  $query = $this->db->get();
  $usuarios = $query->result();

  return array('clientes' => $clientes, 'usuarios' => $usuarios);
}
/**************************************************************************************/

  function getUsuariosClientePorCandidato($id_candidato){
    $this->db
    ->select("cl.correo, CONCAT(c.nombre,' ',c.paterno,' ',c.materno) as candidato, c.privacidad as privacidadCandidato, cl.privacidad as privacidadCliente")
    ->from('candidato as c')
    ->join("usuario_cliente as cl","cl.id_cliente = c.id_cliente")
    ->where('c.id', $id_candidato);

    $query = $this->db->get();
    if($query->num_rows() > 0){
      return $query->result();
    }else{
      return FALSE;
    }
  }
  function addHistorialBloqueos($data){
    $this->db->insert("bloqueo_historial", $data);
  }
  function editHistorialBloqueos($dataBloqueos, $idCliente){
    $this->db
    ->where('id_cliente', $idCliente)
    ->update('bloqueo_historial', $dataBloqueos);
  }
  function getBloqueoHistorial($id_cliente){
    $this->db
    ->select("*")
    ->from('bloqueo_historial')
    ->where('status', 1)
    ->where('id_cliente', $id_cliente);

    $consulta = $this->db->get();
    return $consulta->row();
  }
}