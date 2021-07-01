<div class="container">
	<h1 class="titulo-categoria">Ingresa</h1>
	<div class="panel panel-skechers">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-6">
					<div class="panel panel-info panel-skechers2">
						<div class="panel-heading">
							<h3 class="panel-title">Usuario existente</h3>
						</div>
						<div class="panel-body">
							<?= $this->Form->create('Usuario', array('action' => 'login', 'class' => 'form-horizontal')); ?>
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">Usuario</label>
									<div class="col-sm-10">
										<?= $this->Form->input('email', array('type' => 'text',
																			  'div' => false,
																			  'label' => false,
																			  'class' => 'form-control',
																			  'placeholder' => 'Email')); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">Clave:</label>
									<div class="col-sm-10">
										<?= $this->Form->input('clave', array('type' => 'password',
																			  'div' => false,
																			  'label' => false,
																			  'class' => 'form-control',
																			  'placeholder' => 'Tu clave')); ?>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<button type="submit" class="btn btn-primary">Ingresa</button>
									</div>
								</div>
								<a href="#" class="pull-right" rel="recuperarClave"><i class="fa fa-angle-double-right"></i>recuperar contraseña</a>
							<?= $this->Form->end(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>