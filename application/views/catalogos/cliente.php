<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="align-items-center mb-4">
		<div class="row">
			<div class="col-sm-12 col-md-8">
				<h1 class="h3 mb-0 text-gray-800">Clientes</h1>
			</div> 
			<div class="col-sm-12 col-md-2">
				<a href="#" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#newModal">
					<span class="icon text-white-50">
            <i class="fas fa-user-tie"></i>
					</span>
					<span class="text">Agregar cliente</span>
				</a> 
			</div> 
			<div class="col-sm-12 col-md-2">
				<a href="#" class="btn btn-primary btn-icon-split" onclick="registrarAccesoCliente()">
					<span class="icon text-white-50">
            <i class="fas fa-sign-in-alt"></i>
          </span>
					<span class="text">Registro de clientes</span>
				</a>
			</div>
			<!--<div class="col-sm-12 col-md-8">
				<h1 class="h3 mb-0 text-gray-800"></h1>
			</div> -->
		</div>

			<div class="col-sm-12 col-md-2">
				<a href="#" class="btn btn-primary btn-icon-split" onclick="BotonVisibilidadCliente()">
					<span class="icon text-white-50">
            <i class="fas fa-sign-in-alt"></i>
          </span>
					<span class="text">Dar visibilidad de los clientes a los usuarios internos</span>
				</a>
			</div>
	</div> 

	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary"></h6>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table id="tabla" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				</table>
			</div>
		</div>
	</div>

	<?php echo $modals; ?>
	<div class="loader" style="display: none;"></div>
	<input type="hidden" id="idCliente">
	<input type="hidden" id="idUsuarioCliente">


