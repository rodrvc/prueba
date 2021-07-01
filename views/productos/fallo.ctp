<div class="container">
	<div class="panel panel-default">
		<div class="panel-body nopadding-top">
			<div class="row">
				<div class="col-md-12">
					<div class="progress barra">
						<div class="progress-bar progress-bar-primary relative" style="width: 20%">
							<span class="icon-skechers">
								<div class="icono">
									<img src="<?= $this->Html->url('/img/bootstraps/icon-skechers-wizard.png'); ?>" width="100%">
								</div>
								<div class="texto">
									Despacho
								</div>
							</span>
						</div>
						<div class="progress-bar relative progress-bar-primary" style="width: 20%">
						  <span class="icon-skechers">
							  <div class="icono">
								  <img src="<?= $this->Html->url('/img/bootstraps/icon-skechers-wizard.png'); ?>" width="100%">
							  </div>
							  <div class="texto">
								  Confirmar
							  </div>
						  </span>
						</div>
						<div class="progress-bar relative progress-bar-primary" style="width: 20%">
							<span class="icon-skechers">
								<div class="icono">
									<img src="<?= $this->Html->url('/img/bootstraps/icon-skechers-wizard.png'); ?>" width="100%">
								</div>
								<div class="texto">
									Pago
								</div>
							</span>
						</div>
						<div class="progress-bar relative progress-bar-primary" style="width: 20%">
							<span class="icon-skechers">
								<div class="icono">
									<img src="<?= $this->Html->url('/img/bootstraps/icon-skechers-wizard.png'); ?>" width="100%">
								</div>
								<div class="texto">
									Recibo
								</div>
							</span>
						</div>
						<div class="progress-bar relative progress-bar-primary" style="width: 20%"></div>
					</div>
				</div>
			</div>
			<div class="page-header">
				<h1 class="text-center">
					<b>Estimado <?= $authUser['nombre'].' '.$authUser['apellido_paterno'].' '.$authUser['apellido_materno']; ?>.</b>
					<br>Tu compra NO ha logrado finalizar
				</h1>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div id="documentoCompra" class="panel panel-danger">
						<div class="panel-heading">
							<h3 class="panel-title">Información de tu compra</h3>
						</div>
						<div class="panel-body">
							<div id="carro-mobile" class="hidden-md hidden-lg hidden-sm">
								<h4>
									Tu carro de compras:
								</h4>
								<? if ( isset($productos) && $productos ) : ?>
									<? foreach ( $productos as $index => $producto ) : ?>
										<div class="row">
											<div class="col-xs-4 text-center">
												<img src="<?= $this->Shapeups->imagen('Producto/'.$producto['Producto']['id'].'/full_'.$producto['Producto']['foto']); ?>" width="100%" />
											</div>
											<div class="col-xs-8">
												<ul class="list-group small">
													<li class="list-group-item">
														<b><?= $producto['Producto']['nombre']; ?></b>
													</li>
													<li class="list-group-item">
														Talla: <?= $producto['Stock']['talla']; ?>
													</li>
													<li class="list-group-item">
														Color: <?= $producto['Producto']['Color']['nombre']; ?>
													</li>
													<li class="list-group-item">
														Cantidad: <?= $producto['cantidad']; ?>
													</li>
												</ul>
											</div>
										</div>
										<hr>
									<? endforeach; ?>
								<? endif; ?>
								<div class="row">
									<div class="col-xs-12">
										<ul class="list-group">
											<li class="list-group-item list-group-item-info">
												Subtotal:
												<span class="badge"><?= $this->Shapeups->moneda($subtotal); ?></span>
											</li>
											<li class="list-group-item">
												Despacho a domicilio:
												<span class="badge"><?= $this->Shapeups->moneda($despacho_val); ?></span>
											</li>
											<? if (isset($descuento) && $descuento) : ?>
											<li class="list-group-item list-group-item-success">
												<b>Total:</b>
												<span class="badge">- <?= $this->Shapeups->moneda($descuento); ?></span>
											</li>
											<? endif; ?>
											<li class="list-group-item">
												Precio Neto:
												<span class="badge"><?= $this->Shapeups->moneda($neto); ?></span>
											</li>
											<li class="list-group-item">
												IVA(19%):
												<span class="badge"><?= $this->Shapeups->moneda($iva); ?></span>
											</li>
											
											<li class="list-group-item list-group-item-success">
												<b>Total:</b>
												<span class="badge"><?= $this->Shapeups->moneda($total); ?></span>
											</li>
											<li class="list-group-item text-center">
												<?= $this->Html->link('Ver políticas de despacho', 'http://www.skechers.cl/politicas_skechers.pdf', array('class' => 'politicas btn btn-xs', 'target' => '_blank')); ?>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="table-responsive hidden-xs">
								<table class="table table-skechers table-condensed">
									<thead>
										<tr>
											<th colspan="7">
												<h3>
													Tu carro de compras
												</h3>
											</th>
										</tr>
									</thead>
									<tbody>
										<tr class="plomo">
											<td class="col-md-1">Foto</td>
											<td class="text-left col-md-4">Nombre</td>
											<td class="text-center col-md-1">Color</td>
											<td class="text-center col-md-1">Código</td>
											<td class="text-center col-md-1">Talla</td>
											<td class="text-center col-md-1">Cantidad</td>
											<td class="text-center col-md-2">Valor</td>
										</tr>
										<? if ( isset($productos) && $productos ) : ?>
											<? foreach ( $productos as $index => $producto ) : ?>
												<tr>
													<td>
														<img src="<?= $this->Shapeups->imagen('Producto/'.$producto['Producto']['id'].'/full_'.$producto['Producto']['foto']); ?>" width="100%" />
													</td>
													<td class="text-left">
														<h4 class="nomargin text-info">
															<b><?= $producto['Producto']['nombre']; ?></b>
														</h4>
													</td>
													<td class="text-center">
														<b><?= $producto['Producto']['Color']['nombre']; ?></b>
													</td>
													<td class="text-center">
														<b><?= $producto['Producto']['codigo']; ?></b>
													</td>
													<td class="text-center">
														<b>
															<?= $producto['Stock']['talla']; ?>
														</b>
													</td>
													<td class="text-center">
														<b>
															<?= $producto['cantidad']; ?>
														</b>
													</td>
													<td class="text-center">
														<? if ( $producto['Producto']['oferta'] ) : ?>
															<p>
																<b class="text-info">
																	<?= $this->Shapeups->moneda($producto['Producto']['precio'] * $producto['cantidad']); ?>
																</b>
															</p>
															<p>
																<span class="text-danger">Oferta: <?= $this->Shapeups->moneda($producto['Producto']['precio_oferta'] * $producto['cantidad']); ?></span>
															</p>
														<? else : ?>
															<p>
																<b class="text-info">
																	<?= $this->Shapeups->moneda($producto['Producto']['precio'] * $producto['cantidad']); ?>
																</b>
															</p>
														<? endif; ?>
													</td>
												</tr>
											<? endforeach; ?>
										<? endif; ?>
										<tr class="danger">
											<td colspan="2" rowspan="6" class="text-center">
												<img src="<?= $this->Html->url('/img/bootstraps/webpay-plus.png'); ?>" width="70%">
											</td>
											<td colspan="4" class="text-right">Subtotal</td>
											<td class="text-center"><b class="text-info"><?= $this->Shapeups->moneda($subtotal); ?></b></td>
										</tr>
										<tr class="danger">
											<td colspan="4" class="text-right noborder">Despacho a domicilio <br> <?= $this->Html->link('Ver políticas de despacho', 'http://www.skechers.cl/politicas_skechers.pdf', array('class' => 'politicas btn btn-xs', 'target' => '_blank')); ?></td>
											<td class="text-center noborder"><?= $this->Shapeups->moneda($despacho_val); ?></td>
										</tr>
										<? if (isset($descuento) && $descuento) : ?>
										<tr class="danger">
											<td colspan="4" class="text-right noborder">Descuento</td>
											<td class="text-center noborder">- <?= $this->Shapeups->moneda($descuento); ?></td>
										</tr>
										<? endif; ?>
										<tr class="danger">
											<td colspan="4" class="text-right noborder">Precio Neto:</td>
											<td class="text-center noborder"><?= $this->Shapeups->moneda($neto); ?></td>
										</tr>
										<tr class="danger">
											<td colspan="4" class="text-right noborder">IVA(19%):</td>
											<td class="text-center noborder"><?= $this->Shapeups->moneda($iva); ?></td>
										</tr>
										<tr class="danger">
											<td colspan="4" class="text-right divider-total">Total</td>
											<td class="text-center divider-total">
												<h3>
													<b class="text-info">
														<?= $this->Shapeups->moneda($total); ?>
													</b>
												</h3>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--BOTON CONTINUAR-->
			<div class="col-xs-12">
				<a href="<?= $this->Html->url(array('action' => 'inicio')); ?>" class="btn btn-primary btn-block">Volver al catalogo</a>
			</div>
		</div>
	</div>
</div>
