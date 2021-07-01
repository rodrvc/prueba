<div class="col02">
	<?= $this->Form->create('Administrador', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Editar Administrador'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('id'); ?></li>
		<li><?= $this->Form->input('nombre'); ?></li>
		<li><?= $this->Form->input('usuario'); ?></li>
		<li><?= $this->Form->input('clave'); ?></li>
		<? if ($authUser['perfil'] == 3) : ?>
		<li><span class="texto">Permisos</span>
			<?= $this->Form->select('perfil',
									$perfiles,
									null,
									array('escape' => false, 'empty' => false, 'value' => $this->data['Administrador']['perfil'], 'class' => 'clase-input')); ?></li>
		<? endif; ?>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
