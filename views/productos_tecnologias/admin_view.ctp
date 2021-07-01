<div class="col02">
	<h1 class="titulo">Previsualización de <? __('productosTecnologia');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Productos'); ?>:</span><p><?= $this->Html->link($productosTecnologia['Productos']['id'], array('controller' => 'productos', 'action' => 'view', $productosTecnologia['Productos']['id'])); ?>&nbsp;</p></li>
			<li><span><? __('Tecnologia'); ?>:</span><p><?= $this->Html->link($productosTecnologia['Tecnologia']['id'], array('controller' => 'tecnologias', 'action' => 'view', $productosTecnologia['Tecnologia']['id'])); ?>&nbsp;</p></li>
		</ul>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $productosTecnologia['ProductosTecnologia']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $productosTecnologia['ProductosTecnologia']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $productosTecnologia['ProductosTecnologia']['id'])); ?>
	</div>
</div>
