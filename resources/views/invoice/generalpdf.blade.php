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

			<div class="suscription">
				<div class="fecha" >
					<span class="spanfecha1"> {{$sct['annos']}}  &nbsp;&nbsp;</span>
					<span>{{$sct['mess']}}  &nbsp;&nbsp; &nbsp;&nbsp;</span>
					<span>{{$sct['dias']}}</span>
					<span class="spanfecha2"> {{$sct['annoe']}}  &nbsp;&nbsp;</span>
					<span>{{$sct['mese']}}  &nbsp;&nbsp; &nbsp;&nbsp;</span>
					<span>{{$sct['diae']}}</span>
				</div>
				<div class="nombre">
					<span class="spannombre">  {{$sct['Nombres_socio']}} </span>
				</div>
				<div class="nit">
					<span class="spannit">  {{$sct['identificacion_fr']}} </span>
					<span class="spandir">  {{$sct['Direccion']}} </span>
				</div>	
				<div class="tel">
					<span class="spantel">  {{$sct['Celular']}} , {{$sct['Fijo']}} </span>
					@php($marginefectivo=157)		
					@if($sct['Celular'] == 0 && $sct['Fijo'] == 0)
						<span class="spanforma" style="margin-left: 267px">  {{$sct['forma_pago']}} </span>				
						@elseif($sct['Celular'] != 0 && $sct['Fijo'] != 0)
							<span class="spanforma" style="margin-left: 127px">  {{$sct['forma_pago']}} </span>		
						@else
							<span class="spanforma" style="margin-left: 217px">  {{$sct['forma_pago']}} </span>	
						
					@endif
					<span class="spanemail" style="margin-left: 97px">  {{$sct['Correo']}} </span>					
				</div>

				<div class="suscripcion">
					<div class="fila">
						<span class="cantidad">1</span>
						<span class="descripcion">Suscripción Club de Amigos</span>
						<span class="valorunitario">${{number_format(ceil($sct['precio']/(1+env('PRICE_IVA',0.19))))}}</span>
						<span class="total">${{number_format(ceil($sct['precio']/(1+env('PRICE_IVA',0.19))))}}</span>
						@php($totaliva=ceil($sct['precio']/(1+env('PRICE_IVA',0.19))))
						@php($total=$sct['precio'])
					</div>
					
					@php($add=0)
					@if($sct['precio_beneficiarios_adicionales'])
						<div class="fila">
							<span class="cantidad">{{$sct['precio_beneficiarios_adicionales']/env('PRICE_BENEFICIARY')}}</span>
							<span class="descripcion">Beneficiarios adicionales  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<span class="valorunitario">${{number_format(ceil(env('PRICE_BENEFICIARY')/(1+env('PRICE_IVA',0.19))))}} &nbsp;&nbsp;</span>
							<span class="total">${{number_format(ceil($sct['precio_beneficiarios_adicionales']/(1+env('PRICE_IVA',0.19))))}}</span>
							@php($totaliva=$totaliva+ceil($sct['precio_beneficiarios_adicionales']/(1+env('PRICE_IVA',0.19))))
							@php($total=$total+$sct['precio_beneficiarios_adicionales'])	
						</div>
						@php($add++)
					@endif

					@if($sct['precio_carnets_reimpresion'])
						<div class = "fila" >
							<span class="cantidad">{{$sct['precio_carnets_reimpresion']/env('PRICE_LICENSE')}}</span>
							<span class="descripcion" >Reimpresión de Carnet &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<span class="valorunitario" > ${{number_format(ceil(env('PRICE_LICENSE')/(1+env('PRICE_IVA',0.19))))}} &nbsp;&nbsp;&nbsp;&nbsp;</span>
							<span class="total" >${{number_format(ceil($sct['precio_carnets_reimpresion']/(1+env('PRICE_IVA',0.19))))}}</span>
							@php($totaliva=$totaliva+ceil($sct['precio_carnets_reimpresion']/(1+env('PRICE_IVA',0.19))))
							@php($total=$total+$sct['precio_carnets_reimpresion'])
						</div>
						@php($add++)
					@endif

					@if($sct['precio_carnets'])
						<div class = "fila" >
							<span class="cantidad">{{$sct['precio_carnets']/env('PRICE_LICENSE')}}</span>
							<span class="descripcion" >Carnet adicionales &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<span class="valorunitario" > ${{number_format(ceil(env('PRICE_LICENSE')/(1+env('PRICE_IVA',0.19))))}} &nbsp;&nbsp;&nbsp;&nbsp;</span>
							<span class="total" >${{number_format(ceil($sct['precio_carnets']/(1+env('PRICE_IVA',0.19))))}}</span>
							@php($totaliva=$totaliva+ceil($sct['precio_carnets']/(1+env('PRICE_IVA',0.19))))
							@php($total=$total+$sct['precio_carnets'])
						</div>
						@php($add++)
					@endif

					<!--Dependiendo del $add se crean las siguientes celdas-->
					@for($i=5;$i>$add;$i--)
						<div class = "fila" >
							<span class="cantidad"></span>
							<span class="descripcion" ></span>
							<span class="valorunitario" > </span>
							<span class="total"></span>
						</div>						
					@endfor

					<div class = "fila" >
						<span class="cantidad"></span>
						<span class="descripcion" ></span>
						<span class="valorunitario" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
						<span class="total">${{number_format($total-$totaliva)}}</span>
					</div>
					<div class = "fila" >
						<span class="cantidad"></span>
						<span class="descripcion" ></span>
						<span class="valorunitario" > </span>
						<span class="total"></span>
					</div>		
					<div class = "fila" style="margin-top: 13px;" >
						<span class="cantidad"></span>
						<span class="descripcion" ></span>
						<span class="valorunitario" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
						<span class="total">${{number_format($total)}}</span>
					</div>		
				</div>	
					
				
			</div>

			@endforeach

		</div>
	</body>
</html>
