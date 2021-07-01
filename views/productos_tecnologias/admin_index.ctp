<div class="col02">
	<h1 class="titulo"><? __('Productos Tecnologias');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('productos_id'); ?></th>
			<th><?= $this->Paginator->sort('tecnologia_id'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $productosTecnologias as $productosTecnologia ) : ?>
		<tr>
			<td><?= $this->Html->link($productosTecnologia['Productos']['id'], array('controller' => 'productos', 'action' => 'view', $productosTecnologia['Productos']['id'])); ?></td>
			<td><?= $this->Html->link($productosTecnologia['Tecnologia']['id'], array('controller' => 'tecnologias', 'action' => 'view', $productosTecnologia['Tecnologia']['id'])); ?></td>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $productosTecnologia['ProductosTecnologia']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $productosTecnologia['ProductosTecnologia']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $productosTecnologia['ProductosTecnologia']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $productosTecnologia['ProductosTecnologia']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>

	</table>

	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>