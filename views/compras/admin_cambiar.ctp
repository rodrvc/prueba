<div class="col02" style="background-color: #FFF;">
	<!-- DATOS COMPRA -->
	<h1 class="titulo">Devolucion de <? __('compra');?> Nº <?= $compra['Compra']['id']; ?></h1>
	<h2 class="subtitulo"><? __('Compra');?> #<?= $compra['Compra']['id']; ?></h2>
	<?= $this->Form->create('Compra', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
	<div class="previsualizar">
		<ul>
			<li class="extendido"><?= $this->Form->input('id'); ?></li>
			<li><span><? __('Usuario'); ?>:</span><p><?= $this->Html->link($compra['Usuario']['email'], array('controller' => 'usuarios', 'action' => 'view', $compra['Usuario']['id'])); ?>&nbsp;</p></li>
			<li><span><? __('Subtotal'); ?>:</span><p><?= $this->Shapeups->moneda($compra['Compra']['subtotal']); ?>&nbsp;</p></li>
			<li><p>&nbsp;</p></li>
			<li><span><? __('Iva'); ?>:</span><p><?= $this->Shapeups->moneda($compra['Compra']['iva']); ?>&nbsp;</p></li>
			<li><span><? __('Estado'); ?>:</span><?= $this->Form->select('estado', array('3' => 'En Devolucion', '4' => 'Devuelto'), null, array('escape' => false, 'empty' => false, 'value' => $compra['Compra']['estado'])); ?></li>
			<li><span><? __('Valor Neto'); ?>:</span><p><?= $this->Shapeups->moneda($compra['Compra']['neto']); ?>&nbsp;</p></li>
			<li><span><? __('Valor Despacho'); ?>:</span><p><?= $this->Shapeups->moneda($compra['Compra']['valor_despacho']); ?>&nbsp;</p></li>
			<li><p>&nbsp;</p></li>
			<li><span><? __('Descuento'); ?>:</span><p>- <?= $this->Shapeups->moneda($total_descuento); ?>&nbsp;</p></li>
			<!--<li><span><? __('Pago'); ?>:</span><p><?= $this->Html->link($compra['Pago']['forma'], array('controller' => 'pagos', 'action' => 'view', $compra['Pago']['id'])); ?>&nbsp;</p></li>-->
			<li><span><? __('Fecha'); ?>:</span><p><?= $compra['Compra']['created']; ?>&nbsp;</p></li>
			<li><span><? __('Total'); ?>:</span><p><?= $this->Shapeups->moneda($compra['Compra']['total']); ?>&nbsp;</p></li>
		</ul>
		<div class="botones">
			<?= $this->Html->link('<span class="guardar">Guardar</span>', '#', array('escape' => false, 'class' => 'guarda-devuelto', 'style' => 'margin-top: 10px;')); ?>
			<?= $this->Html->link('<span class="cancelar">Anular</span>', array('action' => 'autodescuento', $compra['Compra']['id']), array('escape' => false, 'style' => 'margin-top: 10px;', 'title' => 'Generar descuento automaticamente y anular compra'), sprintf(__('¿La compra # %s sera anulada. Se generara automaticamente un codigo de descuento por el valor total de la compra y sera enviado al usuario. ¿ Desea continuar ?', true), $compra['Compra']['id'])); ?>
		</div>
	</div>
	
	<!-- DATOS TARJETA -->
	<h2 class="subtitulo"><? __('Pago');?></h2>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Numero Orden'); ?>:</span><p><?= $compra['Pago'][0]['numeroOrden']; ?>&nbsp;</p></li>
			<li><span><?= __('Monto'); ?>:</span><p><?= $compra['Pago'][0]['monto']; ?></p></li>
			<li><span><?= __('Numero Tarjeta'); ?>:</span><p><?= $compra['Pago'][0]['numeroTarjeta']; ?></p></li>
			<li><span><?= __('Fecha'); ?>:</span><p><?= $compra['Pago'][0]['fecha']; ?></p></li>
		</ul>
	</div>
<style>
.previsualizar > .cuadro-1 {
	float: left;
	width: 100%;
}
.previsualizar > .cuadro-1 > ul.producto-original, .previsualizar > .cuadro-1 > ul.producto-original li {
	width: 100%;
}
.previsualizar > .cuadro-1 > ul.producto-original > li span {
	width: 30%;
}
.previsualizar > .cuadro-1 > ul.producto-original > li p {
	width: 70%;
}
.previsualizar > .cuadro-2 {
	float: left;
	width: 320px;
	margin-left: 10px;
	color: #333333;
	padding: 5px;
	background-color: #BBBBBB;
	display: none;
	border: 1px solid #000000;
	border-radius: 10px;
}
.previsualizar > .cuadro-2 > input, .previsualizar > .cuadro-2 > select {
	float: right;
	width: 225px;
}
.disabled {
	opacity: 0.6;
}
</style>
	<!-- DATOS PRODUCTOS -->
	<h2 class="subtitulo"><? __('Productos');?></h2>
	<? foreach ( $productos as $index => $producto ) : ?>
		<?
			$estilo = '';
			if (isset($producto['ProductosCompra']['devolucion_dinero']) && $producto['ProductosCompra']['devolucion_dinero'])
				$estilo = ' style="background-color: #ffbbbb;"';
			elseif (isset($producto['ProductosCompra']['razon']) && $producto['ProductosCompra']['razon'])
				$estilo = ' style="background-color: #aeaef9;"';

			$activar_cambio = true;
			if ($producto['ProductosCompra']['devolucion_dinero'])
				$activar_cambio = false;
			elseif ($producto['ProductosCompra']['estado'] == 2)
				$activar_cambio = false;

			$titulo = 'Producto';
			if (in_array($producto['ProductosCompra']['estado'],array(1,2)))
			{
				if ($producto['ProductosCompra']['estado'] == 1)
					$titulo.= ' (nuevo)';
				else
					$titulo.= ' (devuelto)';
			}

			$descripcion = '';
			if (isset($producto['ProductosCompra']['devolucion_dinero']) && $producto['ProductosCompra']['devolucion_dinero'])
				$descripcion = '<li class="extendido" style="text-align: center;"><b>DEVOLUCIÓN DE DINERO</b></li>';
			elseif (isset($producto['ProductosCompra']['razon']) && $producto['ProductosCompra']['razon'])
				$descripcion = '<li class="extendido" style="text-align: center;"><b>SOLICITUD CAMBIO DE PRODUCTO</b></li>';
		?>

		<div class="previsualizar"<?= $estilo; ?> data-precio="<?= $producto['ProductosCompra']['valor']; ?>">
			<!--CUADRO IZQUIERDA-->
			<div class="cuadro-1">
				<H2 style="margin-bottom: 10px;"><?= $titulo; ?></H2>
				<ul class="producto-original prev-<?= $producto['Producto']['id']; ?>" rel="cuadro-1">
					<?= $descripcion; ?>
					<li class="extendido"><?= $this->Html->image($producto['Producto']['foto']['mini']); ?></li>
					<li class="extendido"><span><?= __('Nombre'); ?>:</span><p><?= $producto['Producto']['nombre']; ?></p></li>
					<li class="extendido"><span><?= __('Talla'); ?>:</span><p><?= $producto['ProductosCompra']['talla']; ?></p></li>
					<li class="extendido"><span><?= __('Color'); ?>:</span><p><?= $producto['Color']['nombre']; ?></p></li>
					<li class="extendido"><span><?= __('Codigo'); ?>:</span><p><?= $producto['Producto']['codigo'], $producto['Color']['codigo']; ?></p></li>
					<li class="extendido"><span><?= __('Precio'); ?>:</span>
					<p><?= $this->Shapeups->moneda($producto['ProductosCompra']['valor']); ?></p>
					</li>
					<? if (isset($producto['ProductosCompra']['devolucion_dinero']) && $producto['ProductosCompra']['devolucion_dinero']) : ?>
					<li class="extendido"><span><?= __('Devolucion dinero'); ?>:</span><p><?= ($producto['ProductosCompra']['devolucion_dinero']) ? 'SI' : 'NO' ; ?></p></li>
					<? endif; ?>
					<? if (isset($producto['ProductosCompra']['razon']) && $producto['ProductosCompra']['razon']) : ?>
					<li class="extendido"><span><?= __('Razón'); ?>:</span><p><?= $producto['ProductosCompra']['razon']; ?></p></li>
					<? endif; ?>
					<? if ( isset($producto['Descuento']) ) : ?>
					<li class="extendido"><span><?= __('Descuento'); ?>:</span><p style="color: Red;"><?= $producto['Descuento']['nombre']; ?></p></li>
					<li class="extendido"><span><?= __('Codigo Desc.'); ?>:</span><p style="color: Red;"><?= $producto['Descuento']['codigo']; ?></p></li>
						<? if ( $producto['Descuento']['tipo'] == 'DIN' ) : ?>
						<li class="extendido"><span><?= __('Monto Desc.'); ?>:</span><p style="color: Red;"><?= $this->Shapeups->moneda($producto['Descuento']['descuento']); ?></p></li>
						<? elseif ( $producto['Descuento']['tipo'] == 'POR' ) : ?>
						<li class="extendido"><span><?= __('Monto Desc.'); ?>:</span><p style="color: Red;"><?= $producto['Descuento']['descuento']; ?>%</p></li>
						<? endif; ?>
					<? endif; ?>
				</ul>
				<div class="botones" style="float: right; width: 100%; margin-bottom: 10px;">
					<? if ($activar_cambio) : ?>
					<?= $this->Html->link('<span class="generar">Cambiar</span>', '#', array('escape' => false, 'class' => 'cambiar', 'data-id' => $producto['Producto']['id'], 'data-valor' => ( $producto['Producto']['oferta'] == 1 ) ? $producto['Producto']['precio_oferta'] : $producto['Producto']['precio'])); ?>
					<? endif; ?>
				</div>
			</div>
			<!--FIN CUADRO IZQUIERDA-->
			<? if ($activar_cambio) : ?>
				<!--CUADRO DERECHA-->
				<div class="cuadro-2" data-compra="<?= $compra['Compra']['id']; ?>">
					<div class="conte-img" style="float: left; width: 80px; height: 63px; border: 1px solid #CCCCCC; border-radius: 5px; background-color: #FFFFFF;" rel="prod-foto">
					</div>
						<?= $this->Form->hidden('compra_id', array('value' => $compra['Compra']['id'])); ?>
						<?= $this->Form->hidden('anterior', array('value' => $producto['ProductosCompra']['id'])); ?>
						<?= $this->Form->hidden('valor1', array('value' => $producto['ProductosCompra']['valor'])); ?>
						<?= $this->Form->hidden('valor2', array('value' => 0)); ?>
						<?= $this->Form->input('valorar', array('type' => 'select',
																'div' => false,
																'label' => false,
																'options' => array(1 => 'menor o igual valor',
																				   2 => 'mostrar todos'),
																'data-valor' => ( $producto['Producto']['oferta'] == 1 ) ? $producto['Producto']['precio_oferta'] : $producto['Producto']['precio'] )); ?>
						<?= $this->Form->input('nuevo', array('type' => 'select',
															'div' => false,
															'label' => false,
															'empty' => '-- seleccione')); ?>
						<?= $this->Form->input('talla', array('type' => 'text',
															  'div' => false,
															  'label' => false)); ?>
						<?= $this->Form->input('motivo', array('type' => 'select',
															   'empty' => '- seleccione motivo',
															   'options' => array(
																	'Falla de producto (garantia)' => 'Falla de producto (garantia)',
																	'Error de despacho' => 'Error de despacho',
																	'Cambio de talla' => 'Cambio de talla',
																	'Producto mal etiquetado' => 'Producto mal etiquetado',
																	'Cambio de modelo' => 'Cambio de modelo',
																	'Sin stock' => 'Sin stock'
																),
															   'div' => false,
															   'label' => false)); ?>
					<div class="contenido" style="float: left; width: 320px; color: #000000;">
						<div class="previsualizar" style="width: 300px; background-color: #BBBBBB; color: #333333; text-align: center; margin-bottom: 0; padding-bottom: 0;">
							<ul class="datos-nuevo" style="width: 300px;">
								<li style="width: 300px;"><span style="width: 60px;"><?= __('Nombre'); ?>:</span><p rel="prod-nombre">&nbsp;</p></li>
								<li style="width: 300px;"><span style="width: 60px;"><?= __('Color'); ?>:</span><p rel="prod-color">&nbsp;</p></li>
								<li style="width: 300px;"><span style="width: 60px;"><?= __('Codigo'); ?>:</span><p rel="prod-codigo">&nbsp;</p></li>
								<li style="width: 300px;"><span style="width: 60px;"><?= __('Precio'); ?>:</span><p rel="prod-precio">&nbsp;</p></li>
								<li style="width: 300px;"><span style="width: 60px;"><?= __('Tallas'); ?>:</span><p rel="prod-tallas">&nbsp;</p></li>
							</ul>
							<div class="botones">
								<a href="#" class="edit-product disabled" style="border-radius: 5px; margin-top: 5px;"><span class="reload">Cambiar</span></a>
								<a href="#" class="cupon-generator disabled" style="border-radius: 5px; margin-top: 5px;"><span class="previsualizar">Generar Cupon</span></a>
							</div>
						</div>
					</div>
				</div>
				<!--FIN CUADRO DERECHA-->
			<? endif; ?>
		</div>
	<? endforeach; ?>
	<?= $this->Form->end(); ?>
</div>
<script>
$('#CompraAdminCambiarForm .guarda-devuelto').click(function(e) {
	e.preventDefault();
	var estado = $('#CompraEstado').val();
	if (estado) {
		$('#CompraAdminCambiarForm').submit();
	} else {
		alert('Dele seleccionar un estado');
	}
});

$('#CompraAdminCambiarForm a.cambiar').click(function(e)
{
	e.preventDefault();
	var id = $(this).data('id'),
		tipo = 1,
		valor = $(this).data('valor'),
		cuadro1 = $(this).parents('div.cuadro-1');

	if (! id)
		return false;

	cuadro1.find('.cambiar').fadeOut(500);
	cuadro1.siblings('.cuadro-2').find('#CompraNuevo').html('<option value="">Cargando Productos...</option>');
	cuadro1.animate({
		width: 300,
		border: '1px solid #000000',
		borderRadius: 10,
		padding: '5px 5px 46px'
	}, 500, function() {
		cuadro1.siblings('.cuadro-2').slideDown(500, function() {
			$.ajax({
				type: "POST",
				async: false,
				url: webroot + "compras/ajax_lista_productos/",
				data:
					{
						tipo : tipo,
						valor: valor
					},
				success: function(respuesta) {
					if (respuesta) {
						cuadro1.siblings('.cuadro-2').find('#CompraNuevo').html(respuesta);
					}
				}
			});
		});
	});
});

$('#CompraAdminCambiarForm #CompraValorar').change(function()
{
	var tipo = $(this).val(),
		valor = $(this).data('valor'),
		cuadro2 = $(this).parents('.cuadro-2');
	if ( tipo && tipo != 0 ) {
		$.ajax({
			type: "POST",
			async: false,
			url: webroot + "compras/ajax_lista_productos/",
			data:
				{
					tipo : tipo,
					valor: valor
				},
			beforeSend: function() {
				cuadro2.find('#CompraNuevo').html('<option value="">Cargando Productos...</option>');
			},
			success: function(respuesta) {
				cuadro2.find('#CompraNuevo').html(respuesta);
			}
		});
	} else {
		alert('debe seleccionar productos a mostrar');
	}
});

$('#CompraAdminCambiarForm .cuadro-2 #CompraNuevo').change(function()
{
	var id = $(this).val(),
		cuadro2 = $(this).parents('.cuadro-2');
	if (isNaN(id))
		return false;
	if( id ) {
		$.ajax(
		{
			dataType	: "json",
			type		: 'POST',
			async: false,
			url: webroot + "compras/ajax_datos_producto/",
			data : { id : id },
			beforeSend: function() {
				cuadro2.find('.conte-img').css({
					'background-image' : 'url("<?= $this->Html->url('/img/loading.gif'); ?>")',
					'background-repeat' : 'no-repeat',
					'background-position': 'center center'
				});
			},
			success: function(respuesta) {
				if (respuesta) {
					cuadro2.find('div[rel="prod-foto"]').html('<img src="'+webroot+'img/'+respuesta.foto+'" width="100%" />');
					cuadro2.find('p[rel="prod-nombre"]').html(respuesta.nombre);
					cuadro2.find('p[rel="prod-color"]').html(respuesta.color);
					cuadro2.find('p[rel="prod-codigo"]').html(respuesta.codigo);
					cuadro2.find('p[rel="prod-precio"]').html(respuesta.precio);
					cuadro2.find('p[rel="prod-tallas"]').html(respuesta.tallas);
					cuadro2.find('#CompraValor2').val(respuesta.precio);
				}
			}
		});
	} else {
		alert('Debe seleccionar un Producto');
	}
});

$('.cupon-generator').live('click', function(evento) {
	evento.preventDefault();
	var boton = $(this),
		valor1 = boton.parents('.cuadro-2').find('#CompraValor1').val(),
		valor2 = boton.parents('.cuadro-2').find('#CompraValor2').val(),
		compra	= boton.parents('.cuadro-2').data('compra'),
		motivo = boton.parents('.cuadro-2').find('#CompraMotivo').val();
	if (! compra || ! valor1 || ! valor2) {
		console.log('1');
		return false;
	}
	if (boton.hasClass('disabled')){
		console.log('2');
		return false;
	}
	// confirmacion para generar descuento
	if ( confirm('¿Desea generar un descuento por la diferencia del producto original y el nuevo?') ) {
		var valorCupon = valor1-valor2;
		boton.addClass('disabled');
		$.ajax(
		{
			type: "POST",
			dataType: "json",
			async: false,
			url: webroot + "compras/ajax_generar_descuento_compra/",
			data: {
					valor_cupon: valorCupon,
					compra: compra,
					motivo: motivo
				  },
			success: function(respuesta_descuento)
			{
				if ( respuesta_descuento.estado == 'OK' )
				{
					boton.parents('.cuadro-2').find('#CompraValorar').attr('readonly','readonly');
					boton.parents('.cuadro-2').find('#CompraNuevo').attr('readonly','readonly');
					boton.parents('.cuadro-2').find('#CompraTalla').attr('readonly','readonly');
					
					if ( confirm('¡Descuento Generado!\n\nCodigo: '+respuesta_descuento.codigo+'\npor: $'+valorCupon+'\n¿Desea enviar el descuento al cliente?') ) {
						$.ajax(
						{
							type: "POST",
							async: false,
							url: webroot + "compras/ajax_enviar_descuento_compra/",
							data: {
									descuento: respuesta_descuento.descuento,
									compra: compra
								  },
							success: function(respuesta_envio)
							{
								if (respuesta_envio == 'OK') {
									alert('El descuento ha sido enviado al cliente.\n¡Recuerde realizar el cambio!');
									// realizar cambio
								}
								else {
									alert(respuesta_envio+'\n¡Recuerde realizar el cambio!');
									// realizar cambio
								}
							}
						});
					}
					else {
						alert('¡Descuento Generado exitosamente!\n\nCodigo: '+respuesta_descuento.codigo+'\npor: $'+valorCupon+'.\n¡Recuerde realizar el cambio!');
						// realizar cambio
					}
				}
				else if ( respuesta_descuento.estado == 'ERROR' )
				{
					alert(respuesta_descuento.texto);
				}
			}
		});
	}
	return false;
});
	
	$('.cuadro-2 #CompraValorar, .cuadro-2 #CompraNuevo, .cuadro-2 #CompraTalla').change(function() {
		var tipo = $(this).parents('.cuadro-2').find('#CompraValorar').val(),
			producto = $(this).parents('.cuadro-2').find('#CompraNuevo').val(),
			talla = $(this).parents('.cuadro-2').find('#CompraTalla').val();
		if (tipo && producto && talla) {
			if ($(this).parents('.cuadro-2').find('.edit-product').hasClass('disabled')) {
				$(this).parents('.cuadro-2').find('.edit-product').removeClass('disabled');
			}
		} else {
			if (! $(this).parents('.cuadro-2').find('.edit-product').hasClass('disabled')) {
				$(this).parents('.cuadro-2').find('.edit-product').addClass('disabled');
			}
		}
		if (producto && talla) {
			if ($(this).parents('.cuadro-2').find('.cupon-generator').hasClass('disabled')) {
				$(this).parents('.cuadro-2').find('.cupon-generator').removeClass('disabled');
			}
		} else {
			if (! $(this).parents('.cuadro-2').find('.cupon-generator').hasClass('disabled')) {
				$(this).parents('.cuadro-2').find('.cupon-generator').addClass('disabled');
			}
		}
	});

	$('.edit-product').live('click', function(evento)
	{
		evento.preventDefault();
		if ($(this).hasClass('disabled'))
			return false;
		if ($(this).hasClass('activo'))
			return false;
		$(this).addClass('activo');
		var anterior = $(this).parents('.cuadro-2').find('#CompraAnterior').val(),
			nuevo = $(this).parents('.cuadro-2').find('#CompraNuevo').val(),
			talla = $(this).parents('.cuadro-2').find('#CompraTalla').val(),
			motivo = $(this).parents('.cuadro-2').find('#CompraMotivo').val();
		$.ajax({
			type: "POST",
			async: false,
			url: webroot + "compras/ajax_cambiar/",
			data: {
					anterior 	: anterior,
					nuevo		: nuevo,
					talla		: talla,
					motivo		: motivo
				  },
			success: function(respuesta)
			{
				if ( respuesta == 'READY' )
				{
					location.reload();
				}
				else
				{
					alert('Lo sentimos, no se pudo realizar el cambio de productos. \nPorfavor intentelo nuevamente');
				}
			}
		});
	});
</script>
