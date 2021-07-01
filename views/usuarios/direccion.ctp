<?= $this->element('menu'); ?>
<?= $this->element('detalle-buscador'); ?>
<div class="wrapper">
	
	<div class="cont-carro">
		<div class="bg-top-contenedor">				
			<div class="boton dos margen"><a href="#" title="Seguir comprando" class="celeste"><span>Volver a catálogo</span></a></div>
		</div>	
		<div class="registro">			
			
			<div class="cont-registro">
				<div class="info-confi">
					<p><b>Estimado Hugo Braulio Acevedo.</b><br /><br />
					 La información que nos proporcionarás es privada y no será utilizada sin tu consentimiento. Si deseas cerrar tu perfil, ponte en contacto con nostros al correo <a href="">ventas@skechers.com</a> y lo borraremos por ti.<br /><br />			 
					</p>
				</div>				
			</div>
			<!--perfil-->
			<div class="perfil-us">
				<ul class="botones">
					<li>
						<a href="perfil-datos.html" class="mi">Mis Datos</a>
					</li>
					<li>
						<a href="perfil-midireccion.html" class="mi dire current">Mis Direcciones</a>
					</li>
					<li>
						<a href="perfil-micontrasena.html" class="mi pass">Mi Contraseña</a>
					</li>
					<li>
						<a href="perfil-historial.html" class="mi historial">Mi Historial de compra</a>
					</li>
					<li class="nomargen">
						<a href="perfil-misordenes.html" class="mi orden">Mis Órdenes no finalizadas</a>
					</li>
				</ul>
				<div class="cont-direccion">
					<div class="p-direccion">
					 <div class="large"><h3 class="titulo-perfil margen">Ingresa una nueva dirección</h3></div>	
					 <ul class="p-datos">
						
						<li>
							<span class="nombres perfil-reg">Calle*</span>
							<input name="calle" type="text" class="p-campos" id="calle" />
						</li>
						
						<li>
							<div class="separador"><span class="nombres perfil-reg">Número*</span>
								<select name="dia" size="1" class="fechas">
									<option>Dia</option>
								<option>1</option>
								<option>2</option>
								<option>3</option>
						</select>
							</div>
							<div class="separador"><span class="nombres perfil-reg">Dpto.*</span>
							<select name="dia" size="1" class="fechas">
								<option>Dia</option>
								<option>1</option>
								<option>2</option>
								<option>3</option>
							</select>
							</div>
						</li>	
						
						<li>
							<span class="nombres perfil-reg">Comuna*</span>
							<select name="comuna" size="1" class="p-campos">
								<option>Seleccione su comuna</option>
								<option>san miguel</option>
							</select>
						</li>									
						<li>
							<span class="nombres perfil-reg">Región*</span>
							<select name="comuna" size="1" class="p-campos">
								<option>Seleccione su comuna</option>
								<option>san miguel</option>
							</select>
						</li>							
						
						<li>
							<span class="nombres perfil-reg">Código Postal (Opcional)</span>
							<input name="postal" type="text" class="p-campos" />
						</li>
						<li>
							<div class="separador"><span class="nombres perfil-reg">Teléfono*</span>
								<label for="telefono"></label>
								<input name="telefono" type="text" class="fechas peque" id="telefono" size="5" /><input type="text" name="telefono" id="telefono" class="fechas" />
							</div>
							
							<div class="separador"><span class="nombres perfil-reg">Celular*</span>
							<input name="telefono" type="text" class="fechas peque" id="telefono" size="5" /><input type="text" name="telefono" id="telefono" class="fechas" />
							</div>
						</li>
						<li>
							<span class="nombres perfil-reg">Guardar dirección como: *</span>
							<input name="postal" type="text" class="p-campos" />
						</li>										
					 </ul>
					 <span class="asterico">* Debes completar todos los campos obligatorios</span>
					 <div class="boton dos dentro"><a href="#" class="azul"><span>Agregar</span></a></div>	
					</div>
					<div class="p-listado">
						<h3 class="titulo-perfil">Selecciona una dirección de tu lista</h3>
						<select name="direcciones" size="1" multiple="multiple" class="lista-di">
							<option selected="selected" class="separar">Casa</option>
							<option class="separar">Trabajo</option>
							<option class="separar">Casa de la nana</option>
						</select>
						<a href="" class="eliminar-red">Eliminar dirección seleccionada</a>
						<div class="boton dos dentrodos"><a href="#" class="azul"><span>Editar</span></a></div>	
					</div>

					
				</div>		
							
				
			</div>
			<!--fin perfil div-->
		</div>		
	</div>
</div>
