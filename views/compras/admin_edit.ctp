<style type="text/css" media="all">
.izquierda {
	float: left;
	width: 400px;
}
.derecha {
	float: left;
	width: 250px;
	margin-left: 10px;
}
.datosCompra {
	width:100%;
	background: #fff;
	padding: 5px 10px;
	border: 1px solid #0080c0;
	border-radius: 5px;
	margin-bottom: 10px;
}
.datosCompra td {
	padding: 0;
	text-align: left;
}
.datosCompra td b {
	color: #0080c0;
}
.datosCompra td.linea {
	border-bottom: 1px solid #0080c0;
}
.datosCompra td.text-right {
	text-align: right;
}
.modal {
	position: fixed;
	width: 100%;
	height: 100%;
	background-color: rgba(0,0,0,0.6);
	top: 0;
	left: 0;
	display: none;
}
.modal > .cuadro-dialogo {
	position: relative;
	width: 670px;
	max-height: 400px;
	margin-left: auto;
	margin-right: auto;
	margin-top: 50px;
	background-color: #FFF;
	padding: 20px 20px 70px 20px;
	border-radius: 8px;
}
.modal > .cuadro-dialogo > .titulo {
	position: relative;
	width: 100%;
	height: 20px;
	font-size: large;
	color: #0080c0;
	text-transform: uppercase;
	border-bottom: 1px solid #0080c0;
	padding-bottom: 10px;
}
.modal > .cuadro-dialogo > .pie {
	position: absolute;
	width: 100%;
	bottom: 0;
	left: 0;
	height: 50px;
	text-align: right;
}
.modal > .cuadro-dialogo .btn {
	display: inline-block;
	border: 1px solid #006595;
	border-radius: 3px;
	padding: 5px 10px;
	margin: 5px 10px 0 0;
	background-color: #FFF;
	color: #777;
	text-decoration: none;
}
.modal > .cuadro-dialogo .btn:hover {
	opacity: 1;
	text-decoration: underline;
	cursor: pointer;
}
.modal > .cuadro-dialogo .btn.primary {
	background-color: #0080c0;
	color: #fff;
}
.modal > .cuadro-dialogo .btn.danger {
	border: 1px solid #d20000;
	background-color: #ff0000;
	color: #fff;
}
.modal > .cuadro-dialogo .btn.success {
	border: 1px solid #004000;
	background-color: #008000;
	color: #fff;
}
.modal > .cuadro-dialogo > .contenido {
	position: relative;
	width: 100%;
	border-bottom: 1px solid #0080c0;
	padding-bottom: 10px;
	max-height: 327px;
}
.contenido .lista {
	position: relative;
	padding: 10px;
	border-bottom: 1px solid #999;
}
.contenido .lista label {
	float: left;
	width: 180px;
	font-weight: bold;
	text-transform: uppercase;
}
.contenido .lista select, .contenido .lista input {
	background-color: #f2f2f2;
    font-size: 14px;
    padding: 5px;
	border: 1px solid #888;
    color: #222;
}
.error {
	border-color: #ff0000 !important;
	background-color: #ffc4c4 !important;
	color: #bb0000 !important;
}
.contenido .lista table {
	width: 100%;
}
.contenido .lista table td.label {
	font-weight: bold;
	text-transform: uppercase;
	text-align: left;
	padding: 0;
}
</style>

