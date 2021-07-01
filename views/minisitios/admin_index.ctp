<div class="col02">
	<h1 class="titulo"><? __('Minisitios');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('nombre'); ?></th>
			<th><?= $this->Paginator->sort('tipo'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $minisitios as $minisitio ) : ?>
		<tr>
			<td><?= $minisitio['Minisitio']['nombre']; ?>&nbsp;</td>
			<? if ( $minisitio['Minisitio']['tipo'] == 0 ) : ?>
			<td>Pre-Diseñada</td>
			<? else : ?>
			<td>Html</td>
			<? endif; ?>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $minisitio['Minisitio']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $minisitio['Minisitio']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $minisitio['Minisitio']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $minisitio['Minisitio']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>
	</table>

	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>