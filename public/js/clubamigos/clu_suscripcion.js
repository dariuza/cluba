function clu_suscripcion() {
	this.table = '';
	this.datos_pie = [];
	this.datos_bar = [];
	this.datos_pie_down = [];
	this.datos_bar_down = [];
	this.colores_pie = [];
	this.datos_advisers = [];
	this.datos_pie_asesor = [];
	this.datos_fechas = [];
	this.benes;
	this.n_add=2;
}

clu_suscripcion.prototype.onjquery = function() {
};

clu_suscripcion.prototype.opt_select = function(controlador,metodo) {
	
	if(clu_suscripcion.table.rows('.selected').data().length){		
		window.location=metodo + "/" + clu_suscripcion.table.rows('.selected').data()[0]['id'];
	}else{
		$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Seleccione una Suscripción! </strong>Esta opción requiere la selección de un registro!!!.<br><br><ul><li>Selecciona un registro dando click sobre él, luego prueba nuevamente la opción</li></ul></div>');
	}
};

clu_suscripcion.prototype.opt_ver = function() {
	if(clu_suscripcion.table.rows('.selected').data().length){		
  	var datos = new Array();
  		datos['id'] = clu_suscripcion.table.rows('.selected').data()[0].id;
  		datos['friend_id'] = clu_suscripcion.table.rows('.selected').data()[0].friend_id;
  		seg_ajaxobject.peticionajax($('#form_abonar').attr('action'),datos,"clu_suscripcion.verRespuesta");
	}else{
		$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Seleccione un registro!</strong> Esta opción requiere la selección de un registro!!!.<br><br><ul><li>Selecciona un registro dando click sobre él, luego prueba nuevamente la opción</li></ul></div>');
	}    	  
};

