<div class="col02">
	<?= $this->Form->create('Categoria', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Agregar Categoria'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('parent_id', array('options' => $categorias, 'empty' => '-- Sin categoria padre', 'label' => array('text' => 'Categoria Padre', 'class' => 'texto'))); ?></li>
		<li><?= $this->Form->input('nombre'); ?></li>
		<li><?= $this->Form->input('alias'); ?></li>
		<li><?= $this->Form->input('sexo_id'); ?></li>
		<li><?= $this->Form->input('desde'); ?></li>
		<li><?= $this->Form->input('hasta'); ?></li>
		<li><?= $this->Form->input('medios', array('label' => array('text' => 'Numeros Medios', 'class' => 'texto'))); ?></li>
		<li><?= $this->Form->input('publico'); ?></li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
