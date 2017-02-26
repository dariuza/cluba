@extends('app')

@section('content')
	<style>
		.name_user{
			background-color: #D9DEE4;
		    color: #515356;		    		   
		    padding: 1%;
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
		
	</style>	
	<div class="col-md-12 col-md-offset-0 container-fluid">
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
	            		<!-- si tiene opcion editar, esta tiene un trato diferentre -->
	            			@if($opc['accion'] == 'actualizar' OR $opc['accion'] == 'borrar')            			
	            				<div class="col-md-1" data-toggle="tooltip" title = "{{$opc[$key]}}">
		            				<a href="javascript:seg_usuario.opt_select('{{json_decode(Session::get('opaplus.usuario.permisos')[Session::get('modulo.id_app')]['modulos'][Session::get('modulo.categoria')][Session::get('modulo.id_mod')]['preferencias'])->controlador}}','{{($opc['accion'])}}/{{Session::get('modulo.id_app')}}/{{Session::get('modulo.categoria')}}/{{Session::get('modulo.id_mod')}}')" class="site_title site_title2" style = "text-decoration: none; ">
			            				<i class="{{$opc['icono']}}"></i>
			            			</a>
		            			</div>            			
	            			@elseif($opc['accion'] == 'mirar')
	            				<div class="col-md-1" data-toggle="tooltip" title = "{{$opc[$key]}}">
		            				<a href="javascript:seg_usuario.opt_ver()" class="site_title site_title2" style = "text-decoration: none; ">
			            				<i class="{{$opc['icono']}}"></i>	            				           				
			            				<!--  <span >{{$opc[$key]}}</span> -->	            				
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
					<strong>¡Modulo Usuarios!</strong> La operación se ha realizado adecuadamente.<br><br>
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
    <!-- Form en blanco para capturar la url -editar y eliminar -->
    {!! Form::open(array('id'=>'form_lugar','url' => 'usuario/lugar')) !!}
    {!! Form::close() !!} 
    <!-- Form en blanco para capturar la url -ver -->
    {!! Form::open(array('id'=>'form_ver','url' => 'usuario/ver')) !!}
    {!! Form::close() !!}
    {!! Form::open(array('id'=>'form_home','url' => '/')) !!}
    {!! Form::close() !!}    
	</div>		
@endsection

@section('modal')
	<div class="modal fade" id="user_modal" role="dialog" data-backdrop="false">
	    <div class="modal-dialog">	    
	      <!-- Modal content-->
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Modulo Usuario</h4>
	        </div>
	        <div class="modal-body">
	        	 <div class="row ">
	        	 	<div class="col-md-8 col-md-offset-0 row_izq">
	        	 	</div>
	        	 	<div class="col-md-4 col-md-offset-0 row_der">
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
		javascript:seg_usuario.table = $('#example').DataTable( {
		    "responsive": true,
		    "processing": true,
	        "serverSide": true,
	        "bLengthChange": false,	        
	        "ajax": "{{url('usuario/listarajax')}}",
	        "iDisplayLength": 25,	       
	        "columns": [				   
	            { "data": "names"},
	            { "data": "surnames","visible": false },	            
	            { "data": "identificacion" },
	            { "data": "email" },	            
	            { "data": "adress"},
	            { "data": "movil_number"},	            
	            { "data": "fix_number","visible": false },
	            { "data": "birthdate",
		           "render":function(data, type, row){			           
		        	   	var fechaActual = new Date()
	          			var diaActual = fechaActual.getDate();
	            		var mmActual = fechaActual.getMonth() + 1;
	            		var yyyyActual = fechaActual.getFullYear();
	            		FechaNac = row.birthdate.split("-");
	            		var yyyyCumple = FechaNac[0];
	            		var mmCumple = FechaNac[1];
	            		var diaCumple = FechaNac[2];
	            			            		
	            		//retiramos el primer cero de la izquierda
	            		if (mmCumple.substr(0,1) == 0) {
	            		mmCumple= mmCumple.substring(1, 2);
	            		}
	            		//retiramos el primer cero de la izquierda
	            		if (diaCumple.substr(0, 1) == 0) {
	            		diaCumple = diaCumple.substring(1, 2);
	            		}
	            		var edad = yyyyActual - yyyyCumple;
	            		//validamos si el mes de cumpleaños es menor al actual
	            		//o si el mes de cumpleaños es igual al actual
	            		//y el dia actual es menor al del nacimiento
	            		//De ser asi, se resta un año
	            		if ((mmActual < mmCumple) || (mmActual == mmCumple && diaActual < diaCumple)) {
	            		edad--;
	            		}
	            		return edad;
	            		
		           },
		           "visible": false },
	            { "data": "birthdate","visible": false },	            	            
	            { "data": "sex","visible": false },	            
	            { "data": "rol","visible": true }	            
	        ],	       
	        "language": {
	            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
	        },
	        /*
	        "sDom": 'T<"clear">lfrtip',            
            "oTableTools": {
                "sSwfPath": "/assets/swf/copy_csv_xls_pdf.swf",
                "aButtons": [
                    {
                        "sExtends": "copy",
                        "mColumns": [0,1,2,3,4,5,6,7,8,9,10],
                        "sTitle": "{{Session::get('modulo.modulo')}}"
                    },
                    {
                        "sExtends": "csv",
                        "mColumns": [0,1,2,3,4,5,6,7,8,9,10],
                        "sTitle": "{{Session::get('modulo.modulo')}}"
                    },
                    {
                        "sExtends": "xls",
                        "mColumns": [0,1,2,3,4,5,6,7,8,9,10],
                        "sTitle": "{{Session::get('modulo.modulo')}}",
                        "sFileName": "*.xls"
                    },
                    {
                        "sExtends": "pdf",
                        "mColumns": [0, 2, 3, 4, 5],
                        "sTitle": "{{Session::get('modulo.modulo')}}"                        
                    }                    
                ]
            }*/
		});
		@if(Session::has('filtro'))
			seg_usuario.table.search( "{{Session::get('filtro')}}" ).draw();
		@endif	
		javascript:$('#example tbody').on( 'click', 'tr', function () {
	        if ($(this).hasClass('selected')) {
	            $(this).removeClass('selected');
	        }
	        else {
	        	seg_usuario.table.$('tr.selected').removeClass('selected');
	            $(this).addClass('selected');
	        }
	    });
	    //llamado del metodo botar
	    $('.bnt_lugar').click(function(e){
  	  		e.preventDefault();//evita que la pagina se refresque
	  	  	if(seg_usuario.table.rows('.selected').data().length){		
		  	  	var datos = new Array();
	  	  		datos['id'] = seg_usuario.table.rows('.selected').data()[0].user_id;
	  	  		datos['activo'] = this.id;
	  	  		seg_ajaxobject.peticionajax($('#form_lugar').attr('action'),datos,"seg_usuario.lugarRespuesta");
	  		}else{
	  			$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Seleccione un registro!</strong>Esta opción requiere la selección de un registro!!!.<br><br><ul><li>Selecciona un registro dando click sobre él, luego prueba nuevamente la opción</li></ul></div>');
	  		}
  	  		
  		});			
	</script>
@endsection