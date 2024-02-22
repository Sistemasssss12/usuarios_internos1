<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends Custom_Controller{

	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('id')){
      redirect('Login/index');
    }
		$this->load->library('usuario_sesion');
		$this->usuario_sesion->checkStatusBD();
	}

	function index(){
		if($this->session->userdata('logueado') && 
			$this->session->userdata('tipo') == 1 && 
			$this->session->userdata('idrol') == 1 || $this->session->userdata('idrol') == 2 || $this->session->userdata('idrol') == 6 || $this->session->userdata('idrol') == 9){
			$data['permisos'] = $this->usuario_model->getPermisos($this->session->userdata('id'));
			$data['submodulos'] = $this->rol_model->getMenu($this->session->userdata('idrol'));
			foreach($data['submodulos'] as $row) {
				$items[] = $row->id_submodulo;
			}
			$data['submenus'] = $items;
			$info['estados'] = $this->funciones_model->getEstados();
			$info['civiles'] = $this->funciones_model->getEstadosCiviles();
			$info['estudios'] = $this->funciones_model->getTiposEstudios();
			$info['proyectos'] = $this->candidato_model->getProyectosCliente(2);
      $info['tipos_docs'] = $this->funciones_model->getTiposDocumentos();
      $info['studies'] = $this->funciones_model->getTiposEstudios();
			$info['paises'] = $this->funciones_model->getPaises();
			$info['paquetes_antidoping'] = $this->funciones_model->getPaquetesAntidoping();
			$info['paises_estudio'] = $this->funciones_model->getPaisesEstudio();

			$vista['modals'] = $this->load->view('modals/mdl_cliente_hcl', $info, TRUE);
			$config = $this->funciones_model->getConfiguraciones();
			$data['version'] = $config->version_sistema;
      $data['cliente'] = $this->cat_cliente_model->getById($this->uri->segment(3));

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
			->view('analista/hcl_index', $vista)
			->view('adminpanel/footer');
		}
		else{
			redirect('Login/index');
		}
	}
	/*----------------------------------------*/
  /*  Consultas 
  /*----------------------------------------*/
		function getCandidatos(){
		  $id_cliente = $_GET['id_cliente'];
			$cand['recordsTotal'] = $this->cliente_hcl_model->getTotal($id_cliente);
			$cand['recordsFiltered'] = $this->cliente_hcl_model->getTotal($id_cliente);
			$cand['data'] = $this->cliente_hcl_model->getCandidatos($id_cliente);
			$this->output->set_output( json_encode( $cand ) );
		}
		function getCandidatoPanelCliente(){
		  $id_cliente = $this->session->userdata('idcliente');
			$cand['recordsTotal'] = $this->cliente_hcl_model->getTotalPanel($id_cliente);
			$cand['recordsFiltered'] = $this->cliente_hcl_model->getTotalPanel($id_cliente);
			$cand['data'] = $this->cliente_hcl_model->getCandidatosPanel($id_cliente);
			$this->output->set_output( json_encode( $cand ) );
		}
		function getVerificacionDocumentos(){
			$id_candidato = $_POST['id_candidato'];
			$salida = "";
			$data['docs'] = $this->candidato_model->checkVerificacionDocumentos($id_candidato);
			if($data['docs']){
				foreach($data['docs'] as $doc){
						$salida .= $doc->licencia.'@@';
						$salida .= $doc->licencia_institucion.'@@';
						$salida .= $doc->ine.'@@';
						$salida .= $doc->ine_ano.'@@';
						$salida .= $doc->ine_vertical.'@@';
						$salida .= $doc->ine_institucion.'@@';
						$salida .= $doc->penales.'@@';
						$salida .= $doc->penales_institucion.'@@';
						$salida .= $doc->domicilio.'@@';
						$salida .= $doc->fecha_domicilio.'@@';
						$salida .= $doc->militar.'@@';
						$salida .= $doc->militar_fecha.'@@';
						$salida .= $doc->pasaporte.'@@';
						$salida .= $doc->pasaporte_fecha.'@@';
						$salida .= $doc->forma_migratoria.'@@';
						$salida .= $doc->forma_migratoria_fecha.'@@';
            $salida .= $doc->imss.'@@';
						$salida .= $doc->imss_institucion.'@@';
						$salida .= $doc->comentarios.'@@';
						$salida .= $doc->motor_vehicle_records.'@@';
						$salida .= $doc->curp;
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
            if(!empty($ref->fecha_entrada_txt)){
              if(strtotime($ref->fecha_entrada_txt) !== false){
                $entrada_laboral = formatoFechaDescripcion($ref->fecha_entrada_txt);
              }
              else{
                $entrada_laboral = $ref->fecha_entrada_txt;
              }
            }
            else{
              $entrada_laboral = 'Not provided';
            }
            //Salida
            if(!empty($ref->fecha_salida_txt)){
              if(strtotime($ref->fecha_salida_txt) !== false){
                $salida_laboral = formatoFechaDescripcion($ref->fecha_salida_txt);
              }
              else{
                $salida_laboral = $ref->fecha_salida_txt;
              }
            }
            else{
              $salida_laboral = 'Not provided';
            }

            $salida .= $ref->empresa."@@";
            $salida .= $ref->direccion."@@";
            $salida .= $entrada_laboral."@@";
            $salida .= $salida_laboral."@@";
            $salida .= $ref->telefono."@@";
            $salida .= $ref->puesto1."@@";
            $salida .= $ref->puesto2."@@";
            $salida .= $ref->salario1."@@";
            $salida .= $ref->salario2."@@";
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
      $id_candidato = $this->input->post('id_candidato');
      $salida = "";
      $data['referencias'] = $this->candidato_model->getVerificacionReferencias($id_candidato);
      if($data['referencias']){
          foreach($data['referencias'] as $ref){
            if(!empty($ref->fecha_entrada_txt)){
              if(strtotime($ref->fecha_entrada_txt) !== false){
                $entrada_verifica = formatoFechaDescripcion($ref->fecha_entrada_txt);
              }
              else{
                $entrada_verifica = $ref->fecha_entrada_txt;
              }
            }
            else{
              $entrada_verifica = 'Not provided';
            }
            //Salida
            if(!empty($ref->fecha_salida_txt)){
              if(strtotime($ref->fecha_salida_txt) !== false){
                $salida_verifica = formatoFechaDescripcion($ref->fecha_salida_txt);
              }
              else{
                $salida_verifica = $ref->fecha_salida_txt;
              }
            }
            else{
              $salida_verifica = 'Not provided';
            }

              $salida .= $ref->empresa."@@";
              $salida .= $ref->direccion."@@";
              $salida .= $entrada_verifica."@@";
              $salida .= $salida_verifica."@@";
              $salida .= $ref->telefono."@@";
              $salida .= $ref->puesto1."@@";
              $salida .= $ref->puesto2."@@";
              $salida .= $ref->salario1."@@";
              $salida .= $ref->salario2."@@";
              $salida .= $ref->jefe_nombre."@@";
              $salida .= $ref->jefe_correo."@@";
              $salida .= $ref->jefe_puesto."@@";
              $salida .= $ref->causa_separacion."@@";
              $salida .= $ref->notas."@@";
              $salida .= $ref->responsabilidad."@@";
              $salida .= $ref->iniciativa."@@";
              $salida .= $ref->eficiencia."@@";
              $salida .= $ref->disciplina."@@";
              $salida .= $ref->puntualidad."@@";
              $salida .= $ref->limpieza."@@";
              $salida .= $ref->estabilidad."@@";
              $salida .= $ref->emocional."@@";
              $salida .= $ref->honestidad."@@";
              $salida .= $ref->rendimiento."@@";
              $salida .= $ref->actitud."@@";
              $salida .= $ref->recontratacion."@@";
              $salida .= $ref->motivo_recontratacion."@@";
              $salida .= $ref->id."@@";
              $salida .= $ref->numero_referencia."###";
          }
          
      }
      echo $salida;
    }
		function getHistorialDomicilios(){
			$id_candidato = $this->input->post('id_candidato');
			$data['doms'] = $this->candidato_model->getHistorialDomicilios($id_candidato);
			if($data['doms']){
				$salida = '';
				foreach($data['doms'] as $dom){
						$salida .= $dom->periodo.'@@';
						$salida .= $dom->causa.'@@';
						$salida .= $dom->calle.'@@';
						$salida .= $dom->exterior.'@@';
						$salida .= $dom->interior.'@@';
						$salida .= $dom->colonia.'@@';
						$salida .= $dom->id_estado.'@@';
						$salida .= $dom->id_municipio.'@@';
						$salida .= $dom->cp.'@@';
						$salida .= $dom->id.'@@';
						$salida .= $dom->domicilio_internacional.'@@';
						$salida .= $dom->pais.'###';
				}
				echo $salida;
			}
			else{
					$salida = 0;
			}
		}
		function getVerificacionHistorialDomicilios(){
			$id_candidato = $this->input->post('id_candidato');
			$salida = '';
			$verificacion = $this->candidato_model->getVerificacionHistorialDomicilios($id_candidato);
			if($verificacion != null){
					echo $salida = $verificacion->comentario;
			}
			else{
					echo $salida;
			}
		}
		function getReferenciasProfesionales(){
			$id_candidato = $this->input->post('id_candidato');
			$data['refs'] = $this->candidato_model->getReferenciasProfesionales($id_candidato);
			if($data['refs']){
				$salida = '';
				foreach($data['refs'] as $ref){
					$salida .= $ref->id.'@@';
					$salida .= $ref->nombre.'@@';
					$salida .= $ref->telefono.'@@';
					$salida .= $ref->tiempo_conocerlo.'@@';
					$salida .= $ref->donde_conocerlo.'@@';
					$salida .= $ref->puesto.'@@';
					$salida .= $ref->verificacion_tiempo.'@@';
					$salida .= $ref->verificacion_conocerlo.'@@';
					$salida .= $ref->verificacion_puesto.'@@';
					$salida .= $ref->cualidades.'@@';
					$salida .= $ref->desempeno.'@@';
					$salida .= $ref->recomienda.'@@';
					$salida .= $ref->comentarios.'###';
				}
				echo $salida;
			}
			else{
				$salida = 0;
			}
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
					$salida .= $ref->comentario."###";
				}
				echo $salida;
      }
      else{
				echo $salida = 0;
      }
    }
		function checkCredito(){
			$id_candidato = $this->input->post('id_candidato');
			$salida = "";
			$data['historial'] = $this->candidato_model->checkCredito($id_candidato);
			if($data['historial']){
				foreach($data['historial'] as $h){
					$salida .= '<div class="col-md-3">
												<p class="text-center"><b>Del</b></p>
												<p class="text-center">'.$h->fecha_inicio.'</p>
												</div>
											<div class="col-md-3">
													<p class="text-center"><b>Al</b></p>
													<p class="text-center">'.$h->fecha_fin.'</p>
											</div>
											<div class="col-md-6">
													<label>Comentario</label>
													<p>'.$h->comentario.'</p>
											</div>';
				}
				echo $salida;
			}
			else{
				echo $salida = 0;
			}
		}
		function verProcesoCandidato(){
			$id_candidato = $this->input->post('id_candidato');
			$status_bgc = $this->input->post('status_bgc');
			$formulario = $this->input->post('formulario');
			$secciones = $this->candidato_model->getSeccionesCandidato($id_candidato);
			$salida = '';
			$estudios = '';
			$domicilios = '';
			$identidad = '';
			$empleo = '';
			$criminal = '';
			$profesionales = '';
			$credito = '';
			//Estudios
			if($secciones->lleva_estudios == 1){
				$check_estudios = $this->candidato_model->getVerificacionMayoresEstudios($id_candidato);
				$estudios = ($check_estudios != null)? "<tr><th>Education </th><th>Registered</th></tr>":"<tr><th>Education </th><th>In process</th></tr>";
				if($status_bgc > 0){
					$estudios = "<tr><th>Education </th><th>Completed</th></tr>";
				}
			}
			else{
				$estudios ="<tr><th>Education </th><th>N/A</th></tr>";
			}
			//Identidad/Verificacion de documentos
			if($secciones->lleva_identidad == 1){
				$data['check_identidad'] = $this->candidato_model->getVerificacionDocumentosCandidato($id_candidato);
				$identidad = ($data['check_identidad'])? "<tr><th>Identity </th><th>Registered</th></tr>":"<tr><th>Identity </th><th>In process</th></tr>";
				if($status_bgc > 0){
					$identidad = "<tr><th>Identity </th><th>Completed</th></tr>";
				}
			}
			else{
				$identidad = "<tr><th>Identity </th><th>N/A</th></tr>";
			}
			//Empleos
			if($secciones->lleva_empleos == 1){
				$data['check_empleo'] = $this->candidato_model->getVerificacionReferencias($id_candidato);
				$empleo = ($data['check_empleo'])? "<tr><th>Employment History </th><th>Registered</th></tr>":"<tr><th>Employment History </th><th>In process</th></tr>";
				if($status_bgc > 0){
					$empleo = "<tr><th>Employment History </th><th>Completed</th></tr>";
				}
			}
			else{
				$empleo = "<tr><th>Employment History </th><th>N/A</th></tr>";
			}
			//Globales
			if($secciones->id_seccion_global_search != null){
				$check_globales = $this->candidato_model->getGlobalSearches($id_candidato);
				$globales = ($check_globales != null)? "<tr><th>Global Database Searches </th><th>Completed</th></tr>":"<tr><th>Global Database Searches </th><th>In process</th></tr>";
				if($status_bgc > 0){
					$globales = "<tr><th>Global Database Searches </th><th>Completed</th></tr>";
				}
			}
			else{
				$globales = "<tr><th>Global Database Searches </th><th>N/A</th></tr>";
			}
			//Domicilios
			if($secciones->lleva_domicilios == 1){
				$check_domicilios = $this->candidato_model->getVerificacionHistorialDomicilios($id_candidato);
				$domicilios = ($check_domicilios != null)? "<tr><th>Address History </th><th>Registered</th></tr>":"<tr><th>Address History </th><th>In process</th></tr>";
				if($status_bgc > 0){
					$domicilios = "<tr><th>Address History </th><th>Completed</th></tr>";
				}
			}
			else{
				$domicilios = "<tr><th>Address History </th><th>N/A</th></tr>";
			}
			//Criminal
			if($secciones->lleva_criminal == 1){
				$check_criminal = $this->candidato_model->getStatusVerificacionPenal($id_candidato);
				if($status_bgc > 0){
					$criminal = "<tr><th>Criminal check </th><th>Completed</th></tr>";
				}
				else{
					$criminal = "<tr><th>Criminal check </th><th>In process</th></tr>";
				}
			}
			else{
				$criminal = "<tr><th>Criminal check </th><th>N/A</th></tr>";
			}
			//Referencias profesionales
			if($secciones->cantidad_ref_profesionales > 0){
				$data['check_ref_profesionales'] = $this->candidato_model->getReferenciasProfesionales($id_candidato);
				$profesionales = ($data['check_ref_profesionales'])? "<tr><th>Professional references  </th><th>Registered</th></tr>":"<tr><th>Professional references  </th><th>In process</th></tr>";
				if($status_bgc > 0){
					$profesionales = "<tr><th>Professional references </th><th>Completed</th></tr>";
				}
			}
			else{
				$profesionales = "<tr><th>Professional references </th><th>N/A</th></tr>";
			}
			//Credito
			if($secciones->lleva_credito == 1){
				$data['check_credito'] = $this->candidato_model->checkCredito($id_candidato);
				$credito = ($data['check_credito'])? "<tr><th>Credit History  </th><th>Registered</th></tr>":"<tr><th>Credit History  </th><th>In process</th></tr>";
				if($status_bgc > 0){
					$credito = "<tr><th>Credit History </th><th>Completed</th></tr>";
				}
			}
			else{
				$credito = "<tr><th>Credit History </th><th>N/A</th></tr>";
			}
			//Referencias personales
			if($secciones->cantidad_ref_personales > 0){
				$data['check_ref_personales'] = $this->candidato_model->getReferenciasPersonales($id_candidato);
				$personales = ($data['check_ref_personales'])? "<tr><th>Personal references  </th><th>Registered</th></tr>":"<tr><th>Personal references  </th><th>In process</th></tr>";
				if($status_bgc > 0){
					$personales = "<tr><th>Personal references </th><th>Completed</th></tr>";
				}
			}
			else{
				$personales = "<tr><th>Personal references </th><th>N/A</th></tr>";
			}
			//Resultados
			$salida .= '<table class="table table-striped">';
			$salida .= '<thead>';
			$salida .= '<tr>';
			$salida .= '<th scope="col">Description</th>';
			$salida .= '<th scope="col">Status</th>';
			$salida .= '</tr>';
			$salida .= '</thead>';
			$salida .= '<tbody>';
			$salida .= $estudios;
			$salida .= $identidad;
			$salida .= $empleo;
			$salida .= $profesionales;
			$salida .= $globales;
			$salida .= $domicilios;
			$salida .= $criminal;
			$salida .= $credito;
			$salida .= $personales;
			$salida .= '</tbody>';
			$salida .= '</table>';
			echo $salida;
		}
		function getDocumentosRequeridos(){
			$id_candidato = $_POST['id_candidato'];
			$data['docs'] = $this->candidato_model->getDocumentosRequeridos($id_candidato);
			echo json_encode($data['docs']);
		}
	/*----------------------------------------*/
  /*  Proceso 
  /*----------------------------------------*/
		function registrar(){
			$this->form_validation->set_rules('opcion', 'Choose an option', 'required');
			if ($this->form_validation->run() == FALSE) {
				$msj = array(
					'codigo' => 0,
					'msg' => validation_errors()
				);
			}
			else{
				if($this->input->post('opcion') == 1){
					$this->form_validation->set_rules('nombre', 'Name', 'required|trim');
					$this->form_validation->set_rules('paterno', 'First lastname', 'required|trim');
					$this->form_validation->set_rules('materno', 'Second lastname', 'trim');
					$this->form_validation->set_rules('correo', 'Email', 'required|trim|valid_email');
					$this->form_validation->set_rules('celular', 'Cellphone number', 'required|trim|max_length[16]');
					$this->form_validation->set_rules('region', 'Location', 'required');
					$this->form_validation->set_rules('pais', 'Country', 'required|trim');
					$this->form_validation->set_rules('proyecto', 'Project name', 'required|trim');
					$this->form_validation->set_rules('examen', 'Examen antidoping', 'required');
					$this->form_validation->set_rules('medico', 'Examen Médico', 'required');

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
							'subproyecto' => $proyecto.' '.$pais,
							'pais' => $pais
						);
						$id_candidato = $this->candidato_model->registrarRetornaCandidato($data);

						$pruebas = array(
							'creacion' => $date,
							'edicion' => $date,
							$tipo_usuario => $id_usuario,
							'id_candidato' => $id_candidato,
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
						//* Envio de notificacion
						if($usuario == 2 || $usuario == 3){
							$cliente = $this->cat_cliente_model->getById($id_cliente);
							$aplicaDoping = ''; $aplicaMedico = '';
							$usuariosAnalistas = $this->usuario_model->get_usuarios_by_rol([1,2,6,8,9]);
							$aplicaDoping = $examen != 0 ? 'para examen de drogas' : '';
							if($medico != 0){
								if($examen != 0){
									$aplicaMedico =' y examen medico';
								}
								else{
									$aplicaMedico =' para examen medico';
								}
							}
							foreach($usuariosAnalistas as $row){
								$usuariosObjetivo[] = $row->id;
							}
							$titulo = 'Registro de examenes para candidato';
							$mensaje = 'El cliente '.$cliente->nombre.' ha registrado al candidato: '.$nombre.' '.$paterno.' '.$materno.' '.$aplicaDoping.$aplicaMedico;
							$this->registrar_notificacion($usuariosObjetivo, $titulo, $mensaje);
						}
					}
				}
				else{
					if($this->input->post('previo') != 0){
						$this->form_validation->set_rules('nombre', 'Name', 'required|trim');
						$this->form_validation->set_rules('paterno', 'First lastname', 'required|trim');
						$this->form_validation->set_rules('materno', 'Second lastname', 'trim');
						$this->form_validation->set_rules('correo', 'Email', 'required|trim|valid_email');
						$this->form_validation->set_rules('celular', 'Cellphone number', 'required|trim|max_length[16]');
						$this->form_validation->set_rules('previo', 'Previous projects', 'required');
						$this->form_validation->set_rules('pais_previo', 'Country for previous project', 'required');
						$this->form_validation->set_rules('examen', 'Examen antidoping', 'required');
						$this->form_validation->set_rules('medico', 'Examen Médico', 'required');
	
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
							$cel = $this->input->post('celular');
							$correo = strtolower($this->input->post('correo'));
							$examen = $this->input->post('examen');
							$medico = $this->input->post('medico');
							$existeCandidato = $this->cliente_hcl_model->repetidoCandidato($nombre, $paterno, $materno, $id_cliente);
							if($existeCandidato > 0){
								$msj = array(
									'codigo' => 2,
									'msg' => 'The candidate already exists'
								);
							}
							else{
								date_default_timezone_set('America/Mexico_City');
								$date = date('Y-m-d H:i:s');
								$usuario = $this->input->post('usuario');
                $privacidad_usuario = 0;
								switch ($usuario) {
									case 1:
										$tipo_usuario = "id_usuario";
										break;
									case 2:
										$tipo_usuario = "id_usuario_cliente";
                    $privacidad_usuario = $this->session->userdata('privacidad');
										break;
									case 3:
										$tipo_usuario = "id_usuario_subcliente";
										break;
								}
								$id_usuario = $this->session->userdata('id');
								//$last = $this->candidato_model->lastIdCandidato();
								//$last = ($last == null || $last == "")? 0 : $last;
								$id_proyecto_previo = $this->input->post('previo');
								$subproyecto_previo = ($this->input->post('pais_previo') == '')? 'México':$this->input->post('pais_previo');
								$pais_previo = ($this->input->post('pais_previo') == '')? 'México':$this->input->post('pais_previo');
								$migracion = $this->input->post('migracion');
								$seccion = $this->candidato_model->getProyectoPrevio($id_proyecto_previo);

								//Acceo a Candidatos
								if($seccion->lleva_identidad == 1 || $seccion->lleva_empleos == 1 || $seccion->lleva_estudios == 1 || $seccion->lleva_domicilios == 1 || $seccion->cantidad_ref_profesionales > 0 || $seccion->cantidad_ref_personales > 0){
									$base = 'k*jJlrsH:cY]O^Z^/J2)Pz{)qz:+yCa]^+V0S98Zf$sV[c@hKKG07Q{utg%OlODS';
									$aux = substr( md5(microtime()), 1, 8);
									$token = md5($aux.$base);
									if($this->input->post('pais_previo') == 'Mexico' || $this->input->post('pais_previo') == 'México' || empty( $this->input->post('pais_previo'))){
										$tipo_formulario = 3;
									}
									if(!empty($this->input->post('pais_previo')) && $this->input->post('pais_previo') != 'Mexico' && $this->input->post('pais_previo') != 'México'){
										$tipo_formulario = 4;
									}
								}
								if($seccion->lleva_identidad != 1 && $seccion->lleva_empleos != 1 && $seccion->lleva_estudios != 1 && $seccion->lleva_domicilios != 1 && $seccion->cantidad_ref_profesionales == 0 && $seccion->cantidad_ref_personales == 0){
									$token = "completo";
									$tipo_formulario = 0;
								}

								$data = array(
									'creacion' => $date,
									'edicion' => $date,
									$tipo_usuario => $id_usuario,
									'fecha_alta' => $date,
									'tipo_formulario' => $tipo_formulario,
									'nombre' => $nombre,
									'paterno' => $paterno,
									'materno' => $materno,
									'correo' => $correo,
									'token' => $token,
									'id_cliente' => $id_cliente,
									'id_subcliente' => 0,
									'celular' => $cel,
									'subproyecto' => $subproyecto_previo,
									'pais' => $pais_previo,
                  'privacidad' => $privacidad_usuario
								);
								$id_candidato = $this->candidato_model->registrarRetornaCandidato($data);

								//Verificar Documentacion Requerida
								$res = $this->candidato_model->getCandidatoProyectoPrevio($seccion->proyecto);
								//$d['info'] = $this->candidato_model->getDocumentosRequeridosCandidatoPrevio($res->id_candidato);
                //$tieneID = 0; $tienePasaporte = 0; $tieneSeguro = 0; $tieneFiscal = 0; $tieneCertificado = 0; $tieneAntecedentes = 0;
                //* Inicia el cambio
                $documentosSolicitados = [];
                if($seccion->lleva_empleos == 1){
                  array_push($documentosSolicitados, 9);
                } 
                if($seccion->lleva_estudios == 1){
                  array_push($documentosSolicitados, 7);
                } 
								if($seccion->lleva_criminal == 1){
									array_push($documentosSolicitados, 12);
								}
                if($seccion->lleva_domicilios == 1){
                  array_push($documentosSolicitados, 2);
                } 
                if($seccion->lleva_credito == 1){
                  array_push($documentosSolicitados, 28);
                } 
                if($seccion->lleva_prohibited_parties_list == 1){
                  array_push($documentosSolicitados, 30);
                } 
                if($seccion->lleva_motor_vehicle_records == 1){
                  array_push($documentosSolicitados, 44);
                } 
                if($this->input->post('migracion') == 1){
                  array_push($documentosSolicitados, 20);
                }
                if($this->input->post('curp') == 1){
                  array_push($documentosSolicitados, 5);
                }
                //* Obligados
                array_push($documentosSolicitados, 3);//ID
                //* Opcionales
                array_push($documentosSolicitados, 14);//Pasaporte
                if($pais_previo == 'México' || empty($pais_previo)){
                  array_push($documentosSolicitados, 45);//Constancia fiscal
                }
                //* Docs extras
                $cant_extras = $this->input->post('extras');
                if(!empty($cant_extras)){
                  for($i = 0; $i < count($cant_extras); $i++){
                    if(!in_array($cant_extras[$i], $documentosSolicitados))
                      array_push($documentosSolicitados, $cant_extras[$i]);
                  }
                }

                for($i = 0; $i < count($documentosSolicitados); $i++){
                  $row = $this->documentacion_model->getDocumentoRequerido($documentosSolicitados[$i]);
                  $solicitado = $row->solicitado;
                  if($documentosSolicitados[$i] == 12 && $seccion->lleva_criminal == 1 && $pais_previo != 'México' && $pais_previo != ''){
                    $solicitado = 1;
                  }
                  $docs_requeridos = array(
                    'id_candidato' => $id_candidato,
                    'id_tipo_documento' => $row->id_tipo_documento,
                    'nombre_espanol' => $row->nombre_espanol,
                    'nombre_ingles' => $row->nombre_ingles,
                    'label_ingles' => $row->label_ingles,
                    'div_id' => $row->div_id,
                    'input_id' => $row->input_id,
                    'multiple' => $row->multiple,
                    'width' => $row->width,
                    'height' => $row->height,
                    'obligatorio' => $row->obligatorio,
                    'solicitado' => $solicitado
                  );
                  $this->documentacion_model->addDocumentoRequerido($docs_requeridos);
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
									'lleva_credito' => $seccion->lleva_credito,
									'lleva_prohibited_parties_list' => $seccion->lleva_prohibited_parties_list,
									'lleva_motor_vehicle_records' => $seccion->lleva_motor_vehicle_records,
                  'lleva_curp' => $seccion->lleva_curp,
									'id_seccion_datos_generales' => $seccion->id_seccion_datos_generales,
									'id_estudios' => $seccion->id_estudios,
									'id_empleos' => $seccion->id_empleos,
									'id_seccion_historial_domicilios' => $seccion->id_seccion_historial_domicilios,
									//'id_seccion_verificacion_docs' => 0,
									'id_seccion_global_search' => $seccion->id_seccion_global_search,
									'id_ref_academica' => $seccion->id_ref_academica,
									'tiempo_empleos' => $seccion->tiempo_empleos,
									'tiempo_criminales' => $seccion->tiempo_criminales,
									'tiempo_domicilios' => $seccion->tiempo_domicilios,
									'tiempo_credito' => $seccion->tiempo_credito,
									'cantidad_ref_profesionales' => $seccion->cantidad_ref_profesionales,
									'cantidad_ref_personales' => $seccion->cantidad_ref_personales,
									'cantidad_ref_academicas' => $seccion->cantidad_ref_academicas
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
									'medico' => $medico
								);
								$this->candidato_model->crearPruebas($pruebas);
	
								//Envio de correo al candidato con sus credenciales
								if($seccion->lleva_identidad == 1 || $seccion->lleva_empleos == 1 || $seccion->lleva_estudios == 1 || $seccion->lleva_domicilios == 1 || $seccion->cantidad_ref_profesionales > 0 || $seccion->cantidad_ref_personales > 0){
									$info_cliente = $this->cliente_general_model->getDatosCliente($id_cliente);
	
									if($usuario == 1){
										$from = $this->config->item('smtp_user');
										$to = $correo;
										$subject = strtolower($info_cliente->nombre)." - credentials for register form";
										$datos['password'] = $aux;
										$datos['cliente'] = strtoupper($info_cliente->nombre);
										$datos['email'] = $correo;
										$message = $this->load->view('mails/mail_hcl',$datos,TRUE);
										$this->load->library('phpmailer_lib');
										$mail = $this->phpmailer_lib->load();
										$mail->isSMTP();
										$mail->Host     = 'mail.rodicontrol.com';
										$mail->SMTPAuth = true;
										$mail->Username = 'rodicontrol@rodicontrol.com';
										$mail->Password = 'r49o*&rUm%91';
										$mail->SMTPSecure = 'ssl';
										$mail->Port     = 465;
                    $mail->addCC('rodicontrol@rodicontrol.com');
										
										$mail->setFrom('rodicontrol@rodicontrol.com', 'Process of the candidate on RODI platform');
										$mail->addAddress($to);
										$mail->Subject = $subject;
										$mail->isHTML(true);
										$mailContent = $message;
										$mail->Body = $mailContent;
				
										if(!$mail->send()){
											$msj = array(
												'codigo' => 3,
												'msg' => $aux
											);
										}else{
											$msj = array(
												'codigo' => 4,
												'msg' => $aux
											);
										}
									}
									else{
										$from = $this->config->item('smtp_user');
										$to = $correo;
										$subject = strtolower($info_cliente->nombre)." - credentials for register form";
										$datos['password'] = $aux;
										$datos['cliente'] = strtoupper($info_cliente->nombre);
										$datos['email'] = $correo;
										$message = $this->load->view('mails/mail_hcl',$datos,TRUE);
										$this->load->library('phpmailer_lib');
										$mail = $this->phpmailer_lib->load();
										$mail->isSMTP();
										$mail->Host     = 'mail.rodicontrol.com';
										$mail->SMTPAuth = true;
										$mail->Username = 'rodicontrol@rodicontrol.com';
										$mail->Password = 'r49o*&rUm%91';
										$mail->SMTPSecure = 'ssl';
										$mail->Port     = 465;
										
										$mail->setFrom('rodicontrol@rodicontrol.com', 'Process of the candidate on RODI platform');
										$mail->addAddress($to);
										$mail->Subject = $subject;
										$mail->isHTML(true);
										$mailContent = $message;
										$mail->Body = $mailContent;
										$mail->send();
										$msj = array(
											'codigo' => 1,
											'msg' => 'Success'
										);
									}
								}
								else{
									$msj = array(
										'codigo' => 1,
										'msg' => 'Success'
									);
								}
								//* Envio de notificacion
								if($usuario == 2 || $usuario == 3){
									$cliente = $this->cat_cliente_model->getById($id_cliente);
									$aplicaDoping = ''; $aplicaMedico = ''; $rolDoping = 0;
									if($examen != 0){
										$aplicaDoping = ', para examen de drogas';
										$rolDoping = 8;
									}
									if($medico != 0){
										$rolDoping = 8;
										if($examen != 0){
											$aplicaMedico =' y examen medico';
										}
										else{
											$aplicaMedico =', para examen medico';
										}
									}
									$usuariosAnalistas = $this->usuario_model->get_usuarios_by_rol([1,2,6,$rolDoping,9]);
									foreach($usuariosAnalistas as $row){
										$usuariosObjetivo[] = $row->id;
									}
									$titulo = 'Registro de un nuevo candidato';
									$mensaje = 'El cliente '.$cliente->nombre.' ha registrado al candidato: '.$nombre.' '.$paterno.' '.$materno.' para ESE'.$aplicaDoping.$aplicaMedico;
									$this->registrar_notificacion($usuariosObjetivo, $titulo, $mensaje);
								}
							}
						} 
					}
					if($this->input->post('previo') == 0){
						$this->form_validation->set_rules('nombre', 'Name', 'required|trim');
						$this->form_validation->set_rules('paterno', 'First lastname', 'required|trim');
						$this->form_validation->set_rules('materno', 'Second lastname', 'trim');
						$this->form_validation->set_rules('correo', 'Email', 'required|trim|valid_email');
						$this->form_validation->set_rules('celular', 'Cellphone number', 'required|trim|max_length[16]');
						$this->form_validation->set_rules('region', 'Location', 'required');
						$this->form_validation->set_rules('pais', 'Country', 'required|trim');
						$this->form_validation->set_rules('proyecto', 'Project name', 'required|trim');
						$this->form_validation->set_rules('empleos', 'Employment history', 'required');
						$this->form_validation->set_rules('empleos_tiempo', 'Time required for employment history', 'required');
						$this->form_validation->set_rules('criminal', 'Criminal check', 'required');
						$this->form_validation->set_rules('criminal_tiempo', 'Time required for criminal check', 'required');
						$this->form_validation->set_rules('domicilios', 'Address history', 'required');
						$this->form_validation->set_rules('domicilios_tiempo', 'Time required for address history', 'required');
						$this->form_validation->set_rules('estudios', 'Education check', 'required');
						$this->form_validation->set_rules('identidad', 'Identity check', 'required');
						$this->form_validation->set_rules('global', 'Global data searches', 'required');
						$this->form_validation->set_rules('ref_profesionales', 'Professional References (quantity)', 'required|numeric|max_length[2]');
						$this->form_validation->set_rules('credito', 'Credit check', 'required');
						$this->form_validation->set_rules('credito_tiempo', 'Time required for credit check', 'required');
						$this->form_validation->set_rules('ref_personales', 'Personal References (quantity)', 'required|numeric|max_length[2]');
						$this->form_validation->set_rules('ref_academicas', 'Academic References (quantity)', 'required|numeric|max_length[2]');
						$this->form_validation->set_rules('migracion', 'Migratory form (FM, FM2 or FM3) check', 'required');
						$this->form_validation->set_rules('prohibited', 'Prohibited parties list check', 'required');
						$this->form_validation->set_rules('curp', 'CURP', 'required');
						$this->form_validation->set_rules('examen', 'Examen antidoping', 'required');
						$this->form_validation->set_rules('medico', 'Examen Médico', 'required');
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
							$cel = $this->input->post('celular');
							$correo = strtolower($this->input->post('correo'));
							$examen = $this->input->post('examen');
							$medico = $this->input->post('medico');
							$existeCandidato = $this->cliente_hcl_model->repetidoCandidato($nombre, $paterno, $materno, $correo, $id_cliente);
							if($existeCandidato > 0){
								$msj = array(
									'codigo' => 2,
									'msg' => 'The candidate already exists'
								);
							}
							else{
								date_default_timezone_set('America/Mexico_City');
								$date = date('Y-m-d H:i:s');
								$usuario = $this->input->post('usuario');
                $privacidad_usuario = 0;
								switch ($usuario) {
									case 1:
										$tipo_usuario = "id_usuario";
										break;
									case 2:
										$tipo_usuario = "id_usuario_cliente";
                    $privacidad_usuario = $this->session->userdata('privacidad');
										break;
									case 3:
										$tipo_usuario = "id_usuario_subcliente";
										break;
								}
								$id_usuario = $this->session->userdata('id');
								//$last = $this->candidato_model->lastIdCandidato();
								//$last = ($last == null || $last == "")? 0 : $last;

								$etiquetas = '';
								$region = $this->input->post('region');
								$pais = $this->input->post('pais');
								$proyecto = $this->input->post('proyecto');
								$empleos = $this->input->post('empleos');
								$criminal = $this->input->post('criminal');
								$domicilios = $this->input->post('domicilios');
								$estudios = $this->input->post('estudios');
								$identidad = $this->input->post('identidad');
								$global = $this->input->post('global');
								$ref_profesionales = $this->input->post('ref_profesionales');
								$credito = $this->input->post('credito');
								$ref_personales = $this->input->post('ref_personales');
								$ref_academicas = $this->input->post('ref_academicas');
      					$migracion = $this->input->post('migracion');
      					$prohibited = $this->input->post('prohibited');
      					$mvr = $this->input->post('mvr');
      					$curp = $this->input->post('curp');
								$conjunto_docs = array();

								//Secciones
								//Datos Generales
								if($identidad == 1){
									if($region == 'Mexico'){
										$id_seccion_datos_generales = 1;
										$salida = $this->candidato_model->getEtiquetaSeccion($id_seccion_datos_generales);
										$etiquetas .= $salida->etiqueta;
									}
									if($region == 'International'){
										$id_seccion_datos_generales = 2;
										$salida = $this->candidato_model->getEtiquetaSeccion($id_seccion_datos_generales);
										$etiquetas .= $salida->etiqueta;
									}
									// array_push($conjunto_docs, 3);
									// array_push($conjunto_docs, 14);
								}
								else{
									$id_seccion_datos_generales = null;
								}
                //* Asignacion de documentos ID
                array_push($conjunto_docs, 3);
                array_push($conjunto_docs, 14);
                //* Asignacion de Semanas cotizadas y constancia fiscal si son Mexicanos
                if($region == 'Mexico'){
                  array_push($conjunto_docs, 45);
                }
								if($region == 'International'){
                  array_push($conjunto_docs, 12);
                }
								//Estudios
								if($estudios != 0){
									$lleva_estudios = 1; 
									$salida = $this->candidato_model->getEtiquetaSeccion(3);
									$etiquetas .= $salida->etiqueta;
									array_push($conjunto_docs, 7);
								}
								else{
									$lleva_estudios = 0;
								}
								//Global searches
								if($global != 0){
									$id_seccion_global_search = $global;
									$salida = $this->candidato_model->getEtiquetaSeccion($id_seccion_global_search);
									$etiquetas .= $salida->etiqueta;
								}
								else{
									$id_seccion_global_search = NULL;
								}
								//Domicilio
								if($domicilios != 0){
									//Candidato seccion
									$lleva_domicilios = 1;
									$tiempo_domicilios = $this->input->post('domicilios_tiempo');
									$id_seccion_historial_domicilios = $domicilios;
									$salida = $this->candidato_model->getEtiquetaSeccion($id_seccion_historial_domicilios);
									$etiquetas .= $salida->etiqueta;
									array_push($conjunto_docs, 2);
								}
								else{
									//Candidato seccion
									$lleva_domicilios = 0;
									$tiempo_domicilios = NULL;
									$id_seccion_historial_domicilios = NULL;
								}								
								//Empleos
								if($empleos != 0){
									//Candidato seccion
									$lleva_empleos = 1;
									$lleva_gaps = 1;
									$tiempo_empleos = $this->input->post('empleos_tiempo');
									$salida = $this->candidato_model->getEtiquetaSeccion(16);
									$etiquetas .= $salida->etiqueta;
                  array_push($conjunto_docs, 9);
								}
								else{
									//Candidato seccion
									$lleva_empleos = 0;
									$lleva_gaps = 0;
									$tiempo_empleos = NULL;
								}
								//Referencias Profesionales
								if($ref_profesionales > 0){
									$salida = $this->candidato_model->getEtiquetaSeccion(22);
									$etiquetas .= $salida->etiqueta;
								}
								//Credito
								if($credito != 0){
									//Candidato seccion
									$lleva_credito = 1;
									$tiempo_credito = $this->input->post('credito_tiempo');
									$salida = $this->candidato_model->getEtiquetaSeccion(23);
									$etiquetas .= $salida->etiqueta;
									array_push($conjunto_docs, 28);
								}
								else{
									//Candidato seccion
									$lleva_credito = 0;
									$tiempo_credito = NULL;
								}
								//Criminal
								if($criminal == 1){
									//Candidato seccion
									$lleva_criminal = 1;
									$tiempo_criminales = $this->input->post('criminal_tiempo');
								}
								else{
									//Candidato seccion
									$lleva_criminal = 0;
									$tiempo_criminales = NULL;
								}
								//Referencias Personales
								if($ref_personales > 0){
									$salida = $this->candidato_model->getEtiquetaSeccion(26);
									$etiquetas .= $salida->etiqueta;
								}
								//Migratorio
								if($migracion == 1){
									array_push($conjunto_docs, 20);
								}
                //Referencias Academicas
								if($ref_academicas > 0){
									$salida = $this->candidato_model->getEtiquetaSeccion(84);
									$etiquetas .= $salida->etiqueta;
									$id_ref_academica = 84;
								}
                else{
                  $id_ref_academica = NULL;
                }
                //Motor Vehicle Records
								if($mvr == 1){
									array_push($conjunto_docs, 44);
								}
                //Prohibited Parties List
								if($prohibited == 1){
									array_push($conjunto_docs, 30);
								}

                //CURP
								if($curp == 1){
									array_push($conjunto_docs, 5);
								}

								//Acceso a Candidatos
								if($identidad == 1 || $lleva_empleos == 1 || $lleva_estudios == 1 || $lleva_domicilios == 1 || $ref_profesionales > 0 || $ref_personales > 0){
									$base = 'k*jJlrsH:cY]O^Z^/J2)Pz{)qz:+yCa]^+V0S98Zf$sV[c@hKKG07Q{utg%OlODS';
									$aux = substr( md5(microtime()), 1, 8);
									$token = md5($aux.$base);
									if($region == 'Mexico'){
										$tipo_formulario = 3;
									}
									if($region == 'International'){
										$tipo_formulario = 4;
									}
								}
								else{
									$token = "completo";
									$tipo_formulario = 0;
								}

								$data = array(
									'creacion' => $date,
									'edicion' => $date,
									$tipo_usuario => $id_usuario,
									'fecha_alta' => $date,
									'tipo_formulario' => $tipo_formulario,
									'nombre' => $nombre,
									'paterno' => $paterno,
									'materno' => $materno,
									'correo' => $correo,
									'token' => $token,
									'id_cliente' => $id_cliente,
									'id_subcliente' => 0,
									'celular' => $cel,
									'subproyecto' => '',
									'pais' => $pais,
                  'privacidad' => $privacidad_usuario
								);
								$id_candidato = $this->candidato_model->registrarRetornaCandidato($data);


								//Verificacion Documentos
								$cant_extras = $this->input->post('extras');
								if(!empty($cant_extras)){
									for($i = 0; $i < count($cant_extras); $i++){
                    if(!in_array($cant_extras[$i], $conjunto_docs))
                      array_push($conjunto_docs, $cant_extras[$i]);
									}
								}

                for($i = 0; $i < count($conjunto_docs); $i++){
                  $row = $this->documentacion_model->getDocumentoRequerido($conjunto_docs[$i]);
                  //var_dump($conjunto_docs[$i]);
                  $solicitado = $row->solicitado;
                  if($conjunto_docs[$i] == 12 && $lleva_criminal == 1 && $region != 'México' && $region != ''){
                    $solicitado = 1;
                  }
                  $docs_requeridos = array(
                    'id_candidato' => $id_candidato,
                    'id_tipo_documento' => $row->id_tipo_documento,
                    'nombre_espanol' => $row->nombre_espanol,
                    'nombre_ingles' => $row->nombre_ingles,
                    'label_ingles' => $row->label_ingles,
                    'div_id' => $row->div_id,
                    'input_id' => $row->input_id,
                    'multiple' => $row->multiple,
                    'width' => $row->width,
                    'height' => $row->height,
                    'obligatorio' => $row->obligatorio,
                    'solicitado' => $solicitado
                  );
                  $this->documentacion_model->addDocumentoRequerido($docs_requeridos);
                }
								$etiquetas .= '<a class="dropdown-item" href="javascript:void(0)" id="datos_verificacion_docs">Verificación de documentos</a>';
								//Se guarda el registro para la tabla candidato_seccion
								$candidato_secciones = array(
									'creacion' => $date,
									$tipo_usuario => $id_usuario,
									'id_candidato' => $id_candidato,
									'proyecto' => $proyecto,
									'secciones' => $etiquetas,
									'lleva_identidad' => $identidad,
									'lleva_empleos' => $lleva_empleos,
									'lleva_criminal' => $lleva_criminal,
									'lleva_estudios' => $lleva_estudios,
									'lleva_domicilios' => $lleva_domicilios,
									'lleva_gaps' => $lleva_gaps,
									'lleva_credito' => $lleva_credito,
									'lleva_prohibited_parties_list' => $prohibited,
									'lleva_curp' => $curp,
									'lleva_motor_vehicle_records' => $mvr,
									'id_seccion_datos_generales' => $id_seccion_datos_generales,
									'id_seccion_historial_domicilios' => $id_seccion_historial_domicilios,
									'id_seccion_verificacion_docs' => 0,
									'id_seccion_global_search' => $id_seccion_global_search,
									'id_ref_academica' => $id_ref_academica,
									'tiempo_empleos' => $tiempo_empleos,
									'tiempo_criminales' => $tiempo_criminales,
									'tiempo_domicilios' => $tiempo_domicilios,
									'tiempo_credito' => $tiempo_credito,
									'cantidad_ref_profesionales' => $ref_profesionales,
									'cantidad_ref_personales' => $ref_personales,
									'cantidad_ref_academicas' => $ref_academicas
								);
								$this->candidato_model->guardarSeccionCandidato($candidato_secciones);

								//Se checa si no existe un proyecto previo con el mismo nombre; si no existe se agrega al historial
								//$existe_proyecto = $this->candidato_model->checkHistorialProyectos($proyecto);
								//Se eliminan los proyectos previos con el mismo nombre de proyecto
								$this->candidato_seccion_model->deleteProyectosPrevios($proyecto, $id_cliente);
								//if($hayId == 0){
									$proyecto_historial = array(
										'creacion' => $date,
										$tipo_usuario => $id_usuario,
										'id_cliente' => $id_cliente,
										'proyecto' => $proyecto,
										'secciones' => $etiquetas,
										'lleva_identidad' => $identidad,
										'lleva_empleos' => $lleva_empleos,
										'lleva_criminal' => $lleva_criminal,
										'lleva_estudios' => $lleva_estudios,
										'lleva_domicilios' => $lleva_domicilios,
										'lleva_gaps' => $lleva_gaps,
										'lleva_credito' => $lleva_credito,
										'lleva_prohibited_parties_list' => $prohibited,
                    'lleva_curp' => $curp,
                    'lleva_motor_vehicle_records' => $mvr,
                    'id_seccion_datos_generales' => $id_seccion_datos_generales,
										'id_seccion_historial_domicilios' => $id_seccion_historial_domicilios,
										'id_seccion_verificacion_docs' => 0,
										'id_seccion_global_search' => $id_seccion_global_search,
                    'id_ref_academica' => $id_ref_academica,
                    'tiempo_empleos' => $tiempo_empleos,
										'tiempo_criminales' => $tiempo_criminales,
										'tiempo_domicilios' => $tiempo_domicilios,
										'tiempo_credito' => $tiempo_credito,
										'cantidad_ref_profesionales' => $ref_profesionales,
										'cantidad_ref_personales' => $ref_personales,
                    'cantidad_ref_academicas' => $ref_academicas
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
									'medico' => $medico
								);
								$this->candidato_model->crearPruebas($pruebas);

								//Envio de correo al candidato con sus credenciales
								if($identidad == 1 || $lleva_empleos == 1 || $lleva_estudios == 1 || $lleva_domicilios == 1 || $ref_profesionales > 0 || $ref_personales > 0){
									$info_cliente = $this->cliente_general_model->getDatosCliente($id_cliente);

									if($usuario == 1){
										$from = $this->config->item('smtp_user');
										$to = $correo;
										$subject = strtolower($info_cliente->nombre)." - credentials for register form";
										$datos['password'] = $aux;
										$datos['cliente'] = strtoupper($info_cliente->nombre);
										$datos['email'] = $correo;
										$message = $this->load->view('mails/mail_hcl',$datos,TRUE);
										$this->load->library('phpmailer_lib');
										$mail = $this->phpmailer_lib->load();
										$mail->isSMTP();
										$mail->Host     = 'mail.rodicontrol.com';
										$mail->SMTPAuth = true;
										$mail->Username = 'rodicontrol@rodicontrol.com';
										$mail->Password = 'r49o*&rUm%91';
										$mail->SMTPSecure = 'ssl';
										$mail->Port     = 465;
                    $mail->addCC('rodicontrol@rodicontrol.com');
										
										$mail->setFrom('rodicontrol@rodicontrol.com', 'Process of the candidate on RODI platform');
										$mail->addAddress($to);
										$mail->Subject = $subject;
										$mail->isHTML(true);
										$mailContent = $message;
										$mail->Body = $mailContent;
				
										if(!$mail->send()){
											$msj = array(
												'codigo' => 3,
												'msg' => $aux
											);
										}else{
											$msj = array(
												'codigo' => 4,
												'msg' => $aux
											);
										}
									}
									else{
										$from = $this->config->item('smtp_user');
										$to = $correo;
										$subject = strtolower($info_cliente->nombre)." - credentials for register form";
										$datos['password'] = $aux;
										$datos['cliente'] = strtoupper($info_cliente->nombre);
										$datos['email'] = $correo;
										$message = $this->load->view('mails/mail_hcl',$datos,TRUE);
										$this->load->library('phpmailer_lib');
										$mail = $this->phpmailer_lib->load();
										$mail->isSMTP();
										$mail->Host     = 'mail.rodicontrol.com';
										$mail->SMTPAuth = true;
										$mail->Username = 'rodicontrol@rodicontrol.com';
										$mail->Password = 'r49o*&rUm%91';
										$mail->SMTPSecure = 'ssl';
										$mail->Port     = 465;
                    $mail->addCC('rodicontrol@rodicontrol.com');
										
										$mail->setFrom('rodicontrol@rodicontrol.com', 'Process of the candidate on RODI platform');
										$mail->addAddress($to);
										$mail->Subject = $subject;
										$mail->isHTML(true);
										$mailContent = $message;
										$mail->Body = $mailContent;
										$mail->send();
										$msj = array(
											'codigo' => 1,
											'msg' => 'Success'
										);
									}
								}
								else{
									$msj = array(
										'codigo' => 1,
										'msg' => 'Success'
									);
								}
								//* Envio de notificacion
								if($usuario == 2 || $usuario == 3){
									$cliente = $this->cat_cliente_model->getById($id_cliente);
									$aplicaDoping = ''; $aplicaMedico = ''; $rolDoping = 0;
									if($examen != 0){
										$aplicaDoping = ', para examen de drogas';
										$rolDoping = 8;
									}
									if($medico != 0){
										$rolDoping = 8;
										if($examen != 0){
											$aplicaMedico =' y examen medico';
										}
										else{
											$aplicaMedico =', para examen medico';
										}
									}
									$usuariosAnalistas = $this->usuario_model->get_usuarios_by_rol([1,2,6,$rolDoping,9]);
									foreach($usuariosAnalistas as $row){
										$usuariosObjetivo[] = $row->id;
									}
									$titulo = 'Registro de un nuevo candidato';
									$mensaje = 'El cliente '.$cliente->nombre.' ha registrado al candidato: '.$nombre.' '.$paterno.' '.$materno.' para ESE'.$aplicaDoping.$aplicaMedico;
									$this->registrar_notificacion($usuariosObjetivo, $titulo, $mensaje);
								}
							}
						} 
					}
				}
			}
			echo json_encode($msj);
		}
		function regenerarPassword(){
      $this->load->config('email');
      date_default_timezone_set('America/Mexico_City');
      $date = date('Y-m-d H:i:s');
      $id_candidato = $_POST['id_candidato'];
      $correo = $_POST['correo'];
      $base = 'k*jJlrsH:cY]O^Z^/J2)Pz{)qz:+yCa]^+V0S98Zf$sV[c@hKKG07Q{utg%OlODS';
      $aux = substr( md5(microtime()), 1, 8);
      $token = md5($aux.$base);
      $this->candidato_model->regenerarPassword($id_candidato, $date, $token);
      $from = $this->config->item('smtp_user');
      $to = $correo;
      $data_candidato = $this->candidato_model->getInfoCandidatoEspecifico($id_candidato);
      $subcliente = ($data_candidato->subcliente != null)? $data_candidato->subcliente : '';
      $subject = "Credentials to access to register form";
      $datos['password'] = $aux;
      $datos['cliente'] = strtoupper($data_candidato->cliente.' '.$subcliente);
      $datos['email'] = $correo;
      $message = $this->load->view('mails/mail_hcl',$datos,TRUE);

      $this->load->library('phpmailer_lib');
      $mail = $this->phpmailer_lib->load();
      $mail->isSMTP();
      $mail->Host     = 'mail.rodicontrol.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'rodicontrol@rodicontrol.com';
      $mail->Password = 'r49o*&rUm%91';
      $mail->SMTPSecure = 'ssl';
      $mail->Port     = 465;
      $mail->addCC('rodicontrol@rodicontrol.com');
      
      $mail->setFrom('rodicontrol@rodicontrol.com', 'Register form on RODICONTROL platform');
      $mail->addAddress($to);
      $mail->Subject = $subject;
      $mail->isHTML(true);
      $mailContent = $message;
      $mail->Body = $mailContent;
      if(!$mail->send()){
          $msj = array(
              'codigo' => 3,
              'msg' => $aux
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
      $this->form_validation->set_rules('calle', 'Calle', 'required|trim');
      $this->form_validation->set_rules('exterior', 'No. Exterior', 'required|trim|max_length[8]');
      $this->form_validation->set_rules('interior', 'No. Interior', 'trim|max_length[8]');
      $this->form_validation->set_rules('colonia', 'Colonia', 'required|trim');
      $this->form_validation->set_rules('estado', 'Estado', 'required|trim|numeric');
      $this->form_validation->set_rules('municipio', 'Municipio', 'required|trim|numeric');
      $this->form_validation->set_rules('cp', 'Código postal', 'required|trim|numeric|max_length[5]');
      $this->form_validation->set_rules('civil', 'Estado civil', 'required|trim');
      $this->form_validation->set_rules('celular', 'Tel. Celular', 'required|trim|max_length[16]');
      $this->form_validation->set_rules('tel_casa', 'Tel. Casa', 'trim|max_length[16]');
      $this->form_validation->set_rules('correo', 'Correo', 'required|trim|valid_email');

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
              'correo' => $this->input->post('correo')
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
		
		function guardarTrabajoGobierno(){
      if($this->input->post('caso') == 2){//Para HCL
				$this->form_validation->set_rules('inactivo', 'Break(s) in Employment', 'required|trim');
				
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
					$candidato = array(
						'edicion' => $date,
						'id_usuario' => $id_usuario,
						'trabajo_gobierno' => $trabajo,
						'trabajo_enterado' => $enterado,
						'trabajo_inactivo' => $inactivo
					);
					$this->candidato_model->editarCandidato($candidato, $id_candidato);
					$msj = array(
						'codigo' => 1,
						'msg' => 'success'
					);
				}
				echo json_encode($msj);
      }
    }
		function guardarReferenciaLaboral(){
      $this->form_validation->set_rules('empresa', 'Empresa', 'required|trim');
      $this->form_validation->set_rules('direccion', 'Direccion', 'required|trim');
      $this->form_validation->set_rules('entrada', 'Fecha de entrada', 'required|trim');
      $this->form_validation->set_rules('salida', 'Fecha de salida', 'required|trim');
      $this->form_validation->set_rules('telefono', 'Teléfono', 'required|trim|max_length[16]');
      $this->form_validation->set_rules('puesto1', 'Puesto inicial', 'required|trim');
      $this->form_validation->set_rules('puesto2', 'Puesto final', 'required|trim');
      $this->form_validation->set_rules('salario1', 'Salario inicial', 'required|trim|numeric');
      $this->form_validation->set_rules('salario2', 'Salario final', 'required|trim|numeric');
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
              //$entrada = fecha_ingles_bd($this->input->post('entrada'));
              //$salida = fecha_ingles_bd($this->input->post('salida'));
              $datos = array(
								'edicion' => $date,
								'empresa' => ucwords(mb_strtolower( $this->input->post('empresa'))),
								'direccion' => $this->input->post('direccion'),
								'fecha_entrada_txt' => $this->input->post('entrada'),
								'fecha_salida_txt' => $this->input->post('salida'),
								'telefono' => $this->input->post('telefono'),
								'puesto1' => $this->input->post('puesto1'),
								'puesto2' => $this->input->post('puesto2'),
								'salario1' => $this->input->post('salario1'),
								'salario2' => $this->input->post('salario2'),
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
              // $entrada = fecha_ingles_bd($this->input->post('entrada'));
              // $salida = fecha_ingles_bd($this->input->post('salida'));
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
                  'salario1' => $this->input->post('salario1'),
                  'salario2' => $this->input->post('salario2'),
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
		function guardarVerificacionLaboral(){
      $this->form_validation->set_rules('empresa', 'Empresa', 'required|trim');
      $this->form_validation->set_rules('direccion', 'Direccion', 'required|trim');
      $this->form_validation->set_rules('entrada', 'Fecha de entrada', 'required|trim');
      $this->form_validation->set_rules('salida', 'Fecha de salida', 'required|trim');
      $this->form_validation->set_rules('telefono', 'Teléfono', 'required|trim|max_length[16]');
      $this->form_validation->set_rules('puesto1', 'Puesto inicial', 'required|trim');
      $this->form_validation->set_rules('puesto2', 'Puesto final', 'required|trim');
      $this->form_validation->set_rules('salario1', 'Salario inicial', 'required|trim|numeric');
      $this->form_validation->set_rules('salario2', 'Salario final', 'required|trim|numeric');
      $this->form_validation->set_rules('jefenombre', 'Jefe inmediato', 'required|trim');
      $this->form_validation->set_rules('jefecorreo', 'Correo del jefe inmediato', 'required|trim');
      $this->form_validation->set_rules('jefepuesto', 'Puesto del jefe inmediato', 'required|trim');
      $this->form_validation->set_rules('separacion', 'Causa de separación', 'required|trim');
      $this->form_validation->set_rules('notas', 'Notas', 'required|trim');
      $this->form_validation->set_rules('responsabilidad', 'Responsabilidad', 'required|trim');
      $this->form_validation->set_rules('iniciativa', 'Iniciativa', 'required|trim');
      $this->form_validation->set_rules('eficiencia', 'Eficiencia', 'required|trim');
      $this->form_validation->set_rules('disciplina', 'Disciplina', 'required|trim');
      $this->form_validation->set_rules('puntualidad', 'Puntualidad y asistencia', 'required|trim');
      $this->form_validation->set_rules('limpieza', 'Limpieza y orden', 'required|trim');
      $this->form_validation->set_rules('estabilidad', 'Estabilidad laboral', 'required|trim');
      $this->form_validation->set_rules('emocional', 'Estabilidad emocional', 'required|trim');
      $this->form_validation->set_rules('honesto', 'Honesto', 'required|trim');
      $this->form_validation->set_rules('rendimiento', 'Rendimiento', 'required|trim');
      $this->form_validation->set_rules('actitud', 'Actitud', 'required|trim');
      $this->form_validation->set_rules('recontratacion', '¿Lo(a) contrataría de nuevo?', 'required|trim');
      $this->form_validation->set_rules('motivo', '¿Por qué?', 'required|trim');
      
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
          //$fentrada = fecha_ingles_bd($this->input->post('entrada'));
          //$fsalida = fecha_ingles_bd($this->input->post('salida'));
          $verificacion_reflab = array(
              'creacion' => $date,
              'edicion' => $date,
              'id_usuario' => $id_usuario,
              'id_candidato' => $id_candidato,
              'numero_referencia' => $num,
              'empresa' => $this->input->post('empresa'), 
              'direccion' => $this->input->post('direccion'),
              'fecha_entrada_txt' => $this->input->post('entrada'), 
              'fecha_salida_txt' => $this->input->post('salida'),
              'telefono' => $this->input->post('telefono'),
              'puesto1' => $this->input->post('puesto1'), 
              'puesto2' => $this->input->post('puesto2'),
              'salario1' => $this->input->post('salario1'), 
              'salario2' => $this->input->post('salario2'), 
              'jefe_nombre' => $this->input->post('jefenombre'), 
              'jefe_correo' => $this->input->post('jefecorreo'),
              'jefe_puesto' => $this->input->post('jefepuesto'), 
              'causa_separacion' => $this->input->post('separacion'), 
              'notas' => $this->input->post('notas'), 
              'responsabilidad' => $this->input->post('responsabilidad'),
              'iniciativa' => $this->input->post('iniciativa'), 
              'eficiencia' => $this->input->post('eficiencia'), 
              'disciplina' => $this->input->post('disciplina'), 
              'puntualidad' => $this->input->post('puntualidad'),
              'limpieza' => $this->input->post('limpieza'), 
              'estabilidad' => $this->input->post('estabilidad'),
              'emocional' => $this->input->post('emocional'),
              'honestidad' => $this->input->post('honesto'),
              'rendimiento' => $this->input->post('rendimiento'),
              'actitud' => $this->input->post('actitud'),
              'recontratacion' => $this->input->post('recontratacion'),
              'motivo_recontratacion' => $this->input->post('motivo')
          );
          $this->candidato_model->guardarVerificacionLaboral($verificacion_reflab);
          //$this->generarAvancesUST($id_candidato);
          $msj = array(
              'codigo' => 1,
              'msg' => 'success'
          );
      }
      echo json_encode($msj);
    }
		function guardarDocumentacion(){
			$id_candidato = $this->input->post('id_candidato');
			$data['docs'] = $this->candidato_model->getDocumentosRequeridos($id_candidato);
			$info = $this->candidato_model->getInfoCandidatoEspecifico($id_candidato);
			if($data['docs']){
				foreach($data['docs'] as $d){
					if($d->id_tipo_documento == 2){
						$this->form_validation->set_rules('domicilio_numero', 'Número de documento del Comprobante de domicilio', 'required|trim');
						$this->form_validation->set_rules('domicilio_fecha', 'Fecha / Institución del Comprobante de domicilio', 'required|trim');
					}
					if($d->id_tipo_documento == 3){
						if($info->pais == 'México' || $info->pais == 'Mexico' || $info->pais == null || $info->pais == ''){
							$this->form_validation->set_rules('ine_clave', 'ID o clave de la INE (ID)', 'required|trim');
							$this->form_validation->set_rules('ine_registro', 'Año de registro de la INE (ID)', 'required|trim|numeric|max_length[4]');
							$this->form_validation->set_rules('ine_vertical', 'Número vertical de la INE (ID)', 'required|trim|numeric|max_length[13]');
							$this->form_validation->set_rules('ine_institucion', 'Fecha / Institución de la INE (ID)', 'required|trim');
						}
						else{
							$this->form_validation->set_rules('ine_clave', 'ID o clave de la INE (ID)', 'required|trim');
							$this->form_validation->set_rules('ine_institucion', 'Fecha / Institución de la INE (ID)', 'required|trim');
						}
					}
          // if($d->id_tipo_documento == 5){
					// 	$this->form_validation->set_rules('curp', 'CURP', 'required|trim');
					// }
          if($d->id_tipo_documento == 6){
						$this->form_validation->set_rules('nss_numero', 'ID o Número del NSS', 'required|trim');
						$this->form_validation->set_rules('nss_fecha', 'Fecha de registro / Institución del NSS', 'required|trim');
					}
					if($d->id_tipo_documento == 7){
						$this->form_validation->set_rules('lic_profesional', 'Número de documento del Comprobante de Estudios', 'required|trim');
						$this->form_validation->set_rules('lic_institucion', 'Fecha / Institución del Comprobante de Estudios', 'required|trim');
					}
					if($d->id_tipo_documento == 12){
						$this->form_validation->set_rules('penales_numero', 'Número del documento de Antecedentes Penales', 'trim');
						$this->form_validation->set_rules('penales_institucion', 'Fecha / Institución Antecedentes Penales', 'trim');
					}
					if($d->id_tipo_documento == 14){
						$this->form_validation->set_rules('pasaporte_numero', 'Número de documento del Pasaporte', 'trim');
						$this->form_validation->set_rules('pasaporte_institucion', 'Fecha / Institución del Pasaporte', 'trim');
					}
					if($d->id_tipo_documento == 15){
						$this->form_validation->set_rules('militar_numero', 'Número de documento de la Carta o Cartilla Militar', 'required|trim');
						$this->form_validation->set_rules('militar_fecha', 'Fecha / Institución de la Carta o Cartilla Militar', 'required|trim');
					}
					if($d->id_tipo_documento == 20){
						$this->form_validation->set_rules('migratorio_numero', 'Número del documento de Forma Migratoria FM, FM2 o FM3', 'required|trim');
						$this->form_validation->set_rules('migratorio_fecha', 'Fecha / Institución Forma Migratoria FM, FM2 o FM3', 'required|trim');
					}
					if($d->id_tipo_documento == 37){
						$this->form_validation->set_rules('licencia_numero', 'Número del documento de Licencia de conducir', 'required|trim');
						$this->form_validation->set_rules('licencia_fecha', 'Fecha vencimiento / Institución Licencia de conducir', 'required|trim');
					}
          if($d->id_tipo_documento == 44){
						$this->form_validation->set_rules('mvr', 'Historial de registros del vehiculo (Motor Vehicle Records)', 'required|trim');
					}
				}
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
						'licencia' => $this->input->post('lic_profesional'),
						'licencia_institucion' => $this->input->post('lic_institucion'),
						'ine' => $this->input->post('ine_clave'),
						'ine_ano' => $this->input->post('ine_registro'),
						'ine_vertical' => $this->input->post('ine_vertical'),
						'ine_institucion' => $this->input->post('ine_institucion'),
						'penales' => $this->input->post('penales_numero'),
						'penales_institucion' => $this->input->post('penales_institucion'),
						'domicilio' => $this->input->post('domicilio_numero'),
						'fecha_domicilio' => $this->input->post('domicilio_fecha'),
						'militar' => $this->input->post('militar_numero'),
						'militar_fecha' => $this->input->post('militar_fecha'),
						'pasaporte' => $this->input->post('pasaporte_numero'),
						'pasaporte_fecha' => $this->input->post('pasaporte_institucion'),
						'licencia_manejo' => $this->input->post('licencia_numero'),
						'licencia_manejo_fecha' => $this->input->post('licencia_fecha'),
            'imss' => $this->input->post('nss_numero'),
						'imss_institucion' => $this->input->post('nss_fecha'),
						'motor_vehicle_records' => $this->input->post('mvr'),
            'forma_migratoria' => $this->input->post('migratorio_numero'),
						'forma_migratoria_fecha' => $this->input->post('migratorio_fecha'),
						//'curp' => $this->input->post('curp'),
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
		
    }
		function finalizarProceso(){
			$this->form_validation->set_rules('comentario_final', 'Declaración final', 'required|trim');
      $this->form_validation->set_rules('bgc_status', 'Estatus final del BGC', 'required|trim');
      
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
				$id_candidato = $_POST['id_candidato'];
				$id_usuario = $this->session->userdata('id');
				//Checks
				$check_identidad = ($this->input->post('check_identidad') != -1)? $this->input->post('check_identidad'):3;
				$check_estudios = ($this->input->post('check_estudios') != -1)? $this->input->post('check_estudios'):3;
				$check_global = ($this->input->post('check_global') != -1)? $this->input->post('check_global'):3;
				$check_laboral = ($this->input->post('check_laboral') != -1)? $this->input->post('check_laboral'):3;
				$check_visita = ($this->input->post('check_visita') != -1)? $this->input->post('check_visita'):3;
				$check_laboratorio = ($this->input->post('check_laboratorio') != -1)? $this->input->post('check_laboratorio'):3;
				$check_domicilio = ($this->input->post('check_domicilio') != -1)? $this->input->post('check_domicilio'):3;
				$check_penales = ($this->input->post('check_penales') != -1)? $this->input->post('check_penales'):3;
				$check_ofac = ($this->input->post('check_ofac') != -1)? $this->input->post('check_ofac'):3;
				$check_credito = ($this->input->post('check_credito') != -1)? $this->input->post('check_credito'):3;
				$check_medico = ($this->input->post('check_medico') != -1)? $this->input->post('check_medico'):3;
			
				$bgc = array(
					'creacion' => $date,
					'edicion' => $date,
					'id_usuario' => $id_usuario,
					'id_candidato' => $id_candidato,
					'identidad_check' => $check_identidad,
					'empleo_check' => $check_laboral,
					'estudios_check' => $check_estudios,
					'visita_check' => $check_visita,
					'penales_check' => $check_penales,
					'ofac_check' => $check_ofac,
					'medico_check' => $check_medico,
					'laboratorio_check' => $check_laboratorio,
					'medico_check' => $check_medico,
					'global_searches_check' => $check_global,
					'domicilios_check' => $check_domicilio,
					'credito_check' => $check_credito,
					'comentario_final' => $this->input->post('comentario_final')
				);
				$this->candidato_model->eliminarBGC($id_candidato);
				$this->candidato_model->guardarBGC($bgc, $id_candidato);
				$this->candidato_model->statusBGCCandidato($this->input->post('bgc_status'), $id_candidato);
				$msj = array(
					'codigo' => 1,
					'msg' => 'success'
				);
			}
      echo json_encode($msj);
		}
		function guardarHistorialDomicilios(){
			if($this->input->post('tipo') == 'general'){
				$this->form_validation->set_rules('address_periodo', 'Periodo', 'required|trim');
				$this->form_validation->set_rules('address_causa', 'Causa de salida', 'required|trim');
				$this->form_validation->set_rules('address_calle', 'Calle', 'required|trim');
				$this->form_validation->set_rules('address_exterior', 'No. Exterior', 'required|trim');
				$this->form_validation->set_rules('address_interior', 'No. Interior', 'trim');
				$this->form_validation->set_rules('address_colonia', 'Colonia', 'required|trim');
				$this->form_validation->set_rules('address_estado', 'Estado', 'required|trim');
				$this->form_validation->set_rules('address_municipio', 'Municipio', 'required|trim');
				$this->form_validation->set_rules('address_cp', 'CP', 'required|trim');

				$this->form_validation->set_message('required', 'El campo {field} es obligatorio');
				$this->form_validation->set_message('max_length', 'El campo {field} debe tener máximo {param} carácteres');

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
					$num =  $this->input->post('num');
					$id_candidato =  $this->input->post('id_candidato');
					$idDomicilio =  $this->input->post('id_domicilio');
					$id_usuario = $this->session->userdata('id');

					$candidato = array(
						'id_usuario' => $id_usuario
					);
					$this->candidato_model->editarCandidato($candidato, $id_candidato);

					if($idDomicilio != ''){
						$dom = array(
							'edicion' => $date,
							'id_candidato' => $id_candidato,
							'periodo' => $this->input->post('address_periodo'),
							'causa' => $this->input->post('address_causa'),
							'calle' => $this->input->post('address_calle'),
							'exterior' => $this->input->post('address_exterior'),
							'interior' => $this->input->post('address_interior'),
							'colonia' => $this->input->post('address_colonia'),
							'id_estado' => $this->input->post('address_estado'),
							'id_municipio' => $this->input->post('address_municipio'),
							'cp' => $this->input->post('address_cp')
						);      
						$this->candidato_model->editarDomicilio($dom, $idDomicilio);
						$msj = array(
							'codigo' => 2,
							'msg' => 'success'
						);
					}
					else{
						$dom = array(
							'creacion' => $date,
							'edicion' => $date,
							'id_candidato' => $id_candidato,
							'periodo' => $this->input->post('address_periodo'),
							'causa' => $this->input->post('address_causa'),
							'calle' => $this->input->post('address_calle'),
							'exterior' => $this->input->post('address_exterior'),
							'interior' => $this->input->post('address_interior'),
							'colonia' => $this->input->post('address_colonia'),
							'id_estado' => $this->input->post('address_estado'),
							'id_municipio' => $this->input->post('address_municipio'),
							'cp' => $this->input->post('address_cp')
						);      
						$idDomicilio = $this->candidato_model->guardarDomicilio($dom);
						$msj = array(
							'codigo' => 1,
							'msg' => $idDomicilio
						);
					}
				}
				echo json_encode($msj);
			}
			if($this->input->post('tipo') == 'internacional'){
				$this->form_validation->set_rules('address_periodo', 'Periodo', 'required|trim');
				$this->form_validation->set_rules('address_causa', 'Causa de salida', 'required|trim');
				$this->form_validation->set_rules('address_domicilio', 'Calle', 'required|trim');
				$this->form_validation->set_rules('address_pais', 'No. Exterior', 'required|trim');

				$this->form_validation->set_message('required', 'El campo {field} es obligatorio');

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
					$num =  $this->input->post('num');
					$id_candidato =  $this->input->post('id_candidato');
					$idDomicilio =  $this->input->post('id_domicilio');
					$id_usuario = $this->session->userdata('id');

					$candidato = array(
						'id_usuario' => $id_usuario
					);
					$this->candidato_model->editarCandidato($candidato, $id_candidato);

					if($idDomicilio != ''){
						$dom = array(
							'edicion' => $date,
							'id_candidato' => $id_candidato,
							'periodo' => $this->input->post('address_periodo'),
							'causa' => $this->input->post('address_causa'),
							'domicilio_internacional' => $this->input->post('address_domicilio'),
							'pais' => $this->input->post('address_pais')
						);      
						$this->candidato_model->editarDomicilio($dom, $idDomicilio);
						$msj = array(
							'codigo' => 2,
							'msg' => 'success'
						);
					}
					else{
						$dom = array(
							'creacion' => $date,
							'edicion' => $date,
							'id_candidato' => $id_candidato,
							'periodo' => $this->input->post('address_periodo'),
							'causa' => $this->input->post('address_causa'),
							'domicilio_internacional' => $this->input->post('address_domicilio'),
							'pais' => $this->input->post('address_pais')
						);      
						$idDomicilio = $this->candidato_model->guardarDomicilio($dom);
						$msj = array(
							'codigo' => 1,
							'msg' => $idDomicilio
						);
					}
				}
				echo json_encode($msj);
			}
		}
		function guardarComentarioVerificarDomicilios(){
			$this->form_validation->set_rules('comentario', 'Comentario', 'required|trim');

			$this->form_validation->set_message('required', 'El campo {field} es obligatorio');

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
				$comentario = $this->input->post('comentario');
				$id_usuario = $this->session->userdata('id');
				$this->candidato_model->eliminarVerificacionDomicilios($id_candidato);
				$domicilio = array(
					'creacion' => $date,
					'edicion' => $date,
					'id_usuario' => $id_usuario,
					'id_candidato' => $id_candidato,
					'comentario' => $comentario
				);
				$this->candidato_model->guardarVerificacionDomicilio($domicilio);
				$msj = array(
					'codigo' => 1,
					'msg' => 'Success'
				);
			}
			echo json_encode($msj);
		}

		
		function guardarGlobalesH(){
      $this->form_validation->set_rules('sanctions', 'Sanctions', 'required|trim');
      $this->form_validation->set_rules('regulatory', 'Regulatory', 'required|trim');
      $this->form_validation->set_rules('law_enforcement', 'Law enforcement', 'required|trim');
      $this->form_validation->set_rules('other_bodies', 'Other bodies', 'required|trim');
      $this->form_validation->set_rules('media_searches', 'Web and media searches', 'required|trim');
      $this->form_validation->set_rules('oig', 'OIG', 'required|trim');
      $this->form_validation->set_rules('ofac', 'OFAC', 'required|trim');
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
          'sanctions' => $this->input->post('sanctions'),
          'regulatory' => $this->input->post('regulatory'),
          'law_enforcement' => $this->input->post('law_enforcement'),
          'other_bodies' => $this->input->post('other_bodies'),
          'media_searches' => $this->input->post('media_searches'),
          'ofac' => $this->input->post('ofac'),
          'oig' => $this->input->post('oig'),
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
		function guardarReferenciaProfesional(){
			$this->form_validation->set_rules('nombre', 'Nombre', 'required|trim');
      $this->form_validation->set_rules('telefono', 'Teléfono', 'required|trim');
      $this->form_validation->set_rules('tiempo', 'Tiempo de conocerlo(a)', 'required|trim');
      $this->form_validation->set_rules('conocido', 'Dónde lo(a) conoció', 'required|trim');
      $this->form_validation->set_rules('puesto', 'Qué puesto desempeñaba', 'required|trim');
      $this->form_validation->set_rules('tiempo2', 'Tiempo de conocer al candidato', 'required|trim');
      $this->form_validation->set_rules('conocido2', 'Dónde conoció al candidato', 'required|trim');
      $this->form_validation->set_rules('puesto2', 'Qué puesto desempeñaba', 'required|trim');
      $this->form_validation->set_rules('cualidades', 'Cualidades del candidato', 'required|trim');
      $this->form_validation->set_rules('desempeno', '¿Cómo fue el desempeño del candidato?', 'required|trim');
      $this->form_validation->set_rules('recomienda', '¿Recomienda al candidato?', 'required|trim');
      $this->form_validation->set_rules('comentario', 'Comentarios de la referencia', 'required|trim');
      
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
				$num = $this->input->post('num');
				$id_candidato = $this->input->post('id_candidato');

				$this->candidato_model->eliminarReferenciaProfesional($id_candidato, $num);
				$refpro = array(
					'creacion' => $date,
					'id_candidato' => $id_candidato,
					'numero' => $num,
					'nombre' => $this->input->post('nombre'),
					'telefono' => $this->input->post('telefono'),
					'tiempo_conocerlo' => $this->input->post('tiempo'),
					'donde_conocerlo' => $this->input->post('conocido'),
					'puesto' => $this->input->post('puesto'),
					'verificacion_tiempo' => $this->input->post('tiempo2'),
					'verificacion_conocerlo' => $this->input->post('conocido2'),
					'verificacion_puesto' => $this->input->post('puesto2'),
					'cualidades' => $this->input->post('cualidades'),
					'desempeno' => $this->input->post('desempeno'),
					'recomienda' => $this->input->post('recomienda'),
					'comentarios' => $this->input->post('comentario'),
				);
				$this->candidato_model->guardarReferenciaProfesional($refpro);
				$msj = array(
					'codigo' => 1,
					'msg' => 'success'
				);
			}
			echo json_encode($msj);
		}
		function createHistorialCrediticio(){
			$this->form_validation->set_rules('fi', 'Fecha inicio', 'required|trim');
      $this->form_validation->set_rules('ff', 'Fecha fin', 'required|trim');
      $this->form_validation->set_rules('comentario', 'Comentario', 'required|trim');
      
      $this->form_validation->set_message('required','El campo {field} es obligatorio');

      $msj = array();
      if ($this->form_validation->run() == FALSE) {
				$msj = array(
					'codigo' => 0,
					'msg' => validation_errors()
				);
      } 
			else{
				$id_candidato = $this->input->post('id_candidato');
				$fi = $this->input->post('fi');
				$ff = $this->input->post('ff');
				$comentario = $this->input->post('comentario');
				$id_usuario = $this->session->userdata('id');
				date_default_timezone_set('America/Mexico_City');
				$date = date('Y-m-d H:i:s');
				$salida = "";
				$credito = array(
					'creacion' => $date,
					'edicion' => $date,
					'id_usuario' => $id_usuario,
					'id_candidato' => $id_candidato,
					'fecha_inicio' => $fi,
					'fecha_fin' => $ff,
					'comentario' => $comentario
				);
				$this->candidato_model->guardarHistorialCrediticio($credito);
				$data['historial'] = $this->candidato_model->checkCredito($id_candidato);
				foreach($data['historial'] as $h){
					$salida .= '<div class="col-md-3">
												<p class="text-center"><b>Del</b></p>
												<p class="text-center">'.$h->fecha_inicio.'</p>
												</div>
											<div class="col-md-3">
													<p class="text-center"><b>Al</b></p>
													<p class="text-center">'.$h->fecha_fin.'</p>
											</div>
											<div class="col-md-6">
													<label>Comentario</label>
													<p>'.$h->comentario.'</p>
											</div>';
				}
				$msj = array(
					'codigo' => 1,
					'msg' => $salida
				);
			}
			echo json_encode($msj);
		}
		function guardarReferenciasPersonales(){
			$cantidad = $this->input->post('cantidad');
			for($i = 1; $i <= $cantidad; $i++){
				$this->form_validation->set_rules('nombre'.$i, 'Nombre de la referencia #'.$i, 'required|trim');
				$this->form_validation->set_rules('tiempo'.$i, 'Tiempo de conocerlo de la referencia #'.$i, 'required|trim');
				$this->form_validation->set_rules('lugar'.$i, 'Lugar donde lo conoció de la referencia #'.$i, 'required|trim');
				$this->form_validation->set_rules('telefono'.$i, 'Teléfono de la referencia #'.$i, 'required|trim|max_length[16]');
				$this->form_validation->set_rules('trabaja'.$i, '¿Sabe dónde trabaja? de la referencia #'.$i, 'required|trim');
				$this->form_validation->set_rules('vive'.$i, '¿Sabe dónde vive? de la referencia #'.$i, 'required|trim');
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
				for($i = 1; $i <= $cantidad; $i++){
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
		function crearReportePDF(){
			$mpdf = new \Mpdf\Mpdf();
			date_default_timezone_set('America/Mexico_City');
			$data['hoy'] = date("d-m-Y");
			$hoy = date("d-m-Y");
			$id_candidato = $_POST['idCandidatoPDF'];
			$data['datos'] = $this->candidato_model->getDatosCandidato($id_candidato);
			$id_usuario = $this->session->userdata('id');
      $tipo_usuario = $this->session->userdata('tipo');
			foreach($data['datos'] as $row){
				$f = $row->fecha_alta;
				$fform = $row->fecha_contestado;
				$fdocs = $row->fecha_documentos;
				$fbgc = $row->fecha_bgc;
				$nombreCandidato = $row->nombre." ".$row->paterno." ".$row->materno;
				$cliente = $row->cliente;
				$id_doping = $row->idDoping;
			}
			$f_alta = formatoFecha($f);
			$fform = formatoFecha($fform);
			$fdocs = formatoFecha($fdocs);
			$fecha_bgc = DateTime::createFromFormat('Y-m-d H:i:s', $fbgc);
			$fecha_bgc = $fecha_bgc->format('F d, Y');
			$fbgc = formatoFecha($fbgc);
			$data['cliente'] = $cliente;
			$data['candidato'] = $nombreCandidato;
			//$hoy = formatoFecha($hoy);
			$data['secciones'] = $this->candidato_model->getSeccionesCandidato($id_candidato);
			$data['checklist'] = $this->candidato_model->getVerificacionChecklist($id_candidato);
			$data['bgc'] = $this->candidato_model->getBGC($id_candidato);
			$data['fecha_ver_documentos'] = $this->candidato_model->getFechaVerificacionDocumentos($id_candidato);
			$data['fecha_ver_laboral'] = $this->candidato_model->getFechaVerificacionLaboral($id_candidato);
			$data['fecha_ver_estudios'] = $this->candidato_model->getFechaVerificacionEstudios($id_candidato);
			$data['fecha_ver_penales'] = $this->candidato_model->getFechaVerificacionPenales($id_candidato);
			$data['global_searches'] = $this->candidato_model->getGlobalSearches($id_candidato);
			$data['fecha_medico'] = $this->candidato_model->getFechaExamenMedico($id_candidato);
			$data['fecha_credito'] = $this->candidato_model->getFechaCredito($id_candidato);
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
			$data['domicilios'] = $this->candidato_model->getHistorialDomicilios($id_candidato);
			$data['ver_domicilios'] = $this->candidato_model->checkVerificacionDomicilios($id_candidato);
			$data['ref_profesional'] = $this->candidato_model->getReferenciasProfesionales($id_candidato);
			$data['credito'] = $this->candidato_model->checkCredito($id_candidato);
			$data['estatus_estudios'] = $this->candidato_model->getEstatusEstudios($id_candidato);
			$data['estatus_laborales'] = $this->candidato_model->getEstatusLaborales($id_candidato);
			$data['estatus_penales'] = $this->candidato_model->getEstatusPenales($id_candidato);
			$data['ref_personales'] = $this->candidato_model->getReferenciasPersonales($id_candidato);
			$data['ref_academicas'] = $this->candidato_ref_academica_model->getById($id_candidato);
			
			$data['fecha_bgc'] = $fecha_bgc;
			$data['pruebas'] = $this->candidato_model->getPruebasCandidato($id_candidato);
			$doping = $this->doping_model->getDatosDoping($id_doping);
			$data['doping'] = $doping;
			//$data['doc_doping'] = $this->load->view('pdfs/doping_pdf',$dop,TRUE);
			$html = $this->load->view('pdfs/hcl_pdf',$data,TRUE);
			$mpdf->setAutoTopMargin = 'stretch';
			$mpdf->AddPage();
			$mpdf->SetHTMLHeader('<div style="width: 33%; float: left;"><img style="height: 50px;" src="'.base_url().'img/logo.png"></div><div style="width: 33%; float: right;text-align: right;">Request Date: '.$f_alta.'<br>Release Date: '.$fbgc.'</div>');
			$mpdf->SetHTMLFooter('<div style="position: absolute; left: 20px; bottom: 10px; color: rgba(0,0,0,0.5);"><p style="font-size: 10px;">Calle Benito Juarez # 5693, Col. Santa María del Pueblito <br>Zapopan, Jalisco C.P. 45018 <br>Tel. (33) 2301-8599</p></div><div style="position: absolute; right: 0;  bottom: 0;"><img class="" src="'.base_url().'img/logo_pie.png"></div>');
			
			//Cifrar pdf
      $nombreArchivo = substr( md5(microtime()), 1, 12);
			/*if($tipo_usuario == 1){
        $usuario = $this->usuario_model->getDatosUsuarioInterno($id_usuario);
      }*/
      if($tipo_usuario == 2){
        $usuario = $this->usuario_model->getDatosUsuarioCliente($id_usuario);
        if($usuario->clave != null){
          $claveAleatoria = substr( md5(microtime()), 1, 8);
          //$clave = ($usuario->clave != null)? $usuario->clave:$claveAleatoria;
          $clave = $usuario->clave;
          $mpdf->SetProtection(array(), $clave, 'r0d1@');
        }
      }
      $mpdf->autoPageBreak = false;
			$mpdf->WriteHTML($html);
      $mpdf->Output(''.$nombreArchivo.'.pdf','D'); // opens in browser
		}
		function createGeneral(){
			$mpdf = new \Mpdf\Mpdf();
			date_default_timezone_set('America/Mexico_City');
			$data['hoy'] = date("d-m-Y");
			$hoy = date("d-m-Y");
			$id_candidato = $_POST['idPDF'];
			$data['datos'] = $this->candidato_model->getDatosCandidato($id_candidato);
			foreach($data['datos'] as $row){
					$f = $row->fecha_alta;
					$fform = $row->fecha_contestado;
					$fdocs = $row->fecha_documentos;
					$fbgc = $row->fecha_bgc;
					$nombreCandidato = $row->nombre." ".$row->paterno." ".$row->materno;
					$cliente = $row->cliente;
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
			$data['domicilios'] = $this->candidato_model->getHistorialDomicilios($id_candidato);
			$data['ver_domicilios'] = $this->candidato_model->checkVerificacionDomicilios($id_candidato);
			$data['cliente'] = $cliente;
			$data['proyecto'] = $proyecto;
			$data['fecha_bgc'] = $fecha_bgc;
			$data['pruebas'] = $this->candidato_model->getPruebasCandidato($id_candidato);
			$doping = $this->doping_model->getDatosDoping($id_doping);
			$data['doping'] = $doping;
			//$data['doc_doping'] = $this->load->view('pdfs/doping_pdf',$dop,TRUE);
			$html = $this->load->view('pdfs/hcl_general_pdf',$data,TRUE);
			$mpdf->setAutoTopMargin = 'stretch';
			$mpdf->AddPage();
			$mpdf->SetHTMLHeader('<div style="width: 33%; float: left;"><img style="height: 50px;" src="'.base_url().'img/logo.png"></div><div style="width: 33%; float: right;text-align: right;">Request Date: '.$f_alta.'<br>Release Date: '.$fbgc.'</div>');
			$mpdf->SetHTMLFooter('<div style="position: absolute; left: 20px; bottom: 10px; color: rgba(0,0,0,0.5);"><p style="font-size: 10px;">Calle Benito Juarez # 5693, Col. Santa María del Pueblito <br>Zapopan, Jalisco C.P. 45018 <br>Tel. (33) 2301-8599</p></div><div style="position: absolute; right: 0;  bottom: 0;"><img class="" src="'.base_url().'img/logo_pie.png"></div>');
			
      $mpdf->autoPageBreak = false;
			$mpdf->WriteHTML($html);

			$mpdf->Output('Background_'.$cliente.'-'.$nombreCandidato.'.pdf','D'); // opens in browser
		}
		function createCustom(){
			$mpdf = new \Mpdf\Mpdf();
			date_default_timezone_set('America/Mexico_City');
			$data['hoy'] = date("d-m-Y");
			$hoy = date("d-m-Y");
			$id_candidato = $_POST['idPDF'];
			$data['datos'] = $this->candidato_model->getDatosCandidato($id_candidato);
			foreach($data['datos'] as $row){
				$f = $row->fecha_alta;
				$fform = $row->fecha_contestado;
				$fdocs = $row->fecha_documentos;
				$fbgc = $row->fecha_bgc;
				$nombreCandidato = $row->nombre." ".$row->paterno." ".$row->materno;
				$cliente = $row->cliente;
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
			$data['bgc'] = $this->candidato_model->getBGC($id_candidato);
			$data['global_searches'] = $this->candidato_model->getGlobalSearches($id_candidato);
			$data['fecha_ver_documentos'] = $this->candidato_model->getFechaVerificacionDocumentos($id_candidato);
			$data['fecha_ver_penales'] = $this->candidato_model->getFechaVerificacionPenales($id_candidato);
			$data['fecha_ver_ofac'] = $this->candidato_model->getFechaVerificacionOfac($id_candidato);
			$data['det_penales'] = $this->candidato_model->getStatusVerificacionPenales($id_candidato);
			$data['docs'] = $this->candidato_model->getDocumentacionCandidato($id_candidato);
			$data['ver_documento'] = $this->candidato_model->getVerificacionDocumentosCandidato($id_candidato);
			$data['domicilios'] = $this->candidato_model->getHistorialDomicilios($id_candidato);
			$data['ver_domicilios'] = $this->candidato_model->checkVerificacionDomicilios($id_candidato);
			$data['analista'] = $this->candidato_model->getAnalista($id_candidato);
			$data['coordinadora'] = $this->candidato_model->getCoordinadora($id_candidato);
			$data['checklist'] = $this->candidato_model->getVerificacionChecklist($id_candidato);
			$data['cliente'] = $cliente;
			$data['proyecto'] = $proyecto;
			$data['fecha_bgc'] = $fecha_bgc;
			$data['pruebas'] = $this->candidato_model->getPruebasCandidato($id_candidato);
			$doping = $this->doping_model->getDatosDoping($id_doping);
			$data['doping'] = $doping;

			$html = $this->load->view('pdfs/hcl_custom_pdf',$data,TRUE);
			$mpdf->setAutoTopMargin = 'stretch';
			$mpdf->AddPage();
			$mpdf->SetHTMLHeader('<div style="width: 33%; float: left;"><img style="height: 50px;" src="'.base_url().'img/logo.png"></div><div style="width: 33%; float: right;text-align: right;">Request Date: '.$f_alta.'<br>Release Date: '.$fbgc.'</div>');
			$mpdf->SetHTMLFooter('<div style="position: absolute; left: 20px; bottom: 10px; color: rgba(0,0,0,0.5);"><p style="font-size: 10px;">Calle Benito Juarez # 5693, Col. Santa María del Pueblito <br>Zapopan, Jalisco C.P. 45018 <br>Tel. (33) 2301-8599<br><br>4-EST-001.Rev. 01 <br>Fecha de Rev. 05/06/2020</p></div><div style="position: absolute; right: 0;  bottom: 0;"><img class="" src="'.base_url().'img/logo_pie.png"></div>');
			
      $mpdf->autoPageBreak = false;
			$mpdf->WriteHTML($html);

			$mpdf->Output('Background_'.$cliente.'-'.$nombreCandidato.'.pdf','D'); // opens in browser
		}
		function createInternacional(){
			$mpdf = new \Mpdf\Mpdf();
			date_default_timezone_set('America/Mexico_City');
			$data['hoy'] = date("d-m-Y");
			$hoy = date("d-m-Y");
			$id_candidato = $_POST['idPDF'];
			$data['datos'] = $this->candidato_model->getDatosCandidato($id_candidato);
			foreach($data['datos'] as $row){
				$f = $row->fecha_alta;
				$fform = $row->fecha_contestado;
				$fdocs = $row->fecha_documentos;
				$fbgc = $row->fecha_bgc;
				$nombreCandidato = $row->nombre." ".$row->paterno." ".$row->materno;
				$cliente = $row->cliente;
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
			$data['credito'] = $this->candidato_model->checkCredito($id_candidato);
			$data['det_empleo'] = $this->candidato_model->getStatusVerificacionEmpleo($id_candidato);
			$data['det_penales'] = $this->candidato_model->getStatusVerificacionPenales($id_candidato);
			$data['analista'] = $this->candidato_model->getAnalista($id_candidato);
			$data['coordinadora'] = $this->candidato_model->getCoordinadora($id_candidato);
			$data['checklist'] = $this->candidato_model->getVerificacionChecklist($id_candidato);
			$data['domicilios'] = $this->candidato_model->getHistorialDomicilios($id_candidato);
			$data['ver_domicilios'] = $this->candidato_model->checkVerificacionDomicilios($id_candidato);
			$data['ref_profesional'] = $this->candidato_model->getReferenciasProfesionales($id_candidato);
			$data['cliente'] = $cliente;
			$data['proyecto'] = $proyecto;
			$data['fecha_bgc'] = $fecha_bgc;
			$data['pruebas'] = $this->candidato_model->getPruebasCandidato($id_candidato);
			$doping = $this->doping_model->getDatosDoping($id_doping);
			$data['doping'] = $doping;
			//$data['doc_doping'] = $this->load->view('pdfs/doping_pdf',$dop,TRUE);
			$html = $this->load->view('pdfs/hcl_internacional_pdf',$data,TRUE);
			$mpdf->setAutoTopMargin = 'stretch';
			$mpdf->AddPage();
			$mpdf->SetHTMLHeader('<div style="width: 33%; float: left;"><img style="height: 50px;" src="'.base_url().'img/logo.png"></div><div style="width: 33%; float: right;text-align: right;">Request Date: '.$f_alta.'<br>Release Date: '.$fbgc.'</div>');
			$mpdf->SetHTMLFooter('<div style="position: absolute; left: 20px; bottom: 10px; color: rgba(0,0,0,0.5);"><p style="font-size: 10px;">Calle Benito Juarez # 5693, Col. Santa María del Pueblito <br>Zapopan, Jalisco C.P. 45018 <br>Tel. (33) 2301-8599</p></div><div style="position: absolute; right: 0;  bottom: 0;"><img class="" src="'.base_url().'img/logo_pie.png"></div>');
			
      $mpdf->autoPageBreak = false;
			$mpdf->WriteHTML($html);

			$mpdf->Output('Background_'.$cliente.'-'.$nombreCandidato.'.pdf','D'); // opens in browser
		}
}