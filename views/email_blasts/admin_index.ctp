<div class="col02">
	<h1 class="titulo"><? __('Email Blasts');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('nombre'); ?></th>
			<th><?= $this->Paginator->sort('fecha'); ?></th>
			<th><?= $this->Paginator->sort('monto'); ?></th>
			<th><?= $this->Paginator->sort('administrador_id'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $emailBlasts as $emailBlast ) : ?>
		<tr>
			<td><?= $emailBlast['EmailBlast']['nombre']; ?>&nbsp;</td>
			<td><?= $emailBlast['EmailBlast']['fecha']; ?>&nbsp;</td>
			<td><?= $emailBlast['EmailBlast']['monto']; ?>&nbsp;</td>
			<td><?= $this->Html->link($emailBlast['Administrador']['id'], array('controller' => 'administradores', 'action' => 'view', $emailBlast['Administrador']['id'])); ?></td>
			<td class="actions">
				<? //$this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $emailBlast['EmailBlast']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $emailBlast['EmailBlast']['id']), array('escape' => false)); ?>
				<? if ($authUser['perfil'] == 3) : ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $emailBlast['EmailBlast']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $emailBlast['EmailBlast']['id'])); ?>
				<? endif; ?>
			</td>
		</tr>
		<? endforeach; ?>

	</table>

	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>