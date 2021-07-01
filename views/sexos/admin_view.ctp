<div class="col02">
	<h1 class="titulo">Previsualización de <? __('sexo');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Nombre'); ?>:</span><p><?= $sexo['Sexo']['nombre']; ?>&nbsp;</p></li>
		</ul>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $sexo['Sexo']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $sexo['Sexo']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $sexo['Sexo']['id'])); ?>
	</div>
</div>