</div>
<!-- /.content-wrapper -->
<script>

	var url = '<?php echo base_url('Cat_Cliente/get'); ?>';
	var tipos_bloqueo_php = '<?php foreach($tipos_bloqueo as $row){ echo '<option value="'.$row->tipo.'">'.$row->descripcion.'</option>';} ?>';
	var tipos_desbloqueo_php = '<?php foreach($tipos_desbloqueo as $row){ echo '<option value="'.$row->tipo.'">'.$row->descripcion.'</option>';} ?>';
	$(document).ready(function() {
		$('[data-toggle="tooltip"]').tooltip();
		$('#newModal').on('shown.bs.modal', function() {
			$(this).find('input[type=text],select,textarea').filter(':visible:first').focus();
		});
		var msj = localStorage.getItem("success");
		if (msj == 1) {
			Swal.fire({
				position: 'center',
				icon: 'success',
				title: 'Se ha guardado correctamente',
				showConfirmButton: false,
				timer: 2500
			})
			localStorage.removeItem("success");
		}
		$('#tabla').DataTable({
			"pageLength": 25,
			//"pagingType": "simple",
			"order": [0, "desc"],
			"stateSave": true,
			"serverSide": false,
			"ajax": url,
			"columns": [
				{ 
					title: 'id', 
					data: 'id', 
					visible: false
				},
				{
					title: 'Nombre',
					data: 'nombre',
					bSortable: false,
					"width": "25%",
          mRender: function(data, type, full){
            return '<span class="badge badge-pill badge-dark">#' + full.id + '</span><br><b>'+data+'</b>';
          }
				},
				{
					title: 'Clave',
					data: 'clave',
					bSortable: false,
					"width": "3%",
          mRender: function(data, type, full){
            return '<b>'+data+'</b>';
          }
				},
				/*{
					title: 'Clave',
					data: 'clave',
					bSortable: false,
					"width": "3%",
          mRender: function(data, type, full){
            return '<b>'+data+'</b>';
          }
				},*/

				{
					title: 'Fecha de alta',
					data: 'creacion',
					bSortable: false,
					"width": "7%",
					mRender: function(data, type, full) {
						var f = data.split(' ');
						var h = f[1];
						var aux = h.split(':');
						var hora = aux[0] + ':' + aux[1];
						var aux = f[0].split('-');
						var fecha = aux[2] + "/" + aux[1] + "/" + aux[0];
						var tiempo = fecha + ' ' + hora;
						return tiempo;
					}
				},
				{
					title: 'Accesos',
					data: 'numero_accesos',
					bSortable: false,
					"width": "15%",
					mRender: function(data, type, full) {
						if (data == 0) {
							return 'Sin registro de accesos';
						} else {
							return 'Cuenta con ' + data + ' registro(s) de acceso';
						}
					}
				},
				{
				  title: 'Acciones',
        data: 'id',
        bSortable: false,
        "width": "10%",
        mRender: function(data, type, full) {
          //console.log(full )

          let editar =
            '<a id="editar" href="javascript:void(0)" data-toggle="tooltip" title="Editar Usuario" class="fa-tooltip icono_datatable icono_azul_oscuro"><i class="fas fa-edit"></i></a> ';

          
          let eliminar = '<a href="javascript:void(0)" data-toggle="tooltip" title="Eliminar usuario" id="eliminar" class="fa-tooltip icono_datatable icono_gris"><i class="fas fa-trash"></i></a> ';
          let acceso =
            '<a href="javascript:void(0)" data-toggle="tooltip" title="Ver accesos" id="acceso" class="fa-tooltip icono_datatable icono_azul_claro"><i class="fas fa-sign-in-alt"></i></a>';

          let accion = (full.status == 0) ?
            '<a href="javascript:void(0)" data-toggle="tooltip" title="Activar" id="activar" class="fa-tooltip icono_datatable icono_rojo"><i class="fas fa-ban"></i></a> ' :
            '<a href="javascript:void(0)" data-toggle="tooltip" title="Desactivar" id="desactivar" class="fa-tooltip icono_datatable icono_verde"><i class="far fa-check-circle"></i></a> ';

          let bloqueo = (full.bloqueado === 'NO') ?
            ' <a href="javascript:void(0)" data-toggle="tooltip" title="Bloquear usuario" id="bloquear_usuario" class="fa-tooltip icono_datatable icono_verde"><i class="fas fa-user-check"></i></a> ' :
            ' <a href="javascript:void(0)" data-toggle="tooltip" title="Desbloquear usuario" id="desbloquear_usuario" class="fa-tooltip icono_datatable icono_rojo"><i class="fas fa-user-lock"></i></a> ';

          return editar + accion + eliminar + acceso + bloqueo;
        }
      }
    ],

			fnDrawCallback: function(oSettings) {
				$('a[data-toggle="tooltip"]').tooltip({
					trigger: "hover"
				});
			},
			rowCallback: function(row, data) {
				$("a#editar", row).bind('click', () => {
					$("#idCliente").val(data.id);
					$("#titulo_nuevo_modal").text("Editar cliente");
					$("#nombre").val(data.nombre);
					$("#clave").val(data.clave);
					$("#newModal").modal("show");
				});

				$("a#activar", row).bind('click', () => {
          mostrarMensajeConfirmacion('activar cliente',data.nombre,data.id)
				});
				$("a#desactivar", row).bind('click', () => {
          mostrarMensajeConfirmacion('desactivar cliente',data.nombre,data.id)
				});

        $("a#bloquear_cliente", row).bind('click', () => {
          mostrarMensajeConfirmacion('bloquear cliente',data.nombre,data.id)
				});
        $("a#desbloquear_cliente", row).bind('click', () => {
          mostrarMensajeConfirmacion('desbloquear cliente',data.nombre,data.id)
				});
				$("a#eliminar", row).bind('click', () => {
          mostrarMensajeConfirmacion('eliminar cliente',data.nombre,data.id)
				});
				$("a#acceso", row).bind('click', () => {
          $(".nombreCliente").text(data.nombre);
					
					$.ajax({
						url: '<?php echo base_url('Cat_Cliente/getClientesAccesos'); ?>',
						type: 'post',
						data: {
							'id_cliente': data.id
						},
						beforeSend: function() {
							$('.loader').css("display", "block");
						},
						success: function(res) {
							setTimeout(function(){
									$('.loader').fadeOut();
							},200);
              if(res != 0){
                let dato = JSON.parse(res);
                let salida = '<table class="table table-striped">';
                salida += '<thead>';
                salida += '<tr>';
                salida += '<th scope="col">Nombre</th>';
                salida += '<th scope="col">Correo</th>';
                salida += '<th scope="col">Alta</th>';
                salida += '<th scope="col">Usuario</th>';
                salida += '<th scope="col">Categoría</th>';
                salida += '<th scope="col">Eliminar</th>';
                salida += '</tr>';
                salida += '</thead>';
                salida += '<tbody>';
                for(let i = 0; i < dato.length; i++){
                  let privacidad = (dato[i]['privacidad'] > 0)? 'Nivel '+dato[i]['privacidad'] : 'Sin privacidad';
                  let fecha = fechaCompletaAFront(dato[i]['alta']);
                  salida += "<tr id='"+dato[i]['idUsuarioCliente']+"'><th>"+dato[i]['usuario_cliente']+"</th><th>"+dato[i]['correo_usuario']+"</th><th>"+fecha+"</th><th>"+dato[i]['usuario']+"</th><th>"+privacidad+"</th><th><a href='javascript:void(0)' class='fa-tooltip icono_datatable icono_accion_gris' onclick='mostrarMensajeConfirmacion(\"eliminar usuario cliente\",\""+dato[i]['usuario_cliente']+"\","+dato[i]['idUsuarioCliente']+")'><i class='fas fa-trash'></i></a></th></tr>";
                }
                salida += '</tbody>';
                salida += '</table>';  
							  $("#div_accesos").html(salida);
              }
              else{
                $('#div_accesos').html('<p style="text-align:center; font-size: 20px;">No hay registro de accesos</p>');
              }
						}
					});
          $("#accesosClienteModal").modal('show');
				});
				$("a#desactivar_acceso", row).bind('click', () => {
					$.ajax({
						url: '<?php echo base_url('Cliente/controlAccesoCliente'); ?>',
						type: 'post',
						data: {
							'idUsuarioCliente': data.idUsuarioCliente,
							'activo': 0
						},
						beforeSend: function() {
							$('.loader').css("display", "block");
						},
						success: function(res) {
							setTimeout(function() {
								$('.loader').fadeOut();
							}, 200);
							$("#mensajeModal").modal('hide');
							recargarTable();
							$("#texto_msj").text('Se ha actualizado exitosamente');
							$("#mensaje").css('display', 'block');
							setTimeout(function() {
								$('#mensaje').fadeOut();
							}, 4000);
						}
					});
				});
				$("a#activar_acceso", row).bind('click', () => {
					$.ajax({
						url: '<?php echo base_url('Cliente/controlAccesoCliente'); ?>',
						type: 'post',
						data: {
							'idUsuarioCliente': data.idUsuarioCliente,
							'activo': 1
						},
						beforeSend: function() {
							$('.loader').css("display", "block");
						},
						success: function(res) {
							setTimeout(function() {
								$('.loader').fadeOut();
							}, 200);
							$("#mensajeModal").modal('hide');
							recargarTable();
							$("#texto_msj").text('Se ha actualizado exitosamente');
							$("#mensaje").css('display', 'block');
							setTimeout(function() {
								$('#mensaje').fadeOut();
							}, 4000);
						}
					});
				});
			},
			"language": {
				"lengthMenu": "Mostrar _MENU_ registros por página",
				"zeroRecords": "No se encontraron registros",
				"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
				"infoEmpty": "Sin registros disponibles",
				"infoFiltered": "(Filtrado _MAX_ registros totales)",
				"sSearch": "Buscar:",
				"oPaginate": {
					"sLast": "Última página",
					"sFirst": "Primera",
					"sNext": "Siguiente",
					"sPrevious": "Anterior"
				}
			}
		});
	});
  function guardarCliente(){
    let datos = $('#formCatCliente').serialize();
    datos += '&id=' + $("#idCliente").val();
    $.ajax({
      url: '<?php echo base_url('Cat_Cliente/set'); ?>',
      type: "POST",
      data: datos,
      beforeSend: function() {
        $('.loader').css("display", "block");
      },
      success: function(res) {
        setTimeout(function(){
          $('.loader').fadeOut();
        },200);
        var data = JSON.parse(res);
        if (data.codigo === 1){
          setTimeout(function() {
            $('.loader').fadeOut();
            $("#newModal").modal('hide')
            recargarTable()
            Swal.fire({
              position: 'center',
              icon: 'success',
              title: 'Cliente guardado correctamente',
              showConfirmButton: false,
              timer: 2500
            })
          }, 200);
        } 
        else {
          $("#newModal #msj_error").css('display', 'block').html(data.msg);
        }
      }
    });
  }
  function mostrarMensajeConfirmacion(accion,valor1,valor2){
		if(accion == "activar cliente"){
			$('#titulo_mensaje').text('Activar cliente');
			$('#mensaje').html('¿Desea activar al cliente <b>'+valor1+'</b>?');
			$('#btnConfirmar').attr("onclick","accionCliente('activar',"+valor2+")");
			$('#mensajeModal').modal('show');
		}
    if(accion == "desactivar cliente"){
			$('#titulo_mensaje').text('Desactivar cliente');
			$('#mensaje').html('¿Desea desactivar al cliente <b>'+valor1+'</b>?');
			$('#btnConfirmar').attr("onclick","accionCliente('desactivar',"+valor2+")");
			$('#mensajeModal').modal('show');
		}
    if(accion == "eliminar cliente"){
			$('#titulo_mensaje').text('Eliminar cliente');
			$('#mensaje').html('¿Desea eliminar al cliente <b>'+valor1+'</b>?');
			$('#btnConfirmar').attr("onclick","accionCliente('eliminar',"+valor2+")");
			$('#mensajeModal').modal('show');
		}
    if(accion == "eliminar usuario cliente"){
			$('#titulo_mensaje').text('Eliminar usuario');
			$('#mensaje').html('¿Desea eliminar al usuario <b>'+valor1+'</b>?');
			$('#btnConfirmar').attr("onclick","controlAcceso('eliminar',"+valor2+")");
      $("#accesosClienteModal").modal('hide')
			$('#mensajeModal').modal('show');
		}
    if(accion == "bloquear cliente"){
			$('#titulo_mensaje').text('Bloquear cliente');
			$('#mensaje').html('¿Desea bloquear al cliente <b>'+valor1+'</b>?');
			$('#mensaje').append('<div class="row mt-3"><div class="col-12"><label>Motivo de bloqueo *</label><select class="form-control" id="opcion_motivo" name="opcion_motivo"><option value="">Selecciona</option>'+tipos_bloqueo_php+'</select></div></div><div class="row mt-3"><div class="col-12"><label>Mensaje para presentar en panel del cliente *</label><textarea class="form-control" rows="5" id="mensaje_comentario" name="mensaje_comentario">¡Lo sentimos! Su acceso ha sido interrumpido por falta de pago. Favor de comunicarse al teléfono 33 3454 2877.</textarea></div></div>');
			$('#mensaje').append('<div class="row mt-3"><div class="col-12"><label class="container_checkbox">Bloquear también subclientes/proveedores<input type="checkbox" id="bloquear_subclientes" name="bloquear_subclientes"><span class="checkmark"></span></label></div></div>');
			$('#btnConfirmar').attr("onclick","accionCliente('bloquear',"+valor2+")");
			$('#mensajeModal').modal('show');
		}
    if(accion == "desbloquear cliente"){
			$('#titulo_mensaje').text('Desbloquear cliente');
			$('#mensaje').html('¿Desea desbloquear al cliente <b>'+valor1+'</b>?');
			$('#mensaje').append('<div class="row mt-3"><div class="col-12"><label>Razón de desbloqueo *</label><select class="form-control" id="opcion_motivo" name="opcion_motivo"><option value="">Selecciona</option>'+tipos_desbloqueo_php+'</select></div></div>');
			$('#btnConfirmar').attr("onclick","accionCliente('desbloquear',"+valor2+")");
			$('#mensajeModal').modal('show');
		}
	}
  function accionCliente(accion, id) {
    let opcion_motivo = $('#mensajeModal #opcion_motivo').val()
    let opcion_descripcion = $( "#mensajeModal #opcion_motivo option:selected" ).text();
    let mensaje_comentario = $('#mensajeModal #mensaje_comentario').val()
    let bloquear_subclientes = $("#mensajeModal #bloquear_subclientes").is(":checked")? 'SI':'NO';
		$.ajax({
			url: '<?php echo base_url('Cat_Cliente/status'); ?>',
			type: 'post',
			data: {
				'id': id, 'accion': accion, 'opcion_motivo':opcion_motivo, 'mensaje_comentario':mensaje_comentario,
        'opcion_descripcion':opcion_descripcion, 'bloquear_subclientes':bloquear_subclientes
			},
			beforeSend: function() {
				$('.loader').css("display", "block");
			},
			success: function(res) {
				setTimeout(function(){
						$('.loader').fadeOut();
				},200);
				var data = JSON.parse(res);
				if (data.codigo === 1){
					$("#mensajeModal").modal('hide')
					recargarTable()
					Swal.fire({
						position: 'center',
						icon: 'success',
						title: data.msg,
						showConfirmButton: false,
						timer: 2500
					})
				} 
			}
		});
	}
  function registrarAccesoCliente(){
    $.ajax({
			url: '<?php echo base_url('Cat_Cliente/getActivos'); ?>',
			type: 'post',
			beforeSend: function() {
				$('.loader').css("display", "block");
			},
			success: function(res) {
        setTimeout(function(){
          $('.loader').fadeOut();
        },200);
        if(res != 0){
          $('#id_cliente').empty();
          var dato = JSON.parse(res);
          $('#id_cliente').append('<option value="">Selecciona</option>');
          for(let i = 0; i < dato.length; i++){
            $('#id_cliente').append('<option value="'+dato[i]['id']+'">'+dato[i]['nombre']+'</option>');
          }
          $('#nuevoAccesoClienteModal').modal('show');
        }
			}
		});
  }

	
	
  function crearAcceso() {
    let datos = $('#formAccesoCliente').serialize();
    $.ajax({
      url: '<?php echo base_url('Cat_Cliente/addUsuario'); ?>',
      type: 'POST',
      data: datos,
      beforeSend: function() {
        $('.loader').css("display", "block");
      },
      success: function(res) {
        setTimeout(function(){
          $('.loader').fadeOut();
        },200);
        var data = JSON.parse(res);
        if (data.codigo === 1){
          $("#nuevoAccesoClienteModal").modal('hide')
          recargarTable()
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Usuario guardado correctamente',
            showConfirmButton: false,
            timer: 2500
          })
        } 
        else {
          $("#nuevoAccesoClienteModal #msj_error").css('display', 'block').html(data.msg);
        }
      }
    });
  }
	function controlAcceso(accion, idUsuarioCliente) {
		$("tr#" + idUsuarioCliente).hide();
		$.ajax({
			url: '<?php echo base_url('Cat_Cliente/controlAcceso'); ?>',
			type: 'post',
			data: {
				'idUsuarioCliente': idUsuarioCliente,
				'accion': accion
			},
			beforeSend: function() {
				$('.loader').css("display", "block");
			},
			success: function(res) {
				setTimeout(function(){
						$('.loader').fadeOut();
				},200);
				var data = JSON.parse(res);
				if (data.codigo === 1){
					recargarTable()
          $("#mensajeModal").modal('hide')
					Swal.fire({
						position: 'center',
						icon: 'success',
						title: data.msg,
						showConfirmButton: false,
						timer: 2500
					})
				} 
			}
		});
	}

