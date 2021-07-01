<div class="col02">
	<?= $this->Form->create('Minisitio', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Editar Minisitio'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('id'); ?></li>
		<li class="radio">
			<span class="texto">Tipo</span>
			<?= $this->Form->radio('tipo', array('0' => 'Pre', '1' => 'Insertar HTML'), array('legend'=>false));; ?>
		</li>
		<li><?= $this->Form->input('nombre'); ?></li>
		<? if ( $this->data['Minisitio']['tipo'] == 0 ) : ?>
			<? if ( $this->data['Minisitio']['imagen'] ) : ?>
			<li class="mini-pre"><span class="texto">Imagen Actual</span><?= $this->Html->image($this->data['Minisitio']['imagen']['mini']); ?></li>
			<li class="mini-pre"><?= $this->Form->input('imagen', array('type' => 'file', 'label' => array('text' => 'cambiar imagen' , 'class' => 'texto' ))); ?></li>
			<? else : ?>
			<li class="mini-pre"><?= $this->Form->input('imagen', array('type' => 'file')); ?></li>
			<? endif; ?>
			<li class="mini-pre"><?= $this->Form->input('descripcion'); ?></li>
			<li class="mini-html" style="display: none;"><?= $this->Form->input('codigo'); ?></li>
		<? else : ?>
			<? if ( $this->data['Minisitio']['imagen'] ) : ?>
			<li class="mini-pre" style="display: none;"><span class="texto">Imagen Actual</span><?= $this->Html->image($this->data['Minisitio']['imagen']['mini']); ?></li>
			<li class="mini-pre" style="display: none;"><?= $this->Form->input('imagen', array('type' => 'file', 'label' => array('text' => 'cambiar imagen' , 'class' => 'texto' ))); ?></li>
			<? else : ?>
			<li class="mini-pre" style="display: none;"><?= $this->Form->input('imagen', array('type' => 'file')); ?></li>
			<? endif; ?>
			<li class="mini-pre" style="display: none;"><?= $this->Form->input('descripcion'); ?></li>
			<li class="mini-html"><?= $this->Form->input('codigo'); ?></li>
		<? endif; ?>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>



<!--<li class="radio">
			<span class="texto">Tipo</span>
			<?= $this->Form->radio('tipo', array('0' => 'Pre', '1' => 'Insertar HTML'), array('legend'=>false, 'value' => '0'));; ?>
		</li>
		<li><?= $this->Form->input('nombre'); ?></li>
		<li class="mini-pre"><?= $this->Form->input('imagen', array('type' => 'file')); ?></li>
		<li class="mini-pre"><?= $this->Form->input('descripcion'); ?></li>
		<li class="mini-html" style="display: none;"><?= $this->Form->input('codigo'); ?></li>-->