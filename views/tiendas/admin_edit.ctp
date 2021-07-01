<div class="col02">
	<?= $this->Form->create('Tienda', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Editar Tienda'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('id'); ?></li>
		<li><?= $this->Form->input('nombre'); ?></li>
		<li><?= $this->Form->input('codigo'); ?></li>
		<li><?= $this->Form->input('direccion'); ?></li>
		<li><?= $this->Form->input('horario', array('type' => 'textarea')); ?></li>

		<li><?= $this->Form->input('telefono'); ?></li>
		<li><?= $this->Form->input('region_id'); ?></li>
		<li><?= $this->Form->input('comuna_id'); ?></li>
		<li><?= $this->Form->input('zona_id'); ?></li>
		<li><?= $this->Form->input('latitud'); ?></li>
		<li><?= $this->Form->input('longitud'); ?></li>
		<li><?= $this->Html->image($this->data['Tienda']['imagen']['mini']); ?></li>
		<li><?= $this->Form->input('imagen', array('type' => 'file')); ?></li>
		<li><?= $this->Form->input('outlet'); ?></li>
		<li><?= $this->Form->input('remodelacion'); ?></li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>