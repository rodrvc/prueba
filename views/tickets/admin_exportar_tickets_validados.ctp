<?
header('Content-Type: application/force-download');
header('Content-Disposition: attachment; filename=Tickets-Cambiados.xls');
header('Content-Transfer-Encoding: binary');
?>
<table>
	<tr>
		<td colspan="4" style="background-color: #bebebe; border: 1px solid #000; border-bottom: 0; text-align: center;"><b>DATOS Ticket</b></td>
		<td colspan="3" style="background-color: #bebebe; border: 1px solid #000; border-bottom: 0; text-align: center;"><b>DATOS Producto Anterior</b></td>
		<td colspan="2" style="background-color: #bebebe; border: 1px solid #000; border-bottom: 0; text-align: center;"><b>DATOS CLIENTE</b></td>
		<td colspan="3" style="background-color: #bebebe; border: 1px solid #000; border-bottom: 0; text-align: center;"><b>DATOS Producto Nuevo</b></td>
		<td colspan="2" style="background-color: #bebebe; border: 1px solid #000; border-bottom: 0; text-align: center;"><b>DATOS Cambio</b></td>

	</tr>
	<tr>
		<td style="background-color: #bebebe; border: 1px solid #000;  "><b>Codigo</b></td>
		<td style="background-color: #bebebe; border: 1px solid #000; border-right: 0; "><b>Numero Guia</b></td>
		<td style="background-color: #bebebe; border: 1px solid #000; border-right: 0; "><b>Fecha Guia</b></td>
		<td style="background-color: #bebebe; border: 1px solid #000; border-right: 0; "><b>Uilizado</b></td>
		<td style="background-color: #bebebe; border: 1px solid #000; border-right: 0;  "><b>Estilo</b></td>
		<td style="background-color: #bebebe; border: 1px solid #000; border-right: 0;  "><b>Color</b></td>
		<td style="background-color: #bebebe; border: 1px solid #000; border-right: 0;  "><b>Talla</b></td>


		<td style="background-color: #bebebe; border: 1px solid #000; border-right: 0;  "><b>Nombre</b></td>
		<td style="background-color: #bebebe; border: 1px solid #000; border-right: 0;  "><b>Rut</b></td>

		<td style="background-color: #bebebe; border: 1px solid #000; border-right: 0;  "><b>Estilo</b></td>
		<td style="background-color: #bebebe; border: 1px solid #000; border-right: 0;  "><b>Color</b></td>
		<td style="background-color: #bebebe; border: 1px solid #000; border-right: 0;  b"><b>Talla</b></td>

		<td style="background-color: #bebebe; border: 1px solid #000; border-right: 0;  "><b>Cambiado por</b></td>
		<td style="background-color: #bebebe; border: 1px solid #000;   "><b>Fecha Cambio</b></td>
	</tr>
	<? if (isset($tickets) && $tickets) : ?>
		<? foreach ($tickets as $ticket) : ?>
		<tr>
			<td style="text-align: left;"><?= utf8_decode($ticket['Ticket']['codigo']); ?></td>
			<td style="text-align: left;"><?= utf8_decode($ticket['Ticket']['numero_guia']); ?></td>
			<td style="text-align: left;"><?= utf8_decode($ticket['Ticket']['fecha_guia']); ?></td>
			<td style="text-align: left;"><?= utf8_decode(($ticket['Ticket']['estado'] == 1) ? 'Si' : 'No'); ?></td>

			<td style="text-align: left;"><?= utf8_decode($ticket['Ticket']['codigo_producto']); ?></td>
			<td style="text-align: left;"><?= utf8_decode($ticket['Ticket']['color']); ?></td>
			<td style="text-align: left;"><?= utf8_decode($ticket['Ticket']['talla']); ?></td>

			<td style="text-align: left;"><?= utf8_decode($ticket['Ticket']['nombre']); ?></td>
			<td style="text-align: left;"><?= utf8_decode($ticket['Ticket']['rut']); ?></td>


			<td style="text-align: left;"><?= utf8_decode($ticket['Ticket']['codigo_producto_nuevo']); ?></td>
			<td style="text-align: left;"><?= utf8_decode($ticket['Ticket']['color_nuevo']); ?></td>
			<td style="text-align: left;"><?= utf8_decode($ticket['Ticket']['talla_nuevo']); ?></td>

			<td style="text-align: left;"><?= utf8_decode($ticket['Administrador']['nombre']); ?></td>
			<td style="text-align: left;"><?= utf8_decode($ticket['Ticket']['created']); ?></td>
		</tr>
		<? endforeach; ?>
	<? endif; ?>
</table>