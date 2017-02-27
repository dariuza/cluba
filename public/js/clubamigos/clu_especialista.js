function clu_especialista() {
	this.datos_pie = [];
	this.table = '';
	
}

clu_especialista.prototype.onjquery = function() {
};

clu_especialista.prototype.validateNuevoEspecialista = function() {
	if($("#form_nuevo_especialista :input")[1].value =="" || $("#form_nuevo_especialista :input")[2].value ==""){
		$('#especialidad_nuevo_modal .alerts-module').html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close close_alert_product" data-dismiss="alert">&times;</button><strong>!Envio Fallido!</strong></br> Faltan campos por diligenciar.</div>');
		//pintar los inputs problematicos
		for(var i=0; i < $("#form_nuevo_especialista :input").length ; i++){
	        if( i==1 || i==2 ) {
	            if($("#form_nuevo_especialista :input")[i].value ==""){
	                $($("#form_nuevo_especialista :input")[i]).addClass('input_danger');
	            }	            
	        }
        }
        $(".close_alert_product").on('click', function () { 
        	$("#form_nuevo_especialista :input").removeClass("input_danger");        	
        });
		return false;
	}
	return true;
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

clu_especialista.prototype.add_dispo = function(add) {
	
	var n = document.getElementsByClassName('tab_dispo3')[0].childElementCount + 1 ;
	var nodo = document.createElement("div");
	nodo.setAttribute("class", "form-group col-md-12");

	var subnodo_1 = document.createElement("div")
	subnodo_1.setAttribute("class", "col-md-2 ");
	var label_1 = document.createElement("label");
	label_1.setAttribute("class", "col-md-12 control-label");
	label_1.setAttribute("for", "dispo_dia_"+n);
	label_1.textContent = "Dìa";
	var select_1=document.createElement("select");
	select_1.setAttribute("class", "form-control");
	select_1.setAttribute("name", "dispo_dia_"+n);
	var opt1 = document.createElement('option');
	opt1.value = '';
	opt1.innerHTML = 'Elije Dìa';
	select_1.appendChild(opt1);
	var opt2 = document.createElement('option');
	opt2.value = 'LUNES';
	opt2.innerHTML = 'LUNES';
	select_1.appendChild(opt2);
	var opt3 = document.createElement('option');
	opt3.value = 'MARTES';
	opt3.innerHTML = 'MARTES';
	select_1.appendChild(opt3);
	var opt4 = document.createElement('option');
	opt4.value = 'MIÈRCOLES';
	opt4.innerHTML = 'MIÈRCOLES';
	select_1.appendChild(opt4);
	var opt5 = document.createElement('option');
	opt5.value = 'JUEVES';
	opt5.innerHTML = 'JUEVES';
	select_1.appendChild(opt5);
	var opt6 = document.createElement('option');
	opt6.value = 'VIERNES';
	opt6.innerHTML = 'VIERNES';
	select_1.appendChild(opt6);
	var opt7 = document.createElement('option');
	opt7.value = 'SABADO';
	opt7.innerHTML = 'SABADO';
	select_1.appendChild(opt7);
	var opt8 = document.createElement('option');
	opt8.value = 'DOMINGO';
	opt8.innerHTML = 'DOMINGO';
	select_1.appendChild(opt8);
	subnodo_1.appendChild(label_1);
	subnodo_1.appendChild(select_1);

	var subnodo_2 = document.createElement("div")
	subnodo_2.setAttribute("class", "col-md-2 ");
	var label_2 = document.createElement("label");
	label_2.setAttribute("class", "col-md-12 control-label");
	label_2.setAttribute("for", "dispo_hora_inicio_"+n);
	label_2.textContent = "Hora Inicio";
	var div_2 = document.createElement("div");
	div_2.setAttribute("class", "input-group bootstrap-timepicker timepicker");
	var input_2 = document.createElement("input");
	input_2.setAttribute("class", "form-control input-small");
	input_2.setAttribute("placeholder", "Hora Inicio HH:mm");
	input_2.setAttribute("name", "dispo_hora_inicio_"+n);
	var span_2 = document.createElement("span");
	span_2.setAttribute("class", "input-group-addon");
	var i_2 = document.createElement("span");
	i_2.setAttribute("class", "glyphicon glyphicon-time");
	span_2.appendChild(i_2);
	div_2.appendChild(input_2);
	div_2.appendChild(span_2);
	subnodo_2.appendChild(label_2);
	subnodo_2.appendChild(div_2);

	var subnodo_3 = document.createElement("div")
	subnodo_3.setAttribute("class", "col-md-2 ");
	var label_3 = document.createElement("label");
	label_3.setAttribute("class", "col-md-12 control-label");
	label_3.setAttribute("for", "dispo_hora_fin_"+n);
	label_3.textContent = "Hora Fin";
	var div_3 = document.createElement("div");
	div_3.setAttribute("class", "input-group bootstrap-timepicker timepicker");
	var input_3 = document.createElement("input");
	input_3.setAttribute("class", "form-control input-small");
	input_3.setAttribute("placeholder", "Hora Fin HH:mm");
	input_3.setAttribute("name", "dispo_hora_fin_"+n);
	var span_3 = document.createElement("span");
	span_3.setAttribute("class", "input-group-addon");
	var i_3 = document.createElement("span");
	i_3.setAttribute("class", "glyphicon glyphicon-time");
	span_3.appendChild(i_3);
	div_3.appendChild(input_3);
	div_3.appendChild(span_3);
	subnodo_3.appendChild(label_3);
	subnodo_3.appendChild(div_3);

	var subnodo_4 = document.createElement("div")
	subnodo_4.setAttribute("class", "col-md-6 ");
	var label_4 = document.createElement("label");
	label_4.setAttribute("class", "col-md-12 control-label");
	label_4.setAttribute("for", "dispo_especialidades_select_"+n);
	label_4.textContent = "Especialidades";
	var select_4=document.createElement("select");
	select_4.setAttribute("class", "form-control chosen-select");
	select_4.setAttribute("multiple", "multiple");
	select_4.setAttribute("tabindex", "4");	
	select_4.setAttribute("name", "dispo_especialidades_select_"+n);
	select_4.setAttribute("id", "dispo_especialidades_select_"+n);
	select_4.setAttribute("data-placeholder", "Selecciona las elpecialidades");
	for(var i = 0 ; i < $('#dispo_especialidades_select_1')[0].options.length ; i++){
		var opt4 = document.createElement('option');
		opt4.value = $('#dispo_especialidades_select_1')[0].options[i].value;
		opt4.innerHTML = $('#dispo_especialidades_select_1')[0].options[i].innerHTML;
		select_4.appendChild(opt4);
	}
	var input_4 = document.createElement("input");
	input_4.setAttribute("type", "hidden"); 
	input_4.setAttribute("class", "col-md-12 control-label");
	input_4.setAttribute("name", "dispo_especialidades_"+n);
	input_4.setAttribute("id", "dispo_especialidades_"+n);
	subnodo_4.appendChild(label_4);
	subnodo_4.appendChild(select_4);
	subnodo_4.appendChild(input_4);

	nodo.appendChild(subnodo_1);//Selector de Dìa
	nodo.appendChild(subnodo_2);//hora inicio
	nodo.appendChild(subnodo_3);//hora fin
	nodo.appendChild(subnodo_4);//especialidades
	document.getElementsByClassName('tab_dispo3')[0].appendChild(nodo);

	$('.input-small').timepicker({showMeridian:false});
	$('.chosen-select').chosen();
	$('.chosen-container').width('100%');		
	$("#dispo_especialidades_select_"+n).chosen().change(function(event) {
		$('#dispo_especialidades_'+n).val($("#dispo_especialidades_select_"+n).chosen().val());		    
	});	
};

clu_especialista.prototype.add_special = function(add) {
	var n = document.getElementsByClassName('tab_dispo2')[0].childElementCount + 1 ;
	var nodo = document.createElement("div");
	nodo.setAttribute("class", "form-group");

	var subnodo_1 = document.createElement("div")
	subnodo_1.setAttribute("class", "col-md-3 ");
	var label_1 = document.createElement("label");
	label_1.setAttribute("class", "col-md-12 control-label");
	label_1.setAttribute("for", "espe_especialidad_"+n);
	label_1.textContent = "Especialidad";
	var select_1=document.createElement("select");
	select_1.setAttribute("class", "form-control");	
	select_1.setAttribute("name", "espe_especialidad_"+n);
	select_1.setAttribute("id", "espe_especialidad_"+n);
	select_1.setAttribute("data-placeholder", "Selecciona la elpecialidad");
	for(var i = 0 ; i < $('#espe_especialidad_1')[0].options.length ; i++){
		var opt1 = document.createElement('option');
		opt1.value = $('#espe_especialidad_1')[0].options[i].value;
		opt1.innerHTML = $('#espe_especialidad_1')[0].options[i].innerHTML;
		select_1.appendChild(opt1);
	}
	subnodo_1.appendChild(label_1);
	subnodo_1.appendChild(select_1);

	var subnodo_2 = document.createElement("div")
	subnodo_2.setAttribute("class", "col-md-3 ");
	var label_2 = document.createElement("label");
	var input_2 = document.createElement("input");
	label_2.setAttribute("class", "col-md-12 control-label");
	label_2.setAttribute("for", "espe_precio_particular_"+n);
	label_2.textContent = "Precio Particular";		
	input_2.setAttribute("class", "form-control solo_numeros");
	input_2.setAttribute("name", "espe_precio_particular_"+n);	
	input_2.setAttribute("placeholder", "Ingresa Precio");
	subnodo_2.appendChild(label_2);
	subnodo_2.appendChild(input_2);

	var subnodo_3 = document.createElement("div")
	subnodo_3.setAttribute("class", "col-md-3 ");
	var label_3 = document.createElement("label");
	var input_3 = document.createElement("input");
	label_3.setAttribute("class", "col-md-12 control-label");
	label_3.setAttribute("for", "espe_precio_suscriptor_"+n);
	label_3.textContent = "Precio Suscriptor";		
	input_3.setAttribute("class", "form-control solo_numeros");
	input_3.setAttribute("name", "espe_precio_suscriptor_"+n);	
	input_3.setAttribute("placeholder", "Ingresa Suscriptor");
	subnodo_3.appendChild(label_3);
	subnodo_3.appendChild(input_3);


	var subnodo_4 = document.createElement("div")
	subnodo_4.setAttribute("class", "col-md-3");
	var label_4 = document.createElement("label");
	label_4.setAttribute("class", "col-md-12 control-label");
	label_4.setAttribute("for", "espe_duracion_"+n);
	label_4.textContent = "Tiempo Duración";
	var div_4 = document.createElement("div");
	div_4.setAttribute("class", "input-group bootstrap-timepicker timepicker");
	var input_4 = document.createElement("input");
	input_4.setAttribute("class", "form-control input-small");
	input_4.setAttribute("placeholder", "Duración HH:mm");
	input_4.setAttribute("name", "espe_duracion_"+n);
	var span_4 = document.createElement("span");
	span_4.setAttribute("class", "input-group-addon");
	var i_4 = document.createElement("span");
	i_4.setAttribute("class", "glyphicon glyphicon-time");
	span_4.appendChild(i_4);
	div_4.appendChild(input_4);
	div_4.appendChild(span_4);
	subnodo_4.appendChild(label_4);
	subnodo_4.appendChild(div_4);

	nodo.appendChild(subnodo_1);//Selector de especialidad
	nodo.appendChild(subnodo_2);//Precio particular
	nodo.appendChild(subnodo_3);//Precio suscriptor
	nodo.appendChild(subnodo_4);//Tiempo duracion

	document.getElementsByClassName('tab_dispo2')[0].appendChild(nodo);

	$('.input-small').timepicker({showMeridian:false});
	$( ".solo_numeros" ).keypress(function(evt) {
		 evt = (evt) ? evt : window.event;
	    var charCode = (evt.which) ? evt.which : evt.keyCode;
	    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
	        return false;
	    }
	    return true;
	});	
	
};

var clu_especialista = new clu_especialista();
