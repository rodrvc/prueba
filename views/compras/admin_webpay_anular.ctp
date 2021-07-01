<div class="col02">
	<?= $this->Form->create('Compra', array('action' => 'admin_procesar_anular', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Anular Orden'); ?></h1>
	<ul class="edit">
		<li><span class="texto">Orden: </span><?= $this->Form->input('orden', array('label' => false, 'autocomplete' => "off")); ?><a class="ayudita" href="#" title="Puede ingresar mas de una orden separada por espacio"><img src="/skechers/ecomm/img/iconos/help_25.png" alt=""></a></li>
		<li><span class="texto">Clave: </span><?= $this->Form->input('clave', array('label' => false,  'autocomplete' => "off")); ?></li>
	
	
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="previsualizar">Anular compra(s)</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
