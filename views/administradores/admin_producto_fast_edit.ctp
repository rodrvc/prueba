<div class="col02">
	<?= $this->Form->create('Administrador', array('inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Editar producto'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('codigo_completo'); ?></li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
