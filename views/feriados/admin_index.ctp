<div class="col02">
	<h1 class="titulo"><? __('Feriados');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('nombre'); ?></th>
			<th><?= $this->Paginator->sort('fecha'); ?></th>
			<th><?= $this->Paginator->sort('Repetir anual', 'repetir'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $feriados as $feriado ) : ?>
		<tr>
			<td><?= $feriado['Feriado']['nombre']; ?>&nbsp;</td>
			<td><?= date('d-m-Y', strtotime($feriado['Feriado']['fecha'])); ?>&nbsp;</td>
			<td><?= ( ($feriado['Feriado']['repetir'] == 1) ? 'si':'no' ); ?>&nbsp;</td>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $feriado['Feriado']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $feriado['Feriado']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $feriado['Feriado']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $feriado['Feriado']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>

	</table>

	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>