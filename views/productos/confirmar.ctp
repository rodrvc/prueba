<style type="text/css">
.paytipe {
	width: 250px;
	height: 75px;
	margin: 2px auto;	
	border-radius: 15px;
	padding: 0;
	
}
.paytipe img{
	width: 100%;
}
.pay_active a {
  display: block;
  width: 250px;
  height: 75px;
  background-image: url('../img/checked.png');
  background-position: center center;
  overflow: hidden;
  border-radius: 14px;
}
</style>
<div class="container">
	<div class="panel panel-default">
		<div class="panel-body nopadding-top">
			<div class="volver-top">
		  		<div class="row">
			  		<div class="col-md-12">
						<a href="<?= $this->Html->url(array('action' => 'inicio')); ?>" class="btn btn-info btn-xs" title="Seguir comprando"><span>Volver a catálogo</span></a>
			  		</div>
			  	</div>
		  	</div> 
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
						<div class="progress-bar relative progress-bar-danger" style="width: 20%">
							<span class="icon-skechers">
								<div class="icono">
									<img src="<?= $this->Html->url('/img/bootstraps/icon-skechers-wizard-disabled.png'); ?>" width="100%">
								</div>
								<div class="texto text-disabled">
									Pago
								</div>
							</span>
						</div>
						<div class="progress-bar relative progress-bar-danger" style="width: 20%">
							<span class="icon-skechers">
								<div class="icono">
									<img src="<?= $this->Html->url('/img/bootstraps/icon-skechers-wizard-disabled.png'); ?>" width="100%">
								</div>
								<div class="texto text-disabled">
									Recibo
								</div>
							</span>
						</div>
						<div class="progress-bar relative progress-bar-danger" style="width: 20%"></div>
					</div>
				</div>
			</div>
			<div class="page-header">
				<h1 class="text-center">Revisa que todo esté correcto</h1>
				<p class="text-center">Aún no haz finalizado la compra. Revisa que todos los datos sean correctos y luego haz click en "Finalizar Compra" para elegir tu medio de pago.</p>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div id="carro-mobile" class="hidden-md hidden-lg hidden-sm">
						<h4>
							Tu carro de compras:
						</h4>
						<? if ( isset($productos) && $productos ) : ?>
							<? foreach ( $productos as $index => $producto ) : ?>
								<div class="row">
									<div class="col-xs-4 text-center">
										<img src="<?= $this->Shapeups->imagen('Producto/'.$producto['Producto']['id'].'/full_'.$producto['Producto']['foto']); ?>" width="100%" />
										<a href="<?= $this->Html->url(array('action' => 'eliminar', $producto['Stock']['id'])); ?>" class="btn btn-danger btn-xs btn-block">
											Eliminar
										</a>
									</div>
									<div class="col-xs-8">
										<ul class="list-group small">
											<li class="list-group-item">
												<b><?= $producto['Producto']['nombre']; ?></b>
											</li>
											<li class="list-group-item">
												Talla: <?= $this->Shapeups->talla_ropa($producto['Stock']['talla']); ?>
											</li>
											<li class="list-group-item">
												Color: <?= $producto['Producto']['Color']['nombre']; ?>
											</li>
											<li class="list-group-item">
												Cantidad: <?= $producto['cantidad']; ?>
											</li>
											<li class="list-group-item">
												<?= $this->Shapeups->moneda($producto['Producto']['precio'] * $producto['cantidad']); ?>
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
										<span class="badge"><?= $this->Shapeups->moneda($valores_compra['subtotal']); ?></span>
									</li>
									<? if ($valores_compra['descuento']) : ?>
									<li class="list-group-item list-group-item-danger">
										<b>Descuento:</b>
										<span class="badge">- <?= $this->Shapeups->moneda($valores_compra['descuento']); ?></span>
									</li>
									<? endif; ?>
									<li class="list-group-item list-group-item-success">
										<b>Total:</b>
										<span class="badge"><?= $this->Shapeups->moneda($valores_compra['total']); ?></span>
									</li>
									<li class="list-group-item">
										Despacho a domicilio:
										<span class="badge">Sin costo</span>
									</li>
									<li class="list-group-item text-center">
										<?= $this->Html->link('Ver políticas de despacho', 'https://s3.amazonaws.com/andain-sckechers/politicas_skechers.pdf', array('class' => 'politicas btn btn-xs', 'target' => '_blank')); ?>
									</li>
								</ul>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
					
                                        <div class="alert alert-info noborderradius">
                                            <h4>Seleccione medio de pago </h4>
                                            <div class="paytipe" <?php if(!$mercadoPago){ echo 'style="display:none"';} ?>>
                                                <a href="/link1" id="mercadopago_movil">
                                                    <img src="<?= $this->Html->url('/img/mercado_pago.png'); ?>">
                                                </a>
                                            </div>

                                            <div class="paytipe">
                                                <a href="/link2" id="webpay_movil">
                                                    <img src="<?= $this->Html->url('/img/webpay.png'); ?>">
                                                </a>
                                            </div>
                                        </div>
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
									<td class="col-xs-1">Foto</td>
									<td class="text-left col-xs-4">Nombre</td>
									<td class="text-center col-xs-1">Color</td>
									<td class="text-center col-xs-1">Código</td>
									<td class="text-center col-xs-1">Talla</td>
									<td class="text-center col-xs-1">Cantidad</td>
									<td class="text-center col-xs-3">Valor</td>
								</tr>
								<? if ( isset($productos) && $productos ) : ?>
									<? foreach ( $productos as $index => $producto ) : ?>
										<tr>
											<td>
												<img src="<?= $this->Shapeups->imagen('Producto/'.$producto['Producto']['id'].'/mini_'.$producto['Producto']['foto']); ?>" width="100%" />
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
													<?= $this->Shapeups->talla_ropa($producto['Stock']['talla']); ?>
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
								<tr>
									<td colspan="7" class="warning">
										<h4>Datos de Despacho:</h4>
										<div class="col-md-7 col-xs-12">
											<b>
												<?= $despacho['Direccion']['calle'].' #'.$despacho['Direccion']['numero']; ?><?= (isset($despacho['Direccion']['depto']) && $despacho['Direccion']['depto']) ? ', depto '.$despacho['Direccion']['depto']:''; ?>
											</b>
											<?= (isset($despacho['Comuna']['nombre']) && $despacho['Comuna']['nombre']) ? ', '.$despacho['Comuna']['nombre']:''; ?>
											<?= (isset($despacho['Region']['nombre']) && $despacho['Region']['nombre']) ? '- '.$despacho['Region']['nombre']:''; ?>
											<?= (isset($despacho['Direccion']['telefono']) && $despacho['Direccion']['telefono']) ? '<br>Teléfono: '.$despacho['Direccion']['telefono']:''; ?>
											<?= (isset($despacho['Direccion']['celular']) && $despacho['Direccion']['celular']) ? '<br>Celular: '.$despacho['Direccion']['celular']:''; ?>
											<?= (isset($despacho['Direccion']['entrega']) && $despacho['Direccion']['entrega']) ? '<br>Entregar a: '.$despacho['Direccion']['entrega']:''; ?>
										</div>
										<div class="col-md-5 col-xs-12 small">
											<i>El correo de confirmación de la compra será enviado dentro de 24 horas.
												Tus SKECHERS serán despachadas a esta dirección, junto con la boleta de compra.
												En caso de necesitar información extras, nos comunicaremos a los teléfonos e email que has ingresado.</i>
										</div>
									</td>
								</tr>
								<tr class="info" id="mediopago">
									<td colspan="2" rowspan="6" class="text-center">
                                        <div>
                                            <h4>Seleccione medio de pago </h4>
                                            <div class="paytipe" <?php if(!$mercadoPago){ echo 'style="display:none"';} ?>>
                                                <a href="/link1" id="mercadopago">
                                                    <img src="<?= $this->Html->url('/img/mercado_pago.png'); ?>">
                                                </a>
                                            </div>

                                            <div class="paytipe">
                                                <a href="/link2" id="webpay">
                                                    <img src="<?= $this->Html->url('/img/webpay.png'); ?>">
                                                </a>
                                            </div>
                                        </div>
                                    </td>
								
								

									<td colspan="4" class="text-right">Subtotal:</td>
									<td class="text-center"><b class="text-info"><?= $this->Shapeups->moneda($valores_compra['subtotal']); ?></td>
								</tr>
								<tr class="info">
									<td colspan="4" class="text-right noborder">Despacho a domicilio <br> <?= $this->Html->link('Ver políticas de despacho', 'https://s3.amazonaws.com/andain-sckechers/politicas_skechers.pdf', array('class' => 'politicas btn btn-xs', 'target' => '_blank')); ?></td>
									<td class="text-center noborder"><?= $this->Shapeups->moneda($valores_compra['despacho']); ?></td>
								</tr>
								<? if (isset($valores_compra['descuento']) && $valores_compra['descuento']) : ?>
								<tr class="info">
									<td colspan="4" class="text-right noborder">Descuento</td>
									<td class="text-center noborder">- <?= $this->Shapeups->moneda($valores_compra['descuento']); ?></td>
								</tr>
								<? endif; ?>
								<tr class="info">
									<td colspan="4" class="text-right noborder">Precio Neto</td>
									<td class="text-center noborder"><?= $this->Shapeups->moneda($valores_compra['neto']); ?></td>
								</tr>
								<tr class="info">
									<td colspan="4" class="text-right noborder">IVA(19%):</td>
									<td class="text-center noborder"><?= $this->Shapeups->moneda($valores_compra['iva']); ?></td>
								</tr>
								<tr class="info">
									<td colspan="4" class="text-right divider-total">Total</td>
									<td class="text-center divider-total">
										<h3>
											<b class="text-info">
												<?= $this->Shapeups->moneda($valores_compra['total']); ?>
											</b>
										</h3>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
	  			</div>
	  		</div>
			<div class="row">
				<div class="col-md-12">
					<div class="alert alert-danger noborderradius">
						<div class="row">
							<div class="col-md-6 text-center">
								<div class="acepto-box">
									<input type="checkbox" name="acepto1" id="acepto1" class="in-chek acepto-declaro" />
									<a href="https://s3.amazonaws.com/andain-sckechers/politicas_skechers.pdf" class="politicas dos" target="_blank">Acepto condiciones de venta</a>
								</div>
							</div>
							<div class="col-md-6 text-center">
								<div class="acepto-box">
									<input type="checkbox" name="acepto2" id="acepto2" class="in-chek acepto-declaro" />
									<a href="https://s3.amazonaws.com/andain-sckechers/zonas_despacho.pdf" class="politicas dos" target="target">Declaro conocer áreas de despacho a domicilio</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
		  		<div class="col-md-6">
					<a href="<?= $this->Html->url(array('action' => 'inicio')); ?>" class="btn btn-info btn-block btn-lg" title="Seguir comprando">Volver a catálogo</a>
		  		</div>
		  		<div class="col-md-6">
					<a href="#" id="btnContinuar" class="btn btn-warning btn-block btn-lg disabled">Finalizar Compra</a>
		  		</div>
		  	</div>
		</div>
	</div>
