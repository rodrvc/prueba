<div class="col02">
	<h1 class="titulo"><? __('Descripciones');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('nombre'); ?></th>
			<th><?= $this->Paginator->sort('compra_id'); ?></th>
			<th><?= $this->Paginator->sort('descripcion'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $descripciones as $descripcion ) : ?>
		<tr>
			<td><?= $descripcion['Descripcion']['nombre']; ?>&nbsp;</td>
			<td><?= $this->Html->link($descripcion['Compra']['neto'], array('controller' => 'compras', 'action' => 'view', $descripcion['Compra']['id'])); ?></td>
			<td><?= $descripcion['Descripcion']['descripcion']; ?>&nbsp;</td>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $descripcion['Descripcion']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $descripcion['Descripcion']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $descripcion['Descripcion']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $descripcion['Descripcion']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>

	</table>

	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>