clu_suscripcion.prototype.verRespuesta = function(result) {
		
	$("#suscripcion_ver_modal .modal-body .row_izq").html('');
	$("#suscripcion_ver_modal .modal-body .row_der").html('');
	$("#suscripcion_ver_modal .modal-body .row_cen").html('');
	$("#suscripcion_ver_modal .modal-body .row_cen0").html('');
	
	$("#suscripcion_ver_modal .modal-body .row_izq").html('<div class="col-md-12" >N° Contrato: '+clu_suscripcion.table.rows('.selected').data()[0].code+'</div>');
	$("#suscripcion_ver_modal .modal-body .row_der").html($("#suscripcion_ver_modal .modal-body .row_der").html()+'<div class="col-md-12" >Fecha de suscripción: '+clu_suscripcion.table.rows('.selected').data()[0].date_suscription+'</div>');
	$("#suscripcion_ver_modal .modal-body .row_izq").html($("#suscripcion_ver_modal .modal-body .row_izq").html()+'<div class="col-md-12" >Precio Suscripción: $'+clu_suscripcion.table.rows('.selected').data()[0].price+'</div>');
	$("#suscripcion_ver_modal .modal-body .row_der").html($("#suscripcion_ver_modal .modal-body .row_der").html()+'<div class="col-md-12" >Fecha de vencimiento: '+clu_suscripcion.table.rows('.selected').data()[0].date_expiration+'</div>');
	$("#suscripcion_ver_modal .modal-body .row_izq").html($("#suscripcion_ver_modal .modal-body .row_izq").html()+'<div class="col-md-12" >Precio Carnets: $'+clu_suscripcion.table.rows('.selected').data()[0].price_cnt+'</div>');
	$("#suscripcion_ver_modal .modal-body .row_der").html($("#suscripcion_ver_modal .modal-body .row_der").html()+'<div class="col-md-12" >Asesor: '+clu_suscripcion.table.rows('.selected').data()[0].names_ad+' ('+clu_suscripcion.table.rows('.selected').data()[0].identificacion_ad+') </div>');
	$("#suscripcion_ver_modal .modal-body .row_izq").html($("#suscripcion_ver_modal .modal-body .row_izq").html()+'<div class="col-md-12" >Precio Beneficiarios [add]: $'+clu_suscripcion.table.rows('.selected').data()[0].price_bnes+'</div>');
	$("#suscripcion_ver_modal .modal-body .row_der").html($("#suscripcion_ver_modal .modal-body .row_der").html()+'<div class="col-md-12" >Observación: '+clu_suscripcion.table.rows('.selected').data()[0].observation+'</div>');
	$("#suscripcion_ver_modal .modal-body .row_izq").html($("#suscripcion_ver_modal .modal-body .row_izq").html()+'<div class="col-md-12" >Precio Reimpresión: $'+clu_suscripcion.table.rows('.selected').data()[0].price_cnt_reprint+'</div>');
	$("#suscripcion_ver_modal .modal-body .row_izq").html($("#suscripcion_ver_modal .modal-body .row_izq").html()+'<div class="col-md-12" >Precio Total: $'+(parseInt(clu_suscripcion.table.rows('.selected').data()[0].price)+parseInt(clu_suscripcion.table.rows('.selected').data()[0].price_cnt)+parseInt(clu_suscripcion.table.rows('.selected').data()[0].price_bnes)+parseInt(clu_suscripcion.table.rows('.selected').data()[0].price_cnt_reprint))+'</div>');
		
	$("#suscripcion_ver_modal .modal-body .row_cen0").html($("#suscripcion_ver_modal .modal-body .row_cen0").html()+'<div class="col-md-12" ><hr size="1"/></div>');
	
	$("#suscripcion_ver_modal .modal-body .row_cen0").html($("#suscripcion_ver_modal .modal-body .row_cen0").html()+'<div class="col-md-12"  ><b>Información de Pago</b></div>');
	$("#suscripcion_ver_modal .modal-body .row_cen0").html($("#suscripcion_ver_modal .modal-body .row_cen0").html()+'<div class="col-md-7" style="background-color: '+clu_suscripcion.table.rows('.selected').data()[0].next_alert+';" >Proximo pago: '+clu_suscripcion.table.rows('.selected').data()[0].next_pay+'</div>');
	var var_state = clu_suscripcion.table.rows('.selected').data()[0].state;
	if(clu_suscripcion.table.rows('.selected').data()[0].state == 'Pago Pendiente' ) var_state = 'Prospecto';
	$("#suscripcion_ver_modal .modal-body .row_cen0").html($("#suscripcion_ver_modal .modal-body .row_cen0").html()+'<div class="col-md-5" style="background-color: '+clu_suscripcion.table.rows('.selected').data()[0].alert+';">Estado: '+var_state+'</div>');	
	$("#suscripcion_ver_modal .modal-body .row_cen0").html($("#suscripcion_ver_modal .modal-body .row_cen0").html()+'<div class="col-md-7" >Mora: $'+clu_suscripcion.table.rows('.selected').data()[0].mora+'</div>');	
	//$("#suscripcion_ver_modal .modal-body .row_izq").html($("#suscripcion_ver_modal .modal-body .row_izq").html()+'<div class="col-md-5" >Cuotas faltantes: '+ diff +' ('+clu_suscripcion.table.rows('.selected').data()[0].fee+')</div>');	
	//$("#suscripcion_ver_modal .modal-body .row_izq").html($("#suscripcion_ver_modal .modal-body .row_izq").html()+'<div class="col-md-5" >Intervalo de Pago: '+clu_suscripcion.table.rows('.selected').data()[0].pay_interval+' Días</div>');
	$("#suscripcion_ver_modal .modal-body .row_cen0").html($("#suscripcion_ver_modal .modal-body .row_cen0").html()+'<div class="col-md-5" >Dirección de pago: '+clu_suscripcion.table.rows('.selected').data()[0].paymentadress+'</div>');
	$("#suscripcion_ver_modal .modal-body .row_cen0").html($("#suscripcion_ver_modal .modal-body .row_cen0").html()+'<div class="col-md-7" >Forma de pago: '+clu_suscripcion.table.rows('.selected').data()[0].waytopay+'</div>');
	$("#suscripcion_ver_modal .modal-body .row_cen0").html($("#suscripcion_ver_modal .modal-body .row_cen0").html()+'<div class="col-md-5" >Referencia: '+clu_suscripcion.table.rows('.selected').data()[0].reference+'</div>');
	$("#suscripcion_ver_modal .modal-body .row_cen0").html($("#suscripcion_ver_modal .modal-body .row_cen0").html()+'<div class="col-md-7" >Telefono Referencia: '+clu_suscripcion.table.rows('.selected').data()[0].reference_phone+'</div>');
	
	$("#suscripcion_ver_modal .modal-body .row_cen").html($("#suscripcion_ver_modal .modal-body .row_cen").html()+'<div class="col-md-12" ><hr size="1"/></div>');
	$("#suscripcion_ver_modal .modal-body .row_cen").html($("#suscripcion_ver_modal .modal-body .row_cen").html()+'<div class="col-md-12" style="text-align: center;" ><b>ABONOS</b></div>');
	if(result.respuesta){
		if(result.data){
			var total = 0;
			var j = 0;
			for(var i = 0; i < result.data.pagos.length; i++){
				$("#suscripcion_ver_modal .modal-body .row_cen").html($("#suscripcion_ver_modal .modal-body .row_cen").html()+' <div class="col-md-12" style = "border-bottom: 1px solid black;" > <div class="col-md-2">'+(i+1)+'</div> <div class="col-md-4">'+result.data.pagos[i].date_payment+'</div> <div class="col-md-3">'+result.data.pagos[i].payment+'</div> <div class="col-md-3">'+result.data.pagos[i].n_receipt+'</div> </div>');
				total = total + result.data.pagos[i].payment;
				j++;
			}
			$("#suscripcion_ver_modal .modal-body .row_cen").html($("#suscripcion_ver_modal .modal-body .row_cen").html()+' <div class="col-md-12"> <div class="col-md-2"><b>'+j+'</b></div> <div class="col-md-4"><b>Total</b></div> <div class="col-md-3"><b>'+total+'</b></div> </div>');
		}				
	}
	
	$("#suscripcion_ver_modal .modal-body .tab2").html('');
	$("#suscripcion_ver_modal .modal-body .tab2").html('<div class="col-md-6" >Nombres y Apellidos: '+clu_suscripcion.table.rows('.selected').data()[0].names_fr+'</div>');	
	$("#suscripcion_ver_modal .modal-body .tab2").html($("#suscripcion_ver_modal .modal-body .tab2").html()+'<div class="col-md-6" >'+clu_suscripcion.table.rows('.selected').data()[0].type_id+': '+clu_suscripcion.table.rows('.selected').data()[0].identificacion_fr+'</div>');
	$("#suscripcion_ver_modal .modal-body .tab2").html($("#suscripcion_ver_modal .modal-body .tab2").html()+'<div class="col-md-6" >Expedida en: '+clu_suscripcion.table.rows('.selected').data()[0].birthplace+'</div>');
	$("#suscripcion_ver_modal .modal-body .tab2").html($("#suscripcion_ver_modal .modal-body .tab2").html()+'<div class="col-md-6" >Fecha de nacimiento: '+clu_suscripcion.table.rows('.selected').data()[0].birthdate+' ('+clu_suscripcion.table.rows('.selected').data()[0].edad+' Años)</div>');
	$("#suscripcion_ver_modal .modal-body .tab2").html($("#suscripcion_ver_modal .modal-body .tab2").html()+'<div class="col-md-6" >Genero: '+clu_suscripcion.table.rows('.selected').data()[0].sex+'</div>');	
	$("#suscripcion_ver_modal .modal-body .tab2").html($("#suscripcion_ver_modal .modal-body .tab2").html()+'<div class="col-md-6" >Departamento: '+clu_suscripcion.table.rows('.selected').data()[0].departamento+'</div>');
	$("#suscripcion_ver_modal .modal-body .tab2").html($("#suscripcion_ver_modal .modal-body .tab2").html()+'<div class="col-md-6" >Ciudad: '+clu_suscripcion.table.rows('.selected').data()[0].city+'</div>');
	$("#suscripcion_ver_modal .modal-body .tab2").html($("#suscripcion_ver_modal .modal-body .tab2").html()+'<div class="col-md-6" >Barrio: '+clu_suscripcion.table.rows('.selected').data()[0].neighborhood+'</div>');
	$("#suscripcion_ver_modal .modal-body .tab2").html($("#suscripcion_ver_modal .modal-body .tab2").html()+'<div class="col-md-6" >Dirección: '+clu_suscripcion.table.rows('.selected').data()[0].adress+'</div>');
	$("#suscripcion_ver_modal .modal-body .tab2").html($("#suscripcion_ver_modal .modal-body .tab2").html()+'<div class="col-md-6" >Email: '+clu_suscripcion.table.rows('.selected').data()[0].email+'</div>');
	$("#suscripcion_ver_modal .modal-body .tab2").html($("#suscripcion_ver_modal .modal-body .tab2").html()+'<div class="col-md-6" >Celular: '+clu_suscripcion.table.rows('.selected').data()[0].movil_number+'</div>');
	$("#suscripcion_ver_modal .modal-body .tab2").html($("#suscripcion_ver_modal .modal-body .tab2").html()+'<div class="col-md-6" >Teléfono fijo: '+clu_suscripcion.table.rows('.selected').data()[0].fix_number+'</div>');
	
	$("#suscripcion_ver_modal .modal-body .tabbne1").html('');
	$("#suscripcion_ver_modal .modal-body .tabbne2").html('');
	if(result.respuesta){
		if(result.data){
			var total = 0;
			var j = 0;
			var crn = 0;
			var aux_crn = 0;
			$("#suscripcion_ver_modal .modal-body .tabbne1").html($("#suscripcion_ver_modal .modal-body .tabbne1").html()+' <div class="col-md-12" style = "border-bottom: 1px solid black;" >  <div class="col-md-1"><b>Cnet</b></div> <div class="col-md-2"><b>Tipo Id</b></div> <div class="col-md-2"><b>Identificación</b></div> <div class="col-md-3"><b>Beneficiario</b></div>  <div class="col-md-2"><b>Parentesco</b></div> <div class="col-md-2"><b>Celular</b></div> </div>');
			$("#suscripcion_ver_modal .modal-body .tabbne2").html($("#suscripcion_ver_modal .modal-body .tabbne2").html()+' <div class="col-md-12" style = "border-bottom: 1px solid black;" >  <div class="col-md-1"><b>Cnet</b></div> <div class="col-md-2"><b>Tipo Id</b></div> <div class="col-md-2"><b>Identificación</b></div> <div class="col-md-3"><b>Beneficiario</b></div>  <div class="col-md-2"><b>Parentesco</b></div> <div class="col-md-2"><b>Celular</b></div> </div>');
			for(var i = 0; i < result.data.bnes.length; i++){
				//carnet
				if( aux_crn != result.data.bnes[i].license_id){
					crn = crn + 1;
					aux_crn = result.data.bnes[i].license_id;
				}				
				
				if(result.data.bnes[i].state == 'Pago por suscripción'){
					$("#suscripcion_ver_modal .modal-body .tabbne1").html($("#suscripcion_ver_modal .modal-body .tabbne1").html()+' <div class="col-md-12" style = "border-bottom: 1px solid black;" >  <div class="col-md-1">'+crn+'</div> <div class="col-md-2">'+result.data.bnes[i].type_id+'</div> <div class="col-md-2">'+result.data.bnes[i].identification+'</div> <div class="col-md-3">'+result.data.bnes[i].names+' '+result.data.bnes[i].surnames+'</div> <div class="col-md-2">'+result.data.bnes[i].relationship+'</div> <div class="col-md-2">'+result.data.bnes[i].movil_number+'</div> </div>');
				}else{
					$("#suscripcion_ver_modal .modal-body .tabbne2").html($("#suscripcion_ver_modal .modal-body .tabbne2").html()+' <div class="col-md-12" style = "border-bottom: 1px solid black;" >  <div class="col-md-1">'+crn+'</div> <div class="col-md-2">'+result.data.bnes[i].type_id+'</div> <div class="col-md-2">'+result.data.bnes[i].identification+'</div> <div class="col-md-3">'+result.data.bnes[i].names+' '+result.data.bnes[i].surnames+'</div> <div class="col-md-2">'+result.data.bnes[i].relationship+'</div> <div class="col-md-2">'+result.data.bnes[i].movil_number+'</div> </div>');
				}
								
			}
			
		}				
	}
	
	$("#suscripcion_ver_modal").modal();
		
};

