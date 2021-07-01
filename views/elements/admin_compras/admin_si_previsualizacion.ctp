<h1 class="titulo">Previsualización de <? __('compra');?> Nº <?= $compra['Compra']['id']; ?></h1>
<h2 class="subtitulo"><? __('Compra');?> #<?= $compra['Compra']['id']; ?></h2>
<div class="previsualizar">
	<ul>
		<li><span><? __('Usuario'); ?>:</span><p><?= $this->Html->link($compra['Usuario']['email'], array('controller' => 'usuarios', 'action' => 'view', $compra['Usuario']['id'])); ?>&nbsp;</p></li>
		<li><span><? __('Subtotal'); ?>:</span><p><?= $this->Shapeups->moneda($compra['Compra']['subtotal']); ?>&nbsp;</p></li>
		<li><p>&nbsp;</p></li>
		<li><span><? __('Iva'); ?>:</span><p><?= $this->Shapeups->moneda($compra['Compra']['iva']); ?>&nbsp;</p></li>
		<li><p>&nbsp;</p></li>
		<li><span><? __('Neto'); ?>:</span><p><?= $this->Shapeups->moneda($compra['Compra']['neto']); ?>&nbsp;</p></li>
		<li><span><? __('Fecha'); ?>:</span><p><?= $compra['Compra']['created']; ?>&nbsp;</p></li>
		<li><span><? __('Valor Despacho'); ?>:</span><p><?= $this->Shapeups->moneda($compra['Compra']['valor_despacho']); ?>&nbsp;</p></li>
		<? if ( $descuento ) : ?>
		<li style="color: Blue;">
			<span><? __('Tipo Descuento'); ?>:</span>
			<p>
				<? if ( $descuento['Descuento']['tipo'] == 'DIN' ) : ?>
				<?= $this->Shapeups->moneda($descuento['descripcion']); ?>
				<? elseif ( $descuento['Descuento']['tipo'] == 'POR' ) : ?>
				<?= $descuento['descripcion']; ?>
				<? endif; ?>&nbsp;
			</p>
		</li>
		<li style="color: Blue;"><span><? __('Descuento'); ?>:</span><p>- <?= $this->Shapeups->moneda($compra['Descuento']); ?>&nbsp;</p></li>
		<? endif; ?>
		<li style="color: Blue;">
			<span>&nbsp;</span>
			<p>
				<? if ($descuento) : ?>
				<?= $descuento['Descuento']['nombre']; ?>
				<? endif; ?>
			</p>
		</li>
		<li><span><? __('Total'); ?>:</span><p><?= $this->Shapeups->moneda($compra['Compra']['total']); ?>&nbsp;</p></li>
	</ul>
</div>

<!-- DATOS USUARIO -->
<h2 class="subtitulo"><? __('Usuario');?></h2>
<div class="previsualizar">
	<ul>
		<li><span><? __('Usuario'); ?>:</span><p><?= $compra['Usuario']['email']; ?>&nbsp;</p></li>
		<li><span><?= __('Nombre'); ?>:</span><p><?= $compra['Usuario']['nombre']. " " .$compra['Usuario']['apellido_paterno']. " " .$compra['Usuario']['apellido_materno']; ?></p></li>
		<li><span><?= __('Rut'); ?>:</span><p><?= $compra['Usuario']['rut']; ?></p></li>
		<li>
			<span><?= __('Sexo'); ?>:</span>
			<p>
				<? if ( isset($compra['Usuario']['Sexo']['nombre']) && $compra['Usuario']['Sexo']['nombre'] ) : ?>
				<?= $compra['Usuario']['Sexo']['nombre']; ?>
				<? endif; ?>
				&nbsp;
			</p>
		</li>
	</ul>
</div>

<!-- DATOS TARJETA -->
<h2 class="subtitulo"><? __('Pago');?></h2>
<div class="previsualizar">
	<ul>
		<li><span><? __('Numero Orden'); ?>:</span><p><?= $compra['Pago'][0]['numeroOrden']; ?>&nbsp;</p></li>
		<li><span><?= __('Monto'); ?>:</span><p><?= $compra['Pago'][0]['monto']; ?></p></li>
		<li><span><?= __('Numero Tarjeta'); ?>:</span><p><?= $compra['Pago'][0]['numeroTarjeta']; ?></p></li>
		<li><span><?= __('Fecha'); ?>:</span><p><?= $compra['Pago'][0]['fecha']; ?></p></li>
		<li><span><?= __('Cod. Autorizacion'); ?>:</span><p><?= $compra['Pago'][0]['codAutorizacion']; ?></p></li>
		<li><span><?= __('Medio Pago'); ?>:</span><p><?= $compra['Pago'][0]['marca']; ?></p></li>
	</ul>
