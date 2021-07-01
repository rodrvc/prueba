<div class="col02">
	<h1 class="titulo">Previsualización de <? __('comuna');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Nombre'); ?>:</span><p><?= $comuna['Comuna']['nombre']; ?>&nbsp;</p></li>
			<li><span><? __('Codigo'); ?>:</span><p><?= $comuna['Comuna']['codigo']; ?>&nbsp;</p></li>
			<li><span><? __('Region'); ?>:</span><p><?= $this->Html->link($comuna['Region']['nombre'], array('controller' => 'regiones', 'action' => 'view', $comuna['Region']['id'])); ?>&nbsp;</p></li>
			<li><span><? __('Limite'); ?>:</span><p><?= $comuna['Comuna']['limite']; ?>&nbsp;</p></li>
			<li><span><? __('Despacho1'); ?>:</span><p><?= $comuna['Comuna']['despacho1']; ?>&nbsp;</p></li>
			<li><span><? __('Despacho2'); ?>:</span><p><?= $comuna['Comuna']['despacho2']; ?>&nbsp;</p></li>
		</ul>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $comuna['Comuna']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $comuna['Comuna']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $comuna['Comuna']['id'])); ?>
	</div>
</div>
