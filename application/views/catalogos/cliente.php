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
var tipos_bloqueo_php =
  '<?php foreach($tipos_bloqueo as $row){ echo '<option value="'.$row->tipo.'">'.$row->descripcion.'</option>';} ?>';
var tipos_desbloqueo_php =
  '<?php foreach($tipos_desbloqueo as $row){ echo '<option value="'.$row->tipo.'">'.$row->descripcion.'</option>';} ?>';
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
    "columns": [{
        title: 'id',
        data: 'id',
        visible: false
      },
      {
        title: 'Nombre',
        data: 'nombre',
        bSortable: false,
        "width": "25%",
        mRender: function(data, type, full) {
          return '<span class="badge badge-pill badge-dark">#' + full.id + '</span><br><b>' + data + '</b>';
        }
      },
      {
        title: 'Clave',
        data: 'clave',
        bSortable: false,
        "width": "3%",
        mRender: function(data, type, full) {
          return '<b>' + data + '</b>';
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


          let eliminar =
            '<a href="javascript:void(0)" data-toggle="tooltip" title="Eliminar usuario" id="eliminar" class="fa-tooltip icono_datatable icono_gris"><i class="fas fa-trash"></i></a> ';
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
        mostrarMensajeConfirmacion('activar cliente', data.nombre, data.id)
      });
      $("a#desactivar", row).bind('click', () => {
        mostrarMensajeConfirmacion('desactivar cliente', data.nombre, data.id)
      });

      $("a#bloquear_cliente", row).bind('click', () => {
        mostrarMensajeConfirmacion('bloquear cliente', data.nombre, data.id)
      });
      $("a#desbloquear_cliente", row).bind('click', () => {
        mostrarMensajeConfirmacion('desbloquear cliente', data.nombre, data.id)
      });
      $("a#eliminar", row).bind('click', () => {
        mostrarMensajeConfirmacion('eliminar cliente', data.nombre, data.id)
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
            setTimeout(function() {
              $('.loader').fadeOut();
            }, 200);
            if (res != 0) {
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
              for (let i = 0; i < dato.length; i++) {
                let privacidad = (dato[i]['privacidad'] > 0) ? 'Nivel ' + dato[i]['privacidad'] :
                  'Sin privacidad';
                let fecha = fechaCompletaAFront(dato[i]['alta']);
                salida += "<tr id='" + dato[i]['idUsuarioCliente'] + "'><th>" + dato[i][
                    'usuario_cliente'
                  ] + "</th><th>" + dato[i]['correo_usuario'] + "</th><th>" + fecha + "</th><th>" +
                  dato[i]['usuario'] + "</th><th>" + privacidad +
                  "</th><th><a href='javascript:void(0)' class='fa-tooltip icono_datatable icono_accion_gris' onclick='mostrarMensajeConfirmacion(\"eliminar usuario cliente\",\"" +
                  dato[i]['usuario_cliente'] + "\"," + dato[i]['idUsuarioCliente'] +
                  ")'><i class='fas fa-trash'></i></a></th></tr>";
              }
              salida += '</tbody>';
              salida += '</table>';
              $("#div_accesos").html(salida);
            } else {
              $('#div_accesos').html(
                '<p style="text-align:center; font-size: 20px;">No hay registro de accesos</p>');
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

function guardarCliente() {
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
      setTimeout(function() {
        $('.loader').fadeOut();
      }, 200);
      var data = JSON.parse(res);
      if (data.codigo === 1) {
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
      } else {
        $("#newModal #msj_error").css('display', 'block').html(data.msg);
      }
    }
  });
}

function mostrarMensajeConfirmacion(accion, valor1, valor2) {
  if (accion == "activar cliente") {
    $('#titulo_mensaje').text('Activar cliente');
    $('#mensaje').html('¿Desea activar al cliente <b>' + valor1 + '</b>?');
    $('#btnConfirmar').attr("onclick", "accionCliente('activar'," + valor2 + ")");
    $('#mensajeModal').modal('show');
  }
  if (accion == "desactivar cliente") {
    $('#titulo_mensaje').text('Desactivar cliente');
    $('#mensaje').html('¿Desea desactivar al cliente <b>' + valor1 + '</b>?');
    $('#btnConfirmar').attr("onclick", "accionCliente('desactivar'," + valor2 + ")");
    $('#mensajeModal').modal('show');
  }
  if (accion == "eliminar cliente") {
    $('#titulo_mensaje').text('Eliminar cliente');
    $('#mensaje').html('¿Desea eliminar al cliente <b>' + valor1 + '</b>?');
    $('#btnConfirmar').attr("onclick", "accionCliente('eliminar'," + valor2 + ")");
    $('#mensajeModal').modal('show');
  }
  if (accion == "eliminar usuario cliente") {
    $('#titulo_mensaje').text('Eliminar usuario');
    $('#mensaje').html('¿Desea eliminar al usuario <b>' + valor1 + '</b>?');
    $('#btnConfirmar').attr("onclick", "controlAcceso('eliminar'," + valor2 + ")");
    $("#accesosClienteModal").modal('hide')
    $('#mensajeModal').modal('show');
  }
  if (accion == "bloquear cliente") {
    $('#titulo_mensaje').text('Bloquear cliente');
    $('#mensaje').html('¿Desea bloquear al cliente <b>' + valor1 + '</b>?');
    $('#mensaje').append(
      '<div class="row mt-3"><div class="col-12"><label>Motivo de bloqueo *</label><select class="form-control" id="opcion_motivo" name="opcion_motivo"><option value="">Selecciona</option>' +
      tipos_bloqueo_php +
      '</select></div></div><div class="row mt-3"><div class="col-12"><label>Mensaje para presentar en panel del cliente *</label><textarea class="form-control" rows="5" id="mensaje_comentario" name="mensaje_comentario">¡Lo sentimos! Su acceso ha sido interrumpido por falta de pago. Favor de comunicarse al teléfono 33 3454 2877.</textarea></div></div>'
    );
    $('#mensaje').append(
      '<div class="row mt-3"><div class="col-12"><label class="container_checkbox">Bloquear también subclientes/proveedores<input type="checkbox" id="bloquear_subclientes" name="bloquear_subclientes"><span class="checkmark"></span></label></div></div>'
    );
    $('#btnConfirmar').attr("onclick", "accionCliente('bloquear'," + valor2 + ")");
    $('#mensajeModal').modal('show');
  }
  if (accion == "desbloquear cliente") {
    $('#titulo_mensaje').text('Desbloquear cliente');
    $('#mensaje').html('¿Desea desbloquear al cliente <b>' + valor1 + '</b>?');
    $('#mensaje').append(
      '<div class="row mt-3"><div class="col-12"><label>Razón de desbloqueo *</label><select class="form-control" id="opcion_motivo" name="opcion_motivo"><option value="">Selecciona</option>' +
      tipos_desbloqueo_php + '</select></div></div>');
    $('#btnConfirmar').attr("onclick", "accionCliente('desbloquear'," + valor2 + ")");
    $('#mensajeModal').modal('show');
  }
}

function accionCliente(accion, id) {
  let opcion_motivo = $('#mensajeModal #opcion_motivo').val()
  let opcion_descripcion = $("#mensajeModal #opcion_motivo option:selected").text();
  let mensaje_comentario = $('#mensajeModal #mensaje_comentario').val()
  let bloquear_subclientes = $("#mensajeModal #bloquear_subclientes").is(":checked") ? 'SI' : 'NO';
  $.ajax({
    url: '<?php echo base_url('Cat_Cliente/status'); ?>',
    type: 'post',
    data: {
      'id': id,
      'accion': accion,
      'opcion_motivo': opcion_motivo,
      'mensaje_comentario': mensaje_comentario,
      'opcion_descripcion': opcion_descripcion,
      'bloquear_subclientes': bloquear_subclientes
    },
    beforeSend: function() {
      $('.loader').css("display", "block");
    },
    success: function(res) {
      setTimeout(function() {
        $('.loader').fadeOut();
      }, 200);
      var data = JSON.parse(res);
      if (data.codigo === 1) {
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

function registrarAccesoCliente() {
  $.ajax({
    url: '<?php echo base_url('Cat_Cliente/getActivos'); ?>',
    type: 'post',
    beforeSend: function() {
      $('.loader').css("display", "block");
    },
    success: function(res) {
      setTimeout(function() {
        $('.loader').fadeOut();
      }, 200);
      if (res != 0) {
        $('#id_cliente').empty();
        var dato = JSON.parse(res);
        $('#id_cliente').append('<option value="">Selecciona</option>');
        for (let i = 0; i < dato.length; i++) {
          $('#id_cliente').append('<option value="' + dato[i]['id'] + '">' + dato[i]['nombre'] + '</option>');
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
      setTimeout(function() {
        $('.loader').fadeOut();
      }, 200);
      var data = JSON.parse(res);
      if (data.codigo === 1) {
        $("#nuevoAccesoClienteModal").modal('hide')
        recargarTable()
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Usuario guardado correctamente',
          showConfirmButton: false,
          timer: 2500
        })
      } else {
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
      setTimeout(function() {
        $('.loader').fadeOut();
      }, 200);
      var data = JSON.parse(res);
      if (data.codigo === 1) {
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

/**************Función para mostrar el modal y cargar los datos*****LLAMADO DEL BOTON "DAR VISIBILIDAD DE LOS CLIENTES A LOS USUARIOS INTERNOS"***********************/
function BotonVisibilidadCliente() {

$('#btnGuardar').on('click', function() {
  // Verificar si se ha seleccionado un cliente
  if ($('#id_clientePermisos').val() === '') {
    $('#errorModal').html('Por favor, seleccione un cliente.').show();
    return;
  }

  if ($('#espacio_para_agregado .usuario-seleccionado').length === 0) {
    $('#errorModal').html('Por favor, seleccione al menos un usuario.').show();
    return;
  }

  $('#errorModal').hide(); // Ocultar el mensaje de error si no hay problemas

  var toggleSwitchValue = $('#toggleSwitch').prop('checked') ? 1 : 0;
  var antidopingValue = $('#seleccion_antidoping').val();
  $.ajax({
    url: '<?php echo base_url('Cat_Cliente/boton_Guardar_1'); ?>',
    type: 'post',
    data: {
      id_clientePermisos: $('#id_clientePermisos').val(),
      usuarios_seleccionados: $('#espacio_para_agregado .usuario-seleccionado').map(function() {
        return $(this).attr('data-valor');
      }).get(),
      antidoping_seleccionado: antidopingValue,
      togglePsicometria: toggleSwitchValue, // Valor del switch
    },
    success: function() {
      Swal.fire({
        icon: 'success',
        title: 'Éxito',
        text: 'Los datos se guardaron correctamente.',
        timer: 1800, // Duración del mini modal en milisegundos
        timerProgressBar: true, // Muestra una barra de progreso
      });

      setTimeout(function() {
        $('#ModalVisibilidadClientes').modal('hide');
        $('#mensajeExito').hide().html('');
        $('#id_clientePermisos').val('');
        $('#id_rol_Usuario').val('');
        $('#espacio_para_agregado').empty();
        $('#toggleSwitch').prop('checked', true); // Restablecer el estado del switch
        $('#seleccion_antidoping').val(''); // Limpiar el select del antidoping
      }, 1800);
    },
    error: function(jqXHR, textStatus, errorThrown) {
      // En caso de error, muestra un mensaje de fallo
      $('#errorModal').html('Ha ocurrido un error al intentar guardar los datos.').show();
    }
  });
});
//Se limpiaran los datos de los select seleccionados si se da en el boton cerrar del modal  sin guardar
$('#btnCerrar').on('click', function() {
  $('#id_clientePermisos').val(''); // Limpiar select del cliente
  $('#espacio_para_agregado').empty(); // Limpiar div de usuarios seleccionados
  $('#errorModal').hide(); // Ocultar el mensaje de error si no hay problemas
});

$.ajax({
  url: '<?php echo base_url('Cat_Cliente/get_Visibilidad'); ?>',
  type: 'get',
  beforeSend: function() {
    $('.loader').css("display", "block");
  },
  success: function(res) {
    setTimeout(function() {
      $('.loader').fadeOut();
    }, 200);
    var data = JSON.parse(res);
    if (data.codigo === 1) {
      recargarTable();
      $("#mensajeModal").modal('hide');
      Swal.fire({
        position: 'center',
        icon: 'success',
        title: data.msg,
        showConfirmButton: false,
        timer: 2500
      });
    }

    if (res) {
      $('#id_clientePermisos').empty();
      $('#id_rol_Usuario').empty();
      var datos = JSON.parse(res);
      $('#id_clientePermisos').append('<option value="">Selecciona</option>');
      $('#id_rol_Usuario').append('<option value="">Selecciona</option>');

      for (var i = 0; i < datos.clientes.length; i++) {
        $('#id_clientePermisos').append('<option value="' + datos.clientes[i]['cliente_id'] + '">' + datos
          .clientes[i]['cliente_nombre'] + '</option>');
      }

      for (var i = 0; i < datos.usuarios.length; i++) {
        $('#id_rol_Usuario').append('<option value="' + datos.usuarios[i]['usuario_id'] + '">' + datos
          .usuarios[i]['usuario_nombre'] + ' ' + datos.usuarios[i]['usuario_paterno'] + '</option>');
      }
      $('#errorModal').hide().html('');

      
      $('#ModalVisibilidadClientes').modal('show');
    }
  }
});
}

/*function BotonVisibilidadCliente() {

$('#btnGuardar').on('click', function() {
 
  if ($('#id_clientePermisos').val() === '') {
    $('#errorModal').html('Por favor, seleccione un cliente.').show();
    return;
  }

  if ($('#espacio_para_agregado .usuario-seleccionado').length === 0) {
    $('#errorModal').html('Por favor, seleccione al menos un usuario.').show();
    return;
  }

  var toggleSwitchValue = $('#toggleSwitch').prop('checked') ? 1 : 0;
  var antidopingValue = $('#seleccion_antidoping').val();
  $.ajax({
    url: '< ?php echo base_url('Cat_Cliente/boton_Guardar_1'); ?>',
    type: 'post',
    data: {
      id_clientePermisos: $('#id_clientePermisos').val(),
      usuarios_seleccionados: $('#espacio_para_agregado .usuario-seleccionado').map(function() {
        return $(this).attr('data-valor');
      }).get(),
      antidoping_seleccionado: antidopingValue,
      togglePsicometria: toggleSwitchValue, // Valor del switch
    },
    success: function() {
      Swal.fire({
        icon: 'success',
        title: 'Éxito',
        text: 'Los datos se guardaron correctamente.',
        timer: 1800, // Duración del mini modal en milisegundos
        timerProgressBar: true, // Muestra una barra de progreso
      });

      setTimeout(function() {
        $('#ModalVisibilidadClientes').modal('hide');
        $('#mensajeExito').hide().html('');
        $('#id_clientePermisos').val('');
        $('#id_rol_Usuario').val('');
        $('#espacio_para_agregado').empty();
        $('#toggleSwitch').prop('checked', true); // Restablecer el estado del switch
        $('#seleccion_antidoping').val(''); // Limpiar el select del antidoping
      }, 2000);
    },
    error: function(jqXHR, textStatus, errorThrown) {
      // En caso de error, muestra un mensaje de fallo
      $('#errorModal').html('Ha ocurrido un error al intentar guardar los datos.').show();
    }
  });
});

$('#btnCerrar').on('click', function() {
  $('#id_clientePermisos').val(''); // Limpiar select del cliente
  $('#espacio_para_agregado').empty(); // Limpiar div de usuarios seleccionados
});

$.ajax({
  url: '< ?php echo base_url('Cat_Cliente/get_Visibilidad'); ?>',
  type: 'get',
  beforeSend: function() {
    $('.loader').css("display", "block");
  },
  success: function(res) {
    setTimeout(function() {
      $('.loader').fadeOut();
    }, 200);
    var data = JSON.parse(res);
    if (data.codigo === 1) {
      recargarTable();
      $("#mensajeModal").modal('hide');
      Swal.fire({
        position: 'center',
        icon: 'success',
        title: data.msg,
        showConfirmButton: false,
        timer: 2500
      });
    }

    if (res) {
      $('#id_clientePermisos').empty();
      $('#id_rol_Usuario').empty();
      var datos = JSON.parse(res);
      $('#id_clientePermisos').append('<option value="">Selecciona</option>');
      $('#id_rol_Usuario').append('<option value="">Selecciona</option>');

      for (var i = 0; i < datos.clientes.length; i++) {
        $('#id_clientePermisos').append('<option value="' + datos.clientes[i]['cliente_id'] + '">' + datos
          .clientes[i]['cliente_nombre'] + '</option>');
      }

      for (var i = 0; i < datos.usuarios.length; i++) {
        $('#id_rol_Usuario').append('<option value="' + datos.usuarios[i]['usuario_id'] + '">' + datos
          .usuarios[i]['usuario_nombre'] + ' ' + datos.usuarios[i]['usuario_paterno'] + '</option>');
      }
      $('#errorModal').hide().html('');

      $('#ModalVisibilidadClientes').modal('show');
    }
  }
});
}*/

/*****************Función para mostrar los clientes o usuarios seleccionados en el div flexible del modal***********/
function mostrarSeleccionados(tipo) {
  var selectElement = tipo === 'cliente' ? document.getElementById('id_clientePermisos') : document.getElementById('id_rol_Usuario');
  var selectedOptions = selectElement.selectedOptions;
  var espacioDiv = document.getElementById('espacio_para_agregado');
  var usuariosSeleccionados = [];
  var clienteSeleccionado = $('#id_clientePermisos').val();
  console.log('ID del cliente seleccionado:', clienteSeleccionado);

  // Recoger los usuarios ya seleccionados en el espacioDiv
  var usuariosDiv = espacioDiv.querySelectorAll('.usuario-seleccionado');
  usuariosDiv.forEach(function(usuarioDiv) {
    usuariosSeleccionados.push(usuarioDiv.getAttribute('data-valor'));
  });

  if (tipo === 'cliente') {
    for (var i = 0; i < selectedOptions.length - 1; i++) {
      selectedOptions[i].selected = false;
    }
  }

  for (var i = 0; i < selectedOptions.length; i++) {
    var selectedOption = selectedOptions[i];
    var optionValue = selectedOption.value;

    // Verificar si el usuario ya ha sido seleccionado
    if (usuariosSeleccionados.includes(optionValue)) {
      alert('Este usuario ya ha sido seleccionado.');
      continue; // Saltar al siguiente usuario
    }

    usuariosSeleccionados.push(optionValue);

    var usuarioDiv = document.createElement('div');
    usuarioDiv.className = 'usuario-seleccionado';
    usuarioDiv.setAttribute('data-valor', optionValue);

    var textoSeleccionado = document.createTextNode(selectedOption.textContent);
    usuarioDiv.appendChild(textoSeleccionado);

    espacioDiv.appendChild(usuarioDiv);

    var botonEliminar = document.createElement('button');
    botonEliminar.textContent = 'Eliminar';
    botonEliminar.className = 'btn btn-danger btn-sm ml-2';
    botonEliminar.onclick = createEliminarHandler(espacioDiv, usuarioDiv, usuariosSeleccionados, optionValue);

    usuarioDiv.appendChild(botonEliminar);
  }

  console.log('IDs de los usuarios seleccionados:', usuariosSeleccionados);
}

function createEliminarHandler(espacioDiv, selectedOption, usuariosSeleccionados, optionText) {
  return function() {
    // Eliminar el div del usuario seleccionado también al ser eliminado
    espacioDiv.removeChild(this.parentNode);

    selectedOption.selected = false;

    // Eliminar el valor del usuario del array cuando sea eliminado de la selección
    var index = usuariosSeleccionados.indexOf(optionText);
    if (index !== -1) {
      usuariosSeleccionados.splice(index, 1);
    }

    console.log('Usuarios seleccionados después de eliminar:', usuariosSeleccionados);
  };
}
/******************************Función para cargar los datos del select de paquete antidoping************************************************************************/
$(function() {
  function cargarPaqueteAntidoping() {
    $.ajax({
      url: 'select_antidoping',
      type: 'GET',
      success: function(response) {
        try {
          var paquetes = JSON.parse(response);
          $('#seleccion_antidoping').empty().append('<option value="0">N/A</option>');
          $.each(paquetes, function(index, paquete) {
            $('#seleccion_antidoping').append('<option value="' + paquete.id + '">' + paquete.nombre +
              ' - ' + paquete.conjunto + '</option>');
          });
        } catch (error) {
          console.error('Error al procesar la respuesta JSON:', error);
        }
      },
      error: function(xhr, status, error) {
        console.error('Error en la solicitud AJAX:', error);
      }
    });
  }

  cargarPaqueteAntidoping();

  $('#seleccion_antidoping').prop('disabled', false);
  //checkbox
  $('#toggleAntidoping').change(function() {
    if ($(this).is(':checked')) {
      $('#seleccion_antidoping').prop('disabled', true);
      // Limpiar los datos seleccionados
      $('#seleccion_antidoping').val('');
    } else {
      $('#seleccion_antidoping').prop('disabled', false);
    }
  });

  $('#seleccion_antidoping').change(function() {
    var opcionSeleccionada = $(this).val();
    console.log('ID del último paquete antidoping seleccionado:', opcionSeleccionada);
  });
});

/*********************************SECCION DE PSCICOMETRIAS***************************************************************/
$(document).ready(function() {

  $('label[for="toggleSwitch"]').click(function() {
    var estado = $(this).text().trim() === "SI" ? 1 :
    0; // Si el texto del label es "SI", estado es 1, de lo contrario 0
    $('#toggleSwitch').prop('checked', estado); // Actualizar el estado del switch
  });

  $('#seleccion_antidoping').change(function() {
    var opcionSeleccionada = $(this).val();
    console.log('ID del último paquete antidoping seleccionado:', opcionSeleccionada);
  });
});
/*******************************************************************************************************/
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