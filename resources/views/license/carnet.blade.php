<DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title>Carnet PDF</title>
	</head>

	{{--
	{{ Html::style('css/license_carnet.css')}}
	{{ Html::style('css/lib/alela/css/bootstrap.min.css')}}
	--}}


	@php ($cnts=$array["cnts"])
	@php ($suscription=$array["suscription"])
	@php ($bnes=$array["bnes"])
	
	
	<style type="text/css">
		
		/*medidas de la pagina
		width: 814px;
		height: 1034px; 
		html{margin:0px 0px 0px 0px;}
		*/
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
		    height: 1256px;
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
	        margin-left: 8px;
		}

		.mini_contenedor_back{
			width: 416px;
		    height: 245px;
		    /*height: 80%;*/
		    background-image: url("carnet_back.jpg");
		    background-repeat: no-repeat;
		    background-size: 100% 100%;	
		    margin-top: 24px;
		    margin-left: 22px;
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
			margin-top: 45px;
			padding-left: 8px;
		    height: 100px;	
		}

		.foot{			
		    color: white;
	        margin-left: 244px;
    		font-size: 14px;
		}

		.info_foot{

		}
		
		/*
		.conteiner .page_conteiner:nth-of-type(1){
			margin-bottom: 2px;
		}
		.conteiner .page_conteiner:nth-of-type(2){
			margin-bottom: 9px;
		}

		.conteiner .page_conteiner:nth-of-type(3){
			margin-bottom: 9px;
		}

		.conteiner .page_conteiner:nth-of-type(4){
			margin-bottom: 9px;
		}
		*/

		/*reverse de crarnets*/
		/*solo para la parte trasera, solo los pares*/
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

	
	<body>
		
		<div class ="conteiner">			
			@php ($p=0)
			@php ($j=1)
			@foreach ($cnts as $cnt)
				<!--Cada 8 es un Contenedor diferente-->
				@if($p%8==0)
					<div class="page_conteiner">
				@endif
					<div class="cnt_conteiner">	
						<div class="mini_contenedor">
							@if( strlen($suscription['names_fr'].' '.$suscription['surnames_fr']) < 24 )
								<div class="name_titular">
									<b>{{ $suscription['names_fr'].' '.$suscription['surnames_fr'] }}</b>
								</div>
								<div class="id_titular">C.C {{ $suscription['identificacion_fr'] }}</div>
							@else

								@if( strlen($suscription['names_fr'].' '.$suscription['surnames_fr']) < 32 )
									<div class="name_titular_big">
									<b>{{ ucwords(strtolower($suscription['names_fr'].' '.$suscription['surnames_fr'] ))}}</b>
									</div>
									<div class="id_titular_big">C.C {{  $suscription['identificacion_fr'] }}</div>
								@else
									<div class="name_titular_big_2">
									<b>{{ ucwords(strtolower($suscription['names_fr'].' '.$suscription['surnames_fr'] ))}}</b>
									</div>
									<div class="id_titular_big_2">C.C {{ $suscription['identificacion_fr'] }}</div>
								@endif
								
							@endif
							
							<div class = "cnt_bnt" >
							@foreach ($bnes as $bnt)
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
										<b> {{ $suscription['code']}} </b>
										<b>
											</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  {{date('j-m-Y',strtotime($suscription['date_expiration']))}}
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
							</div>
						</div>
						<div class="cnt_conteiner">	
							<div class="mini_contenedor_back">	
							</div>
						</div>
						<div class="cnt_conteiner">	
							<div class="mini_contenedor_back">	
							</div>
						</div>
						<div class="cnt_conteiner">	
							<div class="mini_contenedor_back">	
							</div>
						</div>
						<div class="cnt_conteiner">	
							<div class="mini_contenedor_back">	
							</div>
						</div>
						<div class="cnt_conteiner">	
							<div class="mini_contenedor_back">	
							</div>
						</div>
						<div class="cnt_conteiner">	
							<div class="mini_contenedor_back">	
							</div>
						</div>
						<div class="cnt_conteiner">	
							<div class="mini_contenedor_back">	
							</div>
						</div>
					</div>
				@endif
				@php ($p++)
				@php ($j++)
			@endforeach


			@if(count($cnts) < 8)				
				<!--rellenamos con carnet sin fondo-->
				@for($i=0 ;$i < intval(count($cnts)-8); $i++)				
					<div class="cnt_conteiner">	
						<div class="mini_contenedor_relleno">	
						</div>
					</div>
				@endfor
				<!--cierre de page-->
				</div>
				<div class="page_conteiner">
				@for($i=0 ;$i< intval(count($cnts)); $i++)	
					<div class="cnt_conteiner">	
						<div class="mini_contenedor_back">	
						</div>
					</div>
				@endfor
				</div>

			@else
				<!--son más de 8 carnets-->
				<!--rellenamos con carnet sin fondo-->
				@for($i=0 ;$i< intval(count($cnts)%8-8); $i++)				
					<div class="cnt_conteiner">	
						<div class="mini_contenedor_relleno">	
						</div>
					</div>
				@endfor
				<!--cierre de page-->
				</div>
				<div class="page_conteiner">
				@for($i=0 ;$i< intval(count($cnts)%8); $i++)				
					<div class="cnt_conteiner">	
						<div class="mini_contenedor_back">	
						</div>
					</div>
				@endfor
				</div>
			@endif
			
		</div>		
		
	</body>
</html>
