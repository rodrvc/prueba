<div class="col02">
	<h1 class="titulo"><? __('Tiendas');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('codigo'); ?></th>
			<th><?= $this->Paginator->sort('nombre'); ?></th>
			<th><?= $this->Paginator->sort('direccion'); ?></th>
			<th><?= $this->Paginator->sort('telefono'); ?></th>
			<th><?= $this->Paginator->sort('latitud'); ?></th>
			<th><?= $this->Paginator->sort('outlet'); ?></th>
			<th><?= $this->Paginator->sort('remodelacion'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
		<? foreach ( $tiendas as $tienda ) : ?>
		<tr>
			<td><?= $tienda['Tienda']['codigo']; ?>&nbsp;</td>
			<td><?= $tienda['Tienda']['nombre']; ?>&nbsp;</td>
			<td><?= $tienda['Tienda']['direccion']; ?>&nbsp;</td>
			<td><?= $tienda['Tienda']['telefono']; ?>&nbsp;</td>
			<td><?= $tienda['Tienda']['latitud']; ?>&nbsp;</td>
			<td><?= ( (isset($tienda['Tienda']['outlet']) && $tienda['Tienda']['outlet']) ? '<img src="'.$this->Html->url('/img/iconos/tick_16.png').'" />' : '' ); ?>&nbsp;</td>
			<td><?= ( (isset($tienda['Tienda']['remodelacion']) && $tienda['Tienda']['remodelacion']) ? '<img src="'.$this->Html->url('/img/iconos/tick_16.png').'" />' : '' ); ?>&nbsp;</td>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $tienda['Tienda']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $tienda['Tienda']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $tienda['Tienda']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $tienda['Tienda']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>
	</table>
	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>