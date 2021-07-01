<?= $this->element('menu'); ?>
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
				<?= $this->Form->create('Usuario', array('type' => 'file', 'inputDefaults' => array('class' => 'p-campos', 'div' => false, 'label' => false))); ?>
				<div class="cont-direccion">
					<div class="p-listado pass">
						<h3 class="titulo-perfil margen">Cambiar tu contraseña</h3>
						<? if( $mensaje = $this->Session->flash() ) : ?>
						<h3 class="titulo-perfil centro" style="color: Red;"><?= $mensaje; ?></h3>
						<? endif; ?>
						<ul class="p-pass">
						<li>
							<span class="nombres perfil-reg">Contraseña actual</span>
							<?= $this->Form->input('clave_actual', array('type' => 'password')); ?>
						</li>
						<li>
							<span class="nombres perfil-reg">Nueva contraseña</span>
							<?= $this->Form->input('clave', array('type' => 'password')); ?>
						</li>
						<li>
							<span class="nombres perfil-reg">Confirmar nueva contraseña</span>
							<?= $this->Form->input('clave2', array('type' => 'password')); ?>
						</li>
					 </ul>
						<div class="boton dos dentro"><a href="#" class="azul submit"><span>Aceptar</span></a></div>	
					</div>
				</div>
				<?= $this->Form->end(); ?>
			</div>
			<!--fin perfil div-->
		</div>		
	</div>
</div>
