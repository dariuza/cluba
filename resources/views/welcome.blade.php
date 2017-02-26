@extends('app')

@section('content')
	
	@if(Session::has('error'))
		<div class="alert alert-danger">
			<strong>¡Acceso seguro!</strong> Acceso denegado.<br><br>
			<ul>								
				<li>{{ Session::get('error') }}</li>								
			</ul>
		</div>                
    @endif
    @if(Session::has('message-acces'))
		<div class="alert alert-info">
			<strong>¡Acceso seguro!</strong><br>
			<ul>								
				<li>{{ Session::get('message-acces') }}</li>
			</ul>
		</div>                
	@endif	
   
    @foreach ($modulo['suscripciones_state'] as $key => $value)		
		<script type="text/javascript">  clu_suscripcion.datos_pie.push({name:"{{$value->state}}",y:{{$value->total}}});</script>
		<script type="text/javascript">  clu_suscripcion.colores_pie.push('{{$value->alert}}');</script>
	@endforeach
	@foreach ($modulo['suscripciones_advser'] as $key => $value)		
		<script type="text/javascript">  clu_suscripcion.datos_bar.push({name:"{{$value->names}}",y:{{$value->total}}});</script>
	@endforeach
    <div class="col-md-12" style = "margin-bottom: 10px;">
		<div class="col-md-12 col-md-offset-0 cuadro">
			<div id="container_pie" style="width:100%; height:100%;"></div>	
		</div>
	</div>
	<div class="col-md-12">
		<div class="col-md-12 col-md-offset-0 cuadro">
			<div id="container_bar" style="width:100%; height:100%;"></div>	
		</div>
	</div>
	 
		
@endsection
@section('script')
	<script type="text/javascript">  	
  		javascript:seg_user.iniciarPie('#container_pie','Distribución de Suscripciones por Estado',clu_suscripcion.datos_pie,clu_suscripcion.colores_pie);
		javascript:seg_user.iniciarPie('#container_bar','Distribución de Suscripciones por Asesor',clu_suscripcion.datos_bar);		
		javascript:clu_suscripcion.datos_pie = [];
		javascript:clu_suscripcion.colores_pie = [];
		javascript:clu_suscripcion.datos_bar = [];

					
	</script>
@endsection
    