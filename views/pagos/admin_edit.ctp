<div class="col02">
	<?= $this->Form->create('Pago', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Editar Pago'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('id'); ?></li>
		<li><?= $this->Form->input('forma'); ?></li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
