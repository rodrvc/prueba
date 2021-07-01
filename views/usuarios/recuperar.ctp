<div class="container">
	<h1 class="titulo-categoria">Recuperar contraseña</h1>
	<div class="panel panel-skechers">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<div class="panel panel-info panel-skechers2">
						<div class="panel-heading">
							<h4 class="panel-title">Si has olvidado tu contraseña, puedes solicitarla ingresando tu e-mail y siguiendo las instrucciones del correo que te enviaremos.</h4>
						</div>
						<div class="panel-body">
							<?= $this->Form->create('Usuario', array('class' => 'form-horizontal')); ?>
								<div class="input-group relative">
									<?
									$options = array(
										'type' => 'text',
										'div' => false,
										'label' => false,
										'class' => 'form-control',
										'placeholder' => 'Email'
									);
									echo $this->Form->input('email',$options); ?>
									<span class="input-group-btn">
										<button class="btn btn-primary" type="submit">solicitar</button>
									</span>
								</div>
							<?= $this->Form->end(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>