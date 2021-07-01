<div class="container">
	<? if (isset($titulo) && $titulo) : ?>
		<h1 class="titulo-categoria"><?= $titulo; ?></h1>
	<? endif; ?>
	<div class="top-catalogo">
		<div class="row" style="width:100%;margin-left: 0px; margin-right: 0px; padding-bottom: 10px;">
		<img class="img-responsive" src="https://d1d6stsa4vf3p.cloudfront.net/img/Banner_sale.jpg">
	</div>
		<div class="row">
			<div class="col-md-9 col-xs-12 filtro">
				<? if (isset($filtros) && $filtros) : ?>
					<? foreach ($filtros as $filtro) : ?>
						<div class="btn-group-vertical" aria-label="Vertical button group" role="group" style="vertical-align: top;">
							<div class="btn-group" role="group">
								<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
									<?= $filtro['name']; ?>
									<span class="caret"></span>
								</button>
								<? if ($filtro['field'] == 'talla') : ?>
								<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" style="min-width: 250px;">
									<li>
										<? foreach ($filtro['options'] as $optionsFilter) : ?>
											<?
												// generar link rescatando parametros
												$url = array('action' => 'ropa');
												if (isset($this->params['pass'][0]) && $this->params['pass'][0])
													$url[0] = $this->params['pass'][0];
												$url['?'] = array($filtro['field'] => $optionsFilter['value']);
												$excluirParam = array('url',$filtro['field']);
												if (isset($this->params['url']) && $this->params['url'])
												{
													foreach ($this->params['url'] as $paramKey => $paramValue)
													{
														if (in_array($paramKey,$excluirParam))
															continue;
														$url['?'][$paramKey] = $paramValue;
													}
												}
											?>
											<!--LISTADO DE TALLAS-->
											<div class="col-md-3" style="padding: 0 7px;">
												<a href="<?= $this->Html->url($url); ?>" class="btn btn-tallas btn-block text-center"><?= $optionsFilter['name']; ?></a>
											</div>
										<? endforeach; ?>
									</li>
								</ul>
								<? elseif ($filtro['field'] == 'color') : ?>
								<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" style="min-width: 280px;">
									<li>
										<? foreach ($filtro['options'] as $optionsFilter) : ?>
											<?
												// generar link rescatando parametros
												$url = array('action' => 'ropa');
												if (isset($this->params['pass'][0]) && $this->params['pass'][0])
													$url[0] = $this->params['pass'][0];
												$url['?'] = array($filtro['field'] => $optionsFilter['value']);
												$excluirParam = array('url',$filtro['field']);
												if (isset($this->params['url']) && $this->params['url'])
												{
													foreach ($this->params['url'] as $paramKey => $paramValue)
													{
														if (in_array($paramKey,$excluirParam))
															continue;
														$url['?'][$paramKey] = $paramValue;
													}
												}
											?>
											<!--LISTADO DE TALLAS-->
											<div class="col-md-6" style="padding: 0 7px;">
												<a href="<?= $this->Html->url($url); ?>" class="list-color">
													<span class="icon-color" style="background-image:url(<?= $this->Shapeups->imagen($optionsFilter['icon']); ?>);background-position:-1px -1px;width:13px;height:13px;"></span>
													<?= $optionsFilter['name']; ?>
												</a>
											</div>
										<? endforeach; ?>
									</li>
								</ul>
								<? else : ?>
								<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
									<? foreach ($filtro['options'] as $optionsFilter) : ?>
										<?
											// generar link rescatando parametros
											$url = array('action' => 'ropa');
											if (isset($this->params['pass'][0]) && $this->params['pass'][0])
												$url[0] = $this->params['pass'][0];
											$url['?'] = array($filtro['field'] => $optionsFilter['value']);
											if ($filtro['field'] != 'categoria')
											{
												$excluirParam = array('url',$filtro['field']);
												if (isset($this->params['url']) && $this->params['url'])
												{
													foreach ($this->params['url'] as $paramKey => $paramValue)
													{
														if (in_array($paramKey,$excluirParam))
															continue;
														$url['?'][$paramKey] = $paramValue;
													}
												}
											}
										?>
										<!--LISTADO VERTICAL-->
										<li>
											<a href="<?= $this->Html->url($url); ?>" class="ordenar-catalogo">
											   <?= $optionsFilter['name']; ?>
											</a>
										</li>
									<? endforeach; ?>
								</ul>
								<? endif; ?>
							</div>
							<? if (isset($this->params['url'][$filtro['field']]) && $this->params['url'][$filtro['field']]) : ?>
								<?
									$txtClear = '<i class="fa fa-times-circle"></i> ';
									foreach ($filtro['options'] as $optionsFilter)
									{
										if ($optionsFilter['value'] == $this->params['url'][$filtro['field']])
											$txtClear.=$optionsFilter['name'];
									}
									// generar link rescatando parametros
									$url = array('action' => 'ropa');
									if (isset($this->params['pass'][0]) && $this->params['pass'][0])
										$url[0] = $this->params['pass'][0];
									$url['?'] = array();
									$excluirParam = array('url',$filtro['field']);
									if (isset($this->params['url']) && $this->params['url'])
									{
										foreach ($this->params['url'] as $paramKey => $paramValue)
										{
											if (in_array($paramKey,$excluirParam))
												continue;
											$url['?'][$paramKey] = $paramValue;
										}
									}
								?>
								<a class="btn btn-clear" href="<?= $this->Html->url($url); ?>"><small><?= $txtClear; ?></small></a>
							<? endif; ?>
						</div>
					<? endforeach; ?>
				<? endif; ?>
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
								<p class="text-left"><small class="text-muted">Disponible en <?= ($producto['Producto']['colores']) ? $producto['Producto']['colores']:'1'; ?> colores </small></p>
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
								<p class="text-left"><small class="text-muted">Disponible en <?= ($producto['Producto']['colores']) ? $producto['Producto']['colores']:'1'; ?> colores </small></p>
							</div>
						</div>
					<? endforeach; ?>
				</div>
			<? endif; ?>
	  </div>
	</div>
</div>