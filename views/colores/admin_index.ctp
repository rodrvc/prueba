<div class="col02">
	<h1 class="titulo"><? __('Colores');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('nombre'); ?></th>
			<th><?= $this->Paginator->sort('codigo'); ?></th>
			<th><?= $this->Paginator->sort('Primario'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $colores as $color ) : ?>
		<tr>
			<td><?= $color['Color']['nombre']; ?>&nbsp;</td>
			<td><?= $color['Color']['codigo']; ?>&nbsp;</td>
			<td><?= $color['Primario']['nombre']; ?>&nbsp;</td>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $color['Color']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $color['Color']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $color['Color']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $color['Color']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>

	</table>

	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>