@extends('app')

@section('content')
<style>
	.col-md-6{
		margin-top: 10px;
	}
	.ui-autocomplete{
	    color: #555555;
    	background-color: #ffffff;
    	background-image: none;
    	font-size: 14px;
    	line-height: 1.42857143;
    	font-family: inherit;
	}
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

	.chosen-container .chosen-container-multi{
		border: 1px solid #ccc !important;
		border-radius: 4px !important;
	}
		
</style>


<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">@if(Session::has('titulo')) {{Session::get('titulo')}} @else Nuevo @endif Beneficiario</div>
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
						<strong>¡Ingreso de Beneficiarios!</strong> El beneficiario se ha agregado adecuadamente.<br><br>
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
						
				{!! Form::open(array('url' => 'beneficiario/save')) !!}			
					<div class="panel-body">
					
					<ul class="nav nav-tabs">
						<li role="presentation" class="active"><a href="#tab1" data-toggle="tab">BENEFICIARIO</a></li>																					
						@if(old('edit'))
							@if(Session::get('opaplus.usuario.rol_id') == 1 || Session::get('opaplus.usuario.rol_id') == 2)
								<!-- solo para administradores -->									
								@if(old('state') == "Pago pendiente" || old('names') == "Pago efectuado" )
									<li role="presentation"><a href="#tab2" data-toggle="tab">ABONOS</a></li>
								@endif									
							@endif
						@endif							
					</ul>
					
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab1">					
							<div class="form-group">
								{!! Form::label('identification', 'Identificación', array('class' => 'col-md-4 control-label')) !!}							
								<div class="col-md-12">
									{!! Form::text('identification', old('identification'), array('class' => 'form-control','placeholder'=>'Ingresa la identificación', 'autofocus'=>'autofocus'))!!}
								</div>
							</div>
							
							<div class="form-group">									
								{!! Form::label('typeid', 'Tipo Identificación', array('class' => 'col-md-12 control-label')) !!}
									<div class="col-md-12">	
										{!! Form::select('type_id',array('CEDULA CIUDADANIA' => 'CEDULA CIUDADANIA', 'CEDULA EXTRANJERIA' => 'CEDULA EXTRANJERIA'),old('type_id'), array('class' => 'form-control','placeholder'=>'Selecciona una opción')) !!}
									</div>
							</div>
		
							<div class="form-group">
								{!! Form::label('names', 'Nombres', array('class' => 'col-md-4 control-label')) !!}
								<div class="col-md-12">
									{!! Form::text('names', old('names'), array('class' => 'form-control','placeholder'=>'Ingresa los nombres'))!!}
								</div>
							</div>
							
							<div class="form-group">
								{!! Form::label('surnames', 'Apellidos', array('class' => 'col-md-4 control-label')) !!}
								<div class="col-md-12">
									{!! Form::text('surnames', old('surnames'), array('class' => 'form-control','placeholder'=>'Ingresa los apellidos'))!!}
								</div>
							</div>
							
							<div class="form-group">
								{!! Form::label('civil_status', 'Estado Civil', array('class' => 'col-md-12 control-label')) !!}
								<div class="col-md-12">	
									{!! Form::select('civil_status',array('CASADO' => 'CASADO','UNIÓN LIBRE' => 'UNIÓN LIBRE' ,'SEPARADO' => 'SEPARADO','SOLTERO' => 'SOLTERO','VIUDO' => 'VIUDO'),old('civil_status'), array('class' => 'form-control','placeholder'=>'Selecciona una  opción')) !!}
								</div>
								</div>
							
							<div class="form-group">
								{!! Form::label('relationship', 'Parentesco', array('class' => 'col-md-4 control-label')) !!}
								<div class="col-md-12">
									{!! Form::text('relationship', old('relationship'), array('class' => 'form-control','placeholder'=>'Ingresa el parentesco'))!!}
								</div>
							</div>
							
							<div class="form-group">
								{!! Form::label('movil_number', 'Celular', array('class' => 'col-md-4 control-label')) !!}
								<div class="col-md-12">
									{!! Form::text('movil_number', old('movil_number'), array('class' => 'form-control','placeholder'=>'Ingresa telefono Celular'))!!}
								</div>
							</div>
							
							{{--
							<div class="form-group">
								{!! Form::label('titular_id', 'Titular', array('class' => 'col-md-4 control-label')) !!}
								<div class="col-md-12">
									{!! Form::text('titular_id', old('titular_id'), array('class' => 'form-control','placeholder'=>'Ingresa el Suscriptor, [Nombres, Apellidos o Cedula]'))!!}
								</div>
							</div>
							--}}


							<div class="form-group">									
								<div class="col-md-12">
									{!! Form::label('birthdate', 'Fecha de nacimiento', array('class' => 'col-md-12 control-label')) !!}
									{!! Form::text('birthdate',old('birthdate'), array('class' => 'form-control','placeholder'=>'aaaa-mm-dd')) !!}
								</div>
							</div>

							<div class="form-group ">									
								<div class="col-md-12">
									{!! Form::label('adress', 'Dirección', array('class' => 'col-md-12 control-label')) !!}
									{!! Form::text('adress',old('adress'), array('class' => 'form-control','placeholder'=>'Ingresa la dirección de residencia')) !!}
								</div>
							</div>
							<div class="form-group">	
							<div class="col-md-12">
								{!! Form::label('city', 'Municipio recidencia', array('class' => 'col-md-12 control-label')) !!}
								{!! Form::select('city',Session::get('modulo.ciudades'),null, array('class' => 'form-control ','placeholder'=>'Municipio de Beneficiario')) !!}
							</div>
							</div>

							<div class="form-group input-grp">									
								<div class="col-md-12">
									{!! Form::label('email', 'Correo electronico', array('class' => 'col-md-12 control-label')) !!}
									{!! Form::email('email',old('email'), array('class' => 'form-control','placeholder'=>'Ingresa el correo electonico')) !!}
								</div>
							</div>
						</div>
						
						<div class="tab-pane fade" id="tab2">
							@if(old('edit'))
								@if(Session::has('modulo.pagos'))
									<div class="pay-form-inputs col-md-12">
									@foreach (Session::get('modulo.pagos') as $pago)
										<div class = "form-group">
											<div class = "col-md-6 ">
												{!! Form::label('pago_abono_'.$pago['id'], 'Abono', array('class' => 'col-md-12 control-label')) !!}														
												{!! Form::text('pago_abono_'.$pago['id'],$pago['payment'],array('class' => 'form-control','id' => 'pago_abono_'.$pago['id'] )) !!}
											</div>
											<div class = "col-md-6 ">
												{!! Form::label('fecha_pago_'.$pago['id'], 'Fecha de Abono', array('class' => 'col-md-12 control-label')) !!}
												{!! Form::text('fecha_pago_'.$pago['id'],$pago['date_payment'],array('class' => 'form-control fecha_pago','id' => 'fecha_pago_'.$pago['id'])) !!}
											</div>													
										</div>
									
									@endforeach
									</div>
								@endif
							@endif						
						</div>
						
					</div>
					
					</div>
					
					<!-- Aprovechar el formulario para editar -->
					{!! Form::hidden('edit', old('edit')) !!}
					{!! Form::hidden('beneficiary_id', old('beneficiary_id')) !!}
					{!! Form::hidden('state', old('state')) !!}
					
					<div class="form-group">
						<div class="col-md-2 col-md-offset-0">
							{!! Form::submit('Enviar', array('class' => 'btn btn-primary')) !!}																
						</div>
						<div class="col-md-1 col-md-offset-0">
							<a href="{{ url('beneficiario/listar') }}" class="btn btn-primary">Cancelar</a>															
						</div>
					</div>										
			
				{!! Form::close() !!}				
				</div>
			</div>		
		</div>
	</div>	
	
	 <!-- Form en blanco para consultar Titulares -->
	{!! Form::open(array('id'=>'form_consult_titular','url' => 'beneficiario/consultartitular')) !!}
    {!! Form::close() !!}
	
</div>		
@endsection

@section('script')
	<script type="text/javascript" src="{{ asset('/js/lib/chosen.jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/lib/datetimepiker.js') }}"></script>	
	<script type="text/javascript">
		
		$( "#titular_id" ).autocomplete({
	      source: "{{ url('beneficiario/consultartitular') }}",	      
	      minLength: 2
	    });

		$('.fecha_pago').datetimepicker({
			dateFormat: "yy-mm-dd",
			timeFormat: "hh:mm:ss",		
		});

		javascript:seg_user.iniciarDatepiker('birthdate');//todavia no esta definido		

		$('.chosen-select').chosen();
		$('.chosen-container').width('100%');
  		
	    
		
		
	</script>
@endsection