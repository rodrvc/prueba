<div class="col02">
	<h1 class="titulo">Previsualización de <? __('coleccion');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Nombre'); ?>:</span><p><?= $coleccion['Coleccion']['nombre']; ?>&nbsp;</p></li>
		</ul>
	</div>
	<? if ($coleccion['Producto']) : ?>
	<h2 class="subtitulo">Productos</h2>
	<div class="previsualizar listado-productos">
		<ul>
			<? foreach ($coleccion['Producto'] as $producto) : ?>
			<li class="extendido">
				<a href="<?= $this->Html->url(array('controller' => 'productos', 'action' => 'view', $producto['id'])); ?>">
					<?= $producto['nombre']; ?>
					<?= (isset($producto['codigo_completo']) && $producto['codigo_completo']) ? ' ['.$producto['codigo_completo'].']' : '' ; ?>
				&nbsp;</a>
			</li>
			<? endforeach; ?>
		</ul>
	</div>
	<? endif; ?>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $coleccion['Coleccion']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $coleccion['Coleccion']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $coleccion['Coleccion']['id'])); ?>
	</div>
</div>
