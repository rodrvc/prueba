<?= $this->element('menu'); ?>
<?= $this->element('detalle-buscador'); ?>
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
										<td class="o-datos">
											<p class="o-txto">Orden de compra Nº: <?= $compra['Compra']['id']; ?></p>
										</td>
										<td class="o-datos">
											<p class="o-txto"><?= date('d-m-Y', strtotime($compra['Compra']['created'])); ?></p>
										</td>
										<td class="o-datos">
											<p class="o-txto normal">Cantidad de productos:<b><?= $cant_prod[$index]; ?></b></p>
										</td>
										<td class="o-datos">
											<p class="o-txto normal">Precio total:<b>$ <?= number_format($compra['Compra']['total'], 0, ",", "."); ?></b></p>
										</td>
										
										<!-- BOTON MOSTRAR DETALLE -->
										<td class="o-datos">
											<div class="boton dos medio sin"><a href="#" class="azul ver-detalle" data-id="<?= $compra['Compra']['id']; ?>"><span>Detalles</span></a></div>
										</td>											
									</tr>
								</table>
								
								<!--Tabla detalles (oculta)-->
								<div class="tabla-detal dos tab-detal<?= $compra['Compra']['id']; ?>" style="display: none;">
								<table width="100%" class="h-detalles">
									<? foreach( $compra['Producto'] as $producto ) : ?>
									<tr class="d-total o-total prod_id-<?= $producto['ProductosCompra']['id']; ?>">
										<td class="d-datos o-datos">
											<?= $this->Html->image($producto['Producto']['foto']['mini']); ?>
										</td>
										<td class="d-datos o-datos">
											<p class="o-titu"><?= $producto['Producto']['nombre']; ?></p>
											<p class="o-titu normal">Color: <?= $producto['Color']['nombre']; ?></p>
											<p class="o-titu normal">COD: <?= $producto['Producto']['codigo']; ?> <?= $producto['Color']['codigo']; ?></p>
										</td>
										<td class="d-datos o-datos">
											<div class="o-talla">
												<span class="name">Talla:</span>
												<?= $producto['Stock']['talla']; ?>
											</div>
										</td>
										<td class="d-datos o-datos">
											<div class="o-talla">
												<!--<span class="name">Cantidad</span>
												<select name="" size="1">
													<option>38</option>
													<option>37</option>
												</select>-->
											</div>
										</td>
										<td class="d-datos o-datos">
											<? if( $producto['Producto']['oferta'] == 1 ) : ?>
											<p class="o-precio rayao">$ <?= number_format($producto['Producto']['precio'], 0, ",", "."); ?></p>
											<p class="o-precio naranja">$ <?= number_format($producto['Producto']['precio_oferta'], 0, ",", "."); ?></p>
											<? else : ?>
											<p class="o-precio">$ <?= number_format($producto['Producto']['precio'], 0, ",", "."); ?></p>
											<? endif; ?>
										</td>
										<td class="d-datos o-datos">
											<a href="#" class="elimino delete-nofinal" data-id="<?= $producto['ProductosCompra']['id']; ?>">Eliminar</a>
										</td>											
									</tr>
									<? endforeach; ?>
									
									<!-- BOTON AGREGAR MAS PRODUCTOS -->
									<!--<tr class="d-total">
										<td class="d-total d-borde doble" colspan="6">
											<a href="#" class="agregar">Agregar otro producto al carro<span class="mas">&nbsp;</span></a>
										</td>
									</tr>-->
									
									<!-- RESUMEN COMPRA -->
									<tr class="d-total">		
										<td colspan="2" class="d-datos o-tarjetas">
											<?= $this->Html->image('tarjetas-credito.png'); ?>
										</td>
										<td colspan="2" class="d-datos o-totales">
											<p class="titu-negrita normal">Subtotal:</p>
											
											<p class="titu-negrita normal">Despacho a domicilio</p>
											<a href="#" class="despacho-link">(Ver políticas de despacho)</a>
											<p class="titu-negrita big">Total</p>
											
										</td>
										<td colspan="2" class="d-datos o-totales">
											<p class="titu-negrita normal">$ <?= number_format($compra['Compra']['subtotal'], 0, ",", "."); ?></p>
											<p class="titu-negrita normal">$ 0</p>
											<p class="titu-negrita normal">-</p>
											<p class="titu-negrita big">$ <?= number_format($compra['Compra']['total'], 0, ",", "."); ?></p>
										</td>
									</tr>
									
									<!-- BOTON FINALIZAR -->
									<tr class="d-total">		
										<td class="d-datos o-large" colspan="6">
										<div class="boton dos derec mar">
											
											<a href="<?= $this->Html->url(array('controller' => 'productos', 'action' => 'finalizar_nofinalizado', $compra['Compra']['id'])); ?>" class="naranjo"><span>Finalizar compra</span></a>
										</div>
										</td>
									</tr>
								</table>
								
								<!-- BOTON OCULTAR DETALLE -->
								<div class="boton dos derec mar"><a href="" class="azul ocul-detal"><span>Ocultar Detalle</span></a></div>
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
