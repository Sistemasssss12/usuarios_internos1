<div class="modal fade" id="nuevoAccesoUsuariosInternos" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Registro de usuarios internos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formAccesoUsuariosinternos">
          <div class="row">
            <div class="col-md-12">
              <label>Usuarios internos*</label>
              <select name="id_Usuario" id="id_cliente" class="form-control"></select>
              <br>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <label>Nombre *</label>
              <input type="text" class="form-control" name="nombre" id="nombre" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
              <br>
            </div>
            <div class="col-md-4">
              <label>Apellido Paterno *</label>
              <input type="text" class="form-control" name="paterno" id="paterno" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
              <br>
            </div>
            <div class="col-md-4">
              <label>Apellido Materno *</label>
              <input type="text" class="form-control" name="materno" id="materno" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
              <br>
            </div>
          </div> 
          <div class="row">
            <div class="col-12">
              <label>Tipo de rol *</label>
              <select class="form-control" id="tipo_rol" name="tipo_rol">
                <option value="1">Administrador</option>
                <option value="2">Analista</option>
                <option value="4">Reclutadora</option>
                <option value="6">Gerente</option>
                <option value="9">Lider de proyecto </option>
                <option value="10">Recursos Humanos</option>
                <option value="11">Coordinadora de reclutamiento </option>
              </select>
              <br>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <label>Correo *</label>
              <input type="text" class="form-control" name="correo" id="correo">
              <br>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <label>Da click</label>
              <button type="button" class="btn btn-primary" onclick="generarPassword()">Generar contraseña</button>
              <br>
            </div>
            <div class="col-md-6">
              <label>Contraseña *</label>
              <input type="text" class="form-control" name="password" id="password" maxlength="8" readonly>
              <br>
            </div>
          </div>
        </form>
        <div class="row">
          <div class="col-md-12">
            <p>* Copia la contraseña para enviarla al cliente, ya que al no hacerlo se tendrá que generar una nueva</p>
          </div>
        </div>
        <div id="msj_error" class="alert alert-danger hidden"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-success" onclick="crearAcceso()">Guardar</button>
      </div>
    </div>
  </div>
</div>
<!--<div class="modal fade" id="bloquearClienteModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="titulo_mensaje"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4 id="mensaje"></h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success" id="btnConfirmar">Confirmar</button>
      </div>
    </div>
  </div>
</div> ---->
<script>
  /*$("#newModal").on("hidden.bs.modal", function() {
    $("#newModal input").val("");
    $("#newModal #msj_error").css('display', 'none');
    $("#titulo_nuevo_modal").text("Nuevo cliente");
  }); */
  $("#accesoModal").on("hidden.bs.modal", function() {
    $("#accesoModal input, #accesoModal select").val("");
    $("#accesoModal #tipo_rol").val(0);
    $("#accesoModal input").removeClass("requerido");
    $("#accesoModal #msj_error").css('display', 'none');
    $("#idCliente").val("");
  });
  $("#accesosClienteModal").on("hidden.bs.modal", function() {
    $("#accesosClienteModal #div_accesos").empty();
  });
</script>