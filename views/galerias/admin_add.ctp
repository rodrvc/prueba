<div class="col02">
	<?= $this->Form->create('Galeria', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Agregar Galeria'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('imagen', array('type' => 'file')); ?></li>
		<li><?= $this->Form->input('producto_id'); ?></li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
