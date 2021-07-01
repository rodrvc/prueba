<div class="container">
	<div class="panel panel-default">
		  <div class="panel-body nopadding-top">
		  	<div class="volver-top">
		  		<div class="row">
			  		<div class="col-md-12">
			  			<?= $this->Html->link('<span>Volver a catálogo</span>', array('controller' => 'productos', 'action' => 'inicio'), array('title' => 'Seguir comprando', 'class' => 'btn btn-info btn-xs', 'escape' => false)); ?>
			  		</div>
			  	</div>
		  	</div>

			<? if (isset($texto_aviso) && $texto_aviso) : ?>
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-warning">
						    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						    <strong><?= $texto_aviso; ?></strong>
						</div>
					</div>
				</div>
			<? endif; ?>
		  	<div class="row">
		  		<div class="col-md-8">
					<h3>Estimado <b><?= ( isset($authUser) )? $authUser['nombre'].' '.$authUser['apellido_paterno'].' '.$authUser['apellido_materno']:'Sr(a)'; ?></b>.</h3>
					<p>
						La información que nos proporcionarás es privada y no será utilizada sin tu consentimiento.
					</p>
		  		</div>
		  		<div class="col-md-4 hidden-xs">
		  			<a href="<?= $this->Html->url(array('action' => 'inicio')); ?>" class="btn btn-info btn-block" style="margin-top:50px;">
		  				Agregar otro producto al carro
		  				<i class="fa fa-plus"></i>
		  			</a>
		  		</div>
		  	</div>
		  	<div class="row" id="ProductosCarro">
		  		<div class="col-md-12">
		  			<!-- DETALLE MOBILE-->
					<div id="carro-mobile" class="hidden-md hidden-lg hidden-sm">
						<h4>
							Tu carro de compras:
						</h4>
						<? if ( isset($productos) && $productos ) : ?>
							<? foreach ( $productos as $index => $producto ) : ?>
								<div class="row" rel="carro-<?= $index; ?>">
									<div class="col-xs-4 text-center">
										<img src="<?= $this->Shapeups->imagen('Producto/'.$producto['Producto']['id'].'/full_'.$producto['Producto']['foto']); ?>" width="100%" />
										<?
										$excluir_descuento = false;
										if (in_array($producto['Producto']['id'], array(4415,4416)))// productos promo
											$excluir_descuento = true;
										elseif ($producto['Producto']['excluir_descuento'])// productos excluidos de descuento
											$excluir_descuento = false;// siempre muestra el boton de descuento, se valida en el ajax
										
										if ( isset($producto['Producto']['dosporuno']) && $producto['Producto']['dosporuno'] )
										{
											$excluir_descuento = true;
											echo '<button type="button" class="btn btn-warning btn-xs disabled hidden">Promoción 2x1</button>';
										}



									if (! $excluir_descuento)
										{
											if(!array_key_exists ($index,$descuentos))
												echo '
												<a href="#" class="btn btn-info  btn-xs btn-block" rel="activarDescuento" data-cantidad="'.$producto['cantidad'].'" data-id="'.$index.'">

													<small>Descuento</small>
												</a>';
										}
										?>
										<a href="<?= $this->Html->url(array('action' => 'eliminar', $producto['Stock']['id'])); ?>" class="btn btn-danger btn-xs btn-block" rel="eliminarProducto" data-sku="<?=$producto['Stock']['sku'];?>" data-color="<?=$producto['Producto']['Color']['nombre'];?>" data-precio ="<?=$producto['Producto']['precio'];?>" data-nombre="<?=$producto['Producto']['nombre'];?>" data-categoria="<?= $producto['Producto']['Categoria']['nombre']; ?>" data-cantidad="<?= $producto['cantidad']; ?>">
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
												<? if ( $producto['Producto']['oferta'] ) : ?>
														<strike class="text-info">	<?= $this->Shapeups->moneda($producto['Producto']['precio'] * $producto['cantidad']); ?><br></strike>

														Oferta<b class="text-danger">
															<?= $this->Shapeups->moneda($producto['Producto']['precio_oferta'] * $producto['cantidad']); ?>
															</b>
													<? else: ?>
																<b class="text-info"><?= $this->Shapeups->moneda($producto['Producto']['precio'] * $producto['cantidad']); ?></b>
												<? endif; ?>
											</li>
											<? if(array_key_exists ($index,$descuentos))
														echo '<li class="list-group-item list-group-item-danger">+ Descuento Aplicado</li>';
												else if(isset($dosporuno) && in_array($index,$dosporuno))
														echo '<li class="list-group-item list-group-item-danger">+ Promocion Aplicada</li>';		


											?>
										</ul>
									</div>
								</div>
								<hr>
							<? endforeach; ?>
						<? endif; ?>
						<div class="row">
							<div class="col-xs-12">
								<ul class="list-group" rel="compraTotales">
									<li class="list-group-item list-group-item-info" rel="compraSubtotal">
										Subtotal:
										<span class="badge"><?= $this->Shapeups->moneda($valores_compra['subtotal']); ?></span>
									</li>
									<li class="list-group-item list-group-item-success" rel="compraTotal"  <?= (! $valores_compra['descuento']) ? 'style="display: none;"' : ''; ?>>
										<b>Descuento:</b>
										<span class="badge"><?= $this->Shapeups->moneda($valores_compra['descuento']); ?></span>
									</li>
									<li class="list-group-item list-group-item-success" rel="compraTotal">
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
					</div>
		  			<!-- FIN DETALLE MOBILE -->
					<!--DETALLE DESKTOP-->
		  			<div id="carro-desktop" class="table-responsive hidden-xs">
						<table class="table table-skechers table-condensed">
							<thead>
								<tr>
									<th colspan="8">
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
									<td class="text-center col-md-1"></td>
									<td class="text-center col-md-1">Color</td>
									<td class="text-center col-md-1">Código</td>
									<td class="text-center col-md-1">Talla</td>
									<td class="text-center col-md-1">Cantidad</td>
									<td class="text-center col-md-2">Valor</td>
								</tr>
								<? if ( isset($productos) && $productos ) : ?>
									<? foreach ( $productos as $index => $producto ) : ?>
										<tr rel="carro-<?= $index; ?>">
											<td>
												<a href="<?= $this->Html->url(array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug']));?>" target="_blank"><img src="<?= $this->Shapeups->imagen('Producto/'.$producto['Producto']['id'].'/full_'.$producto['Producto']['foto']); ?>" width="100%" /></a>
											</td>
											<td class="text-left">
												<h4 class="nomargin text-info">
													<b><?= $producto['Producto']['nombre']; ?><? echo (false && !$producto['Producto']['dosporuno'] || $producto['Producto']['outlet'])?' <h6>* Producto no incluido en la promocion</h6>':'';?></b>
												</h4>
												<?
												$excluir_descuento = false;
												if (in_array($producto['Producto']['id'], array(4415,4416)))// productos promo
													$excluir_descuento = true;
												elseif ($producto['Producto']['excluir_descuento'])// productos excluidos de descuento
													$excluir_descuento = false;// siempre muestra el boton de descuento, se valida en el ajax

												if ( isset($producto['Producto']['dosporuno']) && $producto['Producto']['dosporuno'] )
												{
													$excluir_descuento = true;
													echo '<button type="button" class="btn btn-warning btn-xs disabled hidden">Promoción 2x1</button>';
												}

												if (! $excluir_descuento)
												{
													if(array_key_exists ($index,$descuentos))
														echo '<div class="descuento-rosa"><ul class="list-group detalle-descuento"><li class="list-group-item">+ Descuento Aplicado</li></ul></div>';
													else
														echo '<div class="descuento-rosa"><div class="boton dos"><a href="#" class="descuento text-blanco btn btn-xs" rel="activarDescuento" data-cantidad="'.$producto['cantidad'].'" data-id="'.$index.'"><span>Agregar descuento</span></a></div>';
													//BOTON DESCUENTO /OCULTO
													/*echo '
													<div class="boton-desc desc-activado-'.$index.'" style="display: none;">
														<span class="ingrese text-blanco">Ingresa el código:</span>
														<input id="cod-desc-'.$index.'" name="codigo-des" type="text" class="in-desc" style="color: #ccc; max-width: 150px;" />
														<a href="#" rel="validar-descuento" class="bt-ir boton-ir-'.$index.' btn btn-info btn-xs btn-ir" data-id="'.$index.'"><i class="fa fa-arrow-right"></i></a>
													</div>
													<div id="alerta-invalido" class="aviso-invalidcod aviso-'.$index.'" style="display:none;">
														<b>Revisa tu código</b> e ingresa todos sus caracteres<br />EJ: SKX-XXXX-18722
													</div></div>';*/
												}
												if(isset($dosporuno) && in_array($index,$dosporuno))
														echo '<li class="list-group-item list-group-item-danger">+ Promocion Aplicada</li>';

												?>
												</div>
											</td>
											<td class="text-center">
												<a href="<?= $this->Html->url(array('action' => 'eliminar', $producto['Stock']['id'])); ?>" class="btn btn-danger btn-xs" rel="eliminarProducto" data-sku="<?=$producto['Stock']['sku'];?>" data-color="<?=$producto['Producto']['Color']['nombre'];?>" data-precio ="<?=$producto['Producto']['precio'];?>" data-nombre="<?=$producto['Producto']['nombre'];?>" data-categoria="<?= $producto['Producto']['Categoria']['nombre']; ?>" data-cantidad="<?= $producto['cantidad']; ?>">
													Eliminar
												</a>
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
													<?
													if(array_key_exists ($index,$descuentos)):
													?>
													<span class="text-danger"> <del>Oferta: <?= $this->Shapeups->moneda($producto['Producto']['precio_oferta'] * $producto['cantidad']); ?></del></span>
													</p>
												<? else : ?>
												<span class="text-danger"> Oferta: <?= $this->Shapeups->moneda($producto['Producto']['precio_oferta'] * $producto['cantidad']); ?></span>
												<?php endif; ?>
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
									<td colspan="3" rowspan="4" class="text-center">
										<img src="<?= $this->Html->url('/img/bootstraps/webpay-plus.png'); ?>" width="70%">
									</td>
									<td colspan="4" class="text-right">Subtotal</td>
									<td class="text-center" rel="totalSubtotal"><b class="text-info"><?= $this->Shapeups->moneda($valores_compra['subtotal']); ?></b></td>
								</tr>
								<tr class="info">
									<td colspan="4" class="text-right noborder">Despacho a domicilio <br> <?= $this->Html->link('Ver políticas de despacho', 'https://s3.amazonaws.com/andain-sckechers/politicas_skechers.pdf', array('class' => 'politicas btn btn-xs', 'target' => '_blank')); ?></td>
									<td class="text-center noborder" rel="totalDespacho"><small>$ 0</small></td>
								</tr>
								<tr class="info" <?= (! $valores_compra['descuento']) ? 'style="display: none;"' : ''; ?>>
									<td colspan="4" class="text-right noborder">Descuento</td>
									<td class="text-center noborder" rel="totalDescuento"><b class="text-danger"> - <?= $this->Shapeups->moneda($valores_compra['descuento']); ?></b></td>
								</tr>
								<tr class="info">
									<td colspan="4" class="text-right divider-total">Total</td>
									<td class="text-center divider-total" rel="totalTotal">
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
				<!--FIN DETALLE DESKTOP-->
				</div>
			</div>
			<div class="row continuar-footer">
				<div class="col-md-6 col-md-offset-3">
					<?= $this->Html->link('<span>Continuar</span>', array('controller' => 'productos', 'action' => 'despacho'), array('escape' => false, 'class' => 'btn btn-warning btn-block btn-lg')); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalDescuento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Ingresa tu descuento</h4>
			</div>
			<div class="modal-body">
				<div class="alert alert-danger" style="display: none;"></div>
				<?= $this->Form->create('Producto', array('class' => 'form-horizontal')); ?>
					<?= $this->Form->hidden('id'); ?>
					<?= $this->Form->hidden('cantidad'); ?>
					<div class="form-group">
						<div class="col-md-3 hidden-xs hidden-sm">
							Código: 
						</div>
						<div class="col-md-9">
							<div class="input-group">
								<?
								$options = array(
									'type' => 'text',
									'div' => false,
									'label' => false,
									'class' => 'form-control',
									'placeholder' => '- Código',
									'autocomplete' => 'off'
								);
								echo $this->Form->input('codigo', $options);
								?>
								<span class="input-group-btn">
									<button class="btn btn-info" type="button" rel="validar-descuento">
										<i class="fa fa-arrow-right"></i>
									</button>
								</span>
							</div>
						</div>
					</div>
				<?= $this->Form->end(); ?>
			</div>
			<div class="modal-footer"></div>
		</div>
	</div>
