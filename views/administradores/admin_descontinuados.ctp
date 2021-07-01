<div class="col02">
 	<h1 class="titulo">
		<? __('Productos descontinuados'); ?>
	</h1>
	<? if (isset($productos) && $productos) : ?>
	<?= $this->Form->create('Administrador'); ?>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th>ID</th>
			<th>codigo</th>
			<th>imagen</th>
			<th>Categoria</th>
			<th></th>
		</tr>
		<? foreach ($productos as $producto) : ?>
			<tr>
				<td>
					<label>
					<?= $this->Form->input('Producto.'.$producto['Producto']['id'].'.id',array('type' => 'checkbox',
																							   'value' => $producto['Producto']['id'],
																							   'div' => false,
																							   'label' => false)); ?>
					<?= $producto['Producto']['id']; ?>
					</label>
					<?= $this->Form->hidden('Producto.'.$producto['Producto']['id'].'.categoria_id',array('value' => $producto['Producto']['categoria_id'])); ?>
				</td>
				<td><?= $producto['Producto']['codigo_completo']; ?></td>
				<td><img src="<?= $this->Html->url('/img/'.$producto['Producto']['foto']['mini']); ?>" /></td>
				<td><?= $producto['Categoria']['nombre']; ?></td>
				<td>
					<a href="<?= $this->Html->url(array('controller' => 'productos', 'action' => 'edit', $producto['Producto']['id'])); ?>" style="margin: 0 7px;" target="_blank">edit</a>
				</td>
			</tr>
		<? endforeach; ?>
	</table>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
	<? else : ?>
	No se encontraron productos descontinuados...
	<? endif; ?>
</div>
