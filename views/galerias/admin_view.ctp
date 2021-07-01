<div class="col02">
	<h1 class="titulo">Previsualización de <? __('galeria');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Imagen'); ?>:</span><p><?= $galeria['Galeria']['imagen']; ?>&nbsp;</p></li>
			<li><span><? __('Producto'); ?>:</span><p><?= $this->Html->link($galeria['Producto']['id'], array('controller' => 'productos', 'action' => 'view', $galeria['Producto']['id'])); ?>&nbsp;</p></li>
		</ul>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $galeria['Galeria']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $galeria['Galeria']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $galeria['Galeria']['id'])); ?>
	</div>
</div>
