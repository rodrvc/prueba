<div class="col02">
	<h1 class="titulo">Previsualización de <? __('Detalle');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Nombre'); ?>:</span><p><?= $detalle['Detalle']['nombre']; ?>&nbsp;</p></li>
			<li><span><? __('Descripcion'); ?>:</span><p><?= $detalle['Detalle']['descripcion']; ?>&nbsp;</p></li>
			<li><span><? __('Compra'); ?>:</span><p><?= $this->Html->link($detalle['Compra']['id'], array('controller' => 'compras', 'action' => 'view', $detalle['Compra']['id'])); ?>&nbsp;</p></li>
		</ul>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $detalle['Detalle']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $detalle['Detalle']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $detalle['Detalle']['id'])); ?>
	</div>
</div>
