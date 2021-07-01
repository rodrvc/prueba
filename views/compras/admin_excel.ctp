<?
header('Content-Type: application/force-download');
header('Content-Disposition: attachment; filename=Ventas.xls');
header('Content-Transfer-Encoding: binary'); 
?>
<table>
	<tr>
		<th><?= utf8_decode('Nº Compra'); ?></th>
		<th>Picking Number</th>
		<th>ID</th>
		<th>Dia Compra</th>
		<th>Mes Compra</th>
		<th><?= utf8_decode('Año Compra'); ?></th>
		<th>Fecha Pago</th>
	<?php if(isset($imprimir['pago'])) : ?>
		<th><?= utf8_decode('Nº Tarjeta'); ?></th>
		<th><?= utf8_decode('Nº Transaccion'); ?></th>
		<th>Cod. Autorizacion</th>
		<th>TipoPago</th>
		<th>Pagado</th>
	<?php endif; ?>
		<th>Despachado</th>
		<th>Boleta</th>
		<th>Fecha Despacho</th>
		<th>Cod. Despacho</th>
		<th>Rural</th>
		<th>Direccion Rural</th>
		<?php if(isset($imprimir['direccion'])) : ?>

		<th>Direccion</th>
		<th>Comuna</th>
		<th>Region</th>
		<?php endif; ?>

		<th>Subtotal</th>
		<th>Neto</th>
		<th>IVA</th>
		<th>Total</th>
		<th>Descuento</th>
		<th>Enviado</th>
		<?php if(isset($imprimir['usuario'])) : ?>

		<th>Nombre</th>
		<th>Apellido Paterno</th>
		<th>Apellido Materno</th>
		<th>Sexo</th>
		<th>Estado Civil</th>
		<th>Rut</th>
		<th>email</th>
		<th>Fecha de Nacimiento</th>
		<?php endif; ?>
		<?php if(isset($imprimir['direccion'])) : ?>

		<th>Telefono</th>
		<th>Celular</th>
		<?php endif; ?>
		<?php if(isset($imprimir['productos'])) : ?>

		<th>Codigo Producto</th>
		<th>Codigo</th>
		<th>Codigo Color</th>
		<th><?= utf8_decode('Descripción'); ?></th>
		<th>ID Producto</th>
		<th>DIV</th>
		<th>SHOWROOM</th>
		<th>Cantidad</th>
		<th>Talla</th>
		<th>Precio</th>
		<th>Precio Oferta</th>
		<th>Real Pagado</th>
		<th>Categoria</th>
		<th>Categoria Pasada</th>
		<th>Nombre Descuento</th>
		<th>Codigo Descuento</th>
		<th>Monto Descuento</th>
		<?php endif; ?>
		<th>IP</th>
	</tr>
	<? foreach( $ventas as $venta ) : ?>
		<tr>
			<td><?= $venta['Compra']['id']; ?></td>
			<td><?= (isset($venta['Compra']['picking_number'])) ? strtoupper(utf8_decode($venta['Compra']['picking_number'])) : '&nbsp;'; ?></td>
			<td><?= (isset($venta['Compra']['numId'])) ? strtoupper(utf8_decode($venta['Compra']['numId'])) : '&nbsp;'; ?></td>
			<td><?= date('d', strtotime($venta['Compra']['created'])); ?></td>
			<td><?= date('m', strtotime($venta['Compra']['created'])); ?></td>
			<td><?= date('Y', strtotime($venta['Compra']['created'])); ?></td>
			<?php if(isset($imprimir['pago'])) : ?>
			<td><?= $venta['Pago']['fecha']; ?></td>
			<td><?= $venta['Pago']['numeroTarjeta']; ?></td>
			<td><?= $venta['Pago']['codigo']; ?></td>
			<td><?= $venta['Pago']['codAutorizacion']; ?></td>
			<td><?= $venta['Pago']['tipoPago']; ?></td>
		<?php endif; ?>
			<td>
				<?
					if (in_array($venta['Compra']['estado'],array(1,3,4,5)))
						echo 'si';
					elseif ($venta['Compra']['estado'] == 2)
						echo 'no';
				?>
				&nbsp;
			</td>
			<td><?= ( isset($venta['Compra']['despachado']) && $venta['Compra']['despachado'] == 1 ) ? 'si' : 'no'; ?></td>
			<td><?= $venta['Compra']['boleta']; ?></td>
			<td><?= $venta['Compra']['fecha_enviado']; ?></td>
			<td><?= (string)$venta['Compra']['cod_despacho']; ?>&nbsp;</td>
			<td><?= ( isset($venta['Compra']['rural']) && $venta['Compra']['rural'] == 1 ) ? 'si' : 'no'; ?></td>
			<td><?= $venta['Compra']['direccion_rural']; ?></td>
			<?php if(isset($imprimir['direccion'])) : ?>

			<td><?
					echo utf8_decode($venta['Direccion']['calle'] . ' ' . $venta['Direccion']['numero']);
					if (isset($venta['Direccion']['depto']) && $venta['Direccion']['depto'])
						echo ' depto. '.$venta['Direccion']['depto'];
				?>
			</td>
			<td><?= ( isset($venta['Comuna']['nombre']) && $venta['Comuna']['nombre'] ) ? utf8_decode($venta['Comuna']['nombre']) : '&nbsp;'; ?></td>
			<td><?= ( isset($venta['Region']['nombre']) && $venta['Region']['nombre'] ) ? utf8_decode($venta['Region']['nombre']) : '&nbsp;'; ?></td>
			<?php endif; ?>
			<?
				$subtotal = ((int)($venta['Compra']['total']/10))*10;
				$neto = ((int)(($subtotal / 1.19)/10))*10;
				$iva = $subtotal - $neto;
			?>
			<td><?= $venta['Compra']['total']; ?></td>
			<td><?= $neto; ?></td>
			<td><?= $iva; ?></td>
			<td><?= $venta['Compra']['total']; ?></td>
			<? if (false) : ?>
			<td><?= $venta['Compra']['subtotal']; ?></td>
			<td><?= $venta['Compra']['neto']; ?></td>
			<td><?= $venta['Compra']['iva']; ?></td>
			<td><?= $venta['Compra']['total']; ?></td>
			<? endif; ?>
			<td><?= (isset($venta['Descuento']['descuento']) && $venta['Descuento']['descuento']) ? 'si' : 'no'; ?></td>
			<td><?= ( isset($venta['Compra']['enviado']) && $venta['Compra']['enviado'] == 1 ) ? 'si' : 'no'; ?></td>
			<?php if(isset($imprimir['usuario'])) : ?>

			<td><?= utf8_decode($venta['Usuario']['nombre']); ?></td>
			<td><?= utf8_decode($venta['Usuario']['apellido_paterno']); ?></td>
			<td><?= utf8_decode($venta['Usuario']['apellido_materno']); ?></td>
			<td>
				<?
					if (isset($venta['Usuario']['sexo_id']))
					{
						if ($venta['Usuario']['sexo_id'] == 1)
							echo 'masculino';
						elseif ($venta['Usuario']['sexo_id'] == 2)
							echo 'femenino';
					}
				?>
				&nbsp;
			</td>
			<td>
				<?
					if (isset($venta['Usuario']['estadocivil_id']))
					{
						if ($venta['Usuario']['estadocivil_id'] == 1)
							echo 'Soltero';
						elseif ($venta['Usuario']['estadocivil_id'] == 2)
							echo 'Casado';
						elseif ($venta['Usuario']['estadocivil_id'] == 3)
							echo 'Separado';
						elseif ($venta['Usuario']['estadocivil_id'] == 4)
							echo 'Viudo';
					}
				?>
				&nbsp;
			</td>
			<td><?= $venta['Usuario']['rut']; ?></td>
			<td><?= utf8_decode($venta['Usuario']['email']); ?></td>
						<td><?= $venta['Usuario']['fecha_nacimiento']; ?></td>

			<?php endif; ?>
			<?php if(isset($imprimir['direccion'])) : ?>

			<td><?= (isset($venta['Direccion']['telefono'])) ? $venta['Direccion']['telefono'] : ''; ?></td>
			<td><?= (isset($venta['Direccion']['celular'])) ? $venta['Direccion']['celular'] : ''; ?></td>
			<?php endif;?>
			<?php if(isset($imprimir['productos'])) : ?>

			<td><?= $venta['Producto']['codigo_completo']; ?></td>
			<td><?= $venta['Producto']['codigo']; ?></td>
			<td><?= $venta['Color']['codigo']; ?></td>
			<td><?= $venta['Producto']['nombre']; ?></td>
			<td><?= $venta['Producto']['id']; ?></td>
			<td><?= (isset($venta['Producto']['division']) && $venta['Producto']['division']) ? $venta['Producto']['division']:''; ?>&nbsp;</td>
			<td><?=$venta['Producto']['showroom']; ?>&nbsp;</td>
			<td>1</td>
			<td><?= $venta['ProductosCompra']['talla']; ?></td>
			<td><?= $venta['Producto']['precio']; ?></td>
			<td><?= $venta['Producto']['precio_oferta']; ?></td>
			<td>
				<?
					if (isset($venta['Descuento']['descuento']) && $venta['Descuento']['descuento'])
					{
						if ($venta['Descuento']['tipo'] == 'POR')
						{
							$total_descuento = ($venta['ProductosCompra']['valor'] * $venta['Descuento']['descuento']) / 100;
							// redondea descuento
							if ( ($total_descuento % 10) > 0 )
								$total_descuento = (((int)($total_descuento/10))*10)+10;
							else
								$total_descuento = ((int)($total_descuento/10))*10;

							echo ($venta['ProductosCompra']['valor']-$total_descuento);
						}
						else
						{
							if ($venta['ProductosCompra']['valor'] >= $venta['Descuento']['descuento'])
								echo ($venta['ProductosCompra']['valor']-$venta['Descuento']['descuento']);
							else
								echo '0';
						}
					}
					else
					{
						echo $venta['ProductosCompra']['valor'];
					}
				?>
			</td>
			<td><?= utf8_decode($venta['ProductosCompra']['categoria']); ?></td>
			<td><?= (isset($venta['Categoria']['publico']) && $venta['Categoria']['publico']) ? 'si' : 'no'; ?></td>
			<td><?= $venta['Descuento']['nombre']; ?></td>
			<td><?= $venta['Descuento']['codigo']; ?></td>
			<td><?
					if ($venta['Descuento']['tipo'] == 'POR')
					{
						$total_descuento = ($venta['ProductosCompra']['valor'] * $venta['Descuento']['descuento']) / 100;
						// redondea descuento
						if ( ($total_descuento % 10) > 0 )
							$total_descuento = (((int)($total_descuento/10))*10)+10;
						else
							$total_descuento = ((int)($total_descuento/10))*10;

						echo $total_descuento;
					}
					else
					{
						echo $venta['Descuento']['descuento'];
					}
				?>
			</td>
		<?php endif;?>
			<td><?= (isset($venta['Compra']['ip']) && $venta['Compra']['ip']) ? $venta['Compra']['ip'] : '0'; ?></td>
			<td>
				<?
					if (isset($venta['Compra']['estado']) && in_array($venta['Compra']['estado'], array(1,2,3,4,5)))
					{
						if ($venta['Compra']['estado'] == 1)
							echo 'pagado';
						elseif ($venta['Compra']['estado'] == 2)
							echo 'anulado';
						elseif ($venta['Compra']['estado'] == 3)
							echo 'en devolucion';
						elseif ($venta['Compra']['estado'] == 4)
							echo 'devuelto';
						elseif ($venta['Compra']['estado'] == 5)
							echo 'pendiente';
					}
					else
					{
						echo 'no pagado';
					}
				?>
			</td>
		</tr>
	<? endforeach; ?>
</table>