<div class="col02">
	<h1 class="titulo">Previsualización de <? __('estilo');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Nombre'); ?>:</span><p><?= $estilo['Estilo']['nombre']; ?>&nbsp;</p></li>
			<li><span><? __('Alias'); ?>:</span><p><?= $estilo['Estilo']['alias']; ?>&nbsp;</p></li>
			<li><span><? __('Activo'); ?>:</span><p><?= $estilo['Estilo']['activo']; ?>&nbsp;</p></li>
		</ul>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $estilo['Estilo']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $estilo['Estilo']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $estilo['Estilo']['id'])); ?>
	</div>
</div>
