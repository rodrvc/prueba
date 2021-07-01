<style>
#previsualizar {
	position: fixed; top: 0; 
	left: 0; 
	width: 100% !important; 
	height: 100%; 
	background-color: rgba(0, 0, 0, 0.56) !important;
}
#previsualizar .contenido {
	position: relative;
	width: 90%;
	margin-left: auto;
	margin-right: auto;
	height: auto;
	background-color: #FFF;
	border: 1px solid #444;
	border-radius: 12px;
}
#previsualizar .contenido h3 {
	text-align: center;
	padding: 30px 15px;
	border-bottom: 1px solid #ccc;
}
#previsualizar .contenido .iframe {
	height: 500px;
}
#previsualizar .contenido .pie {
	border-top: 1px solid #ccc;
	padding: 30px 15px;
}
#ruta {
	width: 100%; 
	/*border-top: 1px solid #ccc; */
	padding-top: 20px;
	/*margin-top: 80px;*/
}
</style>

<div class="col02">
	<?= $this->Form->create('Link', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Agregar Link'); ?></h1>
 	<div id="origen">
 		<ul class="edit">
			<?= $this->Form->hidden('controlador', array('value' => 'productos')); ?>
			<?= $this->Form->hidden('action', array('value' => 'grupos')); ?>
			<li>
				<label class="texto" style="text-transform: lowercase; font-size: large;">www.skechers.cl/productos/grupos/ </label>
				<?= $this->Form->input('parametro', array(
					'label' => false,
					'type' => 'select',
					'options' => $grupos,
					'style' => 'max-width: 150px;',
					'empty' => '- seleccione tag'
				)); ?>
			</li>
			<li>
				<?= $this->Form->input('filtrar', array(
					'label' => array(
						'text' => 'filtrar productos',
						'class' => 'texto'
					),
					'type' => 'checkbox'
				)); ?>
			</li>
		</ul>
		<ul id="filtrar" class="edit" style="display: none;">
			<li>
				<?= $this->Form->hidden('LinkParametro.0.parametro', array('value' => 'categoria')); ?>
				<?= $this->Form->input('LinkParametro.0.valor', array(
					'label' => array(
						'text' => 'Categoria',
						'class' => 'texto'
					),
					'type' => 'select',
					'options' => array(
						'mujer' => 'WOMEN',
						'hombre' => 'MEN',
						'nino' => 'BOYS',
						'nina' => 'GIRLS'
					),
					'empty' => '- seleccione categoria'
				)); ?>
			</li>
		</ul>
		<div class="botones">
			<a href="#" rel="previsualizar"><span class="buscar">Previsualizar Link</span></a>
		</div>
 	</div>
		
	<div id="ruta" style="display: none;">
		<div class="previsualizar">
			<ul>
				<li class="extendido"></li>
			</ul>
		</div>
		<ul class="edit">
			<li>
				<?= $this->Form->input('ruta', array(
					'label' => array(
						'text' => 'www.skechers.cl/',
						'class' => 'texto',
						'style' => 'text-transform: lowercase; font-weight: 600; font-size: large; text-align: right; width: auto;'
					)
				)); ?>
			</li>
			<li>
				<?= $this->Form->input('activo', array(
					'label' => array(
						'text' => 'Activar link',
						'class' => 'texto'
					)
				)); ?>
			</li>
			<li>
				<?= $this->Form->input('filtros', array(
					'label' => array(
						'text' => 'insertar filtros al link',
						'class' => 'texto'
					),
					'type' => 'checkbox'
				)); ?>
			</li>
		</ul>

		<ul id="filtros" class="edit" style="display: none;">
			<li>
				<?= $this->Form->input('LinkFiltro.0.nombre', array(
					'label' => array(
						'text' => 'Categorias',
						'class' => 'texto'
					),
					'type' => 'checkbox',
					'value' => 'categorias'
				)); ?>
			</li>
			<li>
				<?= $this->Form->input('LinkFiltro.1.nombre', array(
					'label' => array(
						'text' => 'Orden',
						'class' => 'texto'
					),
					'type' => 'checkbox',
					'value' => 'orden'
				)); ?>
			</li>
		</ul>


		<div class="botones">
			<a href="#" rel="guardar"><span class="guardar">Guardar</span></a>
			<a href="#" rel="deshacer"><span class="reload">Deshacer</span></a>
		</div>
	</div>
	<?= $this->Form->end(); ?>
</div>

<div id="previsualizar" style="display: none;">
	<div class="contenido">
		<h3>titulo</h3>
		<div class="iframe"></div>
		<div class="pie botones">
			<a href="#" rel="cerrar">
				<span class="borrar">cerrar</span>
			</a>
			<a href="#" rel="continuar">
				<span class="aceptar">continuar</span>
			</a>
		</div>
	</div>
</div>
<script>
$(document).ready(function() {
	$('#LinkFiltrar').change(function() {
		if ($(this).is(':checked')) {
			$('#filtrar').slideDown(300);
		} else {
			$('#filtrar').slideUp(300, function() {
				$('#LinkParametro0Valor').val(false);
			});
		}
	});

	$('a[rel="previsualizar"]').click(function(e) {
		e.preventDefault();
		var ruta = webroot + 'productos/grupos/';
		if (! $('#LinkParametro').val()) {
			alert('Seleccione un tag.');
			return false;
		}
		ruta+=$('#LinkParametro').val();
		if ($('#LinkFiltrar').is(':checked')) {
			if (! $('#LinkParametro0Valor').val()) {
				alert('Seleccione una categoria.');
				return false;
			}
			ruta+='?categoria='+$('#LinkParametro0Valor').val();
		}
		$('#previsualizar .contenido h3').html(ruta);
		$('#previsualizar .contenido .iframe').html('<iframe src="'+ruta+'" style="width: 100%; height: 100%;"></iframe>');
		$('#previsualizar').fadeIn(300);
	});

	$('#previsualizar a[rel="cerrar"]').click(function(e) {
		e.preventDefault();
		$(this).parents('#previsualizar').fadeOut(300);
	});
	$('#previsualizar a[rel="continuar"]').click(function(e) {
		e.preventDefault();
		var ruta = 'www.skechers.cl/productos/grupos/';
		if (! $('#LinkParametro').val()) {
			alert('Seleccione un tag.');
			return false;
		}
		ruta+=$('#LinkParametro').val();
		if ($('#LinkFiltrar').is(':checked')) {
			if (! $('#LinkParametro0Valor').val()) {
				alert('Seleccione una categoria.');
				return false;
			}
			ruta+='?categoria='+$('#LinkParametro0Valor').val();
		}
		$('#ruta > .previsualizar > ul > li').html(ruta);
		$('#origen').hide();
		$(this).parents('#previsualizar').fadeOut(300, function() {
			$('#ruta').slideDown(300);
		});
	});

	$('#ruta a[rel="deshacer"]').click(function(e) {
		e.preventDefault();
		$('#ruta').slideUp(300, function() {
			$('#origen').slideDown(300);
		});
	});

	$('#ruta a[rel="guardar"]').click(function(e) {
		e.preventDefault();
		if (! $('#LinkRuta').val()) {
			alert('Debe ingresar el link.');
			return false;
		}
		$(this).parents('form').submit();
	});

	$('#LinkFiltros').change(function() {
		if ($(this).is(':checked')) {
			$('#filtros').slideDown(300);
		} else {
			$('#filtros').slideUp(300, function() {
				$('input[name^="data[Filtro]"]').each(function(index, elemento) {
					$(elemento).prop('checked',false);
				});
			});
		}
	});
});
</script>