<style>
.boton-recargar {
	border-radius: 5px;
	height: 14px;
	padding-top: 5px;
	background-image: url("<?= $this->Html->url('/img/admin/bg-boton.png'); ?>");
	background-position: center center;
	background-repeat: repeat-x;
	display: block;
	float: right;
	font-size: 13px;
	font-weight: normal;
	margin-left: 10px;
	padding: 10px 15px;
	text-decoration: none;
	text-transform: capitalize;
	background-color: #000000;
	color: #FFFFFF;
}
.boton-recargar:hover {
	background-color: #222;
	color: #DDD;
}
.boton-recargar span {
	background-position: left center;
	background-repeat: no-repeat;
	padding-bottom: 5px;
	padding-left: 30px;
	padding-top: 5px;
	background-image: url("<?= $this->Html->url('/img/iconos/reload_16.png'); ?>");
	color: #fff;
}
.boton-recargar.todas span {
	background-image: url("<?= $this->Html->url('/img/iconos/plus_16.png'); ?>");
}
.boton-recargar.ver-sin-foto span {
	background-image: url("<?= $this->Html->url('/img/iconos/search_16.png'); ?>");
}
td.ok {
	background-color: #0080c0;
	color: #fff;
	font-weight: bold;
	font-size: 12px;
}
td.error {
	background-color: #ff8080;
	color: #fff;
	font-weight: bold;
	font-size: 12px;
}
.opacidad {
	display: none;
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
}
.opacidad > .fondo {
	position: absolute;
	width: 100%;
	height: 100%;
	background: none repeat scroll 0 0 rgba(30, 30, 30, 0.8);
}
.opacidad > .cuadro {
	position: absolute;
	width: 400px;
	min-height: 160px;
	background-color: #FFF;
	border: 1px solid #777;
	top: 20%;
	left: 50%;
	margin-left: -200px;
	border-radius: 7px;
}
.opacidad > .cuadro > .texto {
	float:  left;
	width: 100%;
	text-align: center;
	margin-top: 10px;
	font-size: 14px;
	font-weight: bold;
	color: #000;
}
.opacidad > .cuadro > .progress-bar {
	float:  left;
	width: 100%;
	text-align: center;
	margin-top: 30px;
}
.opacidad > .cuadro > .progress-bar > .porcentaje {
	position: absolute;
	width: 100%;
	color: #555;
	text-align: center;
	font-size: 10px;
}
.opacidad > .cuadro > .progress-bar > .barra {
	display: inline-block;
	width: 208px;
	height: 13px;
	background-image: url("<?= $this->Html->url('/img/loader.gif'); ?>");
	background-position: -131px 156px;
	margin-top: 2px;
}
.opacidad > .cuadro > .progress-bar > .barra > .progress {
	float: left;
	width: 0;
	background: none repeat scroll 0 0 rgba(0, 130, 0, 0.8);
	height: 13px;
	border-radius: 3px;
}
.opacidad > .cuadro > .detalle {
	float:  left;
	width: 360px;
	text-align: left;
	margin-top: 30px;
	font-size: 12px;
	font-weight: normal;
	color: #555;
	overflow-y: auto;
	padding: 0 20px 20px 20px;
	max-height: 160px;
}
.opacidad .cerrar {
	position: absolute;
	right: 10px;
	text-decoration: none;
	color: #999;
}
</style>
<div class="col02">
	<h1 class="titulo">
		<? __('Recargar fotos de productos');?>
	</h1>
	<div style="float: left; width: 100%; margin-bottom: 15px;">
		<a href="#" id="cargarFotos" class="boton-recargar"><span>Cargar</span></a>
		<a href="#" id="seleccionarTodas" class="boton-recargar todas"><span>Seleccionar todas</span></a>
		<?= $this->Form->input('Producto.coleccion_id',array('type' => 'select',
															 'options' => $colecciones,
															 'class' => 'clase-input',
															 'label' => false,
															 'div' => false,
															 'empty' => '-seleccione coleccion',
															 'style' => 'width: 190px;')); ?>
	</div>
	<?= $this->Form->create('Producto'); ?>
	<table cellpadding="0" cellspacing="0" class="tabla" style="background-color: #FFF;">
		<tr>
			<th>check</th>
			<th>ID</th>
			<th>Codigo</th>
			<th>Nombre</th>
			<th>Categoria</th>
			<th>Col</th>
			<th>Estado</th>
			<th>Acciones</th>
		</tr>
		<? foreach ( $productos as $producto ) : ?>
		<tr class="<?= (isset($producto['Producto']['foto']) && $producto['Producto']['foto']) ? 'ok' : ''; ?>">
			<td><?= $this->Form->input('Producto.'.$producto['Producto']['id'].'.producto_id', array('type' => 'checkbox',
																									 'div'	=> false,
																									 'label' => false,
																									 'data-id' => $producto['Producto']['id'],
																									 'data-codigo' => $producto['Producto']['codigo_imagen'])); ?></td>
			<td><?= $producto['Producto']['id']; ?></td>
			<td><?= $producto['Producto']['codigo_imagen']; ?></td>
			<td><?= $producto['Producto']['nombre']; ?>&nbsp;</td>
			<td><?= $producto['Categoria']['nombre']; ?>&nbsp;</td>
			<td><?= $producto['Producto']['coleccion_id']; ?>&nbsp;</td>
			<?
				if ($producto['Producto']['foto'])
					echo '<td class="ok">OK</td>';
				else
					echo '<td class="error">SIN FOTO</td>';
			?>
			<td>
				<a href="<?= $this->Html->url(array('controller' => 'productos', 'action' => 'view', $producto['Producto']['id'])); ?>" target="_blank">
					<img src="<?= $this->Html->url('/img/iconos/clipboard_16.png'); ?>" alt="ver" />
				</a>
				<a href="<?= $this->Html->url(array('controller' => 'productos', 'action' => 'edit', $producto['Producto']['id'])); ?>" target="_blank">
					<img src="<?= $this->Html->url('/img/iconos/pencil_16.png'); ?>" alt="editar" />
				</a>
				<a href="<?= $this->Html->url('/productos/view/'.$producto['Producto']['slug']); ?>" target="_blank">
					<img src="<?= $this->Html->url('/img/iconos/search_16.png'); ?>" alt="catalogo" />
				</a>
			</td>
		</tr>
		<? endforeach; ?>
	</table>
	<div style="float: left; width: 100%;">
		<a href="#" id="cargarFotos" class="boton-recargar"><span>Cargar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
