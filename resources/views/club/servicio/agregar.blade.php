@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">@if(Session::has('titulo')) {{Session::get('titulo')}} @else Nueva @endif Cita</div>
				<div class="panel-body">
				@if (count($errors) > 0)
					<div class="alert alert-danger">
						<strong>Algo no va bien con el ingreso!</strong> Hay problemas con con los datos diligenciados.<br><br>
							<ul>							
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
							</ul>
					</div>
				@endif			
				@if(Session::has('message'))
					<div class="alert alert-info">
						<strong>¡Ingreso de Especialidad!</strong> La cita se ha agregado adecuadamente.<br><br>
						<ul>								
							<li>{{ Session::get('message') }}</li>
						</ul>
					</div>                
				@endif			    
			   	@if(Session::has('error'))			   		
					<div class="alert alert-danger">
						<strong>¡Algo no va bien!</strong> Hay problemas con los datos diligenciados.<br><br>
							<ul>								
								<li>{{ Session::get('error') }}</li>								
							</ul>
					</div>                
				@endif	
						
				{!! Form::open(array('url' => 'servicio/save')) !!}			
					<div class="panel-body">
					<div class="form-group">
						{!! Form::label('especialidad', 'Especialidad', array('class' => 'col-md-4 control-label')) !!}							
						<div class="col-md-12">
							{!! Form::text('especialidad', old('especialidad'), array('class' => 'form-control','placeholder'=>'Ingresa la especialidad', 'autofocus'=>'autofocus'))!!}
						</div>
					</div>
					
					<div class="form-group">
						{!! Form::label('codigo', 'Codigo', array('class' => 'col-md-4 control-label')) !!}							
						<div class="col-md-12">
							{!! Form::text('codigo', old('codigo'), array('class' => 'form-control','placeholder'=>'Ingresa el Codigo'))!!}
						</div>
					</div>

					<div class="form-group">
						{!! Form::label('descripcion', 'Descripción', array('class' => 'col-md-4 control-label')) !!}
						<div class="col-md-12">
							{!! Form::text('descripcion', old('descripcion'), array('class' => 'form-control','placeholder'=>'Ingresa el la descripción'))!!}
						</div>
					</div>											
					
					<!-- Aprovechar el formulario para editar -->
					{!! Form::hidden('edit', old('edit')) !!}
					{!! Form::hidden('specialty_id', old('specialty_id')) !!}
					</div>
					
					<div class="form-group">
						<div class="col-md-1 col-md-offset-0">
							{!! Form::submit('Enviar', array('class' => 'btn btn-primary')) !!}																
						</div>
						<div class="col-md-1 col-md-offset-1">
						<a href="{{ url('servicio/listar') }}" class="btn btn-primary">Cancelar</a>															
					</div>	
					</div>										
			
				{!! Form::close() !!}				
				</div>
			</div>		
		</div>
	</div>	
</div>		
@endsection

@section('script')		
	<script type="text/javascript">  	
		
	</script>
@endsection