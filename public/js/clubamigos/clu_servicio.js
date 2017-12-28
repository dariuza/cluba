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

clu_servicio.prototype.opt_ver = function() {

	if(clu_servicio.table.rows('.selected').data().length){

		var datos = new Array();
  		datos['id_service'] = clu_servicio.table.rows('.selected').data()[0].id;
  		datos['especialty_id'] = clu_servicio.table.rows('.selected').data()[0].especialty_id;
  		datos['especialist_id'] = clu_servicio.table.rows('.selected').data()[0].especialist_id;
  		datos['subentity_id'] = clu_servicio.table.rows('.selected').data()[0].subentity_id;
  		datos['status'] = clu_servicio.table.rows('.selected').data()[0].status;
  		datos['suscription_id'] = clu_servicio.table.rows('.selected').data()[0].suscription_id;
  		

  		seg_ajaxobject.peticionajax($('#form_ver_servicio').attr('action'),datos,"clu_servicio.verServicioRespuesta");

	}else{

		$('.alerts').html('<div class="alert alert-info alert-dismissable"><button type="button" class="close close_alert_message" data-dismiss="alert">&times;</button><strong>¡Seleccione un registro!</strong> Esta opción requiere la selección de un registro!!!.<br><br><ul><li>Selecciona un registro dando click sobre él, luego prueba nuevamente la opción</li></ul></br></div>');

	}

};

