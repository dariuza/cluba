<DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title>Carnet PDF</title>
	</head>
	
	{{-- @php ($data=$array["data"]) --}} 	

	{{ Html::style('css/license.css')}}		
	<body>			
		<div class ="cnt_conteiner">
			@php ($c=0)			
			@foreach ($data as $dato)								
				@foreach ($dato['cnts'] as $cnt)
					@if($c==0)
						<p style = "color:white"> {{ $dato['suscription']['code']}}</p>
						<p style = "color:white"> {{ $dato['suscription']['code']}}</p>
						@php ($c=4)	
					@endif					
					<div class="cnt_cnt">
						<div class ="bnt_container">
							<div class = "bnt_body">
								<div class = "head_titular"> <b><font style = "font-size: 11px;"> TITULAR</font></b> </div>
								<div class = "cnt_titular">
									<div> <font style = "font-size: 12px;" ><b> {{ $dato['suscription']['names_fr'].' '.$dato['suscription']['surnames_fr'] }} </b></font> </div> 
									<div> <font style = "font-size: 12px;" > C.C. {{$dato['suscription']['identificacion_fr'] }} </font></div>
								</div>
								<div style = "margin-left: 5%;margin-bottom: 5px;">
									<div > <font ><b> BENEFICIARIOS </b></font> </div>															
								</div>
								<div class ="cnt_benes">
								@foreach ($dato['bnes'] as $bnt)
									@if($bnt['license_id'] ==$cnt['id'] )
										<div class = "cnt_bnt" > <font > {{ $bnt['names'].' '.$bnt['surnames'] }} </font> </div>
									@endif						
								@endforeach
								</div>
								<div class = "foot">
									<div class="info_foot">
										<span><b>CUPO N°</b> </span>     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    <b><span>VENCE</span></b>         
									</div>
									<div class="info_foot" style = "font-size: 15px;">
										<span style = "font-size: 15px;"> <b> {{ $dato['suscription']['code']}} </b> <b></span>    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   {{date('j-m-Y',strtotime($dato['suscription']['date_expiration']))}}</b>
									</div>
								</div>
							</div>
						</div>
						<div class="info_container">
							<div class = "info_body">
								<div> <font>  Esta credencial de descuanto es para uso de</font> </div>
								<div> <font>  los clientes aqui registrados</font> </div>
								<br><div> <font>  La actualización d ela misma está</font> </div>
								<div> <font>  condiciaonada por el acuerdo con la</font> </div>
								<div><b> <font>  Revista Club de Amigos</font> </b> </div></br>
								<br><div> <font>  Calle 51 N° 50-66 int 506</font> </div>
								<div> <font>  Edificio Plaza Parque Itagui</font> </div>
								<div><b> <font>  Itagui - Antioquia</font> </b> </div></br>
								<div class = "foot_info" >
									<span>PBX: 4449258 . Cel: 313 877 3347 &nbsp;&nbsp; </span>
									{{ Html::image('images/icons/whatsapp.png','Imagen no disponible',array( 'style'=>'width: 6%;' ))}}
									<span> &nbsp; 311 726 3747  </span>
								</div>
							</div>
						</div>
					</div>					
					@php ($c--)
				@endforeach
			@endforeach
		</div>
	</body>
</html>
