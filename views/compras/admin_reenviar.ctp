<div class="col02">
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
			<li><span><? __('Estado'); ?>:</span>
				<? if ( in_array($authUser['perfil'], array(1,2,3)) ): ?>
				<?= $this->Form->select('estado', array('4' => 'Devuelto', '1' => 'Pagado'), null, array('escape' => false, 'empty' => false, 'value' => $compra['Compra']['estado'])); ?>
				<? else : ?>
					<? if ( $compra['Compra']['estado'] == 4) : ?>
						<span style="color: Blue;">Devuelto</span>
					<? else : ?>
						&nbsp;
					<? endif; ?>
				<? endif; ?>
			</li>
			<li><span><? __('Neto'); ?>:</span><p><?= $this->Shapeups->moneda($compra['Compra']['neto']); ?>&nbsp;</p></li>
			<li><span><? __('Descuento'); ?>:</span><p>- <?= $this->Shapeups->moneda($total_descuento); ?>&nbsp;</p></li>
			<!--<li><span><? __('Pago'); ?>:</span><p><?= $this->Html->link($compra['Pago']['forma'], array('controller' => 'pagos', 'action' => 'view', $compra['Pago']['id'])); ?>&nbsp;</p></li>-->
			<li><span><? __('Fecha'); ?>:</span><p><?= $compra['Compra']['created']; ?>&nbsp;</p></li>
			<li><span><? __('Total'); ?>:</span><p><?= $this->Shapeups->moneda($compra['Compra']['total']); ?>&nbsp;</p></li>
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
		</ul>
	</div>
	
	<!-- DATOS PRODUCTOS -->
	<h2 class="subtitulo"><? __('Productos');?></h2>
	<? foreach ( $productos as $index => $producto ) : ?>
		<? if ( isset($producto['ProductosCompra']['estado']) && $producto['ProductosCompra']['estado'] == 0 || $producto['ProductosCompra']['estado'] == 1 ) : ?>
			<div class="previsualizar">
				<div class="prev-imagen" style="position: absolute; right: 0; background-color: #FFF; width: 100px; height: 80px; margin-right: 50px;">
					<?= $this->Html->image($producto['Producto']['foto']['mini'], array('style' => 'margin-top: 8px; margin-left: 13px;')); ?>
				</div>
				<ul class=" prev-<?= $producto['Producto']['id']; ?>">
					<li class="extendido"><span><?= __('Nombre'); ?>:</span><p><?= $producto['Producto']['nombre']; ?></p></li>
					<li class="extendido"><span><?= __('Talla'); ?>:</span><p><?= $producto['Stock']['talla']; ?></p></li>
					<li class="extendido"><span><?= __('Color'); ?>:</span><p><?= $producto['Color']['nombre']; ?></p></li>
					<li class="extendido"><span><?= __('Codigo'); ?>:</span><p><?= $producto['Producto']['codigo'], $producto['Color']['codigo']; ?></p></li>
					<li class="extendido"><span><?= __('Precio'); ?>:</span>
					<p><?= $this->Shapeups->moneda($producto['ProductosCompra']['valor']); ?></p>
					</li>
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
			</div>
		<? endif; ?>
	<? endforeach; ?>
	<h2 class="subtitulo"><? __('Productos Devueltos');?></h2>
	<? foreach ( $productos as $index => $producto ) : ?>
		<? if ( isset($producto['ProductosCompra']['estado']) && $producto['ProductosCompra']['estado'] == 2 ) : ?>
			<div class="previsualizar">
				<div class="prev-imagen" style="position: absolute; right: 0; background-color: #FFF; width: 100px; height: 80px; margin-right: 50px;">
					<?= $this->Html->image($producto['Producto']['foto']['mini'], array('style' => 'margin-top: 8px; margin-left: 13px;')); ?>
				</div>
				<ul class=" prev-<?= $producto['Producto']['id']; ?>">
					<li class="extendido"><span><?= __('Nombre'); ?>:</span><p><?= $producto['Producto']['nombre']; ?></p></li>
					<li class="extendido"><span><?= __('Talla'); ?>:</span><p><?= $producto['Stock']['talla']; ?></p></li>
					<li class="extendido"><span><?= __('Color'); ?>:</span><p><?= $producto['Color']['nombre']; ?></p></li>
					<li class="extendido"><span><?= __('Codigo'); ?>:</span><p><?= $producto['Producto']['codigo'], $producto['Color']['codigo']; ?></p></li>
					<li class="extendido"><span><?= __('Precio'); ?>:</span>
					<p><?= $this->Shapeups->moneda($producto['ProductosCompra']['valor']); ?></p>
					</li>
					<? if ($producto['ProductosCompra']['razon']) : ?>
					<li class="extendido"><span><?= __('Razón'); ?>:</span><?= $producto['ProductosCompra']['razon']; ?></li>
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
			</div>
		<? endif; ?>
	<? endforeach; ?>
	<div class="botones">
		<?= $this->Html->link('<span class="guardar">Guardar</span>', '#', array('escape' => false, 'class' => 'submit')); ?>
	</div>
	<?= $this->Form->end(); ?>
</div>
