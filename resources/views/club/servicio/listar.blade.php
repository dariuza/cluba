@extends('app')

@section('content')
	{{ Html::style('css/lib/bootstrap-timepicker.min.css')}}
	{{ Html::style('css/lib/chosen.css')}}
	{{ Html::style('css/lib/daterangepicker.css')}}

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
		.chosen-container .chosen-container-multi{
			border: 1px solid #ccc !important;
			border-radius: 4px !important;
		}
		.fila{
			margin-top: 10px;
		}
		.site_title3{
			color: #73879C !important;
		}

		.site_title3 i {
		    border: 1px solid #73879C !important;	   
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
		            				<a href="javascript:clu_servicio.opt_edt()" class="site_title site_title2" style = "text-decoration: none; ">
			            				<i class="{{$opc['icono']}}"></i>
			            			</a>
		            			</div>
		            		@elseif($opc['accion'] == 'crear')	            			
	            				<div class="col-md-1" data-toggle="tooltip" title = "{{$opc[$key]}}">
		            				<a href="javascript:clu_servicio.opt_agregar()" class="site_title site_title2" style = "text-decoration: none; ">
			            				<i class="{{$opc['icono']}}"></i>
			            			</a>
		            			</div>	            		            				           			
	            			@elseif($opc['accion'] == 'mirar')	            			
	            				<div class="col-md-1" data-toggle="tooltip" title = "{{$opc[$key]}}">
		            				<a href="javascript:clu_servicio.opt_ver()" class="site_title site_title2" style = "text-decoration: none; ">
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
			

		</div> 

		<!-- Form en blanco para capturar la url editar y eliminar-->
	    {!! Form::open(array('id'=>'form_ver','url' => 'servicio/ver')) !!}
	    {!! Form::close() !!}
	    {!! Form::open(array('id'=>'form_nuevo','url' => 'servicio/nuevo')) !!}
	    {!! Form::close() !!}
	    {!! Form::open(array('id'=>'form_consultar_entidades','url' => 'servicio/consultarentidad')) !!}
	    {!! Form::close() !!}

	</div>
@endsection

@section('modal')

	<div class="modal fade" id="servicio_ver_modal" role="dialog" data-backdrop="false">
	    <div class="modal-dialog modal-lg">	    
	    </div>
    </div>

    <div class="modal fade" id="servicio_nuevo_modal" role="dialog" data-backdrop="false">
	    <div class="modal-dialog modal-md">
	    	<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="form_entidad_title" >Consultar Nuevo Servicio</h4>
				</div>
				<div class = "alerts-module"></div>
				{!! Form::open(array('url' => 'servicio/consultservicio', 'id'=>'form_nuevo_servicio','onsubmit'=>'javascript:return clu_servicio.validateNuevoServicio()')) !!}	
				<div class="modal-body">

					<ul class="nav nav-tabs">
						<li role="bnes_cnt" class="active"><a href="#tab_servicio1" data-toggle="tab">USUARIO</a></li>
						<li role="bnes_cnt"><a href="#tab_servicio2" data-toggle="tab">CONSULTA</a></li>
					</ul>

					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_servicio1">
							<div class="row ">
								<div class="col-md-12 col-md-offset-0">
									<div class="col-md-6">
										{!! Form::label('criterio_usuario', 'Cedula de Usuario ó Código Suscripción', array('class' => ' control-label')) !!}{!! Form::text('criterio_usuario', old('cedula_usuario'), array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa una cedula o código'))!!}		
									</div>
									<div class="col-md-2">
										<div class="col-md-12" data-toggle="tooltip" title="" data-original-title="Consultar Usuario">
				            				<a href="javascript:clu_servicio.opt_ver_usuario()" class="site_title site_title3" style="text-decoration: none; ">
					            				<i class="fa fa-users"></i>
					            			</a>
				            			</div>
									</div>	
									<div class="col-md-5">
									</div>
								</div>
							</div>
						</div>

						<div class="tab-pane fade " id="tab_servicio2">
							<div class="row ">
								<div class="col-md-12 col-md-offset-0">
									<div class="form-group">
										<div class="col-md-12">
											{!! Form::label('especialidad', 'Especialidad', array('class' => 'col-md-4 control-label')) !!}
											{!! Form::select('especialidad',Session::get('modulo.especialidades'),old('especialidad'), array('class' => 'form-control chosen-select','id'=>'select_especialidad','placeholder'=>'Ingresa la especialidad')) !!}
										</div>

										<div class="col-md-12 fila">
											{!! Form::label('municipio', 'Municipio', array('class' => 'col-md-4 control-label')) !!}
											{!! Form::select('municipio',Session::get('modulo.municipios'),old('municipio'), array('class' => 'form-control chosen-select select_municipio','id'=>'select_municipio','placeholder'=>'Ingresa el municipio')) !!}
										</div>

										<div class="col-md-12 fila">
											{!! Form::label('entidad', 'Entidad', array('class' => 'col-md-4 control-label')) !!}
											{!! Form::select('entidad',array(),old('entidad'), array('class' => 'form-control chosen-select select_entidad','id'=>'select_entidad','placeholder'=>'Ingresa la entidad')) !!}
										</div>

										<div class="col-md-12 fila">
											{!! Form::label('fecha', 'Fecha inicio', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::text('fechainicio',old('fechainicio'), array('class' => 'form-control','id'=>'fechainicio','placeholder'=>'aaaa-mm-dd')) !!}
										</div>

										<div class="col-md-12 fila">
											{!! Form::label('fechafin', 'Fecha fin', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::text('fechafin',old('fechafin'), array('class' => 'form-control','id'=>'fechafin','placeholder'=>'aaaa-mm-dd')) !!}
										</div>

										<div class="col-md-12 fila">
											<input name="entidad_check" type="checkbox" value="1" id="entidad_check">
											<label for="entidad_check" class="control-label">Usar Entidad</label>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>

					
				</div>
				{!! Form::close() !!}
				<div class="modal-footer">					
					<button type="submit" form = "form_nuevo_servicio" class="btn btn-default" id="form_entidad_button" >Consultar Disponibilidad</button>	         
		          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>		                  
		        </div>

			</div> 
	    </div>
    </div>
	
@endsection

@section('script')

	{{ Html::script('js/lib/datetimepiker.js') }}
	{{ Html::script('js/lib/daterangepicker.js') }}		
	{{ Html::script('js/lib/bootstrap-timepicker.min.js') }}
	{{ Html::script('js/lib/chosen.jquery.min.js')}}
	{{ Html::script('js/lib/moment.js') }}
	
	<script type="text/javascript">  
		$('.chosen-select').chosen();
		$('.chosen-container').width('100%');

		javascript:seg_user.iniciarDatepikerInicio('fechainicio');	
		javascript:seg_user.iniciarDatepikerFin('fechafin');

		$("#select_especialidad").chosen().change(function(event) {
			//consultamos las cusursales correspondientes al municipio
			if($("#select_especialidad").val()){
				var datos = new Array();
  				datos['idmunicipio'] = $("#select_municipio").val();
  				datos['idespecialidad'] = $("#select_especialidad").val();  		
  				seg_ajaxobject.peticionajax($('#form_consultar_entidades').attr('action'),datos,"clu_servicio.verRespuestaEntidades");
			}else{
				alert('seleccionar una especialidad')
			}			
		});		

		$("#select_municipio").chosen().change(function(event) {
			//consultamos las cusursales correspondientes al municipio
			if($("#select_municipio").val()){
				var datos = new Array();
  				datos['idmunicipio'] = $("#select_municipio").val();
  				datos['idespecialidad'] = $("#select_especialidad").val();  		
  				seg_ajaxobject.peticionajax($('#form_consultar_entidades').attr('action'),datos,"clu_servicio.verRespuestaEntidades");
			}else{
				alert('seleccionar un municipio')
			}			
		});	

	</script>
	
@endsection