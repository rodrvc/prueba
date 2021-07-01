<li>
	<a href="<?= $this->Html->url(array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug'])); ?>">
		<?
			$imagen = $this->Html->url('/img/sin-zapatilla.jpg');
			if (isset($producto['Producto']['foto']['ith']) && $producto['Producto']['foto']['ith'])
			{
				$imagen = $this->Html->url('/img/'.$producto['Producto']['foto']['ith']);
				if (! $serv_produccion)
					$imagen = $dir_produccion.'img/'.$producto['Producto']['foto']['ith'];
			}
		?>
		<img src="<?= $imagen; ?>" width="154" height="130" style="margin-left: 7px; padding-bottom: 20px;" />
		<?
			/**	ETIQUETA QUE DESTACA EL ESTADO DEL PRODUCTO: escolar, new, oferta */
			//if( isset($producto['Producto']['escolar']) && $producto['Producto']['escolar'] == 1 )
			//	echo '<span class="escolar"></span>';
			if( isset($producto['Producto']['new']) && $producto['Producto']['new'] == 1 )
				echo '<span class="new-arrival"></span>';
			//elseif ( isset($producto['Producto']['oferta']) && $producto['Producto']['oferta'] == 1 )
			//	echo '<span class="sale"></span>';
		?>
	</a>
	<?
		$ahorro = 0;
		if ( isset($producto['Producto']['oferta']) && $producto['Producto']['oferta'] == 1 )
		{
			if ($producto['Producto']['precio_oferta'] && $producto['Producto']['precio'])
			{
				$ahorro = ($producto['Producto']['precio_oferta']*100)/$producto['Producto']['precio'];
				$ahorro = 100-$ahorro;
				if ($ahorro)
				{
					$ahorro = round($ahorro,0).'%';
					echo '<div class="porcentaje-descuento">'.$ahorro.'</div>';
				}
			}
		}
	?>
	<div class="boton home">
		<?
			//$class = 'naranja';
			//if ( isset($producto['Producto']['new']) && $producto['Producto']['new'] == 1 )
			//	$class = 'new-arrival';
			//elseif ( isset($producto['Producto']['oferta']) && $producto['Producto']['oferta'] == 1 )
			//	$class = 'rojo';
			$class = 'new-arrival';
		?>
		<a href="<?= $this->Html->url(array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug'])); ?>" class="<?= $class; ?>"><span>Comprar</span></a>
	</div>
	<a href="<?= $this->Html->url(array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug'])); ?>" class="nombre"><?= $producto['Producto']['nombre']; ?></a>
	<span class="codigo">Código #<?= "{$producto['Producto']['codigo']}{$producto['Color']['codigo']}"; ?></span>
	<div class="precios">
		<? if ( $producto['Producto']['oferta'] ) : ?>
		<s><?= $this->Shapeups->moneda($producto['Producto']['precio']); ?></s>
		<b class="oferta"><?= $this->Shapeups->moneda($producto['Producto']['precio_oferta']); ?></b>
		<? else : ?>
		<b><?= $this->Shapeups->moneda($producto['Producto']['precio']); ?></b>
		<? endif; ?>
	</div>
	<span class="disponibilidad">Disponible en <?= $producto['Producto']['colores']; ?> color<?= ($producto['Producto']['colores'] > 1 ? 'es' : ''); ?></span>
</li>