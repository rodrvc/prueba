<div class="col02">
	<h1 class="titulo"><? __('Bloqueos');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('producto'); ?></th>
			<th><?= $this->Paginator->sort('talla'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $bloqueos as $bloqueo ) : ?>
		<tr>
			<td><?= $bloqueo['Producto']['codigo_completo']; ?>&nbsp;</td>
			<td><?= $bloqueo['Bloqueo']['talla']; ?>&nbsp;</td>
			<td class="actions">
			
		
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $bloqueo['Bloqueo']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $bloqueo['Bloqueo']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>

	</table>

	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>