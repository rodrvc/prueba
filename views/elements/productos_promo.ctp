<style>
.productos-promo {
	height: auto !important;
}
.productos-promo .center {
	text-align: center;
}
.productos-promo .botonPromo {
	background-color: #0F65C0;
	border: none;
	color: #fff;
	border-radius: 4px;
	padding: 2px;

    padding-right: 10px;

	margin: 5px 0;
	cursor: pointer;
}
.productos-promo .botonPromo:hover {
	background-color: #22b7ff;
}
.productos-promo > tbody > tr  {
	border-bottom: none !important;
}
.productos-promo > tbody > tr > td {
	width: 185px !important;
}
.productos-promo .botonPromo i.icono-carro {
	float: left;
	background-image: url(<?= $this->Html->url('/img/carrito-compra.png'); ?>);
	background-repeat: no-repeat;
	width: 18px;
	height: 16px;
	padding: 0 3px;
	border-right: 1px dotted #aaa;
	margin-right: 5px;
}
.productos-promo .producto-nombre {
	font-size: small;
	font-weight: bold;
}
.productos-promo .producto-codigo {
	font-size: smaller;
	opacity: 0.7;
	margin: 5px 0;
}
.productos-promo .producto-precio {
	font-size: smaller;
	font-weight: bold;
}
</style>
<div class="carro sinm" style="margin-top: 25px;">
	<h2>Promoción agrega por $2.990</h2>
	<table width="100%" class="item productos-promo">
		<tbody>
			<tr>
				<? foreach ($productosPromo as $promo) : ?>
				<td >
					<div class="txt center" style="padding: 0 25px;">
						<img src="<?= $this->Shapeups->imagen($promo['Producto']['foto']['ith']); ?>" height="97" />
					</div>
					<?= $this->Form->create('Producto'); ?>
					<?= $this->Form->hidden('id', array('value' => $promo['Stock']['id'])); ?>
					<div class="txt center">
						<button type="submit" class="botonPromo"><i class="icono-carro"></i>agregar</button>
					</div>
					<?= $this->Form->end(); ?>
					<div class="txt center producto-nombre"><?= $promo['Producto']['nombre']; ?></div>
					<div class="txt center producto-precio">
						<?
						$precio = $promo['Producto']['precio'];
						if ($promo['Producto']['oferta'] && $promo['Producto']['precio_oferta'] < $precio)
							$precio = $promo['Producto']['precio_oferta'];
						echo '+'. $this->Shapeups->moneda($precio);
						?>
					</div>
				</td>
				<? endforeach; ?>
			</tr>
		</tbody>
	</table>
	<div class="marco-fin" style="position: relative;"></div>
</div>