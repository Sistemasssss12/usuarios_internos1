<!-- Begin Page Content -->
<div class="container-fluid">


	<!-- Page Heading -->
	<div class="align-items-center mb-4">
		<div class="row">
			<div class="col-sm-12 col-md-2">
				<a href="#" class="btn btn-primary btn-icon-split" onclick="Accesousuariointerno()">
					<span class="icon text-white-50">
            <i class="fas fa-sign-in-alt"></i>
          </span>
					<span class="text">Registro de usuarios internos</span>
				</a>
			</div>
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
	<input type="hidden" id="idusuario">
	<input type="hidden" id="idUsuarioCliente">


</div>
<!-- /.content-wrapper -->
<script>
	var url = '<?php echo base_url('Cat_UsuarioInternos/get'); ?>';
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
					"width": "20%",
          mRender: function(data, type, full){
            return '<span class="badge badge-pill badge-dark">#' + full.id + '</span><br><b>'+data+'</b>';
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
					"width": "10%",
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
            let editar = '<a id="editar" href="javascript:void(0)" data-toggle="tooltip" title="Editar" class="fa-tooltip icono_datatable icono_azul_oscuro"><i class="fas fa-edit"></i></a> ';
            let eliminar = '<a href="javascript:void(0)" data-toggle="tooltip" title="Eliminar Usuario" id="eliminar" class="fa-tooltip icono_datatable icono_gris"><i class="fas fa-trash"></i></a> ';
            let acceso = '<a href="javascript:void(0)" data-toggle="tooltip" title="Ver accesos" id="acceso" class="fa-tooltip icono_datatable icono_azul_claro"><i class="fas fa-sign-in-alt"></i></a>';

            let accion = (full.status == 0)? '<a href="javascript:void(0)" data-toggle="tooltip" title="Activar" id="activar" class="fa-tooltip icono_datatable icono_rojo"><i class="fas fa-ban"></i></a> ' : '<a href="javascript:void(0)" data-toggle="tooltip" title="Desactivar" id="desactivar" class="fa-tooltip icono_datatable icono_verde"><i class="far fa-check-circle"></i></a> ';
            
            let bloqueo = (full.bloqueado === 'NO')? ' <a href="javascript:void(0)" data-toggle="tooltip" title="Bloquear cliente" id="bloquear_cliente" class="fa-tooltip icono_datatable icono_verde"><i class="fas fa-user-check"></i></a> ' : ' <a href="javascript:void(0)" data-toggle="tooltip" title="Desbloquear cliente" id="desbloquear_cliente" class="fa-tooltip icono_datatable icono_rojo"><i class="fas fa-user-lock"></i></a> ';
						
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
						url: '<?php echo base_url('Cat_UsuarioInternos/getClientesAccesos'); ?>',
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
  /*function guardarCliente(){
    let datos = $('#formCatCliente').serialize();
    datos += '&id=' + $("#idCliente").val();
    $.ajax({
      url: '<-?php echo base_url('Cat_Cliente/set'); ?>',
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
  }*/
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
			url: '<?php echo base_url('Cat_UsuarioInternos/status'); ?>',
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
  /*function registrarAccesoCliente(){
    $.ajax({
			url: '< !?php echo base_url('Cat_Cliente/getActivos'); ?>', QUITAR EL SIGNO !
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
  }*/ //---QUITAR DESPUES
	function Accesousuariointerno(){
    $.ajax({
			url: '<?php echo base_url('Cat_UsuarioInternos/getActivos'); ?>',
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
          $('#nuevoAccesoUsuariosInternos').modal('show');
        }
			}
		});
  }
  function crearAcceso() {
    let datos = $('#formAccesoUsuariosinternos').serialize();
    $.ajax({
      url: '<?php echo base_url('Cat_UsuarioInternos/addUsuarioInterno'); ?>',
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
          $("#nuevoAccesoUsuariosInternos").modal('hide')
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
          $("#nuevoAccesoUsuariosInternos #msj_error").css('display', 'block').html(data.msg);
        }
      }
    });
  }
	function controlAcceso(accion, idUsuarioCliente) {
		$("tr#" + idUsuarioCliente).hide();
		$.ajax({
			url: '<?php echo base_url('Cat_UsuarioInternos/controlAcceso'); ?>',
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
		
		 debug: true
	}
</script>