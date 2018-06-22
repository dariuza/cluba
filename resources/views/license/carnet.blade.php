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
			font-size: 11px;
			/*font-family: 'Raleway', sans-serif;*/
		}
		.conteiner{
			/*width: 100%;*/
			width:  860px;
		}

		.page_conteiner{
			border: 1px solid;
		    height: 1254px;
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
		}

		.mini_contenedor{
			width: 390px;
		    height: 245px;
		    /*height: 80%;*/
		    background-image: url("carnet_front.png");
		    background-repeat: no-repeat;
		    background-size: 100% 100%;
		}

		.mini_contenedor_back{
			width: 390px;
		    height: 245px;
		    /*height: 80%;*/
		    background-image: url("carnet_back.png");
		    background-repeat: no-repeat;
		    background-size: 100% 100%;	
		}

		.mini_contenedor_relleno{
			width: 363px;
		    height: 245px;
		}

		.name_titular{
			margin-top: 42px;
    		padding-left: 15px;
    		font-size: 14px;
		}

		.cnt_bnt{
			margin-top: 50px;
			padding-left: 15px;
		    height: 114px;	
		}

		.foot{			
		    color: white;
	        margin-left: 258px;
    		font-size: 14px;
		}

		.info_foot{

		}
		
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
							<div class="name_titular">{{ $suscription['names_fr'].' '.$suscription['surnames_fr'] }}</div>
							<div class = "cnt_bnt" >
							@foreach ($bnes as $bnt)
								@if($bnt['license_id'] ==$cnt['id'] )
									
									<div class="name_beneficiario">
										<font> {{ $bnt['names'].' '.$bnt['surnames'] }} </font>
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
