<?
header('Content-Type: application/force-download');
header('Content-Disposition: attachment; filename=DATOS.xls');
header('Content-Transfer-Encoding: binary');
?>
<table>
	<tr>
		<th style="background-color: #023751; color: #FFF;">PRODUCTO</th>
		<th style="background-color: #023751; color: #FFF;">SERVICIO</th>
		<th style="background-color: #023751; color: #FFF;">DESTINO_COMUNA</th>
		<th style="background-color: #023751; color: #FFF;">PESO</th>
		<th style="background-color: #023751; color: #FFF;">DESTINATARIO</th>
		<th style="background-color: #023751; color: #FFF;">CALLE</th>
		<th style="background-color: #023751; color: #FFF;">NUMERO</th>
		<th style="background-color: #023751; color: #FFF;">COMPLEMENTO_DIRECCION</th>
		<th style="background-color: #023751; color: #FFF;">REFERENCIA</th>
		<th style="background-color: #023751; color: #FFF;">ALTO</th>
		<th style="background-color: #023751; color: #FFF;">ANCHO</th>
		<th style="background-color: #023751; color: #FFF;">LARGO</th>
		<th style="background-color: #023751; color: #FFF;">CARGOEMPRESA</th>
		<th style="background-color: #023751; color: #FFF;">MONTO_COBRO_COD</th>
		<th style="background-color: #023751; color: #FFF;">VALOR_DECLARO_PRODUCTO</th>
		<th style="background-color: #023751; color: #FFF;">EMAIL</th>
		<th style="background-color: #023751; color: #FFF;">ENTREGAOFICINA</th>
		<th style="background-color: #023751; color: #FFF;">INFOADICIONAL</th>
		<th style="background-color: #023751; color: #FFF;">AGRAGRUPADA</th>
		<th style="background-color: #023751; color: #FFF;">AGRTOTALPIEZAS</th>
		<th style="background-color: #023751; color: #FFF;">AGRPIRZANUMERO</th>
		<th style="background-color: #023751; color: #FFF;">EMPRESA</th>
		<th style="background-color: #023751; color: #FFF;">DESTINATARIOSECUNDARIO</th>
	</tr>
	<? foreach( $ventas as $venta ) : ?>
		<? if (isset($venta['Producto']) && $venta['Producto']) : ?>
			<? foreach ($venta['Producto'] as $producto) : ?>
			<tr>
				<td>3</td>
				<td>3</td>
				<td>
					<?= (isset($venta['Despacho']['Direccion']['Comuna']['nombre']) && $venta['Despacho']['Direccion']['Comuna']['nombre']) ? strtoupper(utf8_decode($venta['Despacho']['Direccion']['Comuna']['nombre'])) : '&nbsp;'; ?>
				</td>
				<td>1</td>
				<td>
					<?= (isset($venta['Usuario']['nombre']) && $venta['Usuario']['nombre']) ? strtoupper(utf8_decode($venta['Usuario']['nombre']. ' ' .$venta['Usuario']['apellido_paterno'])) : '&nbsp;'; ?>
				</td>
				<td>
					<?= (isset($venta['Despacho']['Direccion']['calle']) && $venta['Despacho']['Direccion']['calle']) ? strtoupper(utf8_decode($venta['Despacho']['Direccion']['calle'])) : '&nbsp;'; ?>
				</td>
				<td>
					<?= (isset($venta['Despacho']['Direccion']['numero']) && $venta['Despacho']['Direccion']['numero']) ? $venta['Despacho']['Direccion']['numero'] : '&nbsp;'; ?>
				</td>
				<td>
					<?= (isset($venta['Despacho']['Direccion']['depto']) && $venta['Despacho']['Direccion']['depto']) ? strtoupper('depto '.$venta['Despacho']['Direccion']['depto']) : '&nbsp;'; ?>
				</td>
				<td>COMPRA <?= $venta['Compra']['id']; ?></td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>
					<?= (isset($venta['Despacho']['Direccion']['otras_indicaciones']) && $venta['Despacho']['Direccion']['otras_indicaciones']) ? utf8_decode($venta['Despacho']['Direccion']['otras_indicaciones']):'&nbsp;'; ?>
				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>
					<?= (isset($venta['Usuario']['email']) && $venta['Usuario']['email']) ? $venta['Usuario']['email'] : '' ; ?>&nbsp;
				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<? endforeach; ?>
		<? endif; ?>
	<? endforeach; ?>
</table>