</div>
<script>
$('#carro-desktop a[rel="activarDescuento"], #carro-mobile a[rel="activarDescuento"]').click(function(e) {
	e.preventDefault();
	var boton = $(this),
		id = boton.data('id');
		cantidad = boton.data('cantidad');
	if (! id)
		return false;
	if (! $('#modalDescuento').length)
		return false;
	var formulario = $('#modalDescuento form');
	if (! formulario.length)
		return false;
	formulario[0].reset();
	formulario.find('#ProductoId').val(id);
	formulario.find('#ProductoCantidad').val(cantidad);
	$('#modalDescuento .alert').hide();
	$('#modalDescuento').modal('show');
});

$('#modalDescuento input#ProductoCodigo').keydown(function(e) {
	if (e.keyCode === 13)
		return false;
});
$(document).on('click','#modalDescuento button[rel="validar-descuento"]',function() {
	var boton = $(this),
		formulario = boton.parents('form'),
		contenedor = boton.parents('#modalDescuento');
	if (! contenedor.length)
		return false;
	contenedor.find('.alert').slideUp(300);
	if (! formulario.length)
		return false;
	var id = formulario.find('#ProductoId').val();
	if (! id)
		return false;
	var codigo = formulario.find('#ProductoCodigo').val();
	if (! codigo)
		return false;
	var cantidad = formulario.find('#ProductoCantidad').val();
	if (! cantidad)
		return false;

	$.ajax({
		async : true,
		type : 'POST',
		dataType: "json",
		url : webroot + 'productos/ajax_descuento',
		data: { id : id, codigo : codigo, cantidad : cantidad },
		success: function(respuesta) {
			var mensaje = 'Lo sentimos, intentelo nuevamente.';
			if (respuesta && respuesta.estado) {
				if (respuesta.estado == 'DESCUENTO_OK') {
					// target desktop
					var target = $('#carro-desktop tr[rel="carro-'+id+'"]');
					if (target.length) {
						target.find('a[rel="activarDescuento"]').parent().remove();
						target.find('.descuento-rosa').html('<ul class="list-group detalle-descuento"><li class="list-group-item">+ Dcto '+respuesta.mensaje+' - Sobre Precio Normal</li></ul>');
					}
					target = $('#carro-desktop table');
					if (target.length) {
						target.find('td[rel="totalSubtotal"]').html('<b class="text-info">$'+formatMoneda(respuesta.total.subtotal)+'</b>');
						target.find('td[rel="totalDescuento"]').html('<b class="text-danger">- $'+formatMoneda(respuesta.total.descuento)+'</b>').parent().show();
						target.find('td[rel="totalTotal"]').html('<h3><b class="text-info">$'+formatMoneda(respuesta.total.total)+'</b></h3>');
					}
					// target mobile test
					target = $('#carro-mobile div[rel="carro-'+id+'"]');
					if (target.length) {
						target.find('a[rel="activarDescuento"]').remove();
						target.find('ul.list-group.small').append('<li class="list-group-item list-group-item-danger">+ Dcto '+respuesta.mensaje+' - Sobre Precio Normal</li>');
					}
					target = $('#carro-mobile ul[rel="compraTotales"]');
					if (target.length) {
						var compraTotales = '<li class="list-group-item list-group-item-info" rel="compraSubtotal">Subtotal: <span class="badge">$'+formatMoneda(respuesta.total.subtotal)+'</span></li>'
						compraTotales+='<li class="list-group-item list-group-item-danger" rel="compraTotal">Descuento:<span class="badge">- $'+formatMoneda(respuesta.total.descuento)+'</span></li>';
						compraTotales+='<li class="list-group-item list-group-item-success" rel="compraTotal"><b>Total:</b><span class="badge">$'+formatMoneda(respuesta.total.total)+'</span></li>';
						compraTotales+='<li class="list-group-item">Despacho a domicilio: <span class="badge">Sin costo</span></li>';
						compraTotales+='<li class="list-group-item text-center"><a href="http://www.skechers.cl/politicas_skechers.pdf" class="politicas btn btn-xs" target="_blank">Ver políticas de despacho</a></li>';
						target.html(compraTotales);
					}
					$('#modalDescuento').modal('hide');
				} else if (respuesta.estado == 'ERROR') {
					if (respuesta.mensaje) {
						mensaje = respuesta.mensaje;
					}
					contenedor.find('.alert').html(mensaje).slideDown(300);
				}
			}
		}
	});
});
<? if (false) : ?>
/*
$('#carro-desktop a.descuento').click(function(e) {
	e.preventDefault();
	var boton = $(this),
		id = $(this).data('id');
	boton.fadeOut(200, function() {
		boton.parent().siblings('.desc-activado-'+id).fadeIn(200);
	});
});
*/
/*
$(document).on('click','#carro-desktop a[rel="validar-descuento"]', function(e) {
	e.preventDefault();
	var boton = $(this),
		id = boton.data('id');

	if (! id)
		return false;
	// verificar contenedor
	if (! boton.parents('.descuento-rosa').length)
		return false;
	var contenedor = boton.parents('.descuento-rosa');
	// verificar input
	if (! boton.siblings('input#cod-desc-'+id).length)
		return false;
	var inputText = boton.siblings('input#cod-desc-'+id),
		codigo = inputText.val();
	if (! codigo)
		return false;

	var botonAgregar = contenedor.find('.boton.dos'),
		cuadro_alerta = contenedor.find('#alerta-invalido');

	if ($(this).hasClass('mal'))
		return false;
	if ($(this).hasClass('bien'))
		return false;

	if (cuadro_alerta.length)
		cuadro_alerta.hide();

	if (inputText.length)
		inputText.attr('readonly','readonly');
	$.ajax({
		async : false,
		type : 'POST',
		dataType: "json",
		url : webroot + 'productos/ajax_descuento',
		data: { id : id, codigo : codigo },
		success: function(respuesta) {
			if ( respuesta.estado == 'DESCUENTO_OK' ) {
				boton.addClass('bien');
				contenedor.fadeOut(300, function() {
					$.ajax({
						async : false,
						dataType: "json",
						type : 'POST',
						url : webroot + 'productos/ajax_actualizar_producto_descuento',
						data : { id : id },
						success: function( actualizacion ) {
							if (actualizacion) {
								contenedor.html(actualizacion.producto);
								$('#carro-desktop [rel="totalSubtotal"]').html('$ '+formatMoneda(actualizacion.total.subtotal));
								$('#carro-desktop [rel="totalDespacho"]').html('$ '+formatMoneda(actualizacion.total.despacho));
								$('#carro-desktop [rel="totalDescuento"]').html('-$ '+formatMoneda(actualizacion.total.descuento));
								$('#carro-desktop [rel="totalTotal"]').html('$ '+formatMoneda(actualizacion.total.total));
							}
						},
						complete : function() {
							if (contenedor.length)
								contenedor.show();

							if (inputText.length)
								inputText.removeAttr('readonly');
						}
					});
				});
			}
			else {
				boton.addClass('mal');
				cuadro_alerta.html(respuesta).fadeIn(500, function() {
					setTimeout(function() {
						boton.removeClass('mal');
						cuadro_alerta.fadeOut(500, function() {
							if (inputText.length)
								inputText.removeAttr('readonly');
						});
					}, 4500);
				});
			}
		}
	});
	return false;
});
*/
<? endif; ?>
$(document).ready(function(){



		dataLayer.push({

	 'event': 'checkoutOption',
	 'ecommerce': {
		 'checkout_option': {
		 	'actionField': {'step': 1}
		 }
	 }
	});

});

</script>

<script language="JavaScript1.1" async src="//pixel.mathtag.com/event/js?mt_id=1438755&mt_adid=229641&mt_exem=&mt_excl=&v1=&v2=&v3=&s1=&s2=&s3"></script>;

