@extends('app')

@section('content')		
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
					<strong>¡Modulo Entidad!</strong> La operación se ha realizado adecuadamente.<br><br>
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
    {!! Form::open(array('id'=>'form_ver','url' => 'entidad/ver')) !!}
    {!! Form::close() !!}
    {!! Form::open(array('id'=>'form_nuevo','url' => 'entidad/nuevo')) !!}
    {!! Form::close() !!}

	</div>
@endsection

@section('modal')

	<div class="modal fade" id="entidad_ver_modal" role="dialog" data-backdrop="false">
	    <div class="modal-dialog modal-lg">	    
	    <!-- Modal content-->      
	      	<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Resumen Entidad</h4>
				</div>
				<div class = "alerts-module"></div>				
							
				<div class="modal-body">
					<ul class="nav nav-tabs">
						<li role="presentation" class="active"><a href="#tab1" data-toggle="tab">ENTIDAD</a></li>
						<li role="presentation"><a href="#tab2" data-toggle="tab">SUCURSALES</a></li>					
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
					</div>					
				</div>
				<div class="modal-footer">		         
		          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>		                  
		        </div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="entidad_nuevo_modal" role="dialog" data-backdrop="false">
		<div class="modal-dialog modal-lg">	    
	    <!-- Modal content-->      
	      	<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="form_entidad_title" >Agregar Entidad</h4>
				</div>
				<div class = "alerts-module"></div>
				{!! Form::open(array('url' => 'entidad/save', 'id'=>'form_nuevo_entidad','onsubmit'=>'javascript:return clu_entidad.validateNuevaEntidad()')) !!}	
				<div class="modal-body">
					<ul class="nav nav-tabs">
						<li role="bnes_cnt" class="active"><a href="#tab_entidad1" data-toggle="tab">ENTIDAD</a></li>
						<li role="bnes_cnt"><a href="#tab_entidad2" data-toggle="tab">SUCURSALES</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_entidad1">
							<div class="row ">
								<div class="col-md-12 col-md-offset-0">
									<div class="form-group">

										<div class="row col-md-12">
											<div class="col-md-12">
												{!! Form::label('nombre', 'Nombre', array('class' => 'col-md-12 control-label')) !!}
												{!! Form::text('nombre', old('nombre'), array('class' => 'form-control','placeholder'=>'Ingresa el nombre de la Entidad','autofocus'=>'autofocus'))!!}
											</div>
										</div>
										<div class="row col-md-6">											
											<div class="col-md-12">
												{!! Form::label('representante_legal', 'Representate Legal', array('class' => 'col-md-12 control-label')) !!}
												{!! Form::text('representante_legal', old('representante_legal'), array('class' => 'form-control','placeholder'=>'Ingresa el Representante Legal'))!!}
											</div>
											<div class="col-md-12">
												{!! Form::label('telefono1', 'Telefono 1', array('class' => 'col-md-12 control-label ')) !!}
												{!! Form::text('telefono1', old('telefono1'), array('class' => 'form-control ','placeholder'=>'Ingresa un número telefonico'))!!}
											</div>
											<div class="col-md-12">
												{!! Form::label('correo', 'Correo de Contacto', array('class' => 'col-md-12 control-label')) !!}
												{!! Form::text('correo', old('correo'), array('class' => 'form-control','placeholder'=>'Ingresa el correo del contacto'))!!}
											</div>
										</div>
										<div class="row col-md-6">
											<div class="col-md-12">
												{!! Form::label('nit', 'NIT', array('class' => 'col-md-12 control-label ')) !!}
												{!! Form::text('nit', old('nit'), array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa el Nit'))!!}
											</div>
											<div class="col-md-12">
												{!! Form::label('contancto_rlegal', 'Contancto Representate Legal', array('class' => 'col-md-12 control-label')) !!}
												{!! Form::text('contancto_rlegal', old('contancto_rlegal'), array('class' => 'form-control','placeholder'=>'Ingresa el contacto del Representante Legal'))!!}
											</div>
											<div class="col-md-12">
												{!! Form::label('telefono2', 'Telefono 2', array('class' => 'col-md-12 control-label')) !!}
												{!! Form::text('telefono2', old('telefono2'), array('class' => 'form-control ','placeholder'=>'Ingresa un número telefonico'))!!}
											</div>
										</div>
										<div class="row col-md-12">
											<div class="col-md-12">
												{!! Form::label('descripcion', 'Descripción', array('class' => 'col-md-12 control-label')) !!}
												{!! Form::textarea('descripcion',old('descripcion'), array('class' => 'form-control','rows'=>'4','placeholder'=>'Ingresa las Descripciónes u Observaciones')) !!}
											</div>
										</div>
										{!! Form::hidden('edit', old('edit'),array('id'=>'edit')) !!}
										{!! Form::hidden('entity_id', old('entity_id'),array('id'=>'entity_id')) !!}
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade " id="tab_entidad2">
							<div class="row ">
								<div class="col-md-12 col-md-offset-0 tab_entidad2">
									<div class="form-group">
										<div class="col-md-2">
											{!! Form::label('subent_nombre_1', 'Nombre', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::text('subent_nombre_1', old('subent_nombre_1'), array('class' => 'form-control','placeholder'=>'Nombre de Sucursal'))!!}
										</div>	
										<div class="col-md-2">
											{!! Form::label('subent_direccion_1', 'Dirección', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::text('subent_direccion_1', old('subent_direccion_1'), array('class' => 'form-control','placeholder'=>'Dirección de Sucursal'))!!}
										</div>	
										<div class="col-md-2">
											{!! Form::label('subent_telefonouno_1', 'Telefono 1', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::text('subent_telefonouno_1', old('subent_telefonouno_1'), array('class' => 'form-control ','placeholder'=>'Primer Teléfono Sucursal'))!!}
										</div>
										<div class="col-md-2">
											{!! Form::label('subent_telefonodos_1', 'Telefono 2', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::text('subent_telefonodos_1', old('subent_telefonodos_1'), array('class' => 'form-control ','placeholder'=>'Segundo Teléfono Sucursal'))!!}
										</div>	
										<div class="col-md-2">
											{!! Form::label('subent_email_1', 'Correo', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::text('subent_email_1', old('subent_email_1'), array('class' => 'form-control','placeholder'=>'Correo de Sucursal'))!!}
										</div>
										<div class="col-md-2">
											{!! Form::label('subent_municipio_1', 'Municipio', array('class' => 'col-md-12 control-label')) !!}
											{!! Form::text('subent_municipio_1', old('subent_municipio_1'), array('class' => 'form-control','placeholder'=>'Municipio de Sucursal'))!!}
										</div>
									</div>									
								</div>
								<div class="col-md-12"><hr size = "1"></hr></div>
								<div class="col-md-1 col-md-offset-11" data-toggle="tooltip" title="" data-original-title="Agregar Entidad">
	            					<a href="javascript:clu_entidad.add_subent('1')" class="site_title site_title2" style="text-decoration: none;color:#5A738E !important;  ">
	            					<i class="fa fa-plus" style="border: 1px solid #5A738E !important"></i>	
	            					</a>
            					</div>
							</div>

						</div>
					</div>

				</div>
				{!! Form::close() !!}
				<div class="modal-footer">
					<button type="submit" form = "form_nuevo_entidad" class="btn btn-default" id="form_entidad_button" >Crear Entidad</button>	         
		          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>		                  
		        </div>

			</div>
		</div>
	</div>
	
	
@endsection

@section('script')	
	<script type="text/javascript">
		javascript:clu_entidad.table = $('#example').DataTable( {
		    "responsive": true,
		    "processing": true,
		    "bLengthChange": false,
		    "serverSide": true,	        
		    "ajax": "{{url('entidad/listarajax')}}",	
		    "iDisplayLength": 25,       
		    "columns": [				   
		        { "data": "business_name"},        	            
		        { "data": "nit" },
		        { "data": "legal_representative" },
		        { "data": "contact_representative" },		        
		        { "data": "phone1_contact" },
		        { "data": "phone2_contact" },
		        { "data": "email_contact" }
		                    
		    ],	       
		    "language": {
		        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		    }		    
		});
		@if(Session::has('filtro'))
			clu_entidad.table.search( "{{Session::get('filtro')}}" ).draw();
		@endif
		javascript:$('#example tbody').on( 'click', 'tr', function () {
		    if ($(this).hasClass('selected')) {
		        $(this).removeClass('selected');
		    }
		    else {
		    	clu_entidad.table.$('tr.selected').removeClass('selected');
		        $(this).addClass('selected');
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


	</script>
@endsection