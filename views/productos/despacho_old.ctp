<style type="text/css" media="all">
div.campo-obligatorio::after {
	content: "(*)";
	position: absolute;
	right: -5px;
	top: 3px;
	opacity: 0.5;
}
</style>

<div class="container">
	<div id="CarroDespacho" class="panel panel-default">
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
						<?= $this->Form->create('Producto', array('action' => 'confirmar', 'class' => 'form-horizontal')); ?>
							<h4 class="titulo-pago">Selecciona tu direccion:</h4>
							<div class="form-group">
								<label for="inputPassword3" class="col-sm-4 control-label">Direcciones:</label>
								<div class="col-sm-7">
									<?= $this->Form->input('direccion_id', array('type' => 'select',
																				 'options' => $direcciones,
																				 'empty' => '- seleccione direccion de despacho',
																				 'class' => 'form-control',
																				 'rel' => 'direccionDespacho',
																				 'div' => false,
																				 'label' => false)); ?>
								</div>
							</div>
						<?= $this->Form->end(); ?>
						<div class="row">
							<div class="col-md-12">
								<table id="tablaDatos" class="table table-striped table-hover">
									<tbody>
										<tr>
											<td>Nombre:</td>
											<td rel="direccionNombre"></td>
										</tr>
										<tr>
											<td>Calle:</td>
											<td rel="direccionCalle"></td>
										</tr>
										<tr>
											<td>Numero:</td>
											<td rel="direccionNumero"></td>
										</tr>
										<tr>
											<td>Depto:</td>
											<td rel="direccionDepto"></td>
										</tr>
										<tr>
											<td>Región:</td>
											<td rel="direccionRegion"></td>
										</tr>
										<tr>
											<td>Comuna:</td>
											<td rel="direccionComuna"></td>
										</tr>
										<tr>
											<td>Codigo postal:</td>
											<td rel="direccionCodigoPostal"></td>
										</tr>
										<tr>
											<td>Teléfono:</td>
											<td rel="direccionTelefono"></td>
										</tr>
										<tr>
											<td>Celular:</td>
											<td rel="direccionCelular"></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<a href="<?= $this->Html->url(array('controller' => 'usuarios','action' => 'perfil_datos', '?' => array('tab' => 'direcciones'))); ?>" class="btn btn-primary btn-block">
									Editar direcciones
								</a>
							</div>
							<div class="col-md-6">
								<a href="<?= $this->Html->url(array('controller' => 'usuarios','action' => 'perfil_datos', '?' => array('tab' => 'direcciones'))); ?>" class="btn btn-primary btn-block">
									Agregar nueva dirección
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 hidden-xs hidden-sm">
					<div class="well well-sm well-skechers">
						<?= $this->Form->create('Direccion', array('class' => 'form-horizontal','inputDefaults' => array('class' => 'form-control requerido', 'div' => false, 'label' => false))); ?>
							<h4 class="titulo-pago">Crea una direccion nueva:</h4>
							<div class="alert alert-warning" rel="error" style="display: none;"></div>
							<div class="alert alert-success" rel="exito" style="display: none;">Se ha añadido una nueva dirección a su listado.</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-4 control-label">Calle</label>
								<div class="col-sm-7 campo-obligatorio">
									<?= $this->Form->input('calle', array('placeholder' => 'Nombre de calle...','maxlength'=>'19')); ?>
								</div>
							</div>
							<div class="form-group">
								<label for="inputPassword3" class="col-sm-4 control-label">Numero</label>
								<div class="col-sm-7 campo-obligatorio">
									<?= $this->Form->input('numero', array('placeholder' => 'Numeración del domicilio...','maxlength'=>'5')); ?>
								</div>
							</div>
							<div class="form-group">
								<label for="inputPassword3" class="col-sm-4 control-label">Depto</label>
								<div class="col-sm-7">
									<?= $this->Form->input('depto', array('class' => 'form-control', 'placeholder' => 'Departamento...','maxlength'=>'24')); ?>
								</div>
							</div>
							<div class="form-group">
								<label for="inputPassword3" class="col-sm-4 control-label">Otras indicacion:</label>
								<div class="col-sm-7">
									<?= $this->Form->input('otras_indicaciones', array('class' => 'form-control', 'placeholder' => 'Indicaciones adicionales...')); ?>
								</div>
							</div>
							<div class="form-group">
								<label for="inputPassword3" class="col-sm-4 control-label">Región:</label>
								<div class="col-sm-7 campo-obligatorio">
									<?= $this->Form->input('region_id', array('empty' => '- seleccione una región',
																			  'options' => $regiones)); ?>
								</div>
							</div>
							<div class="form-group">
								<label for="inputPassword3" class="col-sm-4 control-label">Comuna:</label>
								<div class="col-sm-7 campo-obligatorio">
									<?= $this->Form->input('comuna_id', array('empty' => '- seleccione una comuna',
																			  'options' => $comunas)); ?>
								</div>
							</div>
						
							<div class="form-group">
								<label for="inputPassword3" class="col-sm-4 control-label">Teléfono:</label>
								<div class="col-sm-7 campo-obligatorio">
									<?= $this->Form->input('telefono', array('placeholder' => 'Teléfono...')); ?>
								</div>
							</div>
							<div class="form-group">
								<label for="inputPassword3" class="col-sm-4 control-label">Celular:</label>
								<div class="col-sm-7 campo-obligatorio">
									<?= $this->Form->input('celular', array('placeholder' => 'Celular...')); ?>
								</div>
							</div>
							<div class="form-group">
								<label for="inputPassword3" class="col-sm-4 control-label">Guardar como:</label>
								<div class="col-sm-7 campo-obligatorio">
									<?= $this->Form->input('nombre', array('placeholder' => 'Guardar como...')); ?>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-4 col-sm-8">
								  <button type="button" id="btnGuardarNuevaDireccion" class="btn btn-primary">Guardar dirección</button>
								</div>
							</div>
						<?= $this->Form->end(); ?>
					</div>
				</div>
			</div>
			<div class="row continuar-footer">
		  		<div class="col-md-6 col-md-offset-3">
					<a href="#" id="btnContinuar" class="btn btn-warning btn-block btn-lg disabled"><span>Continuar</span></a>
		  		</div>
		  	</div>
	  	</div>
		</div>
	</div>
