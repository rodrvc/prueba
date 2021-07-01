<div class="titulo">
	paso 2 >> seleccion de productos
</div>
<? if ($compra['Producto']) : ?>
	<? foreach ($compra['Producto'] as $producto) : ?>
	<div class="contenedor-producto">
		<div class="compra">
			<?= $producto['codigo'].$producto['Color']['codigo']; ?>
		</div>
		<div class="productos">
			<ul>
				<? if (isset($producto['ProductosCompra']['devolucion_dinero']) && $producto['ProductosCompra']['devolucion_dinero'] == 0) : ?>
					<? if (isset($producto['ProductosCompra']['estado']) && $producto['ProductosCompra']['estado'] == 0) : ?>
						<? if (isset($producto['ProductosCompra']['razon']) && $producto['ProductosCompra']['razon']) : ?>
							<li class="estado">
								<i>en devolución [solicitud de cambio]</i>
								<a href="#" id="cambiar-producto" class="cambiar" data-id="<?= $producto['ProductosCompra']['id']; ?>" data-proceso="GIFT-CARD"><img src="<?= $this->Html->url('/img/iconos/right_16.png'); ?>" /></a>
							</li>
							<li>
								<b>razon:</b>
								<?= $producto['ProductosCompra']['razon']; ?>
							</li>
						<? else : ?>
							<li class="estado">
								<i>individualizar producto de la compra</i>
								<a href="#" id="cambiar-producto" class="cambiar" data-id="<?= $producto['ProductosCompra']['id']; ?>" data-proceso="GENERAR-COMPRA"><img src="<?= $this->Html->url('/img/iconos/right_16.png'); ?>" /></a>
							</li>
						<? endif; ?>
					<? elseif (isset($producto['ProductosCompra']['estado']) && $producto['ProductosCompra']['estado'] == 1) : ?>
					<li class="estado">
						<i>en devolución [talla seleccionada]</i>
						<a href="#" id="cambiar-producto" class="cambiar" data-id="<?= $producto['ProductosCompra']['id']; ?>" data-proceso="GENERAR-COMPRA"><img src="<?= $this->Html->url('/img/iconos/right_16.png'); ?>" /></a>
					</li>
					<? elseif (isset($producto['ProductosCompra']['estado']) && $producto['ProductosCompra']['estado'] == 2) : ?>
					<li class="estado"><i>en devolución [talla devuelta]</i></li>
					<li>
						<b>razon:</b>
						<?= $producto['ProductosCompra']['razon']; ?>
					</li>
					<? endif; ?>
				<? else : ?>
				<li class="estado">
					<i>en devolución [devolución de dinero]</i>
					<a href="#" id="cambiar-producto" class="cambiar" data-id="<?= $producto['ProductosCompra']['id']; ?>" data-proceso="DEVOLVER-DINERO"><img src="<?= $this->Html->url('/img/iconos/right_16.png'); ?>" /></a>
				</li>
				<? endif; ?>
				<li>
					<b>talla:</b>
					<?= $producto['ProductosCompra']['talla']; ?>
				</li>
				<li>
					<b>valor:</b>
					<?= $this->Shapeups->moneda($producto['ProductosCompra']['valor']); ?>
				</li>
			</ul>
		</div>
	</div>
	<? endforeach; ?>
<? endif; ?>