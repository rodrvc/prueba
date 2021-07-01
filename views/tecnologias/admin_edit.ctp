<div class="col02">
	<?= $this->Form->create('Tecnologia', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Editar Tecnologia'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('id'); ?></li>
		<li><?= $this->Form->input('nombre'); ?></li>
		<li><?= $this->Form->input('descripcion'); ?></li>
		<li><span class="texto">Imagen Actual</span><?= $this->Html->image($this->data['Tecnologia']['imagen']['mini']); ?></li>
		<li><?= $this->Form->input('imagen', array('type' => 'file', 'label' => array('text' => 'cambiar imagen' , 'class' => 'texto' ))); ?></li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
