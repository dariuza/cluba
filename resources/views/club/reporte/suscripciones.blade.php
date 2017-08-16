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
	    	background-color: #5A738E;
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
		.site_title2{
			color: #5A738E !important;
		}
		.site_title2 i{
			border: 1px solid #5A738E;
		}
		*/
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
		
		/* Shortened version style */
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
		
		#sell_all{
			cursor:pointer;
		}
		#sell_reprint{
			cursor:pointer;
			margin-bottom: 10px;
		}
		.tab_cnt_bnt1{
			/*margin-top: 10px;*/
		}
		.tab_cnt_bnt2{
			/*margin-top: 10px;*/
		}
	</style>
	<!-- Mensajes y alertas -->
	<!-- Este se usa para validar formularios -->
	@if (count($errors) > 0)
		<div class="alert alert-danger fade in">
			<strong>Algo no va bien con el modulo eneficiarios!</strong> Hay problemas con con los datos diligenciados.<br><br>
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
				</ul>
		</div>
	@endif	
	<!-- Este se usa para mostrar mensajes -->		
	@if(Session::has('message'))
		<div class="alert alert-info fade in">
			<strong>¡Actualización de Informacion!</strong> El registro se ha actualizado adecuadamente.<br><br>
			<ul>								
				<li>{{ Session::get('message') }}</li>
			</ul>
		</div>                
	@endif
    <!-- Mesnsajes de error -->
   @if(Session::has('error'))
		<div class="alert alert-danger fade in">
			<strong>¡Algo no va bien!</strong> Hay problemas con los datos diligenciados.<br><br>
				<ul>								
					<li>{{ Session::get('error') }}</li>								
				</ul>
		</div>                
	@endif
	
	<div class="row">
		<div class="col-md-12 col-md-offset-0 container-fluid" >
			<div class="col-md-12 col-md-offset-0 name_user">
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
			            				<a href="javascript:clu_reporte.opt_ver()" class="site_title site_title2" style = "text-decoration: none; ">
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
		            			@elseif($opc['accion'] == 'reportegeneral')
		            				<div class="col-md-1" data-toggle="tooltip" title = "{{$opc[$key]}}">           			
				            			<a href="javascript:clu_reporte.opt_report_general()" class="site_title site_title2" style = "text-decoration: none; ">
				            				<i class="{{$opc['icono']}}"></i>				            				
				            			</a>
			            			</div>
		            			@else
		            			<div class="col-md-1" data-toggle="tooltip" title = "{{$opc[$key]}}">           			
			            			<a href="{{url(json_decode(Session::get('opaplus.usuario.permisos')[Session::get('modulo.id_app')]['modulos'][Session::get('modulo.categoria')][Session::get('modulo.id_mod')]['preferencias'])->controlador)}}/{{($opc['accion'])}}/{{Session::get('modulo.id_app')}}/{{Session::get('modulo.categoria')}}/{{Session::get('modulo.id_mod')}}" class="site_title site_title2" style = "text-decoration: none; ">
			            				<i class="{{$opc['icono']}}"></i>				            				
			            			</a>
		            			</div>
		            			
		            			@endif   
			            	@endif
		            	@endif	            		       		
	            	@endforeach
	            @endif
			</div>

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
						<strong>¡Modulo Suscriptores!</strong> La operación se ha realizado adecuadamente.<br><br>
						<ul>								
							<li>{{ Session::get('message') }}</li>
						</ul>
					</div>	                
	            @endif

	            @if(Session::has('messageup'))
					<div class="alert alert-info alert-dismissible fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<strong>¡Modulo Suscriptores!</strong>  El proceso se ejecuto correctamente.<br>			
						<ul>								
							@foreach (Session::get('messageup') as $mensaje)
								<li>{{ $mensaje }}</li>
							@endforeach															
						</ul>
					</div>
	            @endif

	         	@if(Session::has('errorup'))
					<div class="alert alert-danger  alert-dismissible fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<strong>¡Algo no va bien!</strong> Hay problemas con los datos diligenciados.<br><br>
						<ul>								
							@foreach (Session::get('errorup') as $mensaje)
								<li>{{ $mensaje }}</li>
							@endforeach															
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

			<div class="col-md-12 col-md-offset-0">
				Reportes Suscripciones
			</div>

		</div>				
	</div>

	<!-- Datos para autocomplete inicial -->				
	@foreach (Session::get('modulo.asesores') as $asesor)				
		<script type="text/javascript">  clu_reporte.datos_advisers.push("{{$asesor}}"); </script>					
	@endforeach
	<!-- Datos para autocomplete inicial -->				
	@foreach (Session::get('modulo.ciudades') as $ciudad)				
		<script type="text/javascript">  clu_reporte.datos_cities.push("{{$ciudad}}"); </script>					
	@endforeach
	<!-- Datos para autocomplete inicial -->				
	@foreach (Session::get('modulo.estados') as $estado)				
		<script type="text/javascript">  clu_reporte.datos_states.push("{{$estado}}"); </script>					
	@endforeach
		
