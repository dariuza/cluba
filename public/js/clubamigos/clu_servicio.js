function clu_servicio() {
	this.datos_pie = [];
	this.table = '';
	
}

clu_servicio.prototype.onjquery = function() {
};

clu_servicio.prototype.validateNuevoServicio = function() {
	if($("#form_nuevo_servicio :input")[1].value =="" || $("#form_nuevo_servicio :input")[3].value =="" || $("#form_nuevo_servicio :input")[5].value ==""){
		$('#servicio_nuevo_modal .alerts-module').html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close close_alert_product" data-dismiss="alert">&times;</button><strong>!Consulta Fallido!</strong></br> Faltan campos por diligenciar.</div>');
		//pintar los inputs problematicos
		for(var i=0; i < $("#form_nuevo_servicio :input").length ; i++){
	        if( i <= 6 ) {
	            if($("#form_nuevo_servicio :input")[i].value ==""){
	                $($("#form_nuevo_servicio :input")[i]).addClass('input_danger');
	            }	            
	        }
        }
        $(".close_alert_product").on('click', function () { 
        	$("#form_nuevo_servicio :input").removeClass("input_danger");        	
        });
		return false;
	}
	return true;
};

clu_servicio.prototype.validateNuevaCita = function() {

	if($($("#form_nueva_city :input")[14]).val() == "" || $($("#form_nueva_city :input")[15]).val() == "" || $($("#form_nueva_city :input")[16]).val() == "" || $($("#form_nueva_city :input")[17]).val() == "" || $($("#form_nueva_city :input")[3]).val() == "" || $($("#form_nueva_city :input")[7]).val() == "" || $($("#form_nueva_city :input")[6]).val() == ""){
		//id_especialista,id_entidad,id_especialidad,id_suscripcion,municipio,identificacion,names

		$('.alerts-form').html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close close_alert_message" data-dismiss="alert">&times;</button><strong>!Consulta Fallida!</strong></br> Campos insuficiantes para asignar la cita.</div>');
		$(".close_alert_message").on('click', function () { 
			$("#form_nueva_city :input").removeClass("input_danger");        	
		});	
		return false;		

	}	

	return true;
};

clu_servicio.prototype.opt_agregar = function() {
	var datos = new Array();
	seg_ajaxobject.peticionajax($('#form_nuevo').attr('action'),datos,"clu_servicio.nuevoRespuesta");
};

clu_servicio.prototype.nuevoRespuesta = function(result) {
	$("#servicio_nuevo_modal").modal();
};

clu_servicio.prototype.verRespuestaEntidades = function(result) {
	if(result.respuesta){
		if(result.data.length != 0){
			var selects = document.getElementsByClassName("select_entidad")[0];
			$('.select_entidad').empty();	
			for(var i in result.data.entidades) {
				var opt1 = document.createElement('option');
				opt1.value = i;
				opt1.innerHTML = result.data.entidades[i];
				selects.appendChild(opt1);			
			}
			$('.select_entidad').trigger("chosen:updated");		
			
		}else{
			$('.select_entidad').empty();
			$('.select_entidad').trigger("chosen:updated");	
			//alert('No hay entidades para el municipio '+$("#select_municipio").val());
		}
	}
	
};

