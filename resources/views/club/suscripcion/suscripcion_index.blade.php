@extends('app')

@section('content')
	<!-- Mensajes y alertas -->
	<!-- Este se usa para validar formularios -->
	@if (count($errors) > 0)
		<div class="alert alert-danger fade in">
			<strong>Algo no va bien con el modulo Suscripciones!</strong> Hay problemas con con los datos diligenciados.<br><br>
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
		<div class="col-md-4">						
			@if(Session::has('modulo'))
			<div class="col-md-12 col-md-offset-0 cuadro">	
				<ul>					
					<dd class="">
						{!! Form::open(array('id'=>'form_search','url' => 'suscripcion/buscar')) !!}
							{!! Form::label('usuario', 'Buscador de '.Session::get('modulo.modulo'), array('class' => 'col-md-12 control-label')) !!}
							{!! Form::text('names', '', array('class' => 'form-control','placeholder'=>'Ingresa: Fecha, Asesor, Amigo...')) !!}
							{!! Form::hidden('id', Session::get('modulo.id')) !!}
						{!! Form::close() !!}
					</dd>					
				</ul>
			</div>
			<div class="col-md-12 col-md-offset-0 cuadro">
				<ul>
					<dd class="title">Modulo: {{Session::get('modulo.modulo')}}</dd>
					</br>
					<li style="border-bottom: 1px dotted #78a5b1">{{Session::get('modulo.description')}}</li>
					<li> Opciones disponibles: {{ count(Session::get('opaplus.usuario.permisos')[Session::get('modulo.id_aplicacion')]['modulos'][Session::get('modulo.categoria')][Session::get('modulo.id')]['opciones'])}}</li>
					<dd style="border-bottom: 1px dotted #78a5b1">
						<ul>							
						@foreach (Session::get('opaplus.usuario.permisos')[Session::get('modulo.id_aplicacion')]['modulos'][Session::get('modulo.categoria')][Session::get('modulo.id')]['opciones'] as $llave_opt => $opt)
							@if($opt['lugar'] == Session::get('opaplus.usuario.lugar.lugar'))
								@if($opt['vista'] != 'listar')
									<li  type="square" ><a href="{{url(json_decode(Session::get('opaplus.usuario.permisos')[Session::get('modulo.id_aplicacion')]['modulos'][Session::get('modulo.categoria')][Session::get('modulo.id')]['preferencias'])->controlador)}}/{{($opt['accion'])}}/{{Session::get('modulo.id_aplicacion')}}/{{Session::get('modulo.categoria')}}/{{Session::get('modulo.id')}}" >{{$opt[$llave_opt]}}</a></li>   
								@endif
							@endif							
						@endforeach
						</ul>
					</dd>	
					<li> Cantidad de Suscripciones:  {{Session::get('modulo.total_suscripciones')}} </li>
					<dd style="border-bottom: 1px dotted #78a5b1">
						<ul>
						@foreach (Session::get('modulo.suscripciones_state') as $llave_suscripcion => $estado)
							<li type="square" >{{$estado->state}} : {{$estado->total}}</li>
							<script type="text/javascript">  clu_suscripcion.datos_pie.push({name:"{{$estado->state}}",y:{{$estado->total}}});</script>
							<script type="text/javascript">  clu_suscripcion.colores_pie.push('{{$estado->alert}}');</script>
						@endforeach
						</ul>
					</dd>
					
					<dd style="border-bottom: 1px dotted #78a5b1">
						<ul>
						@foreach (Session::get('modulo.suscripciones_advser') as $llave_suscripcion => $asesor)
							<li type="square" >{{$asesor->identificacion}} {{$asesor->names}} : {{$asesor->total}}</li>
							<script type="text/javascript">  clu_suscripcion.datos_pie_asesor.push({name:"{{$asesor->names}}",y:{{$asesor->total}}});</script>							
						@endforeach
						</ul>
					</dd>				
					 											
				</ul>								
			</div>	 
			@else
			<div class="alert alert-danger">
				<strong>¡Algo no va bien!</strong> Hay problemas con los datos diligenciados.<br><br>
					<ul>								
						<li> Los Datos Para la Construcción Del Index no se Consultarón Adecuadamente, o se esta realizando la consulta por medio del URL </li>								
					</ul>
			</div> 			
			@endif			
		</div>
		<div class="col-md-8">
			<div class="col-md-11 col-md-offset-0 cuadro">
				<div id="container_pie" style="width:100%; height:100%;"></div>					
			</div>
			<div class="col-md-11 col-md-offset-0 cuadro">
				<div id="container_pie_asesor" style="width:100%; height:100%;"></div>	
			</div>			
		</div>
		
	</div>
		
@endsection

@section('script')
	<script type="text/javascript">  	
  		javascript:seg_user.iniciarPie('#container_pie','Distribución de Suscripciones por Estado',clu_suscripcion.datos_pie,clu_suscripcion.colores_pie);
		javascript:seg_user.iniciarPie('#container_pie_asesor','Distribución de Suscripciones por Asesor',clu_suscripcion.datos_pie_asesor);		
		javascript:clu_suscripcion.datos_pie = [];
		javascript:clu_suscripcion.colores_pie=[];
		javascript:clu_suscripcion.datos_pie_asesor =[];
					
	</script>
@endsection
    
