<style>
.categoria-hijo > ul > li {
	list-style: square;
	margin-left: 10px;
	width: 650px !important;
}
.categoria-hijo > ul > li > a {
	text-decoration: none !important;
	color: #666;
}
.listado-productos > ul > li {
	list-style: square;
	margin-left: 10px;
	width: 650px !important;
}
.listado-productos > ul > li > a {
	text-decoration: none !important;
	color: #666;
}
</style>
<div class="col02">
	<h1 class="titulo" style="border-bottom: 0;">Previsualización de <? __('categoria');?></h1>
	<h2 class="subtitulo">Datos de la categoria</h2>
	<div class="previsualizar">
		<ul>
			<li class="extendido">
				<span><? __('Nombre'); ?>:</span>
				<?= $categoria['Categoria']['nombre']; ?>&nbsp;
			</li>
			<li class="extendido">
				<span><? __('Sexo'); ?>:</span>
				<?= $categoria['Sexo']['nombre']; ?>&nbsp;
			</li>
			<li class="extendido">
				<span><? __('Rango tallas'); ?>:</span>
				<?= ($categoria['Categoria']['desde'] && $categoria['Categoria']['hasta']) ? '['.$categoria['Categoria']['desde'].' - '.$categoria['Categoria']['hasta'].']' : '' ; ?>&nbsp;
			</li>
			<li class="extendido">
				<span><? __('Medios'); ?>:</span>
				<?= (isset($categoria['Categoria']['medios']) && $categoria['Categoria']['medios']) ? 'SI' : 'NO'; ?>&nbsp;
			</li>
			<li class="extendido">
				<span><? __('Publico'); ?>:</span>
				<?= (isset($categoria['Categoria']['publico']) && $categoria['Categoria']['publico']) ? 'SI' : 'NO'; ?>&nbsp;
			</li>
			<? if (isset($categoria['Categoria']['parent_id']) && $categoria['Categoria']['parent_id']) : ?>
			<li class="extendido">
				<span><? __('Categoria Padre'); ?>:</span>
				<a href="<?= $this->Html->url(array('action' => 'view', $categoria['ParentCategoria']['id'])); ?>"><?= $categoria['ParentCategoria']['nombre']; ?></a>&nbsp;
			</li>
			<? endif; ?>
		</ul>
	</div>
	<? if ($categoria['ChildCategoria']) : ?>
	<h2 class="subtitulo">Categorias Hijo</h2>
	<div class="previsualizar categoria-hijo">
		<ul>
			<? foreach ($categoria['ChildCategoria'] as $child) : ?>
			<li class="extendido">
				<a href="<?= $this->Html->url(array('action' => 'view', $child['id'])); ?>"><?= $child['nombre']; ?></a>&nbsp;
			</li>
			<? endforeach; ?>
		</ul>
	</div>
	<? endif; ?>
	<? if ($categoria['Producto']) : ?>
	<h2 class="subtitulo">Productos</h2>
	<div class="previsualizar listado-productos">
		<ul>
			<? foreach ($categoria['Producto'] as $producto) : ?>
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
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $categoria['Categoria']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $categoria['Categoria']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $categoria['Categoria']['id'])); ?>
	</div>
</div>
