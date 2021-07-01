<?
header('Content-Type: application/force-download');
header('Content-Disposition: attachment; filename=Ventas.xls');
header('Content-Transfer-Encoding: binary'); 
?>
<table>
	<tr>
		<th><?= utf8_decode('Nº Compra'); ?></th>
		<th>Dia Compra</th>
		<th>Mes Compra</th>
		<th><?= utf8_decode('Año Compra'); ?></th>
		<th>Total</th>
		<th>Codigo Producto</th>
		<th>Categoria</th>
		<th>Precio Lista</th>
		<th>Precio Venta Real</th>
		<th>Dolar</th>
		<th>Canal</th>

		
	</tr>
	<? foreach( $ventas as $venta ) : ?>
	<?php
				$precio_venta = $venta['ProductosCompra']['valor'];
				if ($venta['ProductosCompra']['descuento_id'])
				{

					$descontar = 0;
					if ($venta['Descuento']['tipo'] == 'POR')
					{
						if ($venta['Descuento']['descuento'])
						{
							$descontar = ($venta['ProductosCompra']['valor'] * $venta['Descuento']['descuento']) / 100;
												
							if ( ($descontar % 10) > 0 )// redondea descuento
								$descontar = (((int)($descontar/10))*10)+10;
							else
								$descontar = ((int)($descontar/10))*10;
						}
					}
					elseif ($venta['Descuento']['descuento'])
					{
						$descontar = $venta['Descuento']['descuento'];
					}
					$precio_venta = $precio_venta-$descontar;
					if ($precio_venta <= 0)
						$precio_venta = 0;
					
					//prx($precio_venta);

				}
				$precio_venta = number_format($precio_venta/1.19 , 0, ",", "");
				$precio_dolar =number_format(($precio_venta *0.0013) , 2, ",", "");
				?>

		<tr>
			<td><?= $venta['Compra']['id']; ?></td>
			<td><?= date('d', strtotime($venta['Compra']['created'])); ?></td>
			<td><?= date('m', strtotime($venta['Compra']['created'])); ?></td>
			<td><?= date('Y', strtotime($venta['Compra']['created'])); ?></td>
			<td><?= $venta['Compra']['total']; ?></td>
			<td><?= $venta['Producto']['codigo_completo']; ?></td>
			<td><?= utf8_decode($venta['Categoria']['nombre']); ?></td>
			<td><?= number_format($venta['Producto']['precio']/1.19, 0, ",", ""); ?></td>
			<td><?= $precio_venta;?></td>
			<td><?= ($venta['Compra']['local'])?$venta['Compra']['ip']:'web';?></td>
			<td><?= $precio_dolar;?></td>
			
		</tr>
	<? endforeach; ?>
</table>