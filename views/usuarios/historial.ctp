<?= $this->element('menu'); ?>
<style>
.btn-devolucion {
	background-position: left -499px !important;
	font-weight: normal;
	height: 20px !important;
	width: 110px;
	margin-top: 10px;
	border-radius: 5px;
	padding-top: 5px; 
}
.btn-devolucion:hover {
	background-position: left -528px !important;
	text-decoration: underline !important;
}
</style>
<div class="wrapper">
	<div class="cont-carro">
		<div class="bg-top-contenedor">
			<div class="boton dos margen"><a href="#" title="Seguir comprando" class="celeste"><span>Volver a catálogo</span></a></div>
		</div>	
		<div class="registro">
			<?= $this->element('info_confi'); ?>
			<!--perfil-->
			<div class="perfil-us">
				<?= $this->element('botones_perfil'); ?>
				<!--HISTORIAL CARRO-->
				<div class="cont-historial">
					<div class="h-carro">
						<h3 class="titulo-perfil">Lista de compras realizadas</h3>
						<ul class="h-datos">
							<? foreach( $compras as $index => $compra ) : ?>
							<li>
								<table width="100%" class="h-item">
									<tr>
										<td>
											<p class="h-txto">Compra Número (Nº):</p>
											<p class="h-txto">Fecha :</p>
											<p class="h-txto">Estado :</p>
											<p class="h-txto">Boleta Número (Nº):</p>
											<p class="h-txto">Precio TOTAL :</p>
										</td>
										<td>
											<p class="h-txto normal-txt"><?= $compra['Compra']['id']; ?></p>
											<p class="h-txto normal-txt"><?= date('d-m-Y', strtotime($compra['Compra']['created'])); ?></p>
											<?
												// INICIA SWITCH ACTIVAR CAMBIO (desactivado)
												$activar_cambio = false;
												$dias_caducidad = 95; // 95 dias
												$caducidad = date('Y-m-d H:i:s', strtotime($compra['Compra']['created']) + (60 * 60 * 24 * $dias_caducidad));
											?>
											<? if ( isset($compra['Compra']['estado']) && $compra['Compra']['estado'] == 1 ) : ?>
												<? if ( isset($compra['Compra']['despachado']) && $compra['Compra']['despachado'] == 1 ) : ?>
													<? if ( isset($compra['Compra']['enviado']) && $compra['Compra']['enviado'] == 1 ) : ?>
													<p class="h-txto celeste" style="color: #0044CC;">Entregada</p>
													<? else : ?>
													<p class="h-txto celeste">Despachada</p>
													<? endif; ?>
													<?
														/** VERIFICACION DE CADUCIDAD DE CAMBIO
														 * si la compra fue despachada,
														 * si la compra fue o no enviada,
														 * si la compra tiene menos 95 dias de realizada
														 * ESTA ACTIVO EL CAMBIO
														 */
														
														if (date('Y-m-d H:i:s') < $caducidad)
														{
															if (false && in_array($authUser['id'], array(1,2,10,13,14,16)))
															{
																$activar_cambio = true;
															}
														}
													?>
												<? elseif ( $compra['Compra']['created'] >= $tres_dias ) : ?>
												<p class="h-txto verde">En Proceso</p>
												<? else : ?>
												<p class="h-txto gris">En Revision</p>
												<? endif; ?>
											<? elseif ( isset($compra['Compra']['estado']) && $compra['Compra']['estado'] == 2 ) : ?>
												<p class="h-txto gris" style="color: #FF0000;">Anulada</p>
											<? elseif ( isset($compra['Compra']['estado']) && $compra['Compra']['estado'] == 3 ) : ?>
												<p class="h-txto gris" style="color: #FF6600;">En Devolución</p>
												<?
													/** VERIFICACION DE CADUCIDAD DE CAMBIO
													 * si la compra fue despachada,
													 * si la compra fue o no enviada,
													 * si la compra tiene menos 95 dias de realizada
													 * ESTA ACTIVO EL CAMBIO
													 */
													
													if (date('Y-m-d H:i:s') < $caducidad)
													{
														if (in_array($authUser['id'], array(1,2,10,13,14,16)))
														{
															$activar_cambio = true;
														}
													}
												?>
											<? elseif ( isset($compra['Compra']['estado']) && $compra['Compra']['estado'] == 4 ) : ?>
												<p class="h-txto gris" style="color: #FF6600;">Devuelto</p>
											<? else : ?>
												<p class="h-txto gris" style="color: #FF0000;">No pagada</p>
											<? endif; ?>

											<p class="h-txto normal-txt"><?= ( isset($compra['Compra']['boleta']) && $compra['Compra']['boleta'] ) ? $compra['Compra']['boleta'] : 'XXX.XXX.XXX'; ?></p>
											<p class="h-txto normal-txt"><?= $this->Shapeups->moneda($compra['Compra']['total']); ?></p>
										</td>
										<td>
											<!-- Nº SEGUIMIENTO -->
											<p class="h-txto">Nº seguimiento: <?= ( isset($compra['Compra']['cod_despacho']) && $compra['Compra']['cod_despacho'] ) ? $compra['Compra']['cod_despacho'] : 'XXXXXXXXXX'; ?></p><br />
											<!-- Nº TRANSACCION -->
											<p class="h-txto">Nº transacción: <?= ( isset($compra['Pago'][0]['codigo']) && $compra['Pago'][0]['codigo'] ) ? $compra['Pago'][0]['codigo'] : 'xx-xxx-xxx-x'; ?></p>
											<!-- Nº TARJETA -->
											<p class="h-txto">Nº tarjeta pago: xx-xxx-<?= ( isset($compra['Pago'][0]['numeroTarjeta']) && $compra['Pago'][0]['numeroTarjeta'] ) ? $compra['Pago'][0]['numeroTarjeta'] : 'xxx'; ?></p>
											<p class="h-txto">Cantidad Producto: <?= count($compra['Producto']); ?></p>
										</td>
										<td>
											<div class="boton dos medio">
												<a href="#" class="azul ver-detalle" data-id="<?= $compra['Compra']['id']; ?>">
													<span style="width: 90px; padding-right: 20px;">Ver detalles</span>
												</a>
												<? if ($activar_cambio) : ?>
												<a href="<?= $this->Html->url(array('controller' => 'compras', 'action' => 'devolucion', $compra['Compra']['id'])); ?>" class="btn-devolucion">Cambio</a>
												<? endif; ?>
											</div>
										</td>
									</tr>
								</table>
								<!--Tabla detalles (oculta)-->
								<div class="tabla-detal tab-detal<?= $compra['Compra']['id']; ?>" style="display: none;">
								<table width="100%" class="h-detalles">
									<tr class="d-total">
										<td class="d-datos" colspan="5">
											<h3 class="d-titu">Datos de Compra</h3>
										</td>
									</tr>
									<? foreach( $compra['Producto'] as $producto ) : ?>
									<tr class="d-total">
										<td class="d-datos">
											<?
												$imagen = $this->Html->url('/img/sin-zapatilla.jpg');
												if (isset($producto['foto']) && $producto['foto'])
												{
													$imagen = $this->Html->url('/img/Producto/'.$producto['id'].'/mini_'.$producto['foto']);
													if (! $serv_produccion)
													{
														$imagen = $dir_produccion.'img/Producto/'.$producto['id'].'/mini_'.$producto['foto'];
													}
												}
											?>
											<img src="<?= $imagen; ?>" width="75" height="63" />
										</td>
										<td class="d-datos">
										<p class="titu-negrita"><?= $producto['nombre']; ?></p>
										<p class="titu-negrita normal">Color: <?= $producto['Color']['nombre']; ?></p>
										<p class="titu-negrita normal">COD: <?= $producto['codigo']; ?> <?= $producto['Color']['codigo']; ?></p>
										</td>
										<td class="d-datos">
										<p class="txt-medio">Talla: <?= $producto['ProductosCompra']['talla']; ?></p>
										</td>
										<td class="d-datos">
										<p class="txt-medio"><!--Cantidad: X-->&nbsp;</p>
										</td>
										<td class="d-datos">
											<p class="txt-medio negrito">
												<?= $this->Shapeups->moneda($producto['ProductosCompra']['valor']); ?>
											</p>
										</td>
									</tr>
									<? endforeach; ?>
								</table>
								</div>
								<!---->
							</li>
							<? endforeach; ?>
						</ul>
						<div class="marco-fin"></div>
					</div>
				</div>
				<!---->
			</div>
			<!--fin perfil div-->
		</div>
	</div>
</div>