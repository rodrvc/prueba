<div class="col02">
	<h1 class="titulo"><? __('Stocks');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('tienda_id'); ?></th>
			<th><?= $this->Paginator->sort('codigo', 'Producto.codigo'); ?></th>
			<th><?= $this->Paginator->sort('talla'); ?></th>
			<th><?= $this->Paginator->sort('cantidad'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $stocks as $stock ) : ?>
		<tr>
			<td><?= $this->Html->link($stock['Tienda']['nombre'], array('controller' => 'tiendas', 'action' => 'view', $stock['Tienda']['id'])); ?></td>
			<td><?= $this->Html->link($stock['Producto']['codigo'] . '' . $stock['Producto']['Color']['codigo'], array('controller' => 'Producto', 'action' => 'view', $stock['Producto']['id'])); ?></td>
			<td><?= $stock['Stock']['talla']; ?>&nbsp;</td>
			<td><?= $stock['Stock']['cantidad']; ?>&nbsp;</td>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $stock['Stock']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $stock['Stock']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $stock['Stock']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $stock['Stock']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>

	</table>

	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>