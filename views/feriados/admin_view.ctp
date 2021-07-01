<div class="col02">
	<h1 class="titulo">Previsualización de <? __('feriado');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Nombre'); ?>:</span><p><?= $feriado['Feriado']['nombre']; ?>&nbsp;</p></li>
			<li><span><? __('Fecha'); ?>:</span><p><?= $feriado['Feriado']['fecha']; ?>&nbsp;</p></li>
			<li><span><? __('Repetir'); ?>:</span><p><?= $feriado['Feriado']['repetir']; ?>&nbsp;</p></li>
		</ul>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $feriado['Feriado']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $feriado['Feriado']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $feriado['Feriado']['id'])); ?>
	</div>
</div>
