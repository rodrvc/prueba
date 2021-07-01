<div class="col02">
	<?= $this->Form->create('Stock', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Editar Stock'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('id'); ?></li>
		<li><?= $this->Form->input('tienda_id'); ?></li>
		<li><?= $this->Form->input('producto_id'); ?></li>
		<li><?= $this->Form->input('talla'); ?></li>
		<li><?= $this->Form->input('cantidad'); ?></li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
