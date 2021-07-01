<div class="col02">
	<h1 class="titulo">Previsualización de <? __('color');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Nombre'); ?>:</span><p><?= $color['Color']['nombre']; ?>&nbsp;</p></li>
			<li><span><? __('Codigo'); ?>:</span><p><?= $color['Color']['codigo']; ?>&nbsp;</p></li>
			<li><span><? __('Primario'); ?>:</span><p><?= $primarios['Primario']['nombre']; ?>&nbsp;</p></li>
		</ul>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $color['Color']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $color['Color']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $color['Color']['id'])); ?>
	</div>
</div>
