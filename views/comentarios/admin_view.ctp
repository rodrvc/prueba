<div class="col02">
	<h1 class="titulo">Previsualización de <? __('comentario');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Usuario'); ?>:</span><p><?= $this->Html->link($comentario['Usuario']['nombre'] . ' ' . $comentario['Usuario']['apellido_paterno'], array('controller' => 'usuarios', 'action' => 'view', $comentario['Usuario']['id'])); ?>&nbsp;</p></li>
			<li><span><? __('Asunto'); ?>:</span><p><?= $comentario['Comentario']['asunto']; ?>&nbsp;</p></li>
			<li><span><? __('Producto'); ?>:</span><p><?= $this->Html->link($comentario['Producto']['nombre'], array('controller' => 'productos', 'action' => 'view', $comentario['Producto']['id'])); ?>&nbsp;</p></li>
			<li><span><? __('Talla'); ?>:</span><p><?= $comentario['Comentario']['talla']; ?>&nbsp;</p></li>
			<li class="extendido"><span><? __('Como me quedaron'); ?>:</span></li>
			<li class="extendido"><?= $comentario['Comentario']['como']; ?>&nbsp;</li>
			<li class="extendido"><span><? __('Comentario'); ?>:</span></li>
			<li class="extendido"><?= $comentario['Comentario']['comentario']; ?>&nbsp;</li>
		</ul>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $comentario['Comentario']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $comentario['Comentario']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $comentario['Comentario']['id'])); ?>
	</div>
</div>
