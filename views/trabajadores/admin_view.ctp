<div class="col02">
	<h1 class="titulo">Previsualización de <? __('trabajador');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Nombre'); ?>:</span><p><?= $trabajador['Trabajador']['nombre']; ?>&nbsp;</p></li>
		</ul>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $trabajador['Trabajador']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $trabajador['Trabajador']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $trabajador['Trabajador']['id'])); ?>
	</div>
</div>
