<div class="col02">
	<?= $this->Form->create('Direccion', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Editar Direccion'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('id'); ?></li>
		<li><?= $this->Form->input('usuario_id'); ?></li>
		<li><?= $this->Form->input('calle'); ?></li>
		<li><?= $this->Form->input('numero'); ?></li>
		<li><?= $this->Form->input('depto'); ?></li>
		<li><?= $this->Form->input('otras_indicaciones'); ?></li>
		<li><?= $this->Form->input('comuna_id'); ?></li>
		<li><?= $this->Form->input('region_id'); ?></li>
		<li><?= $this->Form->input('codigo_postal'); ?></li>
		<li><?= $this->Form->input('telefono'); ?></li>
		<li><?= $this->Form->input('celular'); ?></li>
		<li><?= $this->Form->input('nombre'); ?></li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
