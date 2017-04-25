function clu_entidad() {
	this.datos_pie = [];
	this.table = '';
	
}

clu_entidad.prototype.onjquery = function() {
};

clu_entidad.prototype.validateNuevaEntidad = function() {
	if($("#form_nuevo_entidad :input")[1].value =="" || $("#form_nuevo_entidad :input")[5].value ==""){
		$('#entidad_nuevo_modal .alerts-module').html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close close_alert_product" data-dismiss="alert">&times;</button><strong>!Envio Fallido!</strong></br> Faltan campos por diligenciar.</div>');
		//pintar los inputs problematicos
		for(var i=0; i < $("#form_nuevo_entidad :input").length ; i++){
	        if( i==1 || i==5 ) {
	            if($("#form_nuevo_entidad :input")[i].value ==""){
	                $($("#form_nuevo_entidad :input")[i]).addClass('input_danger');
	            }	            
	        }
        }
        $(".close_alert_product").on('click', function () { 
        	$("#form_nuevo_entidad :input").removeClass("input_danger");        	
        });
		return false;
	}
	return true;
};

clu_entidad.prototype.opt_select = function(controlador,metodo) {
	
	if(clu_entidad.table.rows('.selected').data().length){		
		window.location=metodo + "/" + clu_entidad.table.rows('.selected').data()[0]['id'];
	}else{
		$('.alerts').html('<div class="alert alert-info alert-dismissable"><button type="button" class="close close_alert_edit_perfil" data-dismiss="alert">&times;</button><strong>¡Seleccione un registro!</strong>Esta opción requiere la selección de un registro!!!.<br><br><ul><li>Selecciona un registro dando click sobre él, luego prueba nuevamente la opción</li></ul></div>');
	}
};

clu_entidad.prototype.opt_ver = function() {
	if(clu_entidad.table.rows('.selected').data().length){		
		var datos = new Array();
  		datos['id'] = clu_entidad.table.rows('.selected').data()[0].id;  		
  		seg_ajaxobject.peticionajax($('#form_ver').attr('action'),datos,"clu_entidad.verRespuesta");
	}else{
		$('.alerts').html('<div class="alert alert-info alert-dismissable"><button type="button" class="close close_alert_edit_perfil" data-dismiss="alert">&times;</button><strong>¡Seleccione un registro!</strong> Esta opción requiere la selección de un registro!!!.<br><br><ul><li>Selecciona un registro dando click sobre él, luego prueba nuevamente la opción</li></ul></div>');
	}	
};

clu_entidad.prototype.verRespuesta = function(result) {
	$("#entidad_ver_modal .modal-body .tab1").html('');
	$("#entidad_ver_modal .modal-body .tab1").html('<div class="col-md-12" >Entidad: '+clu_entidad.table.rows('.selected').data()[0].business_name+' - NIT: '+clu_entidad.table.rows('.selected').data()[0].nit+'</div>');
	$("#entidad_ver_modal .modal-body .tab1").html($("#entidad_ver_modal .modal-body .tab1").html()+'<div class="col-md-12" >Representante Legal: '+clu_entidad.table.rows('.selected').data()[0].legal_representative+'</div>');
	$("#entidad_ver_modal .modal-body .tab1").html($("#entidad_ver_modal .modal-body .tab1").html()+'<div class="col-md-12" >Contacto Representante Legal: '+clu_entidad.table.rows('.selected').data()[0].contact_representative+'</div>');
	$("#entidad_ver_modal .modal-body .tab1").html($("#entidad_ver_modal .modal-body .tab1").html()+'<div class="col-md-12" >Telèfono 1: '+clu_entidad.table.rows('.selected').data()[0].phone1_contact+'</div>');
	$("#entidad_ver_modal .modal-body .tab1").html($("#entidad_ver_modal .modal-body .tab1").html()+'<div class="col-md-12" >Telèfono 2: '+clu_entidad.table.rows('.selected').data()[0].phone2_contact+'</div>');
	$("#entidad_ver_modal .modal-body .tab1").html($("#entidad_ver_modal .modal-body .tab1").html()+'<div class="col-md-12" >Correo Electrònico: '+clu_entidad.table.rows('.selected').data()[0].email_contact+'</div>');
	$("#entidad_ver_modal .modal-body .tab1").html($("#entidad_ver_modal .modal-body .tab1").html()+'<div class="col-md-12" >Descripciòn: '+clu_entidad.table.rows('.selected').data()[0].description+'</div>');
	
	$("#entidad_ver_modal .modal-body .tab2").html('');
	if(result.respuesta){
		if(result.data.sucursales){
			$("#entidad_ver_modal .modal-body .tab2").html($("#entidad_ver_modal .modal-body .tab2").html()+' <div class="col-md-12" style = "border-bottom: 1px solid black;" ><b> <div class="col-md-1">Nº</div> <div class="col-md-2">Nombre</div> <div class="col-md-2">Direcciòn</div> <div class="col-md-2">Telèfonos</div> <div class="col-md-5">Email</div> <b> </div>');
			for(var i = 0; i < result.data.sucursales.length; i++){
				$("#entidad_ver_modal .modal-body .tab2").html($("#entidad_ver_modal .modal-body .tab2").html()+' <div class="col-md-12" style = "border-bottom: 1px solid black;" > <div class="col-md-1">'+(i+1)+'</div> <div class="col-md-2">'+result.data.sucursales[i].sucursal_name+'</div> <div class="col-md-2">'+result.data.sucursales[i].adress+'</div> <div class="col-md-2">'+result.data.sucursales[i].phone1_contact+' - '+result.data.sucursales[i].phone2_contact+'</div> <div class="col-md-5">'+result.data.sucursales[i].email_contact+'</div> </div>');
			}
		}
	}
	//result.data.entidad[0].business_name
	$("#entidad_ver_modal").modal();
};

