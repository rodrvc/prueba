<div class="col02">
	<h1 class="titulo">Previsualización de <? __('tecnologia');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Nombre'); ?>:</span><p><?= $tecnologia['Tecnologia']['nombre']; ?>&nbsp;</p></li>
			<li><span><? __('Descripcion'); ?>:</span><p><?= $tecnologia['Tecnologia']['descripcion']; ?>&nbsp;</p></li>
		</ul>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $tecnologia['Tecnologia']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $tecnologia['Tecnologia']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $tecnologia['Tecnologia']['id'])); ?>
	</div>
</div>
