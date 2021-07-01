<div class="col02">
	<h1 class="titulo">Postulación</h1>
	<div class="previsualizar">
		<ul>
			<li class="extendido"><h2>Datos personales</h2></li>
			<li class="extendido"><span>Nombre:</span><?= $postulante['TrabajePostulante']['nombre']; ?>&nbsp;</li>
			<li class="extendido"><span>Apellidos :</span><?= $postulante['TrabajePostulante']['apellido_paterno'].' '.$postulante['TrabajePostulante']['apellido_materno']; ?>&nbsp;</li>
			<li class="extendido"><span>Rut:</span><?= $postulante['TrabajePostulante']['rut']; ?>&nbsp;</li>
			<li class="extendido"><span>Fecha Nacimiento:</span><?= $postulante['TrabajePostulante']['f_nacimiento']; ?>&nbsp;</li>
			<li class="extendido"><span>Nacionalidad:</span><?= $postulante['TrabajePostulante']['pais']; ?>&nbsp;</li>
			<li class="extendido"><span>Estado civil:</span><?= $postulante['TrabajePostulante']['estado_civil']; ?>&nbsp;</li>
			<li class="extendido"><span>Hijos?:</span><?= $postulante['TrabajePostulante']['hijos']; ?>&nbsp;</li>
		</ul>
	</div>
	<div class="previsualizar">
		<ul>
			<li class="extendido"><h2>Datos de contacto</h2></li>
			<li class="extendido"><span>Email:</span><?= $postulante['TrabajePostulante']['email']; ?>&nbsp;</li>
			<li><span>Telefono Fijo:</span><?= $postulante['TrabajePostulante']['fijo']; ?>&nbsp;</li>
			<li><span>Celular:</span><?= $postulante['TrabajePostulante']['celular']; ?>&nbsp;</li>
			<li class="extendido"><span>Domicilio:</span><?= $postulante['TrabajePostulante']['domicilio'].', '.$postulante['TrabajePostulante']['comuna'].' - '.$postulante['TrabajePostulante']['ciudad']; ?>&nbsp;</li>
			<li class="extendido"><span>Referencia:</span><?= $postulante['TrabajePostulante']['referencia']; ?>&nbsp;</li>
		</ul>
	</div>
	<div class="previsualizar">
		<ul>
			<li class="extendido"><h2>Datos de postulación</h2></li>
			<li class="extendido"><span>Cargo:</span><?= $postulante['TrabajePostulante']['cargo']; ?>&nbsp;</li>
			<li class="extendido"><span>Jornada:</span><?= $postulante['TrabajePostulante']['jornada']; ?>&nbsp;</li>
			<li class="extendido"><span>Fecha postulación:</span><?= $postulante['TrabajePostulante']['created']; ?>&nbsp;</li>
		</ul>
	</div>
	<?
	if ($postulante['TrabajePostulante']['turnos'])
	{
		echo '<div class="previsualizar"><ul><li class="extendido"><h2>Turnos</h2></li>';
		$turnos = json_decode($postulante['TrabajePostulante']['turnos']);
		foreach ($turnos as $turno)
		{
			echo '<li><b>'.$turno[0].':</b></li><li>'.$turno[1].'</li>';
		}
		echo '</ul></div>';
	}
	?>
	<? if ($postulante['TrabajeCarga']) : ?>
	<div class="previsualizar">
		<ul>
			<li class="extendido"><h2>Cargas</h2></li>
			<? foreach ($postulante['TrabajeCarga'] as $carga) : ?>
			<li class="extendido">&raquo; <?= $carga['carga']; ?>&nbsp;</li>
			<? endforeach; ?>
		</ul>
	</div>
	<? endif; ?>
	<? if ($postulante['TrabajeExperiencia']) : ?>
		<h2 class="subtitulo">Experiencia</h2>
		<? foreach ($postulante['TrabajeExperiencia'] as $experiencia) : ?>
			<div class="previsualizar">
				<ul>
					<li class="extendido"><h2><?= $experiencia['nombre']; ?></h2></li>
					<li class="extendido"><span>Tipo empresa:</span><?= $experiencia['tipo']; ?></li>
					<li class="extendido"><span>Cargo:</span><?= $experiencia['cargo']; ?></li>
					<li class="extendido"><span>Periodo:</span><?= $experiencia['periodo']; ?></li>
					<li class="extendido"><span>Jefe directo:</span><?= $experiencia['jefe']; ?></li>
					<li class="extendido"><span>Telefono:</span><?= $experiencia['telefono']; ?></li>
					<li class="extendido"><span>Motivo de salida:</span><?= $experiencia['salida']; ?></li>
					<li class="extendido"><span>Motivación:</span><?= $experiencia['motivacion']; ?></li>
					<li class="extendido"><span>Falimiares?</span><?= $experiencia['familiares']; ?></li>
				</ul>
			</div>
		<? endforeach; ?>
		<? if ($postulante['TrabajePariente']) : ?>
			<div class="previsualizar">
				<ul>
					<li class="extendido"><h2>Parientes</h2></li>
					<? foreach ($postulante['TrabajePariente'] as $pariente) : ?>
						<?= ($pariente == reset($postulante['TrabajePariente'])) ? '':'<li class="extendido">&nbsp;</li>'; ?>
						<li class="extendido"><span>Nombre:</span><?= $pariente['nombre']; ?></li>
						<li class="extendido"><span>Parentesco:</span><?= $pariente['parentesco']; ?></li>
						<li class="extendido"><span>Puesto:</span><?= $pariente['puesto']; ?></li>
						<li class="extendido"><span>Lugar:</span><?= $pariente['lugar']; ?></li>
					<? endforeach; ?>
				</ul>
			</div>
		<? endif; ?>
	<? endif; ?>
	<? if ($postulante['TrabajeReferencia']) : ?>
		<h2 class="subtitulo">Referencias</h2>
		<? foreach ($postulante['TrabajeReferencia'] as $referencia) : ?>
			<div class="previsualizar">
				<ul>
					<li class="extendido"><span>Nombre:</span><?= $referencia['nombre']; ?></li>
					<li class="extendido"><span>Cargo:</span><?= $referencia['cargo']; ?></li>
					<li class="extendido"><span>Empresa:</span><?= $referencia['empresa']; ?></li>
					<li class="extendido"><span>Telefono:</span><?= $referencia['telefono']; ?></li>
					<li class="extendido"><span>Tiempo:</span><?= $referencia['tiempo']; ?></li>
				</ul>
			</div>
		<? endforeach; ?>
	<? endif; ?>
</div>
