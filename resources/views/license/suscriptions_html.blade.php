<DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title>Carnet PDF</title>
	</head>

	<style type="text/css">		
		
		font{
			font-size: 14px;
			/*font-family: 'Raleway', sans-serif;*/
		}
		.conteiner{
			/*width: 100%;*/
			width:  860px;
		}

		.page_conteiner{
			border: 0px solid transparent;
		    height: 1258px;
		}
		.cnt_conteiner{    
		    /*border: 1px solid;*/
		    width: 50%;
		    height: 305px;
		    float: left;
		    display: flex;
    		flex-wrap: wrap;
			align-items: center;
		    -webkit-box-align: center;
		    -webkit-align-items: center;
		    -webkit-box-pack: center;
		    -webkit-justify-content: center;
		    -ms-flex-pack: center;
		    justify-content: center;
		    color: #575757;
		    font-family: sans-serif;
		}

		.mini_contenedor{
			width: 418px;
		    height: 245px;
		    /*height: 80%;*/
		    background-image: url("carnet_front.jpg");
		    background-repeat: no-repeat;
		    background-size: 100% 100%;
	        /*margin-left: 8px;*/
		}

		.mini_contenedor_back{
			width: 418px;
		    height: 245px;
		    /*height: 80%;*/
		    background-image: url("carnet_back.jpg");
		    background-repeat: no-repeat;
		    background-size: 100% 100%;	
		    margin-top: 24px;
		    margin-left: 12px;
		}

		.cont_back{
			margin-left: 0px;
		    margin-top: 60px;
		    font-size: 11px;
		    width: 200px;
		    text-align: right;
		}

		.mini_contenedor_relleno{
			width: 363px;
		    height: 245px;
		}

		.name_titular{
			margin-top: 42px;
    		padding-left:8px;
    		font-size: 14px;
		}

		.id_titular{
			padding-left: 8px;
			font-size: 14px;
		}

		.name_titular_big{
			margin-top: 42px;
    		padding-left:8px;
    		font-size: 14px;
		}

		.id_titular_big{
			padding-left: 18px;
			font-size: 14px;
		}
		
		.name_titular_big_2{
			margin-top: 42px;
    		padding-left:8px;
    		font-size: 12px;
		}

		.id_titular_big_2{
			padding-left: 8px;
			font-size: 14px;
		}

		.cnt_bnt{
			margin-top: 40px;
			padding-left: 8px;
		    height: 105px;	
		}

		.foot{			
		    color: white;
	        margin-left: 268px;
    		font-size: 14px;
		}

		.info_foot{

		}
		
		.conteiner .page_conteiner:nth-of-type(even) .cnt_conteiner:nth-of-type(even){
			
		}

		/*impares*/
		.conteiner .page_conteiner:nth-of-type(even) .cnt_conteiner:nth-of-type(odd){			
			float: right;	
		}
		

		@media print {
		* {
		    -webkit-print-color-adjust: exact !important; /*Chrome, Safari */
		    color-adjust: exact !important;  /*Firefox*/
		  }
		}	

	</style>

 	@php ($dato=$array)
	
	
	<body>			
		<div class ="conteiner">

				@php ($p=0)
				@php ($j=1)
				
				@foreach ($dato[1] as $cnt)

					<!--Cada 8 es un Contenedor diferente-->
					@if($p%8==0)
						<div class="page_conteiner">
					@endif
						<div class="cnt_conteiner">	
							<div class="mini_contenedor">
								@if( strlen($dato[0][$cnt['suscription_id']]['names_fr'].' '.$dato[0][$cnt['suscription_id']]['surnames_fr']) < 24 )
									<div class="name_titular">
										<b>{{ $dato[0][$cnt['suscription_id']]['names_fr'].' '.$dato[0][$cnt['suscription_id']]['surnames_fr'] }}</b>
									</div>
									<div class="id_titular">C.C {{ $dato[0][$cnt['suscription_id']]['identificacion_fr'] }}</div>
								@else

									@if( strlen($dato[0][$cnt['suscription_id']]['names_fr'].' '.$dato[0][$cnt['suscription_id']]['surnames_fr']) < 32 )
										<div class="name_titular_big">
										<b>{{ ucwords(strtolower($dato[0][$cnt['suscription_id']]['names_fr'].' '.$dato[0][$cnt['suscription_id']]['surnames_fr'] ))}}</b>
										</div>
										<div class="id_titular_big">C.C {{  $dato[0][$cnt['suscription_id']]['identificacion_fr'] }}</div>
									@else
										<div class="name_titular_big_2">
										<b>{{ ucwords(strtolower($dato[0][$cnt['suscription_id']]['names_fr'].' '.$dato[0][$cnt['suscription_id']]['surnames_fr'] ))}}</b>
										</div>
										<div class="id_titular_big_2">C.C {{ $dato[0][$cnt['suscription_id']]['identificacion_fr'] }}</div>
									@endif
									
								@endif
								
								<div class = "cnt_bnt" >
								@foreach ($dato[2] as $bnt)
									@if($bnt['license_id'] ==$cnt['id'] )
										
										<div class="name_beneficiario">
											<font> {{ ucwords(strtolower($bnt['names'].' '.$bnt['surnames'])) }} </font>
										</div>
										
									@endif						
								@endforeach
								</div>
								<div class = "foot">
									<div class="info_foot">
										<span>
											<b> {{ $dato[0][$cnt['suscription_id']]['code']}} </b>
											<b>
												</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  {{date('j-m-Y',strtotime($dato[0][$cnt['suscription_id']]['date_expiration']))}}
											</b>
									</div>
								</div>
							</div>
						</div>
						@if($j%8==0)
						</div>

						<div class="page_conteiner">
							<!--Back-->
							<div class="cnt_conteiner">	
								<div class="mini_contenedor_back">
									<div class="cont_back">Esta credencial de descuento es para uso de los clientes aquí registrados<br><br>La actualización de la misma está condicionada por el acuerdo con la <b>Revista Club de Amigos</b> <br><br> Calle 51 Nº 50-66 int 506<br>Edificio Plaza Parque Itaguí<br> Itaguí - Antioquia </div>
								</div>
							</div>
							<div class="cnt_conteiner">	
								<div class="mini_contenedor_back">
									<div class="cont_back">Esta credencial de descuento es para uso de los clientes aquí registrados<br><br>La actualización de la misma está condicionada por el acuerdo con la <b>Revista Club de Amigos</b> <br><br> Calle 51 Nº 50-66 int 506<br>Edificio Plaza Parque Itaguí<br> Itaguí - Antioquia </div>
								</div>
							</div>
							<div class="cnt_conteiner">	
								<div class="mini_contenedor_back">
									<div class="cont_back">Esta credencial de descuento es para uso de los clientes aquí registrados<br><br>La actualización de la misma está condicionada por el acuerdo con la <b>Revista Club de Amigos</b> <br><br> Calle 51 Nº 50-66 int 506<br>Edificio Plaza Parque Itaguí<br> Itaguí - Antioquia </div>
								</div>
							</div>
							<div class="cnt_conteiner">	
								<div class="mini_contenedor_back">
									<div class="cont_back">Esta credencial de descuento es para uso de los clientes aquí registrados<br><br>La actualización de la misma está condicionada por el acuerdo con la <b>Revista Club de Amigos</b> <br><br> Calle 51 Nº 50-66 int 506<br>Edificio Plaza Parque Itaguí<br> Itaguí - Antioquia </div>
								</div>
							</div>
							<div class="cnt_conteiner">	
								<div class="mini_contenedor_back">
									<div class="cont_back">Esta credencial de descuento es para uso de los clientes aquí registrados<br><br>La actualización de la misma está condicionada por el acuerdo con la <b>Revista Club de Amigos</b> <br><br> Calle 51 Nº 50-66 int 506<br>Edificio Plaza Parque Itaguí<br> Itaguí - Antioquia </div>
								</div>
							</div>
							<div class="cnt_conteiner">	
								<div class="mini_contenedor_back">
									<div class="cont_back">Esta credencial de descuento es para uso de los clientes aquí registrados<br><br>La actualización de la misma está condicionada por el acuerdo con la <b>Revista Club de Amigos</b> <br><br> Calle 51 Nº 50-66 int 506<br>Edificio Plaza Parque Itaguí<br> Itaguí - Antioquia </div>
								</div>
							</div>
							<div class="cnt_conteiner">	
								<div class="mini_contenedor_back">
									<div class="cont_back">Esta credencial de descuento es para uso de los clientes aquí registrados<br><br>La actualización de la misma está condicionada por el acuerdo con la <b>Revista Club de Amigos</b> <br><br> Calle 51 Nº 50-66 int 506<br>Edificio Plaza Parque Itaguí<br> Itaguí - Antioquia </div>
								</div>
							</div>
							<div class="cnt_conteiner">	
								<div class="mini_contenedor_back">
									<div class="cont_back">Esta credencial de descuento es para uso de los clientes aquí registrados<br><br>La actualización de la misma está condicionada por el acuerdo con la <b>Revista Club de Amigos</b> <br><br> Calle 51 Nº 50-66 int 506<br>Edificio Plaza Parque Itaguí<br> Itaguí - Antioquia </div>
								</div>
							</div>
						</div>
					@endif
					@php ($p++)
					@php ($j++)
					@endforeach


					@if(count($dato[1]) < 8)				
						<!--rellenamos con carnet sin fondo-->
						@for($i=0 ;$i < intval(count($dato[1])-8); $i++)				
							<div class="cnt_conteiner">	
								<div class="mini_contenedor_relleno">	
								</div>
							</div>
						@endfor
						<!--cierre de page-->
						</div>
						<div class="page_conteiner">
						@for($i=0 ;$i< intval(count($dato[1])); $i++)	
							<div class="cnt_conteiner">	
								<div class="mini_contenedor_back">
									<div class="cont_back">Esta credencial de descuento es para uso de los clientes aquí registrados<br><br>La actualización de la misma está condicionada por el acuerdo con la <b>Revista Club de Amigos</b> <br><br> Calle 51 Nº 50-66 int 506<br>Edificio Plaza Parque Itaguí<br> Itaguí - Antioquia </div>
								</div>
							</div>
						@endfor
						</div>

					@else
						<!--son más de 8 carnets-->
						<!--rellenamos con carnet sin fondo-->
						@for($i=0 ;$i< intval(count($dato[1])%8-8); $i++)				
							<div class="cnt_conteiner">	
								<div class="mini_contenedor_relleno">	
								</div>
							</div>
						@endfor
						<!--cierre de page-->
						</div>
						<div class="page_conteiner">
						@for($i=0 ;$i< intval(count($dato[1])%8); $i++)				
							<div class="cnt_conteiner">	
								<div class="mini_contenedor_back">
									<div class="cont_back">Esta credencial de descuento es para uso de los clientes aquí registrados<br><br>La actualización de la misma está condicionada por el acuerdo con la <b>Revista Club de Amigos</b> <br><br> Calle 51 Nº 50-66 int 506<br>Edificio Plaza Parque Itaguí<br> Itaguí - Antioquia </div>
								</div>
							</div>
						@endfor
						</div>
					@endif


					
				

			

		</div>
	</body>
</html>
