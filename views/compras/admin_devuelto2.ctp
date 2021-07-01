<style type="text/css" media="all">
td.actions a {
	margin-right: 7px;
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
.modal > .cuadro-dialogo .contenido {
	overflow-x: hidden;
	overflow-x: auto;
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
	display: inline-block;
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
/*background-color: #970097;*/
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
<div class="col02">
	<?= $this->element('admin_buscar_compra'); ?>
	<h1 class="titulo"><? __('Compras');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('estado'); ?></th>
			<th><?= $this->Paginator->sort('Nº Compra', 'id'); ?></th>
			<th><?= $this->Paginator->sort('Nombre', 'usuario_id'); ?></th>
			<th><?= $this->Paginator->sort('Fecha', 'created'); ?></th>
			<th><?= $this->Paginator->sort('Total', 'total'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
		<? foreach ( $compras as $compra ) : ?>
			<? if(empty($compra['Devolucion']))  
					continue;
		?>
		<tr>
			<td class="estado-<?= $compra['Compra']['id']; ?>" style="text-align:center;">
				<?
				$boton = array(
					'color' => '#fff',
					'title' => '<br />No Pagado'
				);

				if ($compra['Compra']['estado'] == 3)
				{
					$boton['title'] = '<br />En Devolucion';
					$boton['color'] = '#0080c0';
				}
				elseif ($compra['Compra']['estado'] == 4)
				{
					$boton['title'] = '<br />Devuelto';
					$boton['color'] = '#004080';
				}
				elseif ($compra['Compra']['estado'] == 6)
				{
					$boton['title'] = '<br />Devolución en curso';
					$boton['color'] = '#ff00ff';
				}
				elseif ($compra['Compra']['estado'] == 7)
				{
					$boton['title'] = '<br />Cambio en curso';
					$boton['color'] = '#b164ff';
				}
				elseif ($compra['Compra']['estado'] == 8)
				{
					$boton['title'] = '<br />Cambio en proceso';
					$boton['color'] = '#5700ae';
				}
				elseif ($compra['Compra']['estado'] == 9)
				{
					$boton['title'] = '<br />Cambio aprobado';
					$boton['color'] = '#320064';
				}
				elseif ($compra['Compra']['estado'] == 10)
				{
					$boton['title'] = '<br />Cambio aprobado';
					$boton['color'] = '#970097';
				}
				?>
				<div style="width:18px;height:18px;border-radius:9px;background: radial-gradient(#fff, <?= $boton['color']; ?>, #fff);display: inline-block;"></div>
				<i style="color:<?= $boton['color']; ?>;"><?= $boton['title']; ?></i>
			<!-- FIN BOTON ESTADO -->
			</td>
			<td><?= $compra['Compra']['id']; ?>&nbsp;</td>
			<td><?= $compra['Usuario']['nombre'] . ' ' . $compra['Usuario']['apellido_paterno']; ?></td>
			<td><?= date('d-m-Y', strtotime($compra['Compra']['created'])); ?></td>
			<td><?= $compra['Compra']['total']; ?>&nbsp;</td>
			<td class="actions acciones-compras-<?= $compra['Compra']['id']; ?>">
				<?
				$botones = array(
					'view' => false,
					'devolver' => false,
					'devolucion' => false,
					'anular' => false,
					'editar' => false,
					'anula_devolucion' => false,
					'cambio_devolucion' => false,
					'cancelar_devolucion' => false,
					'aprobar_cambio' => false
				);
				if ($compra['Compra']['estado']==3)
				{
					if (in_array($authUser['perfil'],array(1,2,3)))
					{
						$botones['cambiar'] = true;
						if (in_array($authUser['perfil'],array(2,3)))
						{
							$botones['anular'] = $botones['editar'] = true;
							if ($authUser['perfil'] == 3)
								$botones['devolucion'] = true;
						}
					}
				}
				elseif ($compra['Compra']['estado']==4)
				{
					if (in_array($authUser['perfil'],array(1,2,3)))
					{
						$botones['reenviar'] = true;
						if (in_array($authUser['perfil'],array(2,3)))
							$botones['anular'] = $botones['editar'] = true;
					}
				}
				elseif ($compra['Compra']['estado']==6)
				{
					if (in_array($authUser['perfil'],array(2,3)))
						$botones['editar'] = $botones['anula_devolucion'] = $botones['cancelar_devolucion'] = true;
				}
				elseif ($compra['Compra']['estado']==7)
				{
					if (in_array($authUser['perfil'],array(2,3)))
						$botones['editar'] = $botones['cambio_devolucion'] = $botones['cancelar_devolucion'] = true;
				}
				elseif ($compra['Compra']['estado']==8)
				{
					if (in_array($authUser['perfil'],array(2,3)))
						$botones['editar'] = $botones['aprobar_cambio'] = $botones['cancelar_devolucion'] = true;
				}
				elseif ($compra['Compra']['estado']==9)
				{
					if (in_array($authUser['perfil'],array(2,3)))
						$botones['cancelar_devolucion'] = true;
				}
				elseif ($compra['Compra']['estado']==10)
				{
					if (in_array($authUser['perfil'],array(2,3)))
						$botones['cancelar_devolucion'] = true;
				}

				// ACCIONES
				if ($botones['aprobar_cambio'])
					echo '<a href="#" title="Aprobar Cambio" rel="aprobar-cambio" data-id="'.$compra['Compra']['id'].'"><img src="'.$this->Html->url('/img/iconos/tick_16.png').'" /></a>';
				if ($botones['anula_devolucion'])
					echo '<a href="#" title="Anular Compra" rel="anula-devolucion" data-id="'.$compra['Compra']['id'].'"><img src="'.$this->Html->url('/img/iconos/reload_16.png').'" /></a>';
				if ($botones['cambio_devolucion'])
					echo '<a href="#" title="Cambio" rel="cambio-devolucion" data-id="'.$compra['Compra']['id'].'"><img src="'.$this->Html->url('/img/iconos/trash_16.png').'" /></a>';
				if ($botones['cancelar_devolucion'])
					echo '<a href="#" title="Cancelar Devolución" rel="cancelar" data-id="'.$compra['Compra']['id'].'"><img src="'.$this->Html->url('/img/iconos/block_16.png').'" /></a>';
				if ($botones['view'])
					echo '<a href="'.$this->Html->url(array('action'=>'view',$compra['Compra']['id'])).'" title="Ver"><img src="'.$this->Html->url('/img/iconos/clipboard_16.png').'" /></a>';
				if ($botones['devolucion'])
					echo '<a href="'.$this->Html->url(array('action'=>'devolucion',$compra['Compra']['id'])).'" title="Devolucion"><img src="'.$this->Html->url('/img/iconos/reload_16.png').'" /></a>';
				if ($botones['devolver'])
					echo '<a href="'.$this->Html->url(array('action'=>'devolver',$compra['Compra']['id'])).'" title="En devolucion"><img src="'.$this->Html->url('/img/iconos/left_16.png').'" /></a>';
				if ($botones['anular'])
					echo '<a href="'.$this->Html->url(array('action'=>'anular',$compra['Compra']['id'])).'" title="Anular Compra"><img src="'.$this->Html->url('/img/iconos/stop_16.png').'" /></a>';
				if ($botones['editar'])
					echo '<a href="'.$this->Html->url(array('action'=>'edit',$compra['Compra']['id'])).'" title="Editar"><img src="'.$this->Html->url('/img/iconos/pencil_16.png').'" /></a>';
				?>
			</td>
		</tr>
		<? endforeach; ?>
	</table>
	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>

<div id="cambio" class="modal">
	<div class="cuadro-dialogo status1">
		<div class="titulo">CAMBIO EN CURSO</div>
		<div class="contenido">
			<div class="lista">
				<label>Compra</label>
				<span rel="compra_id"></span>
			</div>
			<div class="lista">
				<label>RA</label>
				<span rel="codigo"></span>
			</div>
			<div class="lista">
				<label>fecha de retiro:</label>
				<span rel="fecha"></span>
			</div>
			<div class="lista">
				<label>hora de retiro:</label>
				<span rel="hora"></span>
			</div>
		</div>
		<div class="pie">
			<button type="button" class="btn default" rel="cerrar">cerrar</button>
			<button type="button" class="btn primary" rel="sin-diferencia" data-id="">sin diferencia &raquo;</button>
			<button type="button" class="btn primary" rel="con-diferencia"  data-id="" style="margin-right: 20px;">con diferencia &raquo;</button>
		</div>
	</div>
	<div class="cuadro-dialogo detalle">
		<div class="titulo">CAMBIO EN CURSO &raquo; <span style="font-size: small;">direrencia $0</span></div>
		<div class="contenido"></div>
		<div class="pie">
			<button type="button" class="btn danger" rel="cerrar">cancelar</button>
			<button type="button" class="btn primary" rel="editar">editar</button>
			<button type="button" class="btn success" rel="guardar" style="margin-right: 20px;">aceptar</button>
		</div>
	</div>
	<div class="cuadro-dialogo status2">
		<div class="titulo">CAMBIO EN CURSO &raquo; <span style="font-size: small;">PREVISUALIZAR</span></div>
		<div class="contenido"></div>
		<div class="pie">
			<button type="button" class="btn danger" rel="cerrar">cancelar</button>
			<button type="button" class="btn primary" rel="editar">editar</button>
			<button type="button" class="btn success" rel="guardar" style="margin-right: 20px;">aceptar</button>
		</div>
	</div>
</div>

<div id="anular" class="modal">
	<div class="cuadro-dialogo status1">
		<div class="titulo">DEVOLUCIÓN EN CURSO</div>
		<div class="contenido">
			<div class="lista">
				<label>Compra</label>
				<span rel="compra_id"></span>
			</div>
			<div class="lista">
				<label>RA</label>
				<span rel="codigo"></span>
			</div>
			<div class="lista">
				<label>fecha de retiro:</label>
				<span rel="fecha"></span>
			</div>
			<div class="lista">
				<label>hora de retiro:</label>
				<span rel="hora"></span>
			</div>
		</div>
		<div class="pie">
			<button type="button" class="btn default" rel="cerrar">cerrar</button>
			<button type="button" class="btn primary" rel="credito" data-id="">credito &raquo;</button>
			<button type="button" class="btn primary" rel="debito"  data-id="" style="margin-right: 20px;">debito &raquo;</button>
		</div>
	</div>
	<div class="cuadro-dialogo status2">
		<div class="titulo">DEVOLUCIÓN EN CURSO &raquo; PREVISUALIZACIÓN</div>
		<div class="contenido"></div>
		<div class="pie">
			<button type="button" class="btn danger" rel="cerrar">cancelar</button>
			<button type="button" class="btn primary" rel="editar">editar</button>
			<button type="button" class="btn success" rel="guardar" data-id="" data-tipo="" style="margin-right: 20px;">aceptar</button>
		</div>
	</div>
</div>

<div id="cambioAprobar" class="modal">
	<div class="cuadro-dialogo status1">
		<div class="titulo">CAMBIO EN PROCESO</div>
		<div class="contenido">
			<?= $this->Form->create('Compra', array('inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => false))); ?>
				<?= $this->Form->hidden('id'); ?>
				<div class="lista">
					<label>Codigo de despacho</label>
					<?= $this->Form->input('cod_despacho',array('autocomplete' => 'off')); ?>
				</div>
				<div class="lista">
					<label>N° de boleta</label>
					<?= $this->Form->input('boleta',array('autocomplete' => 'off')); ?>
				</div>
			<?= $this->Form->end(); ?>
		</div>
		<div class="pie">
			<button type="button" class="btn danger" rel="cerrar">cancelar</button>
			<button type="button" class="btn success" rel="guardar" data-id="" data-tipo="" style="margin-right: 20px;">aceptar</button>
		</div>
	</div>
	<div class="cuadro-dialogo status2">
		<div class="titulo">CAMBIO EN PROCESO &raquo; PREVISUALIZACIÓN</div>
		<div class="contenido"></div>
		<div class="pie">
			<button type="button" class="btn danger" rel="cerrar">cancelar</button>
			<button type="button" class="btn primary" rel="editar">editar</button>
			<button type="button" class="btn success" rel="guardar" data-id="" data-tipo="" style="margin-right: 20px;">aceptar</button>
		</div>
	</div>
</div>
<script type="application/x-javascript">
$('#cambio .cuadro-dialogo.status2 button[rel="guardar"]').live('click',function() {
	if (! $(this).parents('.cuadro-dialogo').length)
		return false;
	var cuadro = $(this).parents('.cuadro-dialogo');
	if (! cuadro.find('form').length)
		return false;
	var formulario = cuadro.find('form');
	if (! confirm('Se enviara un correo de notificacion con el codigo de descuento al cliente. ¿Desea continuar?'))
		return false;
	$.ajax({
		async	: true,
		dataType: 'json',
		data: formulario.serialize(),
		type	: 'POST',
		url		: webroot + 'compras/ajax_cambio_con_diferencia_aprobado/',
		success: function(respuesta) {
			if (respuesta.estado == 'OK') {
				alert('El cambio ha sido guardado exitosamente y el cliente ha sido notificado');
				location.reload();
			} else if (respuesta.estado) {
				cuadro.parents('.modal').fadeOut(500);
				alert(respuesta.estado);
			}
		}
	});
});

$('#cambio .cuadro-dialogo.status1 button[rel="con-diferencia"]').live('click',function() {
	var modal = $(this).parents('.modal'),
		id = $(this).data('id'),
		cuadro = $(this).parents('.cuadro-dialogo'),
		siguiente = cuadro.siblings('.cuadro-dialogo.status2');
	if (! id)
		return false;
	if (! cuadro.length)
		return false;
	if (! siguiente.length)
		return false;
	if (! confirm('La compra se pasara a estado "CAMBIO APROBADO". ¿Desea continuar?'))
		return false;
	$.ajax({
		async	: true,
		data: { id:id },
		type	: 'POST',
		url		: webroot + 'compras/ajax_cambio_con_diferencia_previsualizar/',
		success: function(respuesta) {
			siguiente.find('.contenido').html(respuesta);
			cuadro.fadeOut(500,function() {
				siguiente.fadeIn(600);
			});
		}
	});
});

$('#cambioAprobar .cuadro-dialogo.status2 button[rel="guardar"]').live('click',function() {
	if (! $(this).parents('.cuadro-dialogo').length)
		return false;
	var cuadro = $(this).parents('.cuadro-dialogo');
	if (! cuadro.find('form').length)
		return false;
	var formulario = cuadro.find('form');
	$.ajax({
		async	: true,
		dataType: 'json',
		data: formulario.serialize(),
		type	: 'POST',
		url		: webroot + 'compras/ajax_cambio_sin_diferencia_aprobado/',
		success: function(respuesta) {
			if (respuesta.estado == 'OK') {
				alert('El cambio ha sido guardado exitosamente.');
				location.reload();
			} else if (respuesta.estado) {
				cuadro.parents('.modal').fadeOut(500);
				alert(respuesta.estado);
			}
		}
	});
});

$('#cambioAprobar .cuadro-dialogo.status1 button[rel="guardar"]').live('click',function() {
	if (! $(this).parents('.cuadro-dialogo').length)
		return false;
	var cuadro = $(this).parents('.cuadro-dialogo');
	if (! cuadro.find('form').length)
		return false;
	var formulario = cuadro.find('form');
	$.ajax({
		async	: true,
		data: formulario.serialize(),
		type	: 'POST',
		url		: webroot + 'compras/ajax_cambio_sin_diferencia_previsualizar/',
		success: function(respuesta) {
			if (! respuesta) {
				alert('Error');
				return false;
			}
			cuadro.fadeOut(500,function() {
				var siguiente = cuadro.next('.status2');
				siguiente.find('.contenido').html(respuesta);
				siguiente.fadeIn(600);
			})
		}
	});
});

$('a[rel="aprobar-cambio"]').live('click',function(e) {
	e.preventDefault();
	var id = $(this).data('id');
	if (! id)
		return false;
	if (! $('#cambioAprobar.modal').length)
		return false;
	var modal = $('#cambioAprobar.modal');
	if (! modal.find('.cuadro-dialogo.status1').length)
		return false;
	var cuadro = modal.find('.cuadro-dialogo.status1');
	if (! cuadro.find('form').length)
		return false;
	var formulario = cuadro.find('form');
	formulario[0].reset();
	modal.find('.cuadro-dialogo').hide();
	formulario.find('#CompraId').val(id);
	cuadro.show();
	modal.fadeIn(600);
});

$('#cambio.modal .detalle button[rel="guardar"]').click(function() {
	var formulario = $(this).parents('.cuadro-dialogo.detalle').find('form');
	if (! formulario)
		return false;
	formulario.find('.error').removeClass('error');
	formulario.find('select.clase-input').each(function(index,elemento) {
		if (!$(elemento).val()) {
			$(elemento).addClass('error');
		}
	});
	if (formulario.find('.error').length) {
		alert('Debe seleccionar los campos destacados');
		return false;
	}

	$.ajax({
		async	: true,
		dataType: 'json',
		data: formulario.serialize(),
		type	: 'POST',
		url		: webroot + 'compras/ajax_cambio_sin_diferencia_proceso/',
		success: function(respuesta) {
			if (respuesta.estado == 'OK') {
				alert('El cambio esta en proceso...');
				location.reload();
			} else if (respuesta.estado) {
				alert(respuesta.estado);
			} else {
				return false;
			}
		}
	});
});

$('select[rel="selectorProductoCambio"]').live('change',function() {
	var id = $(this).val();
	if (! id)
		return false;

	var target = $(this).parents('.cuadro-dialogo').find('select[rel="selectorProductoTalla"]');

	if (! target.length)
		return false;

	$.ajax({
		async	: true,
		dataType: 'json',
		url		: webroot + 'compras/ajax_cambio_sin_diferencia_talla/'+id,
		success: function(respuesta) {
			if (! respuesta) {
				alert('Intentelo nuevamente...');
				return false;
			}
			target.html('<option value="">- tallas disponibles</option>');
			$.each(respuesta,function(stockId, productoTalla) {
				target.append('<option value="'+stockId+'">'+productoTalla+'</option>');
			});
		}
	});
});

$('select[rel="selectorProductoDevuelto"]').live('change',function() {
	var id = $(this).val();
	if (! id)
		return false;

	var target = $(this).parents('.cuadro-dialogo').find('select[rel="selectorProductoCambio"]');

	if (! target.length)
		return false;

	$.ajax({
		async	: true,
		dataType: 'json',
		url		: webroot + 'compras/ajax_cambio_sin_diferencia_seleccionado/'+id,
		success: function(respuesta) {
			if (! respuesta) {
				alert('Intentelo nuevamente...');
				return false;
			}
			target.html('<option value="">- productos disponibles</option>');
			$.each(respuesta,function(productoId, productoCodigo) {
				target.append('<option value="'+productoId+'">'+productoCodigo+'</option>');
			});
		}
	});
});

$('#cambio.modal button[rel="sin-diferencia"]').live('click',function() {
	var id = $(this).data('id'),
		actual = $(this).parents('.cuadro-dialogo'),
		modal = $(this).parents('.modal'),
		siguiente = modal.find('.detalle'),
		target = siguiente.find('.contenido');
	if (! id)
		return false;
	if (! actual.length)
		return false;
	if (! siguiente.length)
		return false;
	$.ajax({
		async	: true,
		url		: webroot + 'compras/ajax_cambio_sin_diferencia_devueltos/'+id,
		success: function(respuesta) {
			if (! respuesta) {
				alert('Registro invalido.');
				return false;
			}
			target.html(respuesta);
			modal.find('.cuadro-dialogo.status1').fadeOut(500,function() {
				siguiente.fadeIn(600);
			});
		}
	});
});

$('a[rel="cambio-devolucion"]').live('click',function(e) {
	e.preventDefault();
	var id = $(this).data('id');
	if (! id)
		return false;
	if (! $('#anular.modal').length)
		return false;
	$('#cambio.modal span[rel="compra"]').html('');
	$('#cambio.modal span[rel="ra"]').html('');
	$('#cambio.modal span[rel="retiro"]').html('');
	$('#cambio.modal span[rel="desde"]').html('');
	$('#cambio.modal span[rel="hasta"]').html('');
	$('#cambio.modal button[rel="sin-diferencia"]').data('id','');
	$('#cambio.modal button[rel="con-diferencia"]').data('id','');
	$('#cambio.modal button[rel="cancelar"]').data('id','');
	$('#cambio.modal button[rel="guardar"]').data('id','');
	$('#cambio.modal button[rel="guardar"]').data('tipo','');
	$('#cambio.modal .cuadro-dialogo').hide();
	$('#cambio.modal .cuadro-dialogo.detalle .contenido').html('');
	$.ajax({
		async	: true,
		dataType: 'json',
		url		: webroot + 'compras/ajax_devolucion_info/'+id,
		success: function(respuesta) {
			if (! respuesta)
				return false;
			$.each(respuesta,function(dato, valor) {
				var target = $('#cambio.modal span[rel="'+dato+'"]');
				if (target.length) {
					target.html(valor);
				}
			});
			$('#cambio.modal button[rel="sin-diferencia"]').data('id',respuesta.compra_id);
			$('#cambio.modal button[rel="con-diferencia"]').data('id',respuesta.compra_id);
			$('#cambio.modal button[rel="cancelar"]').data('id',respuesta.compra_id);
			$('#cambio.modal button[rel="guardar"]').data('id',respuesta.compra_id);
			$('#cambio.modal .cuadro-dialogo.status1').show();
			$('#cambio.modal').fadeIn(700);
		}
	});
});

$('.modal button[rel="cerrar"]').live('click',function(e) {
	e.preventDefault();
	var modal = $(this).parents('.modal');
	if (! modal.length)
		return false;
	modal.fadeOut(500);
});

$('#anular.modal button[rel="guardar"]').live('click',function() {
	var id = $(this).data('id'),
		tipo = $(this).data('tipo');
	if (! id)
		return false;
	if (! tipo)
		return false;
	if (! confirm('La compra será anulada y se notificara al cliente. ¿Desea continuar?'))
		return false;
	$.ajax({
		async	: true,
		dataType: 'json',
		url		: webroot + 'compras/ajax_finalizar_devolucion',
		type	: 'POST',
		data: { id: id, tipo: tipo, estado: 'ACEPTAR' },
		success: function(respuesta) {
			if (! respuesta)
				return false;
			if (respuesta.estado == 'OK') {
				alert('La compra ha sido anulada.');
				location.reload();
			} else if (respuesta.estado) {
				alert(respuesta.estado);
			} else {
				return false;
			}
		}
	});
	return false;
});

$('.actions a[rel="cancelar"]').live('click',function() {
	var id = $(this).data('id');
	if (! id)
		return false;
	if (! confirm('Se pasará la compra a estado "PAGADO". ¿Desea continuar?'))
		return false;
	$.ajax({
		async	: true,
		dataType: 'json',
		url		: webroot + 'compras/ajax_finalizar_devolucion',
		type	: 'POST',
		data: { id: id, estado: 'CANCELAR' },
		success: function(respuesta) {
			if (! respuesta)
				return false;
			if (respuesta.estado == 'OK') {
				alert('La compra ha vuelto al estado PAGADO.');
				location.reload();
			} else if (respuesta.estado) {
				alert(respuesta.estado);
			} else {
				return false;
			}
		}
	});
	return false;
});

$('.modal button[rel="editar"]').live('click',function() {
	var	elemento = $(this).parents('.cuadro-dialogo'),
		siguiente = elemento.prev('.cuadro-dialogo');
	if (! elemento.length)
		return false;
	if (! siguiente.length)
		return false;
	elemento.fadeOut(500,function() {
		siguiente.fadeIn(600);
	});
});

$('#anular.modal button[rel="debito"]').live('click',function() {
	var id = $(this).data('id'),
		actual = $(this).parents('.cuadro-dialogo'),
		siguiente = $(this).parents('#anular.modal').find('.status2');
	if (! id)
		return false;
	if (! actual)
		return false;
	if (! siguiente.length)
		return false;
	$.ajax({
		async	: true,
		url		: webroot + 'compras/ajax_previsualizar_email_devolucion/debito',
		success: function(respuesta) {
			if (! respuesta)
				return false;
			actual.fadeOut(500,function() {
				siguiente.find('button[rel="guardar"]').data('tipo','debito');
				siguiente.find('.contenido').html(respuesta);
				siguiente.fadeIn(600);
			});
		}
	});
});

$('#anular.modal button[rel="credito"]').live('click',function() {
	var id = $(this).data('id'),
		actual = $(this).parents('.cuadro-dialogo'),
		siguiente = $(this).parents('#anular.modal').find('.status2');
	if (! id)
		return false;
	if (! actual)
		return false;
	if (! siguiente.length)
		return false;
	$.ajax({
		async	: true,
		url		: webroot + 'compras/ajax_previsualizar_email_devolucion/credito',
		success: function(respuesta) {
			if (! respuesta)
				return false;
			actual.fadeOut(500,function() {
				siguiente.find('button[rel="guardar"]').data('tipo','credito');
				siguiente.find('.contenido').html(respuesta);
				siguiente.fadeIn(600);
			});
		}
	});
});

$('a[rel="anula-devolucion"]').live('click',function(e) {
	e.preventDefault();
	var id = $(this).data('id');
	if (! id)
		return false;
	if (! $('#anular.modal').length)
		return false;
	$('#anular.modal span[rel="compra"]').html('');
	$('#anular.modal span[rel="ra"]').html('');
	$('#anular.modal span[rel="retiro"]').html('');
	$('#anular.modal span[rel="desde"]').html('');
	$('#anular.modal span[rel="hasta"]').html('');
	$('#anular.modal span[rel="credito"]').data('id','');
	$('#anular.modal span[rel="debito"]').data('id','');
	$('#anular.modal button[rel="cancelar"]').data('id','');
	$('#anular.modal button[rel="guardar"]').data('id','');
	$('#anular.modal button[rel="guardar"]').data('tipo','');
	$('#anular.modal .cuadro-dialogo').hide();
	$.ajax({
		async	: true,
		dataType: 'json',
		url		: webroot + 'compras/ajax_devolucion_info/'+id,
		success: function(respuesta) {
			if (! respuesta)
				return false;
			$.each(respuesta,function(dato, valor) {
				var target = $('#anular.modal span[rel="'+dato+'"]');
				if (target.length) {
					target.html(valor);
				}
			});
			$('#anular.modal button[rel="credito"]').data('id',respuesta.compra_id);
			$('#anular.modal button[rel="debito"]').data('id',respuesta.compra_id);
			$('#anular.modal button[rel="cancelar"]').data('id',respuesta.compra_id);
			$('#anular.modal button[rel="guardar"]').data('id',respuesta.compra_id);
			$('#anular.modal .cuadro-dialogo.status1').show();
			$('#anular.modal').fadeIn(700);
		}
	});
});
</script>