<?
$activar_hide = false;
if (! $activar_hide)
{
	$index_hide = 0;
}
?>
<li<?= (isset($index_hide) && $index_hide > 20) ? ' class="hide" style="display: none;"' : '' ; ?>>
	<a href="<?= $this->Html->url(array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug'])); ?>">
		<?
			$imagen = $this->Html->url('/img/sin-zapatilla.jpg');
			if (isset($producto['Producto']['foto']['ith']) && $producto['Producto']['foto']['ith'])
			{
					$imagen = $dir_produccion.'/img/'.$producto['Producto']['foto']['ith'];
			}
		?>
		<? if (isset($index_hide) && $index_hide > 20) : ?>
		<img src="" data-img="<?= $imagen; ?>" width="154" height="130" style="margin-left: 7px; padding-bottom: 20px;" />
		<? else : ?>
		<img src="<?= $imagen; ?>" width="154" height="130" style="margin-left: 7px; padding-bottom: 20px;" />
		<? endif; ?>
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
			if ($producto['Producto']['precio'] > $producto['Producto']['precio_oferta'])
			{
				$ahorro = ($producto['Producto']['precio_oferta']*100)/$producto['Producto']['precio'];
				$ahorro = 100-$ahorro;
				if ($ahorro)
				{
					$ahorro = (round(($ahorro/10),0)*10).'%';
					echo '<div class="porcentaje-descuento">'.$ahorro.'</div>';
				}
			}
		}
	?>
	<div class="boton home">
		<?
			$link = $this->Html->url(array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug']));

			$class = 'new-arrival';
			$texto = '<span>Comprar</span>';
			if (! isset($producto['disponible']))
			{
				$texto = 'Coming Soon';
				$class = 'coming-soon';
			}
			elseif (! $producto['disponible'] && isset($producto['Producto']['new']) && $producto['Producto']['new'] == 1 )
			{
				$texto = 'Coming Soon';
				$class = 'coming-soon';
			}
			echo '<a href="'.$link.'" class="'.$class.'">'.$texto.'</a>';
		?>
	</div>
	<a href="<?= $link; ?>" class="nombre"><?= $producto['Producto']['nombre']; ?></a>
	<span class="codigo">Código #<?= "{$producto['Producto']['codigo']}{$producto['Color']['codigo']}"; ?></span>
	<div class="precios">
		<? if ($producto['Producto']['oferta'] && $producto['Producto']['precio'] > $producto['Producto']['precio_oferta']) : ?>
		<s><?= $this->Shapeups->moneda($producto['Producto']['precio']); ?></s>
		<b class="oferta"><?= $this->Shapeups->moneda($producto['Producto']['precio_oferta']); ?></b>
		<? else : ?>
		<b><?= $this->Shapeups->moneda($producto['Producto']['precio']); ?></b>
		<? endif; ?>
	</div>
	<span class="disponibilidad">Disponible en <?= $producto['Producto']['colores']; ?> color<?= ($producto['Producto']['colores'] > 1 ? 'es' : ''); ?></span>
</li>



<?
	$imagen = $this->Html->url('/img/sin-zapatilla.jpg');
	if (isset($producto['Producto']['foto']['ith']) && $producto['Producto']['foto']['ith'])
	{
		$imagen = $dir_produccion.'/img/'.$producto['Producto']['foto']['ith'];
	}
	$link = $this->Html->url(array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug']));
?>

<div class="thumbnail">
  <img src="<?= $imagen; ?>" alt="...">
  <div class="caption">
    <h3>Thumbnail label</h3>
    <p>...</p>
    <p><a href="<?= $link; ?>" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
  </div>
</div>
