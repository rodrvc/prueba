<div class="col02">
	<h1 class="titulo"><? __('Direcciones');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('usuario_id'); ?></th>
			<th><?= $this->Paginator->sort('calle'); ?></th>
			<th><?= $this->Paginator->sort('numero'); ?></th>
			<th><?= $this->Paginator->sort('depto'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $direcciones as $direccion ) : ?>
		<tr>
			<td><?= $this->Html->link($direccion['Usuario']['email'], array('controller' => 'usuarios', 'action' => 'view', $direccion['Usuario']['id'])); ?></td>
			<td><?= $direccion['Direccion']['calle']; ?>&nbsp;</td>
			<td><?= $direccion['Direccion']['numero']; ?>&nbsp;</td>
			<td><?= $direccion['Direccion']['depto']; ?>&nbsp;</td>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $direccion['Direccion']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $direccion['Direccion']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $direccion['Direccion']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $direccion['Direccion']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>

	</table>

	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>