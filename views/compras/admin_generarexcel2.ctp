<div class="col02">
	<?= $this->Form->create('Compra', array('action' => 'excel2', 'type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Generar Excel Compacto'); ?></h1>
	<ul class="edit">
		<li><span class="texto">Desde: </span><?= $this->Form->input('consulta_fecha1', array('label' => false, 'type' => 'text', 'id' => 'datepicker', 'autocomplete' => "off")); ?></li>
		<li><span class="texto">Hasta: </span><?= $this->Form->input('consulta_fecha2', array('label' => false, 'type' => 'text', 'id' => 'datepicker2', 'autocomplete' => "off")); ?></li>
		<li class="radio">
			<span class="texto">Estado de Pago</span>
			<?= $this->Form->radio('estado', array('1' => 'Pagadas', '0' => 'No Pagadas', '-1' => 'Con Rechazo'), array('legend'=>false, 'value' => '1'));; ?>
		</li>
	
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="previsualizar">Generar Documento</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
