@extends('app')

@section('content')
<style>
	.name_user{
		background-color: #2A3F54;
	    color: #F2F5F7;
	    margin-top: 10px;
	    padding: 1%;
	    text-align: center;	    	
	}
	.perfilImage{
		width: 100%;
		cursor:pointer;	
	}
	.option_user{		
		border: 1px solid #2A3F54;
    	border-radius: 1px;
    	margin-top: 10px;
    	padding: 3% 3% 3% 5%;
	}
	.message_user{
		cursor:pointer;
		color: #2A3F54;
		padding: 1%;
	}
	.message_user:hover , .fa-icono:hover  {
		background-color: #2A3F54;
		color: #F2F5F7;
	}
	.fa-icono{
		margin-right:5px; color:#2A3F54;
	}
	.fa-icono:hover {
		color: #F2F5F7;
	}   

</style>
<div class="container-fluid">
	<div class="row">	
		<div class="col-md-3 col-md-offset-0" >
			<!-- Para cambiar de imagen de perfil -->
			<!-- <div class = "perfilImage" data-toggle="modal" data-target="#img_modal">  -->			
			<div > 
				{{ Html::image('images/user/'.Session::get('opaplus.usuario.avatar'),'Imagen no disponible',array( 'style'=>'width: 100%; border:2px solid #2A3F54;border-radius: 3px;' ))}}
			</div>
			<div class="name_user">
				<div>
					{{Session::get('opaplus.usuario.names')}}
				</div>
				<div>
					{{Session::get('opaplus.usuario.surnames')}}
				</div>
			</div>
			
			<div class="option_user">
			
				<div class = "message_user" >
					<span class="glyphicon glyphicon-paperclip" aria-hidden="true" style = "margin-right:5px; color:#666699;" ></span>Notificaciones
				</div>
				
				<!-- 
				<div class = "message_user" >
					<span class="glyphicon glyphicon-envelope" aria-hidden="true" style = "margin-right:5px; color:#666699;" ></span>Correo interno
				</div>
				
				<div class = "message_user" >
					<span class="glyphicon glyphicon-calendar" aria-hidden="true" style = "margin-right:5px; color:#666699;" ></span>Calendario
				</div>
				 -->
				 			
				@if(Session::get('opaplus.usuario.lugar.active'))
					<div id = "0" class = "message_user bnt_lugar">
						<span class="fa fa-trash-o fa-icono" aria-hidden="true" ></span>Papelera
					</div>
					<div id = "1" class = "message_user bnt_lugar" style="display: none;">
						<span class="fa fa-home fa-icono" aria-hidden="true" ></span>Escritorio
					</div>
				@else
					<div id = "0" class = "message_user bnt_lugar" style="display: none;>
						<span class="fa fa-trash-o fa-icono" aria-hidden="true" ></span>Papelera
					</div>					
					<div id = "1" class = "message_user bnt_lugar" ">
						<span class="fa fa-home fa-icono" aria-hidden="true" ></span>Escritorio
					</div>
				@endif			
				
			</div>	
			
			<div class="option_user">
				
				<div id="btn_new_psw" class = "message_user" data-toggle="modal" data-target="#psw_modal"  >					
					<span class="fa fa-lock fa-icono" aria-hidden="true" ></span>Cambiar contraseña
				</div>				
								
				<div class = "message_user" onclick="javascript:seg_user.edit(this);">
					<span class="fa fa-cog fa-icono" aria-hidden="true" ></span><font>Habilitar edición</font>
				</div>
				
			</div>			
			
		</div>
		<div class="col-md-9 col-md-offset-0">		
			<div class="form-group">
				{!! Form::label('name', 'Usuario:', array('class' => 'col-md-2 control-label')) !!}
				<div class="col-md-10">
					{!! Form::label('name', value(Session::get('opaplus.usuario.name')), array('class' => 'col-md-2 control-label')) !!}
				</div>
			</div>
			
			<div class="form-group">
				{!! Form::label('rol', 'Rol:', array('class' => 'col-md-2 control-label')) !!}
				<div class="col-md-10">
					{!! Form::label('rol', value(Session::get('opaplus.usuario.rol')), array('class' => 'col-md-6 control-label')) !!}
				</div>
			</div>
			
			<div class="form-group">
				{!! Form::label('ultimo_ingreso', 'Ultimo acceso:', array('class' => 'col-md-2 control-label')) !!}
				<div class="col-md-10">
					{!! Form::label('ultimo_ingreso', value(Session::get('opaplus.usuario.ultimo_ingreso')), array('class' => 'col-md-6 control-label')) !!}
				</div>
			</div>
			
			@if(Session::get('opaplus.usuario.rol_id') == 4)
				<div class="form-group">
					{!! Form::label('code', 'Código Asesor:', array('class' => 'col-md-2 control-label')) !!}
					<div class="col-md-10">
						{!! Form::label('code', value(Session::get('opaplus.usuario.code_adviser')), array('class' => 'col-md-6 control-label')) !!}
					</div>
				</div>	
			@endif
			
			@if(Session::get('opaplus.usuario.rol_id') == 5 || Session::get('opaplus.usuario.rol_id') == 6)
				<div class="form-group">
					{!! Form::label('zone', 'Zonas:', array('class' => 'col-md-2 control-label')) !!}
					<div class="col-md-10">
						{!! Form::label('zone', value(Session::get('opaplus.usuario.ciudades')), array('class' => 'col-md-6 control-label')) !!}
					</div>
				</div>	
			@endif
			
		</div>
		<div class="col-md-8 col-md-offset-0">			
			<div class="panel panel-default">
				<div class="panel-heading">Perfil de Usuario {{Session::get('opaplus.usuario.names')}}.</div>
				<div class="panel-body">
					<div class = "alerts">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Algo no va bien con el perfil!</strong> Hay problemas con con los datos diligenciados.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					
					@if(Session::has('message'))
						<div class="alert alert-info">
							<strong>¡Edición de perfil!</strong> Tu perfil se ha editado adecuadamente.<br><br>
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
	            <!-- La clase input-grp sirve para realizar el controles en la funcion seg_user.edit() de javascript-->
	            {!! Form::open(array('id'=>'form_user','url' => 'user/save')) !!}
					<div class="panel-body">	
					<div class="form-group input-grp">
						{!! Form::label('name', 'Usuario', array('class' => 'col-md-12 control-label')) !!}
						<div class="col-md-12">
							{!! Form::text('name', Session::get('opaplus.usuario.name'), array('class' => 'form-control','placeholder'=>'Ingresa tu usuario','disabled' => 'disabled')) !!}
						</div>
					</div>
					
					<div class="form-group input-grp">
						{!! Form::label('names', 'Nombres', array('class' => 'col-md-12 control-label')) !!}
						<div class="col-md-12">
							{!! Form::text('names', Session::get('opaplus.usuario.names'), array('class' => 'form-control','placeholder'=>'Ingresa tus nombres','disabled' => 'disabled')) !!}
						</div>
					</div>

					<div class="form-group input-grp">
						{!! Form::label('surnames', 'Apellidos', array('class' => 'col-md-12 control-label')) !!}
						<div class="col-md-12">
							{!! Form::text('surnames', value(Session::get('opaplus.usuario.surnames')) ,array('class' => 'form-control','placeholder'=>'Ingresa tus apellidos','disabled' => 'disabled')) !!}
						</div>
					</div>
					
					<div class="form-group input-grp">
						{!! Form::label('identificacion', 'Identiticación', array('class' => 'col-md-12 control-label')) !!}
						<div class="col-md-12">
							{!! Form::text('identificacion', value(Session::get('opaplus.usuario.identificacion')) ,array('class' => 'form-control','placeholder'=>'Ingresa tu identificación','disabled' => 'disabled')) !!}
						</div>
					</div>
					
					<div class="form-group input-grp">
						{!! Form::label('typeid', 'Tipo Identificación', array('class' => 'col-md-12 control-label')) !!}
						<div class="col-md-12">
							{!! Form::select('type_id',array('Cedula Ciudadania' => 'Cedula Ciudadania', 'Cedula Extranjeria' => 'Cedula Extranjeria'),value(Session::get('opaplus.usuario.type_id')), array('class' => 'form-control','placeholder'=>'Ingresa tu tipo de identificación','disabled' => 'disabled')) !!}
						</div>
					</div>
					
					<div class="form-group input-grp">
						{!! Form::label('birthdate', 'Fecha de nacimiento', array('class' => 'col-md-12 control-label')) !!}
						<div class="col-md-12">
							{!! Form::text('birthdate',value(Session::get('opaplus.usuario.birthdate')), array('class' => 'form-control','placeholder'=>'Ingresa tu fecha de nacimiento; aaaa-mm-dd','disabled' => 'disabled')) !!}
						</div>
					</div>
					
					<div class="form-group input-grp">
						{!! Form::label('birthplace', 'Lugar de Nacimiento', array('class' => 'col-md-12 control-label')) !!}
						<div class="col-md-12">
							{!! Form::text('birthplace',value(Session::get('opaplus.usuario.birthplace')), array('class' => 'form-control','placeholder'=>'Ingresa el lugar de tu nacimiento','disabled' => 'disabled')) !!}
						</div>
					</div>
					
					<div class="form-group input-grp">
						{!! Form::label('sex', 'Genero', array('class' => 'col-md-12 control-label')) !!}
						<div class="col-md-12">
							{!! Form::select('sex',array('Masculino' => 'Masculino', 'Femenino' => 'Femenino', 'Otro' => 'Otro'),value(Session::get('opaplus.usuario.sex')), array('class' => 'form-control','placeholder'=>'Ingresa tu genero','disabled' => 'disabled')) !!}
						</div>
					</div>
					
					<div class="form-group input-grp">
						{!! Form::label('adress', 'Dirección', array('class' => 'col-md-12 control-label')) !!}
						<div class="col-md-12">
							{!! Form::text('adress',value(Session::get('opaplus.usuario.adress')), array('class' => 'form-control','placeholder'=>'Ingresa tu dirección','disabled' => 'disabled')) !!}
						</div>
					</div>
					
					<div class="form-group input-grp">
						{!! Form::label('city', 'Ciudad', array('class' => 'col-md-12 control-label')) !!}
						<div class="col-md-12">
							{!! Form::text('city',value(Session::get('opaplus.usuario.city')), array('class' => 'form-control','placeholder'=>'Ingresa tu ciudad de recidencia','disabled' => 'disabled')) !!}
						</div>
					</div>
					
					<div class="form-group input-grp">
						{!! Form::label('neighborhood', 'Barrio', array('class' => 'col-md-12 control-label')) !!}
						<div class="col-md-12">
							{!! Form::text('neighborhood',value(Session::get('opaplus.usuario.neighborhood')), array('class' => 'form-control','placeholder'=>'Ingresa el barrio donde vives','disabled' => 'disabled')) !!}
						</div>
					</div>
					
					<div class="form-group input-grp">
						{!! Form::label('movil_number', 'Teléfono Movil', array('class' => 'col-md-12 control-label')) !!}
						<div class="col-md-12">
							{!! Form::text('movil_number',value(Session::get('opaplus.usuario.movil_number')), array('class' => 'form-control','placeholder'=>'Ingresa tu numero de movil','disabled' => 'disabled')) !!}
						</div>
					</div>

					<div class="form-group input-grp">
						{!! Form::label('fix_number', 'Teléfono Fijo', array('class' => 'col-md-12 control-label')) !!}
						<div class="col-md-12">
							{!! Form::text('fix_number',value(Session::get('opaplus.usuario.fix_number')), array('class' => 'form-control','placeholder'=>'Ingresa tu numero fijo','disabled' => 'disabled')) !!}
						</div>
					</div>					

					<div class="form-group input-grp">
						{!! Form::label('email', 'Correo electronico', array('class' => 'col-md-12 control-label')) !!}
						<div class="col-md-12">
							{!! Form::text('email',value(Session::get('opaplus.usuario.email')), array('class' => 'form-control','placeholder'=>'Ingresa tu correo electonico','disabled' => 'disabled')) !!}
						</div>
					</div>
					
					@if(Session::get('opaplus.usuario.rol_id') != 3 )
					
						<div class="form-group input-grp">
							{!! Form::label('reference', 'Referencia', array('class' => 'col-md-12 control-label')) !!}
							<div class="col-md-12">
								{!! Form::text('reference',value(Session::get('opaplus.usuario.reference')), array('class' => 'form-control','placeholder'=>'Ingresa tu referencia','disabled' => 'disabled')) !!}
							</div>
						</div>
						
						<div class="form-group input-grp">
							{!! Form::label('reference_adress', 'Dirección Referencia', array('class' => 'col-md-12 control-label')) !!}
							<div class="col-md-12">
								{!! Form::text('reference_adress',value(Session::get('opaplus.usuario.reference_adress')), array('class' => 'form-control','placeholder'=>'Ingresa l dirección de tu referencia','disabled' => 'disabled')) !!}
							</div>
						</div>
						
						<div class="form-group input-grp">
							{!! Form::label('reference_phone', 'Telefono Referencia', array('class' => 'col-md-12 control-label')) !!}
							<div class="col-md-12">
								{!! Form::text('reference_phone',value(Session::get('opaplus.usuario.reference_phone')), array('class' => 'form-control','placeholder'=>'Ingresa el telefono de tu referencia','disabled' => 'disabled')) !!}
							</div>
						</div>
					
					@endif
					
					<!-- 
					<div class="form-group input-grp">
						{!! Form::label('home', 'Recidencia', array('class' => 'col-md-12 control-label')) !!}
						<div class="col-md-12">
							{!! Form::select('home',array('Propia' => 'Propia', 'Familiar' => 'Familiar', 'Arrendada' => 'Arrendada'),value(Session::get('opaplus.usuario.home')), array('class' => 'form-control','placeholder'=>'Elije si tu vivienda es propia, familiar o arrendada','disabled' => 'disabled')) !!}
						</div>
					</div>				
															
					<div class="form-group input-grp">
						{!! Form::label('profession', 'Profesión', array('class' => 'col-md-12 control-label')) !!}
						<div class="col-md-12">
							{!! Form::text('profession',value(Session::get('opaplus.usuario.profession')), array('class' => 'form-control','placeholder'=>'Ingresa tu ocupasión','disabled' => 'disabled')) !!}
						</div>
					</div>
					
					<div class="form-group input-grp">
						{!! Form::label('paymentadress', 'Dirección de Pago', array('class' => 'col-md-12 control-label')) !!}
						<div class="col-md-12">
							{!! Form::text('paymentadress',value(Session::get('opaplus.usuario.paymentadress')), array('class' => 'form-control','placeholder'=>'Ingresa tu dirección de Pago','disabled' => 'disabled')) !!}
						</div>
					</div>
					
					
					 -->
					 
					<!--				
					<div class="form-group input-grp">
						{!! Form::label('perfil_description', 'Descripción', array('class' => 'col-md-12 control-label')) !!}
						<div class="col-md-12">
							{!! Form::textarea('perfil_description',value(Session::get('opaplus.usuario.perfil_description')), array('class' => 'form-control','placeholder'=>'Ingresa tu descripcion','disabled' => 'disabled')) !!}
						</div>
					</div>				
					-->
					{!! Form::hidden('perfil_description',value(Session::get('opaplus.usuario.perfil_description'))) !!}
					
					</div>
					
					<div class="form-group">
						<div class="col-md-12 col-md-offset-0 ">
							{!! Form::submit('Enviar', array('class' => 'btn btn-primary','style' => 'display:none;')) !!}																
						</div>
					</div>					
											
				{!! Form::close() !!}
	           
				</div>				
			</div>
		</div>		
	</div>