/*******BOTON DAR VISIBILIDAD DE LOS CLIENTES A LOS USUARIOS INTERNOS*************************************************/
function BotonVisibilidadCliente() {
    $.ajax({
        url: '<?php echo base_url('Cat_Cliente/get_Visibilidad'); ?>',
        type: 'get',
        beforeSend: function() {
            $('.loader').css("display", "block");
        },
        success: function(res) {
            setTimeout(function(){
                $('.loader').fadeOut();
            }, 200);
            if(res) {
                $('#id_clientePermisos').empty();
                $('#id_rol_Usuario').empty();
                var datos = JSON.parse(res);
                $('#id_clientePermisos').append('<option value="">Selecciona</option>');
                $('#id_rol_Usuario').append('<option value="">Selecciona</option>');
                for(var i = 0; i < datos.clientes.length; i++){
                    $('#id_clientePermisos').append('<option value="'+datos.clientes[i]['cliente_id']+'">'+datos.clientes[i]['cliente_nombre']+'</option>');
                }
								
                for(var j = 0; j < datos.usuarios.length; j++){
                    $('#id_rol_Usuario').append('<option value="'+datos.usuarios[j]['usuario_id']+'">'+datos.usuarios[j]['usuario_nombre']+' '+datos.usuarios[j]['usuario_paterno']+'</option>');
                }

                $('#ModalVisibilidadClientes').modal('show');
            }
        }
    });
}
/*****************Función para mostrar los clientes o usuarios seleccionados en el div flexible del modal***********/
function mostrarSeleccionados(tipo) {
    var selectElement = tipo === 'cliente' ? document.getElementById('id_clientePermisos') : document.getElementById('id_rol_Usuario');
    var selectedOptions = selectElement.selectedOptions;
    var espacioDiv = document.getElementById('espacio_para_agregado');
    var usuariosSeleccionados = []; // Array para guardar los nombres de los usuarios seleccionados

    // Si el tipo es cliente, permitir seleccionar solo una opción
    if (tipo === 'cliente') {
        // Desmarcar todas las selecciones excepto la última
        for (var i = 0; i < selectedOptions.length - 1; i++) {
            selectedOptions[i].selected = false;
        }
    }

    // Se recorre las opciones seleccionadas para agregarlas al div flexible y al array
    for (var i = 0; i < selectedOptions.length; i++) {
        var selectedOption = selectedOptions[i];
        var optionText = selectedOption.textContent;
        usuariosSeleccionados.push(optionText); // Guardar el nombre del usuario en el array

        // Crear un nuevo div para el usuario seleccionado
        var usuarioDiv = document.createElement('div');
        usuarioDiv.className = 'usuario-seleccionado'; // 
				
        // Contiene el texto del usuario seleccionado 
        var textoSeleccionado = document.createTextNode(optionText);
        usuarioDiv.appendChild(textoSeleccionado);

        // Almacenar el nombre del usuario como un atributo de datos en el div
        usuarioDiv.setAttribute('data-nombre', optionText);

        var botonEliminar = document.createElement('button');
        botonEliminar.textContent = 'Eliminar';
        botonEliminar.className = 'btn btn-danger btn-sm ml-2';
       // botonEliminar.setAttribute('type', 'button'); // Evita el cierre del modal
        // Usar una función auxiliar para manejar el evento onclick
        botonEliminar.onclick = createEliminarHandler(espacioDiv, selectedOption, usuariosSeleccionados, optionText);

        usuarioDiv.appendChild(botonEliminar);

        espacioDiv.appendChild(usuarioDiv);
    }

    // Mostrar el array de usuarios seleccionados en la consola
    console.log('Usuarios seleccionados:', usuariosSeleccionados);
}