clu_suscripcion.prototype.abonarRespuesta = function(result) {
	
	$("#suscripcion_abono_modal .modal-body .row_izq").html('');
	$("#suscripcion_abono_modal .modal-body .row_izq").html('<div class="col-md-2" >Codigo: </div><div class="col-md-2" >'+clu_suscripcion.table.rows('.selected').data()[0].code+'</div>');
	$("#suscripcion_abono_modal .modal-body .row_izq").html($("#suscripcion_abono_modal .modal-body .row_izq").html()+'<div class="col-md-4" >Fecha de suscripción: </div><div class="col-md-4" >'+clu_suscripcion.table.rows('.selected').data()[0].date_suscription+'</div>');
	$("#suscripcion_abono_modal .modal-body .row_izq").html($("#suscripcion_abono_modal .modal-body .row_izq").html()+'<div class="col-md-2" >Precio: </div><div class="col-md-2" >$'+(parseInt(clu_suscripcion.table.rows('.selected').data()[0].price)+parseInt(clu_suscripcion.table.rows('.selected').data()[0].price_cnt)+parseInt(clu_suscripcion.table.rows('.selected').data()[0].price_bnes)+parseInt(clu_suscripcion.table.rows('.selected').data()[0].price_cnt_reprint))+'</div>');
	$("#suscripcion_abono_modal .modal-body .row_izq").html($("#suscripcion_abono_modal .modal-body .row_izq").html()+'<div class="col-md-4" >Forma de pago: </div><div class="col-md-4" >'+clu_suscripcion.table.rows('.selected').data()[0].waytopay+'</div>');	
	$("#suscripcion_abono_modal .modal-body .row_izq").html($("#suscripcion_abono_modal .modal-body .row_izq").html()+'<div class="col-md-2" >Mora: </div><div class="col-md-2" >$'+clu_suscripcion.table.rows('.selected').data()[0].mora+'</div>');
	$("#suscripcion_abono_modal .modal-body .row_izq").html($("#suscripcion_abono_modal .modal-body .row_izq").html()+'<div class="col-md-4" >Proximo pago: </div><div class="col-md-4" >'+clu_suscripcion.table.rows('.selected').data()[0].pay_interval+'</div>');
	
	$("#suscripcion_abono_modal .modal-body .row_izq").html($("#suscripcion_abono_modal .modal-body .row_izq").html()+'<div class="col-md-12" ><hr size="1"/></div>');
	$("#suscripcion_abono_modal .modal-body .row_izq").html($("#suscripcion_abono_modal .modal-body .row_izq").html()+'<div class="col-md-12" style="text-align: center;" ><b>ABONOS</b></div>');
	if(result.respuesta){
		if(result.data){
			var total = 0;
			var j = 0;
			for(var i = 0; i < result.data.pagos.length; i++){
				$("#suscripcion_abono_modal .modal-body .row_izq").html($("#suscripcion_abono_modal .modal-body .row_izq").html()+' <div class="col-md-12" style = "border-bottom: 1px solid black;" > <div class="col-md-2">'+(i+1)+'</div> <div class="col-md-4">'+result.data.pagos[i].date_payment+'</div> <div class="col-md-3">'+result.data.pagos[i].payment+'</div> <div class="col-md-2">'+result.data.pagos[i].n_receipt+'</div> </div>');
				total = total + result.data.pagos[i].payment;
				j++;
			}
			$("#suscripcion_abono_modal .modal-body .row_izq").html($("#suscripcion_abono_modal .modal-body .row_izq").html()+' <div class="col-md-12"> <div class="col-md-2"><b>'+j+'</b></div> <div class="col-md-4"><b>Total</b></div> <div class="col-md-3"><b>'+total+'</b></div> </div>');
		}				
	}
	
	$("#suscripcion_abono_modal .modal-body .row_izq").html($("#suscripcion_abono_modal .modal-body .row_izq").html()+'<div class="col-md-12" ><hr size="1"/></div>');
	$("#suscripcion_abono_modal .modal-body .row_izq").html($("#suscripcion_abono_modal .modal-body .row_izq").html()+'<div class="col-md-12" style="text-align: center;" ><b>AGREGAR ABONO</b></div>');
	$("#suscripcion_abono_modal .modal-body .row_izq").html($("#suscripcion_abono_modal .modal-body .row_izq").html()+'<div class="col-md-12" > <div class="col-md-3"> <input class="form-control" placeholder="Ingresa el valor" autofocus="autofocus" name="payment" type="text" id="payment"> </div> <div class="col-md-3"> <input class="form-control date_payment" placeholder="Fecha de abono" name="date_payment" type="text" id="date_payment">  </div>  <div class="col-md-3"> <input class="form-control" placeholder="N° Recibo" name="n_receipt" type="text" id="n_receipt">  </div>  <div class="col-md-3"> <input class="form-control pay_interval" placeholder="Fecha de proximo pago" name="pay_interval" type="text" id="pay_interval">  </div> </div>');
	

	$("#suscripcion_abono_modal").modal();
	seg_user.iniciarDatepiker('pay_interval');
	//seg_user.iniciarDatepiker('date_payment');
	
	$('.date_payment').datetimepicker({
		//dateFormat: "yy-mm-dd",
		//timeFormat: "hh:mm:ss",
		format: "yyyy-mm-dd",
        language: "es",
        autoclose: true	
	});
		
		
};