</div>  
@endsection

@section('modal')
	
	<div class="modal fade" id="img_modal" role="dialog" data-backdrop="false">
	    <div class="modal-dialog">	    
	      <!-- Modal content-->
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Cambiar imagen de perfil</h4>
	        </div>
	        <div class="modal-body">
	        	 <div class="row">
	        	 	 {!! Form::open(array('id'=>'form_img','url' => 'user/saveimg','method'=>'POST', 'files'=>true)) !!}		
		        	 	<div class="col-md-6">
		        	 		<p> <label class="col-md-offset-2" for="name">Elije una imagen</label>	</p>
		        	 			<div class = "col-md-offset-1 col-md-10">
		        	 				<img id="nueva_img" src="images/user/noimagen.png" class="" alt="Imagen no disponible" style="width: 100%; border:2px solid #78a5b1;border-radius: 3px;">					        	 			
		        	 			</div>
		        	 		<p>{!! Form::file('image',array('id'=>'img_user')) !!}</p>
		        	 	</div>
		        	 	<div class="col-md-6">
		        	 		<p> <label class="col-md-offset-2" for="name">Tu actual imagen</label></p>
			        	 		<div class = "col-md-offset-1 col-md-10">
				        	 		{{ Html::image('images/user/'.Session::get('opaplus.usuario.avatar'),'Imagen no disponible',array( 'style'=>'width: 100%; border:2px solid #78a5b1;border-radius: 3px;' ))}}				        	 					        	 			
		        	 			</div>		        	 			
		        	 	</div>		        	 	 
	        	 	{!! Form::close() !!}
	        	 </div>
	        </div>
	        <div class="modal-footer">
	          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	          <input class="btn btn-primary" type="submit" value="Enviar" id="submit" form="form_img">         
	        </div>
	      </div>
	      
	    </div>
	</div>
	<div class="modal fade" id="psw_modal" role="dialog" data-backdrop="false">
	<div class="modal-dialog">	    
	      <!-- Modal content-->
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Cambiar contraseña</h4>
	        </div>
	        <div class="modal-body">
		        <div class="row">
		        {!! Form::open(array('id'=>'form_psw','url' => 'user/savepsw','method'=>'POST')) !!}
	        		<div class="form-group">
						{!! Form::label('contrasenia_uno', 'Contraseña Actual', array('class' => 'col-md-12 control-label')) !!}
						<div class="col-md-12">
							{!! Form::password('contrasenia_uno', array('class' => 'form-control','placeholder'=>'Ingresa tu actual contraseña')) !!}
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('contrasenia_dos', 'Contraseña Nueva', array('class' => 'col-md-12 control-label')) !!}
						<div class="col-md-12">
							{!! Form::password('contrasenia_dos', array('class' => 'form-control','placeholder'=>'Ingresa tu nueva contraseña')) !!}
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('contrasenia_tres', 'Contraseña Nueva', array('class' => 'col-md-12 control-label')) !!}
						<div class="col-md-12">
							{!! Form::password('contrasenia_tres', array('class' => 'form-control','placeholder'=>'Ingresa tu nueva contraseña')) !!}
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-6">
							{!! Form::submit('Enviar', array('class' => 'btn btn-primary')) !!}																
						</div>
					</div>
		        {!! Form::close() !!}
		        
		        <!-- Form en blanco para capturar la url -->
		        {!! Form::open(array('id'=>'form_lugar','url' => 'user/lugar')) !!}
		        {!! Form::close() !!}
		        </div>
	        </div>
        </div>
	</div>
	</div>
@endsection

@section('script')		
	<script type="text/javascript">  	
  		javascript:seg_user.iniciarDatepiker('birthdate');//todavia no esta definido
  		$("#img_user").change(function(){
  			seg_user.changeImg(this);
  		});
  		/*Petición sin usar ajax*/
  		$('.bnt_lugar').click(function(e){  	  		
  	  		var datos = new Array();
  	  		datos['lugar'] = this.id;
  	  		seg_ajaxobject.peticionpost($('#form_lugar').attr('action'),datos,"seg_user.lugarRespuesta");
  		});  		
  		/*cambia el estado de la variable de session de lugar de consulta, llamado a ajax*/  		
  		/*
  		$('.bnt_lugar').click(function(e){
  	  		e.preventDefault();//evita que la pagina se refresque
  	  		var datos = new Array();
  	  		datos['lugar'] = this.id;
  	  		seg_ajaxobject.peticionajax($('#form_lugar').attr('action'),datos,"seg_user.lugarRespuesta");
  		});
  		*/		
  		
	</script>
@endsection

