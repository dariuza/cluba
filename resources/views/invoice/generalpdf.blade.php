<DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title>Carnet PDF</title>
	</head>	
	{{ Html::style('css/invoice.css')}}	
	<body>
		<div class = "conteiner">

			@foreach ($suscripciones as $sct)

			<!--Div de cabecera-->
			<div class = "suscription">

				<div class = "cabecera_conteiner">
				 	<div class = "cab_cab">
				 		<div class="cab_into">
				 			<div>NIT:900.375.310-2</div>
				 			<div>Calle 51 # 48-74 piso 201 (Itagui al frente de agaval)</div>
				 			<div>PBX: 371 84 90 Cel: 314 782 04 27 </div>
				 		</div>
				 		<div class="cab_info">
				 			<div>FACTURA DE VENTA</div>				 			
				 			<div class = "nro_invoice">{{$sct['n_provisional']}}</div>			 			
				 		</div>
				 	</div>
				 	<div class = "cab_pie">
				 		<div class="cab_pie_into">
				 			<div>Resolución DIAN No. 110000626205</div>
				 			<div>Fecha de Resolución 2015/0423</div>
				 			<div>Numeración Autorizada desde 8.850 hasta 20.000</div>
				 		</div>
				 		<div class="cab_pie_fecha1">
				 			<div>FECHA</div>
				 			<div class = "fecha">{{$sct['fecha_suscipcion']}}</div>
				 		</div>
				 		<div class="cab_pie_fecha2">
				 			<div>FECHA VENCIMIENTO</div>
				 			<div class = "fecha">{{$sct['fecha_expiracion']}}</div>
				 		</div>
				 	</div>
				</div>
				<!--Div de informacion-->
				<div class = "info_cabecera">
					<div>Cliente:  {{$sct['Nombres_socio']}}</div>
					<div>Nit:  {{$sct['identificacion_fr']}} - Dirección:  {{$sct['Direccion']}}</div>
					<div>Tel:  {{$sct['Celular']}} , {{$sct['Fijo']}} - Forma de Pago:  {{$sct['forma_pago']}} - E-mail:  {{$sct['Correo']}}</div>
					
				</div>
				<!--Cuerpo-->
				<div class="cuerpo"></div>

				<!--Div de pie -->
				<div class = "pie">
					Esta Factura de venta se asimila en sus efectos a la Letra de Cambio Art. 774 del código de comercio en caso de atraso en el pago, causará intereses momentarios a la venta maxima permitida por la ley
				</div>
			</div>

			@endforeach

		</div>
	</body>
</html>
