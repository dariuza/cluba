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
		
</style>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12 col-md-offset-0">
			<div class="panel panel-default">
				<div class="panel-heading">@if(Session::has('titulo')) {{Session::get('titulo')}} @else Nueva @endif Suscripción</div>
				<div class="panel-body">
				<div class = "alerts"></div>
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
						<strong>¡Alerta en Suscripción!</strong> La suscripción se diligenciado adecuadamente.<br><br>
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
				<!-- Datos para autocomplete inicial -->				
				@foreach (Session::get('modulo.asesores') as $asesor)				
					<script type="text/javascript">  clu_suscripcion.datos_advisers.push("{{$asesor}}"); </script>					
				@endforeach			
				{!! Form::open(array('url' => 'suscripcion/save')) !!}			
					<div class="panel-body">
					
						<ul class="nav nav-tabs">
							<li role="presentation" class="active"><a href="#tab1" data-toggle="tab">SUSCRIPTOR</a></li>
							<li role="presentation"><a href="#tab2" data-toggle="tab">SUSCRIPCIÓN</a></li>
							<li role="presentation"><a href="#tab3" data-toggle="tab">BENEFICIARIOS</a></li>							
							@if(old('edit'))
								@if(Session::get('opaplus.usuario.rol_id') == 1 || Session::get('opaplus.usuario.rol_id') == 2)
									<!-- solo para administradores -->									
									<li role="presentation"><a href="#tab4" data-toggle="tab">ABONOS</a></li>
								@endif
							@endif
							
						</ul>
						
						<div class="tab-content">
							
							<div class="tab-pane fade in active" id="tab1">
								<div class="form-group">
																							
								<div class="col-md-4">
										{!! Form::label('names', 'Nombres', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::text('names', old('names'), array('class' => 'form-control','placeholder'=>'Ingresa los nombres', 'autofocus'=>'autofocus'))!!}
									</div>
								</div>
															
								<div class="form-group">									
									<div class="col-md-4">
										{!! Form::label('surnames', 'Apellidos', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::text('surnames', old('surnames'), array('class' => 'form-control','placeholder'=>'Ingresa los apellidos'))!!}
									</div>
								</div>
								
								<div class="form-group input-grp">									
									<div class="col-md-4">
										{!! Form::label('sex', 'Genero', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::select('sex',array('MASCULINO' => 'MASCULINO', 'FEMENINO' => 'FEMENINO', 'OTRO' => 'OTRO'),old('sex'), array('class' => 'form-control','placeholder'=>'Ingresa el genero')) !!}
									</div>
								</div>
								
								<div class="form-group">									
									<div class="col-md-3">
										{!! Form::label('typeid', 'Tipo Identificación', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::select('type_id',array('CEDULA CIUDADANIA' => 'CEDULA CIUDADANIA','TERJETA IDENTIDAD' => 'TERJETA IDENTIDAD','REGISTRO CIVIL' => 'REGISTRO CIVIL', 'CEDULA EXTRANJERIA' => 'CEDULA EXTRANJERIA'),old('type_id'), array('class' => 'form-control','placeholder'=>'Selecciona una opción')) !!}
									</div>
								</div>
								
								<div class="form-group">															
									<div class="col-md-3">									
										{!! Form::label('identificacion', 'Identiticación', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::text('identification', old('identification'), array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa la identificación', 'autofocus'=>'autofocus'))!!}
									</div>
								</div>
								
								<div class="form-group input-grp">									
									<div class="col-md-3">
										{!! Form::label('birthplace', 'Lugar de expedición', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::text('birthplace',old('birthplace'), array('class' => 'form-control','placeholder'=>'Expedición de la Identificación')) !!}
									</div>
								</div>								
								
								<div class="form-group input-grp">									
									<div class="col-md-3">
										{!! Form::label('birthdate', 'Fecha de nacimiento', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::text('birthdate',old('birthdate'), array('class' => 'form-control','placeholder'=>'aaaa-mm-dd')) !!}
									</div>
								</div>
								
								<div class="form-group input-grp">									
									<div class="col-md-4">
										{!! Form::label('email', 'Correo electronico', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::email('email',old('email'), array('class' => 'form-control','placeholder'=>'Ingresa el correo electonico')) !!}
									</div>
								</div>
								
								<div class="form-group input-grp">									
									<div class="col-md-4">
										{!! Form::label('movil_number', 'Teléfono 1', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::text('movil_number',old('movil_number'), array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa el número del movil')) !!}
									</div>
								</div>
			
								<div class="form-group input-grp">									
									<div class="col-md-4">
										{!! Form::label('fix_number', 'Teléfono 2', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::text('fix_number',old('fix_number'), array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa el número fijo')) !!}
									</div>
								</div>
								
								<div class="form-group input-grp">									
									<div class="col-md-4">
										{!! Form::label('state', 'Departamento', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::select('state',Session::get('modulo.departamentos'),old('state'), array('class' => 'form-control','placeholder'=>'Ingresa el departamento de recidencia')) !!}
									</div>
								</div>
								
								<div class="form-group input-grp">									
									<div class="col-md-4">
										@if(!old('edit'))
											@if(old('city'))
												{!! Form::label('city', 'Ciudad', array('class' => 'col-md-12 control-label')) !!}
												{!! Form::select('city',Session::get('modulo.ciudades'),old('city'), array('class' => 'form-control','placeholder'=>'Ingresa la ciudad de recidencia')) !!}
											@else
												{!! Form::label('city', 'Ciudad', array('class' => 'col-md-12 control-label')) !!}
												{!! Form::select('city',array(),null, array('class' => 'form-control','placeholder'=>'Ingresa la ciudad de recidencia')) !!}
											@endif										
										@else
											{!! Form::label('city', 'Ciudad', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::select('city',Session::get('modulo.ciudades'),old('city'), array('class' => 'form-control','placeholder'=>'Ingresa la ciudad de recidencia')) !!}										
										@endif
									</div>
								</div>
								
								<div class="form-group input-grp">									
									<div class="col-md-4">
										{!! Form::label('neighborhood', 'Barrio', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::text('neighborhood',old('neighborhood'), array('class' => 'form-control','placeholder'=>'Ingresa el barrio de residencia')) !!}
									</div>
								</div>
															
								<div class="form-group input-grp">									
									<div class="col-md-4">
										{!! Form::label('adress', 'Dirección', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::text('adress',old('adress'), array('class' => 'form-control','placeholder'=>'Ingresa la dirección de residencia')) !!}
									</div>
								</div>
								
								<div class="form-group input-grp">									
									<div class="col-md-4">
										{!! Form::label('home', 'Tipo de Residencia', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::select('home',array('PROPIA' => 'PROPIA', 'FAMILIAR' => 'FAMILIAR', 'ARRENDADA' => 'ARRENDADA'),old('neighborhood'), array('class' => 'form-control','placeholder'=>'Elije si la vivienda es propia, familiar o arrendada')) !!}
									</div>
								</div>

								<div class="form-group input-grp">									
									<div class="col-md-4">
										{!! Form::label('profession', 'Profesión', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::text('profession',old('profession'), array('class' => 'form-control','placeholder'=>'Ingresa tu ocupasión')) !!}
									</div>
								</div>
								
								<div class="form-group input-grp">									
									<div class="col-md-4">
										{!! Form::label('paymentadress', 'Dirección de Cobro', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::text('paymentadress',old('paymentadress'), array('class' => 'form-control','placeholder'=>'Ingresa tu dirección de Pago')) !!}
									</div>
								</div>
								
								
								<div class="form-group input-grp">									
									<div class="col-md-4">
										{!! Form::label('reference', 'Referencia', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::text('reference',old('reference'), array('class' => 'form-control','placeholder'=>'Ingresa tu referencia')) !!}
									</div>
								</div>
								
								<div class="form-group input-grp">
									
									<div class="col-md-4">
										{!! Form::label('reference_phone', 'Telefono Referencia', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::text('reference_phone',old('reference_phone'), array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa el telefono de tu referencia')) !!}
									</div>
								</div>
								
								@if(old('edit'))
									{!! Form::hidden('user_id', old('user_id')) !!}
									{!! Form::hidden('name', old('name')) !!}
									{!! Form::hidden('numer_b', old('nb')) !!}
									{!! Form::hidden('numer_p', old('np')) !!}									
								@endif
																
							</div>
							
							<div class="tab-pane fade" id="tab2">
										
								{{-- Descomentar el codigo para code automatico--}}		
								{{-- {!! Form::hidden('code',old('code')) !!} --}}
								
								{{-- Comentar este div para code automatico--}}
								
								
								@if(old('edit'))
								<div class="form-group input-grp">									
									<div class="col-md-3">
										{!! Form::label('code', 'N° Contrato', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::text('code',old('code'), array('class' => 'form-control','placeholder'=>'Ingresa el N° de Contrato')) !!}
									</div>
								</div>
																
								<div class="form-group input-grp">									
									<div class="col-md-3">
										{!! Form::label('waytopay', 'Forma de pago', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::select('waytopay',array('EFECTIVO' => 'EFECTIVO', 'TRANSFERENCIA' => 'TRANSFERENCIA', 'PAGO ELECTRONICO' => 'PAGO ELECTRONICO'),old('waytopay'), array('class' => 'form-control','placeholder'=>'Elije la forma de pago')) !!}
									</div>
								</div>

								<div class="form-group input-grp">									
									<div class="col-md-3">
										{!! Form::label('price', 'Precio', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::text('price',old('price'), array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa el precio de la suscripción')) !!}
									</div>
								</div>

								<div class="form-group input-grp">									
									<div class="col-md-3">
										{!! Form::label('pay_interval', 'Proximo pago', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::text('pay_interval',old('pay_interval'), array('class' => 'form-control','placeholder'=>'aaaa-mm-dd')) !!}
									</div>
								</div>
								@endif
								
								@if(!old('edit'))
								<div class="form-group input-grp">									
									<div class="col-md-2">
										{!! Form::label('code', 'N° Contrato', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::text('code',old('code'), array('class' => 'form-control','placeholder'=>'Ingresa el N° de Contrato')) !!}
									</div>
								</div>
																
								<div class="form-group input-grp">									
									<div class="col-md-3">
										{!! Form::label('waytopay', 'Forma de pago', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::select('waytopay',array('EFECTIVO' => 'EFECTIVO', 'TRANSFERENCIA' => 'TRANSFERENCIA', 'PAGO ELECTRONICO' => 'PAGO ELECTRONICO'),old('waytopay'), array('class' => 'form-control','placeholder'=>'Elije la forma de pago')) !!}
									</div>
								</div>

								<div class="form-group input-grp">									
									<div class="col-md-2">
										{!! Form::label('payment', 'Couta inicial', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::text('payment',old('payment'), array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa el primer pago')) !!}
									</div>
								</div>

								<div class="form-group input-grp">									
									<div class="col-md-2">
										{!! Form::label('n_receipt', 'N° Recibo', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::text('n_receipt',old('n_receipt'), array('class' => 'form-control','placeholder'=>'Ingresa el número de recibo')) !!}
									</div>
								</div>

								<div class="form-group input-grp">									
									<div class="col-md-3">
										{!! Form::label('pay_interval', 'Proximo pago', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::text('pay_interval',old('pay_interval'), array('class' => 'form-control','placeholder'=>'aaaa-mm-dd')) !!}
									</div>
								</div>
								@endif
								
								
								
								<div class="form-group input-grp">									
									<div class="col-md-12">
										{!! Form::label('observation', 'Observación', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::textarea('observation',old('observation'), array('class' => 'form-control','placeholder'=>'Ingresa las observaciones')) !!}
									</div>
								</div>	
								
								<div class="form-group input-grp">									
									<div class="col-md-4">
										{!! Form::label('provisional', 'N° Provisional', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::text('provisional',old('provisional'), array('class' => 'form-control','placeholder'=>'Ingresa el número provisional')) !!}
									</div>
								</div>

								@if(Session::get('opaplus.usuario.rol_id') == 1 || Session::get('opaplus.usuario.rol_id') == 2)
									<div class="form-group input-grp">									
										<div class="col-md-4">
											{!! Form::label('date_suscription', 'Fecha de suscripción', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::text('date_suscription',old('date_suscription'), array('class' => 'form-control date_suscription','placeholder'=>'Formato: aaaa-mm-dd')) !!}
										</div>
									</div>
									
									<div class="form-group input-grp">									
										<div class="col-md-4">
											{!! Form::label('date_expiration', 'Fecha de vencimiento', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::text('date_expiration',old('date_expiration'), array('class' => 'form-control date_expiration','placeholder'=>'Formato: aaaa-mm-dd')) !!}
										</div>
									</div>
			
									
								@endif
								
								@if(Session::get('opaplus.usuario.rol_id') == 1 || Session::get('opaplus.usuario.rol_id') == 2)
									<!-- opciónes solo para superadministrador -->
									<div class="form-group">								
										<div class="col-md-12">
											{!! Form::label('adviser', 'Asesor', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::text('adviser',old('adviser'),array('class' => 'form-control','placeholder'=>'Ingresa un asesor')) !!}
										</div>
									</div>	
								@else
									<div class="form-group">
									{!! Form::hidden('adviser',Session::get('opaplus.usuario.names').' '.Session::get('opaplus.usuario.surnames').' '.Session::get('opaplus.usuario.identificacion')) !!}
									</div>
								@endif
								
								@if(old('edit'))
								<div class="form-group">								
									<div class="col-md-12">
										{!! Form::label('state', 'Estado', array('class' => 'col-md-12 control-label')) !!}
										{!! Form::select('state_id',Session::get('modulo.estados'),old('state_id'),array('class' => 'form-control','placeholder'=>'Ingresa el estado')) !!}
									</div>
								</div>		
								@endif						
								
							</div>							
							
							@if(old('edit'))
								<div class="tab-pane fade" id="tab3">
								
								<ul class="nav nav-tabs" style="margin-top:8px">
									<li role="type-beneficiary" class="active"><a href="#tab_tb_1" data-toggle="tab">POR SUSCRIPCIÓN</a></li>
									<li role="type-beneficiary"><a href="#tab_tb_2" data-toggle="tab">ADICIONALES</a></li>
								</ul>
								
								<div class="tab-content" >
									<div class="tab-pane fade in active" id="tab_tb_1">
										<ul class="nav nav-tabs nav-cnts" style=" margin-top:8px">
										<!-- aqui van todas los carnets por suscripcion -->
											@if(Session::has('modulo.cnts'))
												@php ($i=1)											
												@foreach (Session::get('modulo.cnts') as $cnt)
													@if($cnt['type'] == 'suscription' || $cnt['type'] == 'suscription_add')
														@if($i==1)
															<li role="beneficiary" class="active"><a href="#tab_c_{{$i}}" data-toggle="tab">CARNET N°{{$i}}</a></li>
														@else
															<li role="beneficiary"><a href="#tab_c_{{$i}}" data-toggle="tab">CARNET N°{{$i}}</a></li>
														@endif														
														@php ($i++)
													@endif													
												@endforeach
												<div class="col-md-1 col-md-offset-0" data-toggle="tooltip" title="" data-original-title="Agregar Carnet">           			
					            					<a href="javascript:clu_suscripcion.add_cnt()" class="site_title" style="text-decoration: none;color:#5A738E !important;line-height: 43px !important;height: 43px !important;">
					            					<i class="fa fa-plus" style="border: 1px solid trasparent !important;border-radius:15% !important;padding: 2px 2px !important;"></i>	
					            					</a>
				            					</div>
											@endif
										</ul>
										<div class="tab-content content-crnt" >
											@if(Session::has('modulo.cnts'))
												@php ($i=1)<!-- para la primera tab activa -->
												@php ($j=0)<!-- Para la cantidad de suscriptores -->
												@php ($k=1)<!-- para las tab inputs_c_k -->	
												@php ($m=0)<!-- cuenta el maximo id, k fallo -->											
												@foreach (Session::get('modulo.cnts') as $cnt)
													<!-- Construcción de carnets -->
													@if($cnt['type'] == 'suscription' || $cnt['type'] == 'suscription_add')
														<!-- Solo carnets de Suscripcion, hasta 8 beneficiarios -->
														@if($i==1)	
														<!-- $i = 1, para la primera tab activa -->													
														<div class="tab-pane fade in active" id="tab_c_{{$i}}">
															<div class = "inputs_c_{{$cnt['id']}} inputs_c">
																@if(Session::has('modulo.bnes'))																	
																	@foreach (Session::get('modulo.bnes') as $bne)
																		@if($bne['license_id'] == $cnt['id'] )
																			<div class="form-group">
																			{!! Form::hidden('bne_beneficiaryid_'.$cnt['id'].'_'.$bne['id'],$bne['id']) !!}
																			
																			<div class="col-md-3">									
																				{!! Form::label('names', 'Nombres', array('class' => 'col-md-12 control-label')) !!}
																				{!! Form::text('bne_names_'.$cnt['id'].'_'.$bne['id'], $bne['names'], array('class' => 'form-control','placeholder'=>'Ingresa los Nombres'))!!}
																			</div>
																			<div class="col-md-3">									
																				{!! Form::label('surnames', 'Apellidos', array('class' => 'col-md-12 control-label')) !!}
																				{!! Form::text('bne_surnames_'.$cnt['id'].'_'.$bne['id'], $bne['surnames'], array('class' => 'form-control','placeholder'=>'Ingresa los Apellidos'))!!}
																			</div>
																			
																				<!-- Comentado por mal entindido con el usuario -->
																						<div class="col-md-3 bne_add_{{$cnt['id'].'_'.$bne['id']}}" style = "display:none">
																							{!! Form::label('typeid', 'Tipo Id', array('class' => 'col-md-12 control-label')) !!}
																							{!! Form::select('bne_type_id_'.$cnt['id'].'_'.$bne['id'],array('CEDULA CIUDADANIA' => 'CEDULA CIUDADANIA','TERJETA IDENTIDAD' => 'TERJETA IDENTIDAD','REGISTRO CIVIL' => 'REGISTRO CIVIL', 'CEDULA EXTRANJERIA' => 'CEDULA EXTRANJERIA'),$bne['type_id'], array('class' => 'form-control','placeholder'=>'SELECCIONA UNA OPCIÓN')) !!}	
																						</div>
																						<div class="col-md-3 bne_add_{{$cnt['id'].'_'.$bne['id']}}" style = "display:none">									
																							{!! Form::label('identificacion', 'Identiticación', array('class' => 'col-md-12 control-label')) !!}
																							{!! Form::text('bne_identification_'.$cnt['id'].'_'.$bne['id'], $bne['identification'] , array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa la identificación'))!!}
																						</div>
																						<div class="col-md-3 bne_add_{{$cnt['id'].'_'.$bne['id']}}" style = "display:none">
																							{!! Form::label('relationship', 'Parentesco', array('class' => 'col-md-12 control-label')) !!}
																							{!! Form::text('bne_relationship_'.$cnt['id'].'_'.$bne['id'], $bne['relationship'], array('class' => 'form-control','placeholder'=>'Ingresa el parentesco'))!!}
																						</div>
																						
																						<div class="col-md-3 bne_add_{{$cnt['id'].'_'.$bne['id']}}" style = "display:none">									
																							{!! Form::label('movil_number', 'Celular', array('class' => 'col-md-12 control-label')) !!}
																							{!! Form::text('bne_movil_number_'.$cnt['id'].'_'.$bne['id'], $bne['movil_number'], array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa el teléfono movil'))!!}
																						</div>
																						<div class="col-md-3 bne_add_{{$cnt['id'].'_'.$bne['id']}}" style = "display:none">
																							{!! Form::label('civil_status', 'Estado Civil', array('class' => 'col-md-12 control-label')) !!}
																							{!! Form::select('bne_civil_status_'.$cnt['id'].'_'.$bne['id'],array('SOLTERO' => 'SOLTERO','COMPROMETIDO' => 'COMPROMETIDO' ,'CASADO' => 'CASADO','DIVORSIADO' => 'DIVORSIADO','VIUDO' => 'VIUDO'),$bne['civil_status'], array('class' => 'form-control','placeholder'=>'SELECCIONA UNA OPCIÓN')) !!}	
																						</div>
																						{{--
																						<div class="col-md-3">	
																							{!! Form::label('more', 'Otros datos', array('class' => 'col-md-12 control-label')) !!}
																							{!! Form::text('bne_more_'.$cnt['id'].'_'.$bne['id'], $bne['more'], array('class' => 'form-control','placeholder'=>'Separados por comas'))!!}
																						</div>
																						--}}
																			<div class="col-md-3">
																				{!! Form::label('more', 'Información Adicional', array('class' => 'col-md-12 control-label')) !!}																			
																				{!! Form::button('Información Adicional', array('id'=>'bne_more_'.$cnt['id'].'_'.$bne['id'],'class' => 'col-md-12 btn btn-default btn_more')) !!}
																			</div>
																			<div class="col-md-12"><hr size = "1"></hr></div>
																			</div>
																			@php ($j++)<!-- Cuenta los socios por suscripcion -->
																			@if($bne['id'] > $m)
																				@php($m = $bne['id'])
																			@endif
																		@endif
																	@endforeach
																@endif
															</div>
															<div class="col-md-1 col-md-offset-11" data-toggle="tooltip" title="" data-original-title="Agregar Beneficiario">           			
								            					<a href="javascript:clu_suscripcion.add_bne({{$cnt['id']}})" class="site_title site_title2" style="text-decoration: none;color:#5A738E !important;  ">
								            					<i class="fa fa-plus" style="border: 1px solid #5A738E !important"></i>	
								            					</a>
							            					</div>
														</div>															
														@else
														<div class="tab-pane fade" id="tab_c_{{$i}}">
															<div class = "inputs_c_{{$cnt['id']}} inputs_c">
																@if(Session::has('modulo.bnes'))
																	@foreach (Session::get('modulo.bnes') as $bne)
																		@if($bne['license_id'] == $cnt['id'] )
																		<div class="form-group">
																			{!! Form::hidden('bne_beneficiaryid_'.$cnt['id'].'_'.$bne['id'],$bne['id']) !!}
																			
																			<div class="col-md-3">									
																				{!! Form::label('names', 'Nombres', array('class' => 'col-md-12 control-label')) !!}
																				{!! Form::text('bne_names_'.$cnt['id'].'_'.$bne['id'], $bne['names'], array('class' => 'form-control','placeholder'=>'Ingresa los Nombres'))!!}
																			</div>
																			<div class="col-md-3">									
																				{!! Form::label('surnames', 'Apellidos', array('class' => 'col-md-12 control-label')) !!}
																				{!! Form::text('bne_surnames_'.$cnt['id'].'_'.$bne['id'], $bne['surnames'], array('class' => 'form-control','placeholder'=>'Ingresa los Apellidos'))!!}
																			</div>
																			
																				<!-- Comentado por mal entindido con el usuario -->
																						<div class="col-md-3 bne_add_{{$cnt['id'].'_'.$bne['id']}}" style = "display:none">
																							{!! Form::label('typeid', 'Tipo Id', array('class' => 'col-md-12 control-label')) !!}
																							{!! Form::select('bne_type_id_'.$cnt['id'].'_'.$bne['id'],array('CEDULA CIUDADANIA' => 'CEDULA CIUDADANIA','TERJETA IDENTIDAD' => 'TERJETA IDENTIDAD','REGISTRO CIVIL' => 'REGISTRO CIVIL', 'CEDULA EXTRANJERIA' => 'CEDULA EXTRANJERIA'),$bne['type_id'], array('class' => 'form-control','placeholder'=>'SELECCIONA UNA OPCIÓN')) !!}	
																						</div>
																						<div class="col-md-3 bne_add_{{$cnt['id'].'_'.$bne['id']}}" style = "display:none">									
																							{!! Form::label('identificacion', 'Identiticación', array('class' => 'col-md-12 control-label')) !!}
																							{!! Form::text('bne_identification_'.$cnt['id'].'_'.$bne['id'], $bne['identification'] , array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa la identificación'))!!}
																						</div>
																						<div class="col-md-3 bne_add_{{$cnt['id'].'_'.$bne['id']}}" style = "display:none">
																							{!! Form::label('relationship', 'Parentesco', array('class' => 'col-md-12 control-label')) !!}
																							{!! Form::text('bne_relationship_'.$cnt['id'].'_'.$bne['id'], $bne['relationship'], array('class' => 'form-control','placeholder'=>'Ingresa el parentesco'))!!}
																						</div>
																						
																						<div class="col-md-3 bne_add_{{$cnt['id'].'_'.$bne['id']}}" style = "display:none">	
																							{!! Form::label('movil_number', 'Celular', array('class' => 'col-md-12 control-label')) !!}
																							{!! Form::text('bne_movil_number_'.$cnt['id'].'_'.$bne['id'], $bne['movil_number'], array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa el teléfono movil'))!!}
																						</div>
																						<div class="col-md-3 bne_add_{{$cnt['id'].'_'.$bne['id']}}" style = "display:none">
																							{!! Form::label('civil_status', 'Estado Civil', array('class' => 'col-md-12 control-label')) !!}
																							{!! Form::select('bne_civil_status_'.$cnt['id'].'_'.$bne['id'],array('SOLTERO' => 'SOLTERO','COMPROMETIDO' => 'COMPROMETIDO' ,'CASADO' => 'CASADO','DIVORSIADO' => 'DIVORSIADO','VIUDO' => 'VIUDO'),$bne['civil_status'], array('class' => 'form-control','placeholder'=>'SELECCIONA UNA OPCIÓN')) !!}	
																						</div>
																						{{--
																						<div class="col-md-3 bne_add_{{$cnt['id'].'_'.$bne['id']}}" style = "display:none">
																							{!! Form::label('more', 'Otros datos', array('class' => 'col-md-12 control-label')) !!}
																							{!! Form::text('bne_more_'.$cnt['id'].'_'.$bne['id'], $bne['more'], array('class' => 'form-control','placeholder'=>'Separados por comas'))!!}
																						</div>
																						--}}
																						
																						<div class="col-md-3">
																							{!! Form::label('more', 'Información Adicional', array('class' => 'col-md-12 control-label')) !!}																			
																							{!! Form::button('Información Adicional', array('id'=>'bne_more_'.$cnt['id'].'_'.$bne['id'],'class' => 'col-md-12 btn btn-default btn_more')) !!}
																						</div>
																			
																			<div class="col-md-12"><hr size = "1"></hr></div>
																			</div>
																			@php ($j++)<!-- Cuenta los socios por suscripcion -->
																			@if($bne['id'] > $m)
																				@php($m = $bne['id'])
																			@endif
																		@endif
																	@endforeach
																@endif
															</div>
															<div class="col-md-1 col-md-offset-11" data-toggle="tooltip" title="" data-original-title="Agregar Beneficiario">           			
								            					<a href="javascript:clu_suscripcion.add_bne({{$cnt['id']}})" class="site_title site_title2" style="text-decoration: none;color:#5A738E !important;  ">
								            					<i class="fa fa-plus" style="border: 1px solid #5A738E !important"></i>	
								            					</a>
							            					</div>
														</div>
														@endif
														@php ($i++)
														@php ($k++)	
													@endif																									
												@endforeach<!-- fin for carnets -->
												@php($m++)
												<script type="text/javascript">  clu_suscripcion.benes = {{$j}};</script>
												<script type="text/javascript">  clu_suscripcion.n_add = {{$m}};</script>											
											@endif
										</div>
									</div>
									<div class="tab-pane fade" id="tab_tb_2">
										<!-- Tab para suscriptores adicionales actualizar -->
										@if(Session::has('modulo.cnts'))
											@php ($i=1)
											@foreach (Session::get('modulo.cnts') as $cnt)
												@if($cnt['type'] == 'beneficiary_add')
													@if(Session::has('modulo.bnes'))														
														@foreach (Session::get('modulo.bnes') as $bne)
															@if($bne['license_id'] == $cnt['id'] )
																@if($i==1)
																	<!-- Crea un solo div de inputs -->
																	<div class = "inputs_c_{{$cnt['id']}} inputs_c">
																	@php ($c=$cnt['id'])
																	@php ($i++)																	
																@endif
																<div class="form-group">
																	{!! Form::hidden('bneadd_beneficiaryid_'.$cnt['id'].'_'.$bne['id'],$bne['id']) !!}
																	
																	<div class="col-md-3">									
																		{!! Form::label('names', 'Nombres', array('class' => 'col-md-12 control-label')) !!}
																		{!! Form::text('bneadd_names_'.$cnt['id'].'_'.$bne['id'], $bne['names'], array('class' => 'form-control','placeholder'=>'Ingresa los Nombres'))!!}
																	</div>
																	<div class="col-md-3">									
																		{!! Form::label('surnames', 'Apellidos', array('class' => 'col-md-12 control-label')) !!}
																		{!! Form::text('bneadd_surnames_'.$cnt['id'].'_'.$bne['id'], $bne['surnames'], array('class' => 'form-control','placeholder'=>'Ingresa los Apellidos'))!!}
																	</div>
																	
																			<div class="col-md-3 bne_add_{{$cnt['id'].'_'.$bne['id']}}" style = "display:none">
																				{!! Form::label('typeid', 'Tipo Id', array('class' => 'col-md-12 control-label')) !!}
																				{!! Form::select('bneadd_type_id_'.$cnt['id'].'_'.$bne['id'],array('CEDULA CIUDADANIA' => 'CEDULA CIUDADANIA','TERJETA IDENTIDAD' => 'TERJETA IDENTIDAD','REGISTRO CIVIL' => 'REGISTRO CIVIL', 'CEDULA EXTRANJERIA' => 'CEDULA EXTRANJERIA'),$bne['type_id'], array('class' => 'form-control','placeholder'=>'SELECCIONA UNA OPCIÓN')) !!}	
																			</div>
																			<div class="col-md-3 bne_add_{{$cnt['id'].'_'.$bne['id']}}" style = "display:none">
																				{!! Form::label('identificacion', 'Identiticación', array('class' => 'col-md-12 control-label')) !!}
																				{!! Form::text('bneadd_identification_'.$cnt['id'].'_'.$bne['id'], $bne['identification'] , array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa la identificación'))!!}
																			</div>
																			<div class="col-md-3 bne_add_{{$cnt['id'].'_'.$bne['id']}}" style = "display:none">
																				{!! Form::label('relationship', 'Parentesco', array('class' => 'col-md-12 control-label')) !!}
																				{!! Form::text('bneadd_relationship_'.$cnt['id'].'_'.$bne['id'], $bne['relationship'], array('class' => 'form-control','placeholder'=>'Ingresa el parentesco'))!!}
																			</div>
																			
																			<div class="col-md-3 bne_add_{{$cnt['id'].'_'.$bne['id']}}" style = "display:none">
																				{!! Form::label('movil_number', 'Celular', array('class' => 'col-md-12 control-label')) !!}
																				{!! Form::text('bneadd_movil_number_'.$cnt['id'].'_'.$bne['id'], $bne['movil_number'], array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa el teléfono movil'))!!}
																			</div>
																			<div class="col-md-3 bne_add_{{$cnt['id'].'_'.$bne['id']}}" style = "display:none">
																				{!! Form::label('civil_status', 'Estado Civil', array('class' => 'col-md-12 control-label')) !!}
																				{!! Form::select('bneadd_civil_status_'.$cnt['id'].'_'.$bne['id'],array('SOLTERO' => 'SOLTERO','COMPROMETIDO' => 'COMPROMETIDO' ,'CASADO' => 'CASADO','DIVORSIADO' => 'DIVORSIADO','VIUDO' => 'VIUDO'),$bne['civil_status'], array('class' => 'form-control','placeholder'=>'SELECCIONA UNA OPCIÓN')) !!}	
																			</div>
																			{{--
																			<div class="col-md-3 bne_add_{{$cnt['id'].'_'.$bne['id']}}" style = "display:none">
																				{!! Form::label('more', 'Otros datos', array('class' => 'col-md-12 control-label')) !!}
																				{!! Form::text('bneadd_more_'.$cnt['id'].'_'.$bne['id'], $bne['more'], array('class' => 'form-control','placeholder'=>'Separados por comas'))!!}
																			</div>
																			--}}
																			<div class="col-md-3">
																				{!! Form::label('more', 'Información Adicional', array('class' => 'col-md-12 control-label')) !!}																			
																				{!! Form::button('Información Adicional', array('id'=>'bneadd_more_'.$cnt['id'].'_'.$bne['id'],'class' => 'col-md-12 btn btn-default btn_more')) !!}
																			</div>
																	
																	<div class="col-md-12"><hr size = "1"></hr></div>
																</div>
															
															@endif
														@endforeach
														
													@endif	
												@endif
											@endforeach
											@if($i==1)
												<!-- No hay beneficiarios adicionales, creamos un div y un boton-->	
												<div class = "inputs_c_0 inputs_c">
												</div>
												<div class="col-md-1 col-md-offset-11" data-toggle="tooltip" title="" data-original-title="Agregar Beneficiario">           			
					            					<a href="javascript:clu_suscripcion.add_bne(0,'add')" class="site_title site_title2" style="text-decoration: none;color:#5A738E !important;  ">
					            					<i class="fa fa-plus" style="border: 1px solid #5A738E !important"></i>	
					            					</a>
				            					</div>
											@endif
											@if($i!=1)
												<!-- Crea un solo div de inputs CIERRE-->
												</div>
												<!-- Crea un solo boton de agregar -->
												<div class="col-md-1 col-md-offset-11" data-toggle="tooltip" title="" data-original-title="Agregar Beneficiario">           			
					            					<a href="javascript:clu_suscripcion.add_bne({{$c}},'add')" class="site_title site_title2" style="text-decoration: none;color:#5A738E !important;  ">
					            					<i class="fa fa-plus" style="border: 1px solid #5A738E !important"></i>	
					            					</a>
				            					</div>
												
											@endif
										@endif
									</div>
								
								</div>
								
								</div>
							
								@if(Session::get('opaplus.usuario.rol_id') == 1 || Session::get('opaplus.usuario.rol_id') == 2)
								<!-- solo para administradores -->						
									<div class="tab-pane fade" id="tab4">
									
									@if(Session::has('modulo.pagos'))
										<div class="pay-form-inputs col-md-12">
										@foreach (Session::get('modulo.pagos') as $pago)
											
											<div class = "form-group">
												<div class = "col-md-4 ">
													{!! Form::label('pago_abono_'.$pago['id'], 'Abono', array('class' => 'col-md-12 control-label')) !!}
													{!! Form::text('pago_abono_'.$pago['id'],$pago['payment'],array('class' => 'form-control','id' => 'pago_abono_'.$pago['id'] )) !!}
												</div>
												<div class = "col-md-4 ">
													{!! Form::label('n_receipt_'.$pago['id'], 'N° Recibo', array('class' => 'col-md-12 control-label')) !!}
													{!! Form::text('n_receipt_'.$pago['id'],$pago['n_receipt'],array('class' => 'form-control n_receipt','id' => 'n_receipt_'.$pago['id'])) !!}
												</div>													
												<div class = "col-md-4 ">
													{!! Form::label('fecha_pago_'.$pago['id'], 'Fecha de Abono', array('class' => 'col-md-12 control-label')) !!}
													{!! Form::text('fecha_pago_'.$pago['id'],$pago['date_payment'],array('class' => 'form-control fecha_pago','id' => 'fecha_pago_'.$pago['id'])) !!}
												</div>													
											</div>
											
											<!--  <script type="text/javascript">  clu_suscripcion.datos_fechas.push('date_payment_'+{{$pago['id']}});</script> -->
										@endforeach
										</div>
										
										<!-- Bonon agregar abonos 				
										<div class="col-md-1 col-md-offset-11" data-toggle="tooltip" title="" data-original-title="Agregar Abono">           			
					            			<a href="javascript:clu_suscripcion.add_pay()" class="site_title site_title2" style="text-decoration: none;color:#5A738E !important;  ">
					            				<i class="fa fa-plus" style="border: 1px solid #5A738E !important"></i>	
					            			</a>
				            			</div>
				            			-->
				            			
									@endif									
									</div>
								@endif								
							
							@else
							
							<div class="tab-pane fade" id="tab3">	
							
								<ul class="nav nav-tabs" style="margin-top:8px">
									<li role="type-beneficiary" class="active"><a href="#tab_tb_1" data-toggle="tab">POR SUSCRIPCIÓN</a></li>
									<li role="type-beneficiary"><a href="#tab_tb_2" data-toggle="tab">ADICIONALES</a></li>
								</ul>
								
								<div class="tab-content" >
									
									<div class="tab-pane fade in active" id="tab_tb_1">
									
										<ul class="nav nav-tabs nav-cnts" style=" margin-top:8px">										
											<li role="beneficiary" class="active"><a href="#tab_c_1" data-toggle="tab">CARNET N°1</a></li>
																				
											<div class="col-md-1 col-md-offset-0" data-toggle="tooltip" title="" data-original-title="Agregar Carnet">           			
				            					<a href="javascript:clu_suscripcion.add_cnt()" class="site_title" style="text-decoration: none;color:#5A738E !important;line-height: 43px !important;height: 43px !important;">
				            					<i class="fa fa-plus" style="border: 1px solid trasparent !important;border-radius:15% !important;padding: 2px 2px !important;"></i>	
				            					</a>
			            					</div>																
										</ul>								
										
										<div class="tab-content content-crnt" >
										
											<div class="tab-pane fade in active" id="tab_c_1">
												<div class = "inputs_c_1 inputs_c">
													<div class="form-group">
																												
														<div class="col-md-3">									
															{!! Form::label('names', 'Nombres', array('class' => 'col-md-12 control-label')) !!}
															{!! Form::text('bne_names_1_1', old('bne_names_1'), array('class' => 'form-control','placeholder'=>'Ingresa los Nombres'))!!}
														</div>
														
														<div class="col-md-3">									
															{!! Form::label('surnames', 'Apellidos', array('class' => 'col-md-12 control-label')) !!}
															{!! Form::text('bne_surnames_1_1', old('bne_surnames_1'), array('class' => 'form-control','placeholder'=>'Ingresa los Apellidos'))!!}
														</div>
														
																	<div class="col-md-3 bne_add_1_1" style = "display:none">
																		{!! Form::label('typeid', 'Tipo Id', array('class' => 'col-md-12 control-label')) !!}
																		{!! Form::select('bne_type_id_1_1',array('CEDULA CIUDADANIA' => 'CEDULA CIUDADANIA','TERJETA IDENTIDAD' => 'TERJETA IDENTIDAD','REGISTRO CIVIL' => 'REGISTRO CIVIL', 'CEDULA EXTRANJERIA' => 'CEDULA EXTRANJERIA'),old('type_id'), array('class' => 'form-control','placeholder'=>'SELECCIONE UNA OPCIÓN')) !!}	
																	</div>
																															
																	<div class="col-md-3 bne_add_1_1" style = "display:none">
																		{!! Form::label('identificacion', 'Identiticación', array('class' => 'col-md-12 control-label')) !!}
																		{!! Form::text('bne_identification_1_1', old('bne_identification_1'), array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa la identificación'))!!}
																	</div>
																	
																	<div class="col-md-3 bne_add_1_1" style = "display:none">
																		{!! Form::label('relationship', 'Parentesco', array('class' => 'col-md-12 control-label')) !!}
																		{!! Form::text('bne_relationship_1_1', old('bne_relationship_1'), array('class' => 'form-control','placeholder'=>'Ingresa el parentesco'))!!}
																	</div>
																	
																	<div class="col-md-3 bne_add_1_1" style = "display:none">
																		{!! Form::label('movil_number', 'Celular', array('class' => 'col-md-12 control-label')) !!}
																		{!! Form::text('bne_movil_number_1_1', old('bne_movil_number_1'), array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa el teléfono movil'))!!}
																	</div>
																	<div class="col-md-3 bne_add_1_1" style = "display:none">
																		{!! Form::label('civil_status', 'Estado Civil', array('class' => 'col-md-12 control-label')) !!}
																		{!! Form::select('bne_civil_status_1_1',array('SOLTERO' => 'SOLTERO','COMPROMETIDO' => 'COMPROMETIDO' ,'CASADO' => 'CASADO','DIVORSIADO' => 'DIVORSIADO','VIUDO' => 'VIUDO'),old('civil_status'), array('class' => 'form-control','placeholder'=>'SELECCIONA UNA OPCIÓN')) !!}	
																	</div>
																	{{--																															
																	<div class="col-md-3 bne_add_1_1" style = "display:none">
																		{!! Form::label('more', 'Otros Datos', array('class' => 'col-md-12 control-label')) !!}
																		{!! Form::text('bne_more_1_1', old('bne_more_1'), array('class' => 'form-control','placeholder'=>'Separados por comas'))!!}
																	</div>
																	--}}
																	<div class="col-md-3">
																		{!! Form::label('more', 'Información Adicional', array('class' => 'col-md-12 control-label')) !!}																			
																		{!! Form::button('Información Adicional', array('id'=>'bne_more_1_1','class' => 'col-md-12 btn btn-default btn_more')) !!}
																	</div>	
														
														<div class="col-md-12"><hr size = "1"></hr></div>
														
													</div>
												
												</div>
												
												<div class="col-md-1 col-md-offset-11" data-toggle="tooltip" title="" data-original-title="Agregar Beneficiario">           			
					            					<a href="javascript:clu_suscripcion.add_bne('1')" class="site_title site_title2" style="text-decoration: none;color:#5A738E !important;  ">
					            					<i class="fa fa-plus" style="border: 1px solid #5A738E !important"></i>	
					            					</a>
				            					</div>
				            																
											</div>	<!-- Cierre de tab -->
																					
										</div> <!-- Cierre de content tab -->
									
									</div> <!-- Cierre de tab -->
									
									<!-- Tab beneficiarios adicionales -->
									<div class="tab-pane fade" id="tab_tb_2">
										
										<div class = "inputs_c_11 inputs_c">													
										</div>
										
										<div class="col-md-1 col-md-offset-11" data-toggle="tooltip" title="" data-original-title="Agregar Beneficiario">           			
			            					<a href="javascript:clu_suscripcion.add_bne(11,'add')" class="site_title site_title2" style="text-decoration: none;color:#5A738E !important;  ">
			            					<i class="fa fa-plus" style="border: 1px solid #5A738E !important"></i>	
			            					</a>
		            					</div>

									</div>
									
								</div>
															
							</div>							
							<script type="text/javascript">  clu_suscripcion.benes = 1;</script>	
							@endif
													
						</div>	<!-- Cierre de content tab -->					
						
					</div>
					{!! Form::hidden('mod_id', old('modulo_id')) !!}
					{!! Form::hidden('edit', old('edit')) !!}
					{!! Form::hidden('suscription_id', old('suscription_id')) !!}
					<div class="form-group">
						<div class="col-md-1 col-md-offset-0">
							{!! Form::submit('Enviar', array('class' => 'btn btn-primary')) !!}																
						</div>
						<div class="col-md-1 col-md-offset-0">
							<a href="{{ url('suscripcion/listar') }}" class="btn btn-primary">Cancelar</a>															
						</div>
					</div>										
			
				{!! Form::close() !!}				
				</div>
			</div>		
		</div>
	</div>
	 <!-- Form en blanco para consultar Ciudades -->
	{!! Form::open(array('id'=>'form_consult_city','url' => 'suscripcion/consultarcity')) !!}
    {!! Form::close() !!}
		
</div>		
@endsection

@section('script')	
	<script type="text/javascript" src="{{ asset('/js/lib/datetimepiker.js') }}"></script>	
	<script type="text/javascript">  
			
  		javascript:seg_user.iniciarDatepiker('birthdate');//todavia no esta definido
  		//javascript:seg_user.iniciarDatepiker('date_suscription');
		javascript:seg_user.iniciarDatepiker('date_expiration');
		javascript:seg_user.iniciarDatepiker('pay_interval');
	

		//iniciar datepiker para fechas
		/*
		for (i = 0; i < clu_suscripcion.datos_fechas.length; i++) {
			javascript:seg_user.iniciarDatepiker(clu_suscripcion.datos_fechas[i]);			
		}
		*/
		$('.date_suscription').datetimepicker({
			//dateFormat: "yy-mm-dd",
			//timeFormat: "hh:mm:ss",
			format: "yyyy-mm-dd",
	        language: "es",
	        autoclose: true	
		});
		
		$('.fecha_pago').datetimepicker({
			//dateFormat: "yy-mm-dd",
			//timeFormat: "hh:mm:ss",
			format: "yyyy-mm-dd",
	        language: "es",
	        autoclose: true	
		});		
  		
  		//autocomplete con los datos iniciales
		$( "#adviser" ).autocomplete({
		      source: clu_suscripcion.datos_advisers
	    });

		$( "#state" ).change(function() {
			var datos = new Array();
			datos['id'] =$( "#state option:selected" ).val();			   
			seg_ajaxobject.peticionajax($('#form_consult_city').attr('action'),datos,"clu_suscripcion.consultaRespuestaCity");
		});

		$('.btn_more').click(function(){
			if(this.innerHTML == "Información Adicional"){
				//se quiere mostrar los datos
				this.innerHTML = "Información Basica";
				$('.bne_add'+this.id.substr(8)).fadeIn();
			}else{
				if(this.innerHTML == "Información Basica"){
					//se quiere ocultar la información adicional
					this.innerHTML = "Información Adicional";
					$('.bne_add'+this.id.substr(8)).fadeOut();
				}
			}			
		});

		$( ".solo_numeros" ).keypress(function(evt) {
			 evt = (evt) ? evt : window.event;
		    var charCode = (evt.which) ? evt.which : evt.keyCode;
		    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		        return false;
		    }
		    return true;
		});		
	    
		clu_suscripcion.datos_advisers= [];
		clu_suscripcion.datos_fechas= [];
		
  		
	</script>
@endsection