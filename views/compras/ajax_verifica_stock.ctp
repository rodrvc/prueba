<div class="previsualizar" style="width: 300px; background-color: #BBBBBB; color: #333333; text-align: center; margin-bottom: 0; padding-bottom: 0;">
    <? if ( isset($producto['Talla']) && $producto['Talla']) : ?>
	<ul class="datos-nuevo" style="width: 300px;">
        <li style="width: 300px;"><span style="width: 60px;"><?= __('Nombre'); ?>:</span><p><?= $producto['Producto']['nombre']; ?></p></li>
        <li style="width: 300px;"><span style="width: 60px;"><?= __('Talla'); ?>:</span><p><?= $producto['Talla'][0]['talla']; ?></p></li>
        <li style="width: 300px;"><span style="width: 60px;"><?= __('Color'); ?>:</span><p><?= $producto['Color']['nombre']; ?></p></li>
        <li style="width: 300px;"><span style="width: 60px;"><?= __('Codigo'); ?>:</span><p><?= $producto['Producto']['codigo'], $producto['Color']['codigo']; ?></p></li>
        <li style="width: 300px;"><span style="width: 60px;"><?= __('Precio'); ?>:</span>
        <?
			$valor = $producto['Producto']['precio'];
			if ( $producto['Producto']['oferta'] )
			{
				$valor = $producto['Producto']['precio_oferta'];
			}
		?>
        <p<?= ($producto['Producto']['oferta']) ? ' style="color: Blue;"' : '' ; ?>><?= $this->Shapeups->moneda($valor); ?></p>
        </li>
    </ul>
    <div class="botones">
        <?= $this->Html->link('<span class="reload">Cambiar</span>', '#', array('escape' => false, 'class' => 'edit-product', 'style' => 'border-radius: 5px; margin-top: 5px;')); ?>
		<? if ($valor < $precio_orinal) : ?>
			<?= $this->Html->link('<span class="previsualizar">Generar Cupon</span>', '#', array('escape' => false, 'class' => 'cupon-generator', 'style' => 'border-radius: 5px; margin-top: 5px;', 'data-valor' => ($precio_orinal-$valor))); ?>
		<? endif; ?>
    </div>
    <? else : ?>
        Este Producto no se encuentra disponible en esta talla
    <? endif; ?>
</div>