clu_suscripcion.prototype.abonarRespuestaSave = function(result) {
	if(result.respuesta){
		if(result.data){
			//cerramos el modal
			$('#suscripcion_abono_modal').modal('toggle');
			//actualizamos tabla
			clu_suscripcion.table.draw();
			$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Abono guardado exitosamente! </strong>El abono ya se registro a la suscripción con codigo: '+result.code+'!!!.<br><br><ul><li>Selecciona la suscripción dando click sobre la fila correspondiente, luego de clic sobre la opción Abonar para verificar la transacción.</li></ul></div>');			
		}
		else{
			//cerramos el modal
			$('#suscripcion_abono_modal').modal('toggle');
			//actualizamos tabla
			clu_suscripcion.table.draw();
			$('.alerts').html('<div class="alert alert-info fade in"><strong>¡El Abono no se guardado! </strong>El registro de abono no se realizo sobre la suscripción con codigo: '+result.code+'!!!.<br><br><ul><li>Selecciona la suscripción dando click sobre la fila correspondiente, luego de clic sobre la opción Abonar para reintentar la transacción.</li></ul></div>');

		}
	}
};

clu_suscripcion.prototype.consultaRespuestaCity = function(result) {
	
	//vamos a limpiar el select		
	var list = document.getElementById("city");
	fistChild=list.firstChild;
	
	while (list.hasChildNodes()) {   
	    list.removeChild(list.firstChild);
	}
	list.appendChild(fistChild);
	if(result.respuesta){
		if(result.data){
			
			//añadimos nuevos elementos			
			for(var i = 0; i < result.data.length; i++){
				var option = document.createElement("option");
				option.value=result.data[i].id;
				option.textContent = result.data[i].city;
				list.appendChild(option);
			}
		}
	}
};

//metodo para agregar carnet
clu_suscripcion.prototype.add_cnt = function() {
	
	if(document.getElementsByClassName("nav-cnts")[0].childElementCount < 9){
		var cn=document.getElementsByClassName("nav-cnts")[0];
		var n=cn.childElementCount;//numero de hijos incluyendo el boton
		
		
		//borramos el boton
		var boton=cn.children[n-1];
		cn.removeChild(boton);
				
		var node = document.createElement("li");
		node.setAttribute("role", "beneficiary");
		
		var subnode = document.createElement("a");
		subnode.setAttribute("href", "#tab_c_"+n);
		subnode.setAttribute("data-toggle", "tab");
		subnode.setAttribute("aria-expanded", "false");
		subnode.innerHTML = 'CARNET N°'+n;
		
		node.appendChild(subnode);
		
		cn.appendChild(node);	
		//agregamos el boton
		cn.appendChild(boton);
		
		//creacion de div tab
		var tc=document.getElementsByClassName("content-crnt")[0];
		
		var node_d = document.createElement("div");	
		node_d.setAttribute("class", "tab-pane fade");
		//verificamos su el tab ya existe
		
		node_d.setAttribute("id", "tab_c_"+n);			
		
		//buscamos un n diferente delos demas
		/*
		var i=0;
		while(i == 0){
			if($('.inputs_c_'+n).length){
				n++;				
			}else{
				i++;
			}
		}
		*/
		//asignamos el n maximo par a los nuevos carnets
		var auxn = 0;
		var aux = 0;
		for(var i = 0; i < $('.inputs_c').length; i++){
			aux = $('.inputs_c')[i].classList[0].split('_')[2];
			if(parseInt(aux) > parseInt(auxn)){
				auxn = parseInt(aux);
			}
		}
		n = parseInt(auxn) + 1;
		
		var node_i = document.createElement("div");
		node_i.setAttribute("class", "inputs_c_"+n+ " inputs_c" );		
		node_d.appendChild(node_i);		
		
		//boton agregar
		var node_bt = document.createElement("div");
		node_bt.setAttribute("class", "col-md-1 col-md-offset-11");
		node_bt.setAttribute("data-toggle", "tooltip");
		//node_bt.setAttribute("title", "");
		node_bt.setAttribute("data-original-title", "Agregar Beneficiario");
		
		var node_bt_a = document.createElement("a");
		node_bt_a.setAttribute("href", "javascript:clu_suscripcion.add_bne("+n+")");
		node_bt_a.setAttribute("class", "site_title");
		node_bt_a.setAttribute("style", "text-decoration: none;color:#5A738E !important; ");
		
		var node_bt_a_i = document.createElement("i");
		node_bt_a_i.setAttribute("class", "fa fa-plus");
		node_bt_a_i.setAttribute("style", "border: 1px solid #5A738E !important");
		
		node_bt_a.appendChild(node_bt_a_i);
		node_bt.appendChild(node_bt_a);		
		node_d.appendChild(node_bt);
		
		tc.appendChild(node_d);
		
	}else{
		$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Agregar Carnets! </strong>Solo es posible tener hasta 8 Carnets por Susripción!!!.<br><br><ul><li>Para agregar más carnet es necesario agregar beneficiarios adicionales</li></ul></div>');
	}
};

//Metodo para agregar beneficiarios
clu_suscripcion.prototype.add_bne = function(crnt,add) {
		
	if(add != undefined){
		if(clu_suscripcion.benes >= 7){
			//es beneficiario adicional
			clu_suscripcion.bene_add(crnt,'add');
			clu_suscripcion.n_add++;
		}else{
			$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Agregar Beneficiarios! </strong>Aún hay menos de 7 Beneficiarios por Susripción!!!.<br><br><ul><li>Para agregar más beneficiarios es necesario inscribirlos como beneficiarios por suscripción.</li></ul></div>');
		}
		
	}else{
		if(clu_suscripcion.benes < 7){
			//llamamos el metodo de agregar beneficiario
			clu_suscripcion.bene_add(crnt,'');	
			$("input[name='numer_b']").val(parseInt(n)+1);
			clu_suscripcion.benes = clu_suscripcion.benes + 1;			
			clu_suscripcion.n_add++;
		}else{			
			$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Agregar Beneficiarios! </strong>Solo es posible tener hasta 7 Beneficiarios por Susripción!!!.<br><br><ul><li>Para agregar más beneficiarios es necesario inscribirlos como beneficiarios adicionales.</li></ul></div>');
		}
	}
	
	
};

