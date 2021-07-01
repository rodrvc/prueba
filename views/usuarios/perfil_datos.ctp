<div id="modalDireccion" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" rel="titulo"></h4>
			</div>
			<div class="modal-body">
				
				<?= $this->Form->create('Direccion', array('action' => 'edit', 'class' => 'form-horizontal','class' => 'form-horizontal','inputDefaults' => array('class' => 'form-control ','div' => false,'label' => false))); ?>
					<div class="alert alert-warning hidden"><i class="fa fa-warning"></i> Se ha producido un problema</div>
					<div class="alert alert-success hidden"><i class="fa fa-warning"></i> Registro guardado exitosamente</div>
					<?= $this->Form->hidden('id'); ?>
					<div class="form-group">
						<label class="col-sm-4 control-label">
							Calle:
						</label>
						<div class="col-sm-7">
							<?= $this->Form->input('calle', array('class' => 'form-control requerido', 'maxlength'=>'19')); ?>
							
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">
							Numero:
						</label>
						<div class="col-sm-7">
							<?= $this->Form->input('numero', array('class' => 'form-control requerido','maxlength'=>'5')); ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">
							Depto:
						</label>
						<div class="col-sm-7">
							<?= $this->Form->input('depto', array('class' => 'form-control','maxlength'=>'24')); ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">
							Región:
						</label>
						<div class="col-sm-7">
							<?= $this->Form->input('region_id', array('class' => 'form-control requerido','empty' => '-- Seleccione una región', 'options' => $regiones)); ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">
							Comuna:
						</label>
						<div class="col-sm-7">
							<?= $this->Form->input('comuna_id', array('class' => 'form-control requerido', 'empty' => '-- Seleccione una región', 'options' => $comunas)); ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">
							Código postal:
						</label>
						<div class="col-sm-7">
							<?= $this->Form->input('codigo_postal'); ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">
							Teléfono:
						</label>
						<div class="col-md-7">
							<div class="row">
								<div class="col-md-12">
									<?= $this->Form->input('telefono', array('size' => 12)); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">
							Celular:
						</label>
						<div class="col-md-7">
							<div class="row">
								<div class="col-md-12">
									<?= $this->Form->input('celular', array('class' => 'form-control', 'size' => 12)); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">
							Guardar dirección como:
						</label>
						<div class="col-sm-7">
							<?= $this->Form->input('nombre', array('class' => 'form-control requerido')); ?>
						</div>
					</div>
					<a href="#" class="azul btn btn-primary btn-block btn-lg" rel="btnGuardarDireccion">Agregar nueva direccion</a>
				<?= $this->Form->end(); ?>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="container">
	<h1 class="titulo-categoria">Perfil de usuario</h1>
	<div class="panel panel-skechers">
		<div class="panel-body">
			<div class="cont-carro">
				<div class="row">
					<div class="col-md-12">
						<div class="well well-sm">
							<h4>Estimad@ <?= $authUser['nombre'].' '.$authUser['apellido_paterno'].' '.$authUser['apellido_materno']; ?>.</h4>
								<p>La información que nos proporcionarás es privada y no será utilizada sin tu consentimiento. Si deseas cerrar tu perfil, ponte en contacto con nostros al correo <a href="mailto: ventas@skechers.com">ventas@skechers.com</a> y lo borraremos por ti.
							</p>
						</div>
					</div>
					<div class="col-md-12">
						<ul class="nav nav-tabs nav-tabs-skechers" role="tablist">
							<li role="presentation" >
								<a href="#datos" aria-controls="messages" role="tab" data-toggle="tab">Mis datos</a>
							</li>
							<li role="presentation">
								<a href="#direcciones" aria-controls="messages" role="tab" data-toggle="tab">Mis direcciones</a>
							</li>
							<li role="presentation">
								<a href="#clave" aria-controls="messages" role="tab" data-toggle="tab">Mi contraseña</a>
							</li>
							<li role="presentation" class="active">
								<a href="#historial" aria-controls="messages" role="tab" data-toggle="tab">Mi historial de compra</a>
							</li>
                            <?php if($cod ) :?>
                             <!--   <li role="presentation">
                                    <a href="#despacho" aria-controls="messages" role="tab" data-toggle="tab">Mi despachos</a>
                                </li> -->
                            <?php endif; ?>

						</ul>
						<div class="tab-content tab-content-skechers">
							<div role="tabpanel" class="tab-pane " id="datos">
								<div class="row">
									<div class="col-md-offset-2 col-md-8">
										<?= $this->Form->create('Usuario', array('class' => 'form-horizontal','inputDefaults' => array('class' => 'form-control','div' => false,'label' => false))); ?>
											<?
											$mensaje = false;
											if ($this->Session->check('Message.flash.params.rel'))
											{
												$mensaje = $this->Session->read('Message.flash');
												$this->Session->delete('Message');
											}
											?>
											<? if($mensaje && $mensaje['params']['rel']=='form-datos') : ?>
												<div class="<?= $mensaje['params']['class']; ?>"><i class="fa fa-warning"></i> <?= $mensaje['message']; ?></div>
											<? endif; ?>
											<legend class="text-info">Estos son tus datos personales</legend>
											<div class="form-group">
												<label class="col-sm-4 control-label">
													Tu email:
												</label>
												<div class="col-sm-7">
													<?= $this->Form->hidden('id'); ?>
													<?= $this->Form->input('email', array('placeholder' => 'Tu email')); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">
													Tu nombre:
												</label>
												<div class="col-sm-7">
													<?= $this->Form->input('nombre', array('placeholder' => 'Tu nombre')); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">
													Tu apellido:
												</label>
												<div class="col-sm-7">
													<?= $this->Form->input('apellido_paterno', array('placeholder' => 'Tu apellido')); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">
													Rut:
												</label>
												<div class="col-sm-7">
													<?= $this->Form->input('rut', array('placeholder' => 'Ej: 12.345.678-9')); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">
													Fecha de nacimiento:
												</label>
												<div class="col-md-7">
													<div class="row">
														<div class="col-md-4">
															<?= $this->Form->Day('fecha_nacimiento', false, array('empty' => 'Día', 'class' => 'form-control')); ?>
														</div>
														<div class="col-md-4">
															<?
															$valor = null;
															if ($date = explode('-',$this->data['Usuario']['fecha_nacimiento']))
																$valor = $date[1];
															elseif (isset($this->data['Usuario']['fecha_nacimiento']['month']) && $this->data['Usuario']['fecha_nacimiento']['month'])
																$valor = $this->data['Usuario']['fecha_nacimiento']['month'];
															?>
															<?= $this->Form->input('Usuario.fecha_nacimiento.month',array('empty' => 'Mes',
																														  'options' => array('01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto','09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'),
																														  'value' => $valor)); ?>
														</div>
														<div class="col-md-4">
															<?= $this->Form->Year('fecha_nacimiento', 1900, date('Y'), false, array('empty' => 'Año', 'class' => 'form-control')); ?>
														</div>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">
													Sexo:
												</label>
												<div class="col-sm-7">
													<?= $this->Form->input('sexo_id', array('empty' => ' -- Seleccione su sexo')); ?>
												</div>
											</div>
											<a href="#" class="azul submit btn btn-primary btn-block btn-lg">Editar</a>
										<?= $this->Form->end(); ?>
									</div>
									<!--<div class="col-md-5 hidden">
										Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate vero veritatis sit libero ullam placeat nulla ipsam at recusandae magni sapiente nobis suscipit mollitia itaque eligendi earum aspernatur, quaerat cumque. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis mollitia omnis, eos doloremque asperiores accusantium quas maiores quidem libero, qui ipsam consectetur facere explicabo nisi amet eaque neque obcaecati nam! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloribus sint nulla at quibusdam minus placeat magni inventore nihil dolor deleniti necessitatibus dolorem provident voluptates quaerat, nobis molestias corrupti voluptatum vel? Lorem ipsum dolor sit amet, consectetur adipisicing elit. Natus vitae nam obcaecati quaerat quasi est, quo veniam magnam exercitationem, facere quia fugiat. Suscipit doloremque quidem facilis iure, tempore praesentium asperiores!
									</div>-->
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="direcciones">
								<div class="row">
									<div class="col-md-5">
										<legend class="text-info">Listado de direcciones</legend>
										<div class="col-md-12">
											<p>Selecciona una direccion para editarla</p>
											<ul class="list-group">
												<? foreach ($direcciones as $direccion) : ?>
												<li class="list-group-item<?= ($direccion == reset($direcciones)) ? ' list-group-item-info':''; ?>">
													<b><?= $direccion['Direccion']['nombre']; ?></b>
													<a href="#" rel="direccionBorrar" data-id="<?= $direccion['Direccion']['id']; ?>" title="Borrar dirección" class="<?= $direccion == reset($direcciones) ? 'hidden':''; ?>">
														<i class="fa fa-trash-o pull-right text-danger" style="font-size:18px!important;"></i>
													</a>
													<a href="#" rel="direccionEditar" data-id="<?= $direccion['Direccion']['id']; ?>" title="Editar dirección">
														<i class="fa fa-edit pull-right text-warning" style="font-size:18px!important"></i>
													</a>
													<a href="#" rel="direccionVer" data-id="<?= $direccion['Direccion']['id']; ?>" title="Dirección predeterminada">
														<i class="fa fa-eye pull-right text-danger" style="font-size:18px!important; margin-left 10px:"></i>
													</a>
												</li>
												<? endforeach; ?>
											</ul>
										</div>
									</div>
									<div class="col-md-7">
										<?= $this->Form->create('Direccion', array('action' => 'agregar', 'class' => 'form-horizontal')); ?>
											<? if($mensaje && $mensaje['params']['rel']=='form-direccion') : ?>
												<div class="<?= $mensaje['params']['class']; ?>"><i class="fa fa-warning"></i> <?= $mensaje['message']; ?></div>
											<? endif; ?>
											<legend class="text-info">Ingresa una nueva dirección</legend>
											<div class="form-group">
												<label class="col-sm-4 control-label">
													Calle:
												</label>
												<div class="col-sm-7">
													<?= $this->Form->input('calle', array('class' => 'form-control requerido', 'placeholder' => 'Calle...', 'label' => false, 'maxlength'=>'19')); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">
													Numero:
												</label>
												<div class="col-sm-7">
													<?= $this->Form->input('numero', array('class' => 'form-control requerido', 'placeholder' => 'Número...', 'label' => false, 'maxlength'=>'5')); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">
													Depto:
												</label>
												<div class="col-sm-7">
													<?= $this->Form->input('depto', array('class' => 'form-control', 'placeholder' => 'Depto...', 'label' => false, 'maxlength'=>'24')); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">
													Región:
												</label>
												<div class="col-sm-7">
													<?= $this->Form->input('region_id', array('class' => 'form-control requerido', 'empty' => '-- Seleccione una región', 'options' => $regiones, 'label' => false)); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">
													Comuna:
												</label>
												<div class="col-sm-7">
													<?= $this->Form->input('comuna_id', array('class' => 'form-control requerido', 'empty' => '-- Seleccione una región', 'options' => $comunas, 'label' => false)); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">
													Código postal:
												</label>
												<div class="col-sm-7">
													<?= $this->Form->input('codigo_postal', array('class' => 'form-control', 'placeholder' => 'Código postal...', 'label' => false)); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">
													Teléfono:
												</label>
												<div class="col-md-7">
													<div class="row">
														<div class="col-md-12">
															<?= $this->Form->input('telefono', array('class' => 'form-control requerido', 'size' => 12, 'label' => false)); ?>
														</div>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">
													Celular:
												</label>
												<div class="col-md-7">
													<div class="row">
														<div class="col-md-12">
															<?= $this->Form->input('celular', array('class' => 'form-control', 'size' => 12, 'label' => false)); ?>
														</div>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">
													Guardar dirección como:
												</label>
												<div class="col-sm-7">
													<?= $this->Form->input('nombre', array('class' => 'form-control requerido', 'placeholder' => 'Guardar como...', 'label' => false)); ?>
												</div>
											</div>
											<a href="#" class="azul btn btn-primary btn-block btn-lg" rel="btnGuardarDireccion">Agregar nueva direccion</a>
										<?= $this->Form->end(); ?>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="clave">
								<?= $this->Form->create('Usuario', array('action' => 'cambioclave','class' => 'form-horizontal','inputDefaults' => array('class' => 'form-control requerido','div' => false,'label' => false))); ?>
									<? if($mensaje && $mensaje['params']['rel']=='form-clave') : ?>
										<div class="<?= $mensaje['params']['class']; ?>"><i class="fa fa-warning"></i> <?= $mensaje['message']; ?></div>
									<? endif; ?>
									<legend class="text-info">Cambio de clave</legend>
									<div class="form-group">
										<label class="col-sm-4 control-label">
											Contraseña actual:
										</label>
										<div class="col-sm-7">
											<?= $this->Form->input('clave_actual', array('type' => 'password', 'placeholder' => 'Clave actual')); ?>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-4 control-label">
											Nueva contraseña:
										</label>
										<div class="col-sm-7">
											<?= $this->Form->input('clave', array('type' => 'password', 'placeholder' => 'Clave nueva')); ?>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-4 control-label">
											Repetir nueva contraseña:
										</label>
										<div class="col-sm-7">
											<?= $this->Form->input('clave2', array('type' => 'password', 'placeholder' => 'Repetir clave nueva')); ?>
										</div>
									</div>
									<a href="#" class="azul btn btn-primary btn-block btn-lg" rel="btnCambiarClave">Cambiar de clave</a>
								<?= $this->Form->end(); ?>
							</div>
							<div role="tabpanel" class="tab-pane active" id="historial" >
								<?= $this->element('historial_compras',array("email" => $authUser['email'])); ?>
							</div>
                            <div role="tabpanel" class="tab-pane" id="despacho" >
                                <?= $this->element('historial_despachos'); ?>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
<? if (isset($this->params['url']['tab']) && $this->params['url']['tab']) : ?>
$('ul.nav-tabs.nav-tabs-skechers > li').removeClass('active');
$('.tab-content.tab-content-skechers > .tab-pane').removeClass('active');
$('ul.nav-tabs.nav-tabs-skechers > li > a[href="#<?= $this->params['url']['tab']; ?>"]').parent().addClass('active');
$('.tab-content.tab-content-skechers > .tab-pane#<?= $this->params['url']['tab']; ?>').addClass('active');
<? endif; ?>
</script>