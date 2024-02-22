<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cliente_Monex extends CI_Controller{

  function __construct(){
    parent::__construct();
    if(!$this->session->userdata('id')){
      redirect('Login/index');
    }
		$this->load->library('usuario_sesion');
		$this->usuario_sesion->checkStatusBD();
  }

  function index(){
    if ($this->session->userdata('logueado') && $this->session->userdata('tipo') == 1) {
      $id_cliente = $this->uri->segment(3);
      $data['permisos'] = $this->usuario_model->getPermisos($this->session->userdata('id'));
      if ($data['permisos']) {
        foreach ($data['permisos'] as $p) {
          if ($p->id_cliente == $id_cliente) {
            $data['cliente'] = $p->nombreCliente;
          }
        }
      }
      $data['submodulos'] = $this->rol_model->getMenu($this->session->userdata('idrol'));
      foreach($data['submodulos'] as $row) {
        $items[] = $row->id_submodulo;
      }
      $data['submenus'] = $items;
      $info['estados'] = $this->funciones_model->getEstados();
      $info['civiles'] = $this->funciones_model->getEstadosCiviles();
      $data['baterias'] = $this->funciones_model->getBateriasPsicometricas();
      $info['subclientes'] = $this->cliente_general_model->getSubclientes($id_cliente);
      $data['personales'] = $this->funciones_model->getTiposPersona();
      $info['puestos'] = $this->funciones_model->getPuestos();
      $info['grados'] = $this->funciones_model->getGradosEstudio();
      $info['drogas'] = $this->funciones_model->getPaquetesAntidoping();
      $data['parentescos'] = $this->funciones_model->getParentescos();
      $info['escolaridades'] = $this->funciones_model->getEscolaridades();
      $info['parentescos'] = $this->funciones_model->getParentescos();
      $data['escolaridades'] = $this->funciones_model->getEscolaridades();
      $info['grados_estudios'] = $this->funciones_model->getGradosEstudio();
      $info['zonas'] = $this->funciones_model->getNivelesZona();
      $info['viviendas'] = $this->funciones_model->getTiposVivienda();
      $info['condiciones'] = $this->funciones_model->getTiposCondiciones();
      $data['examenes_doping'] = $this->funciones_model->getExamenDoping($id_cliente);
      $info['studies'] = $this->funciones_model->getTiposEstudios();
      $info['cands'] = $this->cliente_general_model->getCandidatosCliente($id_cliente);
      $info['usuarios'] = $this->funciones_model->getTipoAnalistas(1);
      $info['tipos_docs'] = $this->funciones_model->getTiposDocumentos();
			$info['paquetes_antidoping'] = $this->funciones_model->getPaquetesAntidoping();


      $vista['modals'] = $this->load->view('modals/mdl_clientes_alterno', $info, TRUE);
      $config = $this->funciones_model->getConfiguraciones();
			$data['version'] = $config->version_sistema;

      //Modals
		  $modales['modals'] = $this->load->view('modals/mdl_usuario','', TRUE);

      //$cliente = $this->cliente_general_model->getDatosCliente($id_cliente);
      $this->load
      ->view('adminpanel/header', $data)
      ->view('adminpanel/scripts', $modales)
      ->view('analista/candidatos_espanol_alterno', $vista)
      ->view('adminpanel/footer');
    }
  }
  /*----------------------------------------*/
  /*  Consultas 
  /*----------------------------------------*/
    function getCandidatos(){
      $id_cliente = $_GET['id'];
      $cand['recordsTotal'] = $this->cliente_alternativo_model->getTotal($id_cliente, $this->session->userdata('idrol'), $this->session->userdata('id'));
      $cand['recordsFiltered'] = $this->cliente_alternativo_model->getTotal($id_cliente, $this->session->userdata('idrol'), $this->session->userdata('id'));
      $cand['data'] = $this->cliente_alternativo_model->getCandidatos($id_cliente, $this->session->userdata('idrol'), $this->session->userdata('id'));
      $this->output->set_output( json_encode( $cand ) );
    }
    function getReferenciasPersonales(){
      $id_candidato = $this->input->post('id_candidato');
      $salida = "";
      $data['refs'] = $this->candidato_model->getReferenciasPersonales($id_candidato);
      if($data['refs']){
          foreach($data['refs'] as $ref){
            $salida .= $ref->nombre."@@";
            $salida .= $ref->telefono."@@";
            $salida .= $ref->tiempo_conocerlo."@@";
            $salida .= $ref->donde_conocerlo."@@";
            $salida .= $ref->sabe_trabajo."@@";
            $salida .= $ref->sabe_vive."@@";
            $salida .= $ref->recomienda."@@";
            $salida .= $ref->comentario."@@";
            $salida .= $ref->id."###";
          }
          echo $salida;
      }
      else{
          echo $salida = 0;
      }
    }
    function getPersonasMismoTrabajo(){
      $id_candidato = $this->input->post('id_candidato');
      $salida = "";
      $data['personas'] = $this->candidato_model->getPersonasMismoTrabajo($id_candidato);
      if($data['personas']){
          foreach($data['personas'] as $p){
            $salida .= $p->id."@@";
            $salida .= $p->nombre."@@";
            $salida .= $p->puesto."###";
          }
          echo $salida;
      }
      else{
          echo $salida = 0;
      }
    }
    function getReferenciasLaborales(){
      $id_candidato = $this->input->post('id_candidato');
      $salida = "";
      $data['referencias'] = $this->candidato_model->getReferenciasLaborales($id_candidato);
      if($data['referencias']){
          foreach($data['referencias'] as $ref){
              $salida .= $ref->empresa."@@";
              $salida .= $ref->direccion."@@";
              $salida .= $ref->fecha_entrada_txt."@@";
              $salida .= $ref->fecha_salida_txt."@@";
              $salida .= $ref->telefono."@@";
              $salida .= $ref->puesto1."@@";
              $salida .= $ref->puesto2."@@";
              $salida .= $ref->salario1_txt."@@";
              $salida .= $ref->salario2_txt."@@";
              $salida .= $ref->jefe_nombre."@@";
              $salida .= $ref->jefe_correo."@@";
              $salida .= $ref->jefe_puesto."@@";
              $salida .= $ref->causa_separacion."@@";
              $salida .= $ref->id."###";
          }
          
      }
      echo $salida;
    }
    function getVerificacionesLaborales(){
      $id_candidato = $_POST['id_candidato'];
      $salida = "";
      $data['referencias'] = $this->candidato_model->getVerificacionReferencias($id_candidato);
      if($data['referencias']){
        foreach($data['referencias'] as $ref){
          $salida .= $ref->empresa."@@";
          $salida .= $ref->direccion."@@";
          $salida .= $ref->fecha_entrada_txt."@@";
          $salida .= $ref->fecha_salida_txt."@@";
          $salida .= $ref->telefono."@@";
          $salida .= $ref->puesto1."@@";
          $salida .= $ref->puesto2."@@";
          $salida .= $ref->salario1_txt."@@";
          $salida .= $ref->salario2_txt."@@";
          $salida .= $ref->jefe_nombre."@@";
          $salida .= $ref->jefe_correo."@@";
          $salida .= $ref->jefe_puesto."@@";
          $salida .= $ref->causa_separacion."@@";
          $salida .= $ref->notas."@@";
          $salida .= $ref->cualidades."@@";
          $salida .= $ref->mejoras."@@";
          $salida .= $ref->id."@@";
          $salida .= $ref->numero_referencia."###";
        }
      }
      echo $salida;
    }
    
    function checkConclusionesCandidato(){
      $id_candidato = $this->input->post('id_candidato');
      $num = $this->candidato_model->checkConclusionesCandidato($id_candidato);
      if($num > 0){
          echo $salida = 1;
      }
      else{
          echo $salida = 0;
      }
    }
    function getComentariosRefPersonales(){
      $id_candidato = $this->input->post('id_candidato');
      $salida = "";
      $data['ref'] = $this->candidato_model->getComentariosRefPersonales($id_candidato);
      if($data['ref']){
          foreach($data['ref'] as $ref){
              $salida .= $ref->comentario.", ";
          }
          $res = trim($salida, ", ");
          echo $res;
      }
      else{
          echo $salida;
      }
    }
    function countReferenciasLaborales(){
      $id_candidato = $this->input->post('id_candidato');
      $numero = $this->candidato_model->countReferenciasLaborales($id_candidato);
      echo $numero;
    }
    function getComentariosRefLaborales(){
      $id_candidato = $this->input->post('id_candidato');
      $salida = "";
      $data['ref'] = $this->candidato_model->getComentariosRefLaborales($id_candidato);
      if($data['ref']){
          foreach($data['ref'] as $ref){
              $salida .= $ref->notas.", ";
          }
          $res = trim($salida, ", ");
          echo $res;
      }
      else{
          echo $salida;
      }
    }
    function getComentariosRefVecinales(){
      $id_candidato = $this->input->post('id_candidato');
      $salida = "";
      $data['ref'] = $this->candidato_model->getComentariosRefVecinales($id_candidato);
      if($data['ref']){
          foreach($data['ref'] as $ref){
              $salida .= $ref->concepto_candidato.", ";
          }
          $res = trim($salida, ", ");
          echo $res;
      }
      else{
          echo $salida;
      }
    }
  /*----------------------------------------*/
  /*  Proceso 
  /*----------------------------------------*/
    function registrar(){
      if($this->input->post('previo') != 0){
        $this->form_validation->set_rules('nombre', 'Nombre(s)', 'required|trim');
        $this->form_validation->set_rules('paterno', 'Apellido paterno', 'required|trim');
        $this->form_validation->set_rules('materno', 'Apellido materno', 'trim');
        $this->form_validation->set_rules('subcliente', 'Subcliente', 'required');
        $this->form_validation->set_rules('puesto', 'Puesto', 'required');
        $this->form_validation->set_rules('celular', 'Teléfono', 'required|trim|max_length[16]');
        $this->form_validation->set_rules('previo', 'Proceso previo', 'required');
        $this->form_validation->set_rules('examen', 'Examen antidoping', 'required');
        $this->form_validation->set_rules('medico', 'Examen Médico', 'required');
        $this->form_validation->set_rules('psicometrico', 'Examen Psicométrico', 'required');

        $this->form_validation->set_message('required', 'El campo {field} es obligatorio');
        $this->form_validation->set_message('max_length', 'El campo {field} debe tener máximo {param} carácteres');
        $this->form_validation->set_message('valid_email', 'El campo {field} debe ser un correo válido');
        $this->form_validation->set_message('numeric','El campo {field} debe ser numérico');
        
        $msj = array();
        if ($this->form_validation->run() == FALSE) {
          $msj = array(
            'codigo' => 0,
            'msg' => validation_errors()
          );
        }
        else{
          if($this->session->userdata('idcliente') != null){
            $id_cliente = $this->session->userdata('idcliente');
          }
          else{
            $id_cliente = $this->input->post('id_cliente');
          }
          $nombre = strtoupper($this->input->post('nombre'));
          $paterno = strtoupper($this->input->post('paterno'));
          $materno = strtoupper($this->input->post('materno'));
          $celular = $this->input->post('celular');
          $id_subcliente = $this->input->post('subcliente');
          $id_puesto = $this->input->post('puesto');
          $id_proyecto_previo = $this->input->post('previo');
          $examen = $this->input->post('examen');
          $medico = $this->input->post('medico');
          $psicometrico = $this->input->post('psicometrico');
          $correo = '';
          $existeCandidato = $this->candidato_model->repetidoCandidato($nombre, $paterno, $materno, $correo, $id_cliente);
          if($existeCandidato > 0){
            $msj = array(
              'codigo' => 2,
              'msg' => 'El candidato ya existe'
            );
          }
          else{
            date_default_timezone_set('America/Mexico_City');
            $date = date('Y-m-d H:i:s');
            $usuario = $this->input->post('usuario');
            switch ($usuario) {
              case 1:
                $tipo_usuario = "id_usuario";
                break;
              case 2:
                $tipo_usuario = "id_usuario_cliente";
                break;
              case 3:
                $tipo_usuario = "id_usuario_subcliente";
                break;
            }
            $id_usuario = $this->session->userdata('id');

            $seccion = $this->candidato_model->getProyectoPrevio($id_proyecto_previo);

            if ($usuario == 2 || $usuario == 3) {
              $configuracion = $this->funciones_model->getConfiguraciones();
              $data = array(
                'creacion' => $date,
                'edicion' => $date,
                $tipo_usuario => $id_usuario,
                'id_usuario' => $configuracion->usuario_lider_espanol,
                'id_cliente' => $id_cliente,
                'id_subcliente' => $id_subcliente,
                'id_tipo_proceso' => 1,
                'id_puesto' => $id_puesto,
                'fecha_alta' => $date,
                'nombre' => strtoupper($nombre),
                'paterno' => strtoupper($paterno),
                'materno' => strtoupper($materno),
                'celular' => $celular,
                'subproyecto' => $seccion->proyecto
              );
            } 
            else {
              $data = array(
                'creacion' => $date,
                'edicion' => $date,
                $tipo_usuario => $id_usuario,
                'id_cliente' => $id_cliente,
                'id_subcliente' => $id_subcliente,
                'id_tipo_proceso' => 1,
                'id_puesto' => $id_puesto,
                'fecha_alta' => $date,
                'nombre' => strtoupper($nombre),
                'paterno' => strtoupper($paterno),
                'materno' => strtoupper($materno),
                'celular' => $celular,
                'subproyecto' => $seccion->proyecto
              );
            }
            $id_candidato = $this->candidato_model->registrarRetornaCandidato($data);

            //Subida y Registro de CV
            if ($this->input->post('hay_cvs') == 1) {
              $countfiles = count($_FILES['cvs']['name']);

              for ($i = 0; $i < $countfiles; $i++) {
                if (!empty($_FILES['cvs']['name'][$i])) {
                  // Define new $_FILES array - $_FILES['file']
                  $_FILES['file']['name'] = $_FILES['cvs']['name'][$i];
                  $_FILES['file']['type'] = $_FILES['cvs']['type'][$i];
                  $_FILES['file']['tmp_name'] = $_FILES['cvs']['tmp_name'][$i];
                  $_FILES['file']['error'] = $_FILES['cvs']['error'][$i];
                  $_FILES['file']['size'] = $_FILES['cvs']['size'][$i];
                  $temp = str_replace(' ', '', $_FILES['cvs']['name'][$i]);
                  $extension = pathinfo($_FILES['cvs']['name'][$i], PATHINFO_EXTENSION);

                  $nombre_cv = $id_candidato . "_CV_" . $i . '.' . $extension;
                  // Set preference
                  $config['upload_path'] = './_docs/';
                  $config['allowed_types'] = 'pdf|jpeg|jpg|png';
                  //$config['max_size'] = '15000'; // max_size in kb
                  $config['file_name'] = $nombre_cv;
                  //Load upload library
                  $this->load->library('upload', $config);
                  $this->upload->initialize($config);
                  // File upload
                  if ($this->upload->do_upload('file')) {
                    $data = $this->upload->data();
                    //$salida = 1; 
                  }
                  $documento = array(
                    'creacion' => $date,
                    'edicion' => $date,
                    'id_candidato' => $id_candidato,
                    'id_tipo_documento' => 16,
                    'archivo' => $nombre_cv
                  );
                  $this->candidato_model->registrarDocumento($documento);
                }
              }
            }

            //$candidato_previo = $this->candidato_model->getInfoCandidatoEspecifico($seccion->id_candidato);
            $candidato_secciones = array(
              'creacion' => $date,
              $tipo_usuario => $id_usuario,
              'id_candidato' => $id_candidato,
              'proyecto' => $seccion->proyecto,
              'secciones' => $seccion->secciones,
              'lleva_identidad' => $seccion->lleva_identidad,
              'lleva_empleos' => $seccion->lleva_empleos,
              'lleva_criminal' => $seccion->lleva_criminal,
              'lleva_estudios' => $seccion->lleva_estudios,
              'lleva_domicilios' => $seccion->lleva_domicilios,
              'lleva_gaps' => $seccion->lleva_gaps,
              'lleva_sociales' => $seccion->lleva_sociales,
              'lleva_no_mencionados' => $seccion->lleva_no_mencionados,
              'lleva_familiares' => $seccion->lleva_familiares,
              'lleva_egresos' => $seccion->lleva_egresos,
              'lleva_vivienda' => $seccion->lleva_vivienda,
              'id_seccion_datos_generales' => $seccion->id_seccion_datos_generales,
              'id_estudios' => $seccion->id_estudios,
              'id_seccion_verificacion_docs' => $seccion->id_seccion_verificacion_docs,
              'id_seccion_social' => $seccion->id_seccion_social,
              'id_finanzas' => $seccion->id_finanzas,
              'tiempo_empleos' => $seccion->tiempo_empleos,
              'tiempo_criminales' => $seccion->tiempo_criminales,
              'cantidad_ref_personales' => $seccion->cantidad_ref_personales,
              'cantidad_ref_vecinales' => $seccion->cantidad_ref_vecinales,
              'visita' => $seccion->visita
            );
            $this->candidato_model->guardarSeccionCandidato($candidato_secciones);

            $tipo_antidoping = ($examen == 0)? 0:1;
            $antidoping = ($examen == 0)? 0:$examen;
            $pruebas = array(
              'creacion' => $date,
              'edicion' => $date,
              $tipo_usuario => $id_usuario,
              'id_candidato' => $id_candidato,
              'id_cliente' => $id_cliente,
              'socioeconomico' => 1,
              'tipo_antidoping' => $tipo_antidoping,
              'antidoping' => $antidoping,
              'tipo_psicometrico' => $psicometrico,
              'psicometrico' => $psicometrico,
              'medico' => $medico,
              'buro_credito' => 0,
              'sociolaboral' => 0
            );
            $this->candidato_model->crearPruebas($pruebas);
              
            if($seccion->visita != NULL){
              $visita = array(
                'creacion' => $date,
                'edicion' => $date,
                $tipo_usuario => $id_usuario,
                'id_cliente' => $id_cliente,
                'id_subcliente' => $id_subcliente,
                'id_candidato' => $id_candidato,
                'id_tipo_formulario' => 4
              );
              $this->candidato_model->crearVisita($visita);
            }

            if ($usuario == 2 || $usuario == 3) {
              $from = $this->config->item('smtp_user');
              $info_cliente = $this->cliente_general_model->getDatosCliente($this->input->post('id_cliente'));
              $to = "bjimenez@rodicontrol.com";
              $subject = " Nuevo candidato en la plataforma del cliente " . $info_cliente->nombre;
              $message = "Se ha agregado a " . strtoupper($this->input->post('nombre')) . " " . strtoupper($this->input->post('paterno')) . " " . strtoupper($this->input->post('materno')) . " del cliente " . $info_cliente->nombre . " en la plataforma";
              $this->load->library('phpmailer_lib');
              $mail = $this->phpmailer_lib->load();
              $mail->isSMTP();
              $mail->Host     = 'mail.rodicontrol.com';
              $mail->SMTPAuth = true;
              $mail->Username = 'rodicontrol@rodicontrol.com';
              $mail->Password = 'r49o*&rUm%91';
              $mail->SMTPSecure = 'ssl';
              $mail->Port     = 465;
              $mail->setFrom('rodicontrol@rodicontrol.com', 'Mensaje automático de RODICONTROL');
              $mail->addAddress($to);
              $mail->Subject = $subject;
              $mail->isHTML(true);
              $mailContent = $message;
              $mail->Body = $mailContent;

              if (!$mail->send()) {
                $enviado = 1;
              } else {
                $enviado = 0;
              }
            }
            $msj = array(
              'codigo' => 1,
              'msg' => 'Success'
            );
          }
        } 
      }
      /*else{
        if($this->input->post('opcion') == 1){
          if($this->session->userdata('idcliente') != null){
            $id_cliente = $this->session->userdata('idcliente');
          }
          else{
            $id_cliente = $this->input->post('id_cliente');
          }
          $nombre = strtoupper($this->input->post('nombre'));
          $paterno = strtoupper($this->input->post('paterno'));
          $materno = strtoupper($this->input->post('materno'));
          $cel = $this->input->post('celular');
          $correo = strtolower($this->input->post('correo'));
          $examen = $this->input->post('examen');
          $medico = $this->input->post('medico');
          $tipo_antidoping = ($examen == 0)? 0:1;
          $antidoping = ($examen == 0)? 0:$examen;
          $pais = ($this->input->post('pais') == -1)? '' : $this->input->post('pais');
          $proyecto = $this->input->post('proyecto');
          date_default_timezone_set('America/Mexico_City');
          $date = date('Y-m-d H:i:s');
          $usuario = $this->input->post('usuario');
          switch ($usuario) {
            case 1:
              $tipo_usuario = "id_usuario";
              break;
            case 2:
              $tipo_usuario = "id_usuario_cliente";
              break;
            case 3:
              $tipo_usuario = "id_usuario_subcliente";
              break;
          }
          $id_usuario = $this->session->userdata('id');
          $last = $this->candidato_model->lastIdCandidato();
          $last = ($last == null || $last == "")? 0 : $last;

          $data = array(
            'creacion' => $date,
            'edicion' => $date,
            $tipo_usuario => $id_usuario,
            'fecha_alta' => $date,
            'tipo_formulario' => 0,
            'nombre' => $nombre,
            'paterno' => $paterno,
            'materno' => $materno,
            'correo' => $correo,
            'id_cliente' => $id_cliente,
            'id_subcliente' => 0,
            'celular' => $cel,
            'subproyecto' => $proyecto.' '.$pais
          );
          $this->candidato_model->guardarCandidato($data);
          $pruebas = array(
            'creacion' => $date,
            'edicion' => $date,
            $tipo_usuario => $id_usuario,
            'id_candidato' => ($last->id + 1),
            'id_cliente' => $id_cliente,
            'socioeconomico' => 0,
            'tipo_antidoping' => $tipo_antidoping,
            'antidoping' => $antidoping,
            'medico' => $medico
          );
          $this->candidato_model->crearPruebas($pruebas);

          $msj = array(
            'codigo' => 1,
            'msg' => 'Success'
          );
        }
        else{
          $this->form_validation->set_rules('nombre', 'Nombre(s)', 'required|trim');
          $this->form_validation->set_rules('paterno', 'Apellido paterno', 'required|trim');
          $this->form_validation->set_rules('materno', 'Apellido materno', 'trim');
          $this->form_validation->set_rules('subcliente', 'Subcliente', 'required');
          $this->form_validation->set_rules('puesto', 'Puesto', 'required');
          $this->form_validation->set_rules('celular', 'Teléfono', 'required|trim|max_length[16]');
          $this->form_validation->set_rules('proyecto', 'Nombre del proceso', 'required|trim');
          $this->form_validation->set_rules('generales', 'Datos generales', 'required');
          $this->form_validation->set_rules('estudios', 'Historial académico', 'required');
          $this->form_validation->set_rules('empleos', 'Referencias laborales', 'required');
          $this->form_validation->set_rules('sociales', 'Antecedentes sociales', 'required');
          $this->form_validation->set_rules('no_mencionados', 'Trabajos no mencionados', 'required');
          $this->form_validation->set_rules('ref_personales', 'Referencias personales', 'required');
          $this->form_validation->set_rules('documentacion', 'Documentación', 'required');
          $this->form_validation->set_rules('familiar', 'Información del grupo familiar', 'required');
          $this->form_validation->set_rules('egresos', 'Egresos mensuales', 'required');
          $this->form_validation->set_rules('habitacion', 'Habitación y medio ambiente', 'required');
          $this->form_validation->set_rules('ref_vecinales', 'Referencias vecinales', 'required');
          $this->form_validation->set_rules('examen', 'Examen antidoping', 'required');
          $this->form_validation->set_rules('medico', 'Examen Médico', 'required');
          $this->form_validation->set_rules('psicometrico', 'Examen Psicométrico', 'required');

          $this->form_validation->set_message('required', 'El campo {field} es obligatorio');
          $this->form_validation->set_message('max_length', 'El campo {field} debe tener máximo {param} carácteres');
          $this->form_validation->set_message('valid_email', 'El campo {field} debe ser un correo válido');
          $this->form_validation->set_message('numeric','El campo {field} debe ser numérico');

          $msj = array();
          if ($this->form_validation->run() == FALSE) {
            $msj = array(
              'codigo' => 0,
              'msg' => validation_errors()
            );
          }
          else{
            if($this->session->userdata('idcliente') != null){
              $id_cliente = $this->session->userdata('idcliente');
            }
            else{
              $id_cliente = $this->input->post('id_cliente');
            }
            $nombre = strtoupper($this->input->post('nombre'));
            $paterno = strtoupper($this->input->post('paterno'));
            $materno = strtoupper($this->input->post('materno'));
            $celular = $this->input->post('celular');
            $id_subcliente = $this->input->post('subcliente');
            $id_puesto = $this->input->post('puesto');
            $proceso = $this->input->post('proyecto');
            $examen = $this->input->post('examen');
            $medico = $this->input->post('medico');
            $psicometrico = $this->input->post('psicometrico');
            $correo = '';
            $existeCandidato = $this->candidato_model->repetidoCandidato($nombre, $paterno, $materno, $correo, $id_cliente);
            if($existeCandidato > 0){
              $msj = array(
                'codigo' => 2,
                'msg' => 'El candidato ya existe'
              );
            }
            else{
              date_default_timezone_set('America/Mexico_City');
              $date = date('Y-m-d H:i:s');
              $usuario = $this->input->post('usuario');
              switch ($usuario) {
                case 1:
                  $tipo_usuario = "id_usuario";
                  break;
                case 2:
                  $tipo_usuario = "id_usuario_cliente";
                  break;
                case 3:
                  $tipo_usuario = "id_usuario_subcliente";
                  break;
              }
              $id_usuario = $this->session->userdata('id');

              if ($usuario == 2 || $usuario == 3) {
                $configuracion = $this->funciones_model->getConfiguraciones();
                $data = array(
                  'creacion' => $date,
                  'edicion' => $date,
                  $tipo_usuario => $id_usuario,
                  'id_usuario' => $configuracion->usuario_lider_espanol,
                  'id_cliente' => $id_cliente,
                  'id_subcliente' => $id_subcliente,
                  'id_tipo_proceso' => 1,
                  'id_puesto' => $id_puesto,
                  'fecha_alta' => $date,
                  'nombre' => strtoupper($nombre),
                  'paterno' => strtoupper($paterno),
                  'materno' => strtoupper($materno),
                  'celular' => $celular,
                  'subproyecto' => $proceso
                );
              } 
              else {
                $data = array(
                  'creacion' => $date,
                  'edicion' => $date,
                  $tipo_usuario => $id_usuario,
                  'id_cliente' => $id_cliente,
                  'id_subcliente' => $id_subcliente,
                  'id_tipo_proceso' => 1,
                  'id_puesto' => $id_puesto,
                  'fecha_alta' => $date,
                  'nombre' => strtoupper($nombre),
                  'paterno' => strtoupper($paterno),
                  'materno' => strtoupper($materno),
                  'celular' => $celular,
                  'subproyecto' => $proceso
                );
              }
              $id_candidato = $this->candidato_model->registrarRetornaCandidato($data);

              //Subida y Registro de CV
              if ($this->input->post('hay_cvs') == 1) {
                $countfiles = count($_FILES['cvs']['name']);

                for ($i = 0; $i < $countfiles; $i++) {
                  if (!empty($_FILES['cvs']['name'][$i])) {
                    // Define new $_FILES array - $_FILES['file']
                    $_FILES['file']['name'] = $_FILES['cvs']['name'][$i];
                    $_FILES['file']['type'] = $_FILES['cvs']['type'][$i];
                    $_FILES['file']['tmp_name'] = $_FILES['cvs']['tmp_name'][$i];
                    $_FILES['file']['error'] = $_FILES['cvs']['error'][$i];
                    $_FILES['file']['size'] = $_FILES['cvs']['size'][$i];
                    $temp = str_replace(' ', '', $_FILES['cvs']['name'][$i]);
                    $extension = pathinfo($_FILES['cvs']['name'][$i], PATHINFO_EXTENSION);

                    $nombre_cv = $id_candidato . "_CV_" . $i . '.' . $extension;
                    // Set preference
                    $config['upload_path'] = './_docs/';
                    $config['allowed_types'] = 'pdf|jpeg|jpg|png';
                    //$config['max_size'] = '15000'; // max_size in kb
                    $config['file_name'] = $nombre_cv;
                    //Load upload library
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    // File upload
                    if ($this->upload->do_upload('file')) {
                      $data = $this->upload->data();
                      //$salida = 1; 
                    }
                    $documento = array(
                      'creacion' => $date,
                      'edicion' => $date,
                      'id_candidato' => $id_candidato,
                      'id_tipo_documento' => 16,
                      'archivo' => $nombre_cv
                    );
                    $this->candidato_model->registrarDocumento($documento);
                  }
                }
              }
              
              //Secciones
              $etiquetas = '';
              $etiquetas_visita = '';
              $lleva_sociales = 0; $lleva_no_mencionados = 0; $lleva_familiares = 0;
              $lleva_egresos = 0; $lleva_vivienda = 0; $num_personales = 0; $num_vecinales = 0;
              //Datos Generales
              if($this->input->post('generales') == 1){
                $salida = $this->candidato_model->getEtiquetaSeccion(28);
                $etiquetas .= $salida->etiqueta;
                $id_seccion_datos_generales = 28;
              }
              else{
                $id_seccion_datos_generales = null;
              }
              //Estudios
              if($this->input->post('estudios') == 1){
                $lleva_estudios = 1; 
                $salida = $this->candidato_model->getEtiquetaSeccion(3);
                $etiquetas .= $salida->etiqueta;
              }
              else{
                $lleva_estudios = 0;
              }
              //Sociales
              if($this->input->post('sociales') == 1){
                $lleva_sociales = 1;
                $salida = $this->candidato_model->getEtiquetaSeccion(64);
                $etiquetas .= $salida->etiqueta;
              }
              //Referencias Personales
              if($this->input->post('ref_personales') == 1){
                $salida = $this->candidato_model->getEtiquetaSeccion(31);
                $etiquetas .= $salida->etiqueta;
                $num_personales = 2;
              }
              //Empleos
              if($this->input->post('empleos') == 1){
                $lleva_empleos = 1;
                $tiempo_empleos = 'All';
                $salida = $this->candidato_model->getEtiquetaSeccion(32);
                $etiquetas .= $salida->etiqueta;
              }
              else{
                $lleva_empleos = 0;
                $tiempo_empleos = NULL;
              }
              //Trabajos no mencionados
              if($this->input->post('no_mencionados') == 1){
                $lleva_no_mencionados = 1;
                $salida = $this->candidato_model->getEtiquetaSeccion(33);
                $etiquetas .= $salida->etiqueta;
              }
              //Visita o extras
              $lleva_vecinales = ($this->input->post('ref_vecinales') == 1)? 1:0;
              //Documentacion
              if($this->input->post('documentacion') == 1){
								$id_seccion_verificacion_docs = 34;
                $salida = $this->candidato_model->getEtiquetaSeccion(34);
                if($lleva_vecinales == 1){
                  $etiquetas_visita .= $salida->etiqueta;
                }
                else{
                  $etiquetas .= $salida->etiqueta;
                }
              }
              else{
                $id_seccion_verificacion_docs = NULL;
              }
              //Grupo familiar
              if($this->input->post('familiar') == 1){
                $lleva_familiares = 1;
                $salida = $this->candidato_model->getEtiquetaSeccion(35);
                if($lleva_vecinales == 1){
                  $etiquetas_visita .= $salida->etiqueta;
                }
                else{
                  $etiquetas .= $salida->etiqueta;
                }
              }
              //Egresos mensuales
              if($this->input->post('egresos') == 1){
                $lleva_egresos = 1;
                $id_finanzas = 43;
                $salida = $this->candidato_model->getEtiquetaSeccion(43);
                if($lleva_vecinales == 1){
                  $etiquetas_visita .= $salida->etiqueta;
                }
                else{
                  $etiquetas .= $salida->etiqueta;
                }
              }
              else{
                $id_finanzas = NULL;
              }
              //Habitacion y medio ambiente
              if($this->input->post('habitacion') == 1){
                $lleva_vivienda = 1;
                $salida = $this->candidato_model->getEtiquetaSeccion(37);
                if($lleva_vecinales == 1){
                  $etiquetas_visita .= $salida->etiqueta;
                }
                else{
                  $etiquetas .= $salida->etiqueta;
                }
              }
              //Referencias vecinales
              if($lleva_vecinales == 1){
                $num_vecinales = 1;
                $salida = $this->candidato_model->getEtiquetaSeccion(38);
                $etiquetas_visita .= $salida->etiqueta;
              }
              else{
                $etiquetas_visita = NULL;
              }
             
              //Se guarda el registro para la tabla candidato_seccion
              $candidato_secciones = array(
                'creacion' => $date,
                $tipo_usuario => $id_usuario,
                'id_candidato' => $id_candidato,
                'proyecto' => $proceso,
                'secciones' => $etiquetas,
                'lleva_empleos' => $lleva_empleos,
                'lleva_estudios' => $lleva_estudios,
                'lleva_gaps' => 0,
                'lleva_sociales' => $lleva_sociales,
                'lleva_no_mencionados' => $lleva_no_mencionados,
                'lleva_familiares' => $lleva_familiares,
                'lleva_egresos' => $lleva_egresos,
                'lleva_vivienda' => $lleva_vivienda,
                'id_seccion_datos_generales' => $id_seccion_datos_generales,
								'id_seccion_verificacion_docs' => $id_seccion_verificacion_docs,
                'id_finanzas' => $id_finanzas,
                'tiempo_empleos' => $tiempo_empleos,
                'cantidad_ref_personales' => $num_personales,
                'cantidad_ref_vecinales' => $num_vecinales,
                'visita' => $etiquetas_visita
              );
              $this->candidato_model->guardarSeccionCandidato($candidato_secciones);

              //Se checa si no existe un proyecto previo con el mismo nombre; si no existe se agrega al historial
              //$existe_proyecto = $this->candidato_model->checkHistorialProyectos($proyecto);
              //if($existe_proyecto == 0){
                $proyecto_historial = array(
                  'creacion' => $date,
                  $tipo_usuario => $id_usuario,
                  'id_cliente' => $id_cliente,
                  'proyecto' => $proceso,
                  'secciones' => $etiquetas,
                  'lleva_empleos' => $lleva_empleos,
                  'lleva_estudios' => $lleva_estudios,
                  'lleva_gaps' => 0,
                  'lleva_sociales' => $lleva_sociales,
                  'lleva_no_mencionados' => $lleva_no_mencionados,
                  'lleva_familiares' => $lleva_familiares,
                  'lleva_egresos' => $lleva_egresos,
                  'lleva_vivienda' => $lleva_vivienda,
                  'id_seccion_datos_generales' => $id_seccion_datos_generales,
								  'id_seccion_verificacion_docs' => $id_seccion_verificacion_docs,
								  'id_finanzas' => $id_finanzas,
                  'tiempo_empleos' => $tiempo_empleos,
                  'cantidad_ref_personales' => $num_personales,
                  'cantidad_ref_vecinales' => $num_vecinales,
                  'visita' => $etiquetas_visita
                );
                $this->candidato_model->guardarHistorialProyecto($proyecto_historial);
              //}
              
              $tipo_antidoping = ($examen == 0)? 0:1;
              $antidoping = ($examen == 0)? 0:$examen;
              $pruebas = array(
                'creacion' => $date,
                'edicion' => $date,
                $tipo_usuario => $id_usuario,
                'id_candidato' => $id_candidato,
                'id_cliente' => $id_cliente,
                'socioeconomico' => 1,
                'tipo_antidoping' => $tipo_antidoping,
                'antidoping' => $antidoping,
                'tipo_psicometrico' => $psicometrico,
                'psicometrico' => $psicometrico,
                'medico' => $medico,
                'buro_credito' => 0,
                'sociolaboral' => 0
              );
              $this->candidato_model->crearPruebas($pruebas);
              
              if($lleva_vecinales == 1){
                $visita = array(
                  'creacion' => $date,
                  'edicion' => $date,
                  $tipo_usuario => $id_usuario,
                  'id_cliente' => $id_cliente,
                  'id_subcliente' => $id_subcliente,
                  'id_candidato' => $id_candidato,
                  'id_tipo_formulario' => 4
                );
                $this->candidato_model->crearVisita($visita);
              }

              if ($usuario == 2 || $usuario == 3) {
                $from = $this->config->item('smtp_user');
                $info_cliente = $this->cliente_general_model->getDatosCliente($this->input->post('id_cliente'));
                $to = "bjimenez@rodi.com.mx";
                $subject = " Nuevo candidato en la plataforma del cliente " . $info_cliente->nombre;
                $message = "Se ha agregado a " . strtoupper($this->input->post('nombre')) . " " . strtoupper($this->input->post('paterno')) . " " . strtoupper($this->input->post('materno')) . " del cliente " . $info_cliente->nombre . " en la plataforma";
                $this->load->library('phpmailer_lib');
                $mail = $this->phpmailer_lib->load();
                $mail->isSMTP();
                $mail->Host     = 'mail.rodicontrol.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'rodicontrol@rodicontrol.com';
                $mail->Password = 'r49o*&rUm%91';
                $mail->SMTPSecure = 'ssl';
                $mail->Port     = 465;
                $mail->setFrom('rodicontrol@rodicontrol.com', 'RODI');
                $mail->addAddress($to);
                $mail->Subject = $subject;
                $mail->isHTML(true);
                $mailContent = $message;
                $mail->Body = $mailContent;

                if (!$mail->send()) {
                  $enviado = 1;
                } else {
                  $enviado = 0;
                }
              }
              $msj = array(
                'codigo' => 1,
                'msg' => 'Success'
              );
            }
          } 
        //}
      }*/
      echo json_encode($msj);
    }
    function guardarDatosGenerales(){
      $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim');
      $this->form_validation->set_rules('paterno', 'Primer apellido', 'required|trim');
      $this->form_validation->set_rules('materno', 'Segundo apellido', 'trim');
      $this->form_validation->set_rules('fecha_nacimiento', 'Fecha de nacimiento', 'required|trim');
      $this->form_validation->set_rules('nacionalidad', 'Nacionalidad', 'required|trim');
      $this->form_validation->set_rules('puesto', 'Puesto', 'required|trim');
      $this->form_validation->set_rules('genero', 'Género', 'required|trim');
      $this->form_validation->set_rules('calle', 'Calle', 'required|trim');
      $this->form_validation->set_rules('exterior', 'No. Exterior', 'required|trim|max_length[8]');
      $this->form_validation->set_rules('interior', 'No. Interior', 'trim|max_length[8]');
      $this->form_validation->set_rules('colonia', 'Colonia', 'required|trim');
      $this->form_validation->set_rules('estado', 'Estado', 'required|trim|numeric');
      $this->form_validation->set_rules('municipio', 'Municipio', 'required|trim|numeric');
      $this->form_validation->set_rules('cp', 'Código postal', 'required|trim|numeric|max_length[5]');
      $this->form_validation->set_rules('civil', 'Civil', 'required|trim');
      $this->form_validation->set_rules('celular', 'Tel. Celular', 'required|trim|max_length[16]');
      $this->form_validation->set_rules('tel_casa', 'Tel. Casa', 'trim|max_length[16]');
      $this->form_validation->set_rules('tel_oficina', 'Tel. Oficina', 'trim|max_length[16]');
      $this->form_validation->set_rules('tiempo_dom_actual', 'Tiempo en el domicilio actual', 'trim|required');
      $this->form_validation->set_rules('tiempo_traslado', 'Tiempo de traslado a la oficina', 'trim|required');
      $this->form_validation->set_rules('medio_transporte', 'Medio de transporte', 'trim|required');
      $this->form_validation->set_rules('grado_estudios', 'Grado máximo de estudios', 'trim|required');
      //$this->form_validation->set_rules('correo', 'Correo', 'required|trim|valid_email');

      $this->form_validation->set_message('required','El campo {field} es obligatorio');
      $this->form_validation->set_message('valid_email','El campo {field} debe ser un correo válido');
      $this->form_validation->set_message('max_length','El campo {field} debe tener máximo {param} carácteres');
      $this->form_validation->set_message('alpha','El campo {field} debe contener solo carácteres alfabéticos y sin acentos');
      $this->form_validation->set_message('numeric','El campo {field} debe ser numérico');


      $msj = array();
      if ($this->form_validation->run() == FALSE) {
          $msj = array(
              'codigo' => 0,
              'msg' => validation_errors()
          );
      } 
      else{
          date_default_timezone_set('America/Mexico_City');
          $date = date('Y-m-d H:i:s');
          $id_candidato = $this->input->post('id_candidato');
          $id_usuario = $this->session->userdata('id');
          $fecha = fecha_espanol_bd($this->input->post('fecha_nacimiento'));
          $edad = calculaEdad($fecha);

          $candidato = array(
              'edicion' => $date,
              'id_usuario' => $id_usuario,
              'nombre' => $this->input->post('nombre'),
              'paterno' => $this->input->post('paterno'),
              'materno' => $this->input->post('materno'),
              'fecha_nacimiento' => $fecha,
              'edad' => $edad,
              'id_puesto' => $this->input->post('puesto'),
              'nacionalidad' => $this->input->post('nacionalidad'),
              'genero' => $this->input->post('genero'),
              'id_grado_estudio' => $this->input->post('grado_estudios'),
              'calle' => $this->input->post('calle'),
              'exterior' => $this->input->post('exterior'),
              'interior' => $this->input->post('interior'),
              'entre_calles' => $this->input->post('entre_calles'),
              'colonia' => $this->input->post('colonia'),
              'id_estado' => $this->input->post('estado'),
              'id_municipio' => $this->input->post('municipio'),
              'cp' => $this->input->post('cp'),
              'estado_civil' => $this->input->post('civil'),
              'celular' => $this->input->post('celular'),
              'telefono_casa' => $this->input->post('tel_casa'),
              'telefono_otro' => $this->input->post('tel_oficina'),
              'tiempo_dom_actual' => $this->input->post('tiempo_dom_actual'),
              'tiempo_traslado' => $this->input->post('tiempo_traslado'),
              'tipo_transporte' => $this->input->post('medio_transporte'),
              'correo' => $this->input->post('correo')
          );
          $this->candidato_model->editarCandidato($candidato, $id_candidato);
          $visita = array(
            'edicion' => $date,
            'id_usuario' => $id_usuario,
            'calle' => $this->input->post('calle'),
            'exterior' => $this->input->post('exterior'),
            'interior' => $this->input->post('interior'),
            'colonia' => $this->input->post('colonia'),
            'id_estado' => $this->input->post('estado'),
            'id_municipio' => $this->input->post('municipio'),
            'cp' => $this->input->post('cp'),
            'celular' => $this->input->post('celular_general'),
            'telefono_casa' => $this->input->post('tel_casa'),
          );
          $this->candidato_model->editarVisita($visita, $id_candidato);
          $msj = array(
              'codigo' => 1,
              'msg' => 'success'
          );
      }
      echo json_encode($msj);
    }
    
    
    function guardarReferenciasPersonales(){
      for($i = 1; $i <= 2; $i++){
        $this->form_validation->set_rules('nombre'.$i, 'Nombre de la referencia #'.$i, 'required|trim');
        $this->form_validation->set_rules('tiempo'.$i, 'Tiempo de conocerlo de la referencia #'.$i, 'required|trim');
        $this->form_validation->set_rules('lugar'.$i, '¿De qué lugar conoce al candidato? de la referencia #'.$i, 'required|trim');
        $this->form_validation->set_rules('trabaja'.$i, '¿Sabe dónde trabaja el candidato? de la referencia #'.$i, 'required|trim');
        $this->form_validation->set_rules('vive'.$i, '¿Sabe dónde vive el candidato? de la referencia #'.$i, 'required|trim');
        $this->form_validation->set_rules('telefono'.$i, 'Teléfono de la referencia #'.$i, 'required|trim|max_length[12]');
        $this->form_validation->set_rules('recomienda'.$i, '¿Lo recomienda? de la referencia #'.$i, 'required|trim');
        $this->form_validation->set_rules('comentario'.$i, 'Comentarios de la referencia #'.$i, 'required|trim');
      }
      
      $this->form_validation->set_message('required','El campo {field} es obligatorio');
      $this->form_validation->set_message('max_length','El campo {field} debe tener máximo {param} carácteres');
      $this->form_validation->set_message('numeric','El campo {field} debe ser numérico');

      $msj = array();
      if ($this->form_validation->run() == FALSE) {
        $msj = array(
          'codigo' => 0,
          'msg' => validation_errors()
        );
      } 
      else{
        date_default_timezone_set('America/Mexico_City');
        $date = date('Y-m-d H:i:s');
        $id_candidato = $this->input->post('id_candidato');
        $id_usuario = $this->session->userdata('id');

        $this->candidato_model->eliminarReferenciasPersonales($id_candidato);
        for($i = 1; $i <= 2; $i++){
          $data_refper = array(
            'creacion' => $date,
            'edicion' => $date,
            'id_usuario' => $id_usuario,
            'id_candidato' => $id_candidato,
            'nombre' => $this->input->post('nombre'.$i),
            'telefono' => $this->input->post('telefono'.$i),
            'tiempo_conocerlo' => $this->input->post('tiempo'.$i),
            'donde_conocerlo' => $this->input->post('lugar'.$i),
            'sabe_trabajo' => $this->input->post('trabaja'.$i),
            'sabe_vive' => $this->input->post('vive'.$i),
            'recomienda' => $this->input->post('recomienda'.$i),
            'comentario' => $this->input->post('comentario'.$i)
          );
          $this->candidato_model->saveRefPer($data_refper);
        }
        $msj = array(
          'codigo' => 1,
          'msg' => 'success'
        );
      }
      echo json_encode($msj);
    }
    function guardarTrabajoGobierno(){
      if($this->input->post('caso') == 2){//Para Monex
        $this->form_validation->set_rules('trabajo', '¿Has trabajado en alguna entidad de gobierno, partido político u ONG?', 'required|trim');
        $this->form_validation->set_rules('enterado', '¿Cómo se enteró del trabajo?', 'required|trim');
        
        $this->form_validation->set_message('required','El campo {field} es obligatorio');

        $msj = array();
        if ($this->form_validation->run() == FALSE) {
            $msj = array(
                'codigo' => 0,
                'msg' => validation_errors()
            );
        }
        else{
            date_default_timezone_set('America/Mexico_City');
            $date = date('Y-m-d H:i:s');
            $id_candidato = $this->input->post('id_candidato');
            $trabajo = ($this->input->post('trabajo')  !== null)? $this->input->post('trabajo'):'';
            $enterado = ($this->input->post('enterado') !== null)? $this->input->post('enterado'):'';
            $inactivo = ($this->input->post('inactivo')  !== null)? $this->input->post('inactivo'):'';
            $id_usuario = $this->session->userdata('id');
            $persona_nombre1 = $this->input->post('persona_nombre1');
            $persona_puesto1 = $this->input->post('persona_puesto1');
            $persona_nombre2 = $this->input->post('persona_nombre2');
            $persona_puesto2 = $this->input->post('persona_puesto2');
            $candidato = array(
                'edicion' => $date,
                'id_usuario' => $id_usuario,
                'trabajo_gobierno' => $trabajo,
                'trabajo_enterado' => $enterado,
                'trabajo_inactivo' => $inactivo
            );
            $this->candidato_model->editarCandidato($candidato, $id_candidato);

            $this->candidato_model->eliminarCandidatoPersonaMismoTrabajo($id_candidato);
            if($persona_nombre1 != '' && $persona_puesto1 != ''){
              $p1 = array(
                'creacion' => $date,
                'edicion' => $date,
                'id_usuario' => $id_usuario,
                'id_candidato' => $id_candidato,
                'nombre' => $persona_nombre1,
                'puesto' => $persona_puesto1
              );
              $this->candidato_model->guardarPersonaMismoTrabajo($p1);
            }
            if($persona_nombre2 != '' && $persona_puesto2 != ''){
              $p2 = array(
                'creacion' => $date,
                'edicion' => $date,
                'id_usuario' => $id_usuario,
                'id_candidato' => $id_candidato,
                'nombre' => $persona_nombre2,
                'puesto' => $persona_puesto2
              );
              $this->candidato_model->guardarPersonaMismoTrabajo($p2);
            }
            $msj = array(
                'codigo' => 1,
                'msg' => 'success'
            );
        }
        echo json_encode($msj);
      }
    }
    function guardarReferenciaLaboralCandidato(){
      $this->form_validation->set_rules('empresa', 'Empresa', 'required|trim');
      $this->form_validation->set_rules('direccion', 'Direccion', 'required|trim');
      $this->form_validation->set_rules('entrada', 'Fecha de entrada', 'required|trim');
      $this->form_validation->set_rules('salida', 'Fecha de salida', 'required|trim');
      $this->form_validation->set_rules('telefono', 'Teléfono', 'required|trim|max_length[16]');
      $this->form_validation->set_rules('puesto1', 'Puesto inicial', 'required|trim');
      $this->form_validation->set_rules('puesto2', 'Puesto final', 'required|trim');
      $this->form_validation->set_rules('salario1', 'Salario inicial', 'required|trim');
      $this->form_validation->set_rules('salario2', 'Salario final', 'required|trim');
      $this->form_validation->set_rules('jefenombre', 'Jefe inmediato', 'required|trim');
      $this->form_validation->set_rules('jefecorreo', 'Correo del jefe inmediato', 'required|trim');
      $this->form_validation->set_rules('jefepuesto', 'Puesto del jefe inmediato', 'required|trim');
      $this->form_validation->set_rules('separacion', 'Causa de separación', 'required|trim');
      
      $this->form_validation->set_message('required','El campo {field} es obligatorio');
      $this->form_validation->set_message('max_length','El campo {field} debe tener máximo {param} carácteres');
      $this->form_validation->set_message('numeric','El campo {field} debe ser numérico');

      $msj = array();
      if ($this->form_validation->run() == FALSE) {
          $msj = array(
              'codigo' => 0,
              'msg' => validation_errors()
          );
      } 
      else{
          date_default_timezone_set('America/Mexico_City');
          $date = date('Y-m-d H:i:s');
          $id_candidato = $this->input->post('id_candidato');
          $num = $this->input->post('num');
          $idref = $this->input->post('idref');
          $id_usuario = $this->session->userdata('id');

          $data['refs'] = $this->candidato_model->revisionReferenciaLaboral($idref);
          if($data['refs']){
              $datos = array(
                  'edicion' => $date,
                  'empresa' => ucwords(mb_strtolower( $this->input->post('empresa'))),
                  'direccion' => $this->input->post('direccion'),
                  'fecha_entrada_txt' => $this->input->post('entrada'),
                  'fecha_salida_txt' => $this->input->post('salida'),
                  'telefono' => $this->input->post('telefono'),
                  'puesto1' => $this->input->post('puesto1'),
                  'puesto2' => $this->input->post('puesto2'),
                  'salario1_txt' => $this->input->post('salario1'),
                  'salario2_txt' => $this->input->post('salario2'),
                  'jefe_nombre' => $this->input->post('jefenombre'),
                  'jefe_correo' => mb_strtolower($this->input->post('jefecorreo')),
                  'jefe_puesto' => $this->input->post('jefepuesto'),
                  'causa_separacion' => $this->input->post('separacion')
              );
              $this->candidato_model->editarReferenciaLaboral($datos, $idref);
              $msj = array(
                  'codigo' => 1,
                  'msg' => 'success'
              );
          }
          else{
              $datos = array(
                  'creacion' => $date,
                  'edicion' => $date,
                  'id_candidato' => $id_candidato,
                  'empresa' => ucwords(mb_strtolower( $this->input->post('empresa'))),
                  'direccion' => $this->input->post('direccion'),
                  'fecha_entrada_txt' => $this->input->post('entrada'),
                  'fecha_salida_txt' => $this->input->post('salida'),
                  'telefono' => $this->input->post('telefono'),
                  'puesto1' => $this->input->post('puesto1'),
                  'puesto2' => $this->input->post('puesto2'),
                  'salario1_txt' => $this->input->post('salario1'),
                  'salario2_txt' => $this->input->post('salario2'),
                  'jefe_nombre' => $this->input->post('jefenombre'),
                  'jefe_correo' => mb_strtolower($this->input->post('jefecorreo')),
                  'jefe_puesto' => $this->input->post('jefepuesto'),
                  'causa_separacion' => $this->input->post('separacion')
              );
              $id_nuevo = $this->candidato_model->guardarReferenciaLaboral($datos);
              $msj = array(
                  'codigo' => 2,
                  'msg' => $id_nuevo
              );
          }
      }
      echo json_encode($msj);
    }
    function guardarVerificacionLaboralCandidato(){
      $this->form_validation->set_rules('empresa', 'Empresa', 'required|trim');
      $this->form_validation->set_rules('direccion', 'Direccion', 'required|trim');
      $this->form_validation->set_rules('entrada', 'Fecha de entrada', 'required|trim');
      $this->form_validation->set_rules('salida', 'Fecha de salida', 'required|trim');
      $this->form_validation->set_rules('telefono', 'Teléfono', 'required|trim|max_length[16]');
      $this->form_validation->set_rules('puesto1', 'Puesto inicial', 'required|trim');
      $this->form_validation->set_rules('puesto2', 'Puesto final', 'required|trim');
      $this->form_validation->set_rules('salario1', 'Salario inicial', 'required|trim');
      $this->form_validation->set_rules('salario2', 'Salario final', 'required|trim');
      $this->form_validation->set_rules('jefenombre', 'Jefe inmediato', 'required|trim');
      $this->form_validation->set_rules('jefecorreo', 'Correo del jefe inmediato', 'required|trim');
      $this->form_validation->set_rules('jefepuesto', 'Puesto del jefe inmediato', 'required|trim');
      $this->form_validation->set_rules('separacion', 'Causa de separación', 'required|trim');
      $this->form_validation->set_rules('cualidades', 'Fortalezas o cualidades del candidato', 'required|trim');
      $this->form_validation->set_rules('mejoras', 'Áreas a mejorar del candidato', 'required|trim');
      $this->form_validation->set_rules('comentarios', 'Referencia', 'required|trim');
      
      $this->form_validation->set_message('required','El campo {field} es obligatorio');
      $this->form_validation->set_message('max_length','El campo {field} debe tener máximo {param} carácteres');
      $this->form_validation->set_message('numeric','El campo {field} debe ser numérico');

      $msj = array();
      if ($this->form_validation->run() == FALSE) {
          $msj = array(
              'codigo' => 0,
              'msg' => validation_errors()
          );
      } 
      else{
        date_default_timezone_set('America/Mexico_City');
        $date = date('Y-m-d H:i:s');
        $id_candidato = $this->input->post('id_candidato');
        $num = $this->input->post('num');
        $idverlab = $this->input->post('idverlab');
        $id_usuario = $this->session->userdata('id');

        $this->candidato_model->eliminarVerificacionLaboral($id_candidato, $num);
        $datos = array(
          'creacion' => $date,
          'edicion' => $date,
          'id_usuario' => $id_usuario,
          'id_candidato' => $id_candidato,
          'numero_referencia' => $num,
          'empresa' => ucwords(strtolower($this->input->post('empresa'))),
          'direccion' => ucwords(strtolower($this->input->post('direccion'))),
          'fecha_entrada_txt' => $this->input->post('entrada'),
          'fecha_salida_txt' => $this->input->post('salida'),
          'telefono' => $this->input->post('telefono'),
          'puesto1' => ucwords(strtolower($this->input->post('puesto1'))),
          'puesto2' => ucwords(strtolower($this->input->post('puesto2'))),
          'salario1_txt' => $this->input->post('salario1'),
          'salario2_txt' => $this->input->post('salario2'),
          'jefe_nombre' => ucwords(strtolower($this->input->post('jefenombre'))),
          'jefe_correo' => strtolower($this->input->post('jefecorreo')),
          'jefe_puesto' => ucwords(strtolower($this->input->post('jefepuesto'))),
          'causa_separacion' => $this->input->post('separacion'),
          'cualidades' => $this->input->post('cualidades'),
          'mejoras' => $this->input->post('mejoras'),
          'notas' => $this->input->post('comentarios')
        );
        $this->candidato_model->guardarVerificacionLaboral($datos);
        //$this->generarAvancesUST($id_candidato);
        $msj = array(
          'codigo' => 1,
          'msg' => 'success'
        );
      }
      echo json_encode($msj);
    }
    
    
    function actualizarProcesoCandidato(){
      date_default_timezone_set('America/Mexico_City');
      $date = date('Y-m-d H:i:s');
      $fecha_dia = date('d-m-Y');
      $id_usuario = $this->session->userdata('id');
      $id_candidato = $this->input->post('id_candidato');
      $id_doping = $this->input->post('id_doping');

      $pruebas = $this->candidato_model->getPruebasCandidato($id_candidato);
      $datos_pruebas = array(
        'creacion' => $date,
        'edicion' => $date,
        'id_usuario' => $id_usuario,
        'id_candidato' => $id_candidato,
        'id_cliente' => $pruebas->id_cliente,
        'socioeconomico' => $pruebas->socioeconomico,
        'tipo_antidoping' => $pruebas->tipo_antidoping,
        'antidoping' => $pruebas->antidoping,
        'status_doping' => 0,
        'tipo_psicometrico' => $pruebas->tipo_psicometrico,
        'psicometrico' => $pruebas->psicometrico,
        'medico' => $pruebas->medico,
        'buro_credito' => $pruebas->buro_credito,
        'sociolaboral' => $pruebas->sociolaboral,
        'ofac' => $pruebas->ofac,
        'resultado_ofac' => $pruebas->resultado_ofac,
        'oig' => $pruebas->oig,
        'resultado_oig' => $pruebas->resultado_oig,
        'sam' => $pruebas->sam,
        'resultado_sam' => $pruebas->resultado_sam,
        'data_juridica' => $pruebas->data_juridica,
        'res_data_juridica' => $pruebas->res_data_juridica,
        'otro_requerimiento' => $pruebas->otro_requerimiento
      );
      $this->candidato_model->eliminarCandidatoPruebas($id_candidato);
      $this->candidato_model->crearPruebas($datos_pruebas);
      $this->doping_model->cambiarEstatusDoping($id_candidato);
      //Historial
      $info = $this->candidato_model->getInfoCandidatoEspecifico($id_candidato);
      $fecha_alta = fecha_sinhora_espanol_front($info->fecha_alta);
      $visita = ($info->visitador == 1)? 'SI':'NO';
      $examen_antidoping = ($pruebas->antidoping > 0)? 'SI':'NO';
      $examen_psicometrico = ($pruebas->psicometrico == 1)? 'SI':'NO';
      $examen_medico = ($pruebas->medico == 1)? 'SI':'NO';
      $buro = ($pruebas->buro_credito == 1)? 'SI':'NO';
      switch ($info->status_bgc) {
          case 1:
              $estatus_final = 'POSITIVO';
              break;
          case 2:
              $estatus_final = 'NEGATIVO';
              break;
          case 3:
              $estatus_final = 'A CONSIDERACION';
              break;
      }
      $historial = array(
        'creacion' => $date,
        'id_candidato' => $id_candidato,
        'usuario' => $info->usuario,
        'id_tipo_proceso' => $info->id_tipo_proceso,
        'puesto' => $info->puesto,
        'fecha_alta' => $fecha_alta,
        'visita' => $visita,
        'antidoping' => $examen_antidoping,
        'psicometrico' => $examen_psicometrico,
        'medico' => $examen_medico,
        'buro_credito' => $buro,
        'tiempo_proceso' => $info->tiempo_parcial,
        'status_bgc' => $estatus_final
      );
      $this->candidato_model->guardarHistorialCandidato($historial);
        
      $this->candidato_model->eliminarCandidatoFinalizado($id_candidato);
      $this->candidato_model->eliminarCandidatoBGC($id_candidato);
      //Borrar datos de la Visita
      $this->candidato_model->eliminarCandidatoEgresos($id_candidato);
      $this->candidato_model->eliminarCandidatoHabitacion($id_candidato);
      $this->candidato_model->eliminarCandidatoVecinos($id_candidato);
      $this->candidato_model->eliminarCandidatoPersona($id_candidato);
      $this->candidato_model->eliminarCandidatoPersonaMismoTrabajo($id_candidato);

      $dop = array(
        'edicion' => $date,
        'status' => 0
      );
      $this->candidato_model->editarDoping($dop, $id_doping);

      $datos = array(
        'edicion' => $date,
        'fecha_alta' => $date,
        'muebles' => '',
        'adeudo_muebles' => 0,
        'ingresos' => '',
        'comentario' => '',
        'status' => 0,
        'status_bgc' => 0,
        'visitador' => 0,
        'tiempo_parcial' => 0
      );
      $this->candidato_model->editarCandidato($datos, $id_candidato);

      $row = $this->candidato_model->checkActualizacionCandidato($id_candidato);
      if($row != null){
        $act = array(
            'edicion' => $date,
            'usuarios' => $row->usuarios.','.$id_usuario,
            'fechas' => $row->fechas.','.$fecha_dia,
            'num' => ($row->num + 1)
        );
        $this->candidato_model->editarActualizacionCandidato($act, $id_candidato);
      }
      else{
        $act = array(
            'creacion' => $date,
            'edicion' => $date,
            'usuarios' => $id_usuario,
            'id_candidato' => $id_candidato,
            'fechas' => $fecha_dia,
            'num' => 1
        );
        $this->candidato_model->guardarActualizacionCandidato($act);
      }
      echo $salida = 1;
    }
    
    
    function editarExtrasCandidato(){
      $this->form_validation->set_rules('notas', 'Notas', 'required|trim');
      $this->form_validation->set_rules('muebles', 'Muebles e inmuebles del candidato', 'required|trim');
      $this->form_validation->set_rules('adeudo', 'Adeudo', 'required|trim');
      $this->form_validation->set_rules('ingresos', 'Ingresos del candidato', 'required|trim|numeric');
      $this->form_validation->set_rules('aporte', 'Aporte del candidato', 'required|trim|numeric');
      
      $this->form_validation->set_message('required','El campo {field} es obligatorio');
      $this->form_validation->set_message('numeric','El campo {field} debe ser numérico');


      $msj = array();
      if ($this->form_validation->run() == FALSE) {
          $msj = array(
              'codigo' => 0,
              'msg' => validation_errors()
          );
      }
      else{
        date_default_timezone_set('America/Mexico_City');
        $date = date('Y-m-d H:i:s');
        $datos = array(
          'edicion' => $date,
          'muebles' => $this->input->post('muebles'),
          'comentario' => $this->input->post('notas'),
          'adeudo_muebles' => $this->input->post('adeudo'),
          'ingresos' => $this->input->post('ingresos'),
          'aporte' => $this->input->post('aporte')

        );
        $this->candidato_model->editarCandidato($datos, $this->input->post('id_candidato'));
        $msj = array(
          'codigo' => 1,
          'msg' => 'success'
        );
      }
      echo json_encode($msj);
    }
    function guardarEgresos(){
      $this->form_validation->set_rules('muebles', 'Muebles e inmuebles del candidato', 'required|trim');
      $this->form_validation->set_rules('adeudo', 'Adeudo', 'required|trim|numeric');
      $this->form_validation->set_rules('ingresos', 'Ingresos del candidato', 'required|trim');
      $this->form_validation->set_rules('aporte', 'Aporte del candidato', 'required|trim');
      $this->form_validation->set_rules('renta', 'Renta', 'required|trim|numeric');
      $this->form_validation->set_rules('alimentos', 'Alimentos', 'required|trim|numeric');
      $this->form_validation->set_rules('servicios', 'Servicios', 'required|trim|numeric');
      $this->form_validation->set_rules('transportes', 'Transportes', 'required|trim|numeric');
      $this->form_validation->set_rules('otros_gastos', 'Otros', 'required|trim|numeric');
      $this->form_validation->set_rules('solvencia', 'Cuando los egresos son mayores a los ingresos, ¿cómo los solventa?', 'required|trim');
      $this->form_validation->set_rules('notas', 'Notas', 'required|trim');
      
      $this->form_validation->set_message('required','El campo {field} es obligatorio');
      $this->form_validation->set_message('numeric','El campo {field} debe ser numérico');

      $msj = array();
      if ($this->form_validation->run() == FALSE) {
        $msj = array(
          'codigo' => 0,
          'msg' => validation_errors()
        );
      }
      else{
        date_default_timezone_set('America/Mexico_City');
        $date = date('Y-m-d H:i:s');
        $id_candidato = $this->input->post('id_candidato');
        $id_usuario = $this->session->userdata('id');

        $candidato = array(
          'edicion' => $date,
          'muebles' => $this->input->post('muebles'),
          'adeudo_muebles' => $this->input->post('adeudo'),
          'ingresos' => $this->input->post('ingresos'),
          'aporte' => $this->input->post('aporte'),
          'comentario' => $this->input->post('notas')
        );
        $this->candidato_model->editarCandidato($candidato, $id_candidato);

        $data['egresos'] = $this->candidato_model->revisionEgresos($id_candidato);
        if($data['egresos']){
          $datos = array(
            'edicion' => $date,
            'id_usuario' => $id_usuario,
            'renta' => $this->input->post('renta'),
            'alimentos' => $this->input->post('alimentos'),
            'servicios' => $this->input->post('servicios'),
            'transporte' => $this->input->post('transportes'),
            'otros' => $this->input->post('otros_gastos'),
            'solvencia' => $this->input->post('solvencia')
          );
          foreach ($data['egresos'] as $dato) {
            $id = $dato->id;
          }
          $this->candidato_model->editarEgresos($datos, $id);
        }
        else{
          $datos = array(
            'creacion' => $date,
            'edicion' => $date,
            'id_usuario' => $id_usuario,
            'id_candidato' => $id_candidato,
            'renta' => $this->input->post('renta'),
            'alimentos' => $this->input->post('alimentos'),
            'servicios' => $this->input->post('servicios'),
            'transporte' => $this->input->post('transportes'),
            'otros' => $this->input->post('otros_gastos'),
            'solvencia' => $this->input->post('solvencia')
          );
          $id = $this->candidato_model->guardarEgresos($datos);
        }
        $msj = array(
          'codigo' => 1,
          'msg' => 'success'
        );
      }
      echo json_encode($msj);
    }
    function guardarIngresosEgresos(){
      $this->form_validation->set_rules('muebles', 'Muebles e inmuebles del candidato', 'required|trim');
      $this->form_validation->set_rules('adeudo', 'Adeudo en muebles e inmuebles del candidato', 'required|trim|numeric');
      $this->form_validation->set_rules('ingresos', 'Monto de ingresos fijos', 'required|trim');
      $this->form_validation->set_rules('ingresos_extra', 'Monto de ingresos extra', 'trim');
      $this->form_validation->set_rules('otros_gastos', 'Monto de gastos generales', 'required|trim');
      $this->form_validation->set_rules('solvencia', 'Cuando los egresos son mayores a los ingresos, ¿cómo los solventa?', 'required|trim');
      $this->form_validation->set_rules('notas', 'Notas', 'required|trim');
      
      $this->form_validation->set_message('required','El campo {field} es obligatorio');
      $this->form_validation->set_message('numeric','El campo {field} debe ser numérico');


      $msj = array();
      if ($this->form_validation->run() == FALSE) {
        $msj = array(
          'codigo' => 0,
          'msg' => validation_errors()
        );
      }
      else{
        date_default_timezone_set('America/Mexico_City');
        $date = date('Y-m-d H:i:s');
        $id_candidato = $this->input->post('id_candidato');
        $id_usuario = $this->session->userdata('id');

        $candidato = array(
          'edicion' => $date,
          'muebles' => $this->input->post('muebles'),
          'adeudo_muebles' => $this->input->post('adeudo'),
          'ingresos' => $this->input->post('ingresos'),
          'ingresos_extra' => $this->input->post('ingresos_extra'),
          'comentario' => $this->input->post('notas'),
          'visitador' => 1
        );
        $this->candidato_model->editarCandidato($candidato, $id_candidato);

        $this->candidato_model->eliminarEgresosCandidato($id_candidato);
        $datos = array(
          'creacion' => $date,
          'edicion' => $date,
          'id_usuario' => $id_usuario,
          'id_candidato' => $id_candidato,
          'otros' => $this->input->post('otros_gastos'),
          'solvencia' => $this->input->post('solvencia')
        );
        $this->candidato_model->guardarEgresos($datos);
        $msj = array(
          'codigo' => 1,
          'msg' => 'success'
        );
      }
      echo json_encode($msj);
    }
    
    function guardarReferenciasVecinales(){
      $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim');
      $this->form_validation->set_rules('domicilio', 'Domicilio', 'required|trim');
      $this->form_validation->set_rules('telefono', 'Teléfono', 'required|trim');
      $this->form_validation->set_rules('concepto', '¿Qué concepto tiene del aspirante?', 'required|trim');
      $this->form_validation->set_rules('familia', '¿En qué concepto tiene a la familia como vecinos?', 'required|trim');
      $this->form_validation->set_rules('civil', '¿Conoce el estado civil del aspirante? ¿Cuál es?', 'required|trim');
      $this->form_validation->set_rules('hijos', '¿Tiene hijos?', 'required|trim');
      $this->form_validation->set_rules('sabetrabaja', '¿Sabe en dónde trabaja?', 'required|trim');
      $this->form_validation->set_rules('notas', 'Notas', 'required|trim');
      
      $this->form_validation->set_message('required','El campo {field} es obligatorio');
      $this->form_validation->set_message('numeric','El campo {field} debe ser numérico');


      $msj = array();
      if ($this->form_validation->run() == FALSE) {
        $msj = array(
            'codigo' => 0,
            'msg' => validation_errors()
        );
      }
      else{
        date_default_timezone_set('America/Mexico_City');
        $date = date('Y-m-d H:i:s');
        $id_candidato = $this->input->post('id_candidato');
        $idrefvec = $this->input->post('idrefvec');
        $id_usuario = $this->session->userdata('id');
        $num = $this->input->post('num');

        if($idrefvec != ""){
          $datos = array(
              'edicion' => $date,
              'id_usuario' => $id_usuario,
              'nombre' => $this->input->post('nombre'),
              'telefono' => $this->input->post('telefono'),
              'domicilio' => $this->input->post('domicilio'),
              'concepto_candidato' => $this->input->post('concepto'),
              'concepto_familia' => $this->input->post('familia'),
              'civil_candidato' => $this->input->post('civil'),
              'hijos_candidato' => $this->input->post('hijos'),
              'sabe_trabaja' => $this->input->post('sabetrabaja'),
              'notas' => $this->input->post('notas')
          );
          $this->candidato_model->updateReferenciaVecinal($idrefvec, $datos);
          $msj = array(
            'codigo' => 1,
            'msg' => 'success'
          );
        }
        else{
            $datos = array(
                'creacion' => $date,
                'edicion' => $date,
                'id_usuario' => $id_usuario,
                'id_candidato' => $id_candidato,
                'nombre' => $this->input->post('nombre'),
                'telefono' => $this->input->post('telefono'),
                'domicilio' => $this->input->post('domicilio'),
                'concepto_candidato' => $this->input->post('concepto'),
                'concepto_familia' => $this->input->post('familia'),
                'civil_candidato' => $this->input->post('civil'),
                'hijos_candidato' => $this->input->post('hijos'),
                'sabe_trabaja' => $this->input->post('sabetrabaja'),
                'notas' => $this->input->post('notas')
            );
            $id = $this->candidato_model->insertReferenciaVecinal($datos);
            $msj = array(
              'codigo' => 2,
              'msg' => $id
            );
        }
      }
      echo json_encode($msj);
    }
    function crearPDF(){
      $mpdf = new \Mpdf\Mpdf();
      date_default_timezone_set('America/Mexico_City');
      $data['hoy'] = date("d-m-Y");
      $hoy = date("d-m-Y");
      $id_candidato = $_POST['idPDF'];
      $data['datos'] = $this->candidato_model->getInfoCandidato($id_candidato);
      $id_usuario = $this->session->userdata('id');
      $tipo_usuario = $this->session->userdata('tipo');
      if($tipo_usuario == 1){
        $usuario = $this->usuario_model->getDatosUsuarioInterno($id_usuario);
      }
      if($tipo_usuario == 2){
        $usuario = $this->usuario_model->getDatosUsuarioCliente($id_usuario);
      }
      if($tipo_usuario == 4){
        $usuario = $this->usuario_model->getDatosUsuarioSubcliente($id_usuario);
      }
      foreach($data['datos'] as $row){
        $f = $row->fecha_alta;
        $ffin = $row->fecha_fin;
        $nombreCandidato = $row->nombre." ".$row->paterno." ".$row->materno;
        $cliente = $row->cliente;
        $subcliente = $row->subcliente;
        $id_doping = $row->idDoping;
        $id_cliente = $row->id_cliente;
      }
      $fecha_fin = formatoFechaEspanol($ffin);
      $f_alta = formatoFechaEspanol($f);
      $hoy = formatoFecha($hoy);
			$data['secciones'] = $this->candidato_model->getSeccionesCandidato($id_candidato);
      $data['finalizado'] = $this->candidato_model->getDatosFinalizadosCandidato($id_candidato);
      $data['doping'] = $this->candidato_model->getDopingCandidato($id_candidato);
      $data['pruebas'] = $this->candidato_model->getPruebasCandidato($id_candidato);
      $data['docs'] = $this->candidato_model->getDocumentacionCandidato($id_candidato);
      $data['ver_documento'] = $this->candidato_model->getVerificacionDocumentosCandidato($id_candidato);
      $data['academico'] = $this->candidato_model->getEstudiosCandidato($id_candidato);
      $data['sociales'] = $this->candidato_model->getAntecedentesSociales($id_candidato);
      $data['familia'] = $this->candidato_model->getFamiliares($id_candidato);
      $data['egresos'] = $this->candidato_model->getEgresosFamiliares($id_candidato);
      $data['vivienda'] = $this->candidato_model->getDatosVivienda($id_candidato);
      $data['ref_personal'] = $this->candidato_model->getReferenciasPersonales($id_candidato);
      $data['ref_empresa'] = $this->candidato_model->getPersonasMismoTrabajo($id_candidato);
      $data['legal'] = $this->candidato_model->getVerificacionLegal($id_candidato);
      $data['nom'] = $this->candidato_model->getTrabajosNoMencionados($id_candidato);
      $data['finalizado'] = $this->candidato_model->getDatosFinalizadosCandidato($id_candidato);
      $data['ref_laboral'] = $this->candidato_model->getReferenciasLaborales($id_candidato);
      $data['ver_laboral'] = $this->candidato_model->getVerificacionReferencias($id_candidato);
      $data['ref_vecinal'] = $this->candidato_model->getReferenciasVecinales($id_candidato);
      $data['analista'] = $this->candidato_model->getAnalista($id_candidato);
      $data['coordinadora'] = $this->candidato_model->getCoordinadora($id_candidato);
			$data['ver_mayor_estudio'] = $this->candidato_model->getVerificacionMayoresEstudios($id_candidato);
			$data['gaps'] = $this->candidato_model->checkGaps($id_candidato);
      $data['cliente'] = $cliente;
      $data['subcliente'] = $subcliente;
      $data['fecha_fin'] = $ffin;

      $html = $this->load->view('pdfs/candidato_alterno_pdf',$data,TRUE);
      $mpdf->setAutoTopMargin = 'stretch';
      $mpdf->AddPage();
      $mpdf->SetHTMLHeader('<div style="width: 33%; float: left;"><img style="height: 50px;" src="'.base_url().'img/logo.png"></div><div style="width: 33%; float: right;text-align: right;">Fecha de Registro: '.$f_alta.'<br>Fecha de Elaboración: '.$fecha_fin.'</div>');
      $mpdf->SetHTMLFooter('<div style="position: absolute; left: 20px; bottom: 10px; color: rgba(0,0,0,0.5);"><p style="font-size: 10px;">Calle Benito Juarez # 5693, Col. Santa María del Pueblito <br>Zapopan, Jalisco C.P. 45018 <br>Tel. (33) 2301-8599<br><br>4-EST-001.Rev. 01 <br>Fecha de Rev. 05/06/2020</p></div><div style="position: absolute; right: 0;  bottom: 0;"><img class="" src="'.base_url().'img/logo_pie.png"></div>');    
      //Cifrar pdf
      $nombreArchivo = substr( md5(microtime()), 1, 12);
      /*$claveAleatoria = substr( md5(microtime()), 1, 8);
      $clave = ($usuario->clave != null)? $usuario->clave:$claveAleatoria;
      $mpdf->SetProtection(array(), $clave, 'r0d1@');*/

			$mpdf->WriteHTML($html);
      $mpdf->Output(''.$nombreArchivo.'.pdf','D'); // opens in browser
    }
    function crearPrevioPDF(){
      $mpdf = new \Mpdf\Mpdf();
      date_default_timezone_set('America/Mexico_City');
      $data['hoy'] = date("d-m-Y");
      $hoy = date("d-m-Y");
      $id_candidato = $_POST['idPrevio'];
      $data['datos'] = $this->candidato_model->getInfoCandidato($id_candidato);
      $id_usuario = $this->session->userdata('id');
      $tipo_usuario = $this->session->userdata('tipo');
      if($tipo_usuario == 1){
        $usuario = $this->usuario_model->getDatosUsuarioInterno($id_usuario);
      }
      if($tipo_usuario == 2){
        $usuario = $this->usuario_model->getDatosUsuarioCliente($id_usuario);
      }
      if($tipo_usuario == 4){
        $usuario = $this->usuario_model->getDatosUsuarioSubcliente($id_usuario);
      }
      foreach($data['datos'] as $row){
        $f = $row->fecha_alta;
        $ffin = $row->fecha_fin;
        $nombreCandidato = $row->nombre." ".$row->paterno." ".$row->materno;
        $cliente = $row->cliente;
        $subcliente = $row->subcliente;
        $id_doping = $row->idDoping;
        $id_cliente = $row->id_cliente;
      }
      $fecha_fin = formatoFechaEspanol($ffin);
      $f_alta = formatoFechaEspanol($f);
      $hoy = formatoFecha($hoy);
			$data['secciones'] = $this->candidato_model->getSeccionesCandidato($id_candidato);
      $data['finalizado'] = $this->candidato_model->getDatosFinalizadosCandidato($id_candidato);
      $data['doping'] = $this->candidato_model->getDopingCandidato($id_candidato);
      $data['pruebas'] = $this->candidato_model->getPruebasCandidato($id_candidato);
      $data['docs'] = $this->candidato_model->getDocumentacionCandidato($id_candidato);
      $data['ver_documento'] = $this->candidato_model->getVerificacionDocumentosCandidato($id_candidato);
      $data['academico'] = $this->candidato_model->getEstudiosCandidato($id_candidato);
      $data['sociales'] = $this->candidato_model->getAntecedentesSociales($id_candidato);
      $data['familia'] = $this->candidato_model->getFamiliares($id_candidato);
      $data['egresos'] = $this->candidato_model->getEgresosFamiliares($id_candidato);
      $data['vivienda'] = $this->candidato_model->getDatosVivienda($id_candidato);
      $data['ref_personal'] = $this->candidato_model->getReferenciasPersonales($id_candidato);
      $data['ref_empresa'] = $this->candidato_model->getPersonasMismoTrabajo($id_candidato);
      $data['legal'] = $this->candidato_model->getVerificacionLegal($id_candidato);
      $data['nom'] = $this->candidato_model->getTrabajosNoMencionados($id_candidato);
      $data['finalizado'] = $this->candidato_model->getDatosFinalizadosCandidato($id_candidato);
      $data['ref_laboral'] = $this->candidato_model->getReferenciasLaborales($id_candidato);
      $data['ver_laboral'] = $this->candidato_model->getVerificacionReferencias($id_candidato);
      $data['ref_vecinal'] = $this->candidato_model->getReferenciasVecinales($id_candidato);
      $data['analista'] = $this->candidato_model->getAnalista($id_candidato);
      $data['coordinadora'] = $this->candidato_model->getCoordinadora($id_candidato);
      $data['cliente'] = $cliente;
      $data['subcliente'] = $subcliente;
      $data['fecha_fin'] = $ffin;

      $html = $this->load->view('pdfs/previo_alterno_pdf',$data,TRUE);
      $mpdf->setAutoTopMargin = 'stretch';
      $mpdf->AddPage();
      $mpdf->SetHTMLHeader('<div style="width: 33%; float: left;"><img style="height: 50px;" src="'.base_url().'img/logo.png"></div><div style="width: 33%; float: right;text-align: right;">Fecha de Registro: '.$f_alta.'<br>Fecha de Elaboración: '.$fecha_fin.'</div>');
      $mpdf->SetHTMLFooter('<div style="position: absolute; left: 20px; bottom: 10px; color: rgba(0,0,0,0.5);"><p style="font-size: 10px;">Calle Benito Juarez # 5693, Col. Santa María del Pueblito <br>Zapopan, Jalisco C.P. 45018 <br>Tel. (33) 2301-8599<br><br>4-EST-001.Rev. 01 <br>Fecha de Rev. 05/06/2020</p></div><div style="position: absolute; right: 0;  bottom: 0;"><img class="" src="'.base_url().'img/logo_pie.png"></div>');    
      //Cifrar pdf
      $nombreArchivo = substr( md5(microtime()), 1, 12);
      /*$claveAleatoria = substr( md5(microtime()), 1, 8);
      $clave = ($usuario->clave != null)? $usuario->clave:$claveAleatoria;
      $mpdf->SetProtection(array(), $clave, 'r0d1@');*/

			$mpdf->WriteHTML($html);
      $mpdf->Output(''.$nombreArchivo.'.pdf','D'); // opens in browser
    }
}