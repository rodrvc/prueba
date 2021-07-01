<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<script>
		window.print();
		setTimeout(function()
		{
			window.close();
		}, 5000);
		
</script>
</head>
<body>
	<table width="900" border="1" style="font-size: 25px;" cellspacing="5" cellpadding="7">
		<tr>
			<td width="150">
				<b style="font-size: 18px;">Nombre: </b>
			</td>
			<td colspan="3">
				<?= utf8_decode($compra['Usuario']['nombre']. " " .$compra['Usuario']['apellido_paterno']. " " .$compra['Usuario']['apellido_materno']); ?>
			</td>
		</tr>
		<tr>
			<td>
				<b style="font-size: 18px;">Rut: </b>
			</td>
			<td colspan="3">
				<?= $compra['Usuario']['rut']; ?>
			</td>
		</tr>
		<tr>
			<td>
				<b style="font-size: 18px;">Direccion: </b>
			</td>
			<td colspan="3">
				<?= utf8_decode($compra['Despacho']['Direccion']['calle'] . ' #' . $compra['Despacho']['Direccion']['numero']); ?>
				<? if ( $compra['Despacho']['Direccion']['depto'] ) : ?>
				 - <b style="font-size: 18px;">Depto: </b><?= $compra['Despacho']['Direccion']['depto']; ?>
				<? endif; ?>
			</td>
		</tr>
		<tr>
			<td width="150">
				<b style="font-size: 18px;">Comuna: </b>
			</td>
			<td width="300">
				<?= utf8_decode($compra['Despacho']['Direccion']['Comuna']['nombre']); ?>
			</td>
			<td width="150">
				<b style="font-size: 18px;">Region: </b>
			</td>
			<td width="300">
				<?= utf8_decode($compra['Despacho']['Direccion']['Region']['nombre']); ?>
			</td>
		</tr>
		<tr>
			<td>
				<b style="font-size: 18px;">Telefono: </b>
			</td>
			<td>
				<?= $compra['Despacho']['Direccion']['telefono']; ?>
			</td>
			<td>
				<b style="font-size: 18px;">Celular: </b>
			</td>
			<td>
				<?= $compra['Despacho']['Direccion']['celular']; ?>
			</td>
		</tr>
		<tr>
			<td>
				<b style="font-size: 18px;">Codigo Postal: </b>
			</td>
			<td colspan="3">
				&nbsp;
			</td>
		</tr>
	</table>
</body>
</html>
