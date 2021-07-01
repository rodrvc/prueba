<div class="col02">
	<?= $this->Form->create('Estilo', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Agregar Estilo'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('nombre'); ?></li>
		<li><?= $this->Form->input('alias'); ?></li>
		<li>
			<?= $this->Form->input('categoria_id',array(
				'empty' => '- seleccione categoria'
			)); ?></li>
        <li><?= $this->Form->input('imagen', array('type' => 'file')); ?></li>
		<li><?= $this->Form->input('activo'); ?></li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>