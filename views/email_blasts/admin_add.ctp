<div class="col02">
	<?= $this->Form->create('EmailBlast', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Agregar Email Blast'); ?></h1>
	<ul class="edit">
		<li>
			<?= $this->Form->input('nombre', array(
				'label' => array(
					'text' => 'Nombre Campaña',
					'class' => 'texto'
				)
			)); 
			?>
		</li>
		<li><?= $this->Form->input('productos'); ?></li>
		<li><?= $this->Form->input('nombre_analytics'); ?></li>
		<li><?= $this->Form->input('fecha'); ?></li>
		<!-- <li><?= $this->Form->input('monto'); ?></li> -->
		<li><?= $this->Form->hidden('administrador_id',array('value' => $authUser['id'])); ?></li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
