<div class="col02">
	<?= $this->Form->create('Administrador', array('action' => 'convertir_ficha', 'type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo">Adaptar archivo</h1>
	<ul class="edit">
		<li><?= $this->Form->input('archivo', array('type' => 'file')); ?></li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Cargar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