@endsection

@section('modal')
	
	<div class="modal fade" id="suscripcion_reporte_general" role="dialog" data-backdrop="false">
	    <div class="modal-dialog">	    
	      <!-- Modal content-->
	      <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Reporte General Suscripción</h4>
				</div>
				<div class = "alerts-module"></div>

				{!! Form::open(array('url' => 'reporte/reportegeneral')) !!}
				<div class="modal-body">
					<div class="row ">
						<div class="col-md-12 col-md-offset-0 row_izq">
							<div class="form-group">

								<div class = "col-md-6">
									{!! Form::label('inicio_rsg', 'FECHA SUSCRIPCION INICIAL', array('class' => 'col-md-12 control-label')) !!}
									{!! Form::text('inicio_rsg', old('inicio_rsg'), array('class' => 'form-control','placeholder'=>'Ingresa los apellidos'))!!}
								</div>

								<div class = "col-md-6">
									{!! Form::label('fin_rsg', 'FECHA SUSCRIPCION FINAL', array('class' => 'col-md-12 control-label')) !!}
									{!! Form::text('fin_rsg', old('fin_rsg'), array('class' => 'form-control','placeholder'=>'Ingresa los apellidos'))!!}
								</div>
								
								<div class = "col-md-6">
									{!! Form::label('state', 'ESTADO', array('class' => 'col-md-12 control-label')) !!}
									{!! Form::text('state',old('state'),array('class' => 'form-control','placeholder'=>'Ingresa un estado')) !!}
								</div>

								<div class = "col-md-6">
									{!! Form::label('adviser', 'ASESOR', array('class' => 'col-md-12 control-label')) !!}
									{!! Form::text('adviser',old('adviser'),array('class' => 'form-control','placeholder'=>'Ingresa un asesor')) !!}
								</div>

								<div class = "col-md-6">
									{!! Form::label('city', 'CIUDAD', array('class' => 'col-md-12 control-label')) !!}
									{!! Form::text('city',old('city'),array('class' => 'form-control','placeholder'=>'Ingresa una ciudad')) !!}
								</div>

							</div>
						</div>						
					</div>
		        </div>

		        <div class="modal-footer">
		          {!! Form::submit('Enviar', array('class' => 'btn btn-default')) !!}
		          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>		                  
		        </div>
		        {!! Form::close() !!}       
	      </div>
      </div>
	</div>

@endsection

@section('script')
	<script type="text/javascript" src="{{ asset('/js/lib/datetimepiker.js') }}"></script>	
	<script type="text/javascript">
		javascript:seg_user.iniciarDatepiker('inicio_rsg');
		javascript:seg_user.iniciarDatepiker('fin_rsg');
		//autocomplete con los datos iniciales
		$( "#adviser" ).autocomplete({
			source: clu_reporte.datos_advisers
	    });
	    $( "#city" ).autocomplete({
			source: clu_reporte.datos_cities
	    });
	    $( "#state" ).autocomplete({
			source: clu_reporte.datos_states
	    });
	    $(".ui-autocomplete").css("zIndex", 1500);	    
	    clu_reporte.datos_advisers= [];
	    clu_reporte.datos_cities= [];
	    clu_reporte.datos_states= [];


	</script>
@endsection
    
