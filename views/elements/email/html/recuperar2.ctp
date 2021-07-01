<table width="100%" border="0" cellspacing="0" style="padding-bottom:15px; height:auto; float:left;">
	<tr>
		<td style="width:575px; height:78px; float:left;">
			<img alt="" src="http://store.skechers-chile.cl/img/mailing/mailing_catss_despacho.png">
		</td>
	</tr>
	<tr style="background-color:#FFF; float:left; width:auto; padding:10px 25px; background-image:url('http://store.skechers-chile.cl/img/mailing/borde-fondo.jpg'); background-position:right bottom; background-repeat:no-repeat; height:140px;">
		<td colspan="1" style="width:auto; float:left; margin-bottom:15px;">
			<p style="color:#7d5179; font-weight:bold; margin:0 0 10px 0; float:left; width:auto; font-size:15px;">
				Estimado(a) <?= $usuario['Usuario']['nombre']; ?>,</p>
			<p style="color:#333333; float:left; margin:10px 0 0 0; width:520px; font-size:14px;">
				Para recuperar tu contraseña, debes ingresar al siguiente link dentro del día de hoy:<br />
				<a href="http://store.skechers-chile.cl/usuarios/confirma_recuperar/<?= $codigo; ?>" style="font-weight:bold;">Recuperar contraseña</a><br /><br />
				Ahí debes ingresar tu e-mail y el codigo que se encuentra a continuacion:<br /><br />
				<b><?= $codigo; ?></b>
			</p>
		</td>
	</tr>
</table>