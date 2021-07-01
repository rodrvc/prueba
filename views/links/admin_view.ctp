<div class="col02">
	<h1 class="titulo">Previsualización de <? __('link');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Ruta'); ?>:</span><p><?= $link['Link']['ruta']; ?>&nbsp;</p></li>
			<li><span><? __('Controlador'); ?>:</span><p><?= $link['Link']['controlador']; ?>&nbsp;</p></li>
			<li><span><? __('Action'); ?>:</span><p><?= $link['Link']['action']; ?>&nbsp;</p></li>
			<li><span><? __('Parametro'); ?>:</span><p><?= $link['Link']['parametro']; ?>&nbsp;</p></li>
			<li><span><? __('Activo'); ?>:</span><p><?= $link['Link']['activo']; ?>&nbsp;</p></li>
		</ul>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $link['Link']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $link['Link']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $link['Link']['id'])); ?>
	</div>
</div>
