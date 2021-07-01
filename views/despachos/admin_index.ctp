<div class="col02">
	<h1 class="titulo"><? __('Despachos');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('usuario_id'); ?></th>
			<th><?= $this->Paginator->sort('direccion_id'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $despachos as $despacho ) : ?>
		<tr>
			<td><?= $this->Html->link($despacho['Usuario']['email'], array('controller' => 'usuarios', 'action' => 'view', $despacho['Usuario']['id'])); ?></td>
			<td><?= $this->Html->link( $despacho['Direccion']['calle'] . ' # ' . $despacho['Direccion']['numero'], array('controller' => 'direcciones', 'action' => 'view', $despacho['Direccion']['id'])); ?></td>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $despacho['Despacho']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $despacho['Despacho']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $despacho['Despacho']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $despacho['Despacho']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>

	</table>

	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>