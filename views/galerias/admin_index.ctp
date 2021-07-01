<div class="col02">
	<h1 class="titulo"><? __('Galerias');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('imagen'); ?></th>
			<th><?= $this->Paginator->sort('producto_id'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $galerias as $galeria ) : ?>
		<tr>
			<td><?= $galeria['Galeria']['imagen']; ?>&nbsp;</td>
			<td><?= $this->Html->link($galeria['Producto']['id'], array('controller' => 'productos', 'action' => 'view', $galeria['Producto']['id'])); ?></td>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $galeria['Galeria']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $galeria['Galeria']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $galeria['Galeria']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $galeria['Galeria']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>

	</table>

	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>