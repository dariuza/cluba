@extends('app')

@section('content')
	<style>
		.right_col{
			overflow: auto;
		}
		.name_user{
			/*background-color: #D9DEE4;*/
		    color: #515356;		    		   
		    /*padding: 1%;*/
		    text-align: center;	    	
		}	
		.option_mod{		
			border: 1px solid #515356;
	    	border-radius: 4px;
	    	margin-top: 10px;
	    	/*padding: 3% 3% 3% 5%;*/
	    	background-color: #007BC1;
		}
		.message_mod{
			cursor:pointer;
			color: #515356;
			padding: 2%;
		}
		.message_mod:hover {
			background-color: #D9DEE4;
			color: #515356;
		}
		/*
		.ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
		.ui-timepicker-div dl { text-align: left; }
		.ui-timepicker-div dl dt { float: left; clear:left; padding: 0 0 0 5px; }
		.ui-timepicker-div dl dd { margin: 0 10px 10px 40%; }
		.ui-timepicker-div td { font-size: 90%; }
		.ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }
		.ui-timepicker-div .ui_tpicker_unit_hide{ display: none; }
		
		.ui-timepicker-div .ui_tpicker_time .ui_tpicker_time_input { background: none; color: inherit; border: none; outline: none; border-bottom: solid 1px #555; width: 95%; }
		.ui-timepicker-div .ui_tpicker_time .ui_tpicker_time_input:focus { border-bottom-color: #aaa; }
		
		.ui-timepicker-rtl{ direction: rtl; }
		.ui-timepicker-rtl dl { text-align: right; padding: 0 5px 0 0; }
		.ui-timepicker-rtl dl dt{ float: right; clear: right; }
		.ui-timepicker-rtl dl dd { margin: 0 40% 10px 10px; }
		*/
		/* Shortened version style */
		/*
		.ui-timepicker-div.ui-timepicker-oneLine { padding-right: 2px; }
		.ui-timepicker-div.ui-timepicker-oneLine .ui_tpicker_time, 
		.ui-timepicker-div.ui-timepicker-oneLine dt { display: none; }
		.ui-timepicker-div.ui-timepicker-oneLine .ui_tpicker_time_label { display: block; padding-top: 2px; }
		.ui-timepicker-div.ui-timepicker-oneLine dl { text-align: right; }
		.ui-timepicker-div.ui-timepicker-oneLine dl dd, 
		.ui-timepicker-div.ui-timepicker-oneLine dl dd > div { display:inline-block; margin:0; }
		.ui-timepicker-div.ui-timepicker-oneLine dl dd.ui_tpicker_minute:before,
		.ui-timepicker-div.ui-timepicker-oneLine dl dd.ui_tpicker_second:before { content:':'; display:inline-block; }
		.ui-timepicker-div.ui-timepicker-oneLine dl dd.ui_tpicker_millisec:before,
		.ui-timepicker-div.ui-timepicker-oneLine dl dd.ui_tpicker_microsec:before { content:'.'; display:inline-block; }
		.ui-timepicker-div.ui-timepicker-oneLine .ui_tpicker_unit_hide,
		.ui-timepicker-div.ui-timepicker-oneLine .ui_tpicker_unit_hide:before{ display: none; }
		*/
	</style>
	<div class="col-md-12 col-md-offset-0 container-fluid" >
		<div class="name_user">
			<div>			
				Modulo {{Session::get('modulo.modulo')}}
			</div>		
		</div>
		<div class="option_mod col-md-12" data-spy="affix">
			@if(Session::has('modulo'))
            	@foreach (Session::get('opaplus.usuario.permisos')[Session::get('modulo.id_app')]['modulos'][Session::get('modulo.categoria')][Session::get('modulo.id_mod')]['opciones'] as $key => $opc)            		
            		@if($opc['lugar'] == Session::get('opaplus.usuario.lugar.lugar'))
	            		@if($opc['vista'] == 'listar')	            		
	            			@if($opc['accion'] == 'actualizar' OR $opc['accion'] == 'borrar')
	            				<div class="col-md-1" data-toggle="tooltip" title = "{{$opc[$key]}}">
		            				<a href="javascript:clu_beneficiario.opt_select('{{json_decode(Session::get('opaplus.usuario.permisos')[Session::get('modulo.id_app')]['modulos'][Session::get('modulo.categoria')][Session::get('modulo.id_mod')]['preferencias'])->controlador}}','{{($opc['accion'])}}/{{Session::get('modulo.id_app')}}/{{Session::get('modulo.categoria')}}/{{Session::get('modulo.id_mod')}}')" class="site_title site_title2" style = "text-decoration: none; ">
			            				<i class="{{$opc['icono']}}"></i>
			            			</a>
		            			</div>	            				           			
	            			@elseif($opc['accion'] == 'mirar')	            			
	            				<div class="col-md-1" data-toggle="tooltip" title = "{{$opc[$key]}}">
		            				<a href="javascript:clu_beneficiario.opt_ver()" class="site_title site_title2" style = "text-decoration: none; ">
			            				<i class="{{$opc['icono']}}"></i>	            				           				
			            				<!--  <span >{{$opc[$key]}}</span> -->	            				
			            			</a>
		            			</div>		            			
		            		@elseif($opc['accion'] == 'abonar')
	            				<div class="col-md-1 bnt_abonar_bne" data-toggle="tooltip" title = "{{$opc[$key]}}">
		            				<a href="#" class="site_title site_title2" style = "text-decoration: none; ">
			            				<i class="{{$opc['icono']}}"></i>	            				           				
			            				<!--  <span >{{$opc[$key]}}</span> -->	            				
			            			</a>
		            			</div>	        				
	            			@elseif($opc['accion'] == 'botar')
	            				<div id = "0" class = "col-md-1 bnt_lugar" data-toggle="tooltip" title = "{{$opc[$key]}}">           				
	            					<a href="#" class="site_title site_title2" style = "text-decoration: none; ">
			            				<i class="{{$opc['icono']}}"></i>
			            			</a>
	            				</div>	            			
	            			@elseif($opc['accion'] == 'recuperar')
	            				<div id = "1" class = "message_mod bnt_lugar" >           				
	            					<span class="{{$opc['icono']}}" aria-hidden="true" style = "margin-right:5px; color:#666699;" ></span>{{$opc[$key]}}
	            				</div>
	            			@elseif($opc['accion'] == 'eliminar')
	            				<div id = "-1" class = "message_mod bnt_lugar" >           				
	            					<span class="{{$opc['icono']}}" aria-hidden="true" style = "margin-right:5px; color:#666699;" ></span>{{$opc[$key]}}
	            				</div>
	            			@else
	            			<div class="col-md-1" data-toggle="tooltip" title = "{{$opc[$key]}}">           			
		            			<a href="{{url(json_decode(Session::get('opaplus.usuario.permisos')[Session::get('modulo.id_app')]['modulos'][Session::get('modulo.categoria')][Session::get('modulo.id_mod')]['preferencias'])->controlador)}}/{{($opc['accion'])}}/{{Session::get('modulo.id_app')}}/{{Session::get('modulo.categoria')}}/{{Session::get('modulo.id_mod')}}" class="site_title site_title2" style = "text-decoration: none; ">
		            				<i class="{{$opc['icono']}}"></i>	            				           				
		            				<!--  <span >{{$opc[$key]}}</span> -->	            				
		            			</a>
	            			</div>
	            			
	            			@endif   
		            	@endif
	            	@endif	            		       		
            	@endforeach
            @endif			
			
		</div>
	</div>
	<div class="col-md-12 col-md-offset-0">
	<div class = "alerts">
			@if (count($errors) > 0)
				<div class="alert alert-danger">
					<strong>Algo no va bien con el Modulo!</strong> Hay problemas con con los datos diligenciados.<br><br>
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			
			@if(Session::has('message'))
				<div class="alert alert-info">
					<strong>¡Modulo Beneficiarios!</strong> La operación se ha realizado adecuadamente.<br><br>
					<ul>								
						<li>{{ Session::get('message') }}</li>
					</ul>
				</div>
                
            @endif
            <!-- error llega cuando se esta recuperando la contraseña inadecuadamente -->
            @if(Session::has('error'))
				<div class="alert alert-danger">
					<strong>¡Algo no va bien!</strong> Hay problemas con los datos diligenciados.<br><br>
					<ul>								
						<li>{{ Session::get('error') }}</li>								
					</ul>
				</div>
                
            @endif
    </div>
	<table id="example" class="display " cellspacing="0" width="100%">
         <thead>
            <tr>
            	@if(Session::has('modulo.fillable'))
            		@foreach (Session::get('modulo.fillable') as $col)
            			<th>{{$col}}</th>
            		@endforeach
            	@endif               
            </tr>
        </thead>              
    </table> 
    <!-- Form en blanco para capturar la url editar y eliminar-->
    {!! Form::open(array('id'=>'form_abonar','url' => 'beneficiario/abonar')) !!}
    {!! Form::close() !!}   
	</div>		
