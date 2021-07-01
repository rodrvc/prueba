<div class="col02">
	<?= $this->Form->create('Banner', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Agregar Banner'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('imagen', array('type' => 'file')); ?></li>
		<li><?= $this->Form->input('link'); ?></li>
		<li>
			<?
			if (isset($this->data['Banner']['tipo']) && $this->data['Banner']['tipo'] == 0)
			{
				echo '<span class="texto">Tipo</span>CARRUSEL';
			}
			?>
			<?= $this->Form->hidden('tipo'); ?>
		</li>
		<!--<li><?= $this->Form->input('tipo', array('type' => 'select',
												 'label' => array('text' => 'Tipo', 'class' => 'texto'),
												 'div' => false,
												 'class' => 'clase-input',
												 'options' => array(0 => 'Banner',
																	1 => 'Caluga'))); ?></li>-->
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
