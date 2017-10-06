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
		/*
		.site_title2{
			color: #5A738E !important;
		}
		.site_title2 i{
			border: 1px solid #5A738E;
		}
		*/
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
		
		#sell_all{
			cursor:pointer;
		}
		#sell_reprint{
			cursor:pointer;
			margin-bottom: 10px;
		}
		.tab_cnt_bnt1{
			/*margin-top: 10px;*/
		}
		.tab_cnt_bnt2{
			/*margin-top: 10px;*/
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
		            				<a href="javascript:clu_suscripcion.opt_select('{{json_decode(Session::get('opaplus.usuario.permisos')[Session::get('modulo.id_app')]['modulos'][Session::get('modulo.categoria')][Session::get('modulo.id_mod')]['preferencias'])->controlador}}','{{($opc['accion'])}}/{{Session::get('modulo.id_app')}}/{{Session::get('modulo.categoria')}}/{{Session::get('modulo.id_mod')}}')" class="site_title site_title2" style = "text-decoration: none; ">
			            				<i class="{{$opc['icono']}}"></i>
			            			</a>
		            			</div>
	            				           			
	            			@elseif($opc['accion'] == 'mirar')
	            				<div class="col-md-1" data-toggle="tooltip" title = "{{$opc[$key]}}">
		            				<a href="javascript:clu_suscripcion.opt_ver()" class="site_title site_title2" style = "text-decoration: none; ">
			            				<i class="{{$opc['icono']}}"></i>
			            				<!--  <span >{{$opc[$key]}}</span> -->	            				
			            			</a>
		            			</div>
		            		@elseif($opc['accion'] == 'abonar')
	            				<div class="col-md-1 bnt_abonar" data-toggle="tooltip" title = "{{$opc[$key]}}">
		            				<a href="#" class="site_title site_title2" style = "text-decoration: none; ">
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
	            			@elseif($opc['accion'] == 'renovar')
	            				<div class="col-md-1 bnt_renovar" data-toggle="tooltip" title = "{{$opc[$key]}}">
		            				<a href="#" class="site_title site_title2" style = "text-decoration: none; ">
			            				<i class="{{$opc['icono']}}"></i>
			            			</a>
		            			</div>	
	            			@elseif($opc['accion'] == 'carnet')
	            				<div class="col-md-1 bnt_carnet" data-toggle="tooltip" title = "{{$opc[$key]}}">
		            				<a href="#" class="site_title site_title2" style = "text-decoration: none; ">
			            				<i class="{{$opc['icono']}}"></i>
			            			</a>
		            			</div>

		            		@elseif($opc['accion'] == 'cargasus')
	            				<div class="col-md-1" data-toggle="tooltip" title = "{{$opc[$key]}}">
		            				<a href="javascript:clu_suscripcion.opt_cargarsus()" class="site_title site_title2" style = "text-decoration: none; ">
			            				<i class="{{$opc['icono']}}"></i>
			            			</a>
		            			</div>
		            		
		            		@elseif($opc['accion'] == 'renovarsuscripcion')
	            				<div class="col-md-1 bnt_renovarsuscripcion" data-toggle="tooltip" title = "{{$opc[$key]}}">
		            				<a href="#" class="site_title site_title2" style = "text-decoration: none; ">
			            				<i class="{{$opc['icono']}}"></i>
			            			</a>
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
					<strong>¡Modulo Suscriptores!</strong> La operación se ha realizado adecuadamente.<br><br>
					<ul>								
						<li>{{ Session::get('message') }}</li>
					</ul>
				</div>
                
            @endif

            @if(Session::has('messageup'))
				<div class="alert alert-info alert-dismissible fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>¡Modulo Suscriptores!</strong>  El proceso se ejecuto correctamente.<br>			
					<ul>								
						@foreach (Session::get('messageup') as $mensaje)
							<li>{{ $mensaje }}</li>
						@endforeach															
					</ul>
				</div>       
                
            @endif

             @if(Session::has('errorup'))
				<div class="alert alert-danger  alert-dismissible fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>¡Algo no va bien!</strong> Hay problemas con los datos diligenciados.<br><br>
					<ul>								
						@foreach (Session::get('errorup') as $mensaje)
							<li>{{ $mensaje }}</li>
						@endforeach															
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
         <tfoot>
            <tr>
                @if(Session::has('modulo.fillable'))
            		@foreach (Session::get('modulo.fillable') as $col)
            			<th>{{$col}}</th>
            		@endforeach
            	@endif    
            </tr>
        </tfoot>            
    </table> 
    <!-- Form en blanco para capturar la url editar y eliminar-->
    {!! Form::open(array('id'=>'form_abonar','url' => 'suscripcion/abonar')) !!}
    {!! Form::close() !!}
    {!! Form::open(array('id'=>'form_abonar_save','url' => 'suscripcion/abonarsave')) !!}
    {!! Form::close() !!}
    {!! Form::open(array('id'=>'form_renovar','url' => 'suscripcion/renovar')) !!}
    {!! Form::close() !!} 
    {!! Form::open(array('id'=>'from_renovarsuscripcion','url' => 'suscripcion/renovarsuscripcion')) !!}    
    {!! Form::close() !!} 
    {!! Form::open(array('id'=>'form_carnet','url' => 'suscripcion/carnet')) !!}
    {!! Form::close() !!}     
    {!! Form::open(array('id'=>'form_carnetreprint','url' => 'suscripcion/carnetreprint')) !!}
    {!! Form::close() !!} 
      
	</div>		
@endsection

@section('modal')
	
	<div class="modal fade" id="suscripcion_abono_modal" role="dialog" data-backdrop="false">
	    <div class="modal-dialog">	    
	      <!-- Modal content-->
	      <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Abono Suscripción</h4>
				</div>
				<div class = "alerts-module"></div>				
				<div class="modal-body">
					<div class="row ">
						<div class="col-md-12 col-md-offset-0 row_izq"></div>						
					</div>
		        </div>
		        <div class="modal-footer">
		          <button type="button" class="btn btn-default btn-enviar-abono" >Enviar Abono</button>
		          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>		                  
		        </div>     
	      </div>
      </div>
	</div>
	
	<div class="modal fade" id="suscripcion_ver_modal" role="dialog" data-backdrop="false">
	    <div class="modal-dialog">	    
	    <!-- Modal content-->      
	      <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Resumen Suscripción</h4>
				</div>
				<div class = "alerts-module"></div>				
							
				<div class="modal-body">
					<ul class="nav nav-tabs">
						<li role="presentation" class="active"><a href="#tab1" data-toggle="tab">Suscripción</a></li>
						<li role="presentation"><a href="#tab2" data-toggle="tab">Suscriptor</a></li>
						<li role="presentation"><a href="#tab3" data-toggle="tab">Beneficiarios</a></li>	
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab1">
							<div class="row ">
								<div class="col-md-5 col-md-offset-0 row_izq"></div>
								<div class="col-md-7 col-md-offset-0 row_der"></div>
								<div class="col-md-12 col-md-offset-0 row_cen0"></div>
								<div class="col-md-12 col-md-offset-0 row_cen"></div>						
							</div>
						</div>
						<div class="tab-pane fade " id="tab2">
							<div class="row ">
								<div class="col-md-12 col-md-offset-0 tab2"></div>						
							</div>
						</div>
						<div class="tab-pane fade " id="tab3">
							<div class="row ">
								<div class="col-md-12 col-md-offset-0 tab3">								
									<ul class="nav nav-tabs" style="margin-top:8px">
										<li role="beneficiary" class="active"><a href="#tab_bne1" data-toggle="tab">Por Suscripción</a></li>
										<li role="beneficiary"><a href="#tab_bne2" data-toggle="tab">Adicionales</a></li>											
									</ul>
									<div class="tab-content">
										<div class="tab-pane fade in active" id="tab_bne1">
											<div class="col-md-12 col-md-offset-0 tabbne1"></div>
										</div>
										<div class="tab-pane fade" id="tab_bne2">
											<div class="col-md-12 col-md-offset-0 tabbne2"></div>
										</div>
									</div>
								
								</div>						
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
	
	<div class="modal fade" id="suscripcion_renovar_modal" role="dialog" data-backdrop="false">
	    <div class="modal-dialog">	    
	      <!-- Modal content-->
	      <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Renovación Suscripción</h4>
				</div>
				<div class = "alerts-module"></div>				
				<div class="modal-body">
					<div class="row ">
						<div class="col-md-12 col-md-offset-0 row_izq"></div>						
					</div>
		        </div>
		        <div class="modal-footer">
		          <button type="button" class="btn btn-default btn-enviar-abono" >Renovar Suscripción</button>
		          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>		                  
		        </div>     
	      </div>
      </div>
	</div>

	<!--
	<div class="modal fade" id="renovar_suscripcion_modal" role="dialog" data-backdrop="false">
	    <div class="modal-dialog modal-lg">	
	    	<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Renovación de Suscripción</h4>
				</div>
				<div class = "alerts-module"></div>
				{!! Form::open(array('url' => 'suscripcion/renovarsuscripcionform', 'id'=>'form_renovar_suscripcion','onsubmit'=>'javascript:return clu_suscripcion.validateRenovarSuscripcion()')) !!}	
				<div class="modal-body">
					<ul class="nav nav-tabs">
						<li role="presentation" class="active"><a href="#renovar_tab1" data-toggle="tab">SUSCRIPCIÓN</a></li>
						<li role="presentation"><a href="#renovar_tab2" data-toggle="tab">SUSCRIPTOR</a></li>
						<li role="presentation"><a href="#renovar_tab3" data-toggle="tab">BENEFICIARIOS</a></li>	
						<li role="presentation"><a href="#renovar_tab4" data-toggle="tab">ABONOS</a></li>	
					</ul>
					<div class="tab-content">
						<div class="row ">
							<div class="col-md-12 col-md-offset-0">
								<div class="tab-pane fade in active" id="renovar_tab1"></div>
								<div class="tab-pane fade in active" id="renovar_tab2"></div>
								<div class="tab-pane fade in active" id="renovar_tab3"></div>
								<div class="tab-pane fade in active" id="renovar_tab4"></div>
							</div>
						</div>
					</div>
				</div>
				{!! Form::close() !!}
				<div class="modal-footer">
					<button type="submit" form = "form_renovar_suscripcion" class="btn btn-default" id="form_entidad_button" >Renovar Suscripción</button>	         
		          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>		                  
		        </div>
			</div>   
	    </div>
    </div>
    -->
	
	<div class="modal fade" id="suscripcion_carnet_modal" role="dialog" data-backdrop="false">
	    <div class="modal-dialog">	    
	      <!-- Modal content-->
	      <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Impresión de Carnets</h4>
				</div>
				<div class = "alerts-module"></div>
				{!! Form::open(array('url' => 'suscripcion/carnetprint')) !!}		
				<div class="modal-body">
					<ul class="nav nav-tabs">
						<li role="bnes_cnt" class="active"><a href="#tab_bnt_cnt1" data-toggle="tab">Por suscripción</a></li>
						<li role="bnes_cnt"><a href="#tab_bnt_cnt2" data-toggle="tab">Adicionales</a></li>							
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_bnt_cnt1">
							<div class="row ">
								<div class="col-md-12 col-md-offset-0 tab_cnt_bnt1"></div>						
							</div>
						</div>
						<div class="tab-pane fade " id="tab_bnt_cnt2">
							<div class="row ">
								<div class="col-md-12 col-md-offset-0 tab_cnt_bnt2"></div>						
							</div>
						</div>
					</div>
										
		        </div>
		        <div class="modal-footer">
		        	
		        	<div id = "sell_all">Seleccionar Todo</div>
		          	<div class="checkbox"><label><input type="checkbox" name = "reimpresion" value="reimpresion">Reimpresión</label></div>
		          	<div id = "sell_reprint">Ver Reimpresiones</div>          
		          	{!! Form::submit('Imprimir Carnets', array('class' => 'btn btn-default')) !!}
		          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>		                  
		        </div>		        
		        {!! Form::close() !!}     
	      </div>
      </div>
	</div>
	
	<div class="modal fade" id="suscripcion_seereprint_modal" role="dialog" data-backdrop="false">
	    <div class="modal-dialog">	    
	      <!-- Modal content-->
	     	<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Reimpresiones</h4>
				</div>
				<div class = "alerts-module"></div>	
				{!! Form::open(array('url' => 'suscripcion/carnetprintedit')) !!}			
				<div class="modal-body">
					<div class="row ">
						<div class="col-md-12 col-md-offset-0 row_izq"></div>						
					</div>
		        </div>
		        <div class="modal-footer">
		          {!! Form::submit('Actualizar', array('class' => 'btn btn-default')) !!}
		          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>		                  
		        </div>
		         {!! Form::close() !!}          
	      	</div>
      	</div>
	</div>

	<div class="modal fade" id="suscripcion_cargasus_modal" role="dialog" data-backdrop="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Cargar Suscripción</h4>
				</div>
				<div class = "alerts-module"></div>	

				{!! Form::open(array('url' => 'suscripcion/cargasus','files'=>true)) !!}			
				<div class="modal-body">
					<div class="row ">
						<div class="col-md-12 col-md-offset-0 row_izq">
							<div class="form-group">
								{!! Form::label('file', 'Archivo a cargar', array('class' => 'col-md-12 control-label')) !!}							
								<div class="col-md-12">
									{!! Form::file('carga_suscripcion') !!}
								</div>
							</div>
						</div>						
					</div>
		        </div>
		        <div class="modal-footer">
			        {!! Form::submit('Cargar', array('class' => 'btn btn-default')) !!}
			        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		        </div>
		        {!! Form::close() !!}        

			</div>
		</div>
	</div>
	

@endsection

@section('script')	
	<script type="text/javascript" src="{{ asset('/js/lib/datetimepiker.js') }}"></script>			
	<script type="text/javascript">  
	@if(Session::get('opaplus.usuario.rol_id') == 4)
		javascript:clu_suscripcion.table = $('#example').DataTable( {
		    "responsive": true,
		    "processing": true,
		    "bLengthChange": false,
		    "serverSide": true,			          
		    "ajax": "{{url('suscripcion/listarajax')}}",
		    "iDisplayLength": 25,
		    "columns": [		    			   
				{ "data": "code"},
				{ "data": "names_fr" },		        
				{ "data": "identificacion_fr" },  	    
		        { "data": "date_expiration"}
		                   
		    ],	
		    "columnDefs": [
    	         { responsivePriority: 1, targets: 0 },
                 { responsivePriority: 2, targets: -2 }
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
		                "mColumns": [0,1,2,3],
		                "sTitle": "{{Session::get('modulo.modulo')}}"
		            },
		            {
		                "sExtends": "csv",
		                "mColumns": [0,1,2,3],
		                "sTitle": "{{Session::get('modulo.modulo')}}"
		            },
		            {
		                "sExtends": "xls",
		                "mColumns": [0,1,2,3],
		                "sTitle": "{{Session::get('modulo.modulo')}}",
		                "sFileName": "*.xls"
		            },
		            {
		                "sExtends": "pdf",
		                "mColumns": [0,1,2,3],
		                "sTitle": "{{Session::get('modulo.modulo')}}"                        
		            }                    
		        ]
		    }*/
		    /*,		    
		    "fnRowCallback": function( nRow, aData ) {
		        //pintar el fondo de la fila
		        //$('td', nRow).css('background-color', aData.alert);
		        //$(nRow).children()[7].style.backgroundColor = aData.next_alert;
		        //$(nRow).children()[3].style.backgroundColor = aData.alert;		        
		        
            }*/
		    
		});
	@else
		javascript:clu_suscripcion.table = $('#example').DataTable( {			
		    "responsive": true,
		    "processing": true,
		    "bLengthChange": false,
		    "serverSide": true,
		    "ajax": "{{url('suscripcion/listarajax')}}",
		    "iDisplayLength": 25,	       
		    "columns": [				   
				{ "data": "code"},  	    
				{ "data": "names_fr" },
				{ "data": "city" },
		        { "data": "names_ad" },
		        { "data": "mora","orderable": false},
		        { "data": "pagos","orderable": false},
		        { "data": "state"},
		        { "data": "next_pay" },
		        { "data": "date_expiration"}
		                   
		    ],
		    "columnDefs": [
    	         { responsivePriority: 1, targets: 0 },
                 { responsivePriority: 2, targets: -2 }
                
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
		                "mColumns": [0,1,2,3,4,5,6],
		                "sTitle": "{{Session::get('modulo.modulo')}}"
		            },
		            {
		                "sExtends": "csv",
		                "mColumns": [0,1,2,3,4,5,6],
		                "sTitle": "{{Session::get('modulo.modulo')}}"
		            },
		            {
		                "sExtends": "xls",
		                "mColumns": [0,1,2,3,4,5,6],
		                "sTitle": "{{Session::get('modulo.modulo')}}",
		                "sFileName": "*.xls"
		            },
		            {
		                "sExtends": "pdf",
		                "mColumns": [0,1,2,3,4,5,6],
		                "sTitle": "{{Session::get('modulo.modulo')}}"                        
		            }                    
		        ]
		    },
		    */
		    "fnRowCallback": function( nRow, aData ) {
		        //pintar el fondo de la fila
		        //$('td', nRow).css('background-color', aData.alert);
		        $(nRow).children()[5].style.backgroundColor = aData.alert;
		        //	$(nRow).children()[5].style.backgroundColor = aData.next_alert;
		        		        
		        
            },
          
            /*
            initComplete: function () {
	            this.api().columns().every( function () {
	                var column = this;
	                var select = $('<select><option value=""></option></select>')
	                    .appendTo( $(column.footer()).empty() )
	                    .on( 'change', function () {
	                        var val = $.fn.dataTable.util.escapeRegex(
	                            $(this).val()
	                        );
	 
	                        column
	                            .search( val ? '^'+val+'$' : '', true, false )
	                            .draw();
	                    } );
	 
	                column.data().unique().sort().each( function ( d, j ) {
	                    select.append( '<option value="'+d+'">'+d+'</option>' )
	                } );
	            } );
	        },
	        */

		    
		});
	@endif	
		
		@if(Session::has('filtro'))
			clu_suscripcion.table.search( "{{Session::get('filtro')}}" ).draw();
		@endif	
		javascript:$('#example tbody').on( 'click', 'tr', function () {
		    if ($(this).hasClass('selected')) {
		        $(this).removeClass('selected');
		    }
		    else {
		    	//clu_suscripcion.table.$('tr.selected').removeClass('selected');
		        $(this).addClass('selected');
		    }
		});
		 //llamado del metodo abonar
	    $('.bnt_abonar').click(function(e){
  	  		//e.preventDefault();//evita que la pagina se refresque
	  	  	if(clu_suscripcion.table.rows('.selected').data().length){
	  	  		if(clu_suscripcion.table.rows('.selected').data()[0].state_id == 2 || clu_suscripcion.table.rows('.selected').data()[0].state_id == 3 || clu_suscripcion.table.rows('.selected').data()[0].state_id == 4 || clu_suscripcion.table.rows('.selected').data()[0].state_id == 7 || clu_suscripcion.table.rows('.selected').data()[0].state_id == 8){
		  	  		var datos = new Array();
		  	  		datos['id'] = clu_suscripcion.table.rows('.selected').data()[0].id;
		  	  		datos['friend_id'] = clu_suscripcion.table.rows('.selected').data()[0].friend_id;	  	  		
		  	  		seg_ajaxobject.peticionajax($('#form_abonar').attr('action'),datos,"clu_suscripcion.abonarRespuesta");
		  	  	}else{
		  	  	$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Abono Invalido!</strong>No se puede abonar a esta Suscripción!!!.<br><br><ul><li>Solo se puede abonar a una suscripción cuando su estado es "Pago en mora", "Pago pendiente" o "Suscrición vencida"</li></ul></div>');
		  	  	}		
		  	  	
	  		}else{
	  			$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Seleccione un registro!</strong> Esta opción requiere la selección de un registro!!!.<br><br><ul><li>Selecciona un registro dando click sobre él, luego prueba nuevamente la opción</li></ul></div>');
	  		}  	  		
  		});

	    $('.btn-enviar-abono').click(function(e){
			//llamado asincrono paraagregar el abono
			//e.preventDefault();//evita que la pagina se refresque
	  	  	if($('#payment').val()){
	  	  		if(parseInt($('#payment').val()) > 0){
		  	  		if(parseInt($('#payment').val()) <= parseInt(clu_suscripcion.table.rows('.selected').data()[0].mora)){
		  	  			
			  	  		var datos = new Array();
			  	  		datos['suscription_id'] = clu_suscripcion.table.rows('.selected').data()[0].id;
			  	  		datos['price'] = clu_suscripcion.table.rows('.selected').data()[0].price;
			  	  		datos['mora'] = clu_suscripcion.table.rows('.selected').data()[0].mora;
			  	  		datos['code'] = clu_suscripcion.table.rows('.selected').data()[0].code;
			  	  		datos['payment'] = $('#payment').val();
			  	  		datos['date_payment'] = $('#date_payment').val();
			  	  		datos['pay_interval'] = $('#pay_interval').val();
			  	  		datos['n_receipt'] = $('#n_receipt').val();
			  	  		//para que funcione adecuadamente esto no debe ser un ajax sino un llamado a metodo de controlador
			  	  		//seg_ajaxobject.peticionajax($('#form_abonar_save').attr('action'),datos,"clu_suscripcion.abonarRespuestaSave");
			  	  		if(datos['date_payment']==""){
			  	  			datos['date_payment']=0;
			  	  		}
				  	  	if(datos['pay_interval']==""){
			  	  			datos['pay_interval']=0;
			  	  		}

			  	  		$('#suscripcion_abono_modal').modal('toggle');
			  	  		//alert("abonarsave/"+datos['payment']+"/"+datos['date_payment']+"/"+datos['suscription_id']+"/"+datos['mora']);
			  	  		window.location = "abonarsave/"+datos['payment']+"/"+datos['date_payment']+"/"+datos['suscription_id']+"_"+datos['n_receipt']+"/"+datos['mora']+"/"+datos['pay_interval'];
			  	  		
		  	  		
		  	  		}else{
		  	  			$('.alerts-module').html('<div class="alert alert-danger fade in"><strong>¡El campo Valor no esta debidamente Diligenciado!</strong> El ingreso de un abono requiere ser menor a la Mora de la Suscripción!!!.<br><br><ul><li>Diligencie un valor numerico en el campo para el valor, luego prueba nuevamente dando click en el botón Enviar Abono; el abomo: '+$('#payment').val()+' es mayor a la mora: '+clu_suscripcion.table.rows('.selected').data()[0].mora+'. El valor maximo del abono es el valor de la mora. </li></ul></div>');
		  	  		}
	  	  		}
	  	  		else{
	  	  			$('.alerts-module').html('<div class="alert alert-danger fade in"><strong>¡El Valor no es valido!</strong> El ingreso de un abono requiere de un Valor!!!.<br><br><ul><li>Diligencie un valor numerico en el campo para el valor, luego prueba nuevamente dando clik en el botón Enviar Abono</li></ul></div>');
	  	  		}
	  		}else{  			
	  			$('.alerts-module').html('<div class="alert alert-danger fade in"><strong>¡El campo Valor esta vacio!</strong> El ingreso de un abono requiere de un Valor!!!.<br><br><ul><li>Diligencie un valor numerico en el campo para el valor, luego prueba nuevamente dando clik en el botón Enviar Abono</li></ul></div>');
	  		}
		});

	    $('.bnt_renovar').click(function(e){
  	  		//e.preventDefault();//evita que la pagina se refresque
	  	  	if(clu_suscripcion.table.rows('.selected').data().length){
				//preguntamos por el estado
		  	  	if(clu_suscripcion.table.rows('.selected').data()[0].state_id == 1 || clu_suscripcion.table.rows('.selected').data()[0].state_id == 4){
			  	  	//preguntamos por la mora
		  	  		if(clu_suscripcion.table.rows('.selected').data()[0].mora == 0){
			  	  		var datos = new Array();
			  	  		datos['id'] = clu_suscripcion.table.rows('.selected').data()[0].id;
			  	  		datos['friend_id'] = clu_suscripcion.table.rows('.selected').data()[0].friend_id;
			  	  		window.location = "renovar/"+datos['id'];	  	  		
		  	  			//seg_ajaxobject.peticionajax($('#form_renovar').attr('action'),datos,"clu_suscripcion.renovarRespuesta");
			  	  	}else{
			  	  	$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Renovación Invalida!</strong> Esta suscripción no se puede renovar!!!.<br><br><ul><li>Solo se renueva una suscripción cuando no tiene cuentas por pagar.</li></ul></div>');
			  	  	}
			  	  	
		  	  	
		  	  	}else{
		  	  		$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Renovación Invalida!</strong> Esta suscripción no se puede renovar!!!.<br><br><ul><li>Solo se renueva una suscripción cuando su estado es "Pago efectuado" o "Suscripción vencida"</li></ul></div>');
		  	  	}
		  	  	
	  		}else{
	  			$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Seleccione un registro!</strong> Esta opción requiere la selección de un registro!!!.<br><br><ul><li>Selecciona un registro dando click sobre él, luego prueba nuevamente la opción</li></ul></div>');
	  		}  	  		
  		});

	    $('.bnt_carnet').click(function(e){
  	  		//e.preventDefault();//evita que la pagina se refresque
	  	  	if(clu_suscripcion.table.rows('.selected').data().length){
	  	  			  	  		
		  	  	if(clu_suscripcion.table.rows('.selected').data().length > 1){
		  	  		var datos = '';		  	  	
			  	  	for(var i=0;i<clu_suscripcion.table.rows('.selected').data().length;i++){
						if(datos == ''){datos = clu_suscripcion.table.rows('.selected').data()[i].id;}else{
							datos= datos+'_'+clu_suscripcion.table.rows('.selected').data()[i].id;
						}
			  	  	}
			  	  	window.location = "carnets/"+datos;
			  	  	
		  	  	}else{
		  	  		var datos = new Array();
	  	  			datos['id'] = clu_suscripcion.table.rows('.selected').data()[0].id;
	  	  			seg_ajaxobject.peticionajax($('#form_carnet').attr('action'),datos,"clu_suscripcion.carnetRespuesta");
			  	}				
		  	  	
		  	  	
	  		}else{
	  			$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Seleccione un registro!</strong> Esta opción requiere la selección de un registro!!!.<br><br><ul><li>Selecciona un registro dando click sobre él, luego prueba nuevamente la opción</li></ul></div>');
	  		}  	  		
  		});
  		
	    $('.btn-enviar-carnet').click(function(e){
  	  		//e.preventDefault();//evita que la pagina se refresque
	  	  	if(clu_suscripcion.table.rows('.selected').data().length){
				
	  	  		var datos = new Array();
	  	  		datos['id'] = clu_suscripcion.table.rows('.selected').data()[0].id;
	  	  		window.location = "carnetprint/"+datos['id'];  	  		
  	  			//seg_ajaxobject.peticionajax($('#form_carnet').attr('action'),datos,"clu_suscripcion.renovarRespuesta");
		  	  	
	  		}else{
	  			$('.alerts').html('<div class="alert alert-info fade in"><strong>¡Seleccione un registro!</strong> Esta opción requiere la selección de un registro!!!.<br><br><ul><li>Selecciona un registro dando click sobre él, luego prueba nuevamente la opción</li></ul></div>');
	  		}  	  		
  		});

  		//seleccionar todos los carets del modal para impr
	    $('#sell_all').click(function(e){
		    if($('#sell_all').html() == 'Seleccionar Todo'){
			    $( "input[type=checkbox]" ).prop( "checked", true );
			    $('#sell_all').html('Deseleccionar Todo');
		    }else{
		    	$( "input[type=checkbox]" ).prop( "checked", false );
		    	$('#sell_all').html('Seleccionar Todo');
			}
		    
	    	
		});
		
	    $('#sell_reprint').click(function(e){
	    	var datos = new Array();
  	  		datos['id'] = clu_suscripcion.table.rows('.selected').data()[0].id;
  	  		seg_ajaxobject.peticionajax($('#form_carnetreprint').attr('action'),datos,"clu_suscripcion.carnetReprintRespuesta");
		});

		//suscripcion con modal para editar
		
  		$('.bnt_renovarsuscripcion').click(function(e){
  			if(clu_suscripcion.table.rows('.selected').data().length){
  				var datos = new Array();
	  	  		datos['id'] = clu_suscripcion.table.rows('.selected').data()[0].id;	
	  	  		datos['mora'] = clu_suscripcion.table.rows('.selected').data()[0].mora;		  	  		  		
  	  			//seg_ajaxobject.peticionajax($('#from_renovarsuscripcion').attr('action'),datos,"clu_suscripcion.renovarsuscripcionRespuesta");
  	  			window.location = "renovarsuscripcion/"+datos['id']+"/"+datos['mora'];  	  		
  	  			clu_suscripcion.table.$('tr.selected').removeClass('selected');
  			}else{
	  			$('.alerts').html('<div class="alert alert-info fade in alert-dismissable"><button type="button" class="close close_alert_product" data-dismiss="alert">&times;</button><strong>¡Seleccione un registro!</strong> Esta opción requiere la selección de un registro!!!.<br><br><ul><li>Selecciona un registro dando click sobre él, luego prueba nuevamente la opción</li></ul></div>');
	  		}  	 
  		});
  		
  		
	</script>
@endsection