</div>
<script>
if ($('input.acepto-declaro:checked').length >= 2) {
	$('#btnContinuar').removeClass('disabled');
}
<?php if($mercadoPago) : ?>
	var mediPagoElegido = null;
<?php else: ?>
	var mediPagoElegido = 'webpay';
<?php endif; ?>

$('input.acepto-declaro').change(function() {
	var aceptados = $('input.acepto-declaro:checked').length;
	if (aceptados < 2) {
		$('#btnContinuar').addClass('disabled');
		return false;
	}
	$('#btnContinuar').removeClass('disabled');
});
$(document).on('click','#btnContinuar',function(e) {
	e.preventDefault();
	var aceptados = $('input.acepto-declaro:checked').length;
	if (aceptados < 2) {
		return false;
	}
	if (mediPagoElegido == null) {
		alert('Debe seleccionar medio de Pago')
		return false;
	}
	location.href="<?= $this->Html->url(array('action' => 'pago')); ?>/"+mediPagoElegido;
})

$(document).on('click','#mercadopago',function(e) {
	mediPagoElegido = 'mercadopago';
	console.log(mediPagoElegido);
	
})
$(document).on('click','#webpay',function(e) {
	mediPagoElegido = 'webpay';
		console.log(mediPagoElegido);

	
})
$(document).on('click','#mercadopago_movil',function(e) {
	mediPagoElegido = 'mercadopago';
	console.log(mediPagoElegido);
	
})
$(document).on('click','#webpay_movil',function(e) {
	mediPagoElegido = 'webpay';
		console.log(mediPagoElegido);

	
})
</script>
<script>
$(document).ready(function(){

		dataLayer.push({

	 'event': 'checkoutOption',
	 'ecommerce': {
		 'checkout_option': {
		 	'actionField': {'step': 3}
		 }
	 }
});

});
</script>
<!-- mercado pago-->
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('.paytipe').click(function(event){
            jQuery('.pay_active').removeClass('pay_active');
            jQuery(this).addClass('pay_active');
            event.preventDefault();
        });
    });
    <!-- mercado pago-->
</script>