clu_entidad.prototype.opt_agregar = function() {
	var datos = new Array();
	seg_ajaxobject.peticionajax($('#form_nuevo').attr('action'),datos,"clu_entidad.nuevoRespuesta");
};

clu_entidad.prototype.nuevoRespuesta = function(result) {	
	$("#entidad_nuevo_modal").modal();
};

clu_entidad.prototype.add_subent = function(add) {
	var n = document.getElementsByClassName('tab_entidad2')[0].childElementCount + 1;

	var nodo = document.createElement("div");
	nodo.setAttribute("class", "form-group");

	var subnodo_1 = document.createElement("div")	
	subnodo_1.setAttribute("class", "col-md-2");
	var label_1 = document.createElement("label");
	var input_1 = document.createElement("input");
	label_1.setAttribute("class", "col-md-12 control-label");
	label_1.setAttribute("for", "subent_nombre_"+n);
	label_1.textContent = "Nombre";
	input_1.setAttribute("class", "form-control");
	input_1.setAttribute("name", "subent_nombre_"+n);	
	input_1.setAttribute("placeholder", "Nombre de Sucursal");
	subnodo_1.appendChild(label_1);
	subnodo_1.appendChild(input_1);

	var subnodo_2 = document.createElement("div")	
	subnodo_2.setAttribute("class", "col-md-3");
	var label_2 = document.createElement("label");
	var input_2 = document.createElement("input");
	label_2.setAttribute("class", "col-md-12 control-label");
	label_2.setAttribute("for", "subent_direccion_"+n);
	label_2.textContent = "Dirección";
	input_2.setAttribute("class", "form-control");
	input_2.setAttribute("name", "subent_direccion_"+n);	
	input_2.setAttribute("placeholder", "Dirección de Sucursal");
	subnodo_2.appendChild(label_2);
	subnodo_2.appendChild(input_2);

	var subnodo_3 = document.createElement("div")	
	subnodo_3.setAttribute("class", "col-md-2");
	var label_3 = document.createElement("label");
	var input_3 = document.createElement("input");
	label_3.setAttribute("class", "col-md-12 control-label");
	label_3.setAttribute("for", "subent_telefonouno_"+n);
	label_3.textContent = "Teléfono 1";
	input_3.setAttribute("class", "form-control solo_numeros");
	input_3.setAttribute("name", "subent_telefonouno_"+n);	
	input_3.setAttribute("placeholder", "Primer Teléfono de Sucursal");
	subnodo_3.appendChild(label_3);
	subnodo_3.appendChild(input_3);

	var subnodo_4 = document.createElement("div")	
	subnodo_4.setAttribute("class", "col-md-2");
	var label_4 = document.createElement("label");
	var input_4 = document.createElement("input");
	label_4.setAttribute("class", "col-md-12 control-label");
	label_4.setAttribute("for", "subent_telefonodos_"+n);
	label_4.textContent = "Teléfono 2";
	input_4.setAttribute("class", "form-control solo_numeros");
	input_4.setAttribute("name", "subent_telefonodos_"+n);	
	input_4.setAttribute("placeholder", "Segundo Teléfono de Sucursal");
	subnodo_4.appendChild(label_4);
	subnodo_4.appendChild(input_4);

	var subnodo_5 = document.createElement("div")	
	subnodo_5.setAttribute("class", "col-md-3");
	var label_5 = document.createElement("label");
	var input_5 = document.createElement("input");
	label_5.setAttribute("class", "col-md-12 control-label");
	label_5.setAttribute("for", "subent_email_"+n);
	label_5.textContent = "Correo";
	input_5.setAttribute("class", "form-control");
	input_5.setAttribute("name", "subent_email_"+n);	
	input_5.setAttribute("placeholder", "Correo de Sucursal");
	subnodo_5.appendChild(label_5);
	subnodo_5.appendChild(input_5);

	nodo.appendChild(subnodo_1);//Nombre de sucursal
	nodo.appendChild(subnodo_2);//Dirección de sucursal
	nodo.appendChild(subnodo_3);//Telefono 1 sucursal
	nodo.appendChild(subnodo_4);//Telefono 2 sucursal
	nodo.appendChild(subnodo_5);//Email sucursal

	document.getElementsByClassName('tab_entidad2')[0].appendChild(nodo);

	$( ".solo_numeros" ).keypress(function(evt) {
		 evt = (evt) ? evt : window.event;
	    var charCode = (evt.which) ? evt.which : evt.keyCode;
	    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
	        return false;
	    }
	    return true;
	});	

}

var clu_entidad = new clu_entidad();