clu_suscripcion.prototype.bene_add = function(crnt,add) {
	
	n=document.getElementsByClassName("inputs_c_"+crnt)[0].childElementCount + 1;
	n=clu_suscripcion.n_add + 1;
	
	var node = document.createElement("div");
	node.setAttribute("class", "form-group");	
	
	var subnode_s = document.createElement("div")
	subnode_s.setAttribute("class", "col-md-3 bne_add_"+crnt+"_"+n);
	subnode_s.style.display = "none";
	
	var label_s = document.createElement("label");
	label_s.setAttribute("class", "col-md-12 control-label");
	label_s.setAttribute("for", "typeid");
	label_s.textContent = "Tipo Id";
	
	var select=document.createElement("select");
	select.setAttribute("class", "form-control");
	select.setAttribute("name", "bne"+add+"_type_id_"+crnt+"_"+n);
	var opt1 = document.createElement('option');
	opt1.value = '';
	opt1.innerHTML = 'Selecciona una opción';
	select.appendChild(opt1);
	var opt2 = document.createElement('option');
	opt2.value = 'CEDULA CIUDADANIA';
	opt2.innerHTML = 'CEDULA CIUDADANIA';
	select.appendChild(opt2);
	var opt3 = document.createElement('option');
	opt3.value = 'TARJETA IDENTIDAD';
	opt3.innerHTML = 'TARJETA IDENTIDAD';
	select.appendChild(opt3);
	var opt4 = document.createElement('option');
	opt4.value = 'REGISTRO CIVIL';
	opt4.innerHTML = 'REGISTRO CIVIL';
	select.appendChild(opt4);
	var opt5 = document.createElement('option');
	opt5.value = 'CEDULA EXTRANJERIA';
	opt5.innerHTML = 'CEDULA EXTRANGJERIA';
	select.appendChild(opt5);
	
	subnode_s.appendChild(label_s);
	subnode_s.appendChild(select);
	
	
	var subnode = document.createElement("div")
	subnode.setAttribute("class",  "col-md-3 bne_add_"+crnt+"_"+n);
	subnode.style.display = "none";
	
	var label = document.createElement("label");
	label.setAttribute("class", "col-md-12 control-label");
	label.setAttribute("for", "identificacion");
	label.textContent = "Identificación";
	
	var input = document.createElement("input");
	input.setAttribute("class", "form-control solo_numeros");
	input.setAttribute("placeholder", "Ingresa la identificación");
	input.setAttribute("name", "bne"+add+"_identification_"+crnt+"_"+n);
	
	subnode.appendChild(label);
	subnode.appendChild(input);
	
	var subnode_n = document.createElement("div")
	subnode_n.setAttribute("class",  "col-md-3");
	
	var label_n = document.createElement("label");		
	var input_n = document.createElement("input");
	label_n.setAttribute("class", "col-md-12 control-label");
	label_n.setAttribute("for", "names");
	label_n.textContent = "Nombres";
	input_n.setAttribute("class", "form-control");
	input_n.setAttribute("placeholder", "Ingresa los nombres");
	input_n.setAttribute("name", "bne"+add+"_names_"+crnt+"_"+n);
	
	subnode_n.appendChild(label_n);
	subnode_n.appendChild(input_n);
	
	var subnode_sn = document.createElement("div")
	subnode_sn.setAttribute("class",  "col-md-3");
	
	var label_sn = document.createElement("label");		
	var input_sn = document.createElement("input");
	label_sn.setAttribute("class", "col-md-12 control-label");
	label_sn.setAttribute("for", "surnames");
	label_sn.textContent = "Apellidos";
	input_sn.setAttribute("class", "form-control");
	input_sn.setAttribute("placeholder", "Ingresa los apellidos");
	input_sn.setAttribute("name", "bne"+add+"_surnames_"+crnt+"_"+n);
	
	subnode_sn.appendChild(label_sn);
	subnode_sn.appendChild(input_sn);
	
	var subnode_r = document.createElement("div")
	subnode_r.setAttribute("class",  "col-md-3 bne_add_"+crnt+"_"+n);
	subnode_r.style.display = "none";
	
	var label_r = document.createElement("label");		
	var input_r = document.createElement("input");
	label_r.setAttribute("class", "col-md-12 control-label");
	label_r.setAttribute("for", "relationship");
	label_r.textContent = "Parentesco";
	input_r.setAttribute("class", "form-control");
	input_r.setAttribute("placeholder", "Ingresa el parentesco");
	input_r.setAttribute("name", "bne"+add+"_relationship_"+crnt+"_"+n);
	
	subnode_r.appendChild(label_r);
	subnode_r.appendChild(input_r);
	
	var subnode_c = document.createElement("div")
	subnode_c.setAttribute("class",  "col-md-3 bne_add_"+crnt+"_"+n);
	subnode_c.style.display = "none";
	
	var label_c = document.createElement("label");		
	var input_c = document.createElement("input");
	label_c.setAttribute("class", "col-md-12 control-label");
	label_c.setAttribute("for", "movil_number");
	label_c.textContent = "Celular";
	input_c.setAttribute("class", "form-control solo_numeros");
	input_c.setAttribute("placeholder", "Ingresa el celular");
	input_c.setAttribute("name", "bne"+add+"_movil_number_"+crnt+"_"+n);
	
	subnode_c.appendChild(label_c);
	subnode_c.appendChild(input_c);
	
	var subnode_ss = document.createElement("div")
	subnode_ss.setAttribute("class",  "col-md-3 bne_add_"+crnt+"_"+n);
	subnode_ss.style.display = "none";
	
	var label_ss = document.createElement("label");
	label_ss.setAttribute("class", "col-md-12 control-label");
	label_ss.setAttribute("for", "typeid");
	label_ss.textContent = "Estado Civil";
	
	var select_ss=document.createElement("select");
	select_ss.setAttribute("class", "form-control");
	select_ss.setAttribute("name", "bne"+add+"_civil_status_"+crnt+"_"+n);
	var opt1_ss = document.createElement('option');
	opt1_ss.value = '';
	opt1_ss.innerHTML = 'Selecciona una opción';
	select_ss.appendChild(opt1_ss);
	var opt2_ss = document.createElement('option');
	opt2_ss.value = 'SOLTERO';
	opt2_ss.innerHTML = 'SOLTERO';
	select_ss.appendChild(opt2_ss);
	var opt3_ss = document.createElement('option');
	opt3_ss.value = 'COMPROMETIDO';
	opt3_ss.innerHTML = 'COMPROMETIDO';
	select_ss.appendChild(opt3_ss);
	var opt4_ss = document.createElement('option');
	opt4_ss.value = 'CASADO';
	opt4_ss.innerHTML = 'CASADO';
	select_ss.appendChild(opt4_ss);
	var opt5_ss = document.createElement('option');
	opt5_ss.value = 'DIVORSIADO';
	opt5_ss.innerHTML = 'DIVORSIADO';
	select_ss.appendChild(opt5_ss);
	var opt6_ss = document.createElement('option');
	opt6_ss.value = 'VIUDO';
	opt6_ss.innerHTML = 'VIUDO';
	select_ss.appendChild(opt6_ss);
	
	subnode_ss.appendChild(label_ss);
	subnode_ss.appendChild(select_ss);

	var subnode_br = document.createElement("div")
	subnode_br.setAttribute("class",  "col-md-3 bne_add_"+crnt+"_"+n);
	subnode_br.style.display = "none";
	
	var label_br = document.createElement("label");		
	var input_br = document.createElement("input");
	label_br.setAttribute("class", "col-md-12 control-label");
	label_br.setAttribute("for", "birthdate");
	label_br.textContent = "Fecha Nacimiento";
	input_br.setAttribute("class", "form-control birthdate_input");
	input_br.setAttribute("placeholder", "Ingresa la fecha de nacimiento");
	input_br.setAttribute("name", "bne"+add+"_birthdate_"+crnt+"_"+n);
	
	subnode_br.appendChild(label_br);
	subnode_br.appendChild(input_br);

	var subnode_ad = document.createElement("div")
	subnode_ad.setAttribute("class",  "col-md-3 bne_add_"+crnt+"_"+n);
	subnode_ad.style.display = "none";
	
	var label_ad = document.createElement("label");		
	var input_ad = document.createElement("input");
	label_ad.setAttribute("class", "col-md-12 control-label");
	label_ad.setAttribute("for", "adress");
	label_ad.textContent = "Dirección";
	input_ad.setAttribute("class", "form-control");
	input_ad.setAttribute("placeholder", "Ingresa la dirección");
	input_ad.setAttribute("name", "bne"+add+"_adress_"+crnt+"_"+n);
	
	subnode_ad.appendChild(label_ad);
	subnode_ad.appendChild(input_ad);


	var subnode_mn = document.createElement("div")
	subnode_mn.setAttribute("class",  "col-md-3 bne_add_"+crnt+"_"+n);
	subnode_mn.style.display = "none";
	var label_mn = document.createElement("label");
	label_mn.setAttribute("class", "col-md-12 control-label");	
	label_mn.textContent = "minicipio";

	var select = document.getElementsByClassName("cityes")[0];
	var select_mn=document.createElement("select");
	select_mn.setAttribute("class", "form-control");
	select_mn.setAttribute("name", "bne"+add+"_city_"+crnt+"_"+n);	
	for(var i=0;i<select.childElementCount;i++){
		var opt = select.options[i];
		var opt1 = document.createElement('option');		
		opt1.value = opt.value;
		opt1.innerHTML = opt.innerHTML;
		select_mn.appendChild(opt1);		
	}
	subnode_mn.appendChild(label_mn);
	subnode_mn.appendChild(select_mn);

	var subnode_em = document.createElement("div")
	subnode_em.setAttribute("class",  "col-md-3 bne_add_"+crnt+"_"+n);
	subnode_em.style.display = "none";
	
	var label_em = document.createElement("label");		
	var input_em = document.createElement("input");
	label_em.setAttribute("class", "col-md-12 control-label");
	label_em.setAttribute("for", "email");
	label_em.textContent = "Correo Electrónico";
	input_em.setAttribute("class", "form-control");
	input_em.setAttribute("placeholder", "Ingresa el correo electónico");
	input_em.setAttribute("name", "bne"+add+"_email_"+crnt+"_"+n);
	
	subnode_em.appendChild(label_em);
	subnode_em.appendChild(input_em);


	/*
	var subnode_o = document.createElement("div")
	subnode_o.setAttribute("class", "col-md-3");
	
	var label_o = document.createElement("label");		
	var input_o = document.createElement("input");
	label_o.setAttribute("class", "col-md-12 control-label");
	label_o.setAttribute("for", "more");
	label_o.textContent = "Otros Datos";
	input_o.setAttribute("class", "form-control");
	input_o.setAttribute("placeholder", "Separados por comas");
	input_o.setAttribute("name", "bne"+add+"_more_"+crnt+"_"+n);
	
	subnode_o.appendChild(label_o);
	subnode_o.appendChild(input_o);
	*/
	
	var subnode_b = document.createElement("div")
	subnode_b.setAttribute("class", "col-md-3");
	
	var label_b = document.createElement("label");
	var button_b = document.createElement("button");
	label_b.setAttribute("class", "col-md-12 control-label");
	label_b.setAttribute("for", "more");
	label_b.textContent = "Información Adicional";
	button_b.setAttribute("class", "col-md-12 btn btn-default btn_more");
	button_b.setAttribute("type", "button");
	button_b.setAttribute("id", "bne_more_"+crnt+"_"+n);
	button_b.textContent = "Información Adicional";
	
	subnode_b.appendChild(label_b);
	subnode_b.appendChild(button_b);
	
	var subnode_hr = document.createElement("div")
	subnode_hr.setAttribute("class", "col-md-12");
	var hr = document.createElement("hr");
	hr.setAttribute("size", 1);
	subnode_hr.appendChild(hr);
	
	node.appendChild(subnode_s);//selector type_id
	node.appendChild(subnode);//identification
	node.appendChild(subnode_n);//nombres
	node.appendChild(subnode_sn);//apellidos
	node.appendChild(subnode_r);//parentesco
	node.appendChild(subnode_c);//Celular
	node.appendChild(subnode_ss);//EstadoCivil
	node.appendChild(subnode_br);//birthdate
	node.appendChild(subnode_ad);//adress
	node.appendChild(subnode_mn);//city
	node.appendChild(subnode_em);//email
	//node.appendChild(subnode_o);//more
	node.appendChild(subnode_b);//boton
	node.appendChild(subnode_hr);
	
	document.getElementsByClassName("inputs_c_"+crnt)[0].appendChild(node);
	
	$('.btn_more').off("click");//borramos las anteriores eventos de la clase
	$('.btn_more').click(function(){
		if(this.innerHTML == "Información Adicional"){
			//se quiere mostrar los datos
			this.innerHTML = "Información Basica";
			$('.bne_add'+this.id.substr(8)).fadeIn();
		}else{
			if(this.innerHTML == "Información Basica"){
				//se quiere ocultar la información adicional
				this.innerHTML = "Información Adicional";
				$('.bne_add'+this.id.substr(8)).fadeOut();
			}
		}			
	});

	$( ".solo_numeros" ).keypress(function(evt) {
		 evt = (evt) ? evt : window.event;
	    var charCode = (evt.which) ? evt.which : evt.keyCode;
	    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
	        return false;
	    }
	    return true;
	});

	javascript:seg_user.iniciarDatepikerClass('birthdate_input');//nacimiento
};

