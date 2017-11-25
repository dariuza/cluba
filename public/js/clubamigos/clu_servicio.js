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


var clu_servicio = new clu_servicio();
