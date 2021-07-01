	<style type="text/css" media="all">
		div.campo-obligatorio::after {
			content: "(*)";
			position: absolute;
			right: -5px;
			top: 3px;
			opacity: 0.5;
		}
		.pac-container {
			width: auto !important;

		}
	
	</style>

	<?php 
	//AIzaSyDqNvgjfslMLyaklUHaKwYN2qq1GvfLVRI  --Viejon
	//AIzaSyB10_34KGW21dLk9CSyNjsEejVRyvX8Wvc -- EXPBOX
	//AIzaSyD1lR6__TNRJefnJoAgbpnqYjPe0ktcBs0
	//AIzaSyAm_7G-p-jk9za2QLrMSIa5reb7rCo_wAM  ---- San Cirilo
	//AIzaSyD6KaMr1mtkupXAMcbajjI9GrtR8K-A2O8 -- Andain

	?>
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD6KaMr1mtkupXAMcbajjI9GrtR8K-A2O8&libraries=places&sessiontoken=<?php echo md5(rand(0,1000000).time());?>"></script>
	<?php
	function quitar_tildes ($cadena) 
	{ 
		$cadBuscar = array("á", "Á", "é", "É", "í", "Í", "ó", "Ó", "ú", "Ú"); 
		$cadPoner = array("a", "A", "e", "E", "i", "I", "o", "O", "u", "U"); 
		$cadena = str_replace ($cadBuscar, $cadPoner, $cadena); 
		return $cadena; 
	} 
	?>
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
					  <div class="progress-bar progress-bar-primary relative" style="width: 20%;">
					    <span class="icon-skechers">
						 <div class="icono">
						   <img src="<?= $this->Html->url('/img/bootstraps/icon-skechers-wizard.png'); ?>" width="60">
						  </div>
						  <div class="texto">
						     Despacho
						   </div>
						  </span>
						</div>

					<div class="progress-bar relative progress-bar-danger" style="width: 20%">
					  <span class="icon-skechers">
						 <div class="icono">
						  <img src="<?= $this->Html->url('/img/bootstraps/icon-skechers-wizard-disabled.png'); ?>" width="60">
							 </div>
						 <div class="texto text-disabled">
						    Confirmar
						 </div>
					 </span>
					 </div>

					 <div class="progress-bar relative progress-bar-danger" style="width: 20%">
					     <span class="icon-skechers">
									<div class="icono">
										<img src="<?= $this->Html->url('/img/bootstraps/icon-skechers-wizard-disabled.png'); ?>" width="60">
									</div>
									<div class="texto text-disabled">
										Pago
									</div>
								</span>
							</div>

							<div class="progress-bar relative progress-bar-danger" style="width: 20%">
								<span class="icon-skechers">
									<div class="icono">
										<img src="<?= $this->Html->url('/img/bootstraps/icon-skechers-wizard-disabled.png'); ?>" width="60">
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
					<div class="col-md-12">
						<ul class="nav nav-tabs nav-tabs-skechers" role="tablist">
							<li role="presentation" class="active">
								<a href="#domicilio" aria-controls="messages" role="tab" data-toggle="tab">Despacho a Domicilio<br><h5>Plazo máximo de entrega 10 días hábiles</h5></a>
							</li>
							<li role="presentation">
								<a href="#skechers" aria-controls="messages" role="tab" data-toggle="tab">Retiro en Tienda Skechers<h5>Plazo máximo de entrega 4 días hábiles</h5></a>
							</li>
							<li role="presentation">
								<a href="#chilexpress" aria-controls="messages" role="tab" data-toggle="tab">Retiro Oficina Chilexpress<h5>Plazo máximo de entrega 10 días hábiles</h5></a>
							</li>
							 
						</ul>
						<div class="tab-content tab-content-skechers">
							<div role="tabpanel" class="tab-pane active" id="domicilio">
							
								<div class="well well-sm well-skechers">
									<?= $this->Form->create('Producto', array('action' => 'confirmar', 'class' => 'form-horizontal')); ?>
									<diV>
										<div class="form-group">
											<label for="inputPassword3" class="col-sm-4 control-label">Direcciones:</label>
											<div class="col-sm-7">
												<?= $this->Form->input('direccion_id', array('type' => 'select',
													'options' => array($direcciones,-1 => 'Crear Nueva Dirección'),
													'empty' => '- seleccione direccion de despacho',
													'class' => 'form-control',
													'rel' => 'direccionDespacho',
													'div' => false,
													'label' => false)); ?>
											</div>
										</div>
										<div class="row" style="display: none" id="domicilio2">
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
															<td>Depto/Casa:</td>
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
															<td>Teléfono:</td>
															<td rel="direccionTelefono"></td>
														</tr>
														<tr>
															<td>Celular:</td>
															<td rel="direccionCelular"></td>
														</tr>
														<tr>
															<td>Entregar a:</td>	
															<td><div class="form-group">
																	<div class="col-sm-6">
																		<?= $this->Form->input('nombre', array('placeholder' => 'Nombre ....',  'div' => false, 'label' => false, "class"=>"form-control")); ?>
																	</div>
                                                                    <div class="col-sm-6">
                                                                        <?= $this->Form->input('apellido', array('placeholder' => 'Apellido ...',  'div' => false, 'label' => false, "class"=>"form-control")); ?>
                                                                    </div>
																</div>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
											<div class="row">
												<div class="col-md-offset-3 col-md-6">
													<a href="<?= $this->Html->url(array('controller' => 'usuarios','action' => 'perfil_datos', '?' => array('tab' => 'direcciones'))); ?>" class="btn btn-primary btn-block">Editar direccion</a>
												</div>
											</div>
											<div class="row continuar-footer">
												<div class="col-md-6 col-md-offset-3">
													<a href="#" id="btnContinuar" class="btn btn-warning btn-block btn-lg disabled"><span>Continuar</span></a>
												</div>
											</div>
										</div>
									</diV>
									<?= $this->Form->end(); ?>
								</div>
								<div class="well well-sm well-skechers"  style="display: none" id="nueva">
									<?= $this->Form->create('Direccion', array('url' => '#','class' => 'form-horizontal','inputDefaults' => array('class' => 'form-control requerido', 'div' => false, 'label' => false, 'autocomplete' => false))); ?>
									<h4 class="titulo-pago">Crea una Nueva Dirección:</h4>
									<div class="alert alert-warning" rel="error" style="display: none;"></div>
									<div class="alert alert-success" rel="exito" style="display: none;">Se ha añadido una nueva dirección a su listado.</div>
									<div class="form-group">
										<label for="inputEmail3" class="col-sm-4 control-label">Dirección Completa</label>
										<div class="col-sm-7 campo-obligatorio">
											<?= $this->Form->input('calle', array('placeholder' => 'Nombre de calle, número...','maxlength'=>'40',  'autocomplete'=>false)); ?>
										</div>
										<div class="col-sm-7 col-sm-offset-5">Debe seleccionar su direccion del listado desplegable</div>
									</div>

									<div id="resto" style="display: none">
										<div class="form-group">
											<label for="inputPassword3" class="col-sm-4 control-label">Numero</label>
											<div class="col-sm-7 campo-obligatorio">
												<?= $this->Form->input('numero', array('placeholder' => '','maxlength'=>'5',																				'disabled' => 'disabled')); ?>
											</div>
										</div>
										<div class="form-group">
											<label for="inputPassword3" class="col-sm-4 control-label">Depto/Casa</label>
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
												<?= $this->Form->input('region_id', array('empty' => 'Region',
																							//'disabled' => 'disabled',
												'options' => quitar_tildes($regiones))); ?>
											</div>
										</div>
										<div class="form-group">
											<label for="inputPassword3" class="col-sm-4 control-label">Comuna:</label>
											<div class="col-sm-7 campo-obligatorio">
												<?= $this->Form->input('comuna_id', array('empty' => 'Comuna',
																							//'disabled' => 'disabled',
												'options' => quitar_tildes($comunas))); ?>
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
										<div class="col-sm-offset-4 col-sm-8">
											<button type="button" id="btnGuardarNuevaDireccion" class="btn btn-primary">Guardar dirección</button>
										</div>
								</div>
								<?= $this->Form->end(); ?>
							</div>




					</div>
			

					<div role="tabpanel" class="tab-pane " id="chilexpress">
					
						<?= $this->Form->create('Producto', array('id' => 'chilexpressForm', 'action' => 'confirmar', 'class' => 'form-horizontal')); ?>
						<div class="form-group">
							<label for="inputPassword3" class="col-sm-4 control-label">Seleccione Region Retiro:</label>
							<div class="col-sm-7">
								<?= $this->Form->input('region', array('type' => 'select',
									'options' => $regiones,
									'empty' => '- seleccione Region Retiro',
									'class' => 'form-control',
									'rel' => 'region',
									'id' => 'region',
									'div' => false,
									'label' => false)); ?>
								</div>
							</div>
							<div class="form-group">
								<label for="inputPassword3" class="col-sm-4 control-label">Seleccione Comuna Retiro:</label>
								<div class="col-sm-7">
									<?= $this->Form->input('comuna', array('type' => 'select',
										'options' => array(),
										'empty' => '- seleccione Comuna Retiro',
										'class' => 'form-control',
										'rel' => 'comuna',
										'id' => 'comuna',
										'div' => false,
										'disabled' => true,
										'label' => false)); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="inputPassword3" class="col-sm-4 control-label">Seleccione Tienda Retiro:</label>
									<div class="col-sm-7">
										<?= $this->Form->input('retiro_id', array('type' => 'select',
											'options' => array(),
											'empty' => '- seleccione Tienda Retiro',
											'class' => 'form-control',
											'rel' => 'retiro',
											'id' => 'retiro',
											'div' => false,
											'disabled' => true,
											'label' => false)); ?>
										</div>
									</div>

									
									<div class="row">
										<div class="col-md-12">
											<table id="tablaDatos" class="table table-striped table-hover">
												<tbody>

													<tr>
														<td>Direccion:</td>
														<td id="tiendaDireccion"></td>
													</tr>

													<tr>
														<td>Región:</td>
														<td id="tiendaRegion"></td>
													</tr>
													<tr>
														<td>Comuna:</td>
														<td id="tiendaComuna"></td>
													</tr>
													<tr>
													<td>Su Telefono:</td>	
															<td><div class="form-group">
																	<div class="col-sm-7">
																		<?= $this->Form->input('telefono', array('placeholder' => 'Su telefono',  'div' => false, 'label' => false, "class"=>"form-control")); ?>

																	</div>
																</div>
															</td>
														</tr>
															<tr>
															<td>Entregar a:</td>	
															<td><div class="form-group">
																	<div class="col-sm-6">
																		<?= $this->Form->input('nombre', array('placeholder' => 'Nombre a...',  'div' => false, 'label' => false, "class"=>"form-control nombre2")); ?>

																	</div>
                                                                    <div class="col-sm-6">
                                                                        <?= $this->Form->input('apellido', array('placeholder' => 'Apellido ...',  'div' => false, 'label' => false, "class"=>"form-control apellido2")); ?>
                                                                    </div>
																</div>
															</td>
														</tr>


												</tbody>
											</table>
										</div>
										<div class="col-md-6 col-md-offset-3">
											<a href="#" id="btnContinuar2" class="btn btn-warning btn-block btn-lg disabled"><span>Continuar</span></a>
										</div>
									</div>
									<?= $this->Form->end(); ?>


								</div>

						<div role="tabpanel" class="tab-pane " id="skechers">
						<div class="col-md-12">
						<div class="well well-sm">
									<p class="text-center"><b>Tu producto estará listo para retiro en un plazo máximo de 4 días hábiles, previa confirmación por email</b></p>
							</div>
						</div>


						<?= $this->Form->create('Producto', array('id' => 'SkechersForm', 'action' => 'confirmar', 'class' => 'form-horizontal')); ?>
						<div class="form-group">
							<label for="inputPassword3" class="col-sm-4 control-label">Seleccione Region Retiros:</label>
							<div class="col-sm-7">
								<?= $this->Form->input('region', array('type' => 'select',
									'options' => $regiones_tiendas,
									'empty' => '- seleccione Region Retiro',
									'class' => 'form-control',
									'rel' => 'regionSkechers',
									'id' => 'regionSkechers',
									'div' => false,
									'label' => false)); ?>
								</div>
							</div>
							<div class="form-group">
									<label for="inputPassword3" class="col-sm-4 control-label">Seleccione Tienda Retiro:</label>
									<div class="col-sm-7">
										<?= $this->Form->input('retiro_id', array('type' => 'select',
											'options' => array(),
											'empty' => '- seleccione Tienda Retiro',
											'class' => 'form-control',
											'rel' => 'retiroSkechers',
											'id' => 'retiroSkechers',
											'div' => false,
											'disabled' => true,
											'label' => false)); ?>
										</div>
									</div>

									
									<div class="row">
										<div class="col-md-12">
											<table id="tablaDatos" class="table table-striped table-hover">
												<tbody>

													<tr>
														<td>Direccion:</td>
														<td id="tiendaDireccion2"></td>
													</tr>

													<tr>
														<td>Región:</td>
														<td id="tiendaRegion2"></td>
													</tr>
													<tr>
														<td>Comuna:</td>
														<td id="tiendaComuna2"></td>
													</tr>
													<tr>
													<td>Su Telefono:</td>	
															<td><div class="form-group">
																	<div class="col-sm-7">
																		<?= $this->Form->input('telefono', array('placeholder' => 'Su telefono', 'id' => 'telefonoSkechers', 'div' => false, 'label' => false, "class"=>"form-control")); ?>

																	</div>
																</div>
															</td>
														</tr>
															<tr>
													<td>Entregar a:</td>	
																<td><div class="form-group">
																	<div class="col-sm-5">
																		<?= $this->Form->input('nombre', array('placeholder' => 'Nombre a...','id' => 'entregaSkechers',  'div' => false, 'label' => false, "class"=>"form-control nombre2")); ?>

																	</div>
                                                                    <div class="col-sm-5">
                                                                        <?= $this->Form->input('apellido', array('placeholder' => 'Apellido ...', 'id' => 'entregaSkechers2' , 'div' => false, 'label' => false, "class"=>"form-control apellido2")); ?>
                                                                    </div>
																</div>
															</td>
														</tr>


													

													<tr>
													<td>Rut<br> (Sin puntos y con Guion)</td>	
															<td><div class="form-group">
																	<div class="col-sm-7">
																		<?= $this->Form->input('rut', array('placeholder' => 'Rut de quien retira', 'id' => 'rutSkechers', 'div' => false, 'label' => false, "class"=>"form-control")); ?>

																	</div>
																</div>
															</td>
														</tr>



												</tbody>
											</table>
										</div>
										<div class="col-md-6 col-md-offset-3">
											<a href="#" id="btnContinuar3" class="btn btn-warning btn-block btn-lg disabled"><span>Continuar</span></a>
										</div>
									</div>
									<?= $this->Form->end(); ?>


								</div>





					</div>
				</div>


			</div>

		</div>
	</div>
