<div class="col02">
	<h1 class="titulo">Previsualización de <? __('emailBlast');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Nombre'); ?>:</span><p><?= $emailBlast['EmailBlast']['nombre']; ?>&nbsp;</p></li>
			<li><span><? __('Nombre'); ?>:</span><p><?= $emailBlast['EmailBlast']['productos']; ?>&nbsp;</p></li>
			<li><span><? __('Nombre'); ?>:</span><p><?= $emailBlast['EmailBlast']['nombre_analytics']; ?>&nbsp;</p></li>
			<li><span><? __('Fecha'); ?>:</span><p><?= $emailBlast['EmailBlast']['fecha']; ?>&nbsp;</p></li>
			<!-- <li><span><? __('Monto'); ?>:</span><p><?= $emailBlast['EmailBlast']['monto']; ?>&nbsp;</p></li> -->
			<li><span><? __('Administrador'); ?>:</span><p><?= $this->Html->link($emailBlast['Administrador']['id'], array('controller' => 'administradores', 'action' => 'view', $emailBlast['Administrador']['id'])); ?>&nbsp;</p></li>
		</ul>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $emailBlast['EmailBlast']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $emailBlast['EmailBlast']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $emailBlast['EmailBlast']['id'])); ?>
	</div>
</div>