clu_servicio.prototype.opt_ver_disponibilidad = function() {

	if(clu_servicio.table.rows('.selected').data().length){

		//limpiamos los campos
		$("input[name='id_especialista']").val('');
		$("input[name='id_entidad']").val('');
		$("input[name='id_especialidad']").val('');

		$("#servicio_disponibilidad_modal .modal-body .row_content").html('');

		$("#servicio_disponibilidad_modal .modal-body .row_content").html($("#servicio_disponibilidad_modal .modal-body .row_content").html()+'<div class="col-md-6" >Especialidad: '+clu_servicio.table.rows('.selected').data()[0][0]+'</div>');
		$("#servicio_disponibilidad_modal .modal-body .row_content").html($("#servicio_disponibilidad_modal .modal-body .row_content").html()+'<div class="col-md-6" >Día de cita: '+clu_servicio.table.rows('.selected').data()[0][6]+'</div>');
		$("#servicio_disponibilidad_modal .modal-body .row_content").html($("#servicio_disponibilidad_modal .modal-body .row_content").html()+'<div class="col-md-6" >Fecha y hora de cita: '+clu_servicio.table.rows('.selected').data()[0][7]+'</div>');
		$("#servicio_disponibilidad_modal .modal-body .row_content").html($("#servicio_disponibilidad_modal .modal-body .row_content").html()+'<div class="col-md-6" >Estado: '+clu_servicio.table.rows('.selected').data()[0][8]+'</div>');
		$("#servicio_disponibilidad_modal .modal-body .row_content").html($("#servicio_disponibilidad_modal .modal-body .row_content").html()+'<div class="col-md-6" >Especialista: '+clu_servicio.table.rows('.selected').data()[0][1]+'</div>');
		$("#servicio_disponibilidad_modal .modal-body .row_content").html($("#servicio_disponibilidad_modal .modal-body .row_content").html()+'<div class="col-md-6" >Tel 1 Especialista: '+clu_servicio.table.rows('.selected').data()[0][10]+'</div>');
		$("#servicio_disponibilidad_modal .modal-body .row_content").html($("#servicio_disponibilidad_modal .modal-body .row_content").html()+'<div class="col-md-6" >Tel 2 Especialista: '+clu_servicio.table.rows('.selected').data()[0][11]+'</div>');
		$("#servicio_disponibilidad_modal .modal-body .row_content").html($("#servicio_disponibilidad_modal .modal-body .row_content").html()+'<div class="col-md-6" >Email Especialista: '+clu_servicio.table.rows('.selected').data()[0][15]+'</div>');
		$("#servicio_disponibilidad_modal .modal-body .row_content").html($("#servicio_disponibilidad_modal .modal-body .row_content").html()+'<div class="col-md-6" >Municipio: '+clu_servicio.table.rows('.selected').data()[0][2]+'</div>');
		$("#servicio_disponibilidad_modal .modal-body .row_content").html($("#servicio_disponibilidad_modal .modal-body .row_content").html()+'<div class="col-md-6" >Sucursal: '+clu_servicio.table.rows('.selected').data()[0][3]+'</div>');
		$("#servicio_disponibilidad_modal .modal-body .row_content").html($("#servicio_disponibilidad_modal .modal-body .row_content").html()+'<div class="col-md-6" >Dirección Sucursal: '+clu_servicio.table.rows('.selected').data()[0][4]+'</div>');
		$("#servicio_disponibilidad_modal .modal-body .row_content").html($("#servicio_disponibilidad_modal .modal-body .row_content").html()+'<div class="col-md-6" >Nombre asistente: '+clu_servicio.table.rows('.selected').data()[0][13]+'</div>');
		$("#servicio_disponibilidad_modal .modal-body .row_content").html($("#servicio_disponibilidad_modal .modal-body .row_content").html()+'<div class="col-md-6" >Tel 1 asistente: '+clu_servicio.table.rows('.selected').data()[0][14]+'</div>');
		$("#servicio_disponibilidad_modal .modal-body .row_content").html($("#servicio_disponibilidad_modal .modal-body .row_content").html()+'<div class="col-md-6" >Tel 2 asistente: '+clu_servicio.table.rows('.selected').data()[0][15]+'</div>');
		$("#servicio_disponibilidad_modal .modal-body .row_content").html($("#servicio_disponibilidad_modal .modal-body .row_content").html()+'<div class="col-md-6" >Email asistente: '+clu_servicio.table.rows('.selected').data()[0][16]+'</div>');

		$("#servicio_disponibilidad_modal .modal-body .row_content").html($("#servicio_disponibilidad_modal .modal-body .row_content").html()+'<div class="col-md-6" >Nit entidad: '+clu_servicio.table.rows('.selected').data()[0][17]+'</div>');
		$("#servicio_disponibilidad_modal .modal-body .row_content").html($("#servicio_disponibilidad_modal .modal-body .row_content").html()+'<div class="col-md-6" >Tel 1 entidad: '+clu_servicio.table.rows('.selected').data()[0][18]+'</div>');
		$("#servicio_disponibilidad_modal .modal-body .row_content").html($("#servicio_disponibilidad_modal .modal-body .row_content").html()+'<div class="col-md-6" >Tel 2 entidad: '+clu_servicio.table.rows('.selected').data()[0][19]+'</div>');
		$("#servicio_disponibilidad_modal .modal-body .row_content").html($("#servicio_disponibilidad_modal .modal-body .row_content").html()+'<div class="col-md-6" >Email entidad: '+clu_servicio.table.rows('.selected').data()[0][20]+'</div>');

		

		$("#servicio_disponibilidad_modal .modal-body .row_content").html($("#servicio_disponibilidad_modal .modal-body .row_content").html()+'<div class="col-md-6" >Precio suscriptor: '+clu_servicio.table.rows('.selected').data()[0][5]+'</div>');
		$("#servicio_disponibilidad_modal .modal-body .row_content").html($("#servicio_disponibilidad_modal .modal-body .row_content").html()+'<div class="col-md-6" >Precio particular: '+clu_servicio.table.rows('.selected').data()[0][21]+'</div>');

		$("#servicio_disponibilidad_modal").modal();

		//solo asigna si el estdo es libre
		if(clu_servicio.table.rows('.selected').data()[0][8] == 'libre'){
			$("input[name='id_especialista']").val(clu_servicio.table.rows('.selected').data()[0][23]);
			$("input[name='id_entidad']").val(clu_servicio.table.rows('.selected').data()[0][24]);
			$("input[name='id_especialidad']").val(clu_servicio.table.rows('.selected').data()[0][25]);
			$("input[name='dia']").val(clu_servicio.table.rows('.selected').data()[0][6]);
			$("input[name='fechahora']").val(clu_servicio.table.rows('.selected').data()[0][7]);
			
			
		}

	}else{
		$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Seleccione un registro!</strong> Esta opción requiere la selección de un registro!!!.<br><br><ul><li>Selecciona un registro dando click sobre él, luego prueba nuevamente la opción</li></ul></div>');
	} 

	
};

