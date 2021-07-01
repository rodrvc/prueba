<div class="col02">
	<?= $this->Form->create('Administrador', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Compras despachadas'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('fecha', array('type' => 'date')); ?></li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="buscar">Filtrar</span></a>
	</div>
	<?= $this->Form->end(); ?>
	<h1 class="titulo"><? __('Administracion');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th>Compra #ID</th>
			<th>P.Number</th>
			<th>C.Despacho</th>
			<th>Local</th>
			<th>Enviado</th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
		<? if ($compras) : ?>
			<? foreach ($compras as $compra) : ?>
				<tr>
					<td><?= $compra['Compra']['id']; ?></td>
					<td><?= $compra['Compra']['picking_number']; ?></td>
					<td><?= $compra['Compra']['cod_despacho']; ?></td>
					<td><?= ($compra['Compra']['local'])?'si':'no'; ?></td>
					<td rel="target" style="background-color: <?= ($compra['Compra']['mail_confirmacion'])?'#80ff80':'#ff8080'; ?>"><?= ($compra['Compra']['mail_confirmacion'])?'si':'no'; ?></td>
					<td>
						<? if ($compra['Compra']['local'] != 1) : ?>
						<a href="#" title="enviar" rel="enviarCorreo" data-id="<?= $compra['Compra']['id']; ?>"><img src="<?= $this->Html->url('/img/iconos/letter_16.png'); ?>" /></a>
						<? endif; ?>
					</td>
				</tr>
			<? endforeach; ?>
		<? else : ?>
		
		<? endif; ?>
	</table>
</div>
<script>
$('a[rel="enviarCorreo"]').click(function(e) {
	e.preventDefault();
	var boton = $(this),
		id = boton.data('id'),
		contenedor = boton.parents('tr'),
		target = contenedor.find('td[rel="target"]');
	if (! id) {
		return false;
	}

	$.ajax({
		type		: 'POST',
		url			: webroot + 'administradores/ajax_enviar_correo_despacho',
		data		: { id : id },
		success: function(respuesta) {
			if (respuesta) {
				if (respuesta == 'OK') {
					target.css({ backgroundColor: '#80ff80' });
				} else {
					alert(respuesta);
				}
			} else {
				alert('ERROR');
			}
		},
		error: function() {
			alert('ERROR');
		}
	});
});
</script>