// Función auxiliar para crear un manejador de evento onclick
function createEliminarHandler(espacioDiv, selectedOption, usuariosSeleccionados, optionText) {
    return function() {
        // Eliminar el div del usuario seleccionado
        espacioDiv.removeChild(this.parentNode);

        // Deseleccionar la opción en el select correspondiente
        selectedOption.selected = false;

        // Eliminar el nombre del usuario del array
        var index = usuariosSeleccionados.indexOf(optionText);
        if (index !== -1) {
            usuariosSeleccionados.splice(index, 1);
        }

        // Mostrar el array de usuarios seleccionados en la consola después de eliminar
        console.log('Usuarios seleccionados después de eliminar:', usuariosSeleccionados);
    };
}


/*function mostrarSeleccionados(tipo) {  
    var selectElement = tipo === 'cliente' ? document.getElementById('id_clientePermisos') : document.getElementById('id_rol_Usuario'); 
    var selectedOptions = selectElement.selectedOptions;
    var espacioDiv = document.getElementById('espacio_para_agregado');
    var usuariosSeleccionados = []; // Array para guardar los nombres de los usuarios seleccionados
    
    // Si el tipo es cliente, permitir seleccionar solo una opción
    if (tipo === 'cliente') {
        // Desmarcar todas las selecciones excepto la última
        for (var i = 0; i < selectedOptions.length - 1; i++) {
            selectedOptions[i].selected = false;
        }
    }
    
    // Se recorre las opciones seleccionadas para agregarlas al div flexible y al array
    for (var i = 0; i < selectedOptions.length; i++) {
        var selectedOption = selectedOptions[i];
        var optionText = selectedOption.textContent;
        usuariosSeleccionados.push(optionText); // Guardar el nombre del usuario en el array
        
        // Crear un nuevo div para el usuario seleccionado
        var usuarioDiv = document.createElement('div');
        usuarioDiv.className = 'usuario-seleccionado'; // Clase CSS para dar estilo
        
        // Contiene el texto del usuario seleccionado 
        var textoSeleccionado = document.createTextNode(optionText);
        usuarioDiv.appendChild(textoSeleccionado);
        
        // Almacenar el nombre del usuario como un atributo de datos en el div
        usuarioDiv.setAttribute('data-nombre', optionText);
        
        var botonEliminar = document.createElement('button');
        botonEliminar.textContent = 'Eliminar';
        botonEliminar.className = 'btn btn-danger btn-sm ml-2';
        botonEliminar.setAttribute('type', 'button'); // Evita el cierre del modal
        botonEliminar.onclick = function() {
            
            var nombreUsuario = this.parentNode.getAttribute('data-nombre');
            // Elimina el div del usuario seleccionado
            espacioDiv.removeChild(this.parentNode);

            // Deseleccionar la opción en el select correspondiente
            selectedOption.selected = false;
            // Eliminar el nombre del usuario del array
            var index = usuariosSeleccionados.indexOf(nombreUsuario);
            if (index !== -1) {
                usuariosSeleccionados.splice(index, 1);
            }
        };
        
        
        usuarioDiv.appendChild(botonEliminar);
        
        espacioDiv.appendChild(usuarioDiv);
    }
    
    // Mostrar el array de usuarios seleccionados en la consola
    console.log('Usuarios seleccionados:', usuariosSeleccionados);
}*/


