<div class="col02">
	<h1 class="titulo"><? __('Detalles');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('nombre'); ?></th>
			<th><?= $this->Paginator->sort('descripcion'); ?></th>
			<th><?= $this->Paginator->sort('compra_id'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $detalles as $detalle ) : ?>
		<tr>
			<td><?= $detalle['Detalle']['nombre']; ?>&nbsp;</td>
			<td><?= $detalle['Detalle']['descripcion']; ?>&nbsp;</td>
			<td><?= $this->Html->link($detalle['Compra']['id'], array('controller' => 'compras', 'action' => 'view', $detalle['Compra']['id'])); ?></td>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $detalle['Detalle']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $detalle['Detalle']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $detalle['Detalle']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $detall['Detall']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>

	</table>

	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>