@endsection

@section('modal')

	<div class="modal fade" id="beneficiario_ver_modal" role="dialog" data-backdrop="false">
	    <div class="modal-dialog">	    
	      <!-- Modal content-->
	      <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Resumen Beneficiario</h4>
				</div>
				<div class = "alerts-module"></div>	
							
				<div class="modal-body">
					 <ul class="nav nav-tabs">
						<li role="presentation" class="active"><a href="#tab1" data-toggle="tab">Beneficiario</a></li>
						<li role="presentation"><a href="#tab2" data-toggle="tab">Suscriptor</a></li>							
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab1">
							<div class="row ">
								<div class="col-md-12 col-md-offset-0 row_izq"></div>						
							</div>
						</div>
						<div class="tab-pane fade " id="tab2">
							<div class="row ">
								<div class="col-md-12 col-md-offset-0 tab2"></div>						
							</div>
						</div>						
					</div>
		        </div>
		        
		        
		        <div class="modal-footer">		          
		          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>		                  
		        </div>     
	      </div>
      </div>     
	</div>
	
	
	<div class="modal fade" id="beneficiario_abono_modal" role="dialog" data-backdrop="false">
	    <div class="modal-dialog">	    
	      <!-- Modal content-->
	      <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Abono Beneficiario</h4>
				</div>
				<div class = "alerts-module"></div>				
				<div class="modal-body">
					<div class="row ">
						<div class="col-md-12 col-md-offset-0 row_izq"></div>						
					</div>
		        </div>
		        <div class="modal-footer">
		          <button type="button" class="btn btn-default btn-enviar-abono-bne" >Enviar Abono</button>
		          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>		                  
		        </div>     
	      </div>
      </div>
	</div>
	

