<div class="container">
	<div class="panel panel-default">
		<div class="panel-body nopadding-top">
			<div class="volver-top">
		  		<div class="row">
			  		<div class="col-md-12">
			  			<?= $this->Html->link('<span>Volver a catálogo</span>', array('controller' => 'productos', 'action' => 'inicio'), array('title' => 'Seguir comprando', 'class' => 'btn btn-info btn-xs', 'escape' => false)); ?>
			  		</div>
			  	</div>
		  	</div>
			<div class="row">
				<div class="col-md-12">
					<div class="progress barra">

					  <div class="progress-bar progress-bar-primary relative" style="width: 20%">
					    <span class="icon-skechers">
					    	<div class="icono">
					    		<img src="<?= $this->Html->url('/img/bootstraps/icon-skechers-wizard.png'); ?>" width="100%">
					    	</div>
							<div class="texto">
								Despacho
							</div>
					    </span>
					  </div>

					  <div class="progress-bar relative progress-bar-danger" style="width: 20%">
					    <span class="icon-skechers">
					    	<div class="icono">
					    		<img src="<?= $this->Html->url('/img/bootstraps/icon-skechers-wizard-disabled.png'); ?>" width="100%">
					    	</div>
							<div class="texto text-disabled">
								Confirmar
							</div>
					    </span>
					  </div>

					   <div class="progress-bar relative progress-bar-danger" style="width: 20%">
					    <span class="icon-skechers">
					    	<div class="icono">
					    		<img src="<?= $this->Html->url('/img/bootstraps/icon-skechers-wizard-disabled.png'); ?>" width="100%">
					    	</div>
							<div class="texto text-disabled">
								Pago
							</div>
					    </span>
					  </div>

					  <div class="progress-bar relative progress-bar-danger" style="width: 20%">
					    <span class="icon-skechers">
					    	<div class="icono">
					    		<img src="<?= $this->Html->url('/img/bootstraps/icon-skechers-wizard-disabled.png'); ?>" width="100%">
					    	</div>
							<div class="texto text-disabled">
								Recibo
							</div>
					    </span>
					  </div>

					  <div class="progress-bar relative progress-bar-danger" style="width: 20%">
					  </div>

					</div>
				</div>
			</div>
			<div class="page-header">
			  <h1 class="text-center">Completa tus datos para el despacho</h1>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="well well-sm well-skechers">
						<form action="" method="POST" class="form-horizontal" role="form">
								<h4 class="titulo-pago">Selecciona tu direccion:</h4>
								<div class="form-group">
								<label for="inputPassword3" class="col-sm-4 control-label">Direcciones:</label>
								<div class="col-sm-7">
									<select name="" id="input" class="form-control" required="required">
										<option value="">Nombre de la direccion aqui</option>
										<option value="">Nombre de la direccion aqui</option>
										<option value="">Nombre de la direccion aqui</option>
										<option value="">Nombre de la direccion aqui</option>
									</select>
								</div>
							</div>
						</form>
						<div class="row">
							<div class="col-md-12">
								<table class="table table-striped table-hover">
									<tbody>
										<tr>
											<td>Nombre:</td>
											<td>Altos del parque norte</td>
										</tr>
										<tr>
											<td>Numero:</td>
											<td>8232</td>
										</tr>
										<tr>
											<td>Región:</td>
											<td>Metropolitana</td>
										</tr>
										<tr>
											<td>Comuna:</td>
											<td>Peñalolen</td>
										</tr>
										<tr>
											<td>Codigo postal:</td>
											<td>785965</td>
										</tr>
										<tr>
											<td>Teléfono:</td>
											<td>02523658</td>
										</tr>
										<tr>
											<td>Celular:</td>
											<td>09123456</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<a href="#" class="btn btn-primary btn-block">
									Editar direcciones
								</a>
							</div>
							<div class="col-md-6">
								<a href="#" class="btn btn-primary btn-block">
									Agregar una nueva direccion
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="well well-sm well-skechers">
						<form action="" method="POST" class="form-horizontal" role="form">
							<h4 class="titulo-pago">Crea una direccion nueva:</h4>
						<form class="form-horizontal">
						  <div class="form-group">
						    <label for="inputEmail3" class="col-sm-4 control-label">Calle</label>
						    <div class="col-sm-7">
						      <input type="email" class="form-control" placeholder="Nombre de calle">
						    </div>
						  </div>
						  <div class="form-group">
						    <label for="inputPassword3" class="col-sm-4 control-label">Numero</label>
						    <div class="col-sm-7">
						      <input type="numero" class="form-control"  placeholder="12345">
						    </div>
						  </div>

							<div class="form-group">
								<label for="inputPassword3" class="col-sm-4 control-label">Otras indicacion:</label>
								<div class="col-sm-7">
									<input type="numero" class="form-control"  placeholder="indicaciones..">
								</div>
							</div>

							<div class="form-group">
								<label for="inputPassword3" class="col-sm-4 control-label">Región:</label>
								<div class="col-sm-7">
									<select name="" id="input" class="form-control" required="required">
										<option value="">ewrwe rwe rwer we rwer ewr ew</option>
										<option value="">ewrwe rwe rwer we rwer ewr ew</option>
										<option value="">ewrwe rwe rwer we rwer ewr ew</option>
										<option value="">ewrwe rwe rwer we rwer ewr ew</option>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label for="inputPassword3" class="col-sm-4 control-label">Comuna:</label>
								<div class="col-sm-7">
									<select name="" id="input" class="form-control" required="required">
										<option value="">ewrwe rwe rwer we rwer ewr ew</option>
										<option value="">ewrwe rwe rwer we rwer ewr ew</option>
										<option value="">ewrwe rwe rwer we rwer ewr ew</option>
										<option value="">ewrwe rwe rwer we rwer ewr ew</option>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label for="inputPassword3" class="col-sm-4 control-label">Código postal:</label>
								<div class="col-sm-7">
									<input type="numero" class="form-control"  placeholder="Código postal">
								</div>
							</div>

							<div class="form-group">
								<label for="inputPassword3" class="col-sm-4 control-label">Teléfono:</label>
								<div class="col-sm-2">
									<input type="numero" class="form-control"  placeholder="02">
								</div>
								<div class="col-sm-5">
									<input type="numero" class="form-control"  placeholder="123456789">
								</div>
							</div>

							<div class="form-group">
								<label for="inputPassword3" class="col-sm-4 control-label">Celular:</label>
								<div class="col-sm-2">
									<input type="numero" class="form-control"  placeholder="02">
								</div>
								<div class="col-sm-5">
									<input type="numero" class="form-control"  placeholder="123456789">
								</div>
							</div>

						  <div class="form-group">
						    <div class="col-sm-offset-4 col-sm-8">
						      <button type="submit" class="btn btn-primary">Guardar dirección</button>
						    </div>
						  </div>
						</form>
					</div>
				</div>
			</div>
			<div class="row continuar-footer">
		  		<div class="col-md-6 col-md-offset-3">
		  			<?= $this->Html->link('<span>Continuar</span>', array('controller' => 'productos', 'action' => 'confirmar'), array('escape' => false, 'class' => 'btn btn-warning btn-block btn-lg')); ?>
		  		</div>
		  	</div>
	  	</div>
	</div>
</div>