<h1 class="titulo"><? __('Datos Despacho # ');?><?=$compra['Compra']['id']; ?></h1>
<div class="previsualizar">
	<ul>
		<li><span><? __('Picking Number'); ?>:</span><p><?= (isset($this->data['Compra']['picking_number']) && $this->data['Compra']['picking_number']) ? $this->data['Compra']['picking_number'] : ''; ?></p></li>
		<li><span><? __('Boleta'); ?>:</span><p><?= $compra['Compra']['boleta']; ?>&nbsp;</p></li>
		<li><span><?= __('Codigo Despacho'); ?>:</span><p><a target="_blank" href="https://www.chilexpress.cl/Views/ChilexpressCL/Resultado.aspx?uq=<?php echo $compra['Compra']['cod_despacho']; ?>" ><?php echo $compra['Compra']['cod_despacho']; ?></a></p></li>
		<li><span>	<?= __('ID'); ?>:</span><p><?= $compra['Compra']['numId']; ?></p></li>
		<li ><span><?= __('Pares'); ?>:</span><?= count($productos); ?></li>
		<li><span>	<?= __('OC'); ?>:</span><p><?= $compra['Compra']['id']; ?></p></li>


<? foreach ( $productos as $producto ) : ?>
		<li class="extendido">
		<span style="width:50px;">Estilo : </span><p style="width:80px;"><?= $producto['Producto']['codigo']; ?></p>
		<span style="width:50px;">Color : </span><p style="width:120px;"><?= $producto['Color']['codigo']; ?></p>
		<span style="width:50px;">Talla : </span><p style="width:40px;"><?= $producto['Stock']['talla']; ?></p>
		<span style="width:70px;">Division : </span><p style="width:40px;"><?= $producto['Producto']['division']; ?></p>
		<span style="width:50px;">Precio : </span><p style="width:60px;"><?= $this->Shapeups->moneda($producto['ProductosCompra']['valor']); ?></p>
	</li>
		<? endforeach; ?>

	</ul>
</div>
<div class="botones">
	<?= $this->Html->link('<span class="guardar">Enviado</span>', array('controller' => 'compras', 'action' => 'enviado', $compra['Compra']['id']), array('escape' => false, 'class' => 'boton-enviado', 'style' => 'display: none;')); ?>
</div>