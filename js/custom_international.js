$(document).ready(function(){
	//Inicializar iCheck
	$('.minimal').iCheck({
	    checkboxClass: 'icheckbox_square-blue',
	    radioClass   : 'iradio_minimal-blue'
	}); 
	//Limpiar inputs
	$(".obligado").focus(function(){
		$(this).removeClass("requerido");
	});
	//Limpia Modal de nuevo registro
	$("#newModal").on("hidden.bs.modal", function(){
      $("input").removeClass("requerido");
      $("input").val("");
      $("#campos_vacios").css('display','none');
      $("#correo_invalido").css('display','none');
    });
    $("#civil").change(function(){
    	var civil = $(this).val();
    	if(civil == 1){
    		$(".casado").addClass('obligado');
    		$(".casado").addClass('requerido');
    	}
    	else{
    		$(".casado").removeClass('obligado');
    		$(".casado").removeClass('requerido');
    	}
    });
    $(".casado").change(function(){
    	var vaciosConyuge = $('.casado').filter(function(){
      		return !$(this).val();
    	}).length;

	    if(vaciosConyuge > 0 && vaciosConyuge < 6){
	      	$(".casado").each(function() {
		        var element = $(this);
		        if (element.val() == "") {
		          	element.addClass("obligado");
		        }
	      	});
	    }
	    else{
	    	$(".casado").removeClass("obligado");
	    	$(".casado").removeClass("requerido");
	    }
    });
   
	//Abrir modal formulario socioeconomico
	$("#form_basico").click(function(){
		$("#formBase").modal('show');
		var hoy = new Date();
	  	var dd = hoy.getDate();
	  	var mm = hoy.getMonth()+1;
	  	var yyyy = hoy.getFullYear();
	  	var hora = hoy.getHours();
	  	var minuto = hoy.getMinutes();

	  	if(dd<10) {
	      	dd='0'+dd;
	  	} 
	  	if(mm<10) {
	      	mm='0'+mm;
	  	}
	  	if(hora < 10){
	  		hora = '0'+hora;
	  	}
	  	if(minuto < 10){
	  		minuto = '0'+minuto;
	  	}

	  	$("#fecha").val(dd+"/"+mm+"/"+yyyy+" "+hora+":"+minuto);
	});
	//Se cambia el idioma al español;
	/*$.fn.datetimepicker.dates['en'] = {
	    days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"],
	    daysShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab", "Dom"],
	    daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa", "Do"],
	    months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
	    monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
	    meridiem: '',
	    today: "Hoy"
	};*/

  	//Se crea la variable para establecer la fecha actual
  	var hoy = new Date();
  	var dd = hoy.getDate();
  	var mm = hoy.getMonth()+1;
  	var yyyy = hoy.getFullYear();
  	var hora = hoy.getHours()+":"+hoy.getMinutes();

  	if(dd<10) {
      	dd='0'+dd;
  	} 

  	if(mm<10) {
      	mm='0'+mm;
  	}
  	//Acepta solo numeros en los input
  	$(".solo_numeros").on("input", function(){
	    var valor = $(this).val();
	    $(this).val(valor.replace(/[^0-9]/g, ''));
	});
	//Se evalúa el caracter para validar un número flotante o decimal; elimina el caracter si no cumple con la regla
	function checkDecimales(el){
	 var ex = /^[0-9]+\.?[0-9]*$/;
	 if(ex.test(el.value)==false){
	   el.value = el.value.substring(0,el.value.length - 1);
	  }
	}
	//Formatea las fechas para almacenar en servidor
	function formatoFecha(date) {
	    var aux = date.split("/");
	    var fecha = aux[2]+"-"+aux[1]+"-"+aux[0];
	    return fecha;
	}
	//Evaluación y guardado de formulario Socioeconomico base
	$("#terminarFormBase").click(function(){
		var data = $("form#datosBasico").serialize();
		var personas = $("#num_personas").val();
		var p_data = "";
		console.log(data)

		var cuarto = $("input:checkbox[id='cuarto']:checked").val();
		var cocina = $("input:checkbox[id='cocina']:checked").val();
		var comedor = $("input:checkbox[id='comedor']:checked").val();
		var patio = $("input:checkbox[id='patio']:checked").val();
		var jardin = $("input:checkbox[id='jardin']:checked").val();
		var garaje = $("input:checkbox[id='garaje']:checked").val();
		var sala = $("input:checkbox[id='sala']:checked").val();

		var visita = $("#fecha").val();
		var aux = visita.split(" ");

		//Formato fechas
		var f = formatoFecha(aux[0]);
		var f_nacimiento = formatoFecha($("#fecha_nacimiento").val());
		var f_registro = formatoFecha($("#fecha_acta").val());
		var f_vencimiento = formatoFecha($("#fecha_vencimiento").val());
		var f_licencia = ($("#fecha_licencia").val() != "") ? formatoFecha($("#fecha_licencia").val()) : "";
		
		//Valores checkboxes
		var _cuarto = (cuarto == undefined) ? 0 : 1;
		var _cocina = (cocina == undefined) ? 0 : 1;
		var _comedor = (comedor == undefined) ? 0 : 1;
		var _patio = (patio == undefined) ? 0 : 1;
		var _jardin = (jardin == undefined) ? 0 : 1;
		var _garaje = (garaje == undefined) ? 0 : 1;
		var _sala = (sala == undefined) ? 0 : 1;
		//Datos de las personas que habitan con el candidato
		for(var i = 1; i <= personas; i++){
			p_data += $("#p_nombre"+i).val()+",";
			p_data += $("#p_parentesco"+i).val()+",";
			p_data += $("#p_edad"+i).val()+",";
			p_data += $("#p_civil"+i).val()+",";
			p_data += $("#p_escolaridad"+i).val()+",";
			p_data += $("#p_depende"+i).val()+",";
			p_data += $("#p_empresa"+i).val()+",";
			p_data += $("#p_puesto"+i).val()+",";
			p_data += $("#p_sueldo"+i).val()+",";
			p_data += $("#p_antiguedad"+i).val()+",";
			p_data += $("#p_aporta"+i).val()+",";
			p_data += $("#p_mueble"+i).val()+",";
			p_data += $("#p_adeudo"+i).val()+"@@";
		}
		//console.log(p_data);
	});

});