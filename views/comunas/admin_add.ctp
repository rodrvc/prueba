<div class="col02">
	<?= $this->Form->create('Comuna', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Agregar Comuna'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('nombre'); ?></li>
		<li><?= $this->Form->input('codigo'); ?></li>
		<li><?= $this->Form->input('region_id', array('empty' => '- seleccione region')); ?></li>
		<li><?= $this->Form->input('limite'); ?></li>
		<li><?= $this->Form->input('despacho1'); ?></li>
		<li><?= $this->Form->input('despacho2'); ?></li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
