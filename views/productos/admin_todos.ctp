<div class="col02">
	<div class="botones" style="position: absolute; top: 18px; right: 40px; width: 430px;">
		<?= $this->Form->create('Producto'); ?>
		<?= $this->Form->input('buscar', array('label' => false,
											   'div' => false,
											   'class' => 'clase-input',
											   'placeholder' => '- nombre o codigo de producto -')); ?>
		<a href="#" class="buscar-todas" style="height: 14px; padding-top: 5px;"><span class="buscar">Buscar</span></a>
		<?= $this->Form->end(); ?>
	</div>
	<h1 class="titulo"><? __('Productos');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('nombre'); ?></th>
			<th><?= $this->Paginator->sort('categoria_id'); ?></th>
			<th><?= $this->Paginator->sort('foto'); ?></th>
			<th><?= $this->Paginator->sort('codigo'); ?></th>
			<th><?= $this->Paginator->sort('precio'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $productos as $producto ) : ?>
		<tr>
			<td><?= $producto['Producto']['nombre']; ?>&nbsp;</td>
			<td><?= $this->Html->link($producto['Categoria']['nombre'], array('controller' => 'categorias', 'action' => 'view', $producto['Categoria']['id'])); ?></td>
			<td>
				<? if ( isset($producto['Producto']['foto']['mini']) && $producto['Producto']['foto']['mini'] ) : ?>
				<?= $this->Html->image($producto['Producto']['foto']['mini']); ?>
				<? else : ?>
				<?= $this->Html->image('sin_imagen.jpg'); ?>
				<? endif; ?>
				&nbsp;
			</td>
			<td><?= $producto['Producto']['codigo'] . '' . $producto['Color']['codigo']; ?></td>
			<? if ( $producto['Producto']['oferta'] == 1 ) : ?>
			<td style="color: Red;"><?= $producto['Producto']['precio_oferta']; ?>&nbsp;</td>
			<? else : ?>
			<td><?= $producto['Producto']['precio']; ?>&nbsp;</td>
			<? endif; ?>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $producto['Producto']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $producto['Producto']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $producto['Producto']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $producto['Producto']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>

	</table>

	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>