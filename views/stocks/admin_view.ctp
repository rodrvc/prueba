<div class="col02">
	<h1 class="titulo">Previsualización de <? __('stock');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Tienda'); ?>:</span><p><?= $this->Html->link($stock['Tienda']['id'], array('controller' => 'tiendas', 'action' => 'view', $stock['Tienda']['id'])); ?>&nbsp;</p></li>
			<li><span><? __('Producto'); ?>:</span><p><?= $this->Html->link($stock['Producto']['nombre'], array('controller' => 'zapatos', 'action' => 'view', $stock['Producto']['id'])); ?>&nbsp;</p></li>
			<li><span><? __('Talla'); ?>:</span><p><?= $stock['Stock']['talla']; ?>&nbsp;</p></li>
			<li><span><? __('Cantidad'); ?>:</span><p><?= $stock['Stock']['cantidad']; ?>&nbsp;</p></li>
		</ul>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $stock['Stock']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $stock['Stock']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $stock['Stock']['id'])); ?>
	</div>
</div>
