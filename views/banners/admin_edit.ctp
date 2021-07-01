<div class="col02">
	<?= $this->Form->create('Banner', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Editar Banner'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('id'); ?></li>
		<li><span class="texto">Imagen Actual</span><?= $this->Html->image($this->data['Banner']['imagen']['mini']); ?></li>
		<li><?= $this->Form->input('imagen', array('type' => 'file', 'label' => array('text' => 'cambiar imagen' , 'class' => 'texto' ))); ?></li>
		<li><?= $this->Form->input('link'); ?></li>
		<li><?= $this->Form->hidden('tipo'); ?></li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
