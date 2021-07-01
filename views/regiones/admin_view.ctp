<div class="col02">
	<h1 class="titulo">Previsualización de <? __('region');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Nombre'); ?>:</span><p><?= $region['Region']['nombre']; ?>&nbsp;</p></li>
			<li><span><? __('Codigo'); ?>:</span><p><?= $region['Region']['codigo']; ?>&nbsp;</p></li>
		</ul>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $region['Region']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $region['Region']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $region['Region']['id'])); ?>
	</div>
</div>
