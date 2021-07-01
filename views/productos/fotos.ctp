<?= $this->Html->script('jquery-1.11.1.min'); ?>
<h1><?= utf8_decode('IMAGENES COLECCIÓN 2016 II'); ?></h1>
<?= $this->Form->create('Producto'); ?>
	<table>
		<tr>
			<td>codigo</td>
			<? foreach ($cortes as $corte) : ?>
			<td><?= $corte; ?></td>
			<? endforeach; ?>
		</tr>
		<? foreach ($productos as $producto) : ?>
			<tr height="180">
				<td>
					<label>
						<input type="checkbox" name="corregir" value="<?= $producto['Producto']['id']; ?>">
						<?= $producto['Producto']['codigo_completo']; ?>
					</label>
				</td>
			<? foreach ($cortes as $corte) : ?>
				<td><?= (isset($producto['Producto']['foto'][$corte]) && $producto['Producto']['foto'][$corte] ? '<img src="http://www.skechers.cl/img/'.$producto['Producto']['foto'][$corte].'" style="max-width: 200px;" />' : ''); ?></td>
			<? endforeach; ?>
			</tr>
		<? endforeach; ?>
	</table>
	<div style="position: fixed; top: 50px; left: 750px;">
		<?= $this->Form->input('codigos', array(
			'type' => 'textarea',
			'div' => false,
			'label' => false,
			'cols' => 60,
			'rows' => 20,
			'readonly' => 'readonly'
		)); ?>
	</div>
<?= $this->Form->end(); ?>
<script>
$(document).on('change' , 'input[name="corregir"]', function() {
	var valor = '';
	$('input[name="corregir"]').each(function(index, elemento) {
		if ($(elemento).is(':checked')) {
			valor+= (valor ? ', ' : '') + $(elemento).val();
		}
	});
	$('#ProductoCodigos').val(valor);
});
</script>