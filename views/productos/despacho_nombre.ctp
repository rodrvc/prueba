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
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdE6Bi4R59SSjEllVoA7IUnzKdeHWU5Jw&libraries=places"></script>
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
				<div class="col-md-12">
					<div class="well well-sm well-skechers">
						<?= $this->Form->create('Producto', array('action' => 'confirmar', 'class' => 'form-horizontal')); ?>
						<!--	<h4 class="titulo-pago">Selecciona Tipo despacho: <select id="seleccion">
																					<option selected="selected" value="domicilio">Domicilio</option>
																					<option value="tienda">Retiro en tienda</option> 

																					</select>
							</h4> -->
						<diV id="domicilio">
							<div class="form-group">
								<label for="inputPassword3" class="col-sm-4 control-label">Direcciones:</label>
								<div class="col-sm-7">
									<?= $this->Form->input('direccion_id', array('type' => 'select',
																				 'options' => array($direcciones,-1 => 'Nueva Direccion'),
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
											<td>Teléfono:</td>
											<td rel="direccionTelefono"></td>
										</tr>
										<tr>
											<td>Celular:</td>
											<td rel="direccionCelular"></td>
										</tr>
										<tr>
											<td>Entregar a:</td>
											<td>
																<div class="form-group">
								<div class="col-sm-7">
												<?= $this->Form->input('nombre', array('placeholder' => 'Entregar a...',  'div' => false, 'label' => false, "class"=>"form-control")); ?>

								</div>
							</div>

											</td>
										</tr>
									</tbody>
								</table>
							</div>
								<div class="row">
							<div class="col-md-offset-3 col-md-6">
								<a href="<?= $this->Html->url(array('controller' => 'usuarios','action' => 'perfil_datos', '?' => array('tab' => 'direcciones'))); ?>" class="btn btn-primary btn-block">
									Editar direccion
								</a>
							</div>
							<!--<div class="col-md-6">
								<a href="<?= $this->Html->url(array('controller' => 'usuarios','action' => 'perfil_datos', '?' => array('tab' => 'direcciones'))); ?>" class="btn btn-primary btn-block">
									Agregar nueva dirección
								</a> 
							</div> -->
						</div>

							<div class="row continuar-footer">
		  		<div class="col-md-6 col-md-offset-3">
					<a href="#" id="btnContinuar" class="btn btn-warning btn-block btn-lg disabled"><span>Continuar</span></a>
		  		</div>
		  	</div>


						</div>
					
						</div>
						<div id="tienda" style="display: none">
							<div class="form-group">
								<label for="inputPassword3" class="col-sm-4 control-label">Seleccione Tienda Retiro:</label>
								<div class="col-sm-7">
									<?= $this->Form->input('tienda_id', array('type' => 'select',
																				 'options' => $tiendas,
																				 'empty' => '- seleccione Tienda Retiro',
																				 'class' => 'form-control',
																				 'rel' => 'tienda_id',
																				 'id' => 'tienda_id',
																				 'div' => false,
																				 'label' => false)); ?>
								</div>
							</div>

						
							<div class="row">
							<div class="col-md-12">
								<table id="tablaDatos" class="table table-striped table-hover">
									<tbody>
									
										<tr>
											<td>Direccion:</td>
											<td rel="tiendaDireccion"></td>
										</tr>
									
										<tr>
											<td>Región:</td>
											<td rel="tiendaRegion"></td>
										</tr>
										<tr>
											<td>Comuna:</td>
											<td rel="tiendaComuna"></td>
										</tr>
										
										<tr>
											<td>Teléfono:</td>
											<td rel="tiendaTelefono"></td>
										</tr>
									
									</tbody>
								</table>
							</div>
						</div>
						</div>
												<?= $this->Form->end(); ?>
												

					</div>
				</div>
				<div class="col-md-12 hidden-xs hidden-sm">
					<div class="well well-sm well-skechers"  style="display: none" id="nueva">
						<?= $this->Form->create('Direccion', array('url' => '#','class' => 'form-horizontal','inputDefaults' => array('class' => 'form-control requerido', 'div' => false, 'label' => false, 'autocomplete' => false))); ?>
							<h4 class="titulo-pago">Crea una direccion nueva:</h4>
							<div class="alert alert-warning" rel="error" style="display: none;"></div>
							<div class="alert alert-success" rel="exito" style="display: none;">Se ha añadido una nueva dirección a su listado.</div>
							<div class="form-group">
							<row class="col-sm-12">
								<label for="inputEmail3" class="col-sm-4 control-label">Direccion</label>
								<div class="col-sm-7 campo-obligatorio">
									<?= $this->Form->input('calle', array('placeholder' => 'Nombre de calle...','maxlength'=>'40',  'autocomplete'=>false)); ?>
								</div>
								<div class="col-sm-1 col-sm-offset-1">&nbsp;</div>
								</row>
								<div class="col-sm-offset-5">Debe seleccionar su direccion del listado desplegable</div>
							</div>


							<div class="form-group">
								<label for="inputPassword3" class="col-sm-4 control-label">Numero</label>
								<div class="col-sm-7 campo-obligatorio">
									<?= $this->Form->input('numero', array('placeholder' => '','maxlength'=>'5',																				'disabled' => 'disabled')); ?>
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
									<?= $this->Form->input('region_id', array('empty' => 'Region',
																				'disabled' => 'disabled',
																			  'options' => quitar_tildes($regiones))); ?>
								</div>
							</div>
							<div class="form-group">
								<label for="inputPassword3" class="col-sm-4 control-label">Comuna:</label>
								<div class="col-sm-7 campo-obligatorio">
									<?= $this->Form->input('comuna_id', array('empty' => 'Comuna',
																				'disabled' => 'disabled',
																			  'options' => quitar_tildes($comunas))); ?>
								</div>
							</div>
						
							<div class="form-group">
								<label for="inputPassword3" class="col-sm-4 control-label">Teléfono:</label>
								<div class="col-sm-7">
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
						<?= $this->Form->end(); ?>
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
$(document).ready(function(){
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
	 				 	alert('Debe ingresar la direccion completa');
	 				 	return false;
	 				}
	 				 $('#DireccionCalle').val(calle);
	 				 $('#DireccionNumero').val(numero);
	 				 console.log(comuna);
	 				 //region = 'Region de Tarapaca';
	 				 $('select[name="data[Direccion][region_id]"]').find('option:contains("'+normalize(region)+'")').attr("selected",true);
	 				 if($('select[name="data[Direccion][comuna_id]"]').find('option:contains("'+normalize(comuna)+'")').length == 0)
	 				 	alert('Su Comuna no se encuentra dentro de las zonas de Despacho')
	 				 else
	 				 	$('select[name="data[Direccion][comuna_id]"]').find('option:contains("'+normalize(comuna)+'")').attr("selected",true);


	 				 console.log(calle,numero,comuna,region);
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