clu_suscripcion.prototype.add_pay = function() {
	
	var node = document.createElement("div");
	node.setAttribute("class", "form-group");
	
	var subnode = document.createElement("div")
	subnode.setAttribute("class", "col-md-6");
	
	n=$("input[name='numer_p']").val();
	
	var label = document.createElement("label");
	label.setAttribute("class", "col-md-12 control-label");
	label.setAttribute("for", "pago_abono_"+n);
	label.textContent = "Abono";
	
	var input = document.createElement("input");
	input.setAttribute("id", "pago_abono_"+n);
	input.setAttribute("class", "form-control");
	input.setAttribute("placeholder", "Ingresa el abono");
	input.setAttribute("name", "pago_abono_"+n);
	
	subnode.appendChild(label);
	subnode.appendChild(input);
	
	var subnode_n = document.createElement("div")
	subnode_n.setAttribute("class", "col-md-6");
	
	var label_n = document.createElement("label");		
	var input_n = document.createElement("input");
	label_n.setAttribute("class", "col-md-12 control-label");
	label_n.setAttribute("for", "fecha_pago_"+n);
	label_n.textContent = "Fecha de Abono";
	input.setAttribute("id", "fecha_pago_"+n);
	input_n.setAttribute("class", "form-control fecha_pago");	
	input_n.setAttribute("placeholder", "Formato aaaa-mm-dd");
	input_n.setAttribute("name", "fecha_pago_"+n);
	
	subnode_n.appendChild(label_n);
	subnode_n.appendChild(input_n);
	
	node.appendChild(subnode);
	node.appendChild(subnode_n);
	
	$("input[name='numer_p']").val(parseInt(n)+1);
	document.getElementsByClassName("pay-form-inputs")[0].appendChild(node);
	$('.fecha_pago').datetimepicker({
		dateFormat: "yy-mm-dd",
		timeFormat: "hh:mm:ss",		
	});	
};
/*
clu_suscripcion.prototype.renovarRespuesta = function(result) {
	alert('Opción en construcción');	
};

clu_suscripcion.prototype.renovarsuscripcionRespuesta = function(result) {
	//borrado del tag para rehacerlo, para evitar trueqye de informacion entre entidades	
	document.getElementById('renovar_tab1').textContent="";
	document.getElementById('renovar_tab2').textContent="";
	document.getElementById('renovar_tab3').textContent="";
	document.getElementById('renovar_tab4').textContent="";

	var nodo1 = document.createElement("div");
	nodo1.setAttribute("class", "form-group");

	var subnodo1_1 = document.createElement("div")	
	subnodo1_1.setAttribute("class", "col-md-4");
	var label1_1 = document.createElement("label");
	var input1_1 = document.createElement("input");
	label1_1.setAttribute("class", "col-md-12 control-label");
	label1_1.setAttribute("for", "suscripcion_nombres");
	label1_1.textContent = "Nombres";
	input1_1.setAttribute("class", "form-control");
	input1_1.setAttribute("name", "suscripcion_nombres");	
	input1_1.setAttribute("placeholder", "Ingresa los nombres");
	subnodo1_1.appendChild(label1_1);
	subnodo1_1.appendChild(input1_1);

	var subnodo1_2 = document.createElement("div")	
	subnodo1_2.setAttribute("class", "col-md-4");
	var label1_2 = document.createElement("label");
	var input1_2 = document.createElement("input");
	label1_2.setAttribute("class", "col-md-12 control-label");
	label1_2.setAttribute("for", "suscripcion_apellidos");
	label1_2.textContent = "Apellidos";
	input1_2.setAttribute("class", "form-control");
	input1_2.setAttribute("name", "suscripcion_apellidos");	
	input1_2.setAttribute("placeholder", "Ingresa los apellidos");
	subnodo1_2.appendChild(label1_2);
	subnodo1_2.appendChild(input1_2);

	nodo1.appendChild(subnodo1_1);//Nombre de sucursal
	nodo1.appendChild(subnodo1_2);//Nombre de sucursal

	document.getElementById('renovar_tab1').appendChild(nodo1);

	$("#renovar_suscripcion_modal").modal();	
};
*/
clu_suscripcion.prototype.carnetRespuesta = function(result) {
	if(result.respuesta){
		if(result.data){			
			$("#suscripcion_carnet_modal .modal-body .tab_cnt_bnt1").html('');
			$("#suscripcion_carnet_modal .modal-body .tab_cnt_bnt2").html('');
			
			var suscciption_input = document.createElement("input");
			suscciption_input.setAttribute("type", "hidden");
			suscciption_input.setAttribute("name", "suscription_id");
			suscciption_input.setAttribute("value", clu_suscripcion.table.rows('.selected').data()[0]['id']);
			$("#suscripcion_carnet_modal .modal-body .tab_cnt_bnt1").append(suscciption_input);
			
			for(var i = 0; i < result.data.cnts.length; i++){
				//carnet
				if(result.data.cnts[i].type == 'suscription' || result.data.cnts[i].type == 'suscription_add' ){
					
					var node = document.createElement("div");
					//node.setAttribute("class", "col-md-12");
					node.setAttribute("style", "display: flex;");					
					
					var n_group = document.createElement("div");
					n_group.setAttribute("class", "checkbox");
					//n_group.setAttribute("style", "margin-right: 16px;");					
					//n_group.setAttribute("class", "col-md-3");
					
					var n_label = document.createElement("label");					
					
					var n_input = document.createElement("input");
					n_input.setAttribute("type", "checkbox");
					n_input.setAttribute("name", "cnt_"+result.data.cnts[i]['id']);
					n_input.setAttribute("value", result.data.cnts[i]['id']);
					
					
					n_label.appendChild(n_input);
					//n_label.innerHTML = n_label.innerHTML + "Carnet "+(i+1);
					var n_group_drop = document.createElement("div");
					n_group_drop.setAttribute("class", "dropdown");
					//n_group_drop.setAttribute("class", "col-md-9");
					
					var button_drop = document.createElement("label");
					button_drop.setAttribute("class", " dropdown-toggle");
					//button_drop.setAttribute("class", "col-md-12 btn btn-default dropdown-toggle");
					
					//button_drop.setAttribute("type", "button");
					button_drop.setAttribute("data-toggle", "dropdown");
					
					var button_drop_span = document.createElement("span");
					button_drop_span.textContent = "Carnet "+(i+1);
					
					var ul_drop = document.createElement("ul");
					ul_drop.setAttribute("class", "dropdown-menu");
					
					for(var j = 0; j < result.data.bnes.length; j++){
						if(result.data.cnts[i]['id'] == result.data.bnes[j]['license_id']){
							var li_drop = document.createElement("li");
							var li_drop_a = document.createElement("a");
							li_drop_a.setAttribute("href", "#");							
							li_drop_a.textContent = result.data.bnes[j]['names']+' '+result.data.bnes[j]['surnames']+' '+result.data.bnes[j]['relationship'] ;
							li_drop.appendChild(li_drop_a);
							ul_drop.appendChild(li_drop);
							var li_drop = '';
							var li_drop_a = '';
						}
					}
					
					button_drop.appendChild(button_drop_span);
					n_group_drop.appendChild(button_drop);
					n_group_drop.appendChild(ul_drop);
					n_label.appendChild(n_group_drop);					
					n_group.appendChild(n_label);
					node.appendChild(n_group);
					
					//node.appendChild(n_group_drop);
					
					document.getElementsByClassName("tab_cnt_bnt1")[0].appendChild(node);
					
				}else{
					
					/*
					var node = document.createElement("div");
					node.setAttribute("class", "col-md-12");
					node.setAttribute("style", "display: flex;");					
					
					var n_group = document.createElement("div");
					n_group.setAttribute("class", "checkbox");
					//n_group.setAttribute("style", "margin-right: 16px;");					
					n_group.setAttribute("class", "col-md-3");
					
					var n_label = document.createElement("label");					
					
					var n_input = document.createElement("input");
					n_input.setAttribute("type", "checkbox");
					n_input.setAttribute("value", result.data.cnts[i]['id']);
					
					
					n_label.appendChild(n_input);
					n_label.innerHTML = n_label.innerHTML + "Carnet "+(i+1);
					n_group.appendChild(n_label);
					node.appendChild(n_group);
						
					
					var n_group_drop = document.createElement("div");
					n_group_drop.setAttribute("class", "dropdown");
					n_group_drop.setAttribute("class", "col-md-9");
					
					var button_drop = document.createElement("button");
					button_drop.setAttribute("class", "col-md-12 btn btn-default dropdown-toggle");
					
					button_drop.setAttribute("type", "button");
					button_drop.setAttribute("data-toggle", "dropdown");
					
					var button_drop_span = document.createElement("span");
					button_drop_span.textContent = "Beneficiarios";
					
					var ul_drop = document.createElement("ul");
					ul_drop.setAttribute("class", "dropdown-menu");
					
					for(var j = 0; j < result.data.bnes.length; j++){
						if(result.data.cnts[i]['id'] == result.data.bnes[j]['license_id']){
							var li_drop = document.createElement("li");
							var li_drop_a = document.createElement("a");
							li_drop_a.setAttribute("href", "#");
							li_drop_a.textContent = result.data.bnes[j]['names']+' '+result.data.bnes[j]['surnames']+' '+result.data.bnes[j]['relationship'] ;
							li_drop.appendChild(li_drop_a);
							ul_drop.appendChild(li_drop);
							var li_drop = '';
							var li_drop_a = '';
						}
					}
					
					button_drop.appendChild(button_drop_span);
					n_group_drop.appendChild(button_drop);
					n_group_drop.appendChild(ul_drop);
					node.appendChild(n_group_drop);
					*/
					
					var node = document.createElement("div");
					//node.setAttribute("class", "col-md-12");
					node.setAttribute("style", "display: flex;");					
					
					var n_group = document.createElement("div");
					n_group.setAttribute("class", "checkbox");
					//n_group.setAttribute("style", "margin-right: 16px;");					
					//n_group.setAttribute("class", "col-md-3");
					
					var n_label = document.createElement("label");					
					
					var n_input = document.createElement("input");
					n_input.setAttribute("type", "checkbox");
					n_input.setAttribute("name", "cnt_"+result.data.cnts[i]['id']);
					n_input.setAttribute("value", result.data.cnts[i]['id']);
					
					
					n_label.appendChild(n_input);
					//n_label.innerHTML = n_label.innerHTML + "Carnet "+(i+1);
					var n_group_drop = document.createElement("div");
					n_group_drop.setAttribute("class", "dropdown");
					//n_group_drop.setAttribute("class", "col-md-9");
					
					var button_drop = document.createElement("label");
					button_drop.setAttribute("class", " dropdown-toggle");
					//button_drop.setAttribute("class", "col-md-12 btn btn-default dropdown-toggle");
					
					//button_drop.setAttribute("type", "button");
					button_drop.setAttribute("data-toggle", "dropdown");
					
					var button_drop_span = document.createElement("span");
					button_drop_span.textContent = "Carnet "+(i+1);
					
					var ul_drop = document.createElement("ul");
					ul_drop.setAttribute("class", "dropdown-menu");
					
					for(var j = 0; j < result.data.bnes.length; j++){
						if(result.data.cnts[i]['id'] == result.data.bnes[j]['license_id']){
							var li_drop = document.createElement("li");
							var li_drop_a = document.createElement("a");
							li_drop_a.setAttribute("href", "#");							
							li_drop_a.textContent = result.data.bnes[j]['names']+' '+result.data.bnes[j]['surnames']+' '+result.data.bnes[j]['relationship'] ;
							li_drop.appendChild(li_drop_a);
							ul_drop.appendChild(li_drop);
							var li_drop = '';
							var li_drop_a = '';
						}
					}
					
					button_drop.appendChild(button_drop_span);
					n_group_drop.appendChild(button_drop);
					n_group_drop.appendChild(ul_drop);
					n_label.appendChild(n_group_drop);					
					n_group.appendChild(n_label);
					node.appendChild(n_group);
					
					//node.appendChild(n_group_drop);
					document.getElementsByClassName("tab_cnt_bnt2")[0].appendChild(node);
					
				}	
			}
			$( "input[type=checkbox]" ).prop( "checked", false );
		}
	}
	
	$("#suscripcion_carnet_modal").modal();
};

