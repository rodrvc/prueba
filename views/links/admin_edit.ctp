<div class="col02">
	<?= $this->Form->create('Link', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Editar Link'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('id'); ?></li>
		<li><?= $this->Form->input('ruta'); ?></li>
		<li><?= $this->Form->input('controlador'); ?></li>
		<li><?= $this->Form->input('action'); ?></li>
		<li><?= $this->Form->input('parametro'); ?></li>
		<li><?= $this->Form->input('activo'); ?></li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
