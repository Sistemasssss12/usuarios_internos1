<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subcliente_RTS extends CI_Controller{

  function __construct(){
    parent::__construct();
    if(!$this->session->userdata('id')){
      redirect('Login/index');
    }
		$this->load->library('usuario_sesion');
		$this->usuario_sesion->checkStatusBD();
  }

  function index(){
    if (
      $this->session->userdata('logueado') &&
      $this->session->userdata('tipo') == 1
    ) {
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
      $data['personales'] = $this->funciones_model->getTiposPersona();
      $info['drogas'] = $this->funciones_model->getPaquetesAntidoping();
      $data['parentescos'] = $this->funciones_model->getParentescos();
      $data['escolaridades'] = $this->funciones_model->getEscolaridades();
      $info['zonas'] = $this->funciones_model->getNivelesZona();
      $info['viviendas'] = $this->funciones_model->getTiposVivienda();
      $info['condiciones'] = $this->funciones_model->getTiposCondiciones();
      //$data['examenes_doping'] = $this->funciones_model->getExamenDoping($id_cliente);
      $info['studies'] = $this->funciones_model->getTiposEstudios();
      $info['cands'] = $this->cliente_general_model->getCandidatosCliente($id_cliente);
      //$info['cands'] = $this->subcliente_rts_model->getCandidatosSubcliente($id_subcliente);
      $info['usuarios'] = $this->funciones_model->getTipoAnalistas(2);
      $info['tipos_docs'] = $this->funciones_model->getTiposDocumentos();
			$info['proyectos'] = $this->candidato_model->getProyectosCliente($id_cliente);
			$info['paises'] = $this->funciones_model->getPaises();
      $info['subclientes'] = $this->cliente_general_model->getSubclientes($id_cliente);
			$info['paquetes_antidoping'] = $this->funciones_model->getPaquetesAntidoping();
      //$info['grados_estudios'] = $this->funciones_model->getGradosEstudio();


      $vista['modals'] = $this->load->view('modals/mdl_subcliente_rts', $info, TRUE);
      $config = $this->funciones_model->getConfiguraciones();
			$data['version'] = $config->version_sistema;

      //Modals
		  $modales['modals'] = $this->load->view('modals/mdl_usuario','', TRUE);

      $this->load
      ->view('adminpanel/header', $data)
      ->view('adminpanel/scripts',$modales)
      ->view('analista/subcliente_rts', $vista)
      ->view('adminpanel/footer');
    }
  }

  /*----------------------------------------*/
  /*  Consultas 
  /*----------------------------------------*/
    function getCandidatos(){
      $id_cliente = $_GET['id'];
      $cand['recordsTotal'] = $this->subcliente_rts_model->getTotal($id_cliente, $this->session->userdata('idrol'), $this->session->userdata('id'));
      $cand['recordsFiltered'] = $this->subcliente_rts_model->getTotal($id_cliente, $this->session->userdata('idrol'), $this->session->userdata('id'));
      $cand['data'] = $this->subcliente_rts_model->getCandidatos($id_cliente, $this->session->userdata('idrol'), $this->session->userdata('id'));
      $this->output->set_output( json_encode( $cand ) );
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
    function getCandidatosPanelSubcliente(){
      $id_cliente = $_GET['id'];
      $cand['recordsTotal'] = $this->subcliente_rts_model->getTotalPanelSubcliente($id_cliente);
      $cand['recordsFiltered'] = $this->subcliente_rts_model->getTotalPanelSubcliente($id_cliente);
      $cand['data'] = $this->subcliente_rts_model->getCandidatosPanelSubcliente($id_cliente);
      $this->output->set_output( json_encode( $cand ) );
    }

  /*----------------------------------------*/
  /*  Proceso 
  /*----------------------------------------*/
    function registrar(){
      if($this->input->post('previo') != 0){
        $this->form_validation->set_rules('nombre', 'Nombre(s)', 'required|trim');
        $this->form_validation->set_rules('paterno', 'Apellido paterno', 'required|trim');
        $this->form_validation->set_rules('materno', 'Apellido materno', 'trim');
        $this->form_validation->set_rules('subcliente', 'Subcliente (Proveedor)', 'required');
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
          $puesto = $this->input->post('puesto');
          $pais = $this->input->post('pais');
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

            $base = 'k*jJlrsH:cY]O^Z^/J2)Pz{)qz:+yCa]^+V0S98Zf$sV[c@hKKG07Q{utg%OlODS';
            $aux = substr( md5(microtime()), 1, 8);
            $seccion = $this->candidato_model->getProyectoPrevio($id_proyecto_previo);
            if($seccion->lleva_empleos == 1 || $seccion->lleva_estudios == 1){
              $tipo_formulario = 2;
              $token = md5($aux.$base);
            }
            else{
              $tipo_formulario = 0;
              $token = '';
            }

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
                'tipo_formulario' => $tipo_formulario,
                'token' => $token,
                'puesto' => $puesto,
                'fecha_alta' => $date,
                'nombre' => strtoupper($nombre),
                'paterno' => strtoupper($paterno),
                'materno' => strtoupper($materno),
                'celular' => $celular,
                'subproyecto' => $seccion->proyecto,
                'pais' => $pais
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
                'tipo_formulario' => $tipo_formulario,
                'token' => $token,
                'puesto' => $puesto,
                'fecha_alta' => $date,
                'nombre' => strtoupper($nombre),
                'paterno' => strtoupper($paterno),
                'materno' => strtoupper($materno),
                'celular' => $celular,
                'subproyecto' => $seccion->proyecto,
                'pais' => $pais
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
              'lleva_empleos' => $seccion->lleva_empleos,
              'lleva_criminal' => $seccion->lleva_criminal,
              'lleva_estudios' => $seccion->lleva_estudios,
              'lleva_gaps' => $seccion->lleva_gaps,
              'lleva_sociales' => $seccion->lleva_sociales,
              'lleva_investigacion' => $seccion->lleva_investigacion,
              'lleva_no_mencionados' => $seccion->lleva_no_mencionados,
              'lleva_familiares' => $seccion->lleva_familiares,
              'lleva_egresos' => $seccion->lleva_egresos,
              'lleva_vivienda' => $seccion->lleva_vivienda,
              'id_seccion_datos_generales' => $seccion->id_seccion_datos_generales,
              'id_seccion_verificacion_docs' => $seccion->id_seccion_verificacion_docs,
              'id_seccion_global_search' => $seccion->id_seccion_global_search,
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

            /*if ($usuario == 2 || $usuario == 3) {
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
              $mail->Password = '*DsaT(Lq;X5&';
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
            */
            $msj = array(
              'codigo' => 1,
              'msg' => 'Success'
            );
          }
        } 
      }
      else{
        /*if($this->input->post('opcion') == 1){
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
        else{*/
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
          $this->form_validation->set_rules('investigacion', 'Investigación legal', 'required');
          $this->form_validation->set_rules('no_mencionados', 'Trabajos no mencionados', 'required');
          $this->form_validation->set_rules('ref_personales', 'Referencias personales', 'required');
          $this->form_validation->set_rules('documentacion', 'Documentación', 'required');
          $this->form_validation->set_rules('familiar', 'Información del grupo familiar', 'required');
          $this->form_validation->set_rules('egresos', 'Egresos mensuales', 'required');
          $this->form_validation->set_rules('habitacion', 'Habitación y medio ambiente', 'required');
          $this->form_validation->set_rules('ref_vecinales', 'Referencias vecinales', 'required');
          $this->form_validation->set_rules('criminal', 'Antecedentes criminales', 'required');
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
            $puesto = $this->input->post('puesto');
            $pais = $this->input->post('pais');
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

              $base = 'k*jJlrsH:cY]O^Z^/J2)Pz{)qz:+yCa]^+V0S98Zf$sV[c@hKKG07Q{utg%OlODS';
              $aux = substr( md5(microtime()), 1, 8);
              if($this->input->post('empleos') == 1 || $this->input->post('estudios') == 1){
                $tipo_formulario = 2;
                $token = md5($aux.$base);
              }
              else{
                $tipo_formulario = 0;
                $token = '';
              }

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
                  'tipo_formulario' => $tipo_formulario,
                  'token' => $token,
                  'puesto' => $puesto,
                  'fecha_alta' => $date,
                  'nombre' => strtoupper($nombre),
                  'paterno' => strtoupper($paterno),
                  'materno' => strtoupper($materno),
                  'celular' => $celular,
                  'subproyecto' => $proceso,
                  'pais' => $pais
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
                  'tipo_formulario' => $tipo_formulario,
                  'token' => $token,
                  'puesto' => $puesto,
                  'fecha_alta' => $date,
                  'nombre' => strtoupper($nombre),
                  'paterno' => strtoupper($paterno),
                  'materno' => strtoupper($materno),
                  'celular' => $celular,
                  'subproyecto' => $proceso,
                  'pais' => $pais
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
              $lleva_investigacion = 0;
              //Datos Generales
              if($this->input->post('generales') == 1){
                $generales = 1;
                $salida = $this->candidato_model->getEtiquetaSeccion($generales);
                $etiquetas .= $salida->etiqueta;
                $id_seccion_datos_generales = $generales;
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
                $salida = $this->candidato_model->getEtiquetaSeccion(53);
                $etiquetas .= $salida->etiqueta;
              }
              //Referencias Personales
              if($this->input->post('ref_personales') == 1){
                $salida = $this->candidato_model->getEtiquetaSeccion(54);
                $etiquetas .= $salida->etiqueta;
                $num_personales = 2;
              }
              //Empleos
              if($this->input->post('empleos') == 1){
                $lleva_empleos = 1;
                $tiempo_empleos = 'All';
                $salida = $this->candidato_model->getEtiquetaSeccion(59);
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
              //Investigacion legal
              if($this->input->post('investigacion') == 1){
                $lleva_investigacion = 1;
                $salida = $this->candidato_model->getEtiquetaSeccion(56);
                $etiquetas .= $salida->etiqueta;
              }
              //Dependiendo si lleva vecinales se va a la columna de Vista, sino en la columna Estudio
              //Visita o extras
              if($this->input->post('ref_vecinales') == 1){
                $lleva_vecinales = 1;
                //Documentacion
                if($this->input->post('documentacion') == 1){
                  $id_seccion_verificacion_docs = 58;
                  $salida = $this->candidato_model->getEtiquetaSeccion(58);
                  $etiquetas_visita .= $salida->etiqueta;
                }
                else{
                  $id_seccion_verificacion_docs = NULL;
                }
                //Grupo familiar
                if($this->input->post('familiar') == 1){
                  $lleva_familiares = 1;
                  $salida = $this->candidato_model->getEtiquetaSeccion(35);
                  $etiquetas_visita .= $salida->etiqueta;
                }
                //Egresos mensuales
                if($this->input->post('egresos') == 1){
                  $lleva_egresos = 1;
                  $id_finanzas = 36;
                  $salida = $this->candidato_model->getEtiquetaSeccion(36);
                  $etiquetas_visita .= $salida->etiqueta;
                }
                else{
                  $id_finanzas = NULL;
                }
                //Habitacion y medio ambiente
                if($this->input->post('habitacion') == 1){
                  $lleva_vivienda = 1;
                  $salida = $this->candidato_model->getEtiquetaSeccion(37);
                  $etiquetas_visita .= $salida->etiqueta;
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
              }
              else{
                $lleva_vecinales = 0;
                //Documentacion
                if($this->input->post('documentacion') == 1){
                  $id_seccion_verificacion_docs = 58;
                  $salida = $this->candidato_model->getEtiquetaSeccion(58);
                  $etiquetas .= $salida->etiqueta;
                }
                else{
                  $id_seccion_verificacion_docs = NULL;
                }
                //Grupo familiar
                if($this->input->post('familiar') == 1){
                  $lleva_familiares = 1;
                  $salida = $this->candidato_model->getEtiquetaSeccion(35);
                  $etiquetas .= $salida->etiqueta;
                }
                //Egresos mensuales
                if($this->input->post('egresos') == 1){
                  $lleva_egresos = 1;
                  $id_finanzas = 36;
                  $salida = $this->candidato_model->getEtiquetaSeccion(36);
                  $etiquetas .= $salida->etiqueta;
                }
                else{
                  $id_finanzas = NULL;
                }
                //Habitacion y medio ambiente
                if($this->input->post('habitacion') == 1){
                  $lleva_vivienda = 1;
                  $salida = $this->candidato_model->getEtiquetaSeccion(37);
                  $etiquetas .= $salida->etiqueta;
                }
              }
              //GAPS
              $lleva_gaps = ($lleva_empleos == 1 || $lleva_estudios == 1)? 1:0;
              //Antecedentes criminales
              if($this->input->post('criminal') == 1){
                $lleva_criminal = 1;
                $tiempo_criminales = 'All';
              }
              else{
                $lleva_criminal = 0;
                $tiempo_criminales = NULL;
              }

              //Se guarda el registro para la tabla candidato_seccion
              $candidato_secciones = array(
                'creacion' => $date,
                $tipo_usuario => $id_usuario,
                'id_candidato' => $id_candidato,
                'proyecto' => $proceso,
                'secciones' => $etiquetas,
                'lleva_empleos' => $lleva_empleos,
                'lleva_criminal' => $lleva_criminal,
                'lleva_estudios' => $lleva_estudios,
                'lleva_gaps' => $lleva_gaps,
                'lleva_sociales' => $lleva_sociales,
                'lleva_investigacion' => $lleva_investigacion,
                'lleva_no_mencionados' => $lleva_no_mencionados,
                'lleva_familiares' => $lleva_familiares,
                'lleva_egresos' => $lleva_egresos,
                'lleva_vivienda' => $lleva_vivienda,
                'id_seccion_datos_generales' => $id_seccion_datos_generales,
								'id_seccion_verificacion_docs' => $id_seccion_verificacion_docs,
                'id_finanzas' => $id_finanzas,
                'tiempo_empleos' => $tiempo_empleos,
                'tiempo_criminales' => $tiempo_criminales,
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
                  'lleva_criminal' => $lleva_criminal,
                  'lleva_estudios' => $lleva_estudios,
                  'lleva_gaps' => $lleva_gaps,
                  'lleva_sociales' => $lleva_sociales,
                  'lleva_investigacion' => $lleva_investigacion,
                  'lleva_no_mencionados' => $lleva_no_mencionados,
                  'lleva_familiares' => $lleva_familiares,
                  'lleva_egresos' => $lleva_egresos,
                  'lleva_vivienda' => $lleva_vivienda,
                  'id_seccion_datos_generales' => $id_seccion_datos_generales,
								  'id_seccion_verificacion_docs' => $id_seccion_verificacion_docs,
								  'id_finanzas' => $id_finanzas,
                  'tiempo_empleos' => $tiempo_empleos,
                  'tiempo_criminales' => $tiempo_criminales,
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

              /*if ($usuario == 2 || $usuario == 3) {
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
                $mail->Password = '*DsaT(Lq;X5&';
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
              }*/
              $msj = array(
                'codigo' => 1,
                'msg' => 'Success'
              );
            }
          } 
        //}
      }
      echo json_encode($msj);
    }
    function generarPassword(){
      $this->load->config('email');
      date_default_timezone_set('America/Mexico_City');
      $date = date('Y-m-d H:i:s');
      $id_candidato = $this->input->post('id_candidato');
      $correo = $this->input->post('correo');
      $base = 'k*jJlrsH:cY]O^Z^/J2)Pz{)qz:+yCa]^+V0S98Zf$sV[c@hKKG07Q{utg%OlODS';
      $aux = substr( md5(microtime()), 1, 8);
      $token = md5($aux.$base);
      $this->candidato_model->regenerarPassword($id_candidato, $date, $token);
      
      $data_candidato = $this->candidato_model->getInfoCandidatoEspecifico($id_candidato);
      $from = $this->config->item('smtp_user');
      $to = $correo;
      $subject = $data_candidato->subcliente." - credentials for register form";
      $datos['password'] = $aux;
      $datos['cliente'] = strtoupper($data_candidato->subcliente);
      $datos['email'] = $correo;
      $message = $this->load->view('mails/mail_candidato_personalizado',$datos,TRUE);
      $this->load->library('phpmailer_lib');
      $mail = $this->phpmailer_lib->load();
      $mail->isSMTP();
      $mail->Host     = 'mail.rodicontrol.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'rodicontrol@rodicontrol.com';
      $mail->Password = 'r49o*&rUm%91';
      $mail->SMTPSecure = 'ssl';
      $mail->Port     = 465;
      
      $mail->setFrom('rodicontrol@rodicontrol.com', $data_candidato->subcliente);
      $mail->addAddress($to);
      $mail->Subject = $subject;
      $mail->isHTML(true);
      $mailContent = $message;
      $mail->Body = $mailContent;

      if(!$mail->send()){
        $msj = array(
          'codigo' => 3,
          'msg' => 'No sent'
        );
      }else{
        $msj = array(
          'codigo' => 1,
          'msg' => $aux
        );
      }  
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
      $this->form_validation->set_rules('calle', 'Calle', 'trim');
      $this->form_validation->set_rules('exterior', 'No. Exterior', 'trim|max_length[8]');
      $this->form_validation->set_rules('interior', 'No. Interior', 'trim|max_length[8]');
      $this->form_validation->set_rules('colonia', 'Colonia', 'trim');
      $this->form_validation->set_rules('estado', 'Estado', 'trim|numeric');
      $this->form_validation->set_rules('municipio', 'Municipio', 'trim|numeric');
      $this->form_validation->set_rules('cp', 'Código postal', 'trim|numeric|max_length[5]');
      $this->form_validation->set_rules('civil', 'Civil', 'required|trim');
      $this->form_validation->set_rules('celular', 'Tel. Celular', 'required|trim|max_length[16]');
      $this->form_validation->set_rules('tel_casa', 'Tel. Casa', 'trim|max_length[16]');
      $this->form_validation->set_rules('correo', 'Correo', 'required|trim|valid_email');
      $this->form_validation->set_rules('domicilio', 'Domicilio completo', 'trim');
      $this->form_validation->set_rules('pais', 'Pais donde reside', 'trim');

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
          'puesto' => $this->input->post('puesto'),
          'nacionalidad' => $this->input->post('nacionalidad'),
          'genero' => $this->input->post('genero'),
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
          'correo' => $this->input->post('correo'),
          'domicilio_internacional' => $this->input->post('domicilio'),
          'pais' => $this->input->post('pais'),

        );
        $this->candidato_model->editarCandidato($candidato, $id_candidato);
        $msj = array(
          'codigo' => 1,
          'msg' => 'success'
        );
      }
      echo json_encode($msj);
    }
    function guardarMayoresEstudios(){
      $this->form_validation->set_rules('mayor_estudios_candidato', 'Nivel escolar del Candidato', 'required|trim');
      $this->form_validation->set_rules('periodo_candidato', 'Periodo del Candidato', 'required|trim');
      $this->form_validation->set_rules('escuela_candidato', 'Escuela del Candidato', 'required|trim');
      $this->form_validation->set_rules('ciudad_candidato', 'Ciudad del Candidato', 'required|trim');
      $this->form_validation->set_rules('certificado_candidato', 'Certificado del Candidato', 'required|trim');
      $this->form_validation->set_rules('mayor_estudios_analista', 'Nivel escolar revisado por Analista', 'required|trim');
      $this->form_validation->set_rules('periodo_analista', 'Periodo revisado por Analista', 'required|trim');
      $this->form_validation->set_rules('escuela_analista', 'Escuela revisado por Analista', 'required|trim');
      $this->form_validation->set_rules('ciudad_analista', 'Ciudad revisado por Analista', 'required|trim');
      $this->form_validation->set_rules('certificado_analista', 'Certificado obtenido revisado por Analista', 'required|trim');
      $this->form_validation->set_rules('comentarios', 'Comentarios de la analista', 'required|trim');

      $this->form_validation->set_message('required','El campo {field} es obligatorio');
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

        $data['estudios'] = $this->candidato_model->revisionMayoresEstudios($id_candidato);
        if($data['estudios']){
          $candidato = array(
              'edicion' => $date,
              'id_usuario' => $id_usuario,
              'id_grado_estudio' => $this->input->post('mayor_estudios_candidato'),
              'estudios_periodo' => $this->input->post('periodo_candidato'),
              'estudios_escuela' => $this->input->post('escuela_candidato'),
              'estudios_ciudad' => $this->input->post('ciudad_candidato'),
              'estudios_certificado' => $this->input->post('certificado_candidato')
          );
          $this->candidato_model->editarCandidato($candidato, $id_candidato);
          $verificacion = array(
              'edicion' => $date,
              'id_usuario' => $id_usuario,
              'id_tipo_studies' => $this->input->post('mayor_estudios_analista'),
              'periodo' => $this->input->post('periodo_analista'),
              'escuela' => $this->input->post('escuela_analista'),
              'ciudad' => $this->input->post('ciudad_analista'),
              'certificado' => $this->input->post('certificado_analista'),
              'comentarios' => $this->input->post('comentarios')
          );
          $this->candidato_model->editarMayoresEstudios($verificacion, $id_candidato);
          $msj = array(
            'codigo' => 1,
            'msg' => 'success'
          );
        }
        else{
          $candidato = array(
              'edicion' => $date,
              'id_usuario' => $id_usuario,
              'id_grado_estudio' => $this->input->post('mayor_estudios_candidato'),
              'estudios_periodo' => $this->input->post('periodo_candidato'),
              'estudios_escuela' => $this->input->post('escuela_candidato'),
              'estudios_ciudad' => $this->input->post('ciudad_candidato'),
              'estudios_certificado' => $this->input->post('certificado_candidato')
          );
          $this->candidato_model->editarCandidato($candidato, $id_candidato);
          $verificacion = array(
              'creacion' => $date,
              'edicion' => $date,
              'id_usuario' => $id_usuario,
              'id_candidato' => $id_candidato,
              'id_tipo_studies' => $this->input->post('mayor_estudios_analista'),
              'periodo' => $this->input->post('periodo_analista'),
              'escuela' => $this->input->post('escuela_analista'),
              'ciudad' => $this->input->post('ciudad_analista'),
              'certificado' => $this->input->post('certificado_analista'),
              'comentarios' => $this->input->post('comentarios')
          );
          $id_ver_estudios = $this->candidato_model->guardarMayoresEstudios($verificacion);
          $msj = array(
            'codigo' => 1,
            'msg' => 'success'
          );
        }
      }
      echo json_encode($msj);
    }
    function guardarVerificacionIdentidad(){
      $this->form_validation->set_rules('ine', 'ID (INE)', 'trim');
      $this->form_validation->set_rules('ine_ano', 'Año de registro', 'trim|numeric|max_length[4]');
      $this->form_validation->set_rules('ine_vertical', 'Número vertical', 'trim');
      $this->form_validation->set_rules('ine_institucion', 'Fecha / Institución del ID', 'trim');
      $this->form_validation->set_rules('pasaporte', 'ID del Pasaporte', 'trim');
      $this->form_validation->set_rules('pasaporte_fecha', 'Fecha / Institución del Pasaporte', 'trim');
      $this->form_validation->set_rules('penales_id', 'ID de la Carta de Antecedentes No Penales (Carta policía)', 'trim');
      $this->form_validation->set_rules('penales_institucion', 'Fecha / Institución de la Carta de Antecedentes No Penales (Carta policía)', 'trim');
      $this->form_validation->set_rules('forma_migratoria', 'ID de la Forma migratoria', 'trim');
      $this->form_validation->set_rules('forma_migratoria_fecha', 'Fecha / Institución de la Forma migratoria', 'trim');
      $this->form_validation->set_rules('comentarios', 'Comentarios', 'required|trim');

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

        $candidato = array(
          'id_usuario' => $id_usuario
        );
        $this->candidato_model->editarCandidato($candidato, $id_candidato);
        $verificacion_documento = array(
          'creacion' => $date,
          'id_usuario' => $id_usuario,
          'id_candidato' => $id_candidato,
          'ine' => $this->input->post('ine'),
          'ine_ano' => $this->input->post('ine_ano'),
          'ine_vertical' => $this->input->post('ine_vertical'),
          'ine_institucion' => $this->input->post('ine_institucion'),
          'penales' => $this->input->post('penales_id'),
          'penales_institucion' => $this->input->post('penales_institucion'),
          'pasaporte' => $this->input->post('pasaporte'),
          'pasaporte_fecha' => $this->input->post('pasaporte_fecha'),
          'forma_migratoria' => $this->input->post('forma_migratoria'),
          'forma_migratoria_fecha' => $this->input->post('forma_migratoria_fecha'),
          'comentarios' => $this->input->post('comentarios')
        );      
        $this->candidato_model->eliminarVerificacionDocumentacion($id_candidato);
        $this->candidato_model->guardarVerificacionDocumento($verificacion_documento);
        $msj = array(
          'codigo' => 1,
          'msg' => 'success'
        );
      }
      echo json_encode($msj);
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
    function guardarGlobalesGenerales(){
      $this->form_validation->set_rules('law_enforcement', 'Law enforcement', 'required|trim');
      $this->form_validation->set_rules('regulatory', 'Regulatory', 'required|trim');
      $this->form_validation->set_rules('sanctions', 'Sanctions', 'required|trim');
      $this->form_validation->set_rules('other_bodies', 'Other bodies', 'required|trim');
      $this->form_validation->set_rules('media_searches', 'Web and media searches', 'required|trim');
      $this->form_validation->set_rules('comentarios', 'Comentarios', 'required|trim');
      
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
        $id_usuario = $this->session->userdata('id');

        $candidato = array(
          'id_usuario' => $id_usuario
        );
        $this->candidato_model->editarCandidato($candidato, $id_candidato);
        $global = array(
          'creacion' => $date,
          'id_usuario' => $id_usuario,
          'id_candidato' => $id_candidato,
          'law_enforcement' => $this->input->post('law_enforcement'),
          'regulatory' => $this->input->post('regulatory'),
          'sanctions' => $this->input->post('sanctions'),
          'other_bodies' => $this->input->post('other_bodies'),
          'media_searches' => $this->input->post('media_searches'),
          'global_comentarios' => $this->input->post('comentarios')
        );
        $this->candidato_model->eliminarGlobalSearches($id_candidato);
        $this->candidato_model->guardarGlobalSearches($global);
        $msj = array(
          'codigo' => 1,
          'msg' => 'success'
        );
      }
      echo json_encode($msj);
    }
    function terminarProceso(){
      $this->form_validation->set_rules('identidad', 'Verif. Identidad', 'required|trim');
      $this->form_validation->set_rules('global', 'Verif. Global Data Searches', 'required|trim');
      $this->form_validation->set_rules('penales', 'Verif. Criminal', 'required|trim');
      $this->form_validation->set_rules('laboral', 'Verif. Historial de empleos', 'required|trim');
      $this->form_validation->set_rules('estudios', 'Verif. Estudios', 'required|trim');
      $this->form_validation->set_rules('ofac', 'Verif. OFAC', 'required|trim');
      $this->form_validation->set_rules('credito', 'Buró de crédito', 'required|trim');
      $this->form_validation->set_rules('comentario', 'Declaración final', 'required|trim');
      $this->form_validation->set_rules('bgc_status', 'Estatus final del Estudio', 'required|trim');
      
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
        $id_usuario = $this->session->userdata('id');
        $id_candidato = $this->input->post('id_candidato');

        $cand = $this->candidato_model->getInfoCandidatoEspecifico($id_candidato);
        switch($cand->status){
          case 0: 
            $nuevo_estatus = 1;
            break;
          case 1: 
            $nuevo_estatus = 2;
            break;
          case 2: 
            $nuevo_estatus = 2;
            break;
        }
        $num = $this->candidato_model->checkBGC($id_candidato);
        if($num > 0){
            $bgc = array(
                'edicion' => $date,
                'id_usuario' => $id_usuario,
                'identidad_check' => $this->input->post('identidad'),
                'empleo_check' => $this->input->post('laboral'),
                'estudios_check' => $this->input->post('estudios'),
                'penales_check' => $this->input->post('penales'),
                'ofac_check' => $this->input->post('ofac'),
                'global_searches_check' => $this->input->post('global'),
                'credito_check' => $this->input->post('credito'),
                'comentario_final' => $this->input->post('comentario')
            );
            $this->candidato_model->editarBGC($bgc, $id_candidato);
            $this->candidato_model->guardarEstatusProgresivo($nuevo_estatus, $this->input->post('bgc_status'), $id_candidato);
        }
        else{
            $bgc = array(
                'creacion' => $date,
                'edicion' => $date,
                'id_usuario' => $id_usuario,
                'id_candidato' => $id_candidato,
                'identidad_check' => $this->input->post('identidad'),
                'empleo_check' => $this->input->post('laboral'),
                'estudios_check' => $this->input->post('estudios'),
                'visita_check' => 3,
                'penales_check' => $this->input->post('penales'),
                'ofac_check' => $this->input->post('ofac'),
                'medico_check' => 3,
                'oig_check' => 3,
                'global_searches_check' => $this->input->post('global'),
                'sam_check' => 3,
                'credito_check' => $this->input->post('credito'),
                'comentario_final' => $this->input->post('comentario')
            );
            $this->candidato_model->guardarBGC($bgc);
            $this->candidato_model->guardarEstatusProgresivo($nuevo_estatus, $this->input->post('bgc_status'), $id_candidato);

        }
        $msj = array(
          'codigo' => 1,
          'msg' => 'success'
        );
      }
      echo json_encode($msj);
    }
    function crearPDFSimple(){
      $mpdf = new \Mpdf\Mpdf();
      date_default_timezone_set('America/Mexico_City');
      $data['hoy'] = date("d-m-Y");
      $hoy = date("d-m-Y");
      if($this->uri->segment(3) != null){
        $id_candidato = $this->uri->segment(3);
      }
      else{
          $id_candidato = $_POST['idCandidatoSimple'];
      }
      $data['datos'] = $this->candidato_model->getCandidato($id_candidato);
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
        $fform = $row->fecha_contestado;
        $fdocs = $row->fecha_documentos;
        $fbgc = $row->ultima_fecha_bgc;
        $nombreCandidato = $row->nombre." ".$row->paterno." ".$row->materno;
        $cliente = $row->cliente;
        $subcliente = $row->subcliente;
        $proyecto = $row->proyecto;
        $id_doping = $row->idDoping;
      }
      $fecha_bgc = DateTime::createFromFormat('Y-m-d H:i:s', $fbgc);
      $fecha_bgc = $fecha_bgc->format('F d, Y');
      $f_alta = formatoFecha($f);
      $fform = formatoFecha($fform);
      $fdocs = formatoFecha($fdocs);
      $fbgc = formatoFecha($fbgc);
      $hoy = formatoFecha($hoy);
			$data['secciones'] = $this->candidato_model->getSeccionesCandidato($id_candidato);
      $data['bgc'] = $this->candidato_model->getBGC($id_candidato);
      $data['global_searches'] = $this->candidato_model->getGlobalSearches($id_candidato);
      $data['fecha_ver_laboral'] = $this->candidato_model->getFechaVerificacionLaboral($id_candidato);
      $data['fecha_ver_estudios'] = $this->candidato_model->getFechaVerificacionEstudios($id_candidato);
      $data['fecha_ver_penales'] = $this->candidato_model->getFechaVerificacionPenales($id_candidato);
      $data['fecha_ver_documentos'] = $this->candidato_model->getFechaVerificacionDocumentos($id_candidato);
      $data['fecha_ver_ofac'] = $this->candidato_model->getFechaVerificacionOfac($id_candidato);
      $data['docs'] = $this->candidato_model->getDocumentacionCandidato($id_candidato);
      $data['ver_documento'] = $this->candidato_model->getVerificacionDocumentosCandidato($id_candidato);
      $data['familia'] = $this->candidato_model->getFamiliaresCandidato($id_candidato);
      $data['det_estudio'] = $this->candidato_model->getStatusVerificacionEstudios($id_candidato);
      $data['ver_mayor_estudio'] = $this->candidato_model->getVerificacionMayoresEstudios($id_candidato);
      $data['ref_laboral'] = $this->candidato_model->getReferenciasLaborales($id_candidato);
      $data['ver_laboral'] = $this->candidato_model->getVerificacionReferencias($id_candidato);
      $data['gaps'] = $this->candidato_model->checkGaps($id_candidato);
      $data['det_empleo'] = $this->candidato_model->getStatusVerificacionEmpleo($id_candidato);
      $data['det_penales'] = $this->candidato_model->getStatusVerificacionPenales($id_candidato);
      $data['analista'] = $this->candidato_model->getAnalista($id_candidato);
      $data['coordinadora'] = $this->candidato_model->getCoordinadora($id_candidato);
      $data['checklist'] = $this->candidato_model->getVerificacionChecklist($id_candidato);
      $data['pruebas'] = $this->candidato_model->getPruebasCandidato($id_candidato);
      $data['cliente'] = $cliente;
      $data['subcliente'] = $subcliente;
      $data['proyecto'] = $proyecto;
      $data['fecha_bgc'] = $fecha_bgc;

      $doping = $this->doping_model->getDatosDoping($id_doping);
      $data['doping'] = $doping;
      $html = $this->load->view('pdfs/subcliente_simple_pdf',$data,TRUE);
      $mpdf->setAutoTopMargin = 'stretch';
      $mpdf->AddPage();
      $mpdf->SetHTMLHeader('<div style="width: 33%; float: left;"><img style="height: 50px;" src="'.base_url().'img/rts_logo.jpg"></div><div style="width: 33%; float: right;text-align: right;">Request Date: '.$f_alta.'<br>Release Date: '.$fbgc.'</div>');
      $mpdf->SetHTMLFooter('<div style="position: absolute; left: 20px; bottom: 10px; color: rgba(0,0,0,0.5);"><p style="font-size: 10px;">Calle Benito Juarez # 5693, Col. Santa María del Pueblito <br>Zapopan, Jalisco C.P. 45018 <br>Tel. (33) 2301-8599<br><br>4-EST-001.Rev. 01 <br>Fecha de Rev. 05/06/2020</p></div>');
      //Cifrar pdf
      $nombreArchivo = substr( md5(microtime()), 1, 12);
      /*$claveAleatoria = substr( md5(microtime()), 1, 8);
      $clave = ($usuario->clave != null)? $usuario->clave:$claveAleatoria;
      $mpdf->SetProtection(array(), $clave, 'r0d1@');*/

      $mpdf->autoPageBreak = false;
			$mpdf->WriteHTML($html);
      $mpdf->Output(''.$nombreArchivo.'.pdf','D'); // opens in browser
    }
    function crearPDFCompleto(){
      $mpdf = new \Mpdf\Mpdf();
      date_default_timezone_set('America/Mexico_City');
      $data['hoy'] = date("d-m-Y");
      $hoy = date("d-m-Y");
      $id_candidato = $_POST['idCandidatoCompleto'];
      $data['datos'] = $this->candidato_model->getCandidato($id_candidato);
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
        $fform = $row->fecha_contestado;
        $fdocs = $row->fecha_documentos;
        $fbgc = $row->ultima_fecha_bgc;
        $nombreCandidato = $row->nombre." ".$row->paterno." ".$row->materno;
        $cliente = $row->cliente;
        $subcliente = $row->subcliente;
        $proyecto = $row->proyecto;
        $id_doping = $row->idDoping;
      }
      $fecha_bgc = DateTime::createFromFormat('Y-m-d H:i:s', $fbgc);
      $fecha_bgc = $fecha_bgc->format('F d, Y');
      $f_alta = formatoFecha($f);
      $fform = formatoFecha($fform);
      $fdocs = formatoFecha($fdocs);
      $fbgc = formatoFecha($fbgc);
      $hoy = formatoFecha($hoy);
			$data['secciones'] = $this->candidato_model->getSeccionesCandidato($id_candidato);
      $data['bgc'] = $this->candidato_model->getBGC($id_candidato);
      $data['global_searches'] = $this->candidato_model->getGlobalSearches($id_candidato);
      $data['fecha_ver_laboral'] = $this->candidato_model->getFechaVerificacionLaboral($id_candidato);
      $data['fecha_ver_estudios'] = $this->candidato_model->getFechaVerificacionEstudios($id_candidato);
      $data['fecha_ver_penales'] = $this->candidato_model->getFechaVerificacionPenales($id_candidato);
      $data['fecha_ver_documentos'] = $this->candidato_model->getFechaVerificacionDocumentos($id_candidato);
      $data['fecha_ver_ofac'] = $this->candidato_model->getFechaVerificacionOfac($id_candidato);
      $data['docs'] = $this->candidato_model->getDocumentacionCandidato($id_candidato);
      $data['ver_documento'] = $this->candidato_model->getVerificacionDocumentosCandidato($id_candidato);
      $data['familia'] = $this->candidato_model->getFamiliaresCandidato($id_candidato);
      $data['det_estudio'] = $this->candidato_model->getStatusVerificacionEstudios($id_candidato);
      $data['ver_mayor_estudio'] = $this->candidato_model->getVerificacionMayoresEstudios($id_candidato);
      $data['ref_laboral'] = $this->candidato_model->getReferenciasLaborales($id_candidato);
      $data['ver_laboral'] = $this->candidato_model->getVerificacionReferencias($id_candidato);
      $data['gaps'] = $this->candidato_model->checkGaps($id_candidato);
      $data['det_empleo'] = $this->candidato_model->getStatusVerificacionEmpleo($id_candidato);
      $data['det_penales'] = $this->candidato_model->getStatusVerificacionPenales($id_candidato);
      $data['analista'] = $this->candidato_model->getAnalista($id_candidato);
      $data['coordinadora'] = $this->candidato_model->getCoordinadora($id_candidato);
      $data['checklist'] = $this->candidato_model->getVerificacionChecklist($id_candidato);
      $data['pruebas'] = $this->candidato_model->getPruebasCandidato($id_candidato);
			$data['fecha_credito'] = $this->candidato_model->getFechaCredito($id_candidato);
			$data['credito'] = $this->candidato_model->checkCredito($id_candidato);
      $data['cliente'] = $cliente;
      $data['subcliente'] = $subcliente;
      $data['proyecto'] = $proyecto;
      $data['fecha_bgc'] = $fecha_bgc;

      $doping = $this->doping_model->getDatosDoping($id_doping);
      $data['doping'] = $doping;
      $html = $this->load->view('pdfs/subcliente_pdf',$data,TRUE);
      $mpdf->setAutoTopMargin = 'stretch';
      $mpdf->AddPage();
      $mpdf->SetHTMLHeader('<div style="width: 33%; float: left;"><img style="height: 50px;" src="'.base_url().'img/rts_logo.jpg"></div><div style="width: 33%; float: right;text-align: right;">Request Date: '.$f_alta.'<br>Release Date: '.$fbgc.'</div>');
      $mpdf->SetHTMLFooter('<div style="position: absolute; left: 20px; bottom: 10px; color: rgba(0,0,0,0.5);"><p style="font-size: 10px;">Calle Benito Juarez # 5693, Col. Santa María del Pueblito <br>Zapopan, Jalisco C.P. 45018 <br>Tel. (33) 2301-8599<br><br>4-EST-001.Rev. 01 <br>Fecha de Rev. 05/06/2020</p></div>');
      
      //Cifrar pdf
      $nombreArchivo = substr( md5(microtime()), 1, 12);
      /*$claveAleatoria = substr( md5(microtime()), 1, 8);
      $clave = ($usuario->clave != null)? $usuario->clave:$claveAleatoria;
      $mpdf->SetProtection(array(), $clave, 'r0d1@');*/

      $mpdf->autoPageBreak = false;
      $mpdf->WriteHTML($html);
      $mpdf->Output(''.$nombreArchivo.'.pdf','D'); // opens in browser
    }
}