clu_servicio.prototype.verServicioRespuesta = function(result) {

	$("#servicio_ver_modal .modal-body .row_tab1_content").html('');
	$("#servicio_ver_modal .modal-body .row_tab2_content").html('');

	//tab 1
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >USUARIO: '+result.data.servicio.names_user+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >CONTACTO USUARIO: '+result.data.servicio.surnames_user+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >IDENTIFICACIÓN USUARIO: '+result.data.servicio.identification_user+'</div>');	
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >DÍA: '+result.data.servicio.day+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >FECHA: '+result.data.servicio.date_service+' </div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >HORA: '+result.data.servicio.hour_start+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >DURACIÓN: '+result.data.servicio.duration+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >PRECIO: '+result.data.servicio.price+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >SUCURSAL: '+result.data.entidad.business_name+' - '+result.data.sucursal.sucursal_name+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >CIUDAD: '+result.data.servicio.city+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >DIRECCIÓN SUCURSAL: '+result.data.sucursal.adress+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >EMAIL SUCURSAL: '+result.data.sucursal.email_contact+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >TELEFONO 1 SUCURSAL: '+result.data.sucursal.phone1_contact+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >TELEFONO 2 SUCURSAL: '+result.data.sucursal.phone2_contact+'</div>');
	
	
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-12" ><hr size="1"/></div>');
	
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-12"  ><b>INFORMACIÓN DE ENTIDAD</b></div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >ENTIDAD: '+result.data.entidad.business_name+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >NIT: '+result.data.entidad.nit+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >REPRESENTANTE LEGAL: '+result.data.entidad.legal_representative+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >CONTACTO: '+result.data.entidad.contact_representative+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >EMAIL CONTACTO: '+result.data.entidad.email_contact+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >TELÉFONO ENTIDAD 1: '+result.data.entidad.phone1_contact+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >TELÉFONO ENTIDAD 2: '+result.data.entidad.phone2_contact+'</div>');

	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-12" ><hr size="1"/></div>');
	
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-12"  ><b>INFORMACIÓN DE ESPECIALISTA</b></div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >ESPECIALIDAD: '+result.data.especialidad.name+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >CÓDIGO: '+result.data.especialidad.code+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >DESCRIPCIÓN: '+result.data.especialidad.description+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >NOMBRES ESPECIALISTA: '+result.data.especialista.name+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >IDENTIFICACIÓN: '+result.data.especialista.identification+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >TELÉFONO 1: '+result.data.especialista.phone1+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >TELÉFONO 2: '+result.data.especialista.phone2+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >EMAIL ESPECIALISTA: '+result.data.especialista.email+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >NOMBRES ASISTENTE: '+result.data.especialista.name_assistant+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >TELÉFONO 1 ASISTENTE: '+result.data.especialista.phone1_assistant+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >TELÉFONO 2 ASISTENTE: '+result.data.especialista.phone2_assistant+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab1_content").html($("#servicio_ver_modal .modal-body .row_tab1_content").html()+'<div class="col-md-4" >EMAIL ASISTENTE: '+result.data.especialista.email_assistant+'</div>');

	//tab 2
	$("#servicio_ver_modal .modal-body .row_tab2_content").html($("#servicio_ver_modal .modal-body .row_tab2_content").html()+'<div class="col-md-4" >CÓDIGO: '+result.data.suscripcion[0].code+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab2_content").html($("#servicio_ver_modal .modal-body .row_tab2_content").html()+'<div class="col-md-4" >ESTADO: '+result.data.suscripcion[0].state+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab2_content").html($("#servicio_ver_modal .modal-body .row_tab2_content").html()+'<div class="col-md-4" >FECHA SUSCRIPCIÓN: '+result.data.suscripcion[0].date_suscription+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab2_content").html($("#servicio_ver_modal .modal-body .row_tab2_content").html()+'<div class="col-md-4" >FECHA VIGENCIA: '+result.data.suscripcion[0].date_expiration+'</div>');
	
	$("#servicio_ver_modal .modal-body .row_tab2_content").html($("#servicio_ver_modal .modal-body .row_tab2_content").html()+'<div class="col-md-12" ><hr size="1"/></div>');
	
	$("#servicio_ver_modal .modal-body .row_tab2_content").html($("#servicio_ver_modal .modal-body .row_tab2_content").html()+'<div class="col-md-12"  ><b>INFORMACIÓN DE TITULAR</b></div>');
	$("#servicio_ver_modal .modal-body .row_tab2_content").html($("#servicio_ver_modal .modal-body .row_tab2_content").html()+'<div class="col-md-4" >NOMBRES: '+result.data.titular[0].names+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab2_content").html($("#servicio_ver_modal .modal-body .row_tab2_content").html()+'<div class="col-md-4" >APELLIDOS: '+result.data.titular[0].surnames+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab2_content").html($("#servicio_ver_modal .modal-body .row_tab2_content").html()+'<div class="col-md-4" >TELÉFONO FIJO: '+result.data.titular[0].fix_number+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab2_content").html($("#servicio_ver_modal .modal-body .row_tab2_content").html()+'<div class="col-md-4" >TELÉFONO MOVIL: '+result.data.titular[0].movil_number+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab2_content").html($("#servicio_ver_modal .modal-body .row_tab2_content").html()+'<div class="col-md-4" >DIRECCIÓN: '+result.data.titular[0].city+' - '+result.data.titular[0].adress+'</div>');
	$("#servicio_ver_modal .modal-body .row_tab2_content").html($("#servicio_ver_modal .modal-body .row_tab2_content").html()+'<div class="col-md-4" >FECHA VIGENCIA: '+result.data.titular[0].identificacion+'</div>');
	
	$("#servicio_ver_modal").modal();
};

clu_servicio.prototype.opt_ver_disponibilidad = function() {

	if(clu_servicio.table.rows('.selected').data().length){

		//limpiamos los campos
		$("input[name='id_especialista']").val('');
		$("input[name='id_entidad']").val('');
		$("input[name='id_especialidad']").val('');		
		$("input[name='dia']").val();
		$("input[name='fechahora']").val();
		$("input[name='price']").val();	
		$("input[name='duration']").val();

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
			$("input[name='price']").val(clu_servicio.table.rows('.selected').data()[0][5]);	
			$("input[name='duration']").val(clu_servicio.table.rows('.selected').data()[0][22]);					
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

    			$('#nombreusuariospan').html(''+result.data.titular[0].names+' '+result.data.titular[0].surnames);
    			$('#nombreusuario').val(result.data.titular[0].names+' '+result.data.titular[0].surnames);
    			
    			/*
    			$('#identificacionspan').html(result.data.titular[0].identificacion)
    			$('#identificacion').val(result.data.titular[0].identificacion)
    			*/

    			$('#identificacionspan').html($("input[name='cedula_usuario']").val());
    			$('#identificacion').val($("input[name='cedula_usuario']").val());			



    			$('#numerocontactospan').html(result.data.titular[0].movil_number);
    			$('#numerocontacto').val(result.data.titular[0].movil_number);

    			$('#suscripcionspan').html(result.data.titular[0].code);
    			$('#suscripcion').val(result.data.titular[0].code);

    			$('#estadospan').html(result.data.titular[0].estado);
    			$('#estado').val(result.data.titular[0].estado);

    			$('#titularspan').html(''+result.data.titular[0].names+' '+result.data.titular[0].surnames);
    			$('#titular').val(result.data.titular[0].names+' '+result.data.titular[0].surnames);
    			$("input[name='id_suscription']").val(result.data.titular[0].suscription_id);

    		}else{
    			//es un beneficiario

    			$('#nombreusuariospan').html(''+result.data.beneficiario[0].names+' '+result.data.beneficiario[0].surnames);
    			$('#nombreusuario').val(result.data.beneficiario[0].names+' '+result.data.beneficiario[0].surnames);
    			
    			/*
    			$('#identificacionspan').html(result.data.titular[0].identificacion)
    			$('#identificacion').val(result.data.titular[0].identificacion)
    			*/

    			$('#identificacionspan').html($("input[name='cedula_usuario']").val());
    			$('#identificacion').val($("input[name='cedula_usuario']").val());			


    			$('#numerocontactospan').html(result.data.beneficiario[0].movil_number);
    			$('#numerocontacto').val(result.data.beneficiario[0].movil_number);

    			$('#suscripcionspan').html(result.data.beneficiario[0].code);
    			$('#suscripcion').val(result.data.beneficiario[0].code);

    			$('#estadospan').html(result.data.beneficiario[0].estado);
    			$('#estado').val(result.data.beneficiario[0].estado);

    			$('#titularspan').html(''+result.data.beneficiario[0].friendnames+' '+result.data.beneficiario[0].friendsurnames);
    			$('#titular').val(result.data.beneficiario[0].friendnames+' '+result.data.beneficiario[0].friendsurnames);
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

clu_servicio.prototype.opt_consultar_usuario = function() {
	document.getElementsByClassName('info_suscripcion')[0].textContent="";
	document.getElementsByClassName('susctiptor_suscripcion')[0].textContent="";
	document.getElementsByClassName('pull_suscripcion')[0].textContent="";
	var datos = new Array();
	if($("#cedula_usuario_modal").val() != ""){
		datos['id'] = $("#cedula_usuario_modal").val(); 
  		seg_ajaxobject.peticionajax($('#form_consult_usermodal').attr('action'),datos,"clu_servicio.verRespuestaConsultaModal");

	}else{
		$('.alerts-module').html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close close_alert_message" data-dismiss="alert">&times;</button><strong>!Consulta Fallida!</strong></br> No se ha ingresado alguna cedula o código.</div>');
		$(".close_alert_message").on('click', function () { 
    		$("#servicio_nuevo_modal :input").removeClass("input_danger");        	
    	});		
	}
  	
};


clu_servicio.prototype.verRespuestaConsultaModal = function(result) {
	//limpiamos
	document.getElementsByClassName('info_suscripcion')[0].textContent="";
	document.getElementsByClassName('susctiptor_suscripcion')[0].textContent="";
	document.getElementsByClassName('pull_suscripcion')[0].textContent="";


	if(result.data.titular.length){
		//suscripcion
		var nodo = document.createElement("div");
		nodo.setAttribute("class", " form-group");

		var subnodo_1 = document.createElement("div")	
		subnodo_1.setAttribute("class", "col-md-3");
		var label_1 = document.createElement("label");
		var span_1 = document.createElement("span");		
		label_1.setAttribute("class", "control-label");
		label_1.setAttribute("for", "sct_code");
		label_1.textContent = "Codigo: ";		
		span_1.textContent = result.data.titular[0]['code'];		
		subnodo_1.appendChild(label_1);
		subnodo_1.appendChild(span_1);


		var subnodo_2 = document.createElement("div")	
		subnodo_2.setAttribute("class", "col-md-3");
		var label_2 = document.createElement("label");
		var span_2 = document.createElement("span");		
		label_2.setAttribute("class", "control-label");
		label_2.setAttribute("for", "sct_code");
		label_2.textContent = "Estado: ";
		span_2.textContent = result.data.titular[0]['estado'];		
		subnodo_2.appendChild(label_2);
		subnodo_2.appendChild(span_2);

		var subnodo_3 = document.createElement("div")	
		subnodo_3.setAttribute("class", "col-md-6");
		var label_3 = document.createElement("label");		
		var span_3 = document.createElement("span");
		label_3.setAttribute("class", "control-label");
		label_3.setAttribute("for", "sct_code");
		label_3.textContent = "Fecha Expiración: ";		
		span_3.textContent = result.data.titular[0]['date_expiration'];		
		subnodo_3.appendChild(label_3);
		subnodo_3.appendChild(span_3);


		nodo.appendChild(subnodo_1);//Codigo
		nodo.appendChild(subnodo_2);//Estado
		nodo.appendChild(subnodo_3);//Fecha expiracion		

		document.getElementsByClassName('info_suscripcion')[0].appendChild(nodo);


		//suscriptor

		var nodo = document.createElement("div");
		nodo.setAttribute("class", " form-group");

		var subnodo_1 = document.createElement("div")	
		subnodo_1.setAttribute("class", "col-md-3");
		var label_1 = document.createElement("label");
		var span_1 = document.createElement("span");		
		label_1.setAttribute("class", "control-label");
		label_1.setAttribute("for", "sct_code");
		label_1.textContent = "Nombres: ";		
		span_1.textContent = result.data.titular[0]['names'];		
		subnodo_1.appendChild(label_1);
		subnodo_1.appendChild(span_1);


		var subnodo_2 = document.createElement("div")	
		subnodo_2.setAttribute("class", "col-md-3");
		var label_2 = document.createElement("label");
		var span_2 = document.createElement("span");		
		label_2.setAttribute("class", "control-label");
		label_2.setAttribute("for", "sct_code");
		label_2.textContent = "Apellidos: ";
		span_2.textContent = result.data.titular[0]['surnames'];		
		subnodo_2.appendChild(label_2);
		subnodo_2.appendChild(span_2);

		var subnodo_3 = document.createElement("div")	
		subnodo_3.setAttribute("class", "col-md-3");
		var label_3 = document.createElement("label");		
		var span_3 = document.createElement("span");
		label_3.setAttribute("class", "control-label");
		label_3.setAttribute("for", "sct_code");
		label_3.textContent = result.data.titular[0]['type_id'];		
		span_3.textContent = result.data.titular[0]['identificacion'];		
		subnodo_3.appendChild(label_3);
		subnodo_3.appendChild(span_3);

		var subnodo_4 = document.createElement("div")	
		subnodo_4.setAttribute("class", "col-md-3");
		var label_4 = document.createElement("label");		
		var span_4 = document.createElement("span");
		label_4.setAttribute("class", "control-label");
		label_4.setAttribute("for", "sct_code");
		label_4.textContent = 'Contacto';		
		span_4.textContent = result.data.titular[0]['movil_number'];		
		subnodo_4.appendChild(label_4);
		subnodo_4.appendChild(span_4);


		nodo.appendChild(subnodo_1);//Codigo
		nodo.appendChild(subnodo_2);//Estado
		nodo.appendChild(subnodo_3);//Fecha expiracion
		nodo.appendChild(subnodo_4);//Movilnumbre		

		document.getElementsByClassName('susctiptor_suscripcion')[0].appendChild(nodo);


		//construimos el contenido de beneficiarios
		for(var i=0;i<result.data.beneficiario.length;i++){
			var nodo = document.createElement("div");
			nodo.setAttribute("class", "col-md-12 form-group");

			var subnodo_1 = document.createElement("div")	
			subnodo_1.setAttribute("class", "col-md-2");
			var input_1 = document.createElement("input");
			input_1.setAttribute("class", "form-control");
			input_1.setAttribute("name", "bns_nombres_"+i);	
			input_1.setAttribute("placeholder", "Nombres");
			input_1.value=result.data.beneficiario[i].names;			
			subnodo_1.appendChild(input_1);

			var subnodo_2 = document.createElement("div")	
			subnodo_2.setAttribute("class", "col-md-2");
			var input_2 = document.createElement("input");
			input_2.setAttribute("class", "form-control");
			input_2.setAttribute("name", "bns_apellidos_"+i);	
			input_2.setAttribute("placeholder", "Apellidos");
			input_2.value=result.data.beneficiario[i].surnames;			
			subnodo_2.appendChild(input_2);

			var subnodo_3 = document.createElement("div")	
			subnodo_3.setAttribute("class", "col-md-2");
			var input_3 = document.createElement('select');
			input_3.setAttribute("class", "form-control");
			input_3.setAttribute("name", "bns_typeid_"+i);
			input_3.setAttribute("id", "bns_typeid_"+i);
			var opt1 = document.createElement('option');
			opt1.innerHTML = 'Selecciona una opción';
			input_3.appendChild(opt1);
			
			var opt1 = document.createElement('option');
			opt1.innerHTML = 'CEDULA CIUDADANIA';
			if(result.data.beneficiario[i].type_id == 'CEDULA CIUDADANIA') opt1.setAttribute('selected','selected');
			input_3.appendChild(opt1);	
			
			var opt2 = document.createElement('option');
			opt2.innerHTML = 'TARJETA IDENTIDAD';
			if(result.data.beneficiario[i].type_id == 'TARJETA IDENTIDAD') opt2.setAttribute('selected','selected');
			input_3.appendChild(opt2);	
			
			var opt3 = document.createElement('option');
			opt3.innerHTML = 'REGISTRO CIVIL';
			if(result.data.beneficiario[i].type_id == 'REGISTRO CIVIL') opt3.setAttribute('selected','selected');
			input_3.appendChild(opt3);	
			
			var opt4 = document.createElement('option');
			opt4.innerHTML = 'CEDULA EXTRAJERIA';
			if(result.data.beneficiario[i].type_id == 'CEDULA EXTRAJERIA') opt4.setAttribute('selected','selected');
			input_3.appendChild(opt4);
			subnodo_3.appendChild(input_3);

			var subnodo_4 = document.createElement("div")	
			subnodo_4.setAttribute("class", "col-md-2");
			var input_4 = document.createElement("input");
			input_4.setAttribute("class", "form-control solo_numeros");
			input_4.setAttribute("name", "bns_identificacion_"+i);	
			input_4.setAttribute("placeholder", "Identificacion");
			input_4.value=result.data.beneficiario[i].identification;			
			subnodo_4.appendChild(input_4);

			var subnodo_41 = document.createElement("div")	
			subnodo_41.setAttribute("class", "col-md-2");
			var input_41 = document.createElement("input");
			input_41.setAttribute("class", "form-control");
			input_41.setAttribute("name", "bns_telefono_"+i);	
			input_41.setAttribute("placeholder", "Teléfono");
			input_41.value=result.data.beneficiario[i].movil_number;			
			subnodo_41.appendChild(input_41);

			var subnodo_5 = document.createElement("div")	
			subnodo_5.setAttribute("class", "col-md-2");
			var input_5 = document.createElement("button");
			input_5.setAttribute("class", "form-control btn btn-default bns_edit_button");
			input_5.setAttribute("id", "bns_edit_"+i);
			input_5.innerHTML = "Actualizar";			
			subnodo_5.appendChild(input_5);

			var subnodo_6 = document.createElement("div")				
			var input_6 = document.createElement("input");
			input_6.setAttribute("type", "hidden");
			input_6.setAttribute("id", "bns_id_"+i);			
			input_6.value=result.data.beneficiario[i].id;			
			subnodo_6.appendChild(input_6);	


			nodo.appendChild(subnodo_1);//Nombres de beneficiario
			nodo.appendChild(subnodo_2);//Apellidos de beneficiario
			nodo.appendChild(subnodo_3);//Tipo id beneficiario
			nodo.appendChild(subnodo_4);//Identificacion
			nodo.appendChild(subnodo_41);//Contacto
			nodo.appendChild(subnodo_5);//Boton
			nodo.appendChild(subnodo_6);//Id de beneficiario

			document.getElementsByClassName('pull_suscripcion')[0].appendChild(nodo);

		}

		//agregamos la funcion a los botones actualizar
		$('.bns_edit_button').click(function(){
		    		    
		    var datos = new Array();
  			datos['names'] = $("input[name='bns_nombres_"+this.id.split('_')[2]+"']" ).val();
  			datos['surnames'] = $("input[name='bns_apellidos_"+this.id.split('_')[2]+"']" ).val();
  			datos['type_id'] = $("#bns_typeid_"+this.id.split('_')[2] ).val();
  			datos['identification'] = $("input[name='bns_identificacion_"+this.id.split('_')[2]+"']" ).val();
  			datos['telefono'] = $("input[name='bns_telefono_"+this.id.split('_')[2]+"']" ).val();
  			datos['id'] = $("input[id='bns_id_"+this.id.split('_')[2]+"']" ).val();

  			seg_ajaxobject.peticionajax($('#form_edit_bnf').attr('action'),datos,"clu_servicio.respuestaEditBeneficiario");
		    
		});

		$( ".solo_numeros" ).keypress(function(evt) {
			evt = (evt) ? evt : window.event;
	    	var charCode = (evt.which) ? evt.which : evt.keyCode;
	    	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
	        	return false;
	    	}
		    return true;
		});		
	}

};

clu_servicio.prototype.respuestaEditBeneficiario = function(result) {
	if(result.respuesta == true){
		$('#servicio_nuevo_modal .alerts-module').html('<div class="alert alert-success alert-dismissable"><button type="button" class="close close_alert_product" data-dismiss="alert">&times;</button><strong>!Actualización acertada!</strong></br> El beneficiario fue actualizado correctamente.</div>');	 
	}else{
		$('#servicio_nuevo_modal .alerts-module').html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close close_alert_product" data-dismiss="alert">&times;</button><strong>!Actualización Fallida!</strong></br> El beneficiario no fue actualizado.</div>');
	}
	$(".close_alert_product").on('click', function () { 
    	$("#form_nuevo_servicio :input").removeClass("input_danger");        	
    });
	
};


clu_servicio.prototype.opt_edt = function() {

	if(clu_servicio.table.rows('.selected').data().length){

		var datos = new Array();
  		datos['id_service'] = clu_servicio.table.rows('.selected').data()[0].id;
  		seg_ajaxobject.peticionajax($('#form_edit_servicio').attr('action'),datos,"clu_servicio.editarServicioRespuesta");
  		
	}else{

		$('.alerts').html('<div class="alert alert-info alert-dismissable"><button type="button" class="close close_alert_message" data-dismiss="alert">&times;</button><strong>¡Seleccione un registro!</strong> Esta opción requiere la selección de un registro!!!.<br><br><ul><li>Selecciona un registro dando click sobre él, luego prueba nuevamente la opción</li></ul></br></div>');

	}

};

clu_servicio.prototype.editarServicioRespuesta = function(result) {

	
	$("#servicio_editar_modal").modal();
}

clu_servicio.prototype.opt_delete = function() {

	if(clu_servicio.table.rows('.selected').data().length){

		var r = confirm("Estas segur@ de borrar el servicio seleccionado!!!");

		if (r == true) {
	        var datos = new Array();
  			datos['id_service'] = clu_servicio.table.rows('.selected').data()[0].id;  		

  			seg_ajaxobject.peticionajax($('#form_delete_servicio').attr('action'),datos,"clu_servicio.borrarServicioRespuesta");
  			
	    }
		

	}else{

		$('.alerts').html('<div class="alert alert-info alert-dismissable"><button type="button" class="close close_alert_message" data-dismiss="alert">&times;</button><strong>¡Seleccione un registro!</strong> Esta opción requiere la selección de un registro!!!.<br><br><ul><li>Selecciona un registro dando click sobre él, luego prueba nuevamente la opción</li></ul></br></div>');

	}

};

clu_servicio.prototype.borrarServicioRespuesta = function(result) {

	if(result.respuesta == true){
		$('.alerts').html('<div class="alert alert-success alert-dismissable"><button type="button" class="close close_alert_message" data-dismiss="alert">&times;</button><strong>¡Muy bien!</strong> Se logro borrar el registro correctamente!!!.<br><br><ul><li>Esta operación es irreversible, ahora si quieres recuperar el registro deberas crear la cita nuevamente dando click en la opción agregar</li></ul></br></div>');
		clu_servicio.table.row('.selected').remove().draw( false );

	}else{
		clu_servicio.table.$('tr.selected').removeClass('selected');
		$('.alerts').html('<div class="alert alert-info alert-dismissable"><button type="button" class="close close_alert_message" data-dismiss="alert">&times;</button><strong>¡No se logro borrar el registro!</strong> Intentalo nuevamente!!!.<br><br><ul><li>Selecciona un registro dando click sobre él, luego prueba nuevamente la opción</li></ul></br></div>');
	}
	
};






var clu_servicio = new clu_servicio();
