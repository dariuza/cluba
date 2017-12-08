function clu_beneficiario() {
	this.datos_pie = [];
	this.table = '';
	
}

clu_beneficiario.prototype.onjquery = function() {
};

clu_beneficiario.prototype.opt_select = function(controlador,metodo) {
	
	if(clu_beneficiario.table.rows('.selected').data().length){		
		window.location=metodo + "/" + clu_beneficiario.table.rows('.selected').data()[0]['id'];
	}else{
		$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Seleccione un registro!</strong>Esta opción requiere la selección de un registro!!!.<br><br><ul><li>Selecciona un registro dando click sobre él, luego prueba nuevamente la opción</li></ul></div>');
	}
};

clu_beneficiario.prototype.opt_ver = function() {	
	
	if(clu_beneficiario.table.rows('.selected').data().length){		
  		var datos = new Array();
  		datos['id'] = clu_beneficiario.table.rows('.selected').data()[0].id; 
  		clu_beneficiario.verRespuesta();
  		//seg_ajaxobject.peticionajax($('#form_abonar').attr('action'),datos,"clu_beneficiario.verRespuesta");
	}else{
		$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Seleccione un registro!</strong> Esta opción requiere la selección de un registro!!!.<br><br><ul><li>Selecciona un registro dando click sobre él, luego prueba nuevamente la opción</li></ul></div>');
	} 
	   	  
};

clu_beneficiario.prototype.verRespuesta = function(result) {
	
	$("#beneficiario_ver_modal .modal-body .row_izq").html('');
	
	$("#beneficiario_ver_modal .modal-body .row_izq").html($("#beneficiario_ver_modal .modal-body .row_izq").html()+'<div class="col-md-6" >Beneficiario: '+clu_beneficiario.table.rows('.selected').data()[0].suscriptor+'</div>');
	$("#beneficiario_ver_modal .modal-body .row_izq").html($("#beneficiario_ver_modal .modal-body .row_izq").html()+'<div class="col-md-6" >Identificación: '+clu_beneficiario.table.rows('.selected').data()[0].identification+'</div>');
	$("#beneficiario_ver_modal .modal-body .row_izq").html($("#beneficiario_ver_modal .modal-body .row_izq").html()+'<div class="col-md-6" >Parentesco: '+clu_beneficiario.table.rows('.selected').data()[0].relationship+'</div>');
	$("#beneficiario_ver_modal .modal-body .row_izq").html($("#beneficiario_ver_modal .modal-body .row_izq").html()+'<div class="col-md-6" >Celular: '+clu_beneficiario.table.rows('.selected').data()[0].movil_number+'</div>');
	$("#beneficiario_ver_modal .modal-body .row_izq").html($("#beneficiario_ver_modal .modal-body .row_izq").html()+'<div class="col-md-6"  style="background-color: '+clu_beneficiario.table.rows('.selected').data()[0].alert+';">Estado: '+clu_beneficiario.table.rows('.selected').data()[0].state+'</div>');
	
	$("#beneficiario_ver_modal .modal-body .tab2").html('');
	$("#beneficiario_ver_modal .modal-body .tab2").html($("#beneficiario_ver_modal .modal-body .tab2").html()+'<div class="col-md-6" >Suscriptor: '+clu_beneficiario.table.rows('.selected').data()[0].suscriptor+'</div>');
	$("#beneficiario_ver_modal .modal-body .tab2").html($("#beneficiario_ver_modal .modal-body .tab2").html()+'<div class="col-md-6" >Identificación: '+clu_beneficiario.table.rows('.selected').data()[0].identificacion_fr+'</div>');
	$("#beneficiario_ver_modal .modal-body .tab2").html($("#beneficiario_ver_modal .modal-body .tab2").html()+'<div class="col-md-6" >Email: '+clu_beneficiario.table.rows('.selected').data()[0].email+'</div>');
	$("#beneficiario_ver_modal .modal-body .tab2").html($("#beneficiario_ver_modal .modal-body .tab2").html()+'<div class="col-md-6" >Teléfono: '+clu_beneficiario.table.rows('.selected').data()[0].fix_number+'</div>');
	$("#beneficiario_ver_modal .modal-body .tab2").html($("#beneficiario_ver_modal .modal-body .tab2").html()+'<div class="col-md-6" >Celular: '+clu_beneficiario.table.rows('.selected').data()[0].fr_movil+'</div>');
	$("#beneficiario_ver_modal .modal-body .tab2").html($("#beneficiario_ver_modal .modal-body .tab2").html()+'<div class="col-md-6" >Departamento: '+clu_beneficiario.table.rows('.selected').data()[0].departamento+'</div>');
	$("#beneficiario_ver_modal .modal-body .tab2").html($("#beneficiario_ver_modal .modal-body .tab2").html()+'<div class="col-md-6" >Ciudad: '+clu_beneficiario.table.rows('.selected').data()[0].city+'</div>');	
	$("#beneficiario_ver_modal .modal-body .tab2").html($("#beneficiario_ver_modal .modal-body .tab2").html()+'<div class="col-md-6" >Barrio: '+clu_beneficiario.table.rows('.selected').data()[0].neighborhood+'</div>');
	$("#beneficiario_ver_modal .modal-body .tab2").html($("#beneficiario_ver_modal .modal-body .tab2").html()+'<div class="col-md-6" >Dirección: '+clu_beneficiario.table.rows('.selected').data()[0].adress+'</div>');
	
	$("#beneficiario_ver_modal .modal-body .tab2").html($("#beneficiario_ver_modal .modal-body .tab2").html()+'<div class="col-md-12" ><hr size="1"/></div>');
	$("#beneficiario_ver_modal .modal-body .tab2").html($("#beneficiario_ver_modal .modal-body .tab2").html()+'<div class="col-md-12"  ><b>Información de Pago</b></div>');
	$("#beneficiario_ver_modal .modal-body .tab2").html($("#beneficiario_ver_modal .modal-body .tab2").html()+'<div class="col-md-6" >Dirección de pago: '+clu_beneficiario.table.rows('.selected').data()[0].paymentadress+'</div>');
	$("#beneficiario_ver_modal .modal-body .tab2").html($("#beneficiario_ver_modal .modal-body .tab2").html()+'<div class="col-md-6" >Referencia: '+clu_beneficiario.table.rows('.selected').data()[0].reference+'</div>');
	$("#beneficiario_ver_modal .modal-body .tab2").html($("#beneficiario_ver_modal .modal-body .tab2").html()+'<div class="col-md-6" >Teléfono referencia: '+clu_beneficiario.table.rows('.selected').data()[0].reference_phone+'</div>');
	
	
	
	$("#beneficiario_ver_modal").modal();
};

