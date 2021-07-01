<?= $this->element('menu'); ?>
<?= $this->element('detalle-buscador'); ?>
<div class="wrapper">
	<?= $this->Form->create('Usuario', array('type' => 'file', 'inputDefaults' => array('class' => 'p-campos', 'div' => false, 'label' => false))); ?>
	<div class="cont-carro">
		<div class="bg-top-contenedor">
			<div class="boton dos margen"><a href="#" title="Seguir comprando" class="celeste"><span>Volver a catálogo</span></a></div>
		</div>
		<div class="registro">
			<div class="cont-registro">
				<!--<div class="info-confi">
					<p><b>Estimado Hugo Braulio Acevedo.</b><br /><br />
					 La información que nos proporcionarás es privada y no será utilizada sin tu consentimiento. Si deseas cerrar tu perfil, ponte en contacto con nostros al correo <a href="">ventas@skechers.com</a> y lo borraremos por ti.<br /><br />			 
					</p>
				</div>	-->
			</div>
			<div class="perfil-us">	
				<ul class="p-registro">
					<li><h3 class="log">Recuperar Contaseña</h3><br />
					<div style="color: Red; float: left;">
						<? if( $mensaje = $this->Session->flash() ) : ?>
							<?= $mensaje; ?>
						<? endif; ?>
					</div>
					</li>
					<li>
						<span class="nombres perfil-reg dos" style="width: 245px;">Si has olvidado tu contraseña, puedes solicitarla ingresando tu e-mail y siguiendo las instrucciones del correo que te enviaremos.</span>
					</li>
					<li>
						<span class="nombres perfil-reg dos" style="width: 100px;">E-mail:</span>
						<?= $this->Form->input('email'); ?>
					</li>
					<li>
						<div class="boton dos"><a href="#" class="azul submit"><span>Aceptar</span></a></div>
					</li>
				</ul>
			</div>
			
			
		</div>
	</div>
	<?= $this->Form->end(); ?>
</div>