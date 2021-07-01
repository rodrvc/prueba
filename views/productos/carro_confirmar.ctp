<?= $this->element('menu'); ?>
<? $this->element('detalle-buscador'); ?>
		<div class="cont-carro">
			<div class="bg-top-contenedor">				
				<div class="boton dos margen"><a href="#" title="Seguir comprando" class="celeste"><span>Continuar Comprando</span></a></div>
			</div>	
			<div class="registro">
				<div class="cont-pasos tres">
					<a href=""><span class="lugar">Registro</span></a>
					<a href=""><span class="lugar dos">Despacho</span></a>
					<a href=""><span class="lugar tres current">Confirmar</span></a>
					<a href=""><span class="lugar cuatro">Pago</span></a>
					<a href=""><span class="lugar cinco">Recibo</span></a>
				</div>
				<div class="online">
					<div class="boton dos"><a href="#" class="volver"><span>Volver</span></a></div>
					<h2>Revisa que todo esté correcto</h2>
				</div>
				<div class="cont-registro">
					<div class="info-confi"><p>Aún no haz finalizado la compra. Revisa 
que todos los datos sean correctos y luego haz click en "Finalizar 
Compra" para elegir tu medio de pago.</p></div>
				<!--bloque del carro-->					
				<div class="carro datos">
					<h2>Datos de compra</h2>
					<table class="item" width="100%">
					  <tbody><tr>
					    <td><?= $this->Html->image('tilla.png'); ?></td>
					    <td class="info">
							<h3>Women`s Prospeed SRR</h3>
							<p>Color: Azul/Verde</p>
							<p> CÓDIGO: 12345ABCDEF</p>							
								
												
						</td>
						<td class="info mini">
							<span class="nombres dos">Talla:</span>
							<span class="nombres tres">38:</span>
						</td>
					    <td class="info mini">
							<span class="nombres dos">Cantidad:</span>
							<span class="nombres tres">3</span>
						</td>
						<td class="info mini"><div class="price"><b>$39.000</b></div></td>
						<td></td>
						

					  </tr>
					  <tr>
					    <td><?= $this->Html->image('tilla.png'); ?></td>
					    <td class="info">
							<h3>Women`s Prospeed SRR - <b class="oferta">OFERTA</b></h3>
							<p>Color: Azul/Verde</p>
							<p>Color: Código: 12345ABCDEF</p>	
										
						</td>
						<td class="info mini">
							<span class="nombres dos">Talla:</span>
							<span class="nombres tres">38:</span>
						</td>
					    <td class="info mini">
							<span class="nombres dos">Cantidad:</span>
							<span class="nombres tres">3</span>
						</td>
						<td class="info mini">						
						<div class="price"><b class="oferta">$39.000</b></div></td>
						<td></td>

					  </tr>
					  	<tr>
							<td>
								<?= $this->Html->image('tilla.png'); ?>
								<?= $this->Html->image('cupon-descuento.png'); ?>
						</td>
					    <td class="info">
							<h3>Women`s Prospeed SRR</h3>
							<p>Color: Azul/Verde</p>
							<p> CÓDIGO: 12345ABCDEF</p>
							<p class="rosa">+ Cupón 30% descuento</p>
							<p>COD: 123aABCDFG35FDF5560</p>
							<p class="rosa">Descuento: $ 11.700</p>
												
						</td>
						<td class="info mini">
							<span class="nombres dos">Talla:</span>
							<span class="nombres tres">38:</span>
						</td>
					    <td class="info mini">
							<span class="nombres dos">Cantidad:</span>
							<span class="nombres tres">3</span>
						</td>
						<td class="info mini">
						<div class="price"><b>$39.000</b></div></td>
						<td></td>
					  
					   </tr>
					   <tr>
					    <td colspan="3" class="info dos">
							<h3>Datos de Despacho:</h3>
							<p class="datos">Tu compra será despachada a esta dirección y la boleta de esta compra la recibirás en tu mail. Los teléfonos indicados serán utilizados en caso que suceda algún contratiempo.</p>											
						
						</td>
						<td colspan="3" class="info">
							<h3>Av. Siempreviva #752<br />
								<p>Comuna el Bosque<br />
								Santiago<br />
								Código postal: 8985585<br />
								Teléfono: (02) 555 1234</p>
							</h3>
						</td>				    
					  </tr>
					  
					  <tr>
					    <td colspan="3" class="info dos">							
							<p class="datos">Tu compra será despachada a esta dirección y la boleta de esta compra la recibirás en tu mail. Los teléfonos indicados serán utilizados en caso que suceda algún contratiempo.</p>											
						
						</td>
						<td colspan="1" class="info tres">
							<h3 class="sub">Subtotal</h3>
							<h3 class="sub">IVA (19%)</h3>
							<h3 class="sub">Precio Neto</h3>
							<h3 class="sub">Despacho a domicilio</h3>
							
							<span class="total">Total</span>
						</td>
						<td colspan="2" class="info tres">
							<h3 class="sub">$ 84.168</h3>	
							<h3 class="sub">$ 19.742</h3>
							<h3 class="sub">$ 103.910</h3>
							<h3 class="sub">$ 0</h3>						
							<span class="total">$103.910</span>
						</td>		    
					  </tr>	 				
					  
					  <tr class="noborde">
					    <td colspan="3" class="tarjetas"><?= $this->Html->image('tarjetas-credito.png'); ?></td>
						<td colspan="3" class="info tres">							
							<div class="despacho">
								<input type="checkbox" name="acepto" id="acepto" class="in-chek" />	
								<a href="#" class="politicas dos">														
								Acepto condiciones de venta</a>
							</div>
							<div class="despacho">
								<input type="checkbox" name="acepto" id="acepto" class="in-chek" />
								<label for="acepto"></label>
								<a href="#" class="politicas dos">								
								Declaro conocer áreas de despacho a domicilio</a>
								<div class="boton dos"><a href="#" class="orange"><span>Finalizar compra</span></a></div>
							</div>												
							
						</td>
						</tr>	 					
					  
					</tbody></table>			

				</div>			
					
			</div>		
		</div>
	</div>
</div>
</div>