<table width="100%" border="0" cellspacing="0" style="height:auto; float:left;">
	<tr>
		<td style="width:575px; height:78px; float:left;">
			<img alt="" src="http://store.skechers-chile.cl/img/mailing/mailing_catss_despacho.png">
		</td>
	</tr>
	<tr style="background-color:#FFF; float:left; width:525px; padding:10px 25px; background-image:url('http://store.skechers-chile.cl/img/mailing/borde-fondo.jpg'); background-position:right bottom; background-repeat:no-repeat; height:210px;">
		<td style="width:auto; float:left;">
			<p style="color:#7d5179; font-weight:bold; margin:0 0 10px 0; float:left; width:auto; font-size:16px;">Estimado(a) <?= $usuario['Usuario']['nombre']; ?>,</p>
		</td>
		<td  style="width:auto; float:left; margin-bottom:15px;">
			<p style="color:#7d5179; float:left; margin:0; width:520px; font-size:16px;">Tu Número de Orden es: <b style="font-weight:bold;"><?= $compra['Compra']['id']; ?></b></p>
		</td>
		<td style="width:520px; float:left;">
			<p style="color:#4d4d4d; float:left; margin:0; width:520px;">
				Estamos verificando tu información de pago. En caso de existir cualquier problema o dificultad relativa al medio de pago utilizado (u otra circunstancia grave que impida procesar la orden de compra), la orden deberá ser anulada.<br /><br />
				Tu orden será despachada en un máximo de 7 días hábiles.<br /><br />
				Tu Boleta se emitirá de forma electrónica y será enviada al mail que has registrado.  Conserva tu boleta para efectos de hacer valer tus derechos de garantía legal.  
			</p>
		</td>
	</tr>
	<tr style="width:525px; float:left; background-color:#FFF; padding:20px 25px 25px;">
		<td colspan="1" style="width:auto; float:left;">
			<p style="color:#666; float:left; width:520px; margin:0 0 5px;">
				<b style="font-weight:bold;">Realizada el:</b> <?= date('d-m-Y', strtotime($compra['Pago'][0]['created'])); ?>
			</p>
			<p style="color:#666; float:left; width:520px; margin:0 0 5px;">
				<b style="font-weight:bold;">Nombre:</b> <?= $usuario['Usuario']['nombre'] . ' ' . $usuario['Usuario']['apellido_paterno'] . ' ' . $usuario['Usuario']['apellido_materno']; ?>
			</p>
			<p style="color:#666; float:left; width:520px; margin:0 0 5px;">
				<b style="font-weight:bold;">RUT:</b> <?= $usuario['Usuario']['rut']; ?>
			</p>
			<p style="color:#666; float:left; width:520px; margin:0 0 5px;">
				<b style="font-weight:bold;">Número de Tarjeta:</b> XXXX- XXXX- XXXX - <?= $compra['Pago'][0]['numeroTarjeta']; ?>
			</p>
			<p style="color:#666; float:left; width:520px; margin:0 0 5px;">
				<b style="font-weight:bold;">Dirección despacho:</b>
				<?= $compra['Despacho']['Direccion']['calle'] . ' #' . $compra['Despacho']['Direccion']['numero'] ?><?= ( isset($compra['Despacho']['depto']) && $compra['Despacho']['Direccion']['depto'] )? ' Dpto.'.$compra['Despacho']['Direccion']['depto'] : ''; ?>, <?= $compra['Despacho']['Direccion']['Comuna']['nombre']; ?>.
			</p>
			<p style="color:#666; float:left; width:520px; margin:0 0 5px;">
				<b style="font-weight:bold;">Fono contacto:</b> <?= $compra['Despacho']['Direccion']['telefono']; ?>
			</p>
			<p style="color:#666; float:left; width:520px; margin:0 0 5px;">
				<b style="font-weight:bold;">E-Mail:</b> <?= $usuario['Usuario']['email']; ?>
			</p>
		</td>
	</tr>
	<tr style="float:left;">
		<table style="border:solid 1px #999; widows:525px; float:left; margin:0 24px; width:525px; margin-bottom:50px;">
			<tr>
				<td colspan="4"><p style="color:#333333; font-weight:bold; margin:0; padding:10px;">Datos de compra:</p></td>
			</tr>
			
			<? foreach($productos as $index => $producto) : ?>
			<tr>
				<td colspan="1" style="width:100px; float:left;">
					<img src="http://www.skechers.cl/img/<?= $producto['Producto']['foto']['mini']; ?>" alt="" />
				</td>
				<td style="width:210px; float:left;">
					<p style="color:#415375; float:left; font-weight:bold; margin:0;"><?= $producto['Producto']['nombre']; ?></p>
					<p style="color:#415375; float:left;"><?= $producto['Producto']['Color']['nombre']; ?><br />
					COD: <?= $producto['Producto']['codigo']; ?> <?= $producto['Producto']['Color']['codigo']; ?></p>
				</td>
				<td style="width:100px; float:left;">
					<p style="color:#415375; float:left;">Cantidad: 1<br />
					Talla: <?= $producto['ProductosCompra']['talla']; ?></p>
				</td>
				<td style="float:left; width:70px;">
					<p style="color:#006; font-weight:bold; font-size:15px;">
						<? if ( $producto['Producto']['oferta'] == 1 ) : ?>
						<?= $producto['Producto']['precio_oferta']; ?>
						<? else : ?>
						<?= $producto['Producto']['precio']; ?>
						<? endif; ?>
					</p>
				</td>
			</tr>
			<? endforeach; ?>
			<tr style="border-top:solid 1px #CCCCCC; float:left; width:520px; padding-top:20px; margin-top:20px; padding-bottom:20px;">
				<td colspan="1" style="float:left; width:100px;"></td>
				<td style="float:left; width:210px;">
				<p style="color:#003399; margin:0; font-size:14px;">Subtotal<br />IVA (19%)<br />Descuento<br />Despacho a Domicilio</p>
				<p style="color:#003399; font-weight:bold; margin:15px 0 0; font-size:16px;">TOTAL</p>
				</td>
				<td colspan="1" style="float:left; width:100px;"></td>
				<td style="float:left; width:70px;">
				<p style="color:#003399; margin:0; font-size:14px;"><?= $compra['Compra']['subtotal']; ?><br /><?= $compra['Compra']['iva']; ?><br />-<?= ($compra['Compra']['subtotal'] - $compra['Compra']['total']); ?><br />$<?= $compra['Compra']['valor_despacho']; ?></p>
				<p style="color:#003399; font-weight:bold; margin:15px 0 0; font-size:16px;"><?= $compra['Compra']['total']; ?></p>
				</td>
			</tr>
		</table>
	</tr>
</table>