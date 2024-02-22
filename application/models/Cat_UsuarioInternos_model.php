<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cat_UsuarioInternos_model extends CI_Model{

  function getTotal(){
    $this->db
    ->select("u.id")
    ->from('usuario as u')
    ->where('u.eliminado', 0)
    ->where('u.eliminado', 0);

    $query = $this->db->get();
    return $query->num_rows();
  }
  function get(){
    $this->db
    ->select("u.*, CONCAT(u.nombre,' ',u.paterno,' ',u.materno) as referente")
    ->from('usuario as u')
    //->join('usuario as u','u.id = u.id_usuario',"left")
    //->join('usuario_cliente as uc','uc.id_Usuario = c.id',"left")
    ->where('u.eliminado', 0)
    ->order_by('u.creacion','ASC')
    ->group_by('u.id');

    $query = $this->db->get();
    if($query->num_rows() > 0){
      return $query->result();
    }else{
      return FALSE;
    }
  }
  function existe($nombre,$id){
    $this->db
    ->select('id')
    ->from('usuario')
    ->or_where('nombre', $nombre)
    ->where_not_in('id',$id);
    
    $query = $this->db->get();
    return $query->num_rows();
  }
  function check($id){
    $this->db
    ->select('id')
    ->from('usuario')
    ->where('id', $id);
    
    $query = $this->db->get();
    return $query->num_rows();
  }

  function add($usuario){
    $this->db->insert("usuario", $usuario);
    return $this->db->insert_id();
  }
  function addPermiso($permiso){
    $this->db->insert("permiso", $permiso);
  }
  function edit($usuario, $id){
    $this->db
    ->where('id', $id)
    ->update('usuario', $usuario);
  }
  function editPermiso($permiso, $id_usuario){
    $this->db
    ->where('id_usuario', $id_usuario)
    ->update('permiso', $permiso);
  }
  function getById($idusuario){
    $this->db
    ->select('*')
    ->from('usuario')
    ->where('id',$idusuario);

    $query = $this->db->get();
    return $query->row();
  }
  /*function checkPermisosByCliente($id_cliente){
    $this->db
    ->select("id")
    ->from('permiso')
    ->where('id_cliente', $id_cliente);

    $query = $this->db->get();
    return $query->num_rows();
  }*/
  function getAccesos($id_usuario){
    $this->db
    ->select("u.*,CONCAT(u.nombre,' ',u.paterno',uc.materno) as usuario, CONCAT(u.nombre,' ',u.paterno',uc.materno) as usuario_cliente, uc.correo as correo_usuario, uc.creacion as alta, uc.id as idUsuarioCliente, uc.id_rol")
    ->from("usuario as u")
    ->join("usuario_cliente as uc","uc.id_Usuario = c.id")
    ->join("usuario as u","u.id = uc.id_usuario")
    ->where("u.id", $id_usuario)
    ->order_by("uc.id", 'desc');

    $query = $this->db->get();
    if($query->num_rows() > 0){
      return $query->result();
    }else{
      return FALSE;
    }
  }
  function editAccesoUsuarioCliente($usuario, $idUsuario){
    $this->db
    ->where('id_usuario', $idUsuario)
    ->update('usuario_cliente', $usuario);
  }
  function editAccesoUsuarioSubcliente($usuario, $idUsuario){
    $this->db
    ->where('id_usuario', $idUsuario)
    ->update('usuario_subcliente', $usuario);
  }
  function addUsuarioInterno($usuario){
    $this->db->insert("usuario", $usuario);
  }
  function deleteAccesoUsuarioCliente($idUsuarioCliente){
    $this->db
    ->where('id', $idUsuarioCliente)
    ->delete('usuario_cliente');
  }

  
  
  
  function getActivos(){
    $this->db
    ->select("u.*")
    ->from('usuario as u')
    ->where('u.status', 1)
    ->where('u.eliminado', 0)
    ->order_by('u.nombre','ASC');

    $query = $this->db->get();
    if($query->num_rows() > 0){
      return $query->result();
    }else{
      return FALSE;
    }
  }

  function getUsuariosClientePorCandidato($id_usuario){
    $this->db
    ->select("cl.correo, CONCAT(c.nombre,' ',c.paterno,' ',c.materno) as candidato, c.privacidad as privacidadCandidato, cl.privacidad as privacidadCliente")
    ->from('candidato as c')
    ->join("usuario_cliente as cl","cl.id_cliente = c.id_cliente")
    ->where('c.id', $id_usuario);

    $query = $this->db->get();
    if($query->num_rows() > 0){
      return $query->result();
    }else{
      return FALSE;
    }
  }
  /*function addHistorialBloqueos($data){
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
  }*/
} 