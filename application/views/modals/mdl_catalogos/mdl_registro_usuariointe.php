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
        <ul class="nav nav-tabs" id="myTabs">
          <li class="nav-item">
            <a class="nav-link active" id="tabNuevo" data-toggle="tab" href="#sectionNuevo">Nuevo</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="tabEditar" data-toggle="tab" href="#sectionEditar">Editar</a>
          </li>
        </ul>

      <div class="tab-content">
          <!--***Pestaña Nuevo*****-->
       <div class="tab-pane fade show active" id="sectionNuevo">
          <div class="modal-body">
            <form id="formAccesoUsuariosinternos">
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
              <label>Apellido Materno </label>
              <input type="text" class="form-control" name="materno" id="materno" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
              <br>
            </div>
          </div> 
          <div class="row">
            <div class="col-12">
              <label>Tipo de rol *</label>
              <select class="form-control" id="id_rol" name="id_rol">
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
         </div>
            <div class="row">
               <div class="col-md-12">
                 <p>* Copia la contraseña para enviarla al cliente, ya que al no hacerlo se tendrá que generar una nueva</p>
                </div>
             </div>
        </div>

         <!--**Pestaña Editar**-->
        <div  class="tab-pane fade" id="sectionEditar">
          <div class="row">
             <br>
              <div class="col-md-12">
              <br>
              <label>Usuarios internos*</label>
              <select name="nombre" id="nombre" class="form-control"></select>
              <br>
              </div>
              <br>
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
              <label>Apellido Materno </label>
              <input type="text" class="form-control" name="materno" id="materno" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
              <br>
            </div>
           
            <div class="col-md-12">
              <label>Correo *</label>
              <input type="text" class="form-control" name="correo" id="correo">
              <br>
            </div>
           
          </div> 
           <div class="row">
            <div class="col-12">
              <label>Tipo de rol *</label>
              <select class="form-control" id="id_rol" name="id_rol">
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
        </div>

       <!-- <div class="row">
          <div class="col-md-12">
            <p>* Copia la contraseña para enviarla al cliente, ya que al no hacerlo se tendrá que generar una nueva</p>
          </div>
        </div> -->
        <div id="msj_error" class="alert alert-danger hidden"></div>
    </div> <!--/**cierre de tab-content***/ -->

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-success" onclick="crearAcceso()">Guardar</button>
      </div>
    </div>
  </div>
</div>

<script>
  $("#newModal").on("hidden.bs.modal", function() {
    $("#newModal input").val("");
    $("#newModal #msj_error").css('display', 'none');
    $("#titulo_nuevo_modal").text("Nuevo Usuario");
  }); 
  $("#accesoModal").on("hidden.bs.modal", function() {
    $("#accesoModal input, #accesoModal select").val("");
    $("#accesoModal #id_rol").val(0);
    $("#accesoModal input").removeClass("requerido");
    $("#accesoModal #msj_error").css('display', 'none');
    $("#idCliente").val("");
  });
</script>


  