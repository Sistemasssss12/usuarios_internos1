<!-- Begin Page Content -->
<div class="container-fluid">


  <!-- Page Heading -->
  <div class="align-items-center mb-4">
    <div class="row">
      <div class="col-sm-12 col-md-2">
        <a href="#" class="btn btn-primary btn-icon-split" onclick="BotonRegistroUsuarioInterno()">
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
</div>
<!-- /.content-wrapper -->
<script>
var url = '<?php echo base_url('Cat_UsuarioInternos/get'); ?>';
$(document).ready(function() {
  $('[data-toggle="tooltip"]').tooltip();
  $('#newModal').on('shown.bs.modal', function() {
    $(this).find('input[type=text],select,textarea').filter(':visible:first').focus();
  });

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
        title: 'Nombre de usuario',
        data: 'nombre',
        bSortable: false,
        "width": "20%",
        mRender: function(data, type, full) {
          return '<span class="badge badge-pill badge-dark">' + '</span><br><b>' + data + '</b>';
        }
      },
      {
        title: 'Apellido Paterno',
        data: 'paterno',
        bSortable: false,
        "width": "20%",
        mRender: function(data, type, full) {
          return '<span class="badge badge-pill badge-dark">' + '</span><br><b>' + data + '</b>';
        }
      },
      {
        title: 'Correo',
        data: 'correo',
        bSortable: false,
        "width": "15%",
        mRender: function(data, type, full) {
          return '<span class="badge badge-pill badge-dark">' + '</span><br><b>' + data + '</b>';
        }
      },
      {
        title: 'Tipo de rol',
        data: 'nombre_rol',
        bSortable: false,
        "width": "3%",
        mRender: function(data, type, full) {
          return '<b>' + data + '</b>';
        }
      },
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
        title: 'Fecha de edición',
        data: 'edicion',
        bSortable: false,
        "width": "8%",
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
        title: 'Acciones',
        data: 'id',
        bSortable: false,
        "width": "10%",
        mRender: function(data, type, full) {

          let editar =
            '<a id="editar" href="javascript:void(0)" data-toggle="tooltip" title="Editar Usuario" class="fa-tooltip icono_datatable icono_azul_oscuro"><i class="fas fa-edit"></i></a> ';

          let eliminar =
            '<a href="javascript:void(0)" data-toggle="tooltip" title="Eliminar Usuario" id="eliminar" class="fa-tooltip icono_datatable icono_rojo"><i class="fas fa-trash"></i></a> ';


          return editar + eliminar;
        }
      }
    ],

    fnDrawCallback: function(oSettings) {
      $('a[data-toggle="tooltip"]').tooltip({
        trigger: "hover"
      });
    },

    /*****Devuelve los valores registrados para editarlos DESDE EL BOTON EDITAR**************/

    rowCallback: function(row, data) {
      $("a#editar", row).bind('click', () => {
        editarUsuario(data.id, data.nombre, data.paterno, data.id_rol, data.correo);
        $("#idusuario").val(data.id,);
        $("#titulo_nuevo_modal").text("Editar Usuario");
        $("#nombre").val(data.nombre);
        $("#paterno").val(data.paterno);
        $("#id_rol").val(data.id_rol);
        $("#correo").val(data.correo);
        $("#nuevoAccesoUsuariosInternos").modal("show");
        

      });
        
     

      $("a#eliminar", row).bind('click', () => {
        mostrarMensajeConfirmacion('eliminar usuario', data.nombre, data.id);
      });
    },

    /****************************************************************/
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

  function editarUsuario(id, nombre, paterno, id_rol, correo) {
    $("#idusuario").val(id);
    $("#titulo_nuevo_modal").text("Editar Usuario");
    $("#nombre").val(nombre);
    $("#paterno").val(paterno);
    $("#id_rol").val(id_rol);
    $("#correo").val(correo);
    $("#nuevoAccesoUsuariosInternos").modal("show");
  }
});
/****************************FUNCION*******EDITAR******************************* */

function mostrarMensajeConfirmacion(accion, valor1, valor2) {

  if (accion == "eliminar usuario") {
    $('#titulo_mensaje').text('Eliminar usuario');
    $('#mensaje').html('¿Desea eliminar el usuario <b>' + valor1 + '</b>?');
    $('#btnConfirmar').attr("onclick", "accionCliente('eliminar'," + valor2 + ")");
    $('#mensajeModal').modal('show');
  }
  /*if(accion == "eliminar usuario cliente"){
			$('#titulo_mensaje').text('Eliminar usuario');
			$('#mensaje').html('¿Desea eliminar al usuario <b>'+valor1+'</b>?');
			$('#btnConfirmar').attr("onclick","controlAcceso('eliminar',"+valor2+")");
      $("#accesosClienteModal").modal('hide')
			$('#mensajeModal').modal('show');
		} */

}

function accionCliente(accion, id) {
    let opcion_motivo = $('#mensajeModal #opcion_motivo').val()
    let opcion_descripcion = $( "#mensajeModal #opcion_motivo option:selected" ).text();
    //let mensaje_comentario = $('#mensajeModal #mensaje_comentario').val()
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

/*--------------LLAMADO DEL BOTON REGISTRO USUARIO INTERNOS----------------------------*/
function BotonRegistroUsuarioInterno() {
  $.ajax({
    url: '<?php echo base_url('Cat_UsuarioInternos/getActivos');?>',
    type: 'post',
    beforeSend: function() {
      $('.loader').css("display", "block");
    },
    success: function(res) {
      setTimeout(function() {
        $('.loader').fadeOut();
      }, 200);

      /*if(res != 0){
        $('#nombre').empty();
        var dato = JSON.parse(res);
        $('#nombre').append('<option value="">Selecciona</option>');
        for(let i = 0; i < dato.length; i++){
          $('#nombre').append('<option value="'+dato[i]['id']+'">'+dato[i]['nombre']+' '+dato[i]['paterno']+'</option>');
        }*/ //EN SU MOMENTO SE UTILIZÓ PARA LLAMAR AL SELECT
      $('#nuevoAccesoUsuariosInternos').modal('show');
      //} 
    }
  });
}
/*--------------LLAMADO DEL ONCLIK DEL BOTON GUARDAR DEL REGISTRO DEL FORMULARIO----------------------------*/
function registroUsuariosInternos() {
  let datos = $('#formAccesoUsuariosinternos').serialize();
  $.ajax({
    url: '<?php echo base_url('Cat_UsuarioInternos/addUsuarioInterno'); ?>',
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
        $("#nuevoAccesoUsuariosInternos").modal('hide')
        recargarTable()
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Usuario guardado correctamente',
          showConfirmButton: false,
          timer: 2500
        })

        $('#formAccesoUsuariosinternos')[0]
      .reset(); //se limpian nnuevamente los campos de registro después de guardar
      } else {
        $("#nuevoAccesoUsuariosInternos #msj_error").css('display', 'block').html(data.msg);
      }
    }
  });
}

/*----------------------------------------------------------*/
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