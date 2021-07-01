<h1 class="titulo"><? __('Ingresar Despacho');?></h1>
<?= $this->Form->create('Compra', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
<ul class="edit">
	<li><?= $this->Form->input('id'); ?></li>
	<li><label class="texto">Picking Number</label><?= (isset($this->data['Compra']['picking_number']) && $this->data['Compra']['picking_number']) ? $this->data['Compra']['picking_number'] : ''; ?></li>
	<li>
		<? if (isset($this->data['Compra']['boleta'])) : ?>
		<?= $this->Form->input('boleta', array('maxlength' => '6',
											   'readonly' => 'readonly')); ?>
		</li><li>
		<?= $this->Form->input('n_boleta', array('maxlength' => '6',
												 'label' => array('class' => 'texto',
																  'text' => 'Nuevo nº boleta'))); ?>
		<? else : ?>
		<?= $this->Form->input('boleta', array('maxlength' => '6')); ?>
	</li>
	<? endif; ?>
	<li>
		<? if (isset($this->data['Compra']['cod_despacho'])) : ?>
		<?= $this->Form->input('cod_despacho', array('maxlength' => '12',
													 'readonly' => 'readonly')); ?>
		</li><li>
		<?= $this->Form->input('n_cod_despacho', array('maxlength' => '12',
													   'label' => array('class' => 'texto',
																		'text' => 'Nuevo codigo de despacho'))); ?>
		<? else : ?>
		<?= $this->Form->input('cod_despacho', array('maxlength' => '12')); ?>
		<? endif; ?>
	</li>
	<li><?= $this->Form->input('rural'); ?></li>
	<li class="dir-rural" style="display: none;"><?= $this->Form->input('direccion_rural'); ?></li>
	<li><?= $this->Form->input('local', array('label' => array('text' => 'Direccion Local', 'class' => 'texto'))); ?></li>
</ul>
<div class="botones">
	<a href="#" id="despacho-si" class="despachar-compra"><span class="guardar">Despachar</span></a>
</div>
<?= $this->Form->end(); ?>
<script>
$('a[rel="cambiar-boleta"]').click(function(e) {
	e.preventDefault();
	$('.modal[rel="cambio-boleta"]').fadeIn(400);
});
$('a.btn[rel="guardar-nueva-boleta"]').click(function(e) {
	e.preventDefault();
	var formulario = $(this).parents('form');
	if (! confirm('El nº de boleta asociado a la compra #<?= $compra['Compra']['id']; ?> va a ser modificado. ¿Desea continuar?')) {
		$('.modal[rel="cambio-boleta"]').fadeOut(400);
		return false;
	}
	$.ajax({
		async	: false,
		type	: 'POST',
		url: webroot + "compras/ajax_cambiar_boleta",
		data: formulario.serialize(),
		success: function(respuesta) {
			if (respuesta == 'OK') {
				var nuevo = $('#BoletaAdminSiForm #BoletaNuevo').val();
				$('#CompraAdminSiForm #CompraBoleta').val(nuevo);
				$('#BoletaAdminSiForm #BoletaActual').val(nuevo);
				$('#BoletaAdminSiForm #BoletaNuevo').val('');
			} else {
				alert('Se ha producido un problema al intentar cambiar el nº de boleta. Por favor intentelo nuevamente.');
			}
		},
		complete: function() {
			$('.modal[rel="cambio-boleta"]').fadeOut(400);
		},
		error: function() {
			alert('Se ha producido un problema al intentar cambiar el nº de boleta. Por favor intentelo nuevamente.');
		}
	});
	
});
// FORMULARIO INGRESAR DESPACHO
$('#despacho-si.despachar-compra').live('click', function(evento)
{
	evento.preventDefault();
	var boleta				= $('#CompraBoleta').val(),
		codigo_despacho		= $('#CompraCodDespacho').val(),
		rural				= false,
		local				= false,
		direccion_rural		= $('#CompraDireccionRural').val(),
		mensaje 			= '',
		acceso				= true;

	// CAPTA EL ESTADO DEL CHECK LOCAL
	if ($('#CompraLocal').is(':checked') ) {
		local = true;
	}

	// CAPTA EL ESTADO DEL CHECK RURAL
	if ($('#CompraRural').is(':checked') ) {
		rural = true;
	}

	// VALIDA EL INGRESO DE UNA BOLETA
	if (! boleta ) {
		acceso = false;
		mensaje += 'ingresar Boleta';
	}

	/** VALIDA EL INGRESO DE UN CODIGO DE DESPACHO
	 * verifica que tenga 10 digitos
	 * verifica que se ingrese un dato
	 */
	if (! local ) {
		if ( codigo_despacho ) {
			if ( codigo_despacho.length < 10 ) {
				if (! $('#CompraCodDespacho').is('[readonly]')) {
					acceso = false;
					if ( mensaje ) {
						mensaje += ', ingresar un Codigo de Despacho de 10 digitos';
					} else {
						mensaje += 'ingresar un Codigo de Despacho de 10 digitos';
					}
				}
			}
		} else {
			acceso = false;
			if ( mensaje ) {
				mensaje += ', ingresar Codigo de Despacho';
			} else {
				mensaje += 'ingresar Codigo de Despacho';
			}
		}
	}

	if ( rural && ! direccion_rural ) {
		acceso = false;
		if ( mensaje ) {
			mensaje += ', ingresar una Direccion Rural';
		} else {
			mensaje += 'ingresar una Direccion Rural';
		}
	}

	if ( acceso ) {
		$('#CompraAdminSiForm').submit();
	} else {
		alert('Para continuar debes:\n' + mensaje + '.');
	}
	return false;
});
</script>