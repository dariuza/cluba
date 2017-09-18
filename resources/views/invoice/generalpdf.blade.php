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
				<div class="cuerpo">
					<div class = "tabla_encabezado" >
						<div class="celda_cantidad">CANTIDAD</div>
						<div class="celda_descripcion" >DESCRIPCIÓN</div>
						<div class="celda_unitario" >VR. UNITARIO</div>
						<div class="celda_total" >VR. TOTAL</div>
					</div>
					<div class = "tabla_fila" >
						<div class="celda_cantidad">1</div>
						<div class="celda_descripcion" >Suscripción Club de Amigos</div>
						<div class="celda_unitario" > ${{$sct['precio']}}</div>
						<div class="celda_total" >${{$sct['precio']}}</div>
						@php($total=$sct['precio'])
					</div>
					@php($add=0)
					@if($sct['precio_beneficiarios_adicionales'])
						<div class = "tabla_fila" >
							<div class="celda_cantidad">{{$sct['precio_beneficiarios_adicionales']/env('PRICE_BENEFICIARY')}}</div>
							<div class="celda_descripcion" >Beneficiarios adicionales</div>
							<div class="celda_unitario" > ${{env('PRICE_BENEFICIARY')}}</div>
							<div class="celda_total" >${{$sct['precio_beneficiarios_adicionales']}}</div>
							@php($total=$total+$sct['precio_beneficiarios_adicionales'])
						</div>
						@php($add++)
					@endif
					@if($sct['precio_carnets_reimpresion'])
						<div class = "tabla_fila" >
							<div class="celda_cantidad">{{$sct['precio_carnets_reimpresion']/env('PRICE_LICENSE')}}</div>
							<div class="celda_descripcion" >Reimpresión de Carnet</div>
							<div class="celda_unitario" > ${{env('PRICE_LICENSE')}}</div>
							<div class="celda_total" >${{$sct['precio_carnets_reimpresion']}}</div>
							@php($total=$total+$sct['precio_carnets_reimpresion'])
						</div>
						@php($add++)
					@endif
					@if($sct['precio_carnets'])
						<div class = "tabla_fila" >
							<div class="celda_cantidad">{{$sct['precio_carnets']/env('PRICE_LICENSE')}}</div>
							<div class="celda_descripcion" >Carnet adicionales</div>
							<div class="celda_unitario" > ${{env('PRICE_LICENSE')}}</div>
							<div class="celda_total" >${{$sct['precio_carnets']}}</div>
							@php($total=$total+$sct['precio_carnets'])
						</div>
						@php($add++)
					@endif

					<!--Dependiendo del $add se crean las siguientes celdas-->
					@for($i=5;$i>$add;$i--)
						<div class = "tabla_fila" >
							<div class="celda_cantidad"></div>
							<div class="celda_descripcion" ></div>
							<div class="celda_unitario" > </div>
							<div class="celda_total" ></div>
						</div>
					@endfor
					
					<div class = "tabla_fila" >
						<div class="celda_cantidad_iva">Valor en letras: </div>							
						<div class="celda_unitario" > IVA:</div>
						<div class="celda_total" >${{$total*env('PRICE_IVA',0.19)}}</div>
					</div>

					<div class = "tabla_fila_final_1" >
						<div class="celda_cantidad_final_1"> </div>
						<div class="celda_unitario_final_1"> </div>							
						<div class="celda_total_final_1"> </div>										
					</div>

					<div class = "tabla_fila_final_2" >
						<div class="celda_cantidad_final_2">1 </div>
						<div class="celda_unitario_final_2"> h</div>							
						<div class="celda_total_final_2"> o</div>										
					</div>



				</div>

				<!--Div de pie -->
				<div class = "pie">
					Esta Factura de venta se asimila en sus efectos a la Letra de Cambio Art. 774 del código de comercio en caso de atraso en el pago, causará intereses momentarios a la venta maxima permitida por la ley
				</div>
			</div>
			<div class ="margen"></div>

			@endforeach

		</div>
	</body>
</html>
