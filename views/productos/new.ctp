<div class="container">
	<div class="top-catalogo" style="margin-top: 20px;">
		<div class="row">
			<div class="col-md-9 col-xs-12 filtro">
				<a class="btn btn-default cyber" href="<?=$this->Html->url(array('controller' => 'new', 'action' => 'women'));?>">Women </a>
				<a class="btn btn-default cyber" href="<?=$this->Html->url(array('controller' => 'new', 'action' => 'men'));?>">Men </a>
				<a class="btn btn-default cyber" href="<?=$this->Html->url(array('controller' => 'new', 'action' => 'girls'));?>">Girls </a>
				<a class="btn btn-default cyber" href="<?=$this->Html->url(array('controller' => 'new', 'action' => 'boys'));?>">Boys </a>
			</div>
			<? if (isset($cont) && $cont) : ?>
			<div class="col-xs-12  col-md-3 pull-right text-right hidden-xs hidden-sm">
				<span>Viendo <b><?= $cont; ?></b> Resultados</span>
			</div>
			<? endif; ?>
		</div>
	</div>
	<div class="panel panel-skechers">
	  <div class="panel-body">
			<div class="row">
			    <? foreach( $productos as $producto ) : ?>
    				<?
						$link = $this->Html->url(array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug'])); // LINK AL DETALLE DE PRODUCTO
						// CLASE Y TEXTO DEL BOTON
						$class = 'new-arrival'; // CLASE INICIAL
						$texto = 'Comprar'; // TEXTO INICIAL
						/** verifica cuando un producto esta en oferta y calcula el porcentaje de ahorro del producto */
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
								}
							}
						}
					?>
					<!--NORMAL-->
					<div class="col-md-2 hidden-xs hidden-sm" style="width: 20%;">
						<div class="item-producto  text-center">
							<a href="<?= $link; ?>"><img src="<?= $this->Shapeups->imagen($producto['Producto']['foto']['ith']); ?>" class="img"></a>
							<div class="row">
								<div class="col-md-10 col-md-offset-1">
									<a href="<?= $link; ?>" class="btn btn-primary btn-block btn-md <?= $class; ?>"><?= $texto; ?></a>
								</div>
							</div>
							<a href="<?= $link; ?>"><h2 class="text-left" style="height: 35px;"><?= $producto['Producto']['nombre']; ?></h2></a>
							<? if ($producto['Producto']['oferta'] && $producto['Producto']['precio'] > $producto['Producto']['precio_oferta']) : ?>
								<h3 class="text-left text-info" style="color: #c00;height: 14px;">
									<s style="color: #888; font-weight: normal;"><?= $this->Shapeups->moneda($producto['Producto']['precio']); ?></s> <?= $this->Shapeups->moneda($producto['Producto']['precio_oferta']); ?>
								</h3>
								<? else : ?>
								<h3 class="text-left text-info" style="height: 14px;"><?= $this->Shapeups->moneda($producto['Producto']['precio']); ?></h3>
							<? endif; ?>
							<?= (false && isset($ahorro) && $ahorro) ? '<div class="porcentaje-descuento">'.$ahorro.'</div>':''; ?>
							<? if(false && isset($producto['Producto']['new']) && $producto['Producto']['new'] == 1 ) : // CUANDO EL PRODUCTO ES NUEVO !!! ?>
								<!-- NEW -->
								<div class="new"><img src="<?= $this->Html->url('/img/new_arrival.png'); ?>" width="100%"></div>
							<? endif; ?>
							<p class="text-left"><small class="text-muted">Disponible en <?= $producto['Producto']['colores']; ?> colores </small></p>
						</div>
					</div>
					<!--MOBILE-->
					<div class="col-xs-6 hidden-md hidden-lg">
						<div class="item-producto  text-center itemProductoMobile">
							<a href="<?= $link; ?>" class="boxImgMobile">
								<img src="<?= $this->Shapeups->imagen($producto['Producto']['foto']['ith']); ?>" width="100%" class="img-mobile" />
							</a>
							<div class="row">
								<div class="col-md-8 col-md-offset-2">
									<a href="<?= $link; ?>" class="btn btn-primary btn-block btn-sm <?= $class; ?>"><?= $texto; ?></a>
								</div>
							</div>
							<a href="<?= $link; ?>">
								<h2 class="text-left" style="height: 25px; font-size: 12px;"><?= $producto['Producto']['nombre']; ?></h2>
							</a>
							<? if ($producto['Producto']['oferta'] && $producto['Producto']['precio'] > $producto['Producto']['precio_oferta']) : ?>
								<h3 class="text-left text-info" style="color: #c00; height: 14px;"><s style="color: #888; font-weight: normal;"><?= $this->Shapeups->moneda($producto['Producto']['precio']); ?></s> <?= $this->Shapeups->moneda($producto['Producto']['precio_oferta']); ?></h3>
							<? else : ?>
								<h3 class="text-left text-info" style="height: 14px;"><?= $this->Shapeups->moneda($producto['Producto']['precio']); ?></h3>
							<? endif; ?>
							<?= (false && isset($ahorro) && $ahorro) ? '<div class="porcentaje-descuento">'.$ahorro.'</div>':''; ?>
							<? if(false && isset($producto['Producto']['new']) && $producto['Producto']['new'] == 1 ) : // CUANDO EL PRODUCTO ES NUEVO !!! ?>
								<!-- NEW -->
								<div class="new"><img src="<?= $this->Html->url('/img/new_arrival.png'); ?>" width="100%"></div>
							<? endif; ?>
							<p class="text-left"><small class="text-muted">Disponible en <?= $producto['Producto']['colores']; ?> colores </small></p>
						</div>
					</div>
				<? endforeach; ?>
			</div>
			<? if (isset($otros) && $otros) : ?>
				<hr />
				<div class="row">
					<p><?= $titulo_otros; ?></p>
					<? foreach ($otros as $producto) : ?>
						<?
							$link = $this->Html->url(array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug'])); // LINK AL DETALLE DE PRODUCTO
							// CLASE Y TEXTO DEL BOTON
							$class = 'new-arrival'; // CLASE INICIAL
							$texto = 'Comprar'; // TEXTO INICIAL
							if (! isset($producto['disponible'])) // CUANDO EL PRODUCTO NO ESTA DISPONIBLE
							{
								$texto = 'Coming Soon';
								$class = 'disabled';
							}
							elseif (! $producto['disponible'] && isset($producto['Producto']['new']) && $producto['Producto']['new'] == 1 ) // CUANDO EL PRODUCTO NO ESTA DISPONIBLE Y NO TIENE STOCK
							{
								$texto = 'Coming Soon';
								$class = 'disabled';
							}
							/** verifica cuando un producto esta en oferta y calcula el porcentaje de ahorro del producto */
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
									}
								}
							}
						?>
						<!--NORMAL-->
						<div class="col-md-2 hidden-xs hidden-sm" style="width: 20%;">
							<div class="item-producto text-center">
								<a href="<?= $link; ?>">
									<img src="<?= $this->Shapeups->imagen($producto['Producto']['foto']['ith']); ?>" width="100%" class="img">
								</a>
								<div class="row">
									<div class="col-md-8 col-md-offset-2">
										<a href="<?= $link; ?>" class="btn btn-primary btn-block btn-sm <?= $class; ?>"><?= $texto; ?></a>
									</div>
								</div>
								<a href="<?= $link; ?>"><h2 class="text-left"><?= $producto['Producto']['nombre']; ?></h2></a>
								<? if ($producto['Producto']['oferta'] && $producto['Producto']['precio'] > $producto['Producto']['precio_oferta']) : ?>
									<p class="text-left"><s><?= $this->Shapeups->moneda($producto['Producto']['precio']); ?></s></p>
									<h3 class="text-left text-info"><?= $this->Shapeups->moneda($producto['Producto']['precio_oferta']); ?></h3>
									<? else : ?>
									<p class="text-left">&nbsp;</p>
									<h3 class="text-left text-info"><?= $this->Shapeups->moneda($producto['Producto']['precio']); ?></h3>
								<? endif; ?>
								<?= (isset($ahorro) && $ahorro) ? '<div class="porcentaje-descuento">'.$ahorro.'</div>':''; ?>
								<? if( isset($producto['Producto']['new']) && $producto['Producto']['new'] == 1 ) : // CUANDO EL PRODUCTO ES NUEVO !!! ?>
									<!-- NEW -->
									<div class="new"><img src="<?= $this->Html->url('/img/new_arrival.png'); ?>" width="100%"></div>
								<? endif; ?>
								<p class="text-left"><small class="text-muted">Disponible en <?= $producto['Producto']['colores']; ?> colores </small></p>
							</div>
						</div>
						<!--MOBILE-->
						<div class="col-xs-6 hidden-md hidden-lg">
							<div class="item-producto  text-center itemProductoMobile">
								<a href="<?= $link; ?>" class="boxImgMobile">
									<img src="<?= $this->Shapeups->imagen($producto['Producto']['foto']['ith']); ?>" width="100%" class="img-mobile" />
								</a>
								<div class="row">
									<div class="col-md-8 col-md-offset-2">
										<a href="<?= $link; ?>" class="btn btn-primary btn-block btn-sm <?= $class; ?>"><?= $texto; ?></a>
									</div>
								</div>
								<a href="<?= $link; ?>"><h2 class="text-left"><?= $producto['Producto']['nombre']; ?></h2></a>
								<? if ($producto['Producto']['oferta'] && $producto['Producto']['precio'] > $producto['Producto']['precio_oferta']) : ?>
									<p class="text-left"><s><?= $this->Shapeups->moneda($producto['Producto']['precio']); ?></s></p>
									<h3 class="text-left text-info"><?= $this->Shapeups->moneda($producto['Producto']['precio_oferta']); ?></h3>
									<? else : ?>
									<p class="text-left">&nbsp;</p>
									<h3 class="text-left text-info"><?= $this->Shapeups->moneda($producto['Producto']['precio']); ?></h3>
								<? endif; ?>
								<?= (isset($ahorro) && $ahorro) ? '<div class="porcentaje-descuento">'.$ahorro.'</div>':''; ?>
								<? if( isset($producto['Producto']['new']) && $producto['Producto']['new'] == 1 ) : // CUANDO EL PRODUCTO ES NUEVO !!! ?>
									<!-- NEW -->
									<div class="new"><img src="<?= $this->Html->url('/img/new_arrival.png'); ?>" width="100%"></div>
								<? endif; ?>
								<p class="text-left"><small class="text-muted">Disponible en <?= $producto['Producto']['colores']; ?> colores </small></p>
							</div>
						</div>
					<? endforeach; ?>
				</div>
			<? endif; ?>
	  </div>
	</div>
</div>
<style>
	 .cyber, .cyber:hover, .btn-default:hover, .btn-default:focus, .btn-default:active, .btn-default.active, .open>.dropdown-toggle.btn-default {
	background: #323232;
	color: #fff;
	text-transform: capitalize;
	line-height: 36px;
	padding: 0 15px;
	text-align: center;
	border: none;
	-webkit-border-radius: 0 0 0 0;
	border-radius: 0 0 0 0;
}
</style>
