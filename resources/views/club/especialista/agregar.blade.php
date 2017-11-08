@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">@if(Session::has('titulo')) {{Session::get('titulo')}} @else Nuevo @endif Especialista</div>
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
						<strong>¡Ingreso de Especialista!</strong> El el especialista se ha agregado adecuadamente.<br><br>
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
						
				{!! Form::open(array('url' => 'especialista/save')) !!}			
					<div class="panel-body">
					<ul class="nav nav-tabs">
						<li role="bnes_cnt" class="active"><a href="#tab_dispo1" data-toggle="tab">ESPECIALISTA</a></li>
						<li role="bnes_cnt"><a href="#tab_dispo2" data-toggle="tab">ESPECIALIDAD</a></li>	
						<li role="bnes_cnt"><a href="#tab_dispo3" data-toggle="tab">DISPONIBILIDAD</a></li>							
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_dispo1">
							<div class="row ">
								<div class="col-md-12 col-md-offset-0 tab_cnt_bnt1">
									<div class="form-group">						
										<div class="col-md-12">
											{!! Form::label('especialidad', 'Especialidad', array('class' => 'col-md-4 control-label')) !!}
											{!! Form::select('entidad',Session::get('modulo.entidades'),old('emtidad'), array('class' => 'form-control','placeholder'=>'Ingresa la entidad')) !!}
										</div>
									</div>
									
									<div class="form-group">									
										<div class="col-md-6">
											{!! Form::label('nombres', 'Nombres', array('class' => 'col-md-12 control-label')) !!}		
											{!! Form::text('nombres', old('nombres'), array('class' => 'form-control','placeholder'=>'Ingresa nombre completo'))!!}
										</div>
									</div>

									<div class="form-group">
										<div class="col-md-6">
											{!! Form::label('nombres_asistente', 'Nombres Asistente', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::text('nombres_asistente', old('nombres_asistente'), array('class' => 'form-control','placeholder'=>'Ingresa el la descripción'))!!}
										</div>
									</div>

									<div class="form-group">									
										<div class="col-md-6">
											{!! Form::label('identificacion', 'Identificaciòn', array('class' => 'col-md-12 control-label')) !!}			
											{!! Form::text('identificacion', old('identificacion'), array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa nombre completo'))!!}
										</div>
									</div>

									<div class="form-group">	
										<div class="col-md-6">
											{!! Form::label('telefono_uno_asistente', 'Teléfono 1 Asistente', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::text('telefono_uno_asistente', old('telefono_uno_asistente'), array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa Teléfono uno asistente'))!!}
										</div>
									</div>	

									<div class="form-group">	
										<div class="col-md-6">
											{!! Form::label('telefono_uno', 'Teléfono 1', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::text('telefono_uno', old('telefono_uno'), array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa Teléfono uno'))!!}
										</div>
									</div>

									<div class="form-group">	
										<div class="col-md-6">
											{!! Form::label('telefono_dos_asistente', 'Teléfono 2 Asistente', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::text('telefono_dos_asistente', old('telefono_dos_asistente'), array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa Teléfono dos asistente'))!!}
										</div>
									</div>	

									<div class="form-group">	
										<div class="col-md-6">
											{!! Form::label('telefono_dos', 'Teléfono 2', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::text('telefono_dos', old('telefono_dos'), array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa Teléfono dos'))!!}
										</div>
									</div>	

									<div class="form-group">	
										<div class="col-md-6">
											{!! Form::label('correo_electronico_asistente', 'Correo electrónico asistente', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::text('correo_electronico_asistente', old('correo_electronico_asistente'), array('class' => 'form-control ','placeholder'=>'Ingresa correo electrónico asistente'))!!}
										</div>
									</div>	

									<div class="form-group">	
										<div class="col-md-6">
											{!! Form::label('correo_electronico', 'Correo electrónico', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::text('correo_electronico', old('correo_electronico'), array('class' => 'form-control ','placeholder'=>'Ingresa correo electrónico'))!!}
										</div>
									</div>		

									<div class="form-group">	
										<div class="col-md-12">
											{!! Form::label('descripcion', 'Descripción', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::textarea('descripcion',old('descripcion'), array('class' => 'form-control','rows'=>'4','placeholder'=>'Ingresa las Descripciónes u Observaciones')) !!}
										</div>
									</div>			
									
								</div>
							</div>
						</div>
						<div class="tab-pane fade " id="tab_dispo2">
							<div class="row ">
								<div class="col-md-12 col-md-offset-0 tab_dispo2">
									<!--Corremos el for de especialidades-->
									@if(old('edit'))
										@php ($i=1)	
										@foreach (Session::get('modulo.clu_specialist_x_specialty') as $especialidad)			
											<div class="form-group">
												<div class="col-md-3">
													{!! Form::label('espe_especialidad_'.$i, 'Especialidad', array('class' => 'col-md-12 control-label')) !!}
													{!! Form::select('espe_especialidad_'.$i,Session::get('modulo.especialidades'),$especialidad['specialty_id'], array('class' => 'form-control','placeholder'=>'Ingresa La Especialidad')) !!}
												</div>
												<div class="col-md-3">
													{!! Form::label('espe_precioparticular_'.$i, 'Precio Particular', array('class' => 'col-md-12 control-label')) !!}
													{!! Form::text('espe_precioparticular_'.$i, $especialidad['rate_particular'], array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa Precio'))!!}
												</div>
												<div class="col-md-3">
													{!! Form::label('espe_preciosuscriptor_'.$i, 'Precio Particular', array('class' => 'col-md-12 control-label')) !!}
													{!! Form::text('espe_preciosuscriptor_'.$i, $especialidad['rate_suscriptor'], array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa Precio'))!!}
												</div>
												<div class="col-md-3">
													{!! Form::label('espe_duracion_'.$i, 'Tiempo Duraciòn', array('class' => 'col-md-12 control-label')) !!}
													<div class="input-group bootstrap-timepicker timepicker">
														{!! Form::text('espe_duracion_'.$i, $especialidad['tiempo'], array('class' => 'form-control input-small','placeholder'=>'Duraciòn HH:mm'))!!}
														<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
													</div>
												</div>

											</div>
											@php ($i++)	
										@endforeach
									@endif
								</div>
								<div class="col-md-12"><hr size = "1"></hr></div>
								<div class="col-md-1 col-md-offset-11" data-toggle="tooltip" title="" data-original-title="Agregar Especialidad">
	            					<a href="javascript:clu_especialista.add_special('1')" class="site_title site_title2" style="text-decoration: none;color:#5A738E !important;  ">
	            					<i class="fa fa-plus" style="border: 1px solid #5A738E !important"></i>	
	            					</a>
            					</div>
							</div>
						</div>
						<div class="tab-pane fade " id="tab_dispo3">
							<div class="row ">
								<div class="col-md-12 col-md-offset-0 tab_dispo3">
									<!--Corremos el for de disponibilidades-->
								</div>
							</div>
						</div>
					</div>


												
					
					<!-- Aprovechar el formulario para editar -->
					{!! Form::hidden('edit', old('edit')) !!}
					{!! Form::hidden('specialist_id', old('specialist_id')) !!}
					</div>
					
					<div class="form-group">
						<div class="col-md-1 col-md-offset-0">
							{!! Form::submit('Enviar', array('class' => 'btn btn-primary')) !!}																
						</div>
						<div class="col-md-1 col-md-offset-1">
						<a href="{{ url('especialista/listar') }}" class="btn btn-primary">Cancelar</a>															
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