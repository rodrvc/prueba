<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Skechers</title>
<style media="all" type="text/css">
body {
	background-color: #fff;
	margin: 0px;
	padding: 0px;
}
#wrap {
	width: 600px;
	margin-top: 0px;
	margin-right: auto;
	margin-left: auto;
	background-color:#333366
}
p { 
	font-size:14px;
	margin-left:10px;
	margin-right:10px;
	font-weight: 500;
	text-align:center;
	color:#FFF;
	font:Arial, Helvetica, sans-serif;

}

h1 {
	font-family:Arial, Helvetica, sans-serif;
	font-size: 19px;
	line-height: 110%;
	font-weight: bold;
	color: #fff;
	text-align: center;
	margin-top: 2px;
	margin-bottom: 4px;
}
a {color:#ffcc00; 
   font-weight:bold;
}
 
#cupon { margin-top:20px;
		 margin-left:15px;
		 margin-right:15px;
		 font-size:10px;
		 color:#FFF;
		 font:Arial, Helvetica, sans-serif;
		 text-align:center;
	
}
</style>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" bgcolor="#FFFFFF">
	<div id="wrap">
		<table id="Tabla_01" width="600" height="486" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="600" height="486" colspan="11">
					<a href="http://store.skechers-chile.cl/">
						<img src="http://store.skechers-chile.cl/img/btn1/email-<?= $email_btn1['Premio']['tipo']; ?>.jpg"  alt="xxxxx" border="0" style="display:block" width="600" height="486">
					</a>
				</td>
			</tr> 
		</table>
		<table width="600" height="330" border="0" cellpadding="0" cellspacing="0" bgcolor="#666699">
			<tr>
				<td width="600" height="285" style="text-align: center;">
					<h1 style="color:#ffcc00; text-align: center;"><?= $email_btn1['Usuario']['nombre'] . ' ' . $email_btn1['Usuario']['apellido_paterno']; ?></h1>
					<? if ( isset($email_btn1['Premio']['tipo']) && ( $email_btn1['Premio']['tipo'] == 1 || $email_btn1['Premio']['tipo'] == 2 || $email_btn1['Premio']['tipo'] == 3 )) : ?>
						<p align="center">
							<font face="Arial" color="#FFFFFF">
								En las pr??ximas 72 horas h??biles te contactaremos para<br />
								coordinar la entrega de tu premio, a los siguientes datos:<br /><br />
								Nombre: <?= $email_btn1['Usuario']['nombre'] . ' ' . $email_btn1['Usuario']['apellido_paterno']; ?><br />
								<? if ( isset($email_btn1['Usuario']['rut']) && $email_btn1['Usuario']['rut'] ) : ?>
								Rut: <?= $email_btn1['Usuario']['rut']; ?><br />
								<? endif; ?>
								<? if ( isset($email_btn1['Usuario']['telefono']) && $email_btn1['Usuario']['telefono'] ) : ?>
								Fono: <?= $email_btn1['Usuario']['telefono']; ?><br />
								<? endif; ?>
								Este es tu c??digo de seguridad:<br />
								<span style="color:#ffcc00; font-weight:bold"><?= $email_btn1['Premio']['codigo_premio']; ?></span><br><br />
								<span style="color:#ffcc00; font-weight:bold">????An??talo!!</span> Te lo solicitaremos al comunicarnos contigo.<br />
								No olvides que tu maleta ya est?? participando por el viaje a NY
							</font>
						</p>
					<? elseif ( isset($email_btn1['Premio']['tipo']) && $email_btn1['Premio']['tipo'] == 9 ) : ?>
						<p align="center" style="color: #FFFFFF;">
							<font face="Arial" color="#FFFFFF">
								<span style="color:#ffcc00; font-weight:bold">Eres el afortunado</span> de venir a participar en vivo junto a otros dos<br />
								participantes el d??a Lunes 3 de Diciembre a las 17:00 en Skechers Costanera, donde cada uno<br />
								tendr?? la opci??n de elegir la maleta que quiera para llevarte as??, uno de los tres  premios.<br />
								Si tienes suerte, el que seleccionaste <span style="color:#ffcc00; font-weight:bold">??Ser?? tuyo!</span><br /><br />
								El d??a viernes 30 de Noviembre durante el transcurso del d??a te contactaremos <br />
								para coordinar como podr??s <span style="color:#ffcc00; font-weight:bold">venir a participar</span>.<br /><br />
								Si no eres de Santiago <span style="color:#ffcc00; font-weight:bold">no te preocupes</span>, podr??s participar de <span style="color:#ffcc00; font-weight:bold">forma remota</span>.<br /><br />
								Nombre: <?= $email_btn1['Usuario']['nombre'] . ' ' . $email_btn1['Usuario']['apellido_paterno']; ?><br />
								<? if ( isset($email_btn1['Usuario']['rut']) && $email_btn1['Usuario']['rut'] ) : ?>
								Rut: <?= $email_btn1['Usuario']['rut']; ?><br />
								<? endif; ?>
								<? if ( isset($email_btn1['Usuario']['telefono']) && $email_btn1['Usuario']['telefono'] ) : ?>
								Fono: <?= $email_btn1['Usuario']['telefono']; ?><br />
								<? endif; ?>
							</font>
						</p>
					<? else : ?>
						<p align="center">
							<font face="Arial" color="#FFFFFF">
								<span style="color:#ffcc00; font-weight:bold">Ingresa</span> ahora a nuestra tienda online <span style="color:#ffcc00; font-weight:bold">(www.skechers-chile.cl)</span> , elige las <br />
								Skechers que m??s te gusten y haz valer tu descuento.
								<br /><br />
								Nombre: <?= $email_btn1['Usuario']['nombre'] . ' ' . $email_btn1['Usuario']['apellido_paterno']; ?><br />
								<? if ( isset($email_btn1['Usuario']['rut']) && $email_btn1['Usuario']['rut'] ) : ?>
								Rut: <?= $email_btn1['Usuario']['rut']; ?><br />
								<? endif; ?>
								<? if ( isset($email_btn1['Usuario']['telefono']) && $email_btn1['Usuario']['telefono'] ) : ?>
								Fono: <?= $email_btn1['Usuario']['telefono']; ?><br />
								<? endif; ?>
								Este es tu c??digo de seguridad:<br />
								<span style="color:#ffcc00; font-weight:bold"><?= $email_btn1['Premio']['codigo_premio']; ?></span><br><br />
								* Si no sabes c??mo cobrar tu descuento, ingresa directamente <span style="color:#ffcc00; font-weight:bold"><a href="http://store.skechers-chile.cl/">ac??</a>.</span><br />
								No olvides que tu maleta ya est?? participando por el viaje a NY
							</font>
						</p>
						<div id="cupon">
							Cup??n v??lido hasta el 30 de Noviembre de 2012. Los premios son canjeables solamente en la colecci??n urbana del cat??logo Hombre/Mujer/Nino/Ni??a de la tienda online ??y excluye categor??a Performance: ??(Shape Ups, SRR, SRT , Toners, Tone Ups, S2lite, ProSpeed, Pro TR, Pro Resistance, Surge, Live, Run, Evolution, Go Run, Go Train, Go Trail, Go Ride, Go Walk). No acumulable con otras promociones. No acumulables entre si. No se puede canjear en Tiendas Skechers ni Distribuidores.??
						</div><!-- cierre cupon-->
					<? endif; ?>
				</td>
			</tr>
		</table>
		<table>
			<tr align="left">
				<td width="16" height="16">
					<a href="http://store.skechers-chile.cl/" target="_blank" >
						<img src="http://store.skechers-chile.cl/img/btn1/ico_facebook.png" width="16" height="16" border="0" alt="Facebook" align="middle" style="display:block" />
					</a>
				</td>
				<td width="16" height="16">
					<a href="http://store.skechers-chile.cl/" target="_blank" >
						<img src="http://store.skechers-chile.cl/img/btn1/ico_twitter.png" width="16" height="16" border="0" alt="Twitter" align="middle" style="display:block" />
					</a>
				</td>
			</tr>
		</table>
	</div><!-- cierre wrap-->
</body>
</html>
