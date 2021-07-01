<div class="contenedor-papa" style="float: left; width: 680px;">
	<div class="col02">
		<!-- DATOS COMPRA -->
		<h1 class="titulo">Previsualización de <? __('compra');?> Nº <?= $compra['Compra']['id']; ?></h1>
		<h2 class="subtitulo"><? __('Compra');?> #<?= $compra['Compra']['id']; ?></h2>
		<div class="previsualizar">
			<ul>
				<li class="extendido">
					<span><? __('canal'); ?>:</span>
					<p><?= $compra['Compra']['ip']; ?></p>
				</li>
				<li>
					<span><? __('Usuario'); ?>:</span>
					<p><?= $compra['Compra']['mv_cliente'];?></p>
				</li>
				<li>
					<span><? __('Subtotal'); ?>:</span>
					<p><?= $this->Shapeups->moneda($compra['Compra']['subtotal']); ?>&nbsp;</p>
				</li>
				<li>
					<span><? __('Numero'); ?>:</span>
					<p><?= $compra['Compra']['mv_numero']; ?>&nbsp;</p>
				</li>
				<li>
					<span><? __('Iva'); ?>:</span>
					<p><?= $this->Shapeups->moneda($compra['Compra']['iva']); ?>&nbsp;</p>
				</li>
				<li>
					<span><? __('Despachado'); ?>:</span>
					<p><?= ($compra['Compra']['despachado'])? 'si':'no'; ?>&nbsp;</p>
				</li>
				<li>
					<span><? __('Neto'); ?>:</span>
					<p><?= $this->Shapeups->moneda($compra['Compra']['neto']); ?>&nbsp;</p>
				</li>
				<li>
					<span><? __('Fecha'); ?>:</span>
					<p><?= $compra['Compra']['created']; ?>&nbsp;</p>
				</li>
				<li>
					<span><? __('Total'); ?>:</span>
					<p><?= $this->Shapeups->moneda($compra['Compra']['total']); ?>&nbsp;</p>
				</li>
				<? if ($compra['Compra']['boleta']) : ?>
				<li class="extendido">
					<span><? __('Boleta'); ?>:</span>
					<div style="float: left;width:205px;">&raquo; <b><?= $compra['Compra']['boleta']; ?></b></div>
					<? if ($compra['Boleta']) : ?>
						<? foreach ($compra['Boleta'] as $boleta) : ?>
							<? if ($boleta['numero']) : ?>
							<div style="width:205px;margin-left: 115px;text-decoration: line-through;"><?= $boleta['numero']; ?></div>
							<? endif; ?>
							
						<? endforeach; ?>
					<? endif; ?>
				</li>
				<? endif; ?>
				<? if ($compra['Compra']['cod_despacho']) : ?>
				<li class="extendido">
					<span><? __('Cod Despacho'); ?>:</span>
					<div style="float: left;width:205px;">&raquo; <b><?= $compra['Compra']['cod_despacho']; ?></b></div>
					<? if ($compra['Boleta']) : ?>
						<? foreach ($compra['Boleta'] as $boleta) : ?>
							<? if ($boleta['cod_despacho']) : ?>
							<div style="width:205px;margin-left: 115px;text-decoration: line-through;"><?= $boleta['cod_despacho']; ?></div>
							<? endif; ?>
						<? endforeach; ?>
					<? endif; ?>
				</li>
				<? endif; ?>
			</ul>
		</div>
	</div>


	<div class="col02">
		<!-- DATOS PRODUCTOS -->
		<div class="btn-collapse" rel="btn-accordion" data-n="4"><? __('Productos');?></div>
		<? foreach ( $productos as $producto ) : ?>
			<?
			$titulo = $estilo = $tipo = '';
			if (isset($producto['ProductosCompra']['estado']) && $producto['ProductosCompra']['estado'] == 1)
				$titulo = '<H2 style="margin-bottom: 10px;">Cambio Producto (nuevo)</H2>';
			elseif (isset($producto['ProductosCompra']['estado']) && $producto['ProductosCompra']['estado'] == 0)
			{
				if (isset($producto['ProductosCompra']['devolucion_dinero']) && $producto['ProductosCompra']['devolucion_dinero'])
				{
					$estilo = ' style="background-color: #ffbbbb;"';
					$tipo = '<li class="extendido" style="text-align: center;"><b>DEVOLUCIÓN DE DINERO</b></li>';
				}
				elseif (isset($producto['ProductosCompra']['razon']) && $producto['ProductosCompra']['razon'])
				{
					$estilo = ' style="background-color: #aeaef9;"';
					$tipo = '<li class="extendido" style="text-align: center;"><b>SOLICITUD CAMBIO DE PRODUCTO</b></li>';
				}
				elseif (isset($producto['ProductosCompra']['estado']) && $producto['ProductosCompra']['estado'] == 2)
				{
					$titulo = '<H2 style="margin-bottom: 10px;">Cambio Producto (devuelto)</H2>';
					$estilo = ' style="border: 1px solid #ffbbbb; background-color: #ffecec;"';
				}
			}
			?>
			<div class="previsualizar"<?= $estilo; ?> rel="accordion-4">
				<?= $titulo; ?>
				<div class="prev-imagen" style="position: absolute; right: 0; background-color: #FFF; width: 100px; height: 80px; margin-right: 50px;">
					<? $img = $this->Html->url('/img/Producto/'.$producto['Producto']['id'].'/mini_'.$producto['Producto']['foto']); ?>
					<img src="<?= $img; ?>" style="margin-top: 8px; margin-left: 13px;" />
				</div>
				<ul>
					<?= $tipo; ?>
					<li class="extendido"><span><?= __('Nombre'); ?>:</span><p><?= $producto['Producto']['nombre']; ?></p></li>
					<li class="extendido"><span><?= __('Talla'); ?>:</span><p><?= $producto['ProductosCompra']['talla']; ?></p></li>
					<li class="extendido"><span><?= __('Color'); ?>:</span><p><?= $producto['Color']['nombre']; ?></p></li>
					<li class="extendido"><span><?= __('Codigo'); ?>:</span><p><?= $producto['Producto']['codigo'], $producto['Color']['codigo']; ?></p></li>
					<li class="extendido"><span><?= __('Cantidad'); ?>:</span><p>1</p></li>
					<li class="extendido"><span><?= __('Precio'); ?>:</span>
					<p><?= $this->Shapeups->moneda($producto['ProductosCompra']['valor']); ?></p>
					</li>
					<? if ( isset($producto['Descuento']) ) : ?>
					<li class="extendido"><span><?= __('Descuento'); ?>:</span><p><?= $producto['Descuento']['nombre']; ?></p></li>
					<li class="extendido"><span><?= __('Codigo Desc.'); ?>:</span><p><?= $producto['Descuento']['codigo']; ?></p></li>
						<? if ( $producto['Descuento']['tipo'] == 'DIN' ) : ?>
						<li class="extendido"><span><?= __('Monto Desc.'); ?>:</span><p><?= $this->Shapeups->moneda($producto['Descuento']['descuento']); ?></p></li>
						<? elseif ( $producto['Descuento']['tipo'] == 'POR' ) : ?>
						<li class="extendido"><span><?= __('Monto Desc.'); ?>:</span><p><?= $producto['Descuento']['descuento']; ?>%</p></li>
						<? endif; ?>
					<? endif; ?>
				</ul>
			</div>
		<? endforeach; ?>
	</div>

