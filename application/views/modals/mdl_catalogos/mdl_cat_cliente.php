<div class="modal fade" id="newModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="titulo_nuevo_modal">Nuevo cliente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formCatCliente">
          <div class="row">
            <div class="col-md-12">
              <label>Nombre del cliente *</label>
              <input type="text" class="form-control" name="nombre" id="nombre"
                onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
              <br>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <label>Clave *</label>
              <input type="text" class="form-control" name="clave" id="clave" maxlength="3"
                onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
              <br>
            </div>
          </div>
        </form>
        <div id="msj_error" class="alert alert-danger hidden"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-success" onclick="guardarCliente()">Guardar</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="mensajeModal" role="dialog" data-backdrop="static" data-keyboard="false">
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
</div>
<div class="modal fade" id="mensajePrueba" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="">probando modal</h4>
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
</div>
<div class="modal fade" id="accesosClienteModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Cliente : <span class="nombreCliente"></span></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="div_accesos"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="nuevoAccesoClienteModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Registro de credenciales del cliente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formAccesoCliente">
          <div class="row">
            <div class="col-md-12">
              <label>Cliente *</label>
              <select name="id_cliente" id="id_cliente" class="form-control"></select>
              <br>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <label>Nombre *</label>
              <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente"
                onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
              <br>
            </div>
            <div class="col-md-4">
              <label>Primer apellido *</label>
              <input type="text" class="form-control" name="paterno_cliente" id="paterno_cliente"
                onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
              <br>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <label>Privacidad de visualizar candidatos *</label>
              <select class="form-control" id="privacidad" name="privacidad">
                <option value="0">Sin privacidad (Visible para usuarios/clientes sin privacidad y Nivel 1)</option>
                <option value="1">Nivel 1 (Visibilidad total de los candidatos)</option>
                <option value="2">Nivel 2 (Visibilidad de candidatos para Nivel 2 y 1) </option>
                <option value="3">Nivel 3 (Visibilidad de candidatos para Nivel 3 y 1)</option>
                <option value="4">Nivel 4 (Visibilidad de candidatos para Nivel 4 y 1)</option>
                <option value="5">Nivel 5 (Visibilidad de candidatos para Nivel 5 y 1)</option>
                <option value="6">Nivel 6 (Visibilidad de candidatos para Nivel 6 y 1)</option>
                <option value="7">Nivel 7 (Visibilidad de candidatos para Nivel 7 y 1)</option>
                <option value="8">Nivel 8 (Visibilidad de candidatos para Nivel 8 y 1)</option>
                <option value="9">Nivel 9 (Visibilidad de candidatos para Nivel 9 y 1)</option>
                <option value="10">Nivel 10 (Visibilidad de candidatos para Nivel 10 y 1)</option>
              </select>
              <br>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <label>Correo *</label>
              <input type="text" class="form-control" name="correo_cliente" id="correo_cliente">
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

<div class="modal fade" id="bloquearClienteModal" role="dialog" data-backdrop="static" data-keyboard="false">
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
</div>

<div class="modal fade" id="ModalVisibilidadClientes" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Selección de clientes y usuarios internos</h5>
      </div>
      <div class="modal-body">

        <form id="formVisibilidadClientes">

          <!-- <div class="row">
            <div class="col-md-12">
              <label>Cliente *</label>
              <div class="search-container">
                <div>
                  <input type="text" id="cliente-search" class="form-control" placeholder="Buscar cliente...">
                  <button type="button" class="form-control" id="search-button">
                    <span class="search-icon">&#128269;</span>
                  </button>
                </div>
                <br>
                <select name="id_clientePermisos" id="id_clientePermisos" class="form-control"></select>
              </div>
              <br>
            </div>
          </div>-->
          <div class="row">
            <div class="col-md-12">
              <label for="cliente-search">Cliente *</label>
              <div class="input-group">
                <input type="text" id="cliente-search" class="form-control" placeholder="Buscar cliente...">
                <div class="input-group-append">
                  <button type="button" class="btn btn-primary btn-md" id="search-button">
                    <span class="search-icon">&#128269;</span>
                  </button>
                </div>
              </div>
              <div>ó</div>
              <select name="id_clientePermisos" id="id_clientePermisos" class="form-control mt-3"></select>
            </div>
          </div>
           <br>
          <div class="row">
            <div class="col-12">
              <label>Selecciona quien podrá visualizar al cliente seleccionado *</label>
              <select id="id_rol_Usuario" name="id_rol_Usuario[]" class="form-control"
                onchange="mostrarSeleccionados('usuario')"></select>
              <br>
            </div>
          </div>
          <!-- Div flexible para mostrar los clientes y usuarios seleccionados -->
          <div class="row">
            <div id="espacio_para_agregado" class="col-12 d-flex flex-column mb-3">
            </div>
          </div>

          <div class="text-center">
            <table class="table table-striped">
              <thead>
              <tbody>
                <tr>
                  <th class="text-center">¿Agregar paquete de doping?</th>
                </tr>
              </tbody>
              </thead>
            </table>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div>
              </div>

              <div>
                <label>En caso de sí, elija el paquete de antidoping</label>
                <select name="paquete_antidoping" id="seleccion_antidoping" class="form-control"></select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div>
                <br>
              </div>

              <div>
                <label>Subcliente</label>
                <select name="subcliente" id="subcliente" class="form-control"></select>
              </div>
            </div>
            <div class="col-md-6">
              <div>
                <br>
              </div>

              <div>
                <label>Proyecto</label>
                <select name="proyecto" id="proyecto" class="form-control"></select>
              </div>
            </div>
          </div>
          <div>
            <br>
          </div>

          <div class="text-center">
            <table class="table table-striped">
              <thead>
              <tbody>
                <tr>
                  <th class="text-center">¿ Agregar psicométrias ?</th>
                </tr>
              </tbody>
              </thead>
            </table>
          </div>

          <div class="row">
            <div class="col-md-12">
              <label for="toggleSwitch">Seleccionar:</label>
              <div class="form-check form-check-inline">
                <input type="checkbox" id="toggleSwitch" class="form-check-input" data-toggle="switch">
                <label class="form-check-label" for="toggleSwitch"><strong>SI</strong></label>
              </div>
            </div>
          </div>
        </form>

        <div id="mensajeExito" class="alert alert-success" style="display: none;"></div>

        <div id="mensaje" class="alert alert-info" style="display: none;"></div>

        <div id="msj_error" class="alert alert-danger hidden"></div>

        <div id="errorModal" class="alert alert-danger" style="display: none;"></div>
      </div>

      <div class="modal-footer" class="modal fade" id="mensajeModal" role="dialog" data-backdrop="static"
        data-keyboard="false">
        <button id="btnCerrar" type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button id="btnGuardar" type="button" class="btn btn-success"
          onclick="GuardarPermisosClientes()">Guardar</button>
      </div>

    </div>
  </div>
</div>





<script>
$("#newModal").on("hidden.bs.modal", function() {
  $("#newModal input").val("");
  $("#newModal #msj_error").css('display', 'none');
  $("#titulo_nuevo_modal").text("Nuevo cliente");
});
$("#accesoModal").on("hidden.bs.modal", function() {
  $("#accesoModal input, #accesoModal select").val("");
  $("#accesoModal #privacidad").val(0);
  $("#accesoModal input").removeClass("requerido");
  $("#accesoModal #msj_error").css('display', 'none');
  $("#idCliente").val("");
});
$("#accesosClienteModal").on("hidden.bs.modal", function() {
  $("#accesosClienteModal #div_accesos").empty();
});
</script>