</div>
<script>
$(document).on('click','#btnGuardarNuevaDireccion',function() {
	var boton = $(this),
		formulario = boton.parents('form'),
		continuar = true;
	formulario.find('.requerido').each(function(index,elemento) {
		if (! $(elemento).val()) {
			continuar = false;
			$(elemento).parents('.form-group').addClass('has-error');
		}
	});

	if (! continuar) {
		return false;
	}

	$.ajax({
		async : true,
		dataType: 'json',
		type : 'POST',
		url : webroot+'direcciones/ajax_guardar',
		data : formulario.serialize(),
		success: function(respuesta) {
			if (respuesta.estado == 'OK') {
				if (respuesta.mensaje) {
					$('#ProductoConfirmarForm select#ProductoDireccionId[rel="direccionDespacho"]').html('<option value="">- seleccione direccion de despacho</option>');
					$.each(respuesta.mensaje,function(index, direccion) {
						$('#ProductoConfirmarForm select#ProductoDireccionId[rel="direccionDespacho"]').append('<option value="'+direccion.Direccion.id+'">'+direccion.Direccion.nombre+'</option>');
					});
				}
				formulario.find('.alert[rel="exito"]').slideDown(300,function() {
					setTimeout(function() {
						formulario.find('.alert[rel="exito"]').slideUp(300);
					},3000);
				});
				formulario.get(0).reset();
			} else if (respuesta.mensaje) {
				formulario.find('.alert[rel="error"]').html(respuesta.mensaje).slideDown(300,function() {
					setTimeout(function() {
						formulario.find('.alert[rel="error"]').slideUp(300);
					},4000);
				});
			} else {
				formulario.find('.alert[rel="error"]').html('<i class="fa fa-warning"></i>Lo sentimos, no se ha podido guardar el registro. Por favor intentelo nuevamente').slideDown(300,function() {
					setTimeout(function() {
						formulario.find('.alert[rel="error"]').slideUp(300);
					},4000);
				});
			}
		}
	});

	return false;
});
</script>
<script>
$(document).ready(function(){
dataLayer.push({
     'event': 'checkoutOption',
     'ecommerce': {
         'checkout_option': {
             'actionField': {'step':2}
         }
     }
});

});
</script>