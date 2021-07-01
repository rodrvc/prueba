<div class="col02">
	<?= $this->Form->create('Despacho', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Editar Despacho'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('id'); ?></li>
		<li><?= $this->Form->input('usuario_id'); ?></li>
		<li><?= $this->Form->input('lugar'); ?></li>
		<li><?= $this->Form->input('calle'); ?></li>
		<li><?= $this->Form->input('numero'); ?></li>
		<li><?= $this->Form->input('departamento'); ?></li>
		<li><?= $this->Form->input('region_id'); ?></li>
		<li><?= $this->Form->input('ciudad'); ?></li>
		<li><?= $this->Form->input('rural', array('type' => 'checkbox')); ?></li>
		<li><?= $this->Form->input('telefono'); ?></li>
		<li><?= $this->Form->input('celular'); ?></li>
		<li><?= $this->Form->input('postal'); ?></li>
		<li><?= $this->Form->input('fecha_despacho'); ?></li>
		<li><?= $this->Form->input('despachado', array('type' => 'checkbox')); ?></li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
