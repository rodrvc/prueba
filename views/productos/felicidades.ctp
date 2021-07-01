<style type="text/css" media="print">
/* <![CDATA[ */
body > .container, body .row .footer, .progress.barra, #documentoCompra img, a.btn {
	display: none;
}
/* ]]> */
</style>
	<script src="https://cdn1.mingadigital.com/px/2322_ldr.js" async></script>

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
			<div id="imprimirDocumento">
				<div class="page-header">
					<h1 class="text-center"><b>Estimado <?= $nombre; ?>.</b> <br>Tu compra ha finalizado con éxito</h1>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div id="documentoCompra" class="panel panel-info">
							<div class="panel-heading">
								<h3 class="panel-title">Información de tu compra</h3>
							</div>
							<div class="panel-body">
								<div class="col-md-6">
									<table class="table">
										<thead>
											<tr>
												<th colspan="2">Despacho</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Dirección:</td>
												<td><?= $despacho['Direccion']['calle']; ?></td>
											</tr>
											<tr>
												<td>Numero:</td>
												<td><?= $despacho['Direccion']['numero']; ?></td>
											</tr>
											<? if (isset($despacho['Direccion']['depto']) && $despacho['Direccion']['depto']) : ?>
											<tr>
												<td>Depto:</td>
												<td><?= $despacho['Direccion']['depto']; ?></td>
											</tr>
											<? endif; ?>
											<tr>
												<td>Comuna:</td>
												<td><?= $despacho['Direccion']['Comuna']['nombre']; ?></td>
											</tr>
											<tr>
												<td>Región:</td>
												<td><?= $despacho['Direccion']['Comuna']['Region']['nombre']; ?></td>
											</tr>
											<tr>
												<td>Teléfono:</td>
												<td><?= $despacho['Direccion']['telefono']; ?></td>
											</tr>
											<tr>
												<td>Celular:</td>
												<td><?= $despacho['Direccion']['celular']; ?></td>
											</tr>
											<tr>
												<td colspan="2" class="info text-center">
													<small><b><i>El correo de confirmación de la compra será enviado dentro de 24 hrs.</i></b></small><br />Tus Skechers serán despachadas a esta dirección. <br>Tu compra será despachada a esta dirección y junto con ella la boleta impresa <br>Los teléfonos indicados serán utilizados en caso que suceda algún contratiempo.
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="col-md-6">
									<div class="col-md-8 col-md-offset-2">
										<img src="<?= $this->Html->url('/img/bootstraps/pagado.png'); ?>" width="100%">
									</div>
									<table class="table">
										<thead>
											<tr>
												<th colspan="2">Pago</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Código de compra:</td>
												<td><b>#<?= $pago['Pago']['compra_id']; ?></b></td>
											</tr>
											<tr>
												<td>Tarjeta de pago:</td>
												<td><b>XXXX - XXXX - XXXX - <?= $pago['Pago']['numeroTarjeta']; ?></b></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
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
												Talla: <?= $producto['Stock']['talla']; ?>
											</li>
											<li class="list-group-item">
												Color: <?= $producto['Producto']['Color']['nombre']; ?>
											</li>
											<li class="list-group-item">
												Cantidad: <?= $producto['cantidad']; ?>
											</li>
											<li class="list-group-item">
												<?
												if ( $producto['Producto']['oferta'] )
													echo $this->Shapeups->moneda($producto['Producto']['precio'] * $producto['cantidad']);
												else
													echo $this->Shapeups->moneda($producto['Producto']['precio'] * $producto['cantidad']);
												?>
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
								<tr class="info">
									<td colspan="2" rowspan="6" class="text-center">
										<img src="<?= $this->Html->url('/img/bootstraps/webpay-plus.png'); ?>" width="70%">
									</td>
									<td colspan="4" class="text-right">Subtotal</td>
									<td class="text-center"><b class="text-info"><?= $this->Shapeups->moneda($subtotal); ?></b></td>
								</tr>
								<tr class="info">
									<td colspan="4" class="text-right noborder">Despacho a domicilio <br> <?= $this->Html->link('Ver políticas de despacho', 'http://www.skechers.cl/politicas_skechers.pdf', array('class' => 'politicas btn btn-xs', 'target' => '_blank')); ?></td>
									<td class="text-center noborder"><?= $this->Shapeups->moneda($despacho_val); ?></td>
								</tr>
								<? if (isset($descuento) && $descuento) : ?>
								<tr class="info">
									<td colspan="4" class="text-right noborder">Descuento</td>
									<td class="text-center noborder">- <?= $this->Shapeups->moneda($descuento); ?></td>
								</tr>
								<? endif; ?>
								<tr class="info">
									<td colspan="4" class="text-right noborder">Precio Neto:</td>
									<td class="text-center noborder"><?= $this->Shapeups->moneda($neto); ?></td>
								</tr>
								<tr class="info">
									<td colspan="4" class="text-right noborder">IVA(19%):</td>
									<td class="text-center noborder"><?= $this->Shapeups->moneda($iva); ?></td>
								</tr>
								<tr class="info">
									<td colspan="4" class="text-right divider-total">Total <b>Pagado</b></td>
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
			<div class="col-md-6 hidden-xs hidden-sm">
				<a href="#" class="btn btn-warning btn-block" rel="imprimir">Imprimir</a>
			</div>
			<!--BOTON CONTINUAR-->
			<div class="col-md-6">
				<a href="<?= $this->Html->url(array('action' => 'inicio')); ?>" class="btn btn-primary btn-block">Ir al catálogo</a>
			</div>
		</div>
	</div>
	<script>
	$(document).ready(function() {
		$(document).on('click','a[rel="imprimir"]',function(e) {
			e.preventDefault();
			var target = $('#imprimirDocumento');
			if (! target.length) {
				return false;
			}
			window.print();
		});

	


dataLayer.push({
  'ecommerce': {
    'purchase': {
      'actionField': {
        'id': '<?= $pago["Pago"]["compra_id"]; ?>',                        
        'affiliation': 'Skechers Online',
        'revenue': '<?=$total;?>',                     
        'tax':0,
        'shipping': 0
      },
      'products': [
		<?	$i = 0;
		 foreach ( $productos as $index => $producto ) : ?>
	 		<? if ($i++ > 0) 
	      	 echo ','; ?>
     	 {                           
        'name': "<?= $producto['Producto']['nombre']; ?>",     
        'id': '<?= $producto["Producto"]["codigo_completo"]; ?>',
        'price': '<?= ($producto["Producto"]["oferta"]) ? $producto["Producto"]["precio_oferta"]:$producto["Producto"]["precio"]; ?>',
        'brand': 'Skechers',
        'category': '<?=$producto["Producto"]["Categoria"]["nombre"];?>',
        'variant': '<?=$producto["Producto"]["Color"]["nombre"];?>',
        'quantity': <?= $producto["cantidad"]; ?>

        <? if($producto["Producto"]["oferta"]) : ?>
        , 'coupon' : 'OFERTA'

        <? endif; ?>
    	}

       

	<? endforeach; ?>
       ]
    }
  }
});
});
</script>
<script language="JavaScript1.1" async src="//pixel.mathtag.com/event/js?mt_id=1444400&mt_adid=229641&mt_exem=&mt_excl=&v1=&v2=&v3=&s1=&s2=&s3"></script>;

</div>
