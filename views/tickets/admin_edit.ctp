<div class="col02">
	<style type="text/css" media="all">
		.ayudita {
			position: absolute;
			right: 30px;
			width: 25px;
			height: 25px;
		}
	</style>
	<script type="application/x-javascript">
	$(document).ready(function()
	{
		$('.ayudita').tooltip();

		// SELECT CANALES
		$('#DescuentoTodos').change(function(evento) {
			var contenedor = $(this).parents('.edit');
			if ( $(this).is(':checked') ) {
				contenedor.find('.check-categoria').attr('checked',true);
			}
			else {
				contenedor.find('.check-categoria').attr('checked',false);
			}
		});
	});
	</script>
	<?= $this->Form->create('Ticket', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Editar Descuento'); ?></h1>
	<ul class="edit">
		<li>
			<?= $this->Form->input('id'); ?>
		</li>
		<li>
			<?= $this->Form->input('codigo'); ?>
			<a class="ayudita" href="#" title="Nombre del descuento."><?= $this->Html->image('iconos/help_25.png'); ?></a>
		</li>
		<li>
			<?= $this->Form->input('numero_guia'); ?>
			<a class="ayudita" href="#" title="Cantidad de veces que se puede utilizar un código de descuento."><?= $this->Html->image('iconos/help_25.png'); ?></a>
		</li>
		<li>
			<?= $this->Form->input('fecha_guia'); ?>
			<a class="ayudita" href="#" title="Fecha hasta la cual es válido el descuento."><?= $this->Html->image('iconos/help_25.png'); ?></a>
		</li>
		<li>
			<?= $this->Form->input('codigo_producto'); ?>
			<a class="ayudita" href="#" title="Código del descuento (ej: COD-001-SK001)."><?= $this->Html->image('iconos/help_25.png'); ?></a>
		</li>
			<li>
			<?= $this->Form->input('color'); ?>
			<a class="ayudita" href="#" title="Código interno del descuento"><?= $this->Html->image('iconos/help_25.png'); ?></a>
		</li>
		<li>
			<?= $this->Form->input('talla'); ?>
			<a class="ayudita" href="#" title="Nombre de la persona que entrego el descuento"><?= $this->Html->image('iconos/help_25.png'); ?></a>
		</li>
		
	</ul>

	<!--showroom-->
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
