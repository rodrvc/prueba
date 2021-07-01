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
				<div class="cont-direccion">
					<div class="p-direccion">
						<?= $this->Form->create('Direccion', array('type' => 'file', 'inputDefaults' => array('class' => 'p-campos', 'div' => false, 'label' => false))); ?>
						<div class="large">
						<? if( $mensaje = $this->Session->flash() ) : ?>
						<h3 class="titulo-perfil margen" style="color: Red;"><?= $mensaje; ?></h3>
						<? else : ?>
						<h3 class="titulo-perfil margen">Ingresa una nueva dirección</h3>
						<? endif; ?>
						</div>
						<ul class="p-datos">
							<li>
								<span class="nombres perfil-reg">Calle*</span>
								<?= $this->Form->input('calle'); ?>
							</li>
							<li>
								<div class="separador"><span class="nombres perfil-reg">Número*</span>
								<?= $this->Form->input('numero', array('style' => 'width: 80px;')); ?>
								</div>
								<div class="separador"><span class="nombres perfil-reg">Dpto.</span>
								<?= $this->Form->input('depto', array('style' => 'width: 80px;')); ?>
								</div>
							</li>
							<li>
								<span class="nombres perfil-reg">Región*</span>
								<select id="DireccionRegionId" class="p-campos" name="data[Direccion][region_id]">
									<option value="0">-- Seleccione una Region</option>
								<? foreach( $regiones as $index => $region ){
									echo "<option value=\"{$index}\">{$region}</option>\n"; } ?>
								</select>
							</li>
							<li>
								<span class="nombres perfil-reg">Comuna*</span>
								<select id="DireccionComunaId" class="p-campos" name="data[Direccion][comuna_id]">
									<option value="0">-- Seleccione una Region</option>
								</select>
							</li>
							<li>
								<span class="nombres perfil-reg">Código Postal (Opcional)</span>
								<?= $this->Form->input('codigo_postal'); ?>
							</li>
							<li>
								<div class="separador"><span class="nombres perfil-reg">Teléfono*</span>
									<label for="telefono"></label>
									<?= $this->Form->input('pre_telefono', array('class' => 'fechas peque', 'size' => 5)); ?>
									<?= $this->Form->input('telefono', array('class' => 'fechas')); ?>
								</div>
								<div class="separador"><span class="nombres perfil-reg">Celular*</span>
									<?= $this->Form->input('pre_celular', array('class' => 'fechas peque', 'size' => 5)); ?>
									<?= $this->Form->input('celular', array('class' => 'fechas')); ?>
								</div>
							</li>
							<li>
								<span class="nombres perfil-reg">Guardar dirección como: *</span>
								<?= $this->Form->input('nombre'); ?>
							</li>										
						</ul>
						<span class="asterico">* Debes completar todos los campos obligatorios</span>
						<div class="boton dos dentro"><a href="#" class="azul submit"><span>Agregar</span></a></div>
						<?= $this->Form->end(); ?>
					</div>
					
					
					<div class="p-listado">
						<h3 class="titulo-perfil">Selecciona una dirección de tu lista</h3>
						<select name="direcciones" size="1" multiple="multiple" class="lista-di">
							<? foreach( $mis_direcciones as $index => $mi_direccion ) : ?>
							<option class="separar sel-dire" data-id="<?= $mi_direccion['Direccion']['id']; ?>"><?= $mi_direccion['Direccion']['nombre']; ?></option>
							<? endforeach; ?>
						</select>
						<?= $this->Html->link('Eliminar dirección seleccionada', '#', array('escape' => false, 'class' => 'eliminar-red btn-eliminar-direccion no-sel-d')); ?>
						<div class="boton dos dentrodos">
							<?= $this->Html->link('<span>Editar</span>', '#', array('escape' => false, 'class' => 'guarda btn-editar-direccion no-sel-d')); ?>
						</div>	
					</div>
				</div>		
			</div>
			<!--fin perfil div-->
		</div>		
	</div>
</div>
