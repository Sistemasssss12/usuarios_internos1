<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cat_UsuarioInternos_model extends CI_Model{

  function getTotal(){
    $this->db
    ->select("u.id")
    ->from('usuario as u')
    ->where('u.eliminado', 0)
    ->where('u.status', 1);

    $query = $this->db->get();
    return $query->num_rows();
  } 

  function get(){
    $this->db
    ->select("u.*, CONCAT(u.nombre,' ',u.paterno,' ',u.materno) as referente, r.nombre as nombre_rol")
        ->from('usuario as u')
        ->join('rol as r', 'r.id = u.id_rol')  //JOIN con la tabla 'rol'
        ->where('u.eliminado', 0)
        ->where('u.status', 1);


    $query = $this->db->get();
    if($query->num_rows() > 0){
      return $query->result();
    }else{
      return FALSE;
    }
  }

  /*function verificarUsuarioExistente($correo,$id){
    $this->db
    ->select('id')
    ->from('usuario')
    
    ->where('correo', $correo)
    ->where('id',$id);
    
    $query = $this->db->get();
    return $query->num_rows();
  }*/

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
  

  function editUsuario($id, $usuario) {
   //echo $id.' no hay ' .$datos;
    $this->db
        ->where('id', $id)
        ->update('usuario', $usuario);
    }
  
    /*function eliminarUsuario($id, $usuario){
      $this->db
      ->where('id', $id)
      ->update('usuario');
    } */

    
    function getById($idusuario){
    $this->db
    ->select('*')
    ->from('usuario')
    ->where('id',$idusuario);

    $query = $this->db->get();
    return $query->row();
  }
  
  
  /*function editAccesoUsuarioCliente($usuario, $idusuario){
    $this->db
    ->where('id_usuario', $idusuario)
    ->update('usuario_cliente', $usuario);
  } 
  */
  function addUsuarioInterno($usuario) {
    $this->db->insert("usuario", $usuario);

    // Obtén el ID del último registro insertado
    $insert_id = $this->db->insert_id();

    return $insert_id; // Puedes devolver el ID o cualquier otra cosa que necesites
}

  /*function updateUsuarioInterno($usuario,$id){
    $this->db->update("usuario", $usuario)
     ->where('id',$id);
  } */
  
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

} 