<div class="modal">
	<div class="cuadro-dialogo status1">
		<div class="titulo">DEVOLUCIÓN EN CURSO</div>
		<div class="contenido">
			<?= $this->Form->create('Compra', array('method' => 'post',action' => 'devolucion_proceso', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => false))); ?>
				<?= $this->Form->hidden('estado'); ?>
				<?= $this->Form->hidden('Devolucion.compra_id',array('value' => $compra['Compra']['id'])); ?>
				<div class="lista">
					<label>RA</label>
					<?= $this->Form->input('Devolucion.codigo',array('autocomplete' => 'off')); ?>
				</div>
				<div class="lista">
					<label>fecha de retiro:</label>
					<?
					$options = array('empty' => '- día');
					echo $this->Form->day('Devolucion.fecha',false,$options); ?> -
					<?
					$options = array(
						'monthNames' => false,
						'empty' => '- mes',
						'style' => 'max-width: 80px;'
					);
					echo $this->Form->month('Devolucion.fecha', null, $options); ?> -
					<?
					$options = array('empty' => '- año');
					echo $this->Form->year('Devolucion.fecha',date('Y'), (intval(date('Y'))+5), false,$options); ?>
				</div>
				<div class="lista">
					<table>
						<tr>
							<td colspan="2" class="label">hora de retiro:</td>
						</tr>
						<tr>
							<td>desde</td>
							<td>hasta</td>
						</tr>
						<tr>
							<td>
								<?= $this->Form->hour('Devolucion.hora_desde', true, false, array('empty' => '- hora')); ?> :
								<?= $this->Form->minute('Devolucion.hora_desde', false, array('empty' => '- minutos')); ?>
							</td>
							<td>
								<?= $this->Form->hour('Devolucion.hora_hasta', true, false, array('empty' => '- hora')); ?> :
								<?= $this->Form->minute('Devolucion.hora_hasta', false, array('empty' => '- minutos')); ?>
							</td>
						</tr>
					</table>
				</div>
			<?= $this->Form->end(); ?>
		</div>
		<div class="pie">
			<button type="button" class="btn danger" rel="cerrar">cancelar</button>
			<button type="button" class="btn primary" rel="siguiente" style="margin-right: 20px;">siguiente &raquo;</button>
		</div>
	</div>
	<div class="cuadro-dialogo status2">
		<div class="titulo">DEVOLUCIÓN EN CURSO &raquo; PREVISUALIZACIÓN</div>
		<div class="contenido">
			<a href="#" class="btn" rel="verEmail"><img src="<?= $this->Html->url('/img/iconos/letter_16.png'); ?>" /> previsualizar email</a>
			<a href="#" class="btn" rel="verPdf" target="_blank"><img src="<?= $this->Html->url('/img/iconos/pdf_16.png'); ?>" /> previsualizar pdf</a>
		</div>
		<div class="pie">
			<button type="button" class="btn danger" rel="cerrar">cancelar</button>
			<button type="button" class="btn primary" rel="editar">editar</button>
			<button type="button" class="btn success" rel="guardar" style="margin-right: 20px;">aceptar</button>
		</div>
	</div>
</div>

<div class="col02">
	<?= $this->Form->create('Compra', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Editar Compra'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('id'); ?></li>
		<li>
			<?
			$options = array(
				'type' => 'select',
				'empty' => false,
				'options' => array(
					'0' => 'No pagado',
					'1' => 'Pagado',
					'2' => 'Anulado',
                    '11' => 'Anulado por stock',
					'3' => 'En Devolucion',
					'4' => 'Devuelto',
					'5' => 'Pendiente',

				)
			);
			if (in_array($compra['Compra']['estado'],array(1,6)))
				$options['options']['6'] = 'Devolución en curso';
			if (in_array($compra['Compra']['estado'],array(1,7)))
				$options['options']['7'] = 'Cambio en curso';
			echo $this->Form->input('estado',$options); ?>
		</li>
	</ul>
	<div class="botones">
		<a href="#" class="cambioEstado"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>

	<div class="previsualizar" style="margin-top: 20px;">
		<ul>
			<li class="extendido">
				<h2>Datos de la compra</h2>
			</li>
			<li class="extendido">
				<div class="izquierda">
					<table class="datosCompra">
						<tr>
							<td><b>nombre cliente:</b></td>
						</tr>
						<tr>
							<td class="linea">
								<?= $compra['Usuario']['nombre'].' '.$compra['Usuario']['apellido_paterno']; ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td><b>rut:</b></td>
						</tr>
						<tr>
							<td class="linea">
								<?= $compra['Usuario']['rut']; ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td><b>email cliente:</b></td>
						</tr>
						<tr>
							<td class="linea">
								<?= $compra['Usuario']['email']; ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td><b>despacho:</b></td>
						</tr>
						<tr>
							<td class="linea">
								<?= $compra['Direccion']['calle'].' #'.$compra['Direccion']['numero']; ?><?= ($compra['Direccion']['depto'])?', depto '.$compra['Direccion']['depto']:''; ?> - <?= $compra['Comuna']['nombre']; ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td><b>telefono:</b></td>
						</tr>
						<tr>
							<td class="linea">
								<?= $compra['Direccion']['telefono']; ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td><b>celular:</b></td>
						</tr>
						<tr>
							<td>
								<?= $compra['Direccion']['celular']; ?>&nbsp;
							</td>
						</tr>
					</table>
				</div>
				<div class="derecha">
					<table class="datosCompra">
						<tr>
							<td class="linea"><b>estado:</b></td>
							<td class="linea text-right">
								<?
								$estado = 'no pagado';
								if ($compra['Compra']['estado'] == 1)
									$estado = 'pagado';
								elseif ($compra['Compra']['estado'] == 2)
									$estado = 'anulado';
								elseif ($compra['Compra']['estado'] == 3)
									$estado = 'en devolución';
								elseif ($compra['Compra']['estado'] == 4)
									$estado = 'devuelto';
								elseif ($compra['Compra']['estado'] == 5)
									$estado = 'pendiente';
								elseif ($compra['Compra']['estado'] == 6)
									$estado = 'devolución en curso';
								elseif ($compra['Compra']['estado'] == 7)
									$estado = 'cambio en curso';
                                elseif ($compra['Compra']['estado'] == 11)
                                    $estado = 'anulado por stock';
								?>
								<?= $estado; ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td class="linea"><b>n° boleta:</b></td>
							<td class="linea text-right">
								<?= $compra['Compra']['boleta']; ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td class="linea"><b>id (invoice):</b></td>
							<td class="linea text-right">
								<?= $compra['Compra']['numId']; ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td class=""><b>cod. despacho:</b></td>
							<td class="text-right">
								<?= $compra['Compra']['cod_despacho']; ?>&nbsp;
							</td>
						</tr>
					</table>
					<table class="datosCompra">
						<tr>
							<td class="linea"><b>subtotal:</b></td>
							<td class="linea text-right">
								<?= $this->Shapeups->moneda($compra['Compra']['subtotal']); ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td class="linea"><b>iva:</b></td>
							<td class="linea text-right">
								<?= $this->Shapeups->moneda($compra['Compra']['iva']); ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td class="linea"><b>neto:</b></td>
							<td class="linea text-right">
								<?= $this->Shapeups->moneda($compra['Compra']['neto']); ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td class="linea"><b>descuento:</b></td>
							<td class="linea text-right">
								- <?= $this->Shapeups->moneda($compra['Compra']['descuento']); ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td class="linea"><b>valor despacho:</b></td>
							<td class="linea text-right">
								<?= $this->Shapeups->moneda($compra['Compra']['valor_despacho']); ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td><b>total:</b></td>
							<td class="text-right">
								<?= $this->Shapeups->moneda($compra['Compra']['total']); ?>&nbsp;
							</td>
						</tr>
					</table>
				</div>
			</li>
		</ul>
	</div>
	<h2 class="subtitulo">Productos</h2>
	<? foreach ($productos as $producto) : ?>
		<div class="previsualizar">
			<ul>
				<li class="extendido">
					<span>
                        <img src="<?= $this->Shapeups->imagen('Producto/'.$producto['Producto']['id'].'/mini_'.$producto['Producto']['foto']); ?>" />
                    </span>
					<b><?= $producto['Producto']['nombre']; ?>
                    </b> (<?= $producto['Producto']['codigo_completo']; ?>)
					<br /><b>talla:</b> <?= $producto['ProductosCompra']['talla']; ?>
					<br /><b>valor pagado:</b> <?= $this->Shapeups->moneda($producto['ProductosCompra']['valor']); ?>
				</li>
			</ul>
		</div>
	<? endforeach; ?>
</div>

<script type="application/x-javascript">
$('.status2 button[rel="guardar"]').live('click',function() {
	var formulario = $(this).parents('.modal').find('form');
	if (! formulario.length)
		return false;
	formulario.submit();
});

$('.status2 button[rel="editar"]').live('click',function() {
	var modal = $(this).parents('.modal');
	if (! modal.length)
		return false;
	modal.find('.status2').fadeOut(500,function() {
		modal.find('.status1 a[rel="verPdf"]').attr('href','#');
		modal.find('.status1').fadeIn(600);
	});
});

$('.status2 a[rel="verEmail"]').live('click',function(e) {
	e.preventDefault();
	var formulario = $('.modal form#CompraDevolucionProcesoForm');
	if (! formulario.length)
		return false;

	$.ajax({
		async	: true,
		type	: 'POST',
		url		: webroot + 'compras/ajax_previsualizar_email',
		data	: formulario.serialize(),
		success: function(respuesta) {
			if (! respuesta)
				return false;

			var previsualizacion = window.open('', '', 'height=600,width=600,scrollbars=YES');
			previsualizacion.document.write(respuesta);
		}
	});
});

$('a.cambioEstado').click(function(e) {
	e.preventDefault();
	var valor = $('#CompraAdminEditForm #CompraEstado').val(),
		formulario = $(this).parents('form');
	if (valor == 6) {
		$('.modal #CompraEstado').val(6);
		$('.modal .error').removeClass('error');
		$('.modal .status1 form')[0].reset();
		$('.modal .cuadro-dialogo').hide();
		$('.modal .cuadro-dialogo.status1').show();
		$('.modal').fadeIn(700);
	} else if (valor == 7) {
		$('.modal #CompraEstado').val(7);
		$('.modal .error').removeClass('error');
		$('.modal .status1 form')[0].reset();
		$('.modal .cuadro-dialogo').hide();
		$('.modal .cuadro-dialogo.status1').show();
		$('.modal').fadeIn(700);
	} else {
		formulario.submit();
	}
});

$('.modal button[rel="cerrar"]').live('click',function(e) {
	e.preventDefault();
	$(this).parents('.modal').fadeOut(500);
});

$('.modal .status1 button[rel="siguiente"]').live('click',function(e) {
	e.preventDefault();
	var formulario = $(this).parents('.cuadro-dialogo').find('form'),
		modal = $(this).parents('.modal');
	if (! formulario.length)
		return false;

	$('.modal .error').removeClass('error');
	formulario.find('input').each(function(index,elemento) {
		if (! $(elemento).val()) {
			$(elemento).addClass('error');
		}
	});
	formulario.find('select').each(function(index,elemento) {
		if (! $(elemento).val()) {
			$(elemento).addClass('error');
		}
	});

	if (modal.find('.error').length) {
		return false;
	}

	modal.find('.status1').fadeOut(500,function() {
		modal.find('.status2 a[rel="verPdf"]').attr('href',webroot+'compras/retiro_pdf?a=<?= $token['a']; ?>&b=<?= $token['b']; ?>&c=<?= $token['c']; ?>&ra='+formulario.find('#DevolucionCodigo').val()+'&estado='+formulario.find('#CompraEstado').val());
		modal.find('.status2').fadeIn(600);
	});
});
</script>