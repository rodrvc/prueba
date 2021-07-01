<h1 class="titulo"><? __('Ingresar Picking Number');?></h1>
<?= $this->Form->create('Compra', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
<ul class="edit">
	<li><?= $this->Form->input('id'); ?></li>
	<li><?= $this->Form->input('picking_number'); ?></li>
</ul>
<div class="botones">
	<a href="#" rel="pickingSave"><span class="guardar">Guardar</span></a>
</div>
<?= $this->Form->end(); ?>
<script>
$('a[rel="pickingSave"]').click(function(e) {
	e.preventDefault();
	var	boton 		= $(this),
		formulario 	= boton.parents('form'),
		campo 		= document.getElementById("CompraPickingNumber"),
		id 			= document.getElementById("CompraId").value,
		valor 		= campo.value;
	if (! id)
		return false;
	if (! valor) {
		alert('Debe ingresar un picking number');
		return false;
	}
	//if (isNaN(valor)) {
	//	alert('Solamente puede ingresar números');
	//	return false;
	//}
	if ( confirm ('Va a asociar el picking number '+valor+' a la compra Nº'+id+'. ¿Desea continuar?') ) {
		$.ajax({
			async	: false,
			type		: 'POST',
			url: webroot + "compras/ajax_picking_save",
			data: formulario.serialize(),
			beforeSend: function() {
				formulario.css('opacity','0.3');
				boton.addClass('disabled');
				$(campo).attr('disabled',true);
			},
			success: function( respuesta ) {
				if (respuesta) {
					if (respuesta == 'OK') {
						location.href = '<?= $this->Html->url(array('controller' => 'compras', 'action' => 'pagadas')); ?>';
					} else {
						alert(respuesta);
						formulario.css('opacity','1');
						boton.removeClass('disabled');
						$(campo).removeAttr('disabled');
					}
				} else {
					alert('Se ha producido un problema al intentar guardar el registro.');
					formulario.css('opacity','1');
					boton.removeClass('disabled');
					$(campo).removeAttr('disabled');
				}
			}
		});
	}
});
</script>
