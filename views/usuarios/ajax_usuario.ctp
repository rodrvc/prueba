<? $authUser = $this->Session->read('Auth.Usuario'); ?>
				<? if (!isset($authUser)) : ?>
			 <div class="btns_header">
				<a href="<?= $this->Html->url(array('controller' => 'usuarios', 'action' => 'add')); ?>" class="iniciar_sesion" >Iniciar Sesión</a>
				<a href="<?= $this->Html->url(array('controller' => 'usuarios', 'action' => 'add')); ?>" class="registrarse">Registrarse</a></li>
			</div> 



				<? else : ?>

					<div class="btns_header">
					 <a href="<?= $this->Html->url(array('controller' => 'usuarios', 'action' => 'perfil_datos')); ?>" class="registrarse"><span class="text-white registrarse"><u>Bienvenid@ <?= $authUser['nombre']; ?></u></span></a>
            		<a href="<?= $this->Html->url(array('controller' => 'usuarios', 'action' => 'logout')); ?>" class="registrarse"><u>Salir</u></a>
            		</div> 
		
			<? endif; ?>




		
