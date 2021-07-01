<div class="col02">
	<?= $this->Form->create('Administrador', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Quitar Etiquetas'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('etiqueta'); ?></li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="borrar">Quitar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
