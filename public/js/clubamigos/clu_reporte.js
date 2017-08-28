function clu_reporte() {
	this.datos_pie = [];
	this.table = '';
	this.datos_advisers = [];
	this.datos_cities = [];
	this.datos_states = [];
	
}

clu_reporte.prototype.onjquery = function() {
};

clu_reporte.prototype.opt_select = function(controlador,metodo) {
	
	if(clu_reporte.table.rows('.selected').data().length){		
		window.location=metodo + "/" + clu_reporte.table.rows('.selected').data()[0]['id'];
	}else{
		$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Seleccione un registro!</strong>Esta opción requiere la selección de un registro!!!.<br><br><ul><li>Selecciona un registro dando click sobre él, luego prueba nuevamente la opción</li></ul></div>');
	}
};

clu_reporte.prototype.opt_report_general = function() {
	//llamado a controlador para traer los datos del formulario
	$("#suscripcion_reporte_general").modal();	   	  
};

clu_reporte.prototype.opt_factura_general = function() {
	//llamado a controlador para traer los datos del formulario
	$("#suscripcion_reporte_general").modal();	   	  
};


var clu_reporte = new clu_reporte();
