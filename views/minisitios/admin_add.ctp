<div class="col02">
	<?= $this->Form->create('Minisitio', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Agregar Minisitio'); ?></h1>
	<ul class="edit">
		<li class="radio">
			<span class="texto">Tipo</span>
			<?= $this->Form->radio('tipo', array('0' => 'Pre', '1' => 'Insertar HTML'), array('legend'=>false, 'value' => '0'));; ?>
		</li>
		<li><?= $this->Form->input('nombre'); ?></li>
		<li class="mini-pre"><?= $this->Form->input('imagen', array('type' => 'file')); ?></li>
		<li class="mini-pre"><?= $this->Form->input('descripcion'); ?></li>
		<li class="mini-html" style="display: none;"><?= $this->Form->input('codigo'); ?></li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