</div>
</div>
<script>
	$(document).on('click','#btnGuardarNuevaDireccion',function() {
		$("#DireccionNumero").prop('disabled', false);
		$("#DireccionRegionId").prop('disabled', false);
		$("#DireccionComunaId").prop('disabled', false);

		var boton = $(this),
		formulario = boton.parents('form'),
		continuar = true;
		formulario.find('.requerido').each(function(index,elemento) {
			if (! $(elemento).val()) {
				$("#DireccionNumero").prop('disabled', true);
				$("#DireccionRegionId").prop('disabled', true);
				$("#DireccionComunaId").prop('disabled', true);
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
						setTimeout(function() {
							$('#nueva').hide();
							var num = $('#ProductoDireccionId option').length;
							$('#ProductoDireccionId').prop('selectedIndex', num-1);
							datosDireccion($('#ProductoDireccionId').val());
						},3100);
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
		$("#DireccionNumero").prop('disabled', true);
		$("#DireccionRegionId").prop('disabled', true);
		$("#DireccionComunaId").prop('disabled', true);

		return false;
	});

</script>
<script>
	$(document).on('change','select[rel="direccionDespacho"]',function() {
		var direccionId = $(this).val();
		console.log( direccionId);
		$('#btnContinuar').addClass('disabled');
		if (! direccionId)
			return false;
		datosDireccion(direccionId);
		$('#btnContinuar').removeClass('disabled');
		return false;
	});

	if ($('#ProductoDireccionId').length && $('#ProductoConfirmarForm #ProductoDireccionId').val()) 
	{
		console.log('aca');
		datosDireccion($('#ProductoConfirmarForm #ProductoDireccionId').val());
		$('#btnContinuar').removeClass('disabled');
	}
	$(document).ready(function(){
	var Fn = {
        // Valida el rut con su cadena completa "XXXXXXXX-X"
        validaRut : function (rutCompleto) {
            rutCompleto = rutCompleto.replace("‐","-");
            if (!/^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test( rutCompleto ))
                return false;
            var tmp 	= rutCompleto.split('-');
            var digv	= tmp[1];
            var rut 	= tmp[0];
            if ( digv == 'K' ) digv = 'k' ;

            return (Fn.dv(rut) == digv );
        },
        dv : function(T){
            var M=0,S=1;
            for(;T;T=Math.floor(T/10))
                S=(S+T%10*(9-M++%6))%11;
            return S?S-1:'k';
        }
    }


		$('#btnContinuar2').click(function(){
			retiro_id = $('#retiro').val();
			if(retiro_id != 0)
			{
				$('#chilexpressForm').submit();
			}
		})
		$('#btnContinuar3').click(function(){

			retiro_id = $('#retiroSkechers').val();
			telefono = $('#telefonoSkechers').val();
			nombre = $('#entregaSkechers').val();
			rut = $('#rutSkechers').val();
			apellido = $('#entregaSkechers2').val();
			if(telefono =='')
			{
				alert('Debe ingresar el Telefono de quien retirara el producto');
				return false;
			}
			if(nombre =='')
			{
				alert('Debe ingresar el Nombre de quien retirara el producto');
				return false;
			}
			if(apellido =='')
			{
				alert('Debe ingresar el Apellido de quien retirara el producto');
				return false;
			}
			if(rut =='')
			{
				alert('Debe ingresar el rut de quien retirara el producto');
				return false;
			}
			if (!Fn.validaRut( rut))
			{
				alert('Debe ingresar in Rut Valido');
				return false;
			}
			if(retiro_id != 0)
			{
				$('#SkechersForm').submit();
			}
		})
		$('#comuna').change(function(){
			console.log($('#region').val());
		})
		$('#comuna').change(function(){
			id = $("#comuna").val();
			$('#retiro').attr('disabled', true);
			$('#retiro').find('option').remove().end();
			$.ajax(
			{
				async	: false,
				type		: 'GET',
				url		: webroot + 'retiros/ajax_retiros/'+id+'/2',
				success: function( respuesta ) 
				{
					respuesta= JSON.parse(respuesta);
					console.log(respuesta);
					console.log(jQuery.isEmptyObject(respuesta) );
					if(!jQuery.isEmptyObject(respuesta))
					{
						$('#retiro').attr('disabled', false);
						$("#retiro").append('<option value=0>--Seleccione Sucursal--</option>');
						$.each(respuesta,function(key, registro) {
							$("#retiro").append('<option value='+key+'>'+registro+'</option>');
						}); 
					}else{
						$("#retiro").append('<option value=0>Comuna sin Oficinas de Despacho</option>');
					}                                        
					
				}
			});

		})

		$('#region').change(function(){
			id = $("#region").val();
			$('#comuna').attr('disabled', true);
			$('#retiro').attr('disabled', true);
			$('#comuna').find('option').remove().end();
			$.ajax(
			{
				async	: false,
				type		: 'GET',
				url		: webroot + 'retiros/ajax_comunas/'+id,
				success: function( respuesta ) 
				{
					respuesta= JSON.parse(respuesta);
					$('#comuna').attr('disabled', false);
					$("#comuna").append('<option value=0>--Seleccione Comuna--</option>');
					$.each(respuesta,function(key, registro) {
						$("#comuna").append('<option value='+key+'>'+registro+'</option>');                                          
					}); 
					$('#btnContinuar2').addClass('disabled');
				

				}
			});

		})


		$('#regionSkechers').change(function(){
			id = $("#regionSkechers").val();
			$('#retiroSkechers').attr('disabled', true);
			$('#retiroSkechers').find('option').remove().end();
			$.ajax(
			{
				async	: false,
				type		: 'GET',
				url		: webroot + 'retiros/ajax_retirosRegion/'+id+'/3',
				success: function( respuesta ) 
				{
					respuesta= JSON.parse(respuesta);
					console.log(respuesta);
					console.log(jQuery.isEmptyObject(respuesta) );
					if(!jQuery.isEmptyObject(respuesta))
					{
						$('#retiroSkechers').attr('disabled', false);
						$("#retiroSkechers").append('<option value=0>--Seleccione Sucursal--</option>');
						$.each(respuesta,function(key, registro) {
							$("#retiroSkechers").append('<option value='+key+'>'+registro+'</option>');
						}); 
					}else{
						$("#retiroSkechers").append('<option value=0>Region sin Tiendas para Retirar</option>');
					}                                        
					
				}
			});

		})
		$('#retiro').change(function(){
			id = $("#retiro").val();

			$.ajax(
			{
				async	: false,
				type		: 'GET',
				url		: webroot + 'retiros/ajax_retiro_info/'+id,
				success: function( respuesta ) 
				{
					respuesta= JSON.parse(respuesta);
					console.log(respuesta);
					$('#tiendaDireccion').html(respuesta.Retiro.calle+' '+respuesta.Retiro.numero + ' '+respuesta.Retiro.extra);
					$('#tiendaTelefono').html(respuesta.Retiro.telefono);
					$('#tiendaComuna').html(respuesta.Comuna.nombre);
					$('#tiendaRegion').html(respuesta.Region.nombre);
					$('#btnContinuar2').removeClass('disabled');


				}
			});

		})
		$('#retiroSkechers').change(function(){
			id = $("#retiroSkechers").val();

			$.ajax(
			{
				async	: false,
				type		: 'GET',
				url		: webroot + 'retiros/ajax_retiro_info/'+id,
				success: function( respuesta ) 
				{
					respuesta= JSON.parse(respuesta);
					console.log(respuesta);
					$('#tiendaDireccion2').html(respuesta.Retiro.calle+' '+respuesta.Retiro.numero + ' '+respuesta.Retiro.extra);
					$('#tiendaTelefono2').html(respuesta.Retiro.telefono);
					$('#tiendaComuna2').html(respuesta.Comuna.nombre);
					$('#tiendaRegion2').html(respuesta.Region.nombre);
					$('#btnContinuar3').removeClass('disabled');


				}
			});

		})


		$("form").keypress(function(e) {
			console.log(e);
			if (e.which == 13) {
				event.preventDefault();
				return false;
			}
		});
		var calle,numero,comuna,region;
		autocomplete = new google.maps.places.Autocomplete(
			document.getElementById('DireccionCalle'), {
				types: ['address'],
				componentRestrictions: {country: 'CL'},
				fields: ["name","address_components"]



			}
			);
		autocomplete.addListener('place_changed', fillInAddress);
		function fillInAddress() {
			numero = null;
			calle=null;
			comuna = null;
			var respuesta = autocomplete.getPlace();
			console.log(respuesta);

			$.each(respuesta.address_components,function(index, valor)
			{
				$('#DireccionNumero').attr('disabled', true)
				console.log(valor);
				if(valor['types'][0]=='street_number')
				{
					numero = valor['long_name'];
				}else if(valor['types'][0]=='route')
				{
					calle = valor['long_name'];
				}else if(valor['types'][0]=="administrative_area_level_3")
				{
					comuna = valor['long_name'];
				}else if(valor['types'][0]=='administrative_area_level_1')
				{
					region = valor['long_name'];
				}
			})
			if(numero == null)
			{
				$('#DireccionCalle').val('');
				$('#DireccionNumero').val('');
				$('#resto').show();
				$('#DireccionNumero').attr('disabled', false)
				alert('Por favor Completar campos obligatorios');
			}else{
				$('#DireccionNumero').val(numero);
			}
			$('#resto').show();
			$('#DireccionCalle').val(calle);

		 				 //region = 'Region de Tarapaca';
		 				 $('select[name="data[Direccion][region_id]"]').find('option:contains("'+normalize(region)+'")').attr("selected",true);
		 				 if($('select[name="data[Direccion][comuna_id]"]').find('option:contains("'+normalize(comuna)+'")').length == 0)
		 				 	alert('Su Comuna no se encuentra dentro de las zonas de Despacho')
		 				 else
		 				 	$('select[name="data[Direccion][comuna_id]"]').find('option:contains("'+normalize(comuna)+'")').attr("selected",true);


		 				}


		 				var normalize = (function() {
		 					var from = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÇç", 
		 					to   = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuucc",
		 					mapping = {};

		 					for(var i = 0, j = from.length; i < j; i++ )
		 						mapping[ from.charAt( i ) ] = to.charAt( i );

		 					return function( str ) {
		 						var ret = [];
		 						for( var i = 0, j = str.length; i < j; i++ ) {
		 							var c = str.charAt( i );
		 							if( mapping.hasOwnProperty( str.charAt( i ) ) )
		 								ret.push( mapping[ c ] );
		 							else
		 								ret.push( c );
		 						}      
		 						return ret.join( '' );
		 					}

		 				})();
		 				$('#seleccion').change(function(){
		 					if($(this).val() == 'domicilio')
		 					{
		 						$('#domicilio').show()
		 						$('#domicilio2').hide()

		 						$('#tienda').hide();
		 						$('#btnContinuar').addClass('disabled')
		 						$("#ProductoDireccionId").val($("#target option:first").val());

		 					}else{
		 						$('#tienda').show()
		 						$('#domicilio').hide();
		 						$('#nueva').hide();
		 						$('#btnContinuar').addClass('disabled')

		 					}
		 				});
	/*  $('#tienda_id').change(function(){
	  		if(!isNaN($(this).val()))
	  		{
	  			$('#btnContinuar').removeClass('disabled')
	  		}else{
	  			$('#btnContinuar').addClass('disabled')
	  		}			
	  	}) */

        $('#ProductoNombre').keyup(function(){
            apellido = $('#ProductoApellido').val();
            if(!isNaN($(this).val()) || !isNaN(apellido))
            {
                $('#btnContinuar').addClass('disabled')
            }else{
                $('#btnContinuar').removeClass('disabled')
            }
            if(!isNaN($(this).val()) && !isNaN(apellido))
                $('#btnContinuar').removeClass('disabled')
        })

        $('#ProductoApellido').keyup(function(){
            nombre = $('#ProductoNombre').val();
            if(!isNaN($(this).val()) || !isNaN(nombre))
            {
                $('#btnContinuar').addClass('disabled')
            }else{
                $('#btnContinuar').removeClass('disabled')
            }
	        if(!isNaN($(this).val()) && !isNaN(nombre))
                $('#btnContinuar').removeClass('disabled')
        })

        //retirar en
        $('.nombre2').keyup(function(){
            apellido = $('.apellido2').val();
            select = $('#retiro option:selected').val();

            if(!isNaN($(this).val()) || !isNaN(apellido))
            {
                $('#btnContinuar2').addClass('disabled')
            }else{
                if(!isNaN(select))
                    $('#btnContinuar2').removeClass('disabled')
            }
            if(!isNaN($(this).val()) && !isNaN(apellido) && !isNaN(select))
                $('#btnContinuar2').removeClass('disabled')
        })

        $('.apellido2').keyup(function(){
            nombre = $('.nombre2').val();
            select = $('#retiro option:selected').val();

            if(!isNaN($(this).val()) || !isNaN(nombre))
            {
                $('#btnContinuar2').addClass('disabled')
            }else{
                if(!isNaN(select))
                    $('#btnContinuar2').removeClass('disabled')
            }
            if(!isNaN($(this).val()) && !isNaN(nombre) &&  !isNaN(select))
                $('#btnContinuar2').removeClass('disabled')
        })





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