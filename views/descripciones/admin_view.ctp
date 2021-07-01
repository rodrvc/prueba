<div class="col02">
	<h1 class="titulo">Previsualización de <? __('Descripcion');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Nombre'); ?>:</span><p><?= $descripcion['Descripcion']['nombre']; ?>&nbsp;</p></li>
			<li><span><? __('Descripción'); ?>:</span><p><?= $descripcion['Descripcion']['descripcion']; ?>&nbsp;</p></li>
		</ul>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $descripcion['Descripcion']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $descripcion['Descripcion']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $descripcion['Descripcion']['id'])); ?>
	</div>
</div>
