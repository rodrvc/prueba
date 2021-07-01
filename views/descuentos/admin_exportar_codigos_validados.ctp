<?
header('Content-Type: application/force-download');
header('Content-Disposition: attachment; filename=codigos-validados.xls');
header('Content-Transfer-Encoding: binary');
?>
<table>
	<tr>
		<td colspan="6" style="background-color: #bebebe; border: 1px solid #000; border-bottom: 0; text-align: center;"><b>DATOS DESCUENTO</b></td>
		<td colspan="3" style="background-color: #bebebe; border: 1px solid #000; border-bottom: 0; text-align: center;"><b>DATOS CLIENTE</b></td>
	</tr>
	<tr>
		<td style="background-color: #bebebe; border: 1px solid #000; border-right: 0; border-top: 0;"><b>Descuento</b></td>
		<td style="background-color: #bebebe; border: 1px solid #000; border-right: 0; border-top: 0;"><b>Codigo</b></td>

		<td style="background-color: #bebebe; border: 1px solid #000; border-right: 0; border-left: 0; border-top: 0;"><b>Monto</b></td>
		<td style="background-color: #bebebe; border: 1px solid #000; border-right: 0; border-left: 0; border-top: 0;"><b>Tipo</b></td>
		<td style="background-color: #bebebe; border: 1px solid #000; border-right: 0; border-left: 0; border-top: 0;"><b>Fecha Cobrado</b></td>
		<td style="background-color: #bebebe; border: 1px solid #000; border-left: 0; border-top: 0;"><b>Tienda</b></td>
		<td style="background-color: #bebebe; border: 1px solid #000; border-right: 0; border-left: 0; border-top: 0;"><b>Nombre</b></td>
		<td style="background-color: #bebebe; border: 1px solid #000; border-right: 0; border-left: 0; border-top: 0;"><b>Rut</b></td>
		<td style="background-color: #bebebe; border: 1px solid #000; border-left: 0; border-top: 0;"><b>Telefono</b></td>
	</tr>
	<? if (isset($descuentos) && $descuentos) : ?>
		<? foreach ($descuentos as $descuento) : ?>
		<tr>
			<td style="text-align: left;"><?= utf8_decode($descuento['Descuento']['nombre']); ?></td>
			<td style="text-align: left;"><?= utf8_decode($descuento['Descuento']['codigo']); ?></td>

			<td style="text-align: right;">
				<?
					$valor = $this->Shapeups->moneda($descuento['Descuento']['descuento']);
					if (isset($descuento['Descuento']['tipo']) && $descuento['Descuento']['tipo'] == 'POR')
					{
						$valor = $descuento['Descuento']['descuento'].'%';
					}
				?>
				<?= $valor; ?>
			</td>
			<td style="text-align: left;">
				<?
					$valor = 'WEB';
					if (isset($descuento['Descuento']['web_tienda']) && $descuento['Descuento']['web_tienda'] == 1)
					{
						$valor = 'TIENDA';
					}
					elseif (isset($descuento['Descuento']['web_tienda']) && $descuento['Descuento']['web_tienda'] == 2)
					{
						$valor = 'WEB/TIENDA';
					}
				?>
				<?= $valor; ?>
			</td>
			<td style="text-align: right;"><?= date('d-m-Y', strtotime($descuento['ClientesTienda']['created'])); ?></td>
			<td style="text-align: left;"><?= utf8_decode(strtoupper($descuento['Administrador']['nombre'])); ?></td>
			<td style="text-align: left;"><?= utf8_decode(strtoupper($descuento['ClientesTienda']['nombre'])); ?></td>
			<td style="text-align: right;"><?= $descuento['ClientesTienda']['rut']; ?></td>
			<td style="text-align: right;"><?= $descuento['ClientesTienda']['telefono']; ?></td>
		</tr>
		<? endforeach; ?>
	<? endif; ?>
</table>