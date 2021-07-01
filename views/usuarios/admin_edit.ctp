<div class="col02">
	<?= $this->Form->create('Usuario', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Editar Usuario'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('id'); ?></li>
		<li><?= $this->Form->input('nombre'); ?></li>
		<li><?= $this->Form->input('apellido_paterno'); ?></li>
		<li><?= $this->Form->input('apellido_materno'); ?></li>
		<li><?= $this->Form->input('sexo_id'); ?></li>
		<li><?= $this->Form->input('rut'); ?></li>
		<li><?= $this->Form->input('fecha_nacimiento'); ?></li>
		<li><?= $this->Form->input('estadocivil_id'); ?></li>
		<li><?= $this->Form->input('email'); ?></li>
		<li><?= $this->Form->input('clave'); ?></li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
