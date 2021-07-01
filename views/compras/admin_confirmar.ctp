<div class="col02">
	<!-- DATOS COMPRA -->
	<h1 class="titulo">Confirmacion de Cambio</h1>
	<h2 class="subtitulo"><? __('Compra');?></h2>
	<?= $this->Form->create('Compra', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
	<div class="previsualizar">
		<ul>
			<li class="extendido"><?= $this->Form->input('id'); ?></li>
			<?= $this->Form->hidden('estado'); ?>
			<?= $this->Form->hidden('subtotal'); ?>
			<?= $this->Form->hidden('iva'); ?>
			<?= $this->Form->hidden('valor_despacho'); ?>
			<?= $this->Form->hidden('descuento'); ?>
			<?= $this->Form->hidden('neto'); ?>
			<?= $this->Form->hidden('total'); ?>
			<li class="extendido"><span><? __('Usuario'); ?>:</span><p><?= $this->Html->link($compra['Usuario']['email'], array('controller' => 'usuarios', 'action' => 'view', $compra['Usuario']['id'])); ?>&nbsp;</p></li>
			<li><span><? __('Estado'); ?>:</span><p><?= $compra['Compra']['estado_nombre']; ?>&nbsp;</p></li>
			<li><p style="color: Blue;"><?= $this->data['Compra']['estado_nombre']; ?>&nbsp;</p></li>
			<li><span><? __('Subtotal'); ?>:</span><p><?= $this->Shapeups->moneda($compra['Compra']['subtotal']); ?>&nbsp;</p></li>
			<li><p style="color: Blue;"><?= $this->Shapeups->moneda($this->data['Compra']['subtotal']); ?>&nbsp;</p></li>
			<li><span><? __('Iva'); ?>:</span><p><?= $this->Shapeups->moneda($compra['Compra']['iva']); ?>&nbsp;</p></li>
			<li><p style="color: Blue;"><?= $this->Shapeups->moneda($this->data['Compra']['iva']); ?>&nbsp;</p></li>
			
			<li><span><? __('Neto'); ?>:</span><p><?= $this->Shapeups->moneda($compra['Compra']['neto']); ?>&nbsp;</p></li>
			<li><p style="color: Blue;"><?= $this->Shapeups->moneda($this->data['Compra']['neto']); ?>&nbsp;</p></li>
			
			<li><span><? __('Valor Despacho'); ?>:</span><p><?= $this->Shapeups->moneda($compra['Compra']['valor_despacho']); ?>&nbsp;</p></li>
			<li><p style="color: Blue;"><?= $this->Shapeups->moneda($this->data['Compra']['valor_despacho']); ?>&nbsp;</p></li>
			<li><span><? __('Descuento'); ?>:</span><p>- <?= $this->Shapeups->moneda($compra['Compra']['descuento']); ?>&nbsp;</p></li>
			<li><p style="color: Blue;">- <?= $this->Shapeups->moneda($this->data['Compra']['descuento']); ?>&nbsp;</p></li>
			<li><span><? __('Total'); ?>:</span><p><?= $this->Shapeups->moneda($compra['Compra']['total']); ?>&nbsp;</p></li>
			<li><p style="color: Blue;"><?= $this->Shapeups->moneda($this->data['Compra']['total']); ?>&nbsp;</p></li>
		</ul>
	</div>
	
	<!-- DATOS PRODUCTOS -->
	<h2 class="subtitulo"><? __('Productos');?></h2>
	<? foreach ( $productos as $index => $producto ) : ?>
	<?= $this->Form->hidden('Producto.' . $index . '.ProductosCompra.id'); ?>
	<?= $this->Form->hidden('Producto.' . $index . '.ProductosCompra.producto_id'); ?>
	<?= $this->Form->hidden('Producto.' . $index . '.ProductosCompra.talla'); ?>
	<div class="previsualizar">
		<ul>
			<li><span><?= __('Nombre'); ?>:</span><p><?= $producto['Producto']['nombre']; ?></p></li>
			<? if ( $producto['Stock']['id'] != $this->data['Producto'][$index]['Stock']['id'] ): ?>
			<li><p style="color: Blue;"><?= $this->data['Producto'][$index]['Producto']['nombre']; ?>&nbsp;</p></li>
			<? else: ?>
			<li><p style="color: Blue;">&nbsp;</p></li>
			<? endif; ?>
			<li><span><?= __('Talla'); ?>:</span><p><?= $producto['Stock']['talla']; ?></p></li>
			<? if ( $producto['Stock']['id'] != $this->data['Producto'][$index]['Stock']['id'] ): ?>
			<li><p style="color: Blue;"><?= $this->data['Producto'][$index]['Stock']['talla']; ?>&nbsp;</p></li>
			<? else: ?>
			<li><p style="color: Blue;">&nbsp;</p></li>
			<? endif; ?>
			<li><span><?= __('Color'); ?>:</span><p><?= $producto['Color']['nombre']; ?></p></li>
			<? if ( $producto['Stock']['id'] != $this->data['Producto'][$index]['Stock']['id'] ): ?>
			<li><p style="color: Blue;"><?= $this->data['Producto'][$index]['Color']['nombre']; ?>&nbsp;</p></li>
			<? else: ?>
			<li><p style="color: Blue;">&nbsp;</p></li>
			<? endif; ?>
			<li><span><?= __('Codigo'); ?>:</span><p><?= $producto['Producto']['codigo'], $producto['Color']['codigo']; ?></p></li>
			<? if ( $producto['Stock']['id'] != $this->data['Producto'][$index]['Stock']['id'] ): ?>
			<li><p style="color: Blue;"><?= $this->data['Producto'][$index]['Producto']['codigo'], $this->data['Producto'][$index]['Color']['codigo']; ?>&nbsp;</p></li>
			<? else: ?>
			<li><p style="color: Blue;">&nbsp;</p></li>
			<? endif; ?>
			<li><span><?= __('Precio'); ?>:</span>
			<? if ( $producto['Producto']['oferta'] == 1) : ?>
				<p><?= $this->Shapeups->moneda($producto['Producto']['precio_oferta']); ?></p>
			<? else : ?>
				<p><?= $this->Shapeups->moneda($producto['Producto']['precio']); ?></p>
			<? endif; ?>
			</li>
			<? if ( $producto['Stock']['id'] != $this->data['Producto'][$index]['Stock']['id'] ): ?>
				<? if ( $this->data['Producto'][$index]['Producto']['oferta'] == 1) : ?>
					<li><p style="color: Blue;"><?= $this->Shapeups->moneda($this->data['Producto'][$index]['Producto']['precio_oferta']); ?>&nbsp;</p></li>
				<? else : ?>
					<li><p style="color: Blue;"><?= $this->Shapeups->moneda($this->data['Producto'][$index]['Producto']['precio']); ?>&nbsp;</p></li>
				<? endif; ?>
			<? else: ?>
			<li><p style="color: Blue;">&nbsp;</p></li>
			<? endif; ?>
			<? if ( isset($producto['Descuento']) ) : ?>
			<li><span><?= __('Descuento'); ?>:</span><p style="color: Orange;"><?= $producto['Descuento']['nombre']; ?></p></li>
			<li><p style="color: Blue;">&nbsp;</p></li>
			<li><span><?= __('Codigo Desc.'); ?>:</span><p style="color: Orange;"><?= $producto['Descuento']['codigo']; ?></p></li>
			<li><p style="color: Blue;">&nbsp;</p></li>
				<? if ( $producto['Descuento']['tipo'] == 'DIN' ) : ?>
				<li><span><?= __('Monto Desc.'); ?>:</span><p style="color: Orange;"><?= $this->Shapeups->moneda($producto['Descuento']['descuento']); ?></p></li>
					<? if ( $producto['Stock']['id'] != $this->data['Producto'][$index]['Stock']['id'] ): ?>
					<li><p style="color: Blue;"><?= $this->Shapeups->moneda($this->data['Producto'][$index]['Descuento']['descuento']); ?>&nbsp;</p></li>
					<? else: ?>
					<li><p style="color: Blue;">&nbsp;</p></li>
					<? endif; ?>
				<? elseif ( $producto['Descuento']['tipo'] == 'POR' ) : ?>
				<li><span><?= __('Monto Desc.'); ?>:</span><p style="color: Orange;"><?= $producto['Descuento']['descuento']; ?>%</p></li>
					<? if ( $producto['Stock']['id'] != $this->data['Producto'][$index]['Stock']['id'] ): ?>
					<li><p style="color: Blue;"><?= $this->Shapeups->moneda($this->data['Producto'][$index]['Descuento']['descuento']); ?>&nbsp;</p></li>
					<? else: ?>
					<li><p style="color: Blue;">&nbsp;</p></li>
					<? endif; ?>
				<? endif; ?>
			<? endif; ?>
		</ul>
	</div>
	<? endforeach; ?>
	<div class="botones">
		<?= $this->Html->link('<span class="guardar">Confirmar Cambio</span>', '#', array('escape' => false, 'class' => 'submit')); ?>
		<?= $this->Html->link('<span class="volver">Volver</span>', array('controller' => 'compras', 'action' => 'cambiar', $compra['Compra']['id']), array('escape' => false)); ?>
	</div>
	<?= $this->Form->end(); ?>
</div>
