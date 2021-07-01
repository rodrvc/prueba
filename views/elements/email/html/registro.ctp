<table width="100%" border="0" cellspacing="0" style="padding-bottom:15px; height:auto; float:left;">
	<tr>
		<td style="width:575px; height:78px; float:left;">
			<img alt="" src="http://store.skechers-chile.cl/img/mailing/mailing_catss_despacho.png">
		</td>
	</tr>
	<tr style="background-color:#FFF; float:left; width:auto; padding:10px 25px; background-image:url('http://store.skechers-chile.cl/img/mailing/borde-fondo.jpg'); background-position:right bottom; background-repeat:no-repeat; height:140px;">
		<td colspan="1" style="width:auto; float:left; margin-bottom:15px;">
			<p style="color:#000; font-weight:bold; margin:0 0 10px 0; float:left; width:auto; font-size:15px;">Estimado(a) <?= $this->data['Usuario']['nombre']; ?>,</p>
			<p style="color:#333; float:left; margin:0; width:520px; font-size:15px;">¡YA ESTÁS <b style="font-weight:bold;">REGISTRAD@</b> EN NUESTRA TIENDA ONLINE!</p>
			<p style="color:#333333; float:left; margin:10px 0 0 0; width:520px; font-size:14px;">
				Ahora puedes comprar tus Skechers en línea en <a href="http://www.skechers.cl" style="font-weight:bold;">skechers.cl</a><br /><br />
				Te recomendamos conservar este correo en caso que quieras reingresar y hayas olvidado tu medio o clave de ingreso.
			</p>
		</td>
	</tr>
	<tr style="width:auto; float:left; background-color:#FFF; padding:10px 25px 25px;">
		<td colspan="1" style="width:auto; float:left;">
			<p style="color:#333; font-weight:bold; font-size:15px; margin:0 0 10px;">
				Tus datos de registro son:
			</p>
			<p style="color:#666; float:left; width:520px; margin:0 0 5px;">
				<b style="font-weight:bold;">Nombre:</b> <?= $this->data['Usuario']['nombre'] . ' ' . $this->data['Usuario']['apellido_paterno'] . ' ' . $this->data['Usuario']['apellido_materno'];?>
			</p>
			<p style="color:#666; float:left; width:520px; margin:0 0 5px;">
				<b style="font-weight:bold;">Clave:</b> <?= $this->data['Usuario']['repetir_clave']; ?>
			</p>
			<p style="color:#666; float:left; width:520px; margin:0 0 5px;">
				<b style="font-weight:bold;">E-Mail:</b> <?= $this->data['Usuario']['email']; ?>
			</p>
		</td>
	</tr>
</table>