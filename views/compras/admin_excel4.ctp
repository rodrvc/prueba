<?
header('Content-Type: application/force-download');
header('Content-Disposition: attachment; filename=Ventas_Retail.xls');
header('Content-Transfer-Encoding: binary'); 
?>
<table>
	<tr>
		<th><?= utf8_decode('Nº Compra'); ?></th>
		<th>Dia Compra</th>
		<th>Mes Compra</th>
		<th><?= utf8_decode('Año Compra'); ?></th>
		<th>Comuna</th>
		<th>Region</th>
		<th>Sexo</th>
		<th>Codigo Producto</th>
		<th>Codigo</th>
		<th>Codigo Color</th>
		<th>Descripcion</th>
		<th>Div</th>
		<th>Showroom</th>
		<th>Talla</th>
		<th>Precio </th>
		<th>Precio Oferta</th>
		<th>Precio Venta Real</th>
		<th>Categoria</th>
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
				?>

		<tr>
			<td><?= $venta['Compra']['id']; ?></td>
			<td><?= date('d', strtotime($venta['Compra']['created'])); ?></td>
			<td><?= date('m', strtotime($venta['Compra']['created'])); ?></td>
			<td><?= date('Y', strtotime($venta['Compra']['created'])); ?></td>
			<td><?= utf8_decode($venta['Comuna']['nombre']); ?></td>
			<td><?= utf8_decode($venta['Region']['nombre']); ?></td>
			<td>	<?
					if (isset($venta['Usuario']['sexo_id']))
					{
						if ($venta['Usuario']['sexo_id'] == 1)
							echo 'masculino';
						elseif ($venta['Usuario']['sexo_id'] == 2)
							echo 'femenino';
					}
				?>
			</td>

			<td><?= $venta['Producto']['codigo_completo']; ?></td>
			<td><?= $venta['Producto']['codigo']; ?></td>
			<td><?= $venta['Color']['codigo']; ?></td>
			<td><?= $venta['Producto']['nombre']; ?></td>
			<td><?= $venta['Producto']['division']; ?></td>
			<td><?= $venta['Producto']['showroom']; ?></td>
			<td><?= $venta['ProductosCompra']['talla']; ?></td>
			<td><?= $venta['Producto']['precio']; ?></td>
			<td><?= $venta['Producto']['precio_oferta']; ?></td>
			<td><?= $venta['ProductosCompra']['valor']; ?></td>
			<td><?= utf8_decode($venta['ProductosCompra']['categoria']); ?></td>
				<td>	<?
					if (isset($venta['Compra']['ip']))
					{
						$pos = strpos($venta['Compra']['ip'], '.');
						if ($pos === false )
							echo $venta['Compra']['ip'];
						else 
							echo 'Ecommerce';
					}
				?>
			</td>
		</tr>
	<? endforeach; ?>
</table>