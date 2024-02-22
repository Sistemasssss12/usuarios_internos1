<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cat_UsuarioInternos extends CI_Controller{

	function __construct(){
		parent::__construct();
    if(!$this->session->userdata('id')){
      redirect('Login/index');
    }
		$this->load->library('usuario_sesion');
		$this->usuario_sesion->checkStatusBD();
	}
  
  function index(){
   //$data['permisos'] = $this->usuario_model->getPermisos($this->session->userdata('id'));
   $data['submodulos'] = $this->rol_model->getMenu($this->session->userdata('idrol'));
    foreach($data['submodulos'] as $row) {
      $items[] = $row->id_submodulo;
    } 
    $data['submenus'] = $items;
    //$datos['estados'] = $this->funciones_model->getEstados(); 
    $datos['tipos_bloqueo'] = $this->funciones_model->getTiposBloqueo();
    $datos['tipos_desbloqueo'] = $this->funciones_model->getTiposDesbloqueo(); 
    $datos['modals'] = $this->load->view('modals/mdl_catalogos/mdl_registro_usuariointe','', TRUE);

    $config = $this->funciones_model->getConfiguraciones();
		$data['version'] = $config->version_sistema;

    //Modals
    $modales['modals'] = $this->load->view('modals/mdl_usuario','', TRUE);

    $notificaciones = $this->notificacion_model->get_by_usuario($this->session->userdata('id'), [0,1]);
    if(!empty($notificaciones)){
      $contador = 0;
      foreach($notificaciones as $row){
        if($row->visto == 0){
          $contador++;
        }
      }
      $data['contadorNotificaciones'] = $contador;
    }
    
    $this->load
    ->view('adminpanel/header',$data)
    ->view('adminpanel/scripts',$modales)
    ->view('catalogos/usuarios_interno',$datos)
    ->view('adminpanel/footer');
  }
  function get(){
		$usuarios_interno['recordsTotal'] = $this->Cat_UsuarioInternos_model->getTotal();
    $usuarios_interno['recordsFiltered'] = $this->Cat_UsuarioInternos_model->getTotal();
    $usuarios_interno['data'] = $this->Cat_UsuarioInternos_model->get();
    $this->output->set_output( json_encode( $usuarios_interno ) );
	}
  /*function set(){
    $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim');
    $this->form_validation->set_rules('clave', 'Clave', 'required|trim|max_length[3]');

    $this->form_validation->set_message('required','El campo {field} es obligatorio');
    $this->form_validation->set_message('max_length','El campo {field} debe tener máximo {param} carácteres');

    $msj = array();
    if ($this->form_validation->run() == FALSE) {
      $msj = array(
        'codigo' => 0,
        'msg' => validation_errors()
      );
    } 
    else {
      $id_usuario = $this->session->userdata('id');
      date_default_timezone_set('America/Mexico_City');
      $date = date('Y-m-d H:i:s');

      $existe = $this->cat_cliente_model->existe($this->input->post('nombre'),$this->input->post('clave'),$this->input->post('id'));
      if($existe == 0){
        $hayId = $this->cat_cliente_model->check($this->input->post('id'));
        if($hayId > 0){
          $cliente = array(
            'edicion' => $date,
            'id_usuario' => $id_usuario,
            'nombre' => $this->input->post('nombre'),
            'clave' => $this->input->post('clave'),
          );
          $this->cat_cliente_model->edit($cliente, $this->input->post('id'));
          $permiso = array(
            'id_usuario' => $id_usuario,
            'cliente' => $this->input->post('nombre')
          );
          $this->cat_cliente_model->editPermiso($permiso, $this->input->post('id'));
          $msj = array(
            'codigo' => 1,
            'msg' => 'success'
          );
        }
        else{
          $cliente = array(
            'creacion' => $date,
            'edicion' => $date,
            'id_usuario' => $id_usuario,
            'nombre' => strtoupper($this->input->post('nombre')),
            'clave' => $this->input->post('clave'),
            'icono' => '<i class="fas fa-user-tie"></i>'
          );
          $id = $this->cat_cliente_model->add($cliente);

          $url = "Cliente_General/index/".$id;
          $data_url = array(
            'url' => $url
          );
          $this->cat_cliente_model->edit($data_url, $id);

          $permiso = array(
            'id_usuario' => $id_usuario,
            'id_cliente' => $id,
            'cliente' => strtoupper($this->input->post('nombre')),
          );
          $this->cat_cliente_model->addPermiso($permiso);
          $msj = array(
            'codigo' => 1,
            'msg' => 'success'
          );
        }
      }
      else{
        $msj = array(
          'codigo' => 2,
          'msg' => 'El nombre del cliente y/o clave ya existe'
        );
      }
    }
    echo json_encode($msj);
  } */
  /*function status(){
    $id_usuario = $this->session->userdata('id');
    $date = date('Y-m-d H:i:s');
    $idCliente = $this->input->post('id');
    $accion = $this->input->post('accion');
    if($accion == "desactivar"){
      $data = array(
        'edicion' => $date,
        'id_usuario' => $id_usuario,
        'status' => 0
      );
      $this->cat_cliente_model->edit($data, $idCliente);
      $this->cat_cliente_model->editAccesoUsuarioCliente($data, $idCliente);
      $this->cat_cliente_model->editAccesoUsuarioSubcliente($data, $idCliente);
      $msj = array(
        'codigo' => 1,
        'msg' => 'Cliente inactivado correctamente'
      );
    }
    if($accion == "activar"){
      $data = array(
        'edicion' => $date,
        'id_usuario' => $id_usuario,
        'status' => 1
      );
      $this->cat_cliente_model->edit($data, $idCliente);
      $this->cat_cliente_model->editAccesoUsuarioCliente($data, $idCliente);
      $this->cat_cliente_model->editAccesoUsuarioSubcliente($data, $idCliente);

      $msj = array(
        'codigo' => 1,
        'msg' => 'Cliente activado correctamente'
      );
    }
    if($accion == "eliminar"){
      $data = array(
        'edicion' => $date,
        'id_usuario' => $id_usuario,
        'eliminado' => 1
      );
      $this->cat_cliente_model->edit($data, $idCliente);
      $this->cat_cliente_model->editAccesoUsuarioCliente($data, $idCliente);
      $this->cat_cliente_model->editAccesoUsuarioSubcliente($data, $idCliente);
      $msj = array(
        'codigo' => 1,
        'msg' => 'Cliente eliminado correctamente'
      );
    }
    if($accion == "bloquear"){
      $cliente = array(
        'edicion' => $date,
        'id_usuario' => $id_usuario,
        'bloqueado' => $this->input->post('opcion_motivo')
      );
      $this->cat_cliente_model->edit($cliente, $idCliente);

      if($this->input->post('bloquear_subclientes') === 'SI'){
        $data['subclientes'] = $this->cat_subclientes_model->getSubclientesByIdCliente($idCliente);
        if($data['subclientes']){
          foreach($data['subclientes'] as $row){
            $subcliente = array(
              'edicion' => $date,
              'id_usuario' => $id_usuario,
              'bloqueado' => $this->input->post('opcion_motivo')
            );
            $this->cat_subclientes_model->editar($subcliente, $row->id);
            unset($subcliente);
          }
        }
      }
      
      $dataBloqueos = array(
        'status' => 0
      );
      $this->cat_cliente_model->editHistorialBloqueos($dataBloqueos, $idCliente);

      $data_bloqueo = array(
        'creacion' => $date,
        'id_usuario' => $id_usuario,
        'descripcion' => $this->input->post('opcion_descripcion'),
        'id_cliente' => $idCliente,
        'bloqueo_subclientes' => $this->input->post('bloquear_subclientes'),
        'tipo' => 'BLOQUEO',
        'mensaje' => $this->input->post('mensaje_comentario'),
      );
      $this->cat_cliente_model->addHistorialBloqueos($data_bloqueo);
      $msj = array(
        'codigo' => 1,
        'msg' => 'Cliente bloqueado correctamente'
      );
    }
    if($accion == "desbloquear"){
      $cliente = array(
        'edicion' => $date,
        'id_usuario' => $id_usuario,
        'bloqueado' => 'NO'
      );
      $this->cat_cliente_model->edit($cliente, $idCliente);

      $data['subclientes'] = $this->cat_subclientes_model->getSubclientesByIdCliente($idCliente);
      if($data['subclientes']){
        foreach($data['subclientes'] as $row){
          $subcliente = array(
            'edicion' => $date,
            'id_usuario' => $id_usuario,
            'bloqueado' => 'NO'
          );
          $this->cat_subclientes_model->editar($subcliente, $row->id);
          unset($subcliente);
        }
      }
      
      $dataBloqueos = array(
        'status' => 0
      );
      $this->cat_cliente_model->editHistorialBloqueos($dataBloqueos, $idCliente);

      $data_bloqueo = array(
        'creacion' => $date,
        'id_usuario' => $id_usuario,
        'descripcion' => $this->input->post('opcion_descripcion'),
        'id_cliente' => $idCliente,
        'bloqueo_subclientes' => 'NO',
        'tipo' => 'DESBLOQUEO',
        'status' => 0,
      );
      $this->Cat_UsuarioInternos_model->addHistorialBloqueos($data_bloqueo);
      $msj = array(
        'codigo' => 1,
        'msg' => 'Cliente desbloqueado correctamente'
      );
    }
    echo json_encode($msj);
  }   */
  function getActivos(){
    $res = $this->Cat_UsuarioInternos_model->getActivos();
    if($res){
      echo json_encode($res);
    }
    else{
      echo $res = 0;
    }
  } 
  function addUsuarioInterno(){
    $this->form_validation->set_rules('nombre', 'nombre', 'required|trim');
    $this->form_validation->set_rules('paterno', 'paterno', 'required|trim');
    $this->form_validation->set_rules('materno', 'materno', 'required|trim');
    $this->form_validation->set_rules('correo', 'Correo', 'required|trim|valid_email|is_unique[usuario.correo]');
    $this->form_validation->set_rules('password', 'Contraseña', 'required|trim');

    $this->form_validation->set_message('required','El campo %s es obligatorio');
    $this->form_validation->set_message('is_unique','El %s ya esta registrado');
    $this->form_validation->set_message('valid_email','El campo %s debe ser un correo válido');

    $msj = array();
    if ($this->form_validation->run() == FALSE) {
      $msj = array(
        'codigo' => 0,
        'msg' => validation_errors()
      );
    } 
    else {
      $id_usuario = $this->session->userdata('id');
      date_default_timezone_set('America/Mexico_City');
      $date = date('Y-m-d H:i:s');
      $nombre = $this->input->post('nombre');
      $paterno = $this->input->post('paterno');
      $materno = $this->input->post('materno');
      $tipo_rol = $this->input->post('tipo_rol');
      $correo = $this->input->post('correo');
      $uncode_password = $this->input->post('password');
      $base = 'k*jJlrsH:cY]O^Z^/J2)Pz{)qz:+yCa]^+V0S98Zf$sV[c@hKKG07Q{utg%OlODS';
      $password = md5($base.$uncode_password);
      $idUsuario = $this->input->post('id_Usuario');

      $id_usuario = array(
        'creacion' => $date,
        'edicion' => $date,
        'id_Usuario' => $idUsuario ,
        'nombre' => $nombre,
        'paterno' => $paterno,
        'tipo_rol' => $tipo_rol,
        'correo' => $correo,
        'password' => $password,
      
      );
      $this->Cat_UsuarioInternos_model->addUsuarioInterno($usuario);

      $dataCliente = $this->Cat_UsuarioInternos_model->getById($idUsuario );
      if($dataCliente->ingles == 0){
        $existe_cliente = $this->Cat_UsuarioInternos_model->checkPermisosByCliente($idUsuario );
        if($existe_cliente == 0){
          $url = "Cliente_General/index/".$idUsuario ;
          $cliente = array(
            'url' => $url
          );
          $this->cat_cliente_model->edit($cliente, $idUsuario );
          $permiso = array(
            'id_usuario' => $id_usuario,
            'cliente' => $dataCliente->nombre,
            'id_Usuario' => $idUsuario 
          );
          $this->cat_cliente_model->addPermiso($permiso);
        }
      }
      $msj = array(
        'codigo' => 1,
        'msg' => 'success'
      );
    }
    echo json_encode($msj);
  }
  function getClientesAccesos(){
    $id_Usuario = $this->input->post('id_Usuario');
    $res = $this->Cat_UsarioInternos_model->getAccesos($id_Usuario);
    if($res){
      echo json_encode($res);
    }
    else{
      echo $res = 0;
    }
  }

  function controlAcceso(){
    $id_usuario = $this->session->userdata('id');
    date_default_timezone_set('America/Mexico_City');
    $date = date('Y-m-d H:i:s');
    $idUsuarioCliente = $this->input->post('idUsuarioCliente');
    $accion = $this->input->post('accion');

    if($accion == 'eliminar'){
      $this->cat_cliente_model->deleteAccesoUsuarioCliente($idUsuarioCliente);
      $msj = array(
        'codigo' => 1,
        'msg' => 'Usuario eliminado correctamente'
      );
    }
    
    echo json_encode($msj);
  }
  
}