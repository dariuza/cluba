@extends('app')

@section('content')

<!-- Estilos para el chosen -->
{{ Html::style('css/lib/chosen.css')}}
<style>
	.chosen-container .chosen-container-multi{
		width: 100% !important;
	}
	.perfilImage{
		width: 100%;
		cursor:pointer;	
	}
</style>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">@if(Session::has('titulo')) {{Session::get('titulo')}} @else Nuevo @endif Usuario</div>
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
						<strong>¡Ingreso de Usuario!</strong> El usuario se ha agregado adecuadamente.<br><br>
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
						
				{!! Form::open(array('url' => 'usuario/save','method'=>'POST','files'=>true)) !!}			
				
				<ul class="nav nav-tabs">
				  <li role="presentation" class="active"><a href="#tab1" data-toggle="tab">CREDENCIAL</a></li>
				  <li role="presentation"><a href="#tab2" data-toggle="tab">PERFIL</a></li>
				  <li role="presentation"><a href="#tab3" data-toggle="tab">APLICACIONES</a></li>				 
				</ul>
				
				<div class="panel-body">
                    <div class="tab-content">
						<div class="tab-pane fade in active" id="tab1">
							<div class="form-group col-md-6">                        		
								{!! Form::label('name', 'Usuario', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::text('name', old('name'), array('class' => 'form-control','placeholder'=>'Ingresa el nombre de usuario')) !!}
								
							</div>
							<div class="form-group col-md-6"> 
								@if(Session::has('titulo'))
									{!! Form::label('password', 'Contraseña', array('class' => 'col-md-12 control-label')) !!}
									{!! Form::password('password', array('class' => 'form-control','placeholder'=>'Nueva contraseña de usuario')) !!}
									
								@else
									{!! Form::label('password', 'Contraseña', array('class' => 'col-md-12 control-label')) !!}
									{!! Form::password('password', array('class' => 'form-control','placeholder'=>'Ingresa la contraseña de usuario, por defecto es: 0000')) !!}
									
								@endif                       		
								
							</div>
							<div class="form-group col-md-6">
								{!! Form::label('email', 'Correo electronico', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::email('email', old('email'), array('class' => 'form-control','placeholder'=>'Ingresa el email')) !!}
								
							</div>
							<div class="form-group col-md-6">								
								{!! Form::label('rol', 'Rol', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::select('rol',Session::get('modulo.roles'),old('rol_id'),array('class' => 'form-control rol_select','placeholder'=>'Ingresa el rol')) !!}
															
							</div>
						</div>
						<div class="tab-pane fade" id="tab2">
							<div class="form-group input-grp col-md-4">
								{!! Form::label('names', 'Nombres', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::text('names', old('names'), array('class' => 'form-control','placeholder'=>'Ingresa los nombres')) !!}
								
							</div>
		
							<div class="form-group input-grp col-md-4">
								{!! Form::label('surnames', 'Apellidos', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::text('surnames', old('surnames') ,array('class' => 'form-control','placeholder'=>'Ingresa los apellidos')) !!}
								
							</div>
							
							<div class="form-group input-grp col-md-4">
								{!! Form::label('sex', 'Genero', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::select('sex',array('Masculino' => 'Masculino', 'Femenino' => 'Femenino'),old('sex'), array('class' => 'form-control','placeholder'=>'Ingresa el sexo')) !!}
								
							</div>
							
							<div class="form-group input-grp col-md-4">
								{!! Form::label('civil_status', 'Estado Civil', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::select('civil_status',array('Soltero' => 'Soltero', 'Comprometido' => 'Comprometido','Casado' => 'Casado','Divorciado' => 'Divorciado','Viudo' => 'Viudo'),old('civil_status'), array('class' => 'form-control','placeholder'=>'Ingresa el estado civil')) !!}
								
							</div>
							
							<div class="form-group input-grp col-md-4">
								{!! Form::label('typeid', 'Tipo Identificación', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::select('type_id',array('Cedula Ciudadania' => 'Cedula Ciudadania', 'Cedula Extranjeria' => 'Cedula Extranjeria'),old('type_id'), array('class' => 'form-control','placeholder'=>'Ingresa tu tipo de identificación')) !!}
								
							</div>
							
							<div class="form-group input-grp col-md-4">
								{!! Form::label('identificacion', 'Identiticación', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::text('identificacion', old('identificacion') ,array('class' => 'form-control','placeholder'=>'Ingresa la  identificación')) !!}
								
							</div>							
							
							<div class="form-group input-grp col-md-4">
								{!! Form::label('birthplace', 'Lugar de Expedición', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::text('birthplace',old('birthplace'), array('class' => 'form-control','placeholder'=>'Ingresa el lugar de tu nacimiento')) !!}
								
							</div>
							
							<div class="form-group input-grp col-md-4">
								{!! Form::label('birthdate', 'Fecha de nacimiento', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::text('birthdate',old('birthdate'), array('class' => 'form-control','placeholder'=>'aaaa-mm-dd')) !!}
								
							</div>
							
							<div class="form-group input-grp col-md-4">
								{!! Form::label('fix_number', 'Telefono Fijo', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::text('fix_number',old('fix_number'), array('class' => 'form-control','placeholder'=>'Ingresa el número fijo')) !!}
								
							</div>
							
							<div class="form-group input-grp col-md-4">
								{!! Form::label('movil_number', 'Telefono Movil', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::text('movil_number',old('movil_number'), array('class' => 'form-control','placeholder'=>'Ingresa el número de movil')) !!}
								
							</div>						
							
							<div class="form-group input-grp col-md-4">
								{!! Form::label('city', 'Ciudad', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::text('city',old('city'), array('class' => 'form-control','placeholder'=>'Ingresa tu ciudad de recidencia')) !!}								
							</div>
							
							<div class="form-group input-grp col-md-4">
								{!! Form::label('neighborhood', 'Barrio', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::text('neighborhood',old('neighborhood'), array('class' => 'form-control','placeholder'=>'Ingresa el barrio donde vives')) !!}
								
							</div>
							
							<div class="form-group input-grp col-md-4">
								{!! Form::label('adress', 'Dirección', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::text('adress',old('adress'), array('class' => 'form-control','placeholder'=>'Ingresa la dirección')) !!}
								
							</div>
							<!--
							<div class="form-group input-grp referencia col-md-4">
								{!! Form::label('reference', 'Referencia', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::text('reference',old('profession'), array('class' => 'form-control','placeholder'=>'Ingresa tu referencia')) !!}
								
							</div>
							 
							<div class="form-group input-grp tel_referencia col-md-4">
								{!! Form::label('reference_adress', 'Dirección Referencia', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::text('reference_adress',old('reference_adress'), array('class' => 'form-control','placeholder'=>'Ingresa la dirección de referencia')) !!}
								
							</div>
							
							<div class="form-group input-grp tel_referencia col-md-4">
								{!! Form::label('reference_phone', 'Telefono Referencia', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::text('reference_phone',old('profession'), array('class' => 'form-control','placeholder'=>'Ingresa el telefono de tu referencia')) !!}
								
							</div>
							 -->
							<div class="form-group input-grp tel_referencia col-md-4">
								{!! Form::label('salary', 'Salario', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::text('salary',old('salary'), array('class' => 'form-control','placeholder'=>'Ingresa tu alario')) !!}
								
							</div>
							
							<!--  
							<div class="form-group input-grp profesion col-md-6">
								{!! Form::label('profession', 'Profesión', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::text('profession',old('profession'), array('class' => 'form-control','placeholder'=>'Ingresa tu ocupasión')) !!}
								
							</div>
													
							<div class="form-group input-grp recidencia col-md-6">
								{!! Form::label('home', 'Recidencia', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::select('home',array('Propia' => 'Propia', 'Familiar' => 'Familiar', 'Arrendada' => 'Arrendada'),old('home'), array('class' => 'form-control','placeholder'=>'Elije si tu vivienda es propia, familiar o arrendada')) !!}
								
							</div>
							
							<div class="form-group input-grp direccion_pago col-md-4">
								{!! Form::label('paymentadress', 'Dirección de Cobro', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::text('paymentadress',old('profession'), array('class' => 'form-control','placeholder'=>'Ingresa tu dirección de Pago')) !!}
								
							</div>
							-->		
							
							<div class="form-group input-grp col-md-4">
								{!! Form::label('date_start', 'Fecha de Ingreso', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::text('date_start',old('date_start'), array('class' => 'form-control','placeholder'=>'aaaa-mm-dd')) !!}
								
							</div>
							
							<div class="form-group input-grp col-md-4">
								{!! Form::label('date_out', 'Fecha de Retiro', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::text('date_out',old('date_out'), array('class' => 'form-control','placeholder'=>'aaaa-mm-dd')) !!}
								
							</div>
							
							<div class="form-group input-grp code_adviser col-md-4">
								{!! Form::label('code_adviser', 'Código Empleado', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::text('code_adviser',old('code_adviser'), array('class' => 'form-control','placeholder'=>'Ingresa el código de empleado')) !!}
								
							</div>
								
							<div class="form-group input-grp zone col-md-12">
								{!! Form::label('zone_select_label', 'Zonas', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::select('zone_select',Session::get('modulo.ciudades'),old('zone_select'), array('class' => 'form-control chosen-select','multiple' ,'data-placeholder'=>'Selecciona las zonas','tabindex'=>'4', 'style'=>'width:350px;')) !!}
								{!! Form::hidden('zone',old('zone'),array('id'=>'zone')) !!}
							</div>
							
							<div class="form-group input-grp">
								{!! Form::label('perfil_description', 'Descripción', array('class' => 'col-md-12 control-label')) !!}
								<div class="col-md-8">
									{!! Form::textarea('perfil_description',old('perfil_description'), array('class' => 'form-control','placeholder'=>'Ingresa la descripcion')) !!}
								</div>
							</div>										
							
							<div class="form-group input-grp col-md-4">
								@if(old('edit'))
									<div class = "perfilImage" >
										{{ Html::image('images/user/'.old('avatar'),'Imagen no disponible',array('id'=>'nueva_img', 'style'=>'width: 100%; border:2px solid #2A3F54;border-radius: 3px;' ))}}
									</div>
								@else
									<div class = "perfilImage" data-toggle="modal" data-target="#img_modal">
										{{ Html::image('images/user/default.png','Imagen no disponible',array('id'=>'nueva_img', 'style'=>'width: 100%; border:2px solid #2A3F54;border-radius: 3px;' ))}}
									</div>
								@endif
								<p>{!! Form::file('image',array('id'=>'img_user')) !!}</p>								
								
							</div>
							
						</div>
						<div class="tab-pane fade" id="tab3">
							
							@if(Session::has('modulo.apps'))
								@foreach (Session::get('modulo.apps') as $app)
									<div class="form-group input-grp">
										<div class = "col-md-12">
										<div class = "col-md-4 ">
											@if (Session::has('modulo.user_apps'))												
												@if (in_array($app['id'],Session::get('modulo.user_apps')))
													<input checked="checked" name="{{Session::get('modulo.id').'_'.$app['app']}}" value="{{$app['id']}}" id="{{Session::get('modulo.id').'_'.$app['app']}}" type="checkbox">
												@else
													{{ Form::checkbox(Session::get('modulo.id').'_'.$app['app'], $app['id'],old(Session::get('modulo.id').'_'.$app['app'])) }}
													
												@endif
											@else
												{{ Form::checkbox(Session::get('modulo.id').'_'.$app['app'], $app['id'],old(Session::get('modulo.id').'_'.$app['app'])) }}
												
											@endif
											
											<span class="{{ json_decode($app['preferences'], true)['icono'] }}">{{ $app['app']}}</span>
										</div>									
										<div class = "col-md-8 ">  {{$app['description']}}</div>
										</div>								
									</div>								
								@endforeach							
							@endif	
							
						</div>						
						{!! Form::hidden('mod_id', Session::get('modulo.id')) !!}
						{!! Form::hidden('edit', old('edit')) !!}
						{!! Form::hidden('user_id', old('user_id')) !!}												
					</div>
				</div>				
				
				<div class="form-group">
					<div class="col-md-1 col-md-offset-0 ">
						{!! Form::submit('Enviar', array('class' => 'btn btn-primary')) !!}																
					</div>	
					<div class="col-md-1 col-md-offset-1">
						<a href="{{ url('usuario/listar') }}" class="btn btn-primary">Cancelar</a>															
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

	{{ Html::script('js/lib/chosen.jquery.min.js')}}
		
	<script type="text/javascript">  	
		javascript:seg_user.iniciarDatepiker('birthdate');
		javascript:seg_user.iniciarDatepiker('date_start');
		javascript:seg_user.iniciarDatepiker('date_out');	
		javascript:$('#rol').addClass('form-control');
	
		//funcion para controlar los datos de usuario
		$('.rol_select').change(function(e){			
			if(this.value != '4' && this.value != '5' && this.value != '6'){
				//$('.code_adviser').fadeOut();
				$('.zone').fadeOut();
			}else{
				if(this.value == '4'){
					//$('.code_adviser').fadeIn();
					$('.zone').fadeOut();
				}else
					if(this.value == '5' || this.value == '6'){
						//$('.code_adviser').fadeOut();
						$('.zone').fadeIn();						
					}
				}
						
		});		

		$('.chosen-select').chosen();
		$('.chosen-container').width('100%');		
		$(".chosen-select").chosen().change(function(event) {
			$('#zone').val($('.chosen-select').chosen().val());		    
		});
		
		$('.zone').fadeOut();
		
		if($('.rol_select').val() == '6'){
			$('.zone').fadeIn();
		}

		$("#img_user").change(function(){
  			seg_user.changeImg(this);
  		});
				
	</script>
@endsection 