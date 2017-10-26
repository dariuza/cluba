@extends('app')

@section('content')
	{{ Html::style('css/lib/bootstrap-timepicker.min.css')}}
	{{ Html::style('css/lib/chosen.css')}}
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
	    	background-color: #007BC1;
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
		            				<a href="javascript:clu_especialista.opt_select('{{json_decode(Session::get('opaplus.usuario.permisos')[Session::get('modulo.id_app')]['modulos'][Session::get('modulo.categoria')][Session::get('modulo.id_mod')]['preferencias'])->controlador}}','{{($opc['accion'])}}/{{Session::get('modulo.id_app')}}/{{Session::get('modulo.categoria')}}/{{Session::get('modulo.id_mod')}}')" class="site_title site_title2" style = "text-decoration: none; ">
			            				<i class="{{$opc['icono']}}"></i>
			            			</a>
		            			</div>
		            		@elseif($opc['accion'] == 'crear')	            			
	            				<div class="col-md-1" data-toggle="tooltip" title = "{{$opc[$key]}}">
		            				<a href="javascript:clu_especialista.opt_agregar()" class="site_title site_title2" style = "text-decoration: none; ">
			            				<i class="{{$opc['icono']}}"></i>
			            			</a>
		            			</div>		            				           			
	            			@elseif($opc['accion'] == 'mirar')	            			
	            				<div class="col-md-1" data-toggle="tooltip" title = "{{$opc[$key]}}">
		            				<a href="javascript:clu_especialista.opt_ver()" class="site_title site_title2" style = "text-decoration: none; ">
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
		            				<!--  <span >{{$opc[$key]}}</span> -->	            				
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
				<div class="alert alert-danger lert-dismissable">
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
				<div class="alert alert-info lert-dismissable">
					<button type="button" class="close close_alert_product" data-dismiss="alert">&times;</button>
					<strong>¡Modulo Especialistas!</strong> La operación se ha realizado adecuadamente.<br><br>
					<ul>								
						<li>{{ Session::get('message') }}</li>
					</ul>
				</div>
                
            @endif
            <!-- error llega cuando se esta recuperando la contraseña inadecuadamente -->
            @if(Session::has('error'))
				<div class="alert alert-danger lert-dismissable">
					<button type="button" class="close close_alert_product" data-dismiss="alert">&times;</button>
					<strong>¡Algo no va bien!</strong> Hay problemas con los datos diligenciados.<br><br>
					<ul>								
						<li>{{ Session::get('error') }}</li>								
					</ul>
				</div>
                
            @endif
    </div>
	<table id="example" class="display " cellspacing="0" width="100%">
         <thead>
            <tr>
            	@if(Session::has('modulo.fillable'))
            		@foreach (Session::get('modulo.fillable') as $col)
            			<th>{{$col}}</th>
            		@endforeach
            	@endif               
            </tr>
        </thead>              
    </table>
    
    <!-- Form en blanco para capturar la url editar y eliminar-->
    {!! Form::open(array('id'=>'form_ver','url' => 'especialista/ver')) !!}
    {!! Form::close() !!}
    {!! Form::open(array('id'=>'form_nuevo','url' => 'especialista/nuevo')) !!}
    {!! Form::close() !!}
    {!! Form::open(array('id'=>'form_select_specialista','url' => 'especialista/selectentity')) !!}
    {!! Form::close() !!}    
	</div>
@endsection

