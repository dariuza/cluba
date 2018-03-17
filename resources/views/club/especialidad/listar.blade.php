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
		            				<a href="javascript:clu_especialidad.opt_select('{{json_decode(Session::get('opaplus.usuario.permisos')[Session::get('modulo.id_app')]['modulos'][Session::get('modulo.categoria')][Session::get('modulo.id_mod')]['preferencias'])->controlador}}','{{($opc['accion'])}}/{{Session::get('modulo.id_app')}}/{{Session::get('modulo.categoria')}}/{{Session::get('modulo.id_mod')}}')" class="site_title site_title2" style = "text-decoration: none; ">
			            				<i class="{{$opc['icono']}}"></i>
			            			</a>
		            			</div>	            				           			
	            			@elseif($opc['accion'] == 'mirar')	            			
	            				<div class="col-md-1" data-toggle="tooltip" title = "{{$opc[$key]}}">
		            				<a href="javascript:clu_especialidad.opt_ver()" class="site_title site_title2" style = "text-decoration: none; ">
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
				<div class="alert alert-danger">
					<strong>Algo no va bien con el Modulo!</strong> Hay problemas con con los datos diligenciados.<br><br>
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			
			@if(Session::has('message'))
				<div class="alert alert-info">
					<strong>¡Modulo Especialidades!</strong> La operación se ha realizado adecuadamente.<br><br>
					<ul>								
						<li>{{ Session::get('message') }}</li>
					</ul>
				</div>
                
            @endif
            <!-- error llega cuando se esta recuperando la contraseña inadecuadamente -->
            @if(Session::has('error'))
				<div class="alert alert-danger">
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
    {!! Form::open(array('id'=>'form_ver','url' => 'especialidad/ver')) !!}
    {!! Form::close() !!}
	</div>
@endsection

@section('modal')

	<div class="modal fade" id="especialidad_ver_modal" role="dialog" data-backdrop="false">
	    <div class="modal-dialog modal-lg">	    
	    <!-- Modal content-->      
	      	<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Resumen Especialiad</h4>
				</div>
				<div class = "alerts-module"></div>				
							
				<div class="modal-body">
					<div class="row ">
						<div class="col-md-12 col-md-offset-0 row_izq">
						</div>
						<hr class="col-md-10 col-md-offset-1" style="border: 1px solid;">
						<div class="col-md-12 col-md-offset-0 row_table">
							<table id="table_specialty" class="display " cellspacing="0" width="100%">
						         <thead>
						            <tr>
						            	<th>Especialidad</th>
						            	<th>Especialista</th>
						            	<th>Precio</th>
						            	<th>Precio Suscripción</th>
						            	<th>Entidad</th>
						            	<th>Sucursal</th>
						            	<th>Ciudad</th>
						            	<th>Dirección</th>
						            </tr>
						        </thead>              
						    </table>
						</div>
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
	 	
		javascript:clu_especialidad.table = $('#example').DataTable( {
		    "responsive": true,
		    "processing": true,
		    "bLengthChange": false,
		    "serverSide": true,	        
		    "ajax": "{{url('especialidad/listarajax')}}",	
		    "iDisplayLength": 25,       
		    "columns": [
		    					   
		        { "data": "name"}, 
		        { "data": "code"}, 
		        { "data": "description"}	             	            
		        		                   
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
			clu_especialidad.table.search( "{{Session::get('filtro')}}" ).draw();
		@endif	
		javascript:$('#example tbody').on( 'click', 'tr', function () {
		    if ($(this).hasClass('selected')) {
		        $(this).removeClass('selected');
		    }
		    else {
		    	clu_especialidad.table.$('tr.selected').removeClass('selected');
		        $(this).addClass('selected');
		    }
		});


		clu_especialidad.table_specialty = $('#table_specialty').DataTable( {
		    "responsive": true,
		    "processing": true,
		    "bLengthChange": false,
		    "serverSide": true,	        
		    "ajax": "{{url('especialidad/listarspecialtyajax')}}",	
		    "iDisplayLength": 25,       
		    "columns": [
		    	/*				   
		        { "data": "specialty"}, 
		        { "data": "name"}, 
		        { "data": "rate_particular"}, 
		        { "data": "rate_suscriptor"} 
		        */

		       	{ "data": "specialty"}, 
		        { "data": "name"}, 
		        { "data": "rate_particular"}, 
		        { "data": "rate_suscriptor"},
		        { "data": "entity"},      
		        { "data": "subentity"},
		        { "data": "city"},
		        { "data": "adress"}           	            
		        		                   
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
		
	</script>
@endsection