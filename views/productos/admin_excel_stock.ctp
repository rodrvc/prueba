<?
header('Content-Type: application/force-download');
header('Content-Disposition: attachment; filename=productos-stock.xls');
header('Content-Transfer-Encoding: binary');
?>
<table>
	<tr>
		<th>ID</th>
		<th>Nombre</th>
		<th>Codigo</th>
		<th>Cod. Color</th>
		<th>Categoria</th>
		<th>Talla</th>
		<th>Cantidad</th>
	</tr>
	<? if (isset($productos) && $productos) : ?>
		<? foreach ($productos as $producto) : ?>
			<? if (isset($producto['Talla']) && $producto['Talla']) : ?>
				<? foreach ($producto['Talla'] as $talla) : ?>
				<tr>
					<td><?= $producto['Producto']['id']; ?></td>
					<td><?= utf8_decode($producto['Producto']['nombre']); ?></td>
					<td><?= utf8_decode($producto['Producto']['codigo']); ?></td>
					<td><?= utf8_decode($producto['Color']['codigo']); ?></td>
					<td><?= utf8_decode($producto['Categoria']['nombre']); ?></td>
					<td><?= $talla['talla']; ?></td>
					<td><?= $talla['cantidad']; ?></td>
				</tr>
				<? endforeach; ?>
			<? else : ?>
			<tr>
				<td><?= $producto['Producto']['id']; ?></td>
				<td><?= utf8_decode($producto['Producto']['nombre']); ?></td>
				<td><?= utf8_decode($producto['Producto']['codigo']); ?></td>
				<td><?= utf8_decode($producto['Color']['codigo']); ?></td>
				<td><?= utf8_decode($producto['Categoria']['nombre']); ?></td>
				<td colspan="2" style="text-align: center;">SIN STOCK</td>
			</tr>
			<? endif; ?>
		<? endforeach; ?>
	<? endif; ?>
</table>