<style type="text/css" media="all">
a {
	outline: none !important;
}
a.btn-default.btn-continuar:hover, a.btn-default.btn-continuar:focus {
	background-color: #eeeeee;
	border: 1px solid #ccc;
    color: #333;
	border-radius: 6px;
    font-size: 30px;
    line-height: 1.33;
    padding: 10px 16px;
	text-transform: none;
}
</style>
<div class="jumbotron paddingparatabs">
	<div class="container">
		<h1 class="text-primary">Postulación laboral</h1>
		<p class="text-grey">Ven a trabajar con nosotros!! Se parte de nuestro éxito. <br>Al postularte en Skechers habras dado el 1° paso hacia una carrera <br>  llena de desafíos y oportunidades.</p>
	</div>
</div>
<div class="container">
    <div class="row">
		<div class="col-md-12">
			<ul id="tabTrabaje" class="nav nav-tabs nav-tabs-trabaje" role="tablist">
				<li class="active">
					<a href="#info" role="tab" data-toggle="tab" class="hidden"></a>
					<a href="#" onclick="return false;">
						<span class="fa-stack">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-inverse fa-stack-1x">1</i>
						</span>
						Información personal
					</a>
				</li>
				<li>
					<a href="#datos" role="tab" data-toggle="tab" class="hidden"></a>
					<a href="#" onclick="return false;">
						<span class="fa-stack">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-inverse fa-stack-1x">2</i>
						</span>
						Datos generales
					</a>
				</li>
				<li>
					<a href="#xp" role="tab" data-toggle="tab" class="hidden"></a>
					<a href="#" onclick="return false;">
						<span class="fa-stack">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-inverse fa-stack-1x">3</i>
						</span>
						Experiencía laboral
					</a>
				</li>
			</ul>
		</div>
		<div class="col-md-8">
			<div class="alert alert-warning alert-trabaje">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil, eveniet! Facilis aperiam vitae eveniet facere maiores doloremque rerum consequuntur nobis, quae est, reiciendis recusandae incidunt eligendi error quasi voluptate obcaecati.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt repudiandae nam minima architecto temporibus illum, laboriosam, obcaecati dignissimos. Voluptate reprehenderit accusantium voluptates rem facilis dolore natus totam alias ab temporibus.
			</div>
			<?
			$options = array(
				'class' => 'form-horizontal',
				'role' => 'form',
				'inputDefaults' => array(
					'label' => false,
					'div' => false,
					'class' => 'form-control input-lg'
				)
			);
			echo $this->Form->create('Usuario',$options); ?>
				<div class="tab-content">
					<div class="tab-pane fade in active" id="info">
						<h1 class="text-trabaje">Información personal</h1>
						<hr>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Apellido Paterno:</h4>
							</label>
							<div class="col-md-8">
								<?= $this->Form->input('apellido_paterno', array('placeholder' => 'Ingresa tu Apellido Paterno', 'required' => 'required')); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Apellido Materno:</h4>
							</label>
							<div class="col-md-8">
								<?= $this->Form->input('apellido_materno', array('placeholder' => 'Ingresa tu Apellido Materno', 'required' => 'required')); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Nombres:</h4>
							</label>
							<div class="col-md-8">
								<?= $this->Form->input('nombre', array('placeholder' => 'Ingresa tu Nombre', 'required' => 'required')); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Cargo al cual postula:</h4>
							</label>
							<div class="col-md-8">
								<?= $this->Form->input('cargo', array('placeholder' => 'Ingresa tu cargo', 'required' => 'required')); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Jornada:</h4>
							</label>
							<div class="col-md-8">
								<?= $this->Form->input('jornada', array('type' => 'select', 'options' => $jornadas, 'empty' => '- jornada', 'required' => 'required')); ?>
							</div>
						</div>
						<div class="form-group" rel="listadoTurnos" style="display: none;">
							<div class="col-md-4"></div>
							<div class="col-md-8">
								<table class="table table-condensed table-hover">
									<thead>
										<tr>
											<th>Día</th>
											<th>Turno</th>
										</tr>
									</thead>
									<? $dias = array('lunes','martes','miercoles','jueves','viernes','sabado','domingo'); ?>
									<? foreach ($dias as $dia) : ?>
									<tr>
										<td class="col-md-4">
											<div class="checkbox">
												<label>
													<?
													$options = array(
														'type' => 'checkbox',
														'label' => false,
														'div' => false,
														'class' => false
													);
													echo $this->Form->input('Jornada.'.$dia.'.activo',$options); ?>
													<?= ucfirst($dia); ?>
												</label>
											</div>
										</td>
										<td class="col-md-8">
											<label class="radio-inline">
												<?
												$attr = array(
													'legend' => false,
													'label' => false,
													'div' => false,
													'class' => false,
													'separator' => '</label><label class="radio-inline">',
												);
												$options = array(
													'dia completo' => 'dia completo',
													'mañana' => 'mañana',
													'tarde' => 'tarde'
												);
												echo $this->Form->radio('Jornada.'.$dia.'.turno',$options, $attr); ?>
											</label>
										</td>
									</tr>
									<? endforeach; ?>
								</table>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Rut:</h4>
							</label>
							<div class="col-md-8">
								<?
								$options = array(
									'placeholder' => 'ej: 12.345.678',
									'size' => '10',
									'style' => 'width: auto; display: inline;',
									'required' => 'required',
									//'onkeypress' => "return justNumbers(event);"
								);
								echo $this->Form->input('rut', $options); ?>
								-
								<?
								$options = array(
									'type' => 'select',
									'empty' => '-',
									'options' => array(
										0=>0,1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,'K'=>'K'
									),
									'style' => 'width: auto; display: inline;',
									'required' => 'required'
								);
								echo $this->Form->input('d_verificador',$options); ?>
								<? $this->Form->input('rut', array('placeholder' => 'ej: 12.345.678-9', 'required' => 'required')); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Fecha de nacimiento:</h4>
							</label>
							<div class="col-md-8">
								<?
								$options = array(
									'empty' => '- día',
									'class' => 'form-control input-lg',
									'style' => 'width: auto; display: inline;',
									'required' => 'required'
								);
								echo $this->Form->day('f_nacimiento',false,$options); ?> -
								<?
								$options = array(
									'monthNames' => false,
									'empty' => '- mes',
									'class' => 'form-control input-lg',
									'style' => 'width: auto; display: inline;',
									'required' => 'required'
								);
								echo $this->Form->month('f_nacimiento', null, $options); ?> -
								<?
								$options = array(
									'empty' => '- año',
									'class' => 'form-control input-lg',
									'style' => 'width: auto; display: inline;',
									'required' => 'required'
								);
								echo $this->Form->year('f_nacimiento',(intval(date('Y'))-110), (intval(date('Y'))-12), false,$options); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Correo electronico:</h4>
							</label>
							<div class="col-md-8">
								<?= $this->Form->input('email', array('placeholder' => 'Ingresa tu Email', 'required' => 'required')); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Nacionalidad:</h4>
							</label>
							<div class="col-md-8">
								<?= $this->Form->input('pais', array('placeholder' => 'Tu pais', 'required' => 'required')); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Telefonos de contacto:</h4>
							</label>
							<div class="col-md-8">
								<p>
									<?= $this->Form->input('fijo', array('placeholder' => 'Fijo', 'required' => 'optional', 'data-option' => 'contacto')); ?>
								</p>
								<p>
									<?= $this->Form->input('celular', array('placeholder' => 'Celular', 'required' => 'optional', 'data-option' => 'contacto')); ?>
								</p>
							</div>
						</div>
						<div class="form-group">
							<hr>
							<div class="col-md-4 col-md-offset-8">
								<a href="#" rel="continuarInfo" class="btn btn-primary btn-lg btn-block text-right btn-continuar">
									Continuar <i class="fa fa-chevron-circle-right"></i>
								</a>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="datos">
						<h1 class="text-trabaje">Datos generales</h1>
						<hr>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Domicilio:</h4>
							</label>
							<div class="col-md-8">
								<?= $this->Form->input('domicilio', array('placeholder' => 'Tu domicilio', 'required' => 'required')); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Comuna:</h4>
							</label>
							<div class="col-md-8">
								<?= $this->Form->input('comuna', array('placeholder' => 'Tu comuna', 'required' => 'required')); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Calles cercanas a su domicilio:</h4>
							</label>
							<div class="col-md-8">
								<?= $this->Form->input('referencia', array('placeholder' => 'Ingresa tu calle principal cercana')); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Ciudad:</h4>
							</label>
							<div class="col-md-8">
								<?= $this->Form->input('ciudad', array('placeholder' => 'Tu ciudad', 'required' => 'required')); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Estado civil:</h4>
							</label>
							<div class="col-md-8">
								<?= $this->Form->input('estado_civil', array('type' => 'select', 'empty' => '- estado civil', 'options' => $estadosCiviles, 'required' => 'required')); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Cargas familiares:</h4>
							</label>
							<div class="col-md-8">
								<?
								$nCargas = 3;
								for ($carga=1;$carga<=$nCargas;$carga++)
									echo '<p>'.$this->Form->input('Carga.'.$carga.'.carga', array('placeholder' => '('.$carga.') Nombre - Rut - Edad')).'</p>';
								?>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Tiene hijos?:</h4>
							</label>
							<div class="col-md-8">
								<?= $this->Form->input('hijos', array('type' => 'select', 'empty' => '- hijos', 'options' => array('si'=>'si','no'=>'no'), 'required' => 'required')); ?>
							</div>
						</div>
						<div class="form-group">
							<hr>
							<div class="col-md-4">
								<a href="#" class="btn btn-default btn-lg btn-block text-right btn-continuar" rel="anteriorDatos">
									<i class="fa fa-chevron-circle-left"></i> Anterior 
								</a>
							</div>
							<div class="col-md-4"></div>
							<div class="col-md-4">
								<a href="#" class="btn btn-primary btn-lg btn-block text-right btn-continuar" rel="continuarDatos">
									Continuar <i class="fa fa-chevron-circle-right"></i>
								</a>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="xp">
						<h1 class="text-trabaje">Experiencía laboral</h1>
						<hr>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Nombre de la empresa:</h4>
							</label>
							<div class="col-md-8">
								<?= $this->Form->input('Experiencia.nombre', array('placeholder' => 'Nombre de la empresa anterior')); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Tipo de empresa:</h4>
							</label>
							<div class="col-md-8">
								<?= $this->Form->input('Experiencia.tipo', array('placeholder' => 'Tipo de empresa')); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Cargo ocupado en la empresa:</h4>
							</label>
							<div class="col-md-8">
								<?= $this->Form->input('Experiencia.cargo', array('placeholder' => 'Cargo en la empresa')); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Periodo trabajado:</h4>
							</label>
							<div class="col-md-8">
								<?= $this->Form->input('Experiencia.periodo', array('placeholder' => 'Tiempo trabajado')); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Nombre de su jefe inmediato:</h4>
							</label>
							<div class="col-md-8">
								<?= $this->Form->input('Experiencia.jefe', array('placeholder' => 'Jefe directo')); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Telefonos de su jefatura:</h4>
							</label>
							<div class="col-md-8">
								<?= $this->Form->input('Experiencia.telefono', array('placeholder' => 'Telefono de su jefe anterior')); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Indique motivo de su salida:</h4>
							</label>
							<div class="col-md-8">
								<?= $this->Form->input('Experiencia.salida', array('type' => 'textarea', 'placeholder' => 'Motivo aquí', 'rows' => 3)); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4>Indique su motivación para postular a <b class="text-info">SKECHERS</b>:</h4>
							</label>
							<div class="col-md-8">
								<?= $this->Form->input('Experiencia.motivacion', array('type' => 'textarea', 'placeholder' => 'Escribe tu motivación aquí', 'rows' => 3)); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-md-4 control-label">
								<h4 class="nomargin">Tienes familiares dentro de la empresa ?:</h4>
							</label>
							<div class="col-md-8">
								<?= $this->Form->input('Experiencia.familiares', array('type' => 'select', 'options' => array('no'=>'no','si'=>'si'), 'class' => 'form-control input-lg familiar')); ?>
							</div>
						</div>
						<div class="sitengo hidden">
							<div class="form-group">
								<label for="inputEmail3" class="col-md-4 control-label">
									<h4>Nombre completo:</h4>
								</label>
								<div class="col-md-8">
									<?= $this->Form->input('Pariente.nombre', array('placeholder' => 'Nombre de la persona')); ?>
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-md-4 control-label">
									<h4>Parentesco:</h4>
								  </label>
								<div class="col-md-8">
									<?= $this->Form->input('Pariente.parentesco', array('placeholder' => 'Parentesco aquí')); ?>
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-md-4 control-label">
									<h4>Puesto:</h4>
								</label>
								<div class="col-md-8">
									<?= $this->Form->input('Pariente.puesto', array('placeholder' => 'Puesto de la persona')); ?>
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-md-4 control-label">
									<h4>Lugar de trabajo:</h4>
								</label>
								<div class="col-md-8">
									<?= $this->Form->input('Pariente.lugar', array('placeholder' => 'Indicar lugar de trabajo aquí')); ?>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<table class="table table-bordered">
									<thead>
										<tr class="info">
											<th colspan="5" class="text-center">
												<h4>Referencias laborales</h4>
											</th>
										</tr>
										<tr>
											<th><h4>Nombre</h4></th>
											<th><h4>Cargo</h4></th>
											<th><h4>Empresa</h4></th>
											<th><h4>Telefono</h4></th>
											<th><h4>Tiempo</h4></th>
										</tr>
									</thead>
									<tbody>
										<? for ($referencia=1;$referencia<=4;$referencia++) : ?>
										<tr>
											<td>
												<?= $this->Form->input('Referencia.'.$referencia.'.nombre'); ?>
											</td>
											<td>
												<?= $this->Form->input('Referencia.'.$referencia.'.cargo'); ?>
											</td>
											<td>
												<?= $this->Form->input('Referencia.'.$referencia.'.empresa'); ?>
											</td>
											<td>
												<?= $this->Form->input('Referencia.'.$referencia.'.telefono'); ?>
											</td>
											<td>
												<?= $this->Form->input('Referencia.'.$referencia.'.tiempo'); ?>
											</td>
										</tr>
										<? endfor; ?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="form-group">
							<hr>
							<div class="col-md-4">
								<a href="#" class="btn btn-default btn-lg btn-block text-right btn-continuar" rel="anteriorExperiencia">
									<i class="fa fa-chevron-circle-left"></i> Anterior 
								</a>
							</div>
							<div class="col-md-3"></div>
							<div class="col-md-5">
								<a href="#" class="btn btn-warning btn-lg btn-block text-right btn-continuar" rel="enviarPostulacion">
									Enviar postulación <i class="fa fa-chevron-circle-right"></i>
								</a>
							</div>
						</div>
					</div>
				</div>
			<?= $this->Form->end(); ?>
		</div>
		<div class="col-md-4">
			<div class="lateral">
				<div class="col-md-7 col-md-offset-4">
					<h4 class="text-info text-right">
						Skechers un fascinante mundo para crecer
					</h4>
				</div>
			</div>
		</div>
    </div>
