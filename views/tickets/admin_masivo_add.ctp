<div class="col02">
	<?= $this->element('admin_descuento_exportar'); ?>
	<? if ( isset($stats) && $stats ) : ?>
		<h1 class="titulo">Resultado de Carga Masiva</h1>
		<div class="previsualizar">
			<ul>
				<li class="extendido"><span><? __('Nuevos registros'); ?>:</span><p><?= $stats['nuevos']; ?>&nbsp;</p></li>
				<li class="extendido"><span><? __('Repetidos'); ?>:</span><p><?= $stats['repetidos']; ?>&nbsp;</p></li>
				<li class="extendido"><span><? __('Errores'); ?>:</span><p><?= $stats['errores']; ?>&nbsp;</p></li>
				<li class="extendido"><span><? __('Memoria consumida'); ?>:</span><p><?= $stats['memoria']; ?>&nbsp;</p></li>
				<li class="extendido"><span><? __('Tiempo transcurrido'); ?>:</span><p><?= $stats['tiempo']; ?> segundos&nbsp;</p></li>
			</ul>
		</div>
		<div class="botones">
			<?= $this->Html->link('<span class="aceptar">Volver</span>', array('action' => 'listar'), array('escape' => false)); ?>
		</div>
	<? else : ?>
		<?= $this->Form->create('Tickets', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
			<h1 class="titulo"><? __('Cargas de Tickets');?></h1>
			<ul class="edit">
								
				
				<li>
					<?= $this->Form->input('Archivo.nombre', array('type' => 'file', 'label' => array('text' => 'Selecciona un archivo', 'class' => 'texto'))); ?>
					<a class="ayudita" href="#" title="Archivo con extensión .csv que contiene un listado de códigos."><?= $this->Html->image('iconos/help_25.png'); ?></a>
				</li>
			</ul>
			<div class="botones">
				<a href="#" id="carga-descuentos"><span class="guardar">Subir Archivo</span></a>
			</div>
		<?= $this->Form->end(); ?>
	<? endif; ?>
</div>


<script>
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
	
	$('#TicketsAdminMasivoAddForm #carga-descuentos').click(function(evento) {
		evento.preventDefault();
		var mensaje,
			acceso = true;
		if (! $('#TicketsAdminMasivoAddForm #ArchivoNombre').val()) {
			if (mensaje) {
				mensaje+= ', archivo';
			}
			else {
				mensaje = 'archivo';
			}
			acceso = false;
		}

		if (acceso) {
			$('#TicketsAdminMasivoAddForm').submit();
		}
		else {
			alert('Faltan: ' + mensaje);
		}
	});
// ]]>
</script>
