@extends('app')

@section('content')

	<style type="text/css">
		.panel-body {
			/*padding: 5px;*/
		}
		.footer-form{
			margin-left: 0px;
		}
		.fil-form{
		    padding: 5px;
		    margin: 5px;
		    border-bottom: 1px solid;
		    border-left: 1px solid;
		}
		.site_title{
			color: #73879C !important;
		}

		.site_title i {
		    border: 1px solid #73879C !important;	   
		}

	</style>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 col-md-offset-0">
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

							
					{!! Form::open(array('url' => 'servicio/save', 'id'=>'form_nueva_city','onsubmit'=>'javascript:return clu_servicio.validateNuevaCita()')) !!}			
						<div class="panel-body">
							<div class = "col-md-12 alerts-form"></div>		
							<div class="form-group">										
								<div class="col-md-5 fil-form">
									{!! Form::label('especialidad', 'Especialidad', array('class' => ' control-label')) !!}
									{!! Session::get('moduloagregar.especialidades')[Session::get('modulo')[0]['especialidad']]  !!}
									{!! Form::hidden('especialidad', Session::get('modulo')[0]['especialidad']) !!}
								</div>

								<div class="col-md-6 fil-form">
									{!! Form::label('entidad', 'Entidad', array('class' => ' control-label')) !!}		
									{!! Session::get('moduloagregar.entidades')[Session::get('modulo')[0]['entidad']]  !!}
									{!! Form::hidden('entidad', Session::get('modulo')[0]['entidad']) !!}
								</div>

								<div class="col-md-3 fil-form">
									{!! Form::label('municipio', 'Municipio', array('class' => ' control-label')) !!}		
									{!! Session::get('modulo')[0]['municipio']  !!}
									{!! Form::hidden('municipio', Session::get('modulo')[0]['municipio']) !!}
								</div>

								<div class="col-md-4 fil-form">
									{!! Form::label('fechainicio', 'Fecha inicio', array('class' => ' control-label')) !!}	
									{!! Session::get('modulo')[1] !!}
									{!! Form::hidden('fechainicio', Session::get('modulo')[1]) !!}
								</div>

								<div class="col-md-4 fil-form">
									{!! Form::label('fechafin', 'Fecha fin', array('class' => ' control-label')) !!}	
									{!! Session::get('modulo')[2] !!}
									{!! Form::hidden('fechafin', Session::get('modulo')[2]) !!}
								</div>								

								<div class="col-md-4 fil-form">
									{!! Form::label('nombreusuario', 'Nombre Usuario', array('class' => ' control-label')) !!}	
									<span id ="nombreusuariospan"></span>
									{!! Form::hidden('nombreusuario', null) !!}
								</div>
								<div class="col-md-3 fil-form">
									{!! Form::label('identificacion', 'Identificación', array('class' => ' control-label')) !!}	
									<span id ="identificacionspan"></span>
									{!! Form::hidden('identificacion', null) !!}
								</div>								
								<div class="col-md-4 fil-form">
									{!! Form::label('numerocontacto', 'Número Contacto', array('class' => ' control-label')) !!}	
									<span id ="numerocontactospan"></span>
									{!! Form::hidden('numerocontacto', null) !!}
								</div>	
								<div class="col-md-4 fil-form">
									{!! Form::label('suscripcion', 'Suscripción', array('class' => ' control-label')) !!}	
									<span id ="suscripcionspan"></span>
									{!! Form::hidden('suscripcion', null) !!}
								</div>
								<div class="col-md-3 fil-form">
									{!! Form::label('estado', 'Estado', array('class' => ' control-label')) !!}	
									<span id ="estadospan"></span>
									{!! Form::hidden('estado', null) !!}
								</div>								
								<div class="col-md-4 fil-form">
									{!! Form::label('titular', 'Titular', array('class' => ' control-label')) !!}	
									<span id ="titularspan"></span>
									{!! Form::hidden('titular', null) !!}
								</div>								


							</div>

							<div class="form-group">
								<div class="col-md-4">
									{!! Form::label('cedula_usuario', 'Cedula de Usuario', array('class' => ' control-label')) !!}	
									{!! Form::text('cedula_usuario', old('cedula_usuario'), array('class' => 'form-control solo_numeros','placeholder'=>'Ingresa una cedula'))!!}		
								</div>								
							</div>

							{!! Form::hidden('id_especialista',null)!!}
							{!! Form::hidden('id_entidad',null)!!}
							{!! Form::hidden('id_especialidad',null)!!}
							{!! Form::hidden('id_suscription',null)!!}

							{!! Form::hidden('dia',null)!!}
							{!! Form::hidden('fechahora',null)!!}

							<!-- Aprovechar el formulario para editar -->
							{!! Form::hidden('edit', old('edit')) !!}
							{!! Form::hidden('price', old('price')) !!}
							{!! Form::hidden('duration', old('duration')) !!}

							<!-- Para actualizar borrar-->
							{!! Form::hidden('service_id', old('service_id')) !!}

							<div class="form-group">

								<div class="col-md-12 col-md-offset-0">
									<div class="col-md-1" data-toggle="tooltip" title="" data-original-title="Consultar Usuario">
			            				<a href="javascript:clu_servicio.opt_ver_usuario()" class="site_title site_title2" style="text-decoration: none; ">
				            				<i class="fa fa-users"></i>
				            			</a>
			            			</div>
									<div class="col-md-1" data-toggle="tooltip" title="" data-original-title="Ver Disponibilidad">
			            				<a href="javascript:clu_servicio.opt_ver_disponibilidad()" class="site_title site_title2" style="text-decoration: none; ">
				            				<i class="fa fa-eye"></i>
				            			</a>
			            			</div>
								</div>

							</div>

							<div class="form-group">

								<div class="col-md-12 col-md-offset-0">

									<!-- Tabla de citas-->
									<table id="example" class="display " cellspacing="0" width="100%">
								    	<thead>
								            <tr>						            	
								            	<th>Especialidad</th>
								            	<th>Especialista</th>
								            	<th>Municipio</th>
								            	<th>Entidad</th>
								            	<th>Dirección</th>
								            	<th>Precio</th>					            	
								            	<th>Dìa</th>
								            	<th>Fecha</th>
								            	<th>Estado</th>
								            	
								            	<th>alerta</th>
								            	<th>telefono_uno_especialista</th>
								            	<th>telefono_dos_especialista</th>
								            	<th>email_especialista</th>

								            	<th>name_asistente</th>
								            	<th>telefono_uno_asistente</th>
								            	<th>telefono_dos_asistente</th>
								            	<th>email_asistente</th>

								            	<th>nit</th>
								            	<th>telefono_uno_contacto</th>
								            	<th>telefono_dos_contacto</th>
								            	<th>email_contacto</th>

								            	<th>precio_particular</th>
								            	<th>tiempo</th>
								            	<th>id_especialista</th>
								            	<th>id_entidad</th>
								            	<th>id_especialidad</th>
								            		
								            </tr>
								        </thead>
								        <tfoot>
								            <tr>
								                <th>Especialidad</th>
								            	<th>Especialista</th>
								            	<th>Municipio</th>
								            	<th>Entidad</th>
								            	<th>Dirección</th>
								            	<th>Precio</th>					            	
								            	<th>Dìa</th>
								            	<th>Fecha</th>
								            	<th>Estado</th>
								            	
								            	<th>alerta</th>
								            	<th>telefono_uno_especialista</th>
								            	<th>telefono_dos_especialista</th>
								            	<th>email_especialista</th>

								            	<th>name_asistente</th>
								            	<th>telefono_uno_asistente</th>
								            	<th>telefono_dos_asistente</th>
								            	<th>email_asistente</th>

								            	<th>nit</th>
								            	<th>telefono_uno_contacto</th>
								            	<th>telefono_dos_contacto</th>
								            	<th>email_contacto</th>

								            	<th>precio_particular</th>
								            	<th>tiempo</th>
								            	<th>id_especialista</th>
								            	<th>id_entidad</th>
								            	<th>id_especialidad</th>

								            </tr>
								        </tfoot>
								        <tbody>						        	
						            		@foreach (Session::get('modulo')[3] as $fila)
						            			<tr>				            						            		
							            			<td> {!!$fila[18]!!} </td>
							            			<td> {!!$fila[3]!!} </td>
							            			<td> {!!$fila[17]!!} </td>
							            			<td> {!!$fila[11]!!} </td>
							            			<td> {!!$fila[13]!!} </td>
							            			<td> ${!!$fila[20]!!} </td>
							            			<td> {!!$fila[2]!!} </td>
							            			<td> {!!$fila[1]!!} </td>
							            			<td> {!!$fila[25]!!} </td>
							            			<!-- Columnas ocultas-->
							            			<td> {!!$fila[26]!!} </td>
							            			<td> {!!$fila[4]!!} </td>
							            			<td> {!!$fila[5]!!} </td>
							            			<td> {!!$fila[6]!!} </td>
							            			<td> {!!$fila[7]!!} </td>
							            			<td> {!!$fila[8]!!} </td>
							            			<td> {!!$fila[9]!!} </td>
							            			<td> {!!$fila[10]!!} </td>
							            			<td> {!!$fila[12]!!} </td>
							            			<td> {!!$fila[14]!!} </td>
							            			<td> {!!$fila[15]!!} </td>
							            			<td> {!!$fila[16]!!} </td>
							            			<td> ${!!$fila[19]!!} </td>
							            			<td> {!!$fila[21]!!} </td>
							            			<td> {!!$fila[22]!!} </td>
							            			<td> {!!$fila[23]!!} </td>
							            			<td> {!!$fila[24]!!} </td>						            			
						            			</tr>
						            		@endforeach
							            	
								        </tbody>
								    </table>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-1 col-md-offset-0">
								{!! Form::submit('Enviar', array('class' => 'btn btn-primary')) !!}
							</div>
							<div class="col-md-1 col-md-offset-1 footer-form">
								<a href="{{ url('servicio/listar') }}" class="btn btn-primary">Cancelar</a>
							</div>	
						</div>						
				
					{!! Form::close() !!}

					</div>
				</div>				
			</div>
		</div>
		 <!-- Form en blanco para consultar Ciudades -->
		{!! Form::open(array('id'=>'form_consult_user','url' => 'servicio/consultaruser')) !!}
    	{!! Form::close() !!}	
	</div>		
