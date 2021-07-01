<?
header('Content-Type: application/force-download');
header('Content-Disposition: attachment; filename=Estado_Ventas.xls');
header('Content-Transfer-Encoding: binary'); 
?>
<table>
	<tr>
		<th><?= utf8_decode('Nº Compra'); ?></th>
		<th>Dia Compra</th>
		<th>Mes Compra</th>
		<th><?= utf8_decode('Año Compra'); ?></th>
		<th>Boleta</th>
		<th>PDF Boleta Producto</th>
		<th>Picking</th>
		<th>Numero Interno</th>
		<th>Despacho</th>
		<th>Canal</th>


		
	</tr>
	<? foreach( $ventas as $venta ) : ?>
	

		<tr>
			<td><?= $venta['Compra']['id']; ?></td>
			<td><?= date('d', strtotime($venta['Compra']['created'])); ?></td>
			<td><?= date('m', strtotime($venta['Compra']['created'])); ?></td>
			<td><?= date('Y', strtotime($venta['Compra']['created'])); ?></td>
			<td><?= $venta['Compra']['boleta']; ?></td>
			<td><?= $venta['Compra']['boleta_pdf']; ?></td>
			<td><?= $venta['Compra']['picking_number']; ?></td>
			<td><?= $venta['Compra']['numId']; ?></td>
			<td><?= $venta['Compra']['cod_despacho']; ?></td>
			<?php if($venta['Compra']['ip'] == 'dafiti' || $venta['Compra']['ip'] == 'mercadolibre' ): ?>
				<td><?= $venta['Compra']['ip']; ?></td>
			<?php else: ?>
				<td>Ecommerce</td>
			<?php endif; ?>


			
		</tr>
	<? endforeach; ?>
</table>