</div>
<script>

$('div.btn-collapse[rel="btn-accordion"]').css({
	cursor:'pointer',
	border: '1px solid #ccc',
	fontSize: '20px',
    marginBottom: '15px',
    padding: '15px',
	color: '#666',
	borderTopLeftRadius: '12px',
	borderTopRightRadius: '12px'
})
<? $compacto = false; ?>

<? if ($compacto) : ?>
$('div[rel^="accordion"]').hide();
$('div.btn-collapse[rel="btn-accordion"]').click(function(e) {
	e.preventDefault();
	var n = $(this).data('n');
	if ($('div[rel^="accordion"]').is(':visible')) {
		if ($('div[rel="accordion-'+n+'"]').is(':visible')) {
			$('div[rel^="accordion"]').slideUp(500);
		} else {
			$('div[rel^="accordion"]').slideUp(400).delay(200).parents('.contenedor-papa').find('div[rel="accordion-'+n+'"]').slideDown(500);
		}
	} else {
		$('div[rel="accordion-'+n+'"]').slideDown(500);
	}
	return false;
});
<? else : ?>
$('div.btn-collapse[rel="btn-accordion"]').click(function(e) {
	e.preventDefault();
	var n = $(this).data('n');
	if ($('div[rel="accordion-'+n+'"]').is(':visible')) {
		$('div[rel="accordion-'+n+'"]').slideUp(500);
	} else {
		$('div[rel="accordion-'+n+'"]').slideDown(500);
	}
	return false;
});
<? endif; ?>
</script>