</div>

<!-- DATOS DESPACHO -->
<h2 class="subtitulo"><? __('Despacho');?></h2>
<div class="previsualizar">
	<ul>
		<li><span><?= __('Calle'); ?>:</span><p><?= $compra['Despacho']['Direccion']['calle']; ?></p></li>
		<li><span><?= __('Numero'); ?>:</span><p><?= $compra['Despacho']['Direccion']['numero']; ?></p></li>
		<li><span><?= __('Departamento'); ?>:</span><p><?= $compra['Despacho']['Direccion']['depto']; ?></p></li>
		<li><span><?= __('Comuna'); ?>:</span><p><?= $compra['Despacho']['Direccion']['Comuna']['nombre']; ?></p></li>
		<li><span><?= __('Region'); ?>:</span><p><?= $compra['Despacho']['Direccion']['Region']['nombre']; ?></p></li>
		<li><span><?= __('Entregar a'); ?>:</span><p><?= $compra['Despacho']['entrega']; ?></p></li>
		<li class="extendido"><span><?= __('Otras Indicaciones'); ?>:</span><p><?= $compra['Despacho']['Direccion']['otras_indicaciones']; ?></p></li>
		<li><span><?= __('Telefono Casa'); ?>:</span><p><?= $compra['Despacho']['Direccion']['telefono']; ?></p></li>
		<li><span><?= __('Celular'); ?>:</span><p><?= $compra['Despacho']['Direccion']['celular']; ?></p></li>
	</ul>
</div>

<!-- DATOS PRODUCTOS -->
<h2 class="subtitulo"><? __('Productos');?></h2>
<? foreach ( $productos as $producto ) : ?>
	<?
		$estilo = '';
		if (isset($producto['ProductosCompra']['devolucion_dinero']) && $producto['ProductosCompra']['devolucion_dinero'])
		{
			$estilo = ' style="background-color: #ffbbbb;"';
		}
		elseif (isset($producto['ProductosCompra']['razon']) && $producto['ProductosCompra']['razon'])
		{
			$estilo = ' style="background-color: #aeaef9;"';
		}
	?>
<div class="previsualizar"<?= $estilo; ?>>
	<div class="prev-imagen" style="position: absolute; right: 0; background-color: #FFF; width: 100px; height: 80px; margin-right: 50px;">
		<?= $this->Html->image($producto['Producto']['foto']['mini'], array('style' => 'margin-top: 8px; margin-left: 13px;')); ?>
	</div>
	<ul>
		<?
			if (isset($producto['ProductosCompra']['devolucion_dinero']) && $producto['ProductosCompra']['devolucion_dinero'])
			{
				echo '<li class="extendido" style="text-align: center;"><b>DEVOLUCIÓN DE DINERO</b></li>';
			}
			elseif (isset($producto['ProductosCompra']['razon']) && $producto['ProductosCompra']['razon'])
			{
				echo '<li class="extendido" style="text-align: center;"><b>SOLICITUD CAMBIO DE PRODUCTO</b></li>';
			}
		?>
		<li class="extendido"><span><?= __('Nombre'); ?>:</span><p><?= $producto['Producto']['nombre']; ?></p></li>
		<li class="extendido"><span><?= __('Talla'); ?>:</span><p><?= $producto['Stock']['talla']; ?></p></li>
		<li class="extendido"><span><?= __('Color'); ?>:</span><p><?= $producto['Color']['nombre']; ?></p></li>
		<li class="extendido"><span><?= __('Codigo'); ?>:</span><p><?= $producto['Producto']['codigo'], $producto['Color']['codigo']; ?></p></li>
		<li class="extendido"><span><?= __('Cantidad'); ?>:</span><p><?= $producto['cantidad']; ?></p></li>
		<li class="extendido"><span><?= __('Precio'); ?>:</span>
		<p><?= $this->Shapeups->moneda($producto['ProductosCompra']['valor']); ?></p>
		</li>
		<? if ($producto['ProductosCompra']['razon']) : ?>
		<li class="extendido"><span><?= __('Razón'); ?>:</span><p><?= $producto['ProductosCompra']['razon']; ?></p></li>
		<? endif; ?>
	</ul>
</div>
<? endforeach; ?>
<div class="botones">
	<?= $this->Html->link('<span class="imprimir">Imprimir</span>', array('controller' => 'compras', 'action' => 'imprimir_despacho', $compra['Compra']['id']), array('escape' => false, 'target' => '_blank')); ?>
</div>