@section('modal')

	<div class="modal fade" id="especialista_ver_modal" role="dialog" data-backdrop="false">
	    <div class="modal-dialog">	    
	    <!-- Modal content-->      
	      	<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Resumen Especialista</h4>
				</div>
				<div class = "alerts-module"></div>				
							
				<div class="modal-body">
					<ul class="nav nav-tabs">
						<li role="presentation" class="active"><a href="#tab1" data-toggle="tab">ESPECIALISTA</a></li>
						<li role="presentation"><a href="#tab2" data-toggle="tab">ESPECIALIDADES</a></li>
						<li role="presentation"><a href="#tab3" data-toggle="tab">DISPONIBILIDAD</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab1">
							<div class="row ">
								<div class="col-md-12 col-md-offset-0 tab1"></div>						
							</div>
						</div>
						<div class="tab-pane fade " id="tab2">
							<div class="row ">
								<div class="col-md-12 col-md-offset-0 tab2"></div>						
							</div>
						</div>
						<div class="tab-pane fade " id="tab3">
							<div class="row ">
								<div class="col-md-12 col-md-offset-0 tab3"></div>						
							</div>
						</div>
					</div>					
				</div>
				<div class="modal-footer">		         
		          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>		                  
		        </div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="especialidad_nuevo_modal" role="dialog" data-backdrop="false">
	    <div class="modal-dialog modal-lg">	    
	    <!-- Modal content-->      
	      	<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Agregar Especialista</h4>
				</div>
				<div class = "alerts-module"></div>				
				{!! Form::open(array('url' => 'especialista/save', 'id'=>'form_nuevo_especialista','onsubmit'=>'javascript:return clu_especialista.validateNuevoEspecialista()')) !!}			
				<div class="modal-body">
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
										<div class="row col-md-12">
											<div class="col-md-12">
												{!! Form::label('entidad', 'Entidad', array('class' => 'col-md-12 control-label')) !!}
												{!! Form::select('entidad',array(),old('entidad'), array('class' => 'form-control','onchange'=>'clu_especialista.changeSelectEntidad(this)','placeholder'=>'Ingresa la Entidad')) !!}
											</div>											
										</div>
										<div class="row col-md-6">
											<div class="col-md-12">
												{!! Form::label('nombres', 'Nombres', array('class' => 'col-md-12 control-label')) !!}
												{!! Form::text('nombres', old('nombres'), array('class' => 'form-control','placeholder'=>'Ingresa los nombres','autofocus'=>'autofocus'))!!}
											</div>
											<div class="col-md-12">
												{!! Form::label('identificacion', 'Identificación', array('class' => 'col-md-12 control-label solo_numeros')) !!}
												{!! Form::text('identificacion', old('identificacion'), array('class' => 'form-control','placeholder'=>'Ingresa la Identificación'))!!}
											</div>
											<div class="col-md-12">
												{!! Form::label('telefono_uno', 'Teléfono 1', array('class' => 'col-md-12 control-label')) !!}
												{!! Form::text('telefono_uno', old('telefono_uno'), array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa Teléfono uno'))!!}
											</div>
											<div class="col-md-12">
												{!! Form::label('telefono_dos', 'Teléfono 2', array('class' => 'col-md-12 control-label solo_numeros')) !!}
												{!! Form::text('telefono_dos', old('telefono_dos'), array('class' => 'form-control','placeholder'=>'Ingresa Teléfono dos'))!!}
											</div>
											<div class="col-md-12">
												{!! Form::label('correo_electronico', 'Correo Eletrónico', array('class' => 'col-md-12 control-label')) !!}
												{!! Form::email('correo_electronico', old('correo_electronico'), array('class' => 'form-control','placeholder'=>'Ingresa Correo Eletrónico'))!!}
											</div>
										</div>
										<div class="row col-md-6">
											<div class="col-md-12">
												{!! Form::label('nombres_asistente', 'Nombres Asistente', array('class' => 'col-md-12 control-label')) !!}
												{!! Form::text('nombres_asistente', old('nombres_asistente'), array('class' => 'form-control','placeholder'=>'Ingresa Nombres de Asistente'))!!}
											</div>											
											<div class="col-md-12">
												{!! Form::label('telefono_uno_asistente', 'Teléfono 1 Asistente', array('class' => 'col-md-12 control-label solo_numeros')) !!}
												{!! Form::text('telefono_uno_asistente', old('telefono_uno_asistente'), array('class' => 'form-control','placeholder'=>'Ingresa Teléfono uno de Asistente'))!!}
											</div>
											<div class="col-md-12">
												{!! Form::label('telefono_dos_asistente', 'Teléfono 2 Asistente', array('class' => 'col-md-12 control-label')) !!}
												{!! Form::text('telefono_dos_asistente', old('telefono_dos_asistente'), array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa Teléfono dos Asistente'))!!}
											</div>
											<div class="col-md-12">
												{!! Form::label('correo_electronico_asistente', 'Correo Eletrónico Asistente', array('class' => 'col-md-12 control-label')) !!}
												{!! Form::email('correo_electronico_asistente', old('correo_electronico_asistente'), array('class' => 'form-control','placeholder'=>'Ingresa Correo Eletrónico Asistente'))!!}
											</div>
										</div>
										<div class="row col-md-12">
											<div class="col-md-12">
												{!! Form::label('descripcion', 'Descripción', array('class' => 'col-md-12 control-label')) !!}
												{!! Form::textarea('descripcion',old('descripcion'), array('class' => 'form-control','rows'=>'4','placeholder'=>'Ingresa las Descripciónes u Observaciones')) !!}
											</div>
										</div>
									</div>
								</div>						
							</div>
						</div>
						<div class="tab-pane fade " id="tab_dispo2">
							<div class="row ">
								<div class="col-md-12 col-md-offset-0 tab_dispo2">
									<div class="form-group">
										
										<div class="col-md-3">
											{!! Form::label('espe_especialidad_1', 'Especialidad', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::select('espe_especialidad_1',Session::get('modulo.especialidades'),old('espe_especialidad_1'), array('class' => 'form-control','placeholder'=>'Ingresa La Especialidad')) !!}
										</div>
										<div class="col-md-3">
											{!! Form::label('espe_precioparticular_1', 'Precio Particular', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::text('espe_precioparticular_1', old('espe_precioparticular_1'), array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa Precio'))!!}
										</div>	

										<div class="col-md-3">
											{!! Form::label('espe_preciosuscriptor_1', 'Precio Suscriptor', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::text('espe_preciosuscriptor_1', old('espe_preciosuscriptor_1'), array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa Precio'))!!}
										</div>
										<div class="col-md-3">
											{!! Form::label('espe_duracion_1', 'Tiempo Duraciòn', array('class' => 'col-md-12 control-label')) !!}
											<div class="input-group bootstrap-timepicker timepicker">									
												{!! Form::text('espe_duracion_1', old('espe_duracion_1'), array('class' => 'form-control input-small','placeholder'=>'Duraciòn HH:mm'))!!}
												<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
											</div>
										</div>
									</div>
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
									<div class="form-group col-md-12">
										<div class="col-md-2">											
											{!! Form::label('dispo_dia_1', 'Día', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::select('dispo_dia_1',array('LUNES' => 'LUNES', 'MARTES' => 'MARTES', 'MIÉRCOLES' => 'MIÉRCOLES', 'JUEVES' => 'JUEVES', 'VIERNES' => 'VIERNES', 'SÁBADO' => 'SÁBADO', 'DOMINGO' => 'DOMINGO'),old('dispo_dia_1'), array('class' => 'form-control','placeholder'=>'Elije Día')) !!}												
										</div>
										
										<div class="col-md-2">
											{!! Form::label('dispo_horainicio_1', 'Hora Inicio', array('class' => 'col-md-12 control-label')) !!}
											<div class="input-group bootstrap-timepicker timepicker">									
												{!! Form::text('dispo_horainicio_1', old('dispo_horainicio_1'), array('class' => 'form-control input-small','placeholder'=>'Hora Inicio HH:mm'))!!}
												<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
											</div>
										</div>
										
										<div class="col-md-2">
											{!! Form::label('dispo_horafin_1', 'Hora Fin', array('class' => 'col-md-12 control-label')) !!}
											<div class="input-group bootstrap-timepicker timepicker">										
												{!! Form::text('dispo_horafin_1', old('dispo_horafin_1'), array('class' => 'form-control input-small','placeholder'=>'Hora Fin HH:mm'))!!}
												<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
											</div>
										</div>
										<div class="epecialist col-md-3">
											{!! Form::label('dispo_especialidadesselect_1', 'Especialidades', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::select('dispo_especialidadesselect_1',Session::get('modulo.especialidades'),old('dispo_especialidadesselect_1'), array('class' => 'form-control chosen-select','multiple' ,'data-placeholder'=>'Selecciona las especialidades','tabindex'=>'4', 'style'=>'width:350px;')) !!}
											{!! Form::hidden('dispo_especialidades_1',old('dispo_especialidades_1'),array('id'=>'dispo_especialidades_1')) !!}
										</div>
										<div class="epecialist col-md-3">
											{!! Form::label('dispo_subentityselect_1', 'Sucursales', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::select('dispo_subentityselect_1',array(),old('dispo_subentityselect_1'), array('class' => 'form-control select-subentity','placeholder'=>'Ingresa la sucursal')) !!}
										</div>									
									</div>

								</div>	
								<div class="col-md-12"><hr size = "1"></hr></div>	
								<div class="col-md-1 col-md-offset-11" data-toggle="tooltip" title="" data-original-title="Agregar Disponibilidad">
	            					<a href="javascript:clu_especialista.add_dispo('1')" class="site_title site_title2" style="text-decoration: none;color:#5A738E !important;  ">
	            					<i class="fa fa-plus" style="border: 1px solid #5A738E !important"></i>	
	            					</a>
            					</div>
													
							</div>
						</div>
						{!! Form::hidden('edit', old('edit'),array('id'=>'edit')) !!}
						{!! Form::hidden('specialist_id', old('specialist_id'),array('id'=>'specialist_id')) !!}
					</div>
				</div>
				{!! Form::close() !!}
				<div class="modal-footer">
					<button type="submit" form = "form_nuevo_especialista" class="btn btn-default " > @if(Session::has('_old_input.edit')) Editar @else Crear @endif Especialista</button>	         
		          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>		                  
		        </div>
			</div>
		</div>
	</div>
	
	
@endsection

@section('script')
	{{ Html::script('js/lib/bootstrap-timepicker.min.js') }}
	{{ Html::script('js/lib/chosen.jquery.min.js')}}		
	<script type="text/javascript">
		
		javascript:clu_especialista.table = $('#example').DataTable( {
		    "responsive": true,
		    "processing": true,
		    "bLengthChange": false,
		    "serverSide": true,	        
		    "ajax": "{{url('especialista/listarajax')}}",	
		    "iDisplayLength": 25,       
		    "columns": [				   
		        { "data": "name"}, 
		        { "data": "identification"}, 
		        { "data": "phone1"},
		        { "data": "phone2"},
		        { "data": "email"}      	            
		        		                   
		    ],	       
		    "language": {
		        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		    },		   
		    "fnRowCallback": function( nRow, aData ) {
		        //pintar el fondo de la fila		        
		        //$(nRow).children()[5].style.backgroundColor = aData.next_alert;
		        //$(nRow).children()[4].style.backgroundColor = aData.alert;		        
		        
            }
		});
	
		@if(Session::has('filtro'))
			clu_especialista.table.search( "{{Session::get('filtro')}}" ).draw();
		@endif	
		javascript:$('#example tbody').on( 'click', 'tr', function () {
		    if ($(this).hasClass('selected')) {
		        $(this).removeClass('selected');
		    }
		    else {
		    	clu_especialista.table.$('tr.selected').removeClass('selected');
		        $(this).addClass('selected');
		    }
		});

		$('.chosen-select').chosen();
		$('.chosen-container').width('100%');		
		$("#dispo_especialidadesselect_1").chosen().change(function(event) {
			$('#dispo_especialidades_1').val($("#dispo_especialidadesselect_1").chosen().val());		    
		});		

		$('.input-small').timepicker({showMeridian:false});

		$( ".solo_numeros" ).keypress(function(evt) {
			 evt = (evt) ? evt : window.event;
		    var charCode = (evt.which) ? evt.which : evt.keyCode;
		    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		        return false;
		    }
		    return true;
		});		
		
	</script>
@endsection