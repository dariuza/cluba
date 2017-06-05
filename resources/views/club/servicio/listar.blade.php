@extends('app')

@section('content')
	{{ Html::style('css/lib/fullcalendar.min.css')}}		
	<link rel="stylesheet" media="print" href="{{ url('css/lib/fullcalendar.print.css') }}">

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
		.input_danger{
			color: #a94442;
    		background-color: #f2dede;
    		border-color: #ebccd1;
		}
		
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
		            				<a href="javascript:clu_entidad.opt_edt()" class="site_title site_title2" style = "text-decoration: none; ">
			            				<i class="{{$opc['icono']}}"></i>
			            			</a>
		            			</div>		
		            		@elseif($opc['accion'] == 'crear')	            			
	            				<div class="col-md-1" data-toggle="tooltip" title = "{{$opc[$key]}}">
		            				<a href="javascript:clu_entidad.opt_agregar()" class="site_title site_title2" style = "text-decoration: none; ">
			            				<i class="{{$opc['icono']}}"></i>
			            			</a>
		            			</div>		            				           			
	            			@elseif($opc['accion'] == 'mirar')	            			
	            				<div class="col-md-1" data-toggle="tooltip" title = "{{$opc[$key]}}">
		            				<a href="javascript:clu_entidad.opt_ver()" class="site_title site_title2" style = "text-decoration: none; ">
			            				<i class="{{$opc['icono']}}"></i>
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
					<div class="alert alert-danger alert-dismissable">
						<button type="button" class="close close_alert_product" data-dismiss="alert">&times;</button>
						<strong>Algo no va bien con el Modulo!</strong> Hay problemas con con los datos diligenciados.<br><br>
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif
				
				@if(Session::has('message'))
					<div class="alert alert-info alert-dismissable">
						<button type="button" class="close close_alert_product" data-dismiss="alert">&times;</button>
						<strong>¡Modulo Servicios!</strong> La operación se ha realizado adecuadamente.<br><br>
						<ul>								
							<li>{{ Session::get('message') }}</li>
						</ul>
					</div>
	                
	            @endif
	            <!-- error llega cuando se esta recuperando la contraseña inadecuadamente -->
	            @if(Session::has('error'))
					<div class="alert alert-danger alert-dismissable">
						<button type="button" class="close close_alert_product" data-dismiss="alert">&times;</button>
						<strong>¡Algo no va bien!</strong> Hay problemas con los datos diligenciados.<br><br>
						<ul>								
							<li>{{ Session::get('error') }}</li>								
						</ul>
					</div>
	                
	            @endif
	    </div>
	
		<div class="col-md-12 col-md-offset-0" style="margin-top: 2%;">
			<div id="calendar" class="fc fc-unthemed fc-ltr">
			</div>
		</div>  
	    

	</div>
@endsection

@section('modal')	
	
@endsection

@section('script')
	{{ Html::script('js/lib/moment.js')}}
	{{ Html::script('js/lib/fullcalendar.min.js')}}
	{{ Html::script('js/lib/es.js')}}
	<script type="text/javascript">
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
	 	var calendar = $('#calendar').fullCalendar({
	 		lang: 'es',        
	        header:
			{
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			defaultView: 'month',
			selectable: true,
			selectHelper: true,            
				
			/*
				when user select timeslot this option code will execute.
				It has three arguments. Start,end and allDay.
				Start means starting time of event.
				End means ending time of event.
				allDay means if events is for entire day or not.
			*/	
		
            events: [
				{
					title: 'ortodocia',
					start: new Date(y, m, 1)
				},
				{
					title: 'Endodoncia',
					start: new Date(y, m, d-5),
					end: new Date(y, m, d-2)
				},
				{
					id: 999,
					title: 'Clase aerobicos',
					start: new Date(y, m, d-3, 16, 0),
					allDay: false
				},
				{
					id: 999,
					title: 'Clase deportiva',
					start: new Date(y, m, d+4, 16, 0),
					allDay: false
				},
				{
					title: 'Paseo',
					start: new Date(y, m, d, 10, 30),
					allDay: false
				},
				{
					title: 'Almuerzo',
					start: new Date(y, m, d, 12, 0),
					end: new Date(y, m, d, 14, 0),
					allDay: false
				},
				{
					title: 'Otro evento',
					start: new Date(y, m, d+1, 19, 0),
					end: new Date(y, m, d+1, 22, 30),
					allDay: false
				},
				{
					title: 'Otro evento2',
					start: new Date(y, m, d+1, 19, 0),
					end: new Date(y, m, d+1, 22, 30),
					allDay: false
				},
				{
					title: 'A una url',
					start: new Date(y, m, 28),
					end: new Date(y, m, 29),
					url: 'http://google.com/'
				}
			]
	        
	    })
	</script>
@endsection