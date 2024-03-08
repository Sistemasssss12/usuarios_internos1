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
    $data['submodulos'] = $this->rol_model->getMenu($this->session->userdata('idrol'));
    foreach($data['submodulos'] as $row) {
      $items[] = $row->id_submodulo;
    } 
    $data['submenus'] = $items;
  
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


  public function editarUsuario() {




    $id = $this->input->post('id');
    $nombre = $this->input->post('nombre');
    $paterno = $this->input->post('paterno');
    $id_rol = $this->input->post('id_rol');
    $correo = $this->input->post('correo');

    // Realizar lógica para actualizar el usuario en la base de datos
    // utilizar modelo o el acceso a la base de datos directamente

    //$this->load->model('Cat_UsuarioInternos_model'); -->QUITAR
    $data = array(
        'nombre' => $nombre,
        'paterno' => $paterno,
        'id_rol' => $id_rol,
        'correo' => $correo
    );
    $this->Cat_UsuarioInternos_model->edit($id, $data);

    $response = array(
        'codigo' => 1, 
        'msg' => 'Usuario actualizado correctamente'
    );
    echo json_encode($response);
} 

  //---------LIGADA A LA FUNCION DE registroUsuariosInternos DEL CATALOGO USUAIOS_INTERNOS
  function addUsuarioInterno(){
    $this->form_validation->set_rules('nombre', 'nombre', 'required|trim');
    $this->form_validation->set_rules('paterno', 'paterno', 'required|trim');
    $this->form_validation->set_rules('materno', 'materno|trim');
  
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
      $id_rol = $this->input->post('id_rol');
      $correo = $this->input->post('correo');
      $uncode_password = $this->input->post('password');
      $base = 'k*jJlrsH:cY]O^Z^/J2)Pz{)qz:+yCa]^+V0S98Zf$sV[c@hKKG07Q{utg%OlODS';
      $password = md5($base.$uncode_password);
      $idUsuario = $this->input->post('id_usuario');

      $UsuariosInternos = array(
        'creacion' => $date,
        'edicion' => $date,
        'id_usuario' => $id_usuario ,
        'nombre' => $nombre,
        'paterno' => $paterno,
        'id_rol' => $id_rol,
        'correo' => $correo,
        'password' => $password,
      
      );

    
      $this->Cat_UsuarioInternos_model->addUsuarioInterno($UsuariosInternos);
      $msj = array(
        'codigo' => 1,
        'msg' => 'success'
      );
    }
    echo json_encode($msj);
  }

  
//__________________________________________________________________________________


  function status(){
    $id_usuario = $this->session->userdata('id');
    $date = date('Y-m-d H:i:s');
    $idUsuario = $this->input->post('id');
    $accion = $this->input->post('accion');
    
    if($accion == "eliminar"){
      $data = array(
        'edicion' => $date,
        'id_usuario' => $id_usuario,
        'eliminado' => 1
      );
      //$this->cat_UsuarioInternos_model->edit($data, $idUsuario);
      $this->cat_UsuarioInternos_model->editUsuarioInterno($data, $idUsuario);
      $this->cat_UsuarioInternos_model->editUsuarioInterno($data, $idUsuario);
      $msj = array(
        'codigo' => 1,
        'msg' => 'Usuario eliminado correctamente'
      );
    }
  
    echo json_encode($msj); 
  }  

  function getActivos(){
    $res = $this->Cat_UsuarioInternos_model->getActivos();
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