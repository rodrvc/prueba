<div class="col02">
	<h1 class="titulo">Previsualización de <? __('administrador');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Nombre'); ?>:</span><p><?= $administrador['Administrador']['nombre']; ?>&nbsp;</p></li>
			<li><span><? __('Usuario'); ?>:</span><p><?= $administrador['Administrador']['usuario']; ?>&nbsp;</p></li>
		</ul>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $administrador['Administrador']['id']), array('escape' => false)); ?>
	</div>
</div>
