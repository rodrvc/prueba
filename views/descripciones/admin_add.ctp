<div class="col02">
	<?= $this->Form->create('Descripcion', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Agregar Descripcion'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('nombre'); ?></li>
		<li><?= $this->Form->input('compra_id'); ?></li>
		<li><?= $this->Form->input('descripcion'); ?></li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