clu_servicio.prototype.opt_ver_usuario = function() {
	if($("#cedula_usuario").val()  != "" ){
		var datos = new Array();
  		datos['id'] = $("#cedula_usuario").val(); 
  		seg_ajaxobject.peticionajax($('#form_consult_user').attr('action'),datos,"clu_servicio.verRespuestaConsulta");
  		//limpiamos los campos
  		$('#nombreusuariospan').html('')
		$('#nombreusuario').val('')
		
		$('#identificacionspan').html('')
		$('#identificacion').val('')

		$('#numerocontactospan').html('')
		$('#numerocontacto').val('')

		$('#suscripcionspan').html('')
		$('#suscripcion').val('')

		$('#estadospan').html('')
		$('#estado').val('')

		$('#titularspan').html('')
		$('#titular').val('')

		$("input[name='id_suscription']").val('');
	}else{
		alert('Aùn no se ha diligenciado una cedula');
	}
};

clu_servicio.prototype.verRespuestaConsulta = function(result) {

	if(result.respuesta){
		if(result.data.titular.length !=0  || result.data.beneficiario.length != 0){
			$('.alerts-form').html('<div class="alert alert-info alert-dismissable"><button type="button" class="close close_alert_message" data-dismiss="alert">&times;</button><strong>!Consulta Exitosa!</strong></br></div>');
			$(".close_alert_message").on('click', function () { 
    			$("#form_nueva_city :input").removeClass("input_danger");        	
    		});

    		if(result.data.beneficiario == undefined){
    			//es un titular

    			$('#nombreusuariospan').html(''+result.data.titular[0].names+' '+result.data.titular[0].surnames)
    			$('#nombreusuario').val(result.data.titular[0].names+' '+result.data.titular[0].surnames)
    			
    			$('#identificacionspan').html(result.data.titular[0].identificacion)
    			$('#identificacion').val(result.data.titular[0].identificacion)

    			$('#numerocontactospan').html(result.data.titular[0].movil_number)
    			$('#numerocontacto').val(result.data.titular[0].movil_number)

    			$('#suscripcionspan').html(result.data.titular[0].code)
    			$('#suscripcion').val(result.data.titular[0].code)

    			$('#estadospan').html(result.data.titular[0].estado)
    			$('#estado').val(result.data.titular[0].estado)

    			$('#titularspan').html(''+result.data.titular[0].names+' '+result.data.titular[0].surnames)
    			$('#titular').val(result.data.titular[0].names+' '+result.data.titular[0].surnames)
    			$("input[name='id_suscription']").val(result.data.titular[0].suscription_id);

    		}else{
    			//es un beneficiario

    			$('#nombreusuariospan').html(''+result.data.beneficiario[0].names+' '+result.data.beneficiario[0].surnames)
    			$('#nombreusuario').val(result.data.beneficiario[0].names+' '+result.data.beneficiario[0].surnames)
    			
    			$('#identificacionspan').html(result.data.beneficiario[0].identification)
    			$('#identification').val(result.data.beneficiario[0].identification)

    			$('#numerocontactospan').html(result.data.beneficiario[0].movil_number)
    			$('#numerocontacto').val(result.data.beneficiario[0].movil_number)

    			$('#suscripcionspan').html(result.data.beneficiario[0].code)
    			$('#suscripcion').val(result.data.beneficiario[0].code)

    			$('#estadospan').html(result.data.beneficiario[0].estado)
    			$('#estado').val(result.data.beneficiario[0].estado)

    			$('#titularspan').html(''+result.data.beneficiario[0].friendnames+' '+result.data.beneficiario[0].friendsurnames)
    			$('#titular').val(result.data.beneficiario[0].friendnames+' '+result.data.beneficiario[0].friendsurnames)
    			$("input[name='id_suscription']").val(result.data.beneficiario[0].suscription_id);
    		}



		}else{
			$('.alerts-form').html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close close_alert_message" data-dismiss="alert">&times;</button><strong>!Consulta Fallida!</strong></br> No se hallan registros para la identificación consultada.</div>');
			$(".close_alert_message").on('click', function () { 
    			$("#form_nueva_city :input").removeClass("input_danger");        	
    		});
		}

	}else{
		$('.alerts-form').html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close close_alert_message" data-dismiss="alert">&times;</button><strong>!Consulta Fallida!</strong></br> Hay problemas con el codigo, consulta el administrador.</div>');
		$(".close_alert_message").on('click', function () { 
    		$("#form_nueva_city :input").removeClass("input_danger");        	
    	});		
	}
	

};


var clu_servicio = new clu_servicio();