@endsection

@section('script')	
	{{--<script type="text/javascript" src="{{ asset('/js/lib/datetimepiker.js') }}"></script>--}}	
	<script type="text/javascript">  	
		javascript:clu_beneficiario.table = $('#example').DataTable( {
		    "responsive": true,
		    "processing": true,
		    "bLengthChange": false,
		    "serverSide": true,	        
		    "ajax": "{{url('beneficiario/listarajax')}}",	
		    "iDisplayLength": 25,       
		    "columns": [				   
		        { "data": "beneficiario"},        	            
		        { "data": "suscriptor" },
		        { "data": "relationship" },
		        { "data": "movil_number" },		        
		        { "data": "fr_movil" }		                   
		    ],	       
		    "language": {
		        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		    },		   
		    "fnRowCallback": function( nRow, aData ) {
		        //pintar el fondo de la fila		        
		        //$(nRow).children()[5].style.backgroundColor = aData.next_alert;
		        //$(nRow).children()[4].style.backgroundColor = aData.alert;		        
		        
            }
		});
		@if(Session::has('filtro'))
			clu_beneficiario.table.search( "{{Session::get('filtro')}}" ).draw();
		@endif	
		javascript:$('#example tbody').on( 'click', 'tr', function () {
		    if ($(this).hasClass('selected')) {
		        $(this).removeClass('selected');
		    }
		    else {
		    	clu_beneficiario.table.$('tr.selected').removeClass('selected');
		        $(this).addClass('selected');
		    }
		});

		$('.bnt_abonar_bne').click(function(e){
  	  		//e.preventDefault();//evita que la pagina se refresque
	  	  	if(clu_beneficiario.table.rows('.selected').data().length){
	  	  		if(clu_beneficiario.table.rows('.selected').data()[0].state == 'Pago pendiente'){
		  	  		var datos = new Array();
		  	  		datos['id'] = clu_beneficiario.table.rows('.selected').data()[0].id;		  	  			  	  		
		  	  		seg_ajaxobject.peticionajax($('#form_abonar').attr('action'),datos,"clu_beneficiario.abonarRespuesta");
		  	  	}else{
		  	  	$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Abono Invalido!</strong>No se puede abonar a este Beneficiario!!!.<br><br><ul><li>Solo se puede abonar a una beneficiarios cuando su estado es "Pago pendiente"</li></ul></div>');
		  	  	}		
		  	  	
	  		}else{
	  			$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Seleccione un registro!</strong> Esta opción requiere la selección de un registro!!!.<br><br><ul><li>Selecciona un registro dando click sobre él, luego prueba nuevamente la opción</li></ul></div>');
	  		}  	  		
  		});

		$('.btn-enviar-abono-bne').click(function(e){
			if($('#payment').val()){
				if(parseInt($('#payment').val()) > 0){
					if(parseInt($('#payment').val()) <= parseInt(clu_beneficiario.table.rows('.selected').data()[0].mora)){

						var datos = new Array();
			  	  		datos['suscription_id'] = clu_beneficiario.table.rows('.selected').data()[0].id;
			  	  		datos['price'] = clu_beneficiario.table.rows('.selected').data()[0].price;
			  	  		datos['mora'] = clu_beneficiario.table.rows('.selected').data()[0].mora;			  	  		
			  	  		datos['payment'] = $('#payment').val();
			  	  		datos['date_payment'] = $('#date_payment').val();
				  	  	if(datos['date_payment']==""){
			  	  			datos['date_payment']=0;
			  	  		}

				  	  $('#beneficiario_abono_modal').modal('toggle');
			  	  		//alert("abonarsave/"+datos['payment']+"/"+datos['date_payment']+"/"+datos['suscription_id']+"/"+datos['mora']);
			  	  		window.location = "abonarsave/"+datos['payment']+"/"+datos['date_payment']+"/"+datos['suscription_id']+"/"+datos['mora'];

						}else{
		  	  			$('.alerts-module').html('<div class="alert alert-danger fade in"><strong>¡El campo Valor no esta debidamente Diligenciado!</strong> El ingreso de un abono requiere ser menor a la Mora de la Suscripción!!!.<br><br><ul><li>Diligencie un valor numerico en el campo para el valor, luego prueba nuevamente dando click en el botón Enviar Abono; el abomo: '+$('#payment').val()+' es mayor a la mora: '+clu_beneficiario.table.rows('.selected').data()[0].mora+'. El valor maximo del abono es el valor de la mora. </li></ul></div>');
		  	  		}
				}else{
	  	  			$('.alerts-module').html('<div class="alert alert-danger fade in"><strong>¡El Valor no es valido!</strong> El ingreso de un abono requiere de un Valor!!!.<br><br><ul><li>Diligencie un valor numerico en el campo para el valor, luego prueba nuevamente dando clik en el botón Enviar Abono</li></ul></div>');
	  	  		}
			}else{  			
	  			$('.alerts-module').html('<div class="alert alert-danger fade in"><strong>¡El campo Valor esta vacio!</strong> El ingreso de un abono requiere de un Valor!!!.<br><br><ul><li>Diligencie un valor numerico en el campo para el valor, luego prueba nuevamente dando clik en el botón Enviar Abono</li></ul></div>');
	  		}
		});
		
		
	</script>
@endsection