<style type="text/css" media="all">
h2.subtitulo {
	color: #0080c0;
	font-weight: bold;
	padding-left: 20px;
}
li.cuadro {
	border-bottom: none !important;
	padding-top: 20px !important;
	width: 320px !important;
	padding-left: 5px !important;
	padding-right: 5px !important;
}
li.cuadro table {
	width: 100%;
	border: 1px solid #0080c0;
	background-color: #fff;
	margin: auto;
	padding: 15px;
	border-radius: 7px;
}
li.cuadro table tr td {
	text-align: right;
	line-height: 0 !important;
}
li.cuadro table tr td.title-left {
	text-align: left;
	font-weight: bold;
}
li.invalido, li.invalido:hover {
	border: 1px solid #800000 !important;
	background-color: rgba(255,0,0,.3) !important;
}
</style>
<div class="contenedor-papa" style="float: left; width: 680px;">
	<div class="col02">
		<!-- DATOS COMPRA -->
		<h1 class="titulo">Cambio de dirección</h1>
		<h2 class="subtitulo">Compra N°<?= $compra['Compra']['id']; ?></h2>
		<div class="previsualizar">
			<ul>
				<li class="extendido">
					<span><? __('Nombre cliente'); ?>:</span>
					<p><?= $compra['Usuario']['nombre'].' '.$compra['Usuario']['apellido_paterno']; ?>&nbsp;</p>
				</li>
				<li class="extendido">
					<span><? __('RUT'); ?>:</span>
					<p><?= $compra['Usuario']['rut']; ?>&nbsp;</p>
				</li>
				<li class="extendido">
					<span><? __('Email'); ?>:</span>
					<p><?= $compra['Usuario']['email']; ?>&nbsp;</p>
				</li>
				<li class="cuadro">
					<table>
						<tr>
							<td class="title-left">subtotal</td>
							<td><?= $this->Shapeups->moneda($compra['Compra']['subtotal']); ?></td>
						</tr>
						<tr>
							<td class="title-left">iva</td>
							<td><?= $this->Shapeups->moneda($compra['Compra']['iva']); ?></td>
						</tr>
						<tr>
							<td class="title-left">neto</td>
							<td><?= $this->Shapeups->moneda($compra['Compra']['neto']); ?></td>
						</tr>
						<tr>
							<td class="title-left">descuento</td>
							<td><?= $this->Shapeups->moneda($compra['Compra']['descuento']); ?></td>
						</tr>
						<tr>
							<td class="title-left">total</td>
							<td><?= $this->Shapeups->moneda($compra['Compra']['total']); ?></td>
						</tr>
						<tr>
							<td class="title-left">valor despacho</td>
							<td><?= $this->Shapeups->moneda($compra['Compra']['valor_despacho']); ?></td>
						</tr>
					</table>
				</li>
				<li class="cuadro">
					<table>
						<tr>
							<td class="title-left">estado</td>
							<td><?= ($compra['Compra']['estado'] == 1) ? '<img src="'.$this->Html->url('/img/iconos/tick_16.png').'" />':'<img src="'.$this->Html->url('/img/iconos/delete_16.png').'" />'; ?></td>
						</tr>
						<tr>
							<td class="title-left">rural</td>
							<td><?= ($compra['Compra']['rural']) ? '<img src="'.$this->Html->url('/img/iconos/tick_16.png').'" />':'<img src="'.$this->Html->url('/img/iconos/delete_16.png').'" />'; ?></td>
						</tr>
						<tr>
							<td class="title-left">local</td>
							<td><?= ($compra['Compra']['local']) ? '<img src="'.$this->Html->url('/img/iconos/tick_16.png').'" />':'<img src="'.$this->Html->url('/img/iconos/delete_16.png').'" />'; ?></td>
						</tr>
					</table>
				</li>
			</ul>
		</div>
		<h2 class="subtitulo">Despacho</h2>
		<div class="previsualizar">
			<ul>
				<li class="extendido">
					<span>Dirección:</span>
					<?= $compra['Direccion']['calle'].' '.$compra['Direccion']['numero']; ?><?= ($compra['Direccion']['depto']) ? ' - dpto.'.$compra['Direccion']['depto']:''; ?>
				</li>
				<li class="extendido">
					<span>Comuna:</span>
					<?= $compra['Comuna']['nombre']; ?>&nbsp;
				</li>
				<li class="extendido">
					<span>Región:</span>
					<?= $compra['Region']['nombre']; ?>&nbsp;
				</li>
				<li class="extendido">
					<span>Otras indicaciones:</span>
					<?= $compra['Direccion']['otras_indicaciones']; ?>&nbsp;
				</li>
				<li class="extendido">
					<span>Telefono:</span>
					<?= $compra['Direccion']['telefono']; ?>&nbsp;
				</li>
				<li class="extendido">
					<span>Celular:</span>
					<?= $compra['Direccion']['celular']; ?>&nbsp;
				</li>
				<li class="extendido">
					<span>Código postal:</span>
					<?= $compra['Direccion']['codigo_postal']; ?>&nbsp;
				</li>
			</ul>
		</div>
		<h2 class="subtitulo">Otras direcciones del cliente</h2>
		<?= $this->Form->create('Compra', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
		<?= $this->Form->hidden('id',array('value' => $compra['Compra']['id'])); ?>
		<ul class="edit">
			<li>
				<?
				$options = array();
				foreach ($direcciones as $direccion)
				{
					$options[$direccion['Direccion']['id']] = $direccion['Direccion']['calle'].' '.$direccion['Direccion']['numero'];
					if ($direccion['Direccion']['depto'])
						$options[$direccion['Direccion']['id']].=', dpto.'.$direccion['Direccion']['depto'];
					if ($direccion['Comuna']['nombre'])
						$options[$direccion['Direccion']['id']].=' - '.$direccion['Comuna']['nombre'];
				}
				echo $this->Form->input('Despacho.direccion_id', array('type' => 'select', 'options' => $options, 'empty' => '- seleccione direccion'));
				?>
			</li>
		</ul>
		<div class="botones">
			<a href="#" rel="guardarOtraDireccion"><span class="guardar">Guardar</span></a>
		</div>
		<?= $this->Form->end(); ?>
		<h2 class="subtitulo">Nueva dirección</h2>
		<?= $this->Form->create('Compra', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
		<?= $this->Form->hidden('id',array('value' => $compra['Compra']['id'])); ?>
		<ul class="edit">
			<li>
			<?= $this->Form->input('Direccion.calle', array('rel' => 'obligatorio')); ?>
			</li><li>
			<?= $this->Form->input('Direccion.numero', array('rel' => 'obligatorio')); ?>
			</li><li>
			<?= $this->Form->input('Direccion.depto'); ?>
			</li><li>
			<?= $this->Form->input('Direccion.region_id', array('empty' => '- seleccione region','rel' => 'obligatorio')); ?>
			</li><li>
			<?= $this->Form->input('Direccion.comuna_id', array('empty' => '- seleccione comuna','rel' => 'obligatorio')); ?>
			</li><li>
			<?= $this->Form->input('Direccion.codigo_postal'); ?>
			</li><li>
			<?= $this->Form->input('Direccion.telefono', array('rel' => 'obligatorio')); ?>
			</li><li>
			<?= $this->Form->input('Direccion.celular', array('rel' => 'obligatorio')); ?>
			</li><li>
			<?= $this->Form->input('Direccion.otras_indicaciones', array('type' => 'textarea')); ?>
			</li><li>
			<?= $this->Form->input('Direccion.nombre', array('rel' => 'obligatorio')); ?>
			</li>
		</ul>
		<div class="botones">
			<a href="#" rel="guardarNuevaDireccion"><span class="guardar">Guardar</span></a>
		</div>
		<?= $this->Form->end(); ?>
	</div>
</div>
<script>
$('a[rel="guardarOtraDireccion"]').click(function(e) {
	e.preventDefault();
	var formulario = $(this).parents('form'),
		elemento = formulario.find('#DespachoDireccionId');
	if (! elemento.length) {
		return false;
	}
	elemento.parent().removeClass('invalido');
	if (! elemento.val()) {
		elemento.parent().addClass('invalido');
		alert('Para realizar el cambio de direccion debe seleccionar una direccion del listado de direcciones del cliente.');
		return false;
	}
	formulario.submit();
});

$('a[rel="guardarNuevaDireccion"]').click(function(e) {
	e.preventDefault();
	var formulario = $(this).parents('form');
	if (! formulario.length) {
		return false;
	}
	formulario.find('.invalido').removeClass('invalido');
	var invalidos = false;
	formulario.find('[rel="obligatorio"]').each(function(index,elemento) {
		if (! $(elemento).val()) {
			$(elemento).parent().addClass('invalido');
			invalidos = true;
		}
	});
	if (invalidos) {
		alert('Para realizar el cambio de direccion debe llenar los campos destacados.');
		return false;
	}
	formulario.submit();
});
</script>
