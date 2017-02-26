function clu_especialidad() {
	this.datos_pie = [];
	this.table = '';
	
}

clu_especialidad.prototype.onjquery = function() {
};

clu_especialidad.prototype.opt_select = function(controlador,metodo) {
	
	if(clu_especialidad.table.rows('.selected').data().length){		
		window.location=metodo + "/" + clu_especialidad.table.rows('.selected').data()[0]['id'];
	}else{
		$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Seleccione un registro!</strong>Esta opción requiere la selección de un registro!!!.<br><br><ul><li>Selecciona un registro dando click sobre él, luego prueba nuevamente la opción</li></ul></div>');
	}
};

clu_especialidad.prototype.opt_ver = function() {
	if(clu_especialidad.table.rows('.selected').data().length){		
		var datos = new Array();
  		datos['id'] = clu_especialidad.table.rows('.selected').data()[0].id;  		
  		seg_ajaxobject.peticionajax($('#form_ver').attr('action'),datos,"clu_especialidad.verRespuesta");
	}else{
		$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Seleccione un registro!</strong> Esta opción requiere la selección de un registro!!!.<br><br><ul><li>Selecciona un registro dando click sobre él, luego prueba nuevamente la opción</li></ul></div>');
	}   
	
};

clu_especialidad.prototype.verRespuesta = function(result) {
	$("#especialidad_ver_modal .modal-body .row_izq").html('');
	$("#especialidad_ver_modal .modal-body .row_izq").html('<div class="col-md-12" >Especialidad: '+clu_especialidad.table.rows('.selected').data()[0].name+'</div>');
	$("#especialidad_ver_modal .modal-body .row_izq").html($("#especialidad_ver_modal .modal-body .row_izq").html()+'<div class="col-md-12" >Descripción: '+clu_especialidad.table.rows('.selected').data()[0].description+'</div>');
	
	$("#especialidad_ver_modal").modal();
};

var clu_especialidad = new clu_especialidad();
