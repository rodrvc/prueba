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
			<?= $this->Html->link('<span class="aceptar">Volver</span>', array('action' => 'index'), array('escape' => false)); ?>
		</div>
	<? else : ?>
		<?= $this->Form->create('Descuento', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
			<h1 class="titulo"><? __('Cargas de Descuentos');?></h1>
			<ul class="edit">
				<li>
					<?= $this->Form->input('nombre', array('label' => array('text' => 'Nombre Descuento',
																					  'class' => 'texto'))); ?>
					<a class="ayudita" href="#" title="Nombre del descuento."><?= $this->Html->image('iconos/help_25.png'); ?></a>
				</li>
				<li>
					<?= $this->Form->input('cantidad'); ?>
					<a class="ayudita" href="#" title="Cantidad de veces que se puede utilizar un código de descuento."><?= $this->Html->image('iconos/help_25.png'); ?></a>
				</li>
				<li>
					<?= $this->Form->input('maximo', array('type' => 'select',
																	 'options' => array(1 => 1,2,3,4,5,6,7,8,9,10),
																	 'label' => array('text' => 'Maximo por Usuario',
																					  'class' => 'texto'))); ?>
					<a class="ayudita" href="#" title="Cantidad de veces que un usuario puede utilizar el mismo código de descuento."><?= $this->Html->image('iconos/help_25.png'); ?></a>
				</li>
				<li>
					<?= $this->Form->input('web_tienda', array('type' => 'select',
																		 'options' => array(0 => 'web',
																							1 => 'tienda',
																							2 => 'ambos'),
																		 'label' => array('text' => 'Web / Tienda',
																						  'class' => 'texto'))); ?>
					<a class="ayudita" href="#" title="Establece si el código de descuento puede ser utilizado en tiendas, en el sitio web o en ambos."><?= $this->Html->image('iconos/help_25.png'); ?></a>
				</li>
				<li>
					<span class="texto">Fecha Caducidad</span>
					<?= $this->Form->Day('fecha_caducidad', false, array('empty' => '-dia')); ?>
					-
					<?= $this->Form->Month('fecha_caducidad', false, array('empty' => '-mes')); ?>
					-
					<?= $this->Form->Year('fecha_caducidad', 2000, 2050, false,  array('empty' => '-año')); ?>
					<a class="ayudita" href="#" title="Fecha hasta la cual es válido el descuento."><?= $this->Html->image('iconos/help_25.png'); ?></a>
				</li>
				<li>
					<?= $this->Form->input('tipo', array('type' => 'select',
																   'div' => false,
																   'options' => array('POR' => 'Porcentaje',
																					  'DIN' => 'Dinero'))); ?>
					<a class="ayudita" href="#" title="Diferencia si el descuento es segun un porcentaje o si se aplicara un monto establecido sobre el valor del producto."><?= $this->Html->image('iconos/help_25.png'); ?></a>
				</li>
				<li>
					<?= $this->Form->input('descuento', array('label' => array('text' => 'Descuento (monto)',
																						 'class' => 'texto'))); ?>
					<a class="ayudita" href="#" title="Valor por el cual se aplicara el descuento."><?= $this->Html->image('iconos/help_25.png'); ?></a>
				</li>
                <li>
                    <label class='texto'>INCLUIR EXCLUIDOS</label>
                    <?= $this->Form->input('super', array('type'=>'checkbox')); ?>
                    <a class="ayudita" href="#" title="Establece si el descuento puede incluir excluidos."><?= $this->Html->image('iconos/help_25.png'); ?></a>
                </li>
				<li>
					<label for="DescuentoEscolar" class="texto">escolar</label>
					<?= $this->Form->input('escolar', array('label' => false)); ?>
					<a class="ayudita" href="#" title="Establece si el descuento puede ser utilizado en productos escolares."><?= $this->Html->image('iconos/help_25.png'); ?></a>
				</li>
				<li>
					<?= $this->Form->input('Archivo.nombre', array('type' => 'file', 'label' => array('text' => 'Selecciona un archivo', 'class' => 'texto'))); ?>
					<a class="ayudita" href="#" title="Archivo con extensión .csv que contiene un listado de códigos."><?= $this->Html->image('iconos/help_25.png'); ?></a>
				</li>
			</ul>
			<h2 class="subtitulo">Categorias<a class="ayudita" href="#" title="Categorias sobre las cuales sera valido el descuento."><?= $this->Html->image('iconos/help_25.png'); ?></a></h2>
			<ul class="edit check-categorias">
				<li>
					<?= $this->Form->checkbox('todos', array('class' => 'check-categoria')); ?>
					<label class="texto"><b>Todas las Categorias</b></label>
				</li>
				<? foreach( $categorias as $index => $categoria ) : ?>
					<li>
						<?= $this->Form->checkbox('Categoria.' . $index . '.categoria_id', array('value' => $index,
																								 'class' => 'check-categoria')); ?>
						<label class="texto"><?= $categoria; ?></label>
					</li>
				<? endforeach; ?>
			</ul>
			<h2 class="subtitulo">
		<? __('SHOWROOM'); ?>
		<a class="ayudita" href="#" title="Showroom del producto al cual se le aplica el descuento."><?= $this->Html->image('iconos/help_25.png'); ?></a>
	</h2>
	<ul class="edit">
		<!--<li>
			<label class="texto"><b>Todas los Showroom</b></label>
			<?= $this->Form->checkbox('Showroom.0.valor',array('value' => 'todos')); ?>
		</li> -->
		<?
		$index=1;
		foreach( $showrooms as $showroom )
		{
			echo '<li><label class="texto">'.$showroom.'</label>'.$this->Form->checkbox('Showroom.' .$index. '.valor', array('class' => 'check-showroom','value' => $showroom)).'</li>';
			$index++;
		}
		?>
	</ul>
			<div class="botones">
				<a href="#" id="carga-descuentos"><span class="guardar">Subir Archivo</span></a>
			</div>
		<?= $this->Form->end(); ?>
	<? endif; ?>
</div>


<script>

    $('label[for="DescuentoSuper"]').hide();
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
	
	$('#DescuentoAdminMasivoAddForm #carga-descuentos').click(function(evento) {
		evento.preventDefault();
		var mensaje,
			acceso = true;
		if (! $('#DescuentoNombre').val() ) {
			mensaje = 'nombre';
			acceso = false;
		}

		if (! $('#DescuentoAdminMasivoAddForm #DescuentoCantidad').val() ) {
			if (mensaje) {
				mensaje+= ', cantidad';
			}
			else {
				mensaje = 'cantidad';
			}
			acceso = false;
		}

		if (! $('#DescuentoAdminMasivoAddForm #DescuentoFechaCaducidadDay').val()) {
			if (mensaje) {
				mensaje+= ', dia';
			}
			else {
				mensaje = 'dia';
			}
			acceso = false;
		}

		if (! $('#DescuentoAdminMasivoAddForm #DescuentoFechaCaducidadMonth').val()) {
			if (mensaje) {
				mensaje+= ', mes';
			}
			else {
				mensaje = 'mes';
			}
			acceso = false;
		}

		if (! $('#DescuentoAdminMasivoAddForm #DescuentoFechaCaducidadYear').val()) {
			if (mensaje) {
				mensaje+= ', año';
			}
			else {
				mensaje = 'año';
			}
			acceso = false;
		}

		if (! $('#DescuentoAdminMasivoAddForm #DescuentoDescuento').val()) {
			if (mensaje) {
				mensaje+= ', descuento';
			}
			else {
				mensaje = 'descuento';
			}
			acceso = false;
		}

		if (! $('#DescuentoAdminMasivoAddForm #DescuentoTipo').val()) {
			if (mensaje) {
				mensaje+= ', tipo';
			}
			else {
				mensaje = 'tipo';
			}
			acceso = false;
		}

		if (! $('#DescuentoAdminMasivoAddForm #ArchivoNombre').val()) {
			if (mensaje) {
				mensaje+= ', archivo';
			}
			else {
				mensaje = 'archivo';
			}
			acceso = false;
		}

		if (acceso) {
			$('#DescuentoAdminMasivoAddForm').submit();
		}
		else {
			alert('Faltan: ' + mensaje);
		}
	});
// ]]>
</script>