/*function mostrarSeleccionados(tipo) {  //2 OPCION FUNCION BIEN
    var selectElement = tipo === 'cliente' ? document.getElementById('id_clientePermisos') : document.getElementById('id_rol_Usuario'); 
    var selectedOptions = selectElement.selectedOptions;
    var espacioDiv = document.getElementById('espacio_para_agregado');
    
    // Si el tipo es cliente, permitir seleccionar solo una opción
    if (tipo === 'cliente') {
        // Desmarcar todas las selecciones excepto la última
        for (var i = 0; i < selectedOptions.length - 1; i++) {
            selectedOptions[i].selected = false;
        }
    }
    
   // Se recorre las opciones seleccionadas para agregarlas al div flexible
    for (var i = 0; i < selectedOptions.length; i++) {
        var selectedOption = selectedOptions[i];
        var optionText = selectedOption.textContent;
        var optionValue = selectedOption.value;
        
        // Contiene el texto del usuario seleccionado 
        var textoSeleccionado = document.createTextNode(optionText);
        var elementoP = document.createElement('p');
        elementoP.appendChild(textoSeleccionado);
        
        // Botón para eliminar el usuario seleccionado
        var botonEliminar = document.createElement('button');
        botonEliminar.textContent = 'Eliminar';
        botonEliminar.className = 'btn btn-danger btn-sm ml-2';
        botonEliminar.setAttribute('type', 'button'); // Evita el cierre del modal
        botonEliminar.onclick = function() {
            // Elimina el contenedor que contiene el texto y el botón
            espacioDiv.removeChild(contenedor);
            // Deseleccionar la opción en el select correspondiente
            selectedOption.selected = false;
        };
        
        // contenedor para agrupar los usuarios seleccionados  y el botón eliminar
        var contenedor = document.createElement('div');
        contenedor.appendChild(elementoP);
        contenedor.appendChild(botonEliminar);
        
        // Agregar el contenedor al div flexible
        espacioDiv.appendChild(contenedor);
    }
}*/
	/********************************************************************************************/
	/*function CrearVisibilidadCliente() {
    var clienteSeleccionado = $('#id_clientePermisos').val();
    var usuariosSeleccionados = $('#id_rol_Usuario').val(); // Obtener todos los valores seleccionados directamente

    // Crear un objeto con el cliente y los usuarios seleccionados
    var datosSeleccionados = {
        cliente: {
            id: clienteSeleccionado,
            nombre: $('#id_clientePermisos option:selected').text() // Obtener el nombre del cliente
        },
        usuarios: usuariosSeleccionados // Los IDs de los usuarios seleccionados
    };

    // Mostrar los datos en la consola antes de realizar la solicitud AJAX
    console.log('ID del cliente:', datosSeleccionados.cliente.id);
    console.log('Nombre del cliente:', datosSeleccionados.cliente.nombre);
    console.log('IDs de los usuarios seleccionados:', datosSeleccionados.usuarios);

    // Realizar la solicitud AJAX para obtener los nombres de los usuarios
    $.ajax({
        url: 'Cat_Cliente/usuarios_del_cotenedor',
        type: 'POST',
        data: datosSeleccionados, // Pasar los datos seleccionados como un objeto
        success: function(response) {
            if (response) {
                alert("Usuarios del contenedor:\n" + response);
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}*/

