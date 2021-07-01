<style type="text/css" media="all">
/* <![CDATA[ */
.menu-editar-producto {
	position: absolute;
	right: 0;
	top: 0;
	width: 300px;
	height: 50px;
	margin-top: 30px;
	margin-right: 40px;
}
.menu-editar-producto .botoncito {
	float: right;
	background-color: #0080c0;
	width: 110px;
	height: 23px;
	padding-top: 2px;
	border: 1px solid #000;
	border-radius: 5px;
	text-align: center;
	text-decoration: none !important;
	margin-left: 10px;
	color: #fff;
	font-weight: bold;
}
.menu-editar-producto .botoncito.current {
	background-color: #20b7ff;
	text-decoration: underline !important;
}
.menu-editar-producto .botoncito:hover {
	background-color: #20b7ff;
	
}
/* ]]> */
</style>
<div class="menu-editar-producto">
	<a href="#" class="botoncito edit-galeria">Editar Galeria</a>
	<a href="#" class="botoncito edit-datos current">Editar Datos</a>
</div>
<div class="col02 editar-datos" style="display: block;">
	<?= $this->Form->create('Producto', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Editar Producto'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('id'); ?><?= $this->data['Producto']['codigo_completo']; ?></li>
		<li><?= $this->Form->input('nombre'); ?></li>
		<li><?= $this->Form->input('activo'); ?></li>
		<li><?= $this->Form->input('categoria_id', array('empty' => '- categoria')); ?></li>
		<li><?= $this->Form->input('coleccion_id', array('empty' => '- colección')); ?></li>
		<li>
			<span class="texto">Foto</span>
			<?
				if ( isset($this->data['Producto']['foto']['mini']) && $this->data['Producto']['foto']['mini'] )
				{
					$imagen = $this->Html->url('/img/'.$this->data['Producto']['foto']['mini']);
					$detalle_imagen = "<div style='width: 250px; border: 1px solid #999;'><img src='".$imagen."' width='100%' /></div>";
					echo ('<a href="#" class="descripcion-tool" title="'.$detalle_imagen.'">'.basename($this->data['Producto']['foto']['path']).'&nbsp;</a>');
				}
			?>
			<?= $this->Form->input('foto', array('type' => 'file', 'label' => false)); ?>
		</li>
		
		<li><?= $this->Form->input('color_id'); ?></li>
		<li><?= $this->Form->input('codigo'); ?></li>
		<li><?= $this->Form->input('precio'); ?></li>
		<li><?= $this->Form->input('oferta', array('type' => 'checkbox')); ?></li>
		<li><?= $this->Form->input('precio_oferta'); ?></li>
		<li><?= $this->Form->input('excluir_descuento'); ?></li>
		<li><?= $this->Form->input('descripcion'); ?></li>
		<li><?= $this->Form->input('ficha'); ?></li>
		<li><?= $this->Form->input('new'); ?></li>
		<li><?= $this->Form->input('escolar'); ?></li>
		<li><?= $this->Form->input('outlet'); ?></li>
		<li><?= $this->Form->input('tipo'); ?></li>
		<li><?= $this->Form->input('division'); ?></li>
		<li><?= $this->Form->input('showroom'); ?></li>
		<li><?= $this->Form->input('grupo'); ?></li>
		<li><?= $this->Form->input('stock_seguridad'); ?></li>
	</ul>
	<!-- GALERIA -->
	<h2 class="subtitulo"><? __('Agregar foto a la galeria'); ?></h2>
	<ul id="galeria" class="edit">
		<li>
			<label class="texto" for="Galeria0Imagen">Imagenes Galeria</label>
			<input type="file" id="Galeria0Imagen" class="clase-input" name="data[Galeria][][imagen]" multiple>
		</li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar datos</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
<div class="col02 editar-galeria" style="display: none;">
	<? if (isset($this->data['Galeria']) && $this->data['Galeria']) : ?>
	<h1 class="titulo">Galeria de fotos</h1>
	<div class="lista-galeria" style="display: block;">
		<?= $this->Form->create('Galeria', array('action' => 'ordenar', 'type' => 'file')); ?>
		<div class="posiciones" style="position: absolute; width: 25px; display: none; background-image: url(<?= $this->Html->url('/img/opacidad.png'); ?>)">
			<? for ($x = 1; $x <= count($this->data['Galeria']); $x++) : ?>
			<div class="posicion-<?= $x; ?>" style="float: left; width: 20px; height: 84px; text-align: right; font-size: 12px; padding: 20px 5px 0 0;">
				<?= $x; ?>
			</div>
			<? endfor; ?>
		</div>
		<ul class="edit ordenar-galeria">
			<? foreach ( $this->data['Galeria'] as $index => $foto ) : ?>
				<li class="linea-galeria">
					<table>
						<tr>
							<td style="width: 100px; max-width: 100px;">
								<?= $this->Html->image("/img/Galeria/{$foto['id']}/mini_{$foto['imagen']}"); ?>
							</td>
							<td style="width: 400px; max-width: 400px;">
								<span class="texto" style="float: left; width: 400px;">Actualizar Imagen</span>
								<span class="texto" style="float: left; width: 400px; font-size: 10px;"><?= $foto['imagen']; ?></span>
								<?= $this->Form->input('Galeria.'.$foto['id'].'.imagen',
													   array('type' => 'file',
															 'div' => false,
															 'label' => false,
															 'style' => 'float: left;'
															 )); ?>
								<?= $this->Form->input('Galeria.'.$foto['id'].'.orden', array('style' => 'display: none;',
																							  'class' => 'orden',
																							  'div' => false,
																							  'label' => false)); ?>
							</td>
							<td style="width: 120px; max-width: 120px;">
								<div class="botones">
									<a href="#" class="eliminar" data-id="<?= $foto['id']; ?>"><span class="borrar">Eliminar</span></a>
								</div>
							</td>
						</tr>
					</table>
				</li>
			<? endforeach; ?>
		</ul>
		<div class="botones">
			<a href="#" class="guardar-galeria"><span class="guardar">Guardar Galeria</span></a>
		</div>
		<?= $this->Form->end(); ?>
	</div>
	<? endif ; ?>
</div>
<script type="application/x-javascript">
$(document).ready(function()
{
	$( ".ordenar-galeria" ).sortable({
		revert: true
	});
	//$( "ul, li" ).disableSelection();

	$('.menu-editar-producto .edit-galeria').click(function(evento) {
		evento.preventDefault();
		var boton = $(this);
		$('.menu-editar-producto .botoncito').removeClass('current');
		boton.addClass('current');
		$('.col02.editar-datos').hide();
		$('.col02.editar-galeria').slideDown(1000);
		$('.col02.editar-galeria .posiciones').slideDown(1000);
	});

	$('.menu-editar-producto .edit-datos').click(function(evento) {
		evento.preventDefault();
		var boton = $(this);
		$('.menu-editar-producto .botoncito').removeClass('current');
		boton.addClass('current');
		$('.col02.editar-galeria').hide();
		$('.col02.editar-galeria .posiciones').hide();
		$('.col02.editar-datos').slideDown(1500);
	});

	$('.guardar-galeria').live('click', function(evento) {
		evento.preventDefault();
		var posicion = 1,
			imagenes = $('#GaleriaOrdenarForm input.orden').length;
		$('.ordenar-galeria li').each(function(index, elemento) {
			$(elemento).find('input.orden').val(posicion);
			posicion++;
		});

		if ( imagenes < 5 ) {
			if ( confirm('La galeria tiene menos imagenes que las recomendadas.\nLa cantidad recomendada es: 5 imagenes para la galeria.\n¿Desea continuar de todas formas?') ) {
				$('#GaleriaOrdenarForm').submit();
			}
		}
		else if ( imagenes > 5 ) {
			if ( confirm('La galeria tiene mas imagenes que las recomendadas.\nLa cantidad recomendada es: 5 imagenes para la galeria.\n¿Desea continuar de todas formas?') ) {
				$('#GaleriaOrdenarForm').submit();
			}
		}
		else {
			$('#GaleriaOrdenarForm').submit();
		}
	});

	$('.ordenar-galeria .eliminar').live('click', function(evento) {
		evento.preventDefault();
		var boton = $(this),
			contenedor = $(this).parents('li.linea-galeria'),
			ultimo = $('.posiciones div').length,
			id = $(this).data('id');
		if ( confirm('Esta a punto de eliminar esta imagen de la Galeria.\n¿Esta seguro?') ) {
			$.ajax(
			{
				type: "POST",
				async: false,
				url: webroot + "admin/galerias/delete/" + id,
				success: function(respuesta)
				{
					if (respuesta == 'READY') {
						contenedor.fadeOut(500,function() {
							$('.posiciones div.posicion-'+ultimo).remove();
							contenedor.remove();
						});
					}
					else {
						alert(respuesta);
					}
				}
			});
		}
	});
});
</script>
