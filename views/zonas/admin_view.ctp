<div class="col02">
	<h1 class="titulo">Previsualización de <? __('zona');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Nombre'); ?>:</span><p><?= $zona['Zona']['nombre']; ?>&nbsp;</p></li>
		</ul>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $zona['Zona']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $zona['Zona']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $zona['Zona']['id'])); ?>
	</div>
</div>