function CrearVisibilidadCliente() {
    var clienteSeleccionado = $('#id_clientePermisos').val();
    var usuariosSeleccionados = $('#id_rol_Usuario').val();

    var datosSeleccionados = {
        cliente: {
            id: clienteSeleccionado,
            nombre: $('#id_clientePermisos option:selected').text()
        },
        usuarios: usuariosSeleccionados
    };

    var usuariosFormateados = [];
    for (var i = 0; i < datosSeleccionados.usuarios.length; i++) {
        var usuarioId = datosSeleccionados.usuarios[i];
        var usuarioNombre = $('#id_rol_Usuario option[value="' + usuarioId + '"]').text();
        usuariosFormateados.push(usuarioId + ' - ' + usuarioNombre);
    }

    console.log('ID del cliente:', datosSeleccionados.cliente.id);
    console.log('Nombre del cliente:', datosSeleccionados.cliente.nombre);
    console.log('IDs de los usuarios seleccionados:', usuariosFormateados.join(', '));

    $.ajax({
        url: 'Cat_Cliente/usuarios_del_cotenedor',
        type: 'POST',
        data: datosSeleccionados,
        success: function(response) {
            if (response) {
                // Cerrar el modal después de guardar los datos
                $('#ModalVisibilidadClientes').modal('hide');

                // Mostrar el mensaje de éxito en un div específico
                $('#mensajeExito').html('Los datos se guardaron correctamente.');

                // Limpiar el div flexible
                $('#espacio_para_agregado').empty();

                // Limpiar el formulario
                $('#formVisibilidadClientes')[0].reset();
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}



	/*function CrearVisibilidadCliente() { //OPCION 2
    var clienteSeleccionado = $('#id_clientePermisos').val();
 
    var usuariosSeleccionados = [];
    $('#id_rol_Usuario option:selected').each(function() {
        usuariosSeleccionados.push($(this).text());
    });

    var datosSeleccionados = {
        cliente: clienteSeleccionado,
        usuarios: usuariosSeleccionados
    };

    // Mostrar el arreglo en la consola
    console.log(datosSeleccionados);

        $.ajax({
        url: 'Cat_Cliente/usuarios_del_cotenedor', 
        type: 'POST', 
        data: {cliente: clienteSeleccionado}, 

        success: function(response) {
            if(response) {
                alert("Usuarios del contenedor:\n" + response);
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
} */

/******************************************************************************************/
	function generarPassword() {
		$.ajax({
			url: '<?php echo base_url('Funciones/generarPassword'); ?>',
			type: 'post',
			beforeSend: function() {
				$('.loader').css("display", "block");
			},
			success: function(res) {
				setTimeout(function() {
					$('.loader').fadeOut();
				}, 200);
				$("#password").val(res)
			}
		});
	}
	function recargarTable() {
		$("#tabla").DataTable().ajax.reload();
	}
</script>