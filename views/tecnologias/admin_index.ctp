<div class="col02">
	<h1 class="titulo"><? __('Tecnologias');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('nombre'); ?></th>
			<th><?= $this->Paginator->sort('descripcion'); ?></th>
			<th><?= $this->Paginator->sort('imagen'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $tecnologias as $tecnologia ) : ?>
		<tr>
			<td><?= $tecnologia['Tecnologia']['nombre']; ?>&nbsp;</td>
			<td><?= $tecnologia['Tecnologia']['descripcion']; ?>&nbsp;</td>
			<td><?= $this->Html->image($tecnologia['Tecnologia']['imagen']['mini']); ?>&nbsp;</td>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $tecnologia['Tecnologia']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $tecnologia['Tecnologia']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $tecnologia['Tecnologia']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $tecnologia['Tecnologia']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>

	</table>

	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>