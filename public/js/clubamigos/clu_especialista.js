function clu_especialista() {
	this.datos_pie = [];
	this.table = '';
	
}

clu_especialista.prototype.onjquery = function() {
};

clu_especialista.prototype.opt_select = function(controlador,metodo) {
	
	if(clu_especialista.table.rows('.selected').data().length){		
		window.location=metodo + "/" + clu_especialista.table.rows('.selected').data()[0]['id'];
	}else{
		$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Seleccione un registro!</strong>Esta opción requiere la selección de un registro!!!.<br><br><ul><li>Selecciona un registro dando click sobre él, luego prueba nuevamente la opción</li></ul></div>');
	}
};

clu_especialista.prototype.opt_ver = function() {
	if(clu_especialista.table.rows('.selected').data().length){		
		var datos = new Array();
  		datos['id'] = clu_especialista.table.rows('.selected').data()[0].id;  		
  		seg_ajaxobject.peticionajax($('#form_ver').attr('action'),datos,"clu_especialista.verRespuesta");
	}else{
		$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Seleccione un registro!</strong> Esta opción requiere la selección de un registro!!!.<br><br><ul><li>Selecciona un registro dando click sobre él, luego prueba nuevamente la opción</li></ul></div>');
	}   
	
};

clu_especialista.prototype.verRespuesta = function(result) {
	$("#especialidad_ver_modal .modal-body .row_izq").html('');
	$("#especialidad_ver_modal .modal-body .row_izq").html('<div class="col-md-12" >Especialidad: '+clu_especialista.table.rows('.selected').data()[0].name+'</div>');
	$("#especialidad_ver_modal .modal-body .row_izq").html($("#especialidad_ver_modal .modal-body .row_izq").html()+'<div class="col-md-12" >Descripción: '+clu_especialista.table.rows('.selected').data()[0].description+'</div>');
	
	$("#especialidad_ver_modal").modal();
};

clu_especialista.prototype.opt_agregar = function() {
	var datos = new Array();
	seg_ajaxobject.peticionajax($('#form_nuevo').attr('action'),datos,"clu_especialista.nuevoRespuesta");
};

clu_especialista.prototype.nuevoRespuesta = function(result) {
	var select = document.getElementById("entidad");	
	select.innerHTML = "";
	var opt1 = document.createElement('option');
	opt1.value = '';
	opt1.innerHTML = 'Selecciona una entidad';
	select.appendChild(opt1);
	for(var i=0;i<result.data.length;i++){
		var opt1 = document.createElement('option');
		opt1.value = result.data[i].id;
		opt1.innerHTML = result.data[i].business_name;
		select.appendChild(opt1);		
	}
	$("#especialidad_nuevo_modal").modal();
};

var clu_especialista = new clu_especialista();