</div>

<script>
function subirScroll() {
	var pos = $('#tabTrabaje').offset();
	$('html,body').animate({scrollTop:pos.top},'slow');
	return false;
}

//function justNumbers(e) {
//	var keynum = window.event ? window.event.keyCode : e.which;
//	if ((keynum == 8) || (keynum == 46))
//		return true;
//
//	return /\d/.test(String.fromCharCode(keynum));
//}

$(document).on('click','a[rel="continuarInfo"]',function(e) {
	e.preventDefault();
	$('#UsuarioTrabajeForm #info .has-error').removeClass('has-error');
	$('#UsuarioTrabajeForm #info [required="required"]').each(function(i,elemento) {
		if (! $(elemento).val()) {
			if ($(elemento).parents('.form-group').length)
				$(elemento).parents('.form-group').addClass('has-error');
		}
	});
	var campos = $('#UsuarioTrabajeForm #info [required="optional"][data-option="contacto"]');
	if (campos.length) {
		var cont = campos.size();
		if (cont) {
			campos.each(function(i,elemento) {
				if (! $(elemento).val())
					cont--;
			});
		}
		if (cont <= 0)
			campos.parents('.form-group').addClass('has-error');
	}
	if ($('#UsuarioTrabajeForm #info .has-error').length) {
		alerta('Para continuar debes llenar los campos destacados que son requeridos para tu postulación.');
		return false;
	}
	if ($('#UsuarioTrabajeForm #UsuarioRut').length) {
		if ((/^([0-9-.])*$/.test($('#UsuarioTrabajeForm #UsuarioRut').val())) == false) {
			$('#UsuarioTrabajeForm #UsuarioRut').parents('.form-group').addClass('has-error');
			alerta('Para continuar debes ingresar un rut valido.');
			return false;
		}
	}
	
	if ($('#UsuarioTrabajeForm #UsuarioEmail').length) {
		if ((/\S+@\S+\.\S+/.test($('#UsuarioTrabajeForm #UsuarioEmail').val())) == false) {
			$('#UsuarioTrabajeForm #UsuarioEmail').parents('.form-group').addClass('has-error');
			alerta('Para continuar debes ingresar un email valido.');
			return false;
		}
	}
	$('#tabTrabaje a[href="#datos"]').tab('show');
	subirScroll();
});