<div class="opacidad">
	<div class="fondo"></div>
	<div class="cuadro">
		<div class="texto">
			Cargando...
		</div>
		<div class="progress-bar">
			<div class="porcentaje">0%</div>
			<div class="barra"><div class="progress"></div></div>
		</div>
	</div>
</div>
<script>
$('#seleccionarTodas').click(function(e) {
	e.preventDefault();
	$('input[name^="data[Producto]"]:checkbox:visible').prop("checked", "checked");
});

$('#cargarFotos').live('click',function(e) {
	e.preventDefault();
	var formulario = $('#ProductoAdminCargarFotosForm'),
		target = $('.opacidad');
	//target.find('.cuadro').html('<div class="texto">Cargando...</div><div class="progress-bar"><div class="porcentaje">0%</div><div class="barra"><div class="progress"></div></div></div>');
	target.find('.cuadro').html('<a href="<?= $this->Html->url('/img/Archivo/log.txt'); ?>" target="_blank">ver log</a><div class="texto">Cargando...</div><div class="progress-bar"><div class="porcentaje">0%</div><div class="barra"><div class="progress"></div></div></div>');
	target.fadeIn(700);
	//recargar('si');
	$.ajax({
		type : 'POST',
		async : true,
		url : webroot + 'archivos/ajax_cargar_fotos',
		data : formulario.serialize(),
		success : function(respuesta) {
			target.find('.cuadro').html('<a href="<?= $this->Html->url('/img/Archivo/log.txt'); ?>" target="_blank">ver log</a><a href="#" class="cerrar">cerrar</a>');
		}
	});
});
$('.opacidad .cerrar').live('click', function(e) {
	e.preventDefault();
	$('.opacidad').fadeOut(800);
});
$('#ProductoColeccionId').change(function() {
	var id = $(this).val();
	if (id) {
		location.href = '<?= $this->Html->url(array('action' => 'cargar_fotos')); ?>?coleccion='+id;
	}
	return false;
});
</script>