clu_suscripcion.prototype.carnetReprintRespuesta = function(result) {
	
	$("#suscripcion_seereprint_modal .modal-body .row_izq").html('');
	
	if(result.respuesta){		
		if(result.data){
			$("#suscripcion_seereprint_modal .modal-body .row_izq").html($("#suscripcion_seereprint_modal .modal-body .row_izq").html()+'<input type ="hidden" name="suscription_id" value="'+clu_suscripcion.table.rows('.selected').data()[0]['id']+'">');
			$("#suscripcion_seereprint_modal .modal-body .row_izq").html($("#suscripcion_seereprint_modal .modal-body .row_izq").html()+' <div class="col-md-12" style = "border-bottom: 1px solid black;" > <div class="col-md-1"></div> <div class="col-md-3"><b>Fecha Impresión</b></div> <div class="col-md-2"><b>Precio</b></div> <div class="col-md-6"><b>Descripción</b></div> </div>');
			for(var i = 0; i < result.data.cnts_print.length; i++){
				$("#suscripcion_seereprint_modal .modal-body .row_izq").html($("#suscripcion_seereprint_modal .modal-body .row_izq").html()+' <div class="col-md-12" style = "border-bottom: 1px solid black;" > <div class="col-md-1">  <input type="checkbox" name = "cnt_print_'+result.data.cnts_print[i].id+'" value = "'+result.data.cnts_print[i].id+'" checked= "checked" > </div> <div class="col-md-3">'+result.data.cnts_print[i].date+'</div> <div class="col-md-2">'+result.data.cnts_print[i].price+'</div> <div class="col-md-6">'+result.data.cnts_print[i].description+'</div> </div>');
			}
			
		}
	}
	$("#suscripcion_seereprint_modal").modal();
};

clu_suscripcion.prototype.carnetsRespuesta = function(result) {
	alert('listo');
};

clu_suscripcion.prototype.opt_cargarsus = function(result) {
	$('#suscripcion_cargasus_modal').modal();
};


var clu_suscripcion = new clu_suscripcion();
