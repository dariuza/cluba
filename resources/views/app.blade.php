<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
		
	<title>{!! Session::get('app') !!}</title>	
	<link rel="shortcut icon" href="{{ url('images/icons/icon.png') }}">
	 
	{{ Html::style('css/lib/alela/css/bootstrap.min.css')}}
	{{ Html::style('css/lib/alela/css/font-awesome.min.css')}}
	{{ Html::style('css/lib/alela/css/custom.min.css')}}
	
	{{ Html::style('css/lib/jquery-ui.css')}}	
	{{ Html::style('css/lib/datatables.min.css')}}
	{{ Html::style('css/lib/datatables.tabletools.css')}}	
	{{ Html::style('css/lib/bootstrap-datepicker.min.css')}}
	
			
</head>
<body class="nav-md">
	<div class="container body">
		<div class="main_container">
		@if (!Auth::guest())
			<div class="col-md-3 left_col">
				<div class="left_col scroll-view">
					<div class="navbar nav_title" style="border: 0;">
						<a href="{{ url('/') }}" class="site_title"><i class="fa fa-users"></i> <span>{!! Session::get('app') !!}</span></a>
					</div>
					
					<div class="clearfix"></div>
					
		            <!-- menu profile quick info -->
					<div class="profile">
						<div class="profile_pic">
							{{ Html::image('images/user/'.Session::get('opaplus.usuario.avatar'),'Imagen no disponible',array( 'class'=>'img-circle profile_img' ))}}
						</div>
						<div class="profile_info">
							@if(Session::get('opaplus.usuario.sex') == "Masculino")
								<span>Bienvenido</span>
							@else
								<span>Bienvenida</span>
							@endif							
							<h2>{{Session::get('opaplus.usuario.names')}}</h2>
						</div>
					</div>
		            <!-- /menu profile quick info -->
		            
					<!-- sidebar menu -->
					<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
						<div class="menu_section">
							<h3>MODULOS</h3>
							<ul class="nav side-menu">
							
							<!-- Menu DASHBOARD -->
							<li><a href="{{ url('/') }}"> <i class = "fa fa-dashboard fa-fw"> </i> DASHBOARD </a></li>
														
							@foreach (Session::get('opaplus.usuario.permisos') as $llave_permiso => $permiso)
								@if($llave_permiso == 1)								
									<!-- Menu para la aplicacion de SEGURIDAD -->
									
									@foreach ($permiso['modulos'] as $llave_categoria => $categoria)
										@foreach ($categoria as $llave_modulo => $modulo)									
											
											<!-- Modulo de usuarios, para que haga solo listar -->
											@if($llave_modulo == 6)
												@foreach ($modulo['opciones'] as $llave_opcion => $opcion)										
													@if($opcion['lugar'] == Session::get('opaplus.usuario.lugar.lugar'))
														@if($opcion['vista'] != 'listar')
															<li><a href="{{ url(json_decode($modulo['preferencias'])->controlador)}}/{{($opcion['accion'])}}/{{$llave_permiso}}/{{$llave_categoria}}/{{$llave_modulo}}" ><i class="{{json_decode($modulo['preferencias'])->icono}}"></i> {{$modulo['modulo']}} </a></li>
														@endif
													@endif											
												@endforeach
											
											@else
											<li>
												<a><i class = "{{json_decode($modulo['preferencias'])->icono}}"></i> {{$modulo['modulo']}}  <span class="fa fa-chevron-down"></span></a>
												<ul class="nav child_menu">
											
												<!-- opción General solo para superadministrador -->
												@if(Session::get('opaplus.usuario.rol_id') == 1)
													<li><a href="{{ url(json_decode($modulo['preferencias'])->controlador)}}/index/{{$llave_modulo}}/{{$modulo['modulo']}}/{{$modulo['descripcion']}}/{{$llave_permiso}}/{{$llave_categoria}}"></i> General </a> </li>
												@endif										
												
												@foreach ($modulo['opciones'] as $llave_opcion => $opcion)										
													@if($opcion['lugar'] == Session::get('opaplus.usuario.lugar.lugar'))
														@if($opcion['vista'] != 'listar')													
															<li><a href="{{ url(json_decode($modulo['preferencias'])->controlador)}}/{{($opcion['accion'])}}/{{$llave_permiso}}/{{$llave_categoria}}/{{$llave_modulo}}" ></i> {{$opcion[$llave_opcion]}} </a> </li>           			
														@endif
													@endif											
												@endforeach
												</ul>
											</li>
											@endif	
											<!-- Cargamos los js que hacer referencia alos modulos para el cliente -->
											{{ Html::script('js/'.json_decode($permiso['preferencias'])->js.'/'.json_decode($modulo['preferencias'])->js.'.js') }}							 
										@endforeach
									@endforeach
									
								@else							
								
								<!-- Menu para la aplicacion de AMIGOS -->
															
									@foreach ($permiso['modulos'] as $llave_categoria => $categoria)
										@foreach ($categoria as $llave_modulo => $modulo)
											
											
											@if($llave_modulo == 7)
												<!-- Listar directamente solo para amigos -->
												@if(Session::get('opaplus.usuario.rol_id') == 3)
												
													@foreach ($modulo['opciones'] as $llave_opcion => $opcion)										
														@if($opcion['lugar'] == Session::get('opaplus.usuario.lugar.lugar'))
															@if($opcion['vista'] != 'listar')
																<li><a href="{{ url(json_decode($modulo['preferencias'])->controlador)}}/{{($opcion['accion'])}}/{{$llave_permiso}}/{{$llave_categoria}}/{{$llave_modulo}}" ><i class="{{json_decode($modulo['preferencias'])->icono}}"></i> {{$modulo['modulo']}} </a></li>
															@endif
														@endif											
													@endforeach
													
												@else
												<!-- Modulo de suscripciones, para que liste las opciones -->
												<li>
													<a><i class = "{{json_decode($modulo['preferencias'])->icono}}"></i> {{$modulo['modulo']}}  <span class="fa fa-chevron-down"></span></a>
													<ul class="nav child_menu">												
													@foreach ($modulo['opciones'] as $llave_opcion => $opcion)										
														@if($opcion['lugar'] == Session::get('opaplus.usuario.lugar.lugar'))
															@if($opcion['vista'] != 'listar')													
																<li><a href="{{ url(json_decode($modulo['preferencias'])->controlador)}}/{{($opcion['accion'])}}/{{$llave_permiso}}/{{$llave_categoria}}/{{$llave_modulo}}" ></i> {{$opcion[$llave_opcion]}} </a> </li>           			
															@endif
														@endif											
													@endforeach
													</ul>
												</li>
												@endif
												
											@else
												@if($llave_modulo == 9)
													<!-- Modulo de convenios, para que liste las opciones -->
													<li>
														<a><i class = "{{json_decode($modulo['preferencias'])->icono}}"></i> {{$modulo['modulo']}}  <span class="fa fa-chevron-down"></span></a>
														<ul class="nav child_menu">												
														@foreach ($modulo['opciones'] as $llave_opcion => $opcion)										
															@if($opcion['lugar'] == Session::get('opaplus.usuario.lugar.lugar'))
																@if($opcion['vista'] != 'listar')													
																	<li><a href="{{ url(json_decode($modulo['preferencias'])->controlador)}}/{{($opcion['accion'])}}/{{$llave_permiso}}/{{$llave_categoria}}/{{$llave_modulo}}" ></i> {{$opcion[$llave_opcion]}} </a> </li>           			
																@endif
															@endif											
														@endforeach
														</ul>
													</li>
												@else
													@if($llave_modulo == 8)
													<!-- No pinta el modulo de beneficiarios -->
													@else													
														@if($llave_modulo == 10)
														<!-- No pinta el modulo de especialidades -->
														@else
															@if($llave_modulo == 11)
															<!-- No pinta el modulo de especialistas -->
															@else
																@foreach ($modulo['opciones'] as $llave_opcion => $opcion)										
																	@if($opcion['lugar'] == Session::get('opaplus.usuario.lugar.lugar'))
																		@if($opcion['vista'] != 'listar')
																			<li><a href="{{ url(json_decode($modulo['preferencias'])->controlador)}}/{{($opcion['accion'])}}/{{$llave_permiso}}/{{$llave_categoria}}/{{$llave_modulo}}" ><i class="{{json_decode($modulo['preferencias'])->icono}}"></i> {{$modulo['modulo']}} </a></li>
																		@endif
																	@endif											
																@endforeach
															@endif
															
														@endif
														
													@endif
													
												@endif									
												
											@endif
											
											
											
												
											<!-- Cargamos los js que hacer referencia alos modulos para el cliente -->
											{{ Html::script('js/'.json_decode($permiso['preferencias'])->js.'/'.json_decode($modulo['preferencias'])->js.'.js') }}							 
										@endforeach
									@endforeach
								@endif
														
							@endforeach
							
							
							
							</ul>
						</div>
					</div>
					<!-- /sidebar menu -->
					
					<!-- /menu footer buttons -->
					<div class="sidebar-footer hidden-small">
					</div>
					<!-- /menu footer buttons -->
		        </div>
			</div> 
			
			<!-- top navigation -->
			<div class="top_nav">
				<div class="nav_menu">
					<nav>
						<div class="nav toggle">
							<a id="menu_toggle"><i class="fa fa-bars"></i></a>
						</div>
						
						<ul class="nav navbar-nav navbar-right">
							<li class="">
								<a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									{{Session::get('opaplus.usuario.names')}}
									<span class=" fa fa-angle-down"></span>
								</a>
								<ul class="dropdown-menu dropdown-usermenu pull-right">
									<li><a href="{{ url('perfil_usuario') }}"> Profile</a></li>									
									<li><a href="{{ url('salida_segura') }}"><i class="fa fa-sign-out pull-right"></i>Salida segura</a></li>
								</ul>
							</li>
							
							<li role="presentation" class="dropdown">
								<a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
									<i class="fa fa-envelope-o"></i>
									<span class="badge bg-green"></span>
								</a>
							</li>
							
						</ul>					
					</nav>
				</div>
			</div>
			<!-- top navigation -->
			
			<!-- page content -->
			<div class="right_col" role="main">
				@yield('content')
			</div>
		@else		
			@yield('content')
		@endif		
				
		</div>
	</div>
		 
	@yield('modal')
	
	<!-- Scripts -->  
	{{ Html::script('js/lib/jquery.min.js')}}
	{{ Html::script('js/lib/jquery-ui.js') }}
	{{ Html::script('js/lib/bootstrap.min.js') }}
	{{ Html::script('js/lib/alela/js/custom.min.js')}}
	{{ Html::script('js/lib/highcharts.js') }}	
	{{ Html::script('js/lib/exporting.js') }}	
	{{ Html::script('js/lib/datatables.min.js') }}	
	{{ Html::script('js/lib/datatables.tabletools.js') }}
	{{ Html::script('js/lib/datatables.responsive.min.js') }}	
	{{ Html::script('js/lib/bootstrap-datepicker.min.js') }}
	{{ Html::script('js/lib/locales/bootstrap-datepicker.es.min.js') }}
				
	{{ Html::script('js/seguridad/seg_user.js') }}
	{{ Html::script('js/seguridad/seg_ajaxobject.js') }}
	
	
	
	@yield('script')
	
</body>
@if (!Auth::guest())
	<footer>
		<div class="form-group">
			<div class="col-md-3 col-md-offset-5">
				<p>© 2016 {{ Session::get('copy') }}, Inc.</p>
			</div>		
		</div>	
	</footer>
@endif		
</html>
<!--  {{ dd(Session::all()) }} -->