$(document).on('click','a[rel="anteriorDatos"]',function(e) {
	e.preventDefault();
	$('#tabTrabaje a[href="#info"]').tab('show');
	
	//subirScroll();
});

$(document).on('click','a[rel="continuarDatos"]',function(e) {
	e.preventDefault();
	$('#UsuarioTrabajeForm #datos .has-error').removeClass('has-error');
	$('#UsuarioTrabajeForm #datos [required="required"]').each(function(i,elemento) {
		if (! $(elemento).val()) {
			if ($(elemento).parents('.form-group').length)
				$(elemento).parents('.form-group').addClass('has-error');
		}
	});
	if ($('#UsuarioTrabajeForm #datos .has-error').length) {
		alerta('Para continuar debes llenar los campos destacados que son requeridos para tu postulación.');
		return false;
	}
	$('#tabTrabaje a[href="#xp"]').tab('show');
	subirScroll();
});

$(document).on('click','a[rel="anteriorExperiencia"]',function(e) {
	e.preventDefault();
	$('#tabTrabaje a[href="#datos"]').tab('show');
	
	//subirScroll();
});

$(document).on('click','a[rel="enviarPostulacion"]',function(e) {
	e.preventDefault();
	$('#UsuarioTrabajeForm').submit();
});

$(document).on('change','#UsuarioTrabajeForm #UsuarioJornada',function() {
	var jornada = $(this).val();
	if (jornada == 'Part Time') {
		$('div[rel="listadoTurnos"]').slideDown(500);
	} else {
		$('div[rel="listadoTurnos"]').slideUp(500);
	}
});

if ($('#UsuarioTrabajeForm #UsuarioJornada').length) {
	if ($('#UsuarioTrabajeForm #UsuarioJornada').val() == 'Part Time') {
		$('div[rel="listadoTurnos"]').show();
	}
}

if ($('#UsuarioTrabajeForm #ExperienciaFamiliares').length) {
	if ($('#UsuarioTrabajeForm #ExperienciaFamiliares').val() == 'si') {
		$('#UsuarioTrabajeForm .sitengo').removeClass('hidden');
		$('#UsuarioTrabajeForm .sitengo').addClass('show');
	}
}
</script>