clu_beneficiario.prototype.abonarRespuesta = function(result) {
	
	$("#beneficiario_abono_modal .modal-body .row_izq").html('');
	$("#beneficiario_abono_modal .modal-body .row_izq").html('<div class="col-md-3" >Beneficiario: </div><div class="col-md-3" >'+clu_beneficiario.table.rows('.selected').data()[0].beneficiario+'</div>');
	$("#beneficiario_abono_modal .modal-body .row_izq").html($("#beneficiario_abono_modal .modal-body .row_izq").html()+'<div class="col-md-3" >Identificación: </div><div class="col-md-3" >'+clu_beneficiario.table.rows('.selected').data()[0].identification+'</div>');
	$("#beneficiario_abono_modal .modal-body .row_izq").html($("#beneficiario_abono_modal .modal-body .row_izq").html()+'<div class="col-md-3" >Precio: </div><div class="col-md-3" >'+clu_beneficiario.table.rows('.selected').data()[0].price+'</div>');
	$("#beneficiario_abono_modal .modal-body .row_izq").html($("#beneficiario_abono_modal .modal-body .row_izq").html()+'<div class="col-md-3" >Mora: </div><div class="col-md-3" >'+clu_beneficiario.table.rows('.selected').data()[0].mora+'</div>');
	$("#beneficiario_abono_modal .modal-body .row_izq").html($("#beneficiario_abono_modal .modal-body .row_izq").html()+'<div class="col-md-6"  style="background-color: '+clu_beneficiario.table.rows('.selected').data()[0].alert+';">Estado: '+clu_beneficiario.table.rows('.selected').data()[0].state+'</div>');
	$("#beneficiario_abono_modal .modal-body .row_izq").html($("#beneficiario_abono_modal .modal-body .row_izq").html()+'<div class="col-md-3" >Fecha de Creación: </div><div class="col-md-3" >'+clu_beneficiario.table.rows('.selected').data()[0].created_at+'</div>');

	if(result.respuesta){
		if(result.data){
			$("#beneficiario_abono_modal .modal-body .row_izq").html($("#beneficiario_abono_modal .modal-body .row_izq").html()+'<div class="col-md-12" ><hr size="1"/></div>');
			$("#beneficiario_abono_modal .modal-body .row_izq").html($("#beneficiario_abono_modal .modal-body .row_izq").html()+'<div class="col-md-12" style="text-align: center;" ><b>ABONOS</b></div>');
			var total = 0;
			var j = 0;
			for(var i = 0; i < result.data.pagos.length; i++){
				$("#beneficiario_abono_modal .modal-body .row_izq").html($("#beneficiario_abono_modal .modal-body .row_izq").html()+' <div class="col-md-12" style = "border-bottom: 1px solid black;" > <div class="col-md-2">'+i+'</div> <div class="col-md-5">'+result.data.pagos[i].date_payment+'</div> <div class="col-md-5">'+result.data.pagos[i].payment+'</div> </div>');
				total = total + result.data.pagos[i].payment;
				j++;
			}
			$("#beneficiario_abono_modal .modal-body .row_izq").html($("#beneficiario_abono_modal .modal-body .row_izq").html()+' <div class="col-md-12"> <div class="col-md-2"><b>'+j+'</b></div> <div class="col-md-5"><b>Total</b></div> <div class="col-md-5"><b>'+total+'</b></div> </div>');
		}				
	}
	
	$("#beneficiario_abono_modal .modal-body .row_izq").html($("#beneficiario_abono_modal .modal-body .row_izq").html()+'<div class="col-md-12" ><hr size="1"/></div>');
	$("#beneficiario_abono_modal .modal-body .row_izq").html($("#beneficiario_abono_modal .modal-body .row_izq").html()+'<div class="col-md-12" style="text-align: center;" ><b>AGREGAR ABONO</b></div>');
	$("#beneficiario_abono_modal .modal-body .row_izq").html($("#beneficiario_abono_modal .modal-body .row_izq").html()+'<div class="col-md-12" > <div class="col-md-6"> <input class="form-control" placeholder="Ingresa el valor" autofocus="autofocus" name="payment" type="text" id="payment"> </div> <div class="col-md-6"> <input class="form-control date_payment" placeholder="Fecha de abono" name="date_payment" type="text" id="date_payment">  </div>  </div>');
	
	$("#beneficiario_abono_modal").modal();
	
	$('.date_payment').datetimepicker({
		dateFormat: "yy-mm-dd",
		timeFormat: "hh:mm:ss",		
	});

	
};

clu_beneficiario.prototype.agregarTitulares = function(result) {	
	$( "#titular_id" ).autocomplete({
		source: result.data
	});	
};


	

var clu_beneficiario = new clu_beneficiario();