@endsection

@section('modal')
	<div class="modal fade" id="servicio_disponibilidad_modal" role="dialog" data-backdrop="false">
	    <div class="modal-dialog">	    
	      <!-- Modal content-->
	      <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Descripción Disponibilidad</h4>
				</div>
				<div class = "alerts-module"></div>				
				<div class="modal-body">
					<div class="row ">
						<div class="col-md-12 col-md-offset-0 row_content"></div>						
					</div>
		        </div>
		        <div class="modal-footer">		          
		          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>		                  
		        </div>     
	      </div>
      </div>
	</div>
@endsection

@section('script')		
	<script type="text/javascript">  	
		javascript:clu_servicio.table = $('#example').DataTable( {
		    "responsive": true,		    
		    "bLengthChange": false,		    
		    "iDisplayLength": 25,
		    "columnDefs": [
	            {
	                "targets": [ 9 ],
	                "visible": false,
	                "searchable": false
	            },
	            {
	                "targets": [ 10 ],
	                "visible": false,
	                "searchable": false
	            },
	            {
	                "targets": [ 11 ],
	                "visible": false,
	                "searchable": false
	            },
	            {
	                "targets": [ 12 ],
	                "visible": false,
	                "searchable": false
	            },
	            {
	                "targets": [ 13 ],
	                "visible": false,
	                "searchable": false
	            },
	            {
	                "targets": [ 14 ],
	                "visible": false,
	                "searchable": false
	            },
	            {
	                "targets": [ 15 ],
	                "visible": false,
	                "searchable": false
	            },
	            {
	                "targets": [ 16 ],
	                "visible": false,
	                "searchable": false
	            },
	            {
	                "targets": [ 17 ],
	                "visible": false,
	                "searchable": false
	            },
	            {
	                "targets": [ 18 ],
	                "visible": false,
	                "searchable": false
	            },
	            {
	                "targets": [ 19 ],
	                "visible": false,
	                "searchable": false
	            },
	            {
	                "targets": [ 20 ],
	                "visible": false,
	                "searchable": false
	            },
	            {
	                "targets": [ 21 ],
	                "visible": false,
	                "searchable": false
	            },
	            {
	                "targets": [ 22 ],
	                "visible": false,
	                "searchable": false
	            },
	            {
	                "targets": [ 23 ],
	                "visible": false,
	                "searchable": false
	            },
	            {
	                "targets": [ 24 ],
	                "visible": false,
	                "searchable": false
	            },
	            {
	                "targets": [ 25 ],
	                "visible": false,
	                "searchable": false
	            }
	        ],	    
		    "language": {
		        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		    },
		    "fnRowCallback": function( nRow, aData ) {
		        //pintar el fondo de la fila
		        //$('td', nRow).css('background-color', aData[9]);
		        $(nRow).children()[8].style.backgroundColor =aData[9];
		        
            },
		});
		
		javascript:$('#example tbody').on( 'click', 'tr', function () {
		    if ($(this).hasClass('selected')) {
		        $(this).removeClass('selected');
		    }
		    else {
		    	clu_servicio.table.$('tr.selected').removeClass('selected');
		        $(this).addClass('selected');

		        //limpiamos los campos
				$("input[name='id_especialista']").val('');
				$("input[name='id_entidad']").val('');
				$("input[name='id_especialidad']").val('');		
				$("input[name='dia']").val();
				$("input[name='fechahora']").val();
				$("input[name='price']").val();	
				$("input[name='duration']").val();		       

				if(clu_servicio.table.rows('.selected').data()[0][8] == 'libre'){
					$("input[name='id_especialista']").val(clu_servicio.table.rows('.selected').data()[0][23]);
					$("input[name='id_entidad']").val(clu_servicio.table.rows('.selected').data()[0][24]);
					$("input[name='id_especialidad']").val(clu_servicio.table.rows('.selected').data()[0][25]);
					$("input[name='dia']").val(clu_servicio.table.rows('.selected').data()[0][6]);
					$("input[name='fechahora']").val(clu_servicio.table.rows('.selected').data()[0][7]);
					$("input[name='price']").val(clu_servicio.table.rows('.selected').data()[0][5]);			
					$("input[name='duration']").val(clu_servicio.table.rows('.selected').data()[0][22]);			